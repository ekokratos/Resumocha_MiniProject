<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$position= $company= $name= $email = $pass ="";
$position_err = $company_err = $name_err = $email_err = $pass_err = "";
$res_err="";
$type_of_alert="";
// checks if submit button is pressed
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate email
    $input_email = trim($_POST["email"]);
    if(empty($input_email)){
        $email_err = "Please enter a email.";
    } elseif(!filter_var($input_email, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/")))){
        $email_err = "Please enter a valid email.";
    } else{
        $email = $input_email;
    }
    
    // Validate pass 
    $input_pass = trim($_POST["pass"]);
    if(empty($input_pass)){
        $pass_err = "Please enter password.";     
    } else{
        $pass = $input_pass;
    }

    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter your name.";     
    } else{
        $name = $input_name;
    }

    // Validate company
    $input_company = trim($_POST["company"]);
    if(empty($input_company)){
        $company_err = "Please enter your company.";     
    } else{
        $company = $input_company;
    }

    // Validate position
    $input_position = trim($_POST["position"]);
    if(empty($input_position)){
        $position_err = "Please enter your current position.";     
    } else{
        $position = $input_position;
    }

    
    // Check input errors before inserting in database
    if(empty($email_err) && empty($pass_err) && empty($name_err) && empty($company_err) && empty($position_err)){

        $sql_ld = "INSERT INTO login_data (email, pass, account_type) VALUES (?, ?, ?)";
        
        $sql_rec = "INSERT INTO recruiters (email, name, company, position) VALUES (?, ?, ?, ?)";

        // Set parameters
        $param_acc = "recruiter";
        $param_email = $email;
        $param_pass = $pass;
        $param_name = $name;
        $param_company = $company;
        $param_position = $position;

        if($stmt1 = $mysqli->prepare($sql_ld)){
            // Bind variables to the prepared statement as parameters
            $stmt1->bind_param("sss", $_POST['email'], $_POST['pass'], $param_acc);

            if($stmt2 = $mysqli->prepare($sql_rec))
                $stmt2->bind_param("ssss", $_POST['email'], $_POST['name'], $_POST['company'], $_POST['position']);
            
            
            
            // Attempt to execute the prepared statement
            if($stmt1->execute() ){
                if($stmt2->execute()){
                    header("location: init.php");
                    exit();
                } else{
                    echo "Something went wrong. Please try again later.";
                }
                
            } 
            
        } else{
            echo "Error: ".$sql_ld."<br>";
        }
        // Close statement
            // $stmt1->close();
            // $stmt2->close();
    }
    
    // Close connection
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Resumocha</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/shards.min.css?v=3.0.0">
    <link rel="stylesheet" href="css/shards-demo.min.css?v=3.0.0">
    </head>
    <style type="text/css">
            .wrapper{
                width: 500px;
                margin: 0 auto;
            }
        </style>
<body background="img/bg.png">
<div class="card col-lg-6 col-md-6 col-sm-12" style = "margin-top:8%">
    <div class="card-body">
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="page-header">
                            <h2>Recruiter Sign up</h2>
                        </div>
                        <p>Please enter your credentials.</p>
                        <form action="<?php echo htmlspecialchars(basename($_SERVER['PHP_SELF'])); ?>" method="post">
                            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                                <label>Email</label>
                                <input type="text" name="email" class="form-control" value="">
                                <span class="help-block"><?php echo $email_err;?></span>
                            </div>

                            <div class="form-group <?php echo (!empty($pass_err)) ? 'has-error' : ''; ?>">
                                <label>Password</label>
                                <input type="password" name="pass" class="form-control" value="">
                                <span class="help-block"><?php echo $pass_err;?></span>
                            </div>

                            <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" value="">
                                <span class="help-block"><?php echo $name_err;?></span>
                            </div>

                            <div class="form-group <?php echo (!empty($company_err)) ? 'has-error' : ''; ?>">
                                <label>Company</label>
                                <input type="text" name="company" class="form-control" value="">
                                <span class="help-block"><?php echo $company_err;?></span>
                            </div>

                            <div class="form-group <?php echo (!empty($position_err)) ? 'has-error' : ''; ?>">
                                <label>Position</label>
                                <input type="text" name="position" class="form-control" value="">
                                <span class="help-block"><?php echo $position_err; ?></span>
                            </div>

                            <!-- <a href="index.html" class="btn btn-success" >Register</a> -->
                            <!-- <div> -->
                            <input type="submit" class="btn btn-primary btn-lg btn-pill" value="Sign up">
                            <a href="init.php" class="btn btn-default btn-lg btn-pill">Cancel</a>
                        </form>
                        <br><br>
                                <!-- Trigger the modal with a button -->
                                <!-- <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">Register</button> -->

                                <!-- Modal
                                <div class="modal fade" id="myModal" role="dialog">
                                    <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Register as</h4>
                                        </div>
                                        <div class="modal-body" align ="center" >
                                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModal">Recruiter</button>
                                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#myModal">User</button>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                    </div>
                                </div> -->
                            <!-- </div> -->
                        
                       
                    </div>
                    
                </div>        
            </div>
        </div>
        </div>
        </div>


    </body>
</html>