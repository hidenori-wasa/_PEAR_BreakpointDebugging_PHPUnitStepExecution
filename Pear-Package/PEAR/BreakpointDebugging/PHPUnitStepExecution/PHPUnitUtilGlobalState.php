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

//    /**
//     * This is reference variable?
//     *
//     * @param mixed  &$variable     A variable to examine.
//     * @param string &$variableInfo A variable information result.
//     *
//     * @return bool This is reference variable?
//     * @author Hidenori Wasa <public@hidenori-wasa.com>
//     */
//    private static function _getVariableInfo(&$variable, &$variableInfo)
//    {
//        static $onceFlag = true;
//
//        if ($onceFlag) {
//            $onceFlag = false;
//            $a = new \Exception();
//            $b = &$a;
//            ob_start();
//            xdebug_debug_zval('b');
//            $outputs = explode("\n", strip_tags(ob_get_clean()));
//            // Expected format: "object(TestClassA)[2]"
//            if (strpos($outputs[0], ' is_ref=1)') === false
//                || preg_match('`^ object \( [_[:alpha:]] [_[:alnum:]]* \) \[ [[:digit:]]+ \] $`xX', $outputs[1]) !== 1
//            ) {
//                throw new \BreakpointDebugging_ErrorException('"xdebug_debug_zval()" result format error.');
//            }
//        }
//
//        ob_start();
//        xdebug_debug_zval('variable');
//        $outputs = explode("\n", strip_tags(ob_get_clean()));
//        $variableInfo = $outputs[1];
//        // If this is not reference variable.
//        if (strpos($outputs[0], ' is_ref=1)') === false) {
//            return false;
//        }
//        return true;
//    }
//
//    /**
//     * This is recursive array?
//     *
//     * @param array $array          An array variable to store.
//     * @param array $parentElements Parent elements.
//     *
//     * @return bool This is recursive array?
//     * @author Hidenori Wasa <public@hidenori-wasa.com>
//     */
//    private static function _isRecursiveArray($array, $parentElements)
//    {
//        B::assert(is_array($array));
//        B::assert(is_array($parentElements));
//
//        if (!self::_getVariableInfo($array, $dummy)) {
//            return false;
//        }
//        foreach ($parentElements as &$parentElement) {
//            if (is_array($parentElement)) {
//                $elementNumber = count($parentElement);
//                array_push($array, '');
//                $currentElementNumber = count($parentElement);
//                array_pop($array);
//                if ($elementNumber + 1 === $currentElementNumber) {
//                    return true;
//                }
//            }
//        }
//        return false;
//    }
//
//    /**
//     * This is recursive object?
//     *
//     * @param object $object         An array variable to store.
//     * @param array  $parentElements Parent elements.
//     *
//     * @return bool This is recursive object?
//     * @author Hidenori Wasa <public@hidenori-wasa.com>
//     */
//    private static function _isRecursiveObject($object, $parentElements)
//    {
//        B::assert(is_object($object));
//        B::assert(is_array($parentElements));
//
//        $objectInfo = null;
//        if (self::_getVariableInfo($object, $objectInfo) === false) {
//            return false;
//        }
//        foreach ($parentElements as &$parentElement) {
//            if (is_object($parentElement)) {
//                self::_getVariableInfo($parentElement, $objectInfo2);
//                // If object is reference variable.
//                if ($objectInfo2 === $objectInfo) {
//                    return true;
//                }
//            }
//        }
//        return false;
//    }
//
//    /**
//     * Stores variables in array.
//     *
//     * @param array $array                   An array variable to store.
//     * @param mixed &$parentVariablesStorage Parent variables storage.
//     * @param array $parentElements          Parent elements.
//     *
//     * @return void
//     * @author Hidenori Wasa <public@hidenori-wasa.com>
//     */
//    private static function _storeVariablesInArray($array, &$parentVariablesStorage, $parentElements)
//    {
//        B::assert(is_array($array));
//        B::assert(is_array($parentElements));
//
//        if (empty($array)) {
//            $variablesStorage = $array;
//        } else {
//            foreach ($array as $key => $value) {
//                $variablesStorage[$key] = array ('array');
//                if (is_array($value)) { // In case of array.
//                    $parentElements[] = &$value;
//                    if (!self::_isRecursiveArray($value, $parentElements)) {
//                        self::_storeVariablesInArray($value, $variablesStorage[$key], $parentElements);
//                    }
//                } else if (is_object($value)) { // In case of object.
//                    $parentElements[] = &$value;
//                    if (!self::_isRecursiveObject($value, $parentElements)) {
//                        self::_storeVariablesInObject($value, $variablesStorage[$key], $parentElements);
//                    }
//                } else { // In case of scalar.
//                    $variablesStorage[$key] = array ('scalar', $value);
//                }
//            }
//        }
//        $parentVariablesStorage = array ('array', $variablesStorage);
//    }
//
//    /**
//     * Stores variables in object.
//     *
//     * @param object $object                  An object to store.
//     * @param mixed  &$parentVariablesStorage Variables storage.
//     * @param array  $parentElements          Parent elements.
//     *
//     * @return void
//     * @author Hidenori Wasa <public@hidenori-wasa.com>
//     */
//    private static function _storeVariablesInObject($object, &$parentVariablesStorage, $parentElements)
//    {
//        B::assert(is_object($object));
//        B::assert(is_array($parentElements));
//
//        $objectReflection = new \ReflectionObject($object);
//        foreach ($objectReflection->getStaticProperties() as $key => $value) {
//            $variablesStorage[$key] = array ('object');
//            if (is_array($value)) { // In case of array.
//                $parentElements[] = &$value;
//                if (!self::_isRecursiveArray($value, $parentElements)) {
//                    self::_storeVariablesInArray($value, $variablesStorage[$key], $parentElements);
//                }
//            } else if (is_object($value)) { // In case of object.
//                $parentElements[] = &$value;
//                if (!self::_isRecursiveObject($value, $parentElements)) {
//                    self::_storeVariablesInObject($value, $variablesStorage[$key], $parentElements);
//                }
//            } else { // In case of scalar.
//                $variablesStorage[$key] = array ('scalar', $value);
//            }
//        }
//        $parentVariablesStorage = array ('object', $variablesStorage);
//    }
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

