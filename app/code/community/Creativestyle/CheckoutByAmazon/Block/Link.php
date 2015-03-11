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
class Creativestyle_CheckoutByAmazon_Block_Link extends Creativestyle_CheckoutByAmazon_Block_Abstract {

    public function _toHtml() {
        $quote = Mage::getSingleton('checkout/session')->getQuote();
        if ($this->_isActive() && $quote->validateMinimumAmount()) {
            return parent::_toHtml();
        }
        return '';
    }

    protected function _getButtonSize() {
        return Mage::getStoreConfig(self::XML_PATH_BUTTON_SIZE);
    }

    protected function _getButtonColor() {
        return Mage::getStoreConfig(self::XML_PATH_BUTTON_COLOR);
    }

    protected function _getButtonBackground() {
        return Mage::getStoreConfig(self::XML_PATH_BUTTON_BACKGROUND);
    }

    public function getButtonWidgetUrl() {
        switch ($this->_getMarketplace()) {
            case 'de_DE':
                switch ($this->_getMode()) {
                    case 'live':
                        return 'https://payments.amazon.de/gp/cba/button?cartOwnerId=' . $this->getMerchantId() . '&size=' . $this->_getButtonSize() . '&color=' . $this->_getButtonColor() . '&background=' . $this->_getButtonBackground() . '&type=inlineCheckout';

                    case 'sandbox':
                        return 'https://payments-sandbox.amazon.de/gp/cba/button?cartOwnerId=' . $this->getMerchantId() . '&size=' . $this->_getButtonSize() . '&color=' . $this->_getButtonColor() . '&background=' . $this->_getButtonBackground() . '&type=inlineCheckout';
                }

            case 'en_GB':
                switch ($this->_getMode()) {
                    case 'live':
                        return 'https://payments.amazon.co.uk/gp/cba/button?cartOwnerId=' . $this->getMerchantId() . '&size=' . $this->_getButtonSize() . '&color=' . $this->_getButtonColor() . '&background=' . $this->_getButtonBackground() . '&type=inlineCheckout';

                    case 'sandbox':
                        return 'https://payments-sandbox.amazon.co.uk/gp/cba/button?cartOwnerId=' . $this->getMerchantId() . '&size=' . $this->_getButtonSize() . '&color=' . $this->_getButtonColor() . '&background=' . $this->_getButtonBackground() . '&type=inlineCheckout';
                }

            default:
                switch ($this->_getMode()) {
                    case 'live':
                        return 'https://payments.amazon.com/gp/cba/button?cartOwnerId=' . $this->getMerchantId() . '&size=' . $this->_getButtonSize() . '&color=' . $this->_getButtonColor() . '&background=' . $this->_getButtonBackground() . '&type=inlineCheckout';

                    case 'sandbox':
                        return 'https://payments-sandbox.amazon.com/gp/cba/button?cartOwnerId=' . $this->getMerchantId() . '&size=' . $this->_getButtonSize() . '&color=' . $this->_getButtonColor() . '&background=' . $this->_getButtonBackground() . '&type=inlineCheckout';
                }
        }

    }

    public function getAmazonCheckoutUrl() {
        $url = Mage::getUrl('checkoutbyamazon/checkout');
/*
        if (preg_match('/\?(.+)/', $url))
            $url .= '&purchaseContractId=';
            $url .= '?purchaseContractId=';
 */
        return $url;
    }

    public function getPurchaseContractId() {
        if (Mage::getStoreConfig(self::XML_PATH_REUSE_AMAZON_SESSION)) {
            if (Mage::getSingleton('checkout/session')->getAmazonPurchaseContractId()) {
                try {
                    $purchaseContract = Mage::getModel('checkoutbyamazon/api_checkout')->getPurchaseContract(Mage::getSingleton('checkout/session')->getAmazonPurchaseContractId());
                    if (strtolower($purchaseContract->getState()) == 'active') return $purchaseContract->getId();
                } catch (Exception $e) {
                    return false;
                }
            }
        }
        return false;
    }

}
