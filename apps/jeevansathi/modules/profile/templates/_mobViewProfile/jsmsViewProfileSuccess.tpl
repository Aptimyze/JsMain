<div id="astroReportLayer" class="overlayAstro dispnone js-astroReportLayer"></div>  
<div id="loadingOverlay" class="loadingOverlay"></div>
~if !isset($errorMsg)`
    <div id="comHistoryOverlay" class="vpro_dn" >
        ~include_partial("profile/mobViewProfile/_communicationHistoryOverlay",['arrData'=>$arrOutDisplay.pic,'username'=>$arrOutDisplay.about.username])`
    </div>
~/if`
<div id='profileContent'>
	~if !isset($errorMsg)`
    ~include_partial("global/jsms3DotLayer")`
    ~include_partial("profile/mobViewProfile/_tabHeader",['username'=>$arrOutDisplay.about.username,'name_of_user'=>$arrOutDisplay.about.name_of_user,'showComHistory'=>$showComHistory,'myPreview'=>$myPreView])`
		~include_partial("profile/mobViewProfile/_imageWidget",['arrData'=>$arrOutDisplay.pic,'gender'=>$arrOutDisplay.about.gender,'myPreview'=>$myPreView,'verificationValue'=>$arrOutDisplay.about.verification_status])`
		<div id="aboutContent">
			~include_partial("profile/mobViewProfile/_basicInfo",['arrData'=>$arrOutDisplay.about])`
			~include_partial("profile/mobViewProfile/_educationAndCareerInfo",['arrData'=>$arrOutDisplay.about])`
			~include_partial("profile/mobViewProfile/_kundaliAndAstroInfo",['arrData'=>$arrOutDisplay.about,'otherProfilechecksum'=>$arrOutDisplay.page_info.profilechecksum])`
			~include_partial("profile/mobViewProfile/_lifestyleInfo",['arrData'=>$arrOutDisplay.lifestyle,'posted_by'=>$arrOutDisplay.about.posted_by])`
<div class="space70" >&nbsp;</div>
		</div>
		<div id="familyContent">
			~include_partial("profile/mobViewProfile/_familyInfo",['arrData'=>$arrOutDisplay.family,'userName'=>$arrOutDisplay.about.username])`
<div class="space70" >&nbsp;</div>
		</div>
		<div id="dppContent">
			~include_partial("profile/mobViewProfile/_dppInfo",['arrData'=>$arrOutDisplay.dpp,'gender'=>$arrOutDisplay.about.gender,'matchingArr'=>$arrOutDisplay.showTicks,'picArr'=>$arrOutDisplay["pic"],'thumbnailPic'=>$arrOutDisplay.about.thumbnailPic,'myPreview'=>$myPreView,'selfThumbnail'=>$arrOutDisplay.about.selfThumbail])`
<div class="space70" >&nbsp;</div>
		</div>
	~/if`
	~if isset($errorMsg)`
		~include_partial("profile/mobViewProfile/_noProfileInfo",['TopUsername'=>$TopUsername,'MESSAGE'=>$MESSAGE,'LOGIN_REQUIRED'=>$LOGIN_REQUIRED,'noProfileIcon'=>$noProfileIcon,'myPreview'=>$myPreView])`
	~/if`
</div>
<div id="buttons1" class="view_ce"></div>
<script>
/* contact buttons and overlay code start*/
var buttonSt = null;
~if isset($headerURLDeepLinking)`
	window.location.href = '~$headerURLDeepLinking|decodevar`';
~/if`
~if isset($arrOutDisplay.buttonDetails)`
try{
    var b = '~$arrOutDisplay.buttonDetails|decodevar`';
	buttonSt = $.parseJSON(b);
   }
   catch(e)
   {
    //Something went wrong   
   }
~/if`
/* contact buttons and overlay code end*/
var previousLink="" , nextLink = "";
var picUrl = "~$arrOutDisplay.pic.url`";
var picCount = "~$arrOutDisplay.pic.pic_count`";
var userName = "~$arrOutDisplay.about.username`";
var selfUsername="~$selfUsername`";
var profileOffset = "~$tupleId`";
var szStype = "~$stype`";
var commHistoryJson = null;
var contactEngineChannel = "VDP";
var szHisHer = "~$szHisHer`";
var isGunnaCallRequire = '~$gunaCallRequires`';
~if isset($BREADCRUMB)`
	var backLink =  '~$BREADCRUMB|decodevar`';
~else if $myPreView eq 1`
    var backLink = 'customBack';
~else`
	var backLink = null;
~/if`

~if $SHOW_NEXT_PREV`
	~if $SHOW_PREV || $SHOW_NEXT`
		~if $SHOW_PREV`
			~if isset($prevLink)`
				previousLink ="/profile/viewprofile.php?~$prevLink|decodevar`&stype=~$stype`&responseTracking=~$responseTracking`~$other_params|decodevar`&~$NAVIGATOR`";
			~else`
				previousLink ="/profile/viewprofile.php?show_profile=prev&total_rec=~$total_rec`&actual_offset=~$actual_offset`&j=~$j`&responseTracking=~$responseTracking`&searchid=~$searchid`~$other_params|decodevar`&~$NAVIGATOR`&tupleId=~$preTupleId`";
			~/if`
		~/if`
		~if $SHOW_NEXT`
			~if isset($nextLink)`
				nextLink ="/profile/viewprofile.php?~$nextLink|decodevar`&stype=~$stype`&responseTracking=~$responseTracking`~$other_params|decodevar`&~$NAVIGATOR`";
			~else`
				nextLink ="/profile/viewprofile.php?show_profile=next&total_rec=~$total_rec`&actual_offset=~$actual_offset`&j=~$j`&responseTracking=~$responseTracking`&searchid=~$searchid`~$other_params|decodevar`&~$NAVIGATOR`&tupleId=~$nextTupleId`";
			~/if`
		~/if`
	~/if`
~/if`

var profileChkSum = "~$arrOutDisplay.page_info.profilechecksum`";
//If Navigator already exist then update it
~if isset($NAVIGATOR)`
    if (typeof NAVIGATOR == 'string'){
        NAVIGATOR = "~$NAVIGATOR`";
    }
    else {
        var NAVIGATOR = "~$NAVIGATOR`";
    }
~/if`

var getNextLink = function()
{
	return nextLink;
}
var getPreviousLink = function()
{
	return previousLink;
}
var getProfileBackLink = function()
{
	return backLink;
}

var getProfileCheckSum = function()
{
    return profileChkSum;    
}
var getProfileOffset = function()
{
    return profileOffset;
}
var getStype = function()
{
    return szStype;
}
var isGunnaCallRequires = function()
{
    return isGunnaCallRequire;
}
var getProfilePicUrl = function()
{
    return picUrl;
}
var getNAVIGATOR = function()
{
    return NAVIGATOR;
}
</script>
