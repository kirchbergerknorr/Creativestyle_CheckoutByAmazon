<?php

/**
 * Amazon Marketplace Reports API: GetReportRequestCount request model
 *
 * Fields:
 * <ul>
 * <li>Marketplace: string</li>
 * <li>Merchant: string</li>
 * <li>ReportTypeList: Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_TypeList</li>
 * <li>ReportProcessingStatusList: Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_StatusList</li>
 * <li>RequestedFromDate: DateTime</li>
 * <li>RequestedToDate: DateTime</li>
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
class Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Request_GetReportRequestCount extends Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'Marketplace' => array('FieldValue' => null, 'FieldType' => 'string'),
            'Merchant' => array('FieldValue' => null, 'FieldType' => 'string'),
            'ReportTypeList' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_TypeList'),
            'ReportProcessingStatusList' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_StatusList'),
            'RequestedFromDate' => array('FieldValue' => null, 'FieldType' => 'DateTime'),
            'RequestedToDate' => array('FieldValue' => null, 'FieldType' => 'DateTime')
        );
        parent::__construct($data);
    }

    public function convertToQueryString() {
        $params = array();
        $params['Action'] = 'GetReportRequestCount';
        if ($this->issetMarketplace()) {
            $params['Marketplace'] = $this->getMarketplace();
        }
        if ($this->issetMerchant()) {
            $params['Merchant'] = $this->getMerchant();
        }
        if ($this->issetReportTypeList()) {
            $reportTypeList = $this->getReportTypeList();
            $reportTypeListIndex = 1;
            foreach ($reportTypeList->getType() as $type) {
                $params['ReportTypeList.Type.' . $reportTypeListIndex] = $type;
                $reportTypeListIndex++;
            }
        }
        if ($this->issetReportProcessingStatusList()) {
            $reportProcessingStatusList = $this->getReportProcessingStatusList();
            $reportProcessingStatusListIndex = 1;
            foreach ($reportProcessingStatusList->getStatus() as $status) {
                $params['ReportProcessingStatusList.Status.' . $reportProcessingStatusListIndex] = $status;
                $reportProcessingStatusListIndex++;
            }
        }
        if ($this->issetRequestedFromDate()) {
            $params['RequestedFromDate'] = $this->getRequestedFromDate();
        }
        if ($this->issetRequestedToDate()) {
            $params['RequestedToDate'] = $this->getRequestedToDate();
        }
        return $params;
    }

}
