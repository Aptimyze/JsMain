var myUrl = window.location.href;
/**
* This function will update the url.
* @param title : suggested that update url have a title
* @param page : page value
*/
$.urlUpdateHistory = function(title,addMoreParams){
        var randomnumber=$.now();
        var value = myUrl.substring(myUrl.lastIndexOf('?'));
        var param = '/search'+addMoreParams;
        var stateObj = {};
        history.replaceState(stateObj,title,param);
}

/**
* This function will update the CC url.
* @param title : suggested that update url have a title
* @param page : addMoreParams
*/
$.urlCCUpdateHistory = function(title,addMoreParams){
        var randomnumber=$.now();
        var value = myUrl.substring(myUrl.lastIndexOf('?'));
            var param = '/inbox'+addMoreParams;
        var stateObj = {};
        history.replaceState(stateObj,title,param);
}


/**
* update browser url : will be used for back button functionality.
* @tupleNo ex id1
*/
function updateHistory(tabName,page,searchId)
{ 
	if (window.location.href.indexOf('/search/')!=-1){
            	var addMoreParams="";
            	var cachingParam="";
                if(typeof tabName  != "undefined" && tabName!="")
                    addMoreParams += '/'+tabName;
                else if(window.location.href.indexOf("/perform?")!=-1){
                        addMoreParams += '/'+window.location.href.split("/perform?")[1].split("=")[0];
                }
                else if(window.location.href.split("/").length>3){
											if(window.location.href.indexOf("?useHeaderCaching")!=-1 || window.location.href.indexOf("visitors?matchedOrAll")!=-1)
											{
												var splitted = window.location.href.split("/")[4];
												addMoreParams += '/'+splitted.split("?")[0];
												cachingParam = window.location.href.split("?")[1];
											}
                                                                                        else if(window.location.href.indexOf("matchedOrAll")!=-1){
                                                                                            //var splitted = window.location.href.split("/")[6];
												addMoreParams = '/visitors';
//												cachingParam = window.location.href.split("?")[1];
                                                                                                
                                                                                        }
                                                                                            
											else
												addMoreParams += '/'+window.location.href.split("/")[4];
                }
                
                addMoreParams += '/'+lastSearchId;
                
                if(typeof page  == "undefined" || page  == ""){
                    if(window.location.href.split('/').length>=7)
                        addMoreParams += '/'+window.location.href.split("/")[6];
                    else
                        addMoreParams += '/1';
                }
                else
                    addMoreParams += '/'+page;
                if(cachingParam!="")
									addMoreParams += '?'+cachingParam;
		//if(window.location.href.indexOf('mySaveSearchId')==-1)
			$.urlUpdateHistory('Search Results Page '+tabName,addMoreParams);
	}
    if (window.location.href.indexOf('/inbox/')!=-1){
                var addMoreParams="";                
                addMoreParams += '/'+lastCCSearchId;
                
                if(typeof page  == "undefined" || page  == ""){
                    if(window.location.href.split('/').length>=6)
                    { 
                       // console.log("1: ");
                        addMoreParams += '/'+window.location.href.split("/")[5];
                        //console.log(addMoreParams);
                    }
                    else
                    { //console.log("2: ");
                        addMoreParams += '/1';
                        //console.log(addMoreParams);
                    }
                }
                else
                {  //console.log("3: ");
                    addMoreParams += '/'+page;
                }
        //console.log(addMoreParams);
        $.urlCCUpdateHistory('CC Search Results Page '+tabName,addMoreParams);   //why required------------LATER

    }
}
