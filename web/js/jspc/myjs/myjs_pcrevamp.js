//commonVars

var t1=null;
var profileCompletionCount=pc_temp1=limit=pc_temp2=0;
var memTimer,memTimerTime,memTimerExtraDays=0;
         
var timeI=""; var timeE=""; var timeD="";
var MyjsRequestCounter=0;

/*
*COMPONENT CLASS
*
*/

var urlArray = {"JUSTJOINED":"/api/v1/search/perform?searchBasedParam=justJoinedMatches&justJoinedMatches=1&myjs=1&caching=1","DESIREDPARTNERMATCHES":"/api/v1/search/perform?partnermatches=1&myjs=1","DAILYMATCHES":"/api/v2/inbox/perform?infoTypeId=7&pageNo=1&myjs=1&caching=1","VISITORS":"/api/v2/inbox/perform?infoTypeId=5&pageNo=1&myjs=1&caching=1","SHORTLIST":"/api/v2/inbox/perform?infoTypeId=8&pageNo=1&myjs=1&caching=1",'INTERESTRECEIVED':"/api/v2/inbox/perform?infoTypeId=1&pageNo=1&myjs=1","MESSAGES":"/api/v2/inbox/perform?infoTypeId=4&pageNo=1&myjs=1","ACCEPTANCE":"/api/v2/inbox/perform?infoTypeId=2&pageNo=1&myjs=1	","PHOTOREQUEST":"/api/v2/inbox/perform?infoTypeId=9&pageNo=1&myjs=1","COUNTS":"/api/v2/common/engagementcount",
"VERIFIEDMATCHES":"/api/v1/search/perform?verifiedMatches=1&myjs=1&caching=1"};

var maxCountArray = {"JUSTJOINED":20,"DESIREDPARTNERMATCHES":20,"DAILYMATCHES":20,"VISITORS":5,"SHORTLIST":5,'INTERESTRECEIVED':20,"MESSAGES":20,"ACCEPTANCE":20,"PHOTOREQUEST":5,"COUNTS":5,"VERIFIEDMATCHES":20};

var noResultMessagesArray={
	"JUSTJOINED":"People matching your desired partner profile who have joined in last one week will appear here","DESIREDPARTNERMATCHES":"We are finding the matches who recently joined us. It might take a while","DAILYMATCHES":"We are finding the best recommendations for you. It may take a while.","VISITORS":"People who visited your profile will appear here","SHORTLIST":"People you shortlist will appear here",'INTERESTRECEIVED':20,"MESSAGES":20,"ACCEPTANCE":20,"PHOTOREQUEST":"People who have requested your photo will appear here.","COUNTS":5,"VERIFIEDMATCHES":"People matching your desired partner profile and are <a href='/static/agentinfo' class='fontreg colr5'>verified by visit</a> will appear here"
};

var listingUrlArray ={"JUSTJOINED":"/search/perform?justJoinedMatches=1","DESIREDPARTNERMATCHES":"/search/partnermatches","DAILYMATCHES":"/search/matchalerts","VISITORS":"/profile/contacts_made_received.php?page=visitors&filter=R","SHORTLIST":"/profile/contacts_made_received.php?page=favorite&filter=M","INTERESTRECEIVED":"/inbox/1/1","ACCEPTANCE":"/inbox/2/1","MESSAGES":"/inbox/4/1","PHOTOREQUEST":"/profile/contacts_made_received.php?&page=photo&filter=R",
"VERIFIEDMATCHES":"/search/verifiedMatches"};


var postActionsUrlArray ={"INITIATE":"/api/v2/contacts/postEOI","ACCEPT":"/api/v2/contacts/postAccept","DECLINE":"/api/v2/contacts/postNotInterested","WRITE_MESSAGE":"/api/v2/contacts/postWriteMessage","VIEWCONTACT":"/api/v2/contacts/contactDetails"};

var component = function() {
  this.data="ABC";
  if (this.constructor === component) {
    throw new Error("Can't instantiate abstract class!");
  }
  //  var data;
    // component initialization...
  };
