var loaderUrl = "IMG_URL/images/jsms/commonImg/loader.gif";
var loaderBottomDiv ='<div class="fullwid txtc loaderBottomDiv" style="margin: 0 auto;"><a href="#"><img src="'+loaderUrl+'"></a></div><div class="clr"></div>';
var loaderTopDiv ='<div class="fullwid txtc loaderTopDiv" style="margin: 0 auto;margin-top:48px;"><a href="#"><img src="'+loaderUrl+'"></a></div><div class="clr"></div>';

AndroidPromotion = 0;
var CurrentScroll = 0; // scrolling position from top
var relaxationArr;
var loadImageId ="idd1"; // global variable for uploading images
var _TRIGGER_POINT_BOTTOM= 3000;
var _TRIGGER_POINT_TOP= 400;
var timedOut = 1000;
var loadPrevTuple=0;
var contactCenter=0;
var viewSimilar=0;
var fmBack=0;
var noScrollingAction=0;
var reachedEnd=0;
var statusValOfImgLoad=0;
var showECPPage="";
var filteredProfilesHeadShown = 0;
_SEARCH_RESULTS_PER_PAGE=25;
var maxResult = 99; //after 100 Results loader

if(typeof stypeKey !== 'undefined')
		stypeKey="W1";
$(document).ready(function(){
$('body').on('click', '.searchNavigation', function()
	{
			updateHistory(this.attributes.tupleno.value);
	});

	if(parseInt($(".tupleOuterDiv").length)<=(_SEARCH_RESULTS_PER_PAGE)){
		var height = ($(window).height()-20)/2;
		$("div.loaderBottomDiv").addClass("initialLoader fullwid").css("margin-top",height+"px");
	}
	$('body').css("background","#b1b1b1");
    if ( firstResponse.searchid == 23 && firstResponse.total != "0")
    {
    	$("#interestExpiringMessage").removeClass('dispnone');
    }

//        onBackBtnSRP = function()
//        {
//            if (window.location.href.indexOf('search/perform')!=-1  &&
//                window.location.href.indexOf('searchId')!=-1   &&
//                window.location.href.indexOf('fmBack')!=-1
//              ){
//                if(typeof BackButtonPointsHere == 'string' && BackButtonPointsHere.length>0){
//                        window.location.href = BackButtonPointsHere;
//                }
//                else
//                {
//                    history.back();
//                }
//                return true;
//            }
//            return false;
//        }

//    if(typeof backSearchId!="undefined"){
//        BackButtonPointsHere = referHandling(backSearchId,BackButtonPointsHere);
//        if(typeof historyStoreObj == "object"){
//             historyStoreObj.push(onBackBtnSRP,"#SRPPage");
//        }
//    }
});


function showProfilePage(url){
    if(window.location.href.indexOf('/search/MobSimilarProfiles?')!=-1)
        ShowNextPage(url,1);
    else
        ShowNextPage(url,0);
}
/**
* update browser url : will be used for back button functionality.
* @tupleNo ex id1
*/
function updateHistory(tupleNo)
{
	if (window.location.href.indexOf('profile/viewprofile.php')==-1 && window.location.href.indexOf('saveLayer')==-1){
		var sbPar = removeNull(firstResponse.searchBasedParam);
		var addMoreParams = 'searchId='+firstResponse.searchid+'&currentPage=1&searchBasedParam='+sbPar;
		if(viewSimilar==1)
			addMoreParams += '&profilechecksum='+viewedProfilechecksum+'&'+NAVIGATOR;
		if(fmBack==1)
			addMoreParams += '&fmBack=1';
                if(window.location.href.indexOf('matchedOrAll')!=-1)
			addMoreParams += '&matchedOrAll='+window.location.href.split('matchedOrAll=')[1].slice(0,1);
		$.urlUpdateHistory('Search Results Page '+tupleNo,tupleNo,addMoreParams);
	}
	BindNextPage();
}
/***
* This function will act as a trigger to load the data of next/prev search results
*/

function triggerLoader(type,loadPageToLoadId,idToLoad)
{
	//Don't trigger if contact enigne layer is opened.
	if($("#commonOverlay").css("display")=="block" || $("#contactLoader").css("display")=="block" || $("#writeMessageOverlay").css("display")=="block")
		return;

    /*
	if(isLoaderSearch && !idToLoad)
		return;
    */
		//if(idToLoad=='undefined')
		   //return;

	var triggerPoint = $(document).height() - ($(window).scrollTop() + $(window).height());
	if(!isLoading)
	{
		if(loadPageToLoadId)
		{
			loadsNextResult(loadPageToLoadId,idToLoad);
		}
		else if($(window).scrollTop()<=_TRIGGER_POINT_TOP || type=='Prev')
		{

			if(minPage>0)
			{
				if(minPage>1)
				{
					if($('.loaderTopDiv:visible').length==0)
						$(loaderTopDiv).prependTo("#sContainer");
				}
				if(minPage>1)
				{
					var callPage = minPage-1
					loadsNextResult(callPage,'','Prev');
				}
			}
			if(firstResponse.searchBasedParam == 'kundlialerts' && type=='Next' && $(document).height() <= $(window).height())
				loadsNextResult();
			else if(firstResponse.searchBasedParam == 'kundlialerts' && type=='Prev')
				loadsNextResult('',idToLoad);

		}
		else if(triggerPoint <=_TRIGGER_POINT_BOTTOM || type=='Next') /* 1st priority is to load below results */
		{

			if(reachedEnd==0)
				loadsNextResult();
		}
	}

}

