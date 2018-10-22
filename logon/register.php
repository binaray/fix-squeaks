<?php
require_once "../_config.php";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	
    if(empty(trim($_POST["email"]))){
        echo "Please enter email";
    } else{
        // Prepare a select statement
        $sql = "SELECT userId FROM Users WHERE email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
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
		
		// Set parameters
		$param_email = $email;
		$param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
		$param_name = $name;
		$param_phone = $phone;
		
		//if registering from telegram
		if(isset($_GET["telegramId"])){
			// Prepare a select statement
			$sql = "SELECT userId FROM Users WHERE telegramId = ?";

			if($stmt = mysqli_prepare($link, $sql)){
				mysqli_stmt_bind_param($stmt, "s", $param_telegramIdUnchecked);
				$param_telegramIdUnchecked = trim($_GET["telegramId"]);
				
				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					mysqli_stmt_store_result($stmt);
					
					if(mysqli_stmt_num_rows($stmt) == 1){
						echo "This telegram ID is already linked to an account!";
					
					} else{
						
						$param_telegramId = trim($_GET["telegramId"]);
						
						$sql = "INSERT INTO Users (email, password, name, phone, telegramId) VALUES (?, ?, ?, ?, ?)";						
						if($stmt = mysqli_prepare($link, $sql)){
							// Bind variables to the prepared statement as parameters
							mysqli_stmt_bind_param($stmt, "sssis", $param_email, $param_password, $param_name, $param_phone, $param_telegramId);
							
							// Attempt to execute the prepared statement
							if(mysqli_stmt_execute($stmt)){
								// Redirect to login page?
								mysqli_stmt_close($stmt);
								mysqli_close($link);
								echo "Registration successful! Close your browser or visit our <a href='../'>main page here</a>!";
							} else{
								echo "Something went wrong. Please try again later.";
							}
						}
						mysqli_stmt_close($stmt);
					}
				} else{
					echo "Oops! Something went wrong. Please try again later.";
				}
			}
			// Close statement
			mysqli_stmt_close($stmt);	
		} else{
			$sql = "INSERT INTO Users (email, password, name, phone) VALUES (?, ?, ?, ?)";
			 
			if($stmt = mysqli_prepare($link, $sql)){
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "sssi", $param_email, $param_password, $param_name, $param_phone);
				
				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					// Redirect to login page
					mysqli_stmt_close($stmt);
					mysqli_close($link);
					echo "Successfully registered!\n"
					header("location: ../");
				} else{
					echo "Something went wrong. Please try again later.";
				}
			}
			mysqli_stmt_close($stmt);
		}
    }
    mysqli_close($link);
}else{
	//show register page
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <style type="/css/style.css" rel="stylesheet"></style>
</head>
<body class="bg-light">
    <div class="container">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email"class="form-control" required>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
			
			<div class="form-group">
				<label for="name">Full name</label>
				<input type="text" name="name" class="form-control" id="name" placeholder="Name" required>
				<span class="help-block">Note that name given will be reflected in receipts</span>
            </div>
			
			<div class="form-group">
                <label>Phone number</label>
                <input type="number" name="phone" class="form-control" required>
            </div>
			
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Already have an account? <a href="login">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>