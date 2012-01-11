#!/usr/bin/php5 -q
<?php
if (!extension_loaded('ssh2')) {
    die('The ssh2 PHP extension is not available! file:line->' . __FILE__ . ":" . __LINE__);
}

/* window file  74 */



require_once('SSH2_Exception.php'); /* Exception  handler by job */
require_once('Remote_File_Handler.php'); /* Handler from remote ssh2 file  */


$puts = new Remote_File_Handler('jobrunner.xml');
$puts->stamp("List file on home dir:");
$gets = $puts->dirlist('/home/'.$puts->get_user().'/');
print_r($gets);
$puts->stamp("End line ->exit(1)");
?>
