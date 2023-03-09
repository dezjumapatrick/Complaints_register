<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $bet_ref = $handler = $resolution ="";
$name_err = $bet_ref_err = $handler_err = $resolution_brief_err ="";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    
    // Validate bet ref
    $input_address = trim($_POST["bet_ref"]);
    if(empty($input_address)){
        $bet_ref_err = "Please enter bet reference no details.";     
    } else{
        $bet_ref = $input_address;
    }
     // Validate complaint
    $input_complaint= trim($_POST["complaint"]);
    if(empty($input_complaint)){
        $complaint_err = "Please enter the Complaint.";     
   
    } else{
        $complaint = $input_complaint;
    }
    
    
     // Validate entry time
    $input_entry_time = trim($_POST["entry_time"]);
    if(empty($input_entry_time)){
        $entry_time_err = "Please enter the Entry Time.";     
   
    } else{
        $entry_time = $input_entry_time;
    }
     // Validate Resolution Brief
     $input_resolution_brief = trim($_POST["resolution_brief"]);
     if(empty($input_resolution_brief)){
         $entry_resolution_brief_err = "Please enter Resolution Brief.";     
    
     } else{
         $resolution_brief = $input_resolution_brief;
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
    
    // Validate handler
    $input_handler = trim($_POST["handler"]);
    if(empty($input_handler)){
        $handler_err = "Please enter the Handler.";     
   
    } else{
        $handler = $input_handler;
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($bet_ref_err) && empty($handler_err)&& empty($resolution_brief_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO complaints_record (name, bet_ref, complaint_details, entry_time,resolution_brief, resolved_time, ticket_status, handler) VALUES (?, ?, ? , ?, ?, ?,?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssss", $param_name,$param_bet_ref, $param_complaint, $param_entry_time, $param_resolution_brief,$param_resolved_time, $param_ticket_status, $param_handler );
            
            // Set parameters
            $param_name = $name;
            $param_bet_ref = $bet_ref;
            $param_complaint = $complaint;
            $param_entry_time= $entry_time;
            $param_resolution_brief = $resolution_brief;
            $param_resolved_time = $resolved_time;
            $param_ticket_status = $ticket_status;
            $param_handler = $handler;
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
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
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 100%;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add complaint record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                         <div class="form-group">
                            <label>MPESA Reference NO</label>
                            <input type="text" name="bet_ref" class="form-control <?php echo (!empty($bet_ref_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $bet_ref_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Complaint Details</label>
                            <textarea name="complaint" class="form-control "></textarea>
                        </div>
                        <div class="form-group">
                             <?php
date_default_timezone_set("Africa/Nairobi");
$time=date("d.m.Y, h:i:sa");
?>
                            
                            <input type="hidden" name="entry_time" class="form-control " value="<?php echo $time; ?>">
                            
                        </div>
                        <div class="form-group">
                          
                            <input type="hidden" name="resolved_time" class="form-control" value="update on resolution">
                           
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
                            <input type="text" name="handler" class="form-control <?php echo (!empty($handler_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $handler; ?>">
                            <span class="invalid-feedback"><?php echo $handler_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>