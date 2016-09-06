function validateFormInputs()
{
	var exclusiveEmail = $("#exclusiveEmail").val(),profileUsernameList = $("#profileUsernameList").val();
	exclusiveEmail = exclusiveEmail.replace(/^\s*|\s*$/,"");
	profileUsernameList = profileUsernameList.replace(/^\s*|\s*$/,"");
	//console.log("ankita",typeof profileUsernameList);
	//console.log("ankita1",profileUsernameList.replace(/^\s*|\s*$/,""));
	if(exclusiveEmail == '' || typeof profileUsernameList == "undefined" || profileUsernameList == '')
	{
		//check email validation
		alert("Email id of exclusive customer and username list must be non-empty");
		return false;
	}
	else{
		//console.log("profileUsernameListParsed1",profileUsernameListParsed);
		var profileUsernameListParsed = profileUsernameList.replace(/\n/g, "||");
		//console.log("profileUsernameListParsed2",profileUsernameListParsed);
		$("#profileUsernameListParsed").val(profileUsernameListParsed);
	}
}