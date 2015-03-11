<?php

/**
 * Instant Order Processing Notification API: BuyerInfo data type model
 *
 * Fields:
 * <ul>
 * <li>BuyerName: string</li>
 * <li>BuyerEmailAddress: string</li>
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
class Creativestyle_CheckoutByAmazon_Model_Api_Model_Iopn_BuyerInfo extends Creativestyle_CheckoutByAmazon_Model_Api_Model_Iopn_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'BuyerName' => array('FieldValue' => null, 'FieldType' => 'string'),
            'BuyerEmailAddress' => array('FieldValue' => null, 'FieldType' => 'string')
        );
        parent::__construct($data);
    }

}
