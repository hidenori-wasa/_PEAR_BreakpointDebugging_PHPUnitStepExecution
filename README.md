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

* I added "\BreakpointDebugging::$_get" like "$_GET" for both of CLI and CGI.
* I repaired unit test with "\BreakpointDebugging::$_get".
* I repaired URL encode and decode with "\BreakpointDebugging::httpBuildQuery()" class method.
* I added document of recommendation file cache extention of production server into "BreakpointDebugging_InDebug.php".

Notice
------

I have been coding the unit tests and "*.phpt" tests.
