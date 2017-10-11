var blankSaveErrorMsg = "Please provide save search name";
$("#manageSearch").bind("click",function(){
        window.location.href="/search/savedSearches";
});

/**
* save this search button click displayes name the search field
*/
$("#saveThisSearch").click(function(){
    if(parseInt($("#saveSearchCount").text())>4){
        saveSearchListing("saveButton");
    }
    else{
        $("#saveThisSearch").slideUp("normal",function(){
                $("#saveThisSearchDetails").slideDown("normal");
                $("#saveThisSearch").slideUp();
        });
    }
});


function resetSaveButton(){
    $("#saveThisSearchDetails").slideUp("normal",function(){
                $("#saveThisSearch").slideDown("normal");
    });
}


/**
* Handle enter on save search textbox.
* It simply trigger @save search button.
*/
$('#js-saveSearchName').on('keypress', function(e) {
    if(event.keyCode == 13){
        $("#saveThisSearchWithName").click();
    }
});


/**
* Save this search with name submits the save search
*/ 
$("#saveThisSearchWithName").click(function(){
        var searchName=$("#saveThisSearchName input").val();
        if(searchName.length<1){
		hideErrorMsg();
		showErrorMsg("#saveThisSearchName",blankSaveErrorMsg);
        	return false;
        }
        $.myObj.ajax({
        type: "GET",
        url: "/api/v1/search/saveSearchCall?perform=savesearch",
        data: { saveSearchName: searchName,searchId: lastSearchId},
        dataType: "json",
        beforeSend: function( xhr ) {
           showCommonLoader(); 
        },
        success: function (result, status, xResponse) {

            hideCommonLoader();
	    hideErrorMsg();
            if(result.saveDetails!=null){
                if(result.saveDetails.errorMsg==null){
                    $("#saveThisSearchDetails").slideUp("normal",function(){
                        $("#saveSearchSuccess").slideDown("normal",function(){
                            saveSearchListing("savingListBottom");
			$('#js-saveSearchName').val("");
                            //alert(result.saveDetails.successMsg);
                        });
                    });
                    $("#manageSearch").removeClass('disp-none');
                }else
		{
			showErrorMsg("#saveThisSearchName",result.saveDetails.errorMsg);
		}
            }
            else{
		showErrorMsg("#saveThisSearchName",result.saveDetails.errorMsg);
            }
        }

    }); 
});

/**
 * Display Save search on "save Search Link click at top"
 */ 
function displaySavedSearches(){
    if($("#savedSearch:visible").length==0){
        if($($("#savedSearchesListTop").children()).length==0){
            saveSearchListing("savedListTop");
        }else{
            $("#savedSearch").slideDown();
        }
    }
    else
        $("#savedSearch").slideUp();
}
/**
 * Deletion of saved Search
 */ 
function deleteSearch(searchIdToDelete,savedBoxId){
    $.myObj.ajax({
    type: "POST",
    url: "/api/v1/search/saveSearchCall?perform=delete",
    data: { searchId: searchIdToDelete},
    dataType: "json",
    beforeSend: function( xhr ) {
           showCommonLoader(); 
    },
    success: function (result, status, xResponse) {
        hideCommonLoader();
        if(result.saveDetails.errorMsg!="null"){
            if(savedBoxId!=0){
                $(".savedSearches"+savedBoxId).slideUp("normal",function(){
                    $(".savedSearches"+savedBoxId).remove();
                });
                
            }else{
                $("#saveThisLimitDetails").slideUp("normal",function(){
                    $("#saveThisSearchDetails").slideDown("normal",function(){
                       $("#saveThisSearch").hide(); 
                    });
                });
                $("#saveSearchSuccess").hide();
            }
            saveSearchListing("savedListBottomTop");
            
        }
        else{
            //alert(result.saveDetails.errorMsg);
        }
    }
    });

}

/**
 * Email me matches button click
 */  
$("#emailMeMatchesCancle").click(function(){
    resetEmailMeMatches();
});     

