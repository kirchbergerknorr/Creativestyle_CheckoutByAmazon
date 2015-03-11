<?php

/**
 * Amazon Marketplace Reports API: GetReportList request model
 *
 * Fields:
 * <ul>
 * <li>Marketplace: string</li>
 * <li>Merchant: string</li>
 * <li>MaxCount: string</li>
 * <li>ReportTypeList: Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_TypeList</li>
 * <li>Acknowledged: bool</li>
 * <li>AvailableFromDate: DateTime</li>
 * <li>AvailableToDate: DateTime</li>
 * <li>ReportRequestIdList: Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_IdList</li>
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
class Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Request_GetReportList extends Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'Marketplace' => array('FieldValue' => null, 'FieldType' => 'string'),
            'Merchant' => array('FieldValue' => null, 'FieldType' => 'string'),
            'MaxCount' => array('FieldValue' => null, 'FieldType' => 'string'),
            'ReportTypeList' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_TypeList'),
            'Acknowledged' => array('FieldValue' => null, 'FieldType' => 'bool'),
            'AvailableFromDate' => array('FieldValue' => null, 'FieldType' => 'DateTime'),
            'AvailableToDate' => array('FieldValue' => null, 'FieldType' => 'DateTime'),
            'ReportRequestIdList' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_IdList')
        );
        parent::__construct($data);
    }

    public function convertToQueryString() {
        $params = array();
        $params['Action'] = 'GetReportList';
        if ($this->issetMarketplace()) {
            $params['Marketplace'] = $this->getMarketplace();
        }
        if ($this->issetMerchant()) {
            $params['Merchant'] = $this->getMerchant();
        }
        if ($this->issetMaxCount()) {
            $params['MaxCount'] = $this->getMaxCount();
        }
        if ($this->issetReportTypeList()) {
            $reportTypeList = $this->getReportTypeList();
            $reportTypeListIndex = 1;
            foreach ($reportTypeList->getType() as $type) {
                $params['ReportTypeList.Type.' . $reportTypeListIndex] = $type;
                $reportTypeListIndex++;
            }
        }
        if ($this->issetAcknowledged()) {
            $params['Acknowledged'] = $this->getAcknowledged() ? 'true' : 'false';
        }
        if ($this->issetAvailableFromDate()) {
            $params['AvailableFromDate'] = $this->getAvailableFromDate();
        }
        if ($this->issetAvailableToDate()) {
            $params['AvailableToDate'] = $this->getAvailableToDate();
        }
        if ($this->issetReportRequestIdList()) {
            $reportRequestIdList = $this->getReportRequestIdList();
            $reportRequestIdListIndex = 1;
            foreach ($reportRequestIdList->getId() as $id) {
                $params['ReportRequestIdList.Id.' . $reportRequestIdListIndex] = $id;
                $reportRequestIdListIndex++;
            }
        }
        return $params;
    }

}
