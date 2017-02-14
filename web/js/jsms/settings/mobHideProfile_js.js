$(document).ready(function(){
	
	$("#hidePasswordCheck").bind('click',function()
	{
		var pswrd = $('#passValueID').val();
		ajaxPassword(checksum,pswrd);
	});

});

function ajaxPassword(checksum,pswrd)
{
  $.ajax({                 
	url: '/profile/password_check.php?',
	data: "checksum="+checksum+"&pswrd="+pswrd,
	success: function(response) 
	{
		if(response=="true")
	  	{
	  		hideAction();
	  	}
	  	else
		{
			// parent.location.href= "/static/hideDuration?hide_option="+hideOption;
			setTimeout(function(){ShowTopDownError(["<center>Invalid Password</center>"]);},animationtimer);
	  	}
	}
  });
}

function hideAction()
{
	$.ajax({
		url : '/api/v1/settings/hideProfile',
		data : "hide_option="+hideOption,
		success: function(response)
		{
			console.log(response);
			if(response == "true")
			{
				parent.location.href= "/static/hideDuration?hide_option="+hideOption;
			}
			else
			{
				setTimeout(function(){ShowTopDownError(["<center>Something went wrong</center>"]);},animationtimer);
			}
		}
	});
}