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
class Creativestyle_CheckoutByAmazon_Block_Adminhtml_Logger_Exceptions_View extends Mage_Adminhtml_Block_Widget_Container {

    protected
        $_model = null;

    public function __construct() {
        parent::__construct();
        $this->_controller = 'adminhtml_logger_exceptions';
        $this->_headerText = $this->__('Exception');
        $this->setTemplate('creativestyle/checkoutbyamazon/logger/exceptions/view.phtml');

        $this->_addButton('back', array(
            'label'     => Mage::helper('adminhtml')->__('Back'),
            'onclick'   => 'window.location.href=\'' . $this->getUrl('*/*/') . '\'',
            'class'     => 'back',
        ));

    }

    public function getLogModel() {
        return $this->_model;
    }

    public function setLogModel($model) {
        $this->_model = $model;
        if ($this->getLogModel()->getId()) {
            $this->_headerText = $this->__('Exception%s | %s',
                $this->getErrorCode() ? ' ' . $this->getErrorCode() : '',
                $this->getCreationTime()
            );
        }
        return $this;
    }

    public function getCreationTime() {
        return $this->formatDate($this->getLogModel()->getCreationTime(), 'medium', true);
    }

    public function getErrorMessage() {
        return $this->getLogModel()->getMessage();
    }

    public function getErrorCode() {
        return $this->getLogModel()->getErrorCode();
    }

    public function getErrorArea() {
        return $this->getLogModel()->getArea();
    }

    public function getFormattedStackTrace() {
        return $this->getLogModel()->getStackTrace();
    }

    public function getHeaderCssClass() {
        return 'icon-head head-' . strtr($this->_controller, '_', '-');
    }

}
