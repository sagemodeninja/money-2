<?php
    namespace Framework\Api\Controller;

    use ReflectionAttribute;
    use ReflectionObject;
    use ReflectionMethod;
    use Framework\Api\Attributes\HttpMethodAttribute;
    use Framework\Http\HttpRequest;
    use Framework\Http\HttpResponse;

    class ControllerAction {
        public ReflectionMethod $method;

        public function __construct(ReflectionMethod $method)
        {
            $this->method = $method;
        }

        public static function getControllerAction(object $controller, HttpRequest $request): ?ControllerAction {
            $reflection = new ReflectionObject($controller);
            $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

            $methodAttribute = 'Framework\\Api\\Attributes\\' . $request->method;
            $route = implode('/', array_slice(explode('/', $request->path), 2));

            foreach($methods as $method) {
                $attributes = $method->getAttributes($methodAttribute, ReflectionAttribute::IS_INSTANCEOF);
                
                if (empty($attributes)) continue;
                
                $actionMethod = current($attributes)->newInstance();
                $routeMatch = @preg_match($actionMethod->getRoutePattern(), $route);

                if (!$routeMatch) continue;
                
                return new ControllerAction($method);
            }
            
            return null;
        }

        public function handle(ControllerBase $controller, HttpRequest $request): HttpResponse {
            // TODO: Improve parsing/mapping of request body.
            // $args = ParameterBinding::bind($request, $this);
            // return $this->method->invoke($controller, ...$args);
            $result = $this->method->invoke($controller);
            return $result;
        }
    }
?>