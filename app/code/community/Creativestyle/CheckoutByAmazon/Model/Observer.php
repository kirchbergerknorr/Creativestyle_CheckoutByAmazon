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
class Creativestyle_CheckoutByAmazon_Model_Observer extends Creativestyle_CheckoutByAmazon_Model_Abstract {

    protected static $_lockApiCalls = false;
    protected static $_lockFeedSending = false;

    protected function _getAmazonManager() {
        return Mage::getSingleton('checkoutbyamazon/manager');
    }

    protected function _assertSchedule($schedule, $timeDiff) {
        switch ($schedule) {
            case Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_NEVER:
            case Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_15_MINUTES:
                if ($timeDiff >= 900) return true;
                break;
            case Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_30_MINUTES:
                if ($timeDiff >= 1800) return true;
                break;
            case Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_1_HOUR:
                if ($timeDiff >= 3600) return true;
                break;
            case Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_2_HOURS:
                if ($timeDiff >= 7200) return true;
                break;
            case Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_4_HOURS:
                if ($timeDiff >= 14400) return true;
                break;
            case Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_8_HOURS:
                if ($timeDiff >= 28800) return true;
                break;
            case Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_12_HOURS:
                if ($timeDiff >= 43200) return true;
                break;
            case Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_1_DAY:
                if ($timeDiff >= 86400) return true;
                break;
            case Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_2_DAYS:
                if ($timeDiff >= 172800) return true;
                break;
            case Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_3_DAYS:
                if ($timeDiff >= 259200) return true;
                break;
            case Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_7_DAYS:
                if ($timeDiff >= 604800) return true;
                break;
            case Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_14_DAYS:
                if ($timeDiff >= 1209600) return true;
                break;
            case Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_15_DAYS:
                if ($timeDiff >= 1296000) return true;
                break;
            case Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_30_DAYS:
                if ($timeDiff >= 2592000) return true;
                break;
        }
        return false;
    }


    /* Cronjobs */

    public function callAmazonApi() {
        if (!self::$_lockApiCalls) {
            self::$_lockApiCalls = true;

            $currentStore = Mage::app()->getStore();
            $now = Mage::getModel('core/date')->gmtTimestamp();
            $schedule = self::getConfigData('report_schedule');

            // Amazon MWS Reports API
            $lastReportProcessing = self::getConfigData('last_report_processing');
            $lastReportTimeDiff = (int) $now - (int) $lastReportProcessing;

            if ($this->_assertSchedule($schedule, $lastReportTimeDiff)) {
                self::setConfigData('last_report_processing', $now);
                $stores = Mage::app()->getStores(false, false);
                foreach ($stores as $store) {
                    Mage::app()->setCurrentStore($store);
                    if (self::getConfigData('api_order_update')) {
                        switch ($schedule) {
                            case Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_NEVER:
                                break;
                            default:
                                $nextToken = null;
                                do {
                                    $nextToken = $this->_getAmazonManager()->retrieveAndHandleReportList($nextToken);
                                } while ($nextToken);
                        }
                        $this->_getAmazonManager()->manageReportSchedule($schedule);
                    }
                    Mage::app()->setCurrentStore($currentStore);
                }
            }

            // Amazon MWS Orders API
            $lastOrderProcessing = self::getConfigData('last_order_processing');
            $lastOrderTimeDiff = (int) ($now - (int) $lastOrderProcessing);

            if ($this->_assertSchedule($schedule, round($lastOrderTimeDiff / 2))) {
                self::setConfigData('last_order_processing', $now);
                $stores = Mage::app()->getStores(false, false);
                foreach ($stores as $store) {
                    Mage::app()->setCurrentStore($store);
                    if (self::getConfigData('api_order_update')) {
                        $this->_getAmazonManager()->updateCanceledOrders();
                    }
                    Mage::app()->setCurrentStore($currentStore);
                }

            }

            self::$_lockApiCalls = false;
        }
    }

    public function cleanAmazonLog() {
        Creativestyle_CheckoutByAmazon_Model_Logger::cleanLogs();
    }

