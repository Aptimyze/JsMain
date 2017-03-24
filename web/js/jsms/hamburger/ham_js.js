var heightPage=$(window).height();
$(".outerdiv").css("height",heightPage);


/*
var userHtml=$("#userHam").html();
var exploreHtml=$("#exploreHam").html();
$("#userHam").remove();
$("#exploreHam").remove();
$("#mainHamDiv").append(userHtml);
$("#mainHamDiv").append(exploreHtml);
$("#innerExplore").addClass('dn');*/
var Flag=0;
var scrollEnable=true;
/*
function onClickUserEnable()
{
	if(Flag===1)
	{
		var hMenu=$("#HamMenu").height();
		var h1=$("#innerExplore").height();
		$("#userEnable").addClass("dn");
		$("#innerExplore").addClass("hamburgerList100");
		$("#innerExplore").css("transform","translateY(-"+h1+"px)");
		$("#innerUser").removeClass("dn");
		setTimeout(function(){
			var h2=h1-hMenu+13;
			//console.log(h2);
			$("#innerUser").addClass("hamburgerList100");
			$("#innerUser").css("transform","translateY(-"+h2+"px)");
		},1);
		setTimeout(function(){
			$("#innerExplore").addClass("dn");
			$("#innerUser").css("transform","translateY(0px)");
			$("#innerUser").removeClass("hamburgerList100");
			$("#innerExplore").remove();
			$("#mainHamDiv").append(exploreHtml);
			$("#innerExplore").addClass("dn");
			Flag=0;
			startTouchEvents(10);
			$("#innerUser").removeClass("dn");
			BindNextPage();
		},750);
		scrollEnable=true;
	}
	
	
}
function onClickExploreEnable()
{
	if(Flag===0)
	{
		var hMenu=$("#HamMenu").height();
		var h1=$("#innerUser").height();
		//console.log(h1);
		$("#exploreEnable").addClass("dn");
		$("#innerUser").addClass("hamburgerList100");
		$("#innerUser").css("transform","translateY(-"+h1+"px)");
		$("#innerExplore").removeClass("dn");
		setTimeout(function(){
			var h2=h1-hMenu+13;
			//console.log(h2);
			$("#innerExplore").addClass("hamburgerList100");
			$("#innerExplore").css("transform","translateY(-"+h2+"px)");
		},1);
		setTimeout(function(){
			$("#innerUser").addClass("dn");
			$("#innerExplore").css("transform","translateY(0px)");
			$("#innerExplore").removeClass("hamburgerList100");
			$("#innerUser").remove();
			$("#mainHamDiv").append(userHtml);
			$("#innerUser").addClass("dn");
			Flag=1;
			startTouchEvents(10);
			$("#innerExplore").removeClass("dn");
			BindNextPage();
		},750);
		scrollEnable=true;
	}
		
}
*/



