<?php
class Digiswiss_Soapsync_Block_Fototheke extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getFototheke()     
     { 
//         Mage::log("fototheke getFototheke");
        if (!$this->hasData('fototheke')) {
            $this->setData('fototheke', Mage::registry('fototheke'));
        }
        return $this->getData('fototheke');
        
    }
}