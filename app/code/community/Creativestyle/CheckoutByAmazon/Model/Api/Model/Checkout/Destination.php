<?php

/**
 * Amazon Checkout API: Destination data type model
 *
 * Fields:
 * <ul>
 * <li>DestinationName: string</li>
 * <li>DestinationType: DestinationType</li>
 * <li>PhysicalDestinationAttributes: Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_PhysicalDestinationAttributes</li>
 * <li>DigitalDestinationAttributes: Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_DigitalDestinationAttributes</li>
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
class Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_Destination extends Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'DestinationName' => array('FieldValue' => null, 'FieldType' => 'string'),
            'DestinationType' => array('FieldValue' => null, 'FieldType' => 'DestinationType'),
            'PhysicalDestinationAttributes' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_PhysicalDestinationAttributes'),
            'DigitalDestinationAttributes' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_DigitalDestinationAttributes')
        );
        parent::__construct($data);
    }

}
