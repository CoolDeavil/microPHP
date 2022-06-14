<?php
use API\Core\Container\MicroDI;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;

require_once(realpath('../') .
    DIRECTORY_SEPARATOR . 'Micro' .
    DIRECTORY_SEPARATOR . 'Config' .
    DIRECTORY_SEPARATOR . 'includes.php');

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);


/**@var $bootstrap */
$ioc = MicroDI::getInstance($bootstrap);

$request = ServerRequest::fromGlobals();
$response = new Response();

$response = (new API\Core\App\Micro($ioc))
    ->run($request);
Http\Response\send($response);
