var tupleno;
$(document).ready(function(){
$('body').on('click touchstart', '.showDetails', function()
{
	profilechecksum=$(this).attr("data-doc");
    tupleno = $(this).attr("tupleno");
    showPopup(profilechecksum,tupleno);

	
});
$('body').on('click touchstart', '.goBack', function()
{
	window.history.back();
});
$('body').on('click touchstart', '.loadStaticPage', function()
{
	window.location.href="/static/jsmsVerificationStaticPage";
	return false;
	
});
});

//This function opens the document verification layer and makes an ajax call to fetch and display data
function showPopup(profilechecksum,tupleno) 
{
	
	$(".docLayer").css({
		'height':window.innerHeight,
		'width':window.innerWidth,
		'display':"block",
		'overflow':'hidden'
	});
	disable_scrolling();
	if(profilechecksum == '')
	{
		profilechecksum=getProfileCheckSum();
	}
	$.ajax({
		method:"POST",
		url : "/api/v1/common/verificationData?profilechecksum="+profilechecksum,
		data : ({dataType:"json"}),
		async:true,
		timeout:30000,
		success:function(response){
			if(response == "")
			{
				$(".docProvided").hide();
				$(".putData").html("");
			}
			else
			{	
				if(response["documentsVerified"] == "")
				{	
					$(".docProvided").hide();
					$(".putData").hide();
				}
				else
				{
					finalResponse=response["documentsVerified"].replace(/\),/g , "),<br>");
					$(".docProvided").show();
					$(".putData").show();	
					$(".putData").html(finalResponse);
				}
			}
		}

	});
	$("#js-docVerified"+tupleno).addClass('dispblock');
	if($("#js-docVerified"+tupleno).parent('.docLayer').hasClass('dispnone')){
		$("#js-docVerified"+tupleno).parent('.docLayer').removeClass('dispnone');
	}
	$("#js-docVerified"+tupleno).removeClass('dispnone');
	if(typeof historyStoreObj != 'undefined'){
        historyStoreObj.push(onDocVerifiedBrowserBack,"#docVerified");
    }
    $(".okClick").unbind('click');
    $(".okClick").bind('click',popBrowserStack);
    return false;
}

//This function closes the document verification layer
function closePopup()
{
	$(".docLayer").css('display',"none");
	$(".docLayer").css('overflow',"auto");
	$("#js-docVerified"+tupleno).addClass('dispnone');
	$("#js-docVerified"+tupleno).removeClass('dispblock');
	$(".putData").hide();
	enable_scrolling();
	return false;
}


onDocVerifiedBrowserBack = function()
{
	if($("#js-docVerified"+tupleno).hasClass('dispnone')==false) {
		closePopup(tupleno);
		return true;
	}
	else 
		return false;

}
