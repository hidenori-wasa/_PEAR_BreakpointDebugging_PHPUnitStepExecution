<?php

/**
 * Debugs unit tests code continuously by IDE. With "\BreakpointDebugging::executeUnitTest()" class method. Supports "php" version 5.3.0 since then.
 *
 * This class extends "PHPUnit_Framework_TestCase".
 * Also, we can execute unit test with remote server without installing "PHPUnit".
 *
 * ### About "PHPUnit" package component. ###
 * I copied following "PHPUnit" package files into "PEAR/BreakpointDebugging/Component/" directory
 * because it avoids "PHPUnit" package version control.
 *      PEAR/PHP/CodeCoverage.php
 *      PEAR/PHP/CodeCoverage/
 *          Copyright (c) 2009-2012 Sebastian Bergmann <sb@sebastian-bergmann.de>
 *      PEAR/PHP/Invoker.php
 *      PEAR/PHP/Invoker/
 *          Copyright (c) 2011-2012 Sebastian Bergmann <sb@sebastian-bergmann.de>
 *      PEAR/PHP/Timer.php
 *      PEAR/PHP/Timer/
 *          Copyright (c) 2010-2011 Sebastian Bergmann <sb@sebastian-bergmann.de>
 *      PEAR/PHP/Token.php
 *      PEAR/PHP/Token/
 *          Copyright (c) 2009-2012 Sebastian Bergmann <sb@sebastian-bergmann.de>
 *      PEAR/PHPUnit/
 *          Copyright (c) 2001-2012 Sebastian Bergmann <sebastian@phpunit.de>
 * Then, I added following into "\PHPUnit_Framework_TestCase" class because this class needed private property.
 *      function __get($propertyName)
 *      {
 *          return $this->$propertyName;
 *      }
 *
 *      function __set($propertyName, $value)
 *      {
 *          \BreakpointDebugging::limitAccess('BreakpointDebugging/PHPUnitStepExecution/PHPUnitFrameworkTestCase.php', true);
 *          $this->$propertyName = $value;
 *      }
 *
 * PHP version 5.3
 *
 * Copyright (c) 2001-2013, Sebastian Bergmann <sebastian@phpunit.de>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Sebastian Bergmann nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   PHP
 * @package    PHPUnit
 * @subpackage Framework
 * @author     Sebastian Bergmann <sebastian@phpunit.de>
 * @copyright  2001-2013 Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       http://www.phpunit.de/
 * @since      File available since Release 2.0.0
 */
// File to have "use" keyword does not inherit scope into a file including itself,
// also it does not inherit scope into a file including,
// and moreover "use" keyword alias has priority over class definition,
// therefore "use" keyword alias does not be affected by other files.
use \BreakpointDebugging as B;
use \BreakpointDebugging_PHPUnitStepExecution_PHPUnitUtilGlobalState as BGS;

/**
 * Debugs unit tests code continuously by IDE. With "\BreakpointDebugging::executeUnitTest()" class method. Supports "php" version 5.3.0 since then.
 *
 * @category   PHP
 * @package    PHPUnit
 * @subpackage Framework
 * @author     Sebastian Bergmann <sebastian@phpunit.de>
 * @copyright  2001-2013 Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.6.11
 * @link       http://www.phpunit.de/
 * @since      Class available since Release 2.0.0
 */
