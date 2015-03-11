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
class Creativestyle_CheckoutByAmazon_Model_Api_Client_Checkout extends Creativestyle_CheckoutByAmazon_Model_Api_Client_Abstract {

    protected
        $_area = 'Amazon Checkout API';

    public function __construct() {
        parent::__construct();
        $config = array(
            'ApiUrl' => self::getConfigData('api_url', array('api' => 'checkout')),
            'ApiVersion' => self::getConfigData('api_version', array('api' => 'checkout'))
        );
        $this->_config = array_merge($this->_config, $config);
    }

}
