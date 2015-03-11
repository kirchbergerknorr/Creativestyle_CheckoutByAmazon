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
abstract class Creativestyle_CheckoutByAmazon_Model_Api_Client_Abstract extends Creativestyle_CheckoutByAmazon_Model_Abstract {

    protected
        $_area = 'General',
        $_config = array(
            'ApiUrl' => null,
            'ApiVersion' => null,
            'UserAgent' => null,
            'SignatureVersion' => 2,
            'SignatureMethod' => 'HmacSHA256',
            'MaxErrorRetry' => 3
        );

    public function __construct() {
        $modelSuffix = preg_replace('/^(.+_Model_)(Api)_(Client)_(.+)$/', '$2_Model_$4', get_class($this));
        $modelSuffix = preg_replace('/(_.?)/e', "strtolower('$1')", $modelSuffix);
        $modelSuffix[0] = strtolower($modelSuffix[0]);
        $this->_modelClassPrefix = $this->_modulePrefix . '/' . $modelSuffix  . '_';

        $phpVersion = preg_replace('/([0-9]+)\.([0-9]+)\.([0-9]+)(.*)/', '$1.$2.$3', phpversion());
        $this->_config['UserAgent'] = 'Creativestyle Checkout by Amazon Magento Extension/' . (string) Mage::getConfig()->getNode('modules/Creativestyle_CheckoutByAmazon/version')
            . ' (Language=PHP/' . $phpVersion . ';'
            . (php_uname('s') ? (' Platform=' . php_uname('s') . ';') : '')
            . (parse_url(Mage::getStoreConfig('web/unsecure/base_url'), PHP_URL_HOST) ? (' Host=' . parse_url(Mage::getStoreConfig('web/unsecure/base_url'), PHP_URL_HOST) . ';') : '')
            . ')';
    }

    protected function _call($params) {
        $actionName = $params['Action'];
        $response = array();

        $params = $this->_includeCommonParams($params);
        $shouldRetry = true;
        $retries = 0;

        do {
            switch ($actionName) {
                case 'SubmitFeed':
                    $response = $this->_httpUpload($params);
                    break;
                default:
                    $response = $this->_httpPost($params);
                    break;
            }
            if ($response['Status'] === 200) {
                $shouldRetry = false;
            } else {
                if ($response['Status'] === 500 || $response['Status'] === 503) {
                    $shouldRetry = true;
                    $this->_pauseBeforeRetry(++$retries, $response['Status']);
                } else {
                    $this->_handleError($response['ResponseBody'], $response['Status']);
                }
            }
        } while ($shouldRetry);
        return $response['ResponseBody'];
    }

    protected function _getQueryAsString($params) {
        $queryParams = array();
        foreach ($params as $key => $value) {
            $queryParams[] = $key . '=' . $this->_urlencode($value);
        }
        return implode('&', $queryParams);
    }

    protected function _handleError($response, $status) {
        $doc = simplexml_load_string($response);
        $message = (string)$doc->Error->Message;
        $requestId = (string)$doc->RequestId;
        $errorCode = (string)$doc->Error->Code;
        $errorType = (string)$doc->Error->Type;

        if (!$message) $message = 'Checkout by Amazon response error: ' . $response;
        if (is_null($errorType)) $errorType = 'Unknown';

        Mage::helper('checkoutbyamazon')->throwException($message, $status, array('area' => $this->_area, 'request_id' => $requestId));

    }

    protected function _httpPost($params) {
        $query = $this->_getQueryAsString($params);
        $url = parse_url($this->_config['ApiUrl']);
        $path = array_key_exists('path', $url) ? $url['path'] : "/";
        $headers = "Content-Type: application/x-www-form-urlencoded; charset=utf-8\r\n";
        $headers .= "Content-Length: " . strlen($query) . "\r\n";
        $headers .= "User-Agent: " . $this->_config['UserAgent'] . "\r\n";
        $post = "POST " . $path . " HTTP/1.0\r\n";
        $post .= "Host: " . $url['host'] . "\r\n";
        $post .= $headers;
        $post .= "\r\n";
        $post .= $query;
        $port = array_key_exists('port', $url) ? $url['port'] : null;
        $scheme = '';

        switch ($url['scheme']) {
            case 'https':
                $scheme = 'ssl://';
                $port = $port === null ? 443 : $port;
                break;
            default:
                $scheme = '';
                $port = $port === null ? 80 : $port;
        }

        $response = '';
        if ($socket = @fsockopen($scheme . $url['host'], $port, $errno, $errstr, 10)) {
            fwrite($socket, $post);
            while (!feof($socket)) {
                $response .= fgets($socket, 1160);
            }
            fclose($socket);
            list($other, $responseBody) = explode("\r\n\r\n", $response, 2);
            $other = preg_split("/\r\n|\n|\r/", $other);
            list($protocol, $code, $text) = explode(' ', trim(array_shift($other)), 3);
            Mage::dispatchEvent('amazon_api_post_call', array('host' => $this->_config['ApiUrl'], 'action' => isset($params['Action']) ? $params['Action'] : '', 'headers' => $headers, 'post' => $query, 'response_code' => (int)$code, 'response' => $responseBody));
        } else {
            Mage::dispatchEvent('amazon_api_post_call', array('host' => $this->_config['ApiUrl'], 'action' => isset($params['Action']) ? $params['Action'] : '', 'headers' => $headers, 'post' => $query));
            Mage::helper('checkoutbyamazon')->throwException(
                Mage::helper('checkoutbyamazon')->__('Unable to establish connection to host %s: %s', $url['host'], $errstr),
                $errno,
                array('area' => $this->_area)
            );
        }

        return array('Status' => (int)$code, 'ResponseBody' => $responseBody);
    }

