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
	stopTouchEvents(1,1,1);
	$.ajax({                 
	url: '/api/v1/common/checkPassword',
	data: "data=" + JSON.stringify({'pswrd' : pswrd}),
	success: function(response) 
	{
		if(response.success == 1)
		{
			hideUnhideAction(action);
		}
		else if(response.success == 0)
		{
			setTimeout(function(){ startTouchEvents(10); ShowTopDownError(["<center>Invalid Password</center>"]);},animationtimer);
		}
	}
  });
}

function hideUnhideAction(action)
{

	if(action)
	{
		// to hide the user
		var dataObject = JSON.stringify({'hide_option' : hideOption, 'actionHide' : action});
	}
	else
	{
		// to UnHide the user
		var dataObject = JSON.stringify({'actionHide' : action});
	}

	$.ajax({
		url : '/api/v1/settings/hideUnhideProfile',
		dataType: 'json',
		data : 'data='+dataObject,
		success: function(response)
		{
			if(response.success == 1)
			{
				url = action ? "/static/hideDuration?hide_option="+hideOption : "/static/unHideResult";
				ShowNextPage(url,0);
			}
			else
			{
				setTimeout(function(){ startTouchEvents(10); ShowTopDownError(["<center>Something went wrong</center>"]);},animationtimer);
			}
		}
	});
}