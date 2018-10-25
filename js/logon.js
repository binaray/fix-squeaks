'use strict'

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
