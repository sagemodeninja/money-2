<?php
namespace Framework\Middleware;

/**
 * A builder class for constructing the middleware request pipeline.
 */
class RequestPipelineBuilder
{
    private array $middlewares;

    /**
     * Adds a middleware into the pipeline.
     * 
     * Middlewares are added in the order they are specified, but they will be 
     * invoked in Last-In-First-Out (LIFO) order.
     * 
     * @param string $class The fully qualified class name
     * of the middleware to add.
     * 
     * @return RequestPipelineBuilder This builder instance.
     * 
     * @example
     * ```php
     * $builder->addMiddleware(SomeMiddleware::class);
     * ```
     */
    public function addMiddleware(string $class): self
    {
        $this->middlewares[] = $class;
        return $this;
    }

    /**
     * Returns the middlewares in LIFO order.
     * 
     * This method reverses the order of the middlewares to ensure they are
     * executed in Last-In-First-Out (LIFO) order.
     * 
     * @return array The list of middlewares in LIFO order.
     */
    public function build(): array
    {
        return array_reverse($this->middlewares);
    }
}
?>