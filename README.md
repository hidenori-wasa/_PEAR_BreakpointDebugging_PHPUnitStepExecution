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

* I added sync's files initialization feature when execution mode is development.
* I changed that error message of unit test is chosen easily.
* I changed procedure of "BreakpointDebugging_ErrorLogFilesManager.php" page.
* I combined "BreakpointDebugging_" prefix to this package work directory and PEAR setting directory.
* I added "BreakpointDebugging_ProductionSwitcher.php" page which switches production mode and development mode.

Notice
------

* Should not use draft.
* I am implementing "\BreakpointDebugging\NativeFunctions" class.
* Also, I have been testing with "BreakpointDebugging_PHPUnit" package.
* And, I have been testing "BreakpointDebugging_PHPUnit" package by "\BreakpointDebugging_PHPUnit::executeUnitTestSimple()".
