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

if (version_compare(Mage::helper('checkoutbyamazon')->getMagentoVersion(), '1.4.2') > 0) {

    $statuses = array(
        'pending_amazon' => Mage::getConfig()->getNode('global/sales/order/statuses/pending_amazon')->asArray()
    );

    foreach ($statuses as $statusCode => $statusInfo) {
        $data = array('status' => $statusCode, 'label' => (isset($statusInfo['label']) ? $statusInfo['label'] : ''));
        $installer->getConnection()->insert($installer->getTable('sales/order_status'), $data);
        $data = array('status' => $statusCode, 'state' => 'pending_payment', 'is_default' => 0);
        $installer->getConnection()->insert($installer->getTable('sales/order_status_state'), $data);
    }
}

$installer->run("
    -- DROP TABLE IF EXISTS {$this->getTable('checkoutbyamazon/log_iopn')};
    CREATE TABLE {$this->getTable('checkoutbyamazon/log_iopn')} (
        `log_id` int(10) unsigned NOT NULL auto_increment,
        `notification_type` varchar(255) NOT NULL default '',
        `uuid` varchar(255) NOT NULL default '',
        `notification_content` longtext NULL,
        `processing_result` varchar(64) NULL,
        `creation_time` timestamp NOT NULL,
        PRIMARY KEY (`log_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->run("
    -- DROP TABLE IF EXISTS {$this->getTable('checkoutbyamazon/log_feed')};
    CREATE TABLE {$this->getTable('checkoutbyamazon/log_feed')} (
        `log_id` int(10) unsigned NOT NULL auto_increment,
        `store_id` smallint(6) unsigned NOT NULL default 0,
        `feed_type` varchar(255) NOT NULL default '',
        `submission_id` varchar(64) NOT NULL default '',
        `feed_content` longtext NULL,
        `processing_status` varchar(64) NULL,
        `processing_result` longtext NULL,
        `creation_time` timestamp NOT NULL,
        PRIMARY KEY (`log_id`),
        FOREIGN KEY (`store_id`) REFERENCES {$this->getTable('core/store')} (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->run("
    -- DROP TABLE IF EXISTS {$this->getTable('checkoutbyamazon/log_report')};
    CREATE TABLE {$this->getTable('checkoutbyamazon/log_report')} (
        `log_id` int(10) unsigned NOT NULL auto_increment,
        `report_type` varchar(255) NOT NULL default '',
        `report_request_id` varchar(64) NOT NULL default '',
        `report_id` varchar(64) NOT NULL default '',
        `report_content` longtext NULL,
        `creation_time` timestamp NOT NULL,
        PRIMARY KEY (`log_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->run("
    -- DROP TABLE IF EXISTS {$this->getTable('checkoutbyamazon/log_exception')};
    CREATE TABLE {$this->getTable('checkoutbyamazon/log_exception')} (
        `log_id` int(10) unsigned NOT NULL auto_increment,
        `message` longtext NOT NULL default '',
        `error_code` varchar(255) NOT NULL default '',
        `stack_trace` longtext NULL,
        `area` varchar(255) NOT NULL default '',
        `request_id` varchar(255) NULL default NULL,
        `creation_time` timestamp NOT NULL,
        PRIMARY KEY (`log_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->run("
    -- DROP TABLE IF EXISTS {$this->getTable('checkoutbyamazon/log_api')};
    CREATE TABLE {$this->getTable('checkoutbyamazon/log_api')} (
        `log_id` int(10) unsigned NOT NULL auto_increment,
        `host` varchar(255) NOT NULL default '',
        `action` varchar(255) NOT NULL default '',
        `request_method` varchar(64) NOT NULL default 'GET',
        `headers` text NULL,
        `get_data` text NULL,
        `post_data` longtext NULL,
        `file_data` longtext NULL,
        `response_code` varchar(64) NULL,
        `response` longtext NULL,
        `creation_time` timestamp NOT NULL,
        PRIMARY KEY (`log_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();
