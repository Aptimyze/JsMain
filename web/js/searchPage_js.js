var closedId = '';
var openId = '';
var id='';
var ageFlag = 0;
var heightFlag = 0;
var incomeFlag = 0;
var closeLayer = '';

$(document).ready(function () {
	//When the page loads this section checks if any of the slider cluster is visible. If visible then it generates that slider
	if($("#AGEloader_collapse").css("display")=="block")		//For age
		createSlider("AGE");
	if($("#HEIGHTloader_collapse").css("display")=="block")		//For height
		createSlider("HEIGHT");
	if($("#INCOMEloader_collapse").css("display")=="block")		//For income
		createSlider("INCOME");
	//Slider generation section ends
	
        
       $(".agentinfo").click(function() {
           var link = SITE_URL+"/static/agentinfo";
           window.open(link,'_self');
           return false;
    });
});

function fullCriteria(action)		//This function is for handling see/hide full criteria popup
{
	if(action=="show")
	{
		if(dID("full_criteria").innerHTML=="See full criteria")
		{
			check_window('fullCriteira(\'hide\')');
			dID("popup_Info").style.display="block";
			dID("full_criteria").innerHTML="Hide full criteria";
			function_to_call="fullCriteria('hide')";
			common_check=1;
		}
		else
		{
			dID("popup_Info").style.display="none";
			dID("full_criteria").innerHTML="See full criteria";
		}
	}
	else
	{
		common_check=0;
		function_to_call="";
		dID("popup_Info").style.display="none";
		dID("full_criteria").innerHTML="See full criteria";
	}
}

function saveAsDpp(action,type)		//This function is for handling save search and save dpp popups
{
	if(loggedIn==1)
	{
		if(type == 1)
		{
			if(action=="show")
			{
				check_window('saveAsDpp(\'hide\',1)');
				dID("saved_as_desired").style.display="block";
				function_to_call="saveAsDpp('hide',1)";
				common_check=1;
			}
			else
			{
				common_check=0;
				function_to_call="";
				dID("saved_as_desired").style.display="none";
			}
		}
		else if(type == 2)
		{
			if(action=="show")
			{
				check_window('saveAsDpp(\'hide\',2)');
				dID("saveSearchCriteria").style.display="block";
				function_to_call="saveAsDpp('hide',2)";
				common_check=1;
			}
			else
			{
				common_check=0;
				function_to_call="";
				dID("saveSearchCriteria").style.display="none";
			}
		}
	}
	else
	{
		$.colorbox({href:"/static/registrationLayer?pageSource=searchpage"});
	}
}

$.ajaxSetup ({  
        cache: false  
    });

	$("[name='showAlbum']").click
	(
		function()
		{
			var id = this.id.replace("showAlbum_","");
			//var params = {profilechecksum:id, searchPage:"1"};
			var params = "profilechecksum="+id+"&searchPage=1";
			var xslUrl = SITE_URL+"/xslt/newAlbumLayer2.xsl";
			var xmlUrl = SITE_URL+"/social/album";
			sendXSLTrequest(xslUrl,xmlUrl,"albumCode",params,"searchPage");
		}
	);

