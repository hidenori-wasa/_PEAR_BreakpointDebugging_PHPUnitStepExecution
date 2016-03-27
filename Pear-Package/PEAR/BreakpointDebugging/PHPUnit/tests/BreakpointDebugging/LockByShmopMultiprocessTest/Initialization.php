<?php

chdir(str_repeat('../', preg_match_all('`/`xX', $_SERVER['PHP_SELF'], $matches) - 2));

require_once './BreakpointDebugging_Inclusion.php';

use \BreakpointDebugging as B;
use \BreakpointDebugging_Window as BW;

class Initialization
{

    function __construct()
    {
        B::assert(extension_loaded('shmop'), 101);
        // Allocate shared memory area.
        $shmopId = shmop_open(1234, 'c', 0600, 10);
        // Initialize shared memory.
        shmop_write($shmopId, '0x00000000', 0);
        shmop_close($shmopId);

        $sharedMemoryID = shmop_open(333, 'c', 0600, 2);
        shmop_write($sharedMemoryID, '00', 0);
        shmop_close($sharedMemoryID);
        // Unlinks internal synchronization file.
        $internalLockFilePath = BREAKPOINTDEBUGGING_WORK_DIR_NAME . 'LockByFileExistingOfInternal.txt';
        if (is_file($internalLockFilePath)) {
            B::unlink(array ($internalLockFilePath));
        }
        // Unlinks synchronization file.
        $lockFileName = BREAKPOINTDEBUGGING_WORK_DIR_NAME . 'LockByShmop.txt';
        if (is_file($lockFileName)) {
            B::unlink(array ($lockFileName));
        }

        $htmlFileContent = <<<EOD
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>INITIALIZATION</title>
    </head>
    <body style="background-color: black; color: white; font-size: 25px">
        <pre></pre>
    </body>
</html>
EOD;
        BW::virtualOpen(__CLASS__, $htmlFileContent);
        BW::htmlAddition(__CLASS__, 'pre', 0, '<b>Initialization is OK.' . PHP_EOL
            . 'Wait about 10 second until hard disk access stops.' . PHP_EOL
            . 'Then, close this window.' . PHP_EOL
            . 'Then, point location which tool tip does not display with mouse until the result is displayed.</b>'
        );
    }

}

new \Initialization();

?>
