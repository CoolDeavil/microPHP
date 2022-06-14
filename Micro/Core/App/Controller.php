<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 28/04/2019
 * Time: 23:11
 */

namespace API\Core\App;


use GuzzleHttp\Psr7\Response;
use API\Interfaces\RenderInterface;
use API\Interfaces\RouterInterface;
use API\Core\Render\TwigRenderer;
use API\Core\Session\Session;
use API\Core\Utils\Logger;
use API\Core\Utils\Validator;
use Psr\Http\Message\ServerRequestInterface;

class Controller
{
    protected RenderInterface $render;
    public RouterInterface $router;
    protected Validator $validator;
    /**
     * Controller constructor.
     * @param RenderInterface $render
     * @param RouterInterface $router
     * @param Validator $validator
     */
    public function __construct(RouterInterface $router, RenderInterface $render , Validator $validator)
    {
        $this->render = $render;
        $this->router = $router;
        $this->validator = $validator;
    }

    protected function handleResponse(
        ServerRequestInterface $request,
        bool $status,
        array $payload,
        string $location
    ) : Response
    {
        $status?$http_code=200:$http_code=401;
        $method = $request->getHeader('X-Requested-With');
        if (isset($method[0]) && $method[0] == 'XMLHttpRequest') {
            $response = new Response();
            $response->getBody()->write(json_encode($payload['data']));
            return $response
                ->withStatus($http_code)
                ->withHeader('Content-Type', 'application/json');
        } else {
            if(isset($payload['flash'])){
                Session::set('TOAST', json_encode($payload['flash']));;
            }
            Session::set('REDIRECT_DATA', serialize($payload));
            return (new Response())
                ->withStatus($http_code)
                ->withHeader('Location', $location);
        }
    }

    protected function resolveRedirectData(array $keys) : array {
        $redirect =  Session::get('REDIRECT_DATA');
        $redirect =  unserialize($redirect);
        Session::unsetKey('REDIRECT_DATA');
        if($redirect){
            extract($redirect, EXTR_SKIP );
            $data = [];
            foreach ($keys as $key) {
                if(isset($redirect[$key])){
                    $data[$key] = json_encode($redirect[$key]);
                } else{
                    $data[$key] = json_encode([]);
                }
            }
        }else{
            $data = [];
            foreach ($keys as $key) {
                $data[$key] = json_encode([]);
            }
        }
        return $data;
    }

    protected function executeAsyncShellCommand($command = null){
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            system($command." > NUL");
        }else{
            shell_exec("/usr/bin/nohup ".$command." >/dev/null 2>&1 &");
        }
    }


}
