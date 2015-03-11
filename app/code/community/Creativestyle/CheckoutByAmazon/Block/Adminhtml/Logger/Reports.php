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
class Creativestyle_CheckoutByAmazon_Block_Adminhtml_Logger_Reports extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        parent::__construct();
        $this->_controller = 'adminhtml_logger_reports';
        $this->_blockGroup = 'checkoutbyamazon';
        $this->_headerText = Mage::helper('checkoutbyamazon')->__('Amazon MWS Reports');
        $this->_removeButton('add');
    }

}
