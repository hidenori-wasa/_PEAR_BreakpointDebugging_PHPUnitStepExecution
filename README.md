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

* I changed to "include_path" which includes default "PEAR" path.
* I deleted parameter 2 of B::asseert() call for breakpoint debugging.
* I repaired bug which does not delete synchronization file at "setUp()" by unit test.

Notice
------

I have been coding the unit tests and "*.phpt" tests.
