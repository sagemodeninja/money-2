<?php
include_once "framework/autoload.php";
include_once "app.php";

$app = new App();
$app->configure();
$app->run();
?>