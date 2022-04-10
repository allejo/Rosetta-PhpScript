<?php

$aSimpleString = 'hello world';
$someStringConcatenation = 'hello' . ' ' . 'bob';
$aTemplateStringWithNoVariables = 'lorem ipsum';
$aTemplateStringWithVariables = "here's an {$someStringConcatenation}{$aSimpleString} embedded string: {$aSimpleString} in the middle";

$aNumber = 123456;
$aDecimal = 123.45;
$aFalseBool = false;
$aTrueBool = true;
