<?php
// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Include config file
    require_once "config.php";
    
    // Prepare a select statement
    $sql = "SELECT * FROM complaints_record WHERE id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["id"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $name = $row["name"];
                $bet_ref = $row["bet_ref"];
                $complaint = $row["complaint_details"];
                 $entry_time = $row["entry_time"];
                $resolved_time = $row["resolved_time"];
                $ticket_status = $row["ticket_status"];
                $handler = $row["handler"];
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
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>View Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="mt-5 mb-3">View Complaint Record</h1>
                    <div class="form-group">
                        <label>Name</label>
                        <p><b><?php echo $row["name"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>MPESA Reference No</label>
                        <p><b><?php echo $row["bet_ref"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Complaint Details</label>
                        <p><b><?php echo $row["complaint_details"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Entry Time</label>
                        <p><b><?php echo $row["entry_time"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Resolve Time</label>
                        <p><b><?php echo $row["resolved_time"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Ticket Status</label>
                        <p><b><?php echo $row["ticket_status"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Handler</label>
                        <p><b><?php echo $row["handler"]; ?></b></p>
                    </div>
                    
                    <p><a href="index.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>