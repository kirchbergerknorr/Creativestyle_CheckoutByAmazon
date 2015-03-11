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
class Creativestyle_CheckoutByAmazon_Model_Lookup_Prefix extends Creativestyle_CheckoutByAmazon_Model_Lookup_Abstract {

    public function toOptionArray() {
        if (null === $this->_options) {
            $this->_options = array();
            $options = trim(Mage::helper('customer/address')->getConfig('prefix_options'));
            if ($options) {
                $options = explode(';', $options);
                foreach ($options as &$v) {
                    $v = Mage::helper('core')->htmlEscape(trim($v));
                    $this->_options[] = array(
                        'value' => $v,
                        'label' => $v
                    );
                }
            }
            array_unshift($this->_options, array('value' => '', 'label' => '---'));
        }
        return $this->_options;
    }
}
