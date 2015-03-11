<?php

/**
 * Amazon Marketplace Feeds API: GetFeedSubmissionCount request model
 *
 * Fields:
 * <ul>
 * <li>Marketplace: string</li>
 * <li>Merchant: string</li>
 * <li>FeedTypeList: Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Feeds_TypeList</li>
 * <li>FeedProcessingStatusList: Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Feeds_StatusList</li>
 * <li>SubmittedFromDate: DateTime</li>
 * <li>SubmittedToDate: DateTime</li>
 * </ul>
 *
 * @category   Creativestyle
 * @package    Creativestyle_CheckoutByAmazon
 * @copyright  Copyright (c) 2011 - 2013 creativestyle GmbH (http://www.creativestyle.de)
 * @author     Marek Zabrowarny / creativestyle GmbH <amazon@creativestyle.de>
 */
class Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Feeds_Request_GetFeedSubmissionCount extends Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Feeds_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'Marketplace' => array('FieldValue' => null, 'FieldType' => 'string'),
            'Merchant' => array('FieldValue' => null, 'FieldType' => 'string'),
            'FeedTypeList' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Feeds_TypeList'),
            'FeedProcessingStatusList' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Feeds_StatusList'),
            'SubmittedFromDate' => array('FieldValue' => null, 'FieldType' => 'DateTime'),
            'SubmittedToDate' => array('FieldValue' => null, 'FieldType' => 'DateTime')
        );
        parent::__construct($data);
    }

    public function convertToQueryString() {
        $params = array();
        $params['Action'] = 'GetFeedSubmissionCount';
        if ($this->issetMarketplace()) {
            $params['Marketplace'] = $this->getMarketplace();
        }
        if ($this->issetMerchant()) {
            $params['Merchant'] = $this->getMerchant();
        }
        if ($this->issetFeedTypeList()) {
            $feedTypeList = $this->getFeedTypeList();
            $feedTypeListIndex = 1;
            foreach ($feedTypeList->getType() as $type) {
                $params['FeedTypeList.Type.' . $feedTypeListIndex] = $type;
                $feedTypeListIndex++;
            }
        }
        if ($this->issetFeedProcessingStatusList()) {
            $feedProcessingStatusList = $this->getFeedProcessingStatusList();
            $feedProcessingStatusListIndex = 1;
            foreach ($feedProcessingStatusList->getStatus() as $status) {
                $params['FeedProcessingStatusList.Status.' . $feedProcessingStatusListIndex] = $status;
                $feedProcessingStatusListIndex++;
            }
        }
        if ($this->issetSubmittedFromDate()) {
            $params['SubmittedFromDate'] = $this->getSubmittedFromDate();
        }
        if ($this->issetSubmittedToDate()) {
            $params['SubmittedToDate'] = $this->getSubmittedToDate();
        }
        return $params;
    }

}
