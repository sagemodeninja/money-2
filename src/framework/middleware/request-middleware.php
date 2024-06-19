<?php
    namespace Framework\Middleware;

    use Framework\Http\HttpRequest;
    use ReflectionClass;

    class RequestMiddleware
    {
        private array $middlewares;

        public function __construct()
        {
            $this->middlewares = [];
        }

        public function addMiddleware(string $class)
        {
            # Middlewares are invoked last-in first-out.
            array_unshift($this->middlewares, $class);
        }

        public function handleRequest(HttpRequest $request)
        {
            $cm = null;

            foreach ($this->middlewares as $middleware)
            {
                $next = isset($cm) ? function ($request) use ($cm) {
                    return $cm->invoke($request);
                } : null;

                $cm = $this->constructMiddleware($middleware, $next);
            }
            
            return $cm->invoke($request);
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