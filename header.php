<?php
//header.php: contains constants, and navigation
//use static linking here

const ITEMS_PER_PAGE=24;
?>	
<div class="header">
	<form class="form-inline">	
		<div class="">
			<h3>Pipsqueak</h3>
			<div>Marketplace</div>
		</div>
		<div class="form-inline mx-auto">
			<input type="text" class="form-control" placeholder="Enter to search..." id="searchInput" class="search">
			<button type="button" class="btn btn-outline-primary">Search</button>
		</div>
		<?php
		if (!isset($_SESSION['email']))
			echo 
				'<button id="button_login" type="button" class="btn btn-outline-success">Login</button>
				<button id="button_register" type="button" class="btn btn-outline-danger">Register</button>';
		else
			echo '<a class="btn btn-outline-success" href="/checkout">Shopping cart</a>
				<a class="btn btn-outline-danger" href="/index?logout=true">Logout</a>';
		?>
	</form>
			
	<nav class="navbar navbar-expand-lg navbar-light bg-light rounded" style="padding: .25rem 0 0 0">
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbarsExample08" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	  </button>
	  <div class="collapse navbar-collapse justify-content-md-center" id="navbar">
		<ul class="navbar-nav">
		  <li class="nav-item filter-button" data-filter="all">
			<a class="nav-link" href="/index">All</a>
		  </li>
		  <li class="nav-item filter-button" data-filter="material">
			<a class="nav-link" href="/index?category=materials">Material</a>
		  </li>
		  <li class="nav-item filter-button" data-filter="electronics">
			<a class="nav-link" href="/index?category=electronics">Electronics</a>
		  </li>
		  <li class="nav-item filter-button" data-filter="fasteners">
			<a class="nav-link" href="/index?category=fasteners">Fasteners</a>
		  </li>
		  <li class="nav-item filter-button" data-filter="paint">
			<a class="nav-link" href="/index?category=paint">Paint</a>
		  </li>
		  <li class="nav-item filter-button" data-filter="chemicals">
			<a class="nav-link" href="/index?category=chemicals">Chemicals</a>
		  </li>
		  <li class="nav-item filter-button" data-filter="amenities">
			<a class="nav-link" href="/index?category=amenities">Amenities</a>
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
				<div class="form-group">
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
				<div class="form-group">
					<input type="submit" class="btn btn-primary" value="Submit">
					<input type="reset" class="btn btn-default button_cancel" value="Cancel">
				</div>
			</form>
		</div>';
}
if(isset($_GET["item"])){
	echo'<div class="overlay_listing overlay_form">
			<h3 id="overlay_header_listing" class="overlay_header">Selected Listing:</h3>
			<h4 id="overlay_listingProperties"></h4>
			<form>
				<p id="overlay_listingPrice"></p>
				<p id="overlay_listingStock"></p>
				<label>Quantity:</label>
				<input id="input_listingQuantity" type="number" class="form-control" placeholder="Quantity" value="1" step=1 min=1 required>
				<div class="form-group">
					<button id="button_addListingToCart" type="button" class="btn btn-primary">Add Listed item to cart</button>
					<input type="reset" class="btn btn-default button_cancel" value="Cancel">
				</div>
			</form>
		</div>';
}
?>
</div>

<div class="alert alert-warning m-3" role="alert">
  Warning: Pipsqueak web is still facing teething issues. While it can be used to browse through listings, Pipsqueak still doesn't really know what to do if you send commands to her through the web for now. If you'd like to help Pipsqueak by sending your suggestions, technical expertise, cheese or well-wishes, you may contact her technical advisor at <a href="mailto:ray_cheng@mymail.sutd.edu.sg" class="alert-link">ray_cheng@mymail.sutd.edu.sg</a>
</div>