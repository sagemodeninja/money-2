<?php
    include_once "../../includes/db_provider.php";
    
    $result = "";
    $conn = connect();
    
    if($conn) {
        $query = "call spRetrieveAccounts();";
        $data = $conn->query($query);
        
        if($data) {
            if($data->num_rows > 0) {
                $result = "{'state': true, 'content': [";
                while($row = $data->fetch_assoc()) {
                    $id = $row["Id"];
                    $shortcode = $row["Shortcode"];
                    $title = str_replace("'", "**", $row["Title"]);
                    $categoryId = $row["CategoryId"];
                    $category = str_replace("'", "**", $row["Category"]);
                    $accountNumber = $row["AccountNumber"];
                    $bankIcon = $row["BankIcon"];
                    $status = $row["Status"];
                    
                    $accountNumberValue = is_null($accountNumber) ? "null" : "'$accountNumber'";
                    $bankIconValue = is_null($bankIcon) ? "null" : "'$bankIcon'";
                    
                    $result .= "{'Id': $id, 'Shortcode': '$shortcode', 'Title': '$title', 'CategoryId': $categoryId, 'Category': '$category', 'AccountNumber': $accountNumberValue, 'BankIcon': $bankIconValue, 'Status': '$status'},";
                }
                $result = rtrim($result, ",");
                $result .= "]}";
            } else {
                $result = "{'state': false, 'content': '0 rows found.'}";
            }
        }
        else
        {
            $error = str_replace("'", "**", $conn->error);
            $result = "{'state': false, 'content': '$error'}";
        }
    } else {
        $result = "{'state': false, 'content': 'An error occured.'}";
    }
    
    echo str_replace("**", "'", str_replace("'", "\"", $result));
?>