<?php

class Digiswiss_Soapsync_Model_Mysql4_Fototheke extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        //Mage::log("Fototheke model mysql4 constructor called");
        $this->_init('soapsync/fototheke', 'item_id');
    }
	
}