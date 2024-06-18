<?php
    class App
    {
        public static function run()
        {
            $uri = trim($_SERVER['REQUEST_URI'], '/');

            if (empty($uri))
            {
                $uri = 'home'; # Fallback
            }

            $path = self::isStaticFile($uri)
                ? $uri
                : implode(DIRECTORY_SEPARATOR, ['views', $uri, 'index.php']);

            if (!file_exists($path))
            {
                http_response_code(404);
                exit(0);
            }

            include_once($path);
            exit(0);
        }

        private static function isStaticFile(string $uri)
        {
            $extension = pathinfo($uri, PATHINFO_EXTENSION);
            return in_array($extension, ['js', 'css']);
        }
    }

    App::run();
?>