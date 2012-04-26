<?php

class Digiswiss_Soapsync_Block_Adminhtml_Ibramssync_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    
  public function __construct()
  {
      parent::__construct();
      $this->setId('ibramssyncGrid');
      $this->setDefaultSort('item_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
	 	  
  }

  
	// **** // trae las imagenes que no estan en base de datos...
  protected function _prepareCollection()
  {
    	$collection = Mage::getModel('soapsync/ibramssync')->getCollection();    	
    	$this->setCollection($collection);    
    	return parent::_prepareCollection();	
  }

  protected function _prepareColumns()
  {
    
      $this->addColumn('id', array(
          'header'    => Mage::helper('soapsync')->__('MAM Id'),
          'renderer'     =>'Digiswiss_Soapsync_Block_Adminhtml_Renderer_Id',
          'align'     =>'left',
          'width'    => '50px',
          'index'     => 'id'

      ));
		  
	    $this->addColumn('partno', array(
          'header'    => Mage::helper('soapsync')->__('Part No'),
          'renderer'     =>'Digiswiss_Soapsync_Block_Adminhtml_Renderer_Id',
          'align'     =>'left',
          'width'    => '50px',
          'index'     => 'efit_id'

      ));
		  
	    $this->addColumn('mage_id', array(
          'header'    => Mage::helper('soapsync')->__('Shop Id'),
          'renderer'     =>'Digiswiss_Soapsync_Block_Adminhtml_Renderer_Id',
          'align'     =>'left',
          'width'    => '50px',
          'index'     => 'mage_id'

      ));
		  
	    $this->addColumn('name', array(
          'header'    => Mage::helper('soapsync')->__('Title'),
          'renderer'     =>'Digiswiss_Soapsync_Block_Adminhtml_Renderer_Name',
          'align'     =>'left',
          'index'     => 'title'

      ));
		  
	    $this->addColumn('filename', array(
          'header'    => Mage::helper('soapsync')->__('Filename'),
          'renderer'     =>'Digiswiss_Soapsync_Block_Adminhtml_Renderer_Name',
          'align'     =>'left',
          'index'     => 'filename'

      ));
		  
      $this->addColumn('action',
          array(
              'header'    =>  Mage::helper('soapsync')->__('Action'),
              'width'     => '100',
              'type'      => 'action',
              'getter'    => 'getId',
              'actions'   => array(
                  array(
                      'caption'   => Mage::helper('soapsync')->__('copy'),
                      'url'       => array('base'=> '*/*/copy'),
                     'field'     => 'id'
                  )
              ),
              'filter'    => false,
              'sortable'  => false,
              'index'     => 'stores',
              'is_system' => true,
      ));
		
      return parent::_prepareColumns();
  } 

   	
	protected function _prepareMassaction()
    {
	
        $this->setMassactionIdField('item_id');
        $this->getMassactionBlock()->setFormFieldName('efitsync');
        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('soapsync')->__('Copy'),
             'url'      => $this->getUrl('*/*/massCopy'),
             'confirm'  => Mage::helper('soapsync')->__('Are you sure?')
        ));
        return $this;
    }
	

}
