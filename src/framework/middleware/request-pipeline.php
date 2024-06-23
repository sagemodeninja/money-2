<?php
namespace Framework\Middleware;

use ReflectionClass;
use Framework\Dependency\ServiceCollection;
use Framework\Http\HttpRequest;
use Framework\Middleware\RequestPipelineBuilder;

class RequestPipeline
{
    private array $middlewares;

    public function __construct(array $middlewares)
    {
        $this->middlewares = $middlewares;
    }

    /**
     * Registers request pipeline into a service collection.
     * 
     * @param ServiceCollection $services The collection where the pipeline will be registered.
     * @param callable $builderCallback A function invoked to build the pipeline.
     * 
     * @return ServiceCollection An instance of the service collection.
     * 
     * @example
     * ```php
     * $services = new ServiceCollection();
     * RequestPipeline::register($services, function (RequestPipelineBuilder $builder) {
     *  # Add middlewares here.
     * })
     * ```
     */
    public static function register(ServiceCollection $services, callable $builderCallback): ServiceCollection
    {
        $services->addService(self::class, function ($instance) use ($builderCallback) {
            $builder = new RequestPipelineBuilder();
            call_user_func($builderCallback, $builder);
            return new RequestPipeline($builder->build());
        });

        return $services;
    }

    public function handleRequest(HttpRequest $request)
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

    private function constructMiddleware(string $class, mixed $next = null) {
        $reflection = new ReflectionClass($class);

        if (isset($next) && $reflection->hasMethod('__construct'))
        {
            return $reflection->newInstanceArgs([$next]);
        }

        return new $class;
    }
}
?>