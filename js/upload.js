'use strict'

$(document).ready(function () {
	var option_count=0;
	toggleDelButton();
	
	$("#inputOptions"+option_count).keyup(function(event){
		if ( event.which == 13 ) {
			event.preventDefault();
		}
		let itemsHelp=recursiveCheck(option_count);
		console.log(itemsHelp);
		$( "#itemsHelp" ).replaceWith("<div id='itemsHelp'>"+itemsHelp+"</div>");
	});
	
	$("#button_addOption").click(function(){
		option_count++;
		// $("#itemOptions").append('<input type="text" class="form-control itemOptions" name="options'+option_count+'" id="inputOptions'+option_count+'" placeholder="option1, option2...">');
		let optionHtml='<div id="options'+option_count+'" class="form-group form-row">'+
					'<input type="text" class="form-control col-3" name="type'+option_count+'" placeholder="type">'+
					'<input type="text" class="form-control itemOptions col-9" name="options'+option_count+'" id="inputOptions'+option_count+'" placeholder="option1, option2...">'+
				'</div>';
		$("#itemOptions").append(optionHtml);
		$("#inputOptions"+option_count).keyup(function(event){
			if ( event.which == 13 ) {
				event.preventDefault();
			}
			let itemsHelp=recursiveCheck(option_count);
			console.log(itemsHelp);
			$( "#itemsHelp" ).replaceWith("<div id='itemsHelp'>"+itemsHelp+"</div>");
		});
		toggleDelButton();
	});
	
	$("#button_deleteOption").click(function(){
		$( "#options"+option_count ).remove();
		option_count--;
		getItemHelp();
		toggleDelButton();
	});
	
	function getItemHelp(){		
		let itemsHelp=recursiveCheck(option_count);
		console.log(itemsHelp);
		$( "#itemsHelp" ).replaceWith("<div id='itemsHelp'>"+itemsHelp+"</div>");
	}
	
	function toggleDelButton(){
		if (option_count>0) $("#button_deleteOption").show();
		else $("#button_deleteOption").hide();
	}
	
	function recursiveCheck(currentDepth){
		let res="";
		var optionString = $( "#inputOptions"+currentDepth).val();
		var options=optionString.split(',');
		for (var x in options){
			console.log(options[x]);
			res=res+" "+options[x];
			if (currentDepth!=0){
				res=res+" "+recursiveCheck(currentDepth-1);
			}
			else res+=': description, imageUrl, price, availability<br>';
		}
		return res;
	}
});
