<?php
    include_once "../../includes/db_provider.php";
    
    $result = "";
    $conn = connect();
    
    if($conn) {
        $id = @$_POST["Id"];
        $title = str_replace("'", "''", @$_POST["Title"]);
        $color = @$_POST["Color"];
        $order = @$_POST["Order"];
        $query = "call spUpdateCategory($id, '$title', '$color', $order);";
        
        if ($conn->query($query) === TRUE) {
            $result = "{'state': true, 'content': 'Category updated!'}";
        } else {
            $error = str_replace("'", "**", $conn->error);
            $result = "{'state': false, 'content': '$error'}";
        }
    } else {
        $result = "{'state': false, 'content': 'An error occured.'}";
    }
    
    echo str_replace("**", "'", str_replace("'", "\"", $result));
?>