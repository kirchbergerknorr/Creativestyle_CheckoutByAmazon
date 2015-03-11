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
class Creativestyle_CheckoutByAmazon_Model_Lookup_Design_Button_Color extends Creativestyle_CheckoutByAmazon_Model_Lookup_Abstract {

    const COLOR_ORANGE  = 'orange';
    const COLOR_TAN     = 'tan';

    public function toOptionArray() {
        if (null === $this->_options) {
            $this->_options = array(
                array('value' => self::COLOR_ORANGE, 'label' => Mage::helper('checkoutbyamazon')->__('Orange (recommended)')),
                array('value' => self::COLOR_TAN, 'label' => Mage::helper('checkoutbyamazon')->__('Tan')),
            );
        }
        return $this->_options;
    }

}
