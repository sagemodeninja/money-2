<?php
    include_once "../includes/db_provider.php";
    
    $result = "";
    $conn = connect();
    
    if($conn) {
        $json = file_get_contents('php://input');
        $data = json_decode($json);

        $accountId = $data->AccountId;
        $date = $data->Date;
        $description = str_replace("'", "''", $data->Description);
        $transactionType = $data->TransactionType;
        $amount = $data->Amount;
        $query = "INSERT INTO transaction (AccountId, `Date`, `Description`, TransactionType, Amount, Total) VALUES ($accountId, '$date', '$description', $transactionType, $amount, $amount);";
        
        if ($conn->query($query) === TRUE) {
            $result = "{'state': true, 'content': 'Transaction created!'}";
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