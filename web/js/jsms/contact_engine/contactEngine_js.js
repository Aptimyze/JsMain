//var commonUrl="/contacts";

var dim='',postParams,photo={},disablePrimary={},disableOthers={},actionUrl = {"CONTACT_DETAIL":"/api/v2/contacts/contactDetails","INITIATE":"/api/v2/contacts/postEOI","INITIATE_MYJS":"/api/v2/contacts/postEOI","CANCEL":"/api/v2/contacts/postCancelInterest","SHORTLIST":"/api/v1/common/AddBookmark","DECLINE":"/api/v2/contacts/postNotInterested","REMINDER":"/api/v2/contacts/postSendReminder","MESSAGE":"/api/v2/contacts/postWriteMessage","ACCEPT":"/api/v2/contacts/postAccept","WRITE_MESSAGE":"/api/v2/contacts/WriteMessage","IGNORE":"/api/v1/common/ignoreprofile","PHONEVERIFICATION":"/phone/jsmsDisplay","MEMBERSHIP":"/profile/mem_comparison.php","COMPLETEPROFILE":"/profile/viewprofile.php","PHOTO_UPLOAD":'/social/MobilePhotoUpload',"ACCEPT_MYJS":"/api/v2/contacts/postAccept","DECLINE_MYJS":"/api/v2/contacts/postNotInterested","EDITPROFILE":"/profile/viewprofile.php?ownview=1"}, actionDetail = {'CONTACT_DETAIL':'ContactDetails',"INITIATE":"postEOI","CANCEL":"cancel","DECLINE":"decline","REMINDER":"reminder","MESSAGE":"postWriteMessage","ACCEPT":"accept","WRITE_MESSAGE":"WriteMessage"},actionTemplate = {"CONTACT_DETAIL":"contactDetailOverlay","INITIATE":"buttonsOverlay","CANCEL":"confirmationOverlay","DECLINE":"confirmationOverlay","REMINDER":"writeMessageOverlay","MESSAGE":"","WRITE_MESSAGE":"writeMessageOverlay","ACCEPT":"confirmationOverlay"},current_index ='', params = {},iButton = {},profile_index = {}, writeMessageAction = false,cssMap={'001':'mainsp msg_srp','003':'mainsp srtlist','004':'mainsp shortlisted','083':'ot_sprtie ot_bell','007':'mainsp vcontact','085':'ot_sprtie ot_chk','084':'deleteDecline','086':'mainsp ot_msg cursp','018':"mainsp srp_phnicon",'020':'mainsp srp_phnicon','ignore':'mainsp ignore','088':'deleteDeclineNew','089':'newitcross','090':'newitchk','099':'reportAbuse mainsp'};
var hideOverlay = 0;
var mainHeight, msgWindowMSGID='',msgWindowCHATID='',msgWindowPageIndex=1,paramsForMsgWindow,indexForMsgWindow,msgWindowOn=0,MsgWindowLoading=0;
function bgSetting() 
{
  dim = getDim();
  $('.bgset').css( "height", dim['hgt'] );
  $('.bgset').css( "width", dim['wid'] );
}
$(document).ready(function() {
  mainHeight = $(window).innerHeight();
  loaderTop();
});
function loaderTop()
{
                var vhgt = mainHeight;//$(window).innerHeight();
                vhgt/=2;
                var perspectiveHeight = $("#perspective").height();
                if(perspectiveHeight!=null && vhgt>perspectiveHeight)
                {
                        var diffHeight = vhgt-perspectiveHeight;
                        vhgt-=diffHeight;
                }
    vhgt+=$(window).scrollTop();
              //  $("#contactLoader").css({"top":vhgt+'px'});
               $("#contactLoader").css({"height":vhgt+'px'});
}
function getDim()
{
  var hgt = $(window).height();
  hgt = (hgt+50)+"px";
  var wid = $(window).width();
  wid = wid+"px";
  var dim={hgt:hgt,wid:wid};
  return dim;
}

function hideReportAbuse(){
  var mainEle=$("#reportAbuseContainer");
	if(mainEle.css('display')!='none'){
    $("#commonOverlayTop").show();
    arrReportAbuseFiles = [];
    var photoNode = document.getElementById("photoDiv");
    while (photoNode.hasChildNodes()) {
        photoNode.removeChild(photoNode.lastChild);
    }
    mainEle.hide();
  return true;  
  }
  
  return false;

}

function reportAbuse(index) {
    
    $("#attachDiv").addClass("dn");
    $("#attachTitle").unbind().bind('click',attachAbuseDocument);    
    $("#attachDiv").css('max-height',Math.round(window.innerHeight/2.5) + 'px');
    
    if(typeof(buttonSt)!='undefined' )
        $("#photoReportAbuse").attr("src", buttonSt.photo.url);
    else {
        var tempPhoto = $("#idd"+index+ " a img").attr('src');
        $("#photoReportAbuse").attr("src", tempPhoto);

            }
$('.RAcorrectImg,#commonOverlayTop').hide();
//$("#commonOverlayTop").hide();
var mainEle=$("#reportAbuseContainer");
mainEle.show();

var el=$("#reportAbuseMidDiv");
el.height($(window).height()-$("#reportAbuseSubmit").height()-mainEle.find('.photoheader').eq(0).height());
$("#reportAbuseMidDiv").removeClass("scrollhidImp")
var div = document.createElement('div');
            // css transition properties
            var props = ['WebkitPerspective', 'MozPerspective', 'OPerspective', 'msPerspective'];
            // test for each property
            for (var i in props) {
                if (div.style[props[i]] !== undefined) {
                    
                    cssPrefix = props[i].replace('Perspective', '').toLowerCase();
                    animProp = '-' + cssPrefix + '-transform';
                }
            }


el.css(animProp, 'translate(50%,0px)');
el.css('-' + cssPrefix + '-transition-duration', 600 + 'ms')
.css(animProp, 'translate(0px,0px)');

selectedReportAbuse="";
RAOtherReasons=0;
mainReasonAbuse = "";

$(".reportAbuseOption").unbind().bind('click',function () {

if($(this).attr('id')=='js-otherReasons' || $(this).attr('id')!='notOpen')
{
el.scrollTop('0px');
el.css('-' + cssPrefix + '-transition-duration', 600 + 'ms')
.css(animProp, 'translate(-50%,0px)');
RAOtherReasons=1;selectedReportAbuse="";
$("#js-otherReasonsLayer").removeClass('dispnone').val('');

$("#attachDiv").removeClass("dn");
var elem=$("#reportAbuseMidDiv");
elem.height($(window).height()-$("#reportAbuseSubmit").height()-mainEle.find('.photoheader').eq(0).height());
$("#attachDiv").width(window.innerWidth-40);
$("#reportAbuseMidDiv").addClass("scrollhidImp");

}

mainReasonAbuse=$(this).text();
$('.RAcorrectImg').hide();
$(this).find('.RAcorrectImg').show();
});


$("#reportAbuseSubmit").unbind().bind('click',function() {

var reason="";

if(RAOtherReasons)
{
	reason=$("#js-otherReasonsLayer").val().trim();
   
	if(!reason || reason.length < 25){ShowTopDownError(["Please Enter The Comments (in atleast 25 characters)"],3000);return;}
}
else {
	if(!mainReasonAbuse){ShowTopDownError(["Please select the reason"],3000);return;}
}
var bUploadSuccessFul = false;
if(arrReportAbuseFiles.length) {
    setTimeout(function(){
        $("#contactLoader,#loaderOverlay").show();
    },0);
    var bResult = uploadAttachment();
    if( false == bResult )
        return ;
    
    for(var itr = 0; itr < arrReportAbuseFiles.length; itr++) {
        if(arrReportAbuseFiles[itr].hasOwnProperty("uploded") || 
                arrReportAbuseFiles[itr].uploded == false || 
                (arrReportAbuseFiles[itr].hasOwnProperty("error") && arrReportAbuseFiles[itr].error == true) ) {
            bUploadSuccessFul = false;
            break;
        }
        bUploadSuccessFul = true;
    }
    if(false === bUploadSuccessFul)
        return bUploadSuccessFul;
}

var feed={};
reason=$.trim(reason);
mainReasonAbuse = $.trim(mainReasonAbuse);
//feed.message:as sdf sd f
feed.category='Abuse';
feed.mainReason = mainReasonAbuse;
feed.message=userName+' has been reported abuse by '+selfUsername+' with the following reason:'+reason;
if( bUploadSuccessFul ) {
    feed.attachment = 1;
    feed.temp_attachment_id = arrReportAbuseFiles['tempAttachmentId'] ;
}
ajaxData={'feed':feed,'CMDSubmit':'1','profilechecksum':profileChkSum,'reason':reason};
var url='/api/v1/faq/feedbackAbuse';
loaderTop();
$("#contactLoader,#loaderOverlay").show();
//$("#loaderOverlay").show();

//performAction('REPORT_ABUSE',ajaxData,index)
$.ajax({
  		          
		url: url,
		type: "POST",
		data: ajaxData,
		//crossDomain: true,
		success: function(result){  
                                        arrReportAbuseFiles = [];
                                        bUploadAttachmentInProgress = false;
                                        bUploadingDone = false
                                        
					$("#contactLoader,#loaderOverlay,#reportAbuseContainer").hide();
                                        var photoNode = document.getElementById("photoDiv");
                                        while (photoNode.hasChildNodes()) {
                                            photoNode.removeChild(photoNode.lastChild);
                                        }
					//$("#loaderOverlay").hide();
					//$("#reportAbuseContainer").hide();
                    if(result.responseStatusCode=='0'||result.responseStatusCode=='1'||CommonErrorHandling(result,'?regMsg=Y') ) 
                    {  
					ShowTopDownError([result.message],5000);
					$("#commonOverlayTop").show();
                    }	
                    
                }
});

});


historyStoreObj.push(hideReportAbuse,"#reportAbuse");




}



