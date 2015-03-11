<?php

/**
 * Amazon Marketplace Reports API: ReportInfo data type model
 *
 * Fields:
 * <ul>
 * <li>ReportId: string</li>
 * <li>ReportType: string</li>
 * <li>ReportRequestId: string</li>
 * <li>AvailableDate: DateTime</li>
 * <li>Acknowledged: bool</li>
 * <li>AcknowledgedDate: DateTime</li>
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
class Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_ReportInfo extends Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'ReportId' => array('FieldValue' => null, 'FieldType' => 'string'),
            'ReportType' => array('FieldValue' => null, 'FieldType' => 'string'),
            'ReportRequestId' => array('FieldValue' => null, 'FieldType' => 'string'),
            'AvailableDate' => array('FieldValue' => null, 'FieldType' => 'DateTime'),
            'Acknowledged' => array('FieldValue' => null, 'FieldType' => 'bool'),
            'AcknowledgedDate' => array('FieldValue' => null, 'FieldType' => 'DateTime')
        );
        parent::__construct($data);
    }

}
