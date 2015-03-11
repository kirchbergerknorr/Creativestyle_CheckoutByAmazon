<?php

/**
 * Amazon Checkout API: ContractCharges data type model
 *
 * Fields:
 * <ul>
 * <li>Tax: Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_Price</li>
 * <li>Shipping: Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_Price</li>
 * <li>GiftWrap: Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_Price</li>
 * <li>Promotions: Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_PromotionList</li>
 * </ul>
 *
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
class Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_ContractCharges extends Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'Tax' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_Price'),
            'Shipping' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_Price'),
            'GiftWrap' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_Price'),
            'Promotions' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_PromotionList')
        );
        parent::__construct($data);
    }

}
