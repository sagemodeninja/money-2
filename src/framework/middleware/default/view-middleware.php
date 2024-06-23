<?php
namespace Framework\Middleware\Default;

use Closure;
use Framework\Http\{HttpRequest,HttpResponse};

class ViewMiddleware
{
    private Closure $next;

    public function __construct(callable $next)
    {
        $this->next = Closure::fromCallable($next);
    }

    public function invoke(HttpRequest $request)
    {
        $path = !empty($request->path) ? $request->path : 'home';
        $fpath = implode(DIRECTORY_SEPARATOR, ['views', ...explode('/', $path), 'index.php']);

        # Check if file exists and get it's absolute path.
        if ($file = realpath($fpath)) {
            $content = @file_get_contents($file);
                
            # If an error occured whle getting file content,
            # return HTTP 500.
            if ($content === false) {
                return new HttpResponse(500, 'Internal Sever Error');
            }

            return new HttpResponse(200, $content);
        }
        
        # Otherwise return HTTP 404
        return $this->next->call($this, $request);
    }
}
?>