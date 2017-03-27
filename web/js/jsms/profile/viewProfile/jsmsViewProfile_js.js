
var tab, tabAbout,tabContent, tabFamily, tabDpp,tabHeader,backBtn;
var picContent,profilePic;
var contentAbout,contentFamily,contentDpp ;
var mainContent,profileContent,comHistoryOverlay;
var lastScrollPos=-1;
var errorContent;
var comHistoryActive,comHistoryClose;
var noProfileIcon,loadingOverlay;
var myMsgHtml = '<div class="vpro_padl"> <div class="fontlig f14 white txtr padr15">MSG_TEXT<span class="dispbl f12 color1  pt5">SENT_TEXT</span></div></div>'
var partnerMsgHtml = '<div class="vpro_padr"> <div class="fontlig f14 white txtl padl15">MSG_TEXT<span class="dispbl f12 color1  pt5">SENT_TEXT</span></div></div>';
var sendInterestBtn = '#buttons1';
var closeMyPreview;
var onScrollIntervalId;
var viewBackLocation = null;
var commLayerPageIndex=1,commHistoryLoading=0,commHScrollHeight=0;
initGuiElements = function()
{
	//Tab Elements
	tab			= '#tab';
	tabAbout 	= '#tabAbout';
	tabFamily   = '#tabFamily';
	tabDpp   	= '#tabDpp';
	tabContent	= '#tabContent';
	tabHeader	= '#tabHeader';
	backBtn 	= '#backBtn';
	mainContent = '#mainContent';
	
	profilePic	= '#profilePic';
	picContent	= '#picContent';
	
	profileContent 		= '#profileContent';
	
	comHistoryOverlay	= '#comHistoryOverlay';
	comHistoryActive 	= '.vpro_comHisIcon';
	comHistoryClose 	= '#js-comCloseBtn';
	comMessage 			= '#commHistoryScroller';
	
	errorContent		= '#errorContent';
	//Content Elements
	contentAbout 	= '#aboutContent';
	contentFamily   = '#familyContent';
	contentDpp   	= '#dppContent';

	noProfileIcon	= '#noProfileIcon';
	loadingOverlay	= '#loadingOverlay';	
	
    closeMyPreview = '#closeMyPreview';
	
}

initEvents = function()
{
	$(comHistoryActive).bind('click',displayComHistory);
	$(comHistoryClose).bind('click',popBrowserStack);

	
	// executes when HTML-Document is loaded and DOM is ready
	$(tabAbout).bind("click",function(){
        renderContent(1);
    });
	$(tabFamily).bind("click",function(){
        renderContent(2);
    });
	$(tabDpp).bind("click",function(){
        renderContent(3);
    });
	
    //Profile Pic Event
    if(typeof picCount!="undefined" && parseInt(picCount))
        $(profilePic).bind("click",openAlbumView);
	
    //Windows Events
	if($(errorContent).length == 0)
	{
        bindOnScrollEvents();
		$(window).bind('resize',onResize);
	}
}
bindOnScrollEvents = function()
{
    UnBindOnScrollEvents();
    $(mainContent).bind("touch",Onscroll);
    $(mainContent).bind("touchstart",Onscroll);
    $(mainContent).bind("touchmove",Onscroll);
    $(mainContent).bind("touchend",Onscroll);
    onScrollIntervalId = setInterval(Onscroll,10);
}
UnBindOnScrollEvents = function()
{
    $(mainContent).unbind("touchstart");
    $(mainContent).unbind("touchend");
    $(mainContent).unbind("touchmove");
    $(mainContent).unbind("touch");
    window.clearInterval(onScrollIntervalId);
}
hideContent = function()
{
	$(contentAbout).addClass('vpro_dn');
	$(contentFamily).addClass('vpro_dn');
	$(contentDpp).addClass('vpro_dn');
	
	$(tabAbout).addClass('opa70');
	$(tabFamily).addClass('opa70');
	$(tabDpp).addClass('opa70');
	
	$(tabAbout).removeClass('vpro_selectTab');
	$(tabFamily).removeClass('vpro_selectTab');
	$(tabDpp).removeClass('vpro_selectTab');
}

