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
use \BreakpointDebugging_PHPUnit_StaticVariableStorage as BSS;
use \BreakpointDebugging_PHPUnit_FrameworkTestCase as BTC;
use \BreakpointDebugging_PHPUnit_FrameworkTestCaseSimple as BTCS;

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
                    $class = ' CLASS=' . $call['class'];
                }
            }
            $message .= $class . $function . $idString;
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
            B::windowVirtualOpen(B::ERROR_WINDOW_NAME, ob_get_clean());
            B::windowFront(B::ERROR_WINDOW_NAME);
            exit;
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
        $unitTestCurrentDir = debug_backtrace();
        if ($this->_phpUnitUse) {
            $unitTestCurrentDir = dirname($unitTestCurrentDir[1]['file']) . DIRECTORY_SEPARATOR;
        } else {
            $unitTestCurrentDir = dirname($unitTestCurrentDir[2]['file']) . DIRECTORY_SEPARATOR;
        }
        self::$unitTestDir = $unitTestCurrentDir;
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
        echo "Runs <b>\"phpunit $command\"</b> command." . PHP_EOL;
        include_once 'PHPUnit/Autoload.php';
        $pPHPUnit_TextUI_Command = new \PHPUnit_TextUI_Command();
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
            BSS::storeProperties($staticProperties, array ());
            // Registers autoload class method to check definition, deletion and change violation of global variables in bootstrap file, unit test file (*Test.php, *TestSimple.php), "setUpBeforeClass()" and "setUp()".
            // And, to check the change violation of static properties in bootstrap file, unit test file (*Test.php, *TestSimple.php), "setUpBeforeClass()" and "setUp()".
            // And, to store initial value of global variables and static properties.
            $result = spl_autoload_register('\BreakpointDebugging_PHPUnit_StaticVariableStorage::loadClass', true, true);
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
        $pPHPUnit_TextUI_Command->run($commandElements, false);
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
            BSS::storeProperties($staticProperties, array ());
            // Registers autoload class method to check definition, deletion and change violation of global variables in bootstrap file, unit test file (*Test.php, *TestSimple.php), "setUpBeforeClass()" and "setUp()".
            // And, to check the change violation of static properties in bootstrap file, unit test file (*Test.php, *TestSimple.php), "setUpBeforeClass()" and "setUp()".
            // And, to store initial value of global variables and static properties.
            $result = spl_autoload_register('\BreakpointDebugging_PHPUnit_StaticVariableStorage::loadClass', true, true);
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
        B::windowHtmlAddition($this->_unitTestWindowName, 'pre', 0, $buffer);
        B::windowScrollBy($this->_unitTestWindowName, $dy);
        B::windowScriptClearance();
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
     * Gets property for test.
     *
     * @param mixed  $objectOrClassName A object or class name.
     * @param string $propertyName      Property name or constant name.
     *
     * @return mixed Property value.
     *
     * Example: $propertyValue = \BreakpointDebugging::getPropertyForTest('ClassName', 'CONST_NAME');
     *          $propertyValue = \BreakpointDebugging::getPropertyForTest('ClassName', '$_privateStaticName');
     *          $propertyValue = \BreakpointDebugging::getPropertyForTest($object, '$_privateStaticName');
     *          $propertyValue = \BreakpointDebugging::getPropertyForTest($object, '$_privateAutoName');
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
     * Example: \BreakpointDebugging::setPropertyForTest('ClassName', '$_privateStaticName', $value);
     *          \BreakpointDebugging::setPropertyForTest($object, '$_privateStaticName', $value);
     *          \BreakpointDebugging::setPropertyForTest($object, '$_privateAutoName', $value);
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
            B::windowVirtualOpen(B::ERROR_WINDOW_NAME, B::getErrorHtmlFileTemplate());
            $errorMessage = <<<EOD
You must set
    "BREAKPOINTDEBUGGING_MODE=DEBUG" or
    "BREAKPOINTDEBUGGING_MODE=RELEASE"
