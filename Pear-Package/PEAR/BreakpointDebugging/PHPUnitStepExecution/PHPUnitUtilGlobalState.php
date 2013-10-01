<?php

/**
 * Utility for static state. Supports "php" version 5.3.0 since then.
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
 * @subpackage Util
 * @author     Sebastian Bergmann <sebastian@phpunit.de>
 * @copyright  2001-2013 Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       http://www.phpunit.de/
 * @since      File available since Release 3.4.0
 */
use \BreakpointDebugging as B;

/**
 * Utility for static state. Supports "php" version 5.3.0 since then.
 *
 * @category   PHP
 * @package    PHPUnit
 * @subpackage Util
 * @author     Sebastian Bergmann <sebastian@phpunit.de>
 * @copyright  2001-2013 Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.6.11
 * @link       http://www.phpunit.de/
 * @since      Class available since Release 3.4.0
 */
class BreakpointDebugging_PHPUnitStepExecution_PHPUnitUtilGlobalState extends \PHPUnit_Util_GlobalState
{
    /**
     * @var int Previous declared classes number.
     */
    private static $_prevDeclaredClassesNumber = 0;

    /**
     * @var array Snapshot of global variables.
     */
    private static $_globalsSnapshot = array ();

    /**
     * Stores variables.
     *
     * NOTICE: Reference setting inside "__construct()" is not broken by "unset()" because it is reset.
     *         However, reference setting inside file scope of "autoload or including" is broken by "unset()".
     * @param array $blacklist         The list to except from doing variables backup.
     * @param array $variables         Array variable to store.
     * @param array &$variablesStorage Variables storage.
     * @param bool  $isGlobal          Is this the global variables?
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    private static function _storeVariables(array $blacklist, array $variables, array &$variablesStorage, $isGlobal = false)
    {
        if ($isGlobal) {
            // Deletes "unset()" variable from storage because we can do "unset()" except property.
            foreach ($variablesStorage as $key => $value) {
                if (!array_key_exists($key, $variables)) {
                    unset($variablesStorage[$key]);
                }
            }
        }

        // Stores new variable by autoload or initialization.
        foreach ($variables as $key => $value) {
            if (in_array($key, $blacklist)
                || array_key_exists($key, $variablesStorage)
                || $value instanceof Closure
                || $key === 'GLOBALS' && $isGlobal
            ) {
                continue;
            }
            $variablesStorage[$key] = $value;
        }
    }

    /**
     * Restores variables.
     *
     * @param array &$variables       Array variable to restore.
     * @param array $variablesStorage Variables storage.
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    private static function _restoreVariables(array &$variables, array $variablesStorage)
    {
        if (empty($variablesStorage)) {
            return;
        }
        foreach ($variablesStorage as $key => $value) {
            // We must not restore by reference copy because variable ID changes.
            $variables[$key] = $value;
        }
    }

    /**
     * Get global property.
     *
     * @return Global property.
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    static function getGlobalProperty()
    {
        return parent::$globals;
    }

    /**
     * Set global property.
     *
     * @param array $globals Global property.
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    static function setGlobalProperty($globals)
    {
        B::limitAccess('BreakpointDebugging_PHPUnitStepExecution.php', true);

        parent::$globals = $globals;
    }

    /**
     * Checks definition deletion violation of global variables to keep reference.
     *
     * @param string $testMethodName The test class method name.
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    static function checkGlobals($testMethodName = '')
    {
        $deletionalVariable = array_diff_key(parent::$globals, $GLOBALS);
        if (empty($deletionalVariable)) {
            return;
        }

        $message = '<pre><b>';
        if ($testMethodName === '') {
            $message .= 'Global variable has been deleted outside unit test class or function! Or, inside of "setUpBeforeClass()"! Or, inside of bootstrap file!' . PHP_EOL;
        } else {
            $message .= 'Global variable has been deleted inside unit test class method "setUp()"!' . PHP_EOL;
        }
        $message .= '</b>' . PHP_EOL;
        $message .= 'Unit test file (*Test.php) must not delete global variable by "unset()".' . PHP_EOL;
        $message .= "\t" . 'because "php" version 5.3.0 cannot detect deleted global variable except unit test file realtime.' . PHP_EOL
            . 'Or, unit test class method must use autoload by "new" instead of include "*.php" file which deletes static status' . PHP_EOL
            . "\t" . 'because "php" version 5.3.0 cannot detect an included static status deletion realtime.</pre>';
        exit($message);
    }

    /**
     * Resets global variables storage to change value.
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    static function resetGlobals()
    {
        parent::$globals = array ();
    }

    /**
     * Stores global variables.
     *
     * @param array $blacklist The list to except from storage global variables.
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    static function backupGlobals(array $blacklist)
    {
        self::_storeVariables($blacklist, $GLOBALS, parent::$globals, true);
    }

    /**
     * Restores global variables.
     *
     * @param array $blacklist Does not use.
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    static function restoreGlobals(array $blacklist = array ())
    {
        self::_restoreVariables($GLOBALS, parent::$globals);
    }

    /**
     * Is it unit test class?
     *
     * @param type $declaredClassName
     *
     * @return bool Is it unit test class?
     */
    private static function _isUnitTestClass($declaredClassName)
    {
        // Excepts unit test classes.
        if (preg_match('`^ (PHP (Unit | (_ (CodeCoverage | Invoker | (T (imer | oken_Stream))))) | File_Iterator | sfYaml | Text_Template )`xXi', $declaredClassName) === 1
            || is_subclass_of($declaredClassName, 'PHPUnit_Util_GlobalState') // For extended class of my package.
            || is_subclass_of($declaredClassName, 'PHPUnit_Framework_Test')
        ) {
            return true;
        }
        return false;
    }

