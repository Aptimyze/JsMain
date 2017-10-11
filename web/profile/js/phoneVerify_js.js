//esha siteurl
var verifyText="verify";
var saveVerifyText="Save & verify";
var smsNumber='9870803838';
var patternIsd=/^([+]{0,1}[0-9]{1,3})$/;
var mdnc,ldnc,adnc;
var sourcepageValue;
var savedMobile,savedLandline,savedAlternate,savedStd;
var vcode;
var newStd,newMobile,newLandline,newAlternate;
var virtualNumber;
var unique;
var tickHideNumbers;
var OFFER_CALLS;
var edit=0;
var any_verified;
var mob_status,landl_status,alt_status;
var isd;
var inboundApiForDncStatus,outboundApiStatus,smsVerificationApiStatus,opsVerificationStatus;
var limit;
var phoneVerified;
var intervalId;
var result;
var mobileValid, landlineValid, alternateValid;
var mandatory;
var noOfAjaxRequestAllowed = 18;
var timeoutId;
var cBoxObject;
function setVariables()
{
	inboundApiForDncStatus=document.getElementById('inboundApiForDncStatus').value;
	outboundApiStatus=document.getElementById('outboundApiStatus').value;
	smsVerificationApiStatus=document.getElementById('smsVerificationApiStatus').value;
	opsVerificationStatus=document.getElementById('opsVerificationStatus').value;
	virtualNumber=document.getElementById('VIRTUAL_NUMBER').value;
	any_verified=document.getElementById('any_verified').value;
	OFFER_CALLS=document.getElementById('OFFER_CALLS').value;
	tickHideNumbers=document.getElementById('tickHideNumbers').value;
	unique=document.getElementById('unique').value;
	sourcepageValue=document.getElementById('sourcepage').value;
	mdnc=document.getElementById('mdnc').value;
	ldnc=document.getElementById('ldnc').value;
	adnc=document.getElementById('adnc').value;
	vcode=document.getElementById('vcode').value;
	mob_status=document.getElementById('mob_status').value;
	landl_status=document.getElementById('landl_status').value;
	alt_status=document.getElementById('alt_status').value;
	savedMobile=document.getElementById('mymobile').value;
	savedStd=document.getElementById('mystd').value;
	savedLandline=document.getElementById('mylandline').value;
	savedAlternate=document.getElementById('alt_mobile').value;
	isd=document.getElementById('ISD').value.replace(/^[0]+/g,"");
	mobileValid=document.getElementById('mobile_valid').value;
	landlineValid=document.getElementById('landline_valid').value;
	alternateValid=document.getElementById('alternate_valid').value;
	limit =0;
}

function checkboxSetting()
{
	if(unique=='Y'&& (mobileValid=='Y'|| landlineValid=='Y'||alternateValid=='Y'))
	{
		document.getElementById('checkbox1').style.display="none";
		document.getElementById('checkbox2').style.display="none";
		document.getElementById('dot1').style.display="inline";
		document.getElementById('dot2').style.display="inline";
		document.getElementById('blankline').style.display="block";
		document.getElementById('checkLine1').innerHTML="&nbsp;Use phone settings to hide your number";
	}	
	else
	{
		document.getElementById('checkbox1').style.display="inline";
		if(tickHideNumbers=='Y')
			document.getElementById('checkbox1').checked=true;
		else
			document.getElementById('checkbox1').checked=false;
		document.getElementById('checkbox2').style.display="inline";
		if(OFFER_CALLS=='U')//unsubscribed
			document.getElementById('checkbox2').checked=true;
		else
			document.getElementById('checkbox2').checked=false;
		document.getElementById('dot1').style.display="none";
		document.getElementById('dot2').style.display="none";
		document.getElementById('blankline').style.display="none";
		document.getElementById('checkLine1').innerHTML="&nbsp;Show my phone number only to members I like";
		//else show checkboxes and hide images and change text both lines
	}

}

