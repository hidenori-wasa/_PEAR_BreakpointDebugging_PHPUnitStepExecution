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

* I repaired search regular expression of unit test rule.
* I repaired execution mode security.
* I repaired "BreakpointDebugging_InDebug.php" file level document.
* I repaired "BreakpointDebugging_PHPUnitStepExecution_DisplayCodeCoverageReport.php" because "form" tag existed inside "pre" tag.
* I changed for inline function from JavaScript function of "\BreakpointDebugging::windowClose()" class method.

Notice
------

I have been coding the unit tests and "*.phpt" tests.
