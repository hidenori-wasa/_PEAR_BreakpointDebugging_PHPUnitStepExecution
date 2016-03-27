<?php

/**
 * Debugs unit test files continuously by IDE.
 *
 * LICENSE:
 * Copyright (c) 2014-, Hidenori Wasa
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
use \BreakpointDebugging_PHPUnit as BU;
use \BreakpointDebugging_Window as BW;
use \BreakpointDebugging_PHPUnit_StaticVariableStorage as BSS;

/**
 * Debugs unit test files continuously by IDE.
 *
 * We can use for unit tests of this package and "PHPUnit" package because this class is instance and this class does not use "PHPUnit" package.
 * Also, we can use instead of "*.phpt".
 * See the "BreakpointDebugging_PHPUnit.php" file-level document for usage.
 *
 * PHP version 5.3.2-5.4.x
 *
 * @category PHP
 * @package  BreakpointDebugging_PHPUnit
 * @author   Hidenori Wasa <public@hidenori-wasa.com>
 * @license  http://opensource.org/licenses/mit-license.php  MIT License
 * @version  Release: @package_version@
 * @link     http://pear.php.net/package/BreakpointDebugging_PHPUnit
 */
class BreakpointDebugging_PHPUnit_FrameworkTestCaseSimple
{

    /**
     * This class method is called first per "*Test.php" or "*TestSimple.php" file.
     *
     * Registers autoload class method to prohibit autoload not to change static status by autoload during "setUp()", "test*()" or "tearDown()".
     * Also, stores initial value of global variables and static properties.
     * Please, inherit this class method and preload class files by following to error display.
     *
     * @return void
     */
    static function setUpBeforeClass()
    {
        BU::checkStartCall();
        BU::displayProgress(300);
        // Registers the check class method for purpose which stores correctly.
        $result = spl_autoload_register('\\' . BSS::AUTOLOAD_NAME, true, true);
        B::assert($result);
        // Stores global variables.
        BSS::storeGlobals(BSS::refGlobalRefs(), BSS::refGlobals(), BSS::refBackupGlobalsBlacklist());
        // Stores static properties.
        BSS::storeProperties(BSS::refStaticProperties(), BSS::refBackupStaticPropertiesBlacklist());
    }

    /**
     * This class method is called lastly per "*TestSimple.php" file.
     *
     * @return void
     */
    public static function tearDownAfterClass()
    {
        $result = spl_autoload_unregister('\\' . BSS::AUTOLOAD_NAME);
        B::assert($result);
    }

    /**
     * Checks the autoload functions.
     *
     * @param string $testClassName  The test class name.
     * @param string $testMethodName The test class method name.
     *
     * @return void
     */
    static function checkAutoloadFunctions($testClassName, $testMethodName = null)
    {
        B::limitAccess(
            array ('BreakpointDebugging/PHPUnit/FrameworkTestCase.php',
            'BreakpointDebugging/PHPUnit/FrameworkTestCaseSimple.php',
            ), true
        );

        // Checks the autoload functions.
        $autoloadFunctions = spl_autoload_functions();
        if (is_array($autoloadFunctions[0]) //
            && is_object($autoloadFunctions[0][0]) //
        ) {
            $className = get_class($autoloadFunctions[0][0]);
        } else {
            $className = $autoloadFunctions[0][0];
        }
        $autoloadFunction = $className . '::' . $autoloadFunctions[0][1];
        if ($autoloadFunction === BSS::AUTOLOAD_NAME) {
            return;
        }

        $message = 'Autoload function "<span style="color:orange">' . $autoloadFunction . '()</span>" must not be registered at top of stack by "spl_autoload_register()"' . PHP_EOL;
        $message .= 'during "setUp()", "test*()" or "tearDown()".' . PHP_EOL;
        $message .= PHP_EOL;
        if ($testMethodName) {
            $message .= 'Inside of "<span style="color:orange">' . $testClassName . '::' . $testMethodName . '()</span>".' . PHP_EOL;
        } else {
            $message .= '"parent::setUpBeforeClass();" must be bottom of "<span style="color:orange">' . $testClassName . '::setUpBeforeClass()</span>".' . PHP_EOL;
        }
        $message .= 'Because "\\' . BSS::AUTOLOAD_NAME . '()" must check static status change error at top of stack.';
        BW::exitForError($message);
    }

