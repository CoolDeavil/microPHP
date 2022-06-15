<?php


namespace API\Controllers;


use API\Core\App\Controller;
use API\Core\Session\Session;
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

        $this->router->get('/php-raw-form',[$this,'index'],'Check.rawValidation');
        $this->router->post('/api/raw-validation', [$this, 'validateRaw'], 'Check.validateRaw');
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response): Response
    {

        $skills = [
            'Front End Graphics',
            'PHP Development',
            'Javascript',
            'JS Frameworks',
            '(CSS/SCSS) Style Cheats',
        ];
        $data = $this->resolveRedirectData([
            'oldData',
            'errors',
        ]);
        $view = (string)$this->render->render("phpRawValidation", [
            'oldData' => json_decode($data['oldData']),
            'errors' => json_decode($data['errors']),
            'skills' => $skills
        ]);
        $response->getBody()->write($view);
        return $response;

    }

    public function validateRaw(ServerRequestInterface $request, Response $response) : Response
    {
        $this->validator->init($request);
        $this->validator->field('name')->rule('minLength')->val(8);
        $this->validator->field('email')->rule('checkEmail');
        $this->validator->field('skill')->rule('noZeroSelection');
        $this->validator->field('about')->rule('minLength')->val(8);
        if (!$this->validator->fetch('terms')) {
            $this->validator->set('terms', '');
        }
        $this->validator->field('terms')->rule('checked')->val("on");

        if ($this->validator->validate()) {
            $result = true;
            $payload = [];
        } else {
            $result = false;
            $payload = [
                'oldData' => $this->validator->fetchAll(),
                'errors' => $this->validator->fetchErrors(),
            ];
        }
        return $this->handleResponse(
            $request,
            $result,
            $payload,
            $result ? Session::get('LAST_INTENT') : $this->router->generateURI('Check.rawValidation', [])
        );
    }

}