    protected function _httpUpload($params) {

        $feedContent = (isset($params['FeedContent']) ? $params['FeedContent'] : '');
        $feedContentMd5 = (isset($params['ContentMd5']) ? $params['ContentMd5'] : base64_encode(md5($feedContent, true)));

        unset($params['FeedContent'], $params['ContentMd5'], $params['Signature']);
        $params['Signature'] = $this->_sign($params);

        $tmpFilename = md5(uniqid() . time()) . '.xml';
        $tmpDir = Mage::getBaseDir('var') . DS . 'tmp';
        if (!file_exists($tmpDir)) @mkdir($tmpDir, 0777, true);
        $filePath = $tmpDir . DS . $tmpFilename;

        $fileSaveHandle = fopen($filePath, 'a');
        fwrite($fileSaveHandle, $feedContent);
        fclose($fileSaveHandle);
        $fileHandle = @fopen($filePath, 'r');

        $headers = array(
            'Expect: ',
            'Accept: ',
            'Transfer-Encoding: chunked',
            'Content-MD5: ' . $feedContentMd5,
            'Content-Type: application/octet-stream'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_config['ApiUrl'] . '?' . $this->_getQueryAsString($params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_INFILE, $fileHandle);
        curl_setopt($ch, CURLOPT_UPLOAD, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->_config['UserAgent']);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

        $response = curl_exec($ch);

        if ($response === false) {
            Mage::helper('checkoutbyamazon')->throwException(
                curl_error($ch),
                curl_errno($ch),
                array('area' => $this->_area)
            );
        }

        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        unlink($filePath);

        Mage::dispatchEvent('amazon_api_file_call', array('host' => $this->_config['ApiUrl'], 'action' => isset($params['Action']) ? $params['Action'] : '', 'headers' => implode("\r\n", $headers), 'get' => $this->_getQueryAsString($params), 'file' => $feedContent, 'response_code' => (int)$code, 'response' => $response));

        return array('Status' => (int)$code, 'ResponseBody' => $response);
    }

    protected function _includeCommonParams($params) {
        $params['AWSAccessKeyId'] = self::getConfigData('access_key');
        $params['Timestamp'] = $this->_getFormattedTimestamp();
        $params['Version'] = $this->_config['ApiVersion'];
        $params['SignatureVersion'] = $this->_config['SignatureVersion'];
        if ($params['SignatureVersion'] > 1) {
            $params['SignatureMethod'] = $this->_config['SignatureMethod'];
        }
        $params['Signature'] = $this->_sign($params);
        return $params;
    }

    protected function _pauseBeforeRetry($retries, $status) {
        if ($retries <= $this->_config['MaxErrorRetry']) {
            $delay = (int) (pow(4, $retries) * 100000);
            usleep($delay);
        } else {
            Mage::helper('checkoutbyamazon')->throwException(
                Mage::helper('checkoutbyamazon')->__('Maximum number of retry attempts to Amazon Payments API reached: %s', $retries),
                $status,
                array('area' => $this->_area)
            );
        }
    }

    protected function _sign(array $params) {
        $signatureVersion = $params['SignatureVersion'];
        $params['SignatureMethod'] = $this->_config['SignatureMethod'];

        $endpoint = parse_url($this->_config['ApiUrl']);
        $data = 'POST';
        $data .= "\n";
        $data .= $endpoint['host'];
        $data .= "\n";
        $uri = array_key_exists('path', $endpoint) ? $endpoint['path'] : null;
        if (!isset($uri)) $uri = '/';
        $uriEncoded = implode('/', array_map(array($this, '_urlencode'), explode('/', $uri)));
        $data .= $uriEncoded;
        $data .= "\n";

        uksort($params, 'strcmp');
        $data .= $this->_getQueryAsString($params);

        switch ($this->_config['SignatureMethod']) {
            case 'HmacSHA256':
                $algorithm = self::HMAC_SHA256_ALGORITHM;
                break;
            case 'HmacSHA1':
                $algorithm = self::HMAC_SHA1_ALGORITHM;
                break;
            default:
                Mage::helper('checkoutbyamazon')->throwException('Non-supported signing method specified', null, array('area' => $this->_area));
        }

        return $this->_generateHmacSignature($data, $algorithm);
    }

    protected function _urlencode($value) {
        return str_replace('%7E', '~', rawurlencode($value));
    }

    public function __call($function, $params) {
        $uFunction = $function;
        $uFunction[0] = strtoupper($uFunction[0]);
        $classPrefix = str_replace('_Model_Api_Client_', '_Model_Api_Model_', get_class($this));
        $classes = array(
            'request' => $classPrefix . '_Request_' . $uFunction,
            'response' => $classPrefix . '_Response_' . $uFunction
        );
        if (class_exists($classes['response'], true) && isset($params[0]) && $params[0] instanceof $classes['request'] && method_exists($classes['request'], 'convertToQueryString')) {
            return call_user_func(array($classes['response'], 'fromXML'), $this->_call($params[0]->convertToQueryString()));
            // following code is incompatible with PHP version < 5.3.0
            // return $classes['response']::fromXML($this->_call($params[0]->convertToQueryString()));
        } else {
            Mage::helper('checkoutbyamazon')->throwException(Mage::helper('checkoutbyamazon')->__('Invalid method %s::%s (%s)', get_class($this), $function, print_r($params, 1)), null, array('area' => $this->_area));
        }
    }

    public function processRequest($request) {
        if (method_exists($request, 'convertToQueryString')) {
            return $this->_call($request->convertToQueryString());
        } else {
            Mage::helper('checkoutbyamazon')->throwException(Mage::helper('checkoutbyamazon')->__('Request %s cannot be converted to query string', get_class($this)), null, array('area' => $this->_area));
        }
    }

}
