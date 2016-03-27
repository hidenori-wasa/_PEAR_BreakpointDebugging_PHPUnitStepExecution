<?php

// >
// Changes current directory to web root.
chdir('../../../../../');

require_once './BreakpointDebugging_Inclusion.php';

use \BreakpointDebugging as B;
use BreakpointDebugging_PHPUnit as BU;

B::checkExeMode(true);

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Please, choose unit tests files by customizing.
$breakpointDebugging_UnitTestFiles = array (
    /*
     */
    'Sub/ExampleTest.php',
    'RuleTest.php',
    /*
     */
);

// Specifies the test directory if "CakePHP".
// BU::setTestDir('../../Plugin/WasaPhpUnit/Test/Case/');
//
// Executes unit tests.
BU::executeUnitTest($breakpointDebugging_UnitTestFiles); exit;

// Makes up code coverage report, then displays in browser.
BU::displayCodeCoverageReport('Sub/ExampleTest.php', 'Sub/Example.php'); exit;
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Please, choose simple unit tests files by customizing.
$breakpointDebugging_UnitTestFiles = array (
    /*
     */
    'Sub/ExampleTestSimple.php',
    'RuleTestSimple.php',
    /*
     */
);

// Executes simple unit tests.
BU::executeUnitTestSimple($breakpointDebugging_UnitTestFiles); exit;

// Makes up code coverage report, then displays in browser.
BU::displayCodeCoverageReport('Sub/ExampleTestSimple.php', 'Sub/Example.php', 'SIMPLE'); exit;
