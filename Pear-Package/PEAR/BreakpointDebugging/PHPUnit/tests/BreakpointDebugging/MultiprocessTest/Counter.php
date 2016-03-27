<?php

//chdir(__DIR__ . '/../../../../');
chdir(__DIR__ . '/../../../../../../');
require_once './BreakpointDebugging_Inclusion.php';

use \BreakpointDebugging as B;

abstract class Counter
{
    protected $shmopId;

    function __construct($shmopKey)
    {
        $this->shmopId = shmop_open($shmopKey, 'w', 0, 0);
        if (empty($this->shmopId)) {
            B::exitForError('Failed "shmop_open()".');
        }
    }

    function __destruct()
    {
        shmop_close($this->shmopId);
    }

    protected function incrementSheredMemory()
    {
        $tmpCount = shmop_read($this->shmopId, 0, 10);
        if ($tmpCount === false) {
            B::exitForError('Failed "shmop_read()".');
        }
        $tmpCount++;
        // Sleep 0.0001 seconds.
        usleep(100);
        $result = shmop_write($this->shmopId, sprintf('0x%08X', $tmpCount), 0);
        if ($result === false) {
            B::exitForError('Failed "shmop_write()".');
        }
    }

}