/**
* noPhotoDivFn
*/
function noPhotoDivFn(photoLabel,profilechecksum,idd,action)
{
	if(!action)
	{
	var msg =
		'<div id="requestphoto'+idd+'"  class="txtc pb20">\
			<span id="loader" style="width: 180px;;display: NONE"><img src="'+loaderUrl+'" align="top"></span>\
			<div class="disptbl">\
				<div class="dispcell txtc">\
				<a id="label'+idd+'" href="javalscript:void(0);" class="white fontthin f18 lh30 dispbl txtc trans1 srp_pad1">'+photoLabel+'</a>\
			    </div>\
			</div>\
	</div>';
	}
	else
	{
		var msg =
	'<div id="requestphoto'+idd+'"  class="txtc pb20">\
			<span id="loader" style="width: 180px;;display: NONE"><img src="'+loaderUrl+'" align="top"></span>\
		<div class="disptbl">\
			<div class="dispcell txtc">\
			<a id="label'+idd+'" href="javascript:void(0);" onclick=requestphototag("'+profilechecksum+'","'+idd+'")  class="white fontthin f18 lh30 dispbl txtc trans2 srp_pad1">'+photoLabel+'</a>\
		    </div>\
		</div>\
	</div>';
	}
	return msg;
}



//This function is used to load prev and next images
function loadNextImages(self)
{
	$(self).find(".loaderPic").remove();
	var ele = $("#"+loadImageId);
	if(ele!="undefined")
		setImageSrc($(ele),loadImageId);
	var prev = "idd"+(parseInt(loadImageId.substring(3))-1).toString();
	if($("#"+prev)!="undefined")
	{
		setImageSrc($("#"+prev),loadImageId);
		var prevD = "idd"+(parseInt(loadImageId.substring(3))-2).toString();
		if($("#"+prevD)!="undefined")
			setImageSrc($("#"+prevD),loadImageId);
	}
	var next = "idd"+(parseInt(loadImageId.substring(3))+1).toString();
	if($("#"+next)!="undefined")
	{
		setImageSrc($("#"+next),loadImageId);
	}
	loadImageId = next;
}

// This function is used to set the image for element
function setImageSrc(ele,loadid)
{
	if(ele!="undefined" && $(ele).find("img[dsrc]").attr("onload")=="")
	{
		var eleSrc = $(ele).find("img[dsrc]").attr("dsrc");
		var eleSrcOld = $(ele).find("img[dsrc]").attr("src");
		var id = $(ele).attr("id");
		$(ele).find("img[dsrc]").attr("onload",'loadNextImages('+id+')');
		if(eleSrcOld!=eleSrc)
			$(ele).find("img[dsrc]").attr("src",eleSrc);
		else if(loadid == loadImageId )
		{
			loadImageId = "idd"+(parseInt(loadImageId.substring(3))+1).toString();
			loadNextImages();
		}
	}

}

/**
* Return tuple structure of search.
*/
function tupleStructureViewSimilar(profilechecksum,count,idd)
{
        if(typeof contactTracking == 'undefined' && stypeKey!='')
		contactTracking="&stype="+stypeKey;



        var tupleStructure =
	'<div class="tupleOuterDiv searchNavigation bg4 padsp1 bbtsp1" tupleNo="idd'+idd+'"  id="{tupleOuterDiv}">\
	<div class="fullwid">\
          <div class="fl widrsp1 txtc">\
              <a tupleNo="idd'+idd+'" id="album'+idd+'" class="searchNavigation" href="javascript:void(0);" onclick=showProfilePage("/profile/viewprofile.php?total_rec='+firstResponse.no_of_results+'&profilechecksum='+profilechecksum+contactTracking+'&tupleId='+idd+'&'+NAVIGATOR+showECPPage+'&'+'offset='+(idd-1)+'&similarOf='+viewedProfilechecksum+'")>\
                <img dsrc= "{searchTupleImage}" src="{searchTupleDefaultImage}" onload=""  class="brdr_radsrp classimg1 img_s_1 sImageClass" style="width:75px;height: 75px;"> \
              </a>\
              <div class="f13 fontlig">{verificationSeal}</div>\
          </div>\
        <a tupleNo="idd'+idd+'" class="searchNavigation" href="javascript:void(0)" onclick=showProfilePage("/profile/viewprofile.php?total_rec='+firstResponse.no_of_results+'&profilechecksum='+profilechecksum+contactTracking+'&tupleId='+idd+'&'+NAVIGATOR+showECPPage+'&'+'offset='+(idd-1)+'&similarOf='+viewedProfilechecksum+'")>\
          <div class="fl padlr_1 widrsp2">\
            <div class="fontreg f14 color7 txtdec">\
		{username}\
            <span class="f11 colrsp1 fontreg padl5 padr2 fb">\
		{userloginstatus}\
            </span>\
            <span class="f11 color2 fontreg fb">\
		{subscription_icon}\
            </span>\
          </div>\
          <div class="f13 color3 fontlig txtdec">\
              <p>{age} {height}, {religion} {caste} </p>\
              <p>{mtongue}, {location} </p>\
              <p>{occupation}</p>\
              <p>{income}, {edu_level_new}</p>\
          </div>\
        </a>\
          <div class="pt10" id="buttons'+idd+'"></div>\
        </div>\
          <div class="clr"></div>\
        </div>\
      </div>';

	return tupleStructure;
}



