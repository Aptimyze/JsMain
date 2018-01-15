function validateInputs()
{
	var username = $("#username").val();
	username = username.replace(/^\s*|\s*$/,"");
	if(username=='')
	{
		alert("Please enter a username to continue");
		return false;
	}
}

$(document).ready(function() {
	showDateSelectionField("visit_date",startYear,endYear);
});
