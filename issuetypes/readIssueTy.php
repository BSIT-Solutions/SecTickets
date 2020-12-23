<?php
// Check existence of id parameter before processing further
if(isset($_GET["issueid"]) && !empty(trim($_GET["issueid"]))){
    // Include config file
     require_once 'C:\xampp\htdocs\SecTickets\config.php';
    
    // Prepare a select statement
    $sql = "SELECT issueid,type,Active FROM issue_types WHERE issueid = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_issueid);
        
        // Set parameters
        $param_issueid = trim($_GET["issueid"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $type = $row["type"];
                $Active = $row["Active"];
               
            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }
            
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Status Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1>View Issue Type Record</h1>
                    </div>
                    <div class="form-group">
                        <label>Issue Type</label>
                        <p class="form-control-static"><?php echo $row["type"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Active</label>
                        <p class="form-control-static"><?php echo $row["Active"]; ?></p>
                    </div>
                    
                    <p><a href="index2.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>