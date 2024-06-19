<?php
    use Framework\Http\HttpRequest;
    use Framework\Http\HttpResponse;
    use Framework\Middleware\RequestMiddleware;
    use Framework\Middleware\StaticFilesMiddleware;
    use Framework\Middleware\ViewMiddleware;

    class App
    {
        public function run()
        {
            $middleware = new RequestMiddleware();

            # Register middlewares
            $middleware->addMiddleware(StaticFilesMiddleware::class);
            $middleware->addMiddleware(ViewMiddleware::class);

            $request = new HttpRequest($_SERVER);
            $response = $middleware->handleRequest($request);

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