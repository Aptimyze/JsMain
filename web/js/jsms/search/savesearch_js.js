 $(".savedSearch").bind("click",function()
{
                var searchId = $(this).attr("id");
                window.location.href="/search/perform?mySaveSearchId="+searchId;
 });
 $("#savedSearchIcon").bind("click",function()
{
	$("#searchMainForm").scrollTo("#savedSearches");
	
 });

  
function ToggleMore(keyName)
{
	event.stopPropagation();
	$("#"+keyName+"_more").addClass("dn");
	$("#"+keyName+"_less").removeClass("dn");
	return false;	
}
function readMore(string,keyName)
{
	string=string.trim();
	var maxLength = 100;
	var readMoreStr="";
	if(string.length>maxLength){
	readMoreStr= [string.slice(0, maxLength).trim(), "<span id=\""+keyName+"_less\" class=\"dn\" >", string.slice(maxLength)].join('');
	readMoreStr=readMoreStr+"</span><span id=\""+keyName+"_more\" onClick=\"ToggleMore(\'"+keyName+"\')\">...<span class=\"color2\"> more</span></span>";
	return readMoreStr;
	}
	else
		return string;
}

if(typeof manage !== 'undefined' && manage==1)
{
	$(".savedSearchList").each(function(){
		var myHtml = $(this).attr("data");
		var myId = $(this).attr("id");
		$(this).html(readMore(myHtml,myId));
        });

}

 
 var searchIdList = "0";
 var crossSaveSearch=0;	
$(".Openlayer").click(function() {
    saveSearchListing();
});
$(".OpenManagelayer").click(function() {
	crossSaveSearch=0;
	saveSearchListing();
});

$("#performSearch").click(function() {
      var time = new Date().getTime();
      window.location.href='/search/topSearchBand?isMobile=Y&stime='+time;
});

function saveSearchForm(){
    var topBar = "Save Your Search";
    var bottonAction = "";
    var buttonContent = {
        title:"Save Search",
        action:"saveSearch()"
    }
    var bodyCompleteContent = '<div class="pad18 givename"><input type="text" id="searchNameToSave" maxlength="40" value="" class="white f14" style="width:100%;" placeholder="Provide a name to your search"  autofocus></div>';
    
    openOverlayLayer(topBar,bodyCompleteContent,buttonContent);
    $("#searchNameToSave").focus();
}

$( "body" ).delegate( ".deleteButton", "click", deleteAndButtonOnClick);
$( "body" ).delegate( ".undoButton", "click", deleteAndButtonOnClick);


function deleteAndButtonOnClick(){
    manage = typeof manage !== 'undefined' ? manage : 0;  
    var textButton="OK";
    if($(".undoButton:visible").length>0){
        $(".sav-head .savsrc-pos4").hide();
         if(manage==0)
		textButton = "Delete and Continue to Save Search";
        $("#saveSearchSubmit").text(textButton).addClass("white").removeClass("color11").addClass("bg7").removeClass("bg6").prop("disabled",false);
    }
    else{
	if(manage==0)
		textButton = "Delete saved searches to Continue";
        $(".sav-head .savsrc-pos4").show();
        $("#saveSearchSubmit").text(textButton).removeClass("white").addClass("color11").addClass("bg6").removeClass("bg7").prop("disabled",true);
    }
}
 

