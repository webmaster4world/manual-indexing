<?php

class Digiswiss_Soapsync_Adminhtml_IbramssyncController extends Mage_Adminhtml_Controller_action
{
//   protected $_basepath = 'ftptransfer';
// 	protected $_folder = 'original300';
// 	protected $_folder72 = 'original72';
// 	//protected $_model = 'downloads/downloads';
	
	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('soapsync/images')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Images Manager'), Mage::helper('adminhtml')->__('Images Manager'));
			
// 		$logpath = 'c:/_vhosts/toyotapr/error_log';
// 		error_log("Logtest: Digiswiss_Filetrans_Adminhtml_FiletransController/_initAction\n", 3, $logpath);
		
		return $this;
	}   

	public function indexAction() {
		
// 		$logpath = 'c:/_vhosts/toyotapr/error_log';
// 		error_log("Logtest: Digiswiss_Filetrans_Adminhtml_FiletransController/indexAction\n", 3, $logpath);
		
		$this->_initAction()
			->renderLayout();
	}
	
	public function newAction(){
	
		Mage::helper('soapsync')->loadImages();
		$this->_redirect('*/*/');
	}
	public function copyAction() {
	   Mage::log("image copy");
	   $imagemodel = Mage::getModel('soapsync/ibramssync');
	   $imagename = $imagemodel->getImageName('090825_11');
	   Mage::log("image: " . $imagename);
      $this->_redirect('*/*/');
  }
  public function massCopyAction() {
      $this->_redirect('*/*/');
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
//   public function massCopyAction() {
//         $filetransIds = $this->getRequest()->getParam('filetrans');
//         if(!is_array($filetransIds)) {
// 			       Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
//         } else {
//             try {
// 				        $model = Mage::getModel('filetrans/downloads');
// 				        $filePath = Mage_Downloadable_Model_Link::getBasePath();
//                 foreach ($filetransIds as $filetransId) {
//                 
//                     $model->load($filetransId);
//             				$entity_id = $model->getEntityId();
//             				$type = 'links';
//                     $uploader = new Digiswiss_File_Uploader($model->getFilename(), $this->_folder);
//                     $uploader->setAllowRenameFiles(true);
//                     $uploader->setFilesDispersion(true);                    
//                     try {
//                         $result = $uploader->save($filePath);
//                     } catch(Exception $e) {
//                         Mage::log($e->getMessage());
//                     }
//           					$model->load($filetransId);
//             				$entity_id = $model->getEntityId();
//             				Mage::helper('filetrans')->delLinks($entity_id);
//            				  $productName = '';//  Mage::getModel('catalog/product')->load($entity_id)->getTitle();
//             				Mage::helper('filetrans')->saveLinkModel($result['file'], $entity_id, $productName . " 300 dpi");
//           					
//             				if (is_file($this->_basepath . DS . $this->_folder72 . DS . strtolower($model->getFilename()))) {          
//                 				$uploader = new Digiswiss_File_Uploader(strtolower($model->getFilename()), $this->_folder72);          
//                         $uploader->setAllowRenameFiles(true);
//                         $uploader->setFilesDispersion(true);
//                         try {
//                             $result = $uploader->save($filePath);
//                         } catch(Exception $e) {
//                             Mage::log($e->getMessage());
//                         }          
//                 				Mage::helper('filetrans')->saveLinkModel($result['file'], $entity_id, $productName . " 72 dpi");
//                         unlink($this->_basepath . DS . $this->_folder72 . DS . strtolower($model->getFilename()));
//                     } else {
//                         Mage::getSingleton('adminhtml/session')->addError(
//                                   "Downloadable 72dpi " . strtolower($model->getFilename()) . "  not found in $this->_folder72"
//                               );
//                     }  				
//             				
//             				try {
//                         unlink($this->_basepath . DS . $this->_folder . DS . $model->getFilename());
//                     } catch (Exception $e) {
//                         Mage::log($e->getMessage());
//                     }
//           					$model->setId($filetransId)->delete();
//                 }
//                 Mage::getSingleton('adminhtml/session')->addSuccess(
//                     Mage::helper('adminhtml')->__(
//                         'Total of %d record(s) were successfully copied and saved to Database', count($filetransIds)
//                     )
//                 );
//             } catch (Exception $e) {
//                 Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
//             }
//         }
//         $this->_redirect('*/*/index');
//   }
}