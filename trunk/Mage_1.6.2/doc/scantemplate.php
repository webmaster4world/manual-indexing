<?php

/*  read all Template & mark this file on top & bottom
  to set info like "__FILE__.":".__LINE__ to recovery
  file place and location on projekt!
  /////die(__FILE__);
 */

function trim_value(&$value)
{
 $value = trim($value);
}

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
if (!defined('_TMPFILESTAMP_')) {
    /* unique stamp to set if having or not information or to leave */
    define('_TMPFILESTAMP_', '57FGTZHUJKIOMJNHCDERF');  /* not change this to remove at end the stamp */
}

class Marktemplate {
    /* dir to read , leave or write */

    function __construct($dir = './', $makejob = true, $option = 0) {
        /* read xml dirs and locale file setting */
        $this->action = $makejob;
        $this->option = $option;
        $this->uniquename = array();
        $this->cap = 0;
        /* start to read xml file */
        $this->init_r($dir);
        /* test comment out */
        /////print_r($this->uniquename);
    }

    /**
     * Read dir and configure $this->xmlmodules
     * array 
     * @param type $path 
     */
    private function init_r($path) {
        /* scandir down on read */
        if (!is_dir($path)) {
            die("Unable to read path: -> " . $path . "On file:line ->" . __FILE__ . ":" . __LINE__);
        }
        $dir = new DirectoryIterator($path);
        foreach ($dir as $fileinfo) {
            $tmp = $fileinfo->getFilename();
            $fullfileandpath = $path . DIRECTORY_SEPARATOR . $tmp;

            if ($tmp == '.' or $tmp == '..')
                continue;

            if (is_dir($fullfileandpath)) {
                $this->init_r($fullfileandpath);
            } else {
                $trac_file = $fullfileandpath;
                $part = pathinfo($trac_file);
                $exte = $this->_findexts($trac_file); /* ! lovercase */
                if ($exte == 'phtml') {
                    /* check if having locale .. */
                    $this->_read_template($trac_file);
                }
            }
        }
    }

 
    private function _read_template($file = '') {
        $this->cap++;
        /* APPEND ITEM */
        array_push($this->uniquename, $file);

        if (!$this->_having_stamp($file, $this->action)) {
            /* insert mark */
            echo $this->cap . "-Read " . $file . "  \n";
            $this->_stampfile($file);
        } else {
            echo $this->cap . "-Having.. " . $file . "  \n";
        }
		$this->_cleanfile($file);
    }

    function _findexts($filename) {
        $filename = strtolower($filename);
        $exts = explode(".", $filename);
        if (is_array($exts)) {
            $n = count($exts) - 1;
            $exts = $exts[$n];
            return $exts;
        }
        return null;
    }

    private function _having_stamp($file, $leave) {
        $SEARCH = "/" . _TMPFILESTAMP_ . "/i";

        $stream = array();
        $having = false;
        if (file_exists($file) && is_readable($file)) {
            $lines = file($file);
            foreach ($lines as $line_num => $line) {
                if (trim($line) == '') {
                    continue;
                }

                if (preg_match($SEARCH, $line)) {
                    if ($leave) {
                        echo "Found term.... \n";
                        $having = true;
                        /* remove line */
                        /* not append stream */
                    } else {
                        return true;
                    }
                } else {
                    array_push($stream, $line);
                }
            }

            if ($leave && $having) {
                echo "Reset file..\n";  ////  $this->_stampfile($file);
                $er = file_put_contents($file, implode("\n", $stream));
                return true;
            }
        }
        return $having;
    }

    private function _stampfile($file) {
        if (!$this->action) {
            if (is_writable($file)) {
                $stamp = '<?php echo "<!--".__FILE__.":".__LINE__."   ' . _TMPFILESTAMP_ . '  end stamp ' . date("D M j G:i:s T Y") . ' file   -->"; ?>';
                $er = file_put_contents($file, "\n" . $stamp, FILE_APPEND | LOCK_EX);
            } else {
                echo "Alert! unable to write! " . $file . "  \n";
            }
        }
    }

    /* clean space line inside template */
    public function _cleanfile($file) {
        if ($this->option!=8) {
            $stream = array();
            if (is_writable($file)) {
                if (file_exists($file) && is_readable($file)) {
                    $lines = file($file);
                    foreach ($lines as $line_num => $line) {
                        if (trim($line) == '') {
                            continue;
                        }
                        array_push($stream,trim($line));
                    }
                    
                    /* clean array */
                    array_walk($stream, 'trim_value');
                    $content = implode("\n",$stream);
                    $er = file_put_contents($file,$content);
                }
            }
        }
    }

}

$direre = dirname(__FILE__) . DS . 'app' . DS . 'design' . DS;
$optionAllow = 'set or unset or clean';

if (count($argv) != 2) {
    die("Pleas write your action:\nphp " . $argv[0] . " " . $optionAllow . " \n");
}

echo "Read ->" . $direre . "\n";

$moder = strtolower(trim($argv[1]));

switch ($moder) {
    case "set":
        $leggi = new Marktemplate($direre, false);  /////  true to remove 
        break;
    case "unset":
        $leggi = new Marktemplate($direre, true);  /////  true to remove 
        break;
    case "clean":
        $leggi = new Marktemplate($direre, true, 1);  /////  only clean cache fast 
        break;
    default;
        die("Unknow action!  write:\nphp " . $argv[0] . " " . $optionAllow . " \n");
        break;
}
?>