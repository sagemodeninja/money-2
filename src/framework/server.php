<?php
namespace Framework;

use Framework\Http\{HttpRequest, HttpResponse};
use Framework\Middleware\RequestPipeline;

class Server
{
    private readonly RequestPipeline $pipeline;

    public function __construct(RequestPipeline $pipeline)
    {
        $this->pipeline = $pipeline;
    }

    public function run()
    {
        $request = new HttpRequest($_SERVER);
        $response = $this->pipeline->handleRequest($request);
        $this->serveResponse($response);
    }

    private function serveResponse(HttpResponse $response): void {
        http_response_code($response->statusCode);

        # Additional headers
        foreach($response->headers as $header)
        {
            header($header['name'] . ': ' . $header['value']);
        }

        echo $response->content;
    }
}
?>