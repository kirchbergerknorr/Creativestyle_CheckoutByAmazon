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
abstract class Creativestyle_CheckoutByAmazon_Model_Api_Model_Abstract extends Creativestyle_CheckoutByAmazon_Model_Abstract {

    protected
        $_area = 'General',
        $_fields = array();

    abstract protected function _getNamespace();

    /**
     * Escape special XML characters
     * 
     * @return string with escaped XML characters
     */
    private function _escapeXml($value)  {
        $from = array( "&", "<", ">", "'", "\""); 
        $to = array( "&amp;", "&lt;", "&gt;", "&#039;", "&quot;");
        return str_replace($from, $to, $value);
    }

    /**
     * Checks whether passed data type is a class of API Model object
     *
     * @param string $type
     * @return TRUE if passed data type is a class of Amazon API model object
     */
    private function _isApiModelClass($type) {
        return preg_match('/^Creativestyle_CheckoutByAmazon_Model_Api_Model_/', $type);
    }

    private function _isApiObject($value) {
        if (gettype($value) == 'object') {
            return preg_match('/^Creativestyle_CheckoutByAmazon_Model_Api_Model_/', get_class($value));
        }
        return false;
    }

    /**
     * Checks whether passed variable is an array indexed by numbers
     *
     * @param mixed $value
     * @return TRUE if passed variable is a number-indexed array
     */
    private function _isArray($value) {
        return is_array($value) && array_keys($value) === range(0, sizeof($value) - 1);
    }

    /**
     * Checks whether passed variable is a hash table
     *
     * @param mixed $value
     * @return TRUE if passed variable is a hash table
     */
    private function _isHashTable($value) {
        return is_array($value) && !$this->_isArray($value);
    }

    /**
     * Checks  whether passed variable is DOMElement
     *
     * @param mixed $var
     * @return TRUE if passed variable is DOMElement
     */
    private function _isDOMElement($value) {
        return $value instanceof DOMElement;
    }

    private function _issetData($key) {
        return isset($this->_fields[$key]['FieldValue']);
    }

    private function _getData($key) {
        if (isset($this->_fields[$key]['FieldValue'])) {
            return $this->_fields[$key]['FieldValue'];
        }
        return null;
    }

    private function _setData($key, $value) {
        $this->_fields[$key]['FieldValue'] = $value;
        return $this;
    }

    /**
     * Returns fields XML representation
     * 
     * @return string XML representation of object's fields
     */
    protected function _fieldsToXml() {
        $xml = '';
        foreach ($this->_fields as $fieldName => $field) {
            $fieldValue = $field['FieldValue'];
            if (!is_null($fieldValue)) {
                $fieldType = $field['FieldType'];
                if (is_array($fieldType)) {
                    if ($this->_isApiModelClass($fieldType[0])) {
                        foreach ($fieldValue as $item) {
                            $xml .= '<' . $fieldName . '>';
                            $xml .= $item->_fieldsToXml();
                            $xml .= '</' . $fieldName . '>';
                        }
                    } else {
                        foreach ($fieldValue as $item) {
                            $xml .= '<' . $fieldName . '>';
                            $xml .= $this->_escapeXml($item);
                            $xml .= '</' . $fieldName . '>';
                        }
                    }
                } else {
                    if ($this->_isApiModelClass($fieldType)) {
                        $xml .= '<' . $fieldName . '>';
                        $xml .= $item->_fieldsToXml();
                        $xml .= '</' . $fieldName . '>';
                    } else {
                        $xml .= '<' . $fieldName . '>';
                        $xml .= $this->_escapeXml($fieldValue);
                        $xml .= '</' . $fieldName . '>';
                    }
                }
            }
        }
        return $xml;
    }

    protected function _fillFromHashTable(array $data) {
        foreach ($this->_fields as $fieldName => $field) {
            $fieldType = $field['FieldType'];   
            if (is_array($fieldType)) {
                if ($this->_isApiModelClass($fieldType[0])) {
                    if (array_key_exists($fieldName, $data)) { 
                        $elements = $data[$fieldName];
                        if (!$this->_isArray($elements)) {
                            $elements =  array($elements);    
                        }
                        if (count ($elements) >= 1) {
                            foreach ($elements as $element) {
                                $this->_fields[$fieldName]['FieldValue'][] = new $fieldType[0]($element);
                            }
                        }
                    } 
                } else {
                    if (array_key_exists($fieldName, $data)) {
                        $elements = $data[$fieldName];
                        if (!$this->_isArray($elements)) {
                            $elements =  array($elements);    
                        }
                        if (count ($elements) >= 1) {
                            foreach ($elements as $element) {
                                $this->_fields[$fieldName]['FieldValue'][] = $element;
                            }
                        }  
                    }
                }
            } else {
                if ($this->_isApiModelClass($fieldType)) {
                    if (array_key_exists($fieldName, $data)) {
                        $this->_fields[$fieldName]['FieldValue'] = new $fieldType($data[$fieldName]);
                    }   
                } else {
                    if (array_key_exists($fieldName, $data)) {
                        $this->_fields[$fieldName]['FieldValue'] = $data[$fieldName];
                    }
                }
            }
        }
    }

