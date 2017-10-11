//This function is used to check the response for requested photo
function requestphototag(profilechecksum,idd)
{ 
		$("#requestphoto"+idd+" #loader").show();
		var logmsg = $("#label"+idd).html();
		console.log(logmsg);
		if(logmsg.indexOf('ogin') >= 0){
			ShowNextPage('/static/LogoutPage?regMsg=Y',0);
		}
		
		else
		{
			$.ajax(
			{          	
				url: '/api/v1/social/requestPhoto',
				type: 'GET', data:	{"profileChecksum":profilechecksum},
				   //timeout: 5000,
				success: function(response)
				{
					CommonErrorHandling(response,'?regMsg=Y');
					if(response.actionDetails && response.actionDetails.errmsglabel)
					{
						$("#requestphoto"+idd+" #loader").hide();
						showSlider('#searchHeader',response.actionDetails.errmsglabel,'',1);
					}
					else if(response.responseMessage== "Successful")
					{
						$("#requestphoto"+idd+" #loader").hide();
						$("#label"+idd).html(response.imageButtonDetail.label);
						$('#label'+idd).removeClass("white fontthin f18 lh30 dispbl txtc trans2 srp_pad1");
						$('#label'+idd).addClass("white fontthin f18 lh30 dispbl txtc trans1 srp_pad1");
						$("#label"+idd).removeAttr('href');
						$('#label'+idd).attr('onclick','').unbind('click');	
					}
					else if(response.responseMessage== "Please login to continue")
					{
						$("#requestphoto"+idd+" #loader").hide();
						$("#label"+idd).html("Please login to continue");
					}
				}
			});
		}
}
