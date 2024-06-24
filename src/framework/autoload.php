<?php
function pascalToKebab($input) {
    $output = preg_replace('/(?<!^)(?<!\\\)([A-Z])/', '-$1', $input);
    return strtolower($output);
}

spl_autoload_register(function ($class) {
    $path = pascalToKebab($class) . '.php';
    $safePath = str_replace('\\', DIRECTORY_SEPARATOR, $path);
    $file = realpath($safePath);

    if (file_exists($file)) {
        require_once $file;
        return true;
    }

    return false;
});
?>