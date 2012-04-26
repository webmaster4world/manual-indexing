<?php

class Digiswiss_Filetrans_Model_Status extends Varien_Object
{
    const STATUS_ENABLED	= 1;
    const STATUS_DISABLED	= 2;

    static public function getOptionArray()
    {
        return array(
            self::STATUS_ENABLED    => Mage::helper('filetrans')->__('Enabled'),
            self::STATUS_DISABLED   => Mage::helper('filetrans')->__('Disabled')
        );
    }
}