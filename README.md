ExceptionHandler
================

ExceptionHandler is a standalone package to manage PHP exceptions.

## Installation

Just download and extract the package. Configures.

## Configuration

All you have to do is to:

1.    set the relative path of your library,
2.    include the GeSHi's file (it lives in geshi's subdirectory),
3.    instantiate GeSHi,
4.    instantiate the handler with the relative path of the file and an instance of GeSHi.

Then configure the PHP exception handler.

```php
<?php

// Load ExceptionHandler
$libraryPath = 'path/to/ExceptionHandler/';
require_once $libraryPath.'ExceptionHandler.php';
require_once $libraryPath.'geshi/geshi.php';

$geshi            = new GeSHi();
$exceptionHandler = new ExceptionHandler($libraryPath, $geshi);

```

GeSHi is used to colorize the code. If you don't want to use it, configure like the following instructions:

```php
<?php

// Load ExceptionHandler
$libraryPath = 'path/to/ExceptionHandler/';
require_once $libraryPath.'ExceptionHandler.php';

$exceptionHandler = new ExceptionHandler($libraryPath);

```

There are two optional arguments: the number of lines to display and the use or not of javascript.
The first is set to 5 by default and the second as true.

You can change it if you want, for example, show 8 lines and do not use javascript:

```php
<?php

$exceptionHandler = new ExceptionHandler($libraryPath, $geshi, 8, false);

```

## Mechanism

When an exception is caught, ExceptionHandler does his job and traces the exceptions' stack.


## License

ExceptionHandler is licensed under the MIT license.