function scrollOn()
{
  if($("#mainContent").length)
  {
    $("#mainContent").css({"overflow":"auto"});
    $("#mainContent").css({"height":"auto"});
  }
}
function scrollOff()
{
  var vhgt = $(window).innerHeight();
  $("#mainContent").css({"height":vhgt+'px', "overflow":"hidden"});
  $("#commonOverlay").css({"height":vhgt+'px', "overflow":"auto"});

  window.scrollTo(0,0);
}
function hideForHide()
{
  $("#bottomElement").removeClass('bg14');
  $(".forHide").hide();

}
function layerClose()
{
        $("#ce_photo,#imageId").attr("src",'');
  //$("#imageId").attr('src','');
  $("#contactLoader").hide();
        var current_index       =$("#selIndexId").val();
  setTimeout(function(){$("#primeButton_"+current_index).attr("tabindex",-1).css('outline',0).focus();}, 0);
  scrollOn();
        var srchIdExist= $("#searchHeader").length;
        if(srchIdExist)
                $("#searchHeader").css('visibility','');
        var tabIdExist= $("#tabHeader").length;
        if(tabIdExist)
                $("#tabHeader").css('visibility','');
        if(window.location.href.search("viewprofile")!=-1){
  var profilechecksum=$("#buttonInput"+current_index).val();
        var interestCheck=$("#primeButton_"+current_index).html();
        if(interestCheck=="Interest Sent"){
    var similarProfileCheckSumTemp = window.location.search.split('similarOf=');
    if(typeof similarProfileCheckSumTemp[1]!="undefined" && similarProfileCheckSumTemp[1])
      similarProfileCheckSum = similarProfileCheckSumTemp[1].split("&")[0];
    else
      similarProfileCheckSum = "";
    if(typeof NAVIGATOR=="undefined" || !NAVIGATOR)
      var NAVIGATOR="";

        if(typeof getNAVIGATOR == "function" && NAVIGATOR==""){
            NAVIGATOR = getNAVIGATOR();
        }
            //if(window.location.href.search('toShowECP')!=-1)
            {
    if(canIShowNext(similarProfileCheckSum,profilechecksum))
                {
      //if(!ISBrowser("UC"))
       //    enableLoader();
                    window.location.href = "/search/MobSimilarProfiles?profilechecksum="+profilechecksum+"&"+NAVIGATOR+"&fromProfilePage=1"+(((typeof SPA_CE!='undefined') && (SPA_CE=='Y')) ? "&fromSPA_CE=1" : "" );
                }
            }
  }
  }
}

function setWindowParams(username, imageUrl, index){

  $("#parentFootId").show();
  if(username)
          $("#usernameId").html(username);
  if(imageUrl)
          $("#imageId").attr('src',imageUrl);
  else
    $("#imageId").attr("src", photo[index]);
  var vhgt = $(window).height(), com_headHgt =$('#comm_headerMsg').outerHeight(), send_hgt =$('#parentFootId').outerHeight(), com_total = com_headHgt + send_hgt;
        com_msgHgt1 = vhgt - com_total;
        $('.message_con').css({'height':com_msgHgt1,'overflow-y':'auto','overflow-x':'hidden'});

        var tabIdExist= $("#tabHeader").length;
        if(tabIdExist){
    $("#tabHeader").css('visibility','hidden').removeClass('posFixTop');
    $(picContent).css('margin-top','0px');
  }
        var srchIdExist= $("#searchHeader").length;
        if(srchIdExist){
                $("#searchHeader").css('visibility','hidden');
        }
  $('.srpoverlay_2').css('height',vhgt);  

}
function setTextAreaHgt(){
  $("#comm_footerMsg").addClass('posfix btmo'); 

}

function bindSlider(){
 /*   var child=$(".detailedProfileRedirect");
    child.unbind("click");
    child.bind("click",function(){
    var countKey=$(this).attr('countkey');
    var params=$(this).attr('params');
    var index= parseInt($(this).attr('index'))+1;
    window.location.href='/profile/viewprofile.php?'+'&total_rec='+countArray[countKey]+'&actual_offset='+index+'&'+params;
    });
   */ 
    var child=$(".eoiAcceptBtn");
    child.unbind("click");
    child.bind("click",function(){
        $(".eoiAcceptBtn").attr("disabled",true);
        $(".eoiDeclineBtn").attr("disabled",true);
        
        var input=$(this).children(".inputProChecksum");
        params["profilechecksum"] =input.val();
        params["actionName"] ="ACCEPT_MYJS";
        performAction(params["actionName"], params, $(this).attr("index"),false);
    
    });
    
    child=$(".eoiDeclineBtn");
    child.unbind("click");
    child.bind("click",function(){
        $(this).unbind("click");
        var input=$(this).children(".inputProChecksum");
        params["profilechecksum"] =input.val();
        params["actionName"] ="DECLINE_MYJS";        
        performAction(params["actionName"], params,  $(this).attr("index"),false);
    
    });
    
    child=$(".matchAlertBtn");
    child.unbind("click");
    child.bind("click",function(){
        $(this).unbind("click");
        var input=$(this).children(".inputProChecksum");
params["profilechecksum"] =input.val();
        params["actionName"] ="INITIATE_MYJS";
        params["fromJSMS_MYJS"] ="1";
        performAction(params["actionName"], params,  $(this).attr("index"),false);
    
    });
    
    child=$(".matchOfDayBtn");
    child.unbind("click");
    child.bind("click",function(){
        $(this).unbind("click");
        var input=$(this).children(".inputProChecksum");
params["profilechecksum"] =input.val();
        params["actionName"] ="INITIATE_MYJS";
        params["fromJSMS_MYJS"] ="1";
        params['fromJSMS_MOD'] = "1";
        performAction(params["actionName"], params,  $(this).attr("index"),false);
    
    });
    
    
    }   
    
function bindPrimeButtonClick(index)
{
	if(disablePrimary[index]==false)
	{
    	$( "#Prime_"+index).bind( "click", function(){
          params["actionName"] =$("#primeAction"+index).val();
       		if(params["actionName"]=="PHOTO_UPLOAD")
				window.location = actionUrl[params["actionName"]];
      else  if(params["actionName"]=="IGNORE")
      {
          if(!profile_index[index] || !profile_index[index]['IGNORE']) {
            if(!profile_index[index]) 
              profile_index[index] = [];
            var paramstr = $("#tracking"+index).val();
            profile_index[index]['IGNORE'] = paramstr.split('=')[1];
          }
           

          var paramsArr = {
            blockArr: {
              profilechecksum : $("#buttonInput"+index).val(),
              action          : profile_index[index]['IGNORE']
            }
          };
          disableOthers[index] = false;
          performAction("IGNORE", paramsArr, index,true,1);
          return false;
      }
			else{
				params["profilechecksum"] =$("#buttonInput"+index).val();
				//$("#Prime_"+index).unbind( "click");
				performAction(params["actionName"], params, index,true,1);
                                
			}
		});
    $( "#Prime_"+index+"_1").bind( "click", function(){
          params["actionName"] =$("#primeAction"+index+"_1").val();
          //console.log(params["actionName"]);
          if(params["actionName"]=="PHOTO_UPLOAD")
        window.location = actionUrl[params["actionName"]];
      else{
        params["profilechecksum"] =$("#buttonInput"+index+"_1").val();
        //$("#Prime_"+index).unbind( "click");
        performAction(params["actionName"], params, index,true,1);
      }
      
    });
    return false;
	}
}
function bindActions(index, action, enableButton, buttonDetailsOthers)
{	
  if(enableButton==null)
    enableButton=false;
  if(enableButton!=false)
	{

		if(action=="REPORT_ABUSE"){
		$('#'+action+"_"+index).bind("click",function(){
				
			reportAbuse(index);
			
			});
		return;
		}

		if($.inArray(action,["SHORTLIST","IGNORE"])<0)
		{
      
        
				$('#'+action+"_"+index).bind("click",function(){
					params["profilechecksum"] =$("#buttonInput"+index).val();
					params["actionName"] = actionDetail[action];
					$('#'+action+"_"+index).unbind( "click");
					performAction(action, params, index,false,1);
					return false;
				});
		}
		else
		{
			if(action =="SHORTLIST")
			{
				$('[id^="SHORTLIST"]').bind("click",function(){
					if(!profile_index[index] || !profile_index[index]['SHORTLIST']) {
						if(!profile_index[index]) 
							profile_index[index] = [];
						var paramstr = buttonDetailsOthers[iButton['SHORTLIST']].params;
						profile_index[index]['SHORTLIST'] = paramstr.split('=')[1];
					}
					params["profilechecksum"] =$("#buttonInput"+index).val();
					params['shortlist'] = profile_index[index]['SHORTLIST'];
					performAction("SHORTLIST", params, index,false);
					return false;
				})
			}
			if(action=="IGNORE")
			{
				$('[id^="IGNORE"]').bind("click",function(){
					if(!profile_index[index] || !profile_index[index]['IGNORE']) {
						if(!profile_index[index]) 
							profile_index[index] = [];
						var paramstr = buttonDetailsOthers[iButton['IGNORE']].params;
						profile_index[index]['IGNORE'] = paramstr.split('=')[1];
					}
					 

          var paramsArr = {
            blockArr: {
              profilechecksum : $("#buttonInput"+index).val(),
              action          : profile_index[index]['IGNORE']
            }
          };
          performAction("IGNORE", paramsArr, index,false);
          return false;
        })
      }
    }
  }
  else{
    $('#'+action+"_"+index).unbind( "click");
    $('#'+action+"_"+index).addClass( "opa50");
  } 
}