function updateVariables()
{
	newMobile=document.getElementById('newmobile').value.replace(/^[0]+/g,"");
	newStd=document.getElementById('newstd').value.replace(/^[0]+/g,"");
	newLandline=document.getElementById('newlandline').value.replace(/^[0]+/g,"");
	newAlternate=document.getElementById('newalternate').value.replace(/^[0]+/g,"");
}
function invalidSettings(type)
{
	showInvalidLine(type);
	hideEditButton(type);
	showBoxBorder(type);
}
function validSettings(type)
{
	hideInvalidLine(type);
	showEditButton(type);
	hideBoxBorder(type);
}
function setButtonValue()
{
	var buttonValue;
	if(mobileValid=='Y')
	{
		document.getElementById('submitMobile').value=verifyText;
		validSettings('M');
	}
	else
	{
		document.getElementById('submitMobile').value=saveVerifyText;
		invalidSettings('M');
	}
	if(landlineValid=='Y')
	{
		document.getElementById('submitLandline').value=verifyText;
		validSettings('L');
	}
	else
	{
		document.getElementById('submitLandline').value=saveVerifyText;
		invalidSettings('L');
	}
	if(alternateValid=='Y')
	{
		document.getElementById('submitAlternate').value=verifyText;
		validSettings('A');
	}
	else
	{
		document.getElementById('submitAlternate').value=saveVerifyText;
		invalidSettings('A');
	}
        var newIsd = document.getElementById('newIsdM').value.replace(/^[0]+/g,"");
        if(newIsd==''|| patternIsd.test(newIsd)==false)
		highlightInvalidIsd('N','N');
}
function hideInvalidLine(type)
{
	if(type=='M')
		document.getElementById('enterValidMobile').innerHTML="&nbsp;";
	if(type=="L")
		document.getElementById('enterValidLandline').innerHTML="&nbsp";
	if(type=='A')
		document.getElementById('enterValidAlternate').innerHTML="&nbsp";


}
function showInvalidLine(type)
{
	if(type=='M')
		document.getElementById('enterValidMobile').innerHTML="Please enter a valid number";
	if(type=="L")
		document.getElementById('enterValidLandline').innerHTML="Please enter a valid number";
	if(type=="A")
		document.getElementById('enterValidAlternate').innerHTML="Please enter a valid number";

}

function highlightInvalid(idName,type)
{
	showInvalidLine(type);
	document.getElementById(idName).className="maroon";
}
function highlightInvalidIsd(type,updatedIsdField)
{
	var newIsd = document.getElementById('newIsdM').value;
	var isdErrorMessage;
	if(newIsd=='')
		isdErrorMessage = "Provide an ISD code";
	else
		isdErrorMessage = "Provide a valid ISD code";
		
	hideInvalidIsd();
	if(type=='N')
		type='M';
	if(type=='M'|| updatedIsdField=='newIsdM')
	{
		document.getElementById('enterValidMobile').innerHTML=isdErrorMessage;
		document.getElementById('enterValidMobile').className="maroon";
	}
	if(type=='L'|| updatedIsdField=='newIsdL')
	{
		document.getElementById('enterValidLandline').innerHTML=isdErrorMessage;
		document.getElementById('enterValidLandline').className="maroon";
	}
	if(type=='A'|| updatedIsdField=='newIsdA')
	{
		document.getElementById('enterValidAlternate').innerHTML=isdErrorMessage;
		document.getElementById('enterValidAlternate').className="maroon";
	}
        hideImageConnect();
}
function hideInvalidIsd()
{
	hideInvalidLine('M');
	hideInvalidLine('A');
	hideInvalidLine('L');
}
function validCheck(type)
{
	limit = 0;
	updateVariables();
	newIsd = document.getElementById('newIsdM').value.replace(/^[0]+/g,"");
	if(newIsd==''|| patternIsd.test(newIsd)==false)
	{
		highlightInvalidIsd(type,'N');
		resetOnEdit(type,'ISD');
		return false;
	}
	else
	{
		hideInvalidIsd();
	}
	if(type=="M" ||type=="A")
	{
		var checkMobileAlternateSame;
		if(newMobile==newAlternate)
			checkMobileAlternateSame="Y";
		else
			checkMobileAlternateSame="N";
	}
	var isdFlag=checkSameNumber("isd");
	if(type=="M")
	{
		var flag=checkSameNumber(type);
		var pattern;
		if(newIsd=="91")
			pattern=/^([7-9]{1}[0-9]{9})$/;
		else
			pattern=/^([1-9]{1}[0-9]{5,13})$/;
		if(pattern.test(newMobile)==false||(flag=="SAME"&& mobileValid=="N" && isdFlag=="SAME"))
		{
			highlightInvalid('enterValidMobile','M');
			resetOnEdit('M','NUM');
		}
		else
			showValidationProcess(type,newMobile,flag,isdFlag);
		//	var urlStr="";
		//	var url=siteUrl+"/profile/myjs_verify_phoneno.php?"+urlStr;
	}
	if(type=="L")
	{
		var flag=checkSameNumber(type);
		var number=newStd+"-"+newLandline;
		var patternStd=/^([0-9]{2,})$/;
		var patternLandline=/^([2-6]{1}[0-9]{4,})$/;
		var patternLandlineIndia=/^([0-9]{10})$/;
		if((patternStd.test(newStd)==false||patternLandline.test(newLandline)==false) ||(flag=="SAME" && landlineValid=="N" && isdFlag=="SAME")||(newIsd=="91"&& patternLandlineIndia.test(newStd+newLandline)==false))
		{
			highlightInvalid('enterValidLandline','L');
			if(patternStd.test(newStd)==false)
				resetOnEdit('L',"STD");
			else
				resetOnEdit('L',"NUM");
				
		}
		else
			showValidationProcess(type,number,flag,isdFlag);
	}
        if(type=="A")
        {
		var flag=checkSameNumber(type);
		var pattern;
		if(newIsd=="91")
			pattern=/^([7-9]{1}[0-9]{9})$/;
		else
                        pattern=/^([1-9]{1}[0-9]{5,13})$/;
                if((pattern.test(newAlternate)==false)||(flag=="SAME" && alternateValid=="N" && isdFlag=="SAME"))
		{
			highlightInvalid('enterValidAlternate','A');
			resetOnEdit('A',"NUM");
		}
		else
			showValidationProcess(type, newAlternate,flag,isdFlag);
        }
return false;
}

