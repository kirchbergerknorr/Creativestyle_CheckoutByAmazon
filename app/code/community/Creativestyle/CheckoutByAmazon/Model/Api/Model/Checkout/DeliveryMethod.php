<?php

/**
 * Amazon Checkout API: DeliveryMethod data type model
 *
 * Fields:
 * <ul>
 * <li>ServiceLevel: ShippingServiceLevel</li>
 * <li>DisplayableShippingLabel: string</li>
 * <li>DestinationName: string</li>
 * <li>ShippingCustomData: string</li>
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
class Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_DeliveryMethod extends Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'ServiceLevel' => array('FieldValue' => null, 'FieldType' => 'ShippingServiceLevel'),
            'DisplayableShippingLabel' => array('FieldValue' => null, 'FieldType' => 'string'),
            'DestinationName' => array('FieldValue' => null, 'FieldType' => 'string'),
            'ShippingCustomData' => array('FieldValue' => null, 'FieldType' => 'string')
        );
        parent::__construct($data);
    }

}
