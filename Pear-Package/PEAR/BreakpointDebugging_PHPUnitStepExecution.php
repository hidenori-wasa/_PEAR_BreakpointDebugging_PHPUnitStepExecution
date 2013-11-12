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
 *      @Example: \BreakpointDebugging::registerNotFixedLocation(self::$_isRegister[__METHOD__]);
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
 * Procedure 1: Make page like "@Example page which runs unit test files (*Test.php)" and pages like "@Example page of unit test file (*Test.php)".
 * Procedure 2: Run page like "example page which runs unit tests" with IDE.
 * Option Procedure: Copy from "PEAR/BreakpointDebugging/" directory and "PEAR/BreakpointDebugging_*.php" files to the project directory of remote server if you want remote unit test.
 *
 * @Example page which runs unit test files (*Test.php).
 *  <?php
 *
 *  chdir(str_repeat('../', preg_match_all('`/`xX', $_SERVER['PHP_SELF'], $matches) - 2));
 *  require_once './BreakpointDebugging_Inclusion.php';
 *
 *  use \BreakpointDebugging as B;
 *
 *  B::checkExeMode(true);
 *
 *  // Please, choose unit tests files by customizing.
 *  $unitTestFilePaths = array (
 *      'SomethingTest.php',
 *      'Something/SubTest.php',
 *  );
 *
 *  // Executes unit tests.
 *  B::executeUnitTest($unitTestFilePaths); exit;
 *
 *  ?>
 *
 * @Example page of unit test file (*Test.php).
 *  <?php
 *
 *  use \BreakpointDebugging as B;
 *  use \BreakpointDebugging_PHPUnitStepExecution as BU;
 *
 *  function localStaticVariable()
 *  {
 *      // static $localStatic = 'Local static value.'; // We must not define local static variable of function. (Autodetects)
 *  }
 *
 *  class LocalStaticVariableOfStaticMethod
 *  {
 *      static $staticProperty = 'Initial value.'; // We can define static property here.
 *
 *      static function localStaticVariable()
 *      {
 *          // static $localStatic = 'Local static value.'; // We must not define local static variable of static class method. (Autodetects)
 *      }
 *
 *      function localStaticVariableOfInstance()
 *      {
 *          static $localStatic = 'Local static value.'; // We can define local static variable of auto class method.
 *      }
 *
 *  }
 *
 *  // global $something;
 *  // $something = 'Defines global variable.'; // We must not define global variable here. (Autodetects)
 *  //
 *  // $_FILES = 'Changes the value.'; // We must not change global variable and property here. (Autodetects)
 *  //
 *  // $_FILES = &$bugReference; // We must not overwrite global variable and property with reference here. (Autodetects)
 *  // unset($bugReference);
 *  //
 *  // unset($_FILES); // We must not delete global variable here. (Autodetects)
 *  //
 *  // spl_autoload_register('\ExampleTest::autoload', true, true); // We must not register autoload function at top of stack by "spl_autoload_register()". (Autodetects)
 *  //
 *  // include_once __DIR__ . '/AFile.php'; // We must not include a file because autoload is only once per file. (Autodetects)
 *  class ExampleTest extends \BreakpointDebugging_PHPUnitStepExecution_PHPUnitFrameworkTestCase
 *  {
 *      private $_pTestObject;
 *
 *      static function autoload($className)
 *      {
 *
 *      }
 *
 *      static function setUpBeforeClass()
 *      {
 *          // global $something;
 *          // $something = 'Defines global variable.'; // We must not define global variable here. (Autodetects)
 *          //
 *          // $_FILES = 'Changes the value.'; // We must not change global variable and property here. (Autodetects)
 *          //
 *          // $_FILES = &$bugReference; // We must not overwrite global variable and property with reference here. (Autodetects)
 *          //
 *          // unset($_FILES); // We must not delete global variable here. (Autodetects)
 *          //
 *          // spl_autoload_register('\ExampleTest::autoload', true, true); // We must not register autoload function at top of stack by "spl_autoload_register()". (Autodetects)
 *          //
 *          // include_once __DIR__ . '/AFile.php'; // We must not include a file because autoload is only once per file. (Autodetects)
 *      }
 *
 *      static function tearDownAfterClass()
 *      {
 *
 *      }
 *
 *      protected function setUp()
 *      {
 *          // This is required at top.
 *          parent::setUp();
 *
 *          // We must construct the test instance here.
 *          $this->_pTestObject = &BreakpointDebugging_LockByFlock::singleton();
 *
 *          global $something;
 *          $something = 'Defines global variable 2.'; // We can define global variable here.
 *
 *          $_FILES = 'Changes the value 2.'; // We can change global variable and property here.
 *
 *          $_FILES = &$aReference2; // We can overwrite global variable except property with reference here.
 *
 *          unset($_FILES); // We can delete global variable here.
 *          //
 *          // spl_autoload_register('\ExampleTest::autoload', true, true); // We must not register autoload function at top of stack by "spl_autoload_register()". (Autodetects)
 *          //
 *          // include_once __DIR__ . '/AFile.php'; // We must not include a file because autoload is only once per file. (Cannot detect!)
 *      }
 *
 *      protected function tearDown()
 *      {
 *          // spl_autoload_register('\ExampleTest::autoload', true, true); // We must not register autoload function at top of stack by "spl_autoload_register()". (Autodetects)
 *          //
 *          // Destructs the test instance to reduce memory use.
 *          $this->_pTestObject = null;
 *
 *          // This is required at bottom.
 *          parent::tearDown();
 *      }
 *
 *      function isCalled()
 *      {
 *          throw new \BreakpointDebugging_ErrorException('Something message.', 101); // This is reflected in "@expectedException" and "@expectedExceptionMessage".
 *      }
 *
 *      /**
 *       * @covers \Example<extended>
 *       *
 *       * @expectedException        \BreakpointDebugging_ErrorException
 *       * @expectedExceptionMessage CLASS=ExampleTest FUNCTION=isCalled ID=101.
 *       * /
 *      public function testSomething_A()
 *      {
 *          global $something;
 *          $something = 'Defines global variable 3.'; // We can define global variable here.
 *
 *          $_FILES = 'Changes the value 3.'; // We can change global variable and property here.
 *
 *          $_FILES = &$aReference3; // We can overwrite global variable except property with reference here.
 *
 *          unset($_FILES); // We can delete global variable here.
 *          //
 *          // spl_autoload_register('\ExampleTest::autoload', true, true); // We must not register autoload function at top of stack by "spl_autoload_register()". (Autodetects)
 *          //
 *          // include_once __DIR__ . '/AFile.php'; // We must not include a file because autoload is only once per file. (Cannot detect!)
 *
 *          BU::markTestSkippedInDebug();
 *
 *          // Destructs the instance.
 *          $this->_pTestObject = null;
 *
 *          BU::$exeMode |= B::IGNORING_BREAK_POINT;
 *          $this->isCalled();
 *      }
 *
 *      /**
 *       * @covers \Example<extended>
 *       * /
 *      public function testSomething_B()
 *      {
 *          BU::markTestSkippedInRelease();
 *
 *          // How to use "try-catch" syntax instead of "@expectedException" and "@expectedExceptionMessage".
 *          // This way can test an error after static status was changed.
 *          try {
 *              B::assert(true, 101);
 *              B::assert(false, 102);
 *          } catch (\BreakpointDebugging_ErrorException $e) {
 *              parent::assertTrue(preg_match('`CLASS=ExampleTest FUNCTION=testSomething_B ID=102\.$`X', $e->getMessage()) === 1);
 *              return;
 *          }
 *          $this->fail();
 *      }
 *
 *  }
 *
 *  ?>
 *
 * ### Coding rule. ###
 * Please, follow rule, then, we can use unit test's "--static-backup" command line switch for execution with IDE.
 *
 * The rule 1: We must overwrite "null" to variable if we call "__destruct()" on the way in all code.
 *      Because server calls "__destruct()" even though reference storage exists.
 *      @Example: $this->_pTestObject = null;
 * The rule 2: We must construct test instance inside "setUp()".
 *      Because we must initialize value and reference of auto properties (auto class method's local static variable and auto property).
 *      @Example:
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
 *      @Example of rule violation:
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
 *      @Example of rule violation:
 *          @codeCoverageIgnore A sentence.
 *      Instead:
 *          @codeCoverageIgnore
 *          A sentence.
 *      Please, search the rule violation of file by the following regular expression.
 *          @codeCoverageIgnore[^SE\r\n][\t\x20]*[^\t\x20].*$
 *
 * Autodetecting rule 1: Follow autoload rule of PEAR in all codes
 *      because this package uses special autoload class method.
 *      @Example of class:
 *          namespace YourName;
 *          class Example_Class {
 *      @Example of file name of class above:
 *          ProjectDir/YourName/Example/Class.php
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
 *      @Example: spl_autoload_register('\SomethingClassName::autoloadFunctionName', true, true);
 * Autodetecting rule 7: We must not use unit test's "--process-isolation" command line switch because its tests is run in other process.
 *      Because we cannot debug unit test code with IDE.
 *
 * Recommendation rule 1: We should destruct a test instance per test in "tearDown()" because it cuts down on actual server memory use.
 *      @Example:
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
 * Caution: Don't test an unit when practical use server has been running with synchronization file because synchronization is destroyed.
 *
 * How to run multiprocess unit test:
 *      Procedure 1: Use "popen()" inside your unit test class method "test...()".
 *      Procedure 2: Judge by using "parent::assertTrue(<conditional expression>)".
 *      @See "\tests_PEAR_BreakpointDebugging_MultiprocessTest_Main::test()".
 *
 * PHP version 5.3
 *
 * LICENSE OVERVIEW:
 * 1. Do not change license text.
 * 2. Copyrighters do not take responsibility for this file code.
 *
 * LICENSE:
 * Copyright (c) 2013, Hidenori Wasa
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
 * @package  BreakpointDebugging_PHPUnitStepExecution
 * @author   Hidenori Wasa <public@hidenori-wasa.com>
 * @license  http://www.opensource.org/licenses/bsd-license.php  BSD 2-Clause
 * @link     http://pear.php.net/package/BreakpointDebugging
 */
// File to have "use" keyword does not inherit scope into a file including itself,
// also it does not inherit scope into a file including,
// and moreover "use" keyword alias has priority over class definition,
// therefore "use" keyword alias does not be affected by other files.
use \BreakpointDebugging as B;
use \BreakpointDebugging_PHPUnitStepExecution_PHPUnitUtilGlobalState as BGS;
use \BreakpointDebugging_PHPUnitStepExecution_PHPUnitFrameworkTestCase as BSF;

B::limitAccess(array ('BreakpointDebugging.php'));
/**
 * Own package exception. For unit test.
 *
 * @category PHP
 * @package  BreakpointDebugging_PHPUnitStepExecution
 * @author   Hidenori Wasa <public@hidenori-wasa.com>
 * @license  http://www.opensource.org/licenses/bsd-license.php  BSD 2-Clause
 * @version  Release: @package_version@
 * @link     http://pear.php.net/package/BreakpointDebugging
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
 * @package  BreakpointDebugging_PHPUnitStepExecution
 * @author   Hidenori Wasa <public@hidenori-wasa.com>
 * @license  http://www.opensource.org/licenses/bsd-license.php  BSD 2-Clause
 * @version  Release: @package_version@
 * @link     http://pear.php.net/package/BreakpointDebugging
 */
class BreakpointDebugging_PHPUnitStepExecution
{
    /**
     * @const string Unit test window name.
     */
    const UNIT_TEST_WINDOW_NAME = 'BreakpointDebugging_PHPUnit';

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
    protected static $unitTestDir;

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
     * @var bool Flag of once per test file.
     */
    private static $_onceFlagPerTestFile = true;

    /**
     * @var string HTML file content.
     */
    private static $_htmlFileContent;

    /**
     * Limits static properties accessing of class.
     *
     * @return void
     */
    static function initialize()
    {
        B::assert(func_num_args() === 0);

        self::$exeMode = &B::refStatic('$exeMode'); // This is not rule violation because this property is not stored.
        $staticProperties = &B::refStaticProperties();
        $staticProperties['$_classFilePaths'] = &self::$_classFilePaths;
        $staticProperties['$_codeCoverageReportPath'] = &self::$_codeCoverageReportPath;
        $staticPropertyLimitings = &B::refStaticPropertyLimitings();
        $staticPropertyLimitings['$_includePaths'] = '';
        $staticPropertyLimitings['$_valuesToTrace'] = '';
        $staticPropertyLimitings['$exeMode'] = 'BreakpointDebugging/PHPUnitStepExecution/PHPUnitFrameworkTestCase.php';
        self::$_separator = PHP_EOL . '//////////////////////////////////////////////////////////////////////////' . PHP_EOL;
        self::$_htmlFileContent = <<<EOD
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>PHPUnit</title>
    </head>
    <body style="background-color: black; color: white; font-size: 1.5em">
        <pre></pre>
    </body>
</html>
EOD;
    }

    /**
     * Displays exception if release unit test error of "local or remote".
     *
     * @param object $pException Exception information.
     *
     * @return void
     * @codeCoverageIgnore
     * Because unit test is exited.
     */
    static function displaysException($pException)
    {
        B::assert(func_num_args() === 1);
        B::assert($pException instanceof \Exception);

        $callStack = debug_backtrace();
        if (!array_key_exists(1, $callStack)
            || !array_key_exists('file', $callStack[1])
            || strripos($callStack[1]['file'], 'PHPUnitFrameworkTestCase.php') === strlen($callStack[1]['file']) - strlen('PHPUnitFrameworkTestCase.php')
        ) {
            B::iniSet('xdebug.var_display_max_depth', '5', false);
            ob_start();
            var_dump($pException);

            B::windowHtmlAddition(BU::UNIT_TEST_WINDOW_NAME, 'pre', 0, ob_get_clean());
            B::exitForError();
        }
    }

    /**
     * Handles unit test exception.
     *
     * @param object $pException Exception information.
     *
     * @return void
     */
    static function handleUnitTestException($pException)
    {
        B::assert(func_num_args() === 1);
        B::assert($pException instanceof \Exception);

        $callStack = $pException->getTrace();
        $call = array_key_exists(0, $callStack) ? $callStack[0] : array ();
        // In case of direct call from "BreakpointDebugging_InAllCase::callExceptionHandlerDirectly()".
        // This call is in case of debug mode.
        if ((array_key_exists('class', $call) && $call['class'] === 'BreakpointDebugging_InAllCase')
            && (array_key_exists('function', $call) && $call['function'] === 'callExceptionHandlerDirectly')
        ) {
            throw $pException;
            // @codeCoverageIgnoreStart
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * Gets unit test directory.
     *
     * @return void
     */
    private static function _getUnitTestDir()
    {
        $unitTestCurrentDir = debug_backtrace();
        $unitTestCurrentDir = dirname($unitTestCurrentDir[1]['file']) . DIRECTORY_SEPARATOR;
        if (BREAKPOINTDEBUGGING_IS_WINDOWS) { // In case of Windows.
            self::$unitTestDir = strtolower($unitTestCurrentDir);
        } else { // In case of Unix.
            self::$unitTestDir = $unitTestCurrentDir;
        }
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
    private static function _runPHPUnitCommand($command)
    {
        $commandElements = explode(' ', $command);
        $testFileName = array_pop($commandElements);
        array_push($commandElements, self::$unitTestDir . $testFileName);
        array_unshift($commandElements, 'dummy');
        // Checks command line switches.
        if (in_array('--process-isolation', $commandElements)) {
            throw new \BreakpointDebugging_ErrorException('You must not use "--process-isolation" command line switch because this unit test is run in other process.' . PHP_EOL . 'So, you cannot debug unit test code with IDE.', 101);
        }
        if (self::$_onceFlag) {
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
        }
        $command = ltrim($command);
        echo self::$_separator;
        echo "Runs <b>\"phpunit $command\"</b> command." . PHP_EOL;
        include_once 'PHPUnit/Autoload.php';
        $pPHPUnit_TextUI_Command = new \PHPUnit_TextUI_Command();
        self::$_onceFlagPerTestFile = true;
        if (self::$_onceFlag) {
            self::$_onceFlag = false;
            // References other class private property.
            self::$_onceFlagPerTestFile = &BSF::refOnceFlagPerTestFile(); // This is not rule violation because this property is not stored.
            // Stores global variables.
            $globalRefs = &BSF::refGlobalRefs();
            $globals = &BSF::refGlobals();
            BGS::storeGlobals($globalRefs, $globals, array ());
            // Stores static properties.
            $staticProperties = &BSF::refStaticProperties2();
            BGS::storeProperties($staticProperties, array ());
            // Registers autoload class method to check definition, deletion and change violation of global variables in bootstrap file, unit test file (*Test.php), "setUpBeforeClass()" and "setUp()".
            // And, to check the change violation of static properties in bootstrap file, unit test file (*Test.php), "setUpBeforeClass()" and "setUp()".
            // And, to store initial value of global variables and static properties.
            $result = spl_autoload_register('\BreakpointDebugging_PHPUnitStepExecution_PHPUnitFrameworkTestCase::autoload', true, true);
            B::assert($result);
        } else {
            // Restores global variables.
            $globalRefs = BSF::refGlobalRefs();
            $globals = BSF::refGlobals();
            BGS::restoreGlobals($globalRefs, $globals);
            // Restores static properties.
            $staticProperties = &BSF::refStaticProperties2();
            BGS::restoreProperties($staticProperties);
        }
        // Uses "PHPUnit" package error handler.
        restore_error_handler();
        // Runs unit test continuously.
        $pPHPUnit_TextUI_Command->run($commandElements, false);
        // Uses "BreakpointDebugging" package error handler.
        set_error_handler('\BreakpointDebugging::handleError', -1);
    }

    //////////////////////////////////////// For package user ////////////////////////////////////////
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
     * Gets property for test.
     *
     * @param mixed  $objectOrClassName A object or class name.
     * @param string $propertyName      Property name or constant name.
     *
     * @return mixed Property value.
     *
     * @example $propertyValue = \BreakpointDebugging::getPropertyForTest('ClassName', 'CONST_NAME');
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
     * @example \BreakpointDebugging::setPropertyForTest('ClassName', '$_privateStaticName', $value);
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
     * @example
     *      <?php
     *
     *      $projectDirPath = str_repeat('../', preg_match_all('`/`xX', $_SERVER['PHP_SELF'], $matches) - 2);
     *      chdir(__DIR__ . '/' . $projectDirPath);
     *      require_once './BreakpointDebugging_Inclusion.php';
     *
     *      use \BreakpointDebugging as B;
     *
     *      B::checkExeMode(true);
     *
     *      class SomethingTest extends \BreakpointDebugging_PHPUnitStepExecution_PHPUnitFrameworkTestCase
     *      {
     *          .
     *          .
     *          .
     *
     * @codeCoverageIgnore
     * Because this exits.
     */
    static function checkExeMode($isUnitTest = false)
    {
        B::assert(is_bool($isUnitTest));

        if (!$isUnitTest) {
            B::windowOpen(B::ERROR_WINDOW_NAME, B::getStatic('$errorHtmlFileContent'));
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
     * Executes unit test files continuously, and debugs with IDE.
     *
     * @param array  $unitTestFilePaths   The file paths of unit tests.
     * @param string $commandLineSwitches Command-line-switches except "--stop-on-failure --static-backup".
     *
     * @return void
     *
     * @codeCoverageIgnore
     * Because "phpunit" command cannot run during "phpunit" command running.
     */
    static function executeUnitTest($unitTestFilePaths, $commandLineSwitches = '')
    {
        if (!B::checkDevelopmentSecurity()) {
            B::exitForError();
        }

        foreach ($unitTestFilePaths as $unitTestFilePath) {
            if (array_key_exists($unitTestFilePath, self::$_unitTestFilePathsStorage)) {
                throw new \BreakpointDebugging_ErrorException('Unit test file path must be unique.', 101);
            }
            self::$_unitTestFilePathsStorage[$unitTestFilePath] = true;
        }

        B::windowOpen(self::UNIT_TEST_WINDOW_NAME, self::$_htmlFileContent);
        ob_start();

        if (self::$exeMode & B::RELEASE) {
            echo '<b>\'RELEASE_UNIT_TEST\' execution mode.</b>' . PHP_EOL;
        } else {
            echo '<b>\'DEBUG_UNIT_TEST\' execution mode.</b>' . PHP_EOL;
        }

        B::assert(func_num_args() <= 2);
        B::assert(is_array($unitTestFilePaths));
        B::assert(!empty($unitTestFilePaths));
        B::assert(is_string($commandLineSwitches));

        self::_getUnitTestDir();
        foreach ($unitTestFilePaths as $unitTestFilePath) {
            // If unit test file does not exist.
            if (!is_file(self::$unitTestDir . $unitTestFilePath)) {
                B::exitForError('Unit test file "' . $unitTestFilePath . '" does not exist.');
            }
            // If test file path contains '_'.
            if (strpos($unitTestFilePath, '_') !== false) {
                echo "You have to change from '_' of '$unitTestFilePath' to '-' because you cannot run unit tests." . PHP_EOL;
                if (function_exists('xdebug_break')
                    && !(self::$exeMode & B::IGNORING_BREAK_POINT)
                ) {
                    xdebug_break();
                }
                continue;
            }
            self::_runPHPUnitCommand($commandLineSwitches . ' --stop-on-failure --static-backup ' . $unitTestFilePath);
            gc_collect_cycles();
        }
        echo self::$_separator;
        BGS::checkFunctionLocalStaticVariable();
        BGS::checkMethodLocalStaticVariable();
        echo '<b>Unit tests have done.</b>';

        B::windowHtmlAddition(self::UNIT_TEST_WINDOW_NAME, 'pre', 0, ob_get_clean());
        exit;
    }

    /**
     * Deletes code coverage report.
     *
     * @return string Code coverage report path.
     */
    static function deleteCodeCoverageReport()
    {
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

    /**
     * Creates code coverage report, then displays in browser.
     *
     * @param string $unitTestFilePath    Relative path of unit test file.
     * @param mixed  $classFilePaths      It is relative path of class which see the code coverage, and its current directory must be project directory.
     * @param string $commandLineSwitches Command-line-switches except "--static-backup --coverage-html".
     *
     * @return void
     * @example
     *      <?php
     *
     *      $projectDirPath = str_repeat('../', preg_match_all('`/`xX', $_SERVER['PHP_SELF'], $matches) - 2);
     *      chdir(__DIR__ . '/' . $projectDirPath);
     *      require_once './BreakpointDebugging_Inclusion.php';
     *
     *      use \BreakpointDebugging as B;
     *
     *      // Makes up code coverage report, then displays in browser.
     *      B::displayCodeCoverageReport('BreakpointDebugging-InAllCaseTest.php', 'PEAR/BreakpointDebugging.php');
     *      B::displayCodeCoverageReport('BreakpointDebugging/LockByFileExistingTest.php', array ('PEAR/BreakpointDebugging/Lock.php', 'PEAR/BreakpointDebugging/LockByFileExisting.php'));
     *          .
     *          .
     *          .
     * @codeCoverageIgnore
     * Because "phpunit" command cannot run during "phpunit" command running.
     */
    static function displayCodeCoverageReport($unitTestFilePath, $classFilePaths, $commandLineSwitches = '')
    {
        if (!B::checkDevelopmentSecurity()) {
            B::exitForError();
        }

        B::assert(func_num_args() === 2);
        B::assert(is_string($unitTestFilePath));
        B::assert(is_string($classFilePaths) || is_array($classFilePaths));

        if (!extension_loaded('xdebug')) {
            B::exitForError('"BreakpointDebugging::displayCodeCoverageReport()" needs "xdebug" extention.');
        }
        // Deletes code coverage report.
        $codeCoverageReportPath = self::deleteCodeCoverageReport();
        self::_getUnitTestDir();
        // Creates code coverage report.
        $displayErrorsStorage = ini_get('display_errors');
        ini_set('display_errors', '');

        B::windowOpen(self::UNIT_TEST_WINDOW_NAME, self::$_htmlFileContent);
        ob_start();

        if (self::$exeMode & B::RELEASE) {
            echo '<b>\'RELEASE_UNIT_TEST\' execution mode.</b>' . PHP_EOL;
        } else {
            echo '<b>\'DEBUG_UNIT_TEST\' execution mode.</b>' . PHP_EOL;
        }

        self::_runPHPUnitCommand($commandLineSwitches . ' --static-backup --coverage-html ' . $codeCoverageReportPath . ' ' . $unitTestFilePath);

        B::windowHtmlAddition(self::UNIT_TEST_WINDOW_NAME, 'pre', 0, ob_get_clean());

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
        include_once './BreakpointDebugging_PHPUnitStepExecution_DisplayCodeCoverageReport.php';
    }

}

// Initializes static class.
\BreakpointDebugging_PHPUnitStepExecution::initialize();

?>
