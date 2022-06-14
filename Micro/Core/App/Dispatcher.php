<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 08/02/2019
 * Time: 02:08
 */

namespace API\Core\App;

use API\Interfaces\ContainerInterface;
use API\Interfaces\RenderInterface;
use API\Interfaces\RouterInterface;
use GuzzleHttp\Psr7\Response;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Dispatcher implements MiddlewareInterface
{

    private RouterInterface $router;
    private ContainerInterface $ioc;

    public function __construct(ContainerInterface $ioc)
    {
        $this->router = $ioc->get(RouterInterface::class);
        $this->ioc = $ioc;
    }

    function process(ServerRequestInterface $request, RequestHandlerInterface $handler) :ResponseInterface
    {
        $matched = $this->router->getMatchedRoute();
        $response=new Response();
        if (!is_bool($matched)) {
            $request = $request->withAttribute('PARAMS', $matched->getParams());
            $response = new Response();
            return call_user_func_array($this->router->getExecutable($matched),[$request,$response]);
        }
        $render = $this->ioc->get(RenderInterface::class);
        $view = (string)$render->render("404View",['ip' =>  $request->getUri()->getPath()]);
        $response->getBody()->write($view,) ;
//        $response->getBody()->write('<strong style="color: red">404</strong> Dispatcher Route Not Found&emsp;&emsp;' . $request->getUri()->getPath()) ;
        return $response->withStatus(404);
    }
}
