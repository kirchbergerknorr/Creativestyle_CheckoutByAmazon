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
 * @copyright  Copyright (c) 2011 - 2013 creativestyle GmbH (http://www.creativestyle.de)
 * @author     Marek Zabrowarny / creativestyle GmbH <amazon@creativestyle.de>
 */
class Creativestyle_CheckoutByAmazon_Model_Payment_CheckoutByAmazon_Abstract extends Creativestyle_CheckoutByAmazon_Model_Payment_Abstract {

    protected $_code = 'checkoutbyamazon_abstract';
    protected $_infoBlockType = 'checkoutbyamazon/payment_info';

    protected $_isGateway = false;
    protected $_canAuthorize = true;
    protected $_canCapture = true;
    protected $_canCapturePartial = false;
    protected $_canRefund = true;
    protected $_canRefundInvoicePartial = true;
    protected $_canVoid = true;
    protected $_canUseInternal = false;
    protected $_canUseCheckout = false;
    protected $_canUseForMultishipping = false;
    protected $_isInitializeNeeded = true;

    /**
     * Get checkout session
     *
     * @return Mage_Checkout_Model_Session
     */
    protected function _getCheckout() {
        return Mage::getSingleton('checkout/type_onepage');
    }

    /**
     * @deprecated after 1.5.0
     */
    protected function _isAmazonCheckout() {
        $_module = Mage::app()->getRequest()->getModuleName();
        if ($this->_getCheckoutMethod() == Creativestyle_CheckoutByAmazon_Model_Abstract::CHECKOUT_METHOD_AMAZON && $_module == 'checkoutbyamazon') return true;
        return false;
    }

    /**
     * @deprecated after 1.5.0
     */
    protected function _getCheckoutMethod() {
        return $this->_getCheckout()->getQuote()->getData('checkout_method');
    }

    /**
     * Check whether payment method can be used
     *
     * @param Mage_Sales_Model_Quote
     * @return bool
     */
    public function isAvailable($quote = null) {
        $checkResult = new StdClass;
        $checkResult->isAvailable = (bool)Mage::helper('checkoutbyamazon')->getConfigData('active');
        Mage::dispatchEvent('payment_method_is_active', array(
            'result' => $checkResult,
            'method_instance' => $this,
            'quote' => $quote,
        ));
        return $checkResult->isAvailable;
    }

    /**
     * Instantiate state and set it to state object
     * @param string $paymentAction
     * @param Varien_Object
     * @return Creativestyle_CheckoutByAmazon_Model_Payment_CheckoutByAmazon_Abstract
     */
    public function initialize($paymentAction, $stateObject) {
        $state = Mage_Sales_Model_Order::STATE_PENDING_PAYMENT;
        $stateObject->setState($state);
        $stateObject->setStatus('pending_amazon');
        $stateObject->setIsNotified(false);
        return $this;
    }

    /**
     * Authorize
     *
     * @param Varien_Object $payment
     * @param float $amount
     * @return Creativestyle_CheckoutByAmazon_Model_Payment_CheckoutByAmazon_Abstract
     */
    public function authorize(Varien_Object $payment, $amount) {

        if (!$this->canAuthorize()) Mage::helper('checkoutbyamazon')->throwException('Authorize action is not available');

        if ($payment->getOrder()->canInvoice()) {
            $invoice = $payment->getOrder()
                ->prepareInvoice()
                ->register();
            $invoice->setTransactionId($payment->getLastTransId());
            $this->_getOrder()->addRelatedObject($invoice);
        }

        return $this;
    }

    /**
     * Refund money
     *
     * @param Varien_Object $payment
     * @param float $amount
     * @return Creativestyle_CheckoutByAmazon_Model_Payment_CheckoutByAmazon_Abstract
     */
    public function refund(Varien_Object $payment, $amount) {
        if (!$this->canRefund()) Mage::helper('checkoutbyamazon')->throwException('Refund action is not available');
        Mage::getSingleton('checkoutbyamazon/manager')->sendRefundNotify($payment);
        return $this;
    }

    public function getOrderPlaceRedirectUrl() {
        return Mage::getUrl('checkoutbyamazon/checkout/success');
    }

}
