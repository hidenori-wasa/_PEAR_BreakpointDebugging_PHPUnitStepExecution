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
* Development PHP version = `XAMPP 1.7.3` ("PHP 5.3.x" Compiled with VC++6.0 in case of windows.)

                            Do not use `XAMPP 1.7.7` ("PHP 5.3.x" Compiled with VC++9.0 in case of windows.)

* Production PHP version = `5.3.x`
* Requires `Xdebug extension`.

Change log
----------

* I repaired Linux execution by remote server emulation.

Notice
------

I have been developing yet.
Also, I have been coding the unit tests and "*.phpt" tests.
