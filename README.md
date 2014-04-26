BreakpointDebugging_PHPUnit
========================================

The basic concept
-----------------

"PHPUnit"-package extention.

The features list
-----------------

* Executes unit test files continuously, and debugs with IDE.
* Creates code coverage report, then displays in browser.

Please, read the file level document block of `PEAR/BreakpointDebugging_PHPUnit.php`.

The dependences
---------------

* Requires "BreakpointDebugging" package.
* OS requires Linux or Windows.
* PHP version = 5.3.2-5.4.x
* Requires "Xdebug extension".

Change log
----------

* I added execution mode procedure description inside of "BreakpointDebugging_InDebug.php" file.
* I changed autoload class method name from "autoload" to "loadClass".
* I customized "\BreakpointDebugging_PHPUnit" static class to auto class for "\BreakpointDebugging_PHPUnit_FrameworkTestCaseSimple" class.
* I changed "\BreakpointDebugging_PHPUnit_GlobalState" class name to "\BreakpointDebugging_PHPUnit_StaticVariableStorage" class name, and I changed its license because its class does not use "PHPUnit" package code.
* I created unit test feature without "PHPUnit" package by "\BreakpointDebugging_PHPUnit_FrameworkTestCaseSimple" class.
* I have been developing code coverage report feature without "PHPUnit" package by "\BreakpointDebugging_PHPUnit_FrameworkTestCaseSimple" class.

Notice
------

* I have been developing package yet.
* Also, I have been testing with "BreakpointDebugging_PHPUnit" package.
* And, I have been testing "BreakpointDebugging_PHPUnit" package by "\BreakpointDebugging_PHPUnit_FrameworkTestCaseSimple" class.
