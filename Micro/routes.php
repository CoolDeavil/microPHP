<?php

use API\Core\Router\MRouter;
use API\Core\Session\Session;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

/**@var MRouter $router */

//$router->get('/', function(Request $request, Response $response) : Response {
//    $response->getBody()->write('You Hit Root Site');
//    return $response;
//})->middleware([\API\Middleware\SecureRouteMiddleware::class]);


//$router->get('/high-security', function(Request $request, Response $response) : Response {
//    $response->getBody()->write('You GOT Clearance');
//    return $response;
//})->middleware([\API\Middleware\SecureRouteMiddleware::class]);
//
//
//$router->get('/wikiContents', function (Request $request, Response $response): Response {
//    $html = file_get_contents('https://en.wikipedia.org/wiki/'.$request->getQueryParams()['term']);
////    $response->getBody()->write(json_encode($html));
//    $response->getBody()->write($html);
//    return $response;
//});

//$router->post('/api/switchLang', function (Request $request, Response $response): Response {
//    $locales = [
//        'pt' => 'pt_PT',
//        'en' => 'en_GB',
//        'fr' => 'fr_FR'
//    ];
//    Session::set('ACTIVE_LANG', $request->getParsedBody()['language']);
//    Session::set('LOCALE', $locales[$request->getParsedBody()['language']]);
//    return $response
//        ->withStatus(200)
//        ->withHeader('Location', Session::get('LAST_INTENT'));
//});
