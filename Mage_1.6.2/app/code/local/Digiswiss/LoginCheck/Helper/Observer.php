<?php
class Digiswiss_LoginCheck_Helper_Observer extends Mage_Core_Helper_Abstract {
	
	public function checkLogin($observer) {
		
		$event = $observer->getEvent();
		$controller = $event->getAction();
		
		if (!Mage::getSingleton( 'customer/session' )->isLoggedIn() && $controller->getFullActionName() != 'customer_account_login' && $controller->getFullActionName() != 'customer_account_create' && $controller->getFullActionName() != 'customer_account_forgotpassword') {
         if ($controller->getRequest()->getPathInfo() !== '/hilfe-seite/') {
            if (($controller->getRequest()->getPathInfo()) && (!strstr($controller->getRequest()->getPathInfo(),'logoutSuccess'))) {
                Mage::getSingleton( 'customer/session' )->setForwardTo($controller->getRequest()->getPathInfo());
            } 
            $controller->getResponse()->setRedirect(Mage::getUrl('customer/account/login'));
        }
    }    
		return $this;
	}	
}