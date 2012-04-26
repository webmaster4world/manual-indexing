<?php

$installer = $this;

// $installer->installEntities(); 

$installer->startSetup();

/*
$setup = Mage::getModel('customer/entity_setup', 'core_setup');

$entities = new Digiswiss_Lexusnewsletter_Model_Resource_Eav_Mysql4_Setup();
$entities = $entities->getDefaultEntities();

foreach ($entities['customer']['attributes'] as $entikey => $entival) {

		$setup->addAttribute('customer', $entikey, $entival);
      $customer = Mage::getModel('customer/customer');
      $attrSetId = $customer->getResource()->getEntityType()->getDefaultAttributeSetId();
      $setup->addAttributeToSet('customer', $attrSetId, 'General', $entikey);
}
*/


/*
$installer->run("CREATE TABLE newsletter_newsletterlink (
  link_id INT(11) NOT NULL,
  customer_id INT(11) NOT NULL,
  product_id INT(11) NOT NULL,
  store_id INT(11) NOT NULL,
  link_touched TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  link_lifetime INT(11) NOT NULL,
  hash CHAR(40)
);");
*/

$installer->endSetup();

?>