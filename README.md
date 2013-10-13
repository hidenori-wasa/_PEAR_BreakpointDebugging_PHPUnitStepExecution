BreakpointDebugging_PHPUnitStepExecution
========================================

The basic concept
-----------------

"PHPUnit"-package extention.

The features list
-----------------

* Executes unit test files continuously, and debugs with IDE.
* Creates code coverage report, then displays in browser.

The dependences
---------------

* Requires `BreakpointDebugging` package.
* OS requires `Linux` or `Windows`, but may be able to require `Unix`.
* PHP version >= `5.3.0`
* Requires `Xdebug extension`.

Change log
----------

* I repaired the check of recursive global variable and recursive static property. ("--static-backup" command line switch)
* I added code coverage report deletion button for security.
* I repaired it to "\BreakpointDebugging::chmod()" which is not overwritten if permission is same.
* I displayed how to repair if "php" command path does not exist.
* I repaired the order of include path of unit test.
* I executed regression test on Linux. (However not all unit tests)

Notice
------

I have been coding the unit tests or "*.phpt" tests.
