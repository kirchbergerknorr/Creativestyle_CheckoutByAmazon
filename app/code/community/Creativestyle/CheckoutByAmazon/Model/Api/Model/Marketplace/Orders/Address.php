<?php

/**
 * Amazon Marketplace Orders API: Address data type model
 *
 * Fields:
 * <ul>
 * <li>Name: string</li>
 * <li>AddressLine1: string</li>
 * <li>AddressLine2: string</li>
 * <li>AddressLine3: string</li>
 * <li>City: string</li>
 * <li>County: string</li>
 * <li>District: string</li>
 * <li>StateOrRegion: string</li>
 * <li>PostalCode: string</li>
 * <li>CountryCode: string</li>
 * <li>Phone: string</li>
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
class Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Address extends Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'Name' => array('FieldValue' => null, 'FieldType' => 'string'),
            'AddressLine1' => array('FieldValue' => null, 'FieldType' => 'string'),
            'AddressLine2' => array('FieldValue' => null, 'FieldType' => 'string'),
            'AddressLine3' => array('FieldValue' => null, 'FieldType' => 'string'),
            'City' => array('FieldValue' => null, 'FieldType' => 'string'),
            'County' => array('FieldValue' => null, 'FieldType' => 'string'),
            'District' => array('FieldValue' => null, 'FieldType' => 'string'),
            'StateOrRegion' => array('FieldValue' => null, 'FieldType' => 'string'),
            'PostalCode' => array('FieldValue' => null, 'FieldType' => 'string'),
            'CountryCode' => array('FieldValue' => null, 'FieldType' => 'string'),
            'Phone' => array('FieldValue' => null, 'FieldType' => 'string')
        );
        parent::__construct($data);
    }

}