function tupleStructure(profilechecksum,count,idd,tupleStype,totalNoOfResults,profileData)
{

	if(firstResponse.infotype != 'VISITORS' && tupleStype!='')
            contactTracking="&stype="+tupleStype;

    if ( firstResponse.infotype == "INTEREST_ARCHIVED")
	{
		contactTracking += "&"+firstResponse.tracking;
	}

    if ( firstResponse.infotype == "INTEREST_EXPIRING" || firstResponse.infotype == "INTEREST_RECEIVED" || firstResponse.infotype == "SHORTLIST")
	{
		contactTracking += "&"+firstResponse.tracking;
	}

	//console.log(contactTracking);
		if(totalNoOfResults=='')
		{
			if(contactCenter==1)
				totalNoOfResults = firstResponse.total;
			else
				totalNoOfResults = firstResponse.no_of_results;
		}
                var tupleStructure = "";
                if (contactCenter !=1 && profileData.filter_reason!="") {
                        if(filteredProfilesHeadShown===0){
                                filteredProfilesHeadShown=1;
                                tupleStructure += '<div class="dn padd3015 filteredDiv" style="background-color: #fff;"><h1 class="txtc fontlig f20 color2" style="font-weight: 600;padding-bottom: 15px;">Below profiles have filtered you out</h1><div class="txtc fontlig f14" style="padding-bottom: 10px;">Filtered profiles are those profiles where you don\'t <br>match their partner preferences.</div><div class="txtc fontlig f14">Your interests will go to their \'filtered\' folder. so<br>response to your interests may be delayed.</div></div>';
                        }
                }
        tupleStructure += '<div class="posrel tupleOuterDiv searchNavigation" tupleNo="idd'+idd+'"  id="{tupleOuterDiv}" style="display:none;height:{searchTupleImageHeight}px;">';
				console.log('here1');
	if(contactCenter==1)
		tupleStructure+='<a tupleNo="idd'+idd+'" class="searchNavigation" href="javascript:void(0)" onclick=showProfilePage("/profile/viewprofile.php?total_rec='+totalNoOfResults+'&profilechecksum='+profilechecksum+contactTracking+'&tupleId='+idd+'&searchid='+firstResponse.searchid+'&'+NAVIGATOR+showECPPage+'&'+'offset='+(idd-1)+'&contact_id='+firstResponse.contact_id+'&actual_offset='+idd+'")>';
	else
		tupleStructure+='<a tupleNo="idd'+idd+'" class="searchNavigation" href="javascript:void(0)" onclick=showProfilePage("/profile/viewprofile.php?total_rec='+totalNoOfResults+'&profilechecksum='+profilechecksum+contactTracking+'&tupleId='+idd+'&searchid='+firstResponse.searchid+'&'+NAVIGATOR+showECPPage+'&'+'offset='+(idd-1)+'")>';
	tupleStructure+='<img dsrc= "{searchTupleImage}" src="{searchTupleDefaultImage}" onload=""  style="width:{searchTupleImageWidth}px;height:{searchTupleImageHeight}px; overflow:hidden;" class="classimg1 img_s_1 sImageClass">\
		</a>';
	if(count>0){
		tupleStructure+='<div class="posabs srp_pos1 searchNavigation" id="{TEST_ME_ID}" tupleNo="idd'+idd+'">\
			<a id="album'+idd+'" onclick=albumcheck("'+count+'","'+idd+'","'+profilechecksum+'","0","'+tupleStype+'","'+totalNoOfResults+'") href="javascript:void(0);">\
				 <div class="posabs outerAlbumIcon">\
					<div class="bg4 txtc disptbl crBoxCount">\
						<div class="f14 color6 dispcell vertmid">{album_count}</div>\
					</div>\
				</div>\
				<div class="bg13 opa50 txtc white opa70 fontreg crBoxIcon">\
				    <div class="pt13">\
					<i class="mainsp camera"></i>\
				    </div>\
				 </div>\
			</a>\
		</div>\
		<div class="fullwid txtc loaderPic" style="position: absolute; top:{loaderPicHeight}px; display:{loaderPicDisplay}">\
				<img src="'+loaderUrl+'"></div><div class="clr">\
		</div>';
	}

	tupleStructure+='<div class="posabs srp_pos3 searchNavigation showDetails '+verifyIcon+'" id="{TEST_ME_ID}" data-doc="'+profilechecksum+'" tupleno="idd'+idd+'">\
	<a href="javascript:void(0);">\
	<div class="bg13 opa50 txtc white opa70 fontreg crBoxIcon">\
	<div class="pt8"> <i class="mainsp verified"></i> </div>\
	</div>\
	</a>\
	</div>\
	<div class="docLayer dispnone">\
	<div class="vOverlay js-docVerified" id="js-docVerifiedidd'+idd+'">\
	<div class="centerDiv">\
	<div class="textDiv fullwid app_txtc">\
	<div class="f15 fb">Profile is verified by visit</div>\
	<a class="loadStaticPage"><div class="f13 color2 pt10">What is this?</div></a>\
	<div class="pt25 f13 color1 docProvided">Documents Provided</div>\
	<div class="pt10 wid90p resf1 putData"></div>\
	</div>\
	<div class="bottonDiv fullwid color2 app_txtc cursp pad4 f18"><span class="okClick dispibl wid150">Ok</span></div>\
	</div>\
	</div>\
	</div>';

	tupleStructure+='<div tupleNo="idd'+idd+'" class="searchNavigation posabs fullwid btmo">\
			{noPhotoDiv}';
			console.log('here2');

	if(contactCenter==1)
		tupleStructure+='<a tupleNo="idd'+idd+'" class="searchNavigation" href="javascript:void(0)" onclick=showProfilePage("/profile/viewprofile.php?total_rec='+totalNoOfResults+'&profilechecksum='+profilechecksum+contactTracking+'&tupleId='+idd+'&searchid='+firstResponse.searchid+'&'+NAVIGATOR+showECPPage+'&'+'offset='+(idd-1)+'&contact_id='+firstResponse.contact_id+'&actual_offset='+idd+'")>';
	else
		tupleStructure+='<a tupleNo="idd'+idd+'" class="searchNavigation" href="javascript:void(0)" onclick=showProfilePage("/profile/viewprofile.php?total_rec='+totalNoOfResults+'&profilechecksum='+profilechecksum+contactTracking+'&tupleId='+idd+'&searchid='+firstResponse.searchid+'&'+NAVIGATOR+showECPPage+'&'+'offset='+(idd-1)+'")>';
		tupleStructure+='<div class="fullwid grad1 padl10 padr10" style="padding: 10px 15px 10px;">\
				<div class="fontlig" id="username'+idd+'" style="padding-top: 30px;">\
						<span class="f16 white fontreg textTru dispibl vbtm wid51p">\
						{username}\
						</span>\
						<span class="f12 white">\
							{userloginstatus}\
						</span>\
						<span class="f12 white fr fontrobbold">\
							{gunascore}\
						</span>\
				</div>\
				<div class="fullwidth f14 fontreg white">\
					<div class="clearfix">\
						<div class="fl wid48p textTru">\
							{age}, {height}, {mstatus}\
						</div>\
						<div class="fr wid48p textTru">\
							{occupation}\
						</div>\
					</div>\
					<div class="clearfix">\
						<div class="fl wid48p textTru">\
							{caste}\
						</div>\
						<div class="fr wid48p textTru">\
							{income}\
						</div>\
					</div>\
					<div class="clearfix">\
						<div class="fl wid48p textTru">{mtongue}</div>\
						<div class="fr wid48p textTru">{edu_level_new}</div>\
					</div>\
					<div class="clearfix">\
						<div class="fl wid48p textTru">{location}</div>\
						<div class="fr txtr color2 f16 fontrobbold">{subscription_icon}</div>\
					</div>\
				</div>\
			</div>\
			</a>\
			<div id="{buttonsDiv}"></div>\
		</div>\
	</div>\
	<div class="clr bb2s tupleOuterSpacer" id="{tupleOuterSpacer}" style="display:none;"></div>';

if(AppLoggedInUser && idd == 3 && contactCenter != 1 && viewSimilar !=1 ){
        if(getAndroidVersion() || getIosVersion()){
                var mbtext = "";
                if(getAndroidVersion()){
                        var type = "apppromotionSRPAndroid";
                        var lableText = "Android";
                        var mbtext = "<div class='txtc fontlig f14 pt5'>(3 MB only)</div>";
                }
                if(getIosVersion()){
                        var type = "apppromotionSRPIos";
                        var lableText = "iOS";
                }
                tupleStructure += '<div class="srp_bgmsg dispnone padd3015"><div class="txtc fontlig f14">Refine search results by Caste,Community, Profession, Occupation, Income and 15 other criteria.</div><a class="txtc color2 mt15 dispbl" onclick=\"trackJsEventGA(\'Download App\',\'SRP\', \''+lableText+'\');\" href="/static/appredirect?type='+type+'\">Download '+lableText+' App</a>'+mbtext+'</div>';
        }
}
	return tupleStructure;
}

