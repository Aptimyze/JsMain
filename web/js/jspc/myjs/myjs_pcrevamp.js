//commonVars

var t1=null;
var profileCompletionCount=pc_temp1=limit=pc_temp2=0;
var memTimer,memTimerTime,memTimerExtraDays=0,calTimerTime,calTimer;
         
var timeI=""; var timeE=""; var timeD="";
var MyjsRequestCounter=0;
// for last search
var PageSrc = 0;
/*
*COMPONENT CLASS
*
*/

var urlArray = {"JUSTJOINED":"/api/v1/search/perform?searchBasedParam=justJoinedMatches&justJoinedMatches=1&myjs=1&caching=1","DESIREDPARTNERMATCHES":"/api/v1/search/perform?partnermatches=1&myjs=1&caching=1","DAILYMATCHES":"/api/v1/search/perform?searchBasedParam=matchalerts&caching=1&myjs=1","VISITORS":"/api/v2/inbox/perform?infoTypeId=5&pageNo=1&matchedOrAll=A&myjs=1&caching=1","SHORTLIST":"/api/v2/inbox/perform?infoTypeId=8&pageNo=1&myjs=1&caching=1",'INTERESTRECEIVED':"/api/v2/inbox/perform?infoTypeId=1&pageNo=1&myjs=1","MESSAGES":"/api/v2/inbox/perform?infoTypeId=4&pageNo=1&myjs=1","ACCEPTANCE":"/api/v2/inbox/perform?infoTypeId=2&pageNo=1&myjs=1 ","PHOTOREQUEST":"/api/v2/inbox/perform?infoTypeId=9&pageNo=1&myjs=1","COUNTS":"/api/v2/common/engagementcount","VERIFIEDMATCHES":"/api/v1/search/perform?verifiedMatches=1&myjs=1&caching=1","FILTEREDINTEREST":"/api/v2/inbox/perform?infoTypeId=12&caching=1&myjs=1","EXPIRINGINTEREST":"/api/v2/inbox/perform?infoTypeId=23&pageNo=1&myjs=1&caching=1","LASTSEARCH":"/api/v1/search/perform?lastSearchResults=1&results_orAnd_cluster=onlyResults&myjs=1&caching=1&lastsearch=1", "MATCHOFTHEDAY":"/api/v2/inbox/perform?infoTypeId=24&pageNo=1&myjs=1&caching=1"};

var maxCountArray = {"JUSTJOINED":20,"DESIREDPARTNERMATCHES":20,"DAILYMATCHES":20,"VISITORS":5,"SHORTLIST":5,'INTERESTRECEIVED':20,'FILTEREDINTEREST':20,"MESSAGES":20,"ACCEPTANCE":20,"PHOTOREQUEST":5,"COUNTS":5,"VERIFIEDMATCHES":20, "LASTSEARCH":20, 'EXPIRINGINTEREST':20, "MATCHOFTHEDAY" : 7};

var noResultMessagesArray={
	"JUSTJOINED":"People matching your desired partner profile who have joined in last one week will appear here","DESIREDPARTNERMATCHES":"We are finding the matches who recently joined us. It might take a while","DAILYMATCHES":"We are finding the best recommendations for you. It may take a while.","VISITORS":"People who visited your profile will appear here","SHORTLIST":"People you shortlist will appear here",'INTERESTRECEIVED':20,"MESSAGES":20,"ACCEPTANCE":20,"PHOTOREQUEST":"People who have requested your photo will appear here.","COUNTS":5,"VERIFIEDMATCHES":"People matching your desired partner profile and are <a href='/static/agentinfo' class='fontreg colr5'>verified by visit</a> will appear here", "LASTSEARCH":"No result message here"
};

