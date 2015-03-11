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
interface Creativestyle_CheckoutByAmazon_Model_Api_Interface_Checkout {

    /**
     * This function calls createPurchaseContract API and returns the PurchaseContract ID.
     * If you use this API, you must pass the Purchase Contract ID as input to the InlineCheckoutWidget.
     * If no Purchase Contract ID is passed to the InlineCheckoutWidget, the widget will always create
     * and return a new Purchase Contract ID.For most cases, you don't need to use this API. A 
     * Purchase Contract ID will be returned to you from the InlineCheckoutWidget, which you can then
     * use with the other APIs.
     */
    public function createPurchaseContract();

    /**
     * This function calls getPurchaseContract API and returns the Address List.
     * It returns the Destination information if the buyer selected an address. This can be used if we need to
     * calculate Promotions and Shipping charges based on the address selected by the user
     */
    public function getAddress($purchaseContractId);

    /**
     * This function calls SetContractCharges API. You can use this API to set shipping
     * or promotion amounts for the entire purchase contract.After the purchase contract is 
     * completed (that is, the order is placed), the contract charges are distributed to each item
     * proportional to the item's cost (that is, the item's unit price multiplied by the quantity).
     * However, the sum of charge amounts distributed to each item will be equal to the contract charge 
     * amount you set with this API.Please refer to the sample codes to see how this can be set.
     * There can be following valid sets of input charge elements to this API:<br />
     * Shipping + Promotion<br />
     * Shipping + No Promotion<br />
     * No Shipping + Promotion
     */
    public function setContractCharges($purchaseContractId, $charges);

    /**
     * This function calls SetPurchaseItems API.The SetPurchaseItems API will take the list
     * of order items as input. You can specify the order total (that is, the
     * amount the buyer will be charged for the entire order) broken down to each item as part of this
     * API call. Please look at the sample code to see how each value can be set for the Item
     */
    public function setItems($purchaseContractId, $itemList);

    /**
     * This function is used to complete the order after setting the Items and contract charges or just the Items only.
     * This API transforms the purchase contract into Checkout by Amazon orders and
     * returns you a list of Checkout by Amazon Order IDs.
     */
    public function completeOrder($purchaseContractId);

}