function viewSimilarLayer(index,profilechecksum){

  var container = $("#containeridd");
  if (container.is( ":visible" ))
    viewSimilarLayerClose();
  else {
   similarProfile(profilechecksum);
    viewSimilarLayerOpen();
    if (typeof historyStoreObj == "object") {
       historyStoreObj.push(onBackBtnECPLayer,"#similarLayer");
    }
  }
}
function onBackBtnECPLayer()
{
  var container = $("#containeridd");
  if (container.is( ":visible" )){
    viewSimilarLayerClose();
    return true;
  }
  return false;
}

function viewSimilarLayerClose(){
  var container = $("#containeridd");

   $("#overlayLayerSimilarProfile").hide();
    container.slideUp( 300 );
    $("#searchHeader").show();
    enable_touch();
    $("#sContainer").css("overflow","auto");
    $("#InterestSentStatus").html("");
    $('#simProfileDiv').html("<div ><img src='' style='width:45%; height:60px;border-radius:30px;  visibility: hidden;'><img src='/images/jsms/commonImg/loader.gif' ></div> ");
}

function viewSimilarLayerOpen(){
  var container = $("#containeridd");
    $("#overlayLayerSimilarProfile").show();
    $("#searchHeader").slideUp();
    container.slideDown( 300 );

    $("#sContainer").css("overflow","hidden");
    $("#sContainer").css("display","block");
   disable_touch();
}

function similarProfile(profilechecksum)
    {
    $.ajax(
                {
                        url: '/search/ViewSimilarProfilesV1',
                        data: "actionName=similarprofile&profilechecksum="+profilechecksum,
                        //timeout: 5000,
                        success: function(response)
                        {
                          var res=JSON.parse(response);
                          $("#InterestSentStatus").html("Interest sent to "+res.username);
                          var photoNumber=res.no_of_results;
                          var abc = res.noresultmessage;
                          if(photoNumber == 0){
			    $("#simProfileDiv").html('<div style="  text-align: center; height:60px;">'+res.noresultmessage+'</div>');
                            setTimeout(function () {
				    viewSimilarLayerClose();
				    }, 5000);
                          }
                          if(photoNumber>3){
                            var thumbnailCount=3;
                            var plusCount = photoNumber-thumbnailCount;
                          }
                          else{
                            var thumbnailCount=photoNumber;
                            var plusCount = 0;
                          }
                          var dataToDisplay = '<div id = "simProfileDivNew" class="clearfix pt10" style="display:none;">';
                          var i=0;
                          for (i = 0; i < thumbnailCount; i++) {
                            var classNameForPic = "fl";
                            if(i>0)
                              var classNameForPic = "fl padl_vp";
                              var stypeKeySimilar='WC';
                              var similarLink = "/search/MobSimilarProfiles?profilechecksum="+profilechecksum+'&stype='+stypeKeySimilar+'&'+NAVIGATOR;
                  dataToDisplay+= '<div class="'+classNameForPic+'"><a href="'+similarLink+'">\
                      <img src="/images/jsms/commonImg/loader.gif" id="simProfile'+i+'" style="width:60px; height:60px;border-radius:30px">\
                   </div>';
                  }
                  if(plusCount>0){
                    plusCount="+"+plusCount;
                    dataToDisplay+= '<div class="fl txtc padl_vp">\
                      <div class=" disptbl bg7 posrel" style="width:60px; height:60px;border-radius:30px">\
                      <div class="cell  vertmid white">'+plusCount+'</div>\
                    </div>\
                  </div>';
                }


                dataToDisplay+= '</div>';

		   $("#similarProfilesView").append(dataToDisplay);
                   i=0;
                   statusValOfImgLoad=0;
                   for (i = 0; i < thumbnailCount; i++) {

			        var newImg = new Image;
				newImg.onload = function() {
				    $("#simProfile"+statusValOfImgLoad).attr("src",res.profiles[statusValOfImgLoad].photo.url);
				    statusValOfImgLoad+=1;
				    if(statusValOfImgLoad==thumbnailCount){
					$('#simProfileDiv').remove();
					$("#simProfileDivNew").css("display","block");
					$("#simProfileDivNew").attr("id","simProfileDiv");
				    }

				}
				newImg.src = res.profiles[i].photo.url;
		   }
                        },
                });
    }

