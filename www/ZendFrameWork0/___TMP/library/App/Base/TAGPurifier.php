<?php


define("App_Base_HTMLPurifier_FILEAUTO",dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Purifier/HTMLPurifier.auto.php');
define("ROOT_TUTTO_PROGETTO_MELCHIONI",Zend_Registry::get('app_root').DIRECTORY_SEPARATOR);

define("CACHE_SERIALIZER",ROOT_TUTTO_PROGETTO_MELCHIONI.'tmp'.DIRECTORY_SEPARATOR);


require_once(App_Base_HTMLPurifier_FILEAUTO);
/*   doc   http://htmlpurifier.org/live/INSTALL  http://htmlpurifier.org/live/configdoc/plain.html   */
/*  pulire il codice html da renderlo leggibile */
/* in mancanza di tidy http://php.net/manual/en/book.tidy.php   */

class App_Base_TAGPurifier  {
	
	
	
	   function __construct($usecache = 1) {
		   $this->config = HTMLPurifier_Config::createDefault();  /* utf8 !!! */
		   if ($usecache == 1) {
		   $this->config->set('Cache.SerializerPath',CACHE_SERIALIZER);
		   } else {
		   $this->config->set('Core.DefinitionCache', null);
		   }
		   $this->setting = 'Purifier';
		   if (@class_exists('tidy')) {
		   $this->setting = 'Tidy';
		   }
		   $this->worknow = true;
	   }
	   
	   
	   /* formatta codice xml leggibile */
	   function  xml_clean( $dirty_xml = '') {
		   
		   if (@class_exists('tidy')) {
		                        $configxml = array(
								'indent' => true,
								'input-xml' => true,
								'output-xml' => true,
								'wrap' => 500);
								
							$tidy = new tidy;
							$tidy->parseString($dirty_xml, $configxml, 'utf8');
							return (string)$tidy;	
			} else {
				return $dirty_xml;
			}
	   }
	   
	   /* formatta codice xml leggibile o solo body o tutto */
	   function  html_clean( $dirty_html = '' , $modus = 'all') {
		   
		 
		   
		   
		   if ($this->setting != 'Purifier') {
		   
		   
						   if (@class_exists('tidy')) {
							   
											 $configbody = array(
											 'indent'         => true,
											 'output-xhtml'   => true,
											 'show-body-only'  => true,
											 'wrap'           => 180);
											 
											 
											 $configall = array(
											 'indent'         => true,
											 'output-xhtml'   => true,
											 'wrap'           => 180);
											 
											 
										   
									$tidy = new tidy;
									if ($modus == 'all') {
									$tidy->parseString($dirty_html, $configall, 'utf8');
									} else {
									$tidy->parseString($dirty_html, $configbody, 'utf8');
									}
									$tidy->cleanRepair();
					                return (string)$tidy;
							   
							   
						   }
		   
		  } else {
					   
					   if ($modus == 'all') {
					   $purifier = new HTMLPurifier($this->config);
					   return $purifier->purify( $dirty_html ); 
					   }
					   
		  }
				   
				   
		 return $dirty_html;
           
	   }
	
	
	
}


?>
