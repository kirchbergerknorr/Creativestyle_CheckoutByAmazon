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
class Creativestyle_CheckoutByAmazon_Model_Api_Server_Iopn extends Creativestyle_CheckoutByAmazon_Model_Api_Server_Abstract {

    protected $_area = 'Amazon IOPN';

    protected function _extractXml(array $params) {
        if (isset($params['NotificationData'])) return $params['NotificationData'];
        return null;
    }

    protected function _validateParams(array $params = array()) {
        if (!isset($params['UUID']) || !isset($params['Timestamp']) || !isset($params['AWSAccessKeyId']) || !isset($params['Signature'])) {
            Mage::helper('checkoutbyamazon')->throwException('Either UUID or Timestamp or AWSAccessKeyId or Signature missing in the request', 500, array('area' => $this->_area));
        }

        if (!$this->_verifyAccessKey($params['AWSAccessKeyId'])) {
            Mage::helper('checkoutbyamazon')->throwException('Invalid AWS Access Key ID', 500, array('area' => $this->_area));
        }

        if (abs(strtotime($params['Timestamp']) - $this->_getTimestamp()) > 900) {
            Mage::helper('checkoutbyamazon')->throwException('Rejecting notification due to timestamp range overflow', 403, array('area' => $this->_area));
        }

        if (!$this->_verifySignature($params['UUID'], $params['Timestamp'], $params['Signature'])) {
            Mage::helper('checkoutbyamazon')->throwException('Rejecting notification due to invalid signature', 403, array('area' => $this->_area));
        }

        if (!$this->_validateXml($this->_extractXml($params))) {
            Mage::helper('checkoutbyamazon')->throwException('Validation of XML against schema failed', 500, array('area' => $this->_area));
        }

        return true;
    }

    protected function _verifyAccessKey($accessKey) {
        if ($accessKey == self::getConfigData('access_key')) return true;
        return false;
    }

    protected function _verifySignature($uuid, $timestamp, $signature) {
        if ($signature == $this->_generateHmacSignature($uuid . $timestamp, self::HMAC_SHA1_ALGORITHM)) return true;
        return false;
    }

    protected function _validateXml($xml) {
        $dom = new DOMDocument();
        $dom->loadXML($xml);
        if ($dom->schemaValidate(self::getConfigData('iopn_schema'))) return true;
        return false;
    }

}
