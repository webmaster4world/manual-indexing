<?php





/*
 * Remote_File_Handler.php
 * get remote file to check time or md5 to load local here
 */

if (!extension_loaded('ssh2')) {
    die('The ssh2 PHP extension is not available! file:line->' . __FILE__ . ":" . __LINE__);
}


if (!function_exists('exec')) {
    die("Alert: Activate exec php function to read home dir by ~/ ");
}

 // @Todo php os ohne tilde in file setting ~/file  

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    /* window */
    /* os ohne tilde in file setting */
    define("USERenterPATH",'Z:'.DIRECTORY_SEPARATOR);
} else if (strtoupper(substr(PHP_OS, 0, 3)) === 'LIN') {
    /* linux */
    $currentuser = exec('whoami');
    define("USERenterPATH",'/home/'.$currentuser.'/');
} else {
    /* mac */
    $currentuser = exec('whoami');
    define("USERenterPATH",'/home/'.$currentuser.'/');
}



/* usage only by  function stamp($paragraph, $mode = 'p') 
 * to get performance on microtime 
 */
function microtime_float() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float) $usec + (float) $sec);
}

/**
 * @since 0.1
 * @uses untrailingslashit() Unslashes string if it was slashed already.
 *
 * @param string $string What to add the trailing slash to.
 * @return string String with trailing slash added.
 */
function trailingslashit($string) {
    return untrailingslashit($string) . '/';
}

/**
 * Removes trailing slash if it exists.
 *
 * The primary use of this is for paths and thus should be used for paths. It is
 * not restricted to paths and offers no specific path support.
 *
 * @since 2.2.0
 *
 * @param string $string What to remove the trailing slash from.
 * @return string String without the trailing slash.
 */
function untrailingslashit($string) {
    return rtrim($string, '/');
}

class Remote_File_Handler {

    ////private $connection;

    function __construct($jobfile = "none.xml") {
        if (!file_exists($jobfile)) {
            die("Unable to open config xml item! File: ->" . __FILE__ . ":" . __LINE__);
        }
        if (!class_exists("SimpleXMLElement")) {
            die("Unable to load class SimpleXMLElement! File: ->" . __FILE__ . ":" . __LINE__);
        }
        $this->search  = array('~/', 'new vars');
        $this->replace = array(USERenterPATH, 'new vars');
        //// str_replace($this->search,$this->replace, $var);
        $this->connection = FALSE;
        $this->authEnter = FALSE;
        $this->usekey = false;
        $this->connect_method = '';
        $this->start = microtime_float();
        $this->configItemFile = "error.xml";
        $this->errors = array();
        $this->conf = array();
        $this->readconfig($jobfile);
        $this->connect();
    }
    
    public function get_user() {
            if ($this->authEnter && $this->connection) {
               return $this->conf['_sftp_user'];
            } else {
                return 'error_root';
            }      
     }

    private function connect($retries = 1) {
        $attepmts = 1;
        $retryTimeout = 2000; /* microsecond */
        $this->stamp('Try a new connection:');
        $this->stamp('Timeout: ' . $retryTimeout);
        $this->stamp('Host: ' . $this->conf['_sftp_host']);
        $this->stamp('Port: ' . $this->conf['_sftp_port']);
        $this->authEnter = FALSE;

        $this->connection = @ssh2_connect($this->conf['_sftp_host'], $this->conf['_sftp_port'],$this->connect_method );

        //if could not connect try again in 30
        while (!$this->connection && $attepmts < $retries) {
            usleep($retryTimeout);
            $this->connection = @ssh2_connect($this->conf['_sftp_host'], $this->conf['_sftp_port'],$this->connect_method );
            $attepmts++;
        }

        if (!$this->connection) {
            throw new SSH2_Exception("Could not connect to host remote {$this->conf['_sftp_host']} on port {$this->conf['_sftp_port']}", 0, 2);
        }

        if (!empty($this->conf['_sftp_pass']) && $this->usekey == FALSE) {
            /* connect on password normal */
            if ($this->connection) {
                $this->authEnter = @ssh2_auth_password($this->connection, $this->conf['_sftp_user'], $this->conf['_sftp_pass']);

                if (!$this->authEnter) {
                    throw new SSH2_Exception("Could not connect by user {$this->conf['_sftp_user']}  to host remote {$this->conf['_sftp_host']} on port {$this->conf['_sftp_port']}", 0, 2);
                }
            }
        } else {
            /* connect on public key modus */
            if ($this->connection) {
                $this->authEnter = @ssh2_auth_pubkey_file($this->connection, $this->conf['_sftp_user'], $this->conf['ssh_pub_key_file'], $this->conf['ssh_pri_key_file'], $this->conf['_sftp_pass']);


                if (!$this->authEnter) {
                    throw new SSH2_Exception("Could not connect by user on public and private key by {$this->conf['_sftp_user']}  to host remote {$this->conf['_sftp_host']} on port {$this->conf['_sftp_port']}", 0, 2);
                }
            }
        }

        if ($this->authEnter && $this->connection) {
            /* no Exception hi you are connect message: */
            $this->sftp_link = ssh2_sftp($this->connection);
            $this->stamp("Sucess connect on host: " . $this->conf['_sftp_host']);
        } else {
            die("Not possibel to load this text!" . __FILE__ . ":" . __LINE__);
        }
    }

