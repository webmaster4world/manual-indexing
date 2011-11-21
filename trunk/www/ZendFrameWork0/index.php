<?php

define("DIR",DIRECTORY_SEPARATOR);
define("_SERVER_ROOT",dirname(__FILE__).DIR);


error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);
date_default_timezone_set('Europe/Rome');
define("LIBRARY_DIR_TODAY",_SERVER_ROOT . 'library'.  DIR);
define("ZEND_DIR_TODAY",LIBRARY_DIR_TODAY . 'Zend'.DIR);
define("APPLICATION_PATH",_SERVER_ROOT . 'application' . DIR);



defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
// As



$bootdir = _SERVER_ROOT.'library'.DIR.'Zend'.DIR.'Application'.DIR.'Bootstrap'.DIR;
define("BOOTSTRAPFILE",$bootdir.'Bootstrap.php');

$_zend_dirs = scandir(ZEND_DIR_TODAY);
$_libs_dirs = scandir(LIBRARY_DIR_TODAY);
if (is_array($_zend_dirs) && is_array($_libs_dirs ) ) {
	     foreach ($_zend_dirs as &$cd_dirs) {
			$path = ZEND_DIR_TODAY.$cd_dirs;
			if (is_dir($path)) {
				/////set_include_path(get_include_path() . PATH_SEPARATOR . $path);
			}
		  }
		 foreach ($_libs_dirs as &$cd_dirs) {
			$path = LIBRARY_DIR_TODAY.$cd_dirs;
			if (is_dir($path)) {
				set_include_path(get_include_path() . PATH_SEPARATOR . $path);
			}
		}
	
}
set_include_path(get_include_path()
            . PATH_SEPARATOR . _SERVER_ROOT  
            . PATH_SEPARATOR . _SERVER_ROOT . 'application'   
            . PATH_SEPARATOR . _SERVER_ROOT . 'library'.  DIR . 'Zend'.DIR.'Application'.DIR
            . PATH_SEPARATOR . $bootdir   
            . PATH_SEPARATOR . _SERVER_ROOT . 'library'.  DIR . 'Zend'.DIR
            . PATH_SEPARATOR . LIBRARY_DIR_TODAY );





function PrintIncDir() {
$dirspath = explode(":",get_include_path());
         echo "<h3>Include Dirs:</h3><p>";
		foreach ($dirspath as &$value) {
			echo $value."<br/>";
		}
		echo "</p>";
        exit;
}

function __autoload($class_name) {
	
	     $search = array("_");
         $replace = array(DIR);
	     $fake_class_Path = LIBRARY_DIR_TODAY . str_replace($search,$replace,$class_name).'.php';
	  if (!is_file($fake_class_Path)) {
		  printf("<p>Unable to get file! ->".$fake_class_Path."</p>");
		  printf("<p>Unable to include Class! ->".$class_name."</p>");
		  PrintIncDir();
          exit;
      } else {
		  require_once ( $fake_class_Path );
	  }
}



if (isset($_GET['phpinfo'])) {
	phpinfo();
	exit;
}

if (isset($_GET['phpdir'])) {
	PrintIncDir();
	exit;
}


require_once 'application/Bootstrap.php';

require_once 'Zend/Application.php'; 
// Creiamo un'istanza di Zend_Application...
$application = new Zend_Application(
APPLICATION_ENV,
APPLICATION_PATH . '/configs/application.ini'
);
//...e la lanciamo
$application->bootstrap()->run();







////echo "\n<br/><br/><br/><br/><p>End line from index file!</p>\n";
exit;
?>