/**
* To handle replace DPP for email
*/  
$("#emailMeMatchesReplace").click(function(){
    var divElement = this;
    var existingHtml = "";
    $.myObj.ajax({
    type: "POST",
    url: "/api/v3/search/saveDpp",
    data: { searchId: lastSearchId},
    beforeSend: function( xhr ) {
           showCommonLoader(); 
    },
    success: function (result, status, xResponse) {
        if(result.done){
            	$(".js-email-desc").slideUp("slow",function(){
				$(".js-email-desc .emailButtons").hide();
        	    		$(".js-email-desc .mauto").text("DPP Replaced successfully and emailed");
				$(".js-email-desc").slideDown("slow",function(){
			});

		});
        }
        else{
           //alert("Something went wrong, Please try Again"); 
        }
    },
        complete: function(result) {
		hideCommonLoader();
        }
});
});  

/**
* Funtion to hanfle reset of email Me matches Block
*/
function showProcessing(divElement){
    var present = $(divElement).html();
    $(divElement).html('<p id="loading">Loading<span>.</span><span>.</span><span>.</span></p>');
    return present;
}

function hideProcessing(divElement,existing)
{ 
    $(divElement).html(existing);
}

/**
* Funtion to hanfle reset of email Me matches Block
*/
function resetEmailMeMatches(){
    $(".js-email-desc .mauto").text("This will replace your desired partner profile criteria");
    $(".js-email-desc").slideUp("normal",function(){
        $(".js-email").slideDown("normal",function(){
            $(".js-email-desc .disp-tbl").slideDown("normal");
        });
    });
}

/**
 * To handle save Search listing AJAX request
 */        
function saveSearchListing(source){
    var postParams = "date="+(new Date).getTime();
    $.myObj.ajax({
        type: "GET",
        url: "/api/v1/search/saveSearchCall?perform=listing",
	data: postParams,
        dataType: "json",
        beforeSend: function( xhr ) {
             if(source=="saveButton"){
                showCommonLoader();
            }
        },
        success: function (result, status, xResponse) {
            hideCommonLoader();
           /**
            * To handle save Search functionality On click of SaveSearch BUTTON
            */
            if(source=="saveButton"){
                    saveSearchButtonClick(result);
            }
           /**
            * To  handle save Search functionality On save search list on Top bar
            */
            else if(source=="savedListTop"){
                    saveSearchTopList(result,1);
            }
           /**
            * To handle save search listing on bottom left layer
            */
            else if(source=="savedListBottomTop"){
                    saveSearchBottomList(result);
                    saveSearchTopList(result,0);
            }
            /**
            * To handle save search listing on bottom after saving
            */
            else if(source=="savingListBottom"){
                    saveSearchBottomList(result,source);
                    saveSearchTopList(result,0);
            }
        }
    });
}

/**
* This function will handle save Search functionality On click of SaveSearch BUTTON
*/
function saveSearchButtonClick(result){
    console.log(result);
    if(result.saveDetails.details == null)
        result.saveDetails.details={};
    
    if(result.saveDetails.details.length>=5){
         var searchesToDelete="";
         $.each(result.saveDetails.details, function( key, value ) {
           searchesToDelete += "<div class='bg6 colrw disp_ib mt10' onclick='deleteSearch("+value.ID+",0)'><div class='srppad18 clearfix cursp'><div class='fl maxwid150 textTru'>"+value.SEARCH_NAME+"</div><i class='sprite2 fl crossicon ml10'></i></div></div><br/>";
         });
         $("#seavedSearchesToDelete").html(searchesToDelete);
         
         $("#saveThisLimitDetails").slideDown("slow",function(){
            $("#saveThisSearch").hide();
         });
         
    }
    else{
        $("#saveThisSearch").slideUp("normal",function(){
            $("#saveThisSearchDetails").slideDown("normal");
            $("#saveThisSearch").slideUp();
        });
    }
}

