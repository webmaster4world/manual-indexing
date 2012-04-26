<?php
class Digiswiss_Soapsync_Block_Ibramssync extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getIbramssync()     
     { 
        if (!$this->hasData('ibramssync')) {
            $this->setData('ibramssync', Mage::registry('ibramssync'));
        }
        return $this->getData('ibramssync');
        
    }
}