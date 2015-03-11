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
 * @copyright  Copyright (c) 2011 - 2014 creativestyle GmbH (http://www.creativestyle.de)
 * @author     Marek Zabrowarny / creativestyle GmbH <amazon@creativestyle.de>
 */
class Creativestyle_CheckoutByAmazon_Helper_Debug extends Mage_Core_Helper_Abstract {

    protected
        $_debugInfo = null;

    protected function _getElapsedTime($start, $end) {
        if (!($start && $end)) return 'No';
        $diff = $end - $start;
        $days = (int)($diff / 86400);
        $hours = (int)(($diff - $days * 86400)/ 3600);
        $minutes = (int)(($diff - $days * 86400 - $hours * 3600)/60);
        $seconds = (int)($diff - $days * 86400 - $hours * 3600 - $minutes * 60);
        $result = ($days ? $days . ' d ' : '') .
            ($hours ? $hours . ' h ' : '') .
            ($minutes ? $minutes . ' min. ' : '') .
            ($seconds ? $seconds . ' s ' : '');
        return trim($result);
    }

    protected function _getEventObservers($eventName) {
        $observers = array();
        $areas = array(
            Mage_Core_Model_App_Area::AREA_GLOBAL,
            Mage_Core_Model_App_Area::AREA_FRONTEND,
            Mage_Core_Model_App_Area::AREA_ADMIN,
            Mage_Core_Model_App_Area::AREA_ADMINHTML
        );
        foreach ($areas as $area) {
            $eventConfig = Mage::getConfig()->getEventConfig($area, $eventName);
            if ($eventConfig) {
                foreach ($eventConfig->observers->children() as $obsName => $obsConfig) {
                    $class = Mage::getConfig()->getModelClassName($obsConfig->class ? (string)$obsConfig->class : $obsConfig->getClassName());
                    $method = (string)$obsConfig->method;
                    $args = implode(', ', (array)$obsConfig->args);
                    $observers[$area] = $class . '::' . $method . '(' . $args . ')';
                }
            }
        }
        return $observers;
    }

    protected function _validateAccessKey($accessKey) {
        if (preg_match('/^[a-z0-9]{20}$/i', $accessKey)) return true;
        return false;
    }

    protected function _validateSecretKey($secretKey) {
        if (preg_match('/\\s/', $secretKey)) return false;
        return true;
    }

    public function getDebugInfo($area = null) {
        if (null === $this->_debugInfo) {

            $dateModel = Mage::getModel('core/date');
            $now = $dateModel->gmtTimestamp();

            /* General information */

            $general = array(
                'Amazon extension version' => (string)Mage::getConfig()->getNode('modules/Creativestyle_CheckoutByAmazon/version'),
                'Magento version' => Mage::getVersion(),
                'PHP version' => PHP_VERSION,
                'Current timestamp' => $now . ' <em>&lt;' . $dateModel->date("Y-m-d H:i:s", $now) . '&gt;</em>',
                'Folder var writable' => is_writable(Mage::getBaseDir('var')) ? 'Yes' : 'No',
                'Folder var/amazon writable' => is_writable(Mage::getBaseDir('var') . DS . 'amazon') ? 'Yes' : 'No'
            );


            /* PHP modules */

            $phpModules = array(
                'cURL' => function_exists('curl_init') ? 'Yes' : 'No',
                'PCRE' => function_exists('preg_replace') ? 'Yes' : 'No',
                'DOM' => class_exists('DOMNode') ? 'Yes' : 'No',
                'SimpleXML' => function_exists('simplexml_load_string') ? 'Yes' : 'No'
            );


            /* Magento extensions */

            $magentoExtensions = array();
            $modules = Mage::getConfig()->getNode('modules')->asArray();
            foreach ($modules as $key => $data) {
                $magentoExtensions[$key] = isset($data['active']) && ($data['active'] == 'false' || !$data['active']) ? 'Inactive' : (isset($data['version']) && $data['version'] ? $data['version'] : '');
            }

            /* Stores */

            $stores = array();
            $stores['__titles__'][0] = '<em>__default__</em>';
            $stores['Code'][0] = 'admin';
            $stores['Base URL'][0] = Mage::getStoreConfig(Mage_Core_Model_Store::XML_PATH_UNSECURE_BASE_URL);
            $stores['Secure URL'][0] = Mage::getStoreConfig(Mage_Core_Model_Store::XML_PATH_SECURE_BASE_URL);
            $stores['Locale'][0] = Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_LOCALE);
            $stores['Timezone'][0] = Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE);
            $stores['Store ID'][0] = '0';
            $stores['Website ID'][0] = '0';
            $stores['Group ID'][0] = '0';

            $storeCollection = Mage::getModel('core/store')->getCollection()->load();
            if ($storeCollection->count()) {
                foreach ($storeCollection as $store) {
                    $stores['__titles__'][$store->getCode()] = $store->getName();
                    $stores['Code'][$store->getCode()] = $store->getCode();
                    $stores['Base URL'][$store->getCode()] = Mage::getStoreConfig(Mage_Core_Model_Store::XML_PATH_UNSECURE_BASE_URL, $store->getId());
                    $stores['Secure URL'][$store->getCode()] = Mage::getStoreConfig(Mage_Core_Model_Store::XML_PATH_SECURE_BASE_URL, $store->getId());
                    $stores['Locale'][$store->getCode()] = Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_LOCALE, $store->getId());
                    $stores['Timezone'][$store->getCode()] = Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE, $store->getId());
                    $stores['Store ID'][$store->getCode()] = $store->getId();
                    $stores['Website ID'][$store->getCode()] = $store->getWebsiteId();
                    $stores['Group ID'][$store->getCode()] = $store->getGroupId();
                }
            }

            /* Amazon extension general settings */

            $amazonGeneral = array();
            $amazonGeneral['__titles__'][0] = '<em>__default__</em>';
            $amazonGeneral['Enabled'][0] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_ACTIVE) ? 'Yes' : 'No';
            $amazonGeneral['Sandbox mode'][0] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_SANDBOX_MODE) ? 'Yes' : 'No';
            $amazonGeneral['IOPN active'][0] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_IOPN_ACTIVE) ? 'Yes' : 'No';
            $amazonGeneral['Marketplace'][0] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_MARKETPLACE);
            $amazonGeneral['Mechant ID'][0] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_MERCHANT_ID);
            $amazonGeneral['Access Key ID'][0] = $this->_validateAccessKey(Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_ACCESS_KEY_ID)) ? 'Ok' : 'Invalid';
            $amazonGeneral['Secret Access Key'][0] = $this->_validateSecretKey(Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_SECRET_KEY)) ? 'Ok' : 'Invalid';
            $amazonGeneral['New order status'][0] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_NEW_ORDER_STATUS);
            $amazonGeneral['Send order confirmation'][0] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_ORDER_CONFIRMATION) ? 'Yes' : 'No';
            $amazonGeneral['Specific countries only'][0] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_COUNTRY_ALLOW) ? 'Yes' : 'No';
            $amazonGeneral['Allowed countries'][0] = str_replace(',', ', ', Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_SPECIFIC_COUNTRY));
            $amazonGeneral['Email placeholder'][0] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_EMAIL_PLACEHOLDER);
            $amazonGeneral['Default prefix'][0] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_DEFAULT_PREFIX);

            if ($storeCollection->count()) {
                foreach ($storeCollection as $store) {
                    $amazonGeneral['__titles__'][$store->getCode()] = $store->getName();
                    $amazonGeneral['Enabled'][$store->getCode()] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_ACTIVE, $store->getId()) ? 'Yes' : 'No';
                    $amazonGeneral['Sandbox mode'][$store->getCode()] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_SANDBOX_MODE, $store->getId()) ? 'Yes' : 'No';
                    $amazonGeneral['IOPN active'][$store->getCode()] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_IOPN_ACTIVE, $store->getId()) ? 'Yes' : 'No';
                    $amazonGeneral['Marketplace'][$store->getCode()] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_MARKETPLACE, $store->getId());
                    $amazonGeneral['Mechant ID'][$store->getCode()] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_MERCHANT_ID, $store->getId());
                    $amazonGeneral['Access Key ID'][$store->getCode()] = $this->_validateAccessKey(Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_ACCESS_KEY_ID, $store->getId())) ? 'Ok' : 'Invalid';
                    $amazonGeneral['Secret Access Key'][$store->getCode()] = $this->_validateSecretKey(Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_SECRET_KEY, $store->getId())) ? 'Ok' : 'Invalid';
                    $amazonGeneral['New order status'][$store->getCode()] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_NEW_ORDER_STATUS, $store->getId());
                    $amazonGeneral['Send order confirmation'][$store->getCode()] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_ORDER_CONFIRMATION, $store->getId()) ? 'Yes' : 'No';
                    $amazonGeneral['Specific countries only'][$store->getCode()] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_COUNTRY_ALLOW, $store->getId()) ? 'Yes' : 'No';
                    $amazonGeneral['Allowed countries'][$store->getCode()] = str_replace(',', ', ', Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_SPECIFIC_COUNTRY, $store->getId()));
                    $amazonGeneral['Email placeholder'][$store->getCode()] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_EMAIL_PLACEHOLDER, $store->getId());
                    $amazonGeneral['Default prefix'][$store->getCode()] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_DEFAULT_PREFIX, $store->getId());
                }
            }

            /* Amazon MWS settings */

            $scheduleList = Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Abstract::getSchedules();

            $amazonMws = array();
            $amazonMws['__titles__'][0] = '<em>__default__</em>';
            $amazonMws['Amazon MWS enabled'][0] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_MWS_ORDER_UPDATE) ? 'Yes' : 'No';
            $amazonMws['Iterative processing'][0] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_MWS_USE_TOKEN) ? 'Yes' : 'No';
            $amazonMws['Processing schedule'][0] = isset($scheduleList[Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_REPORT_SCHEDULE)]) ?
                $scheduleList[Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_REPORT_SCHEDULE)] :
                Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_REPORT_SCHEDULE);
            $amazonMws['Feed batching'][0] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_FEED_BATCHING) ? 'Yes' : 'No';
            $amazonMws['Feed sending schedule'][0] = isset($scheduleList[Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_FEED_SCHEDULE)]) ?
                $scheduleList[Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_FEED_SCHEDULE)] :
                Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_FEED_SCHEDULE);
            $amazonMws['Last MWS Reports processing'][0] = $this->_getElapsedTime(Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_LAST_REPORT_PROCESSING), $now);
            $amazonMws['Last MWS Orders processing'][0] = $this->_getElapsedTime(Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_LAST_ORDER_PROCESSING), $now);
            $amazonMws['Last feeds sending'][0] = $this->_getElapsedTime(Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_LAST_FEED_SENDING), $now);

            if ($storeCollection->count()) {
                foreach ($storeCollection as $store) {
                    $amazonMws['__titles__'][$store->getCode()] = $store->getName();
                    $amazonMws['Amazon MWS enabled'][$store->getCode()] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_MWS_ORDER_UPDATE, $store->getId()) ? 'Yes' : 'No';
                    $amazonMws['Iterative processing'][$store->getCode()] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_MWS_USE_TOKEN, $store->getId()) ? 'Yes' : 'No';
                    $amazonMws['Processing schedule'][$store->getCode()] = isset($scheduleList[Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_REPORT_SCHEDULE, $store->getId())]) ?
                        $scheduleList[Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_REPORT_SCHEDULE, $store->getId())] :
                        Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_REPORT_SCHEDULE, $store->getId());
                    $amazonMws['Feed batching'][$store->getCode()] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_FEED_BATCHING, $store->getId()) ? 'Yes' : 'No';
                    $amazonMws['Feed sending schedule'][$store->getCode()] = isset($scheduleList[Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_FEED_SCHEDULE, $store->getId())]) ?
                        $scheduleList[Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_FEED_SCHEDULE, $store->getId())] :
                        Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Model_Abstract::XML_PATH_FEED_SCHEDULE, $store->getId());
                    $amazonMws['Last MWS Reports processing'][$store->getCode()] = 'No';
                    $amazonMws['Last MWS Orders processing'][$store->getCode()] = 'No';
                    $amazonMws['Last feeds sending'][$store->getCode()] = 'No';
                }
            }


            /* Amazon appearance settings */

            $amazonAppearance = array();
            $amazonAppearance['__titles__'][0] = '<em>__default__</em>';
            $amazonAppearance['Button size'][0] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Block_Abstract::XML_PATH_BUTTON_SIZE);
            $amazonAppearance['Button color'][0] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Block_Abstract::XML_PATH_BUTTON_COLOR);
            $amazonAppearance['Button background'][0] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Block_Abstract::XML_PATH_BUTTON_BACKGROUND);
            $amazonAppearance['Address widget width'][0] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Block_Abstract::XML_PATH_ADDRESS_WIDGET_WIDTH);
            $amazonAppearance['Address widget height'][0] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Block_Abstract::XML_PATH_ADDRESS_WIDGET_HEIGHT);
            $amazonAppearance['Payment widget width'][0] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Block_Abstract::XML_PATH_PAYMENT_WIDGET_WIDTH);
            $amazonAppearance['Payment widget height'][0] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Block_Abstract::XML_PATH_PAYMENT_WIDGET_HEIGHT);
            $amazonAppearance['Progress widget width'][0] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Block_Abstract::XML_PATH_PROGRESS_WIDGET_WIDTH);
            $amazonAppearance['Progress widget height'][0] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Block_Abstract::XML_PATH_PROGRESS_WIDGET_HEIGHT);
            $amazonAppearance['Review widget width'][0] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Block_Abstract::XML_PATH_REVIEW_WIDGET_WIDTH);
            $amazonAppearance['Review widget height'][0] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Block_Abstract::XML_PATH_REVIEW_WIDGET_HEIGHT);
            if ($storeCollection->count()) {
                foreach ($storeCollection as $store) {
                    $amazonAppearance['__titles__'][$store->getCode()] = $store->getName();
                    $amazonAppearance['Button size'][$store->getCode()] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Block_Abstract::XML_PATH_BUTTON_SIZE, $store->getId());
                    $amazonAppearance['Button color'][$store->getCode()] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Block_Abstract::XML_PATH_BUTTON_COLOR, $store->getId());
                    $amazonAppearance['Button background'][$store->getCode()] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Block_Abstract::XML_PATH_BUTTON_BACKGROUND, $store->getId());
                    $amazonAppearance['Address widget width'][$store->getCode()] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Block_Abstract::XML_PATH_ADDRESS_WIDGET_WIDTH, $store->getId());
                    $amazonAppearance['Address widget height'][$store->getCode()] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Block_Abstract::XML_PATH_ADDRESS_WIDGET_HEIGHT, $store->getId());
                    $amazonAppearance['Payment widget width'][$store->getCode()] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Block_Abstract::XML_PATH_PAYMENT_WIDGET_WIDTH, $store->getId());
                    $amazonAppearance['Payment widget height'][$store->getCode()] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Block_Abstract::XML_PATH_PAYMENT_WIDGET_HEIGHT, $store->getId());
                    $amazonAppearance['Progress widget width'][$store->getCode()] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Block_Abstract::XML_PATH_PROGRESS_WIDGET_WIDTH, $store->getId());
                    $amazonAppearance['Progress widget height'][$store->getCode()] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Block_Abstract::XML_PATH_PROGRESS_WIDGET_HEIGHT, $store->getId());
                    $amazonAppearance['Review widget width'][$store->getCode()] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Block_Abstract::XML_PATH_REVIEW_WIDGET_WIDTH, $store->getId());
                    $amazonAppearance['Review widget height'][$store->getCode()] = Mage::getStoreConfig(Creativestyle_CheckoutByAmazon_Block_Abstract::XML_PATH_REVIEW_WIDGET_HEIGHT, $store->getId());
                }
            }

            /* Magento general settings */

            $prefix = 'No';
            if (Mage::getStoreConfig('customer/address/prefix_show') == 'req') {
                $prefix = 'Required';
            } else if (Mage::getStoreConfig('customer/address/prefix_show') == 'opt') {
                $prefix = 'Optional';
            }

            $magentoGeneral = array();
            $magentoGeneral['__titles__'][0] = '<em>__default__</em>';
            $magentoGeneral['Allowed countries'][0] = str_replace(',', ', ', Mage::getStoreConfig('general/country/allow'));
            $magentoGeneral['Optional postcode countries'][0] = str_replace(',', ', ', Mage::getStoreConfig('general/country/optional_zip_countries'));
            $magentoGeneral['Base currency'][0] = Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE);
            $magentoGeneral['General email'][0] = Mage::getStoreConfig('trans_email/ident_general/email');
            $magentoGeneral['Customer prefix'][0] = $prefix;
            $magentoGeneral['Validate REMOTE_ADDR'][0] = Mage::getStoreConfig(Mage_Core_Model_Session_Abstract::XML_PATH_USE_REMOTE_ADDR) ? 'Yes' : 'No';
            $magentoGeneral['Validate HTTP_VIA'][0] = Mage::getStoreConfig(Mage_Core_Model_Session_Abstract::XML_PATH_USE_HTTP_VIA) ? 'Yes' : 'No';
            $magentoGeneral['Validate HTTP_X_FORWARDED_FOR'][0] = Mage::getStoreConfig(Mage_Core_Model_Session_Abstract::XML_PATH_USE_X_FORWARDED) ? 'Yes' : 'No';
            $magentoGeneral['Validate HTTP_USER_AGENT'][0] = Mage::getStoreConfig(Mage_Core_Model_Session_Abstract::XML_PATH_USE_USER_AGENT) ? 'Yes' : 'No';
            $magentoGeneral['Use SID on frontend'][0] = Mage::getStoreConfig(Mage_Core_Model_Session_Abstract::XML_PATH_USE_FRONTEND_SID) ? 'Yes' : 'No';
            $magentoGeneral['Minimal order amount enabled'][0] = Mage::getStoreConfig('sales/minimum_order/active') ? 'Yes' : 'No';
            $magentoGeneral['Minimal order amount'][0] = Mage::helper('core')->currency(Mage::getStoreConfig('sales/minimum_order/amount'), true, false);

            if ($storeCollection->count()) {
                foreach ($storeCollection as $store) {

                    $prefix = 'No';
                    if (Mage::getStoreConfig('customer/address/prefix_show', $store->getId()) == 'req') {
                        $prefix = 'Required';
                    } else if (Mage::getStoreConfig('customer/address/prefix_show', $store->getId()) == 'opt') {
                        $prefix = 'Optional';
                    }

                    $magentoGeneral['__titles__'][$store->getCode()] = $store->getName();
                    $magentoGeneral['Allowed countries'][$store->getCode()] = str_replace(',', ', ', Mage::getStoreConfig('general/country/allow', $store->getId()));
                    $magentoGeneral['Optional postcode countries'][$store->getCode()] = str_replace(',', ', ', Mage::getStoreConfig('general/country/optional_zip_countries', $store->getId()));
                    $magentoGeneral['Base currency'][$store->getCode()] = Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE, $store->getId());
                    $magentoGeneral['General email'][$store->getCode()] = Mage::getStoreConfig('trans_email/ident_general/email', $store->getId());
                    $magentoGeneral['Customer prefix'][$store->getCode()] = $prefix;
                    $magentoGeneral['Validate REMOTE_ADDR'][$store->getCode()] = Mage::getStoreConfig(Mage_Core_Model_Session_Abstract::XML_PATH_USE_REMOTE_ADDR, $store->getId()) ? 'Yes' : 'No';
                    $magentoGeneral['Validate HTTP_VIA'][$store->getCode()] = Mage::getStoreConfig(Mage_Core_Model_Session_Abstract::XML_PATH_USE_HTTP_VIA, $store->getId()) ? 'Yes' : 'No';
                    $magentoGeneral['Validate HTTP_X_FORWARDED_FOR'][$store->getCode()] = Mage::getStoreConfig(Mage_Core_Model_Session_Abstract::XML_PATH_USE_X_FORWARDED, $store->getId()) ? 'Yes' : 'No';
                    $magentoGeneral['Validate HTTP_USER_AGENT'][$store->getCode()] = Mage::getStoreConfig(Mage_Core_Model_Session_Abstract::XML_PATH_USE_USER_AGENT, $store->getId()) ? 'Yes' : 'No';
                    $magentoGeneral['Use SID on frontend'][$store->getCode()] = Mage::getStoreConfig(Mage_Core_Model_Session_Abstract::XML_PATH_USE_FRONTEND_SID, $store->getId()) ? 'Yes' : 'No';
                    $magentoGeneral['Minimal order amount enabled'][$store->getCode()] = Mage::getStoreConfig('sales/minimum_order/active', $store->getId()) ? 'Yes' : 'No';
                    $magentoGeneral['Minimal order amount'][$store->getCode()] = Mage::helper('core')->currency(Mage::getStoreConfig('sales/minimum_order/amount', $store->getId()), true, false);
                }
            }

            /* Amazon cron jobs */

            $cronjobs = array();
            $cronFailures = array();

            $cronSchedule = Mage::getModel('cron/schedule')
                ->getCollection()
                ->addFieldToFilter('job_code', array('amazon_api_call', 'amazon_log_clean', 'amazon_feed_batching', 'amazon_reports_processing'))
                ->setOrder('job_code', 'ASC')
                ->setOrder('scheduled_at', 'DESC')
                ->setOrder('executed_at', 'DESC')
                ->setOrder('finished_at', 'DESC')
                ->setOrder('created_at', 'DESC')
                ->load();

            $cronjobs['__titles__'] = array(
                'job_code' => 'Job code',
                'job_id' => 'Job ID',
                'status' => 'Status',
                'scheduled_at' => 'Scheduled at',
                'executed_at' => 'Executed at',
                'finished_at' => 'Finished at',
                'created_at' => 'Created at'
            );

            if ($cronSchedule->count()) {
                foreach ($cronSchedule as $cron) {
                    $cronjobs[$cron->getId()]['job_code'] = $cron->getJobCode();
                    $cronjobs[$cron->getId()]['job_id'] = $cron->getId();
                    $cronjobs[$cron->getId()]['status'] = $cron->getStatus();
                    $cronjobs[$cron->getId()]['scheduled_at'] = ($cron->getScheduledAt() && $cron->getScheduledAt() != '0000-00-00 00:00:00') ? $dateModel->date("Y-m-d H:i:s", $cron->getScheduledAt()) : '';
                    $cronjobs[$cron->getId()]['executed_at'] = ($cron->getExecutedAt() && $cron->getExecutedAt() != '0000-00-00 00:00:00') ? $dateModel->date("Y-m-d H:i:s", $cron->getExecutedAt()) : '';
                    $cronjobs[$cron->getId()]['finished_at'] = ($cron->getFinishedAt() && $cron->getFinishedAt() != '0000-00-00 00:00:00') ? $dateModel->date("Y-m-d H:i:s", $cron->getFinishedAt()) : '';
                    $cronjobs[$cron->getId()]['created_at'] = ($cron->getCreatedAt() && $cron->getCreatedAt() != '0000-00-00 00:00:00') ? $dateModel->date("Y-m-d H:i:s", $cron->getCreatedAt()) : '';
                    if ($cron->getStatus() == 'error') {
                        $cronFailures[$cron->getJobCode() . ' #' . $cron->getId()] = $cron->getMessages() ? '<em>' . nl2br($cron->getMessages()) . '</em>' : '';
                    }
                }
            } else {
                array_shift($cronjobs);
            }

            /* Crucial event observers */

            $eventObservers = array();
            $events = Mage::getConfig()->getNode(Creativestyle_CheckoutByAmazon_Model_Abstract::GLOBAL_CONFIG_CBA_NODE . '/debug/events');
            $events = $events ? $events->asArray() : array();
            ksort($events);

            foreach ($events as $eventName => $eventData) {
                $eventObservers[$eventName] = $this->_getEventObservers($eventName);
            }

            $this->_debugInfo = array(
                'general' => $general,
                'stores' => $stores,
                'amazon_general_settings' => $amazonGeneral,
                'amazon_mws_settings' => $amazonMws,
                'amazon_appearance_settings' => $amazonAppearance,
                'cronjobs' => $cronjobs,
                'cron_failures' => $cronFailures,
                'event_observers' => $eventObservers,
                'magento_general_settings' => $magentoGeneral,
                'magento_extensions' => $magentoExtensions,
                'php_modules' => $phpModules
            );
        }

        if (null === $area) {
            return $this->_debugInfo;
        } else if (isset($this->_debugInfo[$area])) {
            return $this->_debugInfo[$area];
        }

        return array();
    }

    public function testRun() {
        $amazonTest = array();
        $amazonManager = Mage::getModel('checkoutbyamazon/manager');

        try {
            $amazonManager->retrieveAndHandleReportList();
            $amazonTest['MWS Reports processing'] = 'Ok';
        } catch (Exception $e) {
            $amazonTest['MWS Reports processing'] = '<em>' . $e->getMessage() . '</em>';
        }

        try {
            $schedule = Creativestyle_CheckoutByAmazon_Model_Abstract::getConfigData('report_schedule');
            $amazonManager->manageReportSchedule($schedule);
            $amazonTest['MWS Reports scheduling'] = 'Ok';
        } catch (Exception $e) {
            $amazonTest['MWS Reports scheduling'] = '<em>' . $e->getMessage() . '</em>';
        }

        try {
            $amazonManager->updateCanceledOrders();
            $amazonTest['MWS Orders processing'] = 'Ok';
        } catch (Exception $e) {
            $amazonTest['MWS Orders processing'] = '<em>' . $e->getMessage() . '</em>';
        }

        return $amazonTest;
    }
}
