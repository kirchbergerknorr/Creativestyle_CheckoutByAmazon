<?php

/**
 * Amazon Marketplace Reports API: UpdateReportAcknowledgements result model
 *
 * Fields:
 * <ul>
 * <li>Count: int</li>
 * <li>ReportInfo: Array<Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_ReportInfo></li>
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
class Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Result_UpdateReportAcknowledgements extends Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'Count' => array('FieldValue' => null, 'FieldType' => 'int'),
            'ReportInfo' => array('FieldValue' => null, 'FieldType' => array('Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_ReportInfo'))
        );
        parent::__construct($data);
    }

}
