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

* I repaired "\BreakpointDebugging::$_get" when parameter does not exist.
* I added check of recommendation file cache extention initialization of "php.ini" file into "BreakpointDebugging_MySetting.php".
* I repaired document of recommendation file cache extention of production server into "BreakpointDebugging_InDebug.php".
* I repaired "\BreakpointDebugging::iniCheck()" class method display.

Notice
------

I have been coding the unit tests and "*.phpt" tests.
