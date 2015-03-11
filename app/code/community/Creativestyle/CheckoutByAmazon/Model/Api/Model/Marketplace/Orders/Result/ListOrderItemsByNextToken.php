<?php

/**
 * Amazon Marketplace Orders API: ListOrderItemsByNextToken result model
 *
 * Fields:
 * <ul>
 * <li>NextToken: string</li>
 * <li>AmazonOrderId: string</li>
 * <li>OrderItems: Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_OrderItemList</li>
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
class Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Result_ListOrderItemsByNextToken extends Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'NextToken' => array('FieldValue' => null, 'FieldType' => 'string'),
            'AmazonOrderId' => array('FieldValue' => null, 'FieldType' => 'string'),
            'OrderItems' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_OrderItemList')
        );
        parent::__construct($data);
    }

}