function performAction(action, tempParams, index,isPrime,fromButton)
{
  /* GA tracking action on contactEngine */
  if((typeof action != "undefined")&&(typeof actionDetail[action] != "undefined")){
    GAMapper("GA_CONTACT_ENGINE", {"actionDetail": actionDetail[action]});
  }else{
    /* default case when action not found */
    GAMapper("GA_CONTACT_ENGINE");
  }
  

	if((writeMessageAction=="INITIATE" || writeMessageAction=="REMINDER")&&action=="MESSAGE"){
		aUrl="/api/v1/contacts/MessageHandle";
		tempParams['actionName']            ="MessageHandle";
	}
	else{
		aUrl = actionUrl[action];
	}
	if(postParams)
	{
		if(postParams.substring(0,1)=="&")
			aUrl+="?r=1"+postParams;
		else
			aUrl+="?r=1&"+postParams;
	}
        else{
                aUrl+="?r=1";
        };
        aUrl += "&"+$("#tracking"+index).val();
    if(typeof(contactEngineChannel)!= "undefined")
        aUrl +="&pageSource="+contactEngineChannel;
  postParams='';
     dim='';
     if(action=='WRITE_MESSAGE'){
         paramsForMsgWindow=tempParams;
         indexForMsgWindow=index;
         tempParams['pagination']=1;
         if((typeof fromButton !='undefined')  && fromButton==1) 
         {
             msgWindowMSGID='';
             msgWindowCHATID='';
             msgWindowOn=0;
             
         }
         else msgWindowOn=1; 
         tempParams['MSGID']=msgWindowMSGID;tempParams['CHATID']=msgWindowCHATID;}
     if ((action=="ACCEPT_MYJS")||(action=="DECLINE_MYJS")) {
         tempParams["responseTracking"]=responseTrackingno;
         
    $("#eoituple_"+index+" .contactLoader").css("display","block");
    
}
    else if(action=="INITIATE_MYJS")
    {
                tempParams["fromJSMS_MYJS"]='1';
                tempParams["stype"]='WMM';
        if(tempParams["fromJSMS_MOD"] == 1)
        { 
          tempParams["stype"]='WMOD';
          $("#matchOfDaytuple_"+index+" .contactLoader").css("display","block");
        }
        else
        {
          $("#matchAlerttuple_"+index+" .contactLoader").css("display","block");
        }
        
    }
      else
	{
		loaderTop();
		if(isPrime && action!="MESSAGE" && action!="WRITE_MESSAGE" )
		{
			dim = getDim();
			//scrollOff();
			$("#loaderOverlay").show();
		}
		stopTouchEvents();
                $(window).scrollTop('0px');
                $("#contactLoader").show();
  }
    
   
    $.ajax({
            
    url: aUrl,
    type: "POST",
    data: tempParams,
    //crossDomain: true,
    success: function(result){
                    if ((action=="ACCEPT_MYJS")||(action=="DECLINE_MYJS") || (action=="INITIATE_MYJS"))
                    {
                    var tempSection = (action=="INITIATE_MYJS") ? (tempParams["fromJSMS_MOD"] ? 'matchOfDay' : 'matchAlert') : 'eoi';
                    $("#"+tempSection+"tuple_"+index+" .contactLoader").hide();
                    
                    }
		else
		{
			if(isPrime && action!="MESSAGE")
			{
				$("#loaderOverlay").hide();
				if(dim!='')
					$("#mainContent").css({"height":dim['hgt']});
			}
			$("#contactLoader").hide();
			startTouchEvents();
		}
                    if(CommonErrorHandling(result,'?regMsg=Y')) //CE means contact engine
      {                     
                            
                            if ((action=="ACCEPT_MYJS")||(action=="DECLINE_MYJS")||(action=="INITIATE_MYJS")) afterActionMyjs(index, action, tempParams); 
                            else afterAction(result,action,index,isPrime);
      }
    }
  });
//params = {};
}




function afterActionMyjs(index,action,Params){
    
    var section= (action=='INITIATE_MYJS') ? (Params["fromJSMS_MOD"] ? 'matchOfDay' : 'matchAlert') : 'eoi';
        $("#"+section+"tuple_"+index+" #contactLoader").hide();
        $("#"+section+"tuple_"+index).fadeOut(1500);
        var x=parseInt($("#"+section+"_count").html());
        x--;
        $("#"+section+"_count").html(x);
        setTimeout(function(){
        $("#"+section+"tuple_"+index).remove();
        if ($("#"+section+"_count").html()=='0')
        { 
            $("#"+section+"Absent").show();
            if(section=='matchAlert')$("#matchAlertAbsentText").text('No more profiles for today');
            if(section=='matchOfDay')
            {
              $("#matchOfDayPresent").hide();
            }

        }
        $(".eoiAcceptBtn").attr("disabled",false);
        $(".eoiDeclineBtn").attr("disabled",false);
        tempTupleObject = (section=='matchOfDay') ? tupleObject3 : (section=='matchAlert' ? tupleObject2 : tupleObject);
        tempTupleObject._tupleIndex--;
        tempTupleObject.indexFix();
        tempTupleObject._goTo(tempTupleObject._index);          
    },1500);
                
}


function afterAction(result,action, index,isPrime){
	$("#selIndexId").val(index);
	if($("#mainContent").length){
		if(action!='MESSAGE')
			scrollOff();
	}
        $("#ce_photo").attr("src", photo[index]);
        $("#profilePhoto").attr("src", photo[index]);
    if(window.location.hash.length===0 && ((typeof SPA_CE=='undefined') || (SPA_CE!='Y') || result.actiondetails.writemsgbutton) )
        historyStoreObj.push(browserBackCommonOverlay,"#pushce");
    var ignoreFromPrime = (action=="IGNORE" && isPrime==true) ? true : false
    if(ignoreFromPrime)
    {
        result.button_after_action.buttons = result.button_after_action.buttons.others;
        result.buttondetails.button = result.buttondetails.buttons.primary[0];

    }
    if($.inArray(action,["MESSAGE","WRITE_MESSAGE","SHORTLIST","IGNORE","CONTACT_DETAIL"])<0 || ignoreFromPrime)
	{
		if(typeof result.actiondetails !='undefined' && result.actiondetails.errmsglabel!=null)
		{
			hideForHide();
      var headerLabel = result.actiondetails.headerlabel;
      var underscreen = headerLabel.match(/Under Screen/g);
      if(!hideOverlay)
      {
       showCommonOverlay();
        $("#errorMsgOverlay").show();
      $("#errorMsgHead").html(result.actiondetails.errmsglabel);
      if(underscreen!=''&& underscreen!=null)
      {
        disablePrimeButton(action,index);
        $("#primeButton_"+index).html(result.buttondetails.button.label);
        if(result.buttondetails.button.label == "Interest Saved")
        {
          $("#"+index+'_3Dots').hide();
          hideOverlay = 1;
        }
      }
      if(result.actiondetails.footerbutton!=null)
      {
        bindFooterButtons(result);
      }
      else {
          $("#closeLayer").show();
      }
      }
      else
      {
        //showCommonOverlay();
        //$("#errorMsgOverlay").show();
        if(underscreen!=''&& underscreen!=null)
        {
          disablePrimeButton(action,index);
          $("#primeButton_"+index).html(result.buttondetails.button.label);
          if(result.buttondetails.button.label == "Interest Saved")
          {
            $("#"+index+'_3Dots').hide();
          }
         // $("#closeLayer").show();
        }
        $("#contactLoader").hide();
        var current_index       =$("#selIndexId").val();
    setTimeout(function(){$("#primeButton_"+current_index).attr("tabindex",-1).css('outline',0).focus();}, 0);
  scrollOn();

      }
			return;
		}
		else{
			$("#topMsg2,#topMsg").hide();
			//$("#topMsg").hide();
			$( "#"+index+"_3Dots" ).unbind( "click");
			bind3DotClick(index,result.button_after_action);
			$( "#commonOverlay").hide();
			if(result.buttondetails.button.enable==false)
      {
        /*if(buttonNumber==1)
				  disablePrimeButton(action,index);
        else
        {
          //var buttonDecline = '<div id="buttons1"><div class="fullwid srp_bg1" id="PrimeColor_1" style="display:block; position:relative;"><div class="txtc pad18 ">/           <div class="posrel">              <div id="primeWid_1" style="width: 60%; border: 1px;">            <a tupleno="id1" href="#" id="Prime_1" class="fontlig f15 color7 dispbl" style="text-decoration:none;" disabled="" onclick="return false;"><i class="mainsp msg_srp" id="PrimeIcon_1" style="display:none;"></i><div></div><span id="primeButton_1">They declined bbb interest</span><input type="hidden" id="buttonInput1" name="otherProfileChecksum" value="074e1a0d2f123319c2247aba58fff024i8580596"><input type="hidden" id="primeAction1" name="primeAction" value="DEFAULT"><input type="hidden" id="tracking1" name="contactTracking" value="undefined"></a></div><div class="posabs srp_pos2"></div></div></div></div></div>'
          */
          if($("#PrimeColor_"+index).hasClass("wid50p"))
        {
           $("#PrimeColor_"+index).remove();
          $("#PrimeColor_"+index+"_1").removeClass("wid50p").addClass("wid100p");
          $("#primeButton_"+index+"_1").css('color', '');
          $("#buttonInput"+index+"_1").attr('id',"buttonInput"+index);
          $("#primeButton_"+index+"_1").attr('id',"primeButton_"+index);
          $("#PrimeColor_"+index+"_1").attr('id',"PrimeColor_"+index);
          $("#PrimeIcon_"+index+"_1").attr('id',"PrimeIcon_"+index);
          $("#Prime_"+index+"_1").attr('id',"Prime_"+index);
          $("#Prime_"+index).attr('tupleno',"id"+index);
          $("#primeAction"+index+"_1").attr('id',"primeAction"+index);
                    $( "#primeWid_"+index+"_1").after( '<div class="posabs srp_pos2"><a tupleNo="id'+index+'" href="#" id="'+index+'_3Dots"><i class="mainsp srp_pinkdots"></i></a></div>' );
         $("#primeWid_"+index+"_1").attr('id',"primeWid_"+index);
          $( "#"+index+"_3Dots" ).unbind( "click");
          bind3DotClick(index,result.button_after_action);
          //index=index+"_1";
        }
        disablePrimeButton(action,index);
      }
			else
			{
        if($("#PrimeColor_"+index).hasClass("wid50p"))
        {
          $("#PrimeColor_"+index+"_1").remove();
          $("#PrimeColor_"+index).removeClass("wid50p").addClass("wid100p");
          $( "#primeWid_"+index ).after( '<div class="posabs srp_pos2"><a tupleNo="id'+index+'" href="#" id="'+index+'_3Dots"><i class="mainsp threedot1"></i></a></div>' );
          $( "#"+index+"_3Dots" ).unbind( "click");
          bind3DotClick(index,result.button_after_action);
        }
				enablePrimeButton(result.buttondetails.button.action,index);
				$("#PrimeIcon_"+index).removeClass().show().addClass(cssMap[result.buttondetails.button.iconid]);
			}
			$("#primeButton_"+index).html(result.buttondetails.button.label);
		}
	}
 // console.log(action);
	if(action=="WRITE_MESSAGE")
		enablePrimeButton("WRITE_MESSAGE",index);
   
	switch(action){
	case "INITIATE":
		initiateContact(result,action, index);
		break;
	case "CANCEL":
		cancelInterest(result,action, index);
		break;
        case "ACCEPT":
                acceptInterest(result,action, index);
                break;
        case "DECLINE":
                declineInterest(result,action, index);
                break;
        case "REMINDER":
                sendReminder(result,action, index);
                break;
        case "MESSAGE":
                sendMessage(result,action, index);
                break;
        case "WRITE_MESSAGE":
                writeMessage(result,action, index);
                break;
	case "CONTACT_DETAIL":
		$( "#buttonsOverlay").hide();
		contactDetail(result,action, index);
		break;
        case "SHORTLIST":
                shortlist(result,action,index);
                break;
        case "IGNORE":
                ignore(result,action,index);
                break;
        }
}

