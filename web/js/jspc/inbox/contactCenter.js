/**
* This file is contactCenter.js for loading contact center tuples
*/

var emailReg = /([\w\_]+@([\w]+\.)+[\w]{2,4})/g;
var phoneReg = /([0-9]{10,}|[0-9]{3}-[0-9]{6,}|[0-9]{4}-[0-9]{6,}|[0-9]{4}-[0-9]{3}-[0-9]{4,}|[0-9]{3}-[0-9]{3}-[0-9]{4,}|[0-9]{3}\s[0-9]{3}\s[0-9]{4,}|[0-9]{4}\s[0-9]{3}\s[0-9]{4,}|[0-9]{3}\s[0-9]{3}\s[0-9]{3}\s[0-9]{3,}|[0-9]{4}\s[0-9]{6,}|[0-9]{3}\s[0-9]{7,})/g;
var matchedStr,data,newData;

/** 
* This function will set horizontal line below active horizontal tab
* 
* @param : thisElementID(line id),HorizontalTabID
*/
function setActiveHorizontalLine(thisElementID,HorizontalTabInfoID) {
	var HorizontalTabID = $("#HorizontalTab"+HorizontalTabInfoID).attr("data-id");
	var left = parseInt(HorizontalTabID) * 200;
	$(thisElementID).css("left",left+"px");
}

/** 
* This function will display cc listings by sending ajax request to inbox api v2
* 
* @param : thisElement(active horizontal tab)
*/
function performCCListingAction(thisElement)
{
	$("#HorizontalTab"+activeHorizontalTabInfoID).removeClass("jsButton-disabled");
	$(thisElement).addClass("jsButton-disabled");

	var chosenHorizontalInfoID = $(thisElement).attr("data-infoId");
	activeHorizontalTabInfoID = chosenHorizontalInfoID;
	setActiveHorizontalLine("#horizontalActiveLine",chosenHorizontalInfoID);

	//update url and send ajax request
	var postParams = "infoTypeId="+chosenHorizontalInfoID;
	var infoArr = {};
	infoArr["action"] = "stayOnPage";
	clearTimedOutVar = setTimeout(function(){sendProcessCCRequest(postParams,infoArr) },20);
}
/**
* This function sets the heading for inbox listing page
* @param response Page Response
**/
function setcontactCenterHeading(response){
        if(typeof response.subheading !="undefined" && response.subheading != ""){
                $(".ccSubHeader").html(response.subheading);
                $('.ccSubHeader').removeClass('disp-none');
        }else{
                $('.ccSubHeader').addClass('disp-none');
        }
}
/** 
* This function will load cc tuple data
* 
* @param : response
*/
function loadContactCenterTuples(response)
{
	var profileData = response.profiles,tuplesHtml = "";
	var tupleStructure = $(".js-ccTupleStructure").html();
	$("#ccTuplesMainDiv").html("");
	var viewProfilePageParams = new Array();
	//view profile page params
	viewProfilePageParams["tracking"] = response.tracking;
	viewProfilePageParams["self_profileid"] = response.self_profileid;
	viewProfilePageParams["flag"] = response.flag;
	viewProfilePageParams["contact"] = response.contact;
	viewProfilePageParams["self"] = response.ccself;
	viewProfilePageParams["totalCount"] = response.total;
	viewProfilePageParams["type"] = response.cctype;
    viewProfilePageParams["page"] = response.page;
    viewProfilePageParams["fromPage"] = response.frompage;
    viewProfilePageParams["ccnavigator"] = response.ccnavigator;
    viewProfilePageParams["contact_id"] = response.contact_id;
    viewProfilePageParams["searchid"] = response.searchid;
	$.each(profileData,function( index, val ){
		var profileIDNo = ((parseInt(response.page_index-1))*CC_RESULTS_PER_PAGE) +index + 1;		
		var mapObj = ccTupleResultMapping(val,profileIDNo,viewProfilePageParams);

		tuplesHtml+= $.ReplaceJsVars(tupleStructure,mapObj);
		
		contactEngineButtons=(new ContactEngineCard('CC')).buttonDisplay(val.buttonDetailsJSMS,val,val.intro_call_details);
        contactEngineButtons=contactEngineButtons ? contactEngineButtons : '';
        tuplesHtml=tuplesHtml.replace(/\{\{contactEngineBar\}\}/g,contactEngineButtons);
	});
	if(tuplesHtml){
		$("#ccTuplesMainDiv").append(tuplesHtml);
		tuplesHtml = '';
		
		var typeArray = new Array("{ccTupleImage}","{otherimage}","{myimage}");
		$('img[dsrc]').each(function() {
			var src = $(this).attr("dsrc");
			if($.inArray(src,typeArray)<0)
			{
				$(this).attr("src",src);
			}
		});
        

		cECommonBinding();
	}
	
	//handle visibilty of vsp link(show only for acceptance listings)
	handleVSPLinkVisibility();
}

//handle visibilty of vsp link(show only for acceptance listings)
function handleVSPLinkVisibility()
{
	$(".handleVspHide").remove();
}

