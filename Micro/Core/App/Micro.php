<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 08/02/2019
 * Time: 01:09
 */

namespace API\Core\App;

use API\Core\Session\Session;
use API\Interfaces\ContainerInterface;
use API\Interfaces\RenderInterface;
use API\Interfaces\RouterInterface;
use API\Middleware\LastIntent;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Micro implements RequestHandlerInterface
{
    private array $middleware= [];
    private array $modules= [];
    private int $index = 0;
    private ContainerInterface $ioc;
    private RouterInterface $router;
    private RenderInterface $render;
//    private Session $session;

    public function __construct(ContainerInterface $ioc)
    {
        $this->session = Session::getInstance();
        $this->ioc = $ioc;
        $this->router = $ioc->get(RouterInterface::class);
        $this->render = $ioc->get(RenderInterface::class);
    }
    public function addModule($module) : self
    {
        $this->modules[] = $module;
        return $this;
    }
    public function pipe($middleware) : self
    {
        $this->middleware[] = $middleware;
        return $this;
    }
    private function getMiddleware()
    {
        if (isset($this->middleware[$this->index])) {
            return $this->middleware[$this->index];
        }
        return null;
    }
    private function checkIfMatchedRoute(ServerRequestInterface $request): bool
    {
        $request = $this->setRequestMethod($request);
        $match = $this->router->dispatch($request);
        return (bool)$match;
    }
    private function setRequestMethod(ServerRequestInterface $request) : Request
    {
        $parsedBody = $request->getParsedBody();
        if (array_key_exists('_method', $parsedBody)) {
            $request=$request->withMethod($parsedBody['_method']);
        }
        return $request;
    }
    public function run(ServerRequestInterface $request) : ResponseInterface
    {
        # Initialize the App
        $this->bootstrap();
        # check if route has extra middleware
        if ($this->checkIfMatchedRoute($request)) {
            $routeMiddleware = $this->router->getMatchedRoute()->getMiddleware();
            if (isset($routeMiddleware[0])) {
                foreach ($routeMiddleware as $middleware) {
                    $this->middleware[] = $this->ioc->get($middleware);
                }
            }
        }
        # Add framework middleware
        $this->middleware[] = $this->ioc->get( LastIntent::class);
        $this->middleware[] = $this->ioc->get( Dispatcher::class);
        # Call the Handler
        return $this->handle($request);
    }
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $response = new Response();
        $middleware = $this->getMiddleware();
        $this->index++;

        if (is_null($middleware)) {
            return $response;
        }
        return $middleware->process($request, $this);
    }
    private function bootstrap()
    {
        if (Session::get('ACTIVE_LANG')) {
            $this->render->addGlobal('app_lang', Session::get('ACTIVE_LANG'));
            $flag = Session::get('ACTIVE_LANG').'.png';
        } else {
            $this->render->addGlobal('app_lang', APP_LANG);
            $flag = APP_LANG.'.png';
        }
        $this->render->addGlobal('app_flag', DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$flag);
        $this->render->addGlobal('cur_page',parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        $router = $this->router;
        include_once APP_ROUTES_FILE;




//        $r = $this->router::getAllRoutes();
//
//        dump($r);
//        die;

        $modules = include_once MODULES_PIPE;
        $pipeline = array_unique(array_merge(
            $modules,
            $this->modules
        ),SORT_REGULAR);
        $this->modules=[];
        $params = [
            'router' => $this->router,
            'render' => $this->render,
        ];
        foreach ($pipeline as $module) {
            $this->modules[] = $this->ioc->get($module, $params);
        }
        unset($pipeline);
        $middlewarePipe = include_once MIDDLEWARE_PIPE;
        $pipeline = array_unique(array_merge(
            $middlewarePipe,
            $this->middleware
        ),SORT_REGULAR);
        $this->middleware=[];
        foreach ($pipeline as $middleware){
            $this->middleware[] = $this->ioc->get($middleware);
        }
        unset($pipeline);
        unset($router);
        unset($modules);
        unset($middlewarePipe);
    }
    public function loadModules()
    {
        $modules = include_once MODULES_PIPE;
        $params = [
            'router' => $this->router,
            'render' => $this->render,
        ];
        foreach ($modules as $module) {
            $this->modules[] = $this->ioc->get($module, $params);
        }
    }
    public function routeDetailMapCLI(): array
    {
        $router = $this->router;
        include APP_ROUTES_FILE;
        $this->loadModules();
        unset($this->modules);
        unset($router);
        return $this->router->getAllRoutesCLI();
    }
}