$("#saveBtn1").click(function(){		//This action handles action of SAVE button of save as dpp
	$("#saveDppMsg2").hide();
	$("#saveDppLoader").show();
	var searchId = $("#searchId").val();
	$.post("/search/saveDpp",{searchId:searchId},function(responseText){
		responseText = $.trim(responseText);
		if(responseText=="success")
		{
			$("#saveDppLoader").hide();
			$("#saveDppMsg1").show();
		}
		else if(responseText=="logout")
		{
			$.colorbox({href:"/static/registrationLayer?pageSource=searchpage"});
			$("#saveDppLoader").hide();
			$("#saveDppMsg2").show();
		}
        });
});

	$(".ico_close").click
	(
		function()
		{
			if(loggedIn == 1)
			{
				closeId1 = "#profile"+this.id;
				closeId2 = "#topTab"+this.id;
				closeId3 = "#tupleMsg"+this.id;
				openId = "#ignore"+this.id;
				checksumId = $("#checksum"+this.id).val();
				if(searchId)
				{
					ignoreUrl="/common/ignoreProfile/"+checksumId+"/"+searchId;
				}
				else
					ignoreUrl="/common/ignoreProfile/"+checksumId+"/"+0;
				$.ajax(
				{
//					url: "/common/ignoreProfile?ignoredProfileid="+checksumId+"&searchId="+searchId,
					url: ignoreUrl,
					success: function(response)
					{
						if(response =='loggedOut')
						{
							var url1 = SITE_URL+"/static/newLoginLayer?searchId="+searchId+"&currentPage="+currentPage;
							$.colorbox({href:url1});
							return false;
						}
						else
						{
							$(closeId1).hide();
							$(closeId2).hide();
							if($(closeId3).length > 0)
								$(closeId3).hide();
							$(openId).show();
							$(openId).css("display","block");
							return false;
						}
					}
				});
			}
			else
			{
				var url1 = SITE_URL+"/static/newLoginLayer?searchId="+searchId+"&currentPage="+currentPage;
				$.colorbox({href:url1});
				return false;
			}
		}
	);

	$("a[name='undoIgnore']").click
	(
		function()
		{
			id = this.id.replace("undoIgnore","");
			$.ajax(
			{
				url: "/common/undoIgnoreProfile?ignoredProfileid="+checksumId+"&searchId="+searchId,
				success: function(response)
				{
					if(response =='loggedOut')
					{
						var url1 = SITE_URL+"/static/newLoginLayer?searchId="+searchId+"&currentPage="+currentPage;
						$.colorbox({href:url1});
						return false;
					}
					else
					{
						checksumId = $("#checksum"+id).val();
						openId1 = "#profile"+id;
						openId2 = "#topTab"+id;
						openId3 = "#tupleMsg"+id;
						closeId = "#ignore"+id;
						$(closeId).hide();
						$(openId1).show();
						$(openId2).show();
						if($(openId3).length > 0)
							$(openId3).show();
					}
				}
			});
			return false;
		}
	);

	$("[name='showLoginLayer']").click
	(
		function()
		{
			handleLoginLayer();
		}
	);

	$("[name='showRegistrationLayer']").click
	(
		function()
		{
			handleRegistrationLayer();
		}
	);

	function handleLoginLayer()
	{
		var url1 = SITE_URL+"/static/newLoginLayer?searchId="+searchId+"&currentPage="+currentPage;
		$.colorbox({href:url1});
		return false;
	}

	function handleRegistrationLayer()
	{
		var url1 = SITE_URL+"/static/registrationLayer?pageSource=searchpage";
		$.colorbox({href:url1});
		return false;
	}

	function handleAddShortlist() 
	{
		var bookmarkDone;
		var bookmarkee = this.id.replace("addshortlist","");
		var bookmarkeeChecksum = $("#checksum"+bookmarkee).val();
		var shortlistNote = "#shortlistNote"+bookmarkee;
		$("#shortlistLoad"+bookmarkee).hide();
		$("#shortlistLoadingDiv"+bookmarkee).show();

		noteValue = escape($(shortlistNote).val());
		if(noteValue == '')
		{
			$.ajax(
			{
				url: "/common/addBookmark/"+bookmarkeeChecksum,
				success: function(response)
				{
					if(response == 'success')
					{
						$("#shortlistLoadingDiv"+bookmarkee).hide();
						$("#shortlistLoad"+bookmarkee).show();
						closeId = "#openshortlist"+bookmarkee;
						shortlistId = "#shortlist"+bookmarkee;
						$(closeId).hide();
						$(shortlistId).text("Shortlisted Profile");
						$(shortlistId).removeAttr("name");
						unbindShortlistClick(shortlistId);
						$(shortlistId).removeClass("blink");
						$(shortlistId).css("color","#808080");
						$(shortlistId).css("cursor","default");
						$(shortlistId).removeAttr("href");
					}
					else
					{
						closeId = "#openshortlist"+bookmarkee;
						shortlistId = "#shortlist"+bookmarkee;
						$(closeId).hide();
						$(closeId).css("z-index","100");
						var url2 = "/static/newLoginLayer?searchId="+searchId+"&currentPage="+currentPage;
						$.colorbox({href:url2});
					}
				}
			});
		}
		else
		{
			noteValue = noteValue.replace(/\//g,'**-**');
			noteValue = noteValue.replace(/\./g,'**--**');
			$.ajax(
			{
				url: "/common/addBookmark/"+bookmarkeeChecksum+"/"+escape(noteValue),
//				url: "/common/addBookmark/"+loggedInProfileid+"/"+bookmarkee+"/"+noteValue,
				success: function(response)
				{
					if(response == 'success')
					{
						$("#shortlistLoadingDiv"+bookmarkee).hide();
						$("#shortlistLoad"+bookmarkee).show();
						closeId = "#openshortlist"+bookmarkee;
						shortlistId = "#shortlist"+bookmarkee;
						msgId = "#shortlistMsg"+bookmarkee;
						$(msgId).css("min-height","147px");
						$(msgId).text("Note added successfully");
					}
					else
					{
						closeId = "#openshortlist"+bookmarkee;
						shortlistId = "#shortlist"+bookmarkee;
						$(closeId).hide();
						$(closeId).css("z-index","100");
						var url2 = "/static/newLoginLayer?searchId="+searchId+"&currentPage="+currentPage;
						$.colorbox({href:url2});
					}
				}
			});
		}
		if(bookmarkDone == 0)
		{
			var url2 = "/static/newLoginLayer?searchId="+searchId+"&currentPage="+currentPage;
			$.colorbox({href:url2});
		}
			return false;
	}

	function handleCloseShortlist(closeid,fromOutside)
	{
		if(fromOutside != 1)
		{
			var closeId = "#"+this.id.replace("close","open");
			var shortlistId = "#"+this.id.replace("close","");
		}
		else
		{
			var closeId = closeid;
			var shortlistId = closeId.replace("open","");
		}
		$(shortlistId).text("Shortlisted Profile");
		$(shortlistId).removeAttr("name");
		unbindShortlistClick(shortlistId);
		$(shortlistId).removeClass("blink");
		$(shortlistId).css("color","#808080");
		$(shortlistId).css("cursor","default");
		$(shortlistId).removeAttr("href");
		$(closeId).hide();
		closeLayer="";
		updateClickHolder(false);
		return false;
	}

	function handleAlbumLayer()
	{
		viewedId = this.id.replace("featured","");
		viewedId = viewedId.replace("Album","");
		viewedChecksum = $("#checksum"+viewedId).val();
		//var params = {profilechecksum:viewedChecksum, searchPage:"1"};
		var params = "profilechecksum="+viewedChecksum+"&searchPage=1";
		var xslUrl = SITE_URL+"/xslt/newAlbumLayer2.xsl";
		var xmlUrl = SITE_URL+"/social/album";
		sendXSLTrequest(xslUrl,xmlUrl,"albumCode",params,"searchPage");
		return false;
	}

	$("[name='shortlistProfile']").click
	(
		function()
		{
			var bookmarkee = this.id.replace("shortlist","");
			var bookmarkeeChecksum = $("#checksum"+bookmarkee).val();

			var shortlistLayerInit = "<div class='fr divHeading lh19 white b' >\
						Shortlist\
						</div>\
						<div class='divlinks fl'  style='width:241px!important;_width:233px;height:220px;'  >\
							<div style='text-align:center;margin-top:80px;'>\
								<img src=IMG_URL/images/loader_big.gif >\
							</div>\
							<div class='fr b' style='margin-top:60px;'>\
								<a href='#' name='closeshortlist' ";
			shortlistLayerInit += "id=close"+this.id+" >\
									Close [x]\
								</a>\
							</div>\
						</div>";

			layerId = "#open"+this.id;
			$(layerId).html(shortlistLayerInit);
			$(layerId).show();
			bindClicks();
			shortlistLayer1 = "<div class='fr divHeading lh19 white b' >\
					Shortlist\
				</div>\
				<div class='divlinks fl'  style='width:241px!important;_width:233px'  >\
					<div class='sp15'>\
					</div>";
			shortlistLayer1 += "<div id=shortlistLoad"+this.id.replace("shortlist","") +" >\
					<i class='ico_right_1 fl'></i>\
					<div id=shortlistMsg"+this.id.replace("shortlist","") +">Successfully shortlisted\
					<div class='sp15'>\
						&nbsp;\
					</div>\
					<span >\
						Add a note for your future reference\
					</span>\
					<span>\
						<textarea   rows='0' cols='0'  class='width100' style='height:55px' ";
			shortlistLayer1 += " id=shortlistNote"+this.id.replace("shortlist","")+" ></textarea>\
					</span>\
					<div class='sp5'>\
					</div>\
					<div class='txt_center'>\
						<input type='button'  class='btn_view b widthauto' value='Add Note' name='addShortlist' style='cursor:pointer;'";
			shortlistLayer1 += " id=add"+this.id+" >\
					</div></div>\
						<div class='sp15'>\
						</div>\
						<a  oncontextmenu='return false;' href='#' name='forwardProfile' ";
			shortlistLayer1 += " id= forwardProfile" + this.id.replace("shortlist","") +" >\
							Forward this profile to your friend or family \
						</a>\
						</div>\
						<div id=shortlistLoadingDiv"+this.id.replace('shortlist','') +" style='text-align:center;margin-top:80px;display:none;height:100px;'>\
							<img src=IMG_URL/images/loader_big.gif >\
						</div>\
						<div class='separator fl width100'>\
						</div>\
						<div class='fr b'>\
							<a href='#' name='closeshortlist' ";
			shortlistLayer1 += "id=close"+this.id+" >\
								Close [x]\
							</a>\
						</div>\
					</div>";
			$.ajax(
			{
				url: "/common/addBookmark/"+bookmarkeeChecksum,
				success: function(response)
				{
					if(response == 'success')
					{
						$(layerId).html(shortlistLayer1);
						bindClicks();
						closeLayer="handleCloseShortlist('"+layerId+"',1)";
						updateClickHolder(true);
					}
					else
					{
//						$(layerId).css("z-index","100");
						$(layerId).hide();
						var url2 = "/static/newLoginLayer?searchId="+searchId+"&currentPage="+currentPage;
						$.colorbox({href:url2});
					}
				}
			});
			return false;
		}
	);

	function checkEnter(e)
	{
		if(e.which == 13)
		{
			loginUser();
			return true;
		}
	}

$(".multibuttonSelect").click(function(){
	$(".dummySelect").attr('checked', this.checked);
	$(".multibuttonSelect").attr("checked", this.checked);
});

$(".dummySelect").click(function(){
        if($(".dummySelect").length == $(".dummySelect:checked").length) {
       		$(".multibuttonSelect").attr("checked", "checked");
        } else {
            	$(".multibuttonSelect").removeAttr("checked");
        }
});

$("#saveBtn2").click(function(){		//This event handles action of SAVE button of save search
	var label = $.trim($("#saveSearchCriteriaLabel").val());
	var searchId = $("#searchId").val();
	if(savedSearchLimitReached)
		var replaceId = $('#saveDD').val();
	else
		var replaceId = 0;
	$("#errMsg1").hide();
	$("#errMsg2").hide();
	$("#errMsg3").hide();
	$("#errMsgSpace").hide();
	if(label=="e.g: 28-32, Never Married, Delhi" || label=="")
	{
		$("#saveSearchCriteriaMsg").show();
		$("#errMsg1").show();
		$("#errMsgSpace").show();
		$("#saveSearchCriteriaLoader").hide();
	}
	
	else
	{
		if(replaceId==0)
			var params = {searchId:searchId,saveSearchName:label};
		else
			var params = {searchId:searchId,saveSearchName:label,saveSearchId:replaceId};
		$("#saveSearchCriteriaMsg").hide();
		$("#saveSearchCriteriaLoader").show();
		$.post("/search/saveSearch",params,function(responseText){
			if(responseText=="success")
			{
				
						$("#saveSearchCriteriaMsg1").show();
						$("#saveSearchCriteriaLoader").hide();
			}
			else if(responseText=="No name" || responseText=="save search cannot be blank")
			{
				$("#saveSearchCriteriaMsg").show();
				$("#errMsg1").show();
                		$("#errMsgSpace").show();
                		$("#saveSearchCriteriaLabel").focus();
				$("#saveSearchCriteriaLoader").hide();
			}
			else if(responseText=="Search Name Same")
			{
				$("#saveSearchCriteriaMsg").show();
				$("#errMsg2").show();
                		$("#errMsgSpace").show();
                		$("#saveSearchCriteriaLabel").focus();
				$("#saveSearchCriteriaLoader").hide();
				
			}
			else if(responseText=="Insert Error")
			{
				$("#saveSearchCriteriaMsg").show();
				$("#errMsg3").show();
                		$("#errMsgSpace").show();
                                $("#saveSearchCriteriaLabel").focus();
                                $("#saveSearchCriteriaLoader").hide();
			}
			else if(responseText=="logout")
			{
				$.colorbox({href:"/static/registrationLayer?pageSource=searchpage"});
				$("#saveSearchCriteriaMsg").show();
                		$("#saveSearchCriteriaLabel").focus();
				$("#saveSearchCriteriaLoader").hide();
			}
		});
	}
});

$('.fButtons').click(function(){	//This event handles the actions of next/previous buttons of featured profile
	var url;
	var resultNo = $("#FeaturedResultNo").val();
	if (this.id == "fNextButton")
	{
		resultNo++;
		url = "/search/featuredAction/next/"+$("#FeaturedCheckSum").val()+"/"+$("#searchId").val()+"/"+resultNo;
	}
	else if(this.id == "fPrevButton")
	{
		resultNo--;
		url = "/search/featuredAction/prev/"+$("#FeaturedCheckSum").val()+"/"+$("#searchId").val()+"/"+resultNo;
	}

	$("#profile1").html('<div style="margin-top: 40px;font-size:20px">Loading Profile...<div style="margin-right:20px;margin-top:20px;"><img src="IMG_URL/images/loader_big.gif"><div></div>');
	$("#profile1").css("text-align", "center");

	$.get(url,function(responseText){
                if(responseText)
                {
			$("#featuredProfile").html(responseText);
			bindFeaturedProfileClicks();
			bindEoiContact($("#FeaturedCheckSum").val());
                }
        });
});

$("a[name='chatIcon111']").click
(
	function()
	{
		alert("To initiate chat or receive chat request,chat bar should be at the bottom of the page.--");
		return false;
	}
);

function bindClicks()
{
	$("[name='addShortlist']").unbind('click', handleAddShortlist).click(handleAddShortlist);
	$("[name='closeshortlist']").unbind('click', handleCloseShortlist).click(handleCloseShortlist);
	$("[name='forwardProfile']").unbind('click', handleForwardProfile).click(handleForwardProfile);
}

function unbindShortlistClick(idValue)
{
	$(idValue).unbind('click');
}

function bindFeaturedProfileClicks()
{
	$("[name='showLoginLayer']").unbind('click', handleLoginLayer).click(handleLoginLayer);
	$("[name='showRegistrationLayer']").unbind('click', handleRegistrationLayer).click(handleRegistrationLayer);
	$("[name='featuredAlbum']").unbind('click', handleAlbumLayer).click(handleAlbumLayer);
	checkFeaturedProfilePosition();
}

function checkFeaturedProfilePosition()
{
	var pos = $("#FeaturedProfilePosition").val();

	if(pos == 'first')
	{
		$("#fPrevButton").hide();
		$("#fNextButton").show();
	}
	else if(pos == 'last')
	{
		$("#fPrevButton").show();
		$("#fNextButton").hide();
	}
	else if(pos == 'single')
	{
		$("#fPrevButton").hide();
		$("#fNextButton").hide();
		$("#featuredNextPreviousSpan").css("padding","0px");
		$("#featuredNextPreviousSpan").css("border","0px");
	}
	else
	{
		$("#fPrevButton").show();
		$("#fNextButton").show();
	}
}
checkFeaturedProfilePosition();


function handleForwardProfile()
{
	closeId = this.id.replace("forwardProfile","#openshortlist");
	$(closeId).hide();
	shortlistId = this.id.replace("forwardProfile","#shortlist");
	$(shortlistId).text("Shortlisted Profile");
	$(shortlistId).removeAttr("name");
	unbindShortlistClick(shortlistId);
	$(shortlistId).removeClass("blink");
	$(shortlistId).css("color","#808080");
	$(shortlistId).css("cursor","default");
	$(shortlistId).removeAttr("href");
	viewedId = this.id.replace("forwardProfile","");
	viewedChecksum = $("#checksum"+viewedId).val();
	forwardProfLayer = SITE_URL+"/common/forwardProfileLayer/"+viewedChecksum;
	$.colorbox({href:forwardProfLayer});
	return false;
}

/*** clusters ****/
function createSlider(param)		//This function generates the jquery slider depending on the param passed for AGE or HEIGHT or INCOME
{
	if(param=="HEIGHT")
	{
		$("#SliderHeight").css("diplay","block");
		jQuery("#SliderHeight").slider({ from: 1, to: 26, step: 1, dimension: '', typeSlider: 'Height', scale: ["4'0\"", "6'0\"+"], limits: false, calculate: function( value ){
		value = value+47;
		if(value==73)
		{
			value = 72;
			var feet = Math.floor( value / 12 );
			var inches = ( value - feet*12 );
			return feet + "'" + inches + "\"+";
		}
		else
		{
			var feet = Math.floor( value / 12 );
			var inches = ( value - feet*12 );
			return feet + "'" + inches + "\"";
		}
		}});
		$("#leftPointerHeight").mousedown(function(){
			sliderPointerActions("leftPointerHeight");	//sets action for left pointer of height slider
		});
		$("#rightPointerHeight").mousedown(function(){
			sliderPointerActions("rightPointerHeight");	//sets action for right pointer of height slider
		});
		if($("#value_leftPointerHeight").text()==$("#value_rightPointerHeight").text() && $("#value_leftPointerHeight").text()=="4'0\"")	//If height is selected as 4'0"-4'0" the right pointer of height slider should be at the top
			$("#rightPointerHeight").css("z-index",$("#leftPointerHeight").css("z-index")+1);
		heightFlag = 1;
	}
	else if(param=="AGE")
	{
		$("#SliderAge").css("diplay","block");
		jQuery("#SliderAge").slider({ from: 18, to: 56, step: 1, dimension: '', typeSlider: 'Age', scale: ["18", "55+"], limits: false, calculate: function( value ){
			if(value==56)
				return "55+";
			else
				return value;
			}});
		$("#leftPointerAge").mousedown(function(){
			sliderPointerActions("leftPointerAge");		//sets action for left pointer of age slider
		});
		$("#rightPointerAge").mousedown(function(){
			sliderPointerActions("rightPointerAge");	//sets action for right pointer of age slider
		});
		if($("#value_leftPointerAge").text()==$("#value_rightPointerAge").text() && $("#value_leftPointerAge").text()=="18")	//If age is selected 18-18,then right pointer of age slider should be at the top
			$("#rightPointerAge").css("z-index",$("#leftPointerAge").css("z-index")+1);
		ageFlag = 1;
	}
	else if(param=="INCOME")
	{
		$("#SliderRupee").css("diplay","block");
		$("#SliderDollar").css("diplay","block");
		jQuery("#SliderRupee").slider({ from: 1, to: 16, step: 1, dimension: '', typeSlider: 'IncomeRupee', scale: ["र&nbsp;"+income_arr_rupee_html[0],"&amp;&nbsp;above"], limits: false, calculate: function( value ){
			if(value==16)
				value = 19;
			else if(value==1)
				value=0;
			else if(value>=12 && value<=15)
				value = value+8;
			var label = "र&nbsp;"+income_arr_rupee_html[value];
			if(label.indexOf("above")!=-1)
				label = income_arr_rupee_html[value];
			return label;
			}});

		jQuery("#SliderDollar").slider({ from: 1, to: 9, step: 1, dimension: '', typeSlider: 'IncomeDollar', scale: ["$&nbsp;"+income_arr_dollar_html[0], "&amp;&nbsp;above"], limits: false, calculate: function( value ){
			if(value==1)
				value = 0;
			else
				value = value+10;
			var label = "$&nbsp;"+income_arr_dollar_html[value];
			if(label.indexOf("above")!=-1)
				label = income_arr_dollar_html[value];
			return label;
			}});
		$("#leftPointerIncomeRupee").mousedown(function(){
			sliderPointerActions("leftPointerIncomeRupee");		//sets action for left pointer of rupee slider
		});
		$("#rightPointerIncomeRupee").mousedown(function(){
			sliderPointerActions("rightPointerIncomeRupee");	//sets action for right pointer of rupee slider
		});
		$("#leftPointerIncomeDollar").mousedown(function(){
			sliderPointerActions("leftPointerIncomeDollar");	//sets action for left pointer of dollar slider
		});
		$("#rightPointerIncomeDollar").mousedown(function(){
			sliderPointerActions("rightPointerIncomeDollar");	//sets action for right pointer of dollar slider
		});
		if($("#value_leftPointerIncomeRupee").text()==$("#value_rightPointerIncomeRupee").text() && $("#value_leftPointerIncomeRupee").text().substring(2)=="0")        //If rupee is selected as र 0-र 0 the right pointer of rupee slider should be at the top
                        $("#rightPointerIncomeRupee").css("z-index",$("#leftPointerIncomeRupee").css("z-index")+1);
		if($("#value_leftPointerIncomeDollar").text()==$("#value_rightPointerIncomeDollar").text() && $("#value_leftPointerIncomeDollar").text().substring(2)=="0")        //If dollar is selected as $0-$0 the right pointer of dollar slider should be at the top
                        $("#rightPointerIncomeDollar").css("z-index",$("#leftPointerIncomeDollar").css("z-index")+1);
		incomeFlag = 1;
	}
}

var clearTimedOutVar; 
    $('input:checkbox').click(function () {
      this.blur();
      this.focus();
});

$('.checkbox-selector').click(function() {
        var chb = $(this).prev(); 
        //chb.attr('checked', !chb.attr('checked')); //inverses checked state
	chb.click();
        //chb.attr('checked', !chb.attr('checked')); //inverses checked state
	//chb.change(); // not needed for new jquery version
	return false;
});
$('.checkbox-selector1').click(function() {
	//alert("**1**");
        var chb , chb1 , action;
	chb= $(this); 
	clusterName = chb.attr('name');
	clusterVal = chb.attr('value');

var tempForCity;
tempForCity = $('#forCityCluster').val();
if(tempForCity)
	tempForCity = tempForCity+","+clusterVal;
else
	tempForCity = clusterVal;
$('#forCityCluster').val(tempForCity);

	var array = new Array();
	$('input[name="'+clusterName+'"]:checked').each(function(i,el){
	chb1 = $(this); 
	//alert(chb1.attr('value'));
	if(clusterVal == 'ALL')
	{
		if(chb1.attr('value')!='ALL')
		{
			chb1.attr("checked","");
		}
		else
			array.push($(el).val());
	}
	else
	{
		if(chb1.attr('value')=='ALL')
		{
			chb1.attr("checked","");
		}
		else
		{
			array.push($(el).val());
		}
	}
	});

	if(clusterVal != 'ALL')
	{
		var s_val = clusterName+'_s_val';
		s_val = s_val.split('[')[0]+'_s_val';
		if($('#'+s_val).length > 0)
		{
			s_val = $('#'+s_val).val();
			s_val1 = s_val.split(',');
			jQuery.each(s_val1, function(index, item) {
				array.push(item);
			});
		}
		//alert(array);
	}

	clusterLoader(clusterName);

	var myJsonString = JSON.stringify(array);
	clusterName = clusterName.replace("[]", "");
	setFormValues(clusterName,myJsonString,'json');
	clearTimeout(clearTimedOutVar);
	clearTimedOutVar = setTimeout(function(){ submitClusterForm() },2000);
});

function setFormValues(clusterName,myJsonString,isJson)
{
	$('#NEWSEARCH_CLUSTERING').val(clusterName);
	$('#selectedClusterArr').val(myJsonString);
	$('#addRemoveCluster').val(isJson);
}

var lateDisableCluster;

function submitClusterForm()		//This function disables the cluster selected and then submits the page
{
	if(lateDisableCluster=="AGE" || lateDisableCluster=="HEIGHT" || lateDisableCluster=="INCOME" || lateDisableCluster=="INCOME_DOL")
	{
		disbaleSlider(lateDisableCluster);
	}
	else
		$("#"+lateDisableCluster+"div :input").attr("disabled", true);
	$('#clusterForm').submit();
}

function clusterLoader(clusterName)
{
	var clustersToShowArr , text , clustersToShow;
	var selectedCluster = clusterName.replace("[","");
	selectedCluster = selectedCluster.replace("]","");

	/* diabling all cluster excepth the choosen one */
	clustersToShow = jsonClustersToShow;
	clustersToShow = clustersToShow.replace(/&quot;/g,"");
	clustersToShow = clustersToShow.replace("[","");
	clustersToShow = clustersToShow.replace("]","");
	if(clustersToShow.indexOf("INCOME")!=-1)		//If INCOME exists then INCOME_DOL should also exist in the list of clusters
		clustersToShow = clustersToShow+",INCOME_DOL";
	clustersToShowArr = clustersToShow.split(",");
	for (i in clustersToShowArr)	//This loop is to disable all the clusters except the cluster selected
	{
    		text = clustersToShowArr[i];
		if(text!=selectedCluster)
		{
			if(text=="AGE" || text=="HEIGHT" || text=="INCOME" || text=="INCOME_DOL")
			{
				disbaleSlider(text);
			}
			else
			{
				$("#"+text+"div :input").attr("disabled", true);
				var more_id = "#"+text+"More";
				if($(more_id).length > 0)
				{
					$(more_id).removeAttr('href');
					$(more_id).addClass('disable_href');
				}
			}
		}
		else
		{
			lateDisableCluster = text;
			var more_id = "#"+text+"More";
			if($(more_id).length > 0)
			{
				$(more_id).removeAttr('href');	
				$(more_id).addClass('disable_href');
			}
		}

		//$("#"+text+"loader").html('<img src="IMG_URL/images/searchImages/loader_extra_small.gif">')
		//var img = "IMG_URL/images/searchImages/loader_extra_small.gif";
		$("#"+text+"loader").css('background','url("IMG_URL/images/searchImages/loader_extra_small.gif")');
		
	}
	/* diabling all cluster excepth the choosen one */


        /* positioning the loader at the center of page*/
        var leftPos , topPos , diff;
        leftPos = $('.container_layer').offset().left +($('.container_layer').width() - $('.searchResultsLoader').width())/2;

        var scrollTop = $(document).scrollTop();
        var offsetTop = $('.container_layer').offset().top;
        if(offsetTop>scrollTop)
                diff = offsetTop - scrollTop;
        else
                diff = 0;
        var wHeight = $(window).height();
        var hLayer = $('.searchResultsLoader').height();
        topPos = wHeight - ((wHeight-diff)/2) - hLayer/2;
        $('.searchResultsLoader')
            .css('position','fixed')
            .css('left',leftPos)
            .css('top',topPos);
        $('#searchResultsLoader').show();
        $('#foregroundImage1').show();
        /* positioning the loader at the center of page*/
}

function sliderPointerActions(x)	//This function handles the mousedown event on the slider pointers
{
	clearTimeout(clearTimedOutVar);
	if((x=="leftPointerHeight" || x=="leftPointerAge") && ($("#value_"+x).text()==$("#value_"+x).text()))	//Case when pointers are overlapped
	{
		if(x=="leftPointerHeight" && $("#value_"+x).text()!="4'0\"")	//If height pointers are overlapped but the values is not 4'0" then move the left pointer to left by 2 pixels
		{
			var value = $("#"+x).css("left");
			value = value.substr(0,value.length-1);
			value = parseFloat(value)-2;
			value = value.toString()+"%";
			$("#"+x).css("left",value);
		}
		else if(x=="leftPointerAge" && $("#value_"+x).text()!="18")	//If age pointers are overlapped but the values is not 18 then move the left pointer to left by 2 pixels
		{
			var value = $("#"+x).css("left");
			value = value.substr(0,value.length-1);
			value = parseFloat(value)-2.5;
			value = value.toString()+"%";
			$("#"+x).css("left",value);
		}
	}
	$('body').one('mouseup',function() {
		if(x=="leftPointerIncomeRupee" || x=="rightPointerIncomeRupee")		//If mousedown is on rupee slider then disable dollar slider
			disbaleSlider("INCOME_DOL");
		else if(x=="leftPointerIncomeDollar" || x=="rightPointerIncomeDollar")	//If mousedown is on dollar slider then disable rupee slider
			disbaleSlider("INCOME");
		incomeParams(x);
	});
}

function incomeParams(x)		//Thsi function is used to submit the page when any of the sliders is selected
{
	var sliderObj;
	//Create the slider object depending on the slider selected
        if(x=="leftPointerHeight" || x=="rightPointerHeight")
                sliderObj = $('#SliderHeight');
        else if(x=="leftPointerAge" || x=="rightPointerAge")
                sliderObj = $('#SliderAge');
        else if(x=="leftPointerIncomeRupee" || x=="rightPointerIncomeRupee")
                sliderObj = $('#SliderRupee');
        else if(x=="leftPointerIncomeDollar" || x=="rightPointerIncomeDollar")
                sliderObj = $('#SliderDollar');

	var clusterName = sliderObj.attr('name');
        var clusterVal = new Array(sliderObj.attr('value').replace(";","$"));	//Get the values

	if(x=="leftPointerHeight" || x=="rightPointerHeight")
	{
		var heightArr = clusterVal[0].split("$");
		if(heightArr[1]==26)	//If 6'0"+is selected
			heightArr[1]=37;
		clusterVal[0] = heightArr.join("$");
	}
	else if(x=="leftPointerAge" || x=="rightPointerAge")
	{
		var ageArr = clusterVal[0].split("$");
		if(ageArr[1]==56)	//If 55+ is selected
			ageArr[1]=70;
		clusterVal[0] = ageArr.join("$");
	}
        else if(x=="leftPointerIncomeRupee" || x=="rightPointerIncomeRupee")
        {
                var incomeArr = clusterVal[0].split("$");
		incomeArr[0] = currencyMappingHtml(incomeArr[0],"R");		//Get corresponding value
		incomeArr[1] = currencyMappingHtml(incomeArr[1],"R");		//Get corresponding value
                clusterVal[0] = incomeArr.join("$");
        }
        else if(x=="leftPointerIncomeDollar" || x=="rightPointerIncomeDollar")
        {
                var incomeArr = clusterVal[0].split("$");
		incomeArr[0] = currencyMappingHtml(incomeArr[0],"D");		//Get corresponding value
		incomeArr[1] = currencyMappingHtml(incomeArr[1],"D");		//Get corresponding value
                clusterVal[0] = incomeArr.join("$");
        }

	clusterLoader(clusterName);

        var myJsonString = JSON.stringify(clusterVal);
	setFormValues(clusterName,myJsonString,'json');
	clearTimeout(clearTimedOutVar);
	clearTimedOutVar = setTimeout(function(){ submitClusterForm() },2000);
}

function currencyMappingHtml(val,type)		//This function returns the actual value of selected income. This actual value is the value present in the database
{
        if(type == "R")
                val = income_arr_rupee_mapping_html[val];
        else if(type == "D")
                val = income_arr_dollar_mapping_html[val];
        return val;
}

function disbaleSlider(param)	//This function disables the slider on the basis of the param passed for AGE or HEIGHT or INCOME or INCOME_DOL
{
	if(param=="AGE")
	{
		$("#age_overlap_slider").show();
		$("#leftPointerAge").fadeTo("fast",0.6);
		$("#rightPointerAge").fadeTo("fast",0.6);
		$("#barAge").fadeTo("fast",0.4);
	}
	else if(param=="HEIGHT")
	{
		$("#height_overlap_slider").show();
		$("#leftPointerHeight").fadeTo("fast",0.6);
		$("#rightPointerHeight").fadeTo("fast",0.6);
		$("#barHeight").fadeTo("fast",0.4);
	}
	else if(param=="INCOME")
	{
		$("#rupee_overlap_slider").show();
		$("#leftPointerIncomeRupee").fadeTo("fast",0.6);
		$("#rightPointerIncomeRupee").fadeTo("fast",0.6);
		$("#barIncomeRupee").fadeTo("fast",0.4);
	}
	else if(param=="INCOME_DOL")
	{
		$("#dollar_overlap_slider").show();
		$("#leftPointerIncomeDollar").fadeTo("fast",0.6);
		$("#rightPointerIncomeDollar").fadeTo("fast",0.6);
		$("#barIncomeDollar").fadeTo("fast",0.4);
	}
}

$(".loaderDiv").click(function () {
cluster_collapse_expand(this.id+"er");
});
/*
$(".ico-collapse").click(function () {
cluster_collapse_expand(this.id);
});
$(".ico-shrunk").click(function () {
cluster_collapse_expand(this.id);
});
*/
function cluster_collapse_expand(clickedId)
{
	var collapseId = "#"+clickedId+"_collapse";
	$(collapseId).slideToggle("slow");
	var changeCssId = "#"+clickedId;
	var className = $(changeCssId).attr('class');
        /* JSM-459
	if(className=='ico-collapse')
		$(changeCssId).removeClass('ico-collapse').addClass('ico-shrunk');
	else
		$(changeCssId).removeClass('ico-shrunk').addClass('ico-collapse');
        */
	if(collapseId=="#AGEloader_collapse")	//If Age cluster is toggles
	{
		if($(collapseId).css("display")=="block")	//If Age cluster is made visible
		{
			if(ageFlag==0)		//If Age cluster is being created for the 1st time
				createSlider("AGE");
		}
		
	}
	else if(collapseId=="#HEIGHTloader_collapse")	//If Height cluster is toggled
	{
		if($(collapseId).css("display")=="block")	//If Height cluster is made visible
                {
			if(heightFlag==0)	//If Height cluster is being created for the 1st time
				createSlider("HEIGHT");
                }
	}
	else if(collapseId=="#INCOMEloader_collapse")	//If Income cluster is toggled
	{
		if($(collapseId).css("display")=="block")	//If Income cluster is made visible
                {
			if(incomeFlag==0)	//If Income cluster is being created for the 1st time
				createSlider("INCOME");
                }
	}
}
/*** clusters ****/

var selectProfile=new Array();
var stopNextCall=0;
var searchImagePath='IMG_URL/images/searchImages/';
var preUrl=SITE_URL+"/profile/";
var postURL={"eoi":"/contacts/PostEOI","view_contact":"/contacts/PreContactDetails","call_directly":preUrl+"call_directly.php",'accept':"/contacts/PostAccept",'decline':"/contacts/PostNotinterest",'acceptdeline':"junk"};
var calldirect_url= preUrl+"call_directly.php";
eoi_cm='';
var start={};
var end={};
start['eoi']=eoi_cm+'<a class="blink b">Express Interest</a>';
end['eoi']=eoi_cm+'<a class="gryout">Interest Expressed</a>';
if(eoiButton=="E")
{
	start['eoi']='<a class="blink b" ><input type="button"  class="btn_view b" value="Express Interest" style="cursor:pointer" /></a>';
	
}
end['view_contact']=start['view_contact']='<a class="blink">See phone/Email</a>';

end['multi']=start['multi']='<input type="button" class="multibutton btn_view b fl"  value="Express Interest" id="multibutton">&nbsp;<i class="arrow-down"></i>';
end['bottom']=start['bottom']='<input type="button" class="multibottom btn_view b fl" value="Express Interest" >&nbsp;<i class="arrow-up"></i> &nbsp;';

var clickHolderHeight=($(document).height()-50)+"px";
var clickHolderWidth=($(document).width()-50)+"px";
var typeOfCont=Array("S","M");
//var leftLess=";left:-140px;top:28px;";
var leftLess=";left:-200px;top:28px;";
var leftMore=";left:-210px;top:28px;";
var toDo={eoi:{0:"eoi",1:'profilechecksum',2:leftLess,3:leftLess},view_contact:{0:"view_contact",1:'profilechecksum',2:leftMore,3:leftLess},acceptdecline:{0:"acceptdecline",1:'profilechecksum'}};
var ERRORMES=new Array();
ERRORMES[0]="Please select atleast one profile and Express Interest";
var LayerMes='<div class="divHeading lh19 white b" style="z-index:99"> <i class="LAYER_ICON">&nbsp;</i>TODO_HEADLINE</div><div style="position:absolute;z-index:89;MARGIN_TOPLEFT">TEXT_TO_SHOW</div>';

var searchIconJson={eoi:'',multi:'ico_white_arrow',view_contact:'',bottom:'ico_white_arrow'};
var searchHeadlineJson={eoi:{0:'Express Interest',1:'Interest Expressed'},view_contact:{0:'See Phone/Email',1:'See Phone/Email'}};
var TYPE_OF='TYPE_OF';var ERROR='ERROR';var ID='ID';var senders_data='senders_data';var to_do='to_do';var MESSAGE='MESSAGE';var URL="URL";


var postData={};
/** contact engine on search starts here **/

$('div[id^="eoi_"]').bind("click",function(){
        ShowContactOnSearch(this,toDo["eoi"][0]);
});
$('div[id^="view_contact_"]').bind("click",function(){
        ShowContactOnSearch(this,toDo["view_contact"][0]);
});


var acceptdecline="";
$('div[id^="acceptdecline_"]').bind("click",function(){
        ShowContactOnSearch(this,toDo["acceptdecline"][0]);
});

$('input[id^="accept_"]').bind("click",function(){
	
        acceptdecline="A";
});
$('input[id^="decline_"]').bind("click",function(){
	
        acceptdecline="D";
});

updateClickHolder(false);

var Loader={small:searchImagePath+"/loader_small.gif",xsmall:searchImagePath+"/loader_extra_small.gif"};
function bindEoiContact(id)
{
	$('#eoi_'+id).unbind("click");
	$('#eoi_'+id).bind("click",function(){
        ShowContactOnSearch(this,toDo["eoi"][0]);
});
$('#view_contact_'+id).unbind("click");
$('#view_contact_'+id).bind("click",function(){
        ShowContactOnSearch(this,toDo["view_contact"][0]);
});
}
function ShowContactOnSearch(ele,typeofcontact)
{
	if(checkVerifyLayer(typeofcontact))
	{
		var actId=ele.id;
		$("#"+actId).unbind("click");
		var remPart=actId.replace(typeofcontact+"_","");
		postData[actId]=getReceivers(remPart,typeofcontact);
		if(showError(postData,actId,''))
			return;
			
		postData=UpdatePostData(postData,actId,typeofcontact);
		show_loader(actId,"xsmall");
		
		PostRequest(postData,actId);
	}
}

function PostRequest(postData,id)
{
	
	var ce_url=postData[id].URL;
	$.ajax({
						url: ce_url,
						type: "POST",
						data: postData[id],
						success: function(result){
						AfterPostRequest(postData,id,result);
						}
				});
}
function AfterPostRequest(postData,id,result)
{
	
		postData[id][ERROR]=checkerrors(result);
		if(showError(postData,id,''))
			return;
		if(postData[id].to_do=='eoi')
				AfterEoi(postData,id,result);
		else if(postData[id].to_do=='view_contact')
				AfterContact(postData,id,result);
		else if(postData[id].to_do=="acceptdecline")
				AfterAcceptDecline(postData,id,result);
			
}
function AfterAcceptDecline(postData,id,result)
{
	  var divid=postData[id].ID;
	  var idRes='';
	  if(dID("view_contact_"+divid))
			idRes=dID("view_contact_"+divid);
	  
		var mes={"D":"You have declined "+himher+" and chosen not to communicate."};
		if(FREE_TRIAL_OFFER=="c")
			mes["A"]="You have sent "+himher+" your Acceptance... To include contact details in your message <a href='/fto/offer?profilechecksum="+postData[id]["profilechecksum"]+"' class='b'>Get the Free Trial Offer</a>";
			else if(membershipStatus=="free" && FREE_TRIAL_OFFER!='d' && membershipStatus!="paid")
			mes["A"]="You have sent "+himher+" your Acceptance... To include contact details in your message <a href='/profile/mem_comparison.php?from_source=search_accept' class='b'>Become a Paid Member</a>";
		else
				mes["A"]="You have sent "+himher+" your Acceptance... <a href='#' onclick='return AfterAcceptDetails(\""+divid+"\",\""+toDo["view_contact"][0]+"\");' class='b'> See Phone/Email</a>";
		$("#acceptdecline_"+divid).html("<div>"+mes[postData[id].status]+"</div>");
		
}
function AfterAcceptDetails(id,to)
{
	ShowContactOnSearch(dID("view_contact_"+id),to);
	return false;
}
function AfterContact(postData,id,result)
{
	updateLayer(postData,id,result,1);
	bindThickbox();
}
function CallDirectOnSearch(postData,id,both_users)
{
				id="view_contact_"+id;
        postData[id][URL]=postURL["call_directly"];
        show_loader("shift_mes_"+postData[id].ID,"small");
        postData[id]['both_users']=both_users;
        postData[id]['show_con']=1;
        PostRequest(postData,id);
}
function AfterEoi(postData,id,result)
{
	if(typeof(postData[id].MESSAGE)!='undefined')
	{
		var text=$("#inform_mes").html();
		text=text.replace("INFORM_MESSAGE","Your message has been sent");
		text=text.replace("ICON_CSS","ico_right");
	}
	else
	{		
		var text=$("#"+membershipStatus+"_eoi").html();
		
	}	
		text=text.replace(/PROFILEID/g,postData[id].ID);
		text=text.replace(/SEND_MESSAGE_FUNC/g,"SendMessage(this,postData,'"+id+"')");
		if(postData[id].ID=='multi' || postData[id].ID=='bottom')
		{
				text=text.replace(/HIMHER/g,"them");
				text=text.replace(/HESHE/g,"they");
				text=text.replace(/THISTHESE/g,"these members");
				text=text.replace(/ACCEPTS/g,"accept");
				
		}		
		else
		{
				text=text.replace(/HIMHER/g,himher);
				text=text.replace(/HESHE/g,heshe);
				text=text.replace(/THISTHESE/g,"this member");
				text=text.replace(/ACCEPTS/g,"accepts");
		}		
				
		var cnt=(postData[id].ID=='multi' || postData[id].ID=='bottom')?(" in "+selectProfile.length+" members"):("in this member");
		
		text=text.replace(/MESSAGE_SUCCESS/g,"You have successfully expressed interest "+cnt);	
			
		updateLayer(postData,id,text,1);
	
	
}
function checkerrors(result)
{
	var tempResult=result.replace(/[\n\r]+/, '');
	if(tempResult=="Login")
	{
		handleLoginLayer();
			return "Please login to continue.";
		}
		if(tempResult.substr(0,5)=='ERROR' || tempResult=="A_E")
			if(tempResult=="A_E")
				return errorMes;
		else
			return tempResult.substr(6,result.length);
	return "";
}
function UpdatePostData(postData,id,typeofcontact)
{
	var randNumber=Math.round(Math.random()*1000);
	var s_type=stype;
	if($("#stype_"+postData[id].ID).val())
		s_type=$("#stype_"+postData[id].ID).val();
	if($("#responseTracking"+postData[id].ID).val())
		s_type=$("#responseTracking"+postData[id].ID).val();

var draft='';
	if(typeofcontact=='eoi')
		draft=presetEoiMessage;
	if(typeofcontact=="acceptdecline")
			if(acceptdecline=="A")
				draft=presetAccMessage;
			if(acceptdecline=="D")
				draft=presetDecMessage;
	if(s_type=="CO")
	{
		commonData={ajax_error:2,stype:s_type,rand:randNumber,ONLY_LAYER:1,"page_source":"VSM","draft":draft};
	}
	else
	{
		commonData={ajax_error:2,from_search:1,fromNewSearch:1,stype:s_type,rand:randNumber,ONLY_LAYER:1,searchId:searchId,"page_source":"search","draft":draft,responseTracking:responseTracking};
	}
	jQuery.extend(postData[id], commonData);
	return postData;
}
function showError(postData,divid,result)
{
	
	if(postData[divid][ERROR]!="")
	{
		//Error html
		id=postData[divid].ID;
		text=$("#inform_mes").html();
		text=text.replace(/PROFILEID/g,id);
		text=text.replace(/ICON_CSS/g,"ico_wrong_big");
		text=text.replace(/INFORM_MESSAGE/g,postData[divid].ERROR);
		closeLayer=closeLayerFnc(postData,divid);
		text=text.replace(/CLOSE_FUNC/g,closeLayer);
		updateLayer(postData,divid,text,0);
		return true;
	}
	return false;
}
function updateLayer(postData,id,text,pre)
{
	//Button html
		postStr=LayerMes;
		var icon=searchIconJson[postData[id].ID]?searchIconJson[postData[id].ID]:searchIconJson[postData[id].to_do];
		var headline=searchHeadlineJson[postData[id].to_do][pre];
		closeLayer=closeLayerFnc(postData,id);
		
		text=text.replace(/CLOSE_FUNC/g,closeLayer);
		postStr=postStr.replace("TEXT_TO_SHOW",text);
		postStr=postStr.replace("LAYER_ICON",icon);
		postStr=postStr.replace("TODO_HEADLINE",headline);
		postStr=postStr.replace("PROFILEID_ID",postData[id].ID);
		divid=postData[id].to_do+"_"+postData[id].ID;
		$("#"+divid).css('position','relative');
		$("#"+divid).css('z-index','99');
		//If from links
		if(postData[id].ID!='multi' && postData[id].ID!='bottom')
		{
				var margintopleft=toDo[postData[id].to_do][2];
				if(postData[id].ERROR)
					margintopleft=toDo[postData[id].to_do][3];
				postStr=postStr.replace("MARGIN_TOPLEFT",margintopleft);
		}
		$("#"+id).html(postStr);
		var messageBox=$("#textarea_"+postData[id].ID);
                if(messageBox.val())
                {
                        messageBox.val(removeJunk(messageBox.val()));
                }	
		
		updateClickHolder(true);
		$(".divlinks .thickbox").unbind();
	        $(".divlinks .thickbox").bind("click",function(){$.colorbox({href:this.href});return false});
		
}

function getReceivers(data,typeofcontact)
{
	
	selectProfile=new Array();
	var recData={TYPE_OF:typeOfCont[0],to_do:typeofcontact,ID:data,ERROR:"",URL:postURL[typeofcontact]};
	if(typeofcontact=='acceptdecline')
	{
		recData[TYPE_OF]='CI';recData["status"]=acceptdecline;
		recData.URL=postURL["decline"];
		if(acceptdecline=='A')
			recData.URL=postURL["accept"];
		
	}
	if(data=='multi' || data=='bottom')
	{
		 var checkboxes=$('input[id^="checkbox_"]');
		 var st=0;
		 for(var i=0;i<checkboxes.length;i++)
		 {
			 if(checkboxes[i].checked)
			 {
				 selectProfile[st]=(checkboxes[i].id).replace("checkbox_","");
				 st++;
			 }
		 }
		 if(st>0)
		 {
			 recData[toDo[typeofcontact][1]]=selectProfile.join();
			 if(st>1)
				recData[TYPE_OF]=typeOfCont[1];
			}
			else
			{
				recData[ERROR]=ERRORMES[0];
				recData[TYPE_OF]=typeOfCont[1];
			}
	}
	else
	{
		recData[toDo[typeofcontact][1]]=data;
	}
	return recData;
}


function SendMessage(ele,postData,divid)
{
        var id_str=ele.id;
        id=id_str.replace("send_","");
        var textmessage=$("#textarea_"+id).val();
        var ce_url=postURL[postData[divid].URL];
        postData[divid]["draft"]=textmessage;
        postData[divid]["MESSAGE"]=1;
        postData[divid]["URL"]="/contacts/postSendReminder";
	textWritten=textmessage.replace( /[\s\n\r]+/g, '');
	if(!textWritten)
	{
		CloseLayer(postData,divid,event);
	}
	else
	{
	        PostRequest(postData,divid);
        	show_loader("mes_"+postData[divid].ID,"small");
	}
}
function confirmMessage(postData,id,result)
{
	postData[id][ERROR]=checkerrors(result);
		if(showError(postData,id,''))
			return;
			
}
function show_loader(id,typeOfLoader)
{
	var loader="<div><img src='"+Loader[typeOfLoader]+"'></div>";
	
	$("#"+id).html(loader);

}
function CloseLayer(postData,id,e)
{
	divid=postData[id].to_do+"_"+postData[id].ID;
	var arrayToUse=start;
	if(!postData[id].ERROR)
		var arrayToUse=end;
	var text=arrayToUse[postData[id].ID];
	if(!text)
		text=arrayToUse[postData[id].to_do];
	
        $("#"+divid).html(text); 
        $("#"+divid).css('z-index','');
        $("#"+divid).css('position','');
	
  $("#"+divid).bind("click",function(){
        ShowContactOnSearch(this,postData[id].to_do);
});
  updateEoiLinks(postData,id,text);
if(!e) e=window.event;
	if (e.stopPropagation)    e.stopPropagation();
 if (e.cancelBubble!=null) e.cancelBubble = true;
closeLayer="";
updateClickHolder(false);
return false ;
}
function updateEoiLinks(postData,id,text)
{
	
	if(postData[id].to_do=="eoi" && !postData[id].ERROR)
	{
		profChecksum=postData[id]['profilechecksum'];
		sendersArry=profChecksum.split(",");
		for(i=0;i<sendersArry.length;i++)
		{
			$("#eoi_"+sendersArry[i]).html(end['eoi']);
			$("#checkbox_"+sendersArry[i]).attr("checked",false);
			$("#checkbox_"+sendersArry[i]).css("visibility","hidden");
			$("#eoi_"+sendersArry[i]).unbind("click");
		}
	}
}
function updateClickHolder(isTrue,event)
{
	
        if(isTrue)
        {
                $("#clickHolder").css("height",clickHolderHeight);
                $("#clickHolder").css("width",clickHolderWidth);
        }
        else
        {
                if(closeLayer)
                {
                        eval(""+closeLayer);
                        closeLayer="";
                }
                
                $("#clickHolder").css("height","0px");
                $("#clickHolder").css("width","0px");
        }
}
function updatetextarea(ele)
{
	
        var id_str=ele.id;
        id=id_str.replace("drafts_","");
       	
        var message=MESCE[ele.value];
	message = removeJunk(message);
        $("#textarea_"+id).val(message);
}
var span_layer_id="";
function openChatWindow(aJid,param,profileID,userName,have_photo,checksum){
        //alert("login or not>>>>~$LOGIN`");
        if(user_login=="")
        {
                if(span_layer_id)
               span_layer_id.style.zIndex=0;
                var after_login_call="openChatWindow('"+aJid+"','"+param+"','"+profileID+"','"+userName+"','"+have_photo+"','"+checksum+"')";
                $.colorbox({href:"/static/registrationLayer?pageSource=searchpage"});
                return true;
        }
        //alert("top.ajaxChatRequest is >>>>>"+top.ajaxChatRequest);
        if(top.ajaxChatRequest){
                have_photo="";
                checksum="";
                top.ajaxChatRequest(aJid,param,profileID,userName,have_photo,checksum);
        }else{
                alert("To initiate chat or receive chat request,chat bar should be at the bottom of the page.");
        }

}
function closeLayerFnc(postData,id)
{
	var str="CloseLayer(postData,'"+id+"',event)";
	return str;
}
function checkVerifyLayer(typeofcontact)
{
	if(typeofcontact==toDo["view_contact"][0] && PH_UNVERIFIED_STATUS)
	{
		search_ph_verify_layer("CONTACT");
    return false;
	}
	if(typeofcontact==toDo["eoi"][0] && SHOW_UNVERIFIED_LAYER)
	{
		search_ph_verify_layer("EOI");
    return false;
	}
	return true;
}
function close_all_con_layer()
{
	updateClickHolder(false);
}
//dummy function, required to full fill
function hide_exp_layer()
{
return false;	
}
//Verify check
function search_ph_verify_layer(page)
{
	var url2 = '/profile/myjs_verify_phoneno.php?sourcePage='+page+'&flag=1&fromNewSearch=1&searchId='+searchId;
	$.colorbox({href:url2});
}

		function shortl(idVal)
		{
			var bookmarkee = idVal.replace("shortlist","");
			var bookmarkeeChecksum = $("#checksum"+bookmarkee).val();

			var shortlistLayerInit = "<div class='fr divHeading lh19 white b' >\
						Shortlist\
						</div>\
						<div class='divlinks fl'  style='width:241px!important;_width:233px;height:220px;'  >\
							<div style='text-align:center;margin-top:80px;'>\
								<img src=IMG_URL/images/loader_big.gif >\
							</div>\
							<div class='fr b' style='margin-top:60px;'>\
								<a href='#' name='closeshortlist' ";
			shortlistLayerInit += "id=close"+idVal+" >\
									Close [x]\
								</a>\
							</div>\
						</div>";

			layerId = "#open"+idVal;
			$(layerId).html(shortlistLayerInit);
			$(layerId).show();
			bindClicks();
			shortlistLayer1 = "<div class='fr divHeading lh19 white b' >\
					Shortlist\
				</div>\
				<div class='divlinks fl'  style='width:241px!important;_width:233px'  >\
					<div class='sp15'>\
					</div>";
			shortlistLayer1 += "<div id=shortlistLoad"+idVal.replace("shortlist","") +" >\
					<i class='ico_right_1 fl'></i>\
					<div id=shortlistMsg"+idVal.replace("shortlist","") +">Successfully shortlisted\
					<div class='sp15'>\
						&nbsp;\
					</div>\
					<span >\
						Add a note for your future reference\
					</span>\
					<span>\
						<textarea   rows='0' cols='0'  class='width100' style='height:55px' ";
			shortlistLayer1 += " id=shortlistNote"+idVal.replace("shortlist","")+" ></textarea>\
					</span>\
					<div class='sp5'>\
					</div>\
					<div class='txt_center'>\
						<input type='button'  class='btn_view b widthauto' value='Add Note' name='addShortlist' style='cursor:pointer;'";
			shortlistLayer1 += " id=add"+idVal+" >\
					</div></div>\
						<div class='sp15'>\
						</div>\
						<a  oncontextmenu='return false;' href='#' name='forwardProfile' ";
			shortlistLayer1 += " id= forwardProfile" + idVal.replace("shortlist","") +" >\
							Forward this profile to your friend or family \
						</a>\
						</div>\
						<div id=shortlistLoadingDiv"+idVal.replace('shortlist','') +" style='text-align:center;margin-top:80px;display:none;height:100px;'>\
							<img src=IMG_URL/images/loader_big.gif >\
						</div>\
						<div class='separator fl width100'>\
						</div>\
						<div class='fr b'>\
							<a href='#' name='closeshortlist' ";
			shortlistLayer1 += "id=close"+idVal+" >\
								Close [x]\
							</a>\
						</div>\
					</div>";
			$.ajax(
			{
				url: "/common/addBookmark/"+bookmarkeeChecksum,
				success: function(response)
				{
					if(response == 'success')
					{
						$(layerId).html(shortlistLayer1);
						bindClicks();
						closeLayer="handleCloseShortlist('"+layerId+"',1)";
						updateClickHolder(true);
					}
					else
					{
//						$(layerId).css("z-index","100");
						$(layerId).hide();
						var url2 = "/static/newLoginLayer?searchId="+searchId+"&currentPage="+currentPage;
						$.colorbox({href:url2});
					}
				}
			});
			return false;
		
}

var text_val = $("#textarea_PROFILEID").val();
if(text_val)
{
	text_val = removeJunk(text_val);    
	$("#textarea_PROFILEID").val(text_val);
}