//This function is used to go to album page from search
function albumcheck(count,idd,profilechecksum,IsProfilefiltered,tupleStype,totalNoOfResults)
{

	if(typeof IsProfilefiltered == 'undefined')
		var IsProfilefiltered = 0;
	if(typeof contactTracking == 'undefined' && tupleStype!='')
		contactTracking="&stype="+tupleStype;

	if(IsProfilefiltered==1)
		$("#album"+idd).attr("href", "/profile/viewprofile.php?total_rec="+totalNoOfResults+"&profilechecksum="+profilechecksum+contactTracking+"&tupleId="+idd+"&"+NAVIGATOR+"&"+"offset="+(idd-1)+"&similarOf="+viewedProfilechecksum+"");
	else if(count!=0)
		$("#album"+idd).attr("href", "/social/MobilePhotoAlbum?profilechecksum="+profilechecksum+contactTracking+"");
}
/**
* Fix Header position, {{ lavesh - add inline}}
*/
function fixDiv() {
	$div.css({'position': 'fixed', 'top': '0', 'width': '100%','z-index':'105'});
}

/**
* This function will show(on scrolling up) / hide(scrolling down) search header.
*/
function showHideSearchHeader(force)
{
	if(force=='Hide')
		$div.slideUp();
	else if(force)
		$div.slideDown();
	else
	{
		var NextScroll = $(this).scrollTop();
		if (NextScroll > CurrentScroll){ // scroll down
				if(NextScroll>200 && !$("loaderTopDiv").is(':visible'))
					$div.slideUp(300);
		}
		else if(noScrollingAction==0){
			if(NextScroll<200)
				$div.slideDown(5);
			else
				$div.slideDown(300);
		}
		else{
			noScrollingAction=0;
		}

		if(NextScroll>200)
			$("#searchHeader").removeClass("bg1").addClass("bg1-t");
		else
			$("#searchHeader").removeClass("bg1-t").addClass("bg1");

	}
	CurrentScroll = NextScroll;
}

/**
* Check for top loader condition.
*/
function isTopLoader()
{
	var ifVis = $("loaderTopDiv").is(':visible');
	if(ifVis)
		return true;
}

/**
* This fucntion will check if we come back to search results
* I have checked when we click on any link and then click on back to search results
*/
function ifBackToSearchResults()
{
	var myLoc = $(location).attr('href');
	if (myLoc.indexOf("page") >= 0 && myLoc.indexOf("idd") >= 0)
		return true;
	return false;
}

/***
* This function will show the loader on the window screen.
*/
function showLoaderToScreen()
{
	$("#overLayLoader").height($(window).height());
	$("#overLayLoader").show();
}

function generateParams(page)
{
	var searchid = firstResponse.searchid;
	var sbPar = removeNull(firstResponse.searchBasedParam);
        if(firstResponse.visitorAllOrMatching!='' || typeof(firstResponse.visitorAllOrMatching) !="undefined")
	var temp = "results_orAnd_cluster=onlyResults&searchBasedParam="+sbPar+"&searchId="+searchid+"&matchedOrAll="+firstResponse.visitorAllOrMatching+"&currentPage="
	temp = $.addReplaceParam(temp,'currentPage',page)
	return temp;
}

