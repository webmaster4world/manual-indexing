<?php

class Digiswiss_Soapsync_Model_Mysql4_Ibramssync extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the filetrans_id refers to the key field in your database table.
        //Mage::log("Ibramssync model mysql4 constructor called");
        $this->_init('soapsync/ibramssync', 'item_id');
    }
	
}