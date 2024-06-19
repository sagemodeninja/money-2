<?php
    namespace Framework\Http;

    class HttpResponse
    {
        public int $statusCode;
        public mixed $content;
        public array $headers;

        public function __construct(int $statusCode, mixed $content = null)
        {
            $this->statusCode = $statusCode;
            $this->content = $content;
            $this->headers = [];
        }

        public function addHeader(string $name, string $value)
        {
            array_push($this->headers, [
                'name' => $name,
                'value' => $value
            ]);
        }
    }
?>