function checkSameNumber(type)
{
        var flag;
	if(type=="isd")
	{
		var newIsd = document.getElementById('newIsdM').value.replace(/^[0]+/g,"");
		if(newIsd==isd)
                        flag="SAME";//showDetails(type);
                else
                        flag="DIFF";
	}
        if(type=="M")
        {
                if(savedMobile==newMobile)
                        flag="SAME";//showDetails(type);
                else
                        flag="DIFF";
        }
        if(type=="L")
        {
                if((savedLandline==newLandline)&&(savedStd==newStd))
                        flag="SAME";//showDetails(type);
                else
                        flag="DIFF";//send
        }
        if(type=="A")
        {
                if(savedAlternate==newAlternate)
                        flag="SAME";
                else
                        flag="DIFF";
        }
	return flag;
}
function showValidationProcess(type,number,flag,isdFlag)
{
	if(flag=="SAME" &&isdFlag=="SAME")
	{
		var call;
		if((type=="M" &&mob_status=="Y")||(type=="L" && landl_status=="Y")||(type=="A" && alt_status=="Y"))
			call = "N";
		else
			call = "Y";
		showDetails(type,call);
	}
	else
	{
		initiateVerification(number,type,'Y');
	}
	
}

function initiateVerification(number,type,callAfterFunction)
{
	var newIsd = document.getElementById('newIsdM').value.replace(/^[0]+/g,"");
	var isdFlag=checkSameNumber("isd");
	if(callAfterFunction=='Y')
	{
	        var url=SITE_URL+"/profile/myjs_verify_phoneno.php?number="+number+"&isd="+newIsd+"&phoneType="+type+"&ajax=1&saveNumber=1&sourcePage="+sourcepageValue+"&vcode="+vcode+"&isdFlag="+isdFlag;
		var postData = 
		{ 
			'number': number ,
			'isd': newIsd,
			'phoneType':type ,
			'ajax': 1,
			'saveNumber': 1,
			'sourcePage':sourcepageValue,
			'vcode': vcode,
			'isdFlag': isdFlag
		};
		ajax_req(url,"getResults",postData);
	}
	else
	{
	        var url=SITE_URL+"/profile/myjs_verify_phoneno.php?number="+number+"&isd="+newIsd+"&phoneType="+type+"&ajax=1&sourcePage="+sourcepageValue+"&vcode="+vcode+"&isdFlag="+isdFlag;
		var postData = 
		{ 
			'number': number ,
			'isd': newIsd,
			'phoneType':type ,
			'ajax': 1,
			'sourcePage':sourcepageValue,
			'vcode': vcode,
			'isdFlag': isdFlag
		};
		ajax_req(url,"noCallAfterFunction",postData);
	}
}
function changeSetting(settingType,flag)
{
        var url=SITE_URL+"/profile/myjs_verify_phoneno.php?settingType="+settingType+"&flag="+flag+"&changeSetting=1";
	var postData = 
	{ 
		'settingType': settingType ,
		'flag': flag,
		'changeSetting':1
	};
	
        ajax_req(url,"returnF",postData);
}
function ajax_req(url,afterF,postData)
{
	var request = $.ajax
                        ({
                                url:url,
                                type: "POST",
				data: postData
                                }).done(function(res){
				result =res;
				eval(afterF+'()');
			});
}
function returnF()
{
	if(loginCheck()==true)
		return;
	return false;
}
function noCallAfterFunction()
{
	if(loginCheck()==true)
		return;
	if(mandatory==true)
		checkVerificationStatus();
	return false;
}
function checkVerificationStatus()
{
	if(timeoutId)
		clearTimeout(timeoutId);
	if(intervalId)
		stopAjaxRequests();
	timeoutId = setTimeout(function(){intervalId = window.setInterval(function(){ajaxCallToCheckVerificationUpdate()},5000);},30000);
}
function ajaxCallToCheckVerificationUpdate()
{ 
	if(phoneVerified!='Y')
	{
		var url=SITE_URL+"/profile/phoneVerified.php";
		ajax_req(url,"getVerificationResults");
	}
}
function getVerificationResults()
{
	limit = limit+1;
	if(result)
	{
                if(loginCheck()==true)
                        return;
		resArr=new Array();
                resArr    =result.split("|");
		phoneVerified = resArr[0]; 
		if(resArr[0]=="Y")
		{
			document.getElementById('PHONE_VERIFIED').value = 'Y';
                        var url=SITE_URL+"/profile/phoneVerifiedLayer.htm";
                        $.colorbox({href:url});
			stopAjaxRequests();
			return;
		}
		else 
		{
			if(limit>=noOfAjaxRequestAllowed && intervalId)
			{
				stopAjaxRequests();
				var url=SITE_URL+"/profile/phoneVerifyTimeOut.htm";
				$.colorbox({href:url, overlayClose:false, escKey:false});
			}
		}
	}
}
function stopAjaxRequests()
{
	window.clearInterval(intervalId);
}
function getResults()
{
	if(result)
	{		
	    if(loginCheck()==true)
                        return;
		resArr    =new Array();
                resArr    =result.split("|");
		if(resArr[1]=="INVALID")
		{
			if(resArr[0]=='M')
				highlightInvalid('enterValidMobile','M');
			if(resArr[0]=='L')
				highlightInvalid('enterValidLandline','L');
			if(resArr[0]=='A')
				highlightInvalid('enterValidAlternate','A');
			resetOnEdit(resArr[0],'NUM');
		}
		else
		{
		
			if (resArr[6]=='1') $("#consentMsgDisplay").css('display','block');
		//$phoneType."|".$phone."|".$checkDuplicate."|".$vcode."|".$dncFlag."|".$vNo;
	                hideInvalidLine(resArr[0]);
			if(!vcode)
				vcode=resArr[3];
			isd = newIsd;
			if(resArr[0]=='M')
			{
				savedMobile=resArr[1];
				mdnc = resArr[4];
			}
			if(resArr[0]=='L')
			{	lArray=resArr[1].split("-");
				savedStd=lArray[0];
				savedLandline=lArray[1];
				ldnc = resArr[4];
			}
			if(resArr[0]=='A')
			{
				savedAlternate=resArr[1];
				adnc = resArr[4];
			}
			if(resArr[4]=='Y' && !virtualNumber)
				virtualNumber = resArr[5];
			showDetails(resArr[0],'N');
		}

	}
	if(mandatory==true)
		checkVerificationStatus();
	return false;
}

