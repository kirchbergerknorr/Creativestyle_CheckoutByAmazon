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

$installer = $this;

$installer->startSetup();

$installer->run("
    ALTER TABLE {$this->getTable('checkoutbyamazon/log_feed')} ADD `store_id` smallint(6) unsigned NOT NULL default 0,
    ADD FOREIGN KEY (`store_id`) REFERENCES {$this->getTable('core_store')} (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE;
");

$installer->endSetup();