function bindFooterButtons(result){
	$("#footerButton").html(result.actiondetails.footerbutton.label).show().bind( "click", {
	  action: result.actiondetails.footerbutton.action
	}, function( event ) {
	historyStoreObj.push(browserBackCommonOverlay,"#pushcf");
		window.location=actionUrl[event.data.action];
		return false;
	});
	if(result.actiondetails.footerbutton.action=="MEMBERSHIP")
		$("#neverMindLayer").show();
  else if(result.actiondetails.footerbutton.action=="MEMBERSHIP")
  {
      $( "#footerButton" ).bind( "click", function(){
      
        contactDetailMessage(result);
        $("#closeLayer").show();
      });
  }
	else
	    $("#closeLayer").show();
}

function bindFooterButtonswithId(result, id){
  $("#"+id).html(result.actiondetails.footerbutton.newlabel).show().bind( "click", {
    action: result.actiondetails.footerbutton.action
  }, function( event ) {
  historyStoreObj.push(browserBackCommonOverlay,"#pushcf");
    window.location=actionUrl[event.data.action];
    return false;
  });
  // if(result.actiondetails.footerbutton.action=="MEMBERSHIP")
  if(result.actiondetails.footerbutton.action=="MEMBERSHIP")
  {
      $("#skipLayer").show();
  }
}


function acceptInterest(result,action,index)
{
        /*if(result.buttondetails.infobtnaction=="MEMBERSHIP"){
    hideForHide();
    showCommonOverlay();
                var confirmOverlayId =actionTemplate[action];
                $("#"+confirmOverlayId).show();
                $("#confirmMessage0").show();
                $("#confirmMessage1").show();
                $("#confirmMessage0").html("Accept confirmation msg 1");
                $("#confirmMessage1").html("Accept confirmation msg 2");
          $("#closeLayer").show();
        }
  else
  {*/
    //console.log("aacc");
    hideForHide();
    layerClose();
  //}
}

function cancelInterest(result,action, index){
  hideForHide();
        var confirmLabelHead    =result.buttondetails.confirmLabelHead,confirmLabelMsg     =result.buttondetails.confirmLabelMsg;
        showCommonOverlay();

    $("#closeLayer").show();
        if(confirmLabelHead){
                var confirmOverlayId =actionTemplate[action];
    $("#"+confirmOverlayId).show();
    $("#confirmMessage0,#confirmMessage1").show();
    //$("#confirmMessage1").show();   
                $("#confirmMessage0").html(confirmLabelHead);
                $("#confirmMessage1").html(confirmLabelMsg);
        }
  /*else{
    // condition for error message
                var errorMsgLabel =result.actiondetails.errmsglabel;
                $("#errorMsgOverlay").show();
                $("#errorMsgHead").html(errorMsgLabel);
        }*/
}

function declineInterest(result,action, index){
        var confirmLabelHead    =result.buttondetails.confirmLabelHead,confirmLabelMsg     =result.buttondetails.confirmLabelMsg;
  hideForHide();
  showCommonOverlay();
  scrollOff();

        if(confirmLabelHead){
                var confirmOverlayId =actionTemplate[action];
    $("#"+confirmOverlayId).show();
    $("#confirmMessage0,#confirmMessage1").show();
    //$("#confirmMessage1").show();
                $("#confirmMessage0").html(confirmLabelHead);
                $("#confirmMessage1").html(confirmLabelMsg);
        }/*else{
                // condition for error message
                var errorMsgLabel =result.actiondetails.errmsglabel;
                $("#errorMsgOverlay").show();
                $("#errorMsgHead").html(errorMsgLabel);
  }*/
    $("#closeLayer").show();
   
   
    var address_url=window.location.href;
   if(address_url.indexOf("?") >= 0){
		var hash,pageSource='', hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
		for(var i = 0; i < hashes.length; i++)
		{
			hash = hashes[i].split('=');
			if(hash[0]=="contact_id")
				pageSource=hash[1];
		}
		if(pageSource!='' && (pageSource.indexOf("INTEREST_RECEIVED") >= 0 ||pageSource.indexOf("FILTERED_INTEREST") >= 0))
				handleSwipe('swipeleft','left','','','');
	}
}


function writeMessage(result, action, index){
    var msgWindowHeight;
    msgWindowMSGID=typeof result['MSGID'] !='undefined' ? result['MSGID'] : '' ;
    msgWindowCHATID=typeof result['CHATID'] !='undefined' ? result['CHATID'] : '' ;
    $("#msgId").unbind('scroll');
    if(result.hasNext==true)
    {
        $("#msgId").scroll(function()
        {
            

            if($(this).scrollTop()==0)
            {
            if(MsgWindowLoading)return;
            MsgWindowLoading=1;
            var tempParams=paramsForMsgWindow, tempIndex=indexForMsgWindow;
                performAction("WRITE_MESSAGE", tempParams, tempIndex,false,0);
            }
        });
    }
  if ($("#lastMsgId_"+index).length) $("#lastMsgId_"+index).css("font-weight",'300').css('font-family','Roboto');
  $("#writeMessageTxtId").val('');
  $("#writeMsgDisplayId").html("");
  $("#updateMsgId").val(index);
  bgSetting();
  $("#commonOverlay").hide();
  var actionSel,writeMessageOverlayId =actionTemplate[action];
  if(result.button)
     actionSel =result.button['action'];

  $("#"+writeMessageOverlayId).show();
  if(result.cansend=='true'){
      
    var toggleSet,toggleChk;
    $("#presetMessageId").show();
    var messageObjArr   =result.messages, htmlArr   =new Array(), msgLoopStr ="<div class='fontlig f16 white com_pad1' id='presetMessageDispId'><span id='presetMessageTxtId'></span><span class='dispbl f12 color1 pt5 white' id='presetMessageStatusId'></span></div>";
        $("#comm_footerMem").hide();
          $("#comm_footerMsg").show();
    
 //   $("#presetMessageId").html(msgLoopStr);
    setTextAreaHgt();
    if(messageObjArr){
      $.each(messageObjArr, function(objVal,objLabel){ 
         var tempDiv=$('<div>').append(msgLoopStr);
        var message   =nl2br(objLabel.message);
        var timeTxt =objLabel.timeTxt;      
        var myMsg =objLabel.mymessage;  
        if(myMsg=='true'){
          tempDiv.find("#presetMessageDispId").addClass('txtr com_pad_l').removeClass('txtl_10p'); 
          tempDiv.find("#presetMessageStatusId").addClass('txtr com_pad_l');
          toggleSet =true;
        }
        else{
          tempDiv.find("#presetMessageDispId").removeClass('txtr com_pad_l').addClass('txtl_10p');
          tempDiv.find("#presetMessageStatusId").removeClass('txtr com_pad_l');
          toggleSet =false;
        }
        if((toggleChk!=toggleSet) && tempStr)
          tempDiv.find("#presetMessageDispId").addClass('brdr4');
        toggleChk =toggleSet;

        tempDiv.find("#presetMessageTxtId").html(message);
        tempDiv.find("#presetMessageStatusId").html(timeTxt);
        var tempStr =tempDiv.html(); 
        htmlArr.push(tempStr);
      });
      var messageStr=htmlArr.join(""),JObject=$("#presetMessageId").eq(0),JObject2=$("#msgId").eq(0);
      if(msgWindowOn==0)JObject.html('');
      msgWindowHeight=JObject2.prop('scrollHeight');
      JObject.prepend(messageStr);
      JObject2.scrollTop(JObject2.prop('scrollHeight')-msgWindowHeight);
      $('#setMsgHght').css('height','45px');
    }
    else{
      $("#presetMessageDispId").addClass('padl30_contact');
      $("#presetMessageTxtId").html('Start the conversation by writing a message.');
    }
    setWindowParams(result.label, result.viewed, index);
  }
        else  //if(actionSel=='MEMBERSHIP' || actionSel=='CALL')
  { 

    $("#presetMessageId").hide();
    $("#comm_footerMsg").hide();
    if(result.button && result.button.text)
      $("#CEmembershipMessage2").text(result.button.text).show();
  else
      $("#CEmembershipMessage2").hide(); 
    $("#freeMsgId").css('top',$(window).height()/2+'px').show();
    $("#comm_footerMem").show();
    $("#memTxtId").show().html(result.button.label);
    setWindowParams(result.label, result.viewed, index);
        }
  setTimeout(function(){$("#setMsgHght").attr("tabindex",-1).css('outline',0).focus();}, 0);
  //$("#writeMessageTxtId").focus();
MsgWindowLoading=0;
}
function sendReminder(result, action, index){
  bgSetting();
        hideForHide();
  scrollOff();
        var writeMessageOverlayId =actionTemplate[action];

        if(result.actiondetails.writemsgbutton){
    writeMessageAction = "REMINDER";
    // paid profile condition
    showWriteMessageOverlay();
    $("#writeMessageTxtId").val('');
    if(result.actiondetails.lastsent)
      $("#writeMessageTxtId").val(result.actiondetails.lastsent);
    $("#presetMessageId").show();
    $("#writeMsgDisplayId").show().html('');
    $("#presetMessageTxtId").html('Reminder sent. You may send a personalized message with the reminder');
    $("#comm_footerMem").hide();
    $("#comm_footerMsg").show();
    setWindowParams(result.actiondetails.headerlabel, result.actiondetails.headerthumbnailurl.url, index);
    postParams +=result.actiondetails.writemsgbutton.params;
        }
        /*else if(result.actiondetails.errmsglabel){
    // condition for error message
                var errorMsgLabel =result.actiondetails.errmsglabel;
                $("#commonOverlay").show();
                $("#errorMsgOverlay").show();
                $("#errorMsgHead").html(errorMsgLabel);
        }*/
        else{
    // free profile condition
    // Status in srp and view profile page - Reminder 1/2 Sent(disabled), Reminder 2/2 Sent(disabled).  
    hideForHide();
    layerClose();
        }
}
function showWriteMessageOverlay()
{
  $("#writeMessageOverlay").show();
}
function sendMessage(result, action, index){
  var oldHtml='';
        hideForHide();
                
  $("#writeMessageOverlay").show(); 
  //$("#comm_footerMsg").removeClass('posfix btmo');
        if(result.responseMessage=='Successful'){
                var msgContent  =nl2br($("#writeMessageTxtId").val());
    $("#presetMessageId,#writeMsgDisplayId").show();
           // $("#writeMsgDisplayId").show();
    $("#writeMessageTxtId").val('');

    var htmlStr =$("<div class='txtr com_pad_l fontlig f16 white com_pad1'><div class='com_pad2 clearfix fl dispibl writeMsgDisplayTxtId' style='width:100%' ></div><div class='dispbl f12 color1 pt5 white txtr msgStatusTxt'  id='msgStatusTxt'></div></div><div style='height:1px'></div>");
    //var oldHtml =$("#writeMsgDisplayId").html();
    //oldHtml =oldHtml.replace(/Message Sent/g,'today');  
    
                if(result.isSent=='true' || result.isSent=='false'){
                                
      htmlStr.find(".writeMsgDisplayTxtId").html(msgContent);
                                  $("#writeMsgDisplayId").append(htmlStr);      
                                              $(".msgStatusTxt").html('Recently');  
                                              $("#timeTextId_"+index).text('Recently');
                                                $(".msgStatusTxt").last().html('Message Sent');               
      //var newHtml =$("#writeMsgDisplayId").html();
      //if(writeMessageAction=='REMINDER' || writeMessageAction=='INITIATE')
    //    $("#writeMsgDisplayId").html(newHtml);
    //  else
    //    $("#writeMsgDisplayId").html(oldHtml+newHtml);

      if(writeMessageAction=='REMINDER' || writeMessageAction=='INITIATE'){
        $("#parentFootId,#crossButId").show();
        $("#comm_footerMsg").hide();
        //$("#crossButId").show();
        
      }
      else{
                                $("#parentFootId,#comm_footerMsg").show();
        //$("#comm_footerMsg").show();  
                                $("#crossButId").hide();
      }
               }
               
               var temp=$("#updateMsgId").val();
               $("#lastMsgId_"+temp).text(msgContent);
               /*else{
                        $("#writeMsgDisplayTxtId").html(msgContent);
      $("#errIcon_con").show();   
      $("#msgPendingTxt").html('Failed to send message');
               }*/
        }
  writeMessageAction = false;
  setTimeout(function(){$("#setMsgHght").attr("tabindex",-1).css('outline',0).focus();}, 0);
}
function updateReminder(resultId){
        var msgContent  =$("#"+resultId).val();
	msgContent = msgContent.replace(/<script>/g, "");
	msgContent = msgContent.replace(/<\/script>/g, "");
	$("#"+resultId).val(msgContent);
        var action      ='MESSAGE';
        var index       =$("#selIndexId").val();
  index = $("#selIndexId").val();
        if(msgContent.trim()){
    // success condition to send write message - ajax request
    params['actionName']            =actionDetail[action];
                params["profilechecksum"]       =$("#buttonInput"+index).val();
                params['draft']                 =msgContent;
                performAction(action, params, index,false);
        }
        else{
    // No failure handling required
        }
}
function disablePrimeButton(action,index)
{
  if(action==null)
    action='';
  $("#Prime_"+index).addClass("fontlig f15 color7").css("text-decoration", "none").removeClass("cursp white f13").attr('disabled', true).unbind('click');
  $( "#Prime_"+index).bind( "click", function(){return false;});
  $("#PrimeColor_"+index).removeClass("bg7").addClass("srp_bg1");
  if(window.location.href.search("SimilarProfiles")!=-1)
    $("#PrimeColor_"+index).children().removeClass("pad5new").addClass("pad5new");
  else
    $("#PrimeColor_"+index).children().removeClass("pad5new").addClass("pad18");
  $("#"+index+"_3Dots").children().removeClass("threedot1").addClass("srp_pinkdots");
  $("#PrimeIcon_"+index).hide();
    var profilechecksum=$("#buttonInput"+index).val();
    if(window.location.href.search("viewprofile")!=-1){
  }
    else if(viewSimilar==1){
    }
    else{
      var interestCheck=$("#primeButton_"+index).html();
      if(interestCheck=="Send Interest" && 0){
       var index=$("#selIndexId").val();
      canIShowNext("",profilechecksum);
      viewSimilarLayer(index,profilechecksum);
    }
  }
}
function enablePrimeButton(action,index)
{
	if(action==null)
		action='';
	$("#Prime_"+index).removeClass("fontlig f15 color7").addClass("cursp white f13").attr('disabled', false).unbind("click");
	$("#primeAction"+index).val(action);
	disablePrimary[index]=false;
	bindPrimeButtonClick(index);
	$("#PrimeColor_"+index).addClass("bg7").removeClass("srp_bg1");
	$("#PrimeColor_"+index).children().addClass("pad5new").removeClass("pad18");
	$("#"+index+"_3Dots").children().addClass("threedot1").removeClass("srp_pinkdots");
	$("#PrimeIcon_"+index).show();
}
function initiateContact(result,action, index){

  if(result.actiondetails.writemsgbutton)
  {
    bgSetting();
    postParams += result.actiondetails.writemsgbutton.params;
    writeMessageAction = "INITIATE";
    showWriteMessageOverlay();
    $("#writeMessageTxtId").val('');
    if(result.actiondetails.lastsent)
      $("#writeMessageTxtId").val(result.actiondetails.lastsent);
    $("#presetMessageId,#comm_footerMsg").show();
    $("#presetMessageTxtId").html('Interest sent. You may send a personalized message with the interest.');
    $("#writeMsgDisplayId").html('');
    $("#comm_footerMem").hide();
    //$("#comm_footerMsg").show();
    setWindowParams(result.actiondetails.headerlabel,result.actiondetails.headerthumbnailurl.url,index);
  }
  else
  {
    hideForHide();
    layerClose();
  }
  $('#'+action+"_"+index).unbind( "click");
  $('#Prime_'+index).unbind( "click");
}