function loginCheck()
{
                if(result=="#LOGIN")
                {
			var url=SITE_URL+"/profile/login.php?SHOW_LOGIN_WINDOW=1";
                        $.colorbox({href:url});
                        return true;
                }

}
function showEditButton(type)
{
	if(type=='M')
		document.getElementById('editButtonMobile').style.display="inline";
	if(type=='L')
		document.getElementById('editButtonLandline').style.display="inline";
	if(type=='A')
		document.getElementById('editButtonAlternate').style.display="inline";
}

function hideEditButton(type)
{
	if(type=='M')
		document.getElementById('editButtonMobile').style.display="none";
	if(type=='L')
		document.getElementById('editButtonLandline').style.display="none";
	if(type=='A')
		document.getElementById('editButtonAlternate').style.display="none";
}
function hideArrow(type)
{
        if(type=='M'||type=="ALL")
                document.getElementById('arrowMobile').style.display="none";
        if(type=='L'||type=="ALL")
                document.getElementById('arrowLandline').style.display="none";
        if(type=='A'|| type=="ALL")
                document.getElementById('arrowAlternate').style.display="none";
}

function showArrow(type)
{
        if(type=='M')
        {
		hideArrow('ALL');
	        document.getElementById('arrowMobile').style.display="block";
	}
        if(type=='L')
	{
		hideArrow('ALL');
                document.getElementById('arrowLandline').style.display="block";
	}
        if(type=='A')
        {
		hideArrow('ALL');
	        document.getElementById('arrowAlternate').style.display="block";
	}
}
function hideBoxBorder(type)
{
	if(type=="M")
	{
		document.getElementById('newmobile').className="txtBox_nobrd fs16 black textalign";
		document.getElementById('newmobile').readOnly=true;
		
		document.getElementById('newmobile').style.display="none";
		document.getElementById('fixedMobile').innerHTML="<span class='fs15 black'>"+document.getElementById('newmobile').value+"</span>";
		document.getElementById('fixedMobile').style.display="inline";
	}
	if(type=='L')
	{
		document.getElementById('newlandline').className="txtBox_nobrd fs15 black textalign";
		document.getElementById('newlandline').readOnly=true;
		document.getElementById('newstd').className=" fs15 black txtBox_small_nobrd textalign";
		document.getElementById('newstd').readOnly=true;
		document.getElementById('newlandline').style.display="none";
		document.getElementById('newstd').style.display="none";
		document.getElementById('fixedLandline').innerHTML="<span class='fs15 black'>"+document.getElementById('newstd').value+" "+document.getElementById('newlandline').value+"</span>";
		document.getElementById('fixedLandline').style.display="inline";
	}
	if(type=='A')
	{
		document.getElementById('newalternate').className="txtBox_nobrd fs15 black textalign";
		document.getElementById('newalternate').readOnly=true;
		document.getElementById('newalternate').style.display="none";
		document.getElementById('fixedAlternate').innerHTML="<span class='fs15 black'>"+document.getElementById('newalternate').value+"</span>";
		document.getElementById('fixedAlternate').style.display="inline";

	}
}
function showBoxBorder(type)
{
	if(type=='M')
	{
		document.getElementById('newmobile').style.display="inline";
		document.getElementById('fixedMobile').style.display="none";
		document.getElementById('newmobile').className="txtBox fs15 black textalign";
		document.getElementById('newmobile').readOnly=false;
	}
	if(type=='L')
	{
		document.getElementById('newlandline').style.display="inline";
		document.getElementById('newstd').style.display="inline";
		document.getElementById('fixedLandline').style.display="none";
		document.getElementById('newstd').className="fs15 black txtBox_small textalign";
		document.getElementById('newstd').readOnly=false;
		document.getElementById('newlandline').className="txtBox fs15 black textalign";
		document.getElementById('newlandline').readOnly=false;
	}
	if(type=='A')
	{
		document.getElementById('newalternate').style.display="inline";
		document.getElementById('fixedAlternate').style.display="none";
		document.getElementById('newalternate').className="txtBox fs15 black textalign";
		document.getElementById('newalternate').readOnly=false;
	}
}
function resetOnEdit(type,focusType)
{
	hideEditButton(type);
	setButtons(type,'N','Y');
	setButtonText(type);
	showBoxBorder(type);
	focusBox(type,focusType);
	hideArrow('ALL');
	hideRightBlock();
	return false;
}

