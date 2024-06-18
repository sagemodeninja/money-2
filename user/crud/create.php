<?php
    include_once "../../includes/db_provider.php";
    
    $result = "";
    $conn = connect();
    
    if($conn) {
        $firstname = @$_POST["Firstname"];
        $lastname = @$_POST["Lastname"];
        $email = @$_POST["Email"];
        $query = "INSERT INTO user (Firstname, Lastname, Email) VALUES ('$firstname', '$lastname', '$email')";
        
        if ($conn->query($query) === TRUE) {
            $result = "{'state': true, 'content': 'User created!'}";
        } else {
            $error = str_replace("'", "**", $conn->error);
            $result = "{'state': false, 'content': '$error'}";
        }
    } else {
        $result = "{'state': false, 'content': 'An error occured.'}";
    }
    
    echo str_replace("**", "'", str_replace("'", "\"", $result));
?>