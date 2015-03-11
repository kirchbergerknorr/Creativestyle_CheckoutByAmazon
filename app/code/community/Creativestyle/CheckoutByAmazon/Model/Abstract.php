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

abstract class Creativestyle_CheckoutByAmazon_Model_Abstract {

    // general settings
    const XML_PATH_ACTIVE                   = 'checkoutbyamazon/general/active';
    const XML_PATH_IOPN_ACTIVE              = 'checkoutbyamazon/general/iopn_active';
    const XML_PATH_MARKETPLACE              = 'checkoutbyamazon/general/marketplace';
    const XML_PATH_EMAIL_PLACEHOLDER        = 'checkoutbyamazon/general/email_placeholder';
    const XML_PATH_DEFAULT_PREFIX           = 'checkoutbyamazon/general/default_prefix';
    const XML_PATH_WEIGHT_UNIT              = 'checkoutbyamazon/general/weight_unit';   // TB added
    const XML_PATH_CUT_SKU                  = 'checkoutbyamazon/general/cut_sku';
    const XML_PATH_SANDBOX_MODE             = 'checkoutbyamazon/general/sandbox_mode';
    const XML_PATH_NEW_ORDER_STATUS         = 'checkoutbyamazon/general/new_order_status';
    const XML_PATH_ORDER_CONFIRMATION       = 'checkoutbyamazon/general/order_confirmation';

    // authorization data
    const XML_PATH_MERCHANT_ID              = 'checkoutbyamazon/general/merchant_id';
    const XML_PATH_ACCESS_KEY_ID            = 'checkoutbyamazon/general/access_key';
    const XML_PATH_SECRET_KEY               = 'checkoutbyamazon/general/secret_key';

    // availability settings
    const XML_PATH_COUNTRY_ALLOW            = 'checkoutbyamazon/general/sallowspecific';
    const XML_PATH_SPECIFIC_COUNTRY         = 'checkoutbyamazon/general/specificcountry';

    // Amazon MWS settings
    const XML_PATH_MWS_ORDER_UPDATE         = 'checkoutbyamazon/api/order_update';
    const XML_PATH_REPORT_SCHEDULE          = 'checkoutbyamazon/api/report_schedule';
    const XML_PATH_MWS_USE_TOKEN            = 'checkoutbyamazon/api/use_token';
    const XML_PATH_FEED_BATCHING            = 'checkoutbyamazon/api/feed_batching';
    const XML_PATH_FEED_SCHEDULE            = 'checkoutbyamazon/api/feed_schedule';
    const XML_PATH_LAST_FEED_SENDING        = 'checkoutbyamazon/api/last_feed_sending';
    const XML_PATH_LAST_REPORT_PROCESSING   = 'checkoutbyamazon/api/last_report_processing';
    const XML_PATH_LAST_ORDER_PROCESSING    = 'checkoutbyamazon/api/last_order_processing';

    // debugging options
    const XML_PATH_ALLOWED_IPS              = 'checkoutbyamazon/general/allow_ips';
    const XML_PATH_LOGGER_ENABLED           = 'checkoutbyamazon/logger/active';
    const XML_PATH_LOGGER_CLEAN_AFTER       = 'checkoutbyamazon/logger/clean_after_day';

    const GLOBAL_CONFIG_CBA_NODE            = 'global/creativestyle/checkoutbyamazon';

    const CHECKOUT_METHOD_AMAZON            = 'amazon';

    const HMAC_SHA1_ALGORITHM               = 'sha1';
    const HMAC_SHA256_ALGORITHM             = 'sha256';

    protected $_modulePrefix = 'checkoutbyamazon';
    protected $_modelClassPrefix = null;

