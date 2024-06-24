<?php
namespace Framework\Api\Controller;

use ReflectionAttribute;
use ReflectionObject;
use ReflectionMethod;
use Framework\Api\Attributes\{FromBody,FromFile,HttpMethodAttribute};
use Framework\Http\{HttpRequest,HttpResponse};

class ControllerAction
{
    public ReflectionMethod $method;
    public HttpMethodAttribute $httpMethod;

    public function __construct(ReflectionMethod $method, HttpMethodAttribute $httpMethod)
    {
        $this->method = $method;
        $this->httpMethod = $httpMethod;
    }

    public static function getControllerAction(object $controller, HttpRequest $request): ?ControllerAction
    {
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
            
            return new ControllerAction($method, $actionMethod);
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
        $routeParams = self::getRouteParameters($request, $this);
        $requestParams = array_merge($routeParams, $request->params);

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

    private static function getRouteParameters(HttpRequest $request, ControllerAction $action): array
    {
        $path = implode('/', array_slice(explode('/', $request->path), 2));
        $method = $action->httpMethod;
        
        preg_match($method->getBindingPattern(), $method->route, $keys);
        preg_match($method->getRoutePattern(), $path, $values);
        
        $params = [];
        
        for ($i = 1; $i < count($keys); $i++) {
            $key = $keys[$i];
            $params[$key] = $values[$i];
        }

        return $params;
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