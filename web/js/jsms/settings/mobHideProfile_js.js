$(document).ready(function(){
	
	$("#hidePasswordCheck").bind('click',function()
	{
		var pswrd = $('#passValueID').val();
		// hide
		action = 1;
		ajaxPassword(pswrd,action);
	});

	$("#unHidePasswordCheck").bind('click',function()
	{
		var pswrd = $('#passValueID').val();
		// Unhide
		action = 0;
		ajaxPassword(pswrd,action);
	});
});

function ajaxPassword(pswrd, action)
{
	$.ajax({                 
	url: '/api/v1/common/checkPassword',
	data: "&pswrd="+pswrd,
	success: function(response) 
	{
		if(response.success == 1)
		{
			hideUnhideAction(action);
		}
		else if(response.success == 0)
		{
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
		var dataObject = {'hide_option' : hideOption, 'actionHide' : action};
	}
	else
	{
		// to UnHide the user
		var dataObject = {'actionHide' : action};
	}

	$.ajax({
		url : '/api/v1/settings/hideUnhideProfile',
		dataType: 'json',
		data : dataObject,
		success: function(response)
		{
			if(response.success == 1)
			{
				if(action)
				{
					parent.location.href = "/static/hideDuration?hide_option="+hideOption;
				}
				else
				{
					parent.location.href = "/static/unHideResult";
				}
			}
			else
			{
				setTimeout(function(){ShowTopDownError(["<center>Something went wrong</center>"]);},animationtimer);
			}
		}
	});
}