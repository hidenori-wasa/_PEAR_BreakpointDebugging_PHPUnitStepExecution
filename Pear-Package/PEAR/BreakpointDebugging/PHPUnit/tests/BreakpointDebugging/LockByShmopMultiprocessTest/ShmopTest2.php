<?php

chdir(str_repeat('../', preg_match_all('`/`xX', $_SERVER['PHP_SELF'], $matches) - 2));

require_once './BreakpointDebugging_Inclusion.php';
class ShmopTest2
{
    private $shmopId;

    function __construct()
    {
        $this->shmopId = shmop_open(1234, 'w', 0, 0);
    }

    function __destruct()
    {
        shmop_close($this->shmopId);
    }

    private function _incrementSheredMemory()
    {
        $tmpCount = shmop_read($this->shmopId, 0, 10);
        $tmpCount++;
        // Sleep 0.0001 seconds.
        usleep(100);
        shmop_write($this->shmopId, sprintf('0x%08X', $tmpCount), 0);
    }

    function testLock()
    {
        // Extend maximum execution time.
        set_time_limit(300);
        $start = microtime(true);
        switch ('shmop') {
            case 'shmop':
                $sharedMemoryID = shmop_open(333, 'w', 0, 0);
                for ($count = 0; $count < 5000; $count++) {
                    while (true) {
                        shmop_write($sharedMemoryID, '1', 1);
                        if (shmop_read($sharedMemoryID, 0, 1) === '1') {
                            shmop_write($sharedMemoryID, '0', 1);
                            continue;
                        }
                        $this->_incrementSheredMemory();
                        shmop_write($sharedMemoryID, '0', 1);
                        break;
                    }
                }
                shmop_close($sharedMemoryID);
                break;
            default:
                throw new \BreakpointDebugging_ErrorException('', 101);
        }
        var_dump(shmop_read($this->shmopId, 0, 10) + 0, microtime(true) - $start);
    }

}

$ShmopTest2 = new \ShmopTest2();
$ShmopTest2->testLock();

?>
