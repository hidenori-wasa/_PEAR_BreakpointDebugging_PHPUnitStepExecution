<?php

/**
 * Classes for unit test.
 *
 * This file does not use except unit test. Therefore, response time is zero in release.
 * This file names put "_" to cause error when we do autoload.
 *
 * ### How to develop by this package. ###
 * Procedure 1: Create or change a class method.
 *      Then, write unfinished meaning code into its class method.
 *      Example: \BreakpointDebugging::registerNotFixedLocation(self::$_isRegister[__METHOD__]);
 * Procedure 2: Create or change its unit test.
 * Procedure 3: Do breakpoint debugging by execution of its unit test because as for code with many bugs, this way is earlier.
 * Procedure 4: Please, execute the following procedure.
 *      We must repair the code which was mistaken by step execution of all line of its class method.
 *      Because "php" language sometimes works in mistaken code.
 *      If correcting, return to procedure 3.
 *          1. Set many breakpoint to do step execution for all codes inside its class method.
 *          2. Then, do the step execution after breakpoint by execution of unit test.
 *          3. Then, erase its breakpoint if it succeeded.
 * Procedure 5: Comment out code meaning that its class method is incompletely.
 *
 * ### Running procedure. ###
 * Please, run the following procedure.
 * Procedure 1: Make page like "Example top page:" and pages like "Example page of unit test file (*Test.php, *TestSimple.php):".
 * Procedure 2: Run page like "Example top page:" with IDE.
 * Option Procedure: Copy from "PEAR/BreakpointDebugging/" directory and "PEAR/BreakpointDebugging_*.php" files to the project directory of remote server if you want remote unit test.
 * Option Procedure: "CakePHP" framework requires the following files.
 *      Customize "app/webroot/WasaCakeTestStart.php" which copied "app/webroot/test.php" as below.
 *          <pre><code>
 *          // Hidenori Wasa added. ===>
 *          // require_once CAKE . 'TestSuite' . DS . 'CakeTestSuiteDispatcher.php';
 *          require_once \CakePlugin::path('WasaPhpUnit') . 'TestSuite/WasaTestArrayDispatcher.php';
 *          // CakeTestSuiteDispatcher::run();
 *          \WasaTestArrayDispatcher::run();
 *          // <=== Hidenori Wasa added.
 *          </code></pre>
 *      Load "WasaPhpUnit" plugin inside "app/Config/bootstrap.php" as below.
 *          <pre><code>
 *          \CakePlugin::load('WasaPhpUnit', array ('bootstrap' => true));
 *          </code></pre>
 *          If this plugin cannot execute by difference of version, consult the following.
 *              Customize "app/Plugin/WasaPhpUnit/TestSuite/WasaTestArrayDispatcher.php" which extends "lib/Cake/TestSuite/CakeTestSuiteDispatcher.php".
 *                  Procedure: "dispatch()" override class method must keep instance to static property instead of dispatching.
 *                      And, "_checkPHPUnit()" inside of "dispatch()" must not be called because "BreakpointDebugging_PHPUnit" loads "PHPUnit".
 *                  Procedure: "run()" override class method must change class for "new".
 *                  Procedure: "static function runPHPUnitCommand($commandElements)" must exist because it is called from "\BreakpointDebugging_PHPUnit::_runPHPUnitCommand()".
 *                      And, "--output" command line switch must be deleted because "BreakpointDebugging_PHPUnit" displays.
 *                      And, "PHPUnit_Runner_StandardTestSuiteLoader" must be used instead of "CakeTestLoader" because test file path array must be loaded instead of suite.
 *              Customize "app/Plugin/WasaPhpUnit/TestSuite/WasaTestArrayCommand.php" which extends "lib/Cake/TestSuite/CakeTestSuiteCommand.php".
 *                  Procedure: "run()" override class method must be able to execute when second parameter is false because this is called inside test path array loop.
 *      Customize "app/Config/core.php" as below.
 *          <pre><code>
 *          // Hidenori Wasa added. ===>
 *          require_once './BreakpointDebugging_Inclusion.php';
 *
 *          // Defines debug level automatically.
 *          if (BREAKPOINTDEBUGGING_IS_PRODUCTION) { // In case of production server mode.
 *              \Configure::write('debug', 0);
 *          } else { // In case of development server mode.
 *              \Configure::write('debug', 2);
 *          }
 *          // <=== Hidenori Wasa added.
 *          </code></pre>
 *      Customize "lib/Cake/TestSuite/CakeTestCase.php" as below.
 *          <pre><code>
 *          // abstract class CakeTestCase extends PHPUnit_Framework_TestCase {
 *          //
 *          // Hidenori Wasa added. ===>
 *          $wasaStartPage = debug_backtrace();
 *          $wasaStartPage = array_pop($wasaStartPage);
 *          if (array_key_exists('class', $wasaStartPage)) {
 *              $wasaStartPage = $wasaStartPage['class'];
 *          } else {
 *              $wasaStartPage = '';
 *          }
 *          if ($wasaStartPage === 'CakeTestSuiteDispatcher') {
 *              // If unit tests start with "app/webroot/test.php".
 *              abstract class WasaCakeTestCase extends \PHPUnit_Framework_TestCase {}
 *          } else if ($wasaStartPage === 'BreakpointDebugging_PHPUnit') {
 *              // If unit tests start with "\BreakpointDebugging_PHPUnit::executeUnitTest()". (These tests use "app/webroot/WasaCakeTestStart.php" instead of "app/webroot/test.php".)
 *              abstract class WasaCakeTestCase extends \BreakpointDebugging_PHPUnit_FrameworkTestCase {}
 *          } else {
 *              throw new \WasaErrorException('Mistaken start page.');
 *          }
 *          unset($wasaStartPage);
 *
 *          abstract class CakeTestCase extends \WasaCakeTestCase {
 *          // <=== Hidenori Wasa added.
 *          </code></pre>
 *
 * Example top page:
 *      @see BreakpointDebugging_PHPUnit::executeUnitTest()
 *      @see BreakpointDebugging_PHPUnit::executeUnitTestSimple()
 *      @see BreakpointDebugging_PHPUnit::displayCodeCoverageReport()
 *      @see BreakpointDebugging_PHPUnit::displayCodeCoverageReportSimple()
 *
 * Example page of unit test file (*Test.php, *TestSimple.php):
 *      For "BreakpointDebugging_PHPUnit::executeUnitTest()" or "BreakpointDebugging_PHPUnit::displayCodeCoverageReport()".
 *          @see tests/PEAR/ExampleTest.php
 *      For "BreakpointDebugging_PHPUnit::executeUnitTestSimple()" or "BreakpointDebugging_PHPUnit::displayCodeCoverageReportSimple()".
 *          @see tests/PEAR/ExampleTestSimple.php
 *
 * ### Coding rule. ###
 * Please, follow rule, then, we can use unit test's "--static-backup" command line switch for execution with IDE.
 *
 * The rule 1: We must overwrite "null" to variable if we call "__destruct()" on the way in all code.
 *      Because server calls "__destruct()" even though reference storage exists.
 *      Example: $this->_pTestObject = null;
 * The rule 2: We must construct test instance inside "setUp()".
 *      Because we must initialize value and reference of auto properties (auto class method's local static variable and auto property).
 *      Example:
 *          protected function setUp()
 *          {
 *              // This is required at top.
 *              parent::setUp();
 *
 *              // We must construct the test instance here.
 *              $this->_pTestObject = &BreakpointDebugging_LockByFlock::singleton();
 *          }
 *
 * The file search detection rule 1: We must use property array element reference instead of property reference in all code.
 *      Because server cannot get property reference by reflection in "PHP version 5.3.0".
 *      Example of rule violation:
 *          ::$something = &
 *          or recursive array ::$something = array (&
 *      Instead:
 *          ::$something[0] = &
 *          or recursive array ::$something[0] = array (&
 *      Please, search the rule violation of file by the following regular expression.
 *          ::\$[_a-zA-Z][_a-zA-Z0-9]*[\x20\t\r\n]*=[^=;][^;]*&
 *      About reference copy.
 *          Reference copy must use "&self::" in case of self class.
 *          Reference copy must use "&parent::" in case of parent class above one hierarchy.
 *          Except those, Reference copy must use "&<official class name>::".
 *          Those is same about "$this".
 * The file search detection rule 2: We must not code except "tab and space" behind "@codeCoverageIgnore".
 *      Because of parsing to except '@codeCoverageIgnore' and "@codeCoverageIgnore" of code coverage report.
 *      Example of rule violation:
 *          @codeCoverageIgnore A sentence.
 *      Instead:
 *          @codeCoverageIgnore
 *          A sentence.
 *      Please, search the rule violation of file by the following regular expression.
 *          @codeCoverageIgnore[^SE\r\n][\t\x20]*[^\t\x20].*$
 * The file search detection rule 3: We must not use "filter_input()" and "filter_input_array()".
 *      Because we cannot execute "unit test" by super global variable change.
 *      Please, search the rule violation of file by the following regular expression.
 *          filter_input[\t\x20\r\n]*\(
 *          filter_input_array[\t\x20\r\n]*\(
 *
 * Autodetecting rule 1: Unit test file name of "PHPUnit" package should be "*Test.php".
 *      And, simple unit test file name must be "*TestSimple.php"
 *      because "PHPUnit" package searches "*Test.php" file.
 * Autodetecting rule 2: We must not delete or change static status by the autoload
 *      because autoload is executed only once per file.
 * Autodetecting rule 3: We must use private static property instead of use local static variable in static class method
 *      because "php" version 5.3.0 cannot restore its value.
 * Autodetecting rule 4: We must use private static property in class method instead of use local static variable in function
 *      because "php" version 5.3.0 cannot restore its value.
 * Autodetecting rule 5: We must operate any variable and any property inside "setUp()", test class methods or "tearDown()" about test code.
 *      Because we must test with same condition.
 *      So, global variables or static properties is restored with initial value before "setUp()".
 * Autodetecting rule 6: We must not register autoload function at top of stack by "spl_autoload_register()" in all code
 *      because server stores static status by autoload function.
 *      Example: spl_autoload_register('\SomethingClassName::autoloadFunctionName', true, true);
 * Autodetecting rule 7: We must not use unit test's "--process-isolation" command line switch because its tests is run in other process.
 *      Because we cannot debug unit test code with IDE.
 *
 * Recommendation rule 1: We should destruct a test instance per test in "tearDown()" because it cuts down on production server memory use.
 *      Example:
 *          protected function tearDown()
 *          {
 *              // Destructs the test instance.
 *              $this->_pTestObject = null;
 *
 *              // This is required at bottom.
 *              parent::tearDown();
 *          }
 * Recommendation rule 2: We should not use global variable to avoid variable crash.
 *
 * The special rule of "\BreakpointDebugging_PHPUnit_FrameworkTestCaseSimple":
 *      We must use try-catch statement instead of annotation.
 *      However, we can use following annotation.
 *          @codeCoverageSimpleIgnoreStart (Instead of "@codeCoverageIgnoreStart".)
 *          @codeCoverageSimpleIgnoreEnd (Instead of "@codeCoverageIgnoreEnd".)
 *      The class methods and property which can be used are limited below.
 *          \BreakpointDebugging_PHPUnit::$exeMode
 *          \BreakpointDebugging_PHPUnit::getPropertyForTest()
 *          \BreakpointDebugging_PHPUnit::setPropertyForTest()
 *          \BreakpointDebugging_PHPUnit::assertExceptionMessage()
 *          parent::markTestSkippedInDebug()
 *          parent::markTestSkippedInRelease()
 *          parent::assertTrue()
 *          parent::fail()
 *
 * Caution: Don't test an unit when practical use server has been running with synchronization file because synchronization is destroyed.
 *
 * How to run multiprocess unit test:
 *      Procedure 1: Use "popen()" inside your unit test class method "test...()".
 *      Procedure 2: Judge by using "parent::assertTrue(<conditional expression>)".
 *      @see tests_PEAR_BreakpointDebugging_MultiprocessTest_Main::test()
 *
 * PHP version 5.3.2-5.4.x
 *
 * LICENSE OVERVIEW:
 * 1. Do not change license text.
 * 2. Copyrighters do not take responsibility for this file code.
 *
 * LICENSE:
 * Copyright (c) 2013-2014, Hidenori Wasa
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice,
 * this list of conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice,
 * this list of conditions and the following disclaimer
 * in the documentation and/or other materials provided with the distribution.
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO,
 * THE IMPLIED WARRANTIES OF MERCHANTABILITY
 * AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT,
 * INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE,
 * EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category PHP
 * @package  BreakpointDebugging_PHPUnit
 * @author   Hidenori Wasa <public@hidenori-wasa.com>
 * @license  http://www.opensource.org/licenses/bsd-license.php  BSD 2-Clause
 * @version  Release: @package_version@
 * @link     http://pear.php.net/package/BreakpointDebugging_PHPUnit
 */
