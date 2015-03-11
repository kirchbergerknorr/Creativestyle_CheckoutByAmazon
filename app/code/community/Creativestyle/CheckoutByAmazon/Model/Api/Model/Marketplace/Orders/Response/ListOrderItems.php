<?php

/**
 * Amazon Marketplace Orders API: ListOrderItems response model
 *
 * Fields:
 * <ul>
 * <li>ListOrderItemsResult: Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Result_ListOrderItems</li>
 * <li>ResponseMetadata: Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_ResponseMetadata</li>
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
class Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Response_ListOrderItems extends Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'ListOrderItemsResult' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Result_ListOrderItems'),
            'ResponseMetadata' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_ResponseMetadata')
        );
        parent::__construct($data);
    }

    /**
     * Construct Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Response_ListOrderItems from XML string
     *
     * @param string $xml XML string to construct from
     * @return Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Response_ListOrderItems
     */
    public static function fromXML($xml) {
        $dom = new DOMDocument();
        $dom->loadXML($xml);
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('a', self::getConfigData('api_namespace', array('api' => 'mws_orders')));
        $response = $xpath->query('//a:ListOrderItemsResponse');
        if ($response->length == 1) {
            return new Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Response_ListOrderItems($response->item(0));
        } else {
            Mage::helper('checkoutbyamazon')->throwException(
                Mage::helper('checkoutbyamazon')->__('Unable to construct %s from provided XML. Make sure that %s is a root element.', 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Response_ListOrderItems', 'ListOrderItemsResponse'),
                null,
                array('area' => 'Amazon MWS Orders')
            );
        }
    }

}
