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
class Creativestyle_CheckoutByAmazon_Model_Mysql4_Log_Feed extends Creativestyle_CheckoutByAmazon_Model_Mysql4_Log_Abstract {

    protected
        $_api = array(),
        $_tableName = 'checkoutbyamazon/log_feed';

    protected function _getApi() {
        $storeId = Mage::app()->getStore()->getId();
        if (!isset($this->_api[$storeId])) {
            $this->_api[$storeId] = Mage::getModel('checkoutbyamazon/api_marketplace_feeds');
        }
        return $this->_api[$storeId];
    }

    protected function _afterLoad(Mage_Core_Model_Abstract $object) {
        parent::_afterLoad($object);
        $objectChanged = false;

        $currentStore = Mage::app()->getStore();
        Mage::app()->setCurrentStore($object->getStoreId());

        if (($object->getProcessingStatus() != '_DONE_') && $object->getSubmissionId()) {
            try {
                $feedSubmissionList = $this->_getApi()->getFeedSubmissionList(array($object->getSubmissionId()));
            } catch (Exception $e) {}

            if ($feedSubmissionList && $feedSubmissionList->issetFeedSubmissionInfo()) {
                $feedSubmissionInfo = $feedSubmissionList->getFeedSubmissionInfo();
                if (is_array($feedSubmissionInfo)) {
                    foreach ($feedSubmissionInfo as $_feed) {
                        if ($_feed->issetFeedSubmissionId() && $_feed->issetFeedProcessingStatus() &&
                                ($_feed->getFeedSubmissionId() == $object->getSubmissionId()) &&
                                ($_feed->getFeedProcessingStatus() != $object->getProcessingStatus())) {
                            $object->setProcessingStatus($_feed->getFeedProcessingStatus());
                            $objectChanged = true;
                        }
                    }
                }
            }
        }

        if ($object->getProcessingStatus() == '_DONE_' && is_null($object->getProcessingResult())) {
            try {
                $feedSubmissionResult = $this->_getApi()->getFeedSubmissionResult($object->getSubmissionId());
                $object->setProcessingResult($feedSubmissionResult);
                $objectChanged = true;
            } catch (Exception $e) {
                // do not handle this exception, no result yet
            }
        }

        if ($objectChanged) $object->save();

        Mage::app()->setCurrentStore($currentStore);

        return $this;
    }

}
