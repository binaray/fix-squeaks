<?php
//header.php: contains constants, and navigation
//use static linking here

const ITEMS_PER_PAGE=24;
const BORDER_COLOUR = [
    "amenities"     => "#fe4848",
    "materials"    => "#dc3545",
    "electronics"     => "#2da7ff",
    "fasteners" => "#c000d7",
    "paint" => "#ce00c8",
    "chemicals" => "#28a745",
]; 

if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
?>	
<div class="header">
	<nav class="navbar navbar-expand-lg navbar-dark navbar-shrink" style="background-color: #000000!important;">
      <a class="navbar-brand" href="/">Pipsqueak Marketplace</a>
      <button href="javascript:void(0)" class="navbar-toggler openNav" onclick="openNav()" type="button" >
        <span class="navbar-toggler-icon"></span>
      </button>
	  <div id="main">
		<a href="javascript:void(0)" class="openNav"><span class="fa fa-bars" onclick="openNav()"></span></a>
	  </div>

		<div id="mySidenav" class="sidenav">
		  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
		  <ul class="mob-ul">
			<li class="nav-item">
				<form action="/items" method="get">
				  <input name="search" class="form-control form-control-sm" type="text" placeholder="Search item...">
				</form>
			</li>
			<div class="dropdown">
			  <li class="bt-div" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<a class="nav-link" href="#">Browse by category</a>
			  </li>
			  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="width: 100%; border-radius: 0;">
				<a class="dropdown-item" href="/items">All</a>
				<a class="dropdown-item" href="/items?category=electronics">Electronics</a>
				<a class="dropdown-item" href="/items?category=materials">Material</a>
				<a class="dropdown-item" href="/items?category=fasteners">Fasteners</a>
				<a class="dropdown-item" href="/items?category=paint">Paint</a>
				<a class="dropdown-item" href="/items?category=chemicals">Chemicals</a>
				<a class="dropdown-item" href="/items?category=amenities">Amenities</a>
			  </div>
			</div>
			
			<li class="nav-item"><a class="nav-link" href="/checkout">Cart</a></li>
			<?php
			if (!isset($_SESSION['email']))
				echo'
			<li class="nav-item button_login"><a class="nav-link" href="#">Login</a></li>
			<li class="nav-item button_register bt-div"><a class="nav-link" href="#">Register</a></li>';
			else
				echo '
			<li class="nav-item bt-div"><a class="nav-link" href="/?logout=true">Logout</a></li>';
			?>
			<li class="nav-item"><a class="nav-link" href="/orders">Orders</a></li>
			<li class="nav-item bt-div"><a class="nav-link" href="/sell">Sell</a></li>
			
		  </ul>
		</div>
		
		<div class="collapse navbar-collapse" id="navbarsExample02">
			<form class="form-inline mr-auto" action="/items" method="get">
			  <input name="search" class="form-control form-control-sm" type="text" placeholder="Search item..." style="width: 250px;">
			</form>

			<ul class="navbar-nav">
			  <li class="nav-item">
				<a class="nav-link" href="/checkout">Cart</a>
			  </li>
			<?php
			if (!isset($_SESSION['email']))
				echo'
			  <li class="nav-item button_login">
				<a class="nav-link" href="#">Login</a>
			  </li>
			  <li class="nav-item button_register">
				<a class="nav-link" href="#">Register</a>
			  </li>';
			else
				echo '
			  <li class="nav-item">
				<a class="nav-link" href="/?logout=true">Logout</a>
			  </li>';
			?>
			</ul>
		</div>
    </nav>
			
	<nav class="navbar navbar-expand-lg navbar-light bg-light rounded d-none d-lg-block" style="padding: .25rem 0 0 0">
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbarsExample08" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	  </button>
	  <div class="collapse navbar-collapse justify-content-md-center" id="navbar">
		<ul class="navbar-nav">
		  <li class="nav-item filter-button" data-filter="all">
			<a class="nav-link" href="/items">All</a>
		  </li>
		  <li class="nav-item filter-button" data-filter="material">
			<a class="nav-link" href="/items?category=materials">Material</a>
		  </li>
		  <li class="nav-item filter-button" data-filter="electronics">
			<a class="nav-link" href="/items?category=electronics">Electronics</a>
		  </li>
		  <li class="nav-item filter-button" data-filter="fasteners">
			<a class="nav-link" href="/items?category=fasteners">Fasteners</a>
		  </li>
		  <li class="nav-item filter-button" data-filter="paint">
			<a class="nav-link" href="/items?category=paint">Paint</a>
		  </li>
		  <li class="nav-item filter-button" data-filter="chemicals">
			<a class="nav-link" href="/items?category=chemicals">Chemicals</a>
		  </li>
		  <li class="nav-item filter-button" data-filter="amenities">
			<a class="nav-link" href="/items?category=amenities">Amenities</a>
		  </li>
		  <li class="nav-item disabled">
			<div class="nav-link">|</div>
		  </li>		  
		  <li class="nav-item">
			<a class="nav-link" href="/orders">Orders</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" href="/sell">Sell</a>
		  </li>
		  <?php 
		  if (isset($_SESSION['email'])){
			  if ($_SESSION['email']=="bigsqueak@pipsqueak.com")
				  echo 
					'<li class="nav-item disabled">
						<div class="nav-link">|</div>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/admin/upload">Upload</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/admin/orders">User Orders</a>
					</li>';
		  }
		  ?>
		</ul>
	  </div>
	</nav>