// File to have "use" keyword does not inherit scope into a file including itself,
// also it does not inherit scope into a file including,
// and moreover "use" keyword alias has priority over class definition,
// therefore "use" keyword alias does not be affected by other files.
use \BreakpointDebugging as B;
use \BreakpointDebugging_Window as BW;
use \BreakpointDebugging_PHPUnit_StaticVariableStorage as BSS;

B::limitAccess('BreakpointDebugging.php', true);
/**
 * Own package exception. For unit test.
 *
 * @category PHP
 * @package  BreakpointDebugging_PHPUnit
 * @author   Hidenori Wasa <public@hidenori-wasa.com>
 * @license  http://www.opensource.org/licenses/bsd-license.php  BSD 2-Clause
 * @version  Release: @package_version@
 * @link     http://pear.php.net/package/BreakpointDebugging_PHPUnit
 */
class BreakpointDebugging_Exception extends \BreakpointDebugging_Exception_InAllCase
{

    /**
     * Constructs instance.
     *
     * @param string $message                Exception message.
     * @param int    $id                     Exception identification number.
     * @param object $previous               Previous exception.
     * @param int    $omissionCallStackLevel Omission call stack level.
     *                                       Uses for assertion or error exception throwing because invokes plural inside a class method when we execute error unit test.
     *
     * @return void
     */
    function __construct($message, $id = null, $previous = null, $omissionCallStackLevel = 0)
    {
        B::assert(func_num_args() <= 4);
        B::assert(is_string($message));
        B::assert(is_int($id) || $id === null);
        B::assert($previous instanceof \Exception || $previous === null);

        if (mb_detect_encoding($message, 'utf8', true) === false) {
            throw new \BreakpointDebugging_ErrorException('Exception message is not "UTF8".', 101);
        }

        // Adds "[[[CLASS=<class name>] FUNCTION=<function name>] ID=<identification number>]" to message in case of unit test.
        if (B::getStatic('$exeMode') & B::UNIT_TEST) {
            B::assert(is_int($omissionCallStackLevel) && $omissionCallStackLevel >= 0);

            if ($id === null) {
                $idString = '.';
            } else {
                $idString = ' ID=' . $id . '.';
            }
            $function = '';
            $class = '';
            $callStack = $this->getTrace();
            if (array_key_exists($omissionCallStackLevel, $callStack)) {
                $call = $callStack[$omissionCallStackLevel];
                if (array_key_exists('function', $call)) {
                    $function = ' FUNCTION=' . $call['function'];
                }
                if (array_key_exists('class', $call)) {
                    $class = 'CLASS=' . $call['class'];
                }
            }
            $message .= "'  '" . $class . $function . $idString;
        }
        parent::__construct($message, $id, $previous);
    }

}

/**
 * Class for unit test.
 *
 * @category PHP
 * @package  BreakpointDebugging_PHPUnit
 * @author   Hidenori Wasa <public@hidenori-wasa.com>
 * @license  http://www.opensource.org/licenses/bsd-license.php  BSD 2-Clause
 * @version  Release: @package_version@
 * @link     http://pear.php.net/package/BreakpointDebugging_PHPUnit
 */
class BreakpointDebugging_PHPUnit
{
    /**
     * @var string The test directory.
     */
    private $_testDir;

    /**
     * @var string Full file path of "WasaCakeTestStart.php".
     */
    private $_WasaCakeTestStartPagePath;

    /**
     * @var object "\StaticVariableStorage" instance.
     */
    private $_staticVariableStorage;

    /**
     * @var string Unit test window name.
     */
    private $_unitTestWindowName;

    /**
     * @var bool Does it use "PHPUnit" package?
     */
    private $_phpUnitUse;

    /**
     * @var array Unit test file paths storage.
     */
    private static $_unitTestFilePathsStorage = array ();

    /**
     * @var int Execution mode.
     */
    static $exeMode;

    /**
     * @var  string Unit test directory.
     */
    static $unitTestDir;

    /**
     * @var mixed It is relative path of class which see the code coverage, and its current directory must be project directory.
     */
    private static $_classFilePaths;

