<?php

/**
 * This file is part of The Official Amazon Payments Magento Extension
 * (c) creativestyle GmbH <amazon@creativestyle.de>
 * All rights reserved
 *
 * Reuse or modification of this source code is not allowed
 * without written permission from creativestyle GmbH
 *
 * @category   Creativestyle
 * @package    Creativestyle_CheckoutByAmazon
 * @copyright  Copyright (c) 2011 - 2014 creativestyle GmbH (http://www.creativestyle.de)
 * @author     Marek Zabrowarny / creativestyle GmbH <amazon@creativestyle.de>
 */
class Creativestyle_CheckoutByAmazon_Model_Manager extends Creativestyle_CheckoutByAmazon_Model_Abstract {

    protected
        $_ordersApi = array(),
        $_reportsApi = array(),
        $_feedsApi = array(),
        $_queueDir = array();

    protected function _generateMd5Hash($content) {
        return base64_encode(md5($content, true));
    }

    protected function _getApi($type = 'reports') {
        $storeId = Mage::app()->getStore()->getId();
        switch ($type) {
            case 'orders':
                if (!isset($this->_ordersApi[$storeId])) {
                    $this->_ordersApi[$storeId] = Mage::getModel('checkoutbyamazon/api_marketplace_orders');
                }
                return $this->_ordersApi[$storeId];

            case 'reports':
                if (!isset($this->_reportsApi[$storeId])) {
                    $this->_reportsApi[$storeId] = Mage::getModel('checkoutbyamazon/api_marketplace_reports');
                }
                return $this->_reportsApi[$storeId];

            case 'feeds':
                if (!isset($this->_feedsApi[$storeId])) {
                    $this->_feedsApi[$storeId] = Mage::getModel('checkoutbyamazon/api_marketplace_feeds');
                }
                return $this->_feedsApi[$storeId];

        }
    }

    protected function _getOrderByAmazonId($amazonId) {
        $paymentCollection = Mage::getModel('sales/order_payment')->getCollection()
            ->addAttributeToFilter('last_trans_id', $amazonId)
            ->load();
        if ($paymentCollection->count()) {
            $payment = $paymentCollection->getFirstItem();
            if ($payment->getParentId()) {
                $order = Mage::getModel('sales/order')->load($payment->getParentId());
                return $order;
            }
        }
        return null;
    }


    /* Feeds and acknowledgements handling */

    protected function _submitFeed($feedType, $filePath) {
        $fileHandle = @fopen($filePath, 'r');
        $feedContent = stream_get_contents($fileHandle);
        fclose($fileHandle);
        $md5 = $this->_generateMd5Hash($feedContent);
        $result = $this->_getApi('feeds')->submitFeed($feedType, $feedContent, $md5);
        unlink($filePath);
        return $result;
    }

