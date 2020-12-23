<?php
// Include config file
require_once 'C:\xampp\htdocs\SecTickets\config.php';
 
// Define variables and initialize with empty values
$statusdesc = $Active = "";
$statusdesc_err = $Active_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["statusid"]) && !empty($_POST["statusid"])){
    // Get hidden input value
    $statusid = $_POST["statusid"];
    
    // Validate name
    $statusdesc = trim($_POST["statusdesc"]);
    if(empty($statusdesc)){
        $statusdesc_err = "Please enter a description of status.";
    } elseif(!filter_var($statusdesc, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $statusdesc_err = "Please enter a valid status description.";
    } else{
        $statusdesc = $statusdesc;
    }
    
    // Validate active
    $Active = trim($_POST["Active"]);
    if(empty($Active)){
        $Active_err = "Please enter an active.";     
    } else{
        $Active = $Active;
    }
    
  
    
    // Check input errors before inserting in database
    if(empty($statusdesc_err) && empty($Active_err)){
        // Prepare an update statement
        $sql = "UPDATE status SET statusdesc=?, Active=? WHERE statusid=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sii", $param_statusdesc, $param_Active, $param_statusid);
            
            // Set parameters
            $param_statusdesc = $statusdesc;
            $param_Active = $Active;
            $param_statusid = $statusid;
           
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: statusView.php");
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
    if(isset($_GET["statusid"]) && !empty(trim($_GET["statusid"]))){
        // Get URL parameter
        $statusid =  trim($_GET["statusid"]);
        
        // Prepare a select statement
        $sql = "SELECT statusid,statusdesc,Active FROM status WHERE statusid = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_statusid);
            
            // Set parameters
            $param_statusid = $statusid;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $statusdesc = $row["statusdesc"];
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
                        <div class="form-group <?php echo (!empty($statusdesc_err)) ? 'has-error' : ''; ?>">
                            <label>Status Description</label>
                            <input type="text" name="statusdesc" class="form-control" value="<?php echo $statusdesc; ?>">
                            <span class="help-block"><?php echo $statusdesc_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($Active_err)) ? 'has-error' : ''; ?>">
                            <label>Active</label>
                            <input type="text" name="Active" class="form-control" value="<?php echo $Active; ?>">
                            <span class="help-block"><?php echo $Active_err;?></span>
                        </div>
                       
                        <input type="hidden" name="statusid" value="<?php echo $statusid; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="statusView.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>