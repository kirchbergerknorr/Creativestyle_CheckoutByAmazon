<?php

/**
 * Amazon Checkout API: GetPurchaseContract request model
 *
 * Fields:
 * <ul>
 * <li>PurchaseContractId: string</li>
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
class Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_Request_GetPurchaseContract extends Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'PurchaseContractId' => array('FieldValue' => null, 'FieldType' => 'string')
        );
        parent::__construct($data);
    }

    public function convertToQueryString() {
        $params = array();
        $params['Action'] = 'GetPurchaseContract';
        if ($this->issetPurchaseContractId()) {
            $params['PurchaseContractId'] =  $this->getPurchaseContractId();
        }
        return $params;
    }

}