function contactDetailMessage(result,action,index)
{
 $("#mobile").hide();
  aUrl = actionUrl[action];
  aUrl+="?r=1&stype=WQ"+result.actiondetails.footerbutton.params;
  $("#contactLoader").show();
  $.ajax({
            
    url: aUrl,
    type: "POST",
    data: params,
    //crossDomain: true,
    success: function(response){
      $("#contactLoader,#footerButton,#ViewContactPreLayer,#ViewContactPreLayerNoNumber,#neverMindLayer,#footerButtonNew,#skipLayer").hide();
      //$("#footerButton").hide();
      //$("#ViewContactPreLayer").hide();
      //$("#ViewContactPreLayerNoNumber").hide();
     // $("#neverMindLayer").hide();
      $("#closeLayer").show();
      popBrowserStack();
      contactDetail(response,action, index);
    }
  });
}

function contactDetail(result,action, index){

    $("#topMsg2,topMsg").hide();
  //$("#topMsg").hide();
  $("#"+actionTemplate[action]).show();
  /*if(result.footerbutton)
  {
    contactDetailMessage(result,action, index);
    return;
  }*/var proCheck = params.profilechecksum;
    if(result.actiondetails.errmsglabel)
    {
    showCommonOverlay();
    $("#buttonsOverlay").hide();
    $("#topMsg2").html(result.actiondetails.errmsglabel);
    $("#topMsg2, #mobile, #mobileValBlur, #landline, #landlineValBlur").show();
    if(result.actiondetails.footerbutton && result.actiondetails.footerbutton.text)$("#membershipMessageCE").text(result.actiondetails.footerbutton.text).show();else $("#membershipMessageCE").hide(); 
    }
else
{

    if(result.actiondetails.membershipOfferMsg)$("#membershipMessageCE").text(result.actiondetails.membershipOfferMsg).show();else $("#membershipMessageCE").hide(); 
    if(result.actiondetails.contactdetailmsg){
    $("#topMsg2").html(result.actiondetails.contactdetailmsg).show();
  }
  else
    $("#topMsg2").hide();
    
  if(result.actiondetails.topmsg)
  {
    $("#topMsg2").html(result.actiondetails.contactdetailmsg).show();
  }
  else
    $("#topMsg").hide();
  if(result.actiondetails.bottommsg){
    $("#bottomMsg").html(result.actiondetails.bottommsg);
    if(result.actiondetails.bottommsgurl) {
      $("#bottomMsg").bind('click',function () {window.location.replace(result.actiondetails.bottommsgurl);});
    } else $("#bottomMsg").unbind('click');
    
    $("#bottomMsg").css('display', 'inline-block');
  
  }


if(result.actiondetails.bottommsg2){
    $("#bottomMsg2").html(result.actiondetails.bottommsg2).css('display', 'inline-block');
  }

  if(result.actiondetails.contact1){
    $("#mobileVal,#mobileValBlur").hide();
    //$("#mobileValBlur").hide();
    $("#mobile").show();
    if(result.actiondetails.contact1.value=="blur"){ $("#mobileValBlur").show(); $("#neverMindLayer").show(); }
    else $("#mobileVal").show();
                $("#mobileVal").html(result.actiondetails.contact1.value+'<span  onclick="reportInvalid(\'M\',this,\''+proCheck+'\')" class="reportInvalidjsmsButton invalidMob " style = "color:#d9475c"> Report Invalid </span>');
                if (result.actiondetails.contact1.iconid){ 
                   $("#mobileIcon > a").attr('href','tel:'+result.actiondetails.contact1.value.toString());
            $("#mobileIcon").show();
        }
  }
      else if(result.actiondetails.contact1_message)
      {
        $("#mobileVal,#mobile").show();
        $("#mobileVal").html(result.actiondetails.contact1_message);
      }  
        if(result.actiondetails.contact2){
    $("#landlineValBlur,#landlineVal").hide();
            // $("#landlineVal").hide();
                $("#landline").show();
                if(result.actiondetails.contact2.value=="blur") { $("#landlineValBlur").show(); $("#neverMindLayer").show();}
                else $("#landlineVal").show();$("#landlineVal").html(result.actiondetails.contact2.value+'<span onclick="reportInvalid(\'L\',this,\''+proCheck+'\')" class="reportInvalidjsmsButton invalidMob " style = "color:#d9475c">Report Invalid </span>');
                if (result.actiondetails.contact2.iconid){ 
                   $("#landlineIcon > a").attr('href','tel:'+result.actiondetails.contact2.value.toString());
            $("#landlineIcon").show();
        }
    }
      else if(result.actiondetails.contact2_message)
      {
        $("#landline,#landlineVal").show();
        $("#landlineVal").html(result.actiondetails.contact2_message);
      }  
        if(result.actiondetails.contact3){
    $("#alternateValBlur,#alternateVal").hide();
               // $("#alternateVal").hide();
                $("#alternate").show();
                if(result.actiondetails.contact3.value=="blur"){ $("#alternateValBlur").show(); $("#neverMindLayer").show(); }
                    else $("#alternateVal").show();$("#alternateVal").html(result.actiondetails.contact3.value);
        if (result.actiondetails.contact3.iconid){ 
                   $("#alterIcon > a").attr('href','tel:'+result.actiondetails.contact3.value.toString());
            $("#alterIcon").show();
        }
    
    
    }
      else if(result.actiondetails.contact3_message)
      {
        $("#alternate,#alternateVal").show();
        $("#alternateVal").html(result.actiondetails.contact3_message);
      }  
        if(result.actiondetails.contact4){
                $("#emailVal,#emailValBlur").hide();
    //$("#emailValBlur").hide();
                $("#email").show();
                if(result.actiondetails.contact4.value=="blur") { $("#emailValBlur").show(); $("#neverMindLayer").show();}
                else $("#emailVal").show();$("#emailVal").html(result.actiondetails.contact4.value);
                if (result.actiondetails.contact4.iconid) {
             $("#msgIcon > a").attr('href','mailto:'+result.actiondetails.contact4.value.toString());        
            $("#msgIcon").show();
                }
        }
         if(result.actiondetails.contact9){
    $("#relationshipManageVal").hide();
               // $("#alternateVal").hide();
                $("#relationshipManager").show();
                 $("#relationshipManagerVal").show();$("#relationshipManagerVal").html(result.actiondetails.contact9.value);
        if (result.actiondetails.contact9.iconid){ 
                   $("#relationshipManagerIcon > a").attr('href','tel:'+result.actiondetails.contact9.value.toString());
            $("#relationshipManagerIcon").show();
        }
    
    
    }
    }
    
    
    
        if(result.actiondetails.footerbutton!=null){
		$("#footerButton").html(result.actiondetails.footerbutton.label);
		$("#footerButton").show();
    $("#mobile").hide();
    if(result.actiondetails.infomsglabel)
    {  
      $("#ViewContactPreLayerText").html(result.actiondetails.infomsglabel);
      $("#ViewContactPreLayer").show();
    }
    if(result.actiondetails.newerrmsglabel)
    {
      $("#commonOverlay").hide();
      $("#newErrMsg").html(result.actiondetails.newerrmsglabel);
      $("#membershipheading").html(result.actiondetails.membershipmsgheading);
      $("#subheading1").html(result.actiondetails.membershipmsg.subheading1);
      $("#subheading2").html(result.actiondetails.membershipmsg.subheading2);
      $("#subheading3").html(result.actiondetails.membershipmsg.subheading3);
      
      if(typeof(result.actiondetails.offer) != "undefined" && result.actiondetails.offer != null)
      {
        $("#MembershipOfferExists").show();
        $("#membershipOfferMsg1").html(result.actiondetails.offer.membershipOfferMsg1.toUpperCase());
        $("#membershipOfferMsg2").html(result.actiondetails.offer.membershipOfferMsg2);
        if(typeof(result.actiondetails.strikedprice) != "undefined" && result.actiondetails.strikedprice != null)
        {
          $("#oldPrice").html(result.actiondetails.strikedprice);
          $("#oldPrice").show();
        }
        $("#currency").html(result.actiondetails.membershipoffercurrency);
        $("#newPrice").html(result.actiondetails.discountedprice);
        $("#LowestOffer").show();
      }
      else if(typeof(result.actiondetails.lowestoffer) != "undefined" && result.actiondetails.lowestoffer != null)
      {
        $("#LowestOffer").html(result.actiondetails.lowestoffer);
        $("#LowestOffer").addClass("mt60");
        $("#LowestOffer").show();
      }

      bindFooterButtonswithId(result,'footerButtonNew');
      $("#membershipOverlay").show();

    }
    else if(result.actiondetails.errmsglabel)
    {
      $("#topMsg2,#landline").hide();
      //$("#landline").hide();
      //$("#ViewContactPreLayerTextNoNumber").html("You will be able to see the Email Id of "+result.actiondetails.headerlabel+ "but not the phone number. This is because "+result.actiondetails.headerlabel+"'s has chosen to hide phone number.");
     $("#ViewContactPreLayerTextNoNumber").html(result.actiondetails.errmsglabel);
      $("#ViewContactPreLayerNoNumber").show();
    }

    
    if(result.actiondetails.footerbutton.action=="CONTACT_DETAIL")
    {
      $("#neverMindLayer").show();
      $( "#footerButton" ).bind( "click", function(){
      
        contactDetailMessage(result,action,index);
        
      });
    }
    else
		bindFooterButtons(result);
	}
        else {
            $("#closeLayer").show();
        }
       
        $("#contactDetailOverlay").height($("#bottomElement").offset().top-$("#contactDetailOverlay").offset().top);
        
}