    /**
     * @var string The code coverage report directory path.
     */
    private static $_codeCoverageReportPath;

    /**
     * @var string Separator for display.
     */
    private static $_separator;

    /**
     * @var bool Flag of once.
     */
    private static $_onceFlag = true;

    /**
     * @var string Unit test result.
     */
    private $_unitTestResult = 'DONE';

    /**
     * @var int The output buffering level.
     */
    private $_obLevel;

    /**
     * @var string How to test?
     */
    private static $_codeCoverageKind = 'PHPUNIT';

    /**
     * Limits static properties accessing of class.
     *
     * @return void
     *
     * @codeCoverageIgnore
     * Because this is code for unit test.
     */
    static function initialize()
    {
        B::limitAccess(basename(__FILE__), true);

        B::assert(func_num_args() === 0);

        $staticProperties = &B::refStaticProperties();
        $staticProperties['$_classFilePaths'] = &self::$_classFilePaths;
        $staticProperties['$_codeCoverageReportPath'] = &self::$_codeCoverageReportPath;
        $staticPropertyLimitings = &B::refStaticPropertyLimitings();
        $staticPropertyLimitings['$_includePaths'] = '';
        $staticPropertyLimitings['$_valuesToTrace'] = '';
        self::$exeMode = &B::refStatic('$exeMode'); // This is not rule violation because this property is not stored.
        self::$_separator = PHP_EOL . '//////////////////////////////////////////////////////////////////////////' . PHP_EOL;
    }

    function __construct()
    {
        $this->_WasaCakeTestStartPagePath = getcwd() . '/WasaCakeTestStart.php';
    }

    /**
     * Refers to the output buffering level.
     *
     * @return int The output buffering level.
     */
    function &refObLevel()
    {
        B::limitAccess('BreakpointDebugging/PHPUnit/FrameworkTestCaseSimple.php', true);

        return $this->_obLevel;
    }

    /**
     * Gets content of HTML file.
     */
    function getHtmlFileContent()
    {
        if ($this->_phpUnitUse) {
            $title = 'PHPUnit';
        } else {
            $title = 'PHPUnitSimple';
        }
        return <<<EOD
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <style type="text/css">
        <!--
            b {color: aqua}
            strong {color: fuchsia}
        -->
        </style>
        <title>{$title}</title>
    </head>
    <body style="background-color: black; color: white; font-size: 25px">
        <pre></pre>
    </body>
</html>
EOD;
    }

    /**
     * Gets unit test window name.
     *
     * @param type $phpUnit
     *
     * @return string Unit test window name.
     */
    static function getUnitTestWindowName($phpUnit)
    {
        return $phpUnit->_unitTestWindowName;
    }

    /**
     * Displays exception if release unit test error of "local or remote".
     *
     * @param object $pException Exception information.
     *
     * @return void
     *
     * @codeCoverageIgnore
     * Because unit test is exited.
     */
    static function displaysException($pException)
    {
        B::limitAccess('BreakpointDebugging.php', true);

        B::assert(func_num_args() === 1);
        B::assert($pException instanceof \Exception);

        $callStack = debug_backtrace();
        if (!array_key_exists(1, $callStack) //
            || !array_key_exists('file', $callStack[1]) //
            || strripos($callStack[1]['file'], 'FrameworkTestCase.php') === strlen($callStack[1]['file']) - strlen('FrameworkTestCase.php') //
        ) {
            B::iniSet('xdebug.var_display_max_depth', '5', false);
            ob_start();
            var_dump($pException);
            BW::exitForError(ob_get_clean());
        }
    }

    /**
     * Handles unit test exception.
     *
     * @param object $pException Exception information.
     *
     * @return void
     *
     * @codeCoverageIgnore
     * Because this is code for unit test.
     */
    static function handleUnitTestException($pException)
    {
        B::limitAccess(
            array (basename(__FILE__),
            'BreakpointDebugging_InDebug.php',
            'BreakpointDebugging.php'
            ), true
        );

        B::assert(func_num_args() === 1);
        B::assert($pException instanceof \Exception);

        $callStack = $pException->getTrace();
        $call = array_key_exists(0, $callStack) ? $callStack[0] : array ();
        // In case of direct call from "BreakpointDebugging_InAllCase::callExceptionHandlerDirectly()".
        // This call is in case of debug mode.
        if ((array_key_exists('class', $call) && $call['class'] === 'BreakpointDebugging_InAllCase') //
            && (array_key_exists('function', $call) && $call['function'] === 'callExceptionHandlerDirectly') //
        ) {
            throw $pException;
        }
    }

    /**
     * Gets unit test directory.
     *
     * @return void
     */
    private function _getUnitTestDir()
    {
        $callStack = debug_backtrace();
        $callStack = array_reverse($callStack);
        foreach ($callStack as $call) {
            if ($call['class'] !== 'BreakpointDebugging_PHPUnit') {
                continue;
            }
            $functionName = $call['function'];
            if ($functionName === 'executeUnitTestSimple' //
                || $functionName === 'executeUnitTest' //
                || $functionName === 'displayCodeCoverageReportSimple' //
                || $functionName === 'displayCodeCoverageReport' //
            ) {
                self::$unitTestDir = dirname($call['file']) . DIRECTORY_SEPARATOR;
                return;
            }
        }
        B::assert(false);
    }

    /**
     * Unit test error handler.
     *
     * @param int    $errorNumber  Error number.
     * @param string $errorMessage Error message.
     *
     * @return void
     */
    static function handleError($errorNumber, $errorMessage)
    {
        $errorMessage = B::convertMbString($errorMessage);
        throw new \BreakpointDebugging_ErrorException($errorMessage, $errorNumber);
    }

    /**
     * Runs "phpunit" command.
     *
     * @param string $command The command character-string which excepted "phpunit".
     *
     * @return void
     *
     * @codeCoverageIgnore
     * Because "phpunit" command cannot run during "phpunit" command running.
     */
    private function _runPHPUnitCommand($command)
    {
        $commandElements = explode(' ', $command);
        $testFileName = array_pop($commandElements);
        array_push($commandElements, self::$unitTestDir . $testFileName);
        array_unshift($commandElements, 'dummy');
        // Checks command line switches.
        if (in_array('--process-isolation', $commandElements)) {
            throw new \BreakpointDebugging_ErrorException('You must not use "--process-isolation" command line switch because this unit test is run in other process.' . PHP_EOL . 'So, you cannot debug unit test code with IDE.', 101);
        }
        $command = ltrim($command);
        echo self::$_separator;
        echo 'Runs <b>"' . str_replace('\\', '/', substr(realpath(self::$unitTestDir . $testFileName), strlen(realpath(self::$unitTestDir . $this->_testDir)) + 1)) . '"</b>.' . PHP_EOL;
        // Initializes once's flag per test file.
        $onceFlagPerTestFile = &BSS::refOnceFlagPerTestFile(); // This is not rule violation because this property is not stored.
        $onceFlagPerTestFile = true;
        if (self::$_onceFlag) {
            self::$_onceFlag = false;
            // Stores global variables.
            $globalRefs = &BSS::refGlobalRefs();
            $globals = &BSS::refGlobals();
            BSS::storeGlobals($globalRefs, $globals, array ());
            // Stores static properties.
            $staticProperties = &BSS::refStaticProperties2();
            $this->_staticVariableStorage->storeProperties($staticProperties, array ());
            // Registers autoload class method to check definition, deletion and change violation of global variables in bootstrap file, unit test file (*Test.php, *TestSimple.php), "setUpBeforeClass()" and "setUp()".
            // And, to check the change violation of static properties in bootstrap file, unit test file (*Test.php, *TestSimple.php), "setUpBeforeClass()" and "setUp()".
            // And, to store initial value of global variables and static properties.
            $result = spl_autoload_register(array ($this->_staticVariableStorage, 'loadClass'), true, true);
            B::assert($result);
        } else {
            // Restores global variables.
            BSS::restoreGlobals(BSS::refGlobalRefs(), BSS::refGlobals());
            // Restores static properties.
            BSS::restoreProperties(BSS::refStaticProperties2());
        }
        // Uses "PHPUnit" package error handler.
        set_error_handler('\PHPUnit_Util_ErrorHandler::handleError', E_ALL | E_STRICT);
        // Runs unit test continuously.
        include_once 'PHPUnit/Autoload.php';

        if (BREAKPOINTDEBUGGING_IS_CAKE) {
            \WasaTestArrayDispatcher::runPHPUnitCommand($commandElements);
        } else {
            $pPHPUnit_TextUI_Command = new \PHPUnit_TextUI_Command();

            if (self::$_codeCoverageKind === 'SIMPLE_OWN') {
                // Stops the code coverage report.
                xdebug_stop_code_coverage(false);
                $pPHPUnit_TextUI_Command->run($commandElements, false);
                // Resumes the code coverage report.
                xdebug_start_code_coverage(XDEBUG_CC_UNUSED | XDEBUG_CC_DEAD_CODE);
            } else {
                $pPHPUnit_TextUI_Command->run($commandElements, false);
            }
        }
        // Uses "BreakpointDebugging" package error handler.
        restore_error_handler();
    }

