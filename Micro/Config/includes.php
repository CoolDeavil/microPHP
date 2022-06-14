<?php

require_once('..'.
DIRECTORY_SEPARATOR.'vendor'.
DIRECTORY_SEPARATOR.'autoload.php');

const PSR4_FOLDER = 'Micro';
const PSR4 = 'API';

define('APP_ROOT', realpath('..'.DIRECTORY_SEPARATOR.PSR4_FOLDER).DIRECTORY_SEPARATOR);

require_once(realpath('../') .
    DIRECTORY_SEPARATOR . PSR4_FOLDER .
    DIRECTORY_SEPARATOR . 'Config' .
    DIRECTORY_SEPARATOR . 'micro.conf.php');

require_once(realpath('../') .
    DIRECTORY_SEPARATOR . PSR4_FOLDER .
    DIRECTORY_SEPARATOR . 'Config' .
    DIRECTORY_SEPARATOR . 'micro.database.php');

require_once(realpath('../') .
    DIRECTORY_SEPARATOR . PSR4_FOLDER .
    DIRECTORY_SEPARATOR . 'Config' .
    DIRECTORY_SEPARATOR . 'config.routes.regex.php');

$bootstrap = require_once(realpath('../').
	DIRECTORY_SEPARATOR . PSR4_FOLDER.
	DIRECTORY_SEPARATOR . 'Config'.
	DIRECTORY_SEPARATOR . 'bootstrap.php' );
