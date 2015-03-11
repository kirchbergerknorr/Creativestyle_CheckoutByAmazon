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
abstract class Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Feeds_Abstract extends Creativestyle_CheckoutByAmazon_Model_Api_Model_Abstract {

    const FEED_TYPE_POST_PRODUCT_DATA                               = '_POST_PRODUCT_DATA_';
    const FEED_TYPE_POST_PRODUCT_RELATIONSHIP_DATA                  = '_POST_PRODUCT_RELATIONSHIP_DATA_';
    const FEED_TYPE_POST_ITEM_DATA                                  = '_POST_ITEM_DATA_';
    const FEED_TYPE_POST_PRODUCT_OVERRIDES_DATA                     = '_POST_PRODUCT_OVERRIDES_DATA_';
    const FEED_TYPE_POST_PRODUCT_IMAGE_DATA                         = '_POST_PRODUCT_IMAGE_DATA_';
    const FEED_TYPE_POST_PRODUCT_PRICING_DATA                       = '_POST_PRODUCT_PRICING_DATA_';
    const FEED_TYPE_POST_INVENTORY_AVAILABILITY_DATA                = '_POST_INVENTORY_AVAILABILITY_DATA_';
    const FEED_TYPE_POST_ORDER_ACKNOWLEDGEMENT_DATA                 = '_POST_ORDER_ACKNOWLEDGEMENT_DATA_';
    const FEED_TYPE_POST_ORDER_FULFILLMENT_DATA                     = '_POST_ORDER_FULFILLMENT_DATA_';
    const FEED_TYPE_POST_PAYMENT_ADJUSTMENT_DATA                    = '_POST_PAYMENT_ADJUSTMENT_DATA_';
    const FEED_TYPE_POST_FLAT_FILE_LISTINGS_DATA                    = '_POST_FLAT_FILE_LISTINGS_DATA_';
    const FEED_TYPE_POST_FLAT_FILE_ORDER_ACKNOWLEDGEMENT_DATA       = '_POST_FLAT_FILE_ORDER_ACKNOWLEDGEMENT_DATA_';
    const FEED_TYPE_POST_FLAT_FILE_FULFILLMENT_DATA                 = '_POST_FLAT_FILE_FULFILLMENT_DATA_';
    const FEED_TYPE_POST_FLAT_FILE_PAYMENT_ADJUSTMENT_DATA          = '_POST_FLAT_FILE_PAYMENT_ADJUSTMENT_DATA_';
    const FEED_TYPE_POST_FLAT_FILE_INVLOADER_DATA                   = '_POST_FLAT_FILE_INVLOADER_DATA_';

    const ADJUSTMENT_REASON_NO_INVENTORY                            = 'NoInventory';
    const ADJUSTMENT_REASON_SHIPPING_ADDRESS_UNDELIVERABLE          = 'ShippingAddressUndeliverable';
    const ADJUSTMENT_REASON_CUSTOMER_EXCHANGE                       = 'CustomerExchange';
    const ADJUSTMENT_REASON_BUYER_CANCELED                          = 'BuyerCanceled';
    const ADJUSTMENT_REASON_GENERAL_ADJUSTMENT                      = 'GeneralAdjustment';
    const ADJUSTMENT_REASON_CARRIER_CREDIT_DECISION                 = 'CarrierCreditDecision';
    const ADJUSTMENT_REASON_RISK_ASSESSMENT_INFORMATION_NOT_VALID   = 'RiskAssessmentInformationNotValid';
    const ADJUSTMENT_REASON_CARRIER_COVERAGE_FAILURE                = 'CarrierCoverageFailure';
    const ADJUSTMENT_REASON_CUSTOMER_RETURN                         = 'CustomerReturn';
    const ADJUSTMENT_REASON_MERCHANDISE_NOT_RECEIVED                = 'MerchandiseNotReceived';
    const ADJUSTMENT_REASON_COULD_NOT_SHIP                          = 'CouldNotShip';
    const ADJUSTMENT_REASON_DIFFERENT_ITEM                          = 'DifferentItem';
    const ADJUSTMENT_REASON_ABANDONED                               = 'Abandoned';
    const ADJUSTMENT_REASON_CUSTOMER_CANCEL                         = 'CustomerCancel';
    const ADJUSTMENT_REASON_PRICE_ERROR                             = 'PriceError';
    const ADJUSTMENT_REASON_PRODUCT_OUT_OF_STOCK                    = 'ProductOutofStock';
    const ADJUSTMENT_REASON_CUSTOMER_ADDRESS_INCORRECT              = 'CustomerAddressIncorrect';
    const ADJUSTMENT_REASON_EXCAHNGE                                = 'Exchange';
    const ADJUSTMENT_REASON_OTHER                                   = 'Other';
    const ADJUSTMENT_REASON_TRANSACTION_RECORD                      = 'TransactionRecord';

    protected
        $_area = 'Amazon MWS Feeds';

    protected function _prepareInput($data = null) {
        if (is_array($data) || is_null($data)) {
            if (!isset($data['Merchant'])) $data['Merchant'] = self::getConfigData('merchant_id');
            if (!isset($data['Marketplace'])) $data['Marketplace'] = self::getConfigData('marketplace_id');
        }
        return $data;
    }

    public function __construct($data = null) {
        parent::__construct($this->_prepareInput($data));
    }

    protected function _getNamespace() {
        return self::getConfigData('api_namespace', array('api' => 'mws_feeds'));
    }

    public static function getFeedTypes() {
        return array(
            self::FEED_TYPE_POST_ORDER_ACKNOWLEDGEMENT_DATA => 'Order Acknowledgement',
            self::FEED_TYPE_POST_ORDER_FULFILLMENT_DATA     => 'Order Fulfillment',
            self::FEED_TYPE_POST_PAYMENT_ADJUSTMENT_DATA    => 'Order Adjustment'
        );
    }

    public static function getFeedProcessingStatuses() {
        return array(
            '_SUBMITTED_'   => 'Submitted',
            '_IN_PROGRESS_' => 'In progress',
            '_CANCELLED_'   => 'Cancelled',
            '_DONE_'        => 'Done'
        );
    }

}
