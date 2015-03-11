<?php

/**
 * Copyright 2011 Amazon.com, Inc. or its affiliates. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 *
 * You may not use this file except in compliance with the License.
 * You may obtain a copy of the License at: http://aws.amazon.com/apache2.0
 * This file is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR
 * CONDITIONS OF ANY KIND, either express or implied. See the License for the
 * specific language governing permissions and limitations under the License.
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
interface Creativestyle_CheckoutByAmazon_Model_Api_Interface_Marketplace_Orders {

    /**
     * List Orders By Next Token 
     * If ListOrders returns a nextToken, thus indicating that there are more orders
     * than returned that matched the given filter criteria, ListOrdersByNextToken
     * can be used to retrieve those other orders using that nextToken.
     */
    public function listOrdersByNextToken($request);

    /**
     * List Order Items By Next Token 
     * If ListOrderItems cannot return all the order items in one go, it will
     * provide a nextToken.  That nextToken can be used with this operation to
     * retrive the next batch of items for that order.
     */
    public function listOrderItemsByNextToken($request);

    /**
     * Get Order 
     * This operation takes up to 50 order ids and returns the corresponding orders.
     */
    public function getOrder(array $orderIdList);

    /**
     * List Order Items 
     * This operation can be used to list the items of the order indicated by the
     * given order id (only a single Amazon order id is allowed).
     */
    public function listOrderItems($request);

    /**
     * List Orders 
     * ListOrders can be used to find orders that meet the specified criteria.
     */
    public function listOrders($request);

    /**
     * Get Service Status 
     * Returns the service status of a particular MWS API section. The operation
     * takes no input.
     * All API sections within the API are required to implement this operation.
     */
    public function getServiceStatus($request);

}
