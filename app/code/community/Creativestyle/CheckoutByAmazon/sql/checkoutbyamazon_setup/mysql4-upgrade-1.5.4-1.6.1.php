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

$installer = $this;

$installer->startSetup();

$installer->run("DROP TABLE IF EXISTS {$this->getTable('checkoutbyamazon/log_iopn_order')};");

$installer->run("DROP TABLE IF EXISTS {$this->getTable('checkoutbyamazon/log_feed_order')};");

$installer->run("DROP TABLE IF EXISTS {$this->getTable('checkoutbyamazon/log_report_order')};");

$installer->run("DROP TABLE IF EXISTS {$this->getTable('checkoutbyamazon/log_exception_order')};");

$installer->run("DROP TABLE IF EXISTS {$this->getTable('checkoutbyamazon/log_api_order')};");

$installer->endSetup();
