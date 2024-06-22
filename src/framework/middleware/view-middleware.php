<?php
    namespace Framework\Middleware;

    use Framework\Http\HttpRequest;
    use Framework\Http\HttpResponse;
    
    class ViewMiddleware
    {
        private $next;

        public function __construct(callable $next)
        {
            $this->next = $next;
        }

        public function invoke(HttpRequest $request)
        {
            $path = $request->path;

            if (empty($path))
            {
                $path = 'home';
            }

            $nodes = explode('/', "views/$path");
            $file = implode(DIRECTORY_SEPARATOR, [...$nodes, 'index.php']);

            if (file_exists($file))
            {
                $content = file_get_contents($file);
                return new HttpResponse(200, $content);
            }
            else
            {
                return call_user_func($this->next, $request);
            }
        }
    }
?>