    private static function _getApiEndpointUrl($api, $marketplace = 'en_US', $mode = 'live') {
        switch ($api) {
            // Checkout API
            case 'checkout':
                return (string)Mage::getConfig()->getNode(self::GLOBAL_CONFIG_CBA_NODE . '/api/checkout/endpoint_url/' . $marketplace . '/' . $mode);

            // Marketplace Feeds API
            case 'mws_feeds':
                return (string)Mage::getConfig()->getNode(self::GLOBAL_CONFIG_CBA_NODE . '/api/marketplace/feeds/endpoint_url/' . $marketplace);

            // Marketplace Orders API
            case 'mws_orders':
                return (string)Mage::getConfig()->getNode(self::GLOBAL_CONFIG_CBA_NODE . '/api/marketplace/orders/endpoint_url/' . $marketplace);

            // Marketplace Reports API
            case 'mws_reports':
                return (string)Mage::getConfig()->getNode(self::GLOBAL_CONFIG_CBA_NODE . '/api/marketplace/reports/endpoint_url/' . $marketplace);

            // throw an exception for unhandled APIs
            default:
                Mage::helper('checkoutbyamazon')->throwException('API name invalid or not specified');
        }
    }

    private static function _getApiNamespace($api) {
        switch ($api) {
            // Checkout API
            case 'checkout':
                return (string)Mage::getConfig()->getNode(self::GLOBAL_CONFIG_CBA_NODE . '/api/checkout/xmlns');

            // Instant Order Processing Notification
            case 'iopn':
                return (string)Mage::getConfig()->getNode(self::GLOBAL_CONFIG_CBA_NODE . '/api/iopn/xmlns');

            // Marketplace Feeds API
            case 'mws_feeds':
                return (string)Mage::getConfig()->getNode(self::GLOBAL_CONFIG_CBA_NODE . '/api/marketplace/feeds/xmlns');

            // Marketplace Orders API
            case 'mws_orders':
                return (string)Mage::getConfig()->getNode(self::GLOBAL_CONFIG_CBA_NODE . '/api/marketplace/orders/xmlns');

            // Marketplace Reports API
            case 'mws_reports':
                return (string)Mage::getConfig()->getNode(self::GLOBAL_CONFIG_CBA_NODE . '/api/marketplace/reports/xmlns');

            // throw an exception for unhandled APIs
            default:
                Mage::helper('checkoutbyamazon')->throwException('API name invalid or not specified');
        }
    }

    private static function _getApiVersion($api) {
        switch ($api) {
            // Checkout API
            case 'checkout':
                return (string)Mage::getConfig()->getNode(self::GLOBAL_CONFIG_CBA_NODE . '/api/checkout/version');

            // Instant Order Processing Notification
            case 'iopn':
                return (string)Mage::getConfig()->getNode(self::GLOBAL_CONFIG_CBA_NODE . '/api/iopn/version');

            // Marketplace Orders API
            case 'mws_feeds':
                return (string)Mage::getConfig()->getNode(self::GLOBAL_CONFIG_CBA_NODE . '/api/marketplace/feeds/version');

            // Marketplace Orders API
            case 'mws_orders':
                return (string)Mage::getConfig()->getNode(self::GLOBAL_CONFIG_CBA_NODE . '/api/marketplace/orders/version');

            // Marketplace Reports API
            case 'mws_reports':
                return (string)Mage::getConfig()->getNode(self::GLOBAL_CONFIG_CBA_NODE . '/api/marketplace/reports/version');

            // throw an exception for unhandled APIs
            case 'iopn':
            default:
                Mage::helper('checkoutbyamazon')->throwException('API name invalid or not specified');
        }
    }

    /**
     * Private function that returns Amazon Secret Key fetched from extension configuration
     *
     * @return string
     */
    private static function _getSecretKey() {
        return Mage::getStoreConfig(self::XML_PATH_SECRET_KEY);
    }

    /**
     * This function computes RFC 2104-compliant HMAC signature
     *
     * @param string $data
     * @param string $algorithm Either SHA1 or SHA256
     * @return string Returns RFC 2104-compliant HMAC signature
     */
    protected function _generateHmacSignature($data, $algorithm = self::HMAC_SHA1_ALGORITHM) {
        switch (strtolower($algorithm)) {
            case self::HMAC_SHA1_ALGORITHM:
            case self::HMAC_SHA256_ALGORITHM:
                break;
            default :
                Mage::helper('checkoutbyamazon')->throwException('Non-supported signing method specified');
        }
        return base64_encode(hash_hmac($algorithm, $data, self::_getSecretKey(), true));
    }