    /**
     * Runs a unit test.
     *
     * @param string $testFilePath A test file path.
     *
     * @return void
     */
    private function _runPHPUnitCommandSimple($testFilePath)
    {
        echo self::$_separator;
        echo "Runs <b>a unit test of \"$testFilePath\"</b> file." . PHP_EOL;
        // Initializes once's flag per test file.
        $onceFlagPerTestFile = &BSS::refOnceFlagPerTestFile(); // This is not rule violation because this property is not stored.
        $onceFlagPerTestFile = true;
        if (self::$_onceFlag) {
            self::$_onceFlag = false;
            // Stores global variables.
            $globalRefs = &BSS::refGlobalRefs();
            $globals = &BSS::refGlobals();
            BSS::storeGlobals($globalRefs, $globals, array ());
            // Stores static properties.
            $staticProperties = &BSS::refStaticProperties2();
            $this->_staticVariableStorage->storeProperties($staticProperties, array ());
            // Registers autoload class method to check definition, deletion and change violation of global variables in bootstrap file, unit test file (*Test.php, *TestSimple.php), "setUpBeforeClass()" and "setUp()".
            // And, to check the change violation of static properties in bootstrap file, unit test file (*Test.php, *TestSimple.php), "setUpBeforeClass()" and "setUp()".
            // And, to store initial value of global variables and static properties.
            $result = spl_autoload_register(array ($this->_staticVariableStorage, 'loadClass'), true, true);
            B::assert($result);
        } else {
            // Restores global variables.
            $globalRefs = BSS::refGlobalRefs();
            $globals = BSS::refGlobals();
            BSS::restoreGlobals($globalRefs, $globals);
            // Restores static properties.
            BSS::restoreProperties(BSS::refStaticProperties2());
        }
        // Uses this package error handler.
        set_error_handler('\BreakpointDebugging_PHPUnit::handleError', -1);
        // Includes unit test file.
        include_once self::$unitTestDir . $testFilePath;
        // Translates from a test file path to a test class name.
        $testClassName = substr(str_replace(array ('/', '-'), '_', $testFilePath), 0, strlen($testFilePath) - strlen('.php'));
        $declaredClasses = get_declared_classes();
        B::assert($testClassName === array_pop($declaredClasses));
        // Runs unit test continuously.
        \BreakpointDebugging_PHPUnit_FrameworkTestCaseSimple::runTestMethods($testClassName);
        // Uses "BreakpointDebugging" package error handler.
        restore_error_handler();
    }

    /**
     * Display the progress.
     *
     * @param int $dy Y vector distance.
     *
     * @return void
     *
     * @codeCoverageIgnore
     * Because this is code for unit test.
     */
    function displayProgress($dy = 0)
    {
        B::limitAccess(
            array (basename(__FILE__),
            'BreakpointDebugging/PHPUnit/FrameworkTestCase.php',
            'BreakpointDebugging/PHPUnit/FrameworkTestCaseSimple.php',
            ), true
        );

        // Displays the progress.
        $buffer = '';
        for ($count = 0; ob_get_level() > 0; $count++) {
            $result = ob_get_clean();
            if (is_string($result)) {
                if (strpos($result, 'I') === 0) {
                    $this->_unitTestResult = 'INCOMPLETE';
                    $result = '<strong>I</strong>' . substr($result, 1);
                }
                $buffer .= $result;
            }
        }
        BW::htmlAddition($this->_unitTestWindowName, 'pre', 0, $buffer);
        BW::scrollBy($this->_unitTestWindowName, $dy);
        flush();
        for (; $count > 0; $count--) {
            ob_start();
        }
    }

    /**
     * Deletes code coverage report.
     *
     * @return string Code coverage report path.
     *
     * @codeCoverageIgnore
     * Because this is code for unit test.
     */
    static function deleteCodeCoverageReport()
    {
        B::limitAccess(array (
            basename(__FILE__),
            './BreakpointDebugging_PHPUnit_DisplayCodeCoverageReport.php'
            ), true);

        $codeCoverageReportPath = B::getStatic('$_workDir') . '/CodeCoverageReport/';
        // Deletes code coverage report directory files.
        if (is_dir($codeCoverageReportPath)) {
            foreach (scandir($codeCoverageReportPath) as $codeCoverageReportDirElement) {
                $errorLogDirElementPath = $codeCoverageReportPath . $codeCoverageReportDirElement;
                if (is_file($errorLogDirElementPath)) {
                    // Deletes a file.
                    B::unlink(array ($errorLogDirElementPath));
                }
            }
        }
        return $codeCoverageReportPath;
    }

    //////////////////////////////////////// For package user ////////////////////////////////////////
    /**
     * Asserts exception message.
     *
     * @param object $exception Exception object.
     * @param string $message   Message to compare.
     */
    static function assertExceptionMessage($exception, $message)
    {
        B::assert($exception instanceof \Exception);
        B::assert(is_string($message));

        if (strpos($exception->getMessage(), $message) === false) {
            B::exitForError($exception->getMessage()); // Displays error call stack information.
        }
    }

    /**
     * Marks the test as skipped in debug.
     *
     * @return void
     */
    static function markTestSkippedInDebug()
    {
        if (!(self::$exeMode & B::RELEASE)) {
            \PHPUnit_Framework_Assert::markTestSkipped();
        }
    }

    /**
     * Marks the test as skipped in release.
     *
     * @return void
     */
    static function markTestSkippedInRelease()
    {
        if (self::$exeMode & B::RELEASE) {
            \PHPUnit_Framework_Assert::markTestSkipped();
        }
    }

    /**
     * Calls class method for test.
     *
     * @param array $params Parameters.
     * <pre>
     * ### Template code. ###
     *
     * <code>
     * array(
     *      'objectOrClassName' => ''
     *      'methodName' => ''
     * )
     * </code>
     *
     * ### Static class method call's example code. ###
     *
     * <code>
     * array(
     *      'objectOrClassName' => 'ClassName'
     *      'methodName' => 'staticMethodName'
     * )
     * </code>
     *
     * ### Auto class method call's example code. ###
     *
     * <code>
     * array(
     *      'objectOrClassName' => $object
     *      'methodName' => 'autoMethodName'
     * )
     * </code>
     *
     * </pre>
     *
     * @return mixed Return value of called class method.
     */
    static function callForTest($params)
    {
        extract($params);
        $objectOrClassName;
        $methodName;
    }

    /**
     * Gets property for test.
     *
     * @param mixed  $objectOrClassName A object or class name.
     * @param string $propertyName      Property name or constant name.
     *
     * @return mixed Property value.
     *
     * @example <code>$propertyValue = \BreakpointDebugging_PHPUnit::getPropertyForTest('ClassName', 'CONST_NAME');</code>
     * @example <code>$propertyValue = \BreakpointDebugging_PHPUnit::getPropertyForTest('ClassName', '$_privateStaticName');</code>
     * @example <code>$propertyValue = \BreakpointDebugging_PHPUnit::getPropertyForTest($object, '$_privateStaticName');</code>
     * @example <code>$propertyValue = \BreakpointDebugging_PHPUnit::getPropertyForTest($object, '$_privateAutoName');</code>
     */
    static function getPropertyForTest($objectOrClassName, $propertyName)
    {
        B::assert(func_num_args() === 2);
        B::assert(is_string($propertyName));
        B::assert(is_object($objectOrClassName) || is_string($objectOrClassName));

        if (is_object($objectOrClassName)) {
            $className = get_class($objectOrClassName);
        } else {
            $className = $objectOrClassName;
        }
        $classReflection = new \ReflectionClass($className);
        $propertyReflections = $classReflection->getProperties();
        foreach ($propertyReflections as $propertyReflection) {
            $propertyReflection->setAccessible(true);
            $paramName = '$' . $propertyReflection->getName();
            if ($paramName !== $propertyName) {
                continue;
            }
            if ($propertyReflection->isStatic()) {
                return $propertyReflection->getValue($propertyReflection);
            } else {
                return $propertyReflection->getValue($objectOrClassName);
            }
        }
        $constants = $classReflection->getConstants();
        foreach ($constants as $constName => $constValue) {
            if ($constName !== $propertyName) {
                continue;
            }
            return $constValue;
        }
        throw new \BreakpointDebugging_ErrorException('"' . $className . '::' . $propertyName . '" property does not exist.', 101);
    }