function shortlist(result,action,index){
        if(profile_index[index]['SHORTLIST'] == 'true') {
                $("#otherimage"+iButton['SHORTLIST']).removeClass("shortlisted").addClass("srtlist");
                $("#otherlabel"+iButton['SHORTLIST']).text("Shortlist");
                profile_index[index]['SHORTLIST'] = 'false';
        }
        else {
                $("#otherimage"+iButton['SHORTLIST']).removeClass("srtlist").addClass("shortlisted");
                $("#otherlabel"+iButton['SHORTLIST']).text("Shortlisted");
                profile_index[index]['SHORTLIST'] = 'true';
        }
}

function ignore(result,action,index){
        if(profile_index[index]['IGNORE'] == 1) {       // block request
                $("#buttonsOverlay,#topMsg2,#topMsg").hide();
    //$("#topMsg2").hide();
   // $("#topMsg").hide();
                $("#confirmationOverlay").show();
                $("#confirmMessage0").show().text("This profile has been moved to your Blocked Members & will no longer appear in your search results");
                //
//
//disablePrimeButton(action,index);
                //$("#primeButton_"+index).html("Ignored");
                $("#bottomMsg").show().text("Unblock").addClass("lh50").removeClass("pb20").click(function(){
      profile_index[index]['IGNORE'] = 0;
      var params = {
        blockArr: {
          profilechecksum : $("#buttonInput"+index).val(),
          action          : profile_index[index]['IGNORE']
        }
      };
                        performAction("IGNORE", params, index,false);
                        $("#confirmMessage0,#footerButton").hide();
                        //$("#footerButton").hide();
                });
                $("#footerButton").show().text("Close").click(function(){
      $("#commonOverlay").hide();
      hideForHide();
      layerClose();
    });
        }
        else{      // unblock request
    $("#bottomMsg").addClass("pb20").removeClass("lh50").hide().text();
                $("#otherlabel"+iButton['IGNORE']).text("Block");
    $("#commonOverlay").hide();
    hideForHide();
    layerClose();
        }
    profile_index[index]['IGNORE'] = (profile_index[index]['IGNORE']==1)?"0":"1";
        
        
}
function showCommonOverlay()
{
        dim = getDim();
        $("#commonOverlay").css( "height", dim['hgt'] ).css( "width", dim['wid'] ).show();
  setTimeout(function(){$("#commonOverlay").attr("tabindex",-1).css('outline',0).focus();}, 0);
}
function open3DotLayer(buttonDetails,index)
{
  bgSetting();
  if(buttonDetails.photo.url)
    photo[index]=buttonDetails.photo.url;
  $("#ce_photo").attr("src", photo[index]);
  $("#selIndexId").val(index);
  resetLayerButtons(); 
  current_index  = index;
  getButtonIndex(buttonDetails);
  showCommonOverlay();
  $("#topMsg").show().html(buttonDetails.topmsg);
  //$("#topMsg2").show();
  $("#topMsg2").html(buttonDetails.topmsg2);
  var child=$("#buttonsOverlay > .forHide");
        var i=0;
        for(buttonId in buttonDetails.buttons)
	{    
    if(buttonDetails.buttons[buttonId].secondary=="true")
    {
          	if(profile_index[index] && profile_index[index]['SHORTLIST'] && buttonDetails.buttons[buttonId].action == 'SHORTLIST') {
  			var iconidd = (profile_index[index]['SHORTLIST']=='true') ? '004':'003';
  			child.eq(i).children("#otherlabel"+i).html(profile_index[index]['SHORTLIST']=='true'?'Shortlisted':'Shortlist');
  			child.eq(i).children("i").attr("class",cssMap[iconidd]);
  		}
          	else if(profile_index[index] && profile_index[index]['IGNORE'] && buttonDetails.buttons[buttonId].action == 'IGNORE') {
  			var labell = (profile_index[index]['IGNORE'] == 0) ? 'Unblock':'Block';
  			child.eq(i).children("#otherlabel"+i).html(labell);
  			child.eq(i).children("i").attr("class",cssMap[buttonDetails.buttons[buttonId].iconid]);
  		}
  		else {
  			child.eq(i).children("#otherlabel"+i).html(buttonDetails.buttons[buttonId].label);
  			child.eq(i).children("i").attr("class",cssMap[buttonDetails.buttons[buttonId].iconid]);
  		}
  	       	child.eq(i).attr("id",buttonDetails.buttons[buttonId].action+"_"+index);
  		child.eq(i).show();        
  		bindActions(index, buttonDetails.buttons[buttonId].action,buttonDetails.buttons[buttonId].enable,buttonDetails.buttons);
  		i++;
    }
        }
        $( "#buttonsOverlay").show();
  historyStoreObj.push(browserBackCommonOverlay,"#pushbb");

  // for overlay - viewprofile changes
  scrollOff(); 
        
}

