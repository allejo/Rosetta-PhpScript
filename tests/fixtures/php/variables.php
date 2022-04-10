<?php

$aSimpleString = 'hello world';
$someStringConcatenation = 'hello' . ' ' . 'bob';
$aTemplateStringWithNoVariables = 'lorem ipsum';
$aTemplateStringWithVariables = "here's an {$someStringConcatenation}{$aSimpleString} embedded string: {$aSimpleString} in the middle";

$aNumber = 123456;
$aDecimal = 123.45;
$aFalseBool = false;
$aTrueBool = true;

$anObject = (object) array('hello' => 'world', $aSimpleString => 'variable key');
$anArray = [1, 2, 3, 4, '5', '6', '7', true, false, (object)['hello' => 'world']];
$aCallback = function ($arg1, $arg2) {
    return $arg1 + $arg2;
};
