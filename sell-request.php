<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sell</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <style type="/css/style.css" rel="stylesheet"></style>
</head>
<body class="bg-light">
    <div class="container">
        <h2>Sign Up</h2>
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
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="Confirm password" required>
            </div>
			
			<div class="form-group">
				<label for="name">Full name</label>
				<input type="text" name="name" class="form-control" id="name" placeholder="Full name" required>
				<span class="help-block">Note that name given will be reflected in receipts</span>
            </div>
			
			<div class="form-group">
                <label>Phone number</label>
                <input type="number" name="phone" class="form-control" placeholder="Phone number" required>
            </div>
			
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Already have an account? <a href="login<?php echo(empty($_GET["redirect"])) ? "" : "?redirect=".$_GET["redirect"];?>">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>