<?php
namespace Framework\Middleware\Default;

use Closure;
use Framework\Http\{HttpRequest,HttpResponse};

class StaticFilesMiddleware
{
    private const FILE_TYPES = [
        'html' => 'text/html',
        'htm' => 'text/html',
        'js' => 'text/javascript',
        'css' => 'text/css',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'txt' => 'text/plain',
        'csv' => 'text/csv',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'ico' => 'image/vnd.microsoft.icon',
        'svg' => 'image/svg+xml',
        'webp' => 'image/webp',
        'mp3' => 'audio/mpeg',
        'wav' => 'audio/wav',
        'ogg' => 'audio/ogg',
        'mp4' => 'video/mp4',
        'webm' => 'video/webm',
        'ogg' => 'video/ogg',
        'avi' => 'video/x-msvideo',
        'ttf' => 'font/ttf',
        'otf' => 'font/otf',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'eot' => 'application/vnd.ms-fontobject',
        'pdf' => 'application/pdf',
        'zip' => 'application/zip',
        'rar' => 'application/vnd.rar',
        '7z' => 'application/x-7z-compressed',
        'tar' => 'application/x-tar',
        'gz' => 'application/gzip'
    ];

    private Closure $next;

    public function __construct(callable $next)
    {
        $this->next = Closure::fromCallable($next);
    }

    public function invoke(HttpRequest $request)
    {
        $path = $request->path;
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        if (array_key_exists($extension, self::FILE_TYPES)) {
            # Check if file exists and get it's absolute path.
            if ($file = realpath($path)) {
                $content = @file_get_contents($file);
                
                # If an error occured whle getting file content,
                # return HTTP 500.
                if ($content === false) {
                    return new HttpResponse(500, 'Internal Sever Error');
                }
    
                return new HttpResponse(200, $content, self::FILE_TYPES[$extension]);
            }

            # Otherwise return HTTP 404
            return new HttpResponse(404);
        }

        return $this->next->call($this, $request);
    }
}
?>