var listingUrlArray ={"JUSTJOINED":"/search/perform?justJoinedMatches=1","DESIREDPARTNERMATCHES":"/search/partnermatches","DAILYMATCHES":"/search/matchalerts","VISITORS":"/search/visitors?matchedOrAll=A","SHORTLIST":"/search/shortlisted","INTERESTRECEIVED":"/inbox/1/1","ACCEPTANCE":"/inbox/2/1","MESSAGES":"/inbox/4/1","PHOTOREQUEST":"/inbox/9/1",
"VERIFIEDMATCHES":"/search/verifiedMatches","FILTEREDINTEREST":"/inbox/12/1","LASTSEARCH":"/search/lastSearchResults","EXPIRINGINTEREST":"/inbox/23/1"};


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
  var seeAllTrackingLink = "";
  if(this.name=="DAILYMATCHES"){
   var containerBarObj = new dailyMatchesBar('dailyMatchesTab');
   seeAllTrackingLink = "trackJsEventGA('My JS JSPC', 'Match Alert Section - See All',loggedInJspcGender,'')";
  }
 else if(this.name=="JUSTJOINED"){
   var containerBarObj = new JustJoinBar('dailyMatchesTab');
   seeAllTrackingLink = "trackJsEventGA('My JS JSPC', 'Just Joined Section - See All',loggedInJspcGender,'')";
 }
 else if(this.name=="VISITORS")
   var containerBarObj = new recentProfileVisitorsBar('dailyMatchesTab');
 else if(this.name=="SHORTLIST")
   var containerBarObj = new shortListProfileVisitorsBar('dailyMatchesTab');
 else if(this.name=="DESIREDPARTNERMATCHES"){
   var containerBarObj = new desiredPartnerMatchesBar('dailyMatchesTab');
   seeAllTrackingLink ="trackJsEventGA('My JS JSPC', 'DPP Matches/Last Search Section - See All',loggedInJspcGender,'')";
 }
 else if(this.name=="PHOTOREQUEST")
   var containerBarObj = new photoRequestBar('photoRequestTab');
 else if(this.name=="ACCEPTANCE")
   var containerBarObj = new AcceptanceBar('justJoinedTab');
 else if(this.name=="MESSAGES")
   var containerBarObj =new MessageBar('justJoinedTab');
 else if(this.name=="INTERESTRECEIVED")
  var containerBarObj =new interestReceivedBar();