/**
* This function will send ajax request to get more results
* Two params will be need only for back to search functionality.
* @param forcePage {numeric} {optional} force request to a specific page (rather than next page calculated based on up or down scrolling)
* @param idToJump  {string} {optional} move focus to a partcular id.
*/
function loadsNextResult(forcePage,idToJump,ifPrePend)
{

	var url = '/api/v1/search/perform';

	if(contactCenter==1)
		url = '/api/v2/inbox/perform';
	else if(viewSimilar==1 && typeof(viewedProfilechecksum)!='undefined')
		url = '/search/ViewSimilarProfilesV1?actionName=similarprofile&profilechecksum='+viewedProfilechecksum;

	var searchTuple;
	isLoading = true;  // we are starting a new load of results so set isLoading to true

	/* overwrite the page value with the forced one */
	var searchResultsPostParams1;
	if(forcePage)
	{
		if(ifPrePend)
			searchResultsPostParams1 = generateParams(forcePage)
		else
			searchResultsPostParams = generateParams(forcePage)
	}
	if(!ifPrePend)
		searchResultsPostParams1 = searchResultsPostParams;

	$.ajax(
	{
		url: url,
        dataType: 'json',
		type: 'GET', data: searchResultsPostParams1,
		timeout: 60000,
		beforeSend : function( xhr ) {
						if(firstResponse.searchBasedParam == 'kundlialerts')
							isLoading = true;
        },
		success: function(response)
		{
			if(!CommonErrorHandling(response))
				return;
			if(response.responseStatusCode=='0')
			{
				dataForSearchTuple(response,forcePage,idToJump,ifPrePend,searchTuple);
			}
			else{
				var d = new Date();
				if($('.loaderTopDiv:visible').length>0)
				{
					var msg = topErrorMsg('Results have changed since last time you searched. Kindly perform your search again.','/search/topSearchBand?isMobile=Y&stime='+d.getTime());
				}
				else
				{
					var msg = bottomErrorMsg('Results have changed since last time you searched. Kindly perform your search again.','/search/topSearchBand?isMobile=Y&stime='+d.getTime());
					$("div.loaderBottomDiv").remove();
				}
			}
			BindNextPage();
		},
		error: function(xhr)
		{
			if($('.loaderTopDiv:visible').length>0)
			{
				var msg = topErrorMsg('Connection Lost – Retry.','','loaderTopDiv');
			}
			else
			{
				$("div.loaderBottomDiv").remove();
				var msg = bottomErrorMsg('Connection Lost – Retry.','','loaderBottomDiv');
			}
			isLoading = false;
		}
	})
	return false;
}

function dataForSearchTuple(response,forcePage,idToJump,ifPrePend,searchTuple){
	var noPhotoDiv;
	if(!ifPrePend)
		searchResultsPostParams = generateParams(parseInt(response.page_index) + 1)
	var nextAvail = response.next_avail;
	$("#totalCountId").html(response.result_count);
	var tuplesOfOnePage='';
	var arr1 = {};
	var defaultImage = response.defaultImage;
	if(response.searchBasedParam == 'kundlialerts')
	{
					profileLength = 0;
					if('profiles' in response && Array.isArray(response.profiles))
					{

						profileLength = response.profiles.length;
					}

	}

	/** reading json **/
	$.each(response, function( key, val ) {

		if(key=='profiles' || key=='featuredProfiles')
		{
			if(val!= null){
			$.each(val, function( key1, val1 ) {
				var profileNoId = ((parseInt(response.page_index-1))*_SEARCH_RESULTS_PER_PAGE) +key1 + 1;
				if(key=='featuredProfiles')
					profileNoId='f'+profileNoId.toString(); // featured profiles prefixed with f

				if(val1.photo.label)
					noPhotoDiv = noPhotoDivFn(val1.photo.label,removeNull(val1.profilechecksum),profileNoId,val1.photo.action)
				else
					noPhotoDiv='';
				// create mapping data which contains profile information

				// Removes Loader
				if(val1.size){
					if(val1.photo.label){
						val1.size.WIDTH =  $(window).width();
						val1.size.HEIGHT=$(window).width()*(4/3);
					}
					else
					{
						if(val1.size.HEIGHT){
							val1.size.HEIGHT=$(window).width()*(val1.size.HEIGHT/val1.size.WIDTH);
						}
						if(val1.size.HEIGHT<$(window).width()){
							val1.size.HEIGHT=$(window).width();
						}
						val1.size.WIDTH=$(window).width();
					}
				}
				// Mapping Array in Object
				var mapObj = searchResultMaping(val,noPhotoDiv,val1,profileNoId,defaultImage,key);

				// Relaxation Array in Object
				relaxationArr =removeNull(response.relaxation);
				if(key=='featuredProfiles')
				{
					tupleStype= val1.stype;
					totalNoOfResults = response.featuredProfiles.length;
				}
				else
				{
					tupleStype=stypeKey;
					totalNoOfResults= '';
				}

				/*
				* Removes Loader
				* Add Data into structure of tuple
				* append data at the end
				*/
				if(viewSimilar==1)
					searchTuple = $.ReplaceJsVars(tupleStructureViewSimilar(val1.profilechecksum,val1.album_count,profileNoId),mapObj);
				else
					searchTuple = $.ReplaceJsVars(tupleStructure(val1.profilechecksum,val1.album_count,profileNoId,tupleStype,totalNoOfResults,val1),mapObj);
				if(key=='featuredProfiles')
					tuplesOfOnePage=searchTuple+tuplesOfOnePage;
				else
					tuplesOfOnePage+=searchTuple;
				arr1[profileNoId.toString()] = [val1.buttonDetailsJSMS,val1.profilechecksum];



			});

		}}
		/** looping through profiles **/
	});

	/*****************/
	addTupleToPages(tuplesOfOnePage,arr1,ifPrePend);
	if(forcePage>0)
		forceJumpToPage(idToJump);
	/*****************/

	$("#overLayLoader").hide();
	$("#searchHeader").css("position","fixed");
	$("#searchHeader").css("top","0px");
	if($("#iddf1").length == 0) {

  $("#idd1").css("margin-top",$("#searchHeader").height()+"px");
	}
	else
  $("#iddf1").css("margin-top",$("#searchHeader").height()+"px");

  if(nextAvail!='false')
	{

		$(".initialLoader").remove();
		$("div.loaderBottomDiv").remove();
		{
			if(reachedEnd==0)
			{
				$(loaderBottomDiv).appendTo("#sContainer");
				if(parseInt($(".tupleOuterDiv").length)<=(_SEARCH_RESULTS_PER_PAGE)){
					var height = ($(window).height()-20)/2;
					$("div.loaderBottomDiv").css("margin-top",height+"px");
				}

			}
		}
	}
	else{
		if ( response.archivedInterestLinkAtEnd )
		{
			bottomErrorMsg('<a href="/inbox/jsmsPerform?searchId=22" class="color2 txtc">'+response.archivedInterestLinkAtEnd+'</a>','','')
		}
		noScrollingAction=1;
		reachedEnd=1;
		$("div.loaderBottomDiv").remove();
		//$(".loaderTopDiv").remove();
		if(response.dppLinkAtEnd)
			bottomErrorMsg('<a href="/search/perform?partnermatches=1" class="color2 txtc">'+response.dppLinkAtEnd+'</a>','','viewMyMatches');
	}
	/** reading json **/

  //Rcb Communication
  if (contactCenter && response.hasOwnProperty('display_rcb_comm') && response.display_rcb_comm &&
    typeof response.profiles != "undefined" && response.profiles != null && response.total >= 3 &&
    $('.js-rcbMsg').length === 0
    ) {
      setTimeout(function(){
        $("<div class='rel_c bg4 js-rcbMsg' id='callDiv1'><div class='f14 fontlig mainDiv'>To reach out to our accepted members, you may consider upgrading your membership. Would you like us to call you to explain the benifits of our membership plans?</div><div class='pt15 pb30 f14 alignC'><a href='javascript:void(0)' id='callUser' class='color2 callDiv'>Yes, call me</a><a href='javascript:void(0)' id='noButton' class='color2 noDiv callDiv'>No, Later</a></div></div>").insertAfter("#idd3");

        $("#noButton").off("click");
        $("#noButton").on("click", function () {
           var url = '/common/requestCallBack';
            $.ajax({
              type: "POST",
              url: url,
              cache: false,
              timeout: 5000,
              data: {rcbResponse:'N','device':'mobile_website','channel':'JSMS','callbackSource':'Accepted_Members_List'},
              success: function (result) {
                $("#callDiv1").remove();
                $("<div class='rel_c bg4 js-rcbMsg' id='callDiv2'><div class='f14 fontlig mainDiv2'>Never mind. You still can reach out to us later whenever you want. We will remind you about this after two weeks.</div></div>").insertAfter("#idd3");
                setTimeout(function () {
                  $("#callDiv2").slideUp();
                  setTimeout(function () {
                    $("#callDiv2").remove();
                  }, 300)
                }, 3000);
              },
              error:function(result){
                 $("#callDiv1").hide();
                $("<div class='rel_c bg4 js-rcbMsg' id='callDiv2'><div class='f14 fontlig mainDiv2'>Something went worng. Please try in some time.</div></div>").insertAfter("#idd3");
                setTimeout(function () {
                  $("#callDiv2").slideUp();
                  setTimeout(function () {
                    $("#callDiv2").remove();
                    $("#callDiv1").show();
                  }, 300)
                }, 2000);
              }
            });



        });

        $("#callUser").off("click");
        $("#callUser").on("click", function (e) {
          $('#reqCallBack').attr('data-rcbResponse','Y');
          showRCBLayer(e, 'Accepted_Members_List');
        });
      },(2*timedOut)+300);

  }
}

