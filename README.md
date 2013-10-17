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
* I added autodetecting rule 4.
* I added how to develop by this package.
* I changed error display header.
* I completed coding except unit test of this package.

Notice
------

I have been coding the unit tests or "*.phpt" tests.
