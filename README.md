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

* I created simple unit test cord coverage report display feature by "\BreakpointDebugging_PHPUnit::displayCodeCoverageReportSimple()".
* I created "@codeCoverageSimpleIgnoreStart and @codeCoverageSimpleIgnoreEnd" feature to ignore simple unit test cord coverage report.
* I improved lambda function call of "\BreakpointDebugging_PHPUnit_StaticVariableStorage::$_isUnitTestClass".
* I repaired file level document of "BreakpointDebugging_InDebug.php" file because I differed from estimate in "PHP" version of "Ubuntu".

Notice
------

* I have been developing package yet.
* Also, I have been testing with "BreakpointDebugging_PHPUnit" package.
* And, I have been testing "BreakpointDebugging_PHPUnit" package by "\BreakpointDebugging_PHPUnit::executeUnitTestSimple()".
