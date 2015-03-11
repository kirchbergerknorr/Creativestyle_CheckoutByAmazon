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
class Creativestyle_CheckoutByAmazon_Model_Api_Checkout extends Creativestyle_CheckoutByAmazon_Model_Api_Abstract implements Creativestyle_CheckoutByAmazon_Model_Api_Interface_Checkout {

    protected $_area = 'Amazon Checkout API';

    public function getPurchaseContract($purchaseContractId) {
        $request = $this->_getApiModel('request_getPurchaseContract', array('PurchaseContractId' => $purchaseContractId));
        $response = $this->_getApiClient()->getPurchaseContract($request);
        if ($response->issetGetPurchaseContractResult()) {
            $getPurchaseContractResult = $response->getGetPurchaseContractResult();
            if ($getPurchaseContractResult->issetPurchaseContract()) {
                $purchaseContract = $getPurchaseContractResult->getPurchaseContract();
                return $purchaseContract;
                if ($purchaseContract->issetId()) {
                    return $purchaseContract->getId();
                }
            }
        }
    }

    public function createPurchaseContract() {
        $request = $this->_getApiModel('request_createPurchaseContract');
        $response = $this->_getApiClient()->createPurchaseContract($request);
        if ($response->issetCreatePurchaseContractResult()) {
            $createPurchaseContractResult = $response->getCreatePurchaseContractResult();
            if ($createPurchaseContractResult->issetPurchaseContractId()) {
                return $createPurchaseContractResult->getPurchaseContractId();
            }
        }
    }

    public function getAddress($purchaseContractId) {
        $request = $this->_getApiModel('request_getPurchaseContract', array('PurchaseContractId' => $purchaseContractId));
        $shippingAddressList = array();
        $addressCount = 0;
        $response = $this->_getApiClient()->getPurchaseContract($request);
        if ($response->issetGetPurchaseContractResult()) {
            $getPurchaseContractResult = $response->getGetPurchaseContractResult();
            if ($getPurchaseContractResult->issetPurchaseContract()) {
                $purchaseContract = $getPurchaseContractResult->getPurchaseContract();
                if ($purchaseContract->issetDestinations()) {
                    $destinations = $purchaseContract->getDestinations();
                    $destinationList = $destinations->getDestination();
                    foreach ($destinationList as $destination) {
                        $state = $purchaseContract->getState();
                        $physicalDestinationAttributes = $destination->getPhysicalDestinationAttributes();
                        if ($physicalDestinationAttributes->issetShippingAddress()) {
                            $shippingAddressList[$addressCount++] = $physicalDestinationAttributes->getShippingAddress();
                        }
                    }
                }
            }
        }
        return $shippingAddressList;
    }

    public function setContractCharges($purchaseContractId, $charges) {
        $request = $this->_getApiModel('request_setContractCharges');
        $request->setPurchaseContractId($purchaseContractId);
        $request->setCharges($charges);
        $response = $this->_getApiClient()->setContractCharges($request);
        if ($response->issetResponseMetadata()) {
            return 1;
        }
        return 0;
    }

    public function setItems($purchaseContractId, $itemList) {
        $request = $this->_getApiModel('request_setPurchaseItems');
        $request->setPurchaseContractId($purchaseContractId);
        $request->setPurchaseItems($itemList);
        $response = $this->_getApiClient()->setPurchaseItems($request);
        if ($response->issetResponseMetadata()) {
            return 1;
        }
        return 0;
    }

    public function completeOrder($purchaseContractId) {
        $request = $this->_getApiModel('request_completePurchaseContract', array('PurchaseContractId' => $purchaseContractId));
        if (self::getConfigData('iopn_active')) {
            $request->setInstantOrderProcessingNotificationURLs($this->_getApiModel('instantOrderProcessingNotificationUrl', array('MerchantURL' => self::getConfigData('iopn_url'))));
        }
        $response = $this->_getApiClient()->completePurchaseContract($request);
        if ($response->issetCompletePurchaseContractResult()) {
            $completePurchaseContractResult = $response->getCompletePurchaseContractResult();
            if ($completePurchaseContractResult->issetOrderIds()) {
                $orderIds = $completePurchaseContractResult->getOrderIds();
                $orderIdList = $orderIds->getOrderId();
                return $orderIdList;
            }
        }
    }

}
