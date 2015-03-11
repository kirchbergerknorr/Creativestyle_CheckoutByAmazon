<?php

/**
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
class Creativestyle_CheckoutByAmazon_Model_Api_Marketplace_Orders
    extends Creativestyle_CheckoutByAmazon_Model_Api_Abstract
    implements Creativestyle_CheckoutByAmazon_Model_Api_Interface_Marketplace_Orders {

    protected $_area = 'Amazon MWS Orders';

    public function listOrdersByNextToken($request) {

    }

    public function listOrderItemsByNextToken($request) {

    }

    public function getOrder(array $orderIdList) {
        $request = $this->_getApiModel('request_getOrder', array(
            'AmazonOrderId' => array('Id' => $orderIdList)
        ));
        $response = $this->_getApiClient()->getOrder($request);
        if ($response->issetGetOrderResult()) {
            $getOrderResult = $response->getGetOrderResult();
            if ($getOrderResult->issetOrders()) {
                $orders = $getOrderResult->getOrders();
                if ($orders->issetOrder()) {
                    return $orders->getOrder();
                }
            }
        }
        return null;
    }

    public function listOrderItems($request) {

    }

    public function listOrders($request) {

    }

    public function getServiceStatus($request) {}

}