/**
@abstract
*/
component.prototype.pre = function() {
  if(this.name=="DAILYMATCHES")
   var containerBarObj = new dailyMatchesBar('dailyMatchesTab');
 else if(this.name=="JUSTJOINED")
   var containerBarObj = new JustJoinBar('dailyMatchesTab');
 else if(this.name=="VISITORS")
   var containerBarObj = new recentProfileVisitorsBar('dailyMatchesTab');
 else if(this.name=="SHORTLIST")
   var containerBarObj = new shortListProfileVisitorsBar('dailyMatchesTab');
 else if(this.name=="DESIREDPARTNERMATCHES")
   var containerBarObj = new desiredPartnerMatchesBar('dailyMatchesTab');
 else if(this.name=="PHOTOREQUEST")
   var containerBarObj = new photoRequestBar('photoRequestTab');
 else if(this.name=="ACCEPTANCE")
   var containerBarObj = new AcceptanceBar('justJoinedTab');
 else if(this.name=="MESSAGES")
   var containerBarObj =new MessageBar('justJoinedTab');
 else if(this.name=="INTERESTRECEIVED")
  var containerBarObj =new interestReceivedBar();
 else if(this.name=="VERIFIEDMATCHES")
  var containerBarObj =new verifiedMatchesBar();
this.containerHtml=containerBarObj.getContainerHtml();
this.viewAllInnerHtml=containerBarObj.getViewAllInnerHtml();
this.emptyInnerHtml=containerBarObj.getEmptyInnerHtml();
this.containerHtml=this.containerHtml.replace(/\{\{div_id\}\}/g,this.containerName);
this.containerHtml=this.containerHtml.replace(/\{\{HEADING\}\}/g,this.heading);
this.containerHtml=this.containerHtml.replace(/\{\{p_id\}\}/g,this.headingId);
this.containerHtml=this.containerHtml.replace(/\{\{list_id\}\}/g,this.list);
this.containerHtml=this.containerHtml.replace(/\{\{LISTING_LINK\}\}/g,listingUrlArray[this.name]);
this.containerHtml=this.containerHtml.replace(/\{\{type\}\}/g,this.list);
this.maxCount=maxCountArray[this.name];

this.innerHtml=containerBarObj.getInnerHtml();
}
component.prototype.request = function() {
        //ele = this
	if(this.name=='JUSTJOINED' || this.name=='VERIFIEDMATCHES')
	{
        	var myLurl =  getUrlForHeaderCaching(urlArray[this.name]);
	}
	else
	{
	        var myLurl =  urlArray[this.name];
	}
         $.myObj.ajax({
          type: "GET",
          dataType: "json",
	  cache: true,
          url: myLurl,
          context: this,
          success: function(response,data) {
				data.data = response;
				data.post();
				MyjsRequestCounter++;
          },
          error: function(response,data){
				  data.error=1;
				  data.noResultCase();
				  MyjsRequestCounter++;
		  },
          complete: function(data,settings){
            if( typeof(settings.context) != "undefined" && settings.context !== null)
            {
              context = settings.context;
              if(typeof(context.isEngagementBar)  != "undefined" && context.isEngagementBar == 1)
              {
                NProgress.set(1.0);
              }
            }
            if((showFTU && MyjsRequestCounter>=1 )|| (!showFTU && MyjsRequestCounter>=5))
            {
				jsLoadFlag = 1;
				timeE = new Date().getTime();
				timeD = (timeE - timeI)/3600;
				jsb9init_fourth(timeD,true,2,'http://track.99acres.com/images/zero.gif','AJAXMYJSPAGEURL');
			}
          },
          beforeSend : function(data){
            if( typeof(data) != "undefined" && data !== null)
            {
              if(typeof(data.isEngagementBar)  != "undefined" && data.isEngagementBar == 1)
              {
                if(data.divid)
                  NProgress.configure({parent: '#'+data.divid});
                else
                  NProgress.configure({ parent: '#engagementContainerTop'});
                NProgress.start();
              }
            }
			if(timeI=="")
			{
				jsb9onUnloadTracking();
				jsb9init_first();
				timeI = new Date().getTime();
			}
          }
        }); 
       }
       component.prototype.post = function() {
        throw new Error("Abstract method!");
    }
    component.prototype.noResultCase = function() {
        throw new Error("Abstract method!");
    }
