<?php
namespace Framework\Middleware\Default;

use Framework\Http\HttpRequest;
use Framework\Http\HttpResponse;

class StaticFilesMiddleware
{
    private $next;

    public function __construct(callable $next)
    {
        $this->next = $next;
    }

    public function invoke(HttpRequest $request)
    {
        $path = $request->path;

        if (self::isStaticFile($path))
        {
            if (file_exists($path))
            {
                $content = file_get_contents($path);
                $contentType = self::getContentType($path);

                $response = new HttpResponse(200, $content);
                $response->addHeader('Content-Type', $contentType);

                return $response;
            }
            else
            {
                return new HttpResponse(404);
            }
        }

        return call_user_func($this->next, $request);
    }

    private static function isStaticFile(string $path)
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        return in_array($extension, ['js', 'css', 'ttf']);
    }

    private static function getContentType(string $file)
    {
        switch (pathinfo($file, PATHINFO_EXTENSION))
        {
            case 'js': return 'text/javascript';
            case 'css': return 'text/css';
            case 'ttf': return 'font/ttf';
            default: return 'text/html';
        }
    }
}
?>