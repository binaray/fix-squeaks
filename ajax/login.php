<?php
require_once "/_config.php";

$redirect=$_GET["redirect"];

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
							switch ($redirect){
								case "requests":
									header("Location: /requests.php");
									break;
								case "sell":
									header("Location: /sell.php");
									break;
								default:
									header("Location: /index.php");
							}
                        } else{
                            // Display an error message if password is not valid
                            $password_err = 'The password you entered was not valid.';
                        }
                    }
                } else{
                    // Display an error message if email doesn't exist
                    $email_err = 'No account found with that email.';
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