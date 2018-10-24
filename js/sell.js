'use strict'
let log = console.log.bind(console);

$(document).ready(function () {
	var allItemIds = $( ".itemId" );
	
	$('.sellable').click(function(){
		let itemId = $(this).find('.itemId').text();
		
		$.get("ajax/itemDetails?item="+itemId, function(data) {
			$('.overlay').show();
			$('.overlay_header').text("Sell: "+data.itemName);
			$('#input_itemId').val(itemId);
			if(data.options==null){
				log("Single item");
				$( "#spinner_html" ).replaceWith("<div id='spinner_html'></div>");
			}
			else{
				log("Multi item");
				let spinner_html='';
				let options=data.options;
				let option_count=0;
				var option;
				
				for (option in options){
					spinner_html+='<label>'+option+'</label>'+
					'<input name="type'+option_count+'" style="display: none; value="'+option+'">'+
					'<select name="property'+option_count+'" class="form-control">';
					log(option+":");
					var selection;
					for (selection in options[option]){
						spinner_html+='<option>'+options[option][selection]+'</option>';
						log(options[option][selection]);
					}	
					spinner_html+='</select>';
					option_count++;
				}
				$( "#spinner_html" ).replaceWith("<div id='spinner_html'>"+spinner_html+"</div>");
			}
		}, "json");
	});

	$('.button_cancel').click(function(){
		$('.overlay').hide();
	});

});