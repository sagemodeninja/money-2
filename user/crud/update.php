<?php
    include_once "../../includes/db_provider.php";
    
    $result = "";
    $conn = connect();
    
    if($conn) {
        $id = @$_POST["Id"];
        $firstname = @$_POST["Firstname"];
        $lastname = @$_POST["Lastname"];
        $email = @$_POST["Email"];
        $query = "UPDATE user SET Firstname = '$firstname', Lastname = '$lastname', Email = '$email' WHERE Id = $id";
        
        if ($conn->query($query) === TRUE) {
            $result = "{'state': true, 'content': 'User updated!'}";
        } else {
            $error = str_replace("'", "**", $conn->error);
            $result = "{'state': false, 'content': '$error'}";
        }
    } else {
        $result = "{'state': false, 'content': 'An error occured.'}";
    }
    
    echo str_replace("**", "'", str_replace("'", "\"", $result));
?>