$( document ).ajaxComplete(function( event,request, settings ) {
    if( typeof(settings.context) != "undefined" && settings.context !== null)
  {
    context = settings.context;
    if(typeof(context.isEngagementBar)  != "undefined" && context.isEngagementBar == 1)
    {
      NProgress.set(1.0);
    }
  }
  
});
$( document ).ajaxSend(function( event,request, settings ) {
   if( typeof(settings.context) != "undefined" && settings.context !== null)
  {
    context = settings.context;
    if(typeof(context.isEngagementBar)  != "undefined" && context.isEngagementBar == 1)
    {
    if(settings.context.divid)
      NProgress.configure({parent: '#'+settings.context.divid});
    else
      NProgress.configure({ parent: '#engagementContainerTop'});
    NProgress.start();
  }
  }
});


      /******container******/
      var container = function(){
        this.getContainerHtml = function(){
         return this.ContainerHtml;
       }
       this.getInnerHtml = function(){
         return this.innerHtml;
       }
       this.setInnerHtml = function(){
         return this.innerHtml;
       }
       this.getViewAllInnerHtml = function(){
        return this.viewAllInnerHtml;
      }
      this.getEmptyInnerHtml = function(){
        return this.emptyInnerHtml;
      }
      this.getFTUHtml = function(){
        return this.FTUHtml;
      }
    };


    var dailyMatchesBar = function(name) {
      this.ContainerHtml = $("#largeContainer").html();
      this.innerHtml= $("#faceCard").html();
      this.viewAllInnerHtml=$("#viewAllCard").html();
      this.emptyInnerHtml=$("#noFaceCard").html();
    };
    dailyMatchesBar.prototype = new container();

    var JustJoinBar = function(name) {
      this.ContainerHtml = $("#largeContainer").html();
      this.innerHtml= $("#faceCard").html();
      this.viewAllInnerHtml=$("#viewAllCard").html();
      this.emptyInnerHtml=$("#noFaceCard").html();
    };
    JustJoinBar.prototype = new container();
    
    var verifiedMatchesBar = function(name) {
      this.ContainerHtml = $("#largeContainer").html();
      this.innerHtml= $("#faceCard").html();
      this.viewAllInnerHtml=$("#viewAllCard").html();
      this.emptyInnerHtml=$("#noFaceCard").html();
    };
    verifiedMatchesBar.prototype = new container();



    var recentProfileVisitorsBar = function(name) {
      this.ContainerHtml = $("#smallContainer").html();
      this.innerHtml= $("#smallCard1").html();
      this.viewAllInnerHtml=$("#smallCard2").html();
      this.emptyInnerHtml=$("#noSmallCard").html();
    };
    recentProfileVisitorsBar.prototype = new container();

    var shortListProfileVisitorsBar = function(name) {
      this.ContainerHtml = $("#smallContainer").html();
      this.innerHtml= $("#smallCard1").html();
      this.viewAllInnerHtml=$("#smallCard2").html();
      this.emptyInnerHtml=$("#noSmallCard").html();
    };
    shortListProfileVisitorsBar.prototype = new container();

    var desiredPartnerMatchesBar = function(name) {
      this.ContainerHtml = $("#largeContainer").html();
      this.innerHtml= $("#faceCard").html();
      this.emptyInnerHtml=$("#noFaceCard").html();
      this.viewAllInnerHtml=$("#viewAllCard").html();
    };
    desiredPartnerMatchesBar.prototype = new container();



    //DailyMatches
    var dailyMatches = function() {
      this.name = "DAILYMATCHES";
      this.containerName = this.name+"_Container";
      this.heading = "Match Alerts";
      this.headingId = this.name+"_head";
      this.list = this.name+"_List";
      this.isEngagementBar=0;
      this.error=0;
      component.apply(this, arguments);
    };
    dailyMatches.prototype = Object.create(component.prototype);
    dailyMatches.prototype.constructor = dailyMatches;

    
    dailyMatches.prototype.post = function() {
		if(this.data.no_of_results>0)
		{
			generateFaceCard(this);
		}
		else
			this.noResultCase();
	}
	dailyMatches.prototype.noResultCase = function() {
		bellCountStatus++;
		//createTotalBellCounts(newEngagementArray["DAILY_MATCHES_NEW"]);
		noResultFaceCard(this);		
	}

  

    //JUST JOINED MATCHES
    var justJoinedMatches = function() {
      this.name = "JUSTJOINED";
      this.containerName = this.name+"_Container";
      this.heading = "Just Joined Matches";
      this.headingId = this.name+"_head";
      this.list = this.name+"_List";
      this.error=0;
      component.apply(this, arguments);
    };
    justJoinedMatches.prototype = Object.create(component.prototype);
    justJoinedMatches.prototype.constructor = justJoinedMatches;

    justJoinedMatches.prototype.post = function() {
		if(this.data.no_of_results>0)
		{
			generateFaceCard(this);
		}
		else
			this.noResultCase();
	}
	justJoinedMatches.prototype.noResultCase = function() {
		bellCountStatus++;
		//createTotalBellCounts(newEngagementArray["NEW_MATCHES"]);
		noResultFaceCard(this);		
				
	}


	    //VERIFIED MATCHES
    var verifiedMatches = function() {
      this.name = "VERIFIEDMATCHES";
      this.containerName = this.name+"_Container";
      this.heading = "Matches verified by Visit";
      this.headingId = this.name+"_head";
      this.list = this.name+"_List";
      this.error=0;
      component.apply(this, arguments);
    };
    verifiedMatches.prototype = Object.create(component.prototype);
    verifiedMatches.prototype.constructor = verifiedMatches;

    verifiedMatches.prototype.post = function() {
		if(this.data.no_of_results>0)
		{
			generateFaceCard(this);
		}
		else
			this.noResultCase();
	}
	verifiedMatches.prototype.noResultCase = function() {
		noResultFaceCard(this);		
	}


    //RECENT PROFILE VISITORS
    var recentProfileVisitor = function() {
      this.name = "VISITORS";
      this.containerName = this.name+"_Container";
      this.heading = "Recent Profile Visitors";
      this.headingId = this.name+"_head";
      this.list = this.name+"_List";
      this.error=0;
      component.apply(this, arguments);
      
    };
    recentProfileVisitor.prototype = Object.create(component.prototype);
    recentProfileVisitor.prototype.constructor = recentProfileVisitor;


    recentProfileVisitor.prototype.post = function() {
		if(this.data.total>0)
		{
			generateShortCards(this);
		}
		else
			this.noResultCase();
      
}
	recentProfileVisitor.prototype.noResultCase = function() {
		noShortCards(this);
	}
    //SHORTLIST PROFILE VISITORS
    var shortlistProfiles = function() {
      this.name = "SHORTLIST";
      this.containerName = this.name+"_Container";
      this.heading = "Shortlisted Profiles";
      this.headingId = this.name+"_head";
      this.list = this.name+"_List";
      component.apply(this, arguments);

    };

    shortlistProfiles.prototype = Object.create(component.prototype);
    shortlistProfiles.prototype.constructor = shortlistProfiles;


    shortlistProfiles.prototype.post = function() {
      if(this.data.total>0)
		{
			generateShortCards(this);
		}
		else
			this.noResultCase();     
}
	
	shortlistProfiles.prototype.noResultCase = function() {
		noShortCards(this);
}

  //DESRIED PARTNER MATCHES
  var desiredPartnerMatches = function() {
    this.name = "DESIREDPARTNERMATCHES";
    this.containerName = this.name+"_Container";
    this.heading = "Here are a few matches for you";
    this.headingId = this.name+"_head";
    this.list = this.name+"_List";
    this.error=0;
    component.apply(this, arguments);
  };
  desiredPartnerMatches.prototype = Object.create(component.prototype);
  desiredPartnerMatches.prototype.constructor = desiredPartnerMatches;

  desiredPartnerMatches.prototype.post = function() {
	if(this.data.no_of_results>0)
	{
		generateFaceCard(this);
	}
	else
		this.noResultCase();
  }
  desiredPartnerMatches.prototype.noResultCase = function() {
	  noResultFaceCard(this);	  
	}

  var engagementCounts = function(){
    this.name = "COUNTS";
    component.apply(this,arguments);
  };
  engagementCounts.prototype = Object.create(component.prototype);
  engagementCounts.prototype.constructor = engagementCounts;

  engagementCounts.prototype.pre =function(){

  }
  engagementCounts.prototype.post =function(){
    $("#totalMessagesReceived").html(this.data.MESSAGE);
    $("#totalAcceptsReceived").html(this.data.ACC_ME);
    $("#totalRequestsReceived").html(this.data.PHOTO_REQUEST);
    $("#totalInterestReceived").html(this.data.AWAITING_RESPONSE);

    if(this.data.AWAITING_RESPONSE_NEW!='0'){
     $("#interetReceivedCount").html(this.data.AWAITING_RESPONSE_NEW);
     $("#interetReceivedCount").removeClass("disp-none");
     $("#interetReceivedCount").addClass("disp-cell bounceIn animated");
   }
   else {
    $("#totalInterestReceived").removeClass("disp-none");
   }
   if(this.data.ACC_ME_NEW!='0'){
     $("#allAcceptanceCount").html(this.data.ACC_ME_NEW);
     $("#allAcceptanceCount").removeClass("disp-none");
     $("#allAcceptanceCount").addClass("disp-cell bounceIn animated");
   }
   else
    $("#totalAcceptsReceived").removeClass("disp-none");
   
   if(this.data.MESSAGE_NEW!='0'){
     $("#messagesCountNew").html(this.data.MESSAGE_NEW);
     $("#messagesCountNew").removeClass("disp-none");
     $("#messagesCountNew").addClass("disp-cell bounceIn animated");
   }
   else{
    $("#totalMessagesReceived").removeClass("disp-none");
   }
   if(this.data.PHOTO_REQUEST_NEW!='0'){
     $("#requestCountNew").html(this.data.PHOTO_REQUEST_NEW);
     $("#requestCountNew").removeClass("disp-none");
     $("#requestCountNew").addClass("disp-cell bounceIn animated");
   }
   else{
    $("#totalRequestsReceived").removeClass("disp-none");
   }
   bellCountStatus++;
   createTotalBellCounts(parseInt(this.data.PHOTO_REQUEST_NEW) +this.data.MESSAGE_NEW+this.data.ACC_ME_NEW+this.data.AWAITING_RESPONSE_NEW + this.data.FILTERED_NEW);
   setBellCountHTML(this.data);
 }