to this project execution parameter.
EOD;
            B::windowHtmlAddition(B::ERROR_WINDOW_NAME, 'pre', 0, '<b>' . $errorMessage . '</b>');
            exit;
        }
    }

    /**
     * Prepares unit test.
     */
    private function _prepareUnitTest($phpUnitUse = true)
    {
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
        // Does it use "PHPUnit" package?
        $this->_phpUnitUse = $phpUnitUse;
        // Sets this instance to unit test class.
        if ($phpUnitUse) {
            BTC::setPHPUnit($this);
            $this->_unitTestWindowName = 'BreakpointDebugging_PHPUnit';
        } else {
            BTCS::setPHPUnit($this);
            $this->_unitTestWindowName = 'BreakpointDebugging_PHPUnitSimple';
        }
    }

    /**
     * Executes unit test files continuously, and debugs with IDE.
     *
     * @param array  $testFilePaths       The file paths of unit tests.
     * @param string $commandLineSwitches Command-line-switches except "--stop-on-failure --static-backup".
     * @param bool   $phpUnitUse          Does it use "PHPUnit" package?
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
     *      $breakpointDebugging_PHPUnit->executeUnitTest($unitTestFilePaths); exit;
     *
     *      ?>
     *
     * @codeCoverageIgnore
     * Because "phpunit" command cannot run during "phpunit" command running.
     */
    function executeUnitTest($testFilePaths, $commandLineSwitches = '', $phpUnitUse = true)
    {
        B::assert(func_num_args() <= 3);
        B::assert(is_array($testFilePaths));
        B::assert(!empty($testFilePaths));
        B::assert(is_string($commandLineSwitches));

        if (!B::checkDevelopmentSecurity()) {
            exit;
        }

        $this->_prepareUnitTest($phpUnitUse);

        foreach ($testFilePaths as $testFilePath) {
            if (!$phpUnitUse //
                && substr($testFilePath, 0 - strlen('TestSimple.php')) !== 'TestSimple.php' //
            ) {
                throw new \BreakpointDebugging_ErrorException('Simple unit test file name must be "*TestSimple.php".', 101);
            }
            if (array_key_exists($testFilePath, self::$_unitTestFilePathsStorage)) {
                throw new \BreakpointDebugging_ErrorException('Unit test file path must be unique.', 101);
            }
            self::$_unitTestFilePathsStorage[$testFilePath] = true;
        }

        B::windowVirtualOpen($this->_unitTestWindowName, $this->getHtmlFileContent());
        ob_start();

        if (self::$exeMode & B::RELEASE) {
            echo '<b>\'RELEASE_UNIT_TEST\' execution mode.</b>' . PHP_EOL;
        } else {
            echo '<b>\'DEBUG_UNIT_TEST\' execution mode.</b>' . PHP_EOL;
        }

        $this->_getUnitTestDir();
        foreach ($testFilePaths as $testFilePath) {
            // If unit test file does not exist.
            if (!is_file(self::$unitTestDir . $testFilePath)) {
                throw new \BreakpointDebugging_ErrorException('Unit test file "' . $testFilePath . '" does not exist.', 102);
            }
            // If test file path contains '_'.
            if (strpos($testFilePath, '_') !== false) {
                echo "You have to change from '_' of '$testFilePath' to '-' because you cannot run unit tests." . PHP_EOL;
                if (function_exists('xdebug_break') //
                    && !(self::$exeMode & B::IGNORING_BREAK_POINT) //
                ) {
                    xdebug_break();
                }
                continue;
            }
            if ($phpUnitUse) {
                $this->_runPHPUnitCommand($commandLineSwitches . ' --stop-on-failure --static-backup ' . $testFilePath);
            } else {
                $this->_runPHPUnitCommandSimple($testFilePath);
            }
            gc_collect_cycles();
        }
        $this->displayProgress();
        echo self::$_separator;
        BSS::checkFunctionLocalStaticVariable();
        BSS::checkMethodLocalStaticVariable();

        switch ($this->_unitTestResult) {
            case 'DONE':
                echo '<b>Unit tests have done.</b>';
                break;
            case 'INCOMPLETE':
                echo '<strong>Unit tests have ended incompletely.</strong>';
                break;
            default:
                B::assert(false);
        }

        B::windowHtmlAddition($this->_unitTestWindowName, 'pre', 0, ob_get_clean());
        B::windowFront($this->_unitTestWindowName);
        B::windowScrollBy($this->_unitTestWindowName, PHP_INT_MAX, PHP_INT_MAX);
        B::windowScriptClearance();
    }

    /**
     * Executes unit test files continuously without "PHPUnit" package, and debugs with IDE.
     *
     * @param array  $testFilePaths       The file paths of unit tests.
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
    function executeUnitTestSimple($testFilePaths)
    {
        $this->executeUnitTest($testFilePaths, '', false);
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

        B::windowVirtualOpen($this->_unitTestWindowName, $this->getHtmlFileContent());
        ob_start();

        if (self::$exeMode & B::RELEASE) {
            echo '<b>\'RELEASE_UNIT_TEST\' execution mode.</b>' . PHP_EOL;
        } else {
            echo '<b>\'DEBUG_UNIT_TEST\' execution mode.</b>' . PHP_EOL;
        }

        $this->_runPHPUnitCommand($commandLineSwitches . ' --static-backup --coverage-html ' . $codeCoverageReportPath . ' ' . $testFilePath);

        B::windowHtmlAddition($this->_unitTestWindowName, 'pre', 0, ob_get_clean());

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
     * @param mixed $testFilePaths Relative paths of unit test files.
     * @param type $classFilePath  It is relative path of class which see the code coverage, and its current directory must be project directory.
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
    function displayCodeCoverageReportSimple($testFilePaths, $classFilePath)
    {
        B::assert(func_num_args() === 2);
        B::assert(is_string($testFilePaths) || is_array($testFilePaths));
        B::assert(is_string($classFilePath));

        if (!extension_loaded('xdebug')) {
            B::exitForError('"\BreakpointDebugging_PHPUnit::displayCodeCoverageReportSimple()" needs "xdebug" extention.');
        }
        B::iniSet('xdebug.coverage_enable', 1);
        // B::iniCheck('xdebug.coverage_enable', 1, '');

        if (is_string($testFilePaths)) {
            $testFilePaths = array ($testFilePaths);
        }

        xdebug_start_code_coverage(XDEBUG_CC_UNUSED | XDEBUG_CC_DEAD_CODE);
        $this->executeUnitTest($testFilePaths, '', false);
        $codeCoverages = xdebug_get_code_coverage();
        xdebug_stop_code_coverage();

        ob_start();
        var_dump($classFilePath, $codeCoverages); // For debug.
        B::windowVirtualOpen('DisplayCodeCoverageReportSimple', ob_get_clean());
    }

}

// Initializes static class.
\BreakpointDebugging_PHPUnit::initialize();
