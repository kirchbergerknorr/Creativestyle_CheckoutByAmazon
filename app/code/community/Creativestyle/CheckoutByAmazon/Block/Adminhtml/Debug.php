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
class Creativestyle_CheckoutByAmazon_Block_Adminhtml_Debug extends Mage_Adminhtml_Block_Template {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('creativestyle/checkoutbyamazon/debug.phtml');
    }

    protected function _prepareLayout() {
        
        $accordion = $this->getLayout()->createBlock('adminhtml/widget_accordion')->setId('amazonPaymentsDebug');
        
        $accordion->addItem('general', array(
            'title'     => Mage::helper('checkoutbyamazon')->__('General info'),
            'content'   => $this->getLayout()->createBlock('checkoutbyamazon/adminhtml_debug_section')->setArea('general')->toHtml()
        ));

        $accordion->addItem('stores', array(
            'title'     => Mage::helper('checkoutbyamazon')->__('Stores'),
            'content'   => $this->getLayout()->createBlock('checkoutbyamazon/adminhtml_debug_section_table')->setArea('stores')->toHtml()
        ));

        $accordion->addItem('amazon_general_settings', array(
            'title'     => Mage::helper('checkoutbyamazon')->__('Amazon general settings'),
            'content'   => $this->getLayout()->createBlock('checkoutbyamazon/adminhtml_debug_section_table')->setArea('amazon_general_settings')->toHtml()
        ));

        $accordion->addItem('amazon_mws_settings', array(
            'title'     => Mage::helper('checkoutbyamazon')->__('Amazon MWS settings'),
            'content'   => $this->getLayout()->createBlock('checkoutbyamazon/adminhtml_debug_section_table')->setArea('amazon_mws_settings')->toHtml()
        ));

        $accordion->addItem('amazon_appearance_settings', array(
            'title'     => Mage::helper('checkoutbyamazon')->__('Amazon appearance settings'),
            'content'   => $this->getLayout()->createBlock('checkoutbyamazon/adminhtml_debug_section_table')->setArea('amazon_appearance_settings')->toHtml()
        ));

        $accordion->addItem('cronjobs', array(
            'title'     => Mage::helper('checkoutbyamazon')->__('Amazon cronjobs'),
            'content'   => $this->getLayout()->createBlock('checkoutbyamazon/adminhtml_debug_section_table')->setArea('cronjobs')->setShowKeys(false)->toHtml()
        ));

        $accordion->addItem('cron_failures', array(
            'title'     => Mage::helper('checkoutbyamazon')->__('Cronjob errors'),
            'content'   => $this->getLayout()->createBlock('checkoutbyamazon/adminhtml_debug_section')->setArea('cron_failures')->toHtml()
        ));

        $accordion->addItem('event_observers', array(
            'title'     => Mage::helper('checkoutbyamazon')->__('Event observers'),
            'content'   => $this->getLayout()->createBlock('checkoutbyamazon/adminhtml_debug_section')->setArea('event_observers')->toHtml()
        ));

        $accordion->addItem('magento_general_settings', array(
            'title'     => Mage::helper('checkoutbyamazon')->__('Magento settings'),
            'content'   => $this->getLayout()->createBlock('checkoutbyamazon/adminhtml_debug_section_table')->setArea('magento_general_settings')->toHtml()
        ));

        $accordion->addItem('magento_extensions', array(
            'title'     => Mage::helper('checkoutbyamazon')->__('Installed Magento extensions'),
            'content'   => $this->getLayout()->createBlock('checkoutbyamazon/adminhtml_debug_section')->setArea('magento_extensions')->toHtml()
        ));

        $accordion->addItem('php_modules', array(
            'title'     => Mage::helper('checkoutbyamazon')->__('Installed PHP modules'),
            'content'   => $this->getLayout()->createBlock('checkoutbyamazon/adminhtml_debug_section')->setArea('php_modules')->toHtml()
        ));

        $this->setChild('debug_info', $accordion);

        return parent::_prepareLayout();
    }

}