engagementCounts.prototype.noResultCase = function() {
}

function CriticalActionLayer(){
var CALayerShow=$("#CALayerShow").val();
if(typeof(CALayerShow)=='undefined' ||  !CALayerShow) return;
if(CALayerShow!='0')
  {

    var layer=$("#CALayerShow").val();
    var url="/static/criticalActionLayerDisplay";
 var ajaxData={'layerId':layer};
 var ajaxConfig={'data':ajaxData,'url':url,'dataType':'html'};



ajaxConfig.success=function(response){
$('body').prepend(response);
  showLayerCommon('criticalAction-layer'); 
  $('.js-overlay').unbind('click');
}

$.myObj.ajax(ajaxConfig);

}


}


function showConsentLayer(){
var showConsentMsg=$("#showConsentMsgId").val();
if(typeof(showConsentMsg)=='undefined' ||  !showConsentMsg) return;
if(showConsentMsg == 'Y')
  {

var url="/phone/ConsentMessage";
var ajaxConfig={'url':url,'dataType':'html'};



ajaxConfig.success=function(response){
$('body').prepend(response);
  $('.js-overlay').eq(0).fadeIn(200,"linear",function(){$('#DNC-Consent-layer').fadeIn(300,"linear",function(){})}); 
 
closeConsentLayer=function () {
  $('.js-overlay').fadeOut(200,"linear",function(){$('#DNC-Consent-layer').fadeOut(200,"linear",function(){})}); 
  $('.js-overlay').unbind('click');


}
  $('#consentLayerOKButton').bind('click',closeConsentLayer);


}

$.myObj.ajax(ajaxConfig);

}


}


