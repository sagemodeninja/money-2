<?php
    include_once "../includes/db_provider.php";
    
    $result = "";
    $conn = connect();
    
    if($conn) {
        $json = file_get_contents('php://input');
        $data = json_decode($json);

        $id = $data->Id;
        $accountId = $data->AccountId;
        $date = $data->Date;
        $description = str_replace("'", "''", $data->Description);
        $amount = $data->Amount;
        $query = "UPDATE transaction SET `Date` = '$date', `Description` = '$description', Amount = $amount, Total = $amount WHERE Id = $id";
        
        if ($conn->query($query) === TRUE) {
            $result = "{'state': true, 'content': 'Transaction updated!'}";
        } else {
            $error = str_replace("'", "**", $conn->error);
            $result = "{'state': false, 'content': '$error'}";
        }
    } else {
        $result = "{'state': false, 'content': 'An error occured.'}";
    }
    
    header('Content-Type: application/json; charset=utf-8');
    echo str_replace("**", "'", str_replace("'", "\"", $result));
?>