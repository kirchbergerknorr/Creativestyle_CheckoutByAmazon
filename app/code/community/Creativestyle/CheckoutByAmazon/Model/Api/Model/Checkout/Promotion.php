<?php

/**
 * Amazon Checkout API: Promotion data type model
 *
 * Fields:
 * <ul>
 * <li>PromotionId: IdType</li>
 * <li>Description: string</li>
 * <li>Discount: Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_Price</li>
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
class Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_Promotion extends Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'PromotionId' => array('FieldValue' => null, 'FieldType' => 'IdType'),
            'Description' => array('FieldValue' => null, 'FieldType' => 'string'),
            'Discount' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_Price')
        );
        parent::__construct($data);
    }

}
