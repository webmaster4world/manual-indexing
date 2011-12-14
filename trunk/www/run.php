#!/usr/bin/php5 -q
<?php
define("DIR",DIRECTORY_SEPARATOR);
define('TOPROOT_PATH', realpath(dirname(__FILE__)).DIR);
define('LIBRARY_DIR_TODAY',TOPROOT_PATH.'library'.DIR);
define('INDEXDIRDEBUG',TOPROOT_PATH.'app/');
define('SEARCH_EXT','php');


// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(LIBRARY_DIR_TODAY,get_include_path(),)));

/* service function */


function xprint($paragraph,$mode='p') {
    if (function_exists('apache_get_version')) {
         if ($mode=='p') {
            echo "<p>".$paragraph."<p>\n";
        } else {
            echo "<h3>".$paragraph."</h3>\n";
        }
    } else {
        /* maybe cron or console job*/
        echo $paragraph."\n";
    }
}



function PrintIncDir() {
$dirspath = explode(":",get_include_path());
         xprint("Include Dirs:",'h');
        foreach ($dirspath as &$value) {
            xprint($value);
        }
        xprint('   ');
        exit;
}


class IndexDebug 
{
    public function __construct()
    {
        /* path to indexing and search */
        $this->doclistener=array(); /* liste von alle file die brauchbar sind */
        $this->Searchpath = INDEXDIRDEBUG;   /* welche ordner idizieren nach * documenten */
        $this->xdoc = 0;  /* count */
        $this->IndexingDir($this->Searchpath);
        /* write log */
        $this->writelog();
        
        
    }
    
    private function writelog() {
        
        $feeds = $this->doclistener;
        $html = implode("\n",$feeds);
        @file_put_contents('LOGS_'.SEARCH_EXT.'.txt',$html);
        
    }
    
    
    private function IndexingDir($dir) {
           $nulldir = substr($dir, 0,1);
           if ($nulldir == '.') {
               /* no svn dir or dot init dir */
               return;
           }
           $dirloop = scandir($dir, 1);
           foreach ($dirloop as &$cd_dirs) {
                 if (substr($cd_dirs,-1)!='.') {
                    $path = $dir.$cd_dirs.DIR;
                    $pfile = $dir.$cd_dirs;
                    if (is_dir($path) ) {
                        //////xprint($path);
                        $this->IndexingDir($path);
                    } else if (is_file($pfile)) {
                        $pos = strpos($pfile,'.');
                        if ($pos != 0) {
                            $this->xdoc++;
                            /////echo xprint($pos);
                                $dd = pathinfo($pfile);
                                $extension = @strtolower(@$dd['extension']);
                                if ($extension !='' && $extension==SEARCH_EXT) {
                                //////echo xprint($extension);
                                xprint("Running ->".$this->xdoc);
                                $route = '['.$this->xdoc. '] geany '.$pfile;
                                xprint($route);
                                array_push($this->doclistener,$route);
                                }
                            ///if (strtolower($dd['extension'])==SEARCH_EXT ) {
                                ////$this->xdoc++;
                                ////xprint($this->xdoc . " geany " . $pfile);
                                ///array_push($this->doclistener,$pfile);
                            }
                        }
                    }
                  }
    }
    
    
    
    
    
    
}


echo "Indexing start " . INDEXDIRDEBUG."\n";
/*
$handle = fopen ("php://stdin","r");
$words = trim(fgets($handle));
if(trim($words) == ''){
    echo "ABORTING!\n";
    exit;
}
*/
xprint('Income search ->"'.SEARCH_EXT.'"');
$dd = new IndexDebug();
echo "Indexing end " . INDEXDIRDEBUG."\n";
?>
