/**
*  This function wil show the loader.
**/
function showSearchLoader(type)
{
        if(type=='show')
                $('#searchResultsLoaderTop').show();
        else
                $('#searchResultsLoaderTop').hide();
}

/** 
 * Function for mapping each tuple values into the placeholder
 * 
 */

function searchResultMaping(val, noPhotoDiv, val1, profileNoId, defaultImage, featuredCount, profileOffset,key,resp) {
  var searchDefault = val1.photo.url;
  var orig_username = removeNull(val1.username);
  if(resp.listType=="cc")
    searchDefault = val1.profilepic450url;
  var loaderPicDisplay = 'none';
  /**
   * Managing photo div with loader and defualt pic 
   */
   
  if (noPhotoDiv == '') {
    searchDefault = defaultImage;
    loaderPicDisplay = "block";
    countDisplay = "cursp";
    hasAlbum = "cursp js-openAlbum";
    if(val1.album_count<1)
    {
      countDisplay = "disp-none";
      hasAlbum = "";
    }
  } else {
    countDisplay = "disp-none";
    hasAlbum = "";
  }
  /** 
   * Featured profile display handling LATER 
   */
  if (key=="featuredProfiles") {
    featuredProfile = "";
    featureProfileCount = featuredCount;
    removeThisProfile = "disp-none";
  }else {
    featuredProfile = "disp-none";
    featureProfileCount = "";
    removeThisProfile = "";
  }
  
  if(resp.searchBasedParam=="justJoinedMatches" || resp.searchBasedParam=='matchalerts' || resp.searchBasedParam=='contactViewAttempts'){
        joinedOnMsg = val1.timetext.replace("She j","J").replace("He j","J");
        removeThisProfile = "disp-none";
    }
   else if(resp.listType=="cc" && (resp.searchid=="8"|| resp.searchid=="5")){   //for shortlisted members
        joinedOnMsg = val1.timetext;
        removeThisProfile = "disp-none";
    }
    else
        joinedOnMsg = "";
 
  /** 
   * Filtered Profiles
   */
  if(resp.listType != "undefined" && resp.listType == "vsp")
  {
    removeThisProfile = "disp-none";
    orig_username = removeNull(val1.orig_username);
  }
  else
  {
    if (val1.filter_reason!="") {
       if(filteredProfilesHeadShown==0){
          filteredProfilesHeadShown=1;
          var filteredProfilesHead = "<div class='srppad21 txtc'>\
                     <div class='f17 color11 fontreg'>Below profiles have filtered you out </div>\
                 <div class='pt20 colr2 f15 lh20 fontlig'>\
                     <div>Filtered profiles are those profiles where you don't match their partner preferences. </div>\
                                     <div>Your interests will go to their 'filtered' folder, so response to your interests may be delayed</div>\
                 </div>\
               </div>";
          $("#searchTuplesMainDiv").append(filteredProfilesHead);
      }
    }
  }
  
  /** 
   * Highlighted profile display handling LATER 
   */
 if (val1.highlighted){ 
		highlightedProfile = "highl";
		if(noPhotoDiv!="")
		{
			noPhotoDiv = noPhotoDiv.replace(" bg5 "," bg_pink ");
		}
		
	}
  else highlightedProfile = null;

  /** 
   * Verification seal  display handling LATER 
   */
var verificationDocumentsList;
  if (val1.verification_seal) {
    verificationSeal = ""; //val1.verification_seal;
    verificationSealDoc = "";
    if(val1.verification_seal instanceof Array){
      verificationDocumentsList = val1.verification_seal.join(",</li><li>");
      verificationDocumentsList = "<li>"+verificationDocumentsList+"</li>";
    }else{
        verificationSealDoc = "disp-none";    
    }
  } else {
    verificationSeal = "disp-none";
    verificationSealDoc = "disp-none";
    verificationDocumentsList = null;
  }

  if (val1.photo.label != null) val1.photo.label = 1;
  else val1.photo.label = 0;
  if (typeof val1.religion == 'undefined') val1.religion = '';

  //adding code for caste
  if(val1.caste == val1.religion)
  {
    val1.caste = "";
  }
  else
  {
    val1.caste = ", "+val1.caste;
  }
  var isNewProfile = (val1.seen == "N") ? " new" : "";
  if(val1.filter_reason!="")
      var toShowFilterReason = "";
  else
      var toShowFilterReason = "disp-none";

  if(resp.listType != "undefined" && resp.listType == "vsp")
  {
    toShowFilterReason = "disp-none";
  }

  if (key=="featuredProfiles")
    var divIdSufix = "f";
  else
    var divIdSufix = "";

  //for dev environment only 
  if(val1.income =="undefined" || val1.income == null)
  {
    val1.income = "";
  }
 var searchTupleImage ="";
 if(resp.listType=="cc")
    searchTupleImage = val1.profilepic450url;
 else
    searchTupleImage = val1.photo.url;
  /**
   * Mapping of placeholders and the values
   */
        if(typeof(loggedInJspcUser)!="undefined" && loggedInJspcUser=="" ){
                val1.username = val1.username.substring(0, val1.username.length - 4);
                val1.username += "****";
        }
	else
	{
		if(val1.name_of_user!='' && val1.name_of_user!=null)
			val1.username = val1.name_of_user;
	}
  var collegeTxt = "";
  var a = [];
  var pgCol = '';
  if(typeof val1.pg_college != 'undefined' && val1.pg_college != '' && val1.pg_college != null){
          a.push(val1.pg_college);
          pgCol = '';
  }
  if(typeof val1.college != 'undefined' && val1.college != '' && val1.college != null){
        if(pgCol.toLowerCase() != val1.college.toLowerCase()){
             a.push(val1.college);
        }
  }
  var s = a.join(', ');
  if(s){
        collegeTxt =  "Studied at "+s;
  }
  if(val1.company_name){
          val1.company_name = "Works at "+val1.company_name;
  }
  var mapping = {
    '{StudiedAtDiv}': removeNull(collegeTxt),
    '{WorksAtDiv}': removeNull(val1.company_name),
    '{noPhotoDiv}': removeNull(noPhotoDiv),
    '{searchTupleImage}': removeNull(searchTupleImage),
    '{photoLabel}': removeNull(val1.photo.label),
    '{hasAlbum}': hasAlbum,
    '{loaderPicDisplay}': loaderPicDisplay,
    '{album_count}': removeNull(val1.album_count),
    '{countDisplay}': countDisplay,
    '{username}': removeNull(val1.username),
    '{orig_username}':orig_username,
    '{userloginstatus}': removeNull(val1.userloginstatus),
    '{isNewProfile}': isNewProfile,
    '{age}': removeNull(val1.age),
    '{height}': removeNull(val1.height),
    '{occupation}': removeNull(val1.occupation),
    '{caste}': removeNull(val1.caste),
    '{religion}': removeNull(val1.religion),
    '{income}': removeNull((val1.income).replace(/Rs./, '₹ ')),
    '{mtongue}': removeNull(val1.mtongue),
    '{edu_level_new}': removeNull(val1.edu_level_new),
    '{location}': removeNull(val1.location),
    '{subscription_icon}': removeNull(val1.subscription_icon),
    '{tupleOuterDiv}': "idd"+ divIdSufix + profileNoId,
    '{tupleOffset}': profileOffset,
    '{tupleOuterSpacer}': "idS" + divIdSufix + profileNoId,
    '{tupleImage}': "idI" + divIdSufix + profileNoId,
    '{profileNoId}': profileNoId,
    '{profilechecksum}': removeNull(val1.profilechecksum),
    '{verificationSeal}': removeNull(verificationSeal),
    '{verificationSealDoc}': removeNull(verificationSealDoc),
    '{verificationDocumentsList}': removeNull(verificationDocumentsList),
    '{featuredProfile}': removeNull(featuredProfile),
    '{featureProfileCount}': removeNull(featureProfileCount),
    '{removeThisProfile}': removeNull(removeThisProfile),
    '{joinedOnMsg}' : removeNull(joinedOnMsg),
    '{highlightedProfile}': removeNull(highlightedProfile),
    '{filterReason}': removeNull(val1.filter_reason),
    '{showFilterReason}': toShowFilterReason,
    '{mstatus}': removeNull(val1.mstatus),
    '{userId}':removeNull(val1.profileid),
    '{gender}':removeNull(val1.gender)
  };
  return mapping;
}

