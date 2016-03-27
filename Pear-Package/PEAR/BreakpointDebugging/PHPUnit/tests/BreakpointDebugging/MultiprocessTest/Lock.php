<?php

require_once __DIR__ . '/Counter.php';

use \BreakpointDebugging as B;

class Lock extends \Counter
{

    function testLock($className)
    {
        // Extend maximum execution time.
        set_time_limit(300);

        $pLock = &$className::singleton(60, 300, 1000);
        for ($count = 0; $count < 125; $count++) {
            $pLock->lock();
            $this->incrementSheredMemory();
            $pLock->unlock();
        }

        $result = shmop_read($this->shmopId, 0, 10);
        if ($result === false) {
            B::exitForError('Failed "shmop_read()".');
        }

        echo $result + 0;
    }

}

$get = B::getGet();
// file_put_contents(__DIR__ . '/_getOfCommandLine.txt', 'SHMOP_KEY=' . $get['SHMOP_KEY'] . PHP_EOL . 'CLASS_NAME=' . $get['CLASS_NAME'] . PHP_EOL, LOCK_EX); // For debug.
// $pLock = new \Lock(1111); // For debug.
$pLock = new \Lock($get['SHMOP_KEY']);
// $pLock->testLock('\BreakpointDebugging_LockByFileExisting'); // For debug.
$pLock->testLock($get['CLASS_NAME']);
