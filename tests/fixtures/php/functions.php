<?php

function noOpFunction()
{
}

function withArguments($arg1, $arg2)
{
}

function withArgAndBody($arg1)
{
    $t = 1;

    return $t + $arg1;
}

function withObjectAsArg($obj)
{
    $t = $obj->SOME_CONSTANT;
    $a = $obj->someFunction();

    $a->hello = 'world';
    $a->render('arg1', 'arg2');

    return $obj;
}

withObjectAsArg((object)[]);