/**
 * Populating no photo div based on the action requirement
 */
function noPhotoDivFn(photoLabel, profilechecksum, idd, action) {

  /**
   * There can be just label without action like photo requested etc 
   */
  if (!action) {
    var msg =
      '<div id="requestphoto' + idd + '"  class="pos-abs srppos4 fullwid js-noaction">\
                  <div class=" txtc colrw opa80 mauto wid150">' + photoLabel + '</div>\
     </div>';
  } else {
    /**
     * ACtion can be Request or Login
     */
     if(typeof(loggedInJspcUser)!="undefined" && loggedInJspcUser=="" )
     {
		 var msg =
      '<div id="requestphoto' + idd + '" class="pos-abs srppos3 fullwid cursp " data=' + profilechecksum + ' myaction=' + action + '>\
                  <div class=" bg5 txtc fontlig f14 colrw lh50">' + photoLabel + '</div>\
                 </div>';
	 }
	 else
	 {
		var msg =
      '<div id="requestphoto' + idd + '" class="pos-abs srppos3 fullwid cursp js-hasaction" data=' + profilechecksum + ' myaction=' + action + '>\
                  <div class=" bg5 txtc fontlig f14 colrw lh50">' + photoLabel + '</div>\
                 </div>';
	}

  }
  return msg;
}

