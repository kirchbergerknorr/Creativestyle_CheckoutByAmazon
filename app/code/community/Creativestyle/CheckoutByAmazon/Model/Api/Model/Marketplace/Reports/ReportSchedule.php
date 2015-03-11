<?php

/**
 * Amazon Marketplace Reports API: ReportSchedule data type model
 *
 * Fields:
 * <ul>
 * <li>ReportType: string</li>
 * <li>Schedule: string</li>
 * <li>ScheduledDate: DateTime</li>
 * </ul>
 *
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
class Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_ReportSchedule extends Creativestyle_CheckoutByAmazon_Model_Api_Model_Marketplace_Reports_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'ReportType' => array('FieldValue' => null, 'FieldType' => 'string'),
            'Schedule' => array('FieldValue' => null, 'FieldType' => 'string'),
            'ScheduledDate' => array('FieldValue' => null, 'FieldType' => 'DateTime')
        );
        parent::__construct($data);
    }

}
