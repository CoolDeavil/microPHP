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
        Validator $validator,
        GeneralRepository $repo
    )
    {
        parent::__construct($router, $render, $validator);
        $this->render = $render;
        $this->router = $router;
        $this->validator = $validator;
        $this->repo = $repo;

        $this->router->post('/api/switchLang', [$this, 'switchLanguage'], 'Micro.switchLanguage');
        $this->router->get('/', [$this, 'index'], 'Micro.index');
        $this->router->post('/api-end-point', [$this, 'dataEndPoint'], 'Micro.dataEndPoint');
        $this->router->post('/api-end-point2', [$this, 'dataEndPoint2'], 'Micro.dataEndPoint2');
        $this->router->get('/api-mock-data', [$this, 'mockDataService'], 'Micro.mockDataService');
        $this->router->get('/api/show', [$this, 'listCountries'], 'Micro.listCountries');
        $this->router->get('/api/calendar/:month/:year', [$this, 'calendar'], 'Micro.listCountries');
        $this->router->get('/all-gadgets', [$this, 'allGadgets'], 'Micro.allGadgets');
        $this->router->get('/image-b64-convertor', [$this, 'convertor'], 'Micro.convertor');
        $this->router->post('/api/image-b64', [$this, 'getB64Encoded'], 'Micro.getB64Encoded');
        $this->router->get('/api/autocomplete', [$this, 'autocomplete'], 'Micro.autocomplete');
        $this->router->get('/countries-data', [$this, 'countries'], 'Micro.countries');

        $this->router->get('/php-raw-form',[$this,'rawValidation'],'Micro.rawValidation');
        $this->router->post('/api/raw-validation', [$this, 'validateRaw'], 'Micro.validateRaw');
        $this->router->get('/routes-mapping', [$this, 'showRoutes'], 'Micro.showRoutes');
        $this->router->post('/api/validations', [$this, 'validations'], 'Micro.validations');
        $this->router->post('/api/validations/deny', [$this, 'deny'], 'Micro.deny');
        $this->router->get('/api/async/:dummy', [$this, 'asyncTest'], 'Micro.asyncTest');
        $this->router->get('/api/validation/:demo', [$this, 'showWebPage'], 'Micro.showWebPage');
        $this->router->get('/api/black-listed', [$this, 'forms'], 'Micro.forms');
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


    public function asyncTest(ServerRequestInterface $request, ResponseInterface $response): Response
    {
        //        $month = $request->getQueryParams()['month'];
        $params = $request->getAttribute('PARAMS');
        extract($params);
        /**@var $dummy string $ */
        $response->getBody()->write(json_encode([
            'status' => $dummy != 'dummy',
//            'status' => true,
            'label' =>'You are on the black list...',
        ], JSON_PRETTY_PRINT));
        /**@var $response  Response */
        return $response;
    }
    public function forms(ServerRequestInterface $request, ResponseInterface $response): Response
    {

        $u = $this->repo->UserAvatarByEmail($request->getQueryParams()['email']);
        $response->getBody()->write(json_encode([
            'status' => empty($u),
            'label'=>'You are on the Blacklist'
        ]));

        /**@var $response  Response */
        return $response;
    }
    public function validations(ServerRequestInterface $request, ResponseInterface $response): Response
    {
        $response->getBody()->write(json_encode([
            'status' => true
        ]));
        return $response;
    }
    public function deny(ServerRequestInterface $request, ResponseInterface $response): Response
    {
        $response->getBody()->write(json_encode([
            'status' => false,
            'label'=>'You are on the Blacklist'
        ]));
        return $response;
    }
    public function countries(ServerRequestInterface $request, ResponseInterface $response): Response
    {


        $countries = $this->repo->getAllRecords();
        $view = (string)$this->render->render('countries',['list' => $countries]);


//        $cap = file_get_contents('countryCapitals.json');
//        $cp = json_decode($cap);
//        $capital  =  json_decode(json_encode($cp), true);
//
//        $j = file_get_contents('flags-64x64.json');
//        $d = json_decode($j);
//        // From STDClass to array
//        $flags = json_decode(json_encode($d), true);
//        $countries = $this->repo->getAllRecords();
//        $filter = function($needle) use($capital) {
//            foreach ($capital as $item){
//                if($item['country'] == $needle ){
//                    return $item['city'];
//                }
//            }
//            return false;
//        };

//        foreach ($countries as $country) {
//            $flag="";
//            $capital = $filter($country['name']);
//            if(isset($flags[strtolower($country['code'])])){
//                $flag=$flags[strtolower($country['code'])];
//            }
//            $id = $this->repo->registerCountry([
//                'id' =>  $country['id'],
//                'name' =>  $country['name'],
//                'name_official' =>  $country['name_official'],
//                'capital' =>  $capital,
//                'code' => $country['code'],
//                'latitude' => $country['latitude'],
//                'longitude' => $country['longitude'],
//                'flag' =>    $flag,
//            ]);
//
//            echo $id .'<br>';
//        }


//        foreach ($capital as $item){
//            dump($item['country']);
//            dump($item['city']);
//        }

//        $filter = function($needle) use($capital) {
//           foreach ($capital as $item){
//               if($item['country'] == $needle ){
//                   return $item['city'];
//               }
//           }
//            return false;
//        };
//        dump($filter('Portugal'));
//        foreach ($d as $k => $c){
//            dump($d);
//            dump($c);
//        }
//
//        $k = array_keys($d);
//        dump($k);

//        $array = get_object_vars($d);
//        $properties = array_keys($array);
//        dump($properties);

//        $array = json_decode(json_encode($d), true);
//
//        dump($array['pt']);
//        die;

        $response->getBody()->write($view);
        /**@var $response  Response */
        return $response;
    }
    public function convertor(ServerRequestInterface $request, ResponseInterface $response): Response
    {
        $view = (string)$this->render->render('convertor');
        $response->getBody()->write($view);
        /**@var $response  Response */
        return $response;
    }
    public function allGadgets(ServerRequestInterface $request, ResponseInterface $response): Response
    {
        $view = (string)$this->render->render('allGadgets');
        $response->getBody()->write($view);
        /**@var $response  Response */
        return $response;
    }
    public function autocomplete(ServerRequestInterface $request, ResponseInterface $response): Response
    {
        $view = (string)$this->render->render('autocomplete');
        $response->getBody()->write($view);
        /**@var $response  Response */
        return $response;
    }
    public function mockDataService(ServerRequestInterface $request, ResponseInterface $response): Response
    {
        $params = $request->getAttribute('PARAMS');
        extract($params);
        /**@var $needle string $ */



        $response->getBody()->write(json_encode($this->repo->getRecordsByNeedle($request->getQueryParams()['name'])));

//        file_put_contents("data.json", json_encode($this->repo->getGroupRecords(30)));

//        $response->getBody()->write(json_encode($this->repo->getGroupRecords(30)));
//        $response->getBody()->write(json_encode([]));
        /**@var $response  Response */
        return $response;
    }
    public function listCountries(ServerRequestInterface $request, ResponseInterface $response): Response
    {

        $view = (string)$this->render->render('countryList',['countries'=>$this->repo->getAllRecords()]);
        $response->getBody()->write($view);
        return $response;
    }
    public function calendar(ServerRequestInterface $request, ResponseInterface $response): Response
    {
//        $year = $request->getQueryParams()['year'];
//        $month = $request->getQueryParams()['month'];
        $params = $request->getAttribute('PARAMS');
        extract($params);
        /**@var $month string $ */
        /**@var $year string $ */
        $response->getBody()->write(json_encode($this->repo->getMonthData($month,$year)));
        return $response;
    }
    public function dataEndPoint(ServerRequestInterface $request, ResponseInterface $response): Response
    {
        $response->getBody()->write(json_encode([
            'valid' => true,
            'message' => "Invalidated by server review, try again",
        ]));
        return $response;
    }
    public function showRoutes(ServerRequestInterface $request, ResponseInterface $response): Response
    {
        $routes = $this->router->getAllRoutes();
        $view = (string)$this->render->render("routesList",['routes' => $routes]);
        $response->getBody()->write($view);
        return $response;
    }
    public function dataEndPoint2(ServerRequestInterface $request, ResponseInterface $response): Response
    {
        $response->getBody()->write(json_encode([
            'valid' => true,
            'message' => "Invalidated by server review, try again (2)",
        ]));
        return $response;
    }
    public function showWebPage(ServerRequestInterface $request, ResponseInterface $response): Response
    {
        $params = $request->getAttribute('PARAMS');
        extract($params);
        /**@var $demo string $ */
        $response->getBody()->write(json_encode([
            'status' => true,
            'label' => 'You Sent ' . $demo
        ]));

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

    public function getB64Encoded(ServerRequestInterface $request, ResponseInterface $response): Response
    {
        if (!isset($_FILES['image']['error']) || is_array($_FILES['image']['error'])) {
            throw new RuntimeException('No Files On Request.');
        }
        $output = imagecreatefromjpeg($_FILES['image']['tmp_name']);
        ob_start();
        imagejpeg($output);
        $buffer = ob_get_clean();
        ob_end_clean();
        $dataUri = "data:image/png;base64," . base64_encode($buffer);
        $response = new Response();
        $response->getBody()->write(json_encode([
            "result" => "ok",
            "dataUri" => $dataUri
        ]));
        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }

    public function rawValidation(ServerRequestInterface $request, Response $response): Response
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
//            $this->setFlashCookie([
//                'type' => 'success',
//                'title' => 'Form Submission',
//                'message' => 'Your data was stored successfully, nicely done!'
//            ]);
        } else {
            $result = false;
            $payload = [
                'oldData' => $this->validator->fetchAll(),
                'errors' => $this->validator->fetchErrors(),
            ];
//            $this->setFlashCookie([
//                'type' => 'error',
//                'title' => 'Form Submission',
//                'message' => 'Your data has a incongruity! Please review before post'
//            ]);

        }
        return $this->handleResponse(
            $request,
            $result,
            $payload,
            $result ? Session::get('LAST_INTENT') : $this->router->generateURI('Micro.rawValidation', [])
        );
    }

}
