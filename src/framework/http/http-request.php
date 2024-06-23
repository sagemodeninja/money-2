<?php
    namespace Framework\Http;

    class HttpRequest
    {
        public string $method;
        public string $path;

        public array $params;
        public mixed $body;
        public array $files;

        public function __construct(array $server) {
            $this->method = ucfirst(strtolower($server['REQUEST_METHOD']));
            $this->path = trim(parse_url($server['REQUEST_URI'])['path'], '/');

            $this->params = $_GET;
            $this->body = self::parseBody($server);
            $this->files = [];
        }

        /* Concatenates request bodies across multiple sources depending on
        its content-type. */
        private static function parseBody(array $server)
        {
            // These are the HTTP methods that supports a request body.
            $supportedMethods = ['POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS', 'TRACE'];

            if (!in_array($server['REQUEST_METHOD'], $supportedMethods))
            {
                return null;
            }

            $contentType = $server['CONTENT_TYPE'] ?? '';
            $mimeType = self::getMimeType($contentType);

            switch($mimeType)
            {
                case 'application/json':
                    $rawBody = self::getRawBody();
                    return json_decode($rawBody, true);
                case 'application/xml':
                case 'text/xml':
                    $rawBody = self::getRawBody();
                    $xml = simplexml_load_string($rawBody);
                    return json_decode(json_encode($xml), true);
                case 'multipart/form-data':
                case 'application/x-www-form-urlencoded':
                    return $_POST;
                default:
                    return self::getRawBody();
            }
        }

        private static function getMimeType($contentType)
        {
            $parts = explode(';', $contentType);
            return trim($parts[0]);
        }

        private static function getRawBody()
        {
            return file_get_contents('php://input');
        }
    }
?>