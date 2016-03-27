BreakpointDebugging_PHPUnit
========================================

As for procedure, please, refer to `PEAR/BreakpointDebugging/PHPUnit/docs/BREAKPOINTDEBUGGING_PHPUNIT_MANUAL.html`.

The basic concept
-----------------

"PHPUnit"-package extention.

The features list
-----------------

* Perfect static backup. But some rules exist. Please, see "PHPUnit1Test.php" and "PHPUnit2Test.php" test file.
* Autodetects mistaken test code for static backup. Please, see "ExampleTest.php" test file.
* Tests session by browser display of other process.
* We can identify the long test by browser display per test class method.
* Scrolls browser display automatically during unit test.
* We can test from error test file continuously because test files is specified with array.
* Displays the unexecuted test files.
* We can debug with IDE. (This is same in "CakePHP".)
* We can test in remote by browser display. (This is same in "CakePHP".)
* We can display a code coverage report in remote by browser display. (This is same in "CakePHP".)

The dependences
---------------

* Requires "BreakpointDebugging" package.

Notice
------

* Same as prev upload part of "CakePHPSamples".
