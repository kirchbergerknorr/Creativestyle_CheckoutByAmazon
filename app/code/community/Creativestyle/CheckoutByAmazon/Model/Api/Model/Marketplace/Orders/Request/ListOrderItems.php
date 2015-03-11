<?php

/**
 * Amazon Marketplace Orders API: ListOrderItems request model
 *
 * Fields:
 * <ul>
 * <li>SellerId: string</li>
 * <li>AmazonOrderId: string</li>
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
class Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Request_ListOrderItems extends Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'SellerId' => array('FieldValue' => null, 'FieldType' => 'string'),
            'AmazonOrderId' => array('FieldValue' => null, 'FieldType' => 'string')
        );
        parent::__construct($data);
    }

    public function convertToQueryString() {
        $params = array();
        $params['Action'] = 'ListOrderItems';
        if ($this->issetSellerId()) {
            $params['SellerId'] = $this->getSellerId();
        }
        if ($this->issetAmazonOrderId()) {
            $params['AmazonOrderId'] = $this->getAmazonOrderId();
        }
        return $params;
    }

}
