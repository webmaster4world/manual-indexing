<?php

class Digiswiss_Soapsync_Model_Mysql4_Ibramsmetadata extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('soapsync/ibramsmetadata', 'meta_id');
    }
	
}