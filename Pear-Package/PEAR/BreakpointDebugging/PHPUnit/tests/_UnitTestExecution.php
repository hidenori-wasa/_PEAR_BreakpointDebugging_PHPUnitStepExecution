<?php

// Changes current directory to web root.
chdir('../../../../');

require_once './BreakpointDebugging_Inclusion.php';

use \BreakpointDebugging as B;
use \BreakpointDebugging_PHPUnit as BU;

B::checkExeMode(true);

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Please, choose unit tests files by customizing.
$breakpointDebugging_UnitTestFiles = array (
    /*
     */
    'BreakpointDebugging-IniSetOptimizerTest.php',
    'BreakpointDebugging-ProductionSwitcherTest.php',
    'PHPUnit1Test.php',
    'PHPUnit2Test.php',
    'BreakpointDebugging-ExceptionTest.php',
    'BreakpointDebugging-InAllCaseTest.php',
    'BreakpointDebuggingTest.php',
    'BreakpointDebugging/ErrorInAllCaseTest.php',
    'BreakpointDebugging/ErrorTest.php',
    'BreakpointDebugging/LockByFileExistingTest.php',
    'BreakpointDebugging/LockByFlockTest.php',
    'BreakpointDebugging/OverrideClassTest.php',
    /*
     */
);

// Executes unit tests.
BU::executeUnitTest($breakpointDebugging_UnitTestFiles); exit;
// Makes up code coverage report, then displays in browser.
if (B::isDebug()) { // In case of debug.
    // BU::displayCodeCoverageReport('BreakpointDebuggingTest.php', 'PEAR/BreakpointDebugging_InDebug.php'); exit;
    // BU::displayCodeCoverageReport('BreakpointDebugging/ErrorTest.php', 'PEAR/BreakpointDebugging/Error.php'); exit;
} else { // In case of release.
    // BU::displayCodeCoverageReport('BreakpointDebuggingTest.php', 'PEAR/BreakpointDebugging.php'); exit; // "BreakpointDebugging", "BreakpointDebugging_Middle" class is ? (Windows).
    // BU::displayCodeCoverageReport('BreakpointDebugging/ErrorInAllCaseTest.php', 'PEAR/BreakpointDebugging/Error.php'); exit;
}
// In case of debug or release.
// BU::displayCodeCoverageReport('BreakpointDebugging-IniSetOptimizerTest.php', array ('BreakpointDebugging_Optimizer.php', 'BreakpointDebugging_IniSetOptimizer.php')); exit;
// BU::displayCodeCoverageReport('BreakpointDebugging-ProductionSwitcherTest.php', array ('BreakpointDebugging_Optimizer.php', 'BreakpointDebugging_ProductionSwitcher.php')); exit;
// BU::displayCodeCoverageReport(array ('BreakpointDebugging-IniSetOptimizerTest.php', 'BreakpointDebugging-ProductionSwitcherTest.php'), array ('BreakpointDebugging_Optimizer.php', 'BreakpointDebugging_IniSetOptimizer.php', 'BreakpointDebugging_ProductionSwitcher.php')); exit;
// BU::displayCodeCoverageReport('BreakpointDebugging-ExceptionTest.php', 'PEAROtherPackage/BreakpointDebugging_PHPUnit.php'); exit;
// BU::displayCodeCoverageReport('BreakpointDebugging-InAllCaseTest.php', 'PEAR/BreakpointDebugging.php'); exit;
// BU::displayCodeCoverageReport('BreakpointDebugging/LockByFileExistingTest.php', array ('PEAR/BreakpointDebugging/Lock.php', 'PEAR/BreakpointDebugging/LockByFileExisting.php')); exit; // OK.
// BU::displayCodeCoverageReport('BreakpointDebugging/LockByFlockTest.php', array ('PEAR/BreakpointDebugging/Lock.php', 'PEAR/BreakpointDebugging/LockByFlock.php')); exit; // OK.
// BU::displayCodeCoverageReport('BreakpointDebugging/OverrideClassTest.php', 'PEAR/BreakpointDebugging/OverrideClass.php'); exit;
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Please, choose unit tests files by customizing.
$breakpointDebugging_UnitTestFiles = array (
    'BreakpointDebugging-PHPUnitTestSimple.php',
);

// Executes unit tests of unit test code.
// BU::executeUnitTestSimple($breakpointDebugging_UnitTestFiles, 'SIMPLE_OWN'); exit;
//
// BU::displayCodeCoverageReport($breakpointDebugging_UnitTestFiles, 'BreakpointDebugging_PHPUnit.php', 'SIMPLE_OWN'); exit;
// BU::displayCodeCoverageReport($breakpointDebugging_UnitTestFiles, 'BreakpointDebugging/PHPUnit/FrameworkTestCase.php', 'SIMPLE_OWN'); exit;
