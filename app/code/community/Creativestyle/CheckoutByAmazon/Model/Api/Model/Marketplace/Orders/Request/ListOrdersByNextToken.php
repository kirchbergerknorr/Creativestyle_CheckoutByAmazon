<?php

/**
 * Amazon Marketplace Orders API: ListOrdersByNextToken request model
 *
 * Fields:
 * <ul>
 * <li>SellerId: string</li>
 * <li>NextToken: string</li>
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
class Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Request_ListOrdersByNextToken extends Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'SellerId' => array('FieldValue' => null, 'FieldType' => 'string'),
            'NextToken' => array('FieldValue' => null, 'FieldType' => 'string')
        );
        parent::__construct($data);
    }

    public function convertToQueryString() {
        $params = array();
        $params['Action'] = 'ListOrdersByNextToken';
        if ($this->issetSellerId()) {
            $params['SellerId'] = $this->getSellerId();
        }
        if ($this->issetNextToken()) {
            $params['NextToken'] = $this->getNextToken();
        }
        return $params;
    }

}
