<?php
    use Framework\Http\HttpRequest;
    use Framework\Http\HttpResponse;
    use Framework\Middleware\RequestPipeline;
    use Framework\Middleware\StaticFilesMiddleware;
    use Framework\Middleware\ViewMiddleware;
    use Framework\Middleware\ApiMiddleware;

    class Server
    {
        public function run()
        {
            $pipeline = new RequestPipeline();

            # Register middlewares
            $pipeline->addMiddleware(StaticFilesMiddleware::class);
            $pipeline->addMiddleware(ViewMiddleware::class);
            $pipeline->addMiddleware(ApiMiddleware::class);

            $request = new HttpRequest($_SERVER);
            $response = $pipeline->handleRequest($request);

            $this->serveResponse($response);
        }

        private function serveResponse(HttpResponse $response): void {
            $content = $response->content;

            http_response_code($response->statusCode);

            if (is_array($content)) {
                header('Content-Type: application/json; charset=utf-8');
                $content = json_encode(array_values($content));
            }

            if (is_object($content)) {
                header('Content-Type: application/json; charset=utf-8');
                $content = json_encode($content);
            }

            foreach($response->headers as $header)
            {
                header($header['name'] . ': ' . $header['value']);
            }

            echo $content;
        }
    }
?>