<?php

use \BreakpointDebugging as B;
use \BreakpointDebugging_PHPUnit as BU;
use \BreakpointDebugging_ErrorInAllCaseTest as T;

function test4_($error)
{
    BU::ignoreBreakpoint();
    $error->handleException2(new \Exception(), B::$prependExceptionLog);
    T::$lineA_ = __LINE__ - 1;
    BU::notIgnoreBreakpoint();
}

function test3_($error)
{
    test4_($error);
    T::$lineB_ = __LINE__ - 1;
}

BU::ignoreBreakpoint();
B::addValuesToTrace(array (array ('TestString')));
$this->_error->handleException2(new \Exception(), B::$prependExceptionLog);
$line__ = __LINE__ - 1;
BU::notIgnoreBreakpoint();

test3_($this->_error);
$lineC_ = __LINE__ - 1;

?>
