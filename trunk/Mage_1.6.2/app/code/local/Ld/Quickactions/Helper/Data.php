<?php

/*
 * Quick action helper
 * 
 * Author: Loaden Development
 * Website: http://www.loaden-development.com
 */


class Ld_Quickactions_Helper_Data extends Mage_Newsletter_Helper_Data {
    
     // last checkout product ids comma separated
     public function lastcheckoutpids_html()  {
     
			$downloadableitems = Mage::getResourceModel('downloadable/link_purchased_item_collection')->addFieldToFilter('purchased_id', Mage::getModel('downloadable/link_purchased')->load(Mage::getSingleton('checkout/session')->getLastRealOrderId(), 'order_increment_id')->getPurchasedId());
			$checkoutdownloadlinks = '';		
			
			foreach ($downloadableitems as $item) {
			
				$checkoutdownloadlinks .= ','.$item->getid();
			}			
			return $checkoutdownloadlinks;
     }
     
     // checks if product is already purchased
     public function islinkpurchased($linkid)  {
			$tmp = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchRow("SELECT dlpi.link_id FROM ".Mage::getSingleton('core/resource')->getTableName('downloadable_link_purchased_item')." AS dlpi LEFT JOIN ".Mage::getSingleton('core/resource')->getTableName('downloadable_link_purchased')." AS dlp ON dlp.purchased_id = dlpi.purchased_id WHERE dlp.customer_id = :customer AND dlpi.link_id = :link;", array('customer' => Mage::getSingleton('customer/session')->getCustomerId(), 'link' => $linkid));
                        Mage::log(__METHOD__.' .. return link ->'.$tmp,null,'zip_basket.log');
                        
     		if (!empty($tmp))
     			return true;
     		return false;						
     }  
}

?>