renderContent = function(val)
{
	hideContent();
	if(val === 1)
	{
		$(contentAbout).removeClass('vpro_dn');
		$(tabAbout).removeClass('opa70');
        $(tabAbout).addClass('vpro_selectTab');	
	}
	if(val === 2)
	{
		$(contentFamily).removeClass('vpro_dn');
		$(tabFamily).removeClass('opa70');
        $(tabFamily).addClass('vpro_selectTab');
	}
	if(val === 3)
	{
		$(contentDpp).removeClass('vpro_dn');
        $(tabDpp).removeClass('opa70');
		$(tabDpp).addClass('vpro_selectTab');
	}
}

initCss = function()
{
	//Force Gpu calculation
	$(mainContent).addClass('gpuCalc').addClass('bg4');
	$(mainContent).css('height',$(window).height());
    
	//If Error content exist then initalize errorContent
	if($(errorContent).length)
	{
		var h = $(window).innerHeight() -  $(tabHeader).height();
		$(errorContent).css('height',h);
		if($(noProfileIcon).length) // IF No Profile Icon Exist 
		{
			$(noProfileIcon).css('padding-top',($(window).innerHeight())/3 + 'px')
		}
	}
	
	//Add Height on Pic
	$(picContent).css('height',$(window).innerWidth());
}

enableLoader = function()
{
	$('#loadingOverlay').addClass('loadingOverlay');
	$('#mainContent').bind('click',function(e){preventDefault(e);});
	$('#mainContent').bind('touchmove',function(e){preventDefault(e);});
}

disableLoader = function()
{
	$('#loadingOverlay').removeClass('loadingOverlay');
	$('#mainContent').unbind('click');
	$('#mainContent').unbind('touchmove');
}
var swipeInProgress = false;
function handleSwipe(event, direction, distance, duration, fingerCount){
		if(direction === 'left' || direction === 'right')
		{
			var nextUrl = getNextLink();
			var preUrl = getPreviousLink();
			if(direction === 'left' )
			{
				if(nextUrl && nextUrl.length && !swipeInProgress)
				{
				swipeInProgress = true;
				ShowNextPage(nextLink,0,0);
				}
			}
			if(direction === 'right')
			{
				if(preUrl && preUrl.length && !swipeInProgress)
				{
				swipeInProgress = true;
				ShowNextPage(previousLink,0,1);
				}
			}
	
		}
}

handlePreviousNext = function()
{

//Handle Next And Previous on swipe action
$(mainContent).swipe({swipe:handleSwipe,allowPageScroll:"vertical", threshold:($(window).width()/3)});

}
calcTabPos = function()
{
	if(!($(profilePic).length && $(tab).length))
	{
		setTimeout(function(){disableLoader()},200);
		return;
	}
	var winHeight = window.innerWidth;
	var offsetX = winHeight - $(tab).height();
	$(tab).css('position','absolute');
	$(tab).css('top',offsetX+'px');
}

makeTabSticky = function(bStatus)
{
    var offset = $(tabHeader).height()+$(window).innerWidth()-$(tabContent).outerHeight();
	var topPos =0;

	if(bStatus)
	{
		topPos = $(tabHeader).outerHeight();
	}
	
	if( offset <= $(window).scrollTop() + topPos)
	{
		$(tab).css('top',topPos+'px');
		$(tab).addClass('posFixTop');
		$(tabContent).addClass('bg2WOpa');
	}
	else
	{
		$(tabContent).removeClass('bg2WOpa');
		$(tab).removeClass('posFixTop');
		calcTabPos();
	}
}

makeTopHeaderSticky = function(bStatus)
{
	$(tabHeader).removeClass('posFixTop');
	$(picContent).css('margin-top','0px');
	if(bStatus)
	{
        var iTabHeight = $(tabHeader).height();
		$(tabHeader).addClass('posFixTop');
		$(picContent).css('margin-top',iTabHeight+'px');
	}
}

Onscroll = function()
{
	var bStatus = false;
	if(lastScrollPos > $(window).scrollTop())
	{
		//Reverse Scroll or Scroll Upward
		bStatus = true;
	}
	else if(lastScrollPos < $(window).scrollTop())
	{
		//Forward Scroll or Scroll Downward
		bStatus = false;
	}
	else
	{
		return;
	}
	makeTopHeaderSticky(bStatus);
	makeTabSticky(bStatus);
	lastScrollPos = $(window).scrollTop();
	$(mainContent).trigger('click');
};

