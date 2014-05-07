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

* I repaired "\BreakpointDebugging_PHPUnit_FrameworkTestCaseSimple::runTestMethods()" to split up instance per test class method.
* I added "Autodetecting rule 1" inside "BreakpointDebugging_PHPUnit.php" file level document.

Notice
------

* I have been developing package yet.
* Also, I have been testing with "BreakpointDebugging_PHPUnit" package.
* And, I have been testing "BreakpointDebugging_PHPUnit" package by "\BreakpointDebugging_PHPUnit_FrameworkTestCaseSimple" class.
