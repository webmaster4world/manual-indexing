<?php

/*
 * Newsletter product login link widget based on product link widget
 * 
 * Author: Loaden Development
 * Website: http://www.loaden-development.com
 */

class Digiswiss_Lexusnewsletter_Block_Newsletterlink extends Mage_Catalog_Block_Widget_Link {
     
   // initialize product entity model
   protected function _construct() {
        parent::_construct();
        $this->_entityResource = Mage::getResourceSingleton('catalog/product');
	}
    
	// generate the product login link
   public function getHref() {
   		
   		try {
   			
   			$kid = Mage::getModel('customer/customer')->loadByEmail(Mage::getSingleton('lexusnewsletter/subscriber')->getSubscriberEmail())->getId();
	 	
   		} catch (Exception $e) {
   			
   			// testing kid for mail jaeck@loaden-development.com
   			$kid = 352;  
   		}		
   		$pid = str_replace('product/', '', $this->getId_path());
   		$sid = Mage::app()->getStore()->getId();
   		$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
			$existinglink = $connection->fetchRow($connection->select()->from('newsletter_newsletterlink', array('hash'))->where('customer_id=?', $kid)->where('product_id=?', $pid)->where('store_id=?', $sid));
			
			if (!empty($existinglink['hash'])) {

				$hash = $existinglink['hash'];			
			
			} else {
			
				self::generaterandomstring(40, $secret);
				$hash = sha1($kid.$sid.$pid.$secret);				
				$query = Mage::getSingleton('core/resource')->getConnection('core_write');			
				$query->beginTransaction()->insert('newsletter_newsletterlink', array(
					'customer_id' => $kid,
					'product_id' => $pid,
					'store_id' => $sid,
					'link_lifetime' => ($this->getData('life_time')) ? $this->getData('life_time') : 14,
					'hash' => $hash
				));
				$query->commit();
			}	
		  $this->_href = '/index.php/lexusnewsletter/?action=newsletterlink&ldsid='.$sid.'&ldkid='.$kid.'&ldpid='.$pid.'&ldhash='.$hash;
        return $this->_href;
    }
    
    
	static public function generaterandomstring($length, &$string) {
    
		$which = mt_rand(1, 3);
		
		switch ($which) {
			case 1:
				$min = 65; $max = 90;
				break;
			case 2:
				$min = 97; $max = 122;
				break;
			case 3:
				$min = 48; $max = 57;
				break;
		}
		$rand = mt_rand($min, $max);
		$string .= chr($rand);
		if(strlen($string) >= $length) return;
		self::generaterandomstring($length, $string);
	}
}

?>