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
class Creativestyle_CheckoutByAmazon_Block_Adminhtml_Debug_Section extends Mage_Adminhtml_Block_Template {

    protected
        $_id = null,
        $_area = 'general',
        $_showKeys = true;

    public function __construct() {
        parent::__construct();
        $this->setTemplate('creativestyle/checkoutbyamazon/debug/section.phtml');
    }

    public function getDebugInfo() {
        return Mage::helper('checkoutbyamazon/debug')->getDebugInfo($this->_area);
    }

    public function getSectionId() {
        if (null === $this->_id) {
            $this->_id = 'section-' . uniqid();
        }
        return $this->_id;
    }

    public function setArea($area) {
        $this->_area = $area;
        return $this;
    }

    public function setShowKeys($show) {
        $this->_showKeys = $show;
        return $this;
    }

    public function getShowKeys() {
        return $this->_showKeys;
    }

}
