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

class Creativestyle_CheckoutByAmazon_Block_Checkout_Progress_Info extends Creativestyle_CheckoutByAmazon_Block_Abstract {

    public function getWidgetWidth() {
        return Mage::getStoreConfig(self::XML_PATH_PROGRESS_WIDGET_WIDTH);
    }

    public function getWidgetHeight() {
        return Mage::getStoreConfig(self::XML_PATH_PROGRESS_WIDGET_HEIGHT);
    }

}
