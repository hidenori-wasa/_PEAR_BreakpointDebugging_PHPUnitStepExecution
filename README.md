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
* PHP version = 5.3.x, 5.4.x
* Requires "Xdebug extension".

Change log
----------

* I supported "PHP5.4".
* I added the file search detection rule 3 for unit test.
* I added check of super global variable filter setting.
* I improved the file level document of "BreakpointDebugging_InDebug.php" file.
* I changed package name from "BreakpointDebugging_PHPUnitStepExecution" to "BreakpointDebugging_PHPUnit".
* I changed class name from "BreakpointDebugging_PHPUnitStepExecution" to "BreakpointDebugging_PHPUnit".
* I changed class name from "BreakpointDebugging_PHPUnitStepExecution_PHPUnitFrameworkTestCase" to "BreakpointDebugging_PHPUnit_FrameworkTestCase".
* I changed class name from "BreakpointDebugging_PHPUnitStepExecution_PHPUnitUtilFilesystem" to "BreakpointDebugging_PHPUnit_UtilFilesystem".
* I changed class name from "BreakpointDebugging_PHPUnitStepExecution_PHPUnitUtilGlobalState" to "BreakpointDebugging_PHPUnit_UtilGlobalState".
* I changed class name from "BreakpointDebugging_PHPUnitStepExecution_DisplayCodeCoverageReport" to "BreakpointDebugging_PHPUnit_DisplayCodeCoverageReport".

Notice
------

* I have been developing yet.
* Also, I have been testing with "BreakpointDebugging_PHPUnit".
* And, I will test internal code of "BreakpointDebugging_PHPUnit" with "BreakpointDebugging_PHPUnit_TestOfInternal".
