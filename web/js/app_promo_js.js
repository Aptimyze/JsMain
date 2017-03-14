// A $( document ).ready() block.
var bProcessScroll = true;
var ap_assignRandom=new Date().getTime();
var ap_randNumber=new Date().getTime();
var OperaMiniFlag=0;
var perspective=0;
var showOldSiteMessage=1;
//var isLoaderSearch="";
var AppPromoHgt=$(window).height();
if(!getCookieData("apRandomUser"))
	writeCookie("apRandomUser",ap_assignRandom,24);
else
 ap_assignRandom=getCookieData("apRandomUser");
if(typeof(AndroidPromotion)=="undefined"){
	var AndroidPromotion=0;
}
$( document ).ready(function() {
	
      if((getAndroidVersion() || getIosVersion()) && AndroidPromotion && (typeof webView ==='undefined' || webView =="")){
      if(!getCookieData("appPromo") && (typeof appPromo === 'undefined') /*&& AppLoggedInUser*/ )
      {
		   writeCookie("appPromo","jeevansathi",3);
			if($("#main").length)
			{
				if(getAndroidVersion())
					$( "#main" ).before("<div id=\"appPromo\" class =\"app_posr app_txtc\" style =\"background-color:#721108;\"><img id= \"appPromoImg\" src=\"IMG_URL/images/mobilejs/wap_promotion3.jpg\" border=\"0\"/><div class=\"app_posa app_pos1\"><div class=\"napp_pad1\"><div class=\"app_fnt38 nfamily2 app_txtl ncolr1 app_txtl\" style=\"font-size:25px\">What our users say</div><div class=\"app_txtl app_fntb ncolr2 napp_pt1 nfamily2\">\"Amazing app. Very handy and lightning fast access to all the profiles with great picture clarity and neat fonts. Truly satisfying….\" </div><div class=\"clearfix napp_pt1\"><div class=\"pull-left\"> <div class=\"napp_rat5\"></div>                          </div><div class=\"pull-right app_fntb ncolr2 app_txtr\">Rahulkumar<br/><span class=\"app_fnta\">16th June 2014</span></div></div>  <div class=\"napp_pt20 ncolr2 app_fntb\">Get the best experience with the</br><div class =\"app_f20\">Jeevansathi App | 3 MB </div></div>  <div class=\"app_pt30\"><div class=\"app_btn app_f40\"><a href=\"/static/appredirect?type=androidLayer\" style=\"color: #fff; text-decoration:none\">Download for Free</a></div><div class=\"napp_pt_abc app_clr1 app_f16\" onclick=\"showPromo(0);\">Skip to mobile site</div></div>    </div></div><img style=\"width:0px;height:0px;\" src=\"/static/trackinterstitial?rand="+ap_randNumber+"&randUser="+ap_assignRandom+"\"/></div><div id=\"appPromoHide\" class=\"appPromoHide\" style=\"opacity:1; height:100%; width:100%; z-index:11;margin-top:0px; position:absolute;\"></div>");
				if(getIosVersion())
					$( "#main" ).before("<div id=\"appPromo\" class =\"app_posr app_txtc\" style =\"background-color:#721108;\"><img id= \"appPromoImg\" src=\"IMG_URL/images/mobilejs/wap_promotion3.jpg\" border=\"0\"/><div class=\"app_posa app_pos1\"><div class=\"napp_pad1\"><div class=\"app_fnt38 nfamily2 app_txtl ncolr1 app_txtl\" style=\"font-size:25px\">What our users say</div><div class=\"app_txtl app_fntb ncolr2 napp_pt1 nfamily2\">\"Amazing app. Very handy and lightning fast access to all the profiles with great picture clarity and neat fonts. Truly satisfying….\" </div><div class=\"clearfix napp_pt1\"><div class=\"pull-left\"> <div class=\"napp_rat5\"></div>                          </div><div class=\"pull-right app_fntb ncolr2 app_txtr\">Rahulkumar<br/><span class=\"app_fnta\">16th June 2014</span></div></div>  <div class=\"napp_pt20 ncolr2 app_fntb\">Get the best experience with the</br><div class =\"app_f20\"> Jeevansathi Apple App</div></div>  <div class=\"app_pt30\"><div class=\"app_btn app_f40\"><a href=\"/static/appredirect?type=iosLayer\" style=\"color: #fff; text-decoration:none\">Download for Free</a></div><div class=\"napp_pt_abc app_clr1 app_f16\" onclick=\"showPromo(0);\">Skip to mobile site</div></div>    </div></div><img style=\"width:0px;height:0px;\" src=\"/static/trackinterstitial?rand="+ap_randNumber+"&randUser="+ap_assignRandom+"\"/></div><div id=\"appPromoHide\" class=\"appPromoHide\" style=\"opacity:1; height:100%; width:100%; z-index:11;margin-top:0px; position:absolute;\"></div>");
			}
			if($("#mainContent").length){

				if(showMatchOfTheDay!=1)
				{
					$("#mainContent").addClass("ham_b100");
					var showAppClass = 'ham_b20';
				}
				else
				{
					var showAppClass = 'ham_b20_n';
				}
				

				
				perspective=1;
				//isLoaderSearch=1;
				if(getAndroidVersion())
				{
					$("#mainContent").before('<div id=\'appPromo\' class=\''+showAppClass+' ham_minu20  newocbbg1 fullwid\'>    	<div class=\'padAppPromo clearfix\'>        	<div onclick=\"showPromo(4);\" class=\"fl pt20\">            	<div class=\"ocbnewimg ocbclose\"></div>            </div>        	<div class=\"fl padl5\">            	<div class=\"ocbnewimg logoocb\"></div>            </div>            <div class=\"fr pt10\">            	<div class=\"newocbbg2 ocbbr1 ocbp1\">                	<a href=\"/static/appredirect?type=androidLayer\" class=\"white fontmed f13\">Install</a>                </div>            </div>             <div class=\"fr pt13 padr10\">            	<div class=\"f14 fontmed\">Jeevansathi App | 3 MB </div>                <div class=\"ocbnewimg ocbstar\" style =\"float:right\"></div>            </div>        </div>    </div>');
					AppPromoHgt=$("#appPromo").height();
				}
				if(getIosVersion())
				{
					$("#mainContent").before("    <div id=\"appPromo\" class=\"ham_b20 ham_minu20  newocbbg1 fullwid\">    	<div class=\"padAppPromo clearfix\">        	<div onclick=\"showPromo(4);\" class=\"fl pt20\">            	<div class=\"ocbnewimg ocbclose\"></div>            </div>        	<div class=\"fl padl5\">            	<div class=\"ocbnewimg logoocb\"></div>            </div>            <div class=\"fr pt10\">            	<div class=\"newocbbg2 ocbbr1 ocbp1\">                	<a href=\"/static/appredirect?type=iosLayer\"  class=\"white fontmed f13\">Install</a>                </div>            </div>             <div class=\"fr pt20 padr10\">            	<div class=\"f14 fontmed\">Jeevansathi App</div>                </div>            </div>        </div>    </div>");
					AppPromoHgt=$("#appPromo").height();
				}
			if($("#outerDivAppPromo")){
				$("#outerDivAppPromo").height($(window).height());
				//setTimeout(function(){$("#appPromo").css("display","block");},1100);
			}
		}

        $("#appPromo").addClass("transitionApp");
       setTimeout(function(){
		   if(perspective){
				showPromo(3);
			}
			else
				showPromo(1);
			},1200);

        if($("#appPromo").length)
        {
          $(".appPromoHide").bind("click",function(){
			if(perspective)
				showPromo(4);
			else{
				showPromo(0);
				$('.appPromoHide').remove();
				setTimeout(function(){$("#header").css('top','0px');},100);
			}
          });
        }
        ;
			$(document).bind('touchmove', function(e) {e.preventDefault();});
      }
    }
    if($("#main").length && showOldSiteMessage){	 
			var topX=50;
			if($("#header").css("position")=="relative")
			   topX=0;
		   showOldSiteMessage=0;
			if(!getCookieData("oldbrowser")){
				showOldSiteMessage=1;
				var mes=ReturnBrowMes();
				if(mes)
				{
					var oldBrowserInfo='<section style="background: none repeat scroll 0 0 #5C5F62;color: #fff;padding: 10px 0;font-size:15px;position:relative;top:'+topX+'px;cursor:pointer;"><div class="pgwrapper">'+ReturnBrowMes()+'</div></section>';
					if(mes.indexOf("Chrome")!=-1)
							oldBrowserInfo="<a href='/static/appredirect?type=androidMobFooter' style='text-decoration:none'>"+oldBrowserInfo+"</a>";

					$("#header").after(oldBrowserInfo);
					writeCookie("oldbrowser",1,1);
				}
			}
		}
  if(!showOldSiteMessage){ 
    $.ajax({
    type: "POST",
    url: "/api/v3/membership/membershipDetails",
    data : {getMembershipMessage:1}
    }).done(function(msg){
      if(msg.membership_message == null){
        if($("#main").length && getAndroidVersion() && AndroidPromotion){
          if($("#appPromoMyProfile").length > 0)
          {
            if(OperaMiniFlag){
              $('#appPromoMyProfile').css("display","block");
              $('#appPromoHideProfile').removeClass("nl_close");
            }
            else{
              $('#appPromoMyProfile').slideDown(1000);
              $("#appPromoHideProfile").bind("click",function(){
                $('#appPromoMyProfile').slideUp(1000);
                $("#header").css('top','0px');
              });
            }
          }
        }
		
      } else {
        if((document.location.href).indexOf("register")!=-1){
          $("<a href='/membership/jspc' style='text-decoration: none;'><section id='membership_band' style='background: none repeat scroll 0 0 #42688f;color: #fff;padding: 10px 0;font-size:15px;position:relative;top:0px;cursor:pointer;'><div class='pgwrapper'>"+msg.membership_message.top+" "+msg.membership_message.bottom+"</div></section></div>").insertAfter('.b7nHUd');
        } else {
          $("<a href='/membership/jspc' style='text-decoration: none;'><section id='membership_band' style='background: none repeat scroll 0 0 #42688f;color: #fff;padding: 10px 0;font-size:15px;position:relative;top:50px;cursor:pointer;'><div class='pgwrapper'>"+msg.membership_message.top+" "+msg.membership_message.bottom+"</div></section></div>").insertAfter('.b7nHUd');
        }
      }
    });
}
});

