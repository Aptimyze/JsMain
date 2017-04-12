$(document).ready(function(){
	
	console.log("currentMtongueFilter",currentMtongueFilter);
	$('select[name="mtongueSelect"] option:selected').attr("selected",null);
	$('select[name="mtongueSelect"] option[value="'+currentMtongueFilter+'"]').attr("selected","selected");
});