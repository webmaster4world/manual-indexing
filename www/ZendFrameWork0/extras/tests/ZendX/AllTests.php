<?php

/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   ZendX
 * @package    ZendX
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: AllTests.php 20183 2010-01-10 21:14:36Z freak $
 */

/**
 * Test helper
 */
require_once dirname(__FILE__) . '/../TestHelper.php';

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'ZendX_AllTests::main');
}

require_once 'ZendX/Application/AllTests.php';
require_once 'ZendX/Console/AllTests.php';
require_once 'ZendX/JQuery/AllTests.php';
require_once 'ZendX/Db/AllTests.php';

/**
 * @category   ZendX
 * @package    ZendX
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZendX_AllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Zend Framework Extras - ZendX');

        $suite->addTestSuite('ZendX_Application_AllTests');
        $suite->addTestSuite('ZendX_Console_AllTests');
        $suite->addTestSuite('ZendX_JQuery_AllTests');
        $suite->addTestSuite('ZendX_Db_AllTests');

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'ZendX_AllTests::main') {
    Zend_AllTests::main();
}
