<?php
    namespace Framework\Api\Controller;

    class ControllerCollection
    {
        private readonly array $cache;

        public function __construct(array $cache)
        {
            $this->cache = $cache;    
        }

        public static function load(): ControllerCollection
        {
            $path = 'api/controllers/controller_cache.json';
            $content = file_get_contents($path);
            $cache = json_decode($content, true);

            return new ControllerCollection($cache);
        }

        public function exists(string $controller): bool
        {
            return array_key_exists($controller, $this->cache);
        }

        public function getInstance(string $controller)
        {
            $className = $this->cache[$controller];
            $namespace = 'Api\\Controllers\\' . $className;

            return new $namespace();
        }
    }
?>