    /**
     * Sets property for test.
     *
     * @param mixed  $objectOrClassName A object or class name.
     * @param string $propertyName      Property name or constant name.
     * @param mixed  $value             A value to set.
     *
     * @return void
     *
     * @example <code>\BreakpointDebugging_PHPUnit::setPropertyForTest('ClassName', '$_privateStaticName', $value);</code>
     * @example <code>\BreakpointDebugging_PHPUnit::setPropertyForTest($object, '$_privateStaticName', $value);</code>
     * @example <code>\BreakpointDebugging_PHPUnit::setPropertyForTest($object, '$_privateAutoName', $value);</code>
     */
    static function setPropertyForTest($objectOrClassName, $propertyName, $value)
    {
        B::assert(func_num_args() === 3);
        B::assert(is_string($propertyName));
        B::assert(is_object($objectOrClassName) || is_string($objectOrClassName));

        if (is_object($objectOrClassName)) {
            $className = get_class($objectOrClassName);
        } else {
            $className = $objectOrClassName;
        }
        $classReflection = new \ReflectionClass($className);
        $propertyReflections = $classReflection->getProperties();
        foreach ($propertyReflections as $propertyReflection) {
            $propertyReflection->setAccessible(true);
            $paramName = '$' . $propertyReflection->getName();
            if ($paramName !== $propertyName) {
                continue;
            }
            if ($propertyReflection->isStatic()) {
                $propertyReflection->setValue($propertyReflection, $value);
                return;
            } else {
                $propertyReflection->setValue($objectOrClassName, $value);
                return;
            }
        }
        throw new \BreakpointDebugging_ErrorException('"' . $className . '::' . $propertyName . '" property does not exist.', 101);
    }

    /**
     * Checks unit-test-execution-mode, and sets unit test directory.
     *
     * @param bool $isUnitTest It is unit test?
     *
     * @return void
     *
     * @codeCoverageIgnore
     * Because this exits.
     */
    static function checkExeMode($isUnitTest = false)
    {
        B::assert(is_bool($isUnitTest));

        if (!$isUnitTest) {
            $errorMessage = <<<EOD
You must set
    "define('BREAKPOINTDEBUGGING_MODE', 'DEBUG');" or
    "define('BREAKPOINTDEBUGGING_MODE', 'RELEASE');"
into "BreakpointDebugging_MySetting.php".
EOD;
            BW::exitForError('<b>' . $errorMessage . '</b>');
        }
    }

    /**
     * Prepares unit test.
     */
    private function _prepareUnitTest($howToTest = 'PHPUNIT')
    {
        // Preloads error classes.
        class_exists('BreakpointDebugging_Error');
        // Sets component pear package inclusion paths.
        $pearDir = `pear config-get php_dir`;
        if (isset($pearDir)) {
            $componentDir = PATH_SEPARATOR . rtrim($pearDir) . '/BreakpointDebugging/Component';
        } else {
            $componentDir = '';
        }
        $includePaths = explode(PATH_SEPARATOR, ini_get('include_path'));
        array_unshift($includePaths, $includePaths[0]);
        $includePaths[1] = __DIR__ . '/BreakpointDebugging/Component' . $componentDir;
        ini_set('include_path', implode(PATH_SEPARATOR, $includePaths));

        switch ($howToTest) {
            case 'SIMPLE':
                $isUnitTestClass = function ($declaredClassName) {
                    set_error_handler('\BreakpointDebugging::handleError', 0);
                    // Excepts unit test classes.
                    if ('BreakpointDebugging_PHPUnit_StaticVariableStorage' === $declaredClassName //
                        || 'BreakpointDebugging_Window' === $declaredClassName //
                        || @is_subclass_of($declaredClassName, 'BreakpointDebugging_PHPUnit_FrameworkTestCaseSimple') //
                    ) {
                        restore_error_handler();
                        return true;
                    }
                    restore_error_handler();
                    return false;
                };
                $this->_phpUnitUse = false;
                $this->_unitTestWindowName = 'BreakpointDebugging_PHPUnitSimple';
                break;
            case 'PHPUNIT':
                $isUnitTestClass = function ($declaredClassName) {
                    static $exclusionClassNames = array (
                        // Required class names.
                        'App' => true, // "CakePHP" class.
                        'BaseCoverageReport' => true, // "CakePHP" class.
                        'BreakpointDebugging_Window' => true,
                        'BreakpointDebugging_PHPUnit_StaticVariableStorage' => true,
                        'CakeBaseReporter' => true, // "CakePHP" class.
                        'CakeFixtureManager' => true, // "CakePHP" class.
                        'CakeHtmlReporter' => true, // "CakePHP" class.
                        'CakeTestCase' => true, // "CakePHP" class.
                        'CakeTestFixture' => true, // "CakePHP" class.
                        'CakeTestLoader' => true, // "CakePHP" class.
                        'CakeTestModel' => true, // "CakePHP" class.
                        'CakeTestRunner' => true, // "CakePHP" class.
                        'CakeTestSuite' => true, // "CakePHP" class.
                        'CakeTestSuiteCommand' => true, // "CakePHP" class.
                        'CakeTestSuiteDispatcher' => true, // "CakePHP" class.
                        'CakeTextReporter' => true, // "CakePHP" class.
                        'ClassRegistry' => true, // "CakePHP" class.
                        'ControllerTestCase' => true, // "CakePHP" class.
                        'HtmlCoverageReport' => true, // "CakePHP" class.
                        'TextCoverageReport' => true, // "CakePHP" class.
                        'WasaTestArrayCommand' => true, // Wasa's "CakePHP" class.
                        'WasaTestArrayDispatcher' => true, // Wasa's "CakePHP" class.
                        // Optional class names.
                        'AclException' => true, // "CakePHP" class.
                        'BadRequestException' => true, // "CakePHP" class.
                        'BaseLog' => true, // "CakePHP" class.
                        'BreakpointDebugging_Error' => true,
                        'BreakpointDebugging_ErrorInAllCase' => true,
                        'BreakpointDebugging_PHPUnit' => true,
                        'BreakpointDebugging_PHPUnit_FrameworkTestCaseSimple' => true,
                        'BreakpointDebugging_Shmop' => true,
                        'BreakpointDebugging\NativeFunctions' => true,
                        'Cache' => true, // "CakePHP" class.
                        'CacheEngine' => true, // "CakePHP" class.
                        'CacheException' => true, // "CakePHP" class.
                        'CakeBaseException' => true, // "CakePHP" class.
                        'CakeException' => true, // "CakePHP" class.
                        'CakeLog' => true, // "CakePHP" class.
                        'CakeLogException' => true, // "CakePHP" class.
                        'CakePlugin' => true, // "CakePHP" class.
                        'CakeSessionException' => true, // "CakePHP" class.
                        'Configure' => true, // "CakePHP" class.
                        'ConfigureException' => true, // "CakePHP" class.
                        'ConsoleException' => true, // "CakePHP" class.
                        'Debugger' => true, // "CakePHP" class.
                        'ErrorHandler' => true, // "CakePHP" class.
                        'FatalErrorException' => true, // "CakePHP" class.
                        'FileEngine' => true, // "CakePHP" class.
                        'FileLog' => true, // "CakePHP" class.
                        'ForbiddenException' => true, // "CakePHP" class.
                        'Hash' => true, // "CakePHP" class.
                        'HttpException' => true, // "CakePHP" class.
                        'Inflector' => true, // "CakePHP" class.
                        'InternalErrorException' => true, // "CakePHP" class.
                        'LogEngineCollection' => true, // "CakePHP" class.
                        'MethodNotAllowedException' => true, // "CakePHP" class.
                        'MissingActionException' => true, // "CakePHP" class.
                        'MissingBehaviorException' => true, // "CakePHP" class.
                        'MissingComponentException' => true, // "CakePHP" class.
                        'MissingConnectionException' => true, // "CakePHP" class.
                        'MissingControllerException' => true, // "CakePHP" class.
                        'MissingDatabaseException' => true, // "CakePHP" class.
                        'MissingDatasourceConfigException' => true, // "CakePHP" class.
                        'MissingDatasourceException' => true, // "CakePHP" class.
                        'MissingDispatcherFilterException' => true, // "CakePHP" class.
                        'MissingHelperException' => true, // "CakePHP" class.
                        'MissingLayoutException' => true, // "CakePHP" class.
                        'MissingModelException' => true, // "CakePHP" class.
                        'MissingPluginException' => true, // "CakePHP" class.
                        'MissingShellException' => true, // "CakePHP" class.
                        'MissingShellMethodException' => true, // "CakePHP" class.
                        'MissingTableException' => true, // "CakePHP" class.
                        'MissingTaskException' => true, // "CakePHP" class.
                        'MissingTestLoaderException' => true, // "CakePHP" class.
                        'MissingViewException' => true, // "CakePHP" class.
                        'NotFoundException' => true, // "CakePHP" class.
                        'NotImplementedException' => true, // "CakePHP" class.
                        'ObjectCollection' => true, // "CakePHP" class.
                        'PEAR_Exception' => true,
                        'PrivateActionException' => true, // "CakePHP" class.
                        'RouterException' => true, // "CakePHP" class.
                        'SocketException' => true, // "CakePHP" class.
                        'String' => true, // "CakePHP" class.
                        'UnauthorizedException' => true, // "CakePHP" class.
                        'XmlException' => true, // "CakePHP" class.
                    );

                    set_error_handler('\BreakpointDebugging::handleError', 0);
                    // Excepts unit test classes.
                    if (preg_match('`^ (PHP (Unit | (_ (CodeCoverage | Invoker | (T (imer | oken_Stream))))) | File_Iterator | sfYaml | Text_Template )`xX', $declaredClassName) === 1 //
                        || @is_subclass_of($declaredClassName, 'PHPUnit_Framework_Test') //
                        || array_key_exists($declaredClassName, $exclusionClassNames)
                    ) {
                        restore_error_handler();
                        return true;
                    }
                    restore_error_handler();
                    return false;
                };
                $this->_phpUnitUse = true;
                $this->_unitTestWindowName = 'BreakpointDebugging_PHPUnit';
                \BreakpointDebugging_PHPUnit_FrameworkTestCase::setPHPUnit($this);
                break;
            case 'PHPUNIT_OWN':
                $this->_phpUnitUse = true;
                $this->_unitTestWindowName = 'BreakpointDebugging_PHPUnit';
                \BreakpointDebugging_PHPUnit_FrameworkTestCase::setPHPUnit($this);
            case 'SIMPLE_OWN':
                $isUnitTestClass = function ($declaredClassName) {
                    set_error_handler('\BreakpointDebugging::handleError', 0);
                    // Excepts unit test classes.
                    if (preg_match('`^ (BreakpointDebugging_ (Window | PHPUnit_StaticVariableStorage)) | (PHP (Unit | (_ (CodeCoverage | Invoker | (T (imer | oken_Stream))))) | File_Iterator | sfYaml | Text_Template )`xX', $declaredClassName) === 1 //
                        || @is_subclass_of($declaredClassName, 'PHPUnit_Framework_Test') //
                        || @is_subclass_of($declaredClassName, 'BreakpointDebugging_PHPUnit_FrameworkTestCaseSimple') //
                    ) {
                        restore_error_handler();
                        return true;
                    }
                    restore_error_handler();
                    return false;
                };
                if (!isset($this->_phpUnitUse)) {
                    $this->_phpUnitUse = false;
                    $this->_unitTestWindowName = 'BreakpointDebugging_PHPUnitSimple';
                }
                break;
            default:
                throw new \BreakpointDebugging_ErrorException('Class method parameter is incorrect.');
        }
        $this->_staticVariableStorage = new \BreakpointDebugging_PHPUnit_StaticVariableStorage($isUnitTestClass);
        // Sets this instance to unit test class.
        \BreakpointDebugging_PHPUnit_FrameworkTestCaseSimple::setPHPUnit($this);
        B::setPHPUnit($this);
    }

