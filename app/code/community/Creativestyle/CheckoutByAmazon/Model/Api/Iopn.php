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
class Creativestyle_CheckoutByAmazon_Model_Api_Iopn
    extends Creativestyle_CheckoutByAmazon_Model_Api_Abstract
    implements Creativestyle_CheckoutByAmazon_Model_Api_Interface_Iopn {

    protected $_area = 'Amazon IOPN';

    public function newOrderNotification(array $params) {
        $result = $this->_getApiServer()->newOrderNotification($params);
        if ($result->issetProcessedOrder()) {
            return array('code' => 200, 'order' => $result->getProcessedOrder());
        }
        return array('code' => 500, 'message' => 'Internal error');
    }

    public function orderCancelledNotification(array $params) {
        $result = $this->_getApiServer()->orderCancelledNotification($params);
        if ($result->issetProcessedOrder()) {
            return array('code' => 200, 'order' => $result->getProcessedOrder());
        }
        return array('code' => 500, 'message' => 'Internal error');
    }

    public function orderReadyToShipNotification(array $params) {
        $result = $this->_getApiServer()->orderReadyToShipNotification($params);
        if ($result->issetProcessedOrder()) {
            return array('code' => 200, 'order' => $result->getProcessedOrder());                
        }
        return array('code' => 500, 'message' => 'Internal error');
    }

}
