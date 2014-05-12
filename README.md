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

* I improved "\BreakpointDebugging_PHPUnit::displaysException()" display.
* I repaired "\BreakpointDebugging_PHPUnit_FrameworkTestCaseSimple::runTestMethods()" to catch unhandle exception of unit test.
* I changed "\BreakpointDebugging_PHPUnit_StaticVariableStorage::_isUnitTestClass()" to lambda function for unit test judgment of multiple kind.
* I preloaded error classes to optimize "isUnitTestClass()".
* I tested auto detect by "ExampleTestSimple.php" file, and repaired it. Then, I repaired "\BreakpointDebugging_PHPUnit::executeUnitTest()".
* I tested "BreakpointDebugging-PHPUnitTestSimple.php" file. Then, I repaired "\BreakpointDebugging_PHPUnit::executeUnitTest()".
* I tested "*Test.php" file. Then, I repaired "\BreakpointDebugging_PHPUnit::executeUnitTest()".

Notice
------

* I have been developing package yet.
* Also, I have been testing with "BreakpointDebugging_PHPUnit" package.
* And, I have been testing "BreakpointDebugging_PHPUnit" package by "\BreakpointDebugging_PHPUnit::executeUnitTestSimple()".
