<?php

/*
 * Quick action controller for additional functions
 * 
 * - easy to understand
 * - no new objects
 * - no core behaviour changes
 *
 * Author: Loaden Development
 * Website: http://www.loaden-development.com
 */

class Ld_Quickactions_IndexController extends Mage_Core_Controller_Front_Action {
 
   // manages incoming actions for controller
   // ***MAGE_URL***/index.php/quickactions/?action=***ACTIONNAME*** 
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
	
	// add related products without viewed product to cart logic fix 
	public function relatedtocart() {
	
		// also access via $this->getRequest();
		$relatedproducts = ($_POST['related_products']) ? $_POST['related_products'] : $_GET['related_products'];
		$currentproduct = ($_POST['current_product']) ? $_POST['current_product'] : $_GET['current_product'];

		if ((!empty($relatedproducts)) && (!empty($currentproduct))) {		

			if (is_array($relatedproducts)) {
			
				try {
	
					$session = Mage::getSingleton('core/session', array('name'=>'frontend'));
					$cart = Mage::helper('checkout/cart')->getCart();
		
					foreach ($relatedproducts as $productid => $productqty) {
					
						$product = Mage::getModel('catalog/product')->load($productid);
		
						if (!isset($productqty)) { 
							
							$qty = 1; 
						
						} else { 
							
							$qty = $productqty; 
						}							
						$links = Mage::getModel('downloadable/product_type')->getLinks($product);
		
						if (is_array($links)) {
		
							$linksforcart = array();								
							
							foreach ($links as $link)
									$linksforcart[] = $link->getLinkId();
											
							$qty = array('qty' => $qty, 'links' => $linksforcart);
							
							$request = new Varien_Object();
							$request->setData($qty);									
						}
							
						$cart->addProduct($product, $qty);							
						$session->setLastAddedProductId($product->getId());				
					}	
					$session->setCartWasUpdated(true);
					$cart->save();
					Mage::getSingleton('checkout/session')->addSuccess($this->__('Ausgewählte Medien erfolgreich in den Medienkorb gelegt'));
					$this->_redirectUrl(Mage::helper('catalog/product')->getProductUrl(Mage::getModel('catalog/product')->load($currentproduct)));
			
				} catch (Exception $e) {
		
					Mage::getSingleton('checkout/session')->addError($this->__($e->getMessage()));
					$this->_redirectUrl(Mage::helper('core/url')->getHomeUrl());
				}
			}
		}	
	}
	
	// one click checkout, redirect to my downloads on success requires one page checkout
	public function directdownloadcheckout() {	
	
		try {
	
			$customer = Mage::getSingleton('checkout/session')->getQuote()->getCustomer();
	
			if ($billig = $customer->getDefaultBilling())
	      	$billig = Mage::getModel('customer/address')->load($billig);
	
			if ($shipping = $customer->getDefaultShipping()) {
	      
	      	$shipping = Mage::getModel('customer/address')->load($shipping);
	
	      } else {
	
	      	$shipping = $billig;
	      }
			$checkout = Mage::getSingleton('checkout/type_onepage');
			$checkout->initCheckout();
			$checkout->saveCheckoutMethod('guest');			
			$checkout->saveShipping($shipping, false);
			$checkout->saveBilling($billig, false);
			$checkout->saveShippingMethod();
			$checkout->savePayment(array('method' => 'free'));						
			$checkout->saveOrder();
			$items = Mage::getSingleton('checkout/session')->getQuote()->getItemsCollection();
						
			foreach($items as $item) {
			
   			Mage::getSingleton('checkout/cart')->removeItem($item->getId())->save();
			}
			Mage::getSingleton('checkout/session')->clear();	
			Mage::getSingleton('customer/session')->addSuccess($this->__('Click on Download to download your Media cart as ZIP'));				
			$this->_redirectUrl(Mage::helper('core/url')->getHomeUrl().'downloadable/customer/products/');		
		
		} catch (Exception $e) {

			Mage::getSingleton('checkout/session')->addError($this->__($e->getMessage()));
			$this->_redirectUrl(Mage::helper('core/url')->getHomeUrl().'checkout/cart/');
		}	
	}
	
	// create a archive from file selection, start download
	public function downloadfileselection() {

		// also access via $this->getRequest();
		$files = ($_POST['download_files']) ? $_POST['download_files'] : $_GET['download_files'];

		if (is_array($files)) {	

			try {
		
				$zip = new ZipArchive();				
				$tmpfile = tempnam('var/tmp/', 'pack_');
				rename($tmpfile, ($newtmpfile = $tmpfile.'.zip'));

				if (!$zip->open($newtmpfile, ZIPARCHIVE::CREATE))
					throw new RuntimeException('Can not create temporary Zip file.');				

				foreach($files as $fileid => $check) {
				
					if (Mage::helper('quickactions')->islinkpurchased($fileid)) {						
							$link = Mage::getModel('downloadable/link')->load($fileid);
							if (!$zip->addFile(Mage::getBaseDir('media').'/downloadable/files/links'.$link->getLink_file())) {
								throw new RuntimeException('Can not add file to Archive.');								
							}
					} else {
						echo $this->__('One of the Links not purchased');
						$notpruchased = true;
					}				
				}
				$zip->close();	
				
				if ($notpruchased == false) {
					$file = file_get_contents($newtmpfile); 
					$this->getResponse()
						  ->setBody($file)
						  ->setHeader('Content-Type', 'application/zip')
						  ->setHeader('Content-Disposition', 'attachment; filename="'.basename($newtmpfile).'"')
						  ->setHeader('Content-Length', strlen($file));		
				}			  
				unlink($newtmpfile);

			} catch (Exception $e) {
						
				echo $this->__($e->getMessage());
				$this->_redirectUrl(Mage::helper('core/url')->getHomeUrl().'downloadable/customer/products/');	
			}	
		}
	}
	
	//
	// EXAMPLE
	// call ***MAGE_URL***/index.php/quickactions/?action=ExampleAction
	// method name must be equal to action name for execution
	// use public methods to extend more actions
	//
	public function ExampleAction() {}
	
	//
	// not public actions, not available via this action manager
	//
	private function NotAvailableMethod() {}
}

?>
