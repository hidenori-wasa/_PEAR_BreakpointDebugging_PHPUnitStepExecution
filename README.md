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

Change log
----------

* I changed default permission of "\BreakpointDebugging_InAllCase::mkdir()" from "0777" to "0700" for security.
* I changed default permission of "\BreakpointDebugging_InAllCase::fopen()" from "0777" to "0600" for "BreakpointDebugging_ProductionSwitcher.php" page.
* I repaired "\BreakpointDebugging_Window" class on Linux for "BreakpointDebugging_ProductionSwitcher.php" page.
* I did regression test on Linux.

Notice
------

* Should not use draft.
* I have been implementing new feature and unit test yet.
