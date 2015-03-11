<?php

/**
 * Amazon Marketplace Reports API: ReportRequestInfo data type model
 *
 * Fields:
 * <ul>
 * <li>ReportRequestId: string</li>
 * <li>ReportType: string</li>
 * <li>StartDate: DateTime</li>
 * <li>EndDate: DateTime</li>
 * <li>Scheduled: bool</li>
 * <li>SubmittedDate: DateTime</li>
 * <li>ReportProcessingStatus: string</li>
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
class Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_ReportRequestInfo extends Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'ReportRequestId' => array('FieldValue' => null, 'FieldType' => 'string'),
            'ReportType' => array('FieldValue' => null, 'FieldType' => 'string'),
            'StartDate' => array('FieldValue' => null, 'FieldType' => 'DateTime'),
            'EndDate' => array('FieldValue' => null, 'FieldType' => 'DateTime'),
            'Scheduled' => array('FieldValue' => null, 'FieldType' => 'bool'),
            'SubmittedDate' => array('FieldValue' => null, 'FieldType' => 'DateTime'),
            'ReportProcessingStatus' => array('FieldValue' => null, 'FieldType' => 'string')
        );
        parent::__construct($data);
    }

}
