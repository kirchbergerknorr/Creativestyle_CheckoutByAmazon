<?php

/**
 * Amazon Checkout API: Error data type model
 *
 * Fields:
 * <ul>
 * <li>Type: string</li>
 * <li>Code: string</li>
 * <li>Message: string</li>
 * <li>Detail: Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_Object</li>
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
class Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_Error extends Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'Type' => array('FieldValue' => null, 'FieldType' => 'string'),
            'Code' => array('FieldValue' => null, 'FieldType' => 'string'),
            'Message' => array('FieldValue' => null, 'FieldType' => 'string'),
            'Detail' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_Object')
        );
        parent::__construct($data);
    }

}
