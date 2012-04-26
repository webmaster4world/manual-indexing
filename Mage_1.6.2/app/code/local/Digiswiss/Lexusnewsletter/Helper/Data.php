<?php


/**
 * Newsletter Data Helper
 *
 * @category   Digiswiss_Lexusnewsletter
 */
class Digiswiss_Lexusnewsletter_Helper_Data extends Mage_Newsletter_Helper_Data
{
    
    /**
     * Retrieve subscriber name
     *
     * @param Digiswiss_Lexusnewsletter_Model_Subscriber $subscriber
     * @return string
     */
     public function getSubscriberName($subscriber) 
     {
          $name = Mage::getModel('customer/customer')
              ->setWebsiteId(1)
              ->loadByEmail($subscriber->getEmail())
              ->getName();
          Mage::log(__CLASS__ . '/' . __METHOD__ . PHP_EOL . $name);
          return $name;
     }
     
     
}

?>