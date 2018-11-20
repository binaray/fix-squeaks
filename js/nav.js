'use strict'

function openNav() {
	document.getElementById("mySidenav").style.width = "250px";
	document.getElementById("main").style.display = "0";
	document.body.style.backgroundColor = "white";
}

function closeNav() {
	document.getElementById("mySidenav").style.width = "0";
	document.getElementById("main").style.marginRight= "0";
	document.body.style.backgroundColor = "white";
}

$(document).ready(function () {
	$('#button_close_ann').click(function(){
		$('#announcement').hide();
	});
	
	$('.button_login').click(function(){
		$('.overlay').show();
		$('.overlay_login').show();
		closeNav();
	});
	$('.button_register').click(function(){
		$('.overlay').show();
		$('.overlay_register').show();
		closeNav();
	});
	
	$('.button_cancel').click(function(){
		$('.overlay').children().hide();
		$('.overlay').hide();
	});
	
	 // $(".filter-button").click(function(){
		// var value = $(this).attr('data-filter');			
		// if(value == "all")
		// {
			// $('.filter').show();
		// }
		// else
		// {
			// $(".filter").not('.'+value).hide();
			// $('.filter').filter('.'+value).show();    
		// }
		// $(".filter-button").removeClass("active")
		// $(this).addClass("active");
	// });
});
