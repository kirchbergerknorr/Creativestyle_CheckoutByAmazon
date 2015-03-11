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
class Creativestyle_CheckoutByAmazon_Model_Lookup_Design_Button_Background extends Creativestyle_CheckoutByAmazon_Model_Lookup_Abstract {

    const BACKGROUND_WHITE  = 'white';
    const BACKGROUND_LIGHT  = 'light';
    const BACKGROUND_DARK   = 'dark';

    public function toOptionArray() {
        if (null === $this->_options) {
            $this->_options = array(
                array('value' => self::BACKGROUND_WHITE, 'label' => Mage::helper('checkoutbyamazon')->__('White')),
                array('value' => self::BACKGROUND_LIGHT, 'label' => Mage::helper('checkoutbyamazon')->__('Light coloured')),
                array('value' => self::BACKGROUND_DARK, 'label' => Mage::helper('checkoutbyamazon')->__('Dark')),
            );
        }
        return $this->_options;
    }

}
