<?php
require_once "../_config.php";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	
    if(empty(trim($_POST["email"]))){
        echo "Please enter email";
    } else{
        // Prepare a select statement
        $sql = "SELECT vendorId FROM Vendors WHERE email = ?";
        
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
    }else if($_POST["password"]!=$_POST["confirm_password"]){
		echo "Password mismatch!";
	}else{
        $password = $_POST["password"];
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
			$sql = "SELECT vendorId, email FROM Vendors WHERE telegramId = ?";

			if($stmt = mysqli_prepare($link, $sql)){
				mysqli_stmt_bind_param($stmt, "s", $param_telegramIdUnchecked);
				$param_telegramIdUnchecked = trim($_GET["telegramId"]);
				if(mysqli_stmt_execute($stmt)){
					mysqli_stmt_bind_result($stmt, $result_vendorId, $result_email);
					
					while (mysqli_stmt_fetch($stmt)) {
						$stored_vendorId = $result_vendorId;
						$stored_email = $result_email;
					}
				} else{
					$overlay_message = "Oops! Something went wrong. Please try again later.";
				}
			}
			mysqli_stmt_close($stmt);
			
			if (empty($stored_email)){
				$param_telegramId = trim($_GET["telegramId"]);
				$sql = "UPDATE Vendors SET email='{$param_email}', password='{$param_password}', name='{$param_name}', phone={$param_phone} WHERE vendorId={$stored_vendorId}";
				
				if(mysqli_query($link, $sql)){
					$overlay_message = "Registration successfull. You may close this window.";
				} else {
					$overlay_message = "ERROR: Could not able to execute $sql. " . mysqli_error($link);
				}
			}
			else $overlay_message = "This telegram id is already registered to an account!";
		}
		
		//default register
		else{
			$sql = "INSERT INTO Vendors (email, password, name, phone) VALUES (?, ?, ?, ?)";
			 
			if($stmt = mysqli_prepare($link, $sql)){
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "sssi", $param_email, $param_password, $param_name, $param_phone);
				
				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					// Redirect to login page
					mysqli_stmt_close($stmt);
					mysqli_close($link);
					$overlay_message = "Successfully registered!\n";
					session_start();
					$_SESSION["admin"] = $param_email;
					header("location: index");
				} else{
					$overlay_message = "Something went wrong. Please try again later.";
				}
			}
			mysqli_stmt_close($stmt);
		}
    }
    mysqli_close($link);
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
    <link rel="stylesheet" href="../css/pipsqueaks.css">
</head>
<body class="bg-light">
	<?php 
	if(isset($overlay_message)) 
		echo'
		<div class="fixed-top overlay" style="display: block;">
			<div class="overlay_form">
				<p>'.$overlay_message.'</p>
			</div>
		</div>';
	?>

    <div class="container">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]).(empty($_GET["telegramId"])) ? "" : "?telegramId=".$_GET["telegramId"]; ?>" method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email"class="form-control" placeholder="Email" required>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="Confirm password" required>
            </div>
			
			<div class="form-group">
				<label for="name">Full name</label>
				<input type="text" name="name" class="form-control" id="name" placeholder="Full name" required>
			</div>
			
			<div class="form-group">
                <label>Phone number</label>
                <input type="number" name="phone" class="form-control" placeholder="Phone number" required>
            </div>
			
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
        </form>
    </div>    
</body>
</html>