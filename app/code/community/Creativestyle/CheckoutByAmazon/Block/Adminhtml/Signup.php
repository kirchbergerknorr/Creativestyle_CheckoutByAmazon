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
class Creativestyle_CheckoutByAmazon_Block_Adminhtml_Signup extends Mage_Adminhtml_Block_System_Config_Form_Fieldset {

    public function render(Varien_Data_Form_Element_Abstract $element) {
        $output = '';
        foreach ($element->getSortedElements() as $field) {
            $id = $field->getHtmlId();
            $output .= '<div class="creativestyle-info" id="container_' . $id . '"' . (!$field->getValue() ? ' style="display:none;"' : '') . '>';
            $output .= '<div style="float:right;"><input id="amazon_show_signup_info" type="checkbox" class="checkbox" value="1" name="amazon_show_signup_info"' . (!$field->getValue() ? ' checked="checked"' : '') . '/> <label for="amazon_show_signup_info" style="font-size: 11px; color: #666666;">' . $this->__('Do not show again') . '</label></div>';
            $output .= '<p><strong>' . $this->__('Please note!') . '</strong> ';
            $output .= $this->__('If you do not have a Checkout by Amazon account yet please sign up.');
            $output .= '</p><ul>';
            $output .= '<li>DE: <a target="_blank" href="https://payments.amazon.de/business/?ld=INDECBAmagentoplugin">https://payments.amazon.de/business/</a></li>';
            $output .= '<li>UK: <a target="_blank" href="https://payments.amazon.co.uk/business/?ld=INUKCBAMagentoplugin">https://payments.amazon.co.uk/business/</a></li>';
            $output .= '</ul>';
            $output .= '<input type="hidden" id="' . $field->getHtmlId() . '" name="' . $field->getName() . '" value="' . $field->getEscapedValue() . '"/>';
            $output .= '<script type="text/javascript">//<![CDATA[' . "\n";
            $output .= '    Event.observe(\'amazon_show_signup_info\', \'click\', function() {' . "\n";
            $output .= '        if ($(\'amazon_show_signup_info\').checked) {' . "\n";
            $output .= '            if (confirm(\'' . $this->__('Are you sure?') . '\')) {' . "\n";
            $output .= '                $(\'container_' . $id . '\').hide();' . "\n";
            $output .= '                $(\'' . $field->getHtmlId() . '\').value = 0;' . "\n";
            $output .= '            } else {' . "\n";
            $output .= '                $(\'amazon_show_signup_info\').checked = false;' . "\n";
            $output .= '            };' . "\n";
            $output .= '        } else {' . "\n";
            $output .= '            $(\'container_' . $id . '\').show();' . "\n";
            $output .= '            $(\'' . $field->getHtmlId() . '\').value = 1;' . "\n";
            $output .= '        };' . "\n";
            $output .= '    });' . "\n";
            $output .= '//]]></script>' . "\n";
            $output .= '</div>';
            break;
        }
        return $output;
    }

}
