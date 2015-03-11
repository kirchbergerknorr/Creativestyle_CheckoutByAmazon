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
class Creativestyle_CheckoutByAmazon_CheckoutController extends Mage_Core_Controller_Front_Action {

    protected
        $_sectionUpdateFunctions = array(
            'shipping-method' => '_getShippingMethodsHtml',
            'review'          => '_getReviewHtml',
        );

    protected $_amazonCheckoutApi = null;

    protected function _getPaymentMethod() {
        if (Mage::helper('checkoutbyamazon')->getConfigData('mode') == 'sandbox')
            return array('method' => 'checkoutbyamazon_sandbox');

        return array('method' => 'checkoutbyamazon');
    }

    protected function _ajaxRedirectResponse() {
        $this->getResponse()
            ->setHeader('HTTP/1.1', '403 Session Expired')
            ->setHeader('Login-Required', 'true')
            ->sendResponse();
        return $this;
    }

    protected function _expireAjax() {
        if (!$this->_getOnepage()->getQuote()->hasItems()
            || $this->_getOnepage()->getQuote()->getHasError()
            || $this->_getOnepage()->getQuote()->getIsMultiShipping()
            || ($this->_getOnepage()->getCheckoutMehod()
                && !in_array($this->_getOnepage()->getCheckoutMehod(), array(Creativestyle_CheckoutByAmazon_Model_Abstract::CHECKOUT_METHOD_AMAZON, Mage_Checkout_Model_Type_Onepage::METHOD_CUSTOMER)))) {

            $this->_ajaxRedirectResponse();
            return true;
        }
        $action = $this->getRequest()->getActionName();
        if ($this->_getCheckoutSession()->getCartWasUpdated(true)
            && !in_array($action, array('index', 'progress'))) {
            $this->_ajaxRedirectResponse();
            return true;
        }
        return false;
    }

    protected function _getAmazonCheckoutApi() {
        if (null === $this->_amazonCheckoutApi) {
            $this->_amazonCheckoutApi = Mage::getModel('checkoutbyamazon/api_checkout');
        }
        return $this->_amazonCheckoutApi;
    }

    protected function _getAmazonPrice($amount) {
        return Mage::getModel('checkoutbyamazon/api_model_checkout_price', array(
            'Amount' => Mage::helper('checkoutbyamazon')->sanitizePrice($amount)
        ));
    }

    protected function _getShippingMethodsHtml() {
        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        $update->load('checkout_onepage_shippingmethod');
        $layout->generateXml();
        $layout->generateBlocks();
        $output = $layout->getOutput();
        return $output;
    }

    protected function _getReviewHtml() {
        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        $update->load('checkoutbyamazon_checkout_review');
        $layout->generateXml();
        $layout->generateBlocks();
        $output = $layout->getOutput();
        return $output;
    }

    protected function _getCheckoutSession() {
        return Mage::getSingleton('checkout/session');
    }

    protected function _getOnepage() {
        return Mage::getSingleton('checkoutbyamazon/checkout');
    }

    protected function _getProductId($item) {
        switch ($item->getProductType()) {
            case Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE:
                // get product ID of simply product's sku
                $productId = Mage::getModel('catalog/product')->getIdBySku($item->getProductOptionByCode('simple_sku'));
                break;
            case Mage_Catalog_Model_Product_Type::TYPE_BUNDLE:
            case Mage_Catalog_Model_Product_Type::TYPE_GROUPED:
            case Mage_Catalog_Model_Product_Type::TYPE_VIRTUAL:
            case Mage_Catalog_Model_Product_Type::TYPE_SIMPLE:
            default:
                if ($item->getParentItem() &&
                    in_array($item->getParentItem()->getProductType(), array(Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE, Mage_Catalog_Model_Product_Type::TYPE_BUNDLE))) {
                    return null;
                }
                $productId = $item->getProductId();
                break;
        }
        return $productId;
    }

