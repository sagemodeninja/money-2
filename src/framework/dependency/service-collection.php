<?php
namespace Framework\Dependency;

use Exception;
use ReflectionClass;

class ServiceCollection
{
    public array $services = [];

    /**
     * Adds a service to the collection.
     * 
     * @param string $service The fully qualified class name, an instance, or a
     * function that returns an instance of the service.
     * 
     * @return ServiceCollection This collection instance.
     */
    public function addService(string|object|callable $service)
    {
        $instance = null;

        if (!is_string($service))
        {
            $instance = is_callable($service)
                ? call_user_func($service, $this)
                : $service;
        }

        $class = is_string($service)
            ? $service
            : get_class($instance);

        # Check for dependencies.
        $reflection = new ReflectionClass($class);
        $parameters = $reflection->getConstructor()
            ? $reflection->getConstructor()->getParameters()
            : [];

        $dependencies = array_map(
            function ($p) {
                $type = $p->getType();
                return isset($type) && !$type->isBuiltin()
                    ? $type->getName()
                    : null;
            },
            $parameters
        );

        # Itterate through dependencies and add if missing.
        foreach ($dependencies as $dependency)
        {
            if (isset($dependency) &&
                $dependency != self::class &&
                !array_key_exists($dependency, $this->services))
            {
                $this->addService($dependency);
            }
        }

        $this->services[$class] = new Service(
            $class,
            $dependencies,
            $instance
        );

        return $this;
    }

    public function getService(string $class)
    {
        if ($class == self::class)
        {
            return $this;
        }

        $service = $this->services[$class] ?? null;

        if (!isset($service))
        {
            throw new Exception("The service '$class' was not registered on this collection.");
        }

        # If instance is alread present, return it.
        if (isset($service->instance))
        {
            return $service->instance;
        }
        else
        {
            # Otherwise construct the service.
            $reflection = new ReflectionClass($service->class);

            # Resolve dependencies. This process is recursive!
            $args = array_map(
                function ($dependency) {
                    if (!isset($dependency)) return null;

                    return $dependency != self::class
                        ? $this->getService($dependency)
                        : $this;
                },
                $service->dependencies
            );

            return $reflection->newInstance(...$args);
        }
    }
}
?>