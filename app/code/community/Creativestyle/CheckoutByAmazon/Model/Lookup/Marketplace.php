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
class Creativestyle_CheckoutByAmazon_Model_Lookup_Marketplace extends Creativestyle_CheckoutByAmazon_Model_Lookup_Abstract {

    protected
        $_availableMarketplaces = array('en_US', 'en_GB', 'de_DE');

    public function toOptionArray() {
        if (null === $this->_options) {
            $localeModel = Mage::getSingleton('core/locale');
            $languages = $localeModel->getLocale()->getTranslationList('language', $localeModel->getLocale());
            $countries = $localeModel->getCountryTranslationList();
            unset($localeModel);
            $this->_options = array();
            foreach ($this->_availableMarketplaces as $marketplace) {
                if (strstr($marketplace, '_')) {
                    $explodedLocale = explode('_', $marketplace);
                    if (isset($languages[$explodedLocale[0]]) && isset($countries[$explodedLocale[1]])) {
                        $this->_options[] = array(
                            'value' => $marketplace,
                            'label' => ucwords($languages[$explodedLocale[0]]) . ' (' . $countries[$explodedLocale[1]] . ')'
                        );
                    }
                }
            }
        }
        return $this->_options;
    }
}
