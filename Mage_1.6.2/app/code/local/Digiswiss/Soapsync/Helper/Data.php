<?php

class Digiswiss_Soapsync_Helper_Data extends Mage_Core_Helper_Abstract
{

protected $result = array();
protected $_mainTable;
public $valdir = array();
  
    public function loadImages() {
        $this->truncateTable('aa_ibrams_media');
        $this->truncateTable('aa_ibrams_media_metadata');
        $ibramssoap = new Ibrams_Soap();    
        $searchlist = array(
            array('searchfield' => 'schnittstellenfreigabe', 'value' => 'PR-Plattform')
        );
        $medialist = $ibramssoap->searchMedia($searchlist);
        if ($medialist && isset($medialist->item)) {
            $res = $this->parseMedialist($medialist);
        }
    }
    
    public function getConnection() {
        $config = Mage::getConfig()->getResourceConnectionConfig('core_write');
        $dbConfig = array(
        		'host'      => $config->host,
        		'username'  => $config->username,
        		'password'  => $config->password,
        		'dbname'    => $config->dbname,
        		'driver_options'=> array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8')
        );
        return Zend_Db::factory('Pdo_Mysql', $dbConfig);    
    }
    
    private function truncateTable($table) {
        $db_magento = $this->getConnection();
        try {
            $result = $db_magento->query("TRUNCATE $table");
        } catch (Exception $e) {
            Mage::log("Truncate $table: " . $e->getMessage());
        }
    }
    
    private function parseMedialist($medialist) {
        $db_magento = $this->getConnection();
        $storeId = 0;
        if(is_array($medialist->item) && count($medialist->item) > 0)
        {
            foreach ($medialist->item as $item) 
            {
                $keyArr = array();
                $valArr = array();
                $metaArr = array();
                $efitproductid = '';
                $ibramsstatus = '';
                foreach ($item as $key => $value) {
                    if (!is_object($value)) {
                        $keyArr[] = $key;
                        $valArr[] = addslashes($value);
                        if ($key == 'id') {
                            $iid = $value;
                        }
                    } else {
                        if (is_array($value->item) && count($value->item) > 0) {
                            foreach ($value->item as $val) {                            
                                $ident = (isset($val->value)) ? $val->identifier : '' ;
                                $name = (isset($val->value)) ? $val->name : '' ;
                                $vval = (isset($val->value)) ? addslashes($val->value) : '' ;
                                $metaArr[] = array($ident, $name, $vval);
                                switch ($val->identifier) {
                                    case 'efitproductid':
                                        $efitproductid = $val->value;
                                        break;
                                    case 'status':
                                        $ibramsstatus = addslashes($val->value);
                                }
                            }
                        }
                    }
                }        
                $res = $db_magento->query("INSERT INTO aa_ibrams_media 
                                          (item_id, " . implode(",", $keyArr) . ", efit_id, ibrams_status) 
                                          VALUES (NULL, '" . implode("','", $valArr) . "', '$efitproductid', '$ibramsstatus')");
                $id = $db_magento->lastInsertId();
                foreach ($metaArr as $v) {
                    $res = $db_magento->query("INSERT INTO aa_ibrams_media_metadata 
                                              (meta_id, entity_id, id, sku, identifier, name, value)
                                              VALUES
                                              (NULL, '$id', '$iid', '', '" . implode("','", $v) . "')");
                }
            }
            $aCatalogProducts = Mage::getModel('catalog/product')->getCollection()
            ->addStoreFilter($storeId)
            ->addAttributeToSelect(array('sku', 'status', 'base_image'));            
            foreach ($aCatalogProducts as $_product) 
            {
                $id = $_product->getId();
                $art_no = $_product->sku;
                $sku = $_product->sku;
//                 $querystrarr = array();
//                 $querystrarr[] = " mage_status='$_product->status'";
//                 $querystrarr[] = " mage_image_version='$_product->image_version'";
//                 $querystr = (count($querystrarr) > 1) ? ', ' . implode(',', $querystrarr) : '' ;
//                 if ($_product->fototheke_product != 1) {
//                     if ($art_no == $sku) {
                        try {
                            $res = $db_magento->query("UPDATE aa_ibrams_media SET mage_id='$id', mage_status='$_product->status' WHERE efit_id='$art_no'");
                        } catch(Exception $e) {
                            Mage::log($e->getMessage());
                        }
//                     } 
//                     else {
//                         try {
//                             $res = $db_magento->query("UPDATE aa_ibrams_media SET mage_id='$id'$querystr WHERE efit_id='$sku'");
//                         } catch(Exception $e) {
//                             Mage::log($e->getMessage());
//                         }
//                     }
//                 }
            }
//             $aFotthekeProducts = Mage::getModel('catalog/product')->getCollection()
//             ->addStoreFilter($storeId)
//             ->addAttributeToFilter('sku', array('like' => 'fototheke%'))
//             ->addAttributeToSelect(array('sku', 'status', 'image_version'));            
//             foreach ($aFotthekeProducts as $_product) 
//             {
//                 $id = $_product->getId();
//                 $ibramsid = substr($_product->sku, 10, strlen($_product->sku) - 9);
//                 $querystrarr = array();
//                 if ($_product->status) $querystrarr[] = " mage_status='$_product->status'";
//                 if ($_product->image_version) $querystrarr[] = " mage_image_version='$_product->image_version'";
//                 $querystr = (count($querystrarr)) ? ', ' . implode(',', $querystrarr) : '' ;
//                 try {
//                     Mage::log($id . "/" . $querystr . "/" . $ibramsid);
//                     $res = $db_magento->query("UPDATE aa_ibrams_media SET mage_id='$id'$querystr WHERE id='$ibramsid'");
//                 } catch(Exception $e) {
//                     Mage::log($e->getMessage());
//                 }
//             }   

            return true; 
        }
        return false;
    }

//   	
//     public function getAllConfAttributes() {
// //         $db_connection = $this->getConnection();
// //         $query = 
//         $product = Mage::getModel('catalog/product');
//         $attributes = Mage::getResourceModel('eav/entity_attribute_collection')
//             ->setEntityTypeFilter($product->getResource()->getTypeId())
//             ->setAttributeSetFilter(29)
//             ->addHasOptionsFilter() 
//             ->load(false);
//         $arr = array('' => '');
//         foreach ($attributes as $attribute) {
//             if ($attribute->getApplyTo() == 'configurable') {
//                 $arr[$attribute->getAttributeCode()] = $attribute->getFrontendLabel();
//             }
//         }
//         return $arr;
//     }
    
    public function saveLinkModel($file, $id, $title) {
        $linkmodel = Mage::getModel('downloadable/link');
        $linkmodel->setStoreId(0);
        $linkmodel->setWebsiteId(1);
        $linkmodel->setNumberOfDownloads(0);
        $linkmodel->setIsSharable(2);
        $linkmodel->setLinkFile($file);
        $linkmodel->setLinkType('file');
  			$linkmodel->setProductId($id); 
  			$linkmodel->setTitle($title); 
  			$linkmodel->setPrice("0.00");
        try {
            $linkmodel->save();
        } catch(Exception $e) {
            Mage::log($e->getMessage());
        }
    }
  
    public function delLinks($entity_id) {
        try {          
      			$array = Mage::getModel('soapsync/fototheke')->getCollection()->getLinkIds($entity_id);
      			$vals = implode(',', $array);
            $linkmodel = Mage::getModel('downloadable/link');
            foreach ($array as $value) {
                $linkmodel->setId($value)->delete();
            }
    		}catch(Exception $e){
    			 Mage::log($e->getMessage());
    		}
    }
 

}
