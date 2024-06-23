<?php
    namespace Framework\Api\Controller;

use Api\Data\Models\AccountModel;
use Framework\Api\Attributes\FromBody;
use Framework\Api\Attributes\FromFile;
use ReflectionAttribute;
    use ReflectionObject;
    use ReflectionMethod;
    use Framework\Api\Attributes\HttpMethodAttribute;
    use Framework\Http\HttpRequest;
    use Framework\Http\HttpResponse;
use Reflection;

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
            $args = $this->getArguments($request);
            $result = $this->method->invoke($controller, ...$args);
            return $result;
        }

        private function getArguments(HttpRequest $request)
        {
            $args = [];

            $parameters = $this->method->getParameters();
            $requestParams = $request->params;

            foreach($parameters as $parameter) {
                $fromBody = self::getAttribute($parameter, FromBody::class);
                $fromFile = self::getAttribute($parameter, FromFile::class);

                if (!empty($fromBody))
                {
                    $attribute = $fromBody->newInstance();
                    $args[] = $attribute->parse($request->body);
                }
                else if (!empty($fromFile))
                {
                    $args[] = $request->files;
                }
                else
                {
                    $name = $parameter->getName();
    
                    if (array_key_exists($name, $requestParams)) {
                        $args[] = $requestParams[$name];
                    }
                }
            }

            return $args;
        }

        private static function getAttribute($parameter, $class)
        {
            return current($parameter->getAttributes(
                $class,
                ReflectionAttribute::IS_INSTANCEOF
            ));
        }
    }
?>