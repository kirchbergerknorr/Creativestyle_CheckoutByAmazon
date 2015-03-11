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
class Creativestyle_CheckoutByAmazon_Block_Badge extends Creativestyle_CheckoutByAmazon_Block_Abstract {

    public function _toHtml() {
        if ($this->_isActive()) {
            return parent::_toHtml();
        }
        return '';
    }

    public function getAmazonBadgeUrl() {
        switch ($this->_getMarketplace()) {
            case 'de_DE':
                return $this->getSkinUrl('creativestyle/images/amazon-payments-badge.de.gif');
            default:
                return $this->getSkinUrl('creativestyle/images/amazon-payments-badge.png');
        }
    }

}
