/* Start by setting 
	display:none 
	to make this hidden.
	Then we position it in relation to the viewport window with 
   	position:fixed 
	Width, height, top and left speak
   	for themselves. 
	Background we set to 80% white with
   	our animation centered, and no-repeating */

/*
$.post(
*/
 $.ajax({
	 
	url: 'scan_files.php ',
	type: 'POST',
	data: 
	{
		 fonction:'ma_fonction_php',
		 params: 
		{
					param1: 'valeur1',
					param2: 'valeur2'
		},
	},
	
	$body = $("body");
	
	$(document).on(
	{
		ajaxStart: function() 
		{ 
			$body.addClass("loading");    
		},
		
		ajaxStop: function() 
		{ 
			$body.removeClass("loading"); 
		}    
	});
	
)};