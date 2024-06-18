<?php
    include_once "../includes/db_provider.php";
    
    $result = "";
    $conn = connect();
    
    if($conn) {
        $accountId = @$_GET["accountId"];
        $query = "call spGetAccountBalance($accountId);";
        $data = $conn->query($query);
        
        if($data) {
            if($data->num_rows > 0) {
                $row = $data->fetch_assoc();
                $balance = $row["Balance"];
                $projection = $row["Projection"];
                
                $result = "{'state': true, 'content': {'Balance': $balance, 'Projection': $projection}}";
            } else {
                $result = "{'state': false, 'content': '0 rows found.'}";
            }
        } else {
            $error = str_replace("'", "**", $conn->error);
            $result = "{'state': false, 'content': '$error'}";
        }
    } else {
        $result = "{'state': false, 'content': 'An error occured.'}";
    }
    
    echo str_replace("**", "'", str_replace("'", "\"", $result));
?>