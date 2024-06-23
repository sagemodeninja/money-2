<?php

use Framework\Api\Controller\ControllerCollection;
use Framework\Dependency\ServiceCollection;
use Framework\Middleware\{RequestPipelineBuilder,RequestPipeline};

const CONTROLLER_PATTERN = '/^([a-z0-9-]+-controller)\.php$/';

function addMiddlewares(ServiceCollection $services, callable $callback): ServiceCollection
{
    # Build and get list of middlewares.
    $builder = new RequestPipelineBuilder();
    call_user_func($callback, $builder);
    $middlewares = $builder->build();

    # Register middlewares.
    foreach ($middlewares as $middleware)
    {
        $services->addService($middleware);
    }

    $instance = new RequestPipeline($services, $middlewares);
    $services->addService($instance);

    return $services;
}

function addControllers(ServiceCollection $services)
{
    ControllerCollection::load($services);
}
?>