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
class Creativestyle_CheckoutByAmazon_Adminhtml_Logger_FeedsController extends Mage_Adminhtml_Controller_Action {

    protected function _getModel() {
        return Mage::getModel('checkoutbyamazon/log_feed');
    }

    protected function _initAction() {
        $this->loadLayout()->_setActiveMenu('creativestyle/checkoutbyamazon/logger/feeds');
        return $this;
    }

    protected function _setBreadcrumbs() {
        return $this;
    }

    protected function _setTitle($title = null) {
        $this->_title(Mage::helper('checkoutbyamazon')->__('Checkout by Amazon'))
            ->_title(Mage::helper('checkoutbyamazon')->__('Amazon MWS Feeds'));
        if ($title) $this->_title(Mage::helper('checkoutbyamazon')->__($title));
        return $this;
    }

    public function indexAction() {
        $this->_setTitle()->_initAction()->_setBreadcrumbs();
        $this->renderLayout();
    }

    public function viewAction() {
        $id = $this->getRequest()->getParam('id');
        $logModel = $this->_getModel()->load($id);

        if ($logModel->getId()) {
            $this->_setTitle('View feed')->_initAction()->_setBreadcrumbs();
            $this->_addContent($this->getLayout()->createBlock('checkoutbyamazon/adminhtml_logger_feeds_view')->setLogModel($logModel));
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('checkoutbyamazon')->__('Log does not exist'));
            $this->_redirect('*/*/');
        }
    }

}