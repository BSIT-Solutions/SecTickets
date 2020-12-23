<?php
// Include config file
require_once 'C:\xampp\htdocs\SecTickets\config.php';
 
// Define variables and initialize with empty values
$type = $Active = "";
$type_err = $Active_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["issueid"]) && !empty($_POST["issueid"])){
    // Get hidden input value
    $issueid = $_POST["issueid"];
    
    // Validate name
    $type = trim($_POST["type"]);
    if(empty($type)){
        $type_err = "Please enter a type of issues.";
    } elseif(!filter_var($type, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $type_err = "Please enter a valid issues type.";
    } else{
        $type = $type;
    }
    
    // Validate active
    $Active = trim($_POST["Active"]);
    if(empty($Active)){
        $Active_err = "Please enter an active.";     
    } else{
        $Active = $Active;
    }
    
  
    
    // Check input errors before inserting in database
    if(empty($type_err) && empty($Active_err)){
        // Prepare an update statement
        $sql = "UPDATE issue_types SET type=?, Active=? WHERE issueid=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sii", $param_type, $param_Active, $param_issueid);
            
            // Set parameters
            $param_type = $type;
            $param_Active = $Active;
            $param_issueid = $issueid;
           
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
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
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["issueid"]) && !empty(trim($_GET["issueid"]))){
        // Get URL parameter
        $issueid =  trim($_GET["issueid"]);
        
        // Prepare a select statement
        $sql = "SELECT issueid,type,Active FROM issue_types WHERE issueid = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_issueid);
            
            // Set parameters
            $param_issueid = $issueid;
            
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
                    // URL doesn't contain valid id. Redirect to error page
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
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
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
                        <h2>Update Record</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($type_err)) ? 'has-error' : ''; ?>">
                            <label>Issue Type</label>
                            <input type="text" name="type" class="form-control" value="<?php echo $type; ?>">
                            <span class="help-block"><?php echo $type_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($Active_err)) ? 'has-error' : ''; ?>">
                            <label>Active</label>
                            <input type="text" name="Active" class="form-control" value="<?php echo $Active; ?>">
                            <span class="help-block"><?php echo $Active_err;?></span>
                        </div>
                       
                        <input type="hidden" name="issueid" value="<?php echo $issueid; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="issuetView.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>