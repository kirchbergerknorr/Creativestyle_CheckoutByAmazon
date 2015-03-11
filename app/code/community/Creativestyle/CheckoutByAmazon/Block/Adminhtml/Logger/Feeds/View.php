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
class Creativestyle_CheckoutByAmazon_Block_Adminhtml_Logger_Feeds_View extends Mage_Adminhtml_Block_Widget_Container {

    protected
        $_model = null;

    public function __construct() {
        parent::__construct();
        $this->_controller = 'adminhtml_logger_feeds';
        $this->_headerText = $this->__('Feed submission');
        $this->setTemplate('creativestyle/checkoutbyamazon/logger/feeds/view.phtml');

        $this->_addButton('back', array(
            'label'     => Mage::helper('adminhtml')->__('Back'),
            'onclick'   => 'window.location.href=\'' . $this->getUrl('*/*/') . '\'',
            'class'     => 'back',
        ));

        $this->_addButton('content_button', array(
            'label'     => Mage::helper('checkoutbyamazon')->__('Show feed content'),
            'class'     => 'scalable'
        ));

        $this->_addButton('result_button', array(
            'label'     => Mage::helper('checkoutbyamazon')->__('Show processing result'),
            'class'     => 'scalable'
        ));

    }

    public function getLogModel() {
        return $this->_model;
    }

    public function setLogModel($model) {
        $this->_model = $model;
        if ($this->getLogModel()->getId()) {
            $this->_headerText = $this->__('Feed submission # %s | %s',
                $this->getSubmissionId(),
                $this->getCreationTime()
            );
            if ($this->getLogModel()->getFeedContent()) {
                $this->_updateButton('content_button', 'onclick', 'Modalbox.show($(\'feed_content_code\'), {title: \'' . $this->__('Feed # %s: submitted content', $this->getSubmissionId()) . '\', width: \'95%\'})');
            } else {
                $this->_updateButton('content_button', 'disabled', true);
            }
            if ($this->getLogModel()->getProcessingResult()) {
                $this->_updateButton('result_button', 'onclick', 'Modalbox.show($(\'processing_result_code\'), {title: \'' . $this->__('Feed # %s: processing result', $this->getSubmissionId()) . '\', width: \'95%\'})');
            } else {
                $this->_updateButton('result_button', 'disabled', true);
            }
        }
        return $this;
    }

    public function getCreationTime() {
        return $this->formatDate($this->getLogModel()->getCreationTime(), 'medium', true);
    }

    public function getFeedType() {
        $feedTypeOptions = Mage::getModel('checkoutbyamazon/lookup_feed_type')->getOptions();
        if (isset($feedTypeOptions[$this->getLogModel()->getFeedType()])) return $feedTypeOptions[$this->getLogModel()->getFeedType()];
        return $this->getLogModel()->getFeedType();
    }

    public function getFormattedFeedContent() {
        if (!$this->getLogModel()->getFeedContent()) return null;
        return $this->helper('checkoutbyamazon')->prettifyXml($this->getLogModel()->getFeedContent(), true);
    }

    public function getFormattedProcessingResult() {
        if (!$this->getLogModel()->getProcessingResult()) return null;
        return $this->helper('checkoutbyamazon')->prettifyXml($this->getLogModel()->getProcessingResult(), true);
    }

    public function getProcessingStatus() {
        $feedStatusOptions = Mage::getModel('checkoutbyamazon/lookup_feed_status')->getOptions();
        switch ($this->getLogModel()->getProcessingStatus()) {
            case '_DONE_':
                if (isset($feedStatusOptions[$this->getLogModel()->getProcessingStatus()]))
                    return '<span style="color:#009900;">' . $feedStatusOptions[$this->getLogModel()->getProcessingStatus()] . '</span>';
                return '<span style="color:#009900;">' . $this->getLogModel()->getProcessingStatus() . '</span>';
            default:
                if (isset($feedStatusOptions[$this->getLogModel()->getProcessingStatus()]))
                    return $feedStatusOptions[$this->getLogModel()->getProcessingStatus()];
                return $this->getLogModel()->getProcessingStatus();
        }
    }

    public function getSubmissionId() {
        return $this->getLogModel()->getSubmissionId();
    }

    public function getHeaderCssClass() {
        return 'icon-head head-' . strtr($this->_controller, '_', '-');
    }

}