    /**
     * Gets "\StaticVariableStorage" instance.
     *
     * @return void
     */
    function getStaticVariableStorageInstance()
    {
        return $this->_staticVariableStorage;
    }

    /**
     * Gets verification test file paths.
     *
     * @param string $howToTest How to test?
     *      'PHPUNIT':     Uses "PHPUnit" package.
     *      'PHPUNIT_OWN': This package's 'PHPUNIT' mode test.
     *      'SIMPLE':      Does not use "PHPUnit" package. This mode can be used instead of "*.phpt" file.
     *      'SIMPLE_OWN':  This package test.
     *
     * @return array Verification test file paths.
     */
    private function _getVerificationTestFilePaths($howToTest)
    {
        // Sets regular expression to get test file paths.
        if ($howToTest === 'SIMPLE' || $howToTest === 'SIMPLE_OWN') {
            $regEx = '`.* TestSimple\.php $`xX';
        } else {
            $regEx = '`.* Test\.php $`xX';
        }
        $verificationTestFilePaths = array ();
        // Sets the full test directory path.
        $fullTestDirPath = self::$unitTestDir . $this->_testDir;
        // If test directory specification is mistaken.
        if (!is_dir($fullTestDirPath)) {
            throw new \BreakpointDebugging_ErrorException('Mistaken test directory specification.', 101);
        }
        // Gets test file paths recursively.
        $fileObjects = new RegexIterator(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($fullTestDirPath)), $regEx);
        foreach ($fileObjects as $fileObject) {
            // Gets a full test file path.
            $fullFilePath = $fileObject->getPathname();
            // Gets a relative test file path.
            $testFilePath = substr($fullFilePath, strlen($fullTestDirPath));
            // If "PHPUnit" PEAR package has been used.
            if ($howToTest === 'PHPUNIT' || $howToTest === 'PHPUNIT_OWN') {
                // Excepts the test suite class file path.
                $className = basename($fullFilePath, '.php');
                if (preg_match('`^ [_[:alpha:]] [_[:alnum:]]* $`xX', $className) === 1) {
                    include_once $fullFilePath;
                    if (in_array($className, get_declared_classes()) //
                        && is_subclass_of($className, 'PHPUnit_Framework_TestSuite') //
                    ) {
                        continue;
                    }
                }
            }
            // Registers a verification test file path.
            $verificationTestFilePaths[] = str_replace('\\', '/', $testFilePath);
        }

