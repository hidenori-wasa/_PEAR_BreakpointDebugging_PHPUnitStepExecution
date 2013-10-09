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

* I repaired storing of super global variable. ("--static-backup" command line switch)
* I repaired reference storing of global variable and static property. ("--static-backup" command line switch)
* I repaired continuous execution of unit test files. ("--static-backup" command line switch)
* I changed execution rule.
* I changed unit test example.

Notice
------

I will code the unit tests.
