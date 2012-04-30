<?php



 
/* dir separator default on Mage */
if (!defined('DS')) {
    define("DS",DIRECTORY_SEPARATOR, false);
}


class MediaCheck {
    
    /* read db table to check all file linked to download   */

    const SVersion = '1.01';

    function __construct( $action = 0 ) {
    $this->baseDir = realpath(dirname(__FILE__)).DS; //////   "C:\Users\pho\Desktop\ToyotaMedia\toyota_full\toyota".DS;  // realpath(dirname(__FILE__)).DS;
        if (file_exists('./app/etc/local.xml')) {
            $xml = simplexml_load_file('./app/etc/local.xml');
            $this->tblprefix = $xml->global->resources->db->table_prefix;
            $this->dbhost = $xml->global->resources->default_setup->connection->host;
            $this->dbuser = $xml->global->resources->default_setup->connection->username;
            $this->dbpass = $xml->global->resources->default_setup->connection->password;
            $this->dbname = $xml->global->resources->default_setup->connection->dbname;
        } else {
            exit('Failed to open ./app/etc/local.xml');
        }
        $this->ReportMsg = array(); /* report all error if exist   */
        $this->mediadir = $this->baseDir . DS . 'media' . DS ;
        $this->modus = $action; /* to extend other action */
        $this->connect = @mysql_connect($this->dbhost, $this->dbuser, $this->dbpass);
        if (!$this->connect) {
            echo "\n\n\nActual: \nhost->".$this->dbhost."  \nuser->".$this->dbuser."  \npass->".$this->dbpass." \n\n";
            die('Fatal error Can\'t connect to the MySQL database! ' . mysql_error() . "file:" . __FILE__ . " line:" . __LINE__);
        }
        $this->swap_db($this->dbname);
        if ($action == 0) {
            /*  only get db file */
                /* success connect to db subjekt */
                /* check table link */
                $this->_follow_link();
        }
        if ($action == 1) {
            /* check all file linked on media dir  */
            
            
        }
        $this->report_error();
    }
    
    private function _follow_link() {
        /* todo check file size to get total size dir */
        /* tabelle downloadable_link  &  downloadable_link_purchased_item gleiche query */
		$path_before = $this->baseDir . media.DS.downloadable.DS.files.DS.links.DS;
        $allinks = $this->ShellQuery("SELECT distinct  link_file AS item FROM downloadable_link_purchased_item WHERE link_type =  'file' ");
        if (is_array($allinks)) { 
            ////print_r($allinks);
			  foreach ($allinks as $val) {
			          $OSpath = join(DS, explode("/", ltrim($val['item'], "/")));
			          $file = $path_before . $OSpath ;
					  if (is_file($file)) {
                                              echo "File ok-> ".$file . "  \n";
                                          } else {
                                              array_push($this->ReportMsg, "NOT_FOUND->" . $file);
                                          }
					  
			  }
			
        }
        
    }

    /**
     *
     * @return type 
     */
    public function report_error() {
        if (!is_array($this->ReportMsg)) {
            return;
        }
        foreach ($this->ReportMsg as $msg) {
            echo $msg . "\n";
        }
    }

    function swap_db($dbname) {
        $this->select_db = @mysql_select_db($dbname, $this->connect);
        if (!$this->select_db) {
            die('Fatal error Can\'t select the database:' . $dbname . 'Please be sure that the database is created!' . "file:" . __FILE__ . " line:" . __LINE__);
        }
    }

    /* remake db reset transfer 1.4 to 1.6 mage */
    /*
    function destroy_db() {
        $this->swap_db($this->dbname);
        $file_lines = array();
        array_push($Rdb_line, "DROP DATABASE IF EXISTS `" . $this->dbname . "`;");
        array_push($Rdb_line, "CREATE DATABASE `" . $this->dbname . "`;");
        $this->exec_query($Rdb_line);
        exec("mysql -u" . $this->dbuser . " -p" . $this->dbpass . " -h" . $this->dbhost . "  " . $this->dbname . " < " . $this->localdump);
        $this->swap_db($this->dbname);
        $line = array();
        array_push($line, "update core_config_data set value = '" . $this->www_hostname . "' where config_id = 11;");
        array_push($line, "update core_config_data set value = '" . $this->www_hostname . "' where config_id = 12;");
        array_push($line, "UPDATE `eav_entity_type` SET `attribute_model` = 'customer/attribute', `additional_attribute_table` = 'customer/eav_attribute', `entity_attribute_collection` = 'customer/attribute_collection' WHERE `eav_entity_type`.`entity_type_code` = 'customer';");
       //// $this->exec_query($line);
    }
    */

