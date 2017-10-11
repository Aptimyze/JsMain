var filteredProfilesHeadShown=0,loadImageId="idd1";

$(document).ready(function() {

    showSearchLoader('Show');

    $("#vspMainDiv").html();
    //handle visibility of contacted user details(top section)
    handleContactedProfileDetailsVisibilty(showContactedProfileDetails);
    
	//loads VSP page data
	loadVSPResponse(response);

    //bind click on back to view profile page link on vsp
    $("#vspBackLink").on("click",function(){
       window.location.href='/profile/viewprofile.php?'+viewProfileBackParams.replace(/&amp;/g, '&');
    });

    //binding for view profile 
    fillProfileViewHref(response);
    
    //binding on click of success story tuple
    $('.js-vspSuccessStorySection').each(function(i, obj) {
    //$("body").on("click",".js-vspSuccessStorySection",function(){
        var data = $(this).attr("data");
        var successStoryData = data.split(",");
       var redirectUrl="/successStory/completestory?year="+successStoryData[0]+"&sid="+successStoryData[1];
       $(this).attr("href", redirectUrl);
    });
});

/** 
* This function show/hides ContactedProfileDetails on top
* 
* @param : flag("show"/"hide")
*/
function handleContactedProfileDetailsVisibilty(flag)
{
    if(flag == "show")
    {
        $("#contactedProfileDetailsDiv").show();
        $("#vspBackLink").show();
    }
    else
    {
        $("#contactedProfileDetailsDiv").hide();
        $("#vspBackLink").hide();
	   $("#VSPResultsHeading").addClass("mtn70");
    }
}
/** 
* This function will populate VSP page response
* 
* @param : response
*/
function loadVSPResponse(response)
{
	showSearchLoader('hide');
    /*$("#featuredResultsBlock,#featuredFirstResultsBlock").html("");
    $("#featuredListing,.showLessFeatured,#featuredProfiles").hide();
    $("#featuredMoreMsg").show();*/
    if(similarPageShow == 1)
    {
        $("#zeroResultSection").hide();
        $("#VSPResultsHeading").html(response.pageSubHeading);
        if(response.profiles)
        {
            dataForVSPTuples(response);
            loadNextImages(loadImageId); 
        }
    }
    else{
        $("#zeroResultSection").show();
        //$("#VSPResultsHeading").html(response.noresultmessage);
        $("#zeroPageHeading").html(response.noresultmessage);
    }

   
    //send ajax request to load membership message data
    sendMembershipDataRequest();

    //send ajax request to load success story data
    sendSuccessStoryDataRequest();
}

/** 
* This function will load VSP tuple data
* 
* @param : response
*/
function dataForVSPTuples(response)
{
    var tupleStructure = $(".js-searchTupleStructure").html(),vspTuple="";
    var profileNoId = 1,featuredCount = 0;
    var defaultImage = response.defaultImage;
    $.each(response.profiles,function( key, val ){ 
        var profileOffset = key;
        if (val.photo.label) 
            noPhotoDiv = noPhotoDivFn(val.photo.label, removeNull(val.profilechecksum), profileNoId, val.photo.action)
        else noPhotoDiv = '';
        //if(val.photo.url && noPhotoDiv=="")
          //  defaultImage = val.photo.url;
      
        var mapObj = searchResultMaping(response.profiles, noPhotoDiv, val, profileNoId, defaultImage, featuredCount, profileOffset,"profiles",response);
        vspTuple = $.ReplaceJsVars(tupleStructure,mapObj);
        /**
    
      * contact engine block //added by Palash
      */
     
        contactEngineButtons=(new ContactEngineCard('VSP')).buttonDisplay(val.buttonDetailsJSMS,val);
        contactEngineButtons=contactEngineButtons ? contactEngineButtons : '';  
        vspTuple=vspTuple.replace(/\{\{contactEngineBar\}\}/g,contactEngineButtons);
      
      
      /**
      * contact engine block
      */
        
        
        if(vspTuple)
        {
            /*if(detailsArr.FEATURED == 'Y')
            {
                var firstFeatured="";
                ++totalFeaturedProfiles;

                if(detailsArr.OFFSET == 1)
                    var firstFeatured = "First";
                $("#featured"+firstFeatured+"ResultsBlock").append(vspTuple);

                //Count and more button
                $("#featuredProfiles").show();
            }
            else
            {*/
                $("#searchTuplesMainDiv").append(vspTuple);
            //}
        }
        if(profileNoId%5 == 0 && profileNoId && profileNoId <25)
            $("#searchTuplesMainDiv").append("<div class='mt25' id='zt_"+masterTag+"_belly"+(profileNoId/5)+"'> </div>");
         ++profileNoId;
         vspTuple = "";
    });
    
      
    /*var featuredCountToDisplay = parseInt(totalFeaturedProfiles)-1;
    if(featuredCountToDisplay>0)
        $("#featuredMoreMsg").text("("+featuredCountToDisplay+" more)");
    else
        $("#featuredMoreMsg").text("");*/
}


$('.js-viewTupleImage').on('click', function() {
    var dataFound = $(this).attr("data");
    dataFound = dataFound.split(",");
    
    var username = dataFound[1];
    var profilechecksum = dataFound[2];
    var albumCount = dataFound[0];
    var hasAlbum = dataFound[3];
    openPhotoAlbum(username,profilechecksum,albumCount,hasAlbum);       
});
