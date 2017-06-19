function uploadProgress()
{
	$("#mainContent").hide();
	showLoader('show'); 
	return true;
}

$(document).ready(function() {
	
	//hide loader
	showLoader('Hide');
	$("#mainContent").show();
});