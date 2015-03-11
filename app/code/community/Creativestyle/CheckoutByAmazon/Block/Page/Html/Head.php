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
class Creativestyle_CheckoutByAmazon_Block_Page_Html_Head extends Mage_Page_Block_Html_Head {

    protected function _isIpAllowed() {
        $allowedIps = Mage::helper('checkoutbyamazon')->getConfigData('allowed_ips');
        if (is_array($allowedIps)) {
            if (!(isset($_SERVER['REMOTE_ADDR']) && in_array($_SERVER['REMOTE_ADDR'], $allowedIps))) return false;
        }
        return true;
    }

    protected function _isActive() {
        return Mage::helper('checkoutbyamazon')->getConfigData('active') && $this->_isIpAllowed();
    }

    public function getCssJsHtml() {
        $html = parent::getCssJsHtml();
        if ($this->_isActive()) {
            $prepend = '<script type="text/javascript" src="' . Mage::helper('checkoutbyamazon')->getHeadJs() . '"></script>';
            $html = $prepend . "\n" . $html;
        }
        return $html;
    }

}
