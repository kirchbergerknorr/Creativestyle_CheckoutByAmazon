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
class Creativestyle_CheckoutByAmazon_Model_Checkout extends Mage_Checkout_Model_Type_Onepage {

    public function savePayment($data) {
        if (empty($data)) {
            return array('error' => -1, 'message' => Mage::helper('checkout')->__('Invalid data.'));
        }
        $quote = $this->getQuote();
        if ($quote->isVirtual()) {
            $quote->getBillingAddress()->setPaymentMethod(isset($data['method']) ? $data['method'] : null);
        } else {
            $quote->getShippingAddress()->setPaymentMethod(isset($data['method']) ? $data['method'] : null);
        }

        // shipping totals may be affected by payment method
        if (!$quote->isVirtual() && $quote->getShippingAddress()) {
            $quote->getShippingAddress()->setCollectShippingRates(true);
        }

        $data['checks'] = Creativestyle_CheckoutByAmazon_Model_Payment_Abstract::CHECK_USE_FOR_COUNTRY
            | Creativestyle_CheckoutByAmazon_Model_Payment_Abstract::CHECK_USE_FOR_CURRENCY
            | Creativestyle_CheckoutByAmazon_Model_Payment_Abstract::CHECK_ORDER_TOTAL_MIN_MAX
            | Creativestyle_CheckoutByAmazon_Model_Payment_Abstract::CHECK_ZERO_TOTAL;

        $payment = $quote->getPayment();
        $payment->importData($data);

        $quote->save();

        $this->getCheckout()
            ->setStepData('payment', 'complete', true)
            ->setStepData('review', 'allow', true);

        return array();
    }

}
