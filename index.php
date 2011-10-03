<?php

// Load ExceptionHandler
$libraryPath = './libraries/ExceptionHandler/';
require_once $libraryPath.'ExceptionHandler.php';
require_once $libraryPath.'geshi/geshi.php';

$geshi            = new GeSHi();
$exceptionHandler = new ExceptionHandler($libraryPath, $geshi);

$arg = 'MyArgument';
throw new InvalidArgumentException(sprintf('Oops, an error occured! The argument "%s" is invalid!', $arg));
