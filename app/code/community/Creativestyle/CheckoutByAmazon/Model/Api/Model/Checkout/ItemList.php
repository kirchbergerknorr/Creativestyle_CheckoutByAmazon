<?php

/**
 * Amazon Checkout API: ItemList data type model
 *
 * Fields:
 * <ul>
 * <li>PurchaseItem: Array<Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_PurchaseItem></li>
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
class Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_ItemList extends Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'PurchaseItem' => array('FieldValue' => null, 'FieldType' => array('Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_PurchaseItem'))
        );
        parent::__construct($data);
    }

    /**
     * Add the item with merchantItemId as index
     *
     * @param mixed PurchaseItem or an array of PurchaseItem PurchaseItem
     * @return this instance
     */
    public function addItem($purchaseItem) {
        $merchantItemID = $purchaseItem->_fields['MerchantItemId']['FieldValue'];
        $this->_fields['PurchaseItem']['FieldValue'][$merchantItemID] = $purchaseItem;
    }

}
