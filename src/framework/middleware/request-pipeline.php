<?php
namespace Framework\Middleware;

use Exception;
use ReflectionClass;
use Framework\Dependency\ServiceCollection;
use Framework\Http\HttpRequest;
use Framework\Http\HttpResponse;

class RequestPipeline
{
    private ServiceCollection $services;
    private array $middlewares;

    public function __construct(ServiceCollection $services, array $middlewares)
    {
        $this->services = $services;
        $this->middlewares = $middlewares;
    }

    public function handleRequest(HttpRequest $request)
    {
        try
        {
            $current = null;

            foreach ($this->middlewares as $middleware)
            {
                $next = isset($current) ? function ($request) use ($current) {
                    return $current->invoke($request);
                } : null;
    
                $current = $this->constructMiddleware($middleware, $next);
            }
            
            return $current->invoke($request);
        }
        catch (Exception $e)
        {
            return new HttpResponse(500, $e->getMessage());
        }
    }

    private function constructMiddleware(string $class, callable $next = null)
    {
        $reflection = new ReflectionClass($class);

        $parameters = $reflection->getConstructor()
            ? $reflection->getConstructor()->getParameters()
            : [];

        $args = array_map(
            function ($param) use ($next) { 
                $type = $param->getType();
                return $type == 'callable'
                    ? $next
                    : $this->services->getService($type->getName());
            },
            $parameters
        );
        
        return $reflection->newInstance(...$args);
    }
}
?>