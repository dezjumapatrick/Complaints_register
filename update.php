<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $bet_ref = $handler = "";
$name_err = $bet_ref_err = $handler_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    
    // Validate Bet ref
    $input_bet_ref = trim($_POST["bet_ref"]);
    if(empty($input_bet_ref)){
        $bet_ref_err = "Please enter correct bet reference no.";     
    } else{
        $bet_ref = $input_bet_ref;
    }
     // Validate complaint
    $input_complaint_details= trim($_POST["complaint_details"]);
    if(empty($input_complaint_details)){
        $complaint_err = "Please enter the Complaint.";     
   
    } else{
        $complaint_details = $input_complaint_details;
    }
    
     // Validate Resolved time
    $input_resolved_time = trim($_POST["resolved_time"]);
    if(empty($input_resolved_time)){
        $entry_resolved_time_err = "Please enter the Resolved Time.";     
   
    } else{
        $resolved_time = $input_resolved_time;
    }
    
     
    // Validate Ticket Status
    $input_ticket_status = trim($_POST["ticket_status"]);
    if(empty($input_ticket_status)){
        $ticket_status_err = "Please enter the Ticket Status.";     
   
    } else{
        $ticket_status = $input_ticket_status;
    }
    
    // Validate Handler
    $input_handler = trim($_POST["handler"]);
    if(empty($input_handler)){
        $handler_err = "Please enter the correct handler.";     
    }  else{
        $handler = $input_handler;
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($bet_ref_err) && empty($handler_err)){
        // Prepare an update statement
        $sql = "UPDATE complaints_record SET name=?, bet_ref=?, complaint_details=?,resolved_time=?,ticket_status=?,  handler=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssi", $param_name,$param_bet_ref, $param_complaint_details,  $param_resolved_time, $param_ticket_status, $param_handler, $param_id);
            
            // Set parameters
            $param_name = $name;
            $param_bet_ref = $bet_ref;
            $param_complaint_details= $complaint_details;
            $param_resolved_time = $resolved_time;
            $param_ticket_status= $ticket_status;
            $param_handler = $handler;
            
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM complaints_record WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
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
                    $complaint_details = $row["complaint_details"];
                    $resolved_time = $row["resolved_time"];
                    $ticket_status = $row["ticket_status"];
                    $handler = $row["handler"];
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
                    <h2 class="mt-5">Resolve Complaint</h2>
                    <p>Please edit the data and update to resolve and submit to update the complaint record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                         <div class="form-group">
                            <label>MPESA Reference No</label>
                            <input type="text" name="bet_ref" class="form-control <?php echo (!empty($bet_ref_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $bet_ref; ?>">
                            <span class="invalid-feedback"><?php echo $bet_ref_err;?></span>
                        </div>
                         <div class="form-group">
                            <label>Complaint Details</label>
                            <input type ="text" name="complaint_details" class="form-control " value="<?php echo $complaint_details; ?>" >
                        </div>
                           <div class="form-group">
                                 <?php
date_default_timezone_set("Africa/Nairobi");
$time=date("d.m.Y, h:i:sa");
?>                      <label>Resolved Time</label>
                            <input type="text" name="resolved_time" class="form-control" value="<?php echo $time; ?>" >
                           
                        </div>
                        
                        <div class="form-group">
                            <label>Ticket Status</label>
                           <select name="ticket_status" id="ticket_status" class="form-control">
      <option value="Pending">Pending</option>  
      <option value="Resolved">Resolved</option>
     </select>
                           
                        </div>
                        
                        <div class="form-group">
                            <label>Handler</label>
                            <input type="text" name="handler" class="form-control <?php echo (!empty($handler_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $handler; ?>" >
                            <span class="invalid-feedback"><?php echo $handler_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>