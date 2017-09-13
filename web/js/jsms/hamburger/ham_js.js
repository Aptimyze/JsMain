var heightPage=$(window).height();
$(".outerdiv").css("height",heightPage);

function setScroll(ele){
	var temp = $('#scrollElem');
	var mainHeight = temp.height();
	var diff =mainHeight - ele.position().top;
	if(diff>=150)return;
	var newScroll = temp.scrollTop() +  150 - diff;
temp.animate({
        scrollTop: newScroll
    }, 500);


}
    $(document).ready(function(){
        var settingsSum = 0,myMatchesSum = 0,contactSum = 0;
        $("#settingsMinor li").each(function(){
            settingsSum += $(this).outerHeight() + 12;
        })
        $("#myMatchesMinor li").each(function(){
            myMatchesSum += $(this).outerHeight() + 12;
        })
        $("#contactsMinor li").each(function(){
            contactSum += $(this).outerHeight() + 12;
        })
        contactSum-=20;
        myMatchesSum-=20;
        settingsSum-=20;
        $("#scrollElem").height((window.innerHeight -$("#bottomTab").height())+"px");
        $("#settingsParent").on("click",function(){
            if($("#settingsParent").hasClass('plusParent')) {
                  $("#settingsMinor").animate({height:'0px','margin-top':'0px'},0); 
            } else {
            	$("#settingsMinor").animate({height:settingsSum+'px','margin-top':'12px'},0);
            	setScroll($("#settingsParent").parent());
//                $("#settingsMinor").height("0px");
            }
            $("#settingsParent").toggleClass("plusParent");
        })
        $("#myMatchesParent").on("click",function(){
            if($("#myMatchesParent").hasClass('plusParent')) {
                 $("#myMatchesMinor").animate({height:'0px','margin-top':'0px'},0); 
            } else {
            	$("#myMatchesMinor").animate({height:myMatchesSum+'px','margin-top':'12px'},0);
            	setScroll($("#myMatchesParent").parent());
//                $("#settingsMinor").height("0px");
            }

            $("#myMatchesParent").toggleClass("plusParent");

        })
        $("#contactsParent").on("click",function(){
        	if($("#contactsParent").hasClass('plusParent')) {
                  $("#contactsMinor").animate({height:'0px','margin-top':'0px'},0); 
            } else {
            	$("#contactsMinor").animate({height:contactSum+'px','margin-top':'12px'},0);
            	setScroll($("#contactsParent").parent());
//                $("#settingsMinor").height("0px");
            }
            $("#contactsParent").toggleClass("plusParent");

        })
    });
var Flag=0;
var scrollEnable=true;



(function() {
	var Hamburger=(function(){
		function Hamburger(element){
			this.optionHeight=100;
			this.ham_htm=$("#hamburger").html();
			this.hamid="#hamburger";
			this.persid="#perspective";
			this.pcontid="#pcontainer";
			var ele=this;

			if (getAndroidVersion()) $("[id^='appDownloadLink']").css('display','inline-block');
			if (getIosVersion()) $("[id^='appleAppDownloadLink']").css('display','inline-block');

			$("#hamView").bind("click",function(ev){
                    
				ele.hideHamburger();return false;});

			$(element).bind("click",function(){	
				$(ele.hamid).removeClass("dn");
				(function(elem)
				{
						elem.ShowHamburger();
				})(ele);
				
				//}
				});
			$(this.hamid).addClass('secondProps');
		
		};
	
		Hamburger.prototype.ShowHamburger=function(){
			enable_touch();
			disable_scrolling();
			$(this.hamid).addClass("hamShow");
			$(this.pcontid).height(window.innerHeight+"px").addClass("scrollhid posrel");
            $("#hamView").addClass("z99 backShow").removeClass("dn"); 

			var ele=this;
			 

			historyStoreObj.push(function(){return HideHamburger(ele)},"#mham");
			

			$('#mainHamDiv').css('height',heightPage - $('.js-loginBtn').height() - 20);


		};
		
		Hamburger.prototype.hideHamburger=function()
		{
			enable_scrolling();
			var ele=this;
				$(ele.pcontid).height("100%").removeClass("scrollhid posrel");
	            $(ele.hamid).removeClass("hamShow");
	            $("#hamView").removeClass("z99 backShow").addClass('dn');
				startTouchEvents(10);
			 if($("#noResultListingDiv").length)
				disable_touch();
		};
	
		this.Hamburger=Hamburger;
	}).call(this);
})();	


function HideHamburger(ele)
{
		if($(ele.hamid).hasClass("hamShow"))
		{
			ele.hideHamburger(1);
			return true;
		}	
		return false;
}

function readCookie(name) {
    var nameEQ = escape(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return unescape(c.substring(nameEQ.length, c.length));
    }
    return null;
}

function createCookie(name, value, days,specificDomain) {
    var expires;
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    } else {
        expires = "";
    }
    if(specificDomain == undefined || specificDomain == ""){
    	document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/";
    }
    else{
    	document.cookie = escape(name) + "=" + escape(value) + expires + ";domain="+specificDomain+";path=/";
    }
}

function translateSite(translateURL){
	newHref = translateURL+"?AUTHCHECKSUM="+readCookie("AUTHCHECKSUM");
	if(translateURL.indexOf('hindi')!=-1){
		createCookie("jeevansathi_hindi_site_new","Y",100,".jeevansathi.com");
	} else {
		createCookie("jeevansathi_hindi_site_new","N",100,".jeevansathi.com");
	}
 	window.location.href = newHref;
}

$("[hamburgermenu]").each(function(){
			(new Hamburger(this));	
		});		
$("#hamTollFree").bind("click",function(ev){
			window.location.href="tel://18004196299";
		});	
(function () {
    var _overlay = document.getElementById('scrollElem');
    var _clientY = null; // remember Y position on touch start

    _overlay.addEventListener('touchstart', function (event) {
        if (event.targetTouches.length === 1) {
            // detect single touch
            _clientY = event.targetTouches[0].clientY;
        }
    }, false);

    _overlay.addEventListener('touchmove', function (event) {
        if (event.targetTouches.length === 1) {
            // detect single touch
            disableRubberBand(event);
        }
    }, false);

    function disableRubberBand(event) {
        var clientY = event.targetTouches[0].clientY - _clientY;

        if (_overlay.scrollTop === 0 && clientY > 0) {//alert('1');
            // element is at the top of its scroll
            event.preventDefault();
        }

        if (isOverlayTotallyScrolled() && clientY < 0) {//alert('2');
            //element is at the top of its scroll
            event.preventDefault();
        }
    }

    function isOverlayTotallyScrolled() {
        // https://developer.mozilla.org/en-US/docs/Web/API/Element/scrollHeight#Problems_and_solutions
        // alert(_overlay.scrollHeight - _overlay.scrollTop);
        // alert(_overlay.clientHeight);
        return _overlay.scrollHeight - _overlay.scrollTop <= _overlay.clientHeight;
    }
}())