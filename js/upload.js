'use strict'

$(document).ready(function () {
	var option_count=0;
	var option_combinations=[];
	toggleDelButton();
	
	$("#inputOptions"+option_count).keyup(function(event){
		if ( event.which == 13 ) {
			event.preventDefault();
		}
		getItemHelp();
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
			getItemHelp();
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
		option_combinations=[];
		let itemsHelp=recursiveCheck(option_count);
		console.log(itemsHelp);
		$( "#itemsHelp" ).replaceWith("<div id='itemsHelp'>"+itemsHelp+"</div>");
	}
	
	function toggleDelButton(){
		if (option_count>0) $("#button_deleteOption").show();
		else $("#button_deleteOption").hide();
	}
	
	//magic function
	function recursiveCheck(currentDepth, cursor=""){
		// let res="";
		var optionString = $( "#inputOptions"+currentDepth).val();
		var options=optionString.split(',');
		for (var x in options){
			if (currentDepth!=0){
				recursiveCheck(currentDepth-1,cursor+" "+options[x]);
			}
			else option_combinations.push(cursor+' '+options[x]+': description, imageUrl, price, availability<br>');
		}
		return option_combinations;
	}
});
