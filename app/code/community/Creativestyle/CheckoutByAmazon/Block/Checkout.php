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
class Creativestyle_CheckoutByAmazon_Block_Checkout extends Creativestyle_CheckoutByAmazon_Block_Abstract {

    public function getSteps() {
        $steps = array();
        $stepCodes = array('shipping', 'shipping_method', 'payment', 'review');
        foreach ($stepCodes as $step) {
            $steps[$step] = $this->getCheckout()->getStepData($step);
        }
        return $steps;
    }

    public function getActiveStep() {
        return 'shipping';
    }

    public function getPurchaseContractId() {
        return Mage::getSingleton('checkout/session')->getAmazonPurchaseContractId();
    }
}
