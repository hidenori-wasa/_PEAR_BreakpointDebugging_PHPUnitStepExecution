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
 * @package    PHPUnit
 * @subpackage Util
 * @author     Sebastian Bergmann <sebastian@phpunit.de>
 * @copyright  2001-2013 Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       http://www.phpunit.de/
 * @since      File available since Release 3.4.0
 */
use \BreakpointDebugging as B;
use \BreakpointDebugging_PHPUnit as BU;

/**
 * Utility for static state. Supports "php" version 5.3.0 since then.
 *
 * @package    PHPUnit
 * @subpackage Util
 * @author     Sebastian Bergmann <sebastian@phpunit.de>
 * @copyright  2001-2013 Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.6.12
 * @link       http://www.phpunit.de/
 * @since      Class available since Release 3.4.0
 */
class BreakpointDebugging_PHPUnit_UtilGlobalState // extends \PHPUnit_Util_GlobalState
{
    /**
     * @var int Previous declared classes number.
     */
    private static $_prevDeclaredClassesNumber = 0;

    /**
     * Stores variables.
     *
     * NOTICE: Reference setting inside "__construct()" is not broken by "unset()" because it is reset.
     *         However, reference setting inside file scope of "autoload or including" is broken by "unset()".
     * @param array $blacklist            The list to except from variables storing.
     * @param array $variables            Array variable to store.
     * @param array &$variableRefsStorage Variable references storage.
     * @param array &$variablesStorage    Variables storage.
     * @param bool  $isGlobal             Is this the global variables?
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    private static function _storeVariables($blacklist, $variables, &$variableRefsStorage, &$variablesStorage, $isGlobal = false)
    {
        B::assert(is_array($blacklist));
        B::assert(is_array($variables));
        B::assert(is_array($variableRefsStorage));
        B::assert(is_array($variablesStorage));

        if ($isGlobal) {
            // Deletes "unset()" variable from storage because we can do "unset()" except property definition.
            foreach ($variablesStorage as $key => $value) {
                if (!array_key_exists($key, $variables)) {
                    unset($variablesStorage[$key]);
                }
            }
        }

        // Stores new variable by autoload or initialization.
        foreach ($variables as $key => &$value) {
            if (in_array($key, $blacklist)
                || array_key_exists($key, $variablesStorage)
                || $value instanceof Closure
            ) {
                continue;
            }
            $variableRefsStorage[$key] = &$value;
            $variablesStorage[$key] = $value;
        }
    }

    /**
     * Restores variables.
     *
     * @param array &$variables          Variables to restore.
     * @param array $variableRefsStorage Variable references storage.
     * @param array $variablesStorage    Variables storage.
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    private static function _restoreVariables(array &$variables, array $variableRefsStorage, array $variablesStorage)
    {
        $variables = array ();
        if (empty($variablesStorage)) {
            return;
        }
        foreach ($variablesStorage as $key => $value) {
            if (array_key_exists($key, $variableRefsStorage)) {
                // Copies reference of storage to reference of variable itself.
                $variables[$key] = &$variableRefsStorage[$key];
            }
            // Copies value of storage to variable.
            $variables[$key] = $value;
        }
    }

    /**
     * Checks definition, deletion and change violation of global variables and global variable references.
     *
     * @param array $globalRefs          Global variable's references storage.
     * @param array $globals             Global variables storage.
     * @param bool  $doesDefinitionCheck Does definition check?
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    static function checkGlobals($globalRefs, $globals, $doesDefinitionCheck = false)
    {
        B::limitAccess('BreakpointDebugging/PHPUnit/FrameworkTestCase.php', true);

        $isError = false;
        if ($doesDefinitionCheck) {
            // Checks definition.
            $definitionGlobalVariables = array_diff_key($GLOBALS, $globalRefs);
            if (!empty($definitionGlobalVariables)) {
                $isError = true;
                $defineOrDelete = 'define';
            }

            $message2 = "\t" . 'The bootstrap file.' . PHP_EOL;
            $message2 .= "\t" . 'Code which is executed at autoload of unit test file (*Test.php).' . PHP_EOL;
            $message2 .= "\t" . '"setUpBeforeClass()".' . PHP_EOL;
        } else {
            $message2 = "\t" . 'During autoload.' . PHP_EOL;
        }
        $message2 .= '</b>' . PHP_EOL;

        if (!$isError) {
            // Checks deletion.
            $deletionGlobalVariables = array_diff_key($globalRefs, $GLOBALS);
            if (!empty($deletionGlobalVariables)) {
                $isError = true;
                $defineOrDelete = 'delete';
            }
        }

        if ($isError) {
            // Displays definition or deletion error.
            $message = '<b>';
            $message .= 'Global variable has been ' . $defineOrDelete . 'd in the following place!' . PHP_EOL;
            $message .= $message2;
            $message .= 'We must not ' . $defineOrDelete . ' global variable in the above place.' . PHP_EOL;
            $message .= 'Because "php" version 5.3.0 cannot detect ' . $defineOrDelete . 'd global variable realtime.';
            B::windowHtmlAddition(BU::UNIT_TEST_WINDOW_NAME, 'pre', 0, $message);
            exit;
        }

        // Checks global variables values and global variables child element references.
        unset($globals['GLOBALS']);
        foreach ($globals as $key => $value) {
            if ($value !== $GLOBALS[$key]) {
                $isError = true;
                $message3 = 'value';
                break;
            }
        }
        if (!$isError) {
            // Checks global variables references.
            unset($globalRefs['GLOBALS']);
            foreach ($globalRefs as $key => &$value) {
                if ($key === 'GLOBALS') {
                    continue;
                }
                $cmpArray1 = array (&$value);
                $cmpArray2 = array (&$GLOBALS[$key]);
                if ($cmpArray1 !== $cmpArray2) {
                    $isError = true;
                    $message3 = 'reference';
                    break;
                }
            }
        }
        if ($isError) {
            // Displays error of overwritten value or overwritten reference.
            $message = '<b>';
            $message .= 'Global variable ' . $message3 . ' has been overwritten in the following place!' . PHP_EOL;
            $message .= $message2;
            $message .= 'We must not overwrite global variable ' . $message3 . ' in the above place.' . PHP_EOL;
            $message .= 'Because "php" version 5.3.0 cannot detect overwritten global variable ' . $message3 . ' realtime.';
            B::windowHtmlAddition(BU::UNIT_TEST_WINDOW_NAME, 'pre', 0, $message);
            exit;
        }
    }

    /**
     * Stores global variables.
     *
     * @param array &$globalRefs Global variable's references storage.
     * @param array &$globals    Global variables storage.
     * @param array $blacklist   The list to except from storage global variables.
     * @param bool  $isSnapshot  Is this snapshot?
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    static function storeGlobals(array &$globalRefs, array &$globals, array $blacklist, $isSnapshot = false)
    {
        B::limitAccess(
            array ('BreakpointDebugging_PHPUnit.php',
            'BreakpointDebugging/PHPUnit/FrameworkTestCase.php'
            ), true
        );

        if ($isSnapshot) {
            $globalRefs = array ();
            $globals = array ();
        }
        self::_storeVariables($blacklist, $GLOBALS, $globalRefs, $globals, true);
    }

    /**
     * Restores global variables.
     *
     * @param array $globalRefs Global variable's references storage.
     * @param array $globals    Global variables storage.
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    static function restoreGlobals($globalRefs, $globals)
    {
        B::limitAccess(
            array ('BreakpointDebugging_PHPUnit.php',
            'BreakpointDebugging/PHPUnit/FrameworkTestCase.php'
            ), true
        );

        unset($globalRefs['GLOBALS']);
        unset($globals['GLOBALS']);
        self::_restoreVariables($GLOBALS, $globalRefs, $globals);

        $_COOKIE = &$GLOBALS['_COOKIE'];
        $_ENV = &$GLOBALS['_ENV'];
        $_FILES = &$GLOBALS['_FILES'];
        $_GET = &$GLOBALS['_GET'];
        $_POST = &$GLOBALS['_POST'];
        $_REQUEST = &$GLOBALS['_REQUEST'];
        $_SERVER = &$GLOBALS['_SERVER'];
        $GLOBALS['GLOBALS'] = &$GLOBALS;
    }

    /**
     * Is it unit test class?
     *
     * @param string $declaredClassName
     *
     * @return bool Is it unit test class?
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    static function isUnitTestClass($declaredClassName)
    {
        B::limitAccess(
            array ('BreakpointDebugging/PHPUnit/UtilGlobalState.php',
            'BreakpointDebugging/PHPUnit/FrameworkTestCase.php'
            ), true
        );

        set_error_handler('\BreakpointDebugging::handleError', 0);
        // Excepts unit test classes.
        if (preg_match('`^ BreakpointDebugging_PHPUnit_UtilGlobalState | (PHP (Unit | (_ (CodeCoverage | Invoker | (T (imer | oken_Stream))))) | File_Iterator | sfYaml | Text_Template )`xXi', $declaredClassName) === 1
            || @is_subclass_of($declaredClassName, 'PHPUnit_Framework_Test')
        ) {
            restore_error_handler();
            return true;
        }
        restore_error_handler();
        return false;
    }

    /**
     * Checks the change violation of static properties and static property child element references.
     *
     * @param array $staticProperties Static properties storage.
     * @param bool  $forAutoload      For autoload?
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    static function checkProperties($staticProperties, $forAutoload = true)
    {
        B::limitAccess('BreakpointDebugging/PHPUnit/FrameworkTestCase.php', true);

        if ($forAutoload) {
            $message2 = "\t" . 'During autoload.' . PHP_EOL;
        } else {
            $message2 = "\t" . 'The bootstrap file.' . PHP_EOL;
            $message2 .= "\t" . 'Code which is executed at autoload of unit test file (*Test.php).' . PHP_EOL;
            $message2 .= "\t" . '"setUpBeforeClass()".' . PHP_EOL;
            $message2 .= "\t" . '"setUp()".' . PHP_EOL;
        }
        $message2 .= '</b>' . PHP_EOL;

        // Scans the declared classes.
        $declaredClasses = get_declared_classes();
        $currentDeclaredClassesNumber = count($declaredClasses);
        for ($key = $currentDeclaredClassesNumber - 1; $key >= 0; $key--) {
            $declaredClassName = $declaredClasses[$key];
            // Excepts unit test classes.
            if (self::isUnitTestClass($declaredClassName)
                || $declaredClassName === 'BreakpointDebugging'
                || $declaredClassName === 'BreakpointDebugging_InAllCase'
                || $declaredClassName === 'BreakpointDebugging_PHPUnit'
                || $declaredClassName === 'BreakpointDebugging_PHPUnit_UtilGlobalState'
                || $declaredClassName === 'PEAR_Exception'
            ) {
                continue;
            }
            // Class reflection.
            $classReflection = new \ReflectionClass($declaredClassName);
            // If it is not user defined class.
            if (!$classReflection->isUserDefined()) {
                continue;
            }
            // If class was declared inside unit test code.
            if (!array_key_exists($declaredClassName, $staticProperties)) {
                continue;
            }
            // Static properties reflection.
            $staticPropertiesOfClass = $staticProperties[$declaredClassName];
            foreach ($classReflection->getProperties(\ReflectionProperty::IS_STATIC) as $property) {
                // If it is not property of base class. Because reference variable cannot be extended.
                if ($property->class === $declaredClassName) {
                    $propertyName = $property->name;
                    // If static property does not exist in black list (PHPUnit_Framework_TestCase::$backupStaticAttributesBlacklist).
                    if (!isset($blacklist[$declaredClassName])
                        || !in_array($propertyName, $blacklist[$declaredClassName])
                    ) {
                        $property->setAccessible(true);
                        $propertyValue = $property->getValue();
                        if (!$propertyValue instanceof Closure) {
                            // Checks static property and static property child element references.
                            if (B::clearRecursiveArrayElement($staticPropertiesOfClass[$propertyName]) === B::clearRecursiveArrayElement($propertyValue)) {
                                continue;
                            }
                            $message = '<b>';
                            $message .= 'In "class ' . $declaredClassName . '".' . PHP_EOL;
                            $message .= '"$' . $propertyName . '" static property or reference has been overwritten in the following place!' . PHP_EOL;
                            $message .= $message2;
                            $message .= 'We must not overwrite static property or reference in the above place.' . PHP_EOL;
                            $message .= 'Because "php" version 5.3.0 cannot detect overwritten static property or reference realtime.';
                            B::windowHtmlAddition(BU::UNIT_TEST_WINDOW_NAME, 'pre', 0, $message);
                            exit;
                        }
                    }
                }
            }
        }
    }

    /**
     * Stores static properties.
     *
     * @param array &$staticProperties Static properties storage.
     * @param array $blacklist         The list to except from static properties storage.
     * @param bool  $isSnapshot        Is this snapshot?
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    static function storeProperties(array &$staticProperties, array $blacklist, $isSnapshot = false)
    {
        B::limitAccess(
            array ('BreakpointDebugging_PHPUnit.php',
            'BreakpointDebugging/PHPUnit/FrameworkTestCase.php'
            ), true
        );

        if ($isSnapshot) {
            $staticProperties = array ();
            $prevDeclaredClassesNumber = 0;
        } else {
            $prevDeclaredClassesNumber = self::$_prevDeclaredClassesNumber;
        }
        // Scans the declared classes.
        $declaredClasses = get_declared_classes();
        $currentDeclaredClassesNumber = count($declaredClasses);
        for ($key = $currentDeclaredClassesNumber - 1; $key >= $prevDeclaredClassesNumber; $key--) {
            $declaredClassName = $declaredClasses[$key];
            // Excepts unit test classes.
            if (self::isUnitTestClass($declaredClassName)) {
                continue;
            }
            // Class reflection.
            $classReflection = new \ReflectionClass($declaredClassName);
            // If it is not user defined class.
            if (!$classReflection->isUserDefined()) {
                continue;
            }

            $storage = array ();
            // Static properties reflection.
            foreach ($classReflection->getProperties(\ReflectionProperty::IS_STATIC) as $property) {
                // If it is not property of base class. Because reference variable cannot be extended.
                if ($property->class === $declaredClassName) {
                    $propertyName = $property->name;
                    // If static property does not exist in black list (PHPUnit_Framework_TestCase::$backupStaticAttributesBlacklist).
                    if (!isset($blacklist[$declaredClassName])
                        || !in_array($propertyName, $blacklist[$declaredClassName])
                    ) {
                        $property->setAccessible(TRUE);
                        $propertyValue = $property->getValue();
                        if (!$propertyValue instanceof Closure) {
                            $storage[$propertyName] = $propertyValue;
                        }
                    }
                }
            }

            if (!empty($storage)) {
                $staticProperties[$declaredClassName] = array ();
                // Stores static properties.
                $dummy = array ();
                self::_storeVariables(array (), $storage, $dummy, $staticProperties[$declaredClassName]);
            }
        }
        self::$_prevDeclaredClassesNumber = $currentDeclaredClassesNumber;
    }

    /**
     * Restores static properties.
     *
     * @param array $staticPropertiesStorage Static properties storage.
     *
     * @return void
     * @author Hidenori Wasa <public@hidenori-wasa.com>
     */
    static function restoreProperties($staticPropertiesStorage)
    {
        B::limitAccess(
            array ('BreakpointDebugging_PHPUnit.php',
            'BreakpointDebugging/PHPUnit/FrameworkTestCase.php'
            ), true
        );

        foreach ($staticPropertiesStorage as $className => $staticProperties) {
            $properties = array ();
            self::_restoreVariables($properties, array (), $staticProperties);
            foreach ($staticProperties as $name => $value) {
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
        B::limitAccess('BreakpointDebugging_PHPUnit.php', true);

        $componentFullPath = \BreakpointDebugging_PHPUnit_UtilFilesystem::streamResolveIncludePath('BreakpointDebugging/Component/') . DIRECTORY_SEPARATOR;
        $definedFunctionsName = get_defined_functions();
        foreach ($definedFunctionsName['user'] as $definedFunctionName) {
            $functionReflection = new ReflectionFunction($definedFunctionName);
            $staticVariables = $functionReflection->getStaticVariables();
            // If static variable has been existing.
            if (!empty($staticVariables)) {
                $fileName = $functionReflection->getFileName();
                if (strpos($fileName, $componentFullPath) === 0) {
                    $className = str_replace(array ('\\', '/'), '_', substr($fileName, strlen($componentFullPath)));
                    // Excepts unit test classes.
                    if (self::isUnitTestClass($className)) {
                        continue;
                    }
                }
                echo PHP_EOL
                . 'Code which is tested must use private static property in class method instead of use local static variable in function' . PHP_EOL
                . 'because "php" version 5.3.0 cannot restore its value.' . PHP_EOL
                . "\t" . '<b>FILE: ' . $functionReflection->getFileName() . PHP_EOL
                . "\t" . 'LINE: ' . $functionReflection->getStartLine() . PHP_EOL
                . "\t" . 'FUNCTION: ' . $functionReflection->name . '</b>' . PHP_EOL;
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
        B::limitAccess('BreakpointDebugging_PHPUnit.php', true);

        // Scans the declared classes.
        $declaredClasses = get_declared_classes();
        $currentDeclaredClassesNumber = count($declaredClasses);
        for ($key = $currentDeclaredClassesNumber - 1; $key >= 0; $key--) {
            $declaredClassName = $declaredClasses[$key];
            // Excepts unit test classes.
            if (self::isUnitTestClass($declaredClassName)) {
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
                        echo PHP_EOL
                        . 'Code which is tested must use private static property instead of use local static variable in static class method' . PHP_EOL
                        . 'because "php" version 5.3.0 cannot restore its value.' . PHP_EOL
                        . "\t" . '<b>FILE: ' . $methodReflection->getFileName() . PHP_EOL
                        . "\t" . 'LINE: ' . $methodReflection->getStartLine() . PHP_EOL
                        . "\t" . 'CLASS: ' . $methodReflection->class . PHP_EOL
                        . "\t" . 'METHOD: ' . $methodReflection->name . '</b>' . PHP_EOL;
                    }
                }
            }
        }
    }

}

?>
