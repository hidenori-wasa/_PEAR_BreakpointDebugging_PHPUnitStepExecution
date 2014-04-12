BreakpointDebugging_PHPUnit
========================================

The basic concept
-----------------

"PHPUnit"-package extention.

The features list
-----------------

* Executes unit test files continuously, and debugs with IDE.
* Creates code coverage report, then displays in browser.

Please, read the file level document block of `PEAR/BreakpointDebugging_PHPUnit.php`.

The dependences
---------------

* Requires "BreakpointDebugging" package.
* OS requires Linux or Windows.
* PHP version = 5.3.x, 5.4.x
* Requires "Xdebug extension".

Change log
----------

* I repaired user name and PHP version check.
* I improved production server performance by display which comment out "\BreakpointDebugging::iniSet()" and "\BreakpointDebugging::iniCheck()" in case of remote-release-mode.

Notice
------

* I have been developing yet.
* Also, I have been testing with "BreakpointDebugging_PHPUnit".
* And, I will test internal code of "BreakpointDebugging_PHPUnit" with "BreakpointDebugging_PHPUnit_TestOfInternal".
