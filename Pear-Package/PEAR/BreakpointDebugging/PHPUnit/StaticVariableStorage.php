<?php

/**
 * Static variable storage.
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
use \BreakpointDebugging_Window as BW;

/**
 * Static variable storage.
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
class BreakpointDebugging_PHPUnit_StaticVariableStorage
{
    /**
     * Autoload name to display autoload error at top of stack.
     *
     * @const string
     */
    const AUTOLOAD_NAME = 'BreakpointDebugging_PHPUnit_StaticVariableStorage::displayAutoloadError';

    /**
     * Restoration class name.
     *
     * @var string
     */
    private static $_restorationClassName;

    /**
     * Restoration property name or restoration global variable name.
     *
     * @var string
     */
    private static $_restorationElementName;

    /**
     * Current test class name.
     *
     * @var string
     */
    private static $_currentTestClassName;

    /**
     * Current test class method name.
     *
     * @var string
     */
    private static $_currentTestMethodName;

    /**
     * Memorizes the defined classes.
     *
     * @var array
     */
    private static $_declaredClasses;

    /**
     * Previous declared classes-number storage.
     *
     * @var int
     */
    private static $_prevDeclaredClassesNumberStorage = 0;

    /**
     * Once flag per test file.
     *
     * @var bool
     */
    private static $_onceFlagPerTestFile;

    /**
     * Global variable references.
     *
     * @var array
     */
    private static $_globalRefs = array ();

    /**
     * Global variables.
     *
     * @var array
     */
    private static $_globals = array ();

    /**
     * Static properties.
     *
     * @var array
     */
    private static $_staticProperties = array ();

    /**
     * List to except to store global variable.
     *
     * @var array
     */
    private static $_backupGlobalsBlacklist = array ();

    /**
     * List to except to store static properties values.
     *
     * @var array
     */
    private static $_backupStaticPropertiesBlacklist = array (
        //'BreakpointDebugging_InAllCase' => array ('_callLocations', '_phpUnit'),
        'BreakpointDebugging_InAllCase' => array ('_callLocations'),
        //'BreakpointDebugging_PHPUnit_FrameworkTestCaseSimple' => array ('_phpUnit'),
        'Cache' => array ('_engines'), // "CakePHP" class.
        'CakeLog' => array ('_Collection'), // "CakePHP" class.
    );

    /**
     * Is it unit test class?
     *
     * @var Closure
     */
    private static $_isUnitTestClass;

    /**
     * Initializes this static class.
     *
     * @param Closure $isUnitTestClass Is it unit test class?
     *
     * @return void
     */
    static function initialize($isUnitTestClass)
    {
        B::limitAccess('BreakpointDebugging_PHPUnit.php');

        // "\Closure" object should be static because of function.
        self::$_isUnitTestClass = $isUnitTestClass;
    }

    /**
     * It references "self::$_currentTestClassName".
     *
     * @return string& Reference value.
     */
    static function &refCurrentTestClassName()
    {
        B::limitAccess(
            array (
            'BreakpointDebugging/PHPUnit/FrameworkTestCase.php',
            'BreakpointDebugging/PHPUnit/FrameworkTestCaseSimple.php',
            ), true
        );

        return self::$_currentTestClassName;
    }

    /**
     * It references "self::$_currentTestMethodName".
     *
     * @return string& Reference value.
     */
    static function &refCurrentTestMethodName()
    {
        B::limitAccess(
            array (
            'BreakpointDebugging/PHPUnit/FrameworkTestCase.php',
            'BreakpointDebugging/PHPUnit/FrameworkTestCaseSimple.php',
            ), true
        );

        return self::$_currentTestMethodName;
    }

    /**
     * Returns reference of flag of once per test file.
     *
     * @return bool& Reference value.
     */
    static function &refOnceFlagPerTestFile()
    {
        B::limitAccess(
            array ('BreakpointDebugging_PHPUnit.php',
            'BreakpointDebugging/PHPUnit/FrameworkTestCase.php',
            'BreakpointDebugging/PHPUnit/FrameworkTestCaseSimple.php',
            ), true
        );

        return self::$_onceFlagPerTestFile;
    }

    /**
     * It references "self::$_globalRefs".
     *
     * @return mixed& Reference value.
     */
    static function &refGlobalRefs()
    {
        B::limitAccess(
            array ('BreakpointDebugging_PHPUnit.php',
            'BreakpointDebugging/PHPUnit/FrameworkTestCase.php',
            'BreakpointDebugging/PHPUnit/FrameworkTestCaseSimple.php',
            ), true
        );

        return self::$_globalRefs;
    }

    /**
     * It references "self::$_globals".
     *
     * @return mixed& Reference value.
     */
    static function &refGlobals()
    {
        B::limitAccess(
            array ('BreakpointDebugging_PHPUnit.php',
            'BreakpointDebugging/PHPUnit/FrameworkTestCase.php',
            'BreakpointDebugging/PHPUnit/FrameworkTestCaseSimple.php',
            ), true
        );

        return self::$_globals;
    }

    /**
     * It references "self::$_staticProperties".
     *
     * @return mixed& Reference value.
     */
    static function &refStaticProperties()
    {
        B::limitAccess(
            array ('BreakpointDebugging_PHPUnit.php',
            'BreakpointDebugging/PHPUnit/FrameworkTestCase.php',
            'BreakpointDebugging/PHPUnit/FrameworkTestCaseSimple.php',
            ), true
        );

        return self::$_staticProperties;
    }

    /**
     * It references "self::$_backupGlobalsBlacklist".
     *
     * @return mixed& Reference value.
     */
    static function &refBackupGlobalsBlacklist()
    {
        B::limitAccess('BreakpointDebugging/PHPUnit/FrameworkTestCase.php', true);

        return self::$_backupGlobalsBlacklist;
    }

    /**
     * It references "self::$_backupStaticPropertiesBlacklist".
     *
     * @return mixed& Reference value.
     */
    static function &refBackupStaticPropertiesBlacklist()
    {
        B::limitAccess(
            array (
            'BreakpointDebugging_PHPUnit.php',
            'BreakpointDebugging/PHPUnit/FrameworkTestCase.php'
            ), true
        );

        return self::$_backupStaticPropertiesBlacklist;
    }

    /**
     * Display error.
     *
     * @param bool $isInclude  Is include?
     * @param bool $classNames The class name or the classes names.
     *
     * @return void
     */
    private static function _displayError($isInclude, $classNames)
    {
        if ($isInclude) {
            $messageA = '"include"';
            $messageB = 'file path';
        } else {
            $messageA = 'Autoload';
            $messageB = 'code';
        }
        $message = $messageA . ' must not be executed during "setUp()", "test*()" or "tearDown()" because a class is declared newly.' . PHP_EOL;
        $message .= PHP_EOL;
        $message .= 'ERROR TEST CLASS: "class ' . self::$_currentTestClassName . '".' . PHP_EOL;
        $message .= 'ERROR TEST CLASS METHOD: "setUp()", "' . self::$_currentTestMethodName . '()" or "tearDown()".' . PHP_EOL;
        $message .= PHP_EOL;
        if ($isInclude) {
            $message .= '<span style="color:aqua">';
            foreach ($classNames as $className) {
                $message .= 'Please, search project files by "<span style="color:orange">class ' . $className . '</span>" for "&lt;include path of "' . $className . '" class>".' . PHP_EOL;
            }
            $message .= '</span>';
        }
        $message .= PHP_EOL;
        $message .= 'Code the following into "';
        $message .= '<span style="color:aqua">';
        $message .= self::$_currentTestClassName . '::setUpBeforeClass()';
        $message .= '</span>';
        $message .= '".' . PHP_EOL;
        $message .= 'Before:' . PHP_EOL;
        $message .= '    parent::setUpBeforeClass();' . PHP_EOL;
        $message .= 'After:' . PHP_EOL;
        $message .= '<span style="color:aqua">';
        if ($isInclude) {
            foreach ($classNames as $className) {
                $message .= '    \BreakpointDebugging_PHPUnit::includeClass(\'&lt;include path of "' . $className . '" class>\');' . PHP_EOL;
            }
        } else {
            $className = $classNames;
            $message .= '    \BreakpointDebugging_PHPUnit::loadClass(\'' . $className . '\');' . PHP_EOL;
        }
        $message .= '</span>';
        $message .= '    parent::setUpBeforeClass();' . PHP_EOL;
        BW::exitForError($message);
    }

    /**
     * Prohibits autoload not to change static status by autoload during "setUp()", "test*()" or "tearDown()".
     *
     * @param string $className The class name for autoload of "new", "extends", "static class method call", "class_implements()", "class_exists()", "class_parents()", "class_uses()", "is_a()" or "is_subclass_of()" etc.
     *
     * @return void
     */
    static function displayAutoloadError($className)
    {
        static $isAutoloadDuringAutoload = false, $onceFlag = false, $serchClassName = '';

        // If this is not autoload during autoload.
        if (!$isAutoloadDuringAutoload) {
            // Skips "PHPUnit" pear package classes.
            if (stripos($className, 'PHPUnit_Framework_') === 0) {
                return;
            }
            // Skips "CakePHP" class.
            if (BREAKPOINTDEBUGGING_IS_CAKE //
                && ($className === 'FileLog' //
                || $className === 'BaseLog') //
            ) {
                return;
            }
            if ($onceFlag) {
                B::exitForError('Autoload error must be fixed per a bug. Double click call stack window about "' . $serchClassName . '".');
            }
            $onceFlag = true;
            $serchClassName = $className;
            $isAutoloadDuringAutoload = true;
            // Loads classes files to continue step execution.
            spl_autoload_call($className);
            // If class file has been loaded completely including dependency files.
            $isAutoloadDuringAutoload = false;
            // If the class does not exist.
            if (!class_exists($className)) {
                return false;
            }
            // If this autoload class method was called.
            self::_displayError(false, $className);
        }
    }

    /**
     * Restores an object.
     *
     * @param object $dest     Destination variable.
     * @param array  $src      Source value.
     * @param bool   $forCheck For check?
     *
     * @return void
     */
    private static function _restoreObject($dest, &$src, $forCheck)
    {
        B::assert(is_object($dest));
        B::assert(is_array($src));

        $objectReflection = new ReflectionObject($dest);
        if (!$objectReflection->isUserDefined()) {
            return;
        }
        // Skips an object of "\stdClass" which judges type.
        list(, $className) = each($src);
        $className = $className->scalar;
        if ($className !== $objectReflection->name) {
            throw new \BreakpointDebugging_ErrorException('"\\' . $className . '" class object was changed the type.');
        }
        foreach ($objectReflection->getProperties() as $propertyReflection) {
            // Excepts static property.
            if ($propertyReflection->isStatic()) {
                continue;
            }
            $propertyReflection->setAccessible(true);
            list(, $value) = each($src);
            if ($forCheck //
                && $dest !== $value //
            ) {
                B::exitForError('"' . self::$_restorationClassName . self::$_restorationElementName . '" must not be changed before storing.');
            }
            // Copies an array elements recursively.
            // Or, copies an object ID.
            // Or, copies a value of other type.
            $propertyReflection->setValue($dest, $value);
            if (is_array($value)) {
                // Delivers "$value" as array copy recursively.
                self::_iterateRestorationArrayRecursively($value, $src, $forCheck);
            } else if (is_object($value)) {
                // Delivers "$value" as object ID copy.
                self::_restoreObject($value, $src, $forCheck);
            }
        }
    }

    /**
     * Stores an object.
     *
     * @param array  $dest Destination variable.
     * @param object $src  Source value.
     *
     * @return void
     */
    private static function _storeObject(&$dest, $src)
    {
        B::assert(is_array($dest));
        B::assert(is_object($src));

        $objectReflection = new ReflectionObject($src);
        if (!$objectReflection->isUserDefined()) {
            return;
        }
        // Registers a class name as object type.
        $dest[] = (object) $objectReflection->name;
        foreach ($objectReflection->getProperties() as $propertyReflection) {
            // Excepts static property.
            if ($propertyReflection->isStatic()) {
                continue;
            }
            $propertyReflection->setAccessible(true);
            $value = $propertyReflection->getValue($src);
            // Copies an array elements recursively.
            // Or, copies an object ID.
            // Or, copies a value of other type.
            $dest[] = $value;
            if (is_array($value)) {
                self::_iterateStoreArrayRecursively($dest, $value);
            } else if (is_object($value)) {
                self::_storeObject($dest, $value);
            }
        }
    }

    /**
     * Iterates restoration array recursively.
     *
     * @param array $iterateArray Iteration array.
     * @param array $src          Source array to restore.
     * @param bool  $forCheck     For check?
     *
     * @return void
     */
    private static function _iterateRestorationArrayRecursively($iterateArray, &$src, $forCheck)
    {
        B::assert(is_array($iterateArray));
        B::assert(is_array($src));

        foreach ($iterateArray as $key => $value) {
            if (is_array($value)) {
                if ($key === 'GLOBALS') {
                    continue;
                }
                // Delivers "$value" as array copy recursively.
                self::_iterateRestorationArrayRecursively($value, $src, $forCheck);
            } else if (is_object($value)) {
                // Delivers "$value" as object ID copy.
                self::_restoreObject($value, $src, $forCheck);
            }
        }
    }

    /**
     * Iterates a store array recursively.
     *
     * @param array $dest         Destination variable.
     * @param array $iterateArray Iteration array.
     *
     * @return void
     */
    private static function _iterateStoreArrayRecursively(&$dest, $iterateArray)
    {
        B::assert(is_array($dest));
        B::assert(is_array($iterateArray));

        foreach ($iterateArray as $key => $value) {
            if (is_array($value)) {
                if ($key === 'GLOBALS') {
                    continue;
                }
                self::_iterateStoreArrayRecursively($dest, $value);
            } else if (is_object($value)) {
                self::_storeObject($dest, $value);
            }
        }
    }

    /**
     * Restores a value.
     *
     * @param mixed $dest     Destination variable.
     * @param array $src      Source value.
     * @param bool  $forCheck For check?
     * @param bool  $isGlobal Is this a global variable?
     *
     * @return void
     */
    private static function _restoreValue(&$dest, $src, $forCheck, $isGlobal)
    {
        B::assert(is_array($src));

        reset($src);
        list(, $value) = each($src);

        if ($forCheck //
            && $isGlobal //
            && $dest !== $value //
        ) {
            B::assert(self::$_restorationClassName === '');
            B::exitForError('"' . self::$_restorationElementName . '" must not be changed before storing.');
        }

        // Copies an array elements recursively.
        // Or, Copies an object ID.
        // Or, Copies a value of other type.
        $dest = $value;

        if (is_array($value)) {
            // Delivers "$value" as array copy recursively.
            self::_iterateRestorationArrayRecursively($value, $src, $forCheck);
        } else if (is_object($value)) {
            // Delivers "$value" as object ID copy.
            self::_restoreObject($value, $src, $forCheck);
        }
        $result = each($src);
        B::assert($result === false);
    }

    /**
     * Stores a value.
     *
     * @param mixed $dest Destination variable.
     * @param mixed $src  Source value.
     *
     * @return void
     */
    private static function _storeValue(&$dest, $src)
    {
        B::checkRecursiveDataError($src);
        // Copies an array elements recursively.
        // Or, copies an object ID.
        // Or, copies a value of other type.
        $dest[] = $src;
        if (is_array($src)) {
            self::_iterateStoreArrayRecursively($dest, $src);
        } else if (is_object($src)) {
            self::_storeObject($dest, $src);
        }
    }

    /**
     * Stores variables.
     *
     * NOTICE: Reference setting inside "__construct()" is not broken by "unset()" because it is reset.
     *         However, reference setting inside file scope of "autoload or including" is broken by "unset()".
     *
     * @param array $blacklist           The list to except from variables storing.
     * @param array $variables           Array variable to store.
     * @param array $variableRefsStorage Variable references storage.
     * @param array $variablesStorage    Variables storage.
     * @param bool  $isGlobal            Is this the global variables?
     *
     * @return void
     */
    private static function _storeVariables($blacklist, $variables, &$variableRefsStorage, &$variablesStorage, $isGlobal = false)
    {
        B::assert(is_array($blacklist));
        B::assert(is_array($variables));
        B::assert(is_array($variableRefsStorage));
        B::assert(is_array($variablesStorage));

        if ($isGlobal) {
            // Deletes the deleted global variable by "unset()" from storage.
            foreach ($variablesStorage as $key => $value) {
                if (!array_key_exists($key, $variables)) {
                    unset($variablesStorage[$key]);
                }
            }
        }

        // Stores the created variables by autoload or "include".
        foreach ($variables as $key => &$value) {
            if (in_array($key, $blacklist) //
                || array_key_exists($key, $variablesStorage) //
            ) {
                continue;
            }
            $variableRefsStorage[$key] = &$value;
            self::_storeValue($variablesStorage[$key], $value);
        }
    }

    /**
     * Restores variables.
     *
     * @param array $variables           Variables to restore.
     * @param array $variableRefsStorage Variable references storage. (Reference copy needs array passed by reference because array copy creates different reference at reference copy of array element.)
     * @param array $variablesStorage    Variables storage.
     * @param bool  $forCheck            For check?
     *
     * @return void
     */
    private static function _restoreVariables(array &$variables, array &$variableRefsStorage, array $variablesStorage, $forCheck)
    {
        if (empty($variablesStorage)) {
            return;
        }
        if (!$forCheck // If restoring.
            && $variables !== array () // If global variables.
        ) {
            // Deletes added global variables.
            foreach (array_diff_key($variables, $variablesStorage) as $key => $value) {
                if ($key === 'GLOBALS') {
                    continue;
                }
                unset($variables[$key]);
            }
        }
        foreach ($variablesStorage as $key => $value) {
            if ($key === 'GLOBALS') {
                continue;
            }
            self::$_restorationElementName = '$' . $key;
            $isGlobal = false;
            // If stored global variable.
            if (array_key_exists($key, $variableRefsStorage)) {
                // Checks a reference change error.
                if ($forCheck) {
                    if (array (&$variables[$key]) !== array (&$variableRefsStorage[$key])) {
                        B::assert(self::$_restorationClassName === '');
                        B::exitForError('"&' . self::$_restorationElementName . '" must not be changed before storing.');
                    }
                } else {
                    // Copies storage reference to variable.
                    $variables[$key] = &$variableRefsStorage[$key];
                }
                $isGlobal = true;
            }
            // Copies storage value to variable.
            self::_restoreValue($variables[$key], $value, $forCheck, $isGlobal);
        }
    }

    /**
     * Stores global variables.
     *
     * @param array $globalRefs Global variable's references storage.
     * @param array $globals    Global variables storage.
     * @param array $blacklist  The list to except from storage global variables.
     * @param bool  $isSnapshot Is this snapshot?
     *
     * @return void
     */
    static function storeGlobals(array &$globalRefs, array &$globals, array $blacklist, $isSnapshot = false)
    {
        B::limitAccess(
            array ('BreakpointDebugging/PHPUnit/StaticVariableStorage.php',
            'BreakpointDebugging_PHPUnit.php',
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
     * @param array $globalRefs Global variable's references storage. (Reference copy needs array passed by reference because array copy creates different reference at reference copy of array element.)
     * @param array $globals    Global variables storage.
     * @param bool  $forCheck   For check?
     *
     * @return void
     */
    static function restoreGlobals(&$globalRefs, $globals, $forCheck = false)
    {
        B::limitAccess(
            array ('BreakpointDebugging/PHPUnit/FrameworkTestCase.php',
            'BreakpointDebugging/PHPUnit/FrameworkTestCaseSimple.php',
            'BreakpointDebugging/PHPUnit/StaticVariableStorage.php',
            'BreakpointDebugging_PHPUnit.php',
            ), true
        );

        unset($globalRefs['GLOBALS']);
        unset($globals['GLOBALS']);
        self::$_restorationClassName = '';
        self::_restoreVariables($GLOBALS, $globalRefs, $globals, $forCheck);

        if ($forCheck) {
            if (array (&$_COOKIE) !== array (&$GLOBALS['_COOKIE'])) {
                B::exitForError('"&$_COOKIE" must not be changed before storing.');
            }
            if (array (&$_ENV) !== array (&$GLOBALS['_ENV'])) {
                B::exitForError('"&$_ENV" must not be changed before storing.');
            }
            if (array (&$_FILES) !== array (&$GLOBALS['_FILES'])) {
                B::exitForError('"&$_FILES" must not be changed before storing.');
            }
            if (array (&$_GET) !== array (&$GLOBALS['_GET'])) {
                B::exitForError('"&$_GET" must not be changed before storing.');
            }
            if (array (&$_POST) !== array (&$GLOBALS['_POST'])) {
                B::exitForError('"&$_POST" must not be changed before storing.');
            }
            if (array (&$_REQUEST) !== array (&$GLOBALS['_REQUEST'])) {
                B::exitForError('"&$_REQUEST" must not be changed before storing.');
            }
            if (array (&$_SERVER) !== array (&$GLOBALS['_SERVER'])) {
                B::exitForError('"&$_SERVER" must not be changed before storing.');
            }
            // "$GLOBALS['GLOBALS']" has been deleted for restoring.
        } else {
            $_COOKIE = &$GLOBALS['_COOKIE'];
            $_ENV = &$GLOBALS['_ENV'];
            $_FILES = &$GLOBALS['_FILES'];
            $_GET = &$GLOBALS['_GET'];
            $_POST = &$GLOBALS['_POST'];
            $_REQUEST = &$GLOBALS['_REQUEST'];
            $_SERVER = &$GLOBALS['_SERVER'];
            $GLOBALS['GLOBALS'] = &$GLOBALS;
        }
    }

    /**
     * Stores static properties.
     *
     * @param array $staticProperties Static properties storage.
     * @param array $blacklist        The list to except from static properties storage.
     * @param bool  $isSnapshot       Is this snapshot?
     *
     * @return void
     */
    static function storeProperties(array &$staticProperties, array $blacklist, $isSnapshot = false)
    {
        B::limitAccess(
            array ('BreakpointDebugging/PHPUnit/StaticVariableStorage.php',
            'BreakpointDebugging_PHPUnit.php',
            ), true
        );

        if ($isSnapshot) {
            $staticProperties = array ();
            $prevDeclaredClassesNumber = 0;
        } else {
            $prevDeclaredClassesNumber = self::$_prevDeclaredClassesNumberStorage;
        }
        // Memorizes the declared classes.
        $declaredClasses = self::$_declaredClasses = get_declared_classes();
        $currentDeclaredClassesNumber = count($declaredClasses);
        // Copies property to local variable for closure function call.
        $isUnitTestClass = self::$_isUnitTestClass;
        for ($key = $currentDeclaredClassesNumber - 1; $key >= $prevDeclaredClassesNumber; $key--) {
            $declaredClassName = $declaredClasses[$key];
            // Excepts unit test classes.
            if ($isUnitTestClass($declaredClassName)) {
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
                    if (!isset($blacklist[$declaredClassName]) //
                        || !in_array($propertyName, $blacklist[$declaredClassName]) //
                    ) {
                        $property->setAccessible(true);
                        $storage[$propertyName] = $property->getValue();
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

        self::$_prevDeclaredClassesNumberStorage = $currentDeclaredClassesNumber;
    }

    /**
     * Restores static properties.
     *
     * @param array $staticPropertiesStorage Static properties storage.
     * @param bool  $forCheck                For check?
     *
     * @return void
     */
    static function restoreProperties($staticPropertiesStorage, $forCheck = false)
    {
        B::limitAccess(
            array ('BreakpointDebugging/PHPUnit/FrameworkTestCase.php',
            'BreakpointDebugging/PHPUnit/FrameworkTestCaseSimple.php',
            'BreakpointDebugging/PHPUnit/StaticVariableStorage.php',
            'BreakpointDebugging_PHPUnit.php',
            ), true
        );

        foreach ($staticPropertiesStorage as $className => $staticProperties) {
            $properties = array ();
            self::$_restorationClassName = $className . '::';
            $dummy = array ();
            self::_restoreVariables($properties, $dummy, $staticProperties, $forCheck);
            foreach ($staticProperties as $name => $value) {
                $reflector = new ReflectionProperty($className, $name);
                $reflector->setAccessible(true);
                if ($forCheck //
                    && $reflector->getValue() !== $properties[$name] //
                ) {
                    B::exitForError('"' . $className . '::$' . $name . '" must not be changed before storing.');
                }
                $reflector->setValue($properties[$name]);
            }
        }
    }

    /**
     * Checks a function local static variable.
     *
     * @return void
     */
    static function checkFunctionLocalStaticVariable()
    {
        B::limitAccess('BreakpointDebugging_PHPUnit.php', true);

        $componentFullPath = stream_resolve_include_path('BreakpointDebugging/Component/') . DIRECTORY_SEPARATOR;
        $definedFunctionsName = get_defined_functions();
        // Copies property to local variable for closure function call.
        $isUnitTestClass = self::$_isUnitTestClass;
        foreach ($definedFunctionsName['user'] as $definedFunctionName) {
            $functionReflection = new ReflectionFunction($definedFunctionName);
            $staticVariables = $functionReflection->getStaticVariables();
            // If static variable has been existing.
            if (!empty($staticVariables)) {
                $fileName = $functionReflection->getFileName();
                if (strpos($fileName, $componentFullPath) === 0) {
                    $className = str_replace(array ('\\', '/'), '_', substr($fileName, strlen($componentFullPath)));
                    // Excepts unit test classes.
                    if ($isUnitTestClass($className)) {
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
     */
    static function checkMethodLocalStaticVariable()
    {
        B::limitAccess('BreakpointDebugging_PHPUnit.php', true);

        // Scans the declared classes.
        $declaredClasses = get_declared_classes();
        $currentDeclaredClassesNumber = count($declaredClasses);
        // Copies property to local variable for closure function call.
        $isUnitTestClass = self::$_isUnitTestClass;
        for ($key = $currentDeclaredClassesNumber - 1; $key >= 0; $key--) {
            $declaredClassName = $declaredClasses[$key];
            // Excepts unit test classes.
            if ($isUnitTestClass($declaredClassName)) {
                continue;
            }
            // Class reflection.
            $classReflection = new \ReflectionClass($declaredClassName);
            // If it is not user defined class.
            if (!$classReflection->isUserDefined()) {
                continue;
            }
            // Checks existence of local static variable per class method.
            foreach ($classReflection->getMethods() as $methodReflection) {
                // If this class method is not parent class method.
                if ($methodReflection->class === $declaredClassName) {
                    $result = $methodReflection->getStaticVariables();
                    // If local static variable has been existing.
                    if (!empty($result)) {
                        if ($methodReflection->isStatic()) {
                            // Warns that static class method has local static variable.
                            $messageA = 'static';
                        } else {
                            // Warns that auto class method has local static variable.
                            $messageA = 'auto';
                        }
                        echo PHP_EOL
                        . 'Code which is tested must use private ' . $messageA . ' property instead of use local static variable in ' . $messageA . ' class method' . PHP_EOL
                        . 'because "php" version 5.3.0 cannot restore its value.' . PHP_EOL
                        . 'Also, those variable life time is same.' . PHP_EOL
                        . "\t" . '<b>FILE: ' . $methodReflection->getFileName() . PHP_EOL
                        . "\t" . 'LINE: ' . $methodReflection->getStartLine() . PHP_EOL
                        . "\t" . 'CLASS: ' . $methodReflection->class . PHP_EOL
                        . "\t" . 'METHOD: ' . $methodReflection->name . '</b>' . PHP_EOL;
                    }
                }
            }
        }
    }

    /**
     * Checks an "include" error at "setUp()", "test*()" or "tearDown()".
     *
     * @return void
     */
    static function checkIncludeError()
    {
        B::limitAccess(
            array (
                'BreakpointDebugging/PHPUnit/FrameworkTestCase.php',
                'BreakpointDebugging/PHPUnit/FrameworkTestCaseSimple.php'
            )
        );

        foreach (self::$_declaredClasses as $key => $currentDeclaredClass) {
            if (stripos($currentDeclaredClass, 'PHPUnit_Framework_') === 0) {
                unset(self::$_declaredClasses[$key]);
            }
        }

        $currentDeclaredClasses = get_declared_classes();
        foreach ($currentDeclaredClasses as $key => $currentDeclaredClass) {
            if (stripos($currentDeclaredClass, 'PHPUnit_Framework_') === 0) {
                unset($currentDeclaredClasses[$key]);
            }
        }
        // If a class has not been declared.
        if (count($currentDeclaredClasses) === count(self::$_declaredClasses)) {
            return;
        }
        // Gets the included classes because autoloaded classes make error handling.
        $includedClassNames = array_diff($currentDeclaredClasses, self::$_declaredClasses);
        self::_displayError(true, $includedClassNames);
    }

}