(function() {
	var Hamburger=(function(){
		function Hamburger(element){
			this.optionHeight=100;
			
			this.ham_htm=$("#hamburger").html();
			this.tapid=1;
			this.hamid="#hamburger";
			this.hamoverid="#hamoverlay";
			this.persid="#perspective";
			this.pcontid="#pcontainer";
			
			this.formation=$(element).attr("dmove")=="right"?"r":"l";
			this.whenHide=$(element).attr("dhide");
			this.inputtype=$(element).attr("dselect");
			
			this.callBack=eval($(element).attr("dcallback"));
			this.dependant=$(element).attr("dependant");
			this.indexPos=$(element).attr("dindexpos");
			this.selectedValue=-1;
			
			var ele=this;
			
			
			$(element).bind("click",function(){	
			//$(element).click({longTapThreshold:longTapThreshold,longTap:function(){
				
				ele.type=$(element).attr('dshow').toLowerCase();
				
				ele.tapid=1;
				stopTouchEvents(1);
				$(ele.hamid).removeClass("dn");
				(function(elem)
				{
					setTimeout(function(){
						elem.ShowHamburger();
					},10);
				})(ele);
				
				//}
				});
		
		
		};
	
		Hamburger.prototype.ShowHamburger=function(){
			if (getAndroidVersion()) $("[id^='appDownloadLink']").css('display','block');
			if (getIosVersion()) $("[id^='appleAppDownloadLink']").css('display','block');
			$("#newHamlist").css("height",heightPage-20);
			$(this.pcontid).addClass("pcontainer");
			$(this.hamid).addClass(this.formation+"ham");
			$(this.persid).addClass("showpers");
			
                        $(this.pcontid).addClass("hamb");
			if(!Modernizr.csstransforms3d || ISBrowser("UC") || ISBrowser("AndroidNative"))
			{
			   $(this.pcontid).addClass("twodview"+this.formation);
			   setTimeout(function(){
				    $("#2dView").removeClass("dn");
				    $("#2dView").css("left",'').css("right",0).children().first().attr("src","IMG_URL/images/jsms/commonImg/2d-slider.png");
			   
			   },animationtimer);
			} 
			else 
                            $(this.pcontid).addClass("hamburgerTop"+this.formation);
			$(this.pcontid).prepend("<div class='wrapper' id='wrapper'></div>");
			var ele=this;
			$("#perspective").addClass("ham_img1");
			if(appPromoPerspective)
				$(this.pcontid).addClass("headerimg1");
			 
			$("#wrapper,#2dView,#HamMenu ,#mainHamDiv,#hamProfile,#loggedOutHamFoot,#outerHamDiv").unbind("click");
			
			$("#HamMenu ,#mainHamDiv,#hamProfile,#loggedOutHamFoot,#ExploreLoggedOut").bind("click",function(ev){
				
				stopPropagation(ev);
				});
			
			$("#wrapper,#2dView,#outerHamDiv").bind("click",function(ev){
                    
				ele.hideHamburger();return false;});

			

			historyStoreObj.push(function(){return HideHamburger(ele)},"#mham");
			
			$("#mainHamDiv").css("overflow","auto");

			$('#mainHamDiv').css('height',heightPage - $('.js-loginBtn').height() - 20);

			//startTouchEvents(10);
            setTimeout(function(){
				stopTouchEvents(1);
				setTimeout(function(){startTouchEvents(10);},100);
           },700);
           setTimeout(function(){
				 $("#hamProfile").removeClass("dn");
			 },300);
			 enable_touch();
           
		};
		
		Hamburger.prototype.hideHamburger=function()
		{
			$("#2dView").addClass("dn");
			$(this.pcontid).removeClass("hamburgerTop"+this.formation).removeClass("tcenter"+this.formation).removeClass("twodview"+this.formation);
			//$(this.pcontid).removeClass("hamburgerTop"+this.formation);
			var ele=this;
			$(this.hamid).removeClass(this.formation+"ham");
			setTimeout(function(){
				$(ele.hamid).addClass('dn');
				$(ele.persid).removeClass("showpers");
				$(ele.pcontid).removeClass("hamb");
				$("#wrapper").remove();
				startTouchEvents(10);
				if(DualHamburger)
					$(ele.pcontid).removeClass("pcontainer");
				$("#perspective").removeClass("ham_img1");
				if(appPromoPerspective)
					$(this.pcontid).removeClass("headerimg1");
				},500);
				//stopScrolling();
				//startScrolling();
			 setTimeout(function(){
				 $("#hamProfile").addClass("dn");
			 },200);
			 if($("#noResultListingDiv").length)
				disable_touch();
		};
	
		this.Hamburger=Hamburger;
	}).call(this);
})();	


function HideHamburger(ele)
{
	if($(ele.persid).hasClass("showpers"))
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
	/*if(trackingProfile == "13766629" || trackingProfile == "11238186"){
		alert("before:"+readCookie("jeevansathi_hindi_site_new")+"-"+readCookie("redirected_hindi_new"));
	}*/
	newHref = translateURL+"?AUTHCHECKSUM="+readCookie("AUTHCHECKSUM");
	if(translateURL.indexOf('hindi')!=-1){
		createCookie("jeevansathi_hindi_site_new","Y",100,".jeevansathi.com");
	} else {
		createCookie("jeevansathi_hindi_site_new","N",100,".jeevansathi.com");
	}
	/*if(trackingProfile == "13766629" || trackingProfile == "11238186"){
		alert("after:"+readCookie("jeevansathi_hindi_site_new")+"-"+readCookie("redirected_hindi_new"));
	}*/
 	window.location.href = newHref;
}

$("[hamburgermenu]").each(function(){
			(new Hamburger(this));	
		});		
/*$("#hamburger").swipe({swipe:function(event, direction, distance, duration, fingerCount){
	//console.log("ASD12");
		if(direction === 'up' && scrollEnable)
		{
			scrollEnable=true;
			//stopTouchEvents(1);
			//if(Flag===1)
				//onClickUserEnable();
			//else
				//onClickExploreEnable();
				
			console.log("up");
		}
		if(direction === 'down' && scrollEnable)
		{
			scrollEnable=true;
		}
	}});
*/
$("#hamTollFree").bind("click",function(ev){
			window.location.href="tel://18004196299";
		});	
