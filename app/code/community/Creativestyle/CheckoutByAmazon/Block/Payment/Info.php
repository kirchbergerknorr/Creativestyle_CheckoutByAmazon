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
class Creativestyle_CheckoutByAmazon_Block_Payment_Info extends Mage_Payment_Block_Info {

    protected function _construct() {
        parent::_construct();
        $this->setTemplate('creativestyle/checkoutbyamazon/payment/info.phtml');
    }

    public function toPdf() {
        $this->setTemplate('creativestyle/checkoutbyamazon/payment/pdf/info.phtml');
        return $this->toHtml();
    }

}
