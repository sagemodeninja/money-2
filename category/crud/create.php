<?php
    include_once "../../includes/db_provider.php";
    
    $result = "";
    $conn = connect();
    
    if($conn) {
        $title = str_replace("'", "''", @$_POST["Title"]);
        $color = @$_POST["Color"];
        $order = @$_POST["Order"];
        $query = "call spInsertCategory('$title', '$color', $order);";
        
        if ($conn->query($query) === TRUE) {
            $result = "{'state': true, 'content': 'Category created!'}";
        } else {
            $error = str_replace("'", "**", $conn->error);
            $result = "{'state': false, 'content': '$error'}";
        }
    } else {
        $result = "{'state': false, 'content': 'An error occured.'}";
    }
    
    echo str_replace("**", "'", str_replace("'", "\"", $result));
?>