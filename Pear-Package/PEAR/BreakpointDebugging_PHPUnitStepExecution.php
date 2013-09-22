<?php

/**
 * Classes for unit test.
 *
 * This file does not use except unit test. Therefore, response time is zero in release.
 * This file names put "_" to cause error when we do autoload.
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
        B::assert(func_num_args() <= 4, 1);
        B::assert(is_string($message), 2);
        B::assert(is_int($id) || $id === null, 3);
        B::assert($previous instanceof \Exception || $previous === null, 4);

        if (mb_detect_encoding($message, 'utf8', true) === false) {
            throw new \BreakpointDebugging_ErrorException('Exception message is not "UTF8".', 101);
        }

        // Adds "[[[CLASS=<class name>] FUNCTION=<function name>] ID=<identification number>]" to message in case of unit test.
        if (B::getStatic('$exeMode') & B::UNIT_TEST) {
            B::assert(is_int($omissionCallStackLevel) && $omissionCallStackLevel >= 0, 5);

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
     * @var bool Inclusion path setting flag.
     */
    private static $_inclusionPathSettingFlag = true;

    /**
     * Limits static properties accessing.
     *
     * @return void
     */
    static function initialize()
    {
        B::assert(func_num_args() === 0, 1);

        self::$exeMode = &B::refStatic('$exeMode');
        $staticProperties = &B::refStaticProperties();
        $staticProperties['$_classFilePaths'] = &self::$_classFilePaths;

        self::$_classFilePaths = 'TestA';
        B::assert(B::getStatic('$_classFilePaths') === 'TestA');

        $staticProperties['$_codeCoverageReportPath'] = &self::$_codeCoverageReportPath;
        $staticPropertyLimitings = &B::refStaticPropertyLimitings();
        $staticPropertyLimitings['$_includePaths'] = '';
        $staticPropertyLimitings['$_valuesToTrace'] = '';
        $staticPropertyLimitings['$exeMode'] = 'BreakpointDebugging/PHPUnitStepExecution/PHPUnitFrameworkTestCase.php';
        self::$_separator = PHP_EOL . '//////////////////////////////////////////////////////////////////////////' . PHP_EOL;
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
        B::assert(func_num_args() === 1, 1);
        B::assert($pException instanceof \Exception, 2);

        $callStack = debug_backtrace();
        if (!array_key_exists(1, $callStack)
            || !array_key_exists('file', $callStack[1])
            || strripos($callStack[1]['file'], 'PHPUnitFrameworkTestCase.php') === strlen($callStack[1]['file']) - strlen('PHPUnitFrameworkTestCase.php')
        ) {
            B::iniSet('xdebug.var_display_max_depth', '5', false);
            var_dump($pException);
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
        B::assert(func_num_args() === 1, 1);
        B::assert($pException instanceof \Exception, 2);

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
        B::assert(func_num_args() === 2, 1);
        B::assert(is_string($propertyName), 2);
        B::assert(is_object($objectOrClassName) || is_string($objectOrClassName), 3);

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
        B::assert(func_num_args() === 3, 1);
        B::assert(is_string($propertyName), 2);
        B::assert(is_object($objectOrClassName) || is_string($objectOrClassName), 3);

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
     */
    static function checkExeMode($isUnitTest = false)
    {
        B::assert(is_bool($isUnitTest), 1);

        if (func_num_args() === 0
            || !$isUnitTest
        ) {
            echo '<pre><b>You must not set "$_BreakpointDebugging_EXE_MODE = BreakpointDebugging_setExecutionModeFlags(\'..._UNIT_TEST\');"' . PHP_EOL
            . "\t" . ' into "' . BREAKPOINTDEBUGGING_PEAR_SETTING_DIR_NAME . 'BreakpointDebugging_MySetting.php".' . PHP_EOL
            . 'Or, you mistook start "php" page.</b></pre>';
            self::$exeMode |= B::IGNORING_BREAK_POINT;
            throw new \BreakpointDebugging_ErrorException('', 101);
        }
    }

    /**
     * Runs "phpunit" command.
     *
     * @param string $command The command character-string which excepted "phpunit".
     *
     * @return void
     */
    private static function _runPHPUnitCommand($command)
    {
        if (self::$_inclusionPathSettingFlag) {
            self::$_inclusionPathSettingFlag = false;
            // Sets component pear package inclusion paths.
            $includePath = ini_get('include_path');
            ini_set('include_path', __DIR__ . '/BreakpointDebugging/Component' . PATH_SEPARATOR . getenv('PHP_PEAR_INSTALL_DIR') . '/BreakpointDebugging/Component' . PATH_SEPARATOR . $includePath);
        }
        $command = ltrim($command);
        echo self::$_separator;
        echo "Runs <b>\"phpunit $command\"</b> command." . PHP_EOL;
        $commandElements = explode(' ', $command);
        $testFileName = array_pop($commandElements);
        array_push($commandElements, self::$unitTestDir . $testFileName);
        array_unshift($commandElements, 'dummy');
        include_once 'PHPUnit/Autoload.php';
        $pPHPUnit_TextUI_Command = new \PHPUnit_TextUI_Command();
        // Stores global variables before unit test file is included.
        \BreakpointDebugging_PHPUnitStepExecution_PHPUnitUtilGlobalState::backupGlobals(array ());
        // Checks command line switches.
        if (in_array('--process-isolation', $commandElements)) {
            throw new \BreakpointDebugging_ErrorException('You must not use "--process-isolation" command line switch because this unit test is run in other process.' . PHP_EOL . 'So, you cannot debug unit test code with IDE.', 101);
        }
        // Uses "PHPUnit" package error handler.
        restore_error_handler();
        // Runs unit test continuously.
        $pPHPUnit_TextUI_Command->run($commandElements, false);
        // Uses "BreakpointDebugging" package error handler.
        set_error_handler('\BreakpointDebugging::handleError', -1);
    }

    /**
     * Executes unit test files continuously, and debugs with IDE.
     *
     * @param array  $unitTestFilePaths   The file paths of unit tests.
     * @param string $commandLineSwitches Command-line-switches except "--stop-on-failure --static-backup".
     *
     * @return void
     *
     * Please, follow rule, then, we can use unit test's "--static-backup" command line switch for execution with IDE. Also, those rule violation is detected.
     * The rule 1: Code which is tested must use private static property instead of use local static variable of static class method
     *      because "php" version 5.3.0 cannot restore its value.
     * The rule 2: Unit test file (*Test.php) must use public static property instead of use global variable
     *      because "php" version 5.3.0 cannot detect global variable definition except unit test file realtime.
     * The rule 3: Unit test file (*Test.php) must use autoload by "new" instead of include "*.php" file which defines static status
     *      because "php" version 5.3.0 cannot detect an included static status definition realtime.
     * The rule 4: Code which is tested must not register autoload function by "spl_autoload_register()" at top of stack
     *      because its case cannot store static status.
     *      Example: spl_autoload_register('\SomethingClassName::autoloadFunctionName', true, true);
     *      Instead, unit test file (*Test.php) can include its file at "setUpBeforeClass()" if global variable is not defined and "spl_autoload_register()" executes at include.
     * Also, we should not use global variable to avoid variable crash in all "php" code.
     *
     * Also, we must not use unit test's "--process-isolation" command line switch because its tests is run in other process.
     * Therefore, we cannot debug unit test code with IDE.
     *
     * Caution: Don't test an unit when practical use server has been running with synchronization file because synchronization is destroyed.
     *
     * How to run multiprocess unit test:
     *      Procedure 1: Use "popen()" inside your unit test class method "test...()".
     *      Procedure 2: Judge by using "parent::assertTrue(<conditional expression>)".
     *
     * @Example of multiprocess unit test file.
     *  <?php
     *
     *  use \BreakpointDebugging as B;
     *  use \BreakpointDebugging_PHPUnitStepExecution as BU;
     *
     *  class BreakpointDebugging_LockTest extends \BreakpointDebugging_PHPUnitStepExecution_PHPUnitFrameworkTestCase
     *  {
     *      /**
     *       * @covers \BreakpointDebugging_Lock<extended>
     *       * /
     *      function testMultiprocess()
     *      {
     *          $pHandles = array ();
     *          for ($count = 0; $count < 8; $count++) {
     *              // Creates and runs a test process.
     *              $pHandles[] = popen('php ./tests/PEAR/BreakpointDebugging/MultiprocessTest/Lock.php "param1" "param2"', 'r');
     *          }
     *
     *          $results = array ();
     *          foreach ($pHandles as $pHandle) {
     *              while (!feof($pHandle)) {
     *                  // Gets a result.
     *                  $results[] = fgets($pHandle);
     *              }
     *          }
     *
     *          foreach ($pHandles as $pHandle) {
     *              // Deletes a test process.
     *              pclose($pHandle);
     *          }
     *
     *          // Asserts the results.
     *          if (max($results) !== '1000') {
     *              // Displays error.
     *              foreach ($results as $result) {
     *                  echo $result;
     *              }
     *              // Displays error call stack information, then stops at breakpoint, then exits.
     *              parent::fail();
     *          }
     *      }
     *  }
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
     *  class UnstoringTest
     *  {
     *      // We can define static property in "*Test.php" because static property is not stored in "*Test.php".
     *      static $staticProperty = null;
     *  }
     *
     *  // $somethingGlobal = ''; // We must not add global variable here. (Autodetects)
     *  //
     *  // unset($_FILES); // We must not delete global variable here. (Autodetects)
     *  //
     *  // include_once __DIR__ . '/AFileWhichHasGlobalVariable.php'; // We must not include a file which has global variable here. (Autodetects)
     *  class ExampleTest extends \BreakpointDebugging_PHPUnitStepExecution_PHPUnitFrameworkTestCase
     *  {
     *      private $_pSomething;
     *      private static $_pStaticSomething;
     *
     *      static function setUpBeforeClass()
     *      {
     *          // global $somethingGlobal;
     *          // $somethingGlobal = ''; // We must not add global variable here. (Autodetects)
     *          //
     *          // unset($_FILES); // We must not delete global variable here. (Autodetects)
     *          //
     *          // include_once __DIR__ . '/AFileWhichHasGlobalVariable.php'; // We must not include a file which has global variable here. (Autodetects)
     *          //
     *          // We must not construct test instance here. (Cannot autodetect)
     *          // Because we want to initialize class auto attribute (auto class method's local static and auto property).
     *          // self::$_pStaticSomething = &BreakpointDebugging_LockByFlock::singleton();
     *
     *          $_POST = 'DUMMY_POST'; // We can change global variable here.
     *          \BreakpointDebugging::$prependErrorLog = 'DUMMY_prependErrorLog'; // We can change static property here.
     *      }
     *
     *      static function tearDownAfterClass()
     *      {
     *          parent::assertTrue($_POST === 'DUMMY_POST');
     *          parent::assertTrue(\BreakpointDebugging::$prependErrorLog === 'DUMMY_prependErrorLog');
     *      }
     *
     *      protected function setUp()
     *      {
     *          // This is required at top of "setUp()".
     *          parent::setUp();
     *
     *          // Constructs an instance per test.
     *          // We must construct test instance here
     *          // because we want to initialize class auto attribute (auto class method's local static and auto property).
     *          $this->_pSomething = &BreakpointDebugging_LockByFlock::singleton();
     *          //
     *          // global $somethingGlobal;
     *          // $somethingGlobal = ''; // We must not add global variable here. (Autodetects)
     *          //
     *          // unset($_FILES); // We must not delete global variable here. (Autodetects)
     *          //
     *          // include_once __DIR__ . '/AFileWhichHasGlobalVariable.php'; // We must not include a file which has global variable here. (Autodetects)
     *      }
     *
     *      protected function tearDown()
     *      {
     *          // global $somethingGlobal;
     *          // $somethingGlobal = ''; // We must not add global variable here. (Autodetects)
     *          //
     *          // unset($_FILES); // We must not delete global variable here. (Autodetects)
     *          //
     *          // include_once __DIR__ . '/AFileWhichHasGlobalVariable.php'; // We must not include a file which has global variable here. (Autodetects)
     *          //
     *          // Destructs the instance.
     *          $this->_pSomething = null;
     *          // This is required at bottom of "tearDown()".
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
     *          BU::markTestSkippedInDebug();
     *
     *          // global $somethingGlobal;
     *          // $somethingGlobal = ''; // We must not add global variable here. (Autodetects)
     *          //
     *          // unset($_FILES); // We must not delete global variable here. (Autodetects)
     *          //
     *          // include_once __DIR__ . '/AFileWhichHasGlobalVariable.php'; // We must not include a file which has global variable here. (Autodetects)
     *          //
     *          // Destructs the instance.
     *          $this->_pSomething = null;
     *
     *          BU::$exeMode |= B::IGNORING_BREAK_POINT; // Reference variable must specify class name because it cannot extend.
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
     *          // global $somethingGlobal;
     *          // $somethingGlobal = ''; // We must not add global variable here. (Autodetects)
     *          //
     *          // unset($_FILES); // We must not delete global variable here. (Autodetects)
     *          //
     *          // include_once __DIR__ . '/AFileWhichHasGlobalVariable.php'; // We must not include a file which has global variable here. (Autodetects)
     *          //
     *          // How to use "try-catch" syntax instead of "@expectedException" and "@expectedExceptionMessage".
     *          // This way can test an error after static status was changed.
     *          try {
     *              B::assert(true, 101);
     *              B::assert(false, 102);
     *          } catch (\BreakpointDebugging_ErrorException $e) {
     *              $this->assertTrue(preg_match('`CLASS=ExampleTest FUNCTION=testSomething_B ID=102\.$`X', $e->getMessage()) === 1);
     *              return;
     *          }
     *          $this->fail();
     *      }
     *
     *  }
     *
     *  ?>
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
            if (in_array($unitTestFilePath, self::$_unitTestFilePathsStorage, true)) {
                throw new \BreakpointDebugging_ErrorException('Unit test file path must be unique.', 101);
            }
            self::$_unitTestFilePathsStorage[] = $unitTestFilePath;
        }

        echo file_get_contents('BreakpointDebugging/css/FontStyle.html', true);
        echo '<pre>';

        if (self::$exeMode & B::RELEASE) {
            echo '<b>\'RELEASE_UNIT_TEST\' execution mode.</b>' . PHP_EOL;
        } else {
            echo '<b>\'DEBUG_UNIT_TEST\' execution mode.</b>' . PHP_EOL;
        }

        B::assert(func_num_args() <= 2, 1);
        B::assert(is_array($unitTestFilePaths), 2);
        B::assert(!empty($unitTestFilePaths), 3);
        B::assert(is_string($commandLineSwitches), 4);

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
        }
        /*         * ***
          }
         * *** */
        echo self::$_separator;
        echo '<b>Unit tests have done.</b></pre>';
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

        B::assert(func_num_args() === 2, 1);
        B::assert(is_string($unitTestFilePath), 2);
        B::assert(is_string($classFilePaths) || is_array($classFilePaths), 3);

        echo file_get_contents('BreakpointDebugging/css/FontStyle.html', true);

        if (!extension_loaded('xdebug')) {
            B::exitForError('"BreakpointDebugging::displayCodeCoverageReport()" needs "xdebug" extention.');
        }
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

        self::_getUnitTestDir();
        // Creates code coverage report.
        $displayErrorsStorage = ini_get('display_errors');
        ini_set('display_errors', '');
        echo '<pre>';
        if (self::$exeMode & B::RELEASE) {
            echo '<b>\'RELEASE_UNIT_TEST\' execution mode.</b>' . PHP_EOL;
        } else {
            echo '<b>\'DEBUG_UNIT_TEST\' execution mode.</b>' . PHP_EOL;
        }
        self::_runPHPUnitCommand($commandLineSwitches . ' --static-backup --coverage-html ' . $codeCoverageReportPath . ' ' . $unitTestFilePath);
        echo '</pre>';
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
        echo '</body></html>';
        exit;
    }

}

// Initializes static class.
\BreakpointDebugging_PHPUnitStepExecution::initialize();

?>
