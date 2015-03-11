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
class Creativestyle_CheckoutByAmazon_Block_Adminhtml_Logger_Reports_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('amazon_logger_reports_grid');
        $this->setDefaultSort('creation_time');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('checkoutbyamazon/log_report')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {

        $this->addColumn('creation_time', array(
            'header'        => Mage::helper('checkoutbyamazon')->__('Date'),
            'index'         => 'creation_time',
            'type'          => 'datetime',
            'width'         => '150px'
        ));

        $this->addColumn('report_request_id', array(
            'header'        => Mage::helper('checkoutbyamazon')->__('Report request ID'),
            'index'         => 'report_request_id',
            'width'         => '150px'
        ));

        $this->addColumn('report_id', array(
            'header'        => Mage::helper('checkoutbyamazon')->__('Report ID'),
            'index'         => 'report_id',
            'width'         => '150px'
        ));

        $this->addColumn('report_type', array(
            'header'        => Mage::helper('checkoutbyamazon')->__('Report type'),
            'index'         => 'report_type',
            'type'          => 'options',
            'options'       => Mage::getModel('checkoutbyamazon/lookup_report_type')->getOptions(),
            'width'         => '300px'
        ));

        $this->addColumn('action', array(
            'header'    => Mage::helper('checkoutbyamazon')->__('Action'),
            'type'      => 'action',
            'align'     => 'center',
            'width'     => '50px',
            'getter'    => 'getId',
            'actions'   => array(
                array(
                    'caption' => Mage::helper('checkoutbyamazon')->__('View'),
                    'url'     => array('base' => '*/*/view'),
                    'field'   => 'id'
                )
            ),
            'filter'    => false,
            'sortable'  => false,
            'is_system' => true
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/view', array('id' => $row->getId()));
    }

}
