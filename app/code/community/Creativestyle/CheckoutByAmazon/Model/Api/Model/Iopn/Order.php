<?php

/**
 * Instant Order Processing Notification API: Order data type model
 *
 * Fields:
 * <ul>
 * <li>OrderChannel: string</li>
 * <li>AmazonOrderID: string</li>
 * <li>OrderDate: DateTime</li>
 * <li>BuyerInfo: Creativestyle_CheckoutByAmazon_Model_Api_Model_Iopn_BuyerInfo</li>
 * <li>ShippingAddress: Creativestyle_CheckoutByAmazon_Model_Api_Model_Iopn_ShippingAddress</li>
 * <li>ShippingServiceLevel: ShippingServiceLevel</li>
 * <li>ProcessedOrderItems: Creativestyle_CheckoutByAmazon_Model_Api_Model_Iopn_ItemList</li>
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
class Creativestyle_CheckoutByAmazon_Model_Api_Model_Iopn_Order extends Creativestyle_CheckoutByAmazon_Model_Api_Model_Iopn_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'OrderChannel' => array('FieldValue' => null, 'FieldType' => 'string'),
            'AmazonOrderID' => array('FieldValue' => null, 'FieldType' => 'string'),
            'OrderDate' => array('FieldValue' => null, 'FieldType' => 'DateTime'),
            'BuyerInfo' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Iopn_BuyerInfo'),
            'BillingAddress' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Iopn_BillingAddress'),
            'ShippingAddress' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Iopn_ShippingAddress'),
            'ShippingServiceLevel' => array('FieldValue' => null, 'FieldType' => 'ShippingServiceLevel'),
            'ProcessedOrderItems' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Iopn_ItemList')
        );
        parent::__construct($data);
    }

}