function buttonStructure(profileNoId, jsmsButtons, profilechecksum,page)
{
	var primeButtonLabel = {}, primeButtonAction = {}, primeButtonEnable = {}, buttonCount=jsmsButtons.buttons.length, primeButtonParams = {},buttonNumber=0;
	if(page==null)
		page="search";
  
  for (var i = 0; i < buttonCount; i++) {
    if(jsmsButtons.buttons[i].primary=="true"){
    primeButtonLabel[buttonNumber] = jsmsButtons.buttons[i].label;
    primeButtonAction[buttonNumber] = jsmsButtons.buttons[i].action;
    primeButtonParams[buttonNumber] = jsmsButtons.buttons[i].params;
    primeButtonEnable[buttonNumber] = jsmsButtons.buttons[i].enable;
    buttonNumber++;
  }
}

	

	var enableOther = true;
	if(jsmsButtons.buttons[1]==null)
		enableOther = false;
	if(primeButtonAction==null)
		primeButtonAction='';
	var enablePrime = true;
	if(primeButtonAction[0]=="DEFAULT")
		enablePrime = false;
	if(enablePrime)
	{
		var button ='<div class="fullwid ';
		if(page=="viewSimilar"){
			 button+='brdrsp1 ';
                         var tempButtonParams = primeButtonParams[0] ? primeButtonParams[0] :""; 
		 	 if(typeof stypeKey!='undefined')
				primeButtonParams[0]=tempButtonParams + "&stype="+stypeKey;
		
		}
    if(buttonNumber==1)
    {
      
		 button+='bg7" id="PrimeColor_'+profileNoId+'">\
					<div class="txtc ';
		if(page=="viewSimilar"){
			 button+='pad5new ';
		}else{
			 button+='pad5new ';
		}
		 button+='"><div class="posrel"><div id="primeWid_'+profileNoId+'"><a tupleNo="id'+profileNoId+'" href="javascript:void(0);" id="Prime_'+profileNoId+'" class="fontlig f13 white cursp dispbl">';
		if(page!="viewSimilar"){
				button+='<i class="'+cssMap[jsmsButtons.buttons[0].iconid]+'" id="PrimeIcon_'+profileNoId+'"></i>';
		}


		button+='<div></div>'+
				'<span id="primeButton_'+profileNoId+'">'+primeButtonLabel[0]+'</span><input type="hidden" id="buttonInput'+profileNoId+'" name="otherProfileChecksum" value="'+profilechecksum+'"/><input type="hidden" id="primeAction'+profileNoId+'" name="primeAction" value="'+primeButtonAction[0]+'"/><input type="hidden" id="tracking'+profileNoId+'" name="contactTracking" value="'+primeButtonParams[0]+'"/></a></div>';

		if(page!="viewSimilar"){

				button+='<div class="posabs srp_pos2">'+
								'<a tupleNo="id'+profileNoId+'" href="#" id="'+profileNoId+'_3Dots">\
									<i class="mainsp threedot1"></i>\
								</a>\
							</div>';
		}
			button+='</div></div></div>';
        }
    else
    {

      button+='<div id="buttons_'+profileNoId+'"><div class="wid50p bg7" id="PrimeColor_'+profileNoId+'" style="display: inline-block;border-right: 1px solid white;"><div class="txtc pad5new "><div class="posrel"><div id="primeWid_'+profileNoId+'" style="width: 60%; border: 1px;"><a tupleno="id'+profileNoId+'" href="javascript:void(0);" id="Prime_'+profileNoId+'" class="fontlig f13 white cursp dispbl"><i style="height:20px" class="'+cssMap[jsmsButtons.buttons[0].iconid]+'" id="PrimeIcon_'+profileNoId+'"></i><div></div><span id="primeButton_'+profileNoId+'">'+primeButtonLabel[0]+'</span><input type="hidden" id="buttonInput'+profileNoId+'" name="otherProfileChecksum" value="'+profilechecksum+'"><input type="hidden" id="primeAction'+profileNoId+'" name="primeAction" value="'+primeButtonAction[0]+'"><input type="hidden" id="tracking'+profileNoId+'" name="contactTracking" value="'+primeButtonParams[0]+'"></a></div></div></div></div><div class="wid50p bg7" id="PrimeColor_'+profileNoId+'_1" style="display: inline-block;"><div class="txtc pad5new "><div class="posrel"><div id="primeWid_'+profileNoId+'_1" style="width: 60%; border: 1px;"><a tupleno="id'+profileNoId+'_1" href="#" id="Prime_'+profileNoId+'_1" class="fontlig f13 whitecursp dispbl"><i class="'+cssMap[jsmsButtons.buttons[1].iconid]+'" id="PrimeIcon_'+profileNoId+'_1"></i><div></div><span style="color:white" id="primeButton_'+profileNoId+'_1">'+primeButtonLabel[1]+'</span><input type="hidden" id="buttonInput'+profileNoId+'_1" name="otherProfileChecksum" value="'+profilechecksum+'"><input type="hidden" id="primeAction'+profileNoId+'_1" name="primeAction" value="'+primeButtonAction[1]+'"><input type="hidden" id="tracking'+profileNoId+'_1" name="contactTracking" value="'+primeButtonParams[1]+'"></a></div></div></div></div></div>';
      //button+='"><div class="fullwid bg7 clearfix" id="buttons_'+profileNoId+'"><div class="wid49p dispibl txtc" id="PrimeColor_'+profileNoId+'"><div id="primeWid_'+profileNoId+'"><a class="dispbl" style="width:115px"  tupleno="id'+profileNoId+'" href="#" id="Prime_'+profileNoId+'"><i class="'+cssMap[jsmsButtons.buttons[0].iconid]+'" id="PrimeIcon_'+profileNoId+'"></i><div></div><span style="color:white" id="primeButton_'+profileNoId+'">'+primeButtonLabel[0]+'</span><input type="hidden" id="buttonInput'+profileNoId+'" name="otherProfileChecksum" value="'+profilechecksum+'"><input type="hidden" id="primeAction'+profileNoId+'" name="primeAction" value="'+primeButtonAction[0]+'"><input type="hidden" id="tracking'+profileNoId+'" name="contactTracking" value="'+primeButtonParams[0]+'"></a></div></div><div class="wid49p dispibl txtc ot_hgt1" id="PrimeColor_'+profileNoId+'_1" style="border-left:1px solid #fff;padding:12px 0 10px"><div id="primeWid_'+profileNoId+'"><a tupleno="id'+profileNoId+'" href="#" id="Prime_'+profileNoId+'"><i class="'+cssMap[jsmsButtons.buttons[1].iconid]+'" id="PrimeIcon_'+profileNoId+'_1"></i><div></div><span style="color:white" id="primeButton_'+profileNoId+'_1">'+primeButtonLabel[1]+'</span><input type="hidden" id="buttonInput'+profileNoId+'_1" name="otherProfileChecksum" value="'+profilechecksum+'"><input type="hidden" id="primeAction'+profileNoId+'_1" name="primeAction" value="'+primeButtonAction[1]+'"><input type="hidden" id="tracking'+profileNoId+'_1" name="contactTracking" value="'+primeButtonParams[1]+'"></a></div></div></div>';
    }
			disablePrimary[profileNoId]=false;
	}
	else
	{
		var button ='<div class="fullwid ';
		if(page=="viewSimilar"){
			 button+='brdrsp1 ';
                         var tempButtonParams = primeButtonParams[0] ? primeButtonParams[0] :""; 
			 if(typeof stypeKey!='undefined')
				primeButtonParams[0]=tempButtonParams+"&stype="+stypeKey;
		}
		 button+='srp_bg1" id="PrimeColor_'+profileNoId+'"  style="display:block; position:relative;"><div class="txtc ';
		if(page=="viewSimilar"){
			 button+='pad5new ';
		}else{
			 button+='pad18 ';
		}
		 button+='"><div class="posrel"><div id="primeWid_'+profileNoId+'"><a tupleNo="id'+profileNoId+'" href="#" id="Prime_'+profileNoId+'" class="fontlig f15 color7 dispbl" style="text-decoration:none;" disabled onClick="return false;">';
		if(page!="viewSimilar"){
				button+='<i class="mainsp msg_srp" id="PrimeIcon_'+profileNoId+'" style="display:none;"></i>';
		}

		button+='<div></div><span id="primeButton_'+profileNoId+'">'+primeButtonLabel[0]+'</span><input type="hidden" id="buttonInput'+profileNoId+'" name="otherProfileChecksum" value="'+profilechecksum+'"/><input type="hidden" id="primeAction'+profileNoId+'" name="primeAction" value="'+primeButtonAction[0]+'"/><input type="hidden" id="tracking'+profileNoId+'" name="contactTracking" value="'+primeButtonParams[0]+'"/></a></div>';
		if(page!="viewSimilar"){
				button+='<div class="posabs srp_pos2"><a tupleNo="id'+profileNoId+'" href="#" id="'+profileNoId+'_3Dots"><i class="mainsp srp_pinkdots"></i></a></div>';
		}
			button+='</div></div></div>';
			disablePrimary[profileNoId]=true;
	}
	if(jsmsButtons.buttons[1]==null)
		disableOthers[profileNoId]=true;
	else
		disableOthers[profileNoId]=false;
        return button;
}
function bind3DotClick(index,buttonDetails){
if(buttonDetails.photo)
	photo[index]=buttonDetails.photo.url;
if(disableOthers[index]==false)
{
  $("#primeWid_"+index).css({"width":"60%","border":"1px"});
  $( "#"+index+"_3Dots" ).bind( "click", {
    buttonDetails: buttonDetails,
    buttonIndex: index
  }, function( event ) {
    open3DotLayer(event.data.buttonDetails, event.data.buttonIndex);
    return false;
  });
  $("#"+index+'_3Dots').show();
}
else
{
  $("#primeWid_"+index).css({"width":"80%","border":"1px"});
  $("#"+index+'_3Dots').hide();
}
return false;
}

function getButtonIndex(buttonDetails)
{
	for(buttonId in buttonDetails.buttons)
	{
    
    if(buttonDetails.buttons[buttonId].secondary=="true")
    {
      //console.log(buttonId);
		  iButton[buttonDetails.buttons[buttonId].action] = buttonId;
    }
	}
}
function resetLayerButtons()
{
	$('[id^="confirmMessage"]').hide();
	$('#errorMsgOverlay').hide();
	$('[id^="INITIATE"]').unbind("click").removeClass( "opa50");;
	$('[id^="CANCEL"]').unbind("click").removeClass( "opa50");
	$('[id^="SHORTLIST"]').unbind("click").removeClass( "opa50");
	$('[id^="DECLINE"]').unbind("click").removeClass( "opa50");
	$('[id^="REMINDER"]').unbind("click").removeClass( "opa50");
	$('[id^="MESSAGE"]').unbind("click").removeClass( "opa50");
	$('[id^="ACCEPT"]').unbind("click").removeClass( "opa50");
	$('[id^="WRITE_MESSAGE"]').unbind("click").removeClass( "opa50");
	$('[id^="IGNORE"]').unbind("click").removeClass( "opa50");
	$('[id^="CONTACTDETAIL"]').unbind("click").removeClass( "opa50");
	$('#footerButton').unbind("click");
	writeMessageButton = false;
}
  
function browserBackCommonOverlay() {
  if($("#commonOverlay").is(':visible') || $("#writeMessageOverlay").is(':visible') || $("#membershipOverlay").is(':visible')) {
    hideForHide();
    layerClose();
    return true;
  } else {
    return false;
  }
}
function nl2br(str)
{
    return str.replace(/\n/g, "<br />");
}

function hideReportInvalid(){
  var mainEle=$("#reportInvalidContainer");
  if(mainEle.css('display')!='none'){
    $("#commonOverlayTop").show();
    mainEle.hide();
  return true;  
  }
  
  return false;

}

