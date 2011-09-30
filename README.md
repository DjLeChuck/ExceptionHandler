ExceptionHandler
================

ExceptionHandler is a standalone package to manage PHP exceptions.

## Installation

Just download and extract the package. Configures.

## Configuration

All you have to do is to:
    * set the relative path of your library,
    * include the GeSHi's file (it lives in geshi's subdirectory),
    * instanciate GeSHi,
    * instanciate the handler with the relative path of the file and an instance of GeSHi.
Then configure the PHP exception handler.

```php
<?php

// Load ExceptionHandler
$libraryPath = 'path/to/ExceptionHandler/';
require_once $libraryPath.'geshi/geshi.php';

$geshi            = new GeSHi();
$exceptionHandler = new ExceptionHandler($libraryPath, $geshi);

set_exception_handler(array($exceptionHandler, 'catchEmAll'));

```

GeSHi is used to colorize the code syntax. If you don't want to use it, configure like the following instructions:

```php
<?php

// Load ExceptionHandler
$libraryPath = 'path/to/ExceptionHandler/';

$exceptionHandler = new ExceptionHandler($libraryPath);

set_exception_handler(array($exceptionHandler, 'catchEmAll'));

```

## Mechanism

When an exception is caught, ExceptionHandler do his job and traces the exceptions' stack.


## License

ExceptionHandler is licensed under the MIT license.
