<?php
    namespace Framework\Middleware;

    use Framework\Http\HttpRequest;
    use Framework\Http\HttpResponse;
    use Framework\Views\View;
    
    class ViewMiddleware
    {
        public function invoke(HttpRequest $request)
        {
            $path = $request->uri->path;

            if (empty($path))
            {
                $path = 'home';
            }

            $nodes = explode('/', "views/$path");
            $file = implode(DIRECTORY_SEPARATOR, [...$nodes, 'index.php']);
            
            if (!file_exists($file))
            {
                return new HttpResponse(404);
            }

            # Crawl through the path and look for layouts...
            $dir = [];
            $layouts = [];

            foreach ($nodes as $node)
            {
                $layout = implode(DIRECTORY_SEPARATOR, [...$dir, $node, 'layout.php']);

                if (file_exists($layout))
                {
                    array_unshift($layouts, $layout);
                }

                array_push($dir, $node);
            }

            $view = new View($file);

            foreach ($layouts as $layout)
            {
                $view = new View($layout, $view);
            }

            $content = $view->render();

            return new HttpResponse(200, $content);
        }
    }
?>