/** 
* This function will show requests sub type listings(photo/horoscope) and upload option if profile does not have photo/horoscope
* 
* @param : response,uploadRequestParamArr
*/
function showRequestsSubTypeListings(response,uploadRequestParamArr)
{
	var mapObj="",requestHtml="",requestDivStructure = $("#basicUploadRequestDiv").html();
	$("#mainRequestUploadDiv").html("");
	
	if(activeVerticalTab=="1")
	{
		$("#js-requestTypeSelectListing").show();
		//show upload photo/horoscope option if Received horizontal tab is chosen and no of results on page is not 0
		if(response.no_of_results)
		{
			if((activeHorizontalTabInfoID=="9" && response.havephoto=="N")||(activeHorizontalTabInfoID=="18" && response.haveHoroscope=="N"))
			{
				
				mapObj = requestSubtypeMapping(uploadRequestParamArr,activeRequestTypeID);
				requestHtml = requestHtml+$.ReplaceJsVars(requestDivStructure,mapObj);
				if(requestHtml){
					$("#mainUploadRequestDiv").html(requestHtml);
					$("#mainUploadRequestDiv").show();
					requestHtml = '';
				}	
			}
			else
				$("#mainUploadRequestDiv").hide();
		}
		
		//show/hide request type(photo/horoscope) depending on profile religion
		handleRequestTypeVisibility(showRequestTypeList);
	}
	else
	{
		$("#js-requestTypeSelectListing").hide();
		$("#mainUploadRequestDiv").hide();
	}   
}