function writeCookie (key, value, hours) {
  var date = new Date();

    // Get unix milliseconds at current time plus number of hours
    date.setTime(date.getTime()+(hours*60*60*1000));

    window.document.cookie = key + "=" + value + "; expires=" + date.toGMTString() + "; path=/";

    return value;
  }

  function getCookieData( name ) {
    var pairs = document.cookie.split("; "),
    count = pairs.length, parts;
    while ( count-- ) {
      parts = pairs[count].split("=");
      if ( parts[0] === name )
        return parts[1];
    }
    return false;
  }
  function showPromo(abc)
  {

   if(abc==1)
    document.getElementById("appPromo").style.height=AppPromoHgt+"px";
  else if(abc==0){
    document.getElementById("appPromo").style.height="0px";
    $(document).unbind('touchmove');
  }
  else if(abc==3)
 {
	 $('input').each(function(){
	  $(this).trigger('blur');
	  //each input event one by one... will be blured
	})
	  document.getElementById("appPromo").style.height=AppPromoHgt+"px";
	 
		$("#appPromo").removeClass("ham_minu20");
		if(showMatchOfTheDay!=1)
		{
			$("#mainContent").addClass("ham_plus20");
		}
		
		startTouchEvents(100);
		setTimeout(function(){$(document).unbind('touchmove');},2000);
 }
 else if(abc==4)
 {
	
		$("#appPromo").addClass("ham_minu20");
		$("#mainContent").removeClass("ham_plus20");
		setTimeout(function(){$("#appPromo").remove();},2000);
		$(document).unbind('touchmove');
		startTouchEvents(100);
		setTimeout(function(){$("#mainContent").removeClass("ham_b20");},2000);
 }
}
//only for old site 
$( document ).ready(function() {
	if(!perspective)
	{
		$.fn.isOnScreen = function(){

		  var win = $(window);

		  var viewport = {
			top : win.scrollTop(),
			left : win.scrollLeft()
		  };
		  viewport.right = viewport.left + win.width();
		  viewport.bottom = viewport.top + win.height();

		  var bounds = this.offset();
		  bounds.right = bounds.left + this.outerWidth();
		  bounds.bottom = bounds.top + this.outerHeight();

		  return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));

		};

		$(window).on('scroll', function(){
			if($('#appPromo'))
			{
				var $btn = $('#appPromo');
				if($btn.length !=0 && ($(window).scrollTop() > ($btn.offset().top+$btn.height())))
				{
					if($btn.isOnScreen() == false)
					{
						$btn.css("display","none");
						var scrollVal = $(window).scrollTop();
						window.scrollBy(0,-1*scrollVal);
						$(window).unbind("scroll");
						$('.appPromoHide').remove();
						$('#appPromo').remove();
					}
				}
			}
		});
	}
});
function getAndroidVersion(ua) {
  var ua = ua || navigator.userAgent;
  var android=ua.indexOf("Android");
  var match = ua.match(/Android\s([0-9\.]*)/);
  var mobile=ua.indexOf("Mobile");
  var operaMini=ua.indexOf("Opera Mini");
  if(android==-1)
   return false;
 else if(operaMini!=-1){
   OperaMiniFlag=1;
   return true;
 }
 else if(match==null)
 {
   return true;
 }
 else if(typeof(parseFloat(match[1]))=='number')
 {
   var androidVersion=match[1].substring(0,3);
   if(androidVersion>2.3)
    return true;
  else if(androidVersion==2.3 && match[1].charAt(4)>0)
    return true;
  else
    return false;
}
else
 return true;
		
  };

function ReturnBrowMes()
{
 if( navigator.userAgent.match(/Android/i))
	return "To access the all new full feature site of Jeevansathi, download the app or view in the latest version of Chrome browser";
 if(navigator.userAgent.match(/iPhone/i))
	return "To access the all new full feature site of Jeevansathi, view in the latest version of Safari browser";
 if(navigator.userAgent.match(/Windows Phone/i))
	return "To access the all new full feature site of Jeevansathi, view in the latest version of Chrome browser";
return "To access the all new full feature site of Jeevansathi, view in the latest version of Chrome browser";
}
if (window.location.protocol == "https:")
	    window.location.href = "http:" + window.location.href.substring(window.location.protocol.length);

function getIosVersion(ua) {
	//return false;
	var ua = ua || navigator.userAgent;
	var match= ua.match(/(iPhone);/i);
	//console.log(match);
	var OsVersion=ua.match(/OS\s[0-9.]*/i);
	//console.log(OsVersion);
	if(match==null)
		return false;
	else if(OsVersion==null)
	{
		return false
	}
	else if(OsVersion[0].substring(3,5)>=7)
		return true;
	else
		return false;
	
}
