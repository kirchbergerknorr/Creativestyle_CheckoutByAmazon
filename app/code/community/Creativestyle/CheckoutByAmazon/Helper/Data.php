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
class Creativestyle_CheckoutByAmazon_Helper_Data extends Mage_Core_Helper_Abstract {

    protected
        $_debugInfo = null;

    /**
     *
     * @param string $message
     * @param int $code
     * @throws Creativestyle_CheckoutByAmazon_Exception
     */
    public function throwException($message, $code = 0, $params = array()) {
        $exception = Mage::exception('Creativestyle_CheckoutByAmazon', $message, $code);
        if (!$this->isExceptionLoggingLocked()) {
            Creativestyle_CheckoutByAmazon_Model_Logger::logException(
                    $message,
                    $code ? $code : 0,
                    $exception->getTraceAsString(),
                    isset($params['area']) ? $params['area'] : 'General',
                    isset($params['request_id']) ? $params['request_id'] : null
                );
        }
        throw $exception;
    }

    public function getMagentoVersion() {
        $version = Mage::getVersionInfo();
        return $version['major'] . '.' . $version['minor'] . '.' . $version['revision'];
    }

    public function saveFile($relativePath, $content) {
        try {
            $filePath = Mage::getBaseDir() . DS . $relativePath;
            $dir = dirname($filePath);
            if (!(file_exists($dir) && is_dir($dir))) {
                mkdir($dir, 0777, true);
            }
            $fileSaveHandle = fopen($filePath, 'a');
            if ($fileSaveHandle) {
                if (fwrite($fileSaveHandle, $content) !== false) {
                    fclose($fileSaveHandle);
                    return true;
                }
            }
        } catch (Exception $e) {}
        return false;
    }

    /**
     *
     * @param string $key
     * @param array $params
     * @return string
     */
    public function getConfigData($key, $params = array()) {
        return Creativestyle_CheckoutByAmazon_Model_Abstract::getConfigData($key, $params);
    }

    public function sanitizePrice($amount) {
        return number_format($amount, '2', '.', '');
    }

    public function sanitizeSku($sku) {
        $skuLength = $this->getConfigData('cut_sku');
        if ($skuLength > 0) return substr($sku, 0, $skuLength);
        return $sku;
    }

    public function getCheckoutByAmazonButton() {
        return Mage::getSingleton('core/layout')->createBlock('checkoutbyamazon/link')->setTemplate('creativestyle/checkoutbyamazon/link.phtml')->toHtml();
    }

    public function getCheckoutByAmazonBadge() {
        switch ($this->getConfigData('marketplace')) {
            case 'de_DE':
                return '<img src="' . Mage::getDesign()->getSkinUrl('creativestyle/images/amazon-payments-badge.de.gif') . '" alt="' . $this->__('Checkout by Amazon') . '" title="' . $this->__('Checkout by Amazon') . '" />';
            default:
                return '<img src="' . Mage::getDesign()->getSkinUrl('creativestyle/images/amazon-payments-badge.png') . '" alt="' . $this->__('Checkout by Amazon') . '" title="' . $this->__('Checkout by Amazon') . '" />';
        }
    }

    public function getRegionId($regionName, $countryCode) {
        $regionId = Mage::getModel('directory/region')->loadByName($regionName, $countryCode)->getId();
        if (!$regionId) {
            $regionCollection = Mage::getModel('directory/region')->getCollection()->addRegionNameFilter($regionName)->addCountryFilter($countryCode)->load();
            if ($regionCollection->count()) $regionId = $regionCollection->getFirstItem()->getId();
        }
        return $regionId;
    }

    public function prettifyXml($input, $encodeHtmlEntities = false) {
        try {
            $xmlObj = new SimpleXMLElement($input);
            $level = 4;
            $indent = 0;
            $pretty = array();

            $xml = explode("\n", preg_replace('/>\s*</', ">\n<", $xmlObj->asXML()));

            if (count($xml) && preg_match('/^<\?\s*xml/', $xml[0])) {
                $pretty[] = array_shift($xml);
            }

            foreach ($xml as $el) {
                if (preg_match('/^<([\w])+[^>\/]*>$/U', $el)) {
                    $pretty[] = str_repeat(' ', $indent) . $el;
                    $indent += $level;
                } else {
                    if (preg_match('/^<\/.+>$/', $el)) {
                        $indent -= $level;
                    }
                    if ($indent < 0) {
                        $indent += $level;
                    }
                    $pretty[] = str_repeat(' ', $indent) . $el;
                }
            }
            $xml = implode("\n", $pretty);
            return $encodeHtmlEntities ? htmlspecialchars($xml, ENT_COMPAT, 'UTF-8') : $xml;
        } catch (Exception $e) {
            return $input;
        }
    }

    public function lockExceptionLogging() {
        return Creativestyle_CheckoutByAmazon_Model_Abstract::lockExceptionLogging();
    }

    public function unlockExceptionLogging() {
        return Creativestyle_CheckoutByAmazon_Model_Abstract::unlockExceptionLogging();
    }

    public function isExceptionLoggingLocked() {
        return Creativestyle_CheckoutByAmazon_Model_Abstract::isExceptionLoggingLocked();
    }

    public function getHeadJs() {
        switch ($this->getConfigData('marketplace')) {
            case 'de_DE':
                switch ($this->getConfigData('mode')) {
                    case 'live':
                        return 'https://static-eu.payments-amazon.com/cba/js/de/PaymentWidgets.js';

                    case 'sandbox':
                        return 'https://static-eu.payments-amazon.com/cba/js/de/sandbox/PaymentWidgets.js';
                }

            case 'en_GB':
                switch ($this->getConfigData('mode')) {
                    case 'live':
                        return 'https://static-eu.payments-amazon.com/cba/js/gb/PaymentWidgets.js';

                    case 'sandbox':
                        return 'https://static-eu.payments-amazon.com/cba/js/gb/sandbox/PaymentWidgets.js';
                }

            default:
                switch ($this->getConfigData('mode')) {
                    case 'live':
                        return 'https://static-na.payments-amazon.com/cba/js/us/PaymentWidgets.js';

                    case 'sandbox':
                        return 'https://static-na.payments-amazon.com/cba/js/us/sandbox/PaymentWidgets.js';
                }
        }
    }
}