/** 
* This function will load cc page response
* 
* @param : response
*/
//var typeOfApi='';
//var responseAllInOneResponse;
function loadCCPageResponse(response)
{
	
if(typeof response.searchid!="undefined")
		lastCCSearchId = response.searchid;
	updateHistory("","",lastCCSearchId);
	
	//hide loader
	showCCLoader('hide');

	//upload photo/horoscope params
    uploadRequestParamArr = new Array();      
    if(response.requestMessage!=="undefined")
    {
    	uploadRequestParamArr["EditWhatNew"]=response.EditWhatNew;
    	uploadRequestParamArr["requestMessage"]=response.requestMessage;
    	uploadRequestParamArr["requestButton"]=response.requestButton;
    	uploadRequestParamArr["ccnavigator"]=response.ccnavigator;
    }
	//show horroscope/photo request option if requests tab is selected
	showRequestsSubTypeListings(response,uploadRequestParamArr);
	
       	//typeOfApi='';
	if(response.no_of_results!=0)
	{
		
		
		/** Commented by Reshu as no more required api is changed to pagination*/
		/** 
		* This section is for api which is not written in good manner.
		* to make consistent with existing apis, we are doing some checks in js end.
		* search code for this section using keyword "allInOneRespone"
		*/
		//if(response.page_index=="" || response.searchid=="16")
		//{
	////responseAllInOneResponse = response;
		////typeOfApi='allInOneResponse';
		
		//if(lastCurrentPage=="")
		//lastCurrentPage=1;
	//response.page_index = lastCurrentPage;
	//response.total = response.no_of_results;
	//response.next_avail = (response.total>lastCurrentPage*CC_RESULTS_PER_PAGE) ? "true" : "false";
	//var start = (lastCurrentPage-1)*CC_RESULTS_PER_PAGE
	//var end = lastCurrentPage*CC_RESULTS_PER_PAGE
	//response.profiles = response.profiles.slice(start,end);
	//response.no_of_results = response.profiles.length;
	//if(parseInt(response.page_index)==1)
	//{   
		//updateHistory("",1);
	//}
		//}
		
		/** Comment end */
		loadContactCenterTuples(response);
                setcontactCenterHeading(response);
		dataForCCPagination(parseInt(response.total),parseInt(response.page_index),parseInt(response.no_of_results));
		handleCCPagination(response);
		handleArchiveExpireInterest(activeHorizontalTabInfoID);
		/*
			If this is the interest received page and at the last page , do show horizontal tab.
		 */

		if(activeHorizontalTabInfoID == 1 && response.page_index == response.paginationArray[response.paginationArray.length - 1])
		{
			$('#HorizontalTab22_Label_nonzero').show();
			$('#HorizontalTab22_Label_nonzero').removeClass('jsButton-disabled');
		}

		if(response.total && response.hidePaginationCount != 1)
		{ 
			$(".js-resultsCount").remove();
			var countClass = "js-resultsCount";
			var resultsCountHtml = "<span class='js-resultsCount fontrel'>"+response.total+"</span>";
			$("#HorizontalTab"+activeHorizontalTabInfoID).append(resultsCountHtml);
		}
		$("#js-ccContainer").show();
		$("#ccPaginationDiv").show();
		$("#zeroResultSection").hide();
	}
	else
	{
		handleArchiveExpireInterest(activeHorizontalTabInfoID);
		/*
			If this is the interest received page, do show horizontal tab.
		 */
		if(activeHorizontalTabInfoID == 1)
		{
			$('#HorizontalTab22_Label_zero').show();
			$('#HorizontalTab22_Label_zero').removeClass('jsButton-disabled');
		}
		updateHistory("",1);
		/** handling zero results message */
		$("#HorizontalTab"+activeHorizontalTabInfoID+" .js-resultsCount").remove();
		var zeroResultsHeading = "<span class='bold f28'> 0</span> ",upgradeButtonText="View Membership Plans";
		$("#upgradeMembershipButton").html(upgradeButtonText);
		if(activeVerticalTab == "1")
		{
			zeroResultsHeading = $("#Request"+activeRequestTypeID).html() + " Requests " + $("#HorizontalTab"+activeHorizontalTabInfoID).html() + zeroResultsHeading;
			$("#upgradeMembershipButton").hide();
		}
		else if(activeHorizontalTabInfoID == "16" && response.paid=='N')
		{
			zeroResultsHeading = "Only paid members can view contacts";
			$("#upgradeMembershipButton").show();
		}
		else
		{
			//zeroResultsHeading = zeroResultsHeading + $("#HorizontalTab"+activeHorizontalTabInfoID).html();
			zeroResultsHeading = $("#HorizontalTab"+activeHorizontalTabInfoID).html() + zeroResultsHeading;
		 	$("#upgradeMembershipButton").hide();
		}
		//if(response.result_count)
		//{
		
	/*if(response.result_count.indexOf("0")!=-1)
	{
		response.result_count = response.result_count.replace(" 0","");
		response.result_count = response.result_count.replace("0","");
		response.result_count = "0 "+response.result_count;
	}*/
		
		//response.result_count = "0 "+response.title;
		//}
		//$("#zeroPageHeading").html(response.result_count);
		$("#zeroPageHeading").html(zeroResultsHeading);
		$("#zeroPageMsg").html(response.noresultmessage);
		$("#js-ccContainer").hide();
		$("#ccPaginationDiv").hide();
		$("#zeroResultSection").show();

	 
	}
	if ( activeHorizontalTabInfoID == 22 )
	{
		$(window).scrollTop(0);
	}
	if(typeof zmt_get_tag== "function")
	{
		renderBanners();
	}
  
  //RCB Communication 
  if (response.hasOwnProperty('display_rcb_comm') && response.display_rcb_comm &&
    typeof response.profiles != "undefined" && response.profiles != null) {
    var countOfProfiles = Object.keys(response.profiles).length;
    
    if(countOfProfiles >= 3){
      $("<div class='rel_c js-rcbMessage' id='callDiv1'><div class='ccp2 fontlig color11'><div class='mainBrdr clearfix'><div class='f14 fontlig wid60p inDisp fl'>To reach out to your accepted members, you may consider upgrading your membership. Would you like us to call you to explain the benefits of our membership plans?</div><div class='pt15 pb30 color2 f14 fr inDisp verTop'><span class='hlpcl1 calUserDiv cursp' id='callUser'>Yes, call me</span><span id='noButton' class='hlpcl11 cursp bg6 noUserDiv'>No, Later</span></div></div></div></div>").insertAfter("#outerCCTupleDiv3");
      
      //On Yes Call Now
      $("#callUser").off("click");
      $("#callUser").on("click", function () {
        $('<input>').attr({type: 'hidden',id:'rcbResponse', name: 'rcbResponse',value:'Y'}).appendTo('#Widget');
        toggleRequestCallBackOverlay(1, 'Accepted_Members_List');
        //$(".js-openRequestCallBack").click();
      });
      
      //On Not Now Button
      $("#noButton").off("click");
      $("#noButton").on("click", function () {
        
        var url = '/common/requestCallBack';
        $.ajax({
          type: "POST",
          url: url,
          cache: false,
          timeout: 5000, 
          data: {rcbResponse:'N','device':'desktop','channel':'JSPC','callbackSource':'Accepted_Members_List'},
          success:function(result){
            $("#callDiv1").remove();
            $("<div class='rel_c js-rcbMessage' id='callDiv2'><div class='ccp11 pb20 fontlig color11'><div class='mainBrdr2'><div class='f14 fontlig'>Never mind. You still can reach out to us later whenever you want. We will remind you about this after two weeks.</div></div></div></div>").insertAfter("#outerCCTupleDiv3");        
          },
          error:function(result){
            $("#callDiv1").hide();
            $("<div class='rel_c js-rcbMessage' id='callDiv2'><div class='ccp11 pb20 fontlig color11'><div class='mainBrdr2'><div class='f14 fontlig'>Something Went Wrong</div></div></div></div>").insertAfter("#outerCCTupleDiv3");                  setTimeout(function(){
              $('#callDiv2').remove();
              $("#callDiv1").show();
            },1000)
          }
        });
        
      });
				      
    }
  }
}

