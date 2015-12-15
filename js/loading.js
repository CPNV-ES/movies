/* Start by setting 
	display:none 
	to make this hidden.
	Then we position it in relation to the viewport window with 
   	position:fixed 
	Width, height, top and left speak
   	for themselves. 
	Background we set to 80% white with
   	our animation centered, and no-repeating */

$body = $("body");

$(document).on({
	ajaxStart: function() { 
		$body.addClass("loading");    
	},
	
	ajaxStop: function() { 
		$body.removeClass("loading"); 
	}    
});