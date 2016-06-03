//Mob Header js file
var clickEventType=((document.ontouchstart!==null)?'click':'touchstart');
clickEventType='click';
var left_ml="left_ml";
var right_ml="right_ml";
FixLeftProperty();
var fixedPosTop=0;
var toggled=0;
if(typeof SSL_SITE_URL=="undefined")
		var SSL_SITE_URL="https://"+top.location.host;
$("document").ready(function(){
$("#list-icon").bind(clickEventType,function(ev){
	if($("#appPromo"))
		$("#appPromo").remove();
	if($("#appPromoHide"))
		$("#appPromoHide").remove();
	ev.stopPropagation(); ev.preventDefault();
	if(!toggled)
	{
		$("#slider").removeClass("hideslider");
		$("#maincomponent").addClass(left_ml);
		
		block("#list-icon");
		setTimeout(function(){$("#maincomponent").addClass("transform");},300);	
	}
	else
	{		
		$("#maincomponent").removeClass(left_ml);

		//$("#slider").addClass("transform").addClass("hideslider");
		setTimeout(function(){$("#maincomponent").removeClass("transform");$("#slider").addClass("hideslider");},300);
	}
	toggleStickyHeader(toggled);
	toggled=!toggled;
});
var registerPage=0;
if((document.location.href).indexOf("register")!=-1)
{
	registerPage=1;
	$("#header").css("position","relative");
  $("#mainpart").css("margin-top","0px");
}
function toggleStickyHeader(toggle)
{
	if(registerPage)
		return;
	if(toggle)
	{//Hide Hamburger
		window.scroll = function(){$("header").css('top','0px');};
		$("#header").css("position","fixed");
		$("#header").css("z-index",9999);
		setTimeout(function(){$("#header").css("left","0");},200);

		$("#mainpart").css("margin-top","50px");
		setTimeout(function(){document.body.scrollTop =fixedPosTop},301);
	}
	else
	{//Show Hamburger
		window.scroll = null;
		$("#header").css("position","relative");
		$("#header").css("z-index",0);
		
		$("#mainpart").css("margin-top","0px");
		fixedPosTop = document.body.scrollTop;
		document.body.scrollTop = 0;
	}
}
$("#log-icon").bind(clickEventType,function(event){
	if($("#appPromo"))
		$("#appPromo").remove();
	if($("#appPromoHide"))
		$("#appPromoHide").remove();
	event.preventDefault();
	if(!toggled)
	{
		$("#slider_right").removeClass("hideslider");

		$("#maincomponent").addClass(right_ml);

		block("#log-icon");
		setTimeout(function(){$("#maincomponent").addClass("transform");},300);

	}
	else
	{
		$("#maincomponent").removeClass(right_ml);

		setTimeout(function(){$("#maincomponent").removeClass("transform");$("#slider_right").addClass("hideslider");},300);

	}
	toggleStickyHeader(toggled);
	toggled=!toggled;
});
$(".b7nHUd").bind("touchmove click",function(event){
	CheckOpen(event);
	event.preventDefault();
});
//touch events on mainpart
/*
var touches;
var startX=-1;
var startY=-1;
$("#mainpart").bind("touchstart touchmove",function(event){
event = $.event.fix(event);
touches = event.originalEvent.touches;
if(touches.length == 1)
{
	if(startX!=-1 && event.type!='touchstart')
	{
		var tempX=startX-touches[0].pageX;
		var tempY=startY-touches[0].pageY;
		if(Math.abs(tempX)>90 && Math.abs(tempY)<15)
		{
			event.preventDefault();
			if(tempX<0)
				$("#list-icon").trigger(clickEventType);
			else
				$("#log-icon").trigger(clickEventType);
			
			startX=-1;
			startY=-1;
		}
		
	}
	else
	{
		if(startX==-1)
		{
			startX = touches[0].pageX;
			startY = touches[0].pageY;
		}
	}
}
});
*/
});
function CheckOpen(ev)
{
	if($("#maincomponent").hasClass(right_ml))
	{
		$("#log-icon").trigger(clickEventType);
	}
	if($("#maincomponent").hasClass(left_ml))
	{
		$("#list-icon").trigger(clickEventType);
	}
$(".b7nHUd").removeClass("hamactive");
	ev.preventDefault();

}
function block(wht)
{
	$(".b7nHUd").addClass("hamactive");
}
function FixLeftProperty()
{
	var div=document.createElement("div");
	$(div).addClass("checkL");
	if(!$(div).css("margin-left") && !navigator.userAgent.match(/Micromax/i))
	{
		left_ml="left_l";
		right_ml="right_l";	
	}
}
function loginValidate()
{
$("#error_mess").html("");
var user_name=$("#username").val();
var pass_word=$("#password").val();
if(!checkemail($("#username").val()))
{
        $("#error_mess").html(" Please provide valid Email");
        $("#username").focus();
	if(user_name && pass_word)
        {       
			loginUrl=SSL_SITE_URL+"/static/verifyAuth?username="+user_name+"password="+pass_word;
					$("#homepageLogin").attr('action',loginUrl);
                /*$.post( "/static/verifyAuth", { "username": user_name, "password": pass_word })
                          .done(function( data ) {
                            if(data)
				$("#error_mess").html(data);
                  });*/
        }
        return true;
}
else if(!$("#password").val())
{
        $("#error_mess").html(" Please provide valid password");
        $("#password").focus();
}
if($("#error_mess").html())
{
	$("#error_mess").show();
        $("#error_mess").prev().css('display','inline');
        return false;
}
else
{
	$("#error_mess").hide();
        $("#error_mess").prev().css('display','none');
}
loginUrl=SSL_SITE_URL+"/profile/login.php?redirectProperly=1";
$("#homepageLogin").attr('target', "iframe_login");
$("#homepageLogin").attr('action',loginUrl);
return true;
}
function checkemail(emailadd)
{
        var ce_results = false;
        var theStr = new String(emailadd);
        var index = theStr.indexOf("@");
        if (index > 0)
        {
        var pindex = theStr.indexOf(".",index);
        if ((pindex > index+1) && (theStr.length > pindex+2))
                ce_results = true;
        }

        return ce_results;
}

	////
function onFrameMessageReceived(message)
{
	if(message.origin === SSL_SITE_URL)
	{
			if(!window.addEventListener)
			{
				var emailCheck=message.data.indexOf("Email");
				if(emailCheck!==-1 || message.data.indexOf("invalidAuth")!==-1)
				{
					$("#homepageLogin").attr('action', "");
					if(emailCheck!==-1)
					{
						$("#error_mess").html(message.data);
						$("#error_mess").show();
						$("#error_mess").prev().css('display','inline');
						$("#error_mess").css('display','block');
					}
				}
				
			}
			else
			{
				var emailCheck=message.data.body.indexOf("Email");
				if(emailCheck!==-1 ||  message.data.body.indexOf("invalidAuth")!==-1 )
				{
					$("#homepageLogin").attr('action', "");
					if(emailCheck!==-1)
					{
						$("#error_mess").html(message.data.body);
						$("#error_mess").show();
						$("#error_mess").prev().css('display','inline');
						$("#error_mess").css('display','block');
					}
				}					
			}
	}
	
		
}
if(window.addEventListener)	
	{
		window.addEventListener("message", onFrameMessageReceived, false);
	}
	else if ( window.attachEvent ) //For IE 8
	{
		window.attachEvent( "onmessage", onFrameMessageReceived );
	}
	else if( window.onLoad)
	{
		window.onload = onFrameMessageReceived;
	}
	
