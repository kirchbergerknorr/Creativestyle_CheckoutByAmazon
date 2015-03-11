<?php

/**
 * Copyright 2011 Amazon.com, Inc. or its affiliates. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 *
 * You may not use this file except in compliance with the License.
 * You may obtain a copy of the License at: http://aws.amazon.com/apache2.0
 * This file is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR
 * CONDITIONS OF ANY KIND, either express or implied. See the License for the
 * specific language governing permissions and limitations under the License.
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
interface Creativestyle_CheckoutByAmazon_Model_Api_Interface_Marketplace_Reports {

    /**
     * Get Report 
     * The GetReport operation returns the contents of a report. Reports can potentially be
     * very large (>100MB) which is why we only return one report at a time, and in a
     * streaming fashion.
     */
    public function getReport($reportId);

    /**
     * Get Report Schedule Count 
     * returns the number of report schedules
     */
    public function getReportScheduleCount($request);

    /**
     * Get Report Request List By Next Token 
     * retrieve the next batch of list items and if there are more items to retrieve
     */
    public function getReportRequestListByNextToken($request);

    /**
     * Update Report Acknowledgements 
     * The UpdateReportAcknowledgements operation updates the acknowledged status of one or more reports.
     */
    public function updateReportAcknowledgements(array $reportIdList, $acknowledged = true);

    /**
     * Get Report Count 
     * returns a count of reports matching your criteria;
     * by default, the number of reports generated in the last 90 days,
     * regardless of acknowledgement status
     */
    public function getReportCount($request);

    /**
     * Request Report 
     * requests the generation of a report
     */
    public function requestReport($request);

    /**
     * Cancel Report Requests 
     * cancels report requests that have not yet started processing,
     * by default all those within the last 90 days
     */
    public function cancelReportRequests($request);

    /**
     * Get Report List 
     * returns a list of reports; by default the most recent ten reports,
     * regardless of their acknowledgement status
     */
    public function getReportList();

    /**
     * Get Report Request List 
     * returns a list of report requests ids and their associated metadata
     */

    public function getReportRequestList();

    /**
     * Get Report Schedule List By Next Token 
     * retrieve the next batch of list items and if there are more items to retrieve
     */
    public function getReportScheduleListByNextToken($request);

    /**
     * Get Report List By Next Token 
     * retrieve the next batch of list items and if there are more items to retrieve
     */
    public function getReportListByNextToken($token);

    /**
     * Manage Report Schedule 
     * Creates, updates, or deletes a report schedule
     * for a given report type, such as order reports in particular.
     */
    public function manageReportSchedule($schedule);

    /**
     * Get Report Request Count 
     * returns a count of report requests; by default all the report
     * requests in the last 90 days
     */
    public function getReportRequestCount($request);

    /**
     * Get Report Schedule List 
     * returns the list of report schedules
     */
    public function getReportScheduleList($request);

}
