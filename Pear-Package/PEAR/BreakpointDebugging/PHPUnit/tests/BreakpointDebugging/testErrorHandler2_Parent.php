<?php

use \BreakpointDebugging as B;
use \BreakpointDebugging_ErrorInAllCaseTest as T;

function test4($error)
{
    B::registerNotFixedLocation(\TestErrorHandler2Parent::$isRegister);

    trigger_error2($error);
    T::$lineA = __LINE__ - 1;
}

function test3($error)
{
    test4($error);
    T::$lineB = __LINE__ - 1;
}

trigger_error2($error);
$line_ = __LINE__ - 1;
test3($error);
$lineC = __LINE__ - 1;

?>
