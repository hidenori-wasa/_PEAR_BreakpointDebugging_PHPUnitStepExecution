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

* I repaired "\BreakpointDebugging_InAllCase::clearRecursiveArrayElement()" to display the elements of "$GLOBALS".
* I repaired "\BreakpointDebugging_LockByFlock::__destruct()" to close of file stream resource.
* I repaired for 0% display color of code coverage report inside component directory.
* I changed for code coverage report display which adjusts to error display size.
* I changed for code coverage report display and error display which does not tire.
* I added highlight display feature of "@codeCoverageIgnore", "@codeCoverageIgnoreStart" and "@codeCoverageIgnoreEnd" to code coverage report.
* I added "NetBeans" IDE setting to "./PEAR/misc/" directory.

Notice
------

I have been coding the unit tests and "*.phpt" tests.
