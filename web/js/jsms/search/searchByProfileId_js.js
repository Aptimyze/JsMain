AndroidPromotion=0;
$( document ).ready(function() {
	$('body').css("background-color","white");
	if(!ISBrowser("UC"))
	{
		$("#searchPId").focus();
		$("#inputProfileId").css("padding-top","20%");
		if(ISBrowser("safari"))
		{
			$("#inputProfileId").css("padding","25%");
		}
	}
	
	var elementSPid = document.getElementById("searchPId"); 
	var focused = false;
	var headHeight=$("#head").height();
	var footHeight=$("#foot").height();
	var windowHeight=$(window).height();
	var virtualKeyboardHeight = function () {
		var sx = document.body.scrollLeft, sy = document.body.scrollTop;
		var naturalHeight = window.innerHeight;
		window.scrollTo(sx, document.body.scrollHeight);
		var keyboardHeight = naturalHeight - window.innerHeight;
		window.scrollTo(sx, sy);
		return keyboardHeight;
	};
	
function submitSearchPRofileID(username,urlToRedirect){
			 $.ajax({
			  url: "/api/v1/profile/detail?stype=WO",
			  type: 'POST',
			  datatype: 'json',
			  cache: true,
			  async: true,
			  data: {username : username},
			  success: function(result) {               
					if(CommonErrorHandling(result))
					{
						urlToRedirect=urlToRedirect+result.USERNAME;
						startTouchEvents(100);
						ShowNextPage(urlToRedirect,0);
					}
					else
					{
						startTouchEvents(100);
						setTimeout(function(){
							ShowTopDownError(["<center>"+result.responseMessage+"</center>"]);
						},
						animationtimer);
					}
				},
			  error: function() {
					
					setTimeout(function(){
						ShowTopDownError(["<center>something went wrong</center>"]);
					},
					animationtimer);
			
			}
		});
}


$("#searchByProfileID").bind(clickEventType,function(){
	var username=$.trim($("#searchPId").val());
	if(username){
		stopTouchEvents(1,1,1);
		var urlToRedirect='/profile/viewprofile.php?stype=WO&username=';
		submitSearchPRofileID(username,urlToRedirect);
	}
	else{
		$("#searchPId").val("");
		setTimeout(function(){
			ShowTopDownError(["<center>Please provide a profile ID</center>"]);
		},animationtimer);
	}
});

$("#closeFromSearchByProfileId").bind(clickEventType,function(){
	ShowNextPage('/profile/mainmenu.php');	
});

	$( "#searchPId" ).focus(function() {
		$("#inputProfileId").css("padding-top","20%");
		$("#searchPId").removeClass("txtc");
	});

	$( "#searchPId" ).focusout(function() {
		if(ISBrowser("safari") && !ISBrowser("UC"))
		{
			$("#inputProfileId").css("padding","25%");
		}
		else
			$("#inputProfileId").css("padding","29%");
		//$("#inputProfileId").css("padding-top","29%");
		$("#searchPId").addClass("txtc");
	});
});

