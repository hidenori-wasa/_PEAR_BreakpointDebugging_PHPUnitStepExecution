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

* I added unit test rule.
* I modified "NetBeans" IDE setting to "./PEAR/misc/" directory.
* I changed unit test, code coverage report, error log files manager and error display to display of other window with JavaScript.
* I changed execution mode setting to a project execution parameter.

Notice
------

I have been coding the unit tests and "*.phpt" tests.