    public function sendFeeds() {
        if (!self::$_lockFeedSending) {
            self::$_lockFeedSending = true;

            $currentStore = Mage::app()->getStore();
            $now = Mage::getModel('core/date')->gmtTimestamp();
            $schedule = self::getConfigData('feed_schedule');
            $lastFeedSending = self::getConfigData('last_feed_sending');
            $lastFeedTimeDiff = (int) $now - (int) $lastFeedSending;

            if ($this->_assertSchedule($schedule, $lastFeedTimeDiff)) {
                self::setConfigData('last_feed_sending', $now);
                $stores = Mage::app()->getStores(false, false);
                foreach ($stores as $store) {
                    Mage::app()->setCurrentStore($store);
                    if (self::getConfigData('feed_batching')) {
                        $this->_getAmazonManager()->sendFeeds();
                    }
                    Mage::app()->setCurrentStore($currentStore);
                }
            }

            self::$_lockFeedSending = false;
        }
    }

    /* Observers */

    public function cancelOrderInAmazon($observer) {
        try {
            $order = $observer->getEvent()->getPayment()->getOrder();
            if ($this->_isAmazonPaymentMethod($order->getPayment()->getMethod())) {
                $currentStore = Mage::app()->getStore();
                Mage::app()->setCurrentStore($order->getStore()->getId());
                $this->_getAmazonManager()->sendCancellationNotify($order);
                Mage::app()->setCurrentStore($currentStore);
            }
        } catch (Exception $e) {
            if (!($e instanceof Creativestyle_CheckoutByAmazon_Exception)) {
                Creativestyle_CheckoutByAmazon_Model_Logger::logException(
                    $e->getMessage(),
                    $e->getCode(),
                    $e->getTraceAsString(),
                    'Amazon MWS Feeds',
                    null
                );
            }
        }
        return $this;
    }

    public function confirmShipmentToAmazon($observer) {
        try {
            $order = $observer->getEvent()->getOrder();
            if ($this->_isAmazonPaymentMethod($order->getPayment()->getMethod()) &&
                    !in_array($order->getState(), array(Mage_Sales_Model_Order::STATE_COMPLETE, Mage_Sales_Model_Order::STATE_CLOSED, Mage_Sales_Model_Order::STATE_CANCELED))) {

                if ($order->getShipmentsCollection()->count()) {
                    $currentStore = Mage::app()->getStore();
                    Mage::app()->setCurrentStore($order->getStore()->getId());
                    $transactionSave = Mage::getModel('core/resource_transaction');
                    $invoices = $order->getInvoiceCollection();
                    if ($invoices->count()) {
                        foreach ($invoices as $invoice) {
                            $invoice->pay();
                            $transactionSave->addObject($invoice);
                        }
                    }
                    $order->addStatusHistoryComment(Mage::helper('checkoutbyamazon')->__('<strong>%s</strong>: Order shipment has been notified to Amazon', 'Amazon MWS'));
                    $transactionSave->addObject($order)->save();
                    $this->_getAmazonManager()->sendShipmentNotify($order);
                    Mage::app()->setCurrentStore($currentStore);
                }
            }
        } catch (Exception $e) {
            if (!($e instanceof Creativestyle_CheckoutByAmazon_Exception)) {
                Creativestyle_CheckoutByAmazon_Model_Logger::logException(
                    $e->getMessage(),
                    $e->getCode(),
                    $e->getTraceAsString(),
                    'Amazon MWS Feeds',
                    null
                );
            }
        }
        return $this;
    }

    public function logApiCall($observer) {
        $requestMethod = 'GET';
        $host = $observer->getEvent()->getHost();
        $action = $observer->getEvent()->getAction();
        $headers = $observer->getEvent()->getHeaders();
        $get = $observer->getEvent()->getGet();
        $post = $observer->getEvent()->getPost();
        $file = $observer->getEvent()->getFile();
        $responseCode = $observer->getEvent()->getResponseCode();
        $response = $observer->getEvent()->getResponse();

        if ($file):
            $requestMethod = 'FILE';
        elseif ($post):
            $requestMethod = 'POST';
        endif;

        Creativestyle_CheckoutByAmazon_Model_Logger::logApiCall($host, $action, $requestMethod, $headers, $get, $post, $file, $responseCode, $response);
        return $this;
    }

}
