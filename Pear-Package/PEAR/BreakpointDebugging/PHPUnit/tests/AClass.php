<?php

class AClass
{
    static $staticProperty = 'Initial value of static property.';
    static $objectProperty;

    function __construct()
    {
        self::$objectProperty = new \AClass2();
    }

}
