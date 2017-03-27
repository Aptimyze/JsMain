//This function is used to check the response for requested photo
function requestphoto(profilechecksum,element,addClass)
{ 
        if(typeof addClass == "undefined")
            addClass="";
	showCommonLoader();
	$.myObj.ajax({
		url: '/api/v1/social/requestPhoto',
		type: 'GET', data:	{"profileChecksum":profilechecksum},
		success: function(response)
		{
			hideCommonLoader();
			callAfterContact();
			if(response.actionDetails && response.actionDetails.errmsglabel)
			{
				innerDiv = "<div class=' txtc colrw opa80 "+addClass+" mauto wid150'>"+response.actionDetails.errmsglabel+"</div>";
				$("#"+element).removeClass("js-hasaction").removeClass("srppos3");
				$("#"+element).addClass("js-noaction").addClass("srppos4");
				$("#"+element).removeAttr("myaction").removeAttr("data");
				$("#"+element).html(innerDiv);
				$("#"+element).unbind();
				  
			}
			else if(response.responseMessage== "Successful")
			{
				innerDiv = "<div class=' txtc colrw opa80 "+addClass+" mauto wid150'>"+response.imageButtonDetail.label+"</div>";
				$("#"+element).removeClass("js-hasaction").removeClass("srppos3");
				$("#"+element).addClass("js-noaction").addClass("srppos4");
				$("#"+element).removeAttr("myaction").removeAttr("data");
				$("#"+element).html(innerDiv);
				$("#"+element).unbind();
			}
		}
	});
}