function showTimerForMemberShipPlan() {
if(!membershipPlanExpiry) return;
var expiryDate=new Date(membershipPlanExpiry);

expiryDate.setDate(expiryDate.getDate()+1);
expiryDate.setHours(0);
expiryDate.setMinutes(0);
var currentTime=new Date(); 
if(expiryDate<currentTime) return;
var timeDiffInSeconds=(expiryDate-currentTime)/1000;
if (timeDiffInSeconds>48*60*60) return;  // check for the timer if the time diff is less than 48 hrs
$("#memExpiryDiv").show();
var temp=timeDiffInSeconds;
var timerSeconds=temp%60;
temp=Math.floor(temp/60);
var timerMinutes=temp%60;
temp=Math.floor(temp/60);
var timerHrs=temp;
memTimerExtraDays=Math.floor(timerHrs/24);
memTimerTime=new Date();
memTimerTime.setHours(timerHrs);
memTimerTime.setMinutes(timerMinutes);
memTimerTime.setSeconds(timerSeconds);

memTimer=setInterval('updateMemTimer()',1000);



}


function formatTime(i) {
    if (i < 10 && i>=0) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
}


function updateMemTimer(){
  var h = memTimerTime.getHours();
  var s = memTimerTime.getSeconds();
  var m = memTimerTime.getMinutes();
  if (!m && !s && !h) {
    if(!memTimerExtraDays) clearInterval(memTimer);
    else memTimerExtraDays--;
  }
  
    memTimerTime.setSeconds(s-1);
    h=h+memTimerExtraDays*24;
    
    m = formatTime(m);
    s = formatTime(s);
    h = formatTime(h);
  $("#memExpiryHrs").html(h);
  $("#memExpiryMnts").html(m);
  $("#memExpirySec").html(s);
    }

