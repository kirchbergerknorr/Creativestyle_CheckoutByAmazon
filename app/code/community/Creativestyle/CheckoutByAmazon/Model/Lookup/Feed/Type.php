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
class Creativestyle_CheckoutByAmazon_Model_Lookup_Feed_Type extends Creativestyle_CheckoutByAmazon_Model_Lookup_Abstract {

    public function toOptionArray() {
        if (null === $this->_options) {
            $this->_options = array();
            $feedTypeList = Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Feeds_Abstract::getFeedTypes();
            foreach ($feedTypeList as $feedTypeCode => $feedTypeLabel) {
                $this->_options[] = array(
                    'value' => $feedTypeCode,
                    'label' => Mage::helper('checkoutbyamazon')->__($feedTypeLabel)
                );
            }
        }
        return $this->_options;
    }
}
