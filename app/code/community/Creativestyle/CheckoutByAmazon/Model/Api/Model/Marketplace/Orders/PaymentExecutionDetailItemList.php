<?php

/**
 * Amazon Marketplace Orders API: PaymentExecutionDetailItemList data type model
 *
 * Fields:
 * <ul>
 * <li>PaymentExecutionDetailItem: Array<Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_PaymentExecutionDetailItem></li>
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
class Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_PaymentExecutionDetailItemList extends Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'PaymentExecutionDetailItem' => array('FieldValue' => null, 'FieldType' => array('Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Orders_PaymentExecutionDetailItem'))
        );
        parent::__construct($data);
    }

}