/**
* This function will handle save Search display of top bar showing saved searches
*/
function saveSearchTopList(result,showHide){
    $("#savedSearch #equalheight #savedSearchesListTop").html("");
    var val = result.saveDetails.details;
    var savedSearchTop = '';
    var count = 0;
    if(val!=null){
        $.each(val, function( key1, val1 ) {
            if(count==0)
                var margin = "";
            else
                var margin = "mr10";

                savedSearchTop += '<div class="fr savedSearches'+count+' topBoxSavedSearches wid190 equal color_blockthree '+margin+' minh100 maxh100 scrollhid" alt="'+val1.dataString+'">\
                                        <div class="fontlig">\
                                                <div class="clearfix srpcolr4 srppad22">\
                                                <div class="fl opa50 f12 pt3 maxwid150 textTru">'+val1.SEARCH_NAME+'</div>\
                                                <i class="fr sprite2 delicon cursp" onclick="deleteSearch('+val1.ID+','+count+')"></i>\
                                            </div>\
                                            <a href="/search/perform?mySaveSearchId='+val1.ID+'">\
                                                <div class="cursp saveddetail">\
                                                        <ul class="clearfix pb5 maxh39 scrollhid">\
                                                                <li><span class="disp_ib pl5 pr5">'+val1.dataString+'</span></li>\
                                                        </ul>\
                                                </div>\
                                            </a>\
                                        </div>\
                                    </div>';
            count++;
        });
    }
    
    $("#savedSearch #equalheight #savedSearchesListTop").html(savedSearchTop);
    if(count<=4){
        $("#saveSearchExtraBox").show();
        $("#saveSearchExtraBox").addClass("cursp");
        $("#saveSearchExtraBox div div").text("You can save upto "+(5-count)+" more searches");
    }
    else{
        $("#saveSearchExtraBox").hide();
    }
    if(count==0)
        count="";
    $("#saveSearchCount").text(count);
    if(showHide==1)
        $("#savedSearch").slideToggle();
}


/**
* This function will hoverout from seaved searches block
*/
var toggleSaveSearch=0;
$('body').on('click', '.topBoxSavedSearches', function()
{ 
    /*
    var divEle = this;
    $(".topBoxSavedSearches").animate({"width":"50px"},1000);
    $($(divEle)).animate({"width":"750px"},1000);
    */
  
   /* if(toggleSaveSearch%2==0){
        $(this).removeClass("scrollhid").removeClass("maxh100");
        $($(this).children("div").children(".saveddetail").children("ul")).removeClass("scrollhid").removeClass("maxh39");
    }else{
        $(this).addClass("scrollhid").addClass("maxh100");
        $($(this).children("div").children(".saveddetail").children("ul")).addClass("scrollhid").addClass("maxh39");
    } */
    toggleSaveSearch++;
});



/**
* This function will handle save Search listing of bottom left list
*/
function saveSearchBottomList(result,source){
    
    var val = result.saveDetails.details;
    var savedSearchBottom = '';
    var count = 0;
    if(val!=null)
    $.each(val, function( key1, val1 ) {
        if(count==0 && source=="savingListBottom")
            savedSearchBottom = savedSearchBottom+'<div class="srppad11 srpbdr2 srchsum savedNow"> '+val1.dataString+' </div>';
        else
            savedSearchBottom = savedSearchBottom+'<div class="srppad11 srpbdr2 srchsum"> '+val1.dataString+' </div>';
            count++;
    });
    $("#savedSearchBottom").html(savedSearchBottom);
    $("#savedSearchBottom").slideDown("normal",function(){
        $(".savedNow").fadeOut(400).fadeIn(1000).fadeOut(400).fadeIn(800).css("background","#ccc");
    });

    if(count==0)
        count="";
    $("#saveSearchCount").text(count);
}

$('body').on('click', '#saveSearchExtraBox', function(){
    
    reachSaveSearch();
});

function reachSaveSearch(){
    $("#saveThisSearch").show();
    resetSaveButton();
    var top=$("#saveThisSearch").offset().top;
    var topScroll = top-200;
    
    $("html, body").animate({scrollTop:topScroll}, '500',function(){
        $("#saveThisSearch .cursp").css("background","rebeccapurple").animate({"padding":"10px","margin":"-5px"}, '50',function(){
            $("#saveThisSearch .cursp").animate({"padding":"0px","margin":"0px"}, '50',function(){
                $("#saveThisSearch .cursp").css("background","#34495E").focus();
                
            });
        });
    });
}

function hidePersonalizedOptions(){
     $("#saveThisSearch,#searchSummaryData").hide();
     $(".js-email").hide()
}
function showPersonalizedOptions(){
     $("#saveThisSearch,#searchSummaryData").show();
     $(".js-email").show()
}


function hidesaveSearchSuccess(){
     $("#saveSearchSuccess").slideUp("normal",function(){ $("#manageSearch").addClass("disp-none");$("#saveThisSearch").slideDown()});
}
function showsaveSearchSuccess(){
    $("#saveThisSearch").slideUp("normal",function(){$("#saveSearchSuccess").slideDown()});
}
