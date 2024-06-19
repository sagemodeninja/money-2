<?php
    namespace Framework\Http;
    
    class RequestUri {
        public string $href;
        public string $path;
        public ?string $query;
        public string $controller;
        public string $route;

        public function __construct(string $uri)
        {
            $href = trim($uri, '/');
            $uriComponents = parse_url($href);

            $path = trim($uriComponents['path'], '/');

            $this->href = $href;
            $this->path = $path;
            $this->query = $uriComponents['query'] ?? null;
        }
    }
?>