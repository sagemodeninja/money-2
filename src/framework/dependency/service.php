<?php
namespace Framework\Dependency;

use Closure;

class Service
{
    public string $class;
    public array $dependencies;
    public ?Closure $constructor;
    
    public function __construct(string $class, array $dependencies, callable $constructor = null)
    {
        $this->class = $class;
        $this->dependencies = $dependencies;
        $this->constructor = $constructor;
    }
}
?>