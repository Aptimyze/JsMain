/**
* This file is search js integrating clusters, tuples etc
*/
var loadImageId = "idd1"; // First image id to load
var loadFeaturedImageId = "iddf1"; // first featured profile image id
var profChecksumCheckArr = new Array();
/**
* Document ready function to populate first response
*/
var listType;

$(document).ready(function() {
	showSearchLoader('Show');

	/**
	* Loading first response
	*/
	loadPageResponse(response);
	/**
	* global variable that will store searchId (current).
	*/
	lastSearchId = response.searchid; 
	lastSearchBasedParam = response.searchBasedParam;
	listType = response.listType;
        changeMatchShowHide(lastSearchBasedParam,response.matchAlertsLogic);
        
	/**
	* Handling Clusters
	*/
	invokeSliderCluster();	

    	/**
	* Handling Clusters
	*/
        //bottomBorderNavigation();
        
	/**
	* Binding next page button
	*/
	$('#paginationNext').bind('click', function() {
		currentPage = $('#paginationLiDiv').find('.active').attr("data");
		loadPage(parseInt(currentPage) + 1);
                if(response.infotype == "VISITORS")
                    updateHistory("",parseInt(currentPage) + 1);
	});

	/**
	* Binding prev page button
	*/
	$('#paginationPrev').bind('click', function() {
		currentPage = $('#paginationLiDiv').find('.active').attr("data");
		loadPage(parseInt(currentPage) - 1);
                if(response.infotype == "VISITORS")
                    updateHistory("",parseInt(currentPage) + 1);

	});

	/** Listing Pages **/
	$('#shifttabright').click(function(){
            $('.tabwidt ul').animate({ 'left':'-'+setGap+'px'},function(){ $('#shifttabright').fadeOut(200,function(){ $('#shifttableft').fadeIn(200);}) });		
            bottomBorderNavigation("-"+setGap);
	});

	$('#shifttableft').click(function(){
            $('.tabwidt ul').animate({ 'left':'0'},function(){ $('#shifttableft').fadeOut(200,function(){ $('#shifttabright').fadeIn(200);}) });		
            bottomBorderNavigation("0");
	});

	$('.showOnlineNow').bind('click', function() {
            var postParams;
            if($(this).hasClass("cursp")){
		postParams = "addRemoveCluster=1&appClusterVal=O&appCluster=1";
            }else{
		postParams = "addRemoveCluster=1&appClusterVal=&appCluster=1";
            }
            var infoArr = {};
            infoArr["action"] = "stayOnPage";
            sendProcessSearchRequest(postParams,infoArr);
        });
	
	$(".sortOrder").bind('click', function() {
            if($(this).hasClass("cursp") && !$(this).hasClass("showOnlineNow")){
                var sort = $(this).children("span").text()=="Relevence"?"R":"O";
                sorting(sort);
		var postParams;
		postParams = "sort_logic="+$(this).attr('value');
            	var infoArr = {};
                infoArr["action"] = "stayOnPage";
                infoArr["searchID"] = "skip";
		sendProcessSearchRequest(postParams,infoArr);	
            }
	});
        
        $(".js-visitors").bind('click', function() {
            if($(this).hasClass("cursp")){
                var value=$(this).attr('value');
                var sort = value=="M"?"M":"A";
                var oppo = value=="M"?"A":"M"; 
                $(".js-visType"+oppo).removeClass("js-sort-grey").removeClass("cursd").addClass("cursp");
                $(".js-visType"+sort).addClass("js-sort-grey").removeClass("cursp").addClass("cursd");
		var postParams;
		postParams = "matchedOrAll="+value+"&pageNo=1";
                matchedOrAll=value;
            	var infoArr = {};
                infoArr["action"] = "stayOnPage";
                infoArr["searchID"] = "skip";
                infoArr["listType"] = "cc";
		sendProcessSearchRequest(postParams,infoArr);	
                updateHistory("visitors?matchedOrAll="+value,1);
            }
	});

	$(".js-searchLists").bind('click', function() {
            searchListingAction(this);
	});
	/** Listing Pages **/
        if(window.location.href.search("searchBasedParam")!=-1){
            var searchList = window.location.href.split("searchBasedParam=")[1].split("&")[0];
            switch (searchList){
                case 'matchalerts':
                        clickOn = "js-matchalerts";
                        break;
                case 'partnermatches':
                        clickOn = "js-searchListsDpp";
                        break;
                case 'reverseDpp':
                        clickOn = "js-searchListsRdpp";
                        break;
                case 'justJoinedMatches':
                        clickOn = "js-searchListsJJ";
                        break;
                case 'twowaymatch':
                        clickOn = "js-searchListsMM";
                        break;
                case 'kundlialerts':
                        clickOn = "js-searchListsKM";
                        break;
                case 'shortlisted':
                        clickOn = "js-shortlisted";
                        break;
                case 'visitors':
                        clickOn = "js-visitors";
                        break;
                case 'contactViewAttempts':
                        clickOn = "js-viewAttempts";
                        break;
            }
            $('.matchtabs li.active').removeClass('active').addClass("cursp");
            $("#"+clickOn).closest('li').addClass('active');
            $("#"+clickOn).removeClass("cursp").addClass("cursd");
        }


});

