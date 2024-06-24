<?php
namespace Framework\Middleware\Default;

use Framework\Api\Controller\ControllerCollection;
use Framework\Http\{HttpRequest,HttpResponse};

class ApiMiddleware
{
    private ControllerCollection $controllers;

    public function __construct(ControllerCollection $controllers)
    {
        $this->controllers = $controllers;
    }

    public function invoke(HttpRequest $request)
    {
        $path = strtolower($request->path);
        $controller = explode('/', $path)[1];
        
        # Check if route is valid and if controller exists.
        if (str_starts_with($path, 'api/') && $this->controllers->exists($controller))
        {
            $instance = $this->controllers->getInstance($controller);
            return $instance->handleRequest($request);
        }

        # Otherwise return HTTP 404
        return new HttpResponse(404);
    }
}
?>