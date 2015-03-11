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
class Creativestyle_CheckoutByAmazon_Model_Lookup_Design_Button_Size extends Creativestyle_CheckoutByAmazon_Model_Lookup_Abstract {

    const SIZE_MEDIUM   = 'medium';
    const SIZE_LARGE    = 'large';
    const SIZE_XLARGE   = 'x-large';

    public function toOptionArray() {
        if (null === $this->_options) {
            $this->_options = array(
                array('value' => self::SIZE_MEDIUM, 'label' => Mage::helper('checkoutbyamazon')->__('Medium')),
                array('value' => self::SIZE_LARGE, 'label' => Mage::helper('checkoutbyamazon')->__('Large')),
                array('value' => self::SIZE_XLARGE, 'label' => Mage::helper('checkoutbyamazon')->__('X-Large'))
            );
        }
        return $this->_options;
    }
}
