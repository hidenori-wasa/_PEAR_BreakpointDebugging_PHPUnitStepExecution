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

* I decreased disk access.
* I repaired production server release mode.
* I repaired for exception throw if "BreakpointDebugging_Window" class was used in case of production server release.

Notice
------

* Do not use draft of 31th because "\BreakpointDebugging_DisplayToOtherProcess::displayToOtherProcess()" class method is endless.
* Also, should not use draft.
* I have been developing package yet.
* Also, I have been testing with "BreakpointDebugging_PHPUnit" package.
* And, I have been testing "BreakpointDebugging_PHPUnit" package by "\BreakpointDebugging_PHPUnit::executeUnitTestSimple()".
