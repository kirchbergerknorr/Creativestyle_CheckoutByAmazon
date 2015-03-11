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
class Creativestyle_CheckoutByAmazon_ProcessController extends Mage_Core_Controller_Front_Action {

    protected function _getAmazonManager() {
        return Mage::getSingleton('checkoutbyamazon/manager');
    }

    protected function _getIopnRequestParams() {
        return array(
            'UUID' => $this->getRequest()->getPost('UUID', null),
            'Timestamp' => $this->getRequest()->getPost('Timestamp', null),
            'AWSAccessKeyId' => $this->getRequest()->getPost('AWSAccessKeyId', null),
            'Signature' => $this->getRequest()->getPost('Signature', null),
            'NotificationData' => $this->getRequest()->getPost('NotificationData', null)
        );
    }

    protected function _sendResponse($code = 500, $message = null) {
        $response = $this->getResponse();
        switch ($code) {
            case 200:
                $responseCode = '200 OK';
                break;
            case 403:
                $responseCode = '403 Forbidden';
                break;
            case 404:
                $responseCode = '404 Not Found';
                break;
            default:
                $responseCode = '500 Internal Server Error';
                break;
        }
        $response->setHeader('HTTP/1.1', $responseCode);
        if ($message) $response->setBody($message);
        $response->sendResponse();
    }

    public function iopnAction() {
        if (Mage::helper('checkoutbyamazon')->getConfigData('iopn_active')) {
            $notificationType = $this->getRequest()->getPost('NotificationType', null);
            try {
                $params = $this->_getIopnRequestParams();
                switch ($notificationType) {
                    case 'NewOrderNotification':
                        $result = Mage::getModel('checkoutbyamazon/api_iopn')->newOrderNotification($params);
                        if (isset($result['code']) && ($result['code'] == 200)) {
                            $order = $result['order'];
                            $this->_getAmazonManager()->newOrder($order);
                            Creativestyle_CheckoutByAmazon_Model_Logger::logIopn(
                                $notificationType,
                                isset($params['UUID']) ? $params['UUID'] : '',
                                isset($params['NotificationData']) ? $params['NotificationData'] : '',
                                200
                            );
                            return $this->_sendResponse(200);
                        } else {
                            Mage::helper('checkoutbyamazon')->throwException((isset($result['message']) ? $result['message'] : 'Internal error'), (isset($result['code']) ? $result['code'] : 500), array('area' => 'Amazon IOPN'));
                        }
                        break;

                    case 'OrderCancelledNotification':
                        $result = Mage::getModel('checkoutbyamazon/api_iopn')->orderCancelledNotification($params);
                        if (isset($result['code']) && ($result['code'] == 200)) {
                            $order = $result['order'];
                            $this->_getAmazonManager()->cancelOrder($order);
                            Creativestyle_CheckoutByAmazon_Model_Logger::logIopn(
                                $notificationType,
                                isset($params['UUID']) ? $params['UUID'] : '',
                                isset($params['NotificationData']) ? $params['NotificationData'] : '',
                                200
                            );
                            return $this->_sendResponse(200);
                        } else {
                            Mage::helper('checkoutbyamazon')->throwException(isset($result['message']) ? $result['message'] : 'Internal error', isset($result['code']) ? $result['code'] : 500, array('area' => 'Amazon IOPN'));
                        }
                        break;

                    case 'OrderReadyToShipNotification':
                        $result = Mage::getModel('checkoutbyamazon/api_iopn')->orderReadyToShipNotification($params);
                        if (isset($result['code']) && ($result['code'] == 200)) {
                            $order = $result['order'];
                            $this->_getAmazonManager()->readyToShipOrder($order);
                            Creativestyle_CheckoutByAmazon_Model_Logger::logIopn(
                                $notificationType,
                                isset($params['UUID']) ? $params['UUID'] : '',
                                isset($params['NotificationData']) ? $params['NotificationData'] : '',
                                200
                            );
                            return $this->_sendResponse(200);
                        } else {
                            Mage::helper('checkoutbyamazon')->throwException(isset($result['message']) ? $result['message'] : 'Internal error', isset($result['code']) ? $result['code'] : 500, array('area' => 'Amazon IOPN'));
                        }
                        break;

                    default:
                        $this->norouteAction();
                        return;
                }
            } catch (Exception $e) {
                Creativestyle_CheckoutByAmazon_Model_Logger::logIopn(
                    $notificationType,
                    isset($params['UUID']) ? $params['UUID'] : '',
                    isset($params['NotificationData']) ? $params['NotificationData'] : '',
                    $e->getCode()
                );
                if (!($e instanceof Creativestyle_CheckoutByAmazon_Exception)) {
                    Creativestyle_CheckoutByAmazon_Model_Logger::logException(
                        $e->getMessage(),
                        $e->getCode(),
                        $e->getTraceAsString(),
                        'Amazon IOPN',
                        isset($params['UUID']) ? $params['UUID'] : ''                    );
                }
                $this->_sendResponse($e->getCode(), $e->getMessage());
            }
        } else {
            $this->norouteAction();
            return;
        }

    }

}
