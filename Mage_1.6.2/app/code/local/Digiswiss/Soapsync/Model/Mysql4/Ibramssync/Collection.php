<?php

class Digiswiss_Soapsync_Model_Mysql4_Ibramssync_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
// 	protected $_idst = 495; // id shot_type
//   protected $_idin = 510; // id image_name
//   protected $total;
    protected $_folder150 = 'jpg150';
  	protected $_folder300 = 'jpg300';
  	protected $_capt72 = 'Web-Version (72dpi)';
  	protected $_capt150 = 'Office-Version (150dpi)';
  	protected $_capt300 = 'Print-Version (300dpi)';	

    public function _construct()
    {
        parent::_construct();
        $this->_init('soapsync/ibramssync');
    }
    
//     public function getImages() {
//         Mage::log('Ibramssync Collection was called');
//         return 'collectionresult ibramssync';
//     }
		
	public function getImages(){
	  $array=array();
		try {
			$this->setConnection($this->getResource()->getReadConnection());
			$this->getSelect()
				->from(array('main_table'=>$this->getTable('catalog/product')),'*')
        ->join(array('ai'=>'catalog_product_entity_int'),
            "main_table.entity_id = ai.entity_id",
              'ai.attribute_id as attribute_id'
          )
				->join(array('av'=>'catalog_product_entity_varchar'), 
            "main_table.entity_id = av.entity_id",
            array(
              'main_table.entity_id as entity_id',
              'av.value as value'
              )
          ) 
        ->where("ai.attribute_id=$this->_idst and av.attribute_id=$this->_idin")
				->group(array('entity_id'));

			foreach	($this->getData() as $item){
				$array[]	= array('value'=>$item['value'], 'entity_id'=>$item['entity_id']);
				//Mage::log('Model img.:'.$item['value'].'  id:'.$item['entity_id']);
			}
		}catch(Exception $e){
			Mage::log($e->getMessage());
		}
				
		return $array;
	}
	public function returnItemId($entity_id){
      $this->setConnection($this->getResource()->getReadConnection());
			$this->getSelect()
				->from(array('main_table'=>'aa_ibrams_media'),'item_id')
				->where("mage_id=$entity_id");
			foreach ($this->getData() as $item) {          
          return $item['item_id'];
      }
      return false;
  }
