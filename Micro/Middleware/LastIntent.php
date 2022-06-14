<?php


namespace API\Middleware;


use API\Core\Session\Session;
use API\Core\Utils\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LastIntent implements MiddlewareInterface
{

    private array $ignored;

    public function __construct(array $ignored)
    {
        $this->ignored = $ignored;
    }
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $regex = "#^/api/#";
        if( preg_match($regex, $url = (string) $request->getUri()->getPath())){
            return $handler->handle($request);
        }
        $requestMethod = $request->getMethod();
        $url = (string) $request->getUri()->getPath();
        if(!isset(pathinfo($url)['extension']) && $requestMethod === 'GET'){
            if(!in_array($url, $this->ignored)){
                Session::set('LAST_INTENT',$url);
//                Logger::log('LAST_INTENT ->' . $url);
            }
        }
        return $handler->handle($request);
    }
}