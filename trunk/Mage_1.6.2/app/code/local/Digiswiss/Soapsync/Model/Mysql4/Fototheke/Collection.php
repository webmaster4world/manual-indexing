<?php

class Digiswiss_Soapsync_Model_Mysql4_Fototheke_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	  protected $_idst = 495; // id shot_type
    protected $_idin = 510; // id image_name
    protected $total;
    public function _construct()
    {
        parent::_construct();
        $this->_init('soapsync/fototheke');
        
    }

  	
//   	public function getImages(){
//     	  $array=array();
//     		try {
//     			$this->setConnection($this->getResource()->getReadConnection());
//     			$this->getSelect()
//     				->from(array('main_table'=>$this->getTable('catalog/product')),'*')
//             ->join(array('ai'=>'catalog_product_entity_int'),
//                 "main_table.entity_id = ai.entity_id",
//                   'ai.attribute_id as attribute_id'
//               )
//     				->join(array('av'=>'catalog_product_entity_varchar'), 
//                 "main_table.entity_id = av.entity_id",
//                 array(
//                   'main_table.entity_id as entity_id',
//                   'av.value as value'
//                   )
//               ) 
//             ->where("ai.attribute_id=$this->_idst and av.attribute_id=$this->_idin")
//     				->group(array('entity_id'));
//     
//     			foreach	($this->getData() as $item){
//     				$array[]	= array('value'=>$item['value'], 'entity_id'=>$item['entity_id']);
//     				//Mage::log('Model img.:'.$item['value'].'  id:'.$item['entity_id']);
//     			}
//     		}catch(Exception $e){
//     			Mage::log($e->getMessage());
//     		}
//     				
//     		return $array;
//     }
    
    public function getLinkIds($entity_id) {    
        $this->setConnection($this->getResource()->getReadConnection());
  			$this->getSelect()
  				->from(array('main_table'=>$this->getTable('downloadable/link')),'*')
          ->where("main_table.product_id=$entity_id");
        $array = array();
  			foreach	($this->getData() as $item){
  				$array[]	= $item['link_id'];
  			} 
  			return $array;
    }
}
