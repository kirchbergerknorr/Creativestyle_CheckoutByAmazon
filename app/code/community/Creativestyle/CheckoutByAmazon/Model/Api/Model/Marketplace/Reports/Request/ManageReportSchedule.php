<?php

/**
 * Amazon Marketplace Reports API: ManageReportSchedule request model
 *
 * Fields:
 * <ul>
 * <li>Marketplace: string</li>
 * <li>Merchant: string</li>
 * <li>ReportType: string</li>
 * <li>Schedule: string</li>
 * <li>ScheduledDate: DateTime</li>
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
class Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Request_ManageReportSchedule extends Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'Marketplace' => array('FieldValue' => null, 'FieldType' => 'string'),
            'Merchant' => array('FieldValue' => null, 'FieldType' => 'string'),
            'ReportType' => array('FieldValue' => null, 'FieldType' => 'string'),
            'Schedule' => array('FieldValue' => null, 'FieldType' => 'string'),
            'ScheduledDate' => array('FieldValue' => null, 'FieldType' => 'DateTime')
        );
        parent::__construct($data);
    }

    public function convertToQueryString() {
        $params = array();
        $params['Action'] = 'ManageReportSchedule';
        if ($this->issetMarketplace()) {
            $params['Marketplace'] = $this->getMarketplace();
        }
        if ($this->issetMerchant()) {
            $params['Merchant'] = $this->getMerchant();
        }
        if ($this->issetReportType()) {
            $params['ReportType'] = $this->getReportType();
        }
        if ($this->issetSchedule()) {
            $params['Schedule'] = $this->getSchedule();
        }
        if ($this->issetScheduledDate()) {
            $params['ScheduledDate'] = $this->getScheduledDate();
        }
        return $params;
    }

}
