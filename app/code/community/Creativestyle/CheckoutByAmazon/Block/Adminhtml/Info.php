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
class Creativestyle_CheckoutByAmazon_Block_Adminhtml_Info extends Mage_Adminhtml_Block_System_Config_Form_Fieldset {

    public function render(Varien_Data_Form_Element_Abstract $element) {
        $this->setTemplate('creativestyle/checkoutbyamazon/info.phtml');
        $output = $this->toHtml();
        return $output;
    }

    public function getExtensionVersion() {
        return (string)Mage::getConfig()->getNode('modules/Creativestyle_CheckoutByAmazon/version');
    }


    protected function _getInfo() {
        $output = $this->_getStyle();
        $output .= '<div class="creativestyle-info">';
        $output .= $this->_getLogo();
        $output .= '<h3>' . $this->__('Checkout by Amazon') . ' <small>(v. ' . (string)Mage::getConfig()->getNode('modules/Creativestyle_CheckoutByAmazon/version') . ')</small>' . '</h3>';
        $output .= '<p style="clear:both;">';
        $output .= $this->__('This extension integrates easily your Magento shop with Checkout by Amazon payment service.');
        $output .= '</p>';
        $output .= $this->_getFooter();
        $output .= '</div>';
        return $output;
    }

    protected function _getFooter() {
        $content = '--------------------------------------------------------<br />';
        $content .= '<p>' . $this->helper('creativestyle')->__('Visit <a href="%s" target="_blank">%s</a> to get more information.', 'http://www.creativestyle.de/amazon-extension.html', 'http://www.creativestyle.de/amazon-extension.html') . '</p>';
        return $content;
    }

}
