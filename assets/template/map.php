<?php

use API\Core\Container\MicroDI;
use API\Core\Router\MRoute;
use API\Core\Router\MRouter;
use GuzzleHttp\Psr7\ServerRequest;

error_reporting(0);

require_once(realpath('../') .
    DIRECTORY_SEPARATOR . 'Micro' .
    DIRECTORY_SEPARATOR . 'Config' .
    DIRECTORY_SEPARATOR . 'includes.php');


$bootstrap = include(realpath('../') .
    DIRECTORY_SEPARATOR . 'Micro' .
    DIRECTORY_SEPARATOR . 'Config' .
    DIRECTORY_SEPARATOR . 'bootstrap.php');

$request = ServerRequest::fromGlobals();
$container = MicroDI::getInstance($bootstrap);
$router = $container->get(\API\Interfaces\RouterInterface::class);

$dl_h = html_entity_decode('─', ENT_NOQUOTES, 'UTF-8');  // horizontal wall
$mask = "|%6.6s | %-40.40s | %-20.20s | %-20.20s |\n";

echo str_repeat($dl_h, 98);
printf("\033[32m\n");
printf($mask, 'Method', 'Route','Controller','Action');
printf(str_repeat($dl_h, 98));
printf("\033[37m\n");

$methods = ['GET','POST','PATCH','DELETE'];
$routeMethods=[];
$tCount=0;
$m = $container->get(\API\Core\App\Micro::class);
$routeList= $m->routeDetailMapCLI();

foreach ($methods as $method) {
    if(isset($routeList[$method])){
        $routeMethods[$method] = count($routeList[$method]);
        $tCount += count($routeList[$method]);
    }
}

foreach ($methods as $method){
    if(isset($routeList[$method])){
        foreach ($routeList[$method] as $rt ) {
            /**@var $rt MRoute */
            printf($mask, $method, $rt->getRoute(),$rt->getCallableMap(),$rt->getActionMap());
        }
    }
}

$sep = str_repeat($dl_h, 97);
printf("\033[32m$sep\n");
printf("\033[32mRoutes Available \033[33m $tCount \n");
printf("\033[37m");
$mask = "| %-11.11s | %-10.10s | %-10.10s | %-10.10s |\n";
printf($mask, 'GET','POST','PATCH','DELETE');
printf($mask,
    $routeMethods['GET'] ?? 0,
    $routeMethods['POST'] ?? 0,
    $routeMethods['PATCH'] ?? 0,
    $routeMethods['DELETE'] ?? 0
);
printf("\033[37m\n");
