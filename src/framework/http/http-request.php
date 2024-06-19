<?php
    namespace Framework\Http;

    class HttpRequest
    {
        public string $method;
        public string $path;

        public function __construct(array $server) {
            $this->method = ucfirst(strtolower($server['REQUEST_METHOD']));
            $this->path = trim(parse_url($server['REQUEST_URI'])['path'], '/');
        }
    }
?>