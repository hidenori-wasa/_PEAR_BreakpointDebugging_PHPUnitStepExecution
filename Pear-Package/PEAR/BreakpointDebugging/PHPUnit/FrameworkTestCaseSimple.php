<?php

/**
 * Debugs unit test files continuously by IDE.
 *
 * LICENSE:
 * Copyright (c) 2014-, Hidenori Wasa
 * All rights reserved.
 *
 * License content is written in "PEAR/BreakpointDebugging/BREAKPOINTDEBUGGING_LICENSE.txt".
 *
 * @category PHP
 * @package  BreakpointDebugging_PHPUnit
 * @author   Hidenori Wasa <public@hidenori-wasa.com>
 * @license  http://opensource.org/licenses/mit-license.php  MIT License
 * @version  Release: @package_version@
 * @link     http://pear.php.net/package/BreakpointDebugging_PHPUnit
 */
// File to have "use" keyword does not inherit scope into a file including itself,
// also it does not inherit scope into a file including,
// and moreover "use" keyword alias has priority over class definition,
// therefore "use" keyword alias does not be affected by other files.
use \BreakpointDebugging as B;
use \BreakpointDebugging_Window as BW;
use \BreakpointDebugging_PHPUnit as BU;
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
     * "\BreakpointDebugging_PHPUnit" instance.
     *
     * @var object
     */
    private static $_phpUnit;

    /**
     * This class method is called first per "*TestSimple.php" file.
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {

    }

    /**
     * This class method is called lastly per "*TestSimple.php" file.
     *
     * @return void
     */
    public static function tearDownAfterClass()
    {

    }

    /**
     * Sets the "\BreakpointDebugging_PHPUnit" object.
     *
     * @param object $phpUnit "\BreakpointDebugging_PHPUnit".
     *
     * @return void
     */
    static function setPHPUnit($phpUnit)
    {
        B::limitAccess('BreakpointDebugging_PHPUnit.php', true);

        if (!isset(self::$_phpUnit)) {
            self::$_phpUnit = $phpUnit;
        }
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
            && $autoloadFunctions[0][0] === self::$_phpUnit->getStaticVariableStorageInstance() //
            && $autoloadFunctions[0][1] === 'loadClass' //
        ) {
            return;
        }
        if (is_object($autoloadFunctions[0][0])) {
            $className = get_class($autoloadFunctions[0][0]);
        } else {
            $className = $autoloadFunctions[0][0];
        }
        $autoloadFunction = $className . '::' . $autoloadFunctions[0][1];

        $message = '<b>You must not register autoload function "' . $autoloadFunction . '" at top of stack by "spl_autoload_register()" in all code.' . PHP_EOL;
        if ($testMethodName) {
            $message .= 'Inside of "' . $testClassName . '::' . $testMethodName . '()".' . PHP_EOL;
        } else {
            $message .= 'In "bootstrap file", "file of (class ' . $testClassName . ') which is executed at autoload" or "' . $testClassName . '::setUpBeforeClass()"' . '.' . PHP_EOL;
        }
        $message .= '</b>Because it cannot store static status.';
        BW::exitForError($message);
    }

    /**
     * Base of "setUp()" class method.
     *
     * @param object $phpUnit "\BreakpointDebugging_PHPUnit" instance.
     *
     * @return void
     */
    static function setUpBase($phpUnit)
    {
        B::limitAccess(
            array (
            'BreakpointDebugging/PHPUnit/FrameworkTestCase.php',
            'BreakpointDebugging/PHPUnit/FrameworkTestCaseSimple.php',
            ), true
        );

        B::initializeSync();
        // Stores the output buffering level.
        $obLevel = &$phpUnit->refObLevel();
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
        self::setUpBase(self::$_phpUnit);
    }

    /**
     * Base of "tearDown()" class method.
     *
     * @param object $phpUnit "\BreakpointDebugging_PHPUnit" instance.
     *
     * @return void
     */
    static function tearDownBase($phpUnit)
    {
        B::limitAccess(
            array ('BreakpointDebugging/PHPUnit/FrameworkTestCase.php',
            'BreakpointDebugging/PHPUnit/FrameworkTestCaseSimple.php',
            ), true
        );

        // Restores the output buffering level.
        while (ob_get_level() > $phpUnit->refObLevel()) {
            ob_end_clean();
        }
        B::assert(ob_get_level() === $phpUnit->refObLevel());
    }

    /**
     * This method is called after a test class method is executed.
     * Cleans up environment which is needed at least in unit test.
     *
     * @return void
     */
    protected function tearDown()
    {
        self::tearDownBase(self::$_phpUnit);
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
            $classReflection = new \ReflectionClass($testClassName);
            $methodReflections = $classReflection->getMethods(ReflectionMethod::IS_PUBLIC);
            // Invokes "setUpBeforeClass()" class method.
            $testClassName::setUpBeforeClass();

            self::$_phpUnit->displayProgress(300);
            // Checks the autoload functions.
            self::checkAutoloadFunctions($testClassName);
            // Checks definition, deletion and change violation of global variables and global variable references in "setUp()".
            BSS::checkGlobals(BSS::refGlobalRefs(), BSS::refGlobals(), true);
            // Checks the change violation of static properties and static property child element references.
            self::$_phpUnit->getStaticVariableStorageInstance()->checkProperties(BSS::refStaticProperties2(), BSS::refBackupStaticPropertiesBlacklist(), false);
            foreach ($methodReflections as $methodReflection) {
                if (strpos($methodReflection->name, 'test') !== 0) {
                    continue;
                }
                self::$_phpUnit->displayProgress(5);
                // Start output buffering.
                ob_start();
                // Creates unit test instance.
                $pTestInstance = new $testClassName();
                // Clean up stat cache.
                clearstatcache();
                // Restores global variables.
                BSS::restoreGlobals(BSS::refGlobalRefs(), BSS::refGlobals());
                // Restores static properties.
                BSS::restoreProperties(BSS::refStaticProperties2());

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

    /**
     * Marks the test as skipped in debug.
     *
     * @return void
     */
    static function markTestSkippedInDebug()
    {
        if (!(BU::$exeMode & B::RELEASE)) {
            return true;
        }
        return false;
    }

    /**
     * Marks the test as skipped in release.
     *
     * @return void
     */
    static function markTestSkippedInRelease()
    {
        if (BU::$exeMode & B::RELEASE) {
            return true;
        }
        return false;
    }

}
