<?php

/**
 * Amazon Checkout API: PhysicalProductAttributes data type model
 *
 * Fields:
 * <ul>
 * <li>Weight: Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_Weight</li>
 * <li>Condition: string</li>
 * <li>GiftOption: string</li>
 * <li>GiftMessage: string</li>
 * <li>DeliveryMethod: Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_DeliveryMethod</li>
 * <li>ItemCharges: Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_Charges</li>
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
class Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_PhysicalProductAttributes extends Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'Weight' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_Weight'),
            'Condition' => array('FieldValue' => null, 'FieldType' => 'string'),
            'GiftOption' => array('FieldValue' => null, 'FieldType' => 'string'),
            'GiftMessage' => array('FieldValue' => null, 'FieldType' => 'string'),
            'DeliveryMethod' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_DeliveryMethod'),
            'ItemCharges' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_Charges')
        );
        parent::__construct($data);
    }

}
