<?php
namespace Framework\Http;

class HttpResponse
{
    public int $statusCode;
    public mixed $content;
    public array $headers = [];

    public function __construct(int $statusCode, mixed $content = null, string $contentType = null)
    {
        $content = self::resolveContent($content);

        $this->statusCode = $statusCode;
        $this->content = $content['content'];
        
        $this->addHeader('Content-Type', $contentType ?? $content['type']);
    }

    public function addHeader(string $name, string $value)
    {
        $this->headers[] = [
            'name' => $name,
            'value' => $value
        ];
    }

    private static function resolveContent(mixed $raw)
    {
        if (is_array($raw)) {
            $type = 'application/json; charset=utf-8';
            $content = json_encode(array_values($raw));
        }
        
        if (is_object($raw)) {
            $type = 'application/json; charset=utf-8';
            $content = json_encode($raw);
        }

        return [
            'type' => $type ?? 'text/html',
            'content' => $content ?? strval($raw)
        ];
    }
}
?>