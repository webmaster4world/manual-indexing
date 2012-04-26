<?php

$installer = $this;

$installer->installEntities(); 

// $installer->startSetup();
//
// $installer->run("
// 
// ALTER TABLE `{$installer->getTable('newsletter_subscriber')}` 
//     ADD `news_modelle` INT NULL, 
//     ADD `news_technologie` INT NULL, 
//     ADD `news_unternehmen` INT NULL,
//     ADD `news_nachhaltigkeit` INT NULL,
//     ADD `news_events` INT NULL;
// 
// ");

// $installer->endSetup(); 