displayComHistory = function()
{
        $(comMessage).unbind('scroll');
        $("#commHistoryScroller #commHistoryLoader").show();
        commLayerPageIndex=1;
        commHistoryLoading=0;
	$(profileContent).css('display','none');
	$(comHistoryOverlay).css('display','block');
	$(comHistoryOverlay).css('overflow','hidden');
    $(comHistoryOverlay).removeClass('vpro_dn');
	$(mainContent).addClass('posrel');
	$(comHistoryOverlay).css('height',$(window).height()+'px');
	$(sendInterestBtn).addClass('vpro_dn');
	var com_msgHgt = $(window).innerHeight() - $('#comm_header').outerHeight();
	$(comMessage).css({'height':com_msgHgt,'overflow-y':'auto','overflow-x':'hidden'});
    enableLoader();
    getCommHistory().success(function(data,textStatus,jqXHR){
       commHistoryJson = data;
       bakeCommHistory();
        disableLoader();
    }).error(function(jqXHR,textStatus,errorThrown){
        //Something went wrong use old commHistoryJson
        bakeCommHistory('show error message');
        CommonErrorHandling();
        disableLoader();
    });
    if(typeof historyStoreObj != 'undefined'){
        historyStoreObj.push(onComHistoryBrowserBack,"#comhistory");
    }
}

hideComHistory = function()
{
	$(profileContent).css('display','block');
	$(mainContent).removeClass('posrel');
	$(comHistoryOverlay).css('display','none');
    $(comHistoryOverlay).addClass('vpro_dn');
    $(sendInterestBtn).removeClass('vpro_dn');
    $(comMessage).html($("#commHistoryPreLoad").html());
    
}

bakeCommHistory = function(bShowError)
{
        var commHScrollHeight=0;
	if((!commHistoryJson || typeof commHistoryJson !== "object" ) && typeof bShowError == "undefined")
	{
		return;
	}
    
    if(bShowError)
    {
        message = "<div class='disptbl hgtInherit'><div class='dispcell vertmid white txtc'> Something went wrong. Please try in some time.</div></div>"
        $(comMessage).append(message);
        setTimeout(popBrowserStack,200);
        return;
    }
    
        var commLoader=$("#commHistoryScroller #commHistoryLoader");
        if(commHistoryJson.history)
	{
        commHScrollHeight=$(comMessage)[0].scrollHeight;  
        if(commHistoryJson.nextPage=='false')
        {
            $(comMessage).unbind('scroll');
            commLoader.hide();
            commHistoryFullLoaded = 1; 
        }
        var historyMsg = commHistoryJson.history;
		var msg = '';
		var addBrdr = false;
		var msgType = '';
		$.each(historyMsg,function(key,msgObj){
			var lastMsg = msg;
			if(msgObj.ismine)
			{
				if(msg.length && msgType === 2)
				{
					addBrdr = true;
				}
				msg = myMsgHtml;
				msgType = 1;
			}
			else
			{
				if(msg.length && msgType === 1)
				{
					addBrdr = true;
				}
				msg = partnerMsgHtml;
				msgType = 2;
			}
			msg = msg.replace(/MSG_TEXT/g,msgObj.message);
			msg = msg.replace(/SENT_TEXT/g,msgObj.header + " " + msgObj.time);
			if(addBrdr)
			{
				if(msgType === 1)
					msg+=("<div class='vpro_padr'><div class='brdr4'></div></div>");
				else if(msgType === 2 )	
					msg+=("<div class='vpro_padl'><div class='brdr4'></div></div>");
				addBrdr = false;
                
			}
                        commLoader.after(msg);
		});
        
           //    $(comMessage).append("<div class='hgt35'></div>");
        
	}
	else
	{
		message = "<div class='disptbl hgtInherit'><div class='dispcell vertmid white txtc'> Your interaction with "+ userName + " will appear here.</div></div>"
		$(comMessage).append(message);
	}
        $(comMessage).scrollTop($(comMessage)[0].scrollHeight-commHScrollHeight);
        commHistoryLoading=0;
        
        if(commHistoryJson.nextPage=='false')
        {
            $(comMessage).unbind('scroll');
            commLoader.hide();
            commHistoryFullLoaded = 1; 
        }
        else
         $(comMessage).unbind('scroll').scroll(function()
        {
            
            if($(this).scrollTop()==0)
            {
            if(commHistoryLoading)return;    
            commHistoryLoading=1;    
            getCommHistory().success(function(data,textStatus,jqXHR){
            commHistoryJson = data;
            bakeCommHistory();
            disableLoader();
    }).error(function(jqXHR,textStatus,errorThrown){
        //Something went wrong use old commHistoryJson
        bakeCommHistory('show error message');
        CommonErrorHandling();
        disableLoader();
    });}});

}

