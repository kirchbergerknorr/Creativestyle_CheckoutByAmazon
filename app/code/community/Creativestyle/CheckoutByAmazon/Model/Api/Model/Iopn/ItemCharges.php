<?php

/**
 * Instant Order Processing Notification API: ItemCharges data type model
 *
 * Fields:
 * <ul>
 * <li>Component: Array<Creativestyle_CheckoutByAmazon_Model_Api_Model_Iopn_ChargeComponent></li>
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
class Creativestyle_CheckoutByAmazon_Model_Api_Model_Iopn_ItemCharges extends Creativestyle_CheckoutByAmazon_Model_Api_Model_Iopn_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'Component' => array('FieldValue' => null, 'FieldType' => array('Creativestyle_CheckoutByAmazon_Model_Api_Model_Iopn_ChargeComponent'))
        );
        parent::__construct($data);
    }

}