    /**
     * Base of "setUp()" class method.
     *
     * @return void
     */
    static function setUpBase()
    {
        B::limitAccess(
            array (
            'BreakpointDebugging/PHPUnit/FrameworkTestCase.php',
            'BreakpointDebugging/PHPUnit/FrameworkTestCaseSimple.php',
            ), true
        );

        B::initializeSync();
        // Stores the output buffering level.
        $obLevel = &BU::refObLevel();
        $obLevel = ob_get_level();
    }

    /**
     * This method is called before a test class method is executed.
     * Sets up initializing which is needed at least in unit test.
     *
     * @return void
     */
    protected function setUp()
    {
        self::setUpBase();
    }

    /**
     * Base of "tearDown()" class method.
     *
     * @return void
     */
    static function tearDownBase()
    {
        B::limitAccess(
            array ('BreakpointDebugging/PHPUnit/FrameworkTestCase.php',
            'BreakpointDebugging/PHPUnit/FrameworkTestCaseSimple.php',
            ), true
        );

        // Restores the output buffering level.
        while (ob_get_level() > BU::refObLevel()) {
            ob_end_clean();
        }
        B::assert(ob_get_level() === BU::refObLevel());
    }

    /**
     * This method is called after a test class method is executed.
     * Cleans up environment which is needed at least in unit test.
     *
     * @return void
     */
    protected function tearDown()
    {
        self::tearDownBase();
    }

    /**
     * Runs class methods of this unit test instance continuously.
     *
     * @param string $testClassName The test class name.
     *
     * @return void
     */
    static function runTestMethods($testClassName)
    {
        B::limitAccess('BreakpointDebugging_PHPUnit.php', true);

        try {
            $currentTestClassName = &BSS::refCurrentTestClassName();
            $currentTestClassName = $testClassName;
            $classReflection = new \ReflectionClass($testClassName);
            $methodReflections = $classReflection->getMethods(ReflectionMethod::IS_PUBLIC);
            // Invokes "setUpBeforeClass()" class method.
            $testClassName::setUpBeforeClass();

            BU::displayProgress(300);
            // Checks the autoload functions.
            self::checkAutoloadFunctions($testClassName);
            foreach ($methodReflections as $methodReflection) {
                $currentTestMethodName = &BSS::refCurrentTestMethodName();
                $currentTestMethodName = $methodReflection->name;
                if (strpos($methodReflection->name, 'test') !== 0) {
                    continue;
                }
                BU::displayProgress(5);
                // Start output buffering.
                ob_start();
                // Creates unit test instance.
                $pTestInstance = new $testClassName();
                // Clean up stat cache.
                clearstatcache();
                // Invokes "setUp()" class method.
                $pTestInstance->setUp();

                // Checks the autoload functions.
                self::checkAutoloadFunctions($testClassName, 'setUp');

                // Invokes "test*()" class method.
                $methodReflection->invoke($pTestInstance);

                // Checks the autoload functions.
                self::checkAutoloadFunctions($testClassName, $methodReflection->name);

                // Invokes "tearDown()" class method.
                $pTestInstance->tearDown();

                // Checks the autoload functions.
                self::checkAutoloadFunctions($testClassName, 'tearDown');

                // Checks an "include" error at "setUp()", "test*()" or "tearDown()".
                BSS::checkIncludeError();

                // Restores global variables.
                $refGlobalRefs = &BSS::refGlobalRefs();
                BSS::restoreGlobals($refGlobalRefs, BSS::refGlobals());
                // Restores static properties.
                BSS::restoreProperties(BSS::refStaticProperties());

                // Deletes unit test instance.
                $pTestInstance = null;
                // Stop output buffering.
                ob_end_clean();
                // Displays a completed test.
                echo '.';
            }
            // Invokes "tearDownAfterClass()" class method.
            $testClassName::tearDownAfterClass();
        } catch (Exception $e) {
            B::exitForError($e); // Displays error call stack information.
        }
    }

    /**
     * Displays error call stack information when assertion is failed.
     *
     * @param bool   $condition Conditional expression.
     * @param string $message   Error message.
     *
     * @return void
     */
    static function assertTrue($condition, $message = '')
    {
        B::assert(is_bool($condition));
        B::assert(is_string($message));

        if (!$condition) {
            B::exitForError($message); // Displays error call stack information.
        }
    }

    /**
     * Displays error call stack information when a test is failed.
     *
     * @param string $message The fail message.
     *
     * @return void
     */
    static function fail($message = '')
    {
        B::assert(is_string($message));

        B::exitForError($message); // Displays error call stack information.
    }

}
