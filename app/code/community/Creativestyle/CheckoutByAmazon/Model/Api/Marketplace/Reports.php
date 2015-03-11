<?php

/**
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
class Creativestyle_CheckoutByAmazon_Model_Api_Marketplace_Reports
    extends Creativestyle_CheckoutByAmazon_Model_Api_Abstract
    implements Creativestyle_CheckoutByAmazon_Model_Api_Interface_Marketplace_Reports {

    protected $_area = 'Amazon MWS Reports';

    public function getReport($reportId) {
        $request = $this->_getApiModel('request_getReport', array(
            'ReportId'  => $reportId
        ));
        return $this->_getApiClient()->processRequest($request);
    }

    public function getReportScheduleCount($request) {}

    public function getReportRequestListByNextToken($request) {}

    public function updateReportAcknowledgements(array $reportIdList, $acknowledged = true) {
        $request = $this->_getApiModel('request_updateReportAcknowledgements', array(
            'Acknowledged'  => $acknowledged,
            'ReportIdList'  => array(
                'Id' => $reportIdList
            )
        ));
        $response = $this->_getApiClient()->updateReportAcknowledgements($request);
        if ($response->issetUpdateReportAcknowledgementsResult()) {
            $updateReportAcknowledgementsResult = $response->getUpdateReportAcknowledgementsResult();
            return $updateReportAcknowledgementsResult;
        }
    }

    public function getReportCount($request) {}

    public function requestReport($request) {}

    public function cancelReportRequests($request) {}

    public function getReportList() {
        $request = $this->_getApiModel('request_getReportList', array(
            'Acknowledged'      => false,
            'ReportTypeList'    => array(
                'Type' => array(Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Abstract::REPORT_TYPE_ORDERS_DATA)
            )
        ));
        $response = $this->_getApiClient()->getReportList($request);
        if ($response->issetGetReportListResult()) {
            $getReportListResult = $response->getGetReportListResult();
            return $getReportListResult;
        }
    }

    public function getReportRequestList() {
        $request = $this->_getApiModel('request_getReportRequestList');
        $response = $this->_getApiClient()->getReportRequestList($request);
        if ($response->issetGetReportRequestListResult()) {
            $getReportRequestListResult = $response->getGetReportRequestListResult();
            return $getReportRequestListResult;
        }
    }

    public function getReportScheduleListByNextToken($request) {}

    public function getReportListByNextToken($token) {
        $request = $this->_getApiModel('request_getReportListByNextToken', array(
            'NextToken' => $token
        ));
        $response = $this->_getApiClient()->getReportListByNextToken($request);
        if ($response->issetGetReportListByNextTokenResult()) {
            $getReportListResult = $response->getGetReportListByNextTokenResult();
            return $getReportListResult;
        }
    }

    public function manageReportSchedule($schedule) {
        $request = $this->_getApiModel('request_manageReportSchedule', array(
            'ReportType'    => Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Abstract::REPORT_TYPE_ORDERS_DATA,
            'Schedule'      => $schedule
        ));
        $response = $this->_getApiClient()->manageReportSchedule($request);
        if ($response->issetManageReportScheduleResult()) {
            $manageReportScheduleResult = $response->getManageReportScheduleResult();
            return $manageReportScheduleResult;
        }
    }

    public function getReportRequestCount($request) {}

    public function getReportScheduleList($request) {}

}