function saveSearchListing(){
    manage = typeof manage !== 'undefined' ? manage : 0;
    $.ajax({
        type: "GET",
        url: "/api/v1/search/saveSearchCall",
        data: 'perform=listing',
        dataType: "json",
        success: function (result, status, xResponse) {
            var message = result;
            if((message.saveDetails.details==null || message.saveDetails.details.length<5) && manage!=1){
                    saveSearchForm();
                    return;
            }
             var bodyCompleteContent="";
             var buttonText="Select to delete saved searches";
            if(manage==1)
            {
		var topBar = "Manage Saved Searches";
		var buttonContent = {
			title:"OK",
			action:"saveSearchContinue()"
		}
		buttonText = "OK";
	    }
	    else
	    {
		var topBar = "Save Search Limit Reached";
		var buttonContent = {
			title:"Save to Continue",
			action:"saveSearchContinue()"
		}
	     

                bodyCompleteContent = '<div class="txtc white fontlig f16 pad9 sav-cont"><div>You can only save up to '+message.saveDetails.details.length+' searches</div><div class="opa50 pt10">Remove one of the searches below to save</div></div>';

	    }

                 bodyCompleteContent +='<div id="SavsrcList" style="overflow: visible; min-height: 469px;">';

                $.each( message.saveDetails.details, function( key, value ) {
			if(value.dataString.length>100)
                            var viewText = value.dataString.substring(0, 100)+"...";
                        else
                            var viewText = value.dataString;
                    bodyCompleteContent +='<div class="pad18" id="saved'+key+'">\
                        <div class="disptbl">\
                            <div class="dispcell txtl wid85p white opa50 savsrc-vtop">\
                                <div>'+value.SEARCH_NAME+'</div>\
                                <div class="savsrc-list pt15">\
                                <ul class="wid94p lh25">'
                                +viewText+
                                '</ul></div>\
                            </div>\
                            <div class="dispcell wid10p vertmid deleteButton" onclick=deleteSavedSearch("'+key+'","'+value.ID+'")><i class="mainsp savsrc-icon4"></i></div>\
                            <div class="dispcell wid10p vertmid dispnone white f16 undoButton" onclick=undoDeleteSavedSearch("'+key+'","'+value.ID+'")>Undo</div>\
                        </div> \
                    </div>';
                });

                bodyCompleteContent +='<br><br><br><br></div></div>';
                
                closeOpenLayer();
                openOverlayLayer(topBar,bodyCompleteContent,buttonContent);
                $("#saveSearchSubmit").text(buttonText).removeClass("white").addClass("color11").addClass("bg6").removeClass("bg7").prop("disabled",true);
        }
        });
}

function saveSearch(){
    var saveSearchName = $("#searchNameToSave").val();
    if(typeof firstResponse=="undefined")
        var saveSearchID = window.location.href.split("searchId=")[1].split("&")[0];
    else
        var saveSearchID = firstResponse.searchid;
    if(saveSearchName && saveSearchID){
        $.ajax({
            type: "POST",
            url: "/api/v1/search/saveSearchCall?perform=savesearch",
            data: { saveSearchName: saveSearchName, searchId : saveSearchID},
            dataType: "json",
            success: function (result, status, xResponse) {
                var message = result.saveDetails;
                if(message.errorMsg){
                    if(message.errorMsg.search("limit")!=-1)
                        saveSearchListing();
                    else
                        ShowTopDownError([""+message.errorMsg+""],3000);
                }
                else if(message.successMsg){
                    closeOpenLayer();
                    ShowTopDownError([""+message.successMsg+""],3000);
                }

            }
        });
    }
    else{ 
        ShowTopDownError(["Search name is required."],3000);
    }
}
function deleteSavedSearch(key,searchId){
    $("#saved"+key+" .savsrc-vtop").addClass("undo");
    $("#saved"+key+" .deleteButton").addClass("dispnone");
    $("#saved"+key+" .undoButton").removeClass("dispnone").addClass("dispcell");
    $("#saved"+key+" .undo :nth-child(1)").addClass("namesav");
    searchIdList+=","+searchId;
}

function undoDeleteSavedSearch(key,searchId){
    $("#saved"+key+" .savsrc-vtop").removeClass("undo");
    $("#saved"+key+" .deleteButton").removeClass("dispnone").addClass("dispcell");
    $("#saved"+key+" .undoButton").addClass("dispnone");
    $("#saved"+key+" .undo :nth-child(1)").removeClass("namesav");
    searchIdList = searchIdList.replace(","+searchId,"");
}

function saveSearchContinue(){
    if(searchIdList){
        //console.log("searchIdList");
        $.ajax({
            type: "POST",
            url: "/api/v1/search/saveSearchCall?perform=delete",
            data: { searchId : searchIdList},
            dataType: "json",
            success: function (result, status, xResponse) {
                var message = result.saveDetails;
                if(typeof message=="undefined")
                    ShowTopDownError(["Something Went wrong."],3000);
                if(typeof message.successMsg!="undefined")
                {
                    closeOpenLayer();
		    if(manage!=1)
		    {
                       setTimeout(function(){  saveSearchForm(); }, 10);
		    }
		    else if(manage==1){
			crossSaveSearch=2;
		    }
		    searchIdList="0";

                }
                else if(typeof message.errorMsg!=undefined)
                    ShowTopDownError([""+message.errorMsg+""],3000);
                else
                    ShowTopDownError(["Something Went wrong."],3000);
            }
        });
    }
}


