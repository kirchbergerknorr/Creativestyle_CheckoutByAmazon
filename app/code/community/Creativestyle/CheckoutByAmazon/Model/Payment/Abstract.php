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
class Creativestyle_CheckoutByAmazon_Model_Payment_Abstract extends Mage_Payment_Model_Method_Abstract {

    const CHECK_USE_FOR_COUNTRY       = 1;
    const CHECK_USE_FOR_CURRENCY      = 2;
    const CHECK_USE_CHECKOUT          = 4;
    const CHECK_USE_FOR_MULTISHIPPING = 8;
    const CHECK_USE_INTERNAL          = 16;
    const CHECK_ORDER_TOTAL_MIN_MAX   = 32;
    const CHECK_RECURRING_PROFILES    = 64;
    const CHECK_ZERO_TOTAL            = 128;

    protected $_code = 'amazonpayments_abstract';

    protected $_isGateway = false;
    protected $_canAuthorize = true;
    protected $_canCapture = true;
    protected $_canCapturePartial = false;
    protected $_canRefund = true;
    protected $_canRefundInvoicePartial = false;
    protected $_canVoid = true;
    protected $_canUseInternal = false;
    protected $_canUseCheckout = true;
    protected $_canUseForMultishipping = false;
    protected $_isInitializeNeeded = false;

    protected $_order = null;

    protected function _getOrder() {
        if (null === $this->_order) {
            $paymentInfo = $this->getInfoInstance();
            $this->_order = Mage::getModel('sales/order')->loadByIncrementId($paymentInfo->getOrder()->getRealOrderId());
        }
        return $this->_order;
    }

}