    /*
     * read xml config file and associate on array $this->conf
     */

    private function readconfig($file) {
        /* load one xml file to having one job */
        $this->configItemFile = $file;
        $conf = array();
        $xml = @simpleXML_load_file($file, "SimpleXMLElement", LIBXML_NOCDATA);
        if ($xml === FALSE) {
            throw new SSH2_Exception("Alert! unable to load xml file! check sintax. read: " . @file_get_contents($file), 0, 1);
        }
        if ($xml->getName() == 'sshjob') {
            /* ok xml */
            foreach ($xml->children() as $child) {
                      /* associate key from xml file */
                      $vars = (string) $child->getName();
                      $value = (string) $child;
                      $value = str_replace($this->search,$this->replace,$value);
                      
                $conf[$vars] = $value;
                if ($vars == '_sftp_pass') {
                    /* password or passphrase decode */
                    $conf[$vars] = base64_decode((string) $child);
                }
                if ($vars == 'ssh_pub_key_file') {
                    /* check method key if having */
                    /* file check local? */
                    $type =  $xml->xpath("//ssh_pub_key_file[@type!='none']");
                    foreach ( $type[0]->attributes() as $key => $val ) { 
                        if ($val=='dsa') { 
                             $this->connect_method = array(
                                    'kex' => 'diffie-hellman-group1-sha1',
                                    'hostkey' => 'ssh-dss',
                                    'client_to_server' => array(
                                    'crypt' => '3des-cbc',
                                    'mac' => 'hmac-md5',
                                    'comp' => 'none'),
                                    'server_to_client' => array(
                                    'crypt' => '3des-cbc',
                                    'mac' => 'hmac-md5',
                                    'comp' => 'none'));
                             $this->stamp('Note: setting to dsa key ');
                        }
                    } 
                    
                }
                
            }
            if ($conf['_sftp_port'] < 1 || empty($conf['_sftp_port'])) {
                $conf['_sftp_port'] = 22;
                $this->stamp('Note: setting port default ->22');
            }

            /////print_r($conf);

            $host = (string) $conf["_sftp_host"];
            if (empty($host)) {
                die("Host not set!");
            }
            if (empty($conf["ssh_pub_key_file"])) {
                $this->usekey = FALSE;
                //////$conf['ssh_pub_key_file'] = '';
                //////$conf['ssh_pri_key_file'] = '';
            } else {
                $this->usekey = TRUE;
                if (!is_file($conf['ssh_pub_key_file'])) {
                    throw new SSH2_Exception("Alert! unable to find your -> ssh_pub_key_file!.", 0, 1);
                }
                if (!is_file($conf['ssh_pub_key_file'])) {
                    throw new SSH2_Exception("Alert! unable to find your -> ssh_pub_key_file!.", 0, 1);
                }
                $pair_key = @file_get_contents($conf['ssh_pub_key_file']);
                if (strlen($pair_key) < 50) {
                    throw new SSH2_Exception("Alert! -> _sftp_key_file content is smaller as 50 char: Not possibel.", 0, 1);
                } else {
                    /* not destroy link */
                }
            }
            
            $this->conf = $conf;
            print_r($conf);
            die(__LINE__);
        }
    }

    /*
     * print/echo stamp function to make new line on html or console
     * @param string $string to print & mode paragraph or subtitle.
     * @return void
     */

    public function stamp($paragraph, $mode = 'p') {

        $time_end = microtime_float();
        $time = $time_end - $this->start;
        $time = substr($time, 0, 6);


        if (function_exists('apache_get_version')) {
            if ($mode == 'p') {
                echo "<p>" . $paragraph . "<p>\n";
            } else {
                echo "<h3>" . $paragraph . "</h3>\n";
            }
        } else {
            /* maybe cron or console job */
            echo $time . ") " . $paragraph . "\n";
        }
    }