abstract class BreakpointDebugging_PHPUnitStepExecution_PHPUnitFrameworkTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string The test class method name.
     */
    private static $_testMethodName;

    /**
     * @var array List to except to store global variable.
     */
    private static $_backupGlobalsBlacklist = array ();

    /**
     * @var array List to except to store static attributes.
     */
    private static $_backupStaticAttributesBlacklist = array ();

    /**
     * @var int The output buffering level.
     */
    private $_obLevel;

    /**
     * @var bool Once flag per test file.
     */
    private static $_onceFlagPerTestFile = true;

    /**
     * Stores static status inside autoload handler because static status may be changed.
     *
     * @param string $className The class name which calls class member of static.
     *                          Or, the class name which creates new instance.
     *                          Or, the class name when extends base class.
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    static function autoload($className)
    {
        static $nestLevel = 0;

        // If class file has been loaded first.
        if ($nestLevel === 0) {
            //if (self::$_onceFlagPerTestFile) {
            B::assert(self::$_onceFlagPerTestFile);
            // Checks definition deletion violation of global variables to keep reference inside "setUp()".
            BGS::checkGlobals(self::$_testMethodName);
            //}
            $nestLevel = 1;
            try {
                B::autoload($className);
            } catch (\Exception $exception) {
                $forBreakpoint = 0;
            }
            // If class file has been loaded completely including dependency files.
            $nestLevel = 0;
            //if (self::$_onceFlagPerTestFile) {
            B::assert(self::$_onceFlagPerTestFile);
            // Stores global variables before variable value is changed inside "setUp()".
            BGS::backupGlobals(self::$_backupGlobalsBlacklist);
            //}
            // Stores static attributes before variable value is changed.
            BGS::backupStaticAttributes(self::$_backupStaticAttributesBlacklist);
            if (isset($exception)) {
                throw $exception;
            }
        } else { // In case of auto load inside auto load.
            $nestLevel++;
            B::autoload($className);
            $nestLevel--;
        }
    }

    /**
     * This method is called before a test class method is executed.
     * Sets up initializing which is needed at least in unit test.
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    protected function setUp()
    {
        // Unlinks synchronization files.
        $lockFilePaths = array (
            'LockByFileExistingOfInternal.txt',
            'LockByFileExisting.txt',
            'LockByFlock.txt',
            'LockByShmop.txt',
        );
        $workDir = B::getStatic('$_workDir');
        foreach ($lockFilePaths as $lockFilePath) {
            if (is_file($lockFilePath)) {
                B::unlink(array (realpath($workDir . '/' . $lockFilePath)));
            }
        }
        // Stores the output buffering level.
        $this->_obLevel = ob_get_level();
    }

    /**
     * This method is called after a test class method is executed.
     * Cleans up environment which is needed at least in unit test.
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    protected function tearDown()
    {
        // Restores the output buffering level.
        while (ob_get_level() > $this->_obLevel) {
            ob_end_clean();
        }
    }

    /**
     * Checks an annotation.
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    private function _checkAnnotation()
    {
        $className = get_class($this);
        $methodName = $this->name;
        $errorMessage = '"' . $className . '::' . $methodName . '" unit test class method requires ';
        $methodReflection = new ReflectionMethod($className, $methodName);
        $docComment = $methodReflection->getDocComment();
        if ($docComment === false) {
            B::exitForError($errorMessage . 'document comment.');
        }

        if (preg_match('`@covers [[:blank:]]+ \\\\? [_[:alpha:]] [_[:alnum:]]* <extended> [[:space:]]+`xX', $docComment) !== 1) {
            B::exitForError(
                PHP_EOL
                . $errorMessage . '"@covers ...<extended>" annotation' . PHP_EOL
                . "\t" . 'because we may use code coverage report of base abstract class.' . PHP_EOL
                . "\t" . 'Also, a test-class method calls a base class method, then its base class method may call a test-class method.' . PHP_EOL
            );
        }
    }

    /**
     * Overrides "\PHPUnit_Framework_TestCase::runBare()" to display call stack when error occurred.
     * And, sets autoload class method to store static status by "spl_autoload_register()".
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    public function runBare()
    {
        static $onceFlagPerTestFile = true;

        // Displays the progress.
        for ($count = 0; ob_get_level() > 0; $count++) {
            ob_end_flush();
        }
        flush();
        for (; $count > 0; $count--) {
            ob_start();
        }

        $this->numAssertions = 0;

        //if ($this->backupStaticAttributes) {
        // For autoload.
        self::$_testMethodName = $this->getName();
        if ($onceFlagPerTestFile) {
            self::$_onceFlagPerTestFile = &$onceFlagPerTestFile;
            // For autoload.
            self::$_backupGlobalsBlacklist = $this->backupGlobalsBlacklist;
            self::$_backupStaticAttributesBlacklist = $this->backupStaticAttributesBlacklist;
            // Checks definition deletion violation of global variables to keep reference in the following description.
            // Therefore, when bootstrap file was included.
            // And, when unit test file was included.
            // And, inside of "setUpBeforeClass()".
            BGS::checkGlobals();
            // Registers autoload class method to check definition deletion violation of global variables inside "setUp()".
            $result = spl_autoload_register('\BreakpointDebugging_PHPUnitStepExecution_PHPUnitFrameworkTestCase::autoload', true, true);
            B::assert($result);
        }
        //}
        // Start output buffering.
        ob_start();
        $this->outputBufferingActive = TRUE;

        // Clean up stat cache.
        clearstatcache();

        try {
            // Checks an annotation.
            $this->_checkAnnotation();

            $this->setExpectedExceptionFromAnnotation();
            $this->setUp();

            if ($onceFlagPerTestFile) {
                $onceFlagPerTestFile = false;
                // Checks the autoload functions.
                $autoloadFunctions = spl_autoload_functions();
                if ($autoloadFunctions[0] !== array ('BreakpointDebugging_PHPUnitStepExecution_PHPUnitFrameworkTestCase', 'autoload')) {
                    if (is_array($autoloadFunctions[0])) {
                        $autoloadFunctions[0] = $autoloadFunctions[0][0] . '::' . $autoloadFunctions[0][1];
                    }
                    $className = get_class($this);
                    $methodName = $this->name;
                    throw new \BreakpointDebugging_ErrorException(
                        'You must not register autoload function "' . $autoloadFunctions[0] . '" by "spl_autoload_register()"' . PHP_EOL
                        . 'at top of stack in "' . $className . '::' . $methodName . '"' . PHP_EOL
                        . 'because it cannot store static status.'
                    );
                }
                // Unregisters autoload class method to check definition deletion violation of global variables inside "setUp()".
                $result = spl_autoload_unregister('\BreakpointDebugging_PHPUnitStepExecution_PHPUnitFrameworkTestCase::autoload');
                B::assert($result);
                // Checks definition deletion violation of global variables to keep reference inside "setUp()".
                BGS::checkGlobals(self::$_testMethodName);
                // Resets global variables storage to change value.
                BGS::resetGlobals();
                // Stores definition and value change of global variables inside "setUp()" by autoload.
                BGS::backupGlobals(self::$_backupGlobalsBlacklist);
                // Snapshots static class attributes to restore.
                BGS::snapshotStaticAttributes(self::$_backupStaticAttributesBlacklist);
            } else {
                // Restores "$GLOBALS" if those have been stored.
                BGS::restoreGlobals($this->backupGlobalsBlacklist);
                // Restores static attributes snapshot if those have been stored.
                // Executes behind "BGS::restoreGlobals()" function to restore a reference to global variable.
                BGS::restoreStaticAttributes();
            }

            $this->checkRequirements();
            $this->assertPreConditions();
            $this->testResult = $this->runTest();
            $this->verifyMockObjects();
            $this->assertPostConditions();
            $this->status = PHPUnit_Runner_BaseTestRunner::STATUS_PASSED;
        } catch (PHPUnit_Framework_IncompleteTest $e) {
            $this->status = PHPUnit_Runner_BaseTestRunner::STATUS_INCOMPLETE;
            $this->statusMessage = $e->getMessage();
        } catch (PHPUnit_Framework_SkippedTest $e) {
            $this->status = PHPUnit_Runner_BaseTestRunner::STATUS_SKIPPED;
            $this->statusMessage = $e->getMessage();
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            B::exitForError($e); // Displays error call stack information.
        } catch (Exception $e) {
            B::exitForError($e); // Displays error call stack information.
        }

        // Tear down the fixture. An exception raised in tearDown() will be
        // caught and passed on when no exception was raised before.
        try {
            $this->tearDown();
        } catch (Exception $_e) {
            B::exitForError($_e); // Displays error call stack information.
        }

        // Stop output buffering.
        if ($this->outputCallback === FALSE) {
            $this->output = ob_get_contents();
        } else {
            $this->output = call_user_func_array($this->outputCallback, array (ob_get_contents()));
        }

        ob_end_clean();
        $this->outputBufferingActive = FALSE;

        // Clean up stat cache.
        clearstatcache();

        // Clean up INI settings.
        foreach ($this->iniSettings as $varName => $oldValue) {
            ini_set($varName, $oldValue);
        }

        $this->iniSettings = array ();

        // Clean up locale settings.
        foreach ($this->locale as $category => $locale) {
            setlocale($category, $locale);
        }

        // Perform assertion on output.
        if (!isset($e)) {
            try {
                if ($this->outputExpectedRegex !== NULL) {
                    $this->hasPerformedExpectationsOnOutput = TRUE;
                    $this->assertRegExp($this->outputExpectedRegex, $this->output);
                    $this->outputExpectedRegex = NULL;
                } else if ($this->outputExpectedString !== NULL) {
                    $this->hasPerformedExpectationsOnOutput = TRUE;
                    $this->assertEquals($this->outputExpectedString, $this->output);
                    $this->outputExpectedString = NULL;
                }
            } catch (Exception $_e) {
                $e = $_e;
            }
        }

        // Workaround for missing "finally".
        if (isset($e)) {
            $this->onNotSuccessfulTest($e);
        }
    }

    /**
     * Overrides "\PHPUnit_Framework_TestCase::runTest()" to display call stack when annotation failed.
     *
     * @return mixed
     * @throws RuntimeException
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    protected function runTest()
    {
        $name = $this->getName(false);
        if ($name === NULL) {
            throw new PHPUnit_Framework_Exception('PHPUnit_Framework_TestCase::$name must not be NULL.');
        }

        try {
            $class = new ReflectionClass($this);
            $method = $class->getMethod($name);
        } catch (ReflectionException $e) {
            $this->fail($e->getMessage());
        }

        try {
            $testResult = $method->invokeArgs($this, array_merge($this->data, $this->dependencyInput));
        } catch (Exception $e) {
            // If "\PHPUnit_Framework_Assert::markTestIncomplete()" was called, or if "\PHPUnit_Framework_Assert::markTestSkipped()" was called.
            if ($e instanceof PHPUnit_Framework_IncompleteTest
                || $e instanceof PHPUnit_Framework_SkippedTest
            ) {
                throw $e;
            }
            // If "@expectedException" annotation is not string.
            if (!is_string($this->getExpectedException())) {
                echo '<b>It is error if this test has been not using "@expectedException" annotation, or it requires "@expectedException" annotation.</b>';
                B::exitForError($e); // Displays error call stack information.
            }
            // "@expectedException" annotation should be success.
            try {
                $this->assertThat($e, new PHPUnit_Framework_Constraint_Exception($this->getExpectedException()));
            } catch (Exception $dummy) {
                echo '<b>Is error, or this test mistook "@expectedException" annotation value.</b>';
                B::exitForError($e); // Displays error call stack information.
            }
            // "@expectedExceptionMessage" annotation should be success.
            try {
                $expectedExceptionMessage = $this->expectedExceptionMessage;
                if (is_string($expectedExceptionMessage)
                    && !empty($expectedExceptionMessage)
                ) {
                    $this->assertThat($e, new PHPUnit_Framework_Constraint_ExceptionMessage($expectedExceptionMessage));
                }
            } catch (Exception $dummy) {
                echo '<b>Is error, or this test mistook "@expectedExceptionMessage" annotation value.</b>';
                B::exitForError($e); // Displays error call stack information.
            }
            // "@expectedExceptionCode" annotation should be success.
            try {
                if ($this->expectedExceptionCode !== NULL) {
                    $this->assertThat($e, new PHPUnit_Framework_Constraint_ExceptionCode($this->expectedExceptionCode));
                }
            } catch (Exception $dummy) {
                echo '<b>Is error, or this test mistook "@expectedExceptionCode" annotation value.</b>';
                B::exitForError($e); // Displays error call stack information.
            }
            return;
        }
        if ($this->getExpectedException() !== NULL) {
            // "@expectedException" should not exist.
            echo '<b>Is error in "' . $class->name . '::' . $name . '".</b>';
            $this->assertThat(NULL, new PHPUnit_Framework_Constraint_Exception($this->getExpectedException()));
        }

        return $testResult;
    }

    /**
     * Overrides "\PHPUnit_Framework_Assert::assertTrue()" to display error call stack information.
     *
     * @param bool   $condition Conditional expression.
     * @param string $message   Error message.
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    static function assertTrue($condition, $message = '')
    {
        B::assert(is_bool($condition), 1);
        B::assert(is_string($message), 2);

        try {
            parent::assertTrue($condition, $message);
        } catch (\Exception $e) {
            B::exitForError($e); // Displays error call stack information.
        }
    }

    /**
     * Overrides "\PHPUnit_Framework_Assert::fail()" to display error call stack information.
     *
     * @param string $message The fail message.
     *
     * @return void
     * @throws PHPUnit_Framework_AssertionFailedError
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    public static function fail($message = '')
    {
        B::assert(is_string($message), 1);

        try {
            parent::fail($message);
        } catch (\Exception $e) {
            B::exitForError($e); // Displays error call stack information.
        }
    }

}

?>