/**
 * Function is added to add link for expire interests listing.
 * @param  {int} activeHorizontalTabInfoID to check whether activeHorizontalTabInfoID is 22 and make changes accordingly.
 */
 function handleArchiveExpireInterest(activeHorizontalTabInfoID) {
 	if ( activeHorizontalTabInfoID == 23 )
 	{
 		$('#horizontalActiveLine22').remove();
 		$('#ccHorizontalTabsBar > li').hide();
 		if ( $('#HorizontalTab23').length == 0)
 		{
 			$('#ccHorizontalTabsBar').append('<li id="HorizontalTab23" data-id="23" data-infoId="23" class="js-ccHorizontalLists jsButton-disabled txtc cursp">Expiring Interests</li><li class="pos-abs bg5 cssline" style="bottom: 0px; height: 2px; left: 0px; display: list-item;" id="horizontalActiveLine23"></li>');
 		}
 		else
 		{
 			$('#HorizontalTab23').addClass('jsButton-disabled');
 			$('#horizontalActiveLine23').show();
 			$('#HorizontalTab23').show();
 		}
 	}
 	else
 	{
 		$('#horizontalActiveLine23').remove();
 		if ( $('#HorizontalTab22_Label_nonzero').length == 0)
 		{
 			$("#ccTuplesMainDiv").append('<div id="HorizontalTab22_Label_nonzero" onclick="performCCListingAction(this);" style="font-size: 90%;"  data-id="22" data-infoid="22" class="js-ccHorizontalLists txtc divcenter cursp color5 pt5 pl20 pb20 ">Archived Interests</div>');
 		}
 		if ( $('#HorizontalTab22_Label_zero').length == 0)
 		{
 			$("#zeroResultSection").append('<div id="HorizontalTab22_Label_zero" onclick="performCCListingAction(this);" style="font-size: 90%;"  data-id="22" data-infoid="22" class="js-ccHorizontalLists txtc divcenter cursp color5 pt5 pl20 pb20">Archived Interests</div>');
 		}
 		$('#HorizontalTab22_Label_zero').hide();
 		$('#HorizontalTab22_Label_nonzero').hide();

 		if ( activeHorizontalTabInfoID == 22 )
 		{
 			$('#ccHorizontalTabsBar > li').hide();
 			if ( $('#HorizontalTab22').length == 0)
 			{
 				$('#ccHorizontalTabsBar').append('<li id="HorizontalTab22" data-id="22" data-infoId="22" class="js-ccHorizontalLists jsButton-disabled txtc cursp">Archived Interests</li><li class="pos-abs bg5 cssline" style="bottom: 0px; height: 2px; left: 0px; display: list-item;" id="horizontalActiveLine22"></li>');
 			}
 			else
 			{

 				$('#HorizontalTab22').addClass('jsButton-disabled');
 				$('#horizontalActiveLine22').show();

 				$('#HorizontalTab22').show();
 			}
 		}
 		else
 		{
 			$('#ccHorizontalTabsBar > li').show();	
 			$('#HorizontalTab22').show();
 			$('#horizontalActiveLine22').hide();
 		}
 	}
 }

