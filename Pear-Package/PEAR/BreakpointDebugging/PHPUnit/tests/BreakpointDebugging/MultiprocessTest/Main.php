<?php

use \BreakpointDebugging as B;
use \BreakpointDebugging_Window as BW;
use \BreakpointDebugging_CommandLine as BCL;

//class tests_PEAR_BreakpointDebugging_MultiprocessTest_Main
class BreakpointDebugging_MultiprocessTest_Main
{

    private function _initializeCounter($shmopKey)
    {
        if (!extension_loaded('shmop')) {
            \PHPUnit_Framework_Assert::markTestSkipped();
        }
        // Allocate shared memory area.
        $shmopId = shmop_open($shmopKey, 'c', 0600, 10);

        if ($shmopId === false) {
            B::exitForError('Failed "shmop_open()".');
        }
        // Initialize shared memory.
        $result = shmop_write($shmopId, '0x00000000', 0);
        if ($result === false) {
            B::exitForError('Failed "shmop_write()".');
        }
        shmop_close($shmopId);
    }

    function test($shmopKey, $className)
    {
        $this->_initializeCounter($shmopKey);

        $fullFilePath = __DIR__ . DIRECTORY_SEPARATOR . 'Lock.php';
        $pPipes = array ();
        $queryString = '"' . B::httpBuildQuery(array ('SHMOP_KEY' => $shmopKey, 'CLASS_NAME' => $className)) . '"';
        for ($count = 0; $count < 2; $count++) {
            $pPipes[] = BCL::popen($fullFilePath, $queryString);
        }

        $results = BCL::waitForMultipleProcesses($pPipes);

        foreach ($pPipes as $pPipe) {
            // Deletes a test process.
            pclose($pPipe);
        }

        if (max($results) !== '250') {
            // Displays error.
            foreach ($results as $result) {
                BW::virtualOpen('MultiProcessTestError', $result);
            }
            return false;
        }
        return true;
    }

}