    protected function _getApiModel($modelName, $params = null) {
        return Mage::getModel($this->_modelClassPrefix . $modelName, $params);
    }

    protected function _getFormattedTimestamp($date = null) {
        return Mage::getModel('core/date')->gmtDate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", $date);
    }

    protected function _getTimestamp() {
        return Mage::getModel('core/date')->gmtTimestamp();
    }

    protected function _getAmazonPaymentMethods() {
        return array('amazonpayments', 'checkoutbyamazon', 'checkoutbyamazon_sandbox');
    }

    protected function _isAmazonPaymentMethod($paymentMethod) {
        return in_array($paymentMethod, $this->_getAmazonPaymentMethods());
    }

    /**
     *
     * @param string $key
     * @param array $params
     * @return string
     */
    public static function getConfigData($key, $params = array()) {
        switch ($key) {
            // AWS Access Key ID
            case 'access_key':
            case 'access_key_id':
                return Mage::getStoreConfig(self::XML_PATH_ACCESS_KEY_ID);

            // checks whether extension is active
            case 'active':
                return Mage::getStoreConfig(self::XML_PATH_ACTIVE);

            // checks whether extension is active
            case 'allowed_ips':
                $allowed = trim(Mage::getStoreConfig(self::XML_PATH_ALLOWED_IPS), ' ,');
                if ($allowed):
                    return explode(',', str_replace(' ', '', $allowed));
                else:
                    return false;
                endif;

            // API namespace
            case 'api_namespace':
                if (isset($params['api'])):
                    return self::_getApiNamespace($params['api']);
                else:
                    Mage::helper('checkoutbyamazon')->throwException('API name invalid or not specified');
                endif;

            // check if order data should be updated with Amazon MWS
            case 'api_order_update':
                return Mage::getStoreConfig(self::XML_PATH_MWS_ORDER_UPDATE);

            // check if token use is allowed in API calls
            case 'api_use_token':
                return Mage::getStoreConfig(self::XML_PATH_MWS_USE_TOKEN);

            // API endpoint URL
            case 'api_url':
                if (isset($params['api'])):
                    return self::_getApiEndpointUrl($params['api'], self::getConfigData('marketplace'), self::getConfigData('mode'));
                else:
                    Mage::helper('checkoutbyamazon')->throwException('API name invalid or not specified');
                endif;

            // API version
            case 'api_version':
                if (isset($params['api'])):
                    return self::_getApiVersion($params['api']);
                else:
                    Mage::helper('checkoutbyamazon')->throwException('API name invalid or not specified');
                endif;

            case 'clean_logs_after':
                return Mage::getStoreConfig(self::XML_PATH_LOGGER_CLEAN_AFTER);

            // currency code
            case 'currency_code':
                return Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE);

            // product SKUs max length
            case 'cut_sku':
                if (trim(Mage::getStoreConfig(self::XML_PATH_CUT_SKU)))
                    return (int)trim(Mage::getStoreConfig(self::XML_PATH_CUT_SKU));
                return false;

            // currency code
            case 'default_customer_prefix':
                if (trim(Mage::getStoreConfig(self::XML_PATH_DEFAULT_PREFIX)))
                    return trim(Mage::getStoreConfig(self::XML_PATH_DEFAULT_PREFIX));
                return '---';

            // currency code
            case 'email_placeholder':
                if (trim(Mage::getStoreConfig(self::XML_PATH_EMAIL_PLACEHOLDER)))
                    return trim(Mage::getStoreConfig(self::XML_PATH_EMAIL_PLACEHOLDER));
                return Mage::getStoreConfig('trans_email/ident_general/email');

            case 'feed_batching':
                return Mage::getStoreConfig(self::XML_PATH_FEED_BATCHING);

            case 'feed_schedule':
                return Mage::getStoreConfig(self::XML_PATH_FEED_SCHEDULE);

            // checks whether Instant Order Processing Notification is activated
            case 'iopn_active':
                return Mage::getStoreConfig(self::XML_PATH_IOPN_ACTIVE);

            // return path to Instant Order Processing Notification Schema file
            case 'iopn_schema':
                return Mage::getBaseDir() . DS . str_replace('<codePool>', (string)Mage::getConfig()->getNode('modules/Creativestyle_CheckoutByAmazon/codePool'), (string)Mage::getConfig()->getNode(self::GLOBAL_CONFIG_CBA_NODE . '/api/iopn/schema_path'));

            // returns IOPN url, if in sandbox non-secure URL can be used
            case 'iopn_url':
                switch (self::getConfigData('mode')) {
                    case 'sandbox':
                        return Mage::getUrl('checkoutbyamazon/process/iopn', array('_nosid' => true));
                    default:
                        return Mage::getUrl('checkoutbyamazon/process/iopn', array('_nosid' => true, '_forced_secure' => true));
                }

            case 'last_feed_sending':
                return Mage::getStoreConfig(self::XML_PATH_LAST_FEED_SENDING);

            case 'last_order_processing':
                return Mage::getStoreConfig(self::XML_PATH_LAST_ORDER_PROCESSING);

            case 'last_report_processing':
                return Mage::getStoreConfig(self::XML_PATH_LAST_REPORT_PROCESSING);

            case 'logger_enabled':
                return Mage::getStoreConfig(self::XML_PATH_LOGGER_ENABLED);

            // marketplace code
            case 'marketplace':
                return Mage::getStoreConfig(self::XML_PATH_MARKETPLACE);

            // Amazon Marketplace ID
            case 'marketplace_id':
                switch (self::getConfigData('marketplace')) {
                    case 'de_DE':
                        return (string)Mage::getConfig()->getNode(self::GLOBAL_CONFIG_CBA_NODE . '/api/marketplace/ids/de_DE');
                    case 'en_GB':
                        return (string)Mage::getConfig()->getNode(self::GLOBAL_CONFIG_CBA_NODE . '/api/marketplace/ids/en_GB');
                    case 'en_US':
                        return (string)Mage::getConfig()->getNode(self::GLOBAL_CONFIG_CBA_NODE . '/api/marketplace/ids/en_US');
                    default:
                        Mage::helper('checkoutbyamazon')->throwException('Unknown marketplace');
                }

            // Amazon Merchant ID
            case 'merchant_id':
                return Mage::getStoreConfig(self::XML_PATH_MERCHANT_ID);

            // working mode: either 'sandbox' or 'live'
            case 'mode':
                return Mage::getStoreConfig(self::XML_PATH_SANDBOX_MODE) ? 'sandbox' : 'live';

            // working mode: either 'sandbox' or 'live'
            case 'new_order_status':
                return Mage::getStoreConfig(self::XML_PATH_NEW_ORDER_STATUS);

            // send order confirmation or not
            case 'order_confirmation':
                return Mage::getStoreConfig(self::XML_PATH_ORDER_CONFIRMATION);

            // report processing frequency
            case 'report_schedule':
                return Mage::getStoreConfig(self::XML_PATH_REPORT_SCHEDULE);

            // weight unit
            case 'weight_unit':
                return 'KG';
                return Mage::getStoreConfig(self::XML_PATH_WEIGHT_UNIT);
        }
    }

    public static function setConfigData($key, $value) {
        $configKey = null;
        switch ($key) {
            case 'last_feed_sending':
                $configKey = self::XML_PATH_LAST_FEED_SENDING;
                break;
            case 'last_report_processing':
                $configKey = self::XML_PATH_LAST_REPORT_PROCESSING;
                break;
            case 'last_order_processing':
                $configKey = self::XML_PATH_LAST_ORDER_PROCESSING;
                break;
        }
        if (null !== $configKey) Mage::getConfig()->saveConfig($configKey, $value)->cleanCache();
    }

    public static function lockExceptionLogging() {
        Mage::register('lock_amazon_exception_logging', true, true);
    }

    public static function unlockExceptionLogging() {
        Mage::unregister('lock_amazon_exception_logging');
    }

    public static function isExceptionLoggingLocked() {
        return Mage::registry('lock_amazon_exception_logging');
    }

}
