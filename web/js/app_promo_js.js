// A $( document ).ready() block.
var bProcessScroll = true;
var ap_assignRandom=new Date().getTime();
var ap_randNumber=new Date().getTime();
var OperaMiniFlag=0;
var perspective=0;
var showOldSiteMessage=1;
var messagesAppPromoTime = "messagesAppPromoTime";
var hoursLimit = 6;
var divForMessageAppPromo='';
var messageListingAppPromo=0;
//var isLoaderSearch="";
var AppPromoHgt=$(window).height();
if(!getCookieData("apRandomUser"))
	writeCookie("apRandomUser",ap_assignRandom,24);
else
 ap_assignRandom=getCookieData("apRandomUser");
if(typeof(AndroidPromotion)=="undefined"){
	var AndroidPromotion=0;
}
if(typeof(AppLoggedInUser)=="undefined"){
	var AppLoggedInUser=0;
}
if(typeof(webView) ==='undefined'){
	var webView ="";	
}
$( document ).ready(function() {
	//
      if(typeof(messageListAppPromo) != "undefined" && getAndroidVersion()){
        messageListingAppPromo = showAppPromoForMessageListingPage();
        if(messageListingAppPromo)
            divForMessageAppPromo= "<div class=\"padAppPromo clearfix\"> <div class = \"f14 innerTextBorder txtc pb10 pt5\">Chat real time with online matches, Download App</div></div>";
        
      }  
      if((getAndroidVersion() || getIosVersion()) && AndroidPromotion && AppLoggedInUser && (typeof webView ==='undefined' || webView =="")){
      if((!getCookieData("appPromo") && (typeof appPromo === 'undefined')) || messageListingAppPromo)
      { 
		   writeCookie("appPromo","jeevansathi",3);
			if($("#mainContent").length){
				

				if((typeof(pageMyJs) != 'undefined' && pageMyJs==1))
				{
					var showAppClass = 'ham_b20_n ham_minu20';
				}
                                else if(messageListingAppPromo){
                                        var showAppClass = 'ham_b20_n borderMessAppPromo ham_minu_mess20';
                                }
				else
				{
					$("#mainContent").addClass("ham_b100");
					var showAppClass = 'ham_b20 ham_minu20';
				}
				
				perspective=1;
				//isLoaderSearch=1;
				if(getAndroidVersion())
				{
					$("#mainContent").before('<div id=\'appPromo\' class=\''+showAppClass+'  newocbbg1 fullwid\'>   '+divForMessageAppPromo+' 	<div class=\'padAppPromo clearfix\'>        	<div onclick=\"showPromo(4);\" class=\"fl pt20\">            	<div class=\"ocbnewimg ocbclose\"></div>            </div>        	<div class=\"fl padl5\">            	<div class=\"ocbnewimg logoocb\"></div>            </div>            <div class=\"fr pt10\">            	<div class=\"newocbbg2 ocbbr1 ocbp1\">                	<a href=\"/static/appredirect?type=androidLayer\" class=\"white fontmed f13\">Install</a>                </div>            </div>             <div class=\"fr pt13 padr10\">            	<div class=\"f14 fontmed\">Jeevansathi App | 3 MB </div>                <div class=\"ocbnewimg ocbstar\" style =\"float:right\"></div>            </div>        </div>    </div>');
					AppPromoHgt=$("#appPromo").height();
				}
				if(getIosVersion())
				{  
					$("#mainContent").before('<div id=\'appPromo\' class=\''+showAppClass+'  newocbbg1 fullwid\'>    	<div class=\"padAppPromo clearfix\">        	<div onclick=\"showPromo(4);\" class=\"fl pt20\">            	<div class=\"ocbnewimg ocbclose\"></div>            </div>        	<div class=\"fl padl5\">            	<div class=\"ocbnewimg logoocb\"></div>            </div>            <div class=\"fr pt10\">            	<div class=\"newocbbg2 ocbbr1 ocbp1\">                	<a href=\"/static/appredirect?type=iosLayer\"  class=\"white fontmed f13\">Install</a>                </div>            </div>             <div class=\"fr pt20 padr10\">            	<div class=\"f14 fontmed\">Jeevansathi App</div>                </div>            </div>        </div>    </div>');
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
   else
   {
   	 if($("#mainContent").length  && typeof(webView) ==='undefined' || webView ==""){	 
			var topX=0;
			if($("#mainContent").css("position")=="relative")
			   topX=0;
			if(showOldMobileSiteInfo() && !getCookieData('oldbrowser')){
				showOldSiteMessage=1;
				var mes=ReturnBrowMes();
				if(mes)
				{
					var oldBrowserInfo='<section style="background: none repeat scroll 0 0 #fff;color: #565252;padding: 10px 0;font-size:15px;position:relative;top:'+topX+'px;cursor:pointer;"><div class="pgwrapper txtc">'+ReturnBrowMes()+'</div></section>';
					if(mes.indexOf("Chrome")!=-1)
							oldBrowserInfo="<a href='/static/appredirect?type=androidMobFooter' style='text-decoration:none'>"+oldBrowserInfo+"</a>";

					$("#mainContent").before(oldBrowserInfo);
					writeCookie("oldbrowser",1,1);
				}
			}
		}
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
	 
                if(messageListingAppPromo)
                    $("#appPromo").removeClass("ham_minu_mess20");
                else
                    $("#appPromo").removeClass("ham_minu20");
                if(typeof pageMyJs == 'undefined' && !messageListingAppPromo)
                {
                    $("#mainContent").addClass("ham_plus20");
		}
		startTouchEvents(100);
		setTimeout(function(){$(document).unbind('touchmove');},2000);
 }
 else if(abc==4)
 {
                if(messageListingAppPromo){
                    $("#appPromo").addClass("ham_minu_mess20");
                    dateToStore = new Date();
                    localStorage.setItem(messagesAppPromoTime,dateToStore);
                }
                else
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
	return "We don't officially support your current browser, so you may experience some difficulties. For the best experience, please download Chrome browser or the latest app from Playstore";
 if(navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i))
	return "We don't officially support your current browser, so you may experience some difficulties. For the best experience, please download Safari browser or the latest app from Appstore";
if(navigator.userAgent.match(/Windows Phone/i))
	return "We don't officially support your current browser, so you may experience some difficulties. For the best experience, please download UC Browser ";
return "We don't officially support your current browser, so you may experience some difficulties. For the best experience, please download either Chrome ,safari or UC browser";
}

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

function showAppPromoForMessageListingPage(){
    var currentDate = new Date();
    var storedDate = localStorage.getItem(messagesAppPromoTime);
    if ( storedDate !== null)
    {
            diff = new Date(currentDate-new Date(storedDate));
            if ( Math.floor(diff/(1000*60*60)) < hoursLimit)    
                    return 0;
            else
            {
                    localStorage.removeItem(messagesAppPromoTime);
            }
    }
    return 1;
}

function showOldMobileSiteInfo()
{
	if((getIosVersion() || getAndroidVersion()) && !getCookieData("appPromo") && AppLoggedInUser)
		return false;
	var ua = ua || navigator.userAgent;
	var android=ua.indexOf("Android");
	var match = ua.match(/Android\s([0-9\.]*)/);
	var mobile=ua.indexOf("Mobile");
	var operaMini=ua.indexOf("Opera Mini");
	var windows=ua.indexOf("Windows Phone");
	if(android!=-1 && match!=null && typeof(parseFloat(match[1]))=='number')
 	{
	   	var androidVersion=match[1].substring(0,3);
	   	if(androidVersion>=5.0)
			return false;
 	 	else
			return true;
	}
	if(navigator.userAgent.indexOf('Opera')!=-1 && navigator.userAgent.indexOf('Opera')!=null) {
		return true;
 	}
	var matchIos= ua.match(/(iPhone);/i);
	//console.log(match);
	var OsVersion=ua.match(/OS\s[0-9.]*/i);
	//console.log(OsVersion);
	if(OsVersion!=null && matchIos !=null && OsVersion[0].substring(3,5)>="7")
		return false;
	
 	if(navigator.userAgent.match(/Windows Phone/i)){
 		var matchWindows = navigator.userAgent.match(/Windows Phone\s([0-9\.]*)/);
 		if(matchWindows!=null && typeof(parseFloat(matchWindows[1]))=='number')
 		{
 			var windowVersion=matchWindows[1].substring(0,3);
   		 	if(windowVersion>9)
   		 		return false;
   		 	else
   		 		return true;	
   		}
   		else
   			return false;
	}
	
		return false;



}