    protected function _validateCountry($countryCode) {
        $availableCountries = array();
        if (Mage::getStoreConfigFlag(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_COUNTRY_ALLOW)) {
            $availableCountries = explode(',', strtoupper(Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_SPECIFIC_COUNTRY)));
        } else {
            $availableCountries = explode(',', strtoupper(Mage::getStoreConfig('general/country/allow')));
        }
        if (in_array(strtoupper($countryCode), $availableCountries)) return true;
        return false;
    }

    protected function _logException(Exception $exception) {
        Creativestyle_CheckoutByAmazon_Model_Logger::logException(
            $exception->getMessage(),
            $exception->getCode(),
            $exception->getTraceAsString(),
            'Inline Checkout',
            null
        );
    }

    /**
     * @deprecated after 1.3.0
     */
    public function setCheckoutIdAction() {
        $purchaseContractId = $this->getRequest()->getPost('purchaseContractId', null);
        $this->_getCheckoutSession()->setAmazonPurchaseContractId($purchaseContractId);
        if ($purchaseContractId) {
            $result = array('success' => 1, 'message' => '');
        } else {
            $result = array('success' => 0, 'message' => 'PurchaseContractId is empty');
        }
        $this->getResponse()->setHeader('Content-type', 'application/x-json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    public function indexAction() {

        $purchaseContractId = $this->getRequest()->getPost('purchaseContractId', null);

        /**
         * @deprecated after 1.3.0
         */
        if (null === $purchaseContractId) $purchaseContractId = $this->_getCheckoutSession()->getAmazonPurchaseContractId();

        if (null === $purchaseContractId) {
            $this->_redirect('checkout/cart');
            return;
        }

        $this->_getOnepage()->getCheckout()->setAmazonPurchaseContractId($purchaseContractId);

        $quote = $this->_getOnepage()->getQuote();

        if (!$quote->hasItems() || $quote->getHasError()) {
            $this->_redirect('checkout/cart');
            return;
        }

        if (!$quote->validateMinimumAmount()) {
            $error = Mage::getStoreConfig('sales/minimum_order/error_message');
            $this->_getCheckoutSession()->addError($error);
            $this->_redirect('checkout/cart');
            return;
        }

        $this->_getCheckoutSession()->setCartWasUpdated(false);
        Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::getUrl('*/*/*', array('_secure'=>true)));

        $this->_getOnepage()->initCheckout();
        $this->_getOnepage()->saveCheckoutMethod(Creativestyle_CheckoutByAmazon_Model_Abstract::CHECKOUT_METHOD_AMAZON);

        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->getLayout()->getBlock('head')->setTitle($this->__('Checkout by Amazon'));

        $this->renderLayout();
    }

    public function progressAction() {
        if ($this->_expireAjax()) {
            return;
        }
        $this->loadLayout(false);
        $this->renderLayout();
    }

    public function reviewAction() {
        if ($this->_expireAjax()) {
            return;
        }
        $this->loadLayout(false);
        $this->renderLayout();
    }

    public function saveShippingAction() {
        if ($this->_expireAjax()) {
            return;
        }

        $purchaseContractId = $this->getRequest()->getParam('purchaseContractId', null);

        $result = array();
        try {
            $addressList = $this->_getAmazonCheckoutApi()->getAddress($purchaseContractId);
            if (isset($addressList[0])) {
                $addressModel = $addressList[0];
                $customerName = explode(' ', trim($addressModel->getName()));
                if (count($customerName) > 1) {
                    $firstname = reset($customerName);
                    $lastname = trim(str_replace($firstname, "", $addressModel->getName()));
                } else {
                    $firstname = Mage::helper('checkoutbyamazon')->__('n/a');
                    $lastname = reset($customerName);
                }

                $regionId = Mage::helper('checkoutbyamazon')->getRegionId($addressModel->getStateOrProvinceCode(), $addressModel->getCountryCode());

                $street = array();
                if ($addressModel->issetAddressLineOne() || $addressModel->issetAddressLineTwo() || $addressModel->issetAddressLineThree()) {
                    if ($addressModel->issetAddressLineOne()) $street[] = $addressModel->getAddressLineOne();
                    if ($addressModel->issetAddressLineTwo()) $street[] = $addressModel->getAddressLineTwo();
                    if ($addressModel->issetAddressLineThree()) $street[] = $addressModel->getAddressLineThree();
                } else {
                    $street = array('-- PROCESSING --');
                }

                // check if prefix is required
                $prefix = null;
                if (Mage::getSingleton('eav/config')->getAttribute('customer', 'prefix')->getIsRequired()) {
                    $prefix = Mage::helper('checkoutbyamazon')->getConfigData('default_customer_prefix');
                }

                $address = array(
                    'prefix' => $prefix,
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'company' => '',
                    'email' => Mage::helper('checkoutbyamazon')->getConfigData('email_placeholder'),
                    'street' => $street,
                    'city' => $addressModel->getCity(),
                    'region_id' => $regionId ? $regionId : '',
                    'region' => $addressModel->getStateOrProvinceCode(),
                    'postcode' => $addressModel->getPostalCode(),
                    'country_id' => $addressModel->getCountryCode(),
                    'telephone' => $addressModel->getPhoneNumber(),
                    'use_for_shipping' => 1
                );

                if ($this->_validateCountry($addressModel->getCountryCode())) {
                    $result = $this->_getOnepage()->saveBilling($address, false);
                } else {
                    $result['error'] = 1;
                    $result['message'] = Mage::helper('checkoutbyamazon')->__('Sorry. We do not deliver to this country. Please select another shipping address.');
                }


            } else {
                $result['error'] = 1;
                $result['message'] = 'No valid shipping address';
            }
        } catch (Exception $e) {
            $this->_logException($e);
            $result['error'] = 1;
            $result['message'] = $e->getMessage();
        }

        if (!isset($result['error'])) {
            $result['goto_section'] = 'shipping_method';
            $result['update_section'] = array(
                'name' => 'shipping-method',
                'html' => $this->_getShippingMethodsHtml()
            );
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    public function saveShippingMethodAction() {
        if ($this->_expireAjax()) {
            return;
        }
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('shipping_method', '');
            $result = $this->_getOnepage()->saveShippingMethod($data);
            $this->_getOnepage()->getQuote()->collectTotals()->save();

            if (!$result) {
                Mage::dispatchEvent('checkout_controller_onepage_save_shipping_method', array('request' => $this->getRequest(), 'quote' => $this->_getOnepage()->getQuote()));
                $result['goto_section'] = 'payment';
            }

            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }

    }

    public function savePaymentAction() {

        if ($this->_expireAjax()) {
            return;
        }

        $result = array();
        try {
            $result = $this->_getOnepage()->savePayment($this->_getPaymentMethod());
        } catch (Exception $e) {
            $this->_logException($e);
            $result['error'] = 1;
            $result['message'] = $e->getMessage();
        }

        if (!isset($result['error'])) {
            $result['goto_section'] = 'review';
            $result['update_section'] = array(
                'name' => 'review',
                'html' => $this->_getReviewHtml()
            );
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    public function saveOrderAction() {
        if ($this->_expireAjax()) {
            return;
        }

        $result = array();
        try {
            Mage::helper('checkoutbyamazon')->lockExceptionLogging();
            if ($requiredAgreements = Mage::helper('checkout')->getRequiredAgreementIds()) {
                $postedAgreements = array_keys($this->getRequest()->getPost('agreement', array()));
                if ($diff = array_diff($requiredAgreements, $postedAgreements)) {
                    $result['success'] = false;
                    $result['error'] = true;
                    $result['error_messages'] = $this->__('Please agree to all Terms and Conditions before placing the order.');
                    $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                    return;
                }
            }

            Mage::getSingleton('customer/session')->getCustomer()->setId(null)
                ->setGroupId(Mage_Customer_Model_Group::NOT_LOGGED_IN_ID)
                ->setEmail($this->_getOnepage()->getQuote()->getBillingAddress()->getEmail());

            $this->_getOnepage()->getQuote()->getPayment()->importData($this->_getPaymentMethod());
            $this->_getOnepage()->getQuote()->setCustomerIsGuest(true)->save();
            $this->_getOnepage()->saveOrder();

            $lastOrderId = $this->_getOnepage()->getCheckout()->getLastOrderId();
            $order = Mage::getModel('sales/order')->load($lastOrderId);

            // unsuccesful order save
            if (!$order->getId()) Mage::helper('checkoutbyamazon')->throwException('Cannot load order with given ID', 0, array('area' => 'Inline Checkout'));

            $purchaseContractId = $this->getRequest()->getParam('purchaseContractId', null);
            $itemList = Mage::getModel('checkoutbyamazon/api_model_checkout_itemList');

            foreach ($order->getItemsCollection() as $item) {
                $productId = $this->_getProductId($item);
                if (!$productId) continue;
                $product = Mage::getModel('catalog/product')->load($productId);
                $itemUnitPrice = $item->getBasePriceInclTax();
                if (!$itemUnitPrice) {
                    $itemUnitPrice = ($item->getBaseRowTotal() + $item->getBaseTaxAmount()) / $item->getQtyOrdered();
                }
                $amazonItem = Mage::getModel('checkoutbyamazon/api_model_checkout_purchaseItem', array(
                    'MerchantItemId' => $item->getId(),
                    'SKU' => Mage::helper('checkoutbyamazon')->sanitizeSku($item->getSku()),
                    'Title' => $item->getName(),
                    'UnitPrice' => array('Amount' => Mage::helper('checkoutbyamazon')->sanitizePrice($itemUnitPrice)),
                    'Quantity' => round($item->getQtyOrdered())
                ));
                $amazonItem->setItemTax($this->_getAmazonPrice($item->getBaseTaxAmount() / $item->getQtyOrdered()));
                $amazonItem->setItemShipping($this->_getAmazonPrice(0));
                $itemList->addItem($amazonItem);
                unset($product);
            }

            $amazonCharges = Mage::getModel('checkoutbyamazon/api_model_checkout_charges');
            if (abs($order->getBaseDiscountAmount()) > 0) {
                $amazonPromotion = Mage::getModel('checkoutbyamazon/api_model_checkout_promotion', array(
                    'PromotionId' => (trim($order->getCouponCode()) ? trim($order->getCouponCode()) : Mage::helper('checkoutbyamazon')->__('Discount')),
                    'Description' => (trim($order->getDiscountDescription()) ? trim($order->getDiscountDescription()) : Mage::helper('checkoutbyamazon')->__('Discount')),
                    'Discount' => array('Amount' => abs($order->getBaseDiscountAmount()))
                ));
                $promotionList = Mage::getModel('checkoutbyamazon/api_model_checkout_promotionList');
                $promotionList->setPromotion(array($amazonPromotion));
                $amazonCharges->setPromotions($promotionList);
            }
            $amazonCharges->setTax($this->_getAmazonPrice($order->getBaseTaxAmount()));
            $amazonCharges->setShipping($this->_getAmazonPrice($order->getBaseShippingAmount() + $order->getBaseShippingTaxAmount()));

            // send items data to Amazon
            $setItemsResult = $this->_getAmazonCheckoutApi()->setItems($purchaseContractId, $itemList);
            if (!$setItemsResult) Mage::helper('checkoutbyamazon')->throwException('Error when performing SetItems call', 0, array('area' => 'Inline Checkout'));

            $setContractChargesResult = $this->_getAmazonCheckoutApi()->setContractCharges($purchaseContractId, $amazonCharges);
            if (!$setContractChargesResult) Mage::helper('checkoutbyamazon')->throwException('Error when performing SetContractCharges call', 0, array('area' => 'Inline Checkout'));

            $amazonOrderIdList = $this->_getAmazonCheckoutApi()->completeOrder($purchaseContractId);
            if (!is_null($amazonOrderIdList)) {
                if (is_array($amazonOrderIdList)) {
                    $amazonOrderId = implode(',', $amazonOrderIdList);
                } else {
                    $amazonOrderId = $amazonOrderIdList;
                }
                $order->getPayment()->setLastTransId($amazonOrderId)->save();
            } else {
                Mage::helper('checkoutbyamazon')->throwException('Error when performing CompletePurchaseContract call', 0, array('area' => 'Inline Checkout'));
            }

            $this->_getOnepage()->getCheckout()->setAmazonPurchaseContractId(null);
            $result['success'] = true;
            $result['error']   = false;

            Mage::helper('checkoutbyamazon')->unlockExceptionLogging();

        } catch (Mage_Core_Exception $e) {
            Mage::helper('checkoutbyamazon')->unlockExceptionLogging();
            if (isset($order) && is_object($order) && is_callable(array($order, 'getId')) && ($order->getId())) $order->cancel()->save();
            $this->_logException($e);
            $result['success'] = false;
            $result['error'] = true;
            $result['error_messages'] = $e->getMessage();
            if ($gotoSection = $this->_getOnepage()->getCheckout()->getGotoSection()) {
                $result['goto_section'] = $gotoSection;
                $this->_getOnepage()->getCheckout()->setGotoSection(null);
            }
            if ($updateSection = $this->_getOnepage()->getCheckout()->getUpdateSection()) {
                if (isset($this->_sectionUpdateFunctions[$updateSection])) {
                    $updateSectionFunction = $this->_sectionUpdateFunctions[$updateSection];
                    $result['update_section'] = array(
                        'name' => $updateSection,
                        'html' => $this->$updateSectionFunction()
                    );
                }
                $this->_getOnepage()->getCheckout()->setUpdateSection(null);
            }
            $this->_getOnepage()->getQuote()->save();
        } catch (Exception $e) {
            Mage::helper('checkoutbyamazon')->unlockExceptionLogging();
            if (isset($order) && is_object($order) && is_callable(array($order, 'getId')) && ($order->getId())) $order->cancel()->save();
            $this->_logException($e);
            $result['success']  = false;
            $result['error']    = true;
            $result['error_messages'] = Mage::helper('checkout')->__('There was an error processing your order. Please contact us or try again later.');
            $this->_getOnepage()->getQuote()->save();
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));

    }

    public function successAction() {
        if (!$this->_getOnepage()->getCheckout()->getLastSuccessQuoteId()) {
            $this->_redirect('checkout/cart');
            return;
        }

        $lastQuoteId = $this->_getOnepage()->getCheckout()->getLastQuoteId();
        $lastOrderId = $this->_getOnepage()->getCheckout()->getLastOrderId();

        if (!$lastQuoteId || !$lastOrderId) {
            $this->_redirect('checkout/cart');
            return;
        }

        $this->loadLayout();
        $this->_getCheckoutSession()->clear();
        $this->_initLayoutMessages('checkout/session');
        Mage::dispatchEvent('checkout_onepage_controller_success_action', array('order_ids' => array($lastOrderId)));
        $this->renderLayout();
    }

}
