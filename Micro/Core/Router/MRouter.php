<?php

namespace API\Core\Router;

use API\Core\Utils\Logger;
use API\Interfaces\ContainerInterface;
use API\Interfaces\RenderInterface;
use JetBrains\PhpStorm\Pure;
use GuzzleHttp\Psr7\Request;
use API\Interfaces\RouterInterface;

class MRouter implements RouterInterface {

    private static ?MRouter $instance = null;
    private static string $method = 'GET';
    public static array $routes = [];
    public static array $vars = [];
    private ContainerInterface $ioc;

    private function __construct(Request $request, ContainerInterface $ioc){

        $this->ioc = $ioc;
    }

    public static function getInstance(Request $request,$ioc): ?MRouter
    {
        if (!self::$instance) {
            self::$instance = new self($request,$ioc);
        }
        return self::$instance;
    }
    public function get($path, $callable, $name = null): MRoute
    {
        return self::add("GET", $path, $callable, $name);
    }
    public function post($path, $callable, $name = null): MRoute
    {
        return self::add("POST", $path, $callable, $name);
    }
    public function patch($path, $callable, $name = null): bool|MRoute
    {
        return self::add("PATCH", $path, $callable, $name);
    }
    public function delete($path, $callable, $name = null): bool|MRoute
    {
        return self::add("DELETE", $path, $callable, $name);
    }
    public function resource($route, $callable, $name, $regexp = null, $protected = false): array
    {
        $type = is_string($callable)?"STRING":"OBJECT";
        $RESTFulRoutes = $this->generateResourceRoutes($route, $callable, $type);
        foreach ($RESTFulRoutes as $resource) {
//            /***@Var $route Micro\App\MRouter\MRoute */
            $route = $this->add(
                $resource['method'],
                $resource['route'],
                $resource['callable'],
                $name . "." . $resource['action']
            );
            if (strpos($resource["route"], NEEDLE)) {
                if ($regexp) {
                    $route->with('id', $regexp);
                }
            }
            if ($protected) {
                $route->middleware([\API\Middleware\AuthorMiddleware::class]);
            }
        }
        return self::$routes;
    }
    private function generateResourceRoutes($route, $callable,$type ): array
    {
        $getCallable = function($method) use( $callable,$type ){
            if( $type  === 'STRING' ){
                return "$callable@$method";
            }
            return [$callable,$method];
        };
        $rt_param = ROUTE_PARAM;

        return  [
            [
                "method"=>"GET",
                "route" => "$route",    // (index)  Get all Items
                "action" => "index",
                'callable' => $getCallable("index")
            ],
            [
                "method"=>"GET",
                "route" => "$route/create", // (Form Input) Show Form for new Item
                'callable' => $getCallable("create"),
                "action" => "create",
            ],
            [
                "method"=>"GET",
                "route" => "$route/$rt_param", // (show) Show Item by ID
                'callable' => $getCallable('show'),
                "action" => "show"
            ],
            [
                "method"=>"GET",
                "route" => "$route/$rt_param/edit", // (edit)
                'callable' => $getCallable('edit'),
                "action" => "edit"
            ],
            [
                "method"=>"POST",
                "route" => "$route",    // (store)
                'callable' => $getCallable('store'),
                "action" => "store"
            ],
            [
                "method"=>"DELETE",
                "route" => "$route/$rt_param", // (filter ) -> Update or Delete
                'callable' => $getCallable('destroy'),
                "action" => "destroy"
            ],
            [
                "method"=>"PATCH",
                "route" => "$route/$rt_param", // (filter ) -> Update or Delete
                'callable' => $getCallable('update'),
                "action" => "update"
            ]
        ];
    }
    #[Pure] public function getMatchedRoute(): bool|MRoute
    {
        if(isset(self::$routes[ self::$method ])){
            foreach (self::$routes[ self::$method ] as $route) {
                /**@var $route MRoute */
                if ($route->isMatched()) {
                    return $route;
                };
            }
        }
        return false;
    }
    public function dispatch(Request $request): bool
    {
        self::$method = $request->getMethod();

        if(!isset(self::$routes[self::$method ] )){
            return false;
        }
        foreach (self::$routes[self::$method ] as $route) {
            /**@var $route MRoute */
            if ($route->match($request->getUri()->getPath())) {
                $route->setMatched(true);
                return true;
            }
        }
        return false;
    }
    public function generateURI($slug, array $params = null) : string
    {
        /**@var $route MRoute */
        $methods = ['GET','POST','PATCH','DELETE'];
        foreach ($methods as $method ){
            if(isset(self::$routes[$method])){
                foreach (self::$routes[$method] as $route ){
                    if($route->getName() === $slug ) {
                        $path = $route->getRoute();
                        if($params){
                            foreach ($params as $k=>$v){
                                $path = str_replace($this->replaceRouteParams($k), $v, $path);
                            }
                        }
                        return $path;
                    }
                }

            }
        }
        return '';
    }
    public function replaceRouteParams($value): string
    {
        if(NEEDLE === NEEDLE_TWO_POINTS ){
            return NEEDLE_TWO_POINTS . $value;
        }else {
            return '{' . $value .'}';
        }
    }
    public function setMethod(Request $request)
    {
        // TODO: Implement setMethod() method.
    }
    public function add($method, $path, $callable, $name = null): bool|MRoute
    {
        if( $duplicated = self::isDuplicateRoute($method,$path)){
            $route = $duplicated;
        } else {
            $route = $this->ioc->get(\API\Core\Router\MRoute::class,[
                'route' => $path,
                'callable' => $callable,
                'method' => $method,
                'name' => $name,
            ]);
            self::$routes[$method][] = $route;
        }
        return $route;
    }
    #[Pure] private static function isDuplicateRoute($method, $route): bool|MRoute
    {
        /**@var $rt MRoute */
        if(isset(self::$routes[$method])){
            foreach (self::$routes[$method] as $rt ){
                if($rt->getRoute() === $route ) {
                    return $rt;
                }
            }
        }
        return false;
    }
    public static function getAllRoutes() : array
    {
        $methods = ['GET','POST','PATCH','DELETE'];
        $rtList = [];
        foreach ($methods as $method) {
            if(isset(self::$routes[$method])){
                $rtList[$method] = [];
                foreach (self::$routes[$method] as $route) {
                    $params = [];
                    $rp = $route->getParams();
                    foreach ( $rp as $key => $value) {
                        $params[] = [
                            'key' => $key,
                            'value' => $value,
                        ];
                    }
                    $rtList[$method][]= [
                        'method' => $method,
                        'route' => $route->getRoute(),
                        'controller' => $route->getCallableMap(),
                        'action' => $route->getActionMap(),
                        'parameters' => $params,
                    ];
                }
            }
        }
        return $rtList;
    }
    public static function getAllRoutesCLI() : array
    {
        return self::$routes;
    }
    public function getExecutable($matched): array|callable
    {
        $callable = $matched->getCallable();
        $dependencies = [
            'router' => $this->ioc->get(RouterInterface::class),
            'render' => $this->ioc->get(RenderInterface::class),
        ];
        if(is_string($callable)) {
            if (is_bool(strpos($callable[0], '@'))) {
                $parts = explode('@', $callable);
                $controller = $this->ioc->get('API\Controllers\\' . $parts[0], $dependencies);
                $method = $parts[1];
                return ([
                    $controller,
                    $method
                ]);
            } else {
                die("Route syntax error missing @ method separator; ");
            }
        } elseif (is_array($callable)){
            switch (gettype($callable[0])){
                case 'string':
                    return [
                        $this->ioc->get($callable[0],$dependencies),
                        $callable[1]
                    ];
                    break;
                case 'object':
                    return $callable;
                    break;
            }
        }elseif (is_object($callable)){
            return $callable;
        }
        return [];
    }
}
