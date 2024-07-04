<?php
include_once "framework/autoload.php";
include_once "app.php";

header("Access-Control-Allow-Origin: *");
    
// TODO: Refactor this part!
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS");         
    
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

$app = new App();
$app->configure();
$app->run();
?>