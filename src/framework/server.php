<?php
namespace Framework;

use Framework\Api\Data\DatabaseContext;
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