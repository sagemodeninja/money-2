<?php
    include_once "../../includes/db_provider.php";
    
    $result = "";
    $conn = connect();
    
    if($conn) {
        $id = @$_POST["Id"];
        $query = "call spCloseAccount($id);";
        
        if ($conn->query($query) === TRUE) {
            $result = "{'state': true, 'content': 'Account closed!'}";
        } else {
            $error = str_replace("'", "**", $conn->error);
            $result = "{'state': false, 'content': '$error'}";
        }
    } else {
        $result = "{'state': false, 'content': 'An error occured.'}";
    }
    
    echo str_replace("**", "'", str_replace("'", "\"", $result));
?>