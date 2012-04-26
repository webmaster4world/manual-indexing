<?php

class Digiswiss_Soapsync_Model_Mysql4_Ibramsmetadata_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('soapsync/ibramsmetadata');
        $this->setConnection($this->getResource()->getReadConnection());
    }
    
    public function getSkuFiledownload($id) {
  			Mage::log('id: ' . $id);
        $this->getSelect()
  				->from(array('mt' => $this->getTable('ibramsmetadata')),'*')
  				->where("mt.entity_id=$id AND mt.identifier='sku_filedownload'");
				foreach ($this->getData() as $item) {
    				if ($item['identifier'] == 'sku_filedownload') {
    				    Mage::log('getSkuFiledownload: ' . $item['value']);
                return $item['value'];
            }
				}
				
//   			return $this->getData();
    }
    
    public function renderAttributeArray($id) {
        $metaArr = array();
        $attrArr = array();
  			$this->getSelect()
//   				->from(array('metadata'=>'aa_ibrams_media_metadata'),'*')
  				->from(array('ibramsmetadata'=>$this->getTable('ibramsmetadata')),'*')
  				->where("ibramsmetadata.entity_id=$id");
  			try {
            foreach ($this->getData() as $item) {
                $metaArr[$item['identifier']] = $item['value'];
//                 Mage::log('Soapsync Metadata: ' . $item['identifier'] . '/' . $item['value']);
                if (($item['identifier'] == 'download_attribute') && ($item['value'] != '')) {
                    $attrValArr = explode('/', $item['value']);
                    /* todo: hardcoded attribute_set_id's */
                    $attrArr['attribute_set_id'] = ($attrValArr[0] == 'Media') ? 29 : 27 ;  
                    /* todo */
                    switch ($attrValArr[0]) {
                        case 'Photo':
                            $attrArr['shot_type'] = $attrValArr[1];
                            $photo = true;
                            break;
                        case 'Unternehmen':
                            $attrArr['unternehmen_photo'] = $attrValArr[1];
                            break;
                        case 'Media':
                            $attrArr['media_type'] = $attrValArr[1];
                            break;
                    }
                    
                }
            }
            if (isset($photo)) {
                if (isset($metaArr['farbenton']) &&($metaArr['farbenton'] != 'Keine')) $attrArr['color'] = $metaArr['farbenton'];
                switch($attrValArr[1]) {
                    case 'Exterior':
                        $attrArr['shot_aussen'] = $attrValArr[2];
                        break;
                    case 'Technik':
                        $attrArr['shot_technik'] = $attrValArr[2];
                        break;
                    case 'Details':
                        $attrArr['shot_detail'] = $attrValArr[2];
                        break;
                }
            }
            return $attrArr;
        } catch(Exception $e) {
            Mage::log('Soapsync Metadata error: ' . $e->getMessage());
        } 
        return false;       
    }
                
}
