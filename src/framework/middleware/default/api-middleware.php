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
        $path = $request->path;
        $fragments = explode('/', $path);
        
        if (count($fragments) < 2 || $fragments[0] != 'api' || !$this->controllers->exists($fragments[1]))
        {
            return new HttpResponse(404);
        }

        $instance = $this->controllers->getInstance($fragments[1]);
        return $instance->handleRequest($request);
    }
}
?>