/***
* This function will get all the mapping variables related to contact center tuples.
* @param : val,profileIDNo,viewProfilePageParams
* @return : mapping
*/
function ccTupleResultMapping(val,profileIDNo,viewProfilePageParams) {
	
	/** ---------mapping variables for cc tuple-------**/
		var innerTupleHtml = "",innerTupleMessageStructure = "",innerContentMapObj="",callDetailsHtml="",callStatusShow=" disp-none";
		//show message if Message vertical tab is selected
		if(activeVerticalTab=="3")
				innerTupleMessageStructure = $("#innerTupleMessageContent").html();
		//otherwise show profile details
		else
				innerTupleMessageStructure = $("#innerTupleDetailsContent").html();
		innerContentMapObj = ccTupleInnerContentResultMapping(val,profileIDNo);
		innerTupleHtml = innerTupleHtml+$.ReplaceJsVars(innerTupleMessageStructure,innerContentMapObj);

		var offset = profileIDNo;
		if(viewProfilePageParams["searchid"]==10)
			val.timetext = val.timetext.replace("She","They").replace("He","They");
		if(activeVerticalTab == "6")
			val.timetext = val.intro_call_details.CC_CALL_STATUS;
		var interest_viewed_data='';
		if(val.interest_viewed_date && val.interest_viewed_date!=null)
				interest_viewed_data = val.interest_viewed_date;
		var personalizedmessageClass = "disp-none";
		var personalizedmessage='';
		if(val.message && val.message!=null)
		{
			var personalizedmessageClass = "";
			var personalizedmessage=val.message.replace(/\\"/g , '"');
			personalizedmessage=personalizedmessage.replace(/\\'/g , "'");
			//console.log(personalizedmessage);
			//personalizedmessage=personalizedmessage.replace(/\\n/g, '<br>');
			//console.log(personalizedmessage);
			personalizedmessage=readMore(personalizedmessage,profileIDNo)
			
		}
                
		if(val.name_of_user!='' && val.name_of_user!=null)
			val.username = val.name_of_user;
        
		var mapping = {
				'{ccTupleImage}': removeNull(val.profilepic120url),
				'{ccTupleIDNo}': removeNull(profileIDNo), 
				'{username}': removeNull(val.username),
				'{userloginstatus}': removeNull(val.userloginstatus),
				'{subscription_icon}': removeNull(val.subscription_icon),
				'{timeText}': removeNull(val.timetext),
				'{profilechecksum}':removeNull(val.profilechecksum),
				'{total_rec}':removeNull(viewProfilePageParams["totalCount"]),
				'{actual_offset}':offset,
				'{contact}':removeNull(viewProfilePageParams["contact"]),
                '{contact_id}':removeNull(viewProfilePageParams["contact_id"]),
				'{searchid}':removeNull(viewProfilePageParams["searchid"]),
				'{self}':removeNull(viewProfilePageParams["self"]),   
				'{self_profileid}':removeNull(viewProfilePageParams["self_profileid"]),  
				'{flag}':removeNull(viewProfilePageParams["flag"]),  
				'{type}':removeNull(viewProfilePageParams["type"]),
				'{page}':removeNull(viewProfilePageParams["page"]),   
				'{fromPage}':removeNull(viewProfilePageParams["fromPage"]), 
				'{tracking}':removeNull(viewProfilePageParams["tracking"]),
				'{NAVIGATOR}':removeNull(viewProfilePageParams["ccnavigator"]),  
				'{interest_viewed_data}':removeNull(interest_viewed_data),
				'{innerTupleContent}':innerTupleHtml,
				'{personalizedmessageClass}': removeNull(personalizedmessageClass),
				'{personalizedmessage}': removeNull(personalizedmessage)
			 };
			
	return mapping;
}

/***
 * * This function will get all the mapping variables related to contact center upload request section.
 * * @param : uploadRequestParamArr,requestID
 * * @return : mapping
 * */
function requestSubtypeMapping(uploadRequestParamArr,requestID) {
				
	var mapping="",requestMessage,requestButton;
	mapping = {
			'{requestMessage}': removeNull(uploadRequestParamArr["requestMessage"]),  
			'{requestButton}':removeNull(uploadRequestParamArr["requestButton"]), 
			'{requestID}':requestID,
			'{requestClass}':"js-uploadRequest" 
			};
    return mapping;
}

function ToggleMore(keyName)
{
	
	$("#"+keyName+"_more").addClass("disp-none");
	$("#"+keyName+"_less").removeClass("disp-none");
	return false;	
}
function readMore(string,keyName)
{
	string=string.trim();
	var maxLength = 310; // Maximum length of message to be displayed
	var readMoreStr="";
	if(string.length>maxLength){
	readMoreStr= [string.slice(0, maxLength).trim(), "<span id=\""+keyName+"_less\" class=\"disp-none\" >", string.slice(maxLength)].join('');
	readMoreStr=readMoreStr+"</span><span id=\""+keyName+"_more\" onClick=\"ToggleMore(\'"+keyName+"\')\">...<span class=\"color5 cursp\"> more</span></span>";
	return readMoreStr;
	}
	else
		return string;
}

/***
* This function will get all the mapping variables related to contact center tuples inner content.
* @param : val,profileIDNo
* @return : mapping
*/
function ccTupleInnerContentResultMapping(val,profileIDNo) {
				
		/** ---------mapping variables for cc tuple inner content-------**/
		var mapping="";
		var toHandleVsp = (activeVerticalTab == "2")?'handleVspShow':'handleVspHide';
		if(activeVerticalTab=="3")
		{
			if(val.last_message =="undefined" || val.last_message == null)
				val.last_message='';
      val.last_message = val.last_message.replace(/\\"/g , '"');
      val.last_message = val.last_message.replace(/\\'/g , "'");
                                
				mapping = {
				'{ccTupleIDNo}': removeNull(profileIDNo),  
				'{messageBody}':removeNull(val.last_message) ,
				'{profilechecksum}' : removeNull(val.profilechecksum)
				};
		}
		else
		{		
			var casteStr;	
			if(val.caste == val.religion)
			{
				casteStr = ''; 
			}
			else
			{
				casteStr = (val.caste).substr((val.caste).indexOf(":") + 1);
				casteStr = ", "+casteStr;
			}
				
				//for dev environment only
				if(val.income =="undefined" || val.income == null)
				{
					val.income = "";
				}
				mapping = {
				'{ccTupleIDNo}': removeNull(profileIDNo), 
				'{mstatus}': removeNull(val.mstatus),
				'{age}': removeNull(val.age),
				'{religion}':removeNull(val.religion),
				'{casteStr}':removeNull(casteStr),
				'{location}':removeNull(val.location),
				'{height}': removeNull(val.height),
				'{occupation}': removeNull(val.occupation),
				'{caste}': removeNull(val.caste),
				'{religion}': removeNull(val.religion),
				'{income}': removeNull((val.income).replace(/Rs./, '₹ ')),
				'{mtongue}': removeNull(val.mtongue),
				'{edu_level_new}': removeNull(val.edu_level_new),
				'{location}': removeNull(val.location),
				'{profilechecksum}':removeNull(val.profilechecksum),
				'{username}':removeNull(val.username),
				'{stype}':vspStype,
				'{handleVsp}': toHandleVsp,
				'{showContactedUsernameDetails}':"hide" //hide top vsp details section from acceptance listing
			 };
		}
		return mapping;
}


/** 
* This function will send ajax request related to contact center.
* All ajax related action in which page is reloaded will be handled here.
* @param : requestParams,infoArr
*/
function sendProcessCCRequest(requestParams,infoArr) 
{
	var url = '/api/v2/inbox/perform';
	var infoArr = typeof infoArr !== 'undefined' ? infoArr : {};
	var action = typeof infoArr["action"] !== 'undefined' ? infoArr["action"] : '';

	var postParams='';
	if(action == "pagination")
		postParams = postParams+requestParams;  
		else
				postParams = postParams+requestParams+"&pageNo=1"; 
	
	postParams = postParams+"&ContactCenterDesktop=1&time="+$.now();
	var timeI; var timeE; var timeD;	
	$.myObj.ajax({
		url: url,
		dataType: 'json',
		type: 'GET',
		data: postParams,
		timeout: 60000,
		cache: false,
		updateChatList:(action == "pagination") ? true : false,
		beforeSend: function( xhr ) 
		{               
			$("#mainUploadRequestDiv").hide();
			jsb9onUnloadTracking();
			jsb9init_first();
			timeI = new Date().getTime();
			$('#ccSection').addClass('js-disabled');
			//show loader
			if(action=="pagination")
			{
				showCommonLoader();
			}
			else if(action=="stayOnPage")
			{
				$("#js-ccContainer").hide();
				$("#ccPaginationDiv").hide();
				$("#zeroResultSection").hide();
				showCCLoader('show');
			}
		},

		success: function(response) 
		{
			$('#ccSection').removeClass('js-disabled');
			if(action=='pagination')
			{
				hideCommonLoader();
				animationToTop();
			}
                        
			loadCCPageResponse(response);
			jsLoadFlag = 1;
			timeE = new Date().getTime();
			timeD = (timeE - timeI)/3600;
			jsb9init_fourth(timeD,true,2,'https://track.99acres.com/images/zero.gif','AJAXCONTACTCENTERURL');
		},
		error: function(xhr) 
		{
			console.log("error"); //LATER
			return "error";
		}
	});
	return false;
}

/** Function to load cc page with async call
 * @param : pageNo	
 */
function loadCCPage(pageNo) {
	var postParams = "infoTypeId="+activeHorizontalTabInfoID+"&pageNo=" + pageNo;
	var infoArr = {};
	infoArr["action"] = "pagination";

			/**
	* search code for this section using keyword "allInOneRespone"
	*/
	/*
				if(typeOfApi=='allInOneResponse')
	{
		hideCommonLoader();
		loadCCPageResponse(responseAllInOneResponse);
	}
	else
	*/
		sendProcessCCRequest(postParams,infoArr);

	return false;
}

/**  
 * Start Handling CC pagination bindings and actions
 */
function handleCCPagination(response){
	//Handling next page display
	if (response.next_avail!='true') {
		$('#ccPaginationNext').addClass("js-disabled");
	} else {
		$('#ccPaginationNext').removeClass("js-disabled");
	}
	
	//Handling prev page display
	if (parseInt(response.page_index) == 1) {
		$('#ccPaginationPrev').addClass("js-disabled");
	} else {
		$('#ccPaginationPrev').removeClass("js-disabled");
	} 
}
 
/** Function to populate pagination data
 * @param : totalCount,page_index,no_of_results  
 */
function dataForCCPagination(totalCount,page_index,no_of_results) {

	updateHistory("",page_index);
	var startPageNo = 1 + CC_RESULTS_PER_PAGE *(page_index - 1), endPageNo = startPageNo + no_of_results - 1,paginationHtml="";
	var ccPaginationStructure = $('#ccPaginationCountStructure').html();
	$("#ccPaginationCountDiv").html("");
	var activeClass="active";
	var mapObj = {
		'{totalCount}': removeNull(totalCount),
		'{startPageNo}': removeNull(startPageNo),
		'{endPageNo}': removeNull(endPageNo),
		'{currentPageNo}':removeNull(page_index),
		'{activeClass}': removeNull(activeClass)
	};
	paginationHtml = $.ReplaceJsVars(ccPaginationStructure, mapObj);
	if(paginationHtml)
	{
		$('#ccPaginationCountDiv').append(paginationHtml);
		paginationHtml = "";
	}
}

/*set active horizontal and vertical tabs highlighted in cc page
 * @param : VerticalTab,HorizontalTabInfoID
 */
function setActiveCCTabs(VerticalTab,HorizontalTabInfoID)
{
	if ( HorizontalTabInfoID == 23 )
		VerticalTab = 0;
	/*
		Added this condition for expire interest check.
	 */
	if ( HorizontalTabInfoID == 22 )
		VerticalTab = 0;
	$("#VerticalTab"+VerticalTab).addClass("active").addClass("jsButton-disabled");
	$("#HorizontalTab"+HorizontalTabInfoID).addClass("jsButton-disabled");
	$("#Request"+activeRequestTypeID).addClass("jsButton-disabled");
	setActiveHorizontalLine("#horizontalActiveLine",HorizontalTabInfoID);
	activeVerticalTab = VerticalTab;
	activeHorizontalTabInfoID = HorizontalTabInfoID;
}

/*set horizontal tabs according to Requests type(photo/horoscope)
 * @param : activeRequestTypeID,reloadFlag('true'/'false')
 */
function setHorizontalTabsForRequests(requestTypeID,reloadFlag)
{
	$("#HorizontalTab"+activeHorizontalTabInfoID).removeClass("jsButton-disabled");
	if(typeof reloadFlag!="undefined" && reloadFlag=='true')
	{
		if(requestTypeID!=defaultRequestTypeID)
		{
				$.each(new Array(2),function(i){
						$("#HorizontalTab"+ccRequestTypeListArr[defaultRequestTypeID].horizontalTabsArrInfoID[i]).attr("data-infoId",ccRequestTypeListArr[requestTypeID].horizontalTabsArrInfoID[i]);  
						$("#HorizontalTab"+ccRequestTypeListArr[defaultRequestTypeID].horizontalTabsArrInfoID[i]).attr("id","HorizontalTab"+ccRequestTypeListArr[requestTypeID].horizontalTabsArrInfoID[i]);
				});
		}
	}
	else
	{  
		$("#Request"+activeRequestTypeID).removeClass("jsButton-disabled");
		$("#Request"+requestTypeID).addClass("jsButton-disabled");

		//update id's of horizontal tabs if photo request subtype is changed
		$.each(new Array(2),function(i){
				$("#HorizontalTab"+ccRequestTypeListArr[activeRequestTypeID].horizontalTabsArrInfoID[i]).attr("data-infoId",ccRequestTypeListArr[requestTypeID].horizontalTabsArrInfoID[i]);  
				$("#HorizontalTab"+ccRequestTypeListArr[activeRequestTypeID].horizontalTabsArrInfoID[i]).attr("id","HorizontalTab"+ccRequestTypeListArr[requestTypeID].horizontalTabsArrInfoID[i]);
		});
		activeRequestTypeID = requestTypeID;
	}
	$("ul#RequestTypesLi li:eq(1)").addClass("pl10");
}


/**
*  This function wil show the loader.
* @param : type
**/
function showCCLoader(type)        
{
	if(type=='show')
	{
		$('#ccResultsLoaderTop').show();
	}
	else
	{
		$('#ccResultsLoaderTop').hide();
		setTimeout(function(){hidePersonalisedMessage();},100);
	}
}

/*
* Populate horizonatal tab labels corresponding to selected vertical tab
* @param : thisElement,reloadflag
*/
function populateHorizontalTabs(thisElement,reloadflag)
{
		var VerticalTabID = $(thisElement).attr("data-id");
		$("#VerticalTab"+activeVerticalTab).removeClass("jsButton-disabled").removeClass("active");
		$(thisElement).addClass("jsButton-disabled").addClass("active");
		activeVerticalTab = VerticalTabID;
		var left = 600 * VerticalTabID;
		if(typeof reloadflag!="undefined"&& reloadflag=='true')
				$("#ccHorizontalTabsBar").css("left","-"+left+"px");
		else
				$("#ccHorizontalTabsBar").animate({left:"-"+left+"px"},600);
}

//handle visibility of request subtype listing
function handleRequestTypeVisibility(flag)
{
	if(flag == 'N')
	{
		$("#js-requestTypeSelectListing").hide();
	}
	else
	{
		$("#js-requestTypeSelectListing").show();
	}
}

//handle visibility of intro calls listing
function handleIntroCallsListVisibility(flag)
{
	if(flag == 'N')
	{
		$("#VerticalTab6").hide();
	}
	else
	{
		$("#VerticalTab6").show();
	}
}

/*send ajax request to remove selected profile from intro call list
* @params: postParams
*/
/*function sendRequestForICRemoval(postParams)
{
	var url = "/inbox/removeFromICList";
	$.myObj.ajax({
		url: url,
		dataType: 'json',
		type: 'POST',
		data: postParams,
		timeout: 60000,
		beforeSend: function( xhr ) 
		{               
		},

		success: function(response) 
		{
			console.log("success"); //LATER	- add div 					
		},
		error: function(xhr) 
		{
			console.log("error"); //LATER
			return "error";
		}
	});
}*/

$(document).ready(function() {
		//show loader
		showCCLoader('Show');
		
		//set horizontal tab ids of Requests vertical tab
		setHorizontalTabsForRequests(activeRequestTypeID,'true');  //true set for reloadflag param

		//set active horizontal and vertical tabs on first page load
		setActiveCCTabs(activeVerticalTab,activeHorizontalTabInfoID);

		//set slider of horizontal tabs according to selected vertical tab
		populateHorizontalTabs($("#VerticalTab"+activeVerticalTab),'true');    
		
		//handle visiiblity of intro calls listing
		handleIntroCallsListVisibility(showIntroCallsList);

		$("#ccTuplesMainDiv").html("");
		//loads page data
		loadCCPageResponse(response);
		
		//global variable that will store searchId (current). 
		lastCCSearchId = response.searchid; 
	
		//on click of vertical tabs
		$(".js-ccVerticalLists").bind('click', function() {
			if($("#listingWindow").hasClass( "disp-none" ))
			{
				$("#messageWindow").addClass('disp-none');
  				$("#listingWindow").removeClass('disp-none');
			}
			clearTimeout(clearTimedOutVar);  //--------added to kill previous request
			var verticalTabID = $(this).attr("data-id");
		 
			//set default horizontal tab to open
			var defaultHTabInfoId;
			if(verticalTabID==1)
			{   
				var requestTypeID = 0;
				setHorizontalTabsForRequests(requestTypeID);
				defaultHTabInfoId = ccRequestTypeListArr[activeRequestTypeID].defaultHtabInfoID;
			}
			else
				defaultHTabInfoId = (ccTabsMappingData[verticalTabID]).defaultHtabInfoID;

			//set horizontal tabs according to selected vertical tab
			populateHorizontalTabs(this);

			//populate tuples data
			performCCListingAction($("#HorizontalTab"+defaultHTabInfoId));
			lastCurrentPage = 1;
		});

		//on click of horizontal tabs
		$(".js-ccHorizontalLists").bind('click', function() {
			clearTimeout(clearTimedOutVar);  //--------added to kill previous request
			//populate tuples data
			performCCListingAction(this);
		});
		
		//on click of requests subtype lists(photo/horoscope)
		$(".js-ccRequestTypeLists").bind('click',function(){
			clearTimeout(clearTimedOutVar);  //--------added to kill previous request
			$(".js-resultsCount").remove();
			var requestTypeID = $(this).attr("data-id");
			//change id's of horizontal requests tabs
			setHorizontalTabsForRequests(requestTypeID);
			var horizontalTabID = ccRequestTypeListArr[requestTypeID].defaultHtabInfoID;
    
			//populate tuples data
			performCCListingAction($("#HorizontalTab"+horizontalTabID));
		});

		//Binding next page button   
		$('#ccPaginationNext').bind('click', function() {
			currentPage=$("#ccPaginationCountDiv").find(".active").attr("data");
			loadCCPage(parseInt(currentPage)+1);
			lastCurrentPage = parseInt(currentPage)+1;
		});

		//Binding prev page button
		$('#ccPaginationPrev').bind('click', function() {
			currentPage=$("#ccPaginationCountDiv").find(".active").attr("data");
			loadCCPage(parseInt(currentPage)-1);
			lastCurrentPage = parseInt(currentPage)-1;
		});

		//bind upload photo/horoscope action on upload button
		$("body").on("click",".js-uploadRequest",function(){
			var requestUrl = "";
			if(activeRequestTypeID == 0)
				requestUrl = "/social/addPhotos?social/addPhotos";
			else if(activeRequestTypeID == 1)
				requestUrl = "/profile/viewprofile.php?ownview=1&from=MyJS&EditWhatNew=uploadhoroscope";
			window.location.href = requestUrl;	
		});

		//remove profile from intro call list binding---handled via contact engine button
		/*$("body").on("click",".js-removeFromIC",function(){
			event.preventDefault();
			var match_profileChecksum = $(this).attr("data"),postParams={};
			postParams["match_profileChecksum"] = match_profileChecksum;
			sendRequestForICRemoval(postParams);
		});*/

		//redirect to vsp page on click of view similar profiles
		$("body").on("click",".js-viewSimilarLink",function(event){
			event.preventDefault();

			var data = $(this).attr("data");
			var vspData = data.split(",");
			var profilechecksum = vspData[0],username=vspData[1],stype=vspData[2],showContactedUsernameDetails=vspData[3];
			var vspRedirectUrl = "/search/viewSimilarProfile?profilechecksum="+profilechecksum+"&stype="+stype+"&SIM_USERNAME="+username+"&contactedProfileDetails="+showContactedUsernameDetails;
			window.location.href = vspRedirectUrl;
		});

			hidePersonalisedMessage();
	});

function hidePersonalisedMessage()
{
	$(".js-hideDetail").each(function(index, element) {
		data = $(element).html();
		if(data != ""){
			if(data.match(emailReg) != null){
				matchedStr = data.match(emailReg);
				$.each(matchedStr, function(index, value){
					data = data.replace(value, "<span class='f13 fontreg color11 showText'>&lt;Email visible on accept&gt;</span><span class='disp-none hiddenStr'>"+value+"</span>");
				});
				$(element).html(data);
			}
			if(data.match(phoneReg) != null){
				matchedStr = data.match(phoneReg);
				$.each(matchedStr, function(index, value){
					data = data.replace(value, "<span class='f13 fontreg color11 showText'>&lt;Phone number visible on accept&gt;</span><span class='disp-none hiddenStr'>"+value+"</span>");
				});
				$(element).html(data);
			}
		}
	});
}