else if(this.name=="FILTEREDINTEREST")
{
 var containerBarObj =new filteredInterestBar();
}
else if(this.name=="EXPIRINGINTEREST")
{
  var containerBarObj =new expiringInterestBar();  
}
else if(this.name=="VERIFIEDMATCHES"){
  var containerBarObj =new verifiedMatchesBar();
  seeAllTrackingLink ="trackJsEventGA('My JS JSPC', 'Matches Verified by Visit Section - See All',loggedInJspcGender,'')";
}
else if(this.name=="LASTSEARCH"){
  var containerBarObj = new LastSearchBar('dailyMatchesTab');
  seeAllTrackingLink = "trackJsEventGA('My JS JSPC', 'DPP Matches/Last Search Section - See All',loggedInJspcGender,'')";
}
else if(this.name == "MATCHOFTHEDAY")
{
  var containerBarObj = new MatchOfDayBar();
}
this.containerHtml=containerBarObj.getContainerHtml();
this.viewAllInnerHtml=containerBarObj.getViewAllInnerHtml();
this.emptyInnerHtml=containerBarObj.getEmptyInnerHtml();
this.containerHtml=this.containerHtml.replace(/\{\{div_id\}\}/g,this.containerName);
this.containerHtml=this.containerHtml.replace(/\{\{HEADING\}\}/g,this.heading);
this.containerHtml=this.containerHtml.replace(/\{\{p_id\}\}/g,this.headingId);
this.containerHtml=this.containerHtml.replace(/\{\{list_id\}\}/g,this.list);
this.containerHtml=this.containerHtml.replace(/\{\{LISTING_LINK\}\}/g,listingUrlArray[this.name]);
this.containerHtml=this.containerHtml.replace(/\{\{type\}\}/g,this.list);
this.containerHtml=this.containerHtml.replace(/\{\{SEE_ALL_GA_TRACKING\}\}/g,seeAllTrackingLink);
this.containerHtml=this.containerHtml.replace(/\{\{count_results_id\}\}/g,this.countingValId);
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
          data: {'timestamp':(new Date()).getTime()/1000},  
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
				jsb9init_fourth(timeD,true,2,'https://track.99acres.com/images/zero.gif','AJAXMYJSPAGEURL');
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
    
    var LastSearchBar = function(name) {
      this.ContainerHtml = $("#largeContainer").html();
      this.innerHtml= $("#faceCard").html();
      this.viewAllInnerHtml=$("#viewAllCard").html();
      this.emptyInnerHtml=$("#noFaceCard").html();
    };
    LastSearchBar.prototype = new container();

    var MatchOfDayBar = function(name) {
      this.ContainerHtml = $("#prfDay").html();
      this.innerHtml = $("#matchOfDaySection").html();
    };
    MatchOfDayBar.prototype = new container();

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
      this.heading = "Daily Recommendations";
      this.headingId = this.name+"_head";
      this.list = this.name+"_List";
      this.isEngagementBar=0;
      this.error=0;
      this.countingValId = this.name+"_resultCount";
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
      this.countingValId = this.name+"_resultCount";
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
		noResultFaceCard(this);		
				
	}

    // Last Search
    var lastSearchMatches = function() {
      this.name = "LASTSEARCH";
      this.containerName = this.name+"_Container";
      this.heading = "Based on your Last Search";
      this.headingId = this.name+"_head";
      this.list = this.name+"_List";
      this.error=0;
      this.displayed = 0;
      this.countingValId = this.name+"_resultCount";
      component.apply(this, arguments);
    };
    lastSearchMatches.prototype = Object.create(component.prototype);
    lastSearchMatches.prototype.constructor = lastSearchMatches;
    lastSearchMatches.prototype.post = function() {
        if(this.data.no_of_results >= 5)
        {
            generateFaceCard(this);
        }
        else
            this.noResultCase();
    }
    lastSearchMatches.prototype.noResultCase = function() {
        $("#LASTSEARCH").addClass("disp-none");
        PageSrc = 1;
        var desiredPartnersObj = new desiredPartnerMatches();
        desiredPartnersObj.pre();
        desiredPartnersObj.request();
    }

    var matchOfDayMatches = function() {
      this.name = "MATCHOFTHEDAY";
      this.containerName = this.name+"_Container";
      this.heading = "";
      this.headingId = this.name+"_head";
      this.list = this.name+"_List";
      this.error=0;
      this.displayed = 0;
      this.countingValId = this.name+"_resultCount";
      component.apply(this, arguments);
    };
    matchOfDayMatches.prototype = Object.create(component.prototype);
    matchOfDayMatches.prototype.constructor = matchOfDayMatches;
    matchOfDayMatches.prototype.post = function() {
          showMatchOfTheDayCards(this);
    }
    matchOfDayMatches.prototype.noResultCase = function() {
    }

	    //VERIFIED MATCHES
    var verifiedMatches = function() {
      this.name = "VERIFIEDMATCHES";
      this.containerName = this.name+"_Container";
      this.heading = "Verified Matches";
      this.headingId = this.name+"_head";
      this.list = this.name+"_List";
      this.error=0;
      this.displayed = 0;
      this.countingValId = this.name+"_resultCount";
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


    //Profile Visitors
    var recentProfileVisitor = function() {
      this.name = "VISITORS";
      this.containerName = this.name+"_Container";
      this.heading = "Profile Visitors";
      this.headingId = this.name+"_head";
      this.list = this.name+"_List";
      this.error=0;
      this.displayed = 0;
      this.countingValId = this.name+"_resultCount";
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
      this.displayed = 0;
      this.countingValId = this.name+"_resultCount";
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

  //DESIRED PARTNER MATCHES
  var desiredPartnerMatches = function() {
    this.name = "DESIREDPARTNERMATCHES";
    this.containerName = this.name+"_Container";
    if(showFTU)
    this.heading = "Here are a few matches for you";
    else
      this.heading = "Desired Partner Matches";
    this.headingId = this.name+"_head";
    this.list = this.name+"_List";
    this.error=0;
    this.countingValId = this.name+"_resultCount";
    component.apply(this, arguments);
  };
  desiredPartnerMatches.prototype = Object.create(component.prototype);
  desiredPartnerMatches.prototype.constructor = desiredPartnerMatches;

  desiredPartnerMatches.prototype.post = function() {
    // FTU case
    if(!PageSrc && this.data.no_of_results>0)
    {
        generateFaceCard(this);
    }
    // In case if DPP matches are also less than 5, listing will not be shown for non FTU users.
    else if(PageSrc && this.data.no_of_results >= 5)
    {
        generateFaceCard(this);
    }
	else
		this.noResultCase();
  }
  desiredPartnerMatches.prototype.noResultCase = function() {
      if(!PageSrc)
      {
        noResultFaceCard(this);
      }
      else
      {
        // Remove DPP listing for non FTU users
        $("#DESIREDPARTNERMATCHES_Container").remove();
        inView = $('#'+verifedMatchObj.name+':in-viewport').length;
        if(inView != 0 && verifedMatchObj.displayed == 0)
        {
          verifedMatchObj.pre();
          verifedMatchObj.request();
          verifedMatchObj.displayed = 1;
        }
      }
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
    $("#totalInterestReceived").html(this.data.AWAITING_RESPONSE);
    if(showExpiring)
    {
      expiringCount = this.data.INTEREST_EXPIRING;
      $("#totalExpiringInterestReceived").html(this.data.INTEREST_EXPIRING);
      if(this.data.INTEREST_EXPIRING > 0)
      {
        $("#ExpiringAction").removeClass('disp-none');
        $("#ExpiringAction").addClass('dispib');
      }
    }
    else
    {
      $("#totalFilteredInterestReceived").html(this.data.FILTERED);
    }

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
   if(this.data.FILTERED_NEW!='0'){
     $("#filteredInterestCount").html(this.data.FILTERED_NEW);
     $("#filteredInterestCount").removeClass("disp-none");
     $("#filteredInterestCount").addClass("disp-cell bounceIn animated");
   }
   else{
    $("#totalFilteredInterestReceived").removeClass("disp-none");
   }
   if(showExpiring)
   {
    $("#totalExpiringInterestReceived").removeClass("disp-none");
   }
   setBellCountHTML(this.data);
 }
engagementCounts.prototype.noResultCase = function() {
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

function modifyMemMsgForLightningDeal(){
    var flag = false;
    if($('#jspcMemMsg span:first').html() == "FLASH DEAL"){
        $('#jspcMemMsg span:first').addClass('f16').removeClass('f26');
        var txt = $("#memExtraDiv").html();
        $("#memExtraDiv").html('');
        var memText = txt.split(" ");
        $("#memExtraDiv").append("<span class=''></span><span class=''></span>");
        var s1 = '';var s2='';
        for(var i=0;i<3;i++){
            s1+=memText[i]+" ";
        }
        for(var i=3;i<memText.length;i++){
            s2+=memText[i]+" ";
        }
        $("#memExtraDiv span:nth-child(1)").html(s1);
        $("#memExtraDiv span:nth-child(2)").html(s2);
        $("#memExtraDiv span:nth-child(1)").addClass('f30 fontmed');
        flag = true;
        $("#lightningTimer").show();
        showTimerForLightningMemberShipPlan("jspcMyjs");
    }
    return flag;
}


$(document).ready(function() {
if($("#showConsentMsgId").val()=='Y')
		showConsentLayer();
else {
      var CALayerShow=$("#CALayerShow").val();
      if(!(typeof(CALayerShow)=='undefined' ||  !CALayerShow) && CALayerShow!='0') 
  	     	 CriticalActionLayer()
          ;
      else if (showHelpScreen=='Y') {
              showHelpScreenFunction();
                          }
   }

   if(showMatchOfTheDay)
   {
      var matchOfDay = new matchOfDayMatches();
      matchOfDay.pre();
      matchOfDay.request();
   }

   $('#videoCloseID').bind('click', function(e)
  {
    videoLinkRequest(profileid);
  });
    
    var responseFlag = modifyMemMsgForLightningDeal();
    if(!responseFlag)
      showTimerForMemberShipPlan();
	profile_completion(iPCS);
    
	if(showFTU){
		var desiredPartnersObj = new desiredPartnerMatches();
		desiredPartnersObj.pre();
		desiredPartnersObj.request();
		$("#justJoinedCountBar").removeClass("disp-none");
		$("#dailyMatchesCountBar").removeClass("disp-none");
	}
	else
	{
		inviewCheck();

		var count = new engagementCounts();
		var dailyMatchObj =new dailyMatches();
		var justJoined = new justJoinedMatches();
		var lastSearch = new lastSearchMatches();
		verifedMatchObj =new verifiedMatches();
		var recentvisitors = new recentProfileVisitor();
		var shortlist = new shortlistProfiles();

		count.pre();
		count.request();

		dailyMatchObj.pre();
		dailyMatchObj.request();

		justJoined.pre();
		justJoined.request();

		$(window).on('beforeunload', function() {
		    $(window).scrollTop(0);
		});
		
		$(window).scroll(function () {
		 scrolling(justJoined, lastSearch, verifedMatchObj, recentvisitors, shortlist);
		});

		var interests = new interestReceived();
		var mess = new messages();
		var accept = new acceptance();		
		if(showExpiring)
    {
		  var expiringInterests = new expiringInterest();
    }
    else
    {
      var filteredInterests = new filteredInterest();
    }
		
   


		var tabs = [].slice.call( document.querySelectorAll( 'ul.tabs > li' ) );
		currentTab = -1;
		currentPanelEngagement = -1;
		var called = [];
	$('#MsgEngagementHead').bind("click",function() 
	{
  
 $("#totalMessagesReceived").removeClass('disp-none');
		$("#messagesCountNew").addClass("disp-none").removeClass("disp-cell");
    engagementClickHanding(mess,3);
	  });
  
    $('#acceptanceEngagementHead').bind("click",function() 
  {
    
    $("#totalAcceptsReceived").removeClass('disp-none');
	  $("#allAcceptanceCount").addClass("disp-none").removeClass("disp-cell");
    engagementClickHanding(accept,2);
    });
	 $('#interestEngagementHead').bind("click",function() 
	{
    
    $("#totalInterestReceived").removeClass('disp-none');
		$("#interetReceivedCount").addClass("disp-none").removeClass("disp-cell");
		engagementClickHanding(interests,0);
	  });

    if(showExpiring)
    {
      $('#expiringInterestHead').bind("click",function() 
      {
        $("#totalExpiringInterestReceived").removeClass('disp-none');
        $("#expiringInterestCount").addClass("disp-none").removeClass("disp-cell");
        engagementClickHanding(expiringInterests,1);
      });
    } 
    else
    {
      
      $('#filteredInterestHead').bind("click",function() 
      {
        $("#totalFilteredInterestReceived").removeClass('disp-none');
    		$("#filteredInterestCount").addClass("disp-none").removeClass("disp-cell");
        engagementClickHanding(filteredInterests,1);
	    });
    }
   var engagementClickHanding = function (ele, currentTabId) {
    // same height for all tabs.
    if(currentTabId=='0' || currentTabId=='1') var height='390px';
      else var height='390px';      
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
// cal scripts
var buttonClicked=0;

        function validateUserName(name){
        var name_of_user=name;
        name_of_user = name_of_user.replace(/\./gi, " ");
        name_of_user = name_of_user.replace(/dr|ms|mr|miss/gi, "");
        name_of_user = name_of_user.replace(/\,|\'/gi, "");
        name_of_user = $.trim(name_of_user.replace(/\s+/gi, " "));
        var allowed_chars = /^[a-zA-Z\s]+([a-zA-Z\s]+)*$/i;
        if($.trim(name_of_user)== "" || !allowed_chars.test($.trim(name_of_user))){
                return "Please provide a valid Full Name";
        }else{
                var nameArr = name_of_user.split(" ");
                if(nameArr.length<2){
                      return "Please provide your first name along with surname, not just the first name";
                }else{
                     return true;
                }
        }
       return true;
     
    }    
    


function scrolling(justJoined, lastSearch, verifedMatchObj, recentvisitors, shortlist)
{
	if(!showFTU && PageSource == "MyjsPc")
	{
		inView = $('#'+lastSearch.name+':in-viewport').length;
		if(inView != 0 && lastSearch.displayed == 0)
		{
			lastSearch.pre();
			lastSearch.request();
			lastSearch.displayed = 1;
		}

		inView = $('#'+verifedMatchObj.name+':in-viewport').length;
		if(inView != 0 && verifedMatchObj.displayed == 0)
		{
			verifedMatchObj.pre();
			verifedMatchObj.request();
			verifedMatchObj.displayed = 1;
		}

		inView = $('#'+recentvisitors.name+':in-viewport').length;
		if(inView != 0 && recentvisitors.displayed == 0)
		{
			recentvisitors.pre();
			recentvisitors.request();
			recentvisitors.displayed = 1;
		}

		inView = $('#'+shortlist.name+':in-viewport').length;
		if(inView != 0 && shortlist.displayed == 0)
		{
			shortlist.pre();
			shortlist.request();
			shortlist.displayed = 1;
		}
	}
}

    function showMatchOfTheDayCards(Object)
    {
      if(Object.data.profiles)
      {
        fillMatchOfTheDayCards(Object.data);
      }
    }

    function fillMatchOfTheDayCards(responseObject)
    { 
        jObject = $('#matchOfDaySection');
        htmlInside = jObject.html();
        jObject.html('');
        var totalCount = responseObject.profiles.length;
        var profiles = responseObject.profiles;
        var tracking = "stype=MOD";
        for(var i=0; i < totalCount; i++)
        {
            jObject.append(htmlInside);
            jObject.parent().find('#matchOfDaySubSection').attr('id', profiles[i].profilechecksum + '_matchOfDay');
            jObject.parent().find('#cardsForMatchOfDay').attr('id', profiles[i].profilechecksum + '_matchOfDay_id');
            $("#" + profiles[i].profilechecksum + '_matchOfDay').attr('data-matchID', i);
       }

       var getNStk = parseInt($('.stk').length);
        $('.stk').eq(0).addClass('active');
        $('.stk').each(function(index)
        {
            if(index<3)
            {
                $(this).css('z-index',getNStk).addClass('card-index-'+index);
            }
            else
            {
                $(this).css({
                        'z-index':getNStk,
                        'display':'none'
                    });
            }                             
            getNStk=getNStk-1;
        });

        for(var i = 0; i < totalCount; i++)
        {
						var profileChecksum = profiles[i].profilechecksum;
						var tracking = "stype=MOD";
						jObject = $("#"+profileChecksum+'_matchOfDay');

						var profileUrl = "/profile/viewprofile.php?profilechecksum="+profileChecksum+'&'+tracking+"&total_rec="+totalCount+"&actual_offset="+(i+1)+"&hitFromMyjs="+1+"&listingName=matchOfDay";

            var postAction = "postActionMyjs('"+profileChecksum+"','"+postActionsUrlArray['INITIATE']+"','" +profileChecksum+"_"+'matchOfDay'+"','interest','"+tracking+"');";

            jObject.find('.profileLink').attr('href',profileUrl);
            
            jObject.find('.sendInterest').attr('onClick', postAction);

            var username = '';
            if(typeof profiles[i].name_of_user != 'undefined' && profiles[i].name_of_user != '')
            {
              username = profiles[i].name_of_user;
            }
            else
            {
              username = profiles[i].username;
            }
            jObject.find('.profileName').html(username);
            if(typeof profiles[i].subscription_text != 'undefined')
            {
              jObject.find('.subscription').html(profiles[i].subscription_text);
            }           

            jObject.find('.profileName').attr('profileChecksum',profileChecksum);
            jObject.find('.userLoginStatus').html(profiles[i].userloginstatus);
            jObject.find('.gunascore').html(profiles[i].gunascore);
            
            // set image url
            jObject.find('.mod_img').attr("src",profiles[i].profilepic120url);
            // set age height
            jObject.find('.age_height').html(profiles[i].age + ' yr,  ' + profiles[i].height);
            
            jObject.find('.edu_level_new').html(profiles[i].edu_level_new);
            jObject.find('.caste').html(profiles[i].caste);
            jObject.find('.mtongue').html(profiles[i].mtongue);
            jObject.find('.religion').html(profiles[i].religion);
            jObject.find('.occupation').html(profiles[i].occupation);
            jObject.find('.location').html(profiles[i].location);
            jObject.find('.income').html(profiles[i].income);
            jObject.find('.mstatus').html(profiles[i].mstatus);
            if (loggedInJspcGender == 'F')
            {
              jObject.find('.liketext').html('Like his profile?');
            }
            else
            {
              jObject.find('.liketext').html('Like her profile?'); 
            }
        }

        //on click close button setStack is call'd
        $('.stk_cls').on('click',setStackMOD);
        $('#prfDay').removeClass('disp-none');
    }

    function toggleStackClass(param)
    {
        if(param.hasClass('card-index-1'))
        {
            param.removeClass('card-index-1').addClass('card-index-0');
        }
        else if(param.hasClass('card-index-2'))
        {
            param.removeClass('card-index-2').addClass('card-index-1');
        }
        else if( (!param.hasClass('card-index-1')) && (!param.hasClass('card-index-2')))
        {
            param.addClass('card-index-2').fadeIn('fast');
        }
    }
    function setStackMOD()
    {
      	$('.stk_cls').off('click',setStackMOD);
        var eleActive = $('#prfDay').find('.active');
        if($(this).hasClass('stk_cls'))
        {
      		// on click of close
        	var MatchProfileChecksum = eleActive.find('.profileName').attr('profileChecksum');
        	onCloseMatchOfDay(MatchProfileChecksum);
        }
        
        getIdNum = parseInt(eleActive.attr('data-matchID'));
        $(eleActive).removeClass('active');
        $('.stk').eq(getIdNum+1).addClass('active');
        if((isNaN(getIdNum)) ||   ((getIdNum+1)==($('.stk').length)))
        {
            $('#prfDay').fadeOut(500);
        }
        else
        {
            var start = new Date().getTime();
            $('.stk').eq(getIdNum).addClass('card-card-out');
            setTimeout(function(){ $('.stk_cls').on('click',setStackMOD);},800);
            for(i=getIdNum+1;i<(getIdNum+4);i++)
            {
                var ele = $('.stk').eq(i);
                toggleStackClass(ele);
            }
        }
        
        $('.card').each(function(index){
            
            if($(this).hasClass('card-card-out'))
            {
              $(this).css('z-index','-1');
            }
            
            
          });

    }

    function onCloseMatchOfDay(MatchProfileChecksum) 
    {
			var data = {"MatchProfileChecksum":MatchProfileChecksum};
      $.ajax({
				url: '/api/v1/myjs/closematchOfDay',
				data: data,
				timeout: 5000,
				success: function(response) 
				{
				}
			});
    }

   function sendAltVerifyMail()
   {
                showCommonLoader();
                var ajaxData={'emailType':'2'};
                var ajaxConfig={};
                ajaxConfig.data=ajaxData;
                ajaxConfig.type='POST';
                ajaxConfig.url='/api/v1/profile/sendEmailVerLink';
                ajaxConfig.success=function(resp)
                {   
                      msg ="A link has been sent to your email Id "+altEmail+', click on the link to verify your email';
                        $("#altEmailConfirmText").text(msg);
                        $("#criticalAction-layer").hide();
                        $("#alternateEmailSentLayer").show();
                        hideCommonLoader();
                }
                jQuery.myObj.ajax(ajaxConfig);

   }

