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
interface Creativestyle_CheckoutByAmazon_Model_Api_Interface_Marketplace_Feeds {

    /**
     * Submit Feed 
     * Uploads a file for processing together with the necessary
     * metadata to process the file, such as which type of feed it is.
     * PurgeAndReplace if true means that your existing e.g. inventory is
     * wiped out and replace with the contents of this feed - use with
     * caution (the default is false).
     *   
     * @see http://docs.amazonwebservices.com/${docPath}SubmitFeed.html      
     * @param mixed $request array of parameters for MarketplaceWebService_Model_SubmitFeedRequest request
     * or MarketplaceWebService_Model_SubmitFeedRequest object itself
     * @see MarketplaceWebService_Model_SubmitFeedRequest
     * @return MarketplaceWebService_Model_SubmitFeedResponse MarketplaceWebService_Model_SubmitFeedResponse
     */
    public function submitFeed($feedType, $content, $contentMd5);

    /**
     * Get Feed Submission List By Next Token 
     * retrieve the next batch of list items and if there are more items to retrieve
     *   
     * @see http://docs.amazonwebservices.com/${docPath}GetFeedSubmissionListByNextToken.html      
     * @param mixed $request array of parameters for MarketplaceWebService_Model_GetFeedSubmissionListByNextTokenRequest request
     * or MarketplaceWebService_Model_GetFeedSubmissionListByNextTokenRequest object itself
     * @see MarketplaceWebService_Model_GetFeedSubmissionListByNextTokenRequest
     * @return MarketplaceWebService_Model_GetFeedSubmissionListByNextTokenResponse MarketplaceWebService_Model_GetFeedSubmissionListByNextTokenResponse
     *
     * @throws Creativestyle_CheckoutByAmazon_Exception
     */
    public function getFeedSubmissionListByNextToken($request);

    /**
     * Cancel Feed Submissions 
     * cancels feed submissions - by default all of the submissions of the
     * last 30 days that have not started processing
     *   
     * @see http://docs.amazonwebservices.com/${docPath}CancelFeedSubmissions.html      
     * @param mixed $request array of parameters for MarketplaceWebService_Model_CancelFeedSubmissionsRequest request
     * or MarketplaceWebService_Model_CancelFeedSubmissionsRequest object itself
     * @see MarketplaceWebService_Model_CancelFeedSubmissionsRequest
     * @return MarketplaceWebService_Model_CancelFeedSubmissionsResponse MarketplaceWebService_Model_CancelFeedSubmissionsResponse
     */
    public function cancelFeedSubmissions($request);

    /**
     * Get Feed Submission Count 
     * returns the number of feeds matching all of the specified criteria
     *   
     * @see http://docs.amazonwebservices.com/${docPath}GetFeedSubmissionCount.html      
     * @param mixed $request array of parameters for MarketplaceWebService_Model_GetFeedSubmissionCountRequest request
     * or MarketplaceWebService_Model_GetFeedSubmissionCountRequest object itself
     * @see MarketplaceWebService_Model_GetFeedSubmissionCountRequest
     * @return MarketplaceWebService_Model_GetFeedSubmissionCountResponse MarketplaceWebService_Model_GetFeedSubmissionCountResponse
     */
    public function getFeedSubmissionCount($request);

    /**
     * Get Feed Submission Result 
     * retrieves the feed processing report
     *   
     * @see http://docs.amazonwebservices.com/${docPath}GetFeedSubmissionResult.html      
     * @param mixed $request array of parameters for MarketplaceWebService_Model_GetFeedSubmissionResultRequest request
     * or MarketplaceWebService_Model_GetFeedSubmissionResultRequest object itself
     * @see MarketplaceWebService_Model_GetFeedSubmissionResultRequest
     * @return MarketplaceWebService_Model_GetFeedSubmissionResultResponse MarketplaceWebService_Model_GetFeedSubmissionResultResponse
     */
    public function getFeedSubmissionResult($feedSubmissionId);

    /**
     * Get Feed Submission List 
     * returns a list of feed submission identifiers and their associated metadata
     *   
     * @see http://docs.amazonwebservices.com/${docPath}GetFeedSubmissionList.html      
     * @param mixed $request array of parameters for MarketplaceWebService_Model_GetFeedSubmissionListRequest request
     * or MarketplaceWebService_Model_GetFeedSubmissionListRequest object itself
     * @see MarketplaceWebService_Model_GetFeedSubmissionListRequest
     * @return MarketplaceWebService_Model_GetFeedSubmissionListResponse MarketplaceWebService_Model_GetFeedSubmissionListResponse
     */
    public function getFeedSubmissionList();

}