//        $originalXdebugVarDisplayMaxDepth = ini_set('xdebug.var_display_max_depth', 0);
        // Stores new variable by autoload or initialization.
        foreach ($variables as $key => $value) {
            if (in_array($key, $blacklist)
                || array_key_exists($key, $variablesStorage)
                || $value instanceof Closure
                || $key === 'GLOBALS' && $isGlobal
            ) {
                continue;
            }
//            if ($key === 'GLOBALS' && $isGlobal) {
//                continue;
//            }
//            $parentElements = array (&$value);
//            if (is_array($value)) { // In case of array.
//                self::_storeVariablesInArray($value, $variablesStorage[$key], $parentElements);
//            } else if (is_object($value)) { // In case of object.
//                self::_storeVariablesInObject($value, $variablesStorage[$key], $parentElements);
//            } else {
//                $variablesStorage[$key] = $value;
//            }
            $variablesStorage[$key] = $value;
        }
//        ini_set('xdebug.var_display_max_depth', $originalXdebugVarDisplayMaxDepth);
    }

//    /**
//     * Restores variables by elements. We must not restore by reference copy because variable ID changes.
//     *
//     * @param mixed &$variable        Variable to restore.
//     * @param array $variablesStorage Variables storage.
//     *
//     * @return void
//     * @author Hidenori Wasa <public@hidenori-wasa.com>
//     */
//    private static function _restoreVariablesByElements(&$variable, $variablesStorage)
//    {
//        B::assert(is_array($variablesStorage));
//        B::assert(count($variablesStorage) >= 2);
//
//        $kind = current($variablesStorage);
//        $variablesStorage2 = next($variablesStorage);
//        switch ($kind) {
//            case 'array':
//                B::assert(is_array($variablesStorage2));
//                if (empty($variablesStorage2)) {
//                    $variable = $variablesStorage2;
//                    break;
//                }
//                foreach ($variablesStorage2 as $key => $variableStorage) {
//                    if (is_array($variableStorage)) {
//                        self::_restoreVariablesByElements($variable[$key], $variableStorage);
//                    }
//                }
//                break;
//            case 'object':
//                B::assert(is_object($variablesStorage2));
//                $objectReflection = new \ReflectionObject($variablesStorage2);
//                foreach ($objectReflection->getStaticProperties() as $key => $variableStorage) {
//                    if (is_array($variableStorage)) {
//                        self::_restoreVariablesByElements($variable[$key], $variableStorage);
//                    }
//                }
//                break;
//            case 'scalar':
//                B::assert(!is_array($variablesStorage2) && !is_object($variablesStorage2));
//                $variable = $variablesStorage2;
//                break;
//            default:
//                B::assert(false);
//        }
//    }
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
        //// Judges serialization or copy, and overwrites array variable to restore or adds.
        foreach ($variablesStorage as $key => $value) {
//            if (is_array($value)) {
//                self::_restoreVariablesByElements($variables[$key], $value);
//            } else {
//                $variables[$key] = $value;
//            }
            // We must not restore by reference copy because variable ID changes.
            $variables[$key] = $value;
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
        unset($additionalVariable['GLOBALS']);
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

        //$message = '<pre><b>';
        $message = '<pre>';
        if ($testMethodName === '') {
            //$message .= 'Global variable had been ' . $definedOrDeleted . ' outside unit test class or function! Or, inside of "setUpBeforeClass()"!' . PHP_EOL;
            $message .= 'Global variable had been <b>' . $definedOrDeleted . ' outside</b> unit test class or function! Or, inside of "setUpBeforeClass()"!' . PHP_EOL;
        } else {
            //$message .= 'Global variable had been ' . $definedOrDeleted . ' inside unit test class method "' . $testMethodName . '", "setUp()" or "tearDown()"!' . PHP_EOL;
            $message .= 'Global variable had been <b>' . $definedOrDeleted . ' inside</b> unit test class method <b>"' . $testMethodName . '"</b>, "setUp()" or "tearDown()"!' . PHP_EOL;
        }
        $message .= PHP_EOL;
        if (!empty($additionalVariable)) {
            $message .= 'Unit test file (*Test.php) must use public static property instead of use global variable' . PHP_EOL;
        } else if (!empty($deletionalVariable)) {
            $message .= 'Unit test file (*Test.php) must not delete global variable by "unset()".' . PHP_EOL;
        }
        $message .= "\t" . 'because "php" version 5.3.0 cannot detect ' . $definedOrDeleted . ' global variable except unit test file realtime.' . PHP_EOL
            . 'Or, unit test class method must use autoload by "new" instead of include "*.php" file which ' . $definesOrDeletes . ' static status' . PHP_EOL
            //. "\t" . 'because "php" version 5.3.0 cannot detect an included static status ' . $definitionOrDeletion . ' realtime.</b></pre>';
            . "\t" . 'because "php" version 5.3.0 cannot detect an included static status ' . $definitionOrDeletion . ' realtime.</pre>';
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
                self::_storeVariables(array (), $backup, parent::$staticAttributes[$declaredClassName]);
            }

            // Checks existence of local static variable of static class method.
            foreach ($classReflection->getMethods(ReflectionMethod::IS_STATIC) as $methodReflection) {
                if ($methodReflection->class === $declaredClassName) {
                    $result = $methodReflection->getStaticVariables();
                    // If static variable has been existing.
                    if (!empty($result)) {
                        B::exitForError(
                            PHP_EOL
                            . 'Code which is tested must use private static property instead of use local static variable of static class method' . PHP_EOL
                            . 'because "php" version 5.3.0 cannot restore its value.' . PHP_EOL
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
            self::_restoreVariables($properties, $staticAttributes);
            foreach ($staticAttributes as $name => $value) {
                $reflector = new ReflectionProperty($className, $name);
                $reflector->setAccessible(TRUE);
                $reflector->setValue($properties[$name]);
            }
        }
    }

}

?>