function focusBox(type,focusType)
{
	if(focusType=="ISD")
	{
		if(type=="M")
			document.getElementById('newIsdM').focus();
		if(type=="L")
			document.getElementById('newIsdL').focus();
		if(type=="A")
			document.getElementById('newIsdA').focus();
	}
	else
	{
		if(type=='M' && focusType=="NUM")
			document.getElementById('newmobile').focus();
		if(type=='L' && focusType=="STD")
			document.getElementById('newstd').focus();
		if(type=='L' && focusType=="NUM")
			document.getElementById('newlandline').focus();
		if(type=='A' && focusType == "NUM")
			document.getElementById('newalternate').focus();
	}
}

function setButtonText(type)
{
	if(type=='M')
		document.getElementById('submitMobile').value=saveVerifyText;
	if(type=='L')
		document.getElementById('submitLandline').value=saveVerifyText;
	if(type=='A')
		document.getElementById('submitAlternate').value=saveVerifyText;
}

function showDetails(type,ivrCall)
{
	showEditButton(type);
	setButtons(type,'Y','N');//	change button to Grey
	hideBoxBorder(type);
	showArrow(type);
	showRightBlock();
	var dialCode;
	var smsCode;
	if(vcode)
	{
		dialCode=vcode;
		smsCode=vcode;
	}
	else
	{
		dialCode='1';
		smsCode='Y'
	}
	if(type=='M')
	{
		dncStatus=mdnc;
		number=document.getElementById('newmobile').value;
	}
	if(type=='L')
	{
		dncStatus=ldnc;
		number=document.getElementById('newstd').value+"-"+document.getElementById('newlandline').value;
	}
	if(type=='A')
	{
		dncStatus=adnc;
		number=document.getElementById('newalternate').value;
	}
	document.getElementById('blockRight').innerHTML="<div class='fs16'>To verify your number,</div><div class='sp16'>&nbsp;</div>";
	if(inboundApiForDncStatus || (dncStatus !='Y' && outboundApiStatus))
	{
		document.getElementById('blockRight').innerHTML+="<div class='fs14 black' style='line-height:1.4'><span class='maroon'>Press "+dialCode+"</span> when you receive call from Jeevansathi.com</div><div class='sp16'>&nbsp;</div>";
		if(ivrCall=='Y')
			initiateVerification(number,type,'N');
	}
	else if(outboundApiStatus && dncStatus=='Y'&& !inboundApiForDncStatus)
	{
		document.getElementById('blockRight').innerHTML+="<div class='fs14 black' style='line-height:1.4'><span class='maroon'>Give a missed call to "+virtualNumber+"</span> from your number "+isd+"-"+number+"</div>&nbsp;";
	if(mandatory==true)
		checkVerificationStatus();
	}
	if(!inboundApiForDncStatus && !outboundApiStatus)
	{
		if(smsVerificationApiStatus && type!='L')
			document.getElementById('blockRight').innerHTML+="<span class='fs14 black' style='line-height:1.4'>SMS <span class='maroon'>"+smsCode+"</span> to <span class='maroon'>"+smsNumber+"</span> <br /> from "+number+"</span><div class='sp16'>&nbsp;</div><div class='fs16'></div> ";
		else
			document.getElementById('blockRight').innerHTML+="<div class='fs16'> Contact customer care at <span class='maroon'>1-800-419-6299</span> or <span class='maroon'>help@jeevansathi.com</span></div>";
	}
}
function sendTrackingInfo()
{
        var url=SITE_URL+"/profile/myjs_verify_phoneno.php?ajax=1&trackOnly=1&sourcePage="+sourcepageValue;
	var postData = 
	{ 
		'ajax': 1 ,
		'trackOnly': 1,
		'sourcePage':sourcepageValue
	};
	ajax_req(url,"returnF",postData);
}

