<?php

use \BreakpointDebugging as B;
use \BreakpointDebugging_PHPUnit as BU;

class BreakpointDebugging_LockByShmopTest extends \BreakpointDebugging_PHPUnit_FrameworkTestCase
{
    protected $LockByShmop;

    function setUp()
    {
        parent::setUp();
        // Checks shared memory operation extension existence.
        if ((BU::$exeMode & B::REMOTE) //
            && !extension_loaded('shmop') //
        ) {
            $this->markTestSkipped('"shmop" extention has been not loaded.');
        }
        // Constructs instance.
        $this->LockByShmop = &\BreakpointDebugging_LockByShmop::singleton(5, 10);
    }

    function tearDown()
    {
        // Destructs instance.
        $this->LockByShmop = null;
        parent::tearDown();
    }

    /**
     * @covers \BreakpointDebugging_LockByShmop<extended>
     */
    function testMultiprocess()
    {
        // Destructs instance.
        $this->LockByShmop = null;
        $main = new \tests_PEAR_BreakpointDebugging_MultiprocessTest_Main();
        if (!$main->test(1234, '\BreakpointDebugging_LockByShmop')) {
            parent::fail();
        }
    }

    /**
     * @covers \BreakpointDebugging_LockByShmop<extended>
     *
     * @expectedException        \BreakpointDebugging_ErrorException
     * @expectedExceptionMessage CLASS=BreakpointDebugging_Lock FUNCTION=__clone ID=101.
     */
    function test__clone()
    {
        BU::markTestSkippedInRelease(); // Because this unit test is assertion.

        $tmp = clone $this->LockByShmop;
    }

    /**
     * @covers \BreakpointDebugging_LockByShmop<extended>
     */
    function testSingleton()
    {
        $pFile = B::fopen(array (BREAKPOINTDEBUGGING_WORK_DIR_NAME . 'LockByShmop.txt', 'wb'));
        fwrite($pFile, 'dummydummy');
        fclose($pFile);
        BU::setPropertyForTest('\BreakpointDebugging_Lock', '$_instance', null);
        \BreakpointDebugging_LockByShmop::singleton(5, 10);
    }

    /**
     * @covers \BreakpointDebugging_LockByShmop<extended>
     */
    public function test__destruct()
    {
        parent::assertTrue(BU::getPropertyForTest('\BreakpointDebugging_Lock', '$_instance') instanceof \BreakpointDebugging_LockByShmop);
        // Calls "__destruct".
        $this->LockByShmop = null;
        parent::assertTrue(BU::getPropertyForTest('\BreakpointDebugging_Lock', '$_instance') === null);
    }

    /**
     * @covers \BreakpointDebugging_LockByShmop<extended>
     */
    public function testForceUnlocking()
    {
        $this->LockByShmop->lock();
        $this->LockByShmop->lock();

        parent::assertTrue(BU::getPropertyForTest($this->LockByShmop, '$lockCount') === 2);

        \BreakpointDebugging_Lock::forceUnlocking();

        parent::assertTrue(BU::getPropertyForTest($this->LockByShmop, '$lockCount') === 0);
    }

    /**
     * @covers \BreakpointDebugging_LockByShmop<extended>
     */
    function testLockThenUnlock_A()
    {
        $this->LockByShmop->lock();
        $this->LockByShmop->unlock();
    }

    /**
     * @covers \BreakpointDebugging_LockByShmop<extended>
     */
    function testLockThenUnlock_B()
    {
        $this->LockByShmop->lock();
        $this->LockByShmop->lock();
        $this->LockByShmop->unlock();
        $this->LockByShmop->unlock();
    }

    /**
     * @covers \BreakpointDebugging_LockByShmop<extended>
     *
     * @expectedException        \BreakpointDebugging_ErrorException
     * @expectedExceptionMessage CLASS=BreakpointDebugging_Lock FUNCTION=unlock ID=101.
     */
    function testLockThenUnlock_C()
    {
        BU::markTestSkippedInRelease(); // Because this unit test is assertion.

        $this->LockByShmop->unlock();
    }

    /**
     * @covers \BreakpointDebugging_LockByShmop<extended>
     *
     * @expectedException        \BreakpointDebugging_ErrorException
     * @expectedExceptionMessage CLASS=BreakpointDebugging_Lock FUNCTION=unlock ID=101.
     */
    function testLockThenUnlock_D()
    {
        BU::markTestSkippedInRelease(); // Because this unit test is assertion.

        try {
            $this->LockByShmop->lock();
            $this->LockByShmop->unlock();
        } catch (\Exception $e) {
            $this->fail();
        }
        $this->LockByShmop->unlock(); // Error.
    }

    /**
     * @covers \BreakpointDebugging_LockByShmop<extended>
     *
     * @expectedException        \BreakpointDebugging_ErrorException
     * @expectedExceptionMessage CLASS=BreakpointDebugging_Lock FUNCTION=__destruct ID=101.
     */
    function testLockThenUnlock_E()
    {
        BU::markTestSkippedInRelease(); // Because this unit test is assertion.

        $this->LockByShmop->lock();
        // Calls "__destruct()".
        $this->LockByShmop = null; // Error.
    }

    /**
     * @covers \BreakpointDebugging_LockByShmop<extended>
     *
     * @expectedException        \BreakpointDebugging_ErrorException
     * @expectedExceptionMessage CLASS=BreakpointDebugging_Lock FUNCTION=__destruct ID=101.
     */
    function testLockThenUnlock_F()
    {
        BU::markTestSkippedInRelease(); // Because this unit test is assertion.

        $this->LockByShmop->lock();
        $this->LockByShmop->lock();
        $this->LockByShmop->unlock();
        // Calls "__destruct()".
        $this->LockByShmop = null; // Error.
    }

    /**
     * @covers \BreakpointDebugging_LockByShmop<extended>
     */
    function testSingleton_A()
    {
        $LockByShmop1 = &\BreakpointDebugging_LockByShmop::singleton(5, 10);
        $LockByShmop2 = &\BreakpointDebugging_LockByShmop::singleton(5, 10); // Same object.
        parent::assertTrue($LockByShmop1 === $LockByShmop2);
    }

    /**
     * @covers \BreakpointDebugging_LockByShmop<extended>
     *
     * @expectedException        \BreakpointDebugging_ErrorException
     * @expectedExceptionMessage CLASS=BreakpointDebugging_Lock FUNCTION=singletonBase ID=101.
     */
    function testSingleton_B()
    {
        BU::markTestSkippedInRelease(); // Because this unit test is assertion.
        // Constructs instance of other class.
        $lockByFileExisting = &\BreakpointDebugging_LockByFileExisting::singleton(5, 10);
    }

    /**
     * @covers \BreakpointDebugging_LockByShmop<extended>
     *
     * @expectedException        \BreakpointDebugging_ErrorException
     * @expectedExceptionMessage CLASS=BreakpointDebugging_Lock FUNCTION=singletonBase ID=101.
     */
    function testSingleton_C()
    {
        BU::markTestSkippedInRelease(); // Because this unit test is assertion.
        // Constructs instance of other class.
        $lockByFlock = &\BreakpointDebugging_LockByFlock::singleton(5, 10);
    }

}
