var getaTop,commLayerPageIndex=1,commHistoryFullLoaded=0,commHistoryLoading=0,commHistoryDivCount=1,vspScrollLevel=600,alreadyShown=0;
var kundliResponseArr = {"F":"You cannot request horoscope as you don’t match this profile's filters","G":"You cannot request horoscope to a person of the same gender"};

$(function(){
	 var $el, leftPos, newWidth;
	 $(document).on("scroll", OnScrollChange);	
	  
	 //same page navigation smooth scrolling
	 $('a[href*=#]:not([href=#])').click(function() {
	    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
	      var target = $(this.hash);
	      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
	      if (target.length) {
	        $('html,body').animate({
	          scrollTop: target.offset().top-50
	        }, 500);
	        return false;
	      }
	    }
	  });
	  
	  
	 //get the top postion of the first element ie about him/her
	 var geta = $('.tabs-style-prf');
	 var getH = $('.tabs-style-prf').outerHeight();
	 getaTop= (geta.offset().top + getH)-20;	// the extra value is subtracted to get the exact point of scroll change
	 
	 
	 
	 //animating undlerling bar for ul li in scrollabel div	 
	  $("#menu-center ul li").find("a").click(function() {		  		
		  	 $el = $(this);
			 leftPos = $el.position().left;
			 var z = $el.parent();
        	 newWidth = (Math.floor(z[0].getBoundingClientRect().width));
			 
			 moveline(newWidth,leftPos);
			 
	  });	
	  
	//more function implemetation for she/he likes
	// Configure/customize these variables.
    var showChar=70;  // How many characters are shown by default
    var ellipsestext = "...";
    
    $('.more, .moredes').each(function() {
        var content = $(this).html();
		if($(this).hasClass( "more" ) || $(this).hasClass( "moredes"))
		{
			showChar=70;
		}
		else
		{
			showChar=55;
		}
        if(content.length > showChar) {
            var c = content.substr(0, showChar);
            var h = content.substr(showChar, content.length - showChar);			
			var html = '<span>'+c+'</span><span class="morecontent">'+h+'</span>&nbsp;&nbsp;<span class="morelink cursp color12">...more</span>'; 
           $(this).html(html);
        }
 
    });
	
 
    $(".morelink").click(function(){
		$(this).css('display','none');	   
	   $(this).prev().fadeIn(200);
        return false;
    });
	
	// we talk for you layer
	$('.js-txt1').click(function(){
																showCommonLoader();
                                $.ajax({
                                    url : "/profile/handle_intro_call.php?TYPE_OF=S&to_do=add_intro&senders_data="+ProCheckSum+"&ajax_error=2",
                                    data : ({dataType:"json"}),
                                    async:true,
                                    timeout:30000,
                                    success:function(){
                                    	hideCommonLoader();
                                        $('.js-overlay').fadeIn(200,"linear",function(){ $('#we-talk-layer').fadeIn(300,"linear")});
                                        $('.js-overlay').bind("click",function(){
                                            $('#we-talk-layer').fadeOut(200,"linear",function(){ $('.js-overlay').fadeOut(200,"linear")});
                                            closeWeTalkForYou();
                                            $(this).unbind("click");
                                        });
                                        $(document).keyup(function(e){
                                            if(e.which == 27){
                                              $('#we-talk-layer').fadeOut(200,"linear",function(){ $('.js-overlay').fadeOut(200,"linear")});
                                              closeWeTalkForYou();
                                              $(document).unbind("keyup");
                                            }
                                        });
                                        $('.js-txt1').unbind("click");
                                        $('.js-txt1').removeClass("cursp");
                                        $('.js-div3').html("Added to 'we talk for you' list");
                                    }
                                });
	})
	$('#cls-we-talk').click(function(){
			$('#we-talk-layer').fadeOut(200,"linear",function(){ 
                            closeWeTalkForYou();
				
			});			
		});
    // ignore profile
	
	
	$('.js-action').click(function(){
		
		var offset = $('.prfbtnbar').offset();
		offset = offset-20;
		
		
		if($(this).hasClass('ignore'))
		{
			$('#ignore-layer').css('top',offset);
			$('.js-overlay').fadeIn(200,"linear",function(){ $('#ignore-layer').fadeIn(300,"linear")});	
		}

		if($(this).hasClass('report'))
		{
			showReportAbuseLayer();	
		}
		if($(this).hasClass('share'))
		{
			$('.js-overlay').fadeIn(200,"linear",function(){ $('#share-layer').fadeIn(300,"linear")});
			$('#confirmationMessage').hide();
                        $('.js-overlay').bind("click",function(){
                            $('#share-layer').fadeOut(300,"linear",function(){ $('.js-overlay').fadeOut(200,"linear")});
                            $(this).unbind("click");
                        });
		}

	});
        
       $(document).ready(function(){

       	var totalFields = $(".js-countfields").length;
        $(".js-total").html(totalFields); 
        $(".js-matching").html($(".prfic27").length); 
        if($(".js-hobbies").children().length ==0)
          $(".js-hobbySection").hide();
      	if(selfUsername)
      	{
            if(typeof(hideUnimportantFeatureAtPeakLoad) =="undefined" || hideUnimportantFeatureAtPeakLoad < 4)
        	gunaScore = getGunnaScore();
      	}
        if($(".js-checkMatch").length ==0)
            $(".js-hideMatch").hide();
        $(".content").mCustomScrollbar();
       	
      });
      $('.js-hasaction').click(function() {
	/**
	 * If action is request call request photo else if login than login will be handled LATER
	 * myaction : "Request" or "Login" values are permissible
	 */
	if
	 ($(this).attr("myaction") == "Request") {
		addBottomClass="lh40";
		requestphoto($(this).attr("data"), $(this).attr("id"),addBottomClass);
                $(this).children().addClass("propos6").addClass("fullwid").addClass("pos-abs");
	
	} else if ($(this).attr("myaction") == "Login") {
		
		console.log("error1");// LATER
		//alert(" login to view photo of profilechecksum " + $(this).attr("data"));
	}

});
function closeWeTalkForYou(){
    $('.js-div1').addClass('prfic41');
    $('.js-div3').addClass('prfbg7');
    $('.js-overlay').fadeOut(300,"linear");
}
      function getGunnaScore()
      {
      	//showCommonLoader();
        $.myObj.ajax({
          showError: false, 
          method: "POST",
          url : '/api/v1/profile/gunascore?oprofile='+ProCheckSum,
          data : ({dataType:"json"}),
          async:true,
          timeout:20000,
          success:function(response){
            //hideCommonLoader();
            gunaScore = response.SCORE;
            if(gunaScore != null && gunaScore != 0){
              $(".js-showGuna").html(gunaScore);
              $(".js-hideGuna").show();
              $(".js-changeText").html("Guna Match");
            }
          }
        });
      }	
      $(".js-reqHoro").click(function(){
      	showCommonLoader();
        $.ajax({
          method: "POST",
          url : "/profile/horos_req_layer.php?profilechecksum="+ProCheckSum+"&ajax_error=2&Submit=1",
          async:true,
          timeout:20000,
          success:function(response){
            response = response.replace(/(\r\n|\n|\r)/,"");
          		hideCommonLoader();
              if(response == "true"){
              	$(".js-reqHoro").unbind("click");
                $(".js-reqHoro").html("Horoscope request sent");
                $(".js-reqHoro").removeClass("bg5").addClass("bgDisButton").addClass("color11").removeClass("cursp").removeClass("blueRipple").removeClass("hoverBlue");
              }
              else{
              	$('.fullHoroData').hide();
              	$('.noHoroData').show();
              	$('.horoErrorCondition').html(kundliResponseArr[response]);
              }              	
          }
        });  
      });
      $(".js-viewHoro").click(function(){
      	showCommonLoader();
        $.ajax({
          method: "POST",
          url : "/profile/horoscope_astro.php?SAMEGENDER=&FILTER=&ERROR_MES=&view_username="+ViewedUserName+"&SIM_USERNAME="+ViewedUserName+"&type=Horoscope&ajax_error=2&checksum=&profilechecksum="+ProCheckSum+"&randValue=890&showDownload=1&GENDERSAME="+sameGender, //added an extra parameter here which shows the Download part
          async:true,
          timeout:20000,
          success:function(response){
          		hideCommonLoader();
              $("#putHoroscope").html(response);
              $('.js-overlay').fadeIn(200,"linear",function(){ $('#kundli-layer').fadeIn(200,"linear")});
          }
        });  
      });
      $(".js-astroCompMem,.js-freeAstroComp").click(function(){
              showCommonLoader();
              if($(this).hasClass('js-astroCompMem')){
                  $("#buttonMem").html("Get Astro Compatibility");
                  $("#textMem").html("You can view a detailed report of your compatibility with "+viewedProfileUsername+" by subscribing to our Astro compatibility addon");
                  $("#buttonMem").attr("href","/membership/jspc");
              }
              else{
                  $("#buttonMem").html("Upgrade Membership");
                  $("#buttonMem").attr("href","/membership/jspc");
              }
              $('.js-overlay').fadeIn(200,"linear",function(){ $('#astroComp').fadeIn(200,"linear")});  
              hideCommonLoader();
      });
      $(".js-astroMem").click(function(){
          $.ajax({
                    method: "POST",
                    url : "/profile/check_horoscope_compatibility.php?profilechecksum="+ProCheckSum+"&sendMail=1",
                    async:true,
                    timeout:20000,
                    success:function(response){
                    }
           });
      });
});
function moveline(widthParam, leftParam){	
	  $("#barmov1").stop().animate({left: leftPos,width: newWidth},100);
}
function OnScrollChange(event){
	//this part handles the to hide/show the scrollabel menu
	 var scrollPos = $(document).scrollTop();
	 
	 if(scrollPos>getaTop)
	 {
		 $('.js-barscroll').fadeIn(200);
		
		

	 }
	 else
	 {
		 
		  $('.js-barscroll').fadeOut(200);
		 
	 }
	 //this part handle the scrollabel menu
	 $('#menu-center a').each(function () {		 
		 var currLink = $(this);
         var refElement = $(currLink.attr("href"));	
		  if (refElement.offset().top-60 <= scrollPos && refElement.offset().top + refElement.height() > scrollPos) {
            leftPos = currLink.position().left;
			 var z = currLink.parent();
        	 newWidth = (Math.floor(z[0].getBoundingClientRect().width));
        	
			moveline(newWidth,leftPos);
        }
      });
        if(scrollPos>vspScrollLevel && !alreadyShown){
            if(typeof(hideUnimportantFeatureAtPeakLoad) =="undefined" || hideUnimportantFeatureAtPeakLoad < 3)
               displayViewSimilarProfiles();
        }
	 
     
}
$('#validateSenderEmail').click(function(e){

	var errorMessage = "Please enter a valid e-mail id";
	var email_regex = /^([A-Za-z0-9._%+-]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i;
	if(!email_regex.test($('#receiverEmail').val()))
	{
		$('#errorText').html(errorMessage);
	}
	else
	{	
		$('#errorText').html("");
		shareProfile();

	}
	return false;
});


$('.js-undoAction').click(function(){
	if($(this).hasClass('undoShare'))
	{
		$('#share-layer').fadeOut(300,"linear",function(){ $('.js-overlay').fadeOut(200,"linear")});
		$('#errorText').html("");
	}
	if($(this).hasClass('close'))
	{
		$('#shareProfileDiv').show(300);
		$('#shareProfileTopSection').show(300);
		$('#share-layer').fadeOut(300,"linear",function(){ $('.js-overlay').fadeOut(200,"linear")});
	}
});

function displayViewSimilarProfiles(){
    alreadyShown=1;
    $.myObj.ajax({
          showError: false, 
          method: "POST",
          datatype : "json",
          url : "/api/v1/search/ViewSimilarProfiles",
          async:true,
          timeout:20000,
          data : {profilechecksum: ProCheckSum,actionName :"similarprofile",searchid : searchId},
          success:function(responseData){
              //responseData = JSON.parse(responseData);
              if(responseData.responseStatusCode == 0)
                createSimilarProfileSection(responseData);
          }
    });  
}
function createSimilarProfileSection(vspData){
    var thisProfile={};
    var vspHtml = "";
    noOfProfiles = vspData.no_of_results;
    if(noOfProfiles > 0)
      $("#viewSimilarDiv").show();
    if(noOfProfiles > 12){
        $("#browseBtn").show();
        noOfProfiles = 12;
    }
    for(var i=0;i<noOfProfiles;i++){
      thisProfile = vspData.profiles[i];
      tempHtml = $("#dummyVSP").html();
      tempHtml = tempHtml.replace(/{{profileChecksumm}}/g,thisProfile.profilechecksum);
      tempHtml = tempHtml.replace(/{{photoUrl}}/g,thisProfile.photo.url);
      tempHtml = tempHtml.replace(/{{stypeInfo}}/g,vspData.stype);
      tempHtml = tempHtml.replace(/{{age_similar}}/g,thisProfile.age);
      tempHtml = tempHtml.replace(/{{height_similar}}/g,thisProfile.height);
      tempHtml = tempHtml.replace(/{{caste_similar}}/g,thisProfile.caste);
      tempHtml = tempHtml.replace(/{{city_similar}}/g,thisProfile.city_res);
      tempHtml = tempHtml.replace(/{{mtongue_similar}}/g,thisProfile.mtongue);
      tempHtml = tempHtml.replace(/{{education_similar}}/g,thisProfile.edu_level_new);
      tempHtml = tempHtml.replace(/{{income_similar}}/g,thisProfile.income);
      tempHtml = tempHtml.replace(/{{work_similar}}/g,thisProfile.occupation);
      imgDiv= "src=\""+thisProfile.photo.url+"\"";
      tempHtml = tempHtml.replace(/{{photourl}}/g,imgDiv);
      vspHtml = vspHtml+tempHtml; 
    }
    $("#putDummyDiv").html(vspHtml);
}

function fetchSimilarProfiles(){
 
}


function shareProfile(){
	var dataArr = {};
	var femail = {};
	dataArr["email"] = senderEmail;
	dataArr["name"] =	$('#senderName').val();
	dataArr["femail[]"] =$("#receiverEmail").val();
	dataArr["message"] = $("#message").val();
	dataArr["profilechecksum"] = ProCheckSum;
	dataArr["ajax_error"] = "2";
	dataArr["invitation"] = "1";
	dataArr["send"]= "1";
	dataArr["username"] = viewedProfileUsername;
	showCommonLoader(); 
	$.ajax({
		type : 'POST',
		url : '/profile/forward_profile.php',
		data:  {dataArrObj: JSON.stringify(dataArr), isJson: "1"},
		async:true,
		success:function(response){
			hideCommonLoader();
			if(response == "bye"){
				showConfirmationMessage();
			}
			else if(response == "Mail not sent"){
				showErrorMessage();
			}
			else if(response == "ERROR#Friend Emailid not provided"){
				var errorMessage="Please provide receiver's e-mail id"
				$('#errorText').html(errorMessage);
			}
		}
	});
}

function showErrorMessage(){
	var errMessage="You have reached the maximum limit of number of profiles you can share in a day.<br> Please try after 24 hours";
	$('#shareProfileDiv').hide();
	$('#shareProfileTopSection').hide();
	$('#confirmationMessage').show();
	$('#addConfirmationMessage').html(errMessage);
}
function showConfirmationMessage(){
	var confMessage="An email containing profile details has been sent to your contact";
	$('#shareProfileDiv').hide();
	$('#shareProfileTopSection').hide();
	$('#confirmationMessage').show();
	$('#addConfirmationMessage').html(confMessage);
}
$('#cls-view-horo').click(function(){
	$('#kundli-layer').fadeOut(300,"linear",function(){ $('.js-overlay').fadeOut(200,"linear")});
});
$('#cls-astroComp').click(function(){
	$('#astroComp').fadeOut(300,"linear",function(){ $('.js-overlay').fadeOut(200,"linear")});
});

$('.js-searchTupleImage').click(function(){
    var photoData = $(this).attr("data");
    photoData = photoData.split(",");

    var username = photoData[1];
    var profilechecksum = photoData[2];
    var albumCount = photoData[0];
    if((typeof(loggedInJspcUser)!="undefined" && loggedInJspcUser=="") || !profilechecksum){
                return true;
     }
    openPhotoAlbum(username,profilechecksum,albumCount);

})	
$(".okayClick").click(function(){
	$('.noHoroData').hide();
	$('.fullHoroData').show();
})


function showReportAbuseLayer(){
	var jObject=$("#reportAbuse-layer");
	jObject.find('.js-username').html(finalResponse.about.username);
	jObject.find('.js-otherProfilePic').attr('src',$("#profilePicScrollBar").attr('src'));
  $("#reportAbuseList").mCustomScrollbar({
 theme: "light",
});
$('.js-overlay').unbind();
$('.js-overlay').eq(0).fadeIn(200,"linear",function(){$('#reportAbuse-layer').fadeIn(300,"linear",function(){})}); 
closeReportAbuseLayer=function() {

$('.js-overlay').fadeOut(200,"linear",function(){ 
	$('#reportAbuse-layer').fadeOut(300,"linear")});
	
};

$('#reportAbuseCross').bind('click',closeReportAbuseLayer);


}



/* this function is used for showing commmunication layer on the viewprofile page*/
function showCommunicationLayer(resp) {
var lastId;
var layerObj=$("#commHistoryOverlay-layer");
var commDiv=layerObj.find('#commDiv');
var newHtml="";
var historyResponse=resp.history;
var firstId="commDiv"+commHistoryDivCount;
if(resp.nextPage=='false')
    commHistoryFullLoaded = 1; 
layerObj.find('.otherProfilePic').attr('src',resp.viewed);
layerObj.find(".js-usernameCC").html(resp.label);

if (!historyResponse){
	layerObj.find('#commHistory').addClass('disp-none');
	layerObj.find('#commHistoryAbsent').removeClass('disp-none');
}

else {

	layerObj.find('#commHistory').removeClass('disp-none');
	layerObj.find('#commHistoryAbsent').addClass('disp-none');

for(i=0;i<historyResponse.length;i++)
{
var tempDiv=commDiv.clone();

lastId="commDiv"+commHistoryDivCount++;
tempDiv.attr('id',lastId);
tempDiv.removeClass('disp-none');
tempDiv.find('.js-commHeading').html(historyResponse[i].header);	
tempDiv.find('.js-commTime').html(historyResponse[i].time);	

if(historyResponse[i].message)
tempDiv.find('.js-commMessage').removeClass('disp-none').html(historyResponse[i].message);

if (historyResponse[i].ismine==true)	{
tempDiv.find('.js-profilePic').attr('src',resp.viewer);
tempDiv.addClass('setr');
}

else 
{
tempDiv.find('.js-profilePic').attr('src',resp.viewed);
tempDiv.addClass('setl');

}
newHtml=tempDiv.outerHtml()+newHtml;
}
layerObj.find('#mainDiv').prepend(newHtml);
layerObj.find("#commHistoryLoader").css('visibility','hidden');
}
if(commLayerPageIndex>2){layerObj.find("#commLayerScroller").mCustomScrollbar('scrollTo',$("#"+firstId),{scrollInertia:0});}
commHistoryLoading=0;
if(commLayerPageIndex==2)
$('.js-overlay').eq(0).fadeIn(200,"linear",function(){$('#commHistoryOverlay-layer').fadeIn(300,"linear",function(){
	
        $(this).find("#commLayerScroller").mCustomScrollbar({
                                                        advanced:{updateOnSelectorChange:true},
							callbacks:{
                                                                onTotalScrollBackOffset:200,
								onTotalScrollBack:function(){if(commHistoryFullLoaded || commHistoryLoading) return;$("#commHistoryLoader").css('visibility','visible');communicationLayerAjax();}
							}
				});
        $(this).find("#commLayerScroller").mCustomScrollbar('scrollTo','bottom',{scrollInertia:0});
        
                                
})}); 

closeCommLayer=function() {

$('.js-overlay').fadeOut(200,"linear",function(){ 
	$('#commHistoryOverlay-layer').fadeOut(300,"linear")});
$('.js-overlay').unbind('click');

};


$('.js-overlay').bind('click',closeCommLayer);
layerObj.find('.closeCommLayer').bind('click',closeCommLayer);
}

function communicationLayerAjax(initialise){
var profileChecksum=ProCheckSum;
if(!profileChecksum) return;
commHistoryLoading=1;
    if(typeof initialise !='undefined' && initialise==1){$("#commHistoryOverlay-layer").find('#mainDiv').html('');commLayerPageIndex=1;commHistoryFullLoaded=0;commHistoryLoading=0;commHistoryDivCount=1;showCommonLoader();}
          ajaxConfig= {};
          ajaxConfig.type= "POST";
          ajaxConfig.dataType="json";
          ajaxConfig.url='/contacts/CommunicationHistoryV1?pageNo='+(commLayerPageIndex++);
          ajaxConfig.data={'profilechecksum':profileChecksum};
          ajaxConfig.context= this;
          ajaxConfig.success=function(response) {
          	hideCommonLoader();
             showCommunicationLayer(response);		
             }
jQuery.myObj.ajax(ajaxConfig);
       
}




function reportAbuse(ele){
var reason='';
var mainReason = '';
var layerObj=$("#reportAbuse-layer");  
var isValid = false;
    layerObj.find("#reportAbuseList li").each(function(){
      if($(this).hasClass('selected')) { 
        mainReason = $(this).find(".reason").html();
        if($(this).hasClass("openBox")) {
         reason=$($(this).find(".otherOptionMsgBox textarea")[0]).val();
        if(!reason) {
            layerObj.find('#errorText').removeClass('disp-none');
            isValid = true;
        }
      }
    }
    })
    if(isValid == true) {
      return;
    }
$('.js-overlay').unbind('click');
if (finalResponse) var otherUser=finalResponse.about.username;
var selfUname=selfUsername;
var layerObj=$("#reportAbuse-layer");
var ajaxConfig=new Object();
if(!layerObj.find(".selected").length) {layerObj.find('#RAReasonHead').text("*Please Select a reason");return;}
if(!mainReason) mainReason=layerObj.find(".selected").eq(0).text().trim();
if(!mainReason||!selfUname || !otherUser) return;
showCommonLoader();
var feed={};
reason=$.trim(reason);
//feed.message:as sdf sd f
feed.category='Abuse';
feed.mainReason=mainReason;
feed.message=otherUser+' has been reported abuse by '+selfUname+' with the following reason:'+reason;
ajaxData={'feed':feed,'CMDSubmit':'1','profilechecksum':ProCheckSum,'reason':reason};
ajaxConfig.url='/api/v1/faq/feedbackAbuse';
ajaxConfig.data=ajaxData;
ajaxConfig.type='POST'

ajaxConfig.success=function(response){
	$('#reportAbuse-layer').fadeOut(300,"linear");

	          	hideCommonLoader();
	          	
		var jObject=$("#reportAbuseConfirmLayer");
    if(response.responseStatusCode == '1'){
      $('#hiphenForConfirm').html('');
      $('#reportAbuseConfirmHeading').html('');
    }
      
	jObject.find('.js-username').html(otherUser);
	jObject.find('.js-otherProfilePic').attr('src',$("#profilePicScrollBar").attr('src'));

		$('.js-overlay').eq(0).fadeIn(200,"linear",function(){
      $('#messageForReportAbuse').html(response.message);
      $('#reportAbuseConfirmLayer').fadeIn(300,"linear",function(){})}); 

closeAbuseConfirmLayer=function() {

$('.js-overlay').fadeOut(200,"linear",function(){ 
	$('#reportAbuseConfirmLayer').fadeOut(300,"linear")});
	$('.js-overlay').unbind('click');

};

$('.js-overlay').bind('click',closeAbuseConfirmLayer);

	}

jQuery.myObj.ajax(ajaxConfig);


}

