<?php

class PHPUnit2Test extends \BreakpointDebugging_PHPUnit_FrameworkTestCase
{

    protected function setUp()
    {
        parent::setUp();

        new \AClass(); // For static property registration with object to static backup.
    }

    /**
     * @covers \Example<extended>
     */
    public function testStoring_C()
    {
        // Asserts the value and the reference.
        parent::assertTrue(array (&\PHPUnit1Test::$initialReferenceOfGlobal) === array (&$_FILES));
        parent::assertTrue(\PHPUnit1Test::$initialValueOfGlobal === $_FILES);
        parent::assertTrue(\PHPUnit1Test::$initialValueOfAutoProperty === \AClass::$objectProperty->autoProperty);
        parent::assertTrue(\PHPUnit1Test::$initialValueOfStaticProperty === \AClass::$staticProperty);
        // Changes the value and the reference.
        $_FILES = &$referenceChange3;
        $_FILES = 'The change value of global variable. 3';
        \AClass::$objectProperty->autoProperty = 'The change value of auto property. 3';
        \AClass::$staticProperty = 'The change value of static property. 3';
    }

    /**
     * @covers \Example<extended>
     */
    public function testStoring_D()
    {
        // Asserts the value and the reference.
        parent::assertTrue(array (&\PHPUnit1Test::$initialReferenceOfGlobal) === array (&$_FILES));
        parent::assertTrue(\PHPUnit1Test::$initialValueOfGlobal === $_FILES);
        parent::assertTrue(\PHPUnit1Test::$initialValueOfAutoProperty === \AClass::$objectProperty->autoProperty);
        parent::assertTrue(\PHPUnit1Test::$initialValueOfStaticProperty === \AClass::$staticProperty);
    }

}
