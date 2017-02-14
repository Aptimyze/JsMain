$(document).ready(function(){
	
	$("#hidePasswordCheck").bind('click',function()
	{
		var pswrd = $('#passValueID').val();
		// hide
		action = 1;
		ajaxPassword(checksum,pswrd,action);
	});

	$("#unHidePasswordCheck").bind('click',function()
	{
		var pswrd = $('#passValueID').val();
		// Unhide
		action = 0;
		ajaxPassword(checksum,pswrd,action);
	});
});

function ajaxPassword(checksum, pswrd, action)
{
	$.ajax({                 
	url: '/profile/password_check.php?',
	data: "checksum="+checksum+"&pswrd="+pswrd,
	success: function(response) 
	{
		if(response !== "true")
		{
			hideUnhideAction(action);
		}
		else
		{
			// parent.location.href= "/static/hideDuration?hide_option="+hideOption;
			setTimeout(function(){ShowTopDownError(["<center>Invalid Password</center>"]);},animationtimer);
		}
	}
  });
}

function hideUnhideAction(action)
{

	if(action)
	{
		// to hide the user
		data = "hide_option=" + hideOption + "action=" + action;
	}
	else
	{
		// to UnHide the user
		data = "action=" + action;
	}

	$.ajax({
		url : '/api/v1/settings/hideUnhideProfile',
		data : data,
		success: function(response)
		{
			console.log(response);
			if(response == "true")
			{
				if(action)
				{
					parent.location.href= "/static/hideDuration?hide_option="+hideOption;
				}
				else
				{
					parent.location.href= "/static/unHideResult";	
				}
			}
			else
			{
				setTimeout(function(){ShowTopDownError(["<center>Something went wrong</center>"]);},animationtimer);
			}
		}
	});
}