onResize = function()
{
	if($(errorContent).length ===0)
	{
		$(picContent).css('height',window.innerWidth);
		Onscroll();
	}
}

imageLoadComplete = function(bStatus)
{
    if(bStatus)
    {
        $(profilePic).css('min-height',$(window).innerWidth());
    }
    
    setTimeout(function(){$(window).scrollTop(0);},10); 
	calcTabPos();
	setTimeout(function(){disableLoader(); Onscroll();},200);
}

handleBackButton = function()
{
    
    if(typeof getProfileBackLink != "function")
        return;
	var backBtnHtml = getProfileBackLink();
    
	var backLocation = "";
	if(backBtnHtml && backBtnHtml.length && backBtnHtml.indexOf('href') !=-1)		
	{
		var dummy = document.createElement('div');
		dummy.innerHTML = backBtnHtml;
		backBtnAnchor = dummy.children[0];
		if(backBtnAnchor.href.indexOf('search')!=-1)
			backLocation = backBtnAnchor.href + '&page=idd' + getProfileOffset();
		else
			backLocation = backBtnAnchor.href ;
	}
	
	if(backLocation.length)
	{
		$(backBtn).bind('click',function(){
//            if(typeof ShowNextPage != 'undefined')
//                ShowNextPage(backLocation,1);
//            else
                window.location.href = backLocation;
		})
        viewBackLocation = backLocation;
	}
	else if(backBtnHtml && backBtnHtml.length && backBtnHtml.indexOf('customBack')!=-1)
	{
        if($(closeMyPreview).length)
        {
            $(backBtn).addClass('vpro_dn');
            $(closeMyPreview).bind('click',function(){
                if(typeof ShowNextPage != 'undefined')
                {
                    ShowNextPage('/profile/viewprofile.php?ownview=1',0);
                }
                else
                {
                    window.location.href = '/profile/viewprofile.php?ownview=1';
                }
            });
            viewBackLocation = '/profile/viewprofile.php?ownview=1';
        } 
        commonBackBtnBind();    
	}
	else 
	{
        commonBackBtnBind();
	}
}
commonBackBtnBind = function()
{
    $(backBtn).bind('click',function(){
        if(typeof ShowNextPage != 'undefined')
        {
            if(typeof historyStoreObj != "undefined" && historyStoreObj.History.length>0)
            {
                history.back();
            }
            else
            {
                ShowNextPage('/profile/mainmenu.php',0);
            }
        }
        else
        {
            window.location.href = '/profile/mainmenu.php';
        }
    });
}
openAlbumView = function()
{
    window.location.href = "/social/MobilePhotoAlbum?profilechecksum="+getProfileCheckSum()+"&stype="+getStype();
}

onComHistoryBrowserBack = function()
{
	if($("#comHistoryOverlay").hasClass('vpro_dn')==false) {
        hideComHistory();
		return true;
	}
	else 
		return false;

}
$(document).ready(function() 
{
    initPic();
	initGuiElements();
	initContactCenter();
    
    hideContent();
	renderContent(1);
	
    initCss();
	initEvents();
	
    handleBackButton();
    handlePreviousNext();	
    initGunnaScore();
	
	astroCompatibility();    
    setTimeout(function(){lastScrollPos = -2; onResize()},200);
	if($(errorContent).length)
	{
		setTimeout(function(){disableLoader();},300);
	}
    
});
getCommHistory = function()
{
    return $.ajax({
				url : '/contacts/CommunicationHistoryV1?profilechecksum='+getProfileCheckSum()+"&pageNo="+commLayerPageIndex++,
				data : ({dataType:"json"}),
				async:true,
				timeout:30000,
			});
}

getGunnaScore = function()
{
    return $.ajax({
				url : '/api/v1/profile/gunascore?oprofile='+getProfileCheckSum(),
				data : ({dataType:"json"}),
				async:true,
				timeout:30000,
			});
}

