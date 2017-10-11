/**
 * This file is hadling Tuples , paginations and tuple images
 */

//Globad variables
var filteredProfilesHeadShown=0;
 
 
/** Function to load page with async call	
 */
function loadPage(pageNo) {
  filteredProfilesHeadShown = 0;
  loadImageId = "idd1";
  /**
   * Params to be used for calling pagination
   */
  if(response.infotype == "VISITORS")
      var postParams = "results_orAnd_cluster=onlyResults&matchedOrAll="+matchedOrAll+"&currentPage=" + pageNo;
  else
      var postParams = "results_orAnd_cluster=onlyResults&currentPage=" + pageNo;
  var infoArr = {};
  infoArr["action"] = "pagination";
  infoArr["listType"] = listType;
  if(response.infotype == "VISITORS")
      updateHistory("visitors?matchedOrAll="+matchedOrAll,pageNo);
  else
      updateHistory("",pageNo);   //added to update url on click of next and prev button
  sendProcessSearchRequest(postParams,infoArr);
  return false;

}

/** 
 * Populating pagination block 
 */
function dataForPagination(paginationArray, page_index) {
  /**
   * Pagination basic structure
   */
  var paginationStructure = $('.js-paginationLiStructure').html();
  $('#paginationLiDiv').html("");
  /**
   * Populating pages
   */
if(paginationArray.length>1)
{
  $.each(paginationArray, function(key, val) {
    pageNo = val;
    if (val == page_index) 
      {
        activeClass = "active fontlig";
      }
    else activeClass = "cursp";
    var mapObj = {
      '{pageNo}': removeNull(pageNo),
      '{activeClass}': removeNull(activeClass)
    };
    paginationLi = $.ReplaceJsVars(paginationStructure, mapObj);
    $('#paginationLiDiv').append(paginationLi);

  });
}
}




/**
 * Populate Tuples in the SRP
 */
function dataForSearchTuple(val,keyOfProfileType,resp) {

  try{
    currentPageName = resp.searchBasedParam;
  }catch(err){
    
  }
  /**
   * tupleStructure variables loads default tuple structure.
   */

  var tupleStructure = $(".js-searchTupleStructure").html();
  var noPhotoDiv;
  /** Default stock image for SRP
   */
  var defaultImage = response.defaultImage;

  /** 
   * Count of featured profiles sent in response
   */
  var featuredCount = 0;
  if(keyOfProfileType=="featuredProfiles"){
      $("#featuredResultsBlock,#featuredFirstResultsBlock").html("");
      $("#featuredResultsBlock").hide();
      $("#featuredProfiles").hide();
        var featuredCount = val.length;
    }
  else if(keyOfProfileType=="profiles"){
    $("#searchTuplesMainDiv").html("");
  }
  
  if(resp.page_index!=1){
      $("#featuredListing").hide();
        
  }else{
      $("#featuredListing").show();
  }
  
  /**
   * Need to replace the area with loader
   */
  //$("#searchTuplesMainDiv").html(""); // LATER need to replace with loader

	if(val!==null)
	{
		var profileNoId=0;
		$.each(val, function(key1, val1) {
      
			//var profileNoId = ((parseInt(response.page_index - 1)) * _SEARCH_RESULTS_PER_PAGE) + key1 + 1;
			profileNoId++;
			var profileOffset = key1;
			if (val1.photo.label) noPhotoDiv = noPhotoDivFn(val1.photo.label, removeNull(val1.profilechecksum), profileNoId, val1.photo.action)
			else noPhotoDiv = '';

			/** Mapping Array in Object*/
			var mapObj = searchResultMaping(val, noPhotoDiv, val1, profileNoId, defaultImage, featuredCount, profileOffset,keyOfProfileType,resp);

			/**
			* Removes Loader
			* Add Data into structure of tuple
			* append data at the end
			*/
			searchTuple = $.ReplaceJsVars(tupleStructure, mapObj);

    

      /**
    
      * contact engine block //added by Palash
      */
      //NOT WORKING FOR INBOX API"S ----check added by ankita   LATER
     
        contactEngineButtons=(new ContactEngineCard('search')).buttonDisplay(val1.buttonDetailsJSMS,val1);
        contactEngineButtons=contactEngineButtons ? contactEngineButtons : '';  
        searchTuple=searchTuple.replace(/\{\{contactEngineBar\}\}/g,contactEngineButtons);
      
      
      /**
      * contact engine block
      */
      if(resp.profiles)
				var totalProfile = resp.profiles.length;
			else if(featuredCount)
				var totalProfile  = featuredCount;

    	if(keyOfProfileType=="featuredProfiles"){
			var firstFeatured="";
			  if(profileNoId==1)
			    var firstFeatured = "First";
			$("#featured"+firstFeatured+"ResultsBlock").append(searchTuple);

			//Count and more button
			$("#featuredProfiles").show();
			var featuredCountToDisplay = parseInt(featuredCount)-1;
			if(featuredCountToDisplay>0)
			    $("#featuredMoreMsg").text("("+featuredCountToDisplay+" more)");
			}
			else{
              $("#featuredMoreMsg").text("");
              $("#searchTuplesMainDiv").append(searchTuple);
          }
          if(profileNoId%5 == 0 && profileNoId && profileNoId <25 && (totalProfile-profileNoId)>=5)
            $("#searchTuplesMainDiv").append("<div class='mt15' id='zt_"+masterTag+"_belly"+(profileNoId/5)+"'> </div>");
		});
	 }
}



function setPriority(msg1, msg2) {
  if (msg2) return msg2;
  else return msg1;
}



$('body').on('click', '#featuredProfiles', function(){
    var timeTrasition = 3000;
	
    if($("#iddf2").length==0)
	return;
    
    if($("#iddf2:visible").length>0)
    {
        $("#featuredProfiles").css("top","0px");
        $("#featuredResultsBlock").slideUp(1000,function(){
                $("#featuredProfiles").css("top","-7px");
            });
        $("html,body").animate({ scrollTop: $("#featuredListing").offset().top-100 },1000);
        $("#featuredListing .srpprofbox .fullwid.clearfix").css("border-left","none");
        $("#featuredProfiles .showLessFeatured").hide();
        $("#featuredProfiles #featuredMoreMsg").show();
    }
    else if($("#iddf2:visible").length==0){
            $("#featuredProfiles").css("top","0px");
            $("#featuredResultsBlock").slideDown(timeTrasition,function(){
                $("#featuredProfiles").css("top","-7px");
            });
            $("#featuredListing .srpprofbox .fullwid.clearfix").css("border-left","5px solid #d9475c");
            
            $("#featuredProfiles .showLessFeatured").show();
            $("#featuredProfiles #featuredMoreMsg").hide();
            //$("#featuredResultsBlock").shake();
        }

});

