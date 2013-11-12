<?php

/**
 * Debugs unit tests code continuously by IDE. With "\BreakpointDebugging::executeUnitTest()" class method. Supports "php" version 5.3.0 since then.
 *
 * This class extends "PHPUnit_Framework_TestCase".
 * Also, we can execute unit test with remote server without installing "PHPUnit".
 *
 * ### About "PHPUnit" package component. ###
 * I copied following "PHPUnit" package files into "BreakpointDebugging/Component/" directory
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
 * Then, I added "Hidenori Wasa added." to line which I coded into "BreakpointDebugging/Component/" directory.
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
use \BreakpointDebugging_PHPUnitStepExecution as BU;
use \BreakpointDebugging_PHPUnitStepExecution_PHPUnitUtilGlobalState as BGS;

/**
 * Debugs unit tests code continuously by IDE. With "\BreakpointDebugging::executeUnitTest()" class method. Supports "php" version 5.3.0 since then.
 *
 * @package    PHPUnit
 * @subpackage Framework
 * @author     Sebastian Bergmann <sebastian@phpunit.de>
 * @copyright  2001-2013 Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.6.12
 * @link       http://www.phpunit.de/
 * @since      Class available since Release 2.0.0
 */
abstract class BreakpointDebugging_PHPUnitStepExecution_PHPUnitFrameworkTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array List to except to store global variable.
     */
    private static $_backupGlobalsBlacklist = array ();

    /**
     * @var array List to except to store static properties values.
     */
    private static $_backupStaticPropertiesBlacklist = array ();

    /**
     * @var array Global variables.
     */
    private static $_globals = array ();

    /**
     * @var array Snapshot of global variables.
     */
    private static $_globalsSnapshot = array ();

    /**
     * @var array Global variable references.
     */
    private static $_globalRefs = array ();

    /**
     * @var array Snapshot of global variables references.
     */
    private static $_globalRefsSnapshot = array ();

    /**
     * @var array Static properties.
     */
    private static $_staticProperties = array ();

    /**
     * @var array Static properties's snapshot.
     */
    private static $_staticPropertiesSnapshot = array ();

    /**
     * @var int The output buffering level.
     */
    private $_obLevel;

    /**
     * @var bool Once flag per test file.
     */
    private static $_onceFlagPerTestFile = true;

    /**
     * Returns reference of flag of once per test file.
     *
     * @return bool Flag of once per test file.
     */
    static function &refOnceFlagPerTestFile()
    {
        B::limitAccess('BreakpointDebugging_PHPUnitStepExecution.php', true);

        return self::$_onceFlagPerTestFile;
    }

    /**
     * Checks the autoload functions.
     *
     * @param string $testMethodName The test class method name.
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    private function _checkAutoloadFunctions($testMethodName = null)
    {
        // Checks the autoload functions.
        $autoloadFunctions = spl_autoload_functions();
        if ($autoloadFunctions[0] !== array ('BreakpointDebugging_PHPUnitStepExecution_PHPUnitFrameworkTestCase', 'autoload')) {
            if (is_array($autoloadFunctions[0])) {
                $autoloadFunction = $autoloadFunctions[0][0] . '::' . $autoloadFunctions[0][1];
            }
            $className = get_class($this);
            $message = '<b>You must not register autoload function "' . $autoloadFunction . '" at top of stack by "spl_autoload_register()" in all code.' . PHP_EOL;
            if ($testMethodName) {
                $message .= 'Inside of "' . $className . '::' . $testMethodName . '()".' . PHP_EOL;
            } else {
                $message .= 'In "bootstrap file", "file of (class ' . $className . ') which is executed at autoload" or "' . $className . '::setUpBeforeClass()"' . '.' . PHP_EOL;
            }
            $message .= '</b>Because it cannot store static status.';
            B::windowHtmlAddition(BU::UNIT_TEST_WINDOW_NAME, 'pre', 0, $message);
            exit;
        }
    }

    /**
     * It references "self::$_globalRefs".
     */
    static function &refGlobalRefs()
    {
        B::limitAccess('BreakpointDebugging_PHPUnitStepExecution.php', true);

        return self::$_globalRefs;
    }

    /**
     * It references "self::$_globals".
     */
    static function &refGlobals()
    {
        B::limitAccess('BreakpointDebugging_PHPUnitStepExecution.php', true);

        return self::$_globals;
    }

    /**
     * It references "self::$_staticProperties".
     */
    static function &refStaticProperties2()
    {
        B::limitAccess('BreakpointDebugging_PHPUnitStepExecution.php', true);

        return self::$_staticProperties;
    }

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

        // Excepts unit test classes.
        if (BGS::isUnitTestClass($className)) {
            B::autoload($className);
            return;
        }

        // If class file has been loaded first.
        if ($nestLevel === 0) {
            if (self::$_onceFlagPerTestFile) {
                // Checks definition, deletion and change violation of global variables and global variable references in "setUp()".
                BGS::checkGlobals(self::$_globalRefs, self::$_globals, true);
                // Checks the change violation of static properties and static property child element references.
                BGS::checkProperties(self::$_staticProperties);
            } else {
                // Snapshots global variables.
                BGS::storeGlobals(self::$_globalRefsSnapshot, self::$_globalsSnapshot, self::$_backupGlobalsBlacklist, true);
                // Snapshots static properties.
                BGS::storeProperties(self::$_staticPropertiesSnapshot, self::$_backupStaticPropertiesBlacklist, true);
                // Restores global variables.
                BGS::restoreGlobals(self::$_globalRefs, self::$_globals);
                // Restores static properties.
                BGS::restoreProperties(self::$_staticProperties);
            }
            $nestLevel = 1;
            try {
                B::autoload($className);
            } catch (\Exception $exception) {
                $forBreakpoint = 0;
            }
            // If class file has been loaded completely including dependency files.
            $nestLevel = 0;
            // Checks deletion and change violation of global variables and global variable references during autoload.
            BGS::checkGlobals(self::$_globalRefs, self::$_globals);
            // Checks the change violation of static properties and static property child element references.
            BGS::checkProperties(self::$_staticProperties);
            // Stores global variables before variable value is changed in bootstrap file and "setUpBeforeClass()".
            BGS::storeGlobals(self::$_globalRefs, self::$_globals, self::$_backupGlobalsBlacklist);
            // Stores static properties before variable value is changed.
            BGS::storeProperties(self::$_staticProperties, self::$_backupStaticPropertiesBlacklist);
            if (!self::$_onceFlagPerTestFile) {
                // Restores global variables snapshot.
                BGS::restoreGlobals(self::$_globalRefsSnapshot, self::$_globalsSnapshot);
                // Restores static properties snapshot.
                BGS::restoreProperties(self::$_staticPropertiesSnapshot);
            }
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
        );
        $workDir = B::getStatic('$_workDir');
        foreach ($lockFilePaths as $lockFilePath) {
            $lockFilePath = realpath($workDir . '/' . $lockFilePath);
            if (is_file($lockFilePath)) {
                B::unlink(array ($lockFilePath));
            }
            B::assert(!is_file($lockFilePath));
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
        B::assert(ob_get_level() === $this->_obLevel);
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
     * Also, I changed storing and restoring class method.
     * And, I changed location which calls those.
     * So, it is "--static-backup" command line switch for continuous execution by IDE.
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    public function runBare()
    {
        // Displays the progress.
        $buffer = '';
        for ($count = 0; ob_get_level() > 0; $count++) {
            $result = ob_get_clean();
            if (is_string($result)) {
                $buffer .= $result;
            }
        }
        B::windowHtmlAddition(BU::UNIT_TEST_WINDOW_NAME, 'pre', 0, $buffer);
        flush();
        for (; $count > 0; $count--) {
            ob_start();
        }

        $this->numAssertions = 0;

        if (self::$_onceFlagPerTestFile) {
            // For autoload.
            self::$_onceFlagPerTestFile = false;
            // For autoload.
            self::$_backupGlobalsBlacklist = $this->backupGlobalsBlacklist;
            self::$_backupStaticPropertiesBlacklist = $this->backupStaticAttributesBlacklist;
            // Checks the autoload functions.
            $this->_checkAutoloadFunctions();
            // Checks definition, deletion and change violation of global variables and global variable references in "setUp()".
            BGS::checkGlobals(self::$_globalRefs, self::$_globals, true);
            // Checks the change violation of static properties and static property child element references.
            BGS::checkProperties(self::$_staticProperties, false);
        }

        // Start output buffering.
        ob_start();
        $this->outputBufferingActive = TRUE;

        // Clean up stat cache.
        clearstatcache();

        try {
            $this->setExpectedExceptionFromAnnotation();

            // Checks an annotation.
            $this->_checkAnnotation();
            // Restores global variables.
            BGS::restoreGlobals(self::$_globalRefs, self::$_globals);
            // Restores static properties.
            BGS::restoreProperties(self::$_staticProperties);

            $this->setUp();

            // Checks the autoload functions.
            $this->_checkAutoloadFunctions('setUp');

            $this->checkRequirements();
            $this->assertPreConditions();
            $this->testResult = $this->runTest();

            // Checks the autoload functions.
            $this->_checkAutoloadFunctions($this->getName());

            $this->verifyMockObjects();
            $this->assertPostConditions();
            $this->status = PHPUnit_Runner_BaseTestRunner::STATUS_PASSED;
        } catch (PHPUnit_Framework_IncompleteTest $e) {
            // Checks the autoload functions.
            $this->_checkAutoloadFunctions($this->getName());

            $this->status = PHPUnit_Runner_BaseTestRunner::STATUS_INCOMPLETE;
            $this->statusMessage = $e->getMessage();
        } catch (PHPUnit_Framework_SkippedTest $e) {
            // Checks the autoload functions.
            $this->_checkAutoloadFunctions($this->getName());

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
            // Checks the autoload functions.
            $this->_checkAutoloadFunctions('tearDown');
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
                B::windowHtmlAddition(BU::UNIT_TEST_WINDOW_NAME, 'pre', 0, '<b>It is error if this test has been not using "@expectedException" annotation, or it requires "@expectedException" annotation.</b>');
                B::exitForError($e); // Displays error call stack information.
            }
            // "@expectedException" annotation should be success.
            try {
                $this->assertThat($e, new PHPUnit_Framework_Constraint_Exception($this->getExpectedException()));
            } catch (Exception $dummy) {
                B::windowHtmlAddition(BU::UNIT_TEST_WINDOW_NAME, 'pre', 0, '<b>Is error, or this test mistook "@expectedException" annotation value.</b>');
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
                B::windowHtmlAddition(BU::UNIT_TEST_WINDOW_NAME, 'pre', 0, '<b>Is error, or this test mistook "@expectedExceptionMessage" annotation value.</b>');
                B::exitForError($e); // Displays error call stack information.
            }
            // "@expectedExceptionCode" annotation should be success.
            try {
                if ($this->expectedExceptionCode !== NULL) {
                    $this->assertThat($e, new PHPUnit_Framework_Constraint_ExceptionCode($this->expectedExceptionCode));
                }
            } catch (Exception $dummy) {
                B::windowHtmlAddition(BU::UNIT_TEST_WINDOW_NAME, 'pre', 0, '<b>Is error, or this test mistook "@expectedExceptionCode" annotation value.</b>');
                B::exitForError($e); // Displays error call stack information.
            }
            return;
        }
        if ($this->getExpectedException() !== NULL) {
            // "@expectedException" should not exist.
            B::windowHtmlAddition(BU::UNIT_TEST_WINDOW_NAME, 'pre', 0, '<b>Is error in "' . $class->name . '::' . $name . '".</b>');

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
        B::assert(is_bool($condition));
        B::assert(is_string($message));

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
        B::assert(is_string($message));

        try {
            parent::fail($message);
        } catch (\Exception $e) {
            B::exitForError($e); // Displays error call stack information.
        }
    }

}

?>