function searchResultMaping(val,noPhotoDiv,val1,profileNoId,defaultImage,key){
	var searchDefault =val1.photo.url;
	var loaderPicDisplay='none';
	var loaderPicHeight ='';
	if(noPhotoDiv=='')
	{
		searchDefault = defaultImage;
		loaderPicDisplay = "block";
		if(val1.size){
			loaderPicHeight = (val1.size.HEIGHT)/3;
			var picWidth = val1.size.WIDTH;
			var picHeight = val1.size.HEIGHT;
		}
		else{
			loaderPicHeight = '200px';
			var picWidth ='';
			var picHeight = '';
		}

	}
	if(val1.buttonDetailsJSMS.buttons)
		primeButtonLabel = val1.buttonDetailsJSMS.buttons[0].label;
	else
		primeButtonLabel = '';

	if(val1.verification_seal)
	{
		verificationSeal=val1.verification_seal;
		verifyIcon="";
	}
	else
	{
		verifyIcon="dispnone";
		verificationSeal=null;

	}

	if(val1.photo.label!=null)
		val1.photo.label=1;
	else
		val1.photo.label=0;
	if(val1.gunascore!=null)
		gunascore=val1.gunascore+"/36";
	else
		gunascore=null;
	if(typeof val1.religion=='undefined')
		val1.religion = '';
	//var isNewProfile = (val1.seen=="N")?"New":"";
	var subscriptionOrFeatured = val1.subscription_icon;
	if(key=='featuredProfiles')
		subscriptionOrFeatured = 'Featured';

        if(val1.name_of_user!='' && val1.name_of_user!=null){
                val1.username = val1.name_of_user;
        }
        val1.age = val1.age.replace(" Years","");
	var mapping={

			'{noPhotoDiv}':removeNull(noPhotoDiv),
			'{searchTupleDefaultImage}':removeNull(searchDefault),
			'{searchTupleImage}':removeNull(val1.photo.url),
			'{photoLabel}':removeNull(val1.photo.label),
			'{loaderPicDisplay}':loaderPicDisplay,
			'{loaderPicHeight}':loaderPicHeight,
			'{searchTupleImageWidth}':removeNull(picWidth),
			'{searchTupleImageHeight}':removeNull(picHeight),
			'{album_count}':removeNull(val1.album_count),
			'{username}':removeNull(val1.username),
			'{userloginstatus}':removeNull(setPriority(val1.userloginstatus,val1.timetext)),
			'{gunascore}':removeNull(gunascore),
			'{age}':removeNull(val1.age),
			'{height}':removeNull(val1.height),
			'{occupation}':removeNull(val1.occupation),
			'{caste}':removeNull(val1.caste),
			'{religion}':removeNull(val1.religion),
			'{income}':removeNull(val1.income),
			'{mtongue}':removeNull(val1.mtongue),
			'{edu_level_new}':removeNull(val1.edu_level_new),
			'{location}':removeNull(val1.location),
			'{subscription_icon}':removeNull(subscriptionOrFeatured),
			'{primeButtonLabel}':removeNull(primeButtonLabel),
			'{TEST_ME_ID}':"id"+profileNoId,
			'{tupleOuterDiv}':"idd"+profileNoId,
			'{tupleOuterSpacer}':"idS"+profileNoId,
			'{profileNoId}':profileNoId,
			'{buttonsDiv}':"buttons"+profileNoId,
			'{buttonInputId}':"buttonInput"+profileNoId,
			'{profilechecksum}':removeNull(val1.profilechecksum),
			'{mstatus}':removeNull(val1.mstatus),
			'{verificationSeal}':removeNull(verificationSeal),
			'{blahblahToEnd_withNoComma}':"----------",
                        '{filter_reason}':removeNull(val1.filter_reason)
		};
	return mapping;
}
/* prePend the search result */
function addTupleToPages(tuplesOfOnePage,arr1,ifPrepend){

	if(ifPrepend)
		$(tuplesOfOnePage).prependTo("#sContainer");
	else
		$(tuplesOfOnePage).appendTo("#sContainer");


	setTimeout(function()
	{
		if(ifPrepend)
		{
			minPage = minPage-1;

			var top = $('body').scrollTop();
			var loaderHeight=0;
			if($(".loaderTopDiv").is(':visible'))
				loaderHeight = $(".loaderTopDiv").height();
			top = top-loaderHeight-parseInt($('.loaderTopDiv').css("margin-top")); // make sure on moving up there are no jerk
		}

	$.each(arr1, function( profileNoId, val ) {

			if(profileNoId && val)
			{


				$("#idd"+profileNoId).show();
				$("#idS"+profileNoId).show();
				top += $("#idd"+profileNoId).height();
				top=top+parseInt($('.tupleOuterSpacer:first').css("height"));

				/* contact buttons and overlay code start*/
				if(!$("#buttons"+profileNoId).html())
				{
					if(viewSimilar==1)
						$page="viewSimilar";
					else
						$page="";
					button = buttonStructure(profileNoId,arr1[profileNoId][0],removeNull(arr1[profileNoId][1]),$page);
                                        $( "#buttons"+profileNoId ).append(button);
					bindPrimeButtonClick(profileNoId);
					bind3DotClick(profileNoId,arr1[profileNoId][0]);
				}
				/* contact buttons and overlay code end*/
			}
		});

		if(ifPrepend)
		{

			if($(".loaderTopDiv").length)
				$(".loaderTopDiv").remove();

			$('body, html').scrollTop(top);
			showHideSearchHeader("hide");
		}
		$("div.loaderBottomDiv").css("margin-top","0px");

		if(($("div.tupleOuterDiv").length)>maxResult)
		{
			if(!ifPrepend)
			{
				noScrollingAction=1;
				reachedEnd=1;
				$("div.loaderBottomDiv").remove();
				var pageAct = parseInt($("div.tupleOuterDiv").last().attr("id").replace(/[^-\d\.]/g, ''))+1;
				pageAct = "idd"+pageAct;
				var sbPar = removeNull(firstResponse.searchBasedParam);
				/*
					Added this check for contacts section more listing.
				 */
				if ( contactCenter == 1 )
				{
					var newAction = "/inbox/jsmsPerform?searchBasedParam="+sbPar+"&searchId="+firstResponse.searchid+"&page="+pageAct+"&currentPage=1";
				}
				else
				{
					var newAction = "/search/perform/?searchBasedParam="+sbPar+"&searchId="+firstResponse.searchid+"&page="+pageAct+"&currentPage=1";
				}
				bottomErrorMsg('<a href="'+newAction+'" class="color2 txtc">Load More Profiles.</a>','','');
			}
		}

		setTimeout(function()
		{
			isLoading = false;
			if(loadPrevTuple)
			{

				$('body, html').scrollTop(1);
				$('body, html').scrollTop(0);
			}
			loadPrevTuple=0;
			if(firstResponse.searchBasedParam == 'kundlialerts' && typeof profileLength != 'undefined' && profileLength<3)
				triggerLoader('Next');
			else
			triggerLoader();

			var scrollTopPositioning = $(window).scrollTop();
			if($('.inview').length==0){

				var nextScrollPosition = scrollTopPositioning+1;
				$('body').scrollTop(nextScrollPosition);
				$('body').scrollTop(scrollTopPositioning);
			}
		},timedOut);
		BindNextPage();
		$('.srp_bgmsg').css('display','block');
		$('.filteredDiv').css('display','block');
	},timedOut);
		BindNextPage();
}

/* move the page to the desired position(by using id) */
function forceJumpToPage(idToJump){

	if(idToJump)
	{
		setTimeout(function()
		{
			fixDiv();
			if(idToJump!='iddf1')
				var top = $('#idd'+idToJump).offset().top;
			else
				var top = $('#iddf1').offset().top;

			if ( idToJump != 1 )
			{
				$("html, body").scrollTop(top);
			}
			loadNextImages();
		},timedOut);
	}
}

/**
 * * This fucntion will replace msg2 if exists else return msg1
 * */
function setPriority(msg1,msg2)
{
        if(msg2)
		return msg2;
	else
        	return msg1;
}

/**
 * * This fucntion is used for handling back button functionality
 * */

function referHandling(searchId,referer)
{
	var str = sessionStorage.getItem("searchId"+searchId);
	if(str) {
                refererValue = sessionStorage.getItem("searchId"+searchId);
        }
        else {
		sessionStorage.setItem("searchId"+searchId,referer);
                refererValue=referer;
        }
   return refererValue;
}