    private function _fillFromDOMElement(DOMElement $dom) {
        $xpath = new DOMXPath($dom->ownerDocument);
        $xpath->registerNamespace('a', $this->_getNamespace());
        foreach ($this->_fields as $fieldName => $field) {
            $fieldType = $field['FieldType'];   
            if (is_array($fieldType)) {
                if ($this->_isApiModelClass($fieldType[0])) {
                    $elements = $xpath->query("./a:$fieldName", $dom);
                    if ($elements->length >= 1) {
                        foreach ($elements as $element) {
                            $this->_fields[$fieldName]['FieldValue'][] = new $fieldType[0]($element);
                        }
                    } 
                } else {
                    $elements = $xpath->query("./a:$fieldName", $dom);
                    if ($elements->length >= 1) {
                        foreach ($elements as $element) {
                            $Text = $xpath->query('./text()', $element);
                            $this->_fields[$fieldName]['FieldValue'][] = $Text->item(0)->data;
                        }
                    }  
                }
            } else {
                if ($this->_isApiModelClass($fieldType)) {
                    $elements = $xpath->query("./a:$fieldName", $dom);
                    if ($elements->length == 1) {
                        $this->_fields[$fieldName]['FieldValue'] = new $fieldType($elements->item(0));
                    }   
                } else {
                    $element = $xpath->query("./a:$fieldName/text()", $dom);
                    if ($element->length == 1) {
                        $this->_fields[$fieldName]['FieldValue'] = $element->item(0)->data;
                    }
                }
            }
        }
    }

    protected function _prepareInput($data = null) {
        return $data;
    }

    public function __construct($data = null) {
        $modelSuffix = preg_replace('/^(.+_Model_)(Api_Model)_([a-zA-Z]+)_(.+)$/', '$2_$3', get_class($this));
        $modelSuffix = preg_replace('/(_.?)/e', "strtolower('$1')", $modelSuffix);
        $modelSuffix[0] = strtolower($modelSuffix[0]);
        $this->_modelClassPrefix = $this->_modulePrefix . '/' . $modelSuffix  . '_';
        if (!is_null($data)) {
            if ($this->_isHashTable($data)) {
                $this->_fillFromHashTable($data);
            } elseif ($this->_isDOMElement($data)) {
                $this->_fillFromDOMElement($data);
            } elseif ($this->_isApiObject($data)) {
            } else {
                Mage::helper('checkoutbyamazon')->throwException(
                    Mage::helper('checkoutbyamazon')->__('Unable to construct Amazon API model: %s from provided data. Please be sure to pass associative array or DOMElement.', get_class($this)),
                    null,
                    array('area' => $this->_area)
                );
            }
        }
    }


    public function __call($function, $params) {
        $prefix = array(substr($function, 0, 3), substr($function, 0, 5));
        if (in_array($prefix[0], array('get', 'set'))) {
            $method = $prefix[0];
            $key = substr($function, 3);
        } elseif ($prefix[1] == 'isset') {
            $method = $prefix[1];
            $key = substr($function, 5);
        } else {
            Mage::helper('checkoutbyamazon')->throwException(
                Mage::helper('checkoutbyamazon')->__('Invalid method %s::%s (%s)', get_class($this), $function, print_r($params, 1)),
                null,
                array('area' => $this->_area)
            );
        }

        switch ($method) {
            case 'get':
                $data = $this->_getData($key);
                return $data;

            case 'set':
                $result = $this->_setData($key, array_key_exists(0, $params) ? $params[0] : null);
                return $result;

            case 'isset':
                $result = $this->_issetData($key);
                return $result;
        }
    }

    /**
     * Returns XML representation of the object
     * 
     * @return string
     */
    public function toXML($rootNode) {
        $xml = '';
        $xml .= '<' . $rootNode . ' xmlns="' . $this->_getNamespace() . '">';
        $xml .= $this->_fieldsToXml();
        $xml .= '</' . $rootNode . '>';
        return $xml;
    }

}