function getRightPosForHelpScreen(div){
var offset=$("#"+div).offset();
if (!offset){$("."+classForHelpScreen).fadeOut(10,function(){$('.js-overlay').fadeOut(10);});return;}
  return $(document).width()-$("#"+div).offset().left-$("#"+div).width()-15;
}


function showHelpScreenFunction() 
{
if (showFTU) classForHelpScreen='FTUHelpScreen'; else classForHelpScreen='MyJsHelpScreen';
var TotalLW = $('.'+classForHelpScreen).length;
var divRight=getRightPosForHelpScreen($('.'+classForHelpScreen+0).attr('origDiv'));
$('.js-overlay').fadeIn(200,function() {
$('.'+classForHelpScreen+'0').css( 'right',divRight+'px').fadeIn(200);
});  


  
  $(".LWone").click(function() {    
    var getIDlw = $(this).attr('id');
    var b;
    b = getIDlw.split("-");
    b[1]=parseInt(b[1]);
    var incWin = b[1]+1;
    if(incWin<TotalLW)
    {
      if(incWin==TotalLW-1)
        $("#LW-"+incWin).html('Close');
    else
        $("#LW-"+incWin).html('Next');
      var origDivRight=getRightPosForHelpScreen($('.'+classForHelpScreen+incWin).attr('origDiv'));
      $('.'+classForHelpScreen+b[1]).fadeOut(200,function(){ $('.'+classForHelpScreen+incWin).css(
        'right',origDivRight+'px').fadeIn(200)});
    }
    else
    {
      $('.'+classForHelpScreen+b[1]).fadeOut(200,function(){$('.js-overlay').fadeOut(200)});
      
    }
  });




}

function videoLinkRequest()
{
  $.ajax(
                {                 
                        url: '/myjs/Videolink?',
                        data: "profileid="+profileid,
                        //timeout: 5000,
                        success: function(response) 
                        {
                          $( "#videoLinkDivID" ).hide();

                        }
                        });



}


