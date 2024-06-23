<?php
namespace Framework\Dependency;

class Service
{
    public string $class;
    public array $dependencies;
    public mixed $instance;
    
    public function __construct(string $class, array $dependencies, mixed $instance)
    {
        $this->class = $class;
        $this->dependencies = $dependencies;
        $this->instance = $instance;
    }
}
?>