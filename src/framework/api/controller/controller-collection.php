<?php
namespace Framework\Api\Controller;

use Framework\Dependency\ServiceCollection;

class ControllerCollection
{
    private ServiceCollection $services;
    private array $controllers;

    public function __construct(ServiceCollection $services, array $controllers)
    {
        $this->services = $services;
        $this->controllers = $controllers;    
    }

    public static function load(ServiceCollection $services)
    {
        $root = 'api/controllers';
        $nodes = array_diff(scandir($root), ['.', '..']);

        $controllers = [];
    
        foreach ($nodes as $node) {
            $path = $root . DIRECTORY_SEPARATOR . $node;

            if (is_file($path) && str_ends_with($node, '-controller.php')) {
                $name = str_replace('.php', '', $node);
                $key = str_replace(['-controller.php', '-'], '', $node);

                $class = str_replace('-', '', ucwords($name, '-'));
                $namespace = "Api\\Controllers\\$class";

                $services->addService($namespace);
                $controllers[$key] = $namespace;
            }
        }

        $instance = new ControllerCollection($services, $controllers);
        $services->addService($instance);
    }

    public function exists(string $key): bool
    {
        return array_key_exists($key, $this->controllers);
    }

    public function getInstance(string $controller)
    {
        $class = $this->controllers[$controller];
        return $this->services->getService($class);
    }
}
?>