/**
* Function which will be used for the action of search Listing
*/
function searchListingAction(thisElement){
        if($(thisElement).hasClass("cursp")){
                var postParams;
                var postParams1;
                $('.matchtabs li.active').removeClass('active').addClass("cursp");
                $(".matchtabs li .js-searchLists").removeClass("cursd").addClass("cursp");    
                $(thisElement).closest('li').addClass('active');
                $(thisElement).removeClass("cursp").addClass("cursd");
                bottomBorderNavigation();
                
                switch (thisElement.id){
                        case 'js-matchalerts':
                                postParams = "matchalerts=1&resetMatchAlertCount=1";
				listType="search";
                                break;
                        case 'js-searchListsDpp':
                                postParams = "partnermatches=1";
				listType="search";
                                break;
                        case 'js-searchListsRdpp':
                                postParams = "reverseDpp=1";
				listType="search";
                                if($("#shifttabright:visible").length>0)
                                   setTimeout(function(){ $("#shifttabright").click(); }, 500);
                                break;
                        case 'js-searchListsJJ':
                                postParams = "justJoinedMatches=1";
				listType="search";
				if(setGap==410)
	                                if($("#shifttableft:visible").length>0)
        	                            setTimeout(function(){ $("#shifttableft").click(); }, 500);
                                break;
                        case 'js-searchListsMM':
                                postParams = "twowaymatch=1";
				listType="search";
				if(setGap!=410)
	                                if($("#shifttableft:visible").length>0)
        	                            setTimeout(function(){ $("#shifttableft").click(); }, 500);
                                break;
                        case 'js-searchListsKM':
                                postParams = "kundlialerts=1";
				listType="search";
                                break;
                        case 'js-shortlisted':
				postParams = "searchId=8&currentPage=1";
				postParams1 = "shortlisted=1";
				listType="cc";
                                break;
                        case 'js-visitors':
                                postParams = "searchId=5&currentPage=1&matchedOrAll=A";
                                postParams1 = "visitors=1";
                                matchedOrAll='A';
				listType="cc";
                                break;
                        case 'js-fsoVerified':
                                postParams = "verifiedMatches=1";
				listType="search";
                                break;
                        case 'js-viewAttempts':
                                postParams = "contactViewAttempts=1";
				listType="search";
                                break;
                }
                if(thisElement.id=="js-visitors")
                    updateHistory("visitors?matchedOrAll=A",1);
                else if(postParams1)
	                updateHistory(postParams1.split("=")[0],1);
                else if(postParams)
	                updateHistory(postParams.split("=")[0],1);
                postParams=postParams;
                var infoArr = {};
                infoArr["action"] = "stayOnPage";
                infoArr["listType"] = listType;
		if(0)
                infoArr["pageOfResult"] = window.location.href.split("/")[6]>0?window.location.href.split("/")[6]:"1";
                lastSearchBasedParam = '';
                sendProcessSearchRequest(postParams,infoArr,'noSearchId');
                resetVisitorTabs(response);
            }
}
/**
* Function which will use api response and populate tuples and clusters
*/
function pageResponsePopulate(response) {
	
		/** call to get guna score **/
		if(typeof(loggedInJspcUser)!="undefined" && loggedInJspcUser!=""){
				getGunaScore(response);
		}
        $("#featuredResultsBlock,#featuredFirstResultsBlock").html("");
        $("#featuredListing,.showLessFeatured,#featuredProfiles").hide();
        $("#featuredMoreMsg").show();
	/** reading json of response **/
	$.each(response, function(key, val) {
		if (key == 'profiles') {
			/**
			 * Handling tuples population
			 */
			dataForSearchTuple(val,key,response);
		}
                else if(key == 'featuredProfiles') {
			/**
			 * Handling featured tuples population
			 */
			dataForSearchTuple(val,key,response);
		}
		else if (key == 'clusters') {
			/**
			*  Handling clusters population
			*/

			/**
			* show clusters section is replaced by title on left in case of shortlisted section / visitors
			*/
			if(response.listType=='cc' || response.listType=='noClusSearch')
			{
				var infoArr1 = {};
				infoArr1["action"] = "noClusterSection";
		                infoArr1["heading"] = response.heading;
                		infoArr1["totalCount"] = response.total;
		                infoArr1["message"] = response.ccmessage;
		                infoArr1["searchBasedParam"] = response.searchBasedParam;
				loadClusters(val,infoArr1);
			}
			else
				loadClusters(val);
		}
	});
        
	fillProfileViewHref(response);

	
	
	if($("#iddf1").length>0)
	        var TopTupple = $("#iddf1");
	else
	        var TopTupple = $("#idd1");
        $(TopTupple).removeClass("mt25").addClass("mt8");
        $(TopTupple).children(".srpprofbox").removeClass("mt25").addClass("mt8");

	/**
	* Subheading section like "Shown below are members who match your Desired Partner Profile" do not come in normal search results.
	* subheading is updated here
	*/
	if($('#pageSubHeading').length)
	{
        	if(typeof response.pageSubHeading!="undefined" && response.pageSubHeading!=null)
		{
			$("#pageSubHeadingTop").show();
			$("#pageSubHeading").html(response.pageSubHeading);
		}
		else
		{
			$("#pageSubHeadingTop").hide();
			$("#pageSubHeading").html("");
		}
	}	

	/**
 	* Hide show sorting options.
 	*/
	if(response.showSortingOption=='N')
		sorting('hide',response.listType);
	else
                sorting(response.sorting);
	

	/** 
	* Handle zero results / >0 results
	* If there are zero results, we need to hide everything (js-searchContainer)
 	**/
	showSearchLoader('hide');
	if(response.no_of_results!=0)
	{
		$("#js-searchContainer").show();
		$("#zeroResultSection").hide();
		if(response.listType=='cc' || response.listType == 'noClusSearch')
	                $("#heightRight").addClass('srpHeightRightcc').removeClass('srpHeightRight');
		else
	                $("#heightRight").addClass('srpHeightRight').removeClass('srpHeightRightcc');
                    
                if(response.infotype == "VISITORS"){
                        $("#heightRightVisitors").addClass('srpHeightRight').removeClass('disp-none');
                        $("#ClusterTupleStructure").addClass('srppt28');
                }
                else{
	                $("#heightRightVisitors").addClass('disp-none').removeClass('srpHeightRight');
                        $("#ClusterTupleStructure").removeClass('srppt28');
                }
	}
	else
	{
		if(response.listType=='cc')
		{
			if(response.result_count.indexOf("0")!=-1)
			{
				response.result_count = response.result_count.replace(" 0","");
				response.result_count = response.result_count.replace("0","");
				response.result_count = "0 "+response.result_count;
			}
		}
		/** handling zero results message */
		$("#zeroPageHeading").html(response.result_count);
		$("#zeroPageMsg").html(response.noresultmessage);
		$("#js-searchContainer").hide();
		$("#zeroResultSection").show();
	}
	LoginBinding();
	cECommonBinding();

}



