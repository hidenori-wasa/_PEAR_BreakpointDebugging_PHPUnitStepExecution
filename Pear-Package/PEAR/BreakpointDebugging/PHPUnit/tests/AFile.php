<?php

global $newGlobal;
$newGlobal = 'Initial value.';
// If next line is test code.
$newGlobal = 'Changes the value before storing.'; // Cannot store initial value!
class ThisCannotDoAutoload
{

}
