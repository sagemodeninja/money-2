<?php
    namespace Framework\Http;

    class HttpRequest
    {
        public string $method;
        public RequestUri $uri;

        public function __construct(array $server) {
            $this->method = ucfirst(strtolower($server['REQUEST_METHOD']));
            $this->uri = new RequestUri($server['REQUEST_URI']);
        }
    }
?>