</div>

<div class="fixed-top overlay">
<?php 
if (!isset($_SESSION['email'])){
	echo 
		'<div class="overlay_register overlay_form">
			<h3 class="overlay_header">Register</h3>
			<form enctype="multipart/form-data" action="logon/register" method="post">
				<input type="text" id="input_name" name="name" class="form-control" placeholder="Name" required>
				<input type="email" id="input_email" name="email" class="form-control" placeholder="Email" required>
				<div class="form-row">
					<div class="form-group col-md-6">
						<input type="password" class="form-control" id="input_password" placeholder="Password" required>
					</div>
					<div class="form-group col-md-6">
						<input type="password" class="form-control" id="input_password_confirm" name="password" placeholder="Confirm Password" required>
					</div>
					</div>
				<div class="input-group">
					<div class="input-group-prepend">
					  <span class="input-group-text" id="validationTooltipUsernamePrepend">65</span>
					</div>
					<input type="tel" class="form-control" id="validationTooltipUsername" name="phone" placeholder="Phone number" aria-describedby="validationTooltipUsernamePrepend" required>
				</div>
				<div class="form-group mt-3">
					<input type="submit" class="btn btn-primary" value="Submit">
					<input type="reset" class="btn btn-default button_cancel" value="Cancel">
				</div>
			</form>
		</div>
		
		<div class="overlay_login overlay_form">
			<h3 class="overlay_header">Login</h3>
			<form enctype="multipart/form-data" action="logon/login" method="post">
				<input type="text" name="email" class="form-control" placeholder="Email" required>
				<input type="password" name="password" class="form-control" placeholder="Password" required>
				<div class="form-group mt-3">
					<input type="submit" class="btn btn-primary" value="Submit">
					<input type="reset" class="btn btn-default button_cancel" value="Cancel">
				</div>
			</form>
		</div>';
}
if(isset($_GET["id"])){
	echo'<div class="overlay_listing overlay_form">
			<h3 id="overlay_header_listing" class="overlay_header">Selected Listing:</h3>
			<h4 id="overlay_listingProperties"></h4>
			<form>
				<p id="overlay_listingPrice"></p>
				<p id="overlay_listingStock"></p>
				<label>Quantity:</label>
				<input id="input_listingQuantity" type="number" class="form-control" placeholder="Quantity" value="1" step=1 min=1 required>
				<div class="form-group mt-3">
					<button id="button_addListingToCart" type="button" class="btn btn-primary">Add Listed item to cart</button>
					<input type="reset" class="btn btn-default button_cancel" value="Cancel">
				</div>
			</form>
		</div>';
}
?>
</div>

<!--Announcements-->
<div id="announcement" class="alert alert-warning m-3 pb-3" role="alert">
  Warning: Pipsqueak web is still facing teething issues. While it can be used to browse through listings, Pipsqueak still doesn't really know what to do if you send commands to her through the web for now. If you'd like to help Pipsqueak by sending your suggestions, technical expertise, cheese or well-wishes, you may contact her technical advisor at <a href="mailto:ray_cheng@mymail.sutd.edu.sg" class="alert-link">ray_cheng@mymail.sutd.edu.sg</a>
</div>