<?php


//this middleware will accept only application/json for PUT and POST requests
class JsonMiddleware
{
    /**
     * Example middleware invokable class
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        $method = $request->getMethod();
        $content_type = $request->getContentType();
        $response->write($method);
        $response->write($content_type);
        if(in_array($method, ['PUT', 'POST']) && $content_type != 'application/json'){
            return $response->withJson(['message' => 'content type must be application/json'],400);
        }
        $response = $next($request, $response);

        return $response;
    }
}
