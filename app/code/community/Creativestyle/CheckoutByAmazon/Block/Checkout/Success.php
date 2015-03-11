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

class Creativestyle_CheckoutByAmazon_Block_Checkout_Success extends Creativestyle_CheckoutByAmazon_Block_Abstract {


    /**
     * Retrieve identifier of created order
     *
     * @return string
     * @deprecated after 1.4.0.1
     */
    public function getOrderId() {
        return $this->_getData('order_id');
    }

    /**
     * Check order print availability
     *
     * @return bool
     * @deprecated after 1.4.0.1
     */
    public function canPrint() {
        return $this->_getData('can_view_order');
    }

    /**
     * Get url for order detale print
     *
     * @return string
     * @deprecated after 1.4.0.1
     */
    public function getPrintUrl() {
        return $this->_getData('print_url');
    }

    /**
     * Get url for view order details
     *
     * @return string
     * @deprecated after 1.4.0.1
     */
    public function getViewOrderUrl() {
        return $this->_getData('view_order_id');
    }

    /**
     * See if the order has state, visible on frontend
     *
     * @return bool
     */
    public function isOrderVisible() {
        return (bool) $this->_getData('is_order_visible');
    }

    /**
     * Initialize data and prepare it for output
     */
    protected function _beforeToHtml() {
        $this->_prepareLastOrder();
        return parent::_beforeToHtml();
    }

    /**
     * Get last order ID from session, fetch it and check whether it can be viewed, printed etc
     */
    protected function _prepareLastOrder() {
        $orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        if ($orderId) {
            $order = Mage::getModel('sales/order')->load($orderId);
            if ($order->getId()) {
                $isVisible = !in_array($order->getState(), Mage::getSingleton('sales/order_config')->getInvisibleOnFrontStates());
                $this->addData(array(
                    'is_order_visible' => $isVisible,
                    'view_order_id' => $this->getUrl('sales/order/view/', array('order_id' => $orderId)),
                    'print_url' => $this->getUrl('sales/order/print', array('order_id' => $orderId)),
                    'can_print_order' => $isVisible,
                    'can_view_order' => Mage::getSingleton('customer/session')->isLoggedIn() && $isVisible,
                    'order_id' => $order->getIncrementId(),
                    'amazon_order_id' => $order->getPayment()->getLastTransId()
                ));
            }
        }
    }

    public function getAmazonOrderId() {
        return $this->_getData('amazon_order_id');
    }

    public function getWidgetWidth() {
        return Mage::getStoreConfig(self::XML_PATH_REVIEW_WIDGET_WIDTH);
    }

    public function getWidgetHeight() {
        return Mage::getStoreConfig(self::XML_PATH_REVIEW_WIDGET_HEIGHT);
    }

}
