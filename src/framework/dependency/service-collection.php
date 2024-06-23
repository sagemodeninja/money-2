<?php
namespace Framework\Dependency;

use Exception;
use ReflectionClass;

class ServiceCollection
{
    public array $services;

    /**
     * Add a service to the collection.
     * 
     * @param string $class The fully qualified class name of the service.
     * @param callable $constructor A function called to manually
     * create an instance of the service.
     * 
     * @return ServiceCollection This collection instance.
     */
    public function addService(string $class, callable $constructor = null)
    {
        # Check for dependencies.
        $reflection = new ReflectionClass($class);
        $parameters = $reflection->getConstructor()->getParameters();

        $dependencies = array_map(
            fn ($p) => self::resolveDependency($p),
            $parameters
        );

        # Itterate through dependencies and add if missing.
        foreach ($dependencies as $dependency)
        {
            if (isset($dependency) &&
                !array_key_exists($dependency, $this->services))
            {
                $this->addService($dependency);
            }
        }

        $this->services[$class] = new Service(
            $class,
            $dependencies,
            $constructor,
        );

        return $this;
    }

    public function getService(string $class)
    {
        $service = $this->services[$class] ?? null;

        if (!isset($service))
        {
            throw new Exception("The service '$class' was not registered on this collection.");
        }

        # If service has a contructor, use it instead.
        if (isset($service->constructor))
        {
            return $service->constructor->call($this, [$this]);
        }

        # Otherwise construct the service.
        $reflection = new ReflectionClass($service->class);

        # Resolve dependencies. This process is recursive!
        $args = array_map(
            fn ($dependency) => isset($dependency) ? $this->getService($dependency) : null,
            $service->dependencies
        );

        return $reflection->newInstance(...$args);
    }

    private static function resolveDependency($parameter)
    {
        $type = $parameter->getType();

        return isset($type) && !$type->isBuiltin()
            ? $type->getName()
            : null;
    }
}
?>