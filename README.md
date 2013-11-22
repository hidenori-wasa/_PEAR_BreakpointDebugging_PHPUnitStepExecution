BreakpointDebugging_PHPUnitStepExecution
========================================

The basic concept
-----------------

"PHPUnit"-package extention.

The features list
-----------------

* Executes unit test files continuously, and debugs with IDE.
* Creates code coverage report, then displays in browser.

Please, read the file level document block of `PEAR/BreakpointDebugging_PHPUnitStepExecution.php`.

The dependences
---------------

* Requires `BreakpointDebugging` package.
* OS requires `Linux` or `Windows`, but may be able to require `Unix`.
* PHP version >= `5.3.0`
* Requires `Xdebug extension`.

Change log
----------

* I repaired "$_SERVER['QUERY_STRING']" in case of actual server release.
* I changed class method name from "\BreakpointDebugging::windowOpen()" to "\BreakpointDebugging::windowVirtualOpen()".
* I embedded "windowOpen.js" file to "\BreakpointDebugging::windowVirtualOpen()" class method.
* I created "\BreakpointDebugging::windowScriptClearance()" to reduce memory use in case of unit test.
* I added color to unit test result display.
* I repaired error display anchor from subclass of "PHPUnit_Framework_Test" to subclass of  "BreakpointDebugging_PHPUnitStepExecution_PHPUnitFrameworkTestCase".
* I added auto scroll feature of unit test display.

Notice
------

I have been coding the unit tests and "*.phpt" tests.
