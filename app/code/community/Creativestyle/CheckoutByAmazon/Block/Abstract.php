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
abstract class Creativestyle_CheckoutByAmazon_Block_Abstract extends Mage_Checkout_Block_Onepage_Abstract {

    const XML_PATH_REUSE_AMAZON_SESSION     = 'checkoutbyamazon/general/reuse_amazon_session';

    const XML_PATH_BUTTON_SIZE              = 'checkoutbyamazon/design/button_size';
    const XML_PATH_BUTTON_COLOR             = 'checkoutbyamazon/design/button_color';
    const XML_PATH_BUTTON_BACKGROUND        = 'checkoutbyamazon/design/button_background';
    const XML_PATH_ADDRESS_WIDGET_WIDTH     = 'checkoutbyamazon/design/address_width';
    const XML_PATH_ADDRESS_WIDGET_HEIGHT    = 'checkoutbyamazon/design/address_height';
    const XML_PATH_PAYMENT_WIDGET_WIDTH     = 'checkoutbyamazon/design/payment_width';
    const XML_PATH_PAYMENT_WIDGET_HEIGHT    = 'checkoutbyamazon/design/payment_height';
    const XML_PATH_PROGRESS_WIDGET_WIDTH    = 'checkoutbyamazon/design/progress_width';
    const XML_PATH_PROGRESS_WIDGET_HEIGHT   = 'checkoutbyamazon/design/progress_height';
    const XML_PATH_REVIEW_WIDGET_WIDTH      = 'checkoutbyamazon/design/review_width';
    const XML_PATH_REVIEW_WIDGET_HEIGHT     = 'checkoutbyamazon/design/review_height';

    protected function _isActive() {
        return Mage::helper('checkoutbyamazon')->getConfigData('active') && $this->_isIpAllowed();
    }

    protected function _getMode() {
        return Mage::helper('checkoutbyamazon')->getConfigData('mode');
    }

    protected function _getMarketplace() {
        return Mage::helper('checkoutbyamazon')->getConfigData('marketplace');
    }

    protected function _isIpAllowed() {
        $allowedIps = Mage::helper('checkoutbyamazon')->getConfigData('allowed_ips');
        if (is_array($allowedIps)) {
            if (!(isset($_SERVER['REMOTE_ADDR']) && in_array($_SERVER['REMOTE_ADDR'], $allowedIps))) return false;
        }
        return true;
    }

    public function getMerchantId() {
        return Mage::helper('checkoutbyamazon')->getConfigData('merchant_id');
    }

    /**
     * @deprecated Deprecated since version 1.6.0
     */
    public function getPaymentWidgetJsUrl() {
        switch ($this->_getMarketplace()) {
            case 'de_DE':
                switch ($this->_getMode()) {
                    case 'live':
                        return 'https://static-eu.payments-amazon.com/cba/js/de/PaymentWidgets.js';

                    case 'sandbox':
                        return 'https://static-eu.payments-amazon.com/cba/js/de/sandbox/PaymentWidgets.js';
                }

            case 'en_GB':
                switch ($this->_getMode()) {
                    case 'live':
                        return 'https://static-eu.payments-amazon.com/cba/js/gb/PaymentWidgets.js';

                    case 'sandbox':
                        return 'https://static-eu.payments-amazon.com/cba/js/gb/sandbox/PaymentWidgets.js';
                }

            default:
                switch ($this->_getMode()) {
                    case 'live':
                        return 'https://static-na.payments-amazon.com/cba/js/us/PaymentWidgets.js';

                    case 'sandbox':
                        return 'https://static-na.payments-amazon.com/cba/js/us/sandbox/PaymentWidgets.js';
                }

        }
    }

    public function getPurchaseContractId() {
        if (Mage::getSingleton('checkout/session')->getAmazonPurchaseContractId()) return Mage::getSingleton('checkout/session')->getAmazonPurchaseContractId();
        return false;
    }

    /**
     * @deprecated Deprecated since version 1.6.0
     */
    public function isOverlayEnabled() {
        return false;
    }

    /**
     * @deprecated Deprecated since version 1.6.0
     */
    public function getOverlayWidth() {
        return 0;
    }

}
