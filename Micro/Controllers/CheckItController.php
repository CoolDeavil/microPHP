<?php


namespace API\Controllers;


use API\Core\App\Controller;
use API\Core\Utils\Validator;
use API\Interfaces\RenderInterface;
use API\Interfaces\RouterInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CheckItController extends Controller
{

    public function __construct(RouterInterface $router, RenderInterface $render, Validator $validator)
    {
        parent::__construct($router, $render, $validator);
        $this->router->get('/framework-validation-redirect', [$this, 'index'], 'checkItController.index');

    }

    public function index(ServerRequestInterface $request, ResponseInterface $response): Response
    {
        $view = (string)$this->render->render('rawValidation');
        $response->getBody()->write($view);
        return $response;

    }
}