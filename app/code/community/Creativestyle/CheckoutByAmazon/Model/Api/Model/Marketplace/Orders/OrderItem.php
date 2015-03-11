<?php

/**
 * Amazon Marketplace Orders API: OrderItem data type model
 *
 * Fields:
 * <ul>
 * <li>ASIN: string</li>
 * <li>SellerSKU: string</li>
 * <li>OrderItemId: string</li>
 * <li>Title: string</li>
 * <li>QuantityOrdered: int</li>
 * <li>QuantityShipped: int</li>
 * <li>ItemPrice: Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Money</li>
 * <li>ShippingPrice: Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Money</li>
 * <li>GiftWrapPrice: Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Money</li>
 * <li>ItemTax: Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Money</li>
 * <li>ShippingTax: Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Money</li>
 * <li>GiftWrapTax: Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Money</li>
 * <li>ShippingDiscount: Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Money</li>
 * <li>PromotionDiscount: Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Money</li>
 * <li>PromotionIds: Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_PromotionIdList</li>
 * <li>CODFee: Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Money</li>
 * <li>CODFeeDiscount: Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Money</li>
 * <li>GiftMessageText: string</li>
 * <li>GiftWrapLevel: string</li>
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
class Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_OrderItem extends Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'ASIN' => array('FieldValue' => null, 'FieldType' => 'string'),
            'SellerSKU' => array('FieldValue' => null, 'FieldType' => 'string'),
            'OrderItemId' => array('FieldValue' => null, 'FieldType' => 'string'),
            'Title' => array('FieldValue' => null, 'FieldType' => 'string'),
            'QuantityOrdered' => array('FieldValue' => null, 'FieldType' => 'int'),
            'QuantityShipped' => array('FieldValue' => null, 'FieldType' => 'int'),
            'ItemPrice' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Money'),
            'ShippingPrice' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Money'),
            'GiftWrapPrice' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Money'),
            'ItemTax' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Money'),
            'ShippingTax' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Money'),
            'GiftWrapTax' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Money'),
            'ShippingDiscount' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Money'),
            'PromotionDiscount' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Money'),
            'PromotionIds' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_PromotionIdList'),
            'CODFee' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Money'),
            'CODFeeDiscount' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Money'),
            'GiftMessageText' => array('FieldValue' => null, 'FieldType' => 'string'),
            'GiftWrapLevel' => array('FieldValue' => null, 'FieldType' => 'string')
        );
        parent::__construct($data);
    }

}
