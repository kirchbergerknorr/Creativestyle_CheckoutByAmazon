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
abstract class Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Abstract extends Creativestyle_CheckoutByAmazon_Model_Api_Model_Abstract {

    const REPORT_TYPE_ORDERS_DATA   = '_GET_ORDERS_DATA_';

    const SCHEDULE_15_MINUTES       = '_15_MINUTES_';
    const SCHEDULE_30_MINUTES       = '_30_MINUTES_';
    const SCHEDULE_1_HOUR           = '_1_HOUR_';
    const SCHEDULE_2_HOURS          = '_2_HOURS_';
    const SCHEDULE_4_HOURS          = '_4_HOURS_';
    const SCHEDULE_8_HOURS          = '_8_HOURS_';
    const SCHEDULE_12_HOURS         = '_12_HOURS_';
    const SCHEDULE_1_DAY            = '_1_DAY_';
    const SCHEDULE_2_DAYS           = '_2_DAYS_';
    const SCHEDULE_3_DAYS           = '_72_HOURS_';
    const SCHEDULE_7_DAYS           = '_7_DAYS_';
    const SCHEDULE_14_DAYS          = '_14_DAYS_';
    const SCHEDULE_15_DAYS          = '_15_DAYS_';
    const SCHEDULE_30_DAYS          = '_30_DAYS_';
    const SCHEDULE_NEVER            = '_NEVER_';

    protected
        $_area = 'Amazon MWS Reports';

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
        return self::getConfigData('api_namespace', array('api' => 'mws_reports'));
    }

    public static function getSchedules() {
        return array(
            self::SCHEDULE_15_MINUTES   => '15 minutes',
            self::SCHEDULE_30_MINUTES   => '30 minutes',
            self::SCHEDULE_1_HOUR       => '1 hour',
            self::SCHEDULE_2_HOURS      => '2 hours',
            self::SCHEDULE_4_HOURS      => '4 hours',
            self::SCHEDULE_8_HOURS      => '8 hours',
            self::SCHEDULE_12_HOURS     => '12 hours',
            self::SCHEDULE_1_DAY        => '1 day',
            self::SCHEDULE_2_DAYS       => '2 days',
            self::SCHEDULE_3_DAYS       => '3 days',
            self::SCHEDULE_7_DAYS       => '7 days',
            self::SCHEDULE_14_DAYS      => '14 days',
            self::SCHEDULE_15_DAYS      => '15 days',
            self::SCHEDULE_30_DAYS      => '30 days',
            self::SCHEDULE_NEVER        => 'Never'
        );
    }

    public static function getReportTypes() {
        return array(
            self::REPORT_TYPE_ORDERS_DATA   => 'Scheduled XML Order Report'
        );
    }

}
