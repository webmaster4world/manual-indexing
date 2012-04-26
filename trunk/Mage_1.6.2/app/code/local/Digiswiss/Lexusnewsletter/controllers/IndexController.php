<?php

/*
 * Lexusnewsletter quick action controller for additional functions
 * 
 * - easy to understand
 * - no new objects
 * - no core behaviour changes
 *
 * Author: Loaden Development
 * Website: http://www.loaden-development.com
 */

class Digiswiss_Lexusnewsletter_IndexController extends Mage_Core_Controller_Front_Action {

   // manages incoming actions for controller
   // ***MAGE_URL***/index.php/lexusnewsletter/?action=***ACTIONNAME*** 
	public function indexAction() {

		// also access via $this->getRequest();
		$action = $_GET['action'];
	
		if (!empty($action)) {
			if ($action != 'indexAction')	 {
				if (method_exists($this, $action)) {			
					$reflection = new ReflectionMethod($this, $action);				
					if ($reflection->isPublic()) {
						$this->{$action}();
					} else {
	        			throw new RuntimeException($this->__('The called method is not public.'));
	        		}
	    		} else {
	    			throw new RuntimeException($this->__('The called method not exist.'));
	    		}
	    	} else {
	    		throw new RuntimeException($this->__('indexAction method call not allowed.'));
	    	}
	   } else {
	   	$this->_redirectUrl(Mage::helper('core/url')->getHomeUrl());	
	   }
	}
	
	// login user via newsletterlink
	public function newsletterlink() {
	
		// also access via $this->getRequest();
		$product = ($_POST['ldpid']) ? $_POST['ldpid'] : $_GET['ldpid'];
		$store = ($_POST['ldsid']) ? $_POST['ldsid'] : $_GET['ldsid'];
		$customer = ($_POST['ldkid']) ? $_POST['ldkid'] : $_GET['ldkid'];
		$hash = ($_POST['ldhash']) ? $_POST['ldhash'] : $_GET['ldhash'];		
		Mage::getSingleton('core/resource')->getConnection('core_write')->query("DELETE FROM newsletter_newsletterlink WHERE TIMESTAMPDIFF(DAY, link_touched, NOW()) - link_lifetime > 0;");
		
		if ((!empty($product)) && (!empty($customer)) && (!empty($store)) && (!empty($hash)) && (is_numeric($product)) && (is_numeric($customer)) && (is_numeric($store))) {
		
			$session = Mage::getSingleton('customer/session');
			
			if ($session->isLoggedIn()) {
				
				try {
				
					$this->_redirectUrl(Mage::helper('catalog/product')->getProductUrl(Mage::getModel('catalog/product')->load($product)));
				
				} catch (Exception $e) {
				
					$this->_redirectUrl(Mage::helper('core/url')->getHomeUrl().'cms/index/noRoute/');
				}
			   
			} else {
			
				try {

					if ($login = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchRow("SELECT link_id FROM newsletter_newsletterlink WHERE customer_id = :customer AND product_id = :product AND store_id = :store AND hash = :hash;", array('customer' => $customer, 'product' => $product, 'store' => $store, 'hash' => $hash))) {
					
						$product = Mage::getModel('catalog/product')->load($product);
						$customer = Mage::getModel('customer/customer')->load($customer);	
						Mage::getSingleton('core/session', array('name' => 'frontend'));					
						Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer)->renewSession();
						$this->_redirectUrl(Mage::helper('catalog/product')->getProductUrl($product));
					
					} else {
						
						Mage::getSingleton('customer/session')->addError($this->__('Sorry your link is outdatet.'));
						$this->_redirectUrl(Mage::helper('core/url')->getHomeUrl());
					}
						
				} catch (Exception $e) {

					Mage::getSingleton('customer/session')->addError($this->__($e->getMessage()));
					$this->_redirectUrl(Mage::helper('core/url')->getHomeUrl());
				}
			}
		
		} else {
		
			$this->_redirectUrl(Mage::helper('core/url')->getHomeUrl());	
		}
	}	
	
	//
	// EXAMPLE
	// call ***MAGE_URL***/index.php/lexusnewsletter/?action=ExampleAction
	// method name must be equal to action name for execution
	// use public methods to extend more actions
	//
	public function ExampleAction() {

	}
	
	//
	// not public actions, not available via this action manager
	//
	private function NotAvailableMethod() {}

}

?>