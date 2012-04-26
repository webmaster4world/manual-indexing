<?php

require_once('Mage/Newsletter/controllers/ManageController.php');

class Digiswiss_Lexusnewsletter_Newsletter_ManageController extends Mage_Newsletter_ManageController {

    public function saveAction() {
		
		if (!$this->_validateFormKey())
            return $this->_redirect('customer/account/');

        try {
				
				$session = Mage::getSingleton('customer/session');
            $customer = $session->getCustomer();
            
            /*
            $customer = Mage::getModel('customer/customer')
          		->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
          		->load($session->getCustomer()->getId());
				*/
				/*
				Mage::getSingleton('newsletter/subscriber')
					 ->loadByCustomer($customer)
                ->setNewsModelle((boolean)$this->getRequest()->getParam('news_modelle', false))
                ->setNewsTechnologie((boolean)$this->getRequest()->getParam('news_technologie', false))
                ->setNewsUnternehmen((boolean)$this->getRequest()->getParam('news_unternehmen', false))
                ->setNewsNachhaltigkeit((boolean)$this->getRequest()->getParam('news_nachhaltigkeit', false))
                ->setNewsEvents((boolean)$this->getRequest()->getParam('news_events', false))
                ->save(); 
            */
            
  
            $customer
                ->setStoreId(Mage::app()->getStore()->getId())
                ->setIsSubscribed((boolean) 1)
                ->setNewsModelle((boolean) $this->getRequest()->getParam('news_modelle', false))
                ->setNewsTechnologie((boolean) $this->getRequest()->getParam('news_technologie', false))
                ->setNewsUnternehmen((boolean) $this->getRequest()->getParam('news_unternehmen', false))
                ->setNewsNachhaltigkeit((boolean) $this->getRequest()->getParam('news_nachhaltigkeit', false))
                ->setNewsEvents((boolean) $this->getRequest()->getParam('news_events', false))
                ->save();  
                            
            $session->addSuccess($this->__('The subscription was successfully saved'));
                  		
        } catch (Exception $e) {
        		
            $session->addError($this->__('There was an error while saving your subscription'));
        }
        $this->_redirect('customer/account/');
    }
}

?>