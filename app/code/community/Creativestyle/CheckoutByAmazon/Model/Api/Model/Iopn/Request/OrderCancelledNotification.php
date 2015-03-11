<?php

/**
 * Instant Order Processing Notification API: OrderCancelledNotification request model
 *
 * Fields:
 * <ul>
 * <li>NotificationReferenceId: string</li>
 * <li>ProcessedOrder: Creativestyle_CheckoutByAmazon_Model_Api_Model_Iopn_Order</li>
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
class Creativestyle_CheckoutByAmazon_Model_Api_Model_Iopn_Request_OrderCancelledNotification extends Creativestyle_CheckoutByAmazon_Model_Api_Model_Iopn_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'NotificationReferenceId' => array('FieldValue' => null, 'FieldType' => 'string'),
            'ProcessedOrder' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Iopn_Order')
        );
        parent::__construct($data);
    }

    /**
     * Construct Creativestyle_CheckoutByAmazon_Model_Api_Model_Iopn_Request_OrderCancelledNotification from XML string
     *
     * @param string $xml XML string to construct from
     * @return Creativestyle_CheckoutByAmazon_Model_Api_Model_Iopn_Request_OrderCancelledNotification
     */
    public static function fromXML($xml) {
        $dom = new DOMDocument();
        $dom->loadXML($xml);
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('a', self::getConfigData('api_namespace', array('api' => 'iopn')));
        $response = $xpath->query('//a:OrderCancelledNotification');
        if ($response->length == 1) {
            return new Creativestyle_CheckoutByAmazon_Model_Api_Model_Iopn_Request_OrderCancelledNotification($response->item(0));
        } else {
            Mage::helper('checkoutbyamazon')->throwException(
                Mage::helper('checkoutbyamazon')->__('Unable to construct %s from provided XML. Make sure that %s is a root element.', 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Iopn_Request_OrderCancelledNotification', 'OrderCancelledNotification'),
                null,
                array('area' => 'Amazon IOPN')    
            );
        }
    }

}