// 	public function returnId($item_id){
// 	     Mage::log('ibramssync Collection -- Function returnId, param ' . $item_id);
//       $this->setConnection($this->getResource()->getReadConnection());
// 			$this->getSelect()
// 				->from(array('main_table'=>'aa_ibrams_media'),'mage_id')
// 				->where("item_id=$item_id");
// 			foreach ($this->getData() as $item) {          
//           return $item['mage_id'];
//       }
//       return false;
//   }
    
  public function makeProduct($id) {
      $this->setConnection($this->getResource()->getReadConnection());
			$this->getSelect()
				->from(array('main_table'=>'aa_ibrams_media'),'*')
				->where("item_id=$id");
			if ($result = $this->getData()) {
          $item = $result[0];
          Mage::log(serialize($item));
      }  
      $product = Mage::getModel('catalog/product');
      if ($item['efit_id']) {
          $addImageArr = array();
          $singleImage = false;
          $sku = $item['efit_id'];   	   
          if (!$hasId = $product->getIdBySku($sku)) { 
//               Mage::log('id: ' . $id);
//               $description = ($item['description'] == '') ? '<p>' . $item['title'] . '</p>' : $item['description'];
              $description = ($item['description'] == '') ? $item['title'] : $item['description'];
              $product->setStoreId(0); 	        
              $product->setTypeId('downloadable');
              $product->setAttributeSetId(27);  
              $categories = $this->getCarModelCategories($item['item_id']);
              $status = ($item['ibrams_status'] == 'Aktiv') ? Mage_Catalog_Model_Product_Status::STATUS_ENABLED  : Mage_Catalog_Model_Product_Status::STATUS_DISABLED ;
              $attrModel = Mage::getModel('soapsync/ibramsmetadata')->getCollection();
//               $attrArr = $attrModel->renderAttributeArray($id);
              $attrArr = array();
              Mage::log('attrArr: ' . serialize($attrArr));
              $productdata = array( 
                  'type_id' => 'downloadable',
                  'website_ids' => array(1),
                  'status' => $status,
                  'category_ids' => $categories,
                  'visibility' => 4,
                  'sku' => $sku,
                  'tax_class_id' => 0,
                  'weight' => 0,
                  'price' => 0,
                  'name' => $item['title'],
                  'description' => $description,
                  'short_description' => $item['title'],
                  'image_name' => $item['filename']
              );  
              $productdata = array_merge($productdata, $attrArr);
              Mage::log('productdata: ' . serialize($productdata));
              $product->addData($productdata);
              $product->setStockData(array('use_config_manage_stock' => 0, 'manage_stock' => 1, 'qty' => 1, 'is_in_stock' => 1, 'min_sale_qty' => 1, 'max_sale_qty' => 1));
              $addImageArr = array('small_image', 'thumbnail');
              $singleImage = true;
          }
          $jpg = str_replace(array('.JPG','.jpeg','.JPEG','.psd','.gif','.tif','.png','.bmp','.BMP'), '.jpg', $item['filename']);
          $file = Mage::getBaseDir('media') . DS . 'import/' . $jpg;
          if(file_exists($file)){
              $product->addImageToMediaGallery($file, array_merge(array('image'), $addImageArr), false, $singleImage);  
          } else {
              Mage::getSingleton('adminhtml/session')->addError('Could not load ' . $item['filename']);
          }
          try {
              $product->save();
              $this->updateId($item['item_id'], $product->getId(), $status, $item['version']);
              return $product->getId();
          } catch (Exception $e) {
              Mage::getSingleton('adminhtml/session')->addError($e->getMessage() . "\nCould not save MAM id ($id) sku () $item[title]");
          }
      } else {
          $skuFiledownload = Mage::getModel('soapsync/ibramsmetadata')->getCollection()->getSkuFiledownload($id);          
          Mage::log('sku_filedownload: ' . serialize($skuFiledownload));
          $skuArr = explode(';', $skuFiledownload); 
          $idArr = array();
          foreach ($skuArr as $value) {
              $categories = $this->getCarModelCategories($item['item_id']);
              $status = ($item['ibrams_status'] == 'Aktiv') ? Mage_Catalog_Model_Product_Status::STATUS_ENABLED  : Mage_Catalog_Model_Product_Status::STATUS_DISABLED ;
              $description = ($item['description'] == '') ? $item['title'] : $item['description'];
              $product->setStoreId(0); 	        
              $product->setTypeId('downloadable');  
              $productdata = array(
                  'type_id' => 'downloadable',
                  'attribute_set_id' => 28,
                  'website_ids' => array(1),
                  'status' => $status,
                  'category_ids' => $categories,
                  'visibility' => 4,
                  'sku' => trim($value),
                  'art_no' => trim($value),
                  'tax_class_id' => 0,
                  'weight' => 0,
                  'price' => 0,
                  'name' => $item['title'],
                  'description' => $description,
                  'short_description' => $item['title'],
                  'image_name' => $item['filename']
              );
              $product->addData($productdata);              
              $product->setStockData(array('use_config_manage_stock' => 0, 'manage_stock' => 1, 'qty' => 1, 'is_in_stock' => 1, 'min_sale_qty' => 1, 'max_sale_qty' => 1));
              $addImageArr = array('small_image', 'thumbnail');
              $singleImage = true;
              $jpg = str_replace(array('.JPG','.jpeg','.JPEG','.psd','.gif','.tif','.png','.bmp','.BMP'), '.jpg', $item['filename']);
              $file = Mage::getBaseDir('media') . DS . 'import/' . $jpg;
              if(file_exists($file)){
                  $product->addImageToMediaGallery($file, array_merge(array('image'), $addImageArr), false, $singleImage);  
              } else {
                  Mage::getSingleton('adminhtml/session')->addError('Could not load ' . $item['filename']);
              }
              try {
                  $product->save();
                  $this->updateId($item['item_id'], $product->getId(), $status, $item['version']);
                  $idArr[] = $product->getId();
              } catch (Exception $e) {
                  Mage::getSingleton('adminhtml/session')->addError($e->getMessage() . "\nCould not save MAM id ($id) sku () $item[title]");
              }
          } 
          return $idArr;
      }
      return false;
  }
    public function addDownloads($_item_id, $new = true) {
        $model = Mage::getModel('soapsync/ibramssync'); 
        $item_id = $model->getCollection()->returnItemId($_item_id);      
        $model->load($item_id);
				if (!$entity_id = $model->getMageId()) {
            Mage::getSingleton('adminhtml/session')->addError(
                      "Could not load product $item_id"
                  );
//             continue;
            if (!$new) return false;
        } 
				$type = 'links';
        $error = false;         					
				if (!$new) Mage::helper('soapsync')->delLinks($entity_id);  
        $filePath = Mage_Downloadable_Model_Link::getBasePath();
        $jpg = str_replace(array('.JPG','.jpeg','.JPEG','.psd','.gif','.tif','.png','.bmp','.BMP'), '.jpg', $model->getFilename());     				  
    		if (is_file(Mage::getBaseDir('media') . DS . 'import' . DS . $jpg)) {
            $uploader = new Digiswiss_File_Uploader($jpg);
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);                    
            try {
                $result = $uploader->save($filePath);
            } catch(Exception $e) {
                Mage::log($e->getMessage());
            }
  					Mage::helper('soapsync')->saveLinkModel($result['file'], $entity_id, $this->_capt72); 
        } else {
            $error = true;
            Mage::getSingleton('adminhtml/session')->addError(
                      "Downloadable 72dpi " . strtolower($model->getFilename()) . "  not found in media/import/"
                  );
        }          					
