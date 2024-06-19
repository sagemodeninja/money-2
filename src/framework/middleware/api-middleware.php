<?php
    namespace Framework\Middleware;

    use Framework\Api\ControllerCollection;
    use Framework\Http\HttpRequest;
    use Framework\Http\HttpResponse;

    class ApiMiddleware
    {
        public function invoke(HttpRequest $request)
        {
            // api/[controller]/[route]
            $path = $request->path;
            $fragments = explode('/', $path);
            
            if (count($fragments) < 2 || $fragments[0] != 'api')
            {
                return new HttpResponse(404);
            }
            
            $collection = ControllerCollection::load();
            $controller = $fragments[1];

            if (!$collection->exists($controller))
            {
                return new HttpResponse(404);
            }

            $instance = $collection->getInstance($controller);
            return $instance->handleRequest($request);
        }
    }
?>