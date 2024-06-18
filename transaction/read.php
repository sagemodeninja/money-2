<?php
    include_once "../includes/db_provider.php";
    
    $result = "";
    $conn = connect();
    
    if($conn) {
        $accountId = @$_GET["AccountId"];
        $query = "SELECT Id, `Date`, `Description`, TransactionType, Amount, Total, Posted FROM transaction WHERE AccountId = $accountId AND Status = 1 ORDER BY Posted ASC, `Date` DESC, Id DESC;";
        $data = $conn->query($query);
        
        if($data) {
            if($data->num_rows > 0) {
                $result = "{'state': true, 'content': [";
                while($row = $data->fetch_assoc()) {
                    $id = $row["Id"];
                    $date = $row["Date"];
                    $description = str_replace("'", "**", $row["Description"]);
                    $transactionType = $row["TransactionType"];
                    $amount = $row["Amount"];
                    $total = $row["Total"];
                    $posted = $row["Posted"];
                    
                    $result .= "{ 'Id': $id, 'Date': '$date', 'Description': '$description', 'TransactionType': $transactionType, 'Amount': $amount, 'Total': $total, 'Posted': $posted },";
                }
                $result = rtrim($result, ",");
                $result .= "]}";
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