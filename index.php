<?php
require_once "config.php";

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
	<style>
		.navbar{
			padding: .25rem 0 0 0;
		}
		.overlay{
			display: none;
			position: fixed;
			width: 100%;
			height: 100%;
			background-color: rgba(0,0,0,0.7);
			z-index: 2000;
		}
		.overlay_form{			
			position: absolute;
			padding: 20px 40px;
			border-radius: .25rem;
			width: 100%;
			max-width: 800px;
			max-height: 550px;
			left: 50%;
			top: 50%;
			transform: translateX(-50%) translateY(-50%);
			background-color: #f8f8f8;
			overflow: hidden;
		}
		.overlay_register{
			display: none;
		}
		.overlay_login{
			display: none;
		}
		
		.container{
			margin-top: 1rem;
		}
		.item{
			margin: .25rem 0;
			cursor: pointer;
		}
		.item:hover {
			color: #495057;
			background-color: #fff;
			border-color: #80bdff;
			outline: 0;
			box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
		}
	</style>
    <title>fixsqueaks</title>
  </head>
  
  <body>
	<div class="fixed-top overlay">
		<div class="overlay_register overlay_form">
			<h3 class="overlay_header">Register</h3>
			<form enctype="multipart/form-data" action="/upload/namepicbioblock" method="post">
				<input type="text" id="input_name" name="name" class="form-control" placeholder="Name" required>
				<input type="email" id="input_email" name="email" class="form-control" placeholder="Email" required>
				<div class="form-row">
					<div class="form-group col-md-6">
						<input type="password" class="form-control" id="input_password" placeholder="Password">
					</div>
					<div class="form-group col-md-6">
						<input type="password" class="form-control" id="input_password_confirm" placeholder="Confirm Password">
					</div>
					</div>
				<div class="input-group">
					<div class="input-group-prepend">
					  <span class="input-group-text" id="validationTooltipUsernamePrepend">65</span>
					</div>
					<input type="tel" class="form-control" id="validationTooltipUsername" placeholder="Phone number" aria-describedby="validationTooltipUsernamePrepend" required>
				</div>
				<div class="form-group">
					<input type="submit" class="btn btn-primary" value="Submit">
					<input type="reset" class="btn btn-default button_cancel" value="Cancel">
				</div>
			</form>
		</div>
		
		<div class="overlay_login overlay_form">
			<h3 class="overlay_header">Login</h3>
			<form enctype="multipart/form-data" action="/upload/namepicbioblock" method="post">
				<input type="text" name="email" class="form-control" placeholder="Email" required>
				<input type="password" name="password" class="form-control" placeholder="Password" required>
				<div class="form-group">
					<input type="submit" class="btn btn-primary" value="Submit">
					<input type="reset" class="btn btn-default button_cancel" value="Cancel">
				</div>
			</form>
		</div>
	</div>
	
	<div class="header">
		<form class="form-inline">	
			<div class="">
				<div>FixSqueaks</div>
				<div>we buy and fix things</div>
			</div>
			<div class="form-inline mx-auto">
				<input type="text" class="form-control" placeholder="Search item..." id="searchInput" class="search">
				<button type="button" class="btn btn-outline-primary">Search</button>
			</div>
			<div class="">
				<button id="button_login" type="button" class="btn btn-outline-success">Login</button>
				<button id="button_register" type="button" class="btn btn-outline-danger">Register</button>
			</div>
		</form>
				
		<nav class="navbar navbar-expand-lg navbar-light bg-light rounded">
		  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample08" aria-controls="navbarsExample08" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		  </button>
		  <div class="collapse navbar-collapse justify-content-md-center" id="navbarsExample08">
			<ul class="navbar-nav">
			  <li class="nav-item active filter-button" data-filter="all">
				<a class="nav-link" href="#">All</a>
			  </li>
			  <li class="nav-item filter-button" data-filter="material">
				<a class="nav-link" href="#">Material</a>
			  </li>
			  <li class="nav-item filter-button" data-filter="electronics">
				<a class="nav-link" href="#">Electonics</a>
			  </li>
			  <li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="http://example.com" id="dropdown08" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>
				<div class="dropdown-menu" aria-labelledby="dropdown08">
				  <a class="dropdown-item" href="#">Action</a>
				  <a class="dropdown-item" href="#">Another action</a>
				  <a class="dropdown-item" href="#">Something else here</a>
				</div>
			  </li>
			</ul>
		  </div>
		</nav>
	</div>

  
	<div class="container">
		<div class="row">
		<?php
			for ($x=0; $x<6; $x++){
				echo 
				'<div class="col-6 col-sm-4 col-md-3 col-lg-3 col-xl-2 filter material">
					<div class="img-thumbnail item">
						<img src="https://via.placeholder.com/150x150" alt="{$item}" style="width:100%">
						<div class="caption">
							<div align="center" class="text_item">Item 000</div>
							<div align="center" class="text_category">Category</div>
							<div align="center" class="text_price">Price</div>
						</div>
					</div>
				</div>';
			}
			for ($x=0; $x<6; $x++){
				echo 
				'<div class="col-6 col-sm-4 col-md-3 col-lg-3 col-xl-2 filter electronics">
					<div class="img-thumbnail item">
						<img src="https://via.placeholder.com/150x150" alt="{$item}" style="width:100%">
						<div class="caption">
							<div align="center" class="text_item">Item 000</div>
							<div align="center" class="text_category">Category</div>
							<div align="center" class="text_price">Price</div>
						</div>
					</div>
				</div>';
			}
		?>
		</div>

	</div>
	
	<div class="footer">
	</div>
	
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>	
	<script type="text/javascript">
	$(document).ready(function () {
		$('#button_login').click(function(){
			$('.overlay').show();
			$('.overlay_login').show();
		});
		$('#button_register').click(function(){
			$('.overlay').show();
			$('.overlay_register').show();
		});
		
		$('.button_cancel').click(function(){
			$('.overlay_login').hide();
			$('.overlay_register').hide();
			$('.overlay').hide();
		});
		
		 $(".filter-button").click(function(){
			var value = $(this).attr('data-filter');			
			if(value == "all")
			{
				$('.filter').show();
			}
			else
			{
				$(".filter").not('.'+value).hide();
				$('.filter').filter('.'+value).show();    
			}
			$(".filter-button").removeClass("active")
			$(this).addClass("active");
		});
	});
	</script>
  </body>
</html>