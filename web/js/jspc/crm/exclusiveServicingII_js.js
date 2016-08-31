function validateFormInputs()
{
	var exclusiveEmail = $("#exclusiveEmail").val(),profileUsernameList = $("#profileUsernameList").val();
	exclusiveEmail = exclusiveEmail.replace(/^\s*|\s*$/,"");
	//profileUsernameList = profileUsernameList.replace(/^\s*|\s*$/,"");
	if(exclusiveEmail == '' || profileUsernameList == '')
	{
		//check email validation
		alert("Email id of exclusive customer and username list must be non-empty");
		return false;
	}
	else{
		var profileUsernameListParsed = profileUsernameList.replace(/\n/g, "||");
		$("#profileUsernameListParsed").val(profileUsernameListParsed);
	}
}

$(document).ready(function() {
	
});