function reportInvalid(phoneType,Obj,profileCheckSum) {
var imgURL;
if(typeof(buttonSt) != "undefined" && buttonSt.photo.url){
imgURL = buttonSt.photo.url;
}
else{
 var topDiv = $(Obj).closest('#commonOverlayTop');  
 var nextLevel = topDiv.find("#3DotProPic");
 var lastLevel = nextLevel.find("#photoIDDiv");
     imgURL = lastLevel.find("#ce_photo").attr('src');

}
$("#photoReportInvalid").attr("src",imgURL);
$('.RAcorrectImg,#commonOverlayTop').hide();
var mainEle=$("#reportInvalidContainer");
mainEle.show();

var el=$("#reportInvalidMidDiv");
el.height($(window).height()-$("#reportInvalidSubmit").height()-mainEle.find('.photoheader').eq(0).height());

var div = document.createElement('div');
            // css transition properties
            var props = ['WebkitPerspective', 'MozPerspective', 'OPerspective', 'msPerspective'];
            // test for each property
            for (var i in props) {
                if (div.style[props[i]] !== undefined) {
                    
                    cssPrefix = props[i].replace('Perspective', '').toLowerCase();
                    animProp = '-' + cssPrefix + '-transform';
                }
            }


el.css(animProp, 'translate(50%,0px)');
el.css('-' + cssPrefix + '-transition-duration', 600 + 'ms')
.css(animProp, 'translate(0px,0px)');

selectedReportInvalid="";
RAOtherReasons=0;
var rCode ;

$(".reportInvalidOption").unbind().bind('click',function () {

if($(this).attr('id')=='js-otherInvalidReasons')
{
el.scrollTop('0px');
el.css('-' + cssPrefix + '-transition-duration', 600 + 'ms')
.css(animProp, 'translate(-50%,0px)');
RAOtherReasons=1;selectedReportInvalid="";
rCode = '5';
}
else 
{
  selectedReportInvalid=$(this).text();RAOtherReasons=0;
  rCode = $(this).val();
}
 
;

$('.RAcorrectImg').hide();
$(this).find('.RAcorrectImg').show();
});


$("#reportInvalidSubmit").unbind().bind('click',function() {

var reason="";

if(RAOtherReasons)
{ 
  reason=$("#js-otherInvalidReasonsLayer").val();
  if(!reason){ShowTopDownError(["Please enter the reason"],3000);return;}
}
else {
  reason=selectedReportInvalid;
if(!reason){ShowTopDownError(["Please select the reason"],3000);return;}
}



reason=$.trim(reason);
//Phone type for phone api
if (phoneType=='L') {var mobile='N';var phone='Y';}
if (phoneType=='M') {var mobile='Y';var phone='N';}

var otherReason = '';
if(rCode == '5')
{
  otherReason = reason;
}

ajaxData={'mobile':mobile,'phone':phone,'profilechecksum':profileCheckSum,'reasonCode':rCode,'otherReasonValue':otherReason};
var url='/phone/reportInvalid';
loaderTop();
$("#contactLoader,#loaderOverlay").show();
$("#loaderOverlay").show();
//ajax data for phone api
$.ajax({
                
    url: url,
    type: "POST",
    data: ajaxData,
    //crossDomain: true,
    success: function(result){
         $("#contactLoader,#loaderOverlay,#reportInvalidContainer").hide();
         $("#js-otherInvalidReasonsLayer").val('');
                    if(result.responseStatusCode=='0'||result.responseStatusCode=='1'||CommonErrorHandling(result,'?regMsg=Y')) 
                    { 
          ShowTopDownError([result.message],5000);
          $("#commonOverlayTop").show();
                    }
}

});
});

historyStoreObj.push(hideReportInvalid,"#reportInvalid");


}

var arrReportAbuseFiles = [];
var bUploadAttachmentInProgress = false;
var bUploadingDone = false;
/**
 * 
 */
function attachAbuseDocument(event) {

    var dom = $("<input>",{id:"file", type:"file", accept : ".jpg,.bmp,.jpeg,.gif,.png", multiple:"multiple"});
    var MAX_FILE_SIZE_IN_MB = 6;
    
    var onCrossClick = function() {
        var result = [];
        var self = $(this);
        for(var itr = 0; itr < arrReportAbuseFiles.length; itr++) {
            if(arrReportAbuseFiles[itr].myId == self.attr('id')) {
                
                //If file is already uploaded then remove from server also
                if( "undefined" != typeof arrReportAbuseFiles.tempAttachmentId && arrReportAbuseFiles[itr].uploaded ) {
                    
                    var formData = new FormData();                    
                    var apiUrl = "/api/v1/faq/abuseDeleteAttachment"; 
                    
                    formData.append('feed[attachment_id]', arrReportAbuseFiles['tempAttachmentId'] );
                    formData.append('feed[file_name]', arrReportAbuseFiles[itr].name );
                    setTimeout(function(){
                        $("#contactLoader,#loaderOverlay").show();
                    },0);
                    
                    $.ajax({
                        url     : apiUrl,
                        method  : 'POST',
                        data    : formData,
                        async   : true,
                        cache: false,
                        processData: false,
                        success : function ( response ) {
                                        $("#contactLoader,#loaderOverlay").hide();
                                        if(response.responseStatusCode == 0) {
                                           self.parent().remove();
                                        } else {
                                            result.push(arrReportAbuseFiles[itr]);
                                            ShowTopDownError(['Something went wrong. Please try again.'], 2000);
                                        }
                                    },
                        error   :  function ( response ) {
                                       $("#contactLoader,#loaderOverlay").hide();
                                       result.push(arrReportAbuseFiles[itr]);
                                       ShowTopDownError(['Something went wrong. Please try again.'], 2000);
                                       return ;
                                    },
                    });
                }else {
                    self.parent().remove();
                }
                
                continue;
            }
            
            result.push(arrReportAbuseFiles[itr]);
        }
        if(arrReportAbuseFiles.tempAttachmentId) {
            result.tempAttachmentId = arrReportAbuseFiles.tempAttachmentId;
        }
        arrReportAbuseFiles = result;
    }
    
    
    /**
     * 
     */
    var createPhotoPreview = function(fileObject) {
        /**
         *  <div class="photoEach txtc pad3">
                <i class="reportIcon closeIcon crossPosition"></i>
                <img width="80%" height="100px" src="<IMG PATH>" />
                <div class="f12 white mt5">
                image_name.jpg
                </div>
            </div>
         */
        var previewDom = $("<div />", {"class" : "photoEach txtc pad3"});
        var closeIcon = $("<i />", {"class" : "reportIcon closeIcon crossPosition", "id" : fileObject.myId});
        closeIcon.on('click',onCrossClick);
        
        previewDom.append(closeIcon);
        
        var imgDom = $("<img />", {"width" : "80%", "height" : "100px"});
        previewDom.append(imgDom);
        previewDom.append( $( "<div />", {"class" : "f12 white mt5"} ).html(fileObject.name) );
        
        var reader = new FileReader();
        reader.onload = (function(imgDom) { return function(e) { imgDom[0].src = e.target.result; }; })(imgDom);
        reader.readAsDataURL(fileObject);
        
        $("#photoDiv").append(previewDom);
        return previewDom;
    }
    
    /**
     * 
     */
    var onFileChange = function(event) {
        var existingLength = arrReportAbuseFiles.length;
   
        var validFileTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/bmp'];
        
        //loop on files .. do basic checks like size, type
        $.each( this.files, function( key, file ) {
            
            if( ( file.size / 1048576 ).toFixed(1) > MAX_FILE_SIZE_IN_MB ) {
                ShowTopDownError([file.name + ' You can attach a proof less than 6 MB in size'], 2000);
                return ;
            }
            
            if( validFileTypes.indexOf(file.type) == -1 ) {
                ShowTopDownError([file.name + ' Invalid type of attachment'], 2000);
                return ;
            }
            
            if( arrReportAbuseFiles.length >= 5 ) {
                ShowTopDownError(['You can attach maximum 5 proofs'], 2000);
                return ;
            }
          
            arrReportAbuseFiles.push(file);
        });
        
        if(arrReportAbuseFiles.length == 0) {
            ShowTopDownError(['No valid attachments'], 2000);
            return ;
        }
        
        var iterator = 1;
        arrReportAbuseFiles.forEach( function (file) { 
            if(file.hasOwnProperty('preview') === false) {
                file.myId = iterator;
                createPhotoPreview(file);
            }
            file.preview = true;
            iterator++;
        });
    }
    
    dom.on('change',onFileChange);
    dom.trigger("click");
    
}

/**
 * 
 */

function uploadAttachment()
{   
   
    /**
     * 
     */
    
    var SendAjax = function(fileObject, temp_attachment_id) {
        var apiUrl = "/api/v1/faq/abuseAttachment";
        var formData = new FormData();
        formData.append("feed[attachment_1]", fileObject);
        
        if( ( ( typeof temp_attachment_id == "string" && temp_attachment_id.length ) || typeof temp_attachment_id == "number" ) &&
              isNaN( temp_attachment_id ) == false
                ) {
            formData.append("feed[attachment_id]", temp_attachment_id);
        }
        
        return $.ajax({
            url     : apiUrl,
            method  : 'POST',
            data    : formData,
            async   : true,
            cache: false,
            processData: false,
            contentType: false,
            beforeSend:function(){
                bUploadAttachmentInProgress = true;
            },
              complete:function(){
                bUploadAttachmentInProgress = false;
            },
            success : function ( response ) {
                            if(response.responseStatusCode == 0) {
                               if(file.hasOwnProperty('error')) {
                                   delete file.error;
                               }
                               arrReportAbuseFiles['tempAttachmentId'] = response.attachment_id;
                               fileObject.uploaded = true;
                            } else {
                                fileObject.error = true
                                ShowTopDownError( [ response.message ], 2000 );                                
                            }
                        },
            error   :  function ( response ) {
                            $("#contactLoader,#loaderOverlay").hide();
                            fileObject.error = true;
                            ShowTopDownError( [ "Something went wrong. Please try again" ], 2000 );
                        },
        });
    }
    
    if(0 == arrReportAbuseFiles.length) {
        return true;
    }

    if(bUploadAttachmentInProgress == true) {
        setTimeout(function(){uploadAttachment()},20); return false;
    }
    var len = arrReportAbuseFiles.length ;
    for(var itr =0 ; itr < len; itr++) {
        file = arrReportAbuseFiles[itr];
        if( file.hasOwnProperty("uploaded") == false || file.uploaded == false  ) {
                if(( file.hasOwnProperty('error') && file.error == true )) {
                    setTimeout(function(){
                        $("#contactLoader,#loaderOverlay").hide();
                    },0);
                    return false;
                 }
                var tempId = (typeof arrReportAbuseFiles['tempAttachmentId'] == "undefined") ? "" : arrReportAbuseFiles['tempAttachmentId'] ;
                SendAjax( file, tempId );
                setTimeout(function(){uploadAttachment()},20);return false;
            }
    }
    for(var itr =0 ; itr < len; itr++) {
        file = arrReportAbuseFiles[itr];
        if(file.hasOwnProperty("uploaded") == false || file.uploaded == false) {
            return false;
        }
    }
    if(false == bUploadingDone) {
        bUploadingDone = true;
        $("#reportAbuseSubmit").trigger('click');
    }
    return true;
}