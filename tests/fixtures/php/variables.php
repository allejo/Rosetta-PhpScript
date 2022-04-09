<?php

$aSimpleString = 'hello world';
$someStringConcatenation = 'hello' . ' ' . 'bob';
$aTemplateStringWithNoVariables = 'lorem ipsum';
$aTemplateStringWithVariables = "here's an {$someStringConcatenation}{$aSimpleString} embedded string: {$aSimpleString} in the middle";
