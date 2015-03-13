BreakpointDebugging_PHPUnit
========================================

Notice
------

* Should not use draft.
* I have been implementing new feature and unit test yet.

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

Please, read the file level document block of `PEAR/BreakpointDebugging_PHPUnit.php`.

The dependences
---------------

* Requires "BreakpointDebugging" package.
