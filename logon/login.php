<?php
require_once "../_config.php";

//$redirect=$_GET["redirect"];

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate email
    if(empty(trim($_POST["email"]))){
        echo "Please enter email.";     
    } else{
        $email = trim($_POST["email"]);
    }
	
	// Validate password
    if(empty(trim($_POST["password"]))){
        echo "Please enter a password.";     
    } else{
        $password = trim($_POST["password"]);
    }
	
	// Validate credentials
    if(!empty($email) && !empty($password)){
        // Prepare a select statement
        $sql = "SELECT email, password FROM Users WHERE email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = $email;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if email exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $email, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            /* Password is correct, so start a new session and
                            save the email to the session */
                            session_start();
                            $_SESSION['email'] = $email;
							mysqli_stmt_close($stmt);
							mysqli_close($link);
							session_start();
							if (isset($_GET["redirect"])){
								header("location: {$_GET["redirect"]}");
							}
							else header("location: ../");
                        } else{
                            // Display an error message if password is not valid
                            echo 'The password you entered was not valid.';
                        }
                    }
                } else{
                    // Display an error message if email doesn't exist
                    echo 'No account found with that email.';
                }
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
    <title>Login</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/pipsqueaks.css">
</head>
<body class="bg-light">
    <div class="container">
        <h2>Login</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]).(empty($_GET["redirect"])) ? "" : "?redirect=".$_GET["redirect"]; ?>" method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email"class="form-control" placeholder="Email" required>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
			
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Don't have an account? <a href="register<?php echo(empty($_GET["redirect"])) ? "" : "?redirect=".$_GET["redirect"];?>">Register here</a>.</p>
        </form>
    </div>    
</body>
</html>