// 				if (is_file(Mage::getBaseDir('media') . DS . 'import' . DS . $this->_folder150 . DS . $jpg)) {          
//     				$uploader = new Digiswiss_File_Uploader($jpg, $this->_folder150);          
//             $uploader->setAllowRenameFiles(true);
//             $uploader->setFilesDispersion(true);
//             try {
//                 $result = $uploader->save($filePath);
//             } catch(Exception $e) {
//                 Mage::log($e->getMessage());
//             }          
//     				Mage::helper('soapsync')->saveLinkModel($result['file'], $entity_id, $this->_capt150);
//         } else {
//             $error = true;
//             Mage::getSingleton('adminhtml/session')->addError(
//                       "Downloadable 150dpi " . strtolower($model->getFilename()) . "  not found in $this->_folder150"
//                   );
//         }           					
				if (is_file(Mage::getBaseDir('media') . DS . 'import' . DS . $this->_folder300 . DS . $jpg)) {          
    				$uploader = new Digiswiss_File_Uploader($jpg, $this->_folder300);          
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            try {
                $result = $uploader->save($filePath);
            } catch(Exception $e) {
                Mage::log($e->getMessage());
            }          
    				Mage::helper('soapsync')->saveLinkModel($result['file'], $entity_id, $this->_capt300);
        } else {
            $error = true;
            Mage::getSingleton('adminhtml/session')->addError(
                      "Downloadable 300dpi " . $jpg . "  not found in $this->_folder300"
                  );
        } 
//         $model->setId($item_id)->delete();
        if (!$new && !$error) return true;
    }
    protected function getCarModelCategories($item_id) {
        $db = Mage::helper('soapsync')->getConnection();
  			$query = $db->query("SELECT value FROM aa_ibrams_media_metadata WHERE entity_id=$item_id AND identifier='modell'");
        if ($result = $query->fetchObject()) {
            $carmodel = $result->value;
            $collection = Mage::getModel('catalog/category')->getCollection()  
                ->setStore(0)  
                ->addAttributeToSelect('name');  
            $collection->getSelect()->where("path like '1/2/3/%'"); 
            foreach ($collection as $cat) {                  
                if ($cat->getName() == $carmodel) {  
//                     Mage::log($item_id . ' found: ' . $cat->getName() . '/' . $carmodel);
                    $categories = $cat->getPath();
                    return explode('/', substr($categories, 2, strlen($categories) - 2));
                } 
            }
        }
        return array();
    }  
    protected function updateId($item_id, $mage_id, $status, $version = 1) {
        $db = Mage::helper('soapsync')->getConnection();
  			$query = $db->query("UPDATE aa_ibrams_media SET mage_id=$mage_id, mage_status='$status', mage_image_version='$version' WHERE item_id=$item_id");
    }  
                
}
