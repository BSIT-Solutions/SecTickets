<?php
// Include config file
require_once 'C:\xampp\htdocs\SecTickets\config.php';
 
// Define variables and initialize with empty values
$type = "";
$type_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $type = trim($_POST["type"]);
    if(empty($type)){
        $type_err = "Please enter a issue type.";
    } elseif(!filter_var($type, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $type_err = "Please enter a valid type.";
    } else{
        $type = $type;
    }
    
       
    // Check input errors before inserting in database
    if(empty($type_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO issue_types (type) VALUES (?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_type);
            
            // Set parameters
            $param_type = $type;
           
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: issuetView.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                        <h2>Create Record</h2>
                    </div>
                    <p>Please fill this form and submit to add issue type record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($type_err)) ? 'has-error' : ''; ?>">
                            <label>Issue Type</label>
                            <input name="type" class="form-control"><?php echo $type; ?>
                            <span class="help-block"><?php echo $type_err;?></span>
                        </div>
                        
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="issuetView.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>