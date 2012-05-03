<?

if (isset($_POST["phpinfo"])) {
    phpinfo();
	exit;
}


/* magento check script */

extension_check(array(
    'curl',
    'dom',
    'gd',
    'hash',
    'iconv',
    'mcrypt',
    'pcre',
    'pdo',
    'pdo_mysql',
    'simplexml'
));
 
function extension_check($extensions) {
    $fail = '';
   
    if(version_compare(phpversion(), '5.2.0', '<')) {
        $fail .= '<li>PHP 5.2.0 (or greater)</li>';
    }
   
    if(!ini_get('safe_mode')) {
        if(preg_match('/[0-9].[0-9]+.[0-9]+/', shell_exec('mysql -V'), $version)) {
            if(version_compare($version[0], '4.1.20', '<')) {
                $fail .= '<li>MySQL 4.1.20 (or greater)</li>';
            }
        }
    }
   
    foreach($extensions as $extension) {
        if(!extension_loaded($extension)) {
            $fail .= '<li>'.$extension.'</li>';
        }
    }
   
    if($fail) {
        echo '<p>Your server does not meet the requirements for Magento.';
        echo 'The following requirements failed:</p>';
        echo '<ul>'.$fail.'</ul>';
    } else {
        echo '<p>Congratulations! Your server meets the requirements for Magento.</p>';
    }
}

?>