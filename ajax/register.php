<?php
require_once "/_config.php";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	
    if(empty(trim($_POST["email"]))){
        echo "Please enter email";
    } else{
        // Prepare a select statement
        $sql = "SELECT userId FROM Users WHERE email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    echo "This email is already taken.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        echo "Please enter a password.";     
    } else{
        $password = trim($_POST["password"]);
    }
	
	// Validate name
    if(empty(trim($_POST["name"]))){
        echo "Please enter name";     
    } else{
        $name = trim($_POST["name"]);
    }
	
	// Validate phone
    if(empty(trim($_POST["phone"]))){
        echo "Please enter phone number.";     
    } else{
        $phone = trim($_POST["phone"]);
    }
    
    // Check input errors before inserting in database
    if(!empty($email) && !empty($password)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO Users (email, password, name, phone) VALUES (?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssi", $param_email, $param_password, $param_name, $param_phone);
            
            // Set parameters
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_name = $name;
			$param_phone = $phone;
			
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: /index.php");
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