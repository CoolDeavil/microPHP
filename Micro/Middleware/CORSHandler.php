<?php


namespace API\Middleware;


use API\Core\Utils\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
//const BASE_URL = `192.168.12.12`;

class CORSHandler  implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Resolve Before
        $request = $handler->handle($request);
        $allowed = include_once ALLOWED_CORS_FILE;
        array_unshift($allowed,"http://$_SERVER[HTTP_HOST]");


        if (isset($_SERVER['HTTP_ORIGIN'])) {
            if( !in_array($_SERVER['HTTP_ORIGIN'], $allowed) ) {
                Logger::log('Unauthorized CORS access: ' . $_SERVER['HTTP_ORIGIN'] );
                exit(0);
            }
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day

        }
        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            exit(0);
        }
        return $request;
    }
}