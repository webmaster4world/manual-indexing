<?php
class Digiswiss_Soapsync_Block_Adminhtml_Ibramssync extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    
    $this->_controller = 'adminhtml_ibramssync';
    $this->_blockGroup = 'soapsync';
    $this->_headerText = Mage::helper('soapsync')->__('Images Manager. These images are registered in database.');
    $this->_addButtonLabel = Mage::helper('soapsync')->__('Refresh');
    parent::__construct();
	
  }
}