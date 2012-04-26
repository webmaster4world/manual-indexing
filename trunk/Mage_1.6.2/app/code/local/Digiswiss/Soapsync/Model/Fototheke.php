<?php
class Digiswiss_Soapsync_Model_Fototheke extends Mage_Core_Model_Abstract
{
    
    protected $_id = 1;
    protected $_name = 'Image Renderer';
    
    public function _construct()
    {
//         Mage::register('soapsync_fototheke_model', $this);
        $this->_init('soapsync/fototheke');
    }
    public function getId() {
        return $this->_id;
    }
    public function getName() {
        return $this->_name;
    }
    public function getIdCollection($generate = false, $kind = 'PR-Plattform') {
//         return array(1, 2);
        $idCollection = array();
        $db_magento = Mage::helper('soapsync')->getConnection();
        if (!$generate) {
            $col = 'entity_id';
            $medialist = $db_magento->query("SELECT $col FROM aa_ibrams_media_metadata
                              WHERE aa_ibrams_media_metadata.identifier = 'schnittstellenfreigabe'
                              AND aa_ibrams_media_metadata.value LIKE '%$kind%'");                      
            foreach ($medialist as $row) 
            {
                $idCollection[] = $row[$col];
            }
        } else {
            $col = 'item_id';
            $medialist = $db_magento->query("SELECT aa_ibrams_media.* FROM aa_ibrams_media 
                                LEFT JOIN aa_ibrams_media_metadata ON aa_ibrams_media_metadata.entity_id = aa_ibrams_media.item_id
                                WHERE aa_ibrams_media_metadata.value LIKE '%$kind%' 
                                AND aa_ibrams_media_metadata.identifier='schnittstellenfreigabe'
                                AND aa_ibrams_media.mage_id IS NULL");                    
            foreach ($medialist as $row) 
            {
                if ($row['mage_id'] < 1) {
                    $idCollection[] = $row[$col];
                }
            }
        }
        return $idCollection;
    }
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