    private function run_command($command, $returnbool = false) {
        /* save latest comand here ! */
        die("remote run_command not work!");
        $this->_cmd = $command;

        if (!$this->connection) {
            if (!$returnbool) {
                throw new SSH2_Exception("Alert! -> unable to perform comand-> (" . $this->_cmd . "), connection lost! ", 0, 1);
            } else {
                die("Connection lost!");
            }
        }

        $this->stamp("Init comand: -> " . $this->_cmd);

        if (strlen($this->_cmd) != 0) { /* not null length */
            $stream = @ssh2_exec($this->connection, $this->_cmd);
            stream_set_blocking($stream, true);
            $stream = trim(fgets($stream));
        } else {
            throw new SSH2_Exception("Alert! -> comand is null or false-> (" . $this->_cmd . ") ", 0, 1);
        }

        if (!$returnbool) {
            return $stream;
        } else {
            return true;
        }
    }

    /* file inhalt zeigen */

    public function get_contents($file, $type = '', $resumepos = 0) {
        $file = ltrim($file, '/');
        return file_get_contents('ssh2.sftp://' . $this->sftp_link . '/' . $file);
    }

    function exists($file) {
        $file = ltrim($file, '/');
        return file_exists('ssh2.sftp://' . $this->sftp_link . '/' . $file);
    }

    function is_file($file) {
        $file = ltrim($file, '/');
        return is_file('ssh2.sftp://' . $this->sftp_link . '/' . $file);
    }

    function is_dir($path) {
        $path = ltrim($path, '/');
        return is_dir('ssh2.sftp://' . $this->sftp_link . '/' . $path);
    }

    function getchmod($file) {
        return substr(decoct(@fileperms('ssh2.sftp://' . $this->sftp_link . '/' . ltrim($file, '/'))), 3);
    }

    function owner($file) {
        $owneruid = @fileowner('ssh2.sftp://' . $this->sftp_link . '/' . ltrim($file, '/'));
        if (!$owneruid)
            return false;
        if (!function_exists('posix_getpwuid'))
            return $owneruid;
        $ownerarray = posix_getpwuid($owneruid);
        return $ownerarray['name'];
    }

    function is_readable($file) {
        $file = ltrim($file, '/');
        return is_readable('ssh2.sftp://' . $this->sftp_link . '/' . $file);
    }

    function mtime($file) {
        $file = ltrim($file, '/');
        return filemtime('ssh2.sftp://' . $this->sftp_link . '/' . $file);
    }

    function size($file) {
        $file = ltrim($file, '/');
        return filesize('ssh2.sftp://' . $this->sftp_link . '/' . $file);
    }

    function group($file) {
        $gid = @filegroup('ssh2.sftp://' . $this->sftp_link . '/' . ltrim($file, '/'));
        if (!$gid)
            return false;
        if (!function_exists('posix_getgrgid'))
            return $gid;
        $grouparray = posix_getgrgid($gid);
        return $grouparray['name'];
    }

    function dirlist($path, $include_hidden = true, $recursive = false) {
        if ($this->is_file($path)) {
            $limit_file = basename($path);
            $path = dirname($path);
        } else {
            $limit_file = false;
        }

        if (!$this->is_dir($path))
            return false;

        $ret = array();
        $dir = @dir('ssh2.sftp://' . $this->sftp_link . '/' . ltrim($path, '/'));

        if (!$dir)
            return false;

        while (false !== ($entry = $dir->read())) {
            $struc = array();
            $struc['name'] = $entry;

            if ('.' == $struc['name'] || '..' == $struc['name'])
                continue; //Do not care about these folders.

            if (!$include_hidden && '.' == $struc['name'][0])
                continue;

            if ($limit_file && $struc['name'] != $limit_file)
                continue;

            $struc['perms'] = $this->gethchmod($path . '/' . $entry);
            $struc['permsn'] = $this->getnumchmodfromh($struc['perms']);
            $struc['number'] = false;
            $struc['owner'] = $this->owner($path . '/' . $entry);
            $struc['group'] = $this->group($path . '/' . $entry);
            $struc['size'] = $this->size($path . '/' . $entry);
            $struc['lastmodunix'] = $this->mtime($path . '/' . $entry);
            $struc['lastmod'] = @date('M j', $struc['lastmodunix']);
            $struc['time'] = @date('h:i:s', $struc['lastmodunix']);
            $struc['type'] = $this->is_dir($path . '/' . $entry) ? 'dir' : 'file';

            if ('dir' == $struc['type']) {
                if ($recursive)
                    $struc['files'] = ''; ////$this->dirlist($path . '/' . $struc['name'], $include_hidden, $recursive);
                else
                    $struc['files'] = '';
            }

            $ret[$struc['name']] = $struc;
        }
        $dir->close();
        unset($dir);
        return $ret;
    }

    public function _current_dir($returnbool = FALSE) {
        if (!$this->connection) {
            if (!$returnbool) {
                throw new SSH2_Exception("Alert! -> unable to perform comand-> ls -al, connection lost! ", 0, 2);
            } else {
                die("Connection lost!");
            }
        }
        $stream = @ssh2_exec($this->_stream, 'ls ');
        if (strlen($stream) != 0) {
            return $stream;
        } else {
            die("------------null-data");
        }
    }

