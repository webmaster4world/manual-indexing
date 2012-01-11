<?php

/**
 * SSH2_Exception.php
 * Exception handler to call on specific SSH error and format message or mail 
 * idea http://php-xframe.googlecode.com/svn/trunk/model/core/FrameEx.php
 * by Linus Norton <linusnorton@gmail.com>
 *
 * @author Peter Hohl <peter.hohl@misystems.ch>
 * old Ssh2Ex new SSH2_Exception
 */
class SSH2_Exception extends Exception {

    protected $severity;

    const OFF = 0,
            CRITICAL = 1,
            HIGH = 2,
            MEDIUM = 3,
            LOW = 4,
            LOWEST = 5;

    /**
     * Creates the exception with a message and an error code that are
     * shown when the output method is called.
     *
     * @param String $message
     * @param int $code
     * @param int $severity
     * @param Exception $previous
     */
    public function __construct($message = null, $code = 0, $severity = self::HIGH, Exception $previous = null) {
        if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
            parent::__construct($message, (int) $code, $previous);
        } else {
            parent::__construct($message, (int) $code);
        }
        $this->severity = $severity;
    }

    /**
     * Reset the severity level
     * @param int $severity
     */
    public function setSeverity($severity) {
        $this->severity = $severity;
    }

    /**
     * Use the current registry settings to determine whether this error needs
     * to be logged or emailed (or both)
     */
    public function process() {
        if (Registry::get("ERROR_LOG_LEVEL") >= $this->severity) {
            $this->log();
        }
        if (Registry::get("ERROR_EMAIL_LEVEL") >= $this->severity) {
            $this->email();
        }
    }

    /**
     * Log using the error_log and LoggerManager
     */
    protected function log() {
        ////LoggerManager::getLogger("Exception")->error($this->message);
        ////error_log($this->message);
        echo $this->message;
    }

    /*
     * Email the error to the ADMIN
     */

    protected function email() {
        
        die("Comment out here to start mail ".__FILE__.":".__LINE__);
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'From: "' . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"] . '" <xframe@' . $_SERVER["SERVER_NAME"] . '>' . "\r\n";
        $headers .= 'Content-type: text/plain; charset=iso-8859-1' . "\r\n";
        mail('dummymail', $this->message, $this->getContent(), $headers);
    }

    /**
     * Get the XML for this exception
     */
    public function getXML() {
        $out = "<exception>";
        $out .= "<message>" . htmlspecialchars($this->message, ENT_COMPAT, "UTF-8", false) . "</message>";
        $out .= "<code>" . htmlspecialchars($this->code, ENT_COMPAT, "UTF-8", false) . "</code>";
        $out .= "<backtrace>";
        $i = 1;

        foreach ($this->getReversedTrace() as $back) {
            if ($back["class"] != "Ssh2Ex") {
                $out .= "<step number='" . $i++ . "' line='{$back['line']}' file='{$back['file']}' class='{$back['class']}' function='{$back['function']}' />";
            }
        }
        $out .= "</backtrace>";
        $out .= "</exception>";
        return $out;
    }

    /**
     * Return the array reversed back trace
     * @return array
     */
    public function getReversedTrace() {
        $trace = array();

        foreach (array_reverse($this->getTrace()) as $back) {
            $back['file'] = (array_key_exists("file", $back)) ? basename($back['file']) : "";
            $back['class'] = (array_key_exists("class", $back)) ? $back['class'] : "";
            $back['line'] = (array_key_exists("line", $back)) ? $back['line'] : "";
            $trace[] = $back;
        }

        return $trace;
    }

    /*
     * @return string
     */

    public function __toString() {
        try {
            return $this->getContent();
        } catch (Exception $e) {
            return "Error generating exception content. Original message: " . $this->message;
        }
    }

    /**
     * Get the readable content for this exception
     */
    private function getContent() {

        /* important die if not having DOMDocument class to check xml code!!!  */
        if (!class_exists('DOMDocument')) {
            die('DOMDocument is not configured. File->' . __FILE__ . ':' . __LINE__);
        }
        /* converto to other document standard */
        //////$xslFile = APP_DIR."view/".Registry::get("PLAIN_TEXT_ERROR").".xsl";
        $doc = new DOMDocument();
        /* warning mis masch html */
        if (!@$doc->loadXML("<root><exceptions>" . $this->getXML() . "</exceptions></root>")) {
            die('Unable to load xml Exception tag File->' . __FILE__ . ':' . __LINE__ . ' html->' . $html);
        }
        ////$transformation = new Transformation("<root><exceptions>".$this->getXML()."</exceptions></root>", $xslFile);
        /////return $transformation->execute();
        return $doc->saveXML();
    }

    /*
     * Set the error code
     * @param int $code
     */

    public function setCode($code) {
        $this->code = $code;
    }

    /**
     * Set the message
     * @param string $message
     */
    public function setMessage($message) {
        $this->message = $message;
    }

}

?>