/**Binding of tuple to open profile page based on profilechecksum*/
function fillProfileViewHref(response) {
  
	$('.js-profileDesc').each(function(i, obj) {
	
      var profilechecksum = $(this).attr("data");
  
  /**
   * Contact tracking and navigator variable
   */
  var contactTracking = ""; //LATER
  var navigator = "NAVIGATOR="; //LATER
  var stype= response.stype;
	var totalCount = response.no_of_results;
	if($(this).parents('#featuredListing').length)
	{ 
		if(response.featuredProfiles!="undefined")
				if(response.featuredProfiles instanceof Array)
			{
				stype = response.featuredProfiles[0].stype;
				totalCount = response.featuredProfiles.length;
		}
		
	}
	var trackingParams = contactTracking + '&stype=' + stype;
	var idd = $(this).attr("tupleOffset"); //LATER decide offset or actual offset
	
	
	/**
	 * View profile Link
	 */
	 
  if(response.listType != "undefined" && response.listType=="vsp")
  {
    navigator = NAVIGATOR;
    var viewProfileUrl = '/profile/viewprofile.php?'+ navigator + '&profilechecksum=' + profilechecksum + '&stype='+ stype + '&offset=' + idd + '&responseTracking=' + response.responseTracking;
  }
  else if(response.listType != "undefined" && response.listType=="cc")
  {
	var actualOffset = ((parseInt(response.page_index)-1)*parseInt(profilesPerPage))+parseInt(idd)+1;
	 totalCount = response.total;
    var viewProfileUrl = '/profile/viewprofile.php?total_rec=' + totalCount + '&searchid=' + lastSearchId + '&' + navigator + '&profilechecksum=' + profilechecksum + '&' + response.tracking + '&Sort=' + response.sorting + '&offset=' + idd +'&actual_offset='+actualOffset+'&contact_id='+response.contact_id;
  
  }
  else
	 var viewProfileUrl = '/profile/viewprofile.php?total_rec=' + totalCount + '&searchid=' + lastSearchId + '&' + navigator + '&profilechecksum=' + profilechecksum + trackingParams + '&Sort=' + response.sorting + '&offset=' + idd+'&j='+response.page_index;
  
        if(typeof(loggedInJspcUser)!="undefined" && loggedInJspcUser=="" ){
                $(this).attr("href", "javascript:void(0)");
        }else{
                $(this).attr("href", viewProfileUrl);
        }
});

}


/**Binding of photo to open album of the tuple*/
$('body').on('click','.js-searchTupleImage:has(.js-openAlbum)', function() {
        if(typeof(loggedInJspcUser)!="undefined" && loggedInJspcUser=="" ){
                return true;
        }
    var dataFound = $(this).attr("data");
    dataFound = dataFound.split(",");
    
    var username = dataFound[1];
    var profilechecksum = dataFound[2];
    var albumCount = dataFound[0];
    var hasAlbum = dataFound[3];
    openPhotoAlbum(username,profilechecksum,albumCount,hasAlbum);       
});

/** Binding of Photo with some action to perform*/
$('body').on('click','.js-hasaction', function() {
  /**
   * If action is request call request photo else if login than login will be handled LATER
   * myaction : "Request" or "Login" values are permissible
   */
  if
   ($(this).attr("myaction") == "Request") {
    
    requestphoto($(this).attr("data"), $(this).attr("id"));
  
  } else if ($(this).attr("myaction") == "Login") {
    
    //console.log("error1");// LATER
    //alert(" login to view photo of profilechecksum " + $(this).attr("data"));
  }

});
/**
 * This function is used to load prev and next images.
 * This function make sure that focussed image gets loads 1st and then next and prev one so that when user slides image is already loaded.
 */
function loadNextImages(self) {
  if(self.substring(0,4)!="iddf")
  {
    var ele = $("#" + self);
    var next = "idd" + (parseInt(self.substring(3)) + 1).toString();
  }
  else 
  {
    var next = "iddf" + (parseInt(self.substring(4)) + 1).toString();
    var ele = $("#" + self);
  }
  if (ele != "undefined") setImageSrc($(ele),next);
 
}

/**
 * This function is used to set the image for element
 */
function setImageSrc(ele,next) {
  if (ele != "undefined" && $(ele).find("img[dsrc]").attr("onload") == "") {
    var eleSrc = $(ele).find("img[dsrc]").attr("dsrc");
    var eleSrcOld = $(ele).find("img[dsrc]").attr("src");
    var id = $(ele).attr("id");
    $(ele).find("img[dsrc]").attr("onload", 'loadNextImages("' + next + '")');
    $(ele).find("img[dsrc]").attr("onerror", 'loadNextImages("' + next + '")');
    $(ele).find("img[dsrc]").attr("onabort", 'loadNextImages("' + next + '")');
    if (eleSrcOld != eleSrc) 
      $(ele).find("img[dsrc]").attr("src", eleSrc);
    else 
      loadNextImages(next);
   }

}
$('body').on('click','.js-verificationPage', function(e) {
        if(typeof(loggedInJspcUser)!="undefined" && loggedInJspcUser=="" ){
        }else{
                e.preventDefault();
                e.stopPropagation();
                window.location.href = "/static/agentinfo";
        }
});