initGunnaScore = function()
{
    if($(errorContent).length)
        return ;
    if(typeof isGunnaCallRequires == "function" && isGunnaCallRequires() == "1")
    {
        if(typeof(hideUnimportantFeatureAtPeakLoad) =="undefined" || hideUnimportantFeatureAtPeakLoad < 4){
        getGunnaScore().success(function(data,textStatus,jqXHR){
        //Show Guna Score String
        if(data.responseStatusCode==0 && data.SCORE != 0)
        {
            var col = "green";
            if(parseInt(data.SCORE)<18)
                col = "red";

            var pinHtml = '<div class="fl"><i class="vpro_sprite vpro_pin"></i></div>'
            var gunaHtml = '<div class="fontlig padl5 fl vpro_wordwrap"> Your guna score with ' + szHisHer+ ' is        <span style="color:' +col+ '">' +data.SCORE+ '/36   </span></div>';

            $('#gunaScore').append(pinHtml);
            $('#gunaScore').append(gunaHtml);
            $('#gunaScore').removeClass('vpro_dn');
        }
        else
        {
            CommonErrorHandling(data);
        }
        }).error(function(jqXHR,textStatus,errorThrown){
        //Something went wrong
        });
    }
    }
}
initContactCenter = function()
{
    if(typeof buttonSt != "undefined" && buttonSt != null)
    {
        var button = buttonStructure("1",buttonSt,getProfileCheckSum());
        $( "#buttons1").append(button);
        bindPrimeButtonClick("1");
        bind3DotClick(1,buttonSt);
    }
}
updateHistory = function(){}

initPic = function(){
    if(typeof getProfilePicUrl != "function")
        return ;
    var url = getProfilePicUrl();

    if(url.length==0 || $(errorContent).length==1)
    {
        imageLoadComplete(false);
        return ;
    }
    
    $('.classimg3').attr('src',url);
    $('.classimg3').error(function(){
        imageLoadComplete(false);
    });
    $('.classimg3').load(function(){
        imageLoadComplete(true);
    });
}

getViewProfileBackLocation = function()
{
    return viewBackLocation;
}

astroCompatibility = function()
{
	$(".js-astroCompMem,.js-freeAstroComp").click(function(){		
		$.ajax({
			method: "POST",
			url : "/profile/check_horoscope_compatibility.php?profilechecksum="+otherProfilechecksum+"&sendMail=1&sampleReport=1&username="+username,
			async:true,
			timeout:20000,
			success:function(response){
			}
		});
		if($(this).hasClass('js-astroCompMem')){
			$(".js-buttonAstro").html("Buy Astro Compatibility");
			$(".js-textAstro").html("A sample astro compatibility report has been sent to your Email ID. Buy Astro Compatibility add-on to access these reports for your matches.");
			$(".js-buttonAstro").attr("href","/profile/mem_comparison.php");
			$(".js-astroReportLayer,.js-astroTextButton").removeClass("dispnone");				
		}
		else{
			$(".js-buttonAstro").html("Upgrade Membership");
			$(".js-textAstro").html("A sample astro compatibility report has been sent to your Email ID. Upgrade to a Paid membership and buy Astro Compatibility add-on to access these reports for your matches.");
			$(".js-buttonAstro").attr("href","/profile/mem_comparison.php");
			$(".js-astroReportLayer,.js-astroTextButton").removeClass("dispnone");		
		}
		
	});
	$(".js-astroMem").click(function(){
		$.ajax({
			method: "POST",
			url : "/profile/check_horoscope_compatibility.php?profilechecksum="+otherProfilechecksum+"&sendMail=1",
			async:true,
			timeout:20000,
			success:function(response){
			}
		});
		$(".js-buttonAstro").html("OK");
			$(".js-textAstro").html("Astro compatibility report with this member has been sent to your registered Email ID");
			$(".js-buttonAstro").click(function(){
				$(".js-astroReportLayer,.js-astroTextButton").addClass("dispnone");
			});
			$(".js-astroReportLayer,.js-astroTextButton").removeClass("dispnone");		
	});
}

$(document).on('click',function(event){    closeAstroLayer(event) });

closeAstroLayer = function(event)
{
	var target = $(event.target).first();
	if(target.attr('id') == "astroReportLayer")
	{
		$(".js-astroReportLayer,.js-astroTextButton").addClass("dispnone");		
	}
}
setTimeout(enableLoader,50);

