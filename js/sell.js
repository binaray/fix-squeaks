'use strict'
let log = console.log.bind(console);

$(document).ready(function () {
	var allItemIds = $( ".itemId" );
	
	$('.sellable').click(function(){
		let itemId = $(this).find('.itemId').text();
		
		$.get("ajax/items?item="+itemId, function(data) {
			$('#overlay_sell').show();
			$('#overlay_sell_title').text("Sell: "+data.itemName);
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
					let optionTitle=Object.keys(options[option])[0];
					
					log(optionTitle);
					spinner_html+='<label>'+optionTitle+'</label>'+
					'<input name="type'+option_count+'" style="display: none; value="'+optionTitle+'">'+
					'<select name="property'+option_count+'" class="form-control">';
					
					log(options[option]);
					var selection;
					for (selection in options[option][optionTitle]){
						spinner_html+='<option>'+options[option][optionTitle][selection]+'</option>';
						log(options[option][optionTitle][selection]);
					}	
					spinner_html+='</select>';
					option_count++;
				}
				$( "#spinner_html" ).replaceWith("<div id='spinner_html'>"+spinner_html+"</div>");
			}
		}, "json");
	});

	$('.button_cancel').click(function(){
		$('#overlay_sell').hide();
	});

});