        return $verificationTestFilePaths;
    }

    /**
     * Sets the test directory.
     *
     * @param string $testDir The test directory.
     */
    function setTestDir($testDir)
    {
        $this->_testDir = $testDir;
    }

    /**
     * Executes unit test files continuously, and debugs with IDE.
     *
     * <pre>
     * Example top page:
     *
     * <code>
     *      <?php
     *
     *      // Changes current directory to web root.
     *      chdir('../../');
     *      require_once './BreakpointDebugging_Inclusion.php';
     *
     *      use \BreakpointDebugging as B;
     *
     *      B::checkExeMode(true);
     *      $breakpointDebugging_PHPUnit = new \BreakpointDebugging_PHPUnit();
     *      ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     *      // Please, choose unit tests files by customizing.
     *      $breakpointDebugging_UnitTestFiles = array (
     *          'SomethingTest.php',
     *          'Something/SubTest.php',
     *      );
     *
     *      // Specifies the test directory.
     *      $breakpointDebugging_PHPUnit->setTestDir('../../../Plugin/WasaPhpUnit/Test/Case/'); // This directory if unspecify.
     *      // Executes unit tests.
     *      $breakpointDebugging_PHPUnit->executeUnitTest($breakpointDebugging_UnitTestFiles); exit;
     *
     *      ?>
     * </code>
     *
     * </pre>
     * @param array  $testFilePaths       The file paths of unit tests.
     * @param string $commandLineSwitches Command-line-switches except "--stop-on-failure --static-backup".
     * @param string $howToTest           How to test?
     *      'PHPUNIT':     Uses "PHPUnit" package.
     *      'PHPUNIT_OWN': This package's 'PHPUNIT' mode test.
     *      'SIMPLE':      Does not use "PHPUnit" package. This mode can be used instead of "*.phpt" file.
     *      'SIMPLE_OWN':  This package test.
     *
     * @return void
     *
     * @codeCoverageIgnore
     * Because "phpunit" command cannot run during "phpunit" command running.
     */
    function executeUnitTest($testFilePaths, $commandLineSwitches = '', $howToTest = 'PHPUNIT')
    {
        B::assert(func_num_args() <= 3);
        B::assert(is_array($testFilePaths));
        B::assert(!empty($testFilePaths));
        B::assert(is_string($commandLineSwitches));

        if (!B::checkDevelopmentSecurity()) {
            exit;
        }

        $this->_prepareUnitTest($howToTest);

        foreach ($testFilePaths as $testFilePath) {
            if (($howToTest === 'SIMPLE' || $howToTest === 'SIMPLE_OWN') //
                && substr($testFilePath, 0 - strlen('TestSimple.php')) !== 'TestSimple.php' //
            ) {
                throw new \BreakpointDebugging_ErrorException('Simple unit test file name must be "*TestSimple.php".', 101);
            }
            if (array_key_exists($testFilePath, self::$_unitTestFilePathsStorage)) {
                throw new \BreakpointDebugging_ErrorException('Unit test file path must be unique.', 101);
            }
            self::$_unitTestFilePathsStorage[$testFilePath] = true;
        }

        BW::virtualOpen($this->_unitTestWindowName, $this->getHtmlFileContent());
        ob_start();

        if (self::$exeMode & B::RELEASE) {
            echo '<b>\'RELEASE_UNIT_TEST\' execution mode.</b>' . PHP_EOL;
        } else {
            echo '<b>\'DEBUG_UNIT_TEST\' execution mode.</b>' . PHP_EOL;
        }

        $this->_getUnitTestDir();
        echo 'The test current directory = <b>"' . str_replace('\\', '/', realpath(self::$unitTestDir . $this->_testDir)) . '/"</b>' . PHP_EOL;

        if (BREAKPOINTDEBUGGING_IS_CAKE) {
            // Changes autoload class method order.
            spl_autoload_unregister('\BreakpointDebugging::loadClass');
            require $this->_WasaCakeTestStartPagePath;
            spl_autoload_register('\BreakpointDebugging::loadClass');
            if (!BREAKPOINTDEBUGGING_IS_PRODUCTION) { // In case of development server mode.
                // Checks the fact that "CakeLog" configuration is not defined because "BreakpointDebugging" pear package does logging.
                $wasaResult = \CakeLog::configured();
                if (!empty($wasaResult)) {
                    throw new \WasaErrorException('You must not configure the "CakeLog" by "\CakeLog::config(..." inside "app/Config/bootstrap.php".');
                }
            }
        }

        foreach ($testFilePaths as $testFilePath) {
            $testFullFilePath = $this->_testDir . $testFilePath;
            // If unit test file does not exist.
            if (!is_file(self::$unitTestDir . $testFullFilePath)) {
                throw new \BreakpointDebugging_ErrorException('Unit test file "' . $testFullFilePath . '" does not exist.', 102);
            }
            // Registers the executed full test file path.
            $executedTestFilePaths[] = $testFilePath;
            // If test file path contains '_'.
            if (strpos($testFullFilePath, '_') !== false) {
                echo "You have to change from '_' of '$testFullFilePath' to '-' because you cannot run unit tests." . PHP_EOL;
                if (function_exists('xdebug_break') //
                    && !(self::$exeMode & B::IGNORING_BREAK_POINT) //
                ) {
                    xdebug_break();
                }
                continue;
            }
            if ($howToTest === 'SIMPLE' //
                || $howToTest === 'SIMPLE_OWN' //
            ) {
                $this->_runPHPUnitCommandSimple($testFullFilePath);
            } else {
                $this->_runPHPUnitCommand($commandLineSwitches . ' --stop-on-failure --static-backup ' . $testFullFilePath);
            }
            gc_collect_cycles();
        }
        $this->displayProgress();
        echo self::$_separator;
        $this->_staticVariableStorage->checkFunctionLocalStaticVariable();
        $this->_staticVariableStorage->checkMethodLocalStaticVariable();

        switch ($this->_unitTestResult) {
            case 'DONE':
                echo '<b>Unit tests was completed.</b>' . PHP_EOL;
                break;
            case 'INCOMPLETE':
                echo '<strong>Unit tests was incompletely.</strong>' . PHP_EOL;
                break;
            default:
                B::assert(false);
        }

        $diffTestFilePaths = array_diff($this->_getVerificationTestFilePaths($howToTest), $executedTestFilePaths);
        if (!empty($diffTestFilePaths)) {
            echo self::$_separator;
            echo '<b>The following test file paths had not been executed.</b>' . PHP_EOL;
        }
        foreach ($diffTestFilePaths as $diffTestFilePath) {
            echo '<strong>\'' . $diffTestFilePath . '\',</strong>' . PHP_EOL;
        }

        BW::htmlAddition($this->_unitTestWindowName, 'pre', 0, ob_get_clean());
        BW::front($this->_unitTestWindowName);
        BW::scrollBy($this->_unitTestWindowName, PHP_INT_MAX, PHP_INT_MAX);
    }

    /**
     * Executes unit test files continuously without "PHPUnit" package, and debugs with IDE.
     *
     * @param array  $testFilePaths       The file paths of unit tests.
     * @param string $howToTest           How to test?
     *      'SIMPLE': Does not use "PHPUnit" package. This mode can be used instead of "*.phpt" file.
     *      'SIMPLE_OWN': This package test.
     *
     * @return void
     *
     * Example top page:
     *      <?php
     *
     *      chdir(str_repeat('../', preg_match_all('`/`xX', $_SERVER['PHP_SELF'], $matches) - 2));
     *      unset($matches);
     *
     *      require_once './BreakpointDebugging_Inclusion.php';
     *
     *      B::checkExeMode(true);
     *
     *      // Please, choose unit tests files by customizing.
     *      $unitTestFilePaths = array (
     *          'SomethingTest.php',
     *          'Something/SubTest.php',
     *      );
     *
     *      // Executes unit tests.
     *      $breakpointDebugging_PHPUnit = new \BreakpointDebugging_PHPUnit();
     *      $breakpointDebugging_PHPUnit->executeUnitTestSimple($unitTestFilePaths); exit;
     *
     *      ?>
     */
    function executeUnitTestSimple($testFilePaths, $howToTest = 'SIMPLE')
    {
        $this->executeUnitTest($testFilePaths, '', $howToTest);
    }

    /**
     * Creates code coverage report, then displays in browser.
     *
     * @param string $testFilePath        Relative path of unit test file.
     * @param mixed  $classFilePaths      It is relative path of class which see the code coverage, and its current directory must be project directory.
     * @param string $commandLineSwitches Command-line-switches except "--static-backup --coverage-html".
     *
     * @return void
     *
     * Example top page:
     *      <?php
     *
     *      chdir(str_repeat('../', preg_match_all('`/`xX', $_SERVER['PHP_SELF'], $matches) - 2));
     *      unset($matches);
     *
     *      require_once './BreakpointDebugging_Inclusion.php';
     *
     *      B::checkExeMode(true);
     *      // Makes up code coverage report, then displays in browser.
     *      $breakpointDebugging_PHPUnit = new \BreakpointDebugging_PHPUnit();
     *      $breakpointDebugging_PHPUnit->displayCodeCoverageReport('BreakpointDebugging-InAllCaseTest.php', 'PEAR/BreakpointDebugging.php'); exit;
     *      // Or, "$breakpointDebugging_PHPUnit->displayCodeCoverageReport('BreakpointDebugging/LockByFileExistingTest.php', array ('PEAR/BreakpointDebugging/Lock.php', 'PEAR/BreakpointDebugging/LockByFileExisting.php')); exit;"
     *
     *      ?>
     *
     * @codeCoverageIgnore
     * Because "phpunit" command cannot run during "phpunit" command running.
     */
    function displayCodeCoverageReport($testFilePath, $classFilePaths, $commandLineSwitches = '')
    {
        if (!B::checkDevelopmentSecurity()) {
            exit;
        }

        B::assert(func_num_args() <= 4);
        B::assert(is_string($testFilePath));
        B::assert(is_string($classFilePaths) || is_array($classFilePaths));

        if (!extension_loaded('xdebug')) {
            B::exitForError('"\BreakpointDebugging_PHPUnit::displayCodeCoverageReport()" needs "xdebug" extention.');
        }

        $this->_prepareUnitTest();

        // Deletes code coverage report.
        $codeCoverageReportPath = self::deleteCodeCoverageReport();
        $this->_getUnitTestDir();
        // Creates code coverage report.
        $displayErrorsStorage = ini_get('display_errors');
        ini_set('display_errors', '');

        BW::virtualOpen($this->_unitTestWindowName, $this->getHtmlFileContent());
        ob_start();

        if (self::$exeMode & B::RELEASE) {
            echo '<b>\'RELEASE_UNIT_TEST\' execution mode.</b>' . PHP_EOL;
        } else {
            echo '<b>\'DEBUG_UNIT_TEST\' execution mode.</b>' . PHP_EOL;
        }

        if (BREAKPOINTDEBUGGING_IS_CAKE) {
            // Changes autoload class method order.
            spl_autoload_unregister('\BreakpointDebugging::loadClass');
            require $this->_WasaCakeTestStartPagePath;
            spl_autoload_register('\BreakpointDebugging::loadClass');
        }

        $this->_runPHPUnitCommand($commandLineSwitches . ' --static-backup --coverage-html ' . $codeCoverageReportPath . ' ' . $testFilePath);

        BW::htmlAddition($this->_unitTestWindowName, 'pre', 0, ob_get_clean());

        ini_set('display_errors', $displayErrorsStorage);
        // Displays the code coverage report in browser.
        $documentRoot = $_SERVER['DOCUMENT_ROOT'];
        $dir = str_replace('\\', '/', __DIR__);
        if (preg_match("`^$documentRoot`xX", $dir) === 0) {
            foreach ($classFilePaths as &$classFilePath) {
                $classFilePath = 'php/' . $classFilePath;
            }
        }
        self::$_classFilePaths = $classFilePaths;
        self::$_codeCoverageReportPath = $codeCoverageReportPath;
        include_once './BreakpointDebugging_PHPUnit_DisplayCodeCoverageReport.php';
    }

    /**
     * Creates code coverage report without "PHPUnit" package, then displays in browser.
     *
     * @param mixed  $testFilePaths         Relative paths of unit test files.
     * @param string $classFileRelativePath Relative path of class which see the code coverage.
     * @param string $howToTest             How to test?
     *      'SIMPLE': Does not use "PHPUnit" package. This mode can be used instead of "*.phpt" file.
     *      'SIMPLE_OWN': This package test.
     *
     * @return void
     *
     * Example top page:
     *      <?php
     *
     *      chdir(str_repeat('../', preg_match_all('`/`xX', $_SERVER['PHP_SELF'], $matches) - 2));
     *      unset($matches);
     *
     *      require_once './BreakpointDebugging_Inclusion.php';
     *
     *      B::checkExeMode(true);
     *      // Makes up code coverage report, then displays in browser.
     *      $breakpointDebugging_PHPUnit = new \BreakpointDebugging_PHPUnit();
     *      $breakpointDebugging_PHPUnit->displayCodeCoverageReportSimple('SomethingTest.php', 'Something.php'); exit;
     *      // Or, "$breakpointDebugging_PHPUnit->displayCodeCoverageReportSimple(array ('Something1Test.php', 'Something2Test.php'), 'Something.php'); exit;"
     *
     *      ?>
     */
    function displayCodeCoverageReportSimple($testFilePaths, $classFileRelativePath, $howToTest = 'SIMPLE')
    {
        xdebug_start_code_coverage(XDEBUG_CC_UNUSED | XDEBUG_CC_DEAD_CODE);

        B::assert(func_num_args() <= 3);
        B::assert(is_string($testFilePaths) || is_array($testFilePaths));
        B::assert(is_string($classFileRelativePath));
        B::assert(is_string($howToTest));

        if (!extension_loaded('xdebug')) {
            B::exitForError('"\BreakpointDebugging_PHPUnit::displayCodeCoverageReportSimple()" needs "xdebug" extention.');
        }
        B::iniCheck('xdebug.coverage_enable', '1', '');

        if (is_string($testFilePaths)) {
            $testFilePaths = array ($testFilePaths);
        }

        self::$_codeCoverageKind = $howToTest;
        $this->executeUnitTestSimple($testFilePaths, $howToTest);
        $codeCoverages = xdebug_get_code_coverage();
        xdebug_stop_code_coverage();

        $classFilePath = stream_resolve_include_path($classFileRelativePath);
        $buffer = '';
        $isDuringIgnore = false;
        $errorMessage = PHP_EOL
            . 'FILE: ' . $classFileRelativePath . PHP_EOL
            . 'LINE: ';
        foreach ($codeCoverages as $filePath => $codeCoverage) {
            if ($filePath === $classFilePath) {
                $pFile = B::fopen(array ($filePath, 'rb'));
                $lineNumber = 0;
                $coveringLineNumber = 0;
                $notCoveringNumber = 0;
                while (( $line = fgets($pFile)) !== false) {
                    $lineNumber++;
                    $lineNumberString = '<span class="lineNum">' . sprintf('%05d: ', $lineNumber) . '</span>';
                    $line = $lineNumberString . htmlspecialchars($line, ENT_QUOTES, 'UTF-8');
                    if (!array_key_exists($lineNumber, $codeCoverage)) {
                        if ($isDuringIgnore) { // Is during ignoring.
                            if (preg_match("`@codeCoverageSimpleIgnoreEnd [^_[:alnum:]]`xX", $line)) {
                                $isDuringIgnore = false;
                            } else if (preg_match("`@codeCoverageSimpleIgnoreStart [^_[:alnum:]]`xX", $line)) {
                                B::exitForError('We must not start to ignore during ignoring.' . $errorMessage . $lineNumber);
                            }
                        } else { // Is not during ignoring.
                            if (preg_match("`@codeCoverageSimpleIgnoreEnd [^_[:alnum:]]`xX", $line)) {
                                B::exitForError('We must not end to ignore during not ignoring.' . $errorMessage . $lineNumber);
                            } else if (preg_match("`@codeCoverageSimpleIgnoreStart [^_[:alnum:]]`xX", $line)) {
                                $isDuringIgnore = true;
                            }
                        }
                        $buffer .= '<span>' . $line . '</span>';
                        continue;
                    }
                    switch ($codeCoverage[$lineNumber]) {
                        case 1:
                            if ($isDuringIgnore) { // Is during ignoring.
                                B::exitForError('We must not ignore covering line.' . $errorMessage . $lineNumber);
                            } else { // Is not during ignoring.
                                $coveringLineNumber++;
                                $buffer .= '<span class="lineCov">' . $line . '</span>';
                            }
                            break;
                        case -1:
                            if ($isDuringIgnore) { // Is during ignoring.
                                $buffer .= '<span class="lineIgnoring">' . $line . '</span>';
                            } else { // Is not during ignoring.
                                $notCoveringNumber++;
                                $buffer .= '<span class="lineNoCov">' . $line . '</span>';
                            }
                            break;
                        case -2:
                            $buffer .= '<span class="lineDeadCode">' . $line . '</span>';
                            break;
                        default :
                            assert(false);
                    }
                }
                $codeLineNumber = $coveringLineNumber + $notCoveringNumber;
                $codeCoveragePercent = $coveringLineNumber * 100 / $codeLineNumber;
                $html = <<<EOD
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>DisplayCodeCoverageReportSimple</title>
        <style type="text/css">
            <!--
            body
            {
                background-color: black;
                color: white;
                font-family: arial, helvetica, sans-serif;
                font-size: 12px;
                margin: 0 auto;
                width: 100%;
            }

            p.title
            {
                text-align: center;
                padding: 10px;
                font-family: sans-serif;
                font-style: italic;
                font-weight: bold;
                font-size: 36px;
            }

            p.coverage
            {
                text-align: center;
                padding: 10px;
                font-family: sans-serif;
                font-weight: bold;
                font-size: 36px;
            }

            pre.source
            {
                font-family: monospace;
                white-space: pre;
            }

            span.lineNum
            {
                background-color: #404040;
            }

            span.lineIgnoring
            {
                background-color: navy;
                display: block;
            }

            span.lineCov
            {
                background-color: #008000;
                display: block;
            }

            span.lineNoCov
            {
                background-color: #bd0000;
                display: block;
            }

            span.lineDeadCode
            {
                background-color: gray;
                display: block;
            }
            -->
        </style>
	</head>
	<body>
        <p class="title">$classFileRelativePath</p>
        <hr />
        <p class="coverage">
            Code line number: $codeLineNumber<br />
            Covering line number: $coveringLineNumber<br />
            Code coverage percent: <span style="color:aqua">$codeCoveragePercent%</span>
        </p>
        <hr />
		<pre class="source">
$buffer
        </pre>
	</body>
</html>
EOD;
                BW::virtualOpen('BreakpointDebugging_displayCodeCoverageReportSimple', $html);
                exit;
            }
        }
        B::assert(false);
    }

    /**
     * Gets "self::$_codeCoverageKind".
     *
     * @return bool Was code coverage started?
     */
    static function getCodeCoverageKind()
    {
        return self::$_codeCoverageKind;
    }

}

// Initializes static class.
\BreakpointDebugging_PHPUnit::initialize();
