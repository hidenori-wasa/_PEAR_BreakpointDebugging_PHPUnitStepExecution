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
     * @var array Global variable serialization-keys storage.
     */
    private static $_globalSerializationKeysStorage = array ();

    /**
     * @var array Static attributes serialization-keys storage.
     */
    private static $_staticAttributesSerializationKeysStorage = array ();

    /**
     * Stores variables.
     * NOTICE: A referenced value is not stored. It is until two-dimensional array element that a reference ID is stored.
     *
     * We should not store by serialization because serialization cannot store resource and array element reference variable.
     * However, we may store by serialization because we cannot detect recursive array without changing array and we take time to search deep nest array.
     * Also, we must store by serialization in case of object because we may not be able to clone object by "__clone()" class method.
     *
     * @param array $blacklist                 The list to except from doing variables backup.
     * @param array $variables                 Array variable to store.
     * @param array &$variablesStorage         Variables storage.
     * @param array &$serializationKeysStorage Serialization-keys storage.
     * @param bool  $isGlobal                  Is this the global variables?
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    private static function _storeVariables(array $blacklist, array $variables, array &$variablesStorage, array &$serializationKeysStorage, $isGlobal = false)
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
            ) {
                continue;
            }
            if (($key === 'GLOBALS' && $isGlobal)
                || (!is_object($value) && !is_array($value))
            ) {
                $variablesStorage[$key] = $value;
                continue;
            }
            do {
                if (is_array($value)) {
                    foreach ($value as $value2) {
                        if (is_object($value2)) {
                            break 2;
                        }
                        if (is_array($value2)) {
                            // For example, increases the speed by searching until "deepest array of super global variable" like "$GLOBALS['_SERVER']['argv']".
                            // Also, supports recursive array by searching until there.
                            foreach ($value2 as $value3) {
                                if (is_object($value3)
                                    || is_array($value3)
                                ) {
                                    break 3;
                                }
                            }
                        }
                    }
                    $variablesStorage[$key] = $value;
                    continue 2;
                }
            } while (false);
            $variablesStorage[$key] = serialize($value);
            $serializationKeysStorage[$key] = null;
        }
    }

    /**
     * Restores variables. We must not restore by reference copy because variable ID changes.
     *
     * @param array &$variables               Array variable to restore.
     * @param array $variablesStorage         Variables storage.
     * @param array $serializationKeysStorage Serialization-keys storage.
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    private static function _restoreVariables(array &$variables, array $variablesStorage, array $serializationKeysStorage)
    {
        if (empty($variablesStorage)) {
            return;
        }
        // This loop will not need.
        foreach ($variables as $key => $value) {
            if (!array_key_exists($key, $variablesStorage)) {
                xdebug_break(); // For debug. Will not stop.
            }
        }
        // Judges serialization or copy, and overwrites array variable to restore or adds.
        foreach ($variablesStorage as $key => $value) {
            if (array_key_exists($key, $serializationKeysStorage)) {
                $variables[$key] = unserialize($value);
            } else {
                $variables[$key] = $value;
            }
        }
    }

    /**
     * Checks definition change violation of global variables.
     *
     * @param string $testMethodName The test class method name.
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    static function checkGlobals($testMethodName = '')
    {
        $additionalVariable = array_diff_key($GLOBALS, parent::$globals);
        $deletionalVariable = array_diff_key(parent::$globals, $GLOBALS);
        $isError = false;
        if (!empty($additionalVariable)) {
            $isError = true;
            $definedOrDeleted = 'defined';
            $definesOrDeletes = 'defines';
            $definitionOrDeletion = 'definition';
        } else if (!empty($deletionalVariable)) {
            $isError = true;
            $definedOrDeleted = 'deleted';
            $definesOrDeletes = 'deletes';
            $definitionOrDeletion = 'deletion';
        }
        if (!$isError) {
            return;
        }

        $message = '<pre><b>';
        if ($testMethodName === '') {
            $message .= 'Global variable had been ' . $definedOrDeleted . ' outside unit test class or function! Or, inside of "setUpBeforeClass()"!' . PHP_EOL;
        } else {
            $message .= 'Global variable had been ' . $definedOrDeleted . ' inside unit test class method "' . $testMethodName . '", "setUp()" or "tearDown()"!' . PHP_EOL;
        }
        $message .= PHP_EOL;
        if (!empty($additionalVariable)) {
            $message .= 'We must use public static property instead of use global variable inside unit test file (*Test.php)' . PHP_EOL;
        } else if (!empty($deletionalVariable)) {
            $message .= 'We must not delete global variable by "unset()" inside unit test file (*Test.php).' . PHP_EOL;
        }
        $message .= "\t" . 'because "php" version 5.3.0 cannot detect ' . $definedOrDeleted . ' global variable except unit test file realtime.' . PHP_EOL
            . 'Or, we must use autoload by "new" instead of include "*.php" file which ' . $definesOrDeletes . ' static status inside unit test class method' . PHP_EOL
            . "\t" . 'because "php" version 5.3.0 cannot detect an included static status ' . $definitionOrDeletion . ' realtime.</b></pre>';
        exit($message);
    }

    /**
     * Resets global variables storage.
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    static function resetGlobals()
    {
        parent::$globals = array ();
        self::$_globalSerializationKeysStorage = array ();
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
        self::_storeVariables($blacklist, $GLOBALS, parent::$globals, self::$_globalSerializationKeysStorage, true);
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
        self::_restoreVariables($GLOBALS, parent::$globals, self::$_globalSerializationKeysStorage);
    }

    /**
     *  Checks the declared classes number.
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    static function checkStaticAttributes()
    {
        $declaredClasses = get_declared_classes();
        $currentDeclaredClassesNumber = count($declaredClasses);
        $declaredClassName = $declaredClasses[$currentDeclaredClassesNumber - 1];
        // If the declared-classes-number increases.
        if ($currentDeclaredClassesNumber > self::$_prevDeclaredClassesNumber) {
            // If the included unit test class before test.
            if (is_subclass_of($declaredClassName, 'PHPUnit_Framework_Test')
                || $declaredClassName === 'PHP_Token_BACKTICK' // For code coverage report.
            ) {
                return;
            }
            exit('<pre><b>"class ' . $declaredClassName . '" definition violation.' . PHP_EOL
                . "\t" . 'We must use autoload by "new" instead of include "*.php" file which defines static property' . PHP_EOL
                . "\t" . 'inside unit test class method' . PHP_EOL
                . "\t" . 'because "php" version 5.3.0 cannot detect an included static property definition realtime.</b></pre>'
            );
        } else if ($currentDeclaredClassesNumber < self::$_prevDeclaredClassesNumber) {
            xdebug_break(); // For debug. Will not stop.
        }
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
            // Excepts existing class.
            if (array_key_exists($declaredClassName, parent::$staticAttributes)) {
                xdebug_break(); // For debug. Will not stop.
            }
            // Excepts unit test classes.
            if (preg_match('`^ (PHP (Unit | (_ (CodeCoverage | Invoker | (T (imer | oken_Stream))))) | File_Iterator | sfYaml | Text_Template )`xXi', $declaredClassName) === 1
                || is_subclass_of($declaredClassName, 'PHPUnit_Util_GlobalState') // For extended class of my package.
                || is_subclass_of($declaredClassName, 'PHPUnit_Framework_Test')
            ) {
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
                self::_storeVariables(array (), $backup, parent::$staticAttributes[$declaredClassName], self::$_staticAttributesSerializationKeysStorage);
            }

            // Checks existence of local static variable of static class method.
            foreach ($classReflection->getMethods(ReflectionMethod::IS_STATIC) as $methodReflection) {
                if ($methodReflection->class === $declaredClassName) {
                    $result = $methodReflection->getStaticVariables();
                    // If static variable has been existing.
                    if (!empty($result)) {
                        B::exitForError(
                            PHP_EOL
                            . 'We must use private static property instead of use local static variable of class static method' . PHP_EOL
                            . "\t" . 'because "php" version 5.3.0 cannot restore its value.' . PHP_EOL
                            . "\t" . 'FILE: ' . $methodReflection->getFileName() . PHP_EOL
                            . "\t" . 'LINE: ' . $methodReflection->getStartLine() . PHP_EOL
                            . "\t" . 'CLASS: ' . $methodReflection->class . PHP_EOL
                            . "\t" . 'METHOD: ' . $methodReflection->name . PHP_EOL
                        );
                    }
                }
            }
        }
        self::$_prevDeclaredClassesNumber = $currentDeclaredClassesNumber;
    }

    /**
     * Restores static class attributes.
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    static function restoreStaticAttributes()
    {
        foreach (parent::$staticAttributes as $className => $staticAttributes) {
            $properties = array ();
            self::_restoreVariables($properties, $staticAttributes, self::$_staticAttributesSerializationKeysStorage);
            foreach ($staticAttributes as $name => $value) {
                $reflector = new ReflectionProperty($className, $name);
                $reflector->setAccessible(TRUE);
                $reflector->setValue($properties[$name]);
            }
        }
    }

}

?>