function updateOtherIsdFields(updatedIsdField,type)
{
	var newIsd=document.getElementById(updatedIsdField).value;
	document.getElementById('newIsdM').value = newIsd;
	document.getElementById('newIsdL').value = newIsd;
	document.getElementById('newIsdA').value = newIsd;
        newIsd = document.getElementById('newIsdM').value.replace(/^[0]+/g,"");
        if(newIsd==''|| patternIsd.test(newIsd)==false)
		highlightInvalidIsd('',updatedIsdField);
	else
		hideInvalidIsd();
	setButtons("ALL",'N','Y');
}
function setButtons(type,onclick,onedit)
{
	var newIsd=document.getElementById('newIsdM').value;
	if(type=="M"||type=="ALL")
	{
		var number=document.getElementById('newmobile').value;
		if(number!=savedMobile)
			mob_status='N';
		if((newIsd && number) && ((onclick=='N'&& mob_status!='Y')||onedit=='Y'))//green if number is there
		{
			document.getElementById('submitMobile').className="btnGreen_2";
			document.getElementById('submitMobile').disabled=false;
		}
		else  //grey onclick
		{
			document.getElementById('submitMobile').className="btnGrey_2";
			document.getElementById('submitMobile').disabled=true;
		}
	}
	if(type=="L" || type=="ALL")
	{
		var number1=document.getElementById('newstd').value;
		var number2=document.getElementById('newlandline').value;
		if(number1!=savedStd || number2!=savedLandline)
			landl_status='N';
		if((newIsd && number1 && number2) && ((onclick=='N' && landl_status!='Y') ||onedit=='Y'))
		{
			document.getElementById('submitLandline').className="btnGreen_2";
			document.getElementById('submitLandline').disabled=false;
		}
		else
		{
			document.getElementById('submitLandline').className="btnGrey_2";
			document.getElementById('submitLandline').disabled=true;
		}
	}
	if(type=="A" ||type=="ALL")
	{
		var number=document.getElementById('newalternate').value;
		if(number!=savedAlternate)
			alt_status='N';
		if((newIsd && number) && ((onclick=='N' && alt_status!='Y') || onedit=='Y'))
		{
			document.getElementById('submitAlternate').className="btnGreen_2";
			document.getElementById('submitAlternate').disabled=false;
		}
		else
		{
			document.getElementById('submitAlternate').className="btnGrey_2";
			document.getElementById('submitAlternate').disabled=true;
		}

	}
	return false;
}