    protected function _submitBatchFeeds($feedType, $directory, $merchantId = null) {

        $messageId = 0;
        $filesToDelete = array();
        $ordersProcessed = array();

        if (null === $merchantId) $merchantId = self::getConfigData('merchant_id');

        $allowedMessageType = '';
        switch ($feedType) {
            case Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Feeds_Abstract::FEED_TYPE_POST_ORDER_ACKNOWLEDGEMENT_DATA:
                $allowedMessageType = 'OrderAcknowledgement';
                break;
            case Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Feeds_Abstract::FEED_TYPE_POST_ORDER_FULFILLMENT_DATA:
                $allowedMessageType = 'OrderFulfillment';
                break;
            case Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Feeds_Abstract::FEED_TYPE_POST_PAYMENT_ADJUSTMENT_DATA:
                $allowedMessageType = 'OrderAdjustment';
                break;
        }

        $envelopeDom = new DOMDocument('1.0', 'utf-8');
        //$envelopeDom->formatOutput = true;

        $_envelope = $envelopeDom->createElement('AmazonEnvelope');

        $_header = $envelopeDom->createElement('Header');
        $_documentVersion = $envelopeDom->createElement('DocumentVersion', '1.01');
        $_merchantIdentifier = $envelopeDom->createElement('MerchantIdentifier', $merchantId);
        $_header->appendChild($_documentVersion);
        $_header->appendChild($_merchantIdentifier);
        $_envelope->appendChild($_header);

        $_messageType = $envelopeDom->createElement('MessageType', $allowedMessageType);
        $_envelope->appendChild($_messageType);

        $dom = new DOMDocument();
        $handle = opendir($directory);
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                if ($dom->load($directory . DS . $file)) {
                    $xpath = new DOMXPath($dom);
                    $merchantResponse = $xpath->query('/AmazonEnvelope/Header/MerchantIdentifier');
                    if ($merchantResponse->length == 1) {
                        $feedMerchantId = trim($merchantResponse->item(0)->nodeValue);
                        if ($feedMerchantId == $merchantId) {
                            $messageTypeResponse = $xpath->query('/AmazonEnvelope/MessageType');
                            if ($messageTypeResponse->length == 1) {
                                $messageType = trim(strtolower($messageTypeResponse->item(0)->nodeValue));
                                if ($messageType == strtolower($allowedMessageType)) {
                                    $messagesResponse = $xpath->query('/AmazonEnvelope/Message');
                                    if ($messagesResponse->length >= 1) {
                                        foreach ($messagesResponse as $message) {
                                            $messageIdResponse = $xpath->query('MessageID', $message);
                                            if ($messageIdResponse->length == 1) {
                                                $messageIdResponse->item(0)->nodeValue = ++$messageId;
                                                $_envelope->appendChild($envelopeDom->importNode($message, true));
                                                $filesToDelete[] = $directory . DS . $file;
                                                $orderIdResponse = $xpath->query($allowedMessageType . '/MerchantOrderID', $message);
                                                if ($orderIdResponse->length == 1) {
                                                    $ordersProcessed[] = trim($orderIdResponse->item(0)->nodeValue);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        unset($dom);

        $envelopeDom->appendChild($_envelope);

        if ($messageId > 0) {
            $feedContent = $envelopeDom->saveXML();
            $md5 = $this->_generateMd5Hash($feedContent);

            // submit feed to Amazon MWS server
            $result = $this->_getApi('feeds')->submitFeed($feedType, $feedContent, $md5);

            // log feed
            Creativestyle_CheckoutByAmazon_Model_Logger::logFeed(
                $feedType,
                $result,
                trim($envelopeDom->saveXML()),
                Mage::app()->getStore()->getId()
            );

            // remove parsed XML files
            foreach ($filesToDelete as $filePath) {
                unlink($filePath);
            }

        } else {
            $result = false;
        }
        return $result;
    }

    protected function _updateReportAcknowledgements(array $reportIds) {
        return $this->_getApi('reports')->updateReportAcknowledgements($reportIds);
    }


    /* Amazon MWS call results processing */

    protected function _processAmazonReportsApiCallResult($report) {
        $doc = simplexml_load_string($report);
        if (isset($doc->Message)) {
            $processedOrders = array();
            foreach ($doc->Message as $message) {
                if (isset($message->OrderReport->AmazonOrderID)) {
                    $order = $this->_getOrderByAmazonId((string)$message->OrderReport->AmazonOrderID);
                    if (!empty($order) && $order->getId()) {
                        $transactionSave = Mage::getModel('core/resource_transaction');
                        $order->setIsInProcess(true);
                        if (isset($message->OrderReport->BillingData->BuyerName)) {
                            $buyerName = explode(' ', (string)$message->OrderReport->BillingData->BuyerName);
                            if (count($buyerName) > 1) {
                                $buyerFirstname = reset($buyerName);
                                $buyerLastname = trim(str_replace($buyerFirstname, "", (string)$message->OrderReport->BillingData->BuyerName));
                            } else {
                                $buyerFirstname = Mage::helper('checkoutbyamazon')->__('n/a');
                                $buyerLastname = reset($buyerName);
                            }
                            $order->getBillingAddress()->setFirstname($buyerFirstname);
                            $order->getBillingAddress()->setLastname($buyerLastname);
                        }
                        if (isset($message->OrderReport->BillingData->BuyerPhoneNumber)) {
                            $order->getBillingAddress()->setTelephone((string)$message->OrderReport->BillingData->BuyerPhoneNumber);
                        }
                        if (isset($message->OrderReport->BillingData->BuyerEmailAddress)) {
                            $order->setCustomerEmail((string)$message->OrderReport->BillingData->BuyerEmailAddress);
                            $order->getBillingAddress()->setEmail((string)$message->OrderReport->BillingData->BuyerEmailAddress);
                            $order->getShippingAddress()->setEmail((string)$message->OrderReport->BillingData->BuyerEmailAddress);
                        }
                        if (isset($message->OrderReport->BillingData->Address)) {
                            $street = array();
                            if (isset($message->OrderReport->BillingData->Address->AddressFieldOne)) {
                                $street[] = (string)$message->OrderReport->BillingData->Address->AddressFieldOne;
                            }
                            if (isset($message->OrderReport->BillingData->Address->AddressFieldTwo)) {
                                $street[] = (string)$message->OrderReport->BillingData->Address->AddressFieldTwo;
                            }
                            if (isset($message->OrderReport->BillingData->Address->AddressFieldThree)) {
                                $street[] = (string)$message->OrderReport->BillingData->Address->AddressFieldThree;
                            }
                            $order->getBillingAddress()->setStreet($street);
                            if (isset($message->OrderReport->BillingData->Address->City)) {
                                $order->getBillingAddress()->setCity((string)$message->OrderReport->BillingData->Address->City);
                            }
                            if (isset($message->OrderReport->BillingData->Address->CountryCode)) {
                                if (isset($message->OrderReport->BillingData->Address->StateOrRegion)) {
                                    $order->getBillingAddress()->setRegionId(Mage::helper('checkoutbyamazon')->getRegionId((string)$message->OrderReport->BillingData->Address->StateOrRegion, (string)$message->OrderReport->BillingData->Address->CountryCode));
                                    $order->getBillingAddress()->setRegion((string)$message->OrderReport->BillingData->Address->StateOrRegion);
                                }
                                $order->getBillingAddress()->setCountryId((string)$message->OrderReport->BillingData->Address->CountryCode);
                            }
                            if (isset($message->OrderReport->BillingData->Address->PostalCode)) {
                                $order->getBillingAddress()->setPostcode((string)$message->OrderReport->BillingData->Address->PostalCode);
                            }
                            if (isset($message->OrderReport->BillingData->Address->PhoneNumber)) {
                                $order->getBillingAddress()->setTelephone((string)$message->OrderReport->BillingData->Address->PhoneNumber);
                            }
                        } else if (isset($message->OrderReport->FulfillmentData->Address)) {
                            $street = array();
                            if (isset($message->OrderReport->FulfillmentData->Address->AddressFieldOne)) {
                                $street[] = (string)$message->OrderReport->FulfillmentData->Address->AddressFieldOne;
                            }
                            if (isset($message->OrderReport->FulfillmentData->Address->AddressFieldTwo)) {
                                $street[] = (string)$message->OrderReport->FulfillmentData->Address->AddressFieldTwo;
                            }
                            if (isset($message->OrderReport->FulfillmentData->Address->AddressFieldThree)) {
                                $street[] = (string)$message->OrderReport->FulfillmentData->Address->AddressFieldThree;
                            }
                            $order->getBillingAddress()->setStreet($street);
                        }
                        if (isset($message->OrderReport->FulfillmentData->Address)) {
                            $street = array();
                            if (isset($message->OrderReport->FulfillmentData->Address->AddressFieldOne)) {
                                $street[] = (string)$message->OrderReport->FulfillmentData->Address->AddressFieldOne;
                            }
                            if (isset($message->OrderReport->FulfillmentData->Address->AddressFieldTwo)) {
                                $street[] = (string)$message->OrderReport->FulfillmentData->Address->AddressFieldTwo;
                            }
                            if (isset($message->OrderReport->FulfillmentData->Address->AddressFieldThree)) {
                                $street[] = (string)$message->OrderReport->FulfillmentData->Address->AddressFieldThree;
                            }
                            $order->getShippingAddress()->setStreet($street);
                        }
                        $itemIdMapping = null;
                        if (isset($message->OrderReport->Item)) {
                            $itemIdMapping = array();
                            foreach ($message->OrderReport->Item as $item) {
                                if (isset($item->SKU) && isset($item->AmazonOrderItemCode)) {
                                    $itemIdMapping[(string)$item->SKU] = (string)$item->AmazonOrderItemCode;
                                }
                            }
                        }
                        if ($order->getState() == Mage_Sales_Model_Order::STATE_PENDING_PAYMENT) {
                            if ($order->getStatus() == 'pending_amazon') {
                                $order->addStatusToHistory(Mage_Sales_Model_Order::STATE_PENDING_PAYMENT, Mage::helper('checkoutbyamazon')->__('<strong>%s</strong>: Order details have been updated', 'Amazon MWS'), false);
                            }
                            $status = self::getConfigData('new_order_status');
                            $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, $status, Mage::helper('checkoutbyamazon')->__('<strong>%s</strong>: Order amount has been authorized by Amazon', 'Amazon MWS'), false);
                            $order->getPayment()->getMethodInstance()->setStore($order->getStoreId())->authorize($order->getPayment(), $order->getBaseTotalDue());
                            $invoices = $order->getInvoiceCollection();
                            if ($invoices->count()) {
                                foreach ($invoices as $invoice) {
                                    $transactionSave->addObject($invoice);
                                }
                            }
                            $this->sendAcknowledgementNotify($order, $itemIdMapping);
                        }
                        $transactionSave->addObject($order)->save();
                        if (!$order->getEmailSent() && self::getConfigData('order_confirmation')) $order->sendNewOrderEmail();
                        $processedOrders[] = $order->getId();
                    }
                }
            }
            return $processedOrders;
        }
        return false;
    }

    protected function _processAmazonOrdersApiCallResult($orders) {
        if (is_array($orders) && !empty($orders)) {
            foreach ($orders as $order) {
                if ($order->issetOrderStatus() && (strtolower($order->getOrderStatus()) == 'canceled')) {
                    $order->setAmazonOrderID($order->getAmazonOrderId());
                    $this->cancelOrder($order, 'Amazon MWS');
                }
            }
        }
    }


    /* Public interface */

    public function __construct() {
        $this->_queueDir['cancel'] = Mage::getBaseDir('var') . DS . 'amazon' . DS . 'feed_queue' . DS . 'cancel';
        $this->_queueDir['acknowledge'] = Mage::getBaseDir('var') . DS . 'amazon' . DS . 'feed_queue' . DS . 'acknowledge';
        $this->_queueDir['shipment'] = Mage::getBaseDir('var') . DS . 'amazon' . DS . 'feed_queue' . DS . 'shipment';
        $this->_queueDir['refund'] = Mage::getBaseDir('var') . DS . 'amazon' . DS . 'feed_queue' . DS . 'refund';
        foreach ($this->_queueDir as $dir) {
            if (!(file_exists($dir) && is_dir($dir))) {
                mkdir($dir, 0777, true);
            }
        }
    }

    public function manageReportSchedule($schedule) {
        return $this->_getApi('reports')->manageReportSchedule($schedule);
    }

    public function retrieveAndHandleReportList($token = null) {
        $acknowledgements = array();
        if ($token) {
            $reportList = $this->_getApi('reports')->getReportListByNextToken($token);
        } else {
            $reportList = $this->_getApi('reports')->getReportList();
        }
        if ($reportList->issetReportInfo()) {
            $reports = $reportList->getReportInfo();
            foreach ($reports as $reportListItem) {
                if ($reportListItem->issetReportType() && $reportListItem->issetReportId() &&
                    ($reportListItem->getReportType() == Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Abstract::REPORT_TYPE_ORDERS_DATA)) {
                    $reportId = $reportListItem->getReportId();
                    $report = $this->_getApi('reports')->getReport($reportId);
                    if ($processedOrders = $this->_processAmazonReportsApiCallResult($report)) {
                        $acknowledgements[] = $reportId;
                        Creativestyle_CheckoutByAmazon_Model_Logger::logReport(
                            $reportListItem->getReportType(),
                            $reportListItem->getReportId(),
                            ($reportListItem->issetReportRequestId() ? $reportListItem->getReportRequestId() : null),
                            $report
                        );
                    }
                }
            }
        }

        if (!empty($acknowledgements)) {
            $this->_updateReportAcknowledgements($acknowledgements);
        }

        if (self::getConfigData('api_use_token')) {
            if ($reportList->issetHasNext() && $reportList->getHasNext()) {
                if ($reportList->issetNextToken() && $reportList->getNextToken()) {
                    return $reportList->getNextToken();
                }
            }
        }

        return null;
    }

    public function updateCanceledOrders() {
        $storeId = Mage::app()->getStore()->getId();
        if (version_compare(Mage::helper('checkoutbyamazon')->getMagentoVersion(), '1.4.1') >= 0) {
            $collection = Mage::getResourceModel('sales/order_collection')
                ->join('order_payment', 'main_table.entity_id = order_payment.parent_id')
                ->addAttributeToFilter('main_table.state', array('nin' => array(
                    Mage_Sales_Model_Order::STATE_COMPLETE,
                    Mage_Sales_Model_Order::STATE_CLOSED,
                    Mage_Sales_Model_Order::STATE_CANCELED
                )))
                ->addAttributeToFilter('main_table.store_id', $storeId)
                ->addAttributeToFilter('order_payment.method', array('in' => $this->_getAmazonPaymentMethods()))
                ->addAttributeToFilter('order_payment.last_trans_id', array('notnull' => 1))
                ->load();
        } else {
            $collection = Mage::getResourceModel('sales/order_payment_collection')
                ->addAttributeToSelect('last_trans_id')
                ->joinAttribute('order_state', 'order/state', 'parent_id', null, 'right')
                ->joinAttribute('order_store_id', 'order/store_id', 'parent_id', null, 'right')
                ->addAttributeToFilter('order_state', array('nin' => array(
                    Mage_Sales_Model_Order::STATE_COMPLETE,
                    Mage_Sales_Model_Order::STATE_CLOSED,
                    Mage_Sales_Model_Order::STATE_CANCELED
                )))
                ->addAttributeToFilter('order_store_id', $storeId)
                ->addAttributeToFilter('method', array('in' => $this->_getAmazonPaymentMethods()))
                ->addAttributeToFilter('last_trans_id', array('notnull' => true))
                ->load();
        }

        if ($collection->count()) {
            $i = 0;
            $orderIdList = array();

            foreach ($collection as $payment) {
                $orderIdList[] = $payment->getLastTransId();
                $i++;
                if ($i >= 50) {
                    $this->_processAmazonOrdersApiCallResult($this->_getApi('orders')->getOrder($orderIdList));
                    $i = 0; $orderIdList = array();
                }
            }

            if ($i > 0) {
                $this->_processAmazonOrdersApiCallResult($this->_getApi('orders')->getOrder($orderIdList));
            }

        }

    }

    public function newOrder($amazonOrder) {
        if ($amazonOrder->issetAmazonOrderID()) {
            $order = $this->_getOrderByAmazonId($amazonOrder->getAmazonOrderID());
            if (!empty($order) && $order->getId()) {
                if ($amazonOrder->issetBuyerInfo()) {
                    $buyerInfo = $amazonOrder->getBuyerInfo();
                    if ($buyerInfo->issetBuyerName()) {
                        $buyerName = explode(' ', trim($buyerInfo->getBuyerName()));
                        if (count($buyerName) > 1) {
                            $buyerFirstname = reset($buyerName);
                            $buyerLastname = trim(str_replace($buyerFirstname, "", $buyerInfo->getBuyerName()));
                        } else {
                            $buyerFirstname = Mage::helper('checkoutbyamazon')->__('n/a');
                            $buyerLastname = reset($buyerName);
                        }
                        $order->getBillingAddress()->setFirstname($buyerFirstname);
                        $order->getBillingAddress()->setLastname($buyerLastname);
                    }
                    if ($buyerInfo->issetBuyerEmailAddress()) {
                        $buyerEmailAddress = $buyerInfo->getBuyerEmailAddress();
                        $order->setCustomerEmail($buyerEmailAddress);
                    }
                }
                if ($amazonOrder->issetShippingAddress()) {
                    $street = array();
                    $shippingAddress = $amazonOrder->getShippingAddress();
                    if ($shippingAddress->issetAddressFieldOne()) {
                        $street[] = $shippingAddress->getAddressFieldOne();
                    }
                    if ($shippingAddress->issetAddressFieldTwo()) {
                        $street[] = $shippingAddress->getAddressFieldTwo();
                    }
                    if ($shippingAddress->issetAddressFieldThree()) {
                        $street[] = $shippingAddress->getAddressFieldThree();
                    }
                    $order->getBillingAddress()->setStreet($street);
                    $order->getShippingAddress()->setStreet($street);
                }
                if ($order->getStatus() == 'pending_amazon') {
                    $state = Mage_Sales_Model_Order::STATE_PENDING_PAYMENT;
                    $order->setState($state, $state, Mage::helper('checkoutbyamazon')->__('<strong>%s</strong>: Order details have been updated', 'IOPN'), false);
                }
                $order->save();
                if (!$order->getEmailSent() && self::getConfigData('order_confirmation')) $order->sendNewOrderEmail();
            }
        } else {
            Mage::helper('checkoutbyamazon')->throwException('Amazon Order ID not provided');
        }
    }

    public function cancelOrder($amazonOrder, $sender = 'IOPN') {
        if ($amazonOrder->issetAmazonOrderID() || $amazonOrder->issetAmazonOrderId()) {
            $amazonOrderId = $amazonOrder->issetAmazonOrderID() ? $amazonOrder->getAmazonOrderID() : $amazonOrder->getAmazonOrderId();
            $order = $this->_getOrderByAmazonId($amazonOrderId);
            if (!empty($order) && $order->getId()) {
                $invoices = $order->getInvoiceCollection();
                $order->getPayment()->setMessage(Mage::helper('checkoutbyamazon')->__('<strong>%s</strong>: Authorization has been voided by Amazon', $sender));
                if ($invoices->count()) {
                    foreach ($invoices as $invoice) {
                        $invoice->void();
                    }
                }
                $order->cancel()->save();
            }
        } else {
            Mage::helper('checkoutbyamazon')->throwException('Amazon Order ID not provided');
        }
    }

    public function readyToShipOrder($amazonOrder) {
        if ($amazonOrder->issetAmazonOrderID()) {
            $order = $this->_getOrderByAmazonId($amazonOrder->getAmazonOrderID());
            if (!empty($order) && $order->getId()) {
                if ($order->getState() == Mage_Sales_Model_Order::STATE_PENDING_PAYMENT) {
                    $transactionSave = Mage::getModel('core/resource_transaction');
                    if ($amazonOrder->issetBillingAddress()) {
                        $street = array();
                        if ($amazonOrder->getBillingAddress()->issetAddressFieldOne()) {
                            $street[] = $amazonOrder->getBillingAddress()->getAddressFieldOne();
                        }
                        if ($amazonOrder->getBillingAddress()->issetAddressFieldTwo()) {
                            $street[] = $amazonOrder->getBillingAddress()->getAddressFieldTwo();
                        }
                        if ($amazonOrder->getBillingAddress()->issetAddressFieldThree()) {
                            $street[] = $amazonOrder->getBillingAddress()->getAddressFieldThree();
                        }
                        if (!empty($street)) $order->getBillingAddress()->setStreet($street);
                        if ($amazonOrder->getBillingAddress()->issetCity()) {
                            $order->getBillingAddress()->setCity($amazonOrder->getBillingAddress()->getCity());
                        }
                        if ($amazonOrder->getBillingAddress()->issetCountryCode()) {
                            if ($amazonOrder->getBillingAddress()->issetState()) {
                                $order->getBillingAddress()->setRegionId(Mage::helper('checkoutbyamazon')->getRegionId($amazonOrder->getBillingAddress()->getState(), $amazonOrder->getBillingAddress()->getCountryCode()));
                                $order->getBillingAddress()->setRegion($amazonOrder->getBillingAddress()->getState());
                            }
                            $order->getBillingAddress()->setCountryId($amazonOrder->getBillingAddress()->getCountryCode());
                        }
                        if ($amazonOrder->getBillingAddress()->issetPostalCode()) {
                            $order->getBillingAddress()->setPostcode($amazonOrder->getBillingAddress()->getPostalCode());
                        }
                    }
                    $order->getPayment()->getMethodInstance()->setStore($order->getStoreId())->authorize($order->getPayment(), $order->getBaseTotalDue());
                    $status = self::getConfigData('new_order_status');
                    $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, $status, Mage::helper('checkoutbyamazon')->__('<strong>%s</strong>: Order amount has been authorized by Amazon', 'IOPN'), false);
                    $invoices = $order->getInvoiceCollection();
                    if ($invoices->count()) {
                        foreach ($invoices as $invoice) {
                            $transactionSave->addObject($invoice);
                        }
                    }
                    $transactionSave->addObject($order)->save();
                    if (!$order->getEmailSent() && self::getConfigData('order_confirmation')) $order->sendNewOrderEmail();

                    $itemIdMapping = null;
                    if ($amazonOrder->issetProcessedOrderItems()) {
                        if ($amazonOrder->getProcessedOrderItems()->issetProcessedOrderItem()) {
                            $itemIdMapping = array();
                            foreach ($amazonOrder->getProcessedOrderItems()->getProcessedOrderItem() as $item) {
                                if ($item->issetSKU() && $item->issetAmazonOrderItemCode()) {
                                    $itemIdMapping[$item->getSKU()] = $item->getAmazonOrderItemCode();
                                }
                            }
                        }
                    }
                    $this->sendAcknowledgementNotify($order, $itemIdMapping);
                }
            }
        } else {
            Mage::helper('checkoutbyamazon')->throwException('Amazon Order ID not provided');
        }
    }

    /* Feeds sending routines */

    public function sendCancellationNotify($order) {
        $result = null;
        if ($this->_isAmazonPaymentMethod($order->getPayment()->getMethod()) && $order->getPayment()->getLastTransId()) {
            $filePath = $this->_queueDir['cancel'] . DS . $this->_getTimestamp() . '_order_' . $order->getIncrementId() . '.xml';

            $envelope = new SimpleXMLElement('<AmazonEnvelope></AmazonEnvelope>');
            $envelope->Header->DocumentVersion = '1.01';
            $envelope->Header->MerchantIdentifier = self::getConfigData('merchant_id');
            $envelope->MessageType = 'OrderAcknowledgement';
            $envelope->Message[0]->MessageID = 1;
            $envelope->Message[0]->OrderAcknowledgement->AmazonOrderID = $order->getPayment()->getLastTransId();
            $envelope->Message[0]->OrderAcknowledgement->MerchantOrderID = $order->getIncrementId();
            $envelope->Message[0]->OrderAcknowledgement->StatusCode = 'Failure';

            $fileSaveHandle = fopen($filePath, 'a');
            fwrite($fileSaveHandle, trim($envelope->asXML()));
            fclose($fileSaveHandle);

            if (!self::getConfigData('feed_batching')) {
                $result = $this->_submitFeed(Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Feeds_Abstract::FEED_TYPE_POST_ORDER_ACKNOWLEDGEMENT_DATA, $filePath);
                Creativestyle_CheckoutByAmazon_Model_Logger::logFeed(
                    Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Feeds_Abstract::FEED_TYPE_POST_ORDER_ACKNOWLEDGEMENT_DATA,
                    $result,
                    trim($envelope->asXML()),
                    $order->getStore()->getId()
                );
            }
        }
        return $result;
    }

    public function sendAcknowledgementNotify($order, $itemIdMappingArray = null) {
        $result = null;
        if ($this->_isAmazonPaymentMethod($order->getPayment()->getMethod()) && $order->getPayment()->getLastTransId()) {
            $filePath = $this->_queueDir['acknowledge'] . DS . $this->_getTimestamp() . '_order_' . $order->getIncrementId() . '.xml';

            $envelope = new SimpleXMLElement('<AmazonEnvelope></AmazonEnvelope>');
            $envelope->Header->DocumentVersion = '1.01';
            $envelope->Header->MerchantIdentifier = self::getConfigData('merchant_id');
            $envelope->MessageType = 'OrderAcknowledgement';
            $envelope->Message[0]->MessageID = 1;
            $envelope->Message[0]->OrderAcknowledgement->AmazonOrderID = $order->getPayment()->getLastTransId();
            $envelope->Message[0]->OrderAcknowledgement->MerchantOrderID = $order->getIncrementId();
            $envelope->Message[0]->OrderAcknowledgement->StatusCode = 'Success';

            if (is_array($itemIdMappingArray)) {
                $i = 0;
                foreach ($order->getItemsCollection() as $item) {
                    if (isset($itemIdMappingArray[$item->getSku()])) {
                        $envelope->Message[0]->OrderAcknowledgement->Item[$i]->AmazonOrderItemCode = $itemIdMappingArray[$item->getSku()];
                        $envelope->Message[0]->OrderAcknowledgement->Item[$i++]->MerchantOrderItemID = $item->getId();
                    }
                }
            }

            $fileSaveHandle = fopen($filePath, 'a');
            fwrite($fileSaveHandle, trim($envelope->asXML()));
            fclose($fileSaveHandle);

            if (!self::getConfigData('feed_batching')) {
                $result = $this->_submitFeed(Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Feeds_Abstract::FEED_TYPE_POST_ORDER_ACKNOWLEDGEMENT_DATA, $filePath);
                Creativestyle_CheckoutByAmazon_Model_Logger::logFeed(
                    Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Feeds_Abstract::FEED_TYPE_POST_ORDER_ACKNOWLEDGEMENT_DATA,
                    $result,
                    trim($envelope->asXML()),
                    $order->getStore()->getId()
                );
            }
        }
        return $result;
    }

    public function sendShipmentNotify($order) {
        $result = null;
        if (($order->getState() == Mage_Sales_Model_Order::STATE_COMPLETE) && $this->_isAmazonPaymentMethod($order->getPayment()->getMethod()) && $order->getPayment()->getLastTransId()) {
            $shipmentsCollection = $order->getShipmentsCollection();
            $shipment = null;
            if ($shipmentsCollection->count()) {
                $shipment = Mage::getModel('sales/order_shipment')->load($shipmentsCollection->getFirstItem()->getId());
            }
            if ($shipment) {
                $filePath = $this->_queueDir['shipment'] . DS . $this->_getTimestamp() . '_order_' . $order->getIncrementId() . '.xml';

                $envelope = new SimpleXMLElement('<AmazonEnvelope></AmazonEnvelope>');
                $envelope->Header->DocumentVersion = '1.01';
                $envelope->Header->MerchantIdentifier = self::getConfigData('merchant_id');
                $envelope->MessageType = 'OrderFulfillment';
                $envelope->Message[0]->MessageID = 1;
                $envelope->Message[0]->OrderFulfillment->AmazonOrderID = $order->getPayment()->getLastTransId();
                $envelope->Message[0]->OrderFulfillment->FulfillmentDate = $this->_getFormattedTimestamp($shipment->getCreatedAt());

                $tracks = $shipment->getAllTracks();
                if (isset($tracks[0])) {
                    $envelope->Message[0]->OrderFulfillment->FulfillmentData->CarrierName = $tracks[0]->getTitle() ? $tracks[0]->getTitle() : $tracks[0]->getCarrierCode();
                    $envelope->Message[0]->OrderFulfillment->FulfillmentData->ShipperTrackingNumber = $tracks[0]->getTrackNumber();
                }

                $fileSaveHandle = fopen($filePath, 'a');
                fwrite($fileSaveHandle, trim($envelope->asXML()));
                fclose($fileSaveHandle);

                if (!self::getConfigData('feed_batching')) {
                    $result = $this->_submitFeed(Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Feeds_Abstract::FEED_TYPE_POST_ORDER_FULFILLMENT_DATA, $filePath);
                    Creativestyle_CheckoutByAmazon_Model_Logger::logFeed(
                        Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Feeds_Abstract::FEED_TYPE_POST_ORDER_FULFILLMENT_DATA,
                        $result,
                        trim($envelope->asXML()),
                        $order->getStore()->getId()
                    );
                }
            } else {
                Mage::helper('checkoutbyamazon')->throwException('No shipment assigned to order #' . $order->getIncrementId());
            }
        }
        return $result;
    }

    public function sendRefundNotify($payment) {
        $result = null;
        $order = $payment->getOrder();
        $creditmemo = $payment->getCreditmemo();
        if ($this->_isAmazonPaymentMethod($order->getPayment()->getMethod()) && $order->getPayment()->getLastTransId()) {
            $filePath = $this->_queueDir['refund'] . DS . $this->_getTimestamp() . '_order_' . $order->getIncrementId() . '.xml';

            $envelope = new SimpleXMLElement('<AmazonEnvelope></AmazonEnvelope>');
            $envelope->Header->DocumentVersion = '1.01';
            $envelope->Header->MerchantIdentifier = self::getConfigData('merchant_id');
            $envelope->MessageType = 'OrderAdjustment';
            $envelope->Message[0]->MessageID = 1;
            $envelope->Message[0]->OrderAdjustment->AmazonOrderID = $payment->getLastTransId();
            $envelope->Message[0]->OrderAdjustment->MerchantOrderID = $order->getIncrementId();
            $i = 0;
            foreach ($creditmemo->getAllItems() as $item) {
                $j = 0;
                $envelope->Message[0]->OrderAdjustment->AdjustedItem[$i]->MerchantOrderItemID = $item->getId();
                $envelope->Message[0]->OrderAdjustment->AdjustedItem[$i]->AdjustmentReason = Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Feeds_Abstract::ADJUSTMENT_REASON_CUSTOMER_RETURN;
                $envelope->Message[0]->OrderAdjustment->AdjustedItem[$i]->ItemPriceAdjustments->Component[$j]->Type = 'Principal';
                $principalAmount = $envelope->Message[0]->OrderAdjustment->AdjustedItem[$i]->ItemPriceAdjustments->Component[$j]->addChild('Amount', Mage::helper('checkoutbyamazon')->sanitizePrice($item->getBaseRowTotalInclTax() - $item->getBaseDiscountAmount()));
                $principalAmount->addAttribute('currency', $creditmemo->getBaseCurrencyCode());
                if (!$i) {
                    if ($creditmemo->getBaseShippingAmount() > 0) {
                        $envelope->Message[0]->OrderAdjustment->AdjustedItem[$i]->ItemPriceAdjustments->Component[++$j]->Type = 'Shipping';
                        $shippingAmount = $envelope->Message[0]->OrderAdjustment->AdjustedItem[$i]->ItemPriceAdjustments->Component[$j]->addChild('Amount', Mage::helper('checkoutbyamazon')->sanitizePrice($creditmemo->getBaseShippingAmount()));
                        $shippingAmount->addAttribute('currency', $creditmemo->getBaseCurrencyCode());
                    }
                }
                $i++;
            }

            $fileSaveHandle = fopen($filePath, 'a');
            fwrite($fileSaveHandle, trim($envelope->asXML()));
            fclose($fileSaveHandle);

            if (!self::getConfigData('feed_batching')) {
                $result = $this->_submitFeed(Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Feeds_Abstract::FEED_TYPE_POST_PAYMENT_ADJUSTMENT_DATA, $filePath);
                Creativestyle_CheckoutByAmazon_Model_Logger::logFeed(
                    Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Feeds_Abstract::FEED_TYPE_POST_PAYMENT_ADJUSTMENT_DATA,
                    $result,
                    trim($envelope->asXML()),
                    $order->getStore()->getId()
                );
            }
        }
        return $result;
    }

    public function sendFeeds() {
        $merchantId = self::getConfigData('merchant_id');

        // acknowledge feeds
        $this->_submitBatchFeeds(Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Feeds_Abstract::FEED_TYPE_POST_ORDER_ACKNOWLEDGEMENT_DATA, $this->_queueDir['acknowledge'], $merchantId);

        // cancellation feeds
        $this->_submitBatchFeeds(Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Feeds_Abstract::FEED_TYPE_POST_ORDER_ACKNOWLEDGEMENT_DATA, $this->_queueDir['cancel'], $merchantId);

        // shipment feeds
        $this->_submitBatchFeeds(Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Feeds_Abstract::FEED_TYPE_POST_ORDER_FULFILLMENT_DATA, $this->_queueDir['shipment'], $merchantId);

        // refund feeds
        $this->_submitBatchFeeds(Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Feeds_Abstract::FEED_TYPE_POST_PAYMENT_ADJUSTMENT_DATA, $this->_queueDir['refund'], $merchantId);

    }

}
