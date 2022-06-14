<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 08/02/2019
 * Time: 02:33
 */

namespace API\Middleware;

use API\Core\Utils\Logger;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TrailingSlash implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $url = $request->getUri()->getPath();
        if ($url !== '/') {
            if (!empty($url) && substr($url, -1) === "/") {
                return (new Response())
                    ->withStatus(301)
                    ->withHeader('location', substr($url, 0, -1));
            }
        }
        $request->withAttribute('TrailingSlash', 'Done');

        return $handler->handle($request);
    }
}
