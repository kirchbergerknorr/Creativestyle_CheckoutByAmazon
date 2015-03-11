<?php

/**
 * Instant Order Processing Notification API: OrderItem data type model
 *
 * Fields:
 * <ul>
 * <li>AmazonOrderItemCode: string</li>
 * <li>MerchantId: string</li>
 * <li>SKU: string</li>
 * <li>Title: string</li>
 * <li>Description: string</li>
 * <li>ClientRequestId: string</li>
 * <li>CartId: string</li>
 * <li>IntegratorId: string</li>
 * <li>IntegratorName: string</li>
 * <li>Price: Creativestyle_CheckoutByAmazon_Model_Api_Model_Iopn_Price</li>
 * <li>Quantity: PositiveInteger</li>
 * <li>Weight: Creativestyle_CheckoutByAmazon_Model_Api_Model_Iopn_Weight</li>
 * <li>Category: string</li>
 * <li>Condition: string</li>
 * <li>FulfillmentNetwork: FulfillmentNetwork</li>
 * <li>ItemCharges: Creativestyle_CheckoutByAmazon_Model_Api_Model_Iopn_ItemCharges</li>
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
class Creativestyle_CheckoutByAmazon_Model_Api_Model_Iopn_OrderItem extends Creativestyle_CheckoutByAmazon_Model_Api_Model_Iopn_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'AmazonOrderItemCode' => array('FieldValue' => null, 'FieldType' => 'string'),
            'MerchantId' => array('FieldValue' => null, 'FieldType' => 'string'),
            'SKU' => array('FieldValue' => null, 'FieldType' => 'string'),
            'Title' => array('FieldValue' => null, 'FieldType' => 'string'),
            'Description' => array('FieldValue' => null, 'FieldType' => 'string'),
            'ClientRequestId' => array('FieldValue' => null, 'FieldType' => 'string'),
            'CartId' => array('FieldValue' => null, 'FieldType' => 'string'),
            'IntegratorId' => array('FieldValue' => null, 'FieldType' => 'string'),
            'IntegratorName' => array('FieldValue' => null, 'FieldType' => 'string'),
            'Price' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Iopn_Price'),
            'Quantity' => array('FieldValue' => null, 'FieldType' => 'PositiveInteger'),
            'Weight' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Iopn_Weight'),
            'Category' => array('FieldValue' => null, 'FieldType' => 'string'),
            'Condition' => array('FieldValue' => null, 'FieldType' => 'string'),
            'FulfillmentNetwork' => array('FieldValue' => null, 'FieldType' => 'FulfillmentNetwork'),
            'ItemCharges' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Iopn_ItemCharges')
        );
        parent::__construct($data);
    }

}
