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
class Creativestyle_CheckoutByAmazon_Model_Mysql4_Log_Collection_Abstract extends Mage_Core_Model_Mysql4_Collection_Abstract {

    protected
        $_modelName = 'checkoutbyamazon/log_abstract';

    public function _construct() {
        parent::_construct();
        $this->_init($this->_modelName);
    }

}
