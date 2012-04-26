<?php

class Digiswiss_Soapsync_Adminhtml_FotothekeController extends Mage_Adminhtml_Controller_action
{
	
  protected $_folder150 = 'jpg150';
	protected $_folder300 = 'jpg300';
	protected $_capt72 = 'Web-Version (72dpi)';
	protected $_capt150 = 'Office-Version (150dpi)';
	protected $_capt300 = 'Print-Version (300dpi)';
	
	protected function _initAction() {
// 	  Mage::log('init Fototheke');
		$this->loadLayout()
			->_setActiveMenu('soapsync/fototheke')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('sSync'), Mage::helper('adminhtml')->__('Fototheke Manager'));
		
		return $this;
	}   

	public function indexAction() {
// 	  Mage::log('indexAction Fototheke');
		$this->_initAction()
			->renderLayout();
	}
	
	public function newAction(){	
// 	  Mage::log('newAction Fototheke');
		//Mage::helper('soapsync')->loadImages();
		$this->_redirect('*/*/');
	}
	public function updateAction() {
      Mage::helper('soapsync')->loadImages();
		  $this->_redirect('*/*/');
  }
	public function copyAction() {
// 	   Mage::log("image copy");
// 	   $imagemodel = Mage::getModel('soapsync/ibramssync');
// 	   $imagename = $imagemodel->getImageName('090825_11');
// 	   Mage::log("image: " . $imagename);
      $this->_redirect('*/*/');
  }
  public function massImportAction() {
      $fotothekeIds = $this->getRequest()->getParam('fototheke');
      if(!is_array($fotothekeIds)) {
           Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
      } else {
            try {
				        $filePath = Mage_Downloadable_Model_Link::getBasePath();
				        $i = 0;
                foreach ($fotothekeIds as $fotothekeId) {  
                    $model = Mage::getModel('soapsync/ibramssync');
                    $added =  $model->getCollection()->addDownloads($fotothekeId, false); 
          					if ($added) {
          					    $productmodel = Mage::getModel('catalog/product');
                        $product = $productmodel->load($fotothekeId);
                        $product->setStockData(array('use_config_manage_stock' => 1, 'manage_stock' => 1, 'qty' => 1, 'is_in_stock' => 1, 'min_sale_qty' => 0, 'max_sale_qty' => 1));
                        $product->setLinksPurchasedSeparately(1);
                        $product->setHasOptions(1);
                        $product->setRequiredOptions(1);
                        $product->save();
                        $i++;   
                    }
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully copied to filesystem and links saved to Database', $i
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
  
      }
      $this->_redirect('*/*/');
  }
  public function massImageAction() {  
      $fotothekeIds = $this->getRequest()->getParam('fototheke');
      if(!is_array($fotothekeIds)) {
           Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
      } else {
          $i = 0;
          $productmodel = Mage::getModel('catalog/product');
          $db = Mage::helper('soapsync')->getConnection();
          foreach ($fotothekeIds as $fotothekeId) {
              $product = $productmodel->load($fotothekeId);
//               Mage::log('sku: ' . $product->getSku());
//               Mage::log('id: ' . $fotothekeId);
              try {
                  $res = $db->query("SELECT value as val FROM catalog_product_entity_media_gallery WHERE entity_id='$fotothekeId'");
//                   $product->setStoreId(0);
//                   $images = $product->getMediaGalleryImages();
              } catch (Exception $e) {
                  Mage::log('getMediaGalleryImages: ' . $e->getMessage());
              } 
              if (!is_object($res->fetchObject())) {
                  $addImageArr = array('small_image', 'thumbnail');
                  $res = $db->query("SELECT filename as fn FROM aa_ibrams_media WHERE mage_id='$fotothekeId'");
                  $obj = $res->fetchObject();
                  if ($obj) {
                      $iFilename = $obj->fn;
//                       Mage::log('iFilename: ' . $iFilename);
                      $jpg = str_replace(array('.JPG','.jpeg','.JPEG','.psd','.gif','.tif','.png','.bmp','.BMP'), '.jpg', $iFilename);
                      $file = Mage::getBaseDir('media') . DS . 'import/' . $jpg;
                      if(file_exists($file)){
                          $product->addImageToMediaGallery($file, array_merge(array('image'), $addImageArr), false, true); 
                          $product->save(); 
                      } else {
                          Mage::getSingleton('adminhtml/session')->addError('Image not available in import folder for id ' . $fotothekeId);
                      }
                  } else {
                      Mage::getSingleton('adminhtml/session')->addError('Image not available for id ' . $fotothekeId);
                  }
              } else {
                  Mage::getSingleton('adminhtml/session')->addError('Image already available in shop for id ' . $fotothekeId);
              }
              $i++;
          }  
          try {
//               Mage::log(serialize($fotothekeIds));
              Mage::getSingleton('adminhtml/session')->addSuccess(
                  Mage::helper('adminhtml')->__(
                      'Total of %d image(s) were successfully copied to filesystem and saved to Database', $i
                  )
              );
          } catch (Exception $e) {
              Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
          }
          
          $this->_redirect('*/*/');
      }
  }
  public function massDocumentimageAction() {  
      $fotothekeIds = $this->getRequest()->getParam('fototheke');
      if(!is_array($fotothekeIds)) {
           Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
      } else {
          $i = 0;
          $productmodel = Mage::getModel('catalog/product');
          $db = Mage::helper('soapsync')->getConnection();
          foreach ($fotothekeIds as $fotothekeId) {
              $product = $productmodel->load($fotothekeId);
              try {
                  $res = $db->query("SELECT value as val FROM catalog_product_entity_media_gallery WHERE entity_id='$fotothekeId'");
              } catch (Exception $e) {
                  Mage::log('getMediaGalleryImages: ' . $e->getMessage());
              } 
              if (!is_object($res->fetchObject())) {
                  $addImageArr = array('small_image', 'thumbnail');
                  $res = $db->query("SELECT entity_id as id FROM aa_ibrams_media_metadata WHERE value LIKE '%" . $product->getSku() . "%'");
                  $obj = $res->fetchObject();
                  if ($obj) {
                      $res = $db->query("SELECT filename as fn FROM aa_ibrams_media WHERE item_id='" . $obj->id . "'");
                      $obj = $res->fetchObject();
                      if ($obj) {
                          $iFilename = $obj->fn;
//         Mage::log('iFilename: ' . $iFilename);
                          $jpg = str_replace(array('.JPG','.jpeg','.JPEG','.psd','.gif','.tif','.png','.bmp','.BMP'), '.jpg', $iFilename);
                          $file = Mage::getBaseDir('media') . DS . 'import/' . $jpg;
                          if(file_exists($file)){
                              $product->addImageToMediaGallery($file, array_merge(array('image'), $addImageArr), false, true); 
                              $product->save(); 
                          } else {
                              Mage::getSingleton('adminhtml/session')->addError('Image not available in import folder for id ' . $fotothekeId);
                          }
                      }
                  } else {
                      Mage::getSingleton('adminhtml/session')->addError('Image not available for id ' . $fotothekeId);
                  }
              } else {
                  if ($product->getAttributeSetId() == 28) {                      
                      $sku = $product->getSku();
                      $res = $db->query("SELECT aa_ibrams_media.* FROM aa_ibrams_media 
                                LEFT JOIN aa_ibrams_media_metadata ON aa_ibrams_media_metadata.entity_id = aa_ibrams_media.item_id
                                WHERE aa_ibrams_media_metadata.identifier='sku_filedownload' 
                                AND aa_ibrams_media_metadata.value LIKE '%$sku%'");
                      $file = '';
                      foreach ($res as $item) {
                          $jpg = str_replace(array('.JPG','.jpeg','.JPEG','.psd','.gif','.tif','.png','.bmp','.BMP'), '.jpg', $item['filename']);
                          $file = Mage::getBaseDir('media') . DS . 'import/' . $jpg;
                      }
                      if(file_exists($file)){
                          $product->addImageToMediaGallery($file, array('image'), false, false); 
                          $product->save(); 
                      } else {
                          Mage::getSingleton('adminhtml/session')->addError('Image not available in import folder for id ' . $fotothekeId);
                      }
                  } else {                 
                      Mage::getSingleton('adminhtml/session')->addError('Image available, but id ' . $fotothekeId . ' has not attribut set document');
                  }
              }
              $i++;
          }  
          try {
//               Mage::log(serialize($fotothekeIds));
              Mage::getSingleton('adminhtml/session')->addSuccess(
                  Mage::helper('adminhtml')->__(
                      'Total of %d image(s) were successfully copied to filesystem and saved to Database', $i
                  )
              );
          } catch (Exception $e) {
              Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
          }
          
          $this->_redirect('*/*/');
      }
  }
  
    public function runAction()
    {
//         $this->_initProfile();
        #$this->loadLayout();

        #$this->_setActiveMenu('system/convert');

        #$this->_addContent(
        #    $this->getLayout()->createBlock('adminhtml/system_convert_profile_run')
        #);
        $this->getResponse()->setBody($this->getLayout()->createBlock('soapsync/adminhtml_fototheke_soap_run')->toHtml());
        $this->getResponse()->sendResponse();

        #$this->renderLayout();
    }

    public function batchRunAction()
    {
        if ($this->getRequest()->isPost()) {
            $batchId = $this->getRequest()->getPost('batch_id',0);
            $rowIds  = $this->getRequest()->getPost('rows');
            $batchModel = Mage::getModel('soapsync/fototheke');
            if (!$batchModel->getId()) {
                return ;
            }
            if (!is_array($rowIds) || count($rowIds) < 1) {
                return ;
            }
            $batchImportModel = Mage::getModel('soapsync/ibramssync');            
            $errors = array();
            $saved  = 0;
            foreach ($rowIds as $importId) {
                $batchImportModel->load($importId);
                if (!$batchImportModel->getId()) {
                    $errors[] = Mage::helper('dataflow')->__('Skip undefined row');
                    continue;
                }
                try {
                    $batchImportModel->getAllImages($importId);
                }
                catch (Exception $e) {
                    $errors[] = $e->getMessage();
                    continue;
                }
                $saved ++;
            }
            $result = array(
                'savedRows' => $saved,
                'errors'    => $errors
            );
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        } else {
            return;
        }
    }
    public function batchFinishAction()
    {
        if ($batchId = $this->getRequest()->getParam('id')) {
                $result = array();
                try {
//                     $batchModel->beforeFinish();
                }
                catch (Mage_Core_Exception $e) {
                    $result['error'] = $e->getMessage();
                }
                catch (Exception $e) {
                    $result['error'] = Mage::helper('adminhtml')->__('Error while finished process. Please refresh cache');
                }
//                 $batchModel->delete();
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }
  
    public function generateAction()
    {
        $this->getResponse()->setBody($this->getLayout()->createBlock('soapsync/adminhtml_fototheke_soap_generate')->toHtml());
        $this->getResponse()->sendResponse();
    }

    public function batchGenerateAction()
    {
        if ($this->getRequest()->isPost()) {
            $batchId = $this->getRequest()->getPost('batch_id',0);
            $rowIds  = $this->getRequest()->getPost('rows');
            $batchModel = Mage::getModel('soapsync/fototheke');
            if (!$batchModel->getId()) {
                return ;
            }
            if (!is_array($rowIds) || count($rowIds) < 1) {
                return ;
            }
            $batchImportModel = Mage::getModel('soapsync/ibramssync');            
            $errors = array();
            $saved  = 0;
            foreach ($rowIds as $importId) {
                $batchImportModel->load($importId);
                if (!$batchImportModel->getId()) {
                    $errors[] = Mage::helper('dataflow')->__('Skip undefined row');
                    continue;
                }
                try {
                    $batchImportModel->getCollection()->makeProduct($importId);
                }
                catch (Exception $e) {
                    $errors[] = $e->getMessage();
                    continue;
                }
                $saved ++;
            }

            $result = array(
                'savedRows' => $saved,
                'errors'    => $errors
            );
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }

    public function generateEndAction()
    {
        if ($batchId = $this->getRequest()->getParam('id')) {
                $result = array();
                try {
//                     $batchModel->beforeFinish();
                }
                catch (Mage_Core_Exception $e) {
                    $result['error'] = $e->getMessage();
                }
                catch (Exception $e) {
                    $result['error'] = Mage::helper('adminhtml')->__('Error while finished process. Please refresh cache');
                }
//                 $batchModel->delete();
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
//             }
        }
    }

// 	
// 	public function deleteAction() {
// 		if( $this->getRequest()->getParam('id') > 0 ) {
// 			try {
// 				$model = Mage::getModel('filetrans/filetrans');
// 				$model->load($this->getRequest()->getParam('id'));
// 				unlink('media/catalog/product'. $model->getFilename());
// 				$model->setId($this->getRequest()->getParam('id'))->delete();
// 
// 				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
// 				$this->_redirect('*/*/');
// 			} catch (Exception $e) {
// 				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
// 				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
// 			}
// 		}
// 		$this->_redirect('*/*/');
// 	}
// 
//     public function massDeleteAction() {
//         $filetransIds = $this->getRequest()->getParam('filetrans');
//         if(!is_array($filetransIds)) {
// 			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
//         } else {
//             try {
// 				$model = Mage::getModel('filetrans/filetrans');
//                 foreach ($filetransIds as $filetransId) {
// 					$model->load($filetransId);
// 					unlink('media/catalog/product'. $model->getFilename());
// 					$model->setId($filetransId)->delete();
//                 }
//                 Mage::getSingleton('adminhtml/session')->addSuccess(
//                     Mage::helper('adminhtml')->__(
//                         'Total of %d record(s) were successfully deleted', count($filetransIds)
//                     )
//                 );
//             } catch (Exception $e) {
//                 Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
//             }
//         }
//         $this->_redirect('*/*/index');
//     }
	  
// 	public function copyAction() {
// 	 	if( $this->getRequest()->getParam('id') > 0 ) {
//   			try {
//   			  $filetransId = $this->getRequest()->getParam('id');
//   				$model = Mage::getModel('filetrans/downloads');
//   				$model->load($filetransId);
//   				$entity_id = $model->getEntityId();
//   					        
//           $filePath = Mage_Downloadable_Model_Link::getBasePath();
//           $uploader = new Digiswiss_File_Uploader($model->getFilename(), $this->_folder);
//           $uploader->setAllowRenameFiles(true);
//           $uploader->setFilesDispersion(true);
//           try {
//               $result = $uploader->save($filePath);
//           } catch(Exception $e) {
//               Mage::log($e->getMessage());
//           }
// 					$model->load($filetransId);
//   				$entity_id = $model->getEntityId();
//   				Mage::helper('filetrans')->delLinks($entity_id);
//   				$productName = '';//  Mage::getModel('catalog/product')->load($entity_id)->getTitle();
//   				Mage::helper('filetrans')->saveLinkModel($result['file'], $entity_id, $productName . " 300 dpi");
//   				
//   				if (is_file($this->_basepath . DS . $this->_folder72 . DS . strtolower($model->getFilename()))) {          
//       				$uploader = new Digiswiss_File_Uploader(strtolower($model->getFilename()), $this->_folder72);          
//               $uploader->setAllowRenameFiles(true);
//               $uploader->setFilesDispersion(true);
//               try {
//                   $result = $uploader->save($filePath);
//               } catch(Exception $e) {
//                   Mage::log($e->getMessage());
//               }          
//       				Mage::helper('filetrans')->saveLinkModel($result['file'], $entity_id, $productName . " 72 dpi");
//               unlink($this->_basepath . DS . $this->_folder72 . DS . strtolower($model->getFilename()));
//           } else {
//               Mage::getSingleton('adminhtml/session')->addError("Downloadable 72dpi not found in $this->_folder72");
//           }  				
//   				
//   				try {
//               unlink($this->_basepath . DS . $this->_folder . DS . $model->getFilename());
//           } catch (Exception $e) {
//               Mage::log($e->getMessage());
//           }
//   				
//   				//Mage::log('$entity_id: ' . $entity_id);
//   				$model->setId($this->getRequest()->getParam('id'))->delete();
// //   
//   				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully copied and saved to Database'));
//   				$this->_redirect('*/*/');
//   			} catch (Exception $e) {
//   				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
//   				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
//   			}
//   		}
//   		$this->_redirect('*/*/');
// 	}
// 

}