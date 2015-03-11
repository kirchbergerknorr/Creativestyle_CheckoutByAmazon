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

class Creativestyle_CheckoutByAmazon_Block_Checkout_Progress extends Creativestyle_CheckoutByAmazon_Block_Abstract {

    public function formatPrice($price) {
        return $this->getQuote()->getStore()->formatPrice($price);
    }

    public function getShippingMethod() {
        return $this->getQuote()->getShippingAddress()->getShippingMethod();
    }

    public function getShippingDescription() {
        return $this->getQuote()->getShippingAddress()->getShippingDescription();
    }

    public function getShippingPriceInclTax() {
        $exclTax = $this->getQuote()->getShippingAddress()->getShippingAmount();
        $taxAmount = $this->getQuote()->getShippingAddress()->getShippingTaxAmount();
        return $this->formatPrice($exclTax + $taxAmount);
    }

    public function getShippingPriceExclTax() {
        return $this->formatPrice($this->getQuote()->getShippingAddress()->getShippingAmount());
    }

    public function getShippingHtml() {
        return $this->getChildHtml('shipping_info');
    }

    public function getPaymentHtml() {
        return $this->getChildHtml('payment_info');
    }

}