function hideRightBlock()
{
	document.getElementById('blockRight').style.display="none";
}

function showRightBlock()
{
        document.getElementById('blockRight').style.display="block";
}

function setCheckboxCondition(idName)
{
	if(idName=="checkbox1")
	{
		if(document.getElementById('checkbox1').checked==true)
		{
			document.getElementById('checkbox1').checked=true;
			changeSetting("hideNumber",'C');
		}
		else
		{
			document.getElementById('checkbox1').checked=false;
			changeSetting("hideNumber",'Y');
		}
	}
	else if(idName=="checkbox2")
	{
		if(document.getElementById('checkbox2').checked==true)
		{
			document.getElementById('checkbox2').checked=true;
			changeSetting("promo_mails",'U');
		}
		else
		{
			document.getElementById('checkbox2').checked=false;
			changeSetting("promo_mails",'S');
		}
	}
return false;
}
function reloadIfProfilePage()
{
	if(document.getElementById('profile_layer'))
	{
		window.location.reload();
	}
	else
	{
		$.colorbox.close();return false;
	}
}
function setClose()
{
	if(mandatory==true)
	{
		$("#close").hide();
		$("#setLineLegacy").hide();
		$("#setLineNew").show();
		
	}
	else
	{
		$("#close").show();
		$("#setLineLegacy").show();
		$("#setLineNew").hide();
	}
}

$(document).ready(function () {
	
    
  
   	if($("#PHONE_VERIFIED").val()!='Y'&& user_login)
	{
		mandatory = true;
		var url=SITE_URL+"/profile/myjs_verify_phoneno.php";
		$.colorbox({href:url, overlayClose:false, escKey:false});
		ajaxCallToCheckVerificationUpdate();
	}
else if(($("#showConsentMsgId").val()=='Y') && user_login)
	{						

				var url=SITE_URL+"/phone/ConsentMessage";
                    		cBoxObject=$.colorbox({href:url, uniqueId:1,overlayClose:false, escKey:false});

							
}
});
