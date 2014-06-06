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
* PHP version = 5.3.2-5.4.x
* Requires "Xdebug extension".
* Requires "Mozilla Firefox" web browser.

Change log
----------

* I repaired interprocess communication of shared memory on local server about "\BreakpointDebugging_Window" class.
* I checked caution display in case of shared file on local server about "\BreakpointDebugging_Window" class.
* I repaired interprocess communication of shared memory on remote server about "\BreakpointDebugging_Window" class.

Notice
------

* Do not use draft of 31th because "\BreakpointDebugging_DisplayToOtherProcess::displayToOtherProcess()" class method is endless.
* Also, should not use draft.
* I have been developing package yet.
* Also, I have been testing with "BreakpointDebugging_PHPUnit" package.
* And, I have been testing "BreakpointDebugging_PHPUnit" package by "\BreakpointDebugging_PHPUnit::executeUnitTestSimple()".
