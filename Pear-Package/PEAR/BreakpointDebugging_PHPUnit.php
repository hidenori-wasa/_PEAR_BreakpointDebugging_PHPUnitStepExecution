<?php

/**
 * Classes for unit test.
 *
 * PHP version 5.3.2-5.4.x
 *
 * This file does not use except unit test. Therefore, response time is zero in release.
 * This file names put "_" to cause error at autoload.
 *
 * LICENSE:
 * Copyright (c) 2013-, Hidenori Wasa
 * All rights reserved.
 *
 * License content is written in "PEAR/BreakpointDebugging/docs/BREAKPOINTDEBUGGING_LICENSE.txt".
 *
 * @category PHP
 * @package  BreakpointDebugging_PHPUnit
 * @author   Hidenori Wasa <public@hidenori-wasa.com>
 * @license  http://opensource.org/licenses/mit-license.php  MIT License
 * @version  Release: @package_version@
 * @link     http://pear.php.net/package/BreakpointDebugging_PHPUnit
 */
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
 * @license  http://opensource.org/licenses/mit-license.php  MIT License
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
     *                                       Uses for assertion or error exception throwing because invokes plural inside a class method at unit test error.
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
        if (B::getExeMode() & B::UNIT_TEST) {
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
     * The once flag.
     *
     * @var bool
     */
    private static $_onceFlag;

    /**
     * The test directory.
     *
     * @var string
     */
    private static $_testDir;

    /**
     * Unit test window name.
     *
     * @var string
     */
    private static $_unitTestWindowName;

    /**
     * Does it use "PHPUnit" package?
     *
     * @var bool
     */
    private static $_phpUnitUse;

    /**
     * Unit test file paths storage.
     *
     * @var array
     */
    private static $_unitTestFilePathsStorage = array ();

    /**
     * Execution mode for unit test.
     *
     * @var int
     */
    static $exeMode;

    /**
     * Unit test directory.
     *
     * @var string
     */
    static $unitTestDir;

    /**
     * It is relative path of class which see the code coverage, and its current directory must be project directory.
     *
     * @var mixed
     */
    private static $_classFilePaths;

    /**
     * The code coverage report directory path.
     *
     * @var string
     */
    private static $_codeCoverageReportPath;

    /**
     * Separator for display.
     *
     * @var string
     */
    private static $_separator;

    /**
     * Unit test result.
     *
     * @var string
     */
    private static $_unitTestResult = 'DONE';

    /**
     * The output buffering level.
     *
     * @var int
     */
    private static $_obLevel;

    /**
     * How to test?
     *
     * @var string
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

        self::$exeMode = &B::refExeMode(); // This is not static backup rule violation because this reference is copied once at initialization.
        self::$_separator = PHP_EOL . '//////////////////////////////////////////////////////////////////////////' . PHP_EOL;
    }

    /**
     * Gets this class's property.
     *
     * @return mixed This class's property.
     */
    static function getClassFilePaths()
    {
        return self::$_classFilePaths;
    }

    /**
     * Gets this class's property.
     *
     * @return array This class's property.
     */
    static function getCodeCoverageReportPath()
    {
        return self::$_codeCoverageReportPath;
    }

    /**
     * Refers to the output buffering level.
     *
     * @return int The output buffering level.
     */
    static function &refObLevel()
    {
        B::limitAccess('BreakpointDebugging/PHPUnit/FrameworkTestCaseSimple.php', true);

        return self::$_obLevel;
    }

    /**
     * Gets content of HTML file.
     *
     * @return void
     */
    static function getHtmlFileContent()
    {
        if (self::$_phpUnitUse) {
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
     * @return string Unit test window name.
     */
    static function getUnitTestWindowName()
    {
        return self::$_unitTestWindowName;
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
            B::iniSet('xdebug.var_display_max_depth', '5');
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
    private static function _getUnitTestDir()
    {
        $callStack = debug_backtrace();
        $callStack = array_reverse($callStack);
        foreach ($callStack as $call) {
            if (!array_key_exists('class', $call) //
                || $call['class'] !== 'BreakpointDebugging_PHPUnit' //
            ) {
                continue;
            }
            $functionName = $call['function'];
            if ($functionName === 'executeUnitTestSimple' //
                || $functionName === 'executeUnitTest' //
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
     */
    private static function _runPHPUnitCommand($command)
    {
        $commandElements = explode(' ', $command);
        $testFileName = array_pop($commandElements);
        array_push($commandElements, self::$unitTestDir . $testFileName);
        array_unshift($commandElements, 'dummy');
        // Checks command line switches.
        if (in_array('--process-isolation', $commandElements)) {
            throw new \BreakpointDebugging_ErrorException('"--process-isolation" command line switch must not be used because IDE cannot see other process.' . PHP_EOL . 'So, unit test cannot be debugged with IDE.', 101);
        }
        $command = ltrim($command);
        echo self::$_separator;
        echo 'Runs <b>"' . str_replace('\\', '/', substr(realpath(self::$unitTestDir . $testFileName), strlen(realpath(self::$unitTestDir . self::$_testDir)) + 1)) . '"</b>.' . PHP_EOL;
        // Initializes once's flag per test file.
        $onceFlagPerTestFile = &BSS::refOnceFlagPerTestFile(); // This is not rule violation because this property is not stored.
        $onceFlagPerTestFile = true;
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
    private static function _runPHPUnitCommandSimple($testFilePath)
    {
        echo self::$_separator;
        echo "Runs <b>a unit test of \"$testFilePath\"</b> file." . PHP_EOL;
        // Initializes once's flag per test file.
        $onceFlagPerTestFile = &BSS::refOnceFlagPerTestFile(); // This is not rule violation because this property is not stored.
        $onceFlagPerTestFile = true;
        // Uses this package error handler.
        set_error_handler('\BreakpointDebugging_PHPUnit::handleError', -1);
        $predeclaredClassesNumber = count(get_declared_classes());
        // Includes unit test file.
        include_once self::$unitTestDir . $testFilePath;
        $postdeclaredClasses = get_declared_classes();
        // Gets test-class-name.
        while (true) {
            for ($count = count($postdeclaredClasses) - 1; $count >= $predeclaredClassesNumber; $count--) {
                $declaredClassName = $postdeclaredClasses[$count];
                if (is_subclass_of($declaredClassName, '\BreakpointDebugging_PHPUnit_FrameworkTestCaseSimple')) {
                    break 2;
                }
            }
            B::assert(false);
        }
        // Runs unit test continuously.
        \BreakpointDebugging_PHPUnit_FrameworkTestCaseSimple::runTestMethods($declaredClassName);
        // Uses "BreakpointDebugging" package error handler.
        restore_error_handler();
    }

    /**
     * Display the progress.
     *
     * @param int $dy Y vector distance.
     *
     * @return void
     */
    static function displayProgress($dy = 0)
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
                    self::$_unitTestResult = 'INCOMPLETE';
                    $result = '<strong>I</strong>' . substr($result, 1);
                }
                $buffer .= $result;
            }
        }
        BW::htmlAddition(self::$_unitTestWindowName, 'pre', 0, $buffer);
        BW::scrollBy(self::$_unitTestWindowName, $dy);
        flush();
        for (; $count > 0; $count--) {
            ob_start();
        }
    }

    //////////////////////////////////////// For package user ////////////////////////////////////////
    /**
     * Sets the debug execution mode.
     *
     * @return void
     */
    static function setDebug()
    {
        // Excepts the release bit.
        self::$exeMode &= ~B::RELEASE;
    }

    /**
     * Sets the release execution mode.
     *
     * @return void
     */
    static function setRelease()
    {
        // Adds the release bit.
        self::$exeMode |= B::RELEASE;
    }

    /**
     * Ignores breakpoint.
     *
     * @return void
     */
    static function ignoreBreakpoint()
    {
        // Adds ignoring breakpoint bit.
        self::$exeMode |= B::IGNORING_BREAK_POINT;
    }

    /**
     * Stops at breakpoint.
     *
     * @return void
     */
    static function notIgnoreBreakpoint()
    {
        // Excepts ignoring breakpoint bit.
        self::$exeMode &= ~B::IGNORING_BREAK_POINT;
    }

    /**
     * Asserts exception message.
     *
     * @param object $exception Exception object.
     * @param string $message   Message to compare.
     *
     * @return void
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
     * Marks the test as skipped.
     *
     * @return void
     * @throws PHPUnit_Framework_SkippedTestError
     */
    private static function _markTestSkipped()
    {
        if (self::$_phpUnitUse) {
            \PHPUnit_Framework_Assert::markTestSkipped();
        }
        // Stop output buffering.
        ob_end_clean();
        // Displays skipped test.
        echo 'S';
        // Start output buffering.
        ob_start();
    }

    /**
     * Marks the test as skipped in debug.
     *
     * @return bool TRUE if debug mode of simple unit test.
     * @throws PHPUnit_Framework_SkippedTestError if debug mode of unit test.
     */
    static function markTestSkippedInDebug()
    {
//        if (B::isDebug()) {
//            \PHPUnit_Framework_Assert::markTestSkipped();
//        }
        if (B::isDebug()) {
            self::_markTestSkipped();
            return true;
        }
        return false;
    }

    /**
     * Marks the test as skipped in release.
     *
     * @return bool TRUE if release mode of simple unit test.
     * @throws PHPUnit_Framework_SkippedTestError if release mode of unit test.
     */
    static function markTestSkippedInRelease()
    {
//        if (!B::isDebug()) {
//            \PHPUnit_Framework_Assert::markTestSkipped();
//        }
        if (!B::isDebug()) {
            self::_markTestSkipped();
            return true;
        }
        return false;
    }

    /**
     * Calls class method for test.
     *
     * <pre>
     * ### Template code. ###
     *
     * <code>
     * array(
     *      'objectOrClassName' => '',
     *      'methodName' => '',
     *      'params' => array()
     * )
     * </code>
     *
     * ### Static class method call's example code. ###
     *
     * <code>
     * array(
     *      'objectOrClassName' => 'ClassName',
     *      'methodName' => '_staticMethodName',
     *      'params' => array('param1value','param2value')
     * )
     * </code>
     *
     * ### Auto class method call's example code. ###
     *
     * <code>
     * array(
     *      'objectOrClassName' => $object,
     *      'methodName' => '_autoMethodName',
     *      'params' => array('param1value','param2value')
     * )
     * </code>
     *
     * </pre>
     *
     * @param array $parameters Parameters.
     *
     * @return mixed Return value of called class method.
     */
    static function callForTest($parameters)
    {
        B::assert(func_num_args() === 1);
        B::assert(is_array($parameters));

        extract($parameters);

        B::assert(is_object($objectOrClassName) || is_string($objectOrClassName));
        B::assert(is_string($methodName));
        B::assert(is_array($params));

        if (is_object($objectOrClassName)) {
            $className = get_class($objectOrClassName);
        } else {
            $className = $objectOrClassName;
        }
        $classReflection = new \ReflectionClass($className);
        $methodReflection = $classReflection->getMethod($methodName);
        $methodReflection->setAccessible(true);
        if ($methodReflection->isStatic()) {
            return $methodReflection->invokeArgs(null, $params);
        } else {
            return $methodReflection->invokeArgs($objectOrClassName, $params);
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
     */
    static function checkExeMode($isUnitTest = false)
    {
        B::assert(is_bool($isUnitTest));

        if (!$isUnitTest) {
            $errorMessage = <<<EOD
"define('BREAKPOINTDEBUGGING_MODE', 'DEBUG');" or
"define('BREAKPOINTDEBUGGING_MODE', 'RELEASE');"
    must be set into "BreakpointDebugging_MySetting.php".
EOD;
            BW::exitForError('<b>' . $errorMessage . '</b>');
        }
    }

    static $exclusionClassNames = array (
        // Required class names.
        'App' => true, // "CakePHP" class.
        'BaseCoverageReport' => true, // "CakePHP" class.
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
    );

    /**
     * Prepares unit test.
     *
     * @param string $howToTest How to test.
     *
     * @return bool "false" if failure.
     * @throws \BreakpointDebugging_ErrorException
     */
    private static function _prepareUnitTest($howToTest = 'PHPUNIT')
    {
        // Preloads error classes.
        class_exists('BreakpointDebugging_Error');
        // Adds pear package component directory into "include_path".
        $pearDir = `pear config-get php_dir`;
        if (isset($pearDir)) {
            if (BREAKPOINTDEBUGGING_IS_WINDOWS) {
                if ($pearDir[1] !== ':') {
                    $pearDir = 'C:' . $pearDir;
                }
            }
            $componentDir = PATH_SEPARATOR . rtrim($pearDir) . '/BreakpointDebugging/Component';
        } else {
            $componentDir = '';
        }
        $includePaths = explode(PATH_SEPARATOR, ini_get('include_path'));
        array_unshift($includePaths, $includePaths[0]);
        $includePaths[1] = __DIR__ . '/BreakpointDebugging/Component' . $componentDir;
        // Adds the unit test file directory into "include_path".
        array_unshift($includePaths, $includePaths[0]);
        $includePaths[1] = self::$unitTestDir;
        ini_set('include_path', implode(PATH_SEPARATOR, $includePaths));

        switch ($howToTest) {
            case 'SIMPLE':
                $isUnitTestClass = function ($declaredClassName) {
                    set_error_handler('\BreakpointDebugging::handleError', 0);
                    // Excepts unit test classes.
                    if (preg_match('`^ BreakpointDebugging_ (PHPUnit_StaticVariableStorage | Window)`xX', $declaredClassName) === 1 //
                        || @is_subclass_of($declaredClassName, 'BreakpointDebugging_PHPUnit_FrameworkTestCaseSimple') //
                        || array_key_exists($declaredClassName, \BreakpointDebugging_PHPUnit::$exclusionClassNames) //
                    ) {
                        restore_error_handler();
                        return true;
                    }
                    restore_error_handler();
                    return false;
                };
                self::$_phpUnitUse = false;
                self::$_unitTestWindowName = 'BreakpointDebugging_PHPUnitSimple';
                break;
            case 'PHPUNIT':
                $isUnitTestClass = function ($declaredClassName) {
                    set_error_handler('\BreakpointDebugging::handleError', 0);
                    // Excepts unit test classes.
                    if (preg_match('`^ (BreakpointDebugging_ (PHPUnit_StaticVariableStorage | Window)) | (PHP (Unit | (_ (CodeCoverage | Invoker | (T (imer | oken_Stream))))) | File_Iterator | sfYaml | Text_Template )`xX', $declaredClassName) === 1 //
                        || @is_subclass_of($declaredClassName, 'PHPUnit_Framework_Test') //
                        || array_key_exists($declaredClassName, \BreakpointDebugging_PHPUnit::$exclusionClassNames) //
                    ) {
                        restore_error_handler();
                        return true;
                    }
                    restore_error_handler();
                    return false;
                };
                self::$_phpUnitUse = true;
                self::$_unitTestWindowName = 'BreakpointDebugging_PHPUnit';
                break;
            case 'PHPUNIT_OWN':
                $isUnitTestClass = function ($declaredClassName) {
                    set_error_handler('\BreakpointDebugging::handleError', 0);
                    // Excepts unit test classes.
                    if (preg_match('`^ PHP (Unit | (_ (CodeCoverage | Invoker | (T (imer | oken_Stream))))) | File_Iterator | sfYaml | Text_Template`xX', $declaredClassName) === 1 //
                        || @is_subclass_of($declaredClassName, 'PHPUnit_Framework_Test') //
                        || array_key_exists($declaredClassName, \BreakpointDebugging_PHPUnit::$exclusionClassNames) //
                    ) {
                        restore_error_handler();
                        return true;
                    }
                    restore_error_handler();
                    return false;
                };
                self::$_phpUnitUse = true;
                self::$_unitTestWindowName = 'BreakpointDebugging_PHPUnit';
            case 'SIMPLE_OWN':
                $isUnitTestClass = function ($declaredClassName) {
                    set_error_handler('\BreakpointDebugging::handleError', 0);
                    // Excepts unit test classes.
                    if (preg_match('`^ (BreakpointDebugging_ (PHPUnit_StaticVariableStorage | Window)) | (PHP (Unit | (_ (CodeCoverage | Invoker | (T (imer | oken_Stream))))) | File_Iterator | sfYaml | Text_Template )`xX', $declaredClassName) === 1 //
                        || @is_subclass_of($declaredClassName, 'PHPUnit_Framework_Test') //
                        || @is_subclass_of($declaredClassName, 'BreakpointDebugging_PHPUnit_FrameworkTestCaseSimple') //
                        || array_key_exists($declaredClassName, \BreakpointDebugging_PHPUnit::$exclusionClassNames) //
                    ) {
                        restore_error_handler();
                        return true;
                    }
                    restore_error_handler();
                    return false;
                };
                if (!isset(self::$_phpUnitUse)) {
                    self::$_phpUnitUse = false;
                    self::$_unitTestWindowName = 'BreakpointDebugging_PHPUnitSimple';
                }
                break;
            default:
                throw new \BreakpointDebugging_ErrorException('Class method parameter is incorrect.');
        }
        \BreakpointDebugging_PHPUnit_StaticVariableStorage::initialize($isUnitTestClass);
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
    private static function _getVerificationTestFilePaths($howToTest)
    {
        // Sets regular expression to get test file paths.
        if ($howToTest === 'SIMPLE' || $howToTest === 'SIMPLE_OWN') {
            $regEx = '`.* TestSimple\.php $`xX';
        } else {
            $regEx = '`.* Test\.php $`xX';
        }
        $verificationTestFilePaths = array ();
        // Sets the full test directory path.
        $fullTestDirPath = self::$unitTestDir . self::$_testDir;
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
     *
     * @return void
     */
    static function setTestDir($testDir)
    {
        self::$_testDir = $testDir;
    }

    /**
     * Executes unit test files continuously, and debugs with IDE.
     *
     * @param array  $testFilePaths       The file paths of unit tests.
     * @param string $howToTest           How to test?
     *      'PHPUNIT':     Uses "PHPUnit" package.
     *      'PHPUNIT_OWN': This package's 'PHPUNIT' mode test.
     *      'SIMPLE':      Does not use "PHPUnit" package. This mode can be used instead of "*.phpt" file.
     *      'SIMPLE_OWN':  This package test.
     * @param string $commandLineSwitches Command-line-switches except "--stop-on-failure --static-backup".
     *
     * @return void
     */
    static function executeUnitTest($testFilePaths, $howToTest = 'PHPUNIT', $commandLineSwitches = '')
    {
        B::assert(func_num_args() <= 3);
        B::assert(is_array($testFilePaths));
        B::assert(!empty($testFilePaths));
        B::assert(is_string($commandLineSwitches));

        if (!B::checkDevelopmentSecurity()) {
            exit;
        }

        self::_getUnitTestDir();
        self::_prepareUnitTest($howToTest);

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

        BW::virtualOpen(self::$_unitTestWindowName, self::getHtmlFileContent());
        ob_start();

        if (B::isDebug()) {
            echo '<b>\'DEBUG_UNIT_TEST\' execution mode.</b>' . PHP_EOL;
        } else {
            echo '<b>\'RELEASE_UNIT_TEST\' execution mode.</b>' . PHP_EOL;
        }

        echo 'The test current directory = <b>"' . str_replace('\\', '/', realpath(self::$unitTestDir . self::$_testDir)) . '/"</b>' . PHP_EOL;

        if (BREAKPOINTDEBUGGING_IS_CAKE //
            && spl_autoload_functions() === array (array ('BreakpointDebugging', 'loadClass')) //
        ) {
            // Changes autoload class method order.
            $result = spl_autoload_unregister('\BreakpointDebugging::loadClass');
            B::assert($result === true);
            include getcwd() . '/WasaCakeTestStart.php';
            $result = spl_autoload_register('\BreakpointDebugging::loadClass');
            B::assert($result === true);

            if (!BREAKPOINTDEBUGGING_IS_PRODUCTION) { // In case of development server mode.
                // Checks the fact that "CakeLog" configuration is not defined because "BreakpointDebugging" pear package does logging.
                $wasaResult = \CakeLog::configured();
                if (!empty($wasaResult)) {
                    throw new \BreakpointDebugging_ErrorException('The "CakeLog" must not be configured by "\CakeLog::config(..." inside "app/Config/bootstrap.php".');
                }
            }
        }

        foreach ($testFilePaths as $testFilePath) {
            $testFullFilePath = self::$_testDir . $testFilePath;
            // If unit test file does not exist.
            if (!is_file(self::$unitTestDir . $testFullFilePath)) {
                throw new \BreakpointDebugging_ErrorException('Unit test file "' . $testFullFilePath . '" does not exist.', 102);
            }
            // Registers the executed full test file path.
            $executedTestFilePaths[] = $testFilePath;
            // If test file path contains '_'.
            if (strpos($testFullFilePath, '_') !== false) {
                echo "'_' of '$testFullFilePath' must be changed to '-' because it is translated to directory separator." . PHP_EOL;
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
                self::_runPHPUnitCommandSimple($testFullFilePath);
            } else {
                self::_runPHPUnitCommand($commandLineSwitches . ' --stop-on-failure --static-backup ' . $testFullFilePath);
            }
            // Moves unit test window in front.
            BW::front(self::$_unitTestWindowName);
            BW::scrollBy(self::$_unitTestWindowName, PHP_INT_MAX, PHP_INT_MAX);
            gc_collect_cycles();
        }
        self::displayProgress();
        echo self::$_separator;
        BSS::checkFunctionLocalStaticVariable();
        BSS::checkMethodLocalStaticVariable();

        switch (self::$_unitTestResult) {
            case 'DONE':
                echo '<b>Unit tests was completed.</b>' . PHP_EOL;
                break;
            case 'INCOMPLETE':
                echo '<strong>Unit tests was incompletely.</strong>' . PHP_EOL;
                break;
            default:
                B::assert(false);
        }

        $diffTestFilePaths = array_diff(self::_getVerificationTestFilePaths($howToTest), $executedTestFilePaths);
        if (!empty($diffTestFilePaths)) {
            echo self::$_separator;
            echo '<b>The following test file paths had not been executed.</b>' . PHP_EOL;
        }
        foreach ($diffTestFilePaths as $diffTestFilePath) {
            echo '<strong>\'' . $diffTestFilePath . '\',</strong>' . PHP_EOL;
        }

        BW::htmlAddition(self::$_unitTestWindowName, 'pre', 0, ob_get_clean());
        BW::front(self::$_unitTestWindowName);
        BW::scrollBy(self::$_unitTestWindowName, PHP_INT_MAX, PHP_INT_MAX);
    }

    /**
     * Executes unit test files continuously without "PHPUnit" package, and debugs with IDE.
     *
     * @param array  $testFilePaths       The file paths of unit tests.
     * @param string $howToTest           How to test?
     *      'SIMPLE': Does not use "PHPUnit" package. This mode can be used instead of "*.phpt" file.
     *      'SIMPLE_OWN': This package test.
     * @param string $commandLineSwitches Command-line-switches except "--stop-on-failure --static-backup".
     *
     * @return void
     */
    static function executeUnitTestSimple($testFilePaths, $howToTest = 'SIMPLE', $commandLineSwitches = '')
    {
        self::executeUnitTest($testFilePaths, $howToTest, $commandLineSwitches);
    }

    /**
     * Checks the lines to ignore in code coverage report.
     *
     * @param string   $filename           Filename to parse.
     * @param resource $pFile              The file pointer to parse.
     * @param array    $codeCoverageReport The code coverage report.
     *
     * @return void
     */
    private static function _checkLinesToIgnoreInCodeCoverageReport($filename, $pFile, &$codeCoverageReport)
    {
        $checkCodeCoverageReport = function($startLineNumber, $endLineNumber, &$codeCoverageReport, $filename) {
            $codeCoverageReportEndElement = array_slice($codeCoverageReport, count($codeCoverageReport) - 1, 1, true);
            $key = key($codeCoverageReportEndElement);
            if ($endLineNumber > $key) {
                $endLineNumber = $key;
            }
            for ($count = $startLineNumber; $count <= $endLineNumber; $count++) {
                if (array_key_exists($count, $codeCoverageReport)) {
                    // If a line has been executed.
                    if ($codeCoverageReport[$count] === 1) {
                        $errorMessage = <<<EOD
ERROR MESSAGE: A class of "@codeCoverageIgnore" and a class method of "@codeCoverageIgnore" must not be executed.
    FILE: $filename
    LINE: $count
EOD;
                        throw new \BreakpointDebugging_ErrorException($errorMessage);
                    }
                    // Checks the line.
                    $codeCoverageReport[$count] = -3;
                }
            }
        };

        $fileContent = '';
        do {
            $readData = fread($pFile, 4096);
            B::assert($readData !== false);
            $fileContent .= $readData;
        } while ($readData !== '');

        $state = 'NONE';
        $tokens = token_get_all($fileContent);
        foreach ($tokens as $token) {
            $tokenName = null;
            $tokenLine = null;
            $tokenChar = null;
            if (is_array($token)) {
                $tokenName = $token[0];
                $tokenLine = $token[2];
            } else if (is_string($token)) {
                $tokenChar = $token;
            } else {
                B::assert(false);
            }

            switch ($state) {
                case 'NONE':
                    if ($tokenName === T_DOC_COMMENT) {
                        $tokenData = $token[1];
                        if (preg_match('`@codeCoverageIgnore [^_[:alnum:]]`xX', $tokenData) === 1) {
                            $state = 'SEARCH_START_LINE';
                        }
                    }
                    break;
                case 'SEARCH_START_LINE':
                    if ($tokenName === T_CLASS //
                        || $tokenName === T_FUNCTION //
                    ) {
                        $state = 'SEARCH_END_LINE';
                        $startLineNumber = $tokenLine;
                        $curlyBracketCount = 0;
                    }
                    if ($tokenName === T_DOC_COMMENT) {
                        throw new \BreakpointDebugging_ErrorException('"@codeCoverageIgnore" must be in document comment of class or class method.');
                    }
                    break;
                case 'SEARCH_END_LINE':
                    if ($tokenChar === '{') {
                        $curlyBracketCount++;
                    } else if ($tokenChar === '}') {
                        $curlyBracketCount--;
                        if ($curlyBracketCount === 0) {
                            $state = 'SEARCH_END_LINE_NUMBER';
                        }
                        if ($curlyBracketCount < 0) {
                            throw new \BreakpointDebugging_ErrorException('Curly bracket count must be positive number.');
                        }
                    }
                    break;
                case 'SEARCH_END_LINE_NUMBER':
                    if (is_int($tokenLine)) {
                        $state = 'NONE';
                        $endLineNumber = $tokenLine;
                        // Checks the lines to ignore in code coverage report.
                        $checkCodeCoverageReport($startLineNumber, $endLineNumber, $codeCoverageReport, $filename);
                    }
                    break;
                default:
                    B::assert(false);
            }
        }
        switch ($state) {
            case 'SEARCH_START_LINE':
                throw new \BreakpointDebugging_ErrorException('"@codeCoverageIgnore" must be in document comment of class or class method.');
            case 'SEARCH_END_LINE':
                throw new \BreakpointDebugging_ErrorException('Curly bracket count must become zero.');
            case 'SEARCH_END_LINE_NUMBER':
                $endLineNumber = PHP_INT_MAX;
                // Checks the lines to ignore in code coverage report.
                $checkCodeCoverageReport($startLineNumber, $endLineNumber, $codeCoverageReport, $filename);
        }
    }

    /**
     * Creates code coverage report without "PHPUnit" package, then displays in browser.
     *
     * @param mixed  $testFilePaths          Relative paths of unit test files.
     * @param mixed  $classFileRelativePaths Relative paths of class which see the code coverage.
     * @param string $howToTest              How to test?
     *      'SIMPLE': Does not use "PHPUnit" package. This mode can be used instead of "*.phpt" file.
     *      'SIMPLE_OWN': This package test.
     *      'PHPUNIT': The default test.
     * @param string $commandLineSwitches Command-line-switches except "--static-backup --coverage-html" in case of 'PHPUNIT'.
     *
     * @return void
     */
    static function displayCodeCoverageReport($testFilePaths, $classFileRelativePaths, $howToTest = 'PHPUNIT', $commandLineSwitches = '')
    {
        xdebug_start_code_coverage(XDEBUG_CC_UNUSED | XDEBUG_CC_DEAD_CODE);

        B::assert(func_num_args() <= 4);
        B::assert(is_string($testFilePaths) || is_array($testFilePaths));
        B::assert(is_string($classFileRelativePaths) || is_array($classFileRelativePaths));
        B::assert($howToTest === 'PHPUNIT' //
            || (($howToTest === 'SIMPLE' || $howToTest === 'SIMPLE_OWN' ) && $commandLineSwitches === '') //
        );
        B::assert(is_string($commandLineSwitches));

        if (!extension_loaded('xdebug')) {
            B::exitForError('"' . __METHOD__ . '()" needs "xdebug" extention.');
        }
        // B::iniCheck('xdebug.coverage_enable', '1', '');

        if (is_string($testFilePaths)) {
            $testFilePaths = array ($testFilePaths);
        }
        if (is_string($classFileRelativePaths)) {
            $classFileRelativePaths = array ($classFileRelativePaths);
        }

        self::$_codeCoverageKind = $howToTest;
        self::executeUnitTestSimple($testFilePaths, $howToTest, $commandLineSwitches);
        $codeCoverages = xdebug_get_code_coverage();
        xdebug_stop_code_coverage();

        $windowNumber = 1;
        foreach ($classFileRelativePaths as $classFileRelativePath) {
            $classFilePath = stream_resolve_include_path($classFileRelativePath);
            B::assert(array_key_exists($classFilePath, $codeCoverages));
            $codeCoverageReport = $codeCoverages[$classFilePath];
            $buffer = '';
            $isDuringIgnore = false;
            $errorMessage = PHP_EOL
                . 'FILE: ' . $classFileRelativePath . PHP_EOL
                . 'LINE: ';
            $pFile = B::fopen(array ($classFilePath, 'rb'));
            self::_checkLinesToIgnoreInCodeCoverageReport($classFileRelativePath, $pFile, $codeCoverageReport);
            $result = rewind($pFile);
            B::assert($result === true);
            $lineNumber = 0;
            $coveringLineNumber = 0;
            $notCoveringNumber = 0;
            while (( $line = fgets($pFile)) !== false) {
                $lineNumber++;
                $lineNumberString = '<span class="lineNum">' . sprintf('%05d: ', $lineNumber) . '</span>';
                $line = $lineNumberString . htmlspecialchars($line, ENT_QUOTES, 'UTF-8');
                // If a comment line.
                if (!array_key_exists($lineNumber, $codeCoverageReport)) {
                    if ($isDuringIgnore) { // Is during ignoring.
                        if (preg_match("`@codeCoverageIgnoreEnd [^_[:alnum:]]`xX", $line)) {
                            $isDuringIgnore = false;
                        } else if (preg_match("`@codeCoverageIgnoreStart [^_[:alnum:]]`xX", $line)) {
                            throw new \BreakpointDebugging_ErrorException('"@codeCoverageIgnoreStart" area has been existing on covering line.' . $errorMessage . $lineNumber);
                        }
                    } else { // Is not during ignoring.
                        if (preg_match("`@codeCoverageIgnoreEnd [^_[:alnum:]]`xX", $line)) {
                            throw new \BreakpointDebugging_ErrorException('"@codeCoverageIgnoreEnd" has been called before "@codeCoverageIgnoreStart" is called.' . $errorMessage . $lineNumber);
                        } else if (preg_match("`@codeCoverageIgnoreStart [^_[:alnum:]]`xX", $line)) {
                            $isDuringIgnore = true;
                        }
                    }
                    $buffer .= '<span>' . $line . '</span>';
                    continue;
                }
                // If a code line.
                switch ($codeCoverageReport[$lineNumber]) {
                    case 1: // If a covering line.
                        if ($isDuringIgnore) { // Is during ignoring.
                            throw new \BreakpointDebugging_ErrorException('Covering line must not be ignored.' . $errorMessage . $lineNumber);
                        } else { // Is not during ignoring.
                            $coveringLineNumber++;
                            $buffer .= '<span class="lineCov">' . $line . '</span>';
                        }
                        break;
                    case -1: // If not covering line.
                        if ($isDuringIgnore) { // Is during ignoring.
                            $buffer .= '<span class="lineIgnoring">' . $line . '</span>';
                        } else { // Is not during ignoring.
                            $notCoveringNumber++;
                            $buffer .= '<span class="lineNoCov">' . $line . '</span>';
                        }
                        break;
                    case -2: // If not execution line.
                        $buffer .= '<span class="lineDeadCode">' . $line . '</span>';
                        break;
                    case -3: // If a ignored line by "@codeCoverageIgnore".
                        $buffer .= '<span class="lineIgnoring">' . $line . '</span>';
                        break;
                    default :
                        assert(false);
                }
            }
            $codeLineNumber = $coveringLineNumber + $notCoveringNumber;
            $codeCoveragePercent = sprintf('%3.2f', $coveringLineNumber * 100 / $codeLineNumber);
            $html = <<<EOD
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>DisplayCodeCoverageReport</title>
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
            BW::virtualOpen('BreakpointDebugging_displayCodeCoverageReport' . $windowNumber++, $html);
        }
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

    /**
     * Checks the start function call.
     *
     * @return void
     */
    static function checkStartCall()
    {
        B::limitAccess(
            array (
            'BreakpointDebugging_PHPUnit.php',
            'BreakpointDebugging/PHPUnit/FrameworkTestCaseSimple.php'
            ), true
        );

        if (self::$_onceFlag) {
            return;
        }
        self::$_onceFlag = true;

        $callStack = debug_backtrace();
        $callStart = array_pop($callStack);
        if ($callStart['class'] !== 'BreakpointDebugging_PHPUnit' // Parent class method must be called from "\BreakpointDebugging_PHPUnit" class.
            || ($callStart['function'] !== 'executeUnitTest' // Parent class method must be called from "executeUnitTest" class method.
            && $callStart['function'] !== 'executeUnitTestSimple' // Parent class method must be called from "executeUnitTest" class method.
            && $callStart['function'] !== 'displayCodeCoverageReport') // Parent class method must be called from "executeUnitTest" class method.
        ) {
            throw new \BreakpointDebugging_ErrorException('This unit test file must be executed by calling "\BreakpointDebugging_PHPUnit::executeUnitTest()" or "\BreakpointDebugging_PHPUnit::executeUnitTestSimple()" or "\BreakpointDebugging_PHPUnit::displayCodeCoverageReport()".');
        }
    }

    /**
     * Checks a parent call.
     *
     * @return void
     */
    private static function _checkCall()
    {
        self::checkStartCall();
        $callStack = debug_backtrace();
        $call = $callStack[2];
        if ($call['function'] !== 'setUpBeforeClass') {
            throw new \BreakpointDebugging_ErrorException('"\BreakpointDebugging_PHPUnit::loadClass()" or "\BreakpointDebugging_PHPUnit::includeClass()" class method must be called inside "setUpBeforeClass()" class method.');
        }
    }

    /**
     * Loads a class file and checks static status change error during it.
     *
     * @param type $className
     *
     * @return void
     */
    static function loadClass($className)
    {
        // Checks this call.
        self::_checkCall();
        // Stores global variables.
        BSS::storeGlobals(BSS::refGlobalRefs(), BSS::refGlobals(), BSS::refBackupGlobalsBlacklist());
        // Stores static properties.
        BSS::storeProperties(BSS::refStaticProperties(), BSS::refBackupStaticPropertiesBlacklist());
        // Loads a class file.
        class_exists($className);
        // Checks static status change error.
        $globalRefs = &BSS::refGlobalRefs();
        BSS::restoreGlobals($globalRefs, BSS::refGlobals(), true);
        BSS::restoreProperties(BSS::refStaticProperties(), true);
    }

    /**
     * Includes a class file and checks static status change error during it.
     *
     * @param type $filePath
     */
    static function includeClass($filePath)
    {
        // Checks this call.
        self::_checkCall();
        // Stores global variables.
        BSS::storeGlobals(BSS::refGlobalRefs(), BSS::refGlobals(), BSS::refBackupGlobalsBlacklist());
        // Stores static properties.
        BSS::storeProperties(BSS::refStaticProperties(), BSS::refBackupStaticPropertiesBlacklist());
        // Includes a class file.
        include_once $filePath;
        // Checks static status change error.
        $globalRefs = &BSS::refGlobalRefs();
        BSS::restoreGlobals($globalRefs, BSS::refGlobals(), true);
        BSS::restoreProperties(BSS::refStaticProperties(), true);
    }

}

// Initializes static class.
\BreakpointDebugging_PHPUnit::initialize();