    /**
     * Returns the *nix style file permissions for a file
     *
     * From the PHP documentation page for fileperms()
     *
     * @link http://docs.php.net/fileperms
     * @since 2.5
     * @access public
     *
     * @param string $file string filename
     * @return int octal representation of permissions
     */
    function gethchmod($file) {
        $perms = $this->getchmod($file);
        if (($perms & 0xC000) == 0xC000) // Socket
            $info = 's';
        elseif (($perms & 0xA000) == 0xA000) // Symbolic Link
            $info = 'l';
        elseif (($perms & 0x8000) == 0x8000) // Regular
            $info = '-';
        elseif (($perms & 0x6000) == 0x6000) // Block special
            $info = 'b';
        elseif (($perms & 0x4000) == 0x4000) // Directory
            $info = 'd';
        elseif (($perms & 0x2000) == 0x2000) // Character special
            $info = 'c';
        elseif (($perms & 0x1000) == 0x1000) // FIFO pipe
            $info = 'p';
        else // Unknown
            $info = 'u';

        // Owner
        $info .= (($perms & 0x0100) ? 'r' : '-');
        $info .= (($perms & 0x0080) ? 'w' : '-');
        $info .= (($perms & 0x0040) ?
                        (($perms & 0x0800) ? 's' : 'x' ) :
                        (($perms & 0x0800) ? 'S' : '-'));

        // Group
        $info .= (($perms & 0x0020) ? 'r' : '-');
        $info .= (($perms & 0x0010) ? 'w' : '-');
        $info .= (($perms & 0x0008) ?
                        (($perms & 0x0400) ? 's' : 'x' ) :
                        (($perms & 0x0400) ? 'S' : '-'));

        // World
        $info .= (($perms & 0x0004) ? 'r' : '-');
        $info .= (($perms & 0x0002) ? 'w' : '-');
        $info .= (($perms & 0x0001) ?
                        (($perms & 0x0200) ? 't' : 'x' ) :
                        (($perms & 0x0200) ? 'T' : '-'));
        return $info;
    }

    /**
     * Returns the clean  path to stay OS compatible
     *
     * @since 2.7
     * @access public
     * @return string  path.
     */
    public function getCleanPath($path) {
        if (empty($path)) {
            return './';
        }

        $path = trim(preg_replace("/\\\\/", "/", (string) $path));

        if (!preg_match("/(\.\w{1,4})$/", $path) && !preg_match("/\?[^\\/]+$/", $path) && !preg_match("/\\/$/", $path)) {
            $path .= '/';
        }

        $matches = array();
        $pattern = "/^(\\/|\w:\\/|https?:\\/\\/[^\\/]+\\/)?(.*)$/i";
        preg_match_all($pattern, $path, $matches, PREG_SET_ORDER);

        $pathTokR = $matches[0][1];
        $pathTokP = $matches[0][2];

        $pathTokP = preg_replace(array("/^\\/+/", "/\\/+/"), array("", "/"), $pathTokP);

        $pathParts = explode("/", $pathTokP);
        $realPathParts = array();

        for ($i = 0, $realPathParts = array(); $i < count($pathParts); $i++) {
            if ($pathParts[$i] == '.') {
                continue;
            } elseif ($pathParts[$i] == '..') {
                if ((isset($realPathParts[0]) && $realPathParts[0] != '..') || ($pathTokR != "")) {
                    array_pop($realPathParts);
                    continue;
                }
            }

            array_push($realPathParts, $pathParts[$i]);
        }

        return $pathTokR . implode('/', $realPathParts);
    }

    /**
     * Converts *nix style file permissions to a octal number.
     *
     * Converts '-rw-r--r--' to 0644
     * From "info at rvgate dot nl"'s comment on the PHP documentation for chmod()
     *
     * @link http://docs.php.net/manual/en/function.chmod.php#49614
     * @since 2.5
     * @access public
     *
     * @param string $mode string *nix style file permission
     * @return int octal representation
     */
    function getnumchmodfromh($mode) {
        $realmode = '';
        $legal = array('', 'w', 'r', 'x', '-');
        $attarray = preg_split('//', $mode);

        for ($i = 0; $i < count($attarray); $i++)
            if ($key = array_search($attarray[$i], $legal))
                $realmode .= $legal[$key];

        $mode = str_pad($realmode, 9, '-');
        $trans = array('-' => '0', 'r' => '4', 'w' => '2', 'x' => '1');
        $mode = strtr($mode, $trans);

        $newmode = '';
        $newmode .= $mode[0] + $mode[1] + $mode[2];
        $newmode .= $mode[3] + $mode[4] + $mode[5];
        $newmode .= $mode[6] + $mode[7] + $mode[8];
        return $newmode;
    }

}

?>