$(document).ready(function() {
if($("#showConsentMsgId").val()=='Y')
		showConsentLayer();
else {
      var CALayerShow=$("#CALayerShow").val();
      if(!(typeof(CALayerShow)=='undefined' ||  !CALayerShow) && CALayerShow!='0') 
  	     	CriticalActionLayer();
      else if (showHelpScreen=='Y') {
              showHelpScreenFunction();
                          }
   }

   $('#videoCloseID').bind('click', function(e)
  {
    console.log("jhsgcbjk");
    videoLinkRequest(profileid);
  });

      showTimerForMemberShipPlan();
	profile_completion(iPCS);
	if(showFTU){
		var desiredPartnersObj = new desiredPartnerMatches();
		desiredPartnersObj.pre();
		desiredPartnersObj.request();
		$("#justJoinedCountBar").removeClass("disp-none");
		$("#dailyMatchesCountBar").removeClass("disp-none");
		bellCountStatus++;
		setBellCountHTML(newEngagementArray);
		bellCountStatus++;
		createTotalBellCounts(newEngagementArray["DAILY_MATCHES_NEW"]);
		bellCountStatus++;
		createTotalBellCounts(newEngagementArray["NEW_MATCHES"]);
	}
	else
	{
		var recentvisitors = new recentProfileVisitor();
		recentvisitors.pre();
		recentvisitors.request();
		var shortlist = new shortlistProfiles();
		shortlist.pre();
		shortlist.request();
		var count = new engagementCounts();
		count.pre();
		count.request();
		var justJoined = new justJoinedMatches();
		justJoined.pre();
		justJoined.request();
		var dailyMatchObj =new dailyMatches();
		dailyMatchObj.pre();
		dailyMatchObj.request();
		var verifedMatchObj =new verifiedMatches();
		verifedMatchObj.pre();
		verifedMatchObj.request();
		
		var interests = new interestReceived();
		var mess = new messages();
		var accept = new acceptance();		
		var photoReq = new photoRequest();		
		
		
   


		var tabs = [].slice.call( document.querySelectorAll( 'ul.tabs > li' ) );
		currentTab = -1;
		currentPanelEngagement = -1;
		var called = [];
		$("#requestEngagementHead").bind(clickEventType,function(){
      $("#totalRequestsReceived").removeClass('disp-none');
			$("#requestCountNew").addClass("disp-none").removeClass("disp-cell");
			engagementClickHanding(photoReq,3);
		});
	$('#MsgEngagementHead').bind("click",function() 
	{
 $("#totalMessagesReceived").removeClass('disp-none');
		$("#messagesCountNew").addClass("disp-none").removeClass("disp-cell");
    engagementClickHanding(mess,2);
	  });
  
    $('#acceptanceEngagementHead').bind("click",function() 
  {
    $("#totalAcceptsReceived").removeClass('disp-none');
	  $("#allAcceptanceCount").addClass("disp-none").removeClass("disp-cell");
    engagementClickHanding(accept,1);
    });
	 $('#interestEngagementHead').bind("click",function() 
	{
    $("#totalInterestReceived").removeClass('disp-none');
		$("#interetReceivedCount").addClass("disp-none").removeClass("disp-cell");
		engagementClickHanding(interests,0);
	  });
   var engagementClickHanding = function (ele, currentTabId) {
    if(currentTabId=='0') var height='360px';
      else var height='350px';      
    if(currentTab == -1)
    {
      $("#engagementContainerTop").addClass("myjs-p6");
      $('.myjs-p6').animate({height:height}, 500)
      //$(".myjs-bg2").addClass("myjs-h4");
      tabs.forEach( function( tab ) {
        tab.className = "";
      });
    }
    if(currentTab>=0)
    {  
      tabs[currentTab].className = '';
      $('.myjs-p6').css('height',height);
      $('#'+currentPanelEngagement).addClass("disp-none");
    }
    currentTab = currentTabId;
    currentPanelEngagement = ele.containerName;
    currentPanelArray[currentPanelEngagement]=1;
    tabs[currentTab].className = 'active';
    if(called[currentTab] ==1)
      $('#'+currentPanelEngagement).removeClass("disp-none");
    else
    {
      ele.pre();
      ele.request();
    }
    called[currentTab] = 1;

}
}
});