/**
 *  loading Page response after async call to remove loader
 */
function loadPageResponse(response) {
   
        lastSearchBasedParam = response.searchBasedParam;

	if(response.result_count)
	{
		if(response.listType=='cc' || response.listType == 'noClusSearch')
		{
			$("#searchResultsBlock").removeClass("mt8").addClass("mt13");
			$("#pageHeading").hide();
		}
		else
		{		
			$("#pageHeading").show();
			$("#pageHeading").html(response.result_count);
			$("#searchResultsBlock").removeClass("mt13").addClass("mt8");
		}
	}

	/**
	* global variable that will store searchId (current).
	*/
        if(typeof response.searchid!="undefined")
            lastSearchId = response.searchid;
        
        if(typeof response.newTagJustJoinDate!="undefined")
		newTagJustJoinDate = response.newTagJustJoinDate
	else
		newTagJustJoinDate = 0;

        updateHistory("","",lastSearchId);
        changeMatchShowHide(response.searchBasedParam,response.matchAlertsLogic);
	/**
	* Populating tuples and clusters based on response
	*/
	pageResponsePopulate(response);
        resetTopTab();
        hidesaveSearchSuccess();
	/** 
	* Populating pagination data
	*/
	if(response.no_of_results!=0)
		dataForPagination(response.paginationArray, response.page_index);

	/**
	* Load images one after another initiating with first id
	*/
	if(response.no_of_results!=0)
	{	
		loadNextImages(loadImageId); 
		loadNextImages(loadFeaturedImageId); 
	}
	
        /**
	* Show Hide personal Options
	*/
        if(0){ //Condition LATER
            hidePersonalizedOptions();
            showPersonalizedOptions();
        }
	/** 
	 * Call to function handling pagination on page load
	 */
	if(response.no_of_results!=0)
		handlePagination(response);
	/** 
	 * Call to function handling RCB on page load
	 */
	if(response.no_of_results!=0)
		handleRCB(response);
	
	//LOGIN Binding
	LoginBinding();
	
	//ZEDO
	if(typeof zmt_get_tag== "function")
		renderBanners();
	
}
function handleRCB(response){
        //RCB Communication 
  if (response.hasOwnProperty('display_rcb_comm') && response.display_rcb_comm &&
    typeof response.profiles != "undefined" && response.profiles != null) {
    var countOfProfiles = response.no_of_results;
    
    if(countOfProfiles >= 3){
      $("<div class='rel_c js-rcbMessage' id='callDiv1'><div class='ccp2 fontlig color11'><div class='mainBrdr clearfix'><div class='f14 fontlig wid60p inDisp fl'>Become an EValue member and allow hundreds of matching profiles like these to view your contacts without membership. Would you like us to call you and explain the benefits of Evalue?</div><div class='pt15 pb30 color2 f14 fr inDisp verTop'><span class='hlpcl1 calUserDiv cursp' id='callUser'>Yes, call me</span><span id='noButton' class='hlpcl11 cursp bg6 noUserDiv'>No, Later</span></div></div></div></div>").insertAfter("#idd3");
      
      //On Yes Call Now
      $("#callUser").off("click");
      $("#callUser").on("click", function () {
        $('<input>').attr({type: 'hidden',id:'rcbResponse', name: 'rcbResponse',value:'Y'}).appendTo('#Widget');
        $(".js-openRequestCallBack").click();
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
          data: {rcbResponse:'N'},
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

/**Show Online now profiles only*/
function sorting(sort,listType){
    if(typeof sort=="undefined")
        return false;
    else if(sort=="hide"){
	$(".sortOrderF").hide();
	$(".sortOrderR").hide();
	$(".sortOrderOnline").hide();
    }
    else{
	$(".sortOrderF").show();
	$(".sortOrderR").show();
	$(".sortOrderOnline").show();
	var oppo = sort=="O"?"R":"F";
	var sort = sort=="O" ? "F":"R"; 
	$(".sortOrder"+oppo).removeClass("js-sort-grey").removeClass("cursd").addClass("cursp");
	$(".sortOrder"+sort).addClass("js-sort-grey").removeClass("cursp").addClass("cursd");
        resetTopTab();
    }
}


/**Remove profile binding */
$("body").delegate('.js-removeProfile, .js-search-undoRemoveProfile','click', function() {
	var srpTuple = $(this).attr("id").replace("idRemove","").replace("undoRemove","");
	 var profileCheckSum = $(this).attr("data"),chatData = $(this).attr("data-chat");
	 var usernameOfProfile = $("#idd"+srpTuple+" .usernameOfTuple").text();
	var url = '/api/v1/common/ignoreprofile';
	if($("#idd"+srpTuple+"removed:visible").length>0){
	     var blockOrUnblock = 0;
	}else
	     var blockOrUnblock = 1;
       
	if((blockOrUnblock==1 && $(this).text().indexOf("Ignore")!=-1) || blockOrUnblock==0){
	    var postParams = {'blockArr[profilechecksum]':profileCheckSum,'blockArr[action]':blockOrUnblock};
	    $.myObj.ajax({
		    url: url,
		    dataType: 'json',
		    type: 'POST',
		    data: postParams,
		    timeout: 60000,
                    beforeSend: function( xhr ) {
                           showCommonLoader();
                    },
		    success: function(response) {
                        if(response.responseStatusCode==1)
                        {
                        hideCommonLoader();
			showCustomCommonError(response.responseMessage,5000);
                        return;
                        }
			callAfterContact();
                        hideCommonLoader();
			if(response.status==1 && blockOrUnblock==1){
				//console.log("ignore from search module");
				if(updateNonRosterListOnCEAction && typeof updateNonRosterListOnCEAction == "function"){
					if(chatData != undefined){
						var details = chatData.split(",");
						updateNonRosterListOnCEAction({"user_id":details[0],"action":details[1]});
					}
				}
			    blockProfileOnSRP(srpTuple,profileCheckSum,usernameOfProfile);
			}
			else if(response.status==0 && blockOrUnblock==0){
			    unblockProfileOnSRP(srpTuple,profileCheckSum);
			 }
			 else{
			     //alert(response.responseMessage);
				//console.log("error2");// LATER
			 }

		    },
		    error: function(xhr) {
		      //alert("error");
			//console.log("error3");// LATER
		    }
		  });
	}
});

	




/**  
* Handle frontend Blocking changes on SRP
*/
function blockProfileOnSRP(srpTuple,profileCheckSum,usernameOfProfile){
        var replaceRemovedProfile = "<div class='srpprofbox disp-none' id='idd"+srpTuple+"removed'>\
                    <div class='fullwid clearfix srpbg2'> \
                        <div class='srppad28'>\
                                <div class='clearfix f13 fontlig'>\
                                <div class='fl'>\
                                         <div class='srpbdr8  srpdim2 srprad1'>\
                                        <img src='"+$("#idd"+srpTuple+" .photoOfTuple").attr("src")+"' style='border-radius:30px;width:53px;height:53px;' class='srpdim1 srprad1'/>\
                                     </div>\
                                </div>\
                                <div class='fl pl10 pt10'>\
                                        <div class='color11'>"+usernameOfProfile+"</div>\
                                    <div class='colr2 pt3'>This profile has been moved to Blocked/Ignored list. It will not appear again in future searches or in other listings.</div>\
                                </div>\
                                <div class='fr pt25 colr5 js-search-undoRemoveProfile cursp' id='undoRemove"+srpTuple+"' data='"+profileCheckSum+"'>\
                                        Undo\
                                </div>\
                            </div>\
                        </div>\
                    </div>\
                  </div>";

                    $("#idd"+srpTuple).append(replaceRemovedProfile);
                    $("#idd"+srpTuple+"Tuple").slideUp('slow', function() {
                            $("#idd"+srpTuple+"removed").slideDown('slow')
                    });
                    
}

/**  
 * Handle frontend Unblocking changes on SRP
 */
function unblockProfileOnSRP(srpTuple,profileCheckSum){
        $("#idd"+srpTuple+"removed").slideUp('slow',function(){
            $("#idd"+srpTuple+"Tuple").slideDown('slow',function(){
                $("#idd"+srpTuple+"removed").remove();
            });
        });
}


/**  
 * Start Handling pagination bindings and actions
 */
function handlePagination(response){
	
	
	$('#paginationLiDiv').find('li').each(function(i, obj) {
		
		/** 
		 * Binding pages other than current
		 */
		if (!$(this).hasClass("active")) {
			$(this).bind('click', function() {
				
				var myPage = $(this).attr("data");
				/**
				 * Calling load page function  for the clicked page no
				 */
				loadPage(myPage);
                                updateHistory("",myPage);
			});
		}
	});

	currentPage = parseInt(response.page_index);

	/**
	 * Hnadling next page display
	 */
	if (!response.next_avail) {
		$('#paginationNext').addClass("disp-none");
	} else {
		$('#paginationNext').removeClass("disp-none");
	}

	/**
	 * Handling prev page display
	 */
	if (response.page_index == 1) {
		$('#paginationPrev').addClass("disp-none");
	} else {
		$('#paginationPrev').removeClass("disp-none");
	}
	if (response.next_avail == 'false') {
		$('#paginationNext').addClass("disp-none");
	}
	else
		$('#paginationNext').removeClass("disp-none");
	/**  End handling pagination binding
	 */
	
}


/** 
* This function will send ajax request related to search.
* All ajax related action in which page is reloaded will be handled here.
* @param postParamArray = 
*/
function sendProcessSearchRequest(requestParams,infoArr,noSearchId) 
{
	var url = '/api/v1/search/perform';
	var searchID = lastSearchId;

	var infoArr = typeof infoArr !== 'undefined' ? infoArr : {};
	var action = typeof infoArr["action"] !== 'undefined' ? infoArr["action"] : '';
	var additionalUrl = typeof infoArr["additionalUrl"] !== 'undefined' ? infoArr["additionalUrl"] : '';
	var featuredProfiles = typeof infoArr["featuredProfiles"] !== 'undefined' ? infoArr["featuredProfiles"] : '';
	var titleOfFilter = typeof infoArr["titleOfFilter"] !== 'undefined' ? infoArr["titleOfFilter"] : '';
        var pageOfResult = typeof infoArr["pageOfResult"] !== 'undefined' ? infoArr["pageOfResult"] : '1';
        var listType = typeof infoArr["listType"] !== 'undefined' ? infoArr["listType"] : '';

	/**
	* Params to be used for calling pagination
	*/
	if(listType=='cc')
		var url = '/api/v2/inbox/perform';

	var postParams='';
	if(noSearchId)
		;
	else if(typeof searchID!="undefined")
		postParams = "searchId="+searchID+"&";
	postParams = postParams+requestParams+additionalUrl+featuredProfiles;
	if(lastSearchBasedParam!==null)
		postParams = postParams+"&searchBasedParam="+lastSearchBasedParam;
	if(newTagJustJoinDate!=0)
		postParams = postParams+="&newTagJustJoinDate="+newTagJustJoinDate;
	if(listType=='cc')
		postParams = postParams+"&ContactCenterDesktop=1";

	//alert(postParams.indexOf('partnermatches'));
	if(postParams.indexOf('partnermatches')!='-1' || postParams.indexOf('matchalerts')!='-1' || postParams.indexOf('justJoined')!='-1' || postParams.indexOf('kundlialerts')!='-1' || postParams.indexOf('twowaymatch')!='-1' || postParams.indexOf('reverseDpp')!='-1' || postParams.indexOf('verifiedMatches')!='-1' || postParams.indexOf('reverseDpp')!='-1')
		url =  getUrlForHeaderCaching(url);
	/*
        if(postParams.search("sort_logic")==-1 && postParams.search("currentPage")==-1 && pageOfResult!==null)
                postParams = postParams+"&currentPage="+pageOfResult;
	*/
        var timeI; var timeE; var timeD;	
	$.myObj.ajax({
                url: url,
		dataType: 'json',
		type: 'GET',
                cache: true,
		data: postParams,
		timeout: 60000,
		updateChatList:(infoArr["action"] == "pagination") ? true : false,
		beforeSend: function( xhr ) {
			//if(action=="moreCluster")
			if(action=='pagination' || action =='stayOnPage')
			{
        			jsb9onUnloadTracking();
				jsb9init_first();
				timeI = new Date().getTime();
			}

                        if(action=="moreCluster" || action=='pagination')
			{
				showCommonLoader(); 
			}
			else if(action=="stayOnPage")
			{
				$("#js-searchContainer").hide();
				$("#zeroResultSection").hide();
				showSearchLoader('show');
			}
			if(action=='Clusters')
			{
				if($("#relaxationBox").length>0)
					$("#relaxationBox").hide();
				lastUrl = window.location.href;
                                if(newTagJustJoinDate!=0)
                                {
                                        if(lastUrl.indexOf('newTagJustJoinDate')=='-1')
                                        {
                                                if(lastUrl.indexOf('?')=='-1')
                                                        lastUrl=lastUrl+"?newTagJustJoinDate="+newTagJustJoinDate;
                                                else
                                                        lastUrl=lastUrl+"&newTagJustJoinDate="+newTagJustJoinDate;
                                        }
                                }
			}
		},
		success: function(response) {

			if(action=="moreCluster"){
				$('.overlay').show();
				$('#filterlayer').show();
				hideCommonLoader();
				/**
				* Handling more options for educationa and occupation cluster
				*/
				formatClusterData(response,titleOfFilter);
			}
			else
			{
				if(typeof response.responseStatusCode!="undefined" && response.responseStatusCode==10)
				{
					location.reload();
				}

				if(action=='pagination'){
					hideCommonLoader();
				}

				if(action=='Clusters' || action=="stayOnPage")
					updateHistory('',1,'');

				if(parseInt(response.no_of_results)>0 || action=="stayOnPage" || action=='Clusters')
				{
					loadPageResponse(response);
					invokeSliderCluster();
					if(typeof response.searchSummary !="undefined") /** cc listings */
						invokeSearchSummary(response.searchSummary.searchSummaryFormatted);
					if(action!="searchListings")
						if(action!="stayOnPage")
							animationToTop();
					if(action=='Clusters')
						$("#zeroPageMsg").append(".  <a class='colr5' href='"+lastUrl+"'>Go Back to your original search.</a>");
				}
				else
				{
					showCommonLoader();
					if(window.location.href.indexOf("?")!=-1)
						var urlParam = "&searchId="+response.searchid;
					else
						var urlParam = "?searchId="+response.searchid;

					window.location.href = window.location.href+urlParam;
				}
			}
			hideLoader();
			resetEmailMeMatches();

			if(action=='pagination' || action =='stayOnPage')
			{
				jsLoadFlag = 1; 	
				timeE = new Date().getTime();
				timeD = (timeE - timeI)/3600;
				jsb9init_fourth(timeD,true,2,'http://track.99acres.com/images/zero.gif','AJAXSEARCHURL');
			}
		},
		error: function(xhr) 
		{
			hideCommonLoader();
			hideLoader();
			return "error";
		}
	});
	return false;
}

/**
* This function wil show the loader.
*/
function showLoader(clickedElement)
{
        var leftPos , topPos , diff;
        leftPos = $('#tupleContainer').offset().left +($('#tupleContainer').width() - $('#searchResultsLoader').width())/2;

        var scrollTop = $(document).scrollTop();
        var offsetTop = $('#tupleContainer').offset().top;
        if(offsetTop>scrollTop)
                diff = offsetTop - scrollTop;
        else
                diff = 0;
        var wHeight = $(window).height();
        var hLayer = $('#searchResultsLoader').height();
        topPos = wHeight - ((wHeight-diff)/2) - hLayer/2;
        $('#searchResultsLoader')
            .css('position','fixed')
            .css('left',leftPos)
            .css('top',topPos);
        $('#searchResultsLoader').show();
        $('.overlaywhite').show();
        enableLoader(clickedElement);
}

/**
* This function will hide the loader.
*/
function hideLoader()
{
        disableLoader();
	$('#searchResultsLoader').hide();
	$('.overlaywhite').hide();
        $('.sideClusters').removeClass("disable_href");
}


/**
* Enable Loader
*/
function enableLoader(clickedElement)
{
    //window.abc = clickedElement;
    $('.sideClusters').addClass("disable_href");
    //$('.moreCluster').addClass("disable_href");
    if(typeof clickedElement.clusterID!="undefined"){
	var id = clickedElement.clusterID.replace('&','').replace('=','');
	clusterId = id;
	$(".sideCluster"+clusterId).removeClass("disable_href");
    } 
    else
    {
    	$($(clickedElement).parent().parent().parent().parent()).removeClass("disable_href");
    }
}

/**
* Disable Loader
*/
function disableLoader()
{
    $('.sideClusters').removeClass("disable_href");
}


/**
*  This function will handle border bottom
**/
function bottomBorderNavigation(MainLeft){
    
	if($('.matchtabs li.active').length)
	{
		var leftPx = $('.matchtabs li.active').position().left;
		if(typeof MainLeft=="undefined"){
		    var finalLeft = $('.matchtabs').position().left;
		}
		else{
		    var finalLeft = parseInt(MainLeft);
		}
		finalLeft = finalLeft + leftPx;
		//console.log(finalLeft+"----"+leftPx);
		$("#leftPointUnderline").animate({
		    left:finalLeft,
		    width:$('.matchtabs li.active').width()
		},"normal");
	}
        
}

function resetTopTab(){
    if($("input[name='appCluster1[]']").length>0){
            if($("input[name='appCluster1[]']")[1].checked)
                $(".sortOrderOnline").addClass("js-sort-grey").removeClass("cursp").addClass("cursd");
            else
                $(".sortOrderOnline").removeClass("js-sort-grey").removeClass("cursd").addClass("cursp");
        }
        else
            $(".sortOrderOnline").removeClass("js-sort-grey").removeClass("cursd").addClass("cursp");
}

function resetVisitorTabs(response){
    $(".js-visTypeM").removeClass("js-sort-grey").removeClass("cursd").addClass("cursp");
    $(".js-visTypeA").addClass("js-sort-grey").removeClass("cursp").addClass("cursd");
}

/**Change Listing Logic */
$("body").delegate('.changeListingLogic','click', function() {
            var url = "/api/v1/search/matchAlertToggleLogic";
            var Coded = $(this).val()=="dpp"?"O":"N";
            var postParams = "logic="+$(this).val();
            $(".popsrp2").css("display","block");
            //resetting Canvas
            $("#listingLogicO")[0].width= $("#listingLogicO")[0].width;
            $("#listingLogicN")[0].width= $("#listingLogicN")[0].width;
	    $.myObj.ajax({
		    url: url,
		    dataType: 'json',
		    type: 'GET',
		    data: postParams,
		    timeout: 60000,
                    beforeSend: function( xhr ) {
                          
                    },
		    success: function(response) {
												callAfterDppChange();
                        $(".changeListingLogic"+response.successMessage.matchAlertLogic).attr("checked","checked");
                        if(response.successMessage.matchAlertLogic==Coded){
                            var idOfElement = "listingLogic"+Coded;
                            performTick(idOfElement);
                            if($("#pageSubHeading").text().search("history")!=-1)
                                $("#pageSubHeading").text($("#pageSubHeading").text().replace("history of your interests & acceptances","your Desired Partner Preferences"));
                            else
                                $("#pageSubHeading").text($("#pageSubHeading").text().replace("your Desired Partner Preferences","history of your interests & acceptances"));
                        }
                        setTimeout(function(){ $(".popsrp2").css("display",""); }, 3000);
		    },
		    error: function(xhr) {
			//console.log("error5");// LATER
		      //alert("error");
		    }
		  });	
});

function changeMatchShowHide(lastSearchBasedParam,changeMatchShowHide){
    if(changeMatchShowHide=='1')
	$(".js-mato").attr('checked', true);
    if(changeMatchShowHide=='0')
	$(".js-matn").attr('checked', true);

    if(lastSearchBasedParam=="matchalerts")
        $(".changematch").show();
    else
        $(".changematch").hide();
}
        
function performTick(idOfElement){
    //console.log(idOfElement);
var start = 20;
var mid = 25;
var end = 40;
var width = 3;
var leftX = start;
var leftY = start;
var rightX = mid - (width / 2.7);
var rightY = mid + (width / 2.7);
var animationSpeed = 20;

var ctx = document.getElementById(idOfElement).getContext('2d');
ctx.lineWidth = width;
ctx.strokeStyle = 'rgba(0, 150, 0, 1)';

for (i = start; i < mid; i++) {
    var drawLeft = window.setTimeout(function () {
        ctx.beginPath();
        ctx.moveTo(start, start);
        ctx.lineTo(leftX, leftY);
        ctx.stroke();
        leftX++;
        leftY++;
    }, 1 + (i * animationSpeed) / 3);
}

for (i = mid; i < end; i++) {
    var drawRight = window.setTimeout(function () {
        ctx.beginPath();
        ctx.moveTo(leftX, leftY);
        ctx.lineTo(rightX, rightY);
        ctx.stroke();
        rightX++;
        rightY--;
    }, 1 + (i * animationSpeed) / 3);
}
}

/*	This function creates a profilechecksum Array and then makes the ajax call. 
*	It places the guna score corresponding to the profilchecksum in case the repsonse is an array
*/
function getGunaScore(response)
{	var diffGender = response.diffGenderSearch;
	var profilechecksumArr = new Array();
	var deleteChecksumArr = new Array();
	var searchBasedParam = response.searchBasedParam;
	var searchResponse = response;
	var profileLength = 0;
	var featureProfileLength = 0;
	var gunaScoreArr = new Array();
	//Length of profiles array in response
	if('profiles' in searchResponse && Array.isArray(searchResponse.profiles))
	{
		profileLength = searchResponse.profiles.length;
	}

	//Length of feature profiles array in response
	if('featuredProfiles' in searchResponse)
	{
		featureProfileLength = searchResponse.featuredProfiles.length;
	}

	//loop to fetch profilchecksums for normal and featured profiles and club them in an array
	$.each(response, function(key, val) {
		if (key == 'profiles' && val!==null) {
			$.each(val, function(key1, val1)
			{
				profilechecksumArr.push(val1.profilechecksum);
				var obj = {};
				obj[val1.profilechecksum] = val1.gunascore;
				gunaScoreArr.push(obj);
			
			});
		}
		if(key  == 'featuredProfiles' && val!==null){
			$.each(val, function(key1, val1)
			{
				profilechecksumArr.push(val1.profilechecksum);
			});
		}
	});
	profChecksumCheckArr = profilechecksumArr;
	//The profileChecksumArr contains profilechecksum of both profiles and featured profiles on a particular page
	profilechecksumArr = profilechecksumArr.join(",");
	if(searchBasedParam == 'kundlialerts')
	{		
		if(profileLength == 0)
		{
			if(typeof searchResponse.paginationArray ==="undefined")
			{
				// Do nothing
			}
			else if(typeof searchResponse.paginationArray !=="undefined" && searchResponse.page_index < searchResponse.paginationArray[searchResponse.paginationArray.length -1 ])
			{
					loadPage(parseInt(searchResponse.page_index) + 1);
			}
			else
			{
					setTimeout(function(){
					$("#zeroPageHeading").html(searchResponse.result_count);
					$("#zeroPageMsg").html(searchResponse.DefaultZeroMsg);
					$("#js-searchContainer").hide();
					$("#zeroResultSection").show();
				}, 0.1);
			}
		}
		else
		{
			setTimeout(function(){
						setGunaScoreOnListing(gunaScoreArr);
					}, 100);
		}
	}
	else
	{	
            if(typeof(hideUnimportantFeatureAtPeakLoad) =="undefined" || hideUnimportantFeatureAtPeakLoad < 4){
		$.myObj.ajax({
			showError: false, 
			method: "POST",
			url : '/api/v1/search/gunaScore?profilechecksumArr='+profilechecksumArr+'&diffGender='+diffGender,
			data : ({dataType:"json"}),
			async: true,
			timeout:20000,
			success:function(response){
				gunaScoreArr=null;
				gunaScoreArr = response.gunaScores;
				setGunaScoreOnListing(gunaScoreArr);
			}
		});
            }
	}
}


//This function sets the Guna score on search tuples corresponnding to their id's
function setGunaScoreOnListing(gunaScoreArr)
{
	if(Array.isArray(gunaScoreArr))
	{
			$.each(gunaScoreArr, function(key,val){	
				$.each(val, function(profchecksum,gunaScore){
					$(".gunaScore-"+profchecksum).html("Guna "+gunaScore+"/36");
				});	
			});
	}
}

