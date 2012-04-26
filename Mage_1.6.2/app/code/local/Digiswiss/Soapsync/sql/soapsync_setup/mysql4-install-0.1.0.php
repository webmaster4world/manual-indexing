<?php

$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('aa_ibrams_media')};
CREATE TABLE {$this->getTable('aa_ibrams_media')} (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) NOT NULL,
  `title` tinytext NOT NULL,
  `filename` tinytext NOT NULL,
  `folder` tinytext NOT NULL,
  `width` tinytext NOT NULL,
  `height` tinytext NOT NULL,
  `dpi` tinytext NOT NULL,
  `colorspace` tinytext NOT NULL,
  `version` tinytext NOT NULL,
  `description` text NOT NULL,
  `efit_id` tinytext NOT NULL,
  `ibrams_status` tinytext,
  `mage_id` int(11) DEFAULT NULL,
  `mage_status` tinytext,
  `mage_image_version` tinytext,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS {$this->getTable('aa_ibrams_media_metadata')};
CREATE TABLE {$this->getTable('aa_ibrams_media_metadata')} (
  `meta_id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `sku` tinytext NOT NULL,
  `identifier` tinytext NOT NULL,
  `name` tinytext NOT NULL,
  `value` tinytext NOT NULL,
  PRIMARY KEY (`meta_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

");
/*
DROP TABLE IF EXISTS {$this->getTable('aafiletrans')};
CREATE TABLE {$this->getTable('aafiletrans')} (
 `filetrans_id` int(11) unsigned NOT NULL auto_increment,
 `filename` varchar(255) NOT NULL default '',
 `filetype` varchar(255) NOT NULL default '',
 PRIMARY KEY  (`filetrans_id`),
 UNIQUE KEY `filename` (`filename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8; 
    
DROP TABLE IF EXISTS {$this->getTable('aadownloads')};
CREATE TABLE {$this->getTable('aadownloads')} (
  `filetrans_id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `filetype` varchar(255) NOT NULL,
  `entity_id` int(11) NOT NULL,
  PRIMARY KEY (`filetrans_id`),
  UNIQUE KEY `filename` (`filename`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
*/
$installer->endSetup(); 