    function ShellQuery($sql) {
        $xrow = array();
        $loopi = 0 - 1;
        $result = mysql_query($sql);
        if (!$result) {
            return false;
        }

        if (mysql_affected_rows() < 1) {
            return false;
        }

        if (!is_array(mysql_fetch_array($result))) {
            return true;
        }

        while ($row = mysql_fetch_array($result)) {
            $loopi++;
            $j = mysql_num_fields($result);
            for ($i = 0; $i < $j; $i++) {
                $k = mysql_field_name($result, $i);
                $xrow[$loopi][$k] = $this->utf8_check_set(stripslashes($row[$k]));
            }
        }



        if (is_array($xrow)) {
            return $xrow;
        } else {
            return true;
        }
    }

    function utf8_check_set($content, $in = null, $out = null) {
        if (!mb_check_encoding($content, 'UTF-8')
                OR !($content === mb_convert_encoding(mb_convert_encoding($content, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32'))) {

            $content = mb_convert_encoding($content, 'UTF-8');
            if (mb_check_encoding($content, 'UTF-8')) {
                // log('Converted to UTF-8');
            } else {
                // log('Could not converted to UTF-8');
            }
        }
        if ($in != '') {
            $content = eregi_replace($in, $out, $content);
        }
        return $content;
    }

    /* window and linux compatible */

    function gzdecode($data) {
        $g = tempnam('/tmp', 'ff');
        @file_put_contents($g, $data);
        ob_start();
        readgzfile($g);
        $d = ob_get_clean();
        return $d;
    }

    function curl_download($remote, $local) {
        $cp = curl_init($remote);
        $fp = fopen($local, "w");

        curl_setopt($cp, CURLOPT_FILE, $fp);
        curl_setopt($cp, CURLOPT_HEADER, 0);

        curl_exec($cp);
        curl_close($cp);
        fclose($fp);
    }

    /**
     *  remove file from basedir/var/   cache session or .... 
     */
    private function _cachehandler($dir) {
        if (!is_array($dir)) {
            return;
        }
        echo "Cache Handler start action \n";
        foreach ($dir as $recdir) {
            $fulldir = $this->baseDir . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . $recdir;
            /////echo "Init remove -> " .$fulldir . "\n";
            $this->_clean_file_from_dir($fulldir);
        }
        echo "Cache Handler end action \n";
    }

    private function _clean_file_from_dir($path) {
        if (is_dir($path)) {
            $dir = new DirectoryIterator($path);
            echo "Init Clean dir down recursive from -> " . $path . "\n";
            /* iterate all directory and path to grab */
            foreach ($dir as $fileinfo) {
                $tmp = $fileinfo->getFilename();
                $fullfileandpath = $path . DIRECTORY_SEPARATOR . $tmp;
                ///////echo "pars-> " .$fullfileandpath . "\n";
                if ($tmp == '.' or $tmp == '..') {
                    continue;
                }
                if (is_dir($fullfileandpath)) {
                    $this->_rec_rmdir($fullfileandpath);
                }
                if (is_file($fullfileandpath) && is_writable($fullfileandpath)) {
                    $this->_runlink($fullfileandpath);
                } else {
                    if (!is_writable($fullfileandpath)) {
                        array_push($this->ReportMsg, "NO write access on   -> " . $fullfileandpath);
                    }
                }
            }
        } else {
            array_push($this->ReportMsg, "No dir on    -> " . $path);
        }
    }

    private function _rec_rmdir($dir) {
        $success = false;
        if (is_dir($dir)) {
            echo "Recursive _rec_rmdir  -> " . $dir . "\n";
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    $ditem = $dir . DIRECTORY_SEPARATOR . $object;
                    if (filetype($ditem) == "dir") {
                        $this->_rec_rmdir($ditem);
                    } else {
                        $this->_runlink($ditem);
                    }
                    if ($this->notificate_notlist) {
                        if (is_file($ditem)) {
                            array_push($this->ReportMsg, "Not listed to remove!   -> " . $ditem);
                        }
                    }
                }
            }
            reset($objects);
            if (!@rmdir($dir)) {
                @chmod($dir, 0777);
                return @rmdir($dir);
            } else {
                return $success;
            }
        } else {
            return $success;
        }
    }

    /**
     * remove file and report if success or not 
     * @param type $file 
     */
    private function _runlink($file) {
        echo "Call rm " . $file . "\n";
        if (!unlink($file)) {
            array_push($this->ReportMsg, 'Unable to remove file ->' . $file);
        }
    }

}

$optionAllow = 'check or remcache';

if (count($argv) != 2) {
    die("Pleas write your action:\nphp " . $argv[0] . " " . $optionAllow . " \n");
}

$moder = strtolower(trim($argv[1]));

switch ($moder) {
    case "check":
        $make = new MediaCheck(0); /* only check media links!   */
        break;
    case "remcache":
        $make = new MediaCheck(1);   /* clean cache */
        break;
    default;
        die("Unknow action!  write:\nphp " . $argv[0] . " " . $optionAllow . " \n");
        break;
}
?>