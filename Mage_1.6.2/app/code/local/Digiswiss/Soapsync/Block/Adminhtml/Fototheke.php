<?php
class Digiswiss_Soapsync_Block_Adminhtml_Fototheke extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
//     Mage::log('__construct Digiswiss_Soapsync_Block_Adminhtml_Fototheke');
    $this->_addButton('generate_products', array(
        'label'     => Mage::helper('soapsync')->__('Generate Products'),
        'onclick'   => "window.open('" . $this->getUrl('*/*/generate') . "')",
        'class'     => '',
    ));

    $this->_addButton('soap_connect', array(
        'label'     => Mage::helper('soapsync')->__('Render Images'),
        'onclick'   => "window.open('" . $this->getUrl('*/*/run') . "')",
        'class'     => '',
    ));

    $this->_addButton('soap_sync', array(
        'label'     => Mage::helper('soapsync')->__('Synchronize Data'),
        'onclick'   => "setLocation('" . $this->getUrl('*/*/update') . "')",
        'class'     => '',
    ));

    $this->_controller = 'adminhtml_fototheke';
    $this->_blockGroup = 'soapsync';
    $this->_headerText = Mage::helper('soapsync')->__('Manager. These products are registered in database.');
    $this->_addButtonLabel = Mage::helper('soapsync')->__('Refresh');
    parent::__construct();
	
  }
}