    /**
     * Stores static class attributes.
     *
     * @param array $blacklist The list to except from storage static class attributes.
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    static function backupStaticAttributes(array $blacklist)
    {
        // Scans the declared classes.
        $declaredClasses = get_declared_classes();
        $currentDeclaredClassesNumber = count($declaredClasses);
        for ($key = $currentDeclaredClassesNumber - 1; $key >= self::$_prevDeclaredClassesNumber; $key--) {
            $declaredClassName = $declaredClasses[$key];
            // Excepts unit test classes.
            if (self::_isUnitTestClass($declaredClassName)) {
                continue;
            }
            // Class reflection.
            $classReflection = new \ReflectionClass($declaredClassName);
            // If it is not user defined class.
            if (!$classReflection->isUserDefined()) {
                continue;
            }

            $backup = array ();
            // Static properties reflection.
            foreach ($classReflection->getProperties(\ReflectionProperty::IS_STATIC) as $attribute) {
                // If it is not property of base class. Because reference variable cannot be extended.
                if ($attribute->class === $declaredClassName) {
                    $attributeName = $attribute->name;
                    // If static property does not exist in black list (PHPUnit_Framework_TestCase::$backupStaticAttributesBlacklist).
                    if (!isset($blacklist[$declaredClassName])
                        || !in_array($attributeName, $blacklist[$declaredClassName])
                    ) {
                        $attribute->setAccessible(TRUE);
                        $attributeValue = $attribute->getValue();

                        if (!$attributeValue instanceof Closure) {
                            $backup[$attributeName] = $attributeValue;
                        }
                    }
                }
            }

            if (!empty($backup)) {
                parent::$staticAttributes[$declaredClassName] = array ();
                // Stores static class properties.
                self::_storeVariables(array (), $backup, parent::$staticAttributes[$declaredClassName]);
            }
        }
        self::$_prevDeclaredClassesNumber = $currentDeclaredClassesNumber;
    }

    /**
     * Snapshots static class attributes to restore.
     *
     * @param array $blacklist The list to except from storage static class attributes.
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    static function snapshotStaticAttributes(array $blacklist)
    {
        // Stores property.
        $staticAttributes = parent::$staticAttributes;
        $prevDeclaredClassesNumber = self::$_prevDeclaredClassesNumber;
        // Resets static class attributes.
        self::$_prevDeclaredClassesNumber = 0;
        parent::$staticAttributes = array ();
        // Makes snapshot.
        self::backupStaticAttributes($blacklist);
        // Copies the snapshot.
        self::$_globalsSnapshot = parent::$staticAttributes;
        // Restores property.
        self::$_prevDeclaredClassesNumber = $prevDeclaredClassesNumber;
        parent::$staticAttributes = $staticAttributes;
    }

    /**
     * Swaps snapshot and static attributes.
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    static function swapsSnapshotAndStaticAttributes()
    {
        $tmp = parent::$staticAttributes;
        parent::$staticAttributes = self::$_globalsSnapshot;
        self::$_globalsSnapshot = $tmp;
    }

    /**
     * Restores static class attributes.
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    static function restoreStaticAttributes()
    {
        foreach (self::$_globalsSnapshot as $className => $staticAttributes) {
            $properties = array ();
            self::_restoreVariables($properties, $staticAttributes);
            foreach ($staticAttributes as $name => $value) {
                $reflector = new ReflectionProperty($className, $name);
                $reflector->setAccessible(TRUE);
                $reflector->setValue($properties[$name]);
            }
        }
    }

    /**
     * Checks a function local static variable.
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    static function checkFunctionLocalStaticVariable()
    {
        $componentFullPath = \BreakpointDebugging_PHPUnitStepExecution_PHPUnitUtilFilesystem::streamResolveIncludePath('BreakpointDebugging/Component/');
        $definedFunctionsName = get_defined_functions();
        foreach ($definedFunctionsName['user'] as $definedFunctionName) {
            $functionReflection = new ReflectionFunction($definedFunctionName);
            $staticVariables = $functionReflection->getStaticVariables();
            // If static variable has been existing.
            if (!empty($staticVariables)) {
                $fileName = $functionReflection->getFileName();
                if (strpos($fileName, $componentFullPath) === 0) {
                    continue;
                }
                echo '<pre>' . PHP_EOL
                . 'We must use private static property of class method instead of use local static variable of function' . PHP_EOL
                . 'because "php" version 5.3.0 cannot restore its value.' . PHP_EOL
                . "\t" . '<b>FILE: ' . $functionReflection->getFileName() . PHP_EOL
                . "\t" . 'LINE: ' . $functionReflection->getStartLine() . PHP_EOL
                . "\t" . 'FUNCTION: ' . $functionReflection->name . '</b></pre>';
            }
        }
    }

    /**
     * Checks a function local static variable.
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    static function checkMethodLocalStaticVariable()
    {
        // Scans the declared classes.
        $declaredClasses = get_declared_classes();
        $currentDeclaredClassesNumber = count($declaredClasses);
        for ($key = $currentDeclaredClassesNumber - 1; $key >= 0; $key--) {
            $declaredClassName = $declaredClasses[$key];
            // Excepts unit test classes.
            if (self::_isUnitTestClass($declaredClassName)) {
                continue;
            }
            // Class reflection.
            $classReflection = new \ReflectionClass($declaredClassName);
            // If it is not user defined class.
            if (!$classReflection->isUserDefined()) {
                continue;
            }
            // Checks existence of local static variable of static class method.
            foreach ($classReflection->getMethods(ReflectionMethod::IS_STATIC) as $methodReflection) {
                if ($methodReflection->class === $declaredClassName) {
                    $result = $methodReflection->getStaticVariables();
                    // If static variable has been existing.
                    if (!empty($result)) {
                        echo '<pre>' . PHP_EOL
                        . 'Code which is tested must use private static property instead of use local static variable of static class method' . PHP_EOL
                        . 'because "php" version 5.3.0 cannot restore its value.' . PHP_EOL
                        . "\t" . '<b>FILE: ' . $methodReflection->getFileName() . PHP_EOL
                        . "\t" . 'LINE: ' . $methodReflection->getStartLine() . PHP_EOL
                        . "\t" . 'CLASS: ' . $methodReflection->class . PHP_EOL
                        . "\t" . 'METHOD: ' . $methodReflection->name . '</b></pre>';
                    }
                }
            }
        }
    }

}

?>
