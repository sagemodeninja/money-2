<?php
    namespace Framework\Api\Attributes;

    class HttpMethodAttribute {
        public string $route;

        public function __construct(string $route = "")
        {
            $this->route = $route;
        }

        public function getRoutePattern() {
            $route = str_replace('/', '\\/', $this->route);
            $pattern = preg_replace('/{[\w-]+}/', '(\w+)', $route);

            return "/^$pattern$/";
        }

        public function getBindingPattern() {
            $routePattern = $this->getRoutePattern();
            return str_replace('(\w+)', '{(\w+)}', $routePattern);
        }
    }
?>