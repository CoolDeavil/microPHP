<?php

namespace API\Controllers;

use API\Repository\GeneralRepository;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use API\Interfaces\RenderInterface;
use API\Interfaces\RouterInterface;
use API\Core\App\Controller;
use API\Core\Session\Session;
use API\Core\Utils\Validator;
use RuntimeException;

/**
 * Class MicroController
 * @package Micro\Controllers
 */
class MicroController extends Controller
{
    private GeneralRepository $repo;

    public function __construct(
        RouterInterface $router, RenderInterface $render,
        Validator $validator
    )
    {
        parent::__construct($router, $render, $validator);
        $this->render = $render;
        $this->router = $router;
        $this->validator = $validator;
        $this->router->post('/api/switchLang', [$this, 'switchLanguage'], 'Micro.switchLanguage');
        $this->router->get('/', [$this, 'index'], 'Micro.index');
        $this->router->get('/one-dummy-for-all/:demo', [$this, 'showWebPage'], 'Micro.showWebPage');
    }
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return Response
     */
    public function index(ServerRequestInterface $request, ResponseInterface $response): Response
    {
        $view = (string)$this->render->render('landing');
        $response->getBody()->write($view);
//        $response->getBody()->write(json_encode(['root'=> true] ));
        return $response;
    }
    public function showWebPage(ServerRequestInterface $request, ResponseInterface $response): Response
    {
        $params = $request->getAttribute('PARAMS');
        extract($params);
        /**@var $demo string $ */
        $view = (string)$this->render->render('dummyForAll',['from' => $demo]);
        $response->getBody()->write($view);
        return $response;
    }
    public function switchLanguage(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $locales = [
            'pt' => 'pt_PT',
            'en' => 'en_GB',
            'fr' => 'fr_FR'
        ];
        Session::set('ACTIVE_LANG', $request->getParsedBody()['language']);
        Session::set('LOCALE', $locales[$request->getParsedBody()['language']]);
        return (new Response())
            ->withStatus(200)
            ->withHeader('Location', Session::get('LAST_INTENT'));
    }
}
