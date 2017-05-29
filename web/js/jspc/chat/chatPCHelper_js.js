/*This file includes functions used for intermediate data transfer for JSPC chat from 
 * chat client(chatStrophieClient_js.js) to chat plugin(chat_js.js)
 */
var listingInputData = [],
    listCreationDone = false,
    objJsChat, pass, username,
    pluginId = '#chatOpenPanel',
    device = 'pc',
    loggingEnabledPC = false,
    clearTimedOut,
    listingPhotoRequestCompleted = ",",
    localStorageExists = isStorageExist(),
    rosterMsgTime= '0';

/*clearNonRosterPollingInterval
function to stop polling for non roster webservice api 
* @inputs:type(optional)
*/
function clearNonRosterPollingInterval(type){
    //console.log("in clearNonRosterPollingInterval");
    if(type == undefined){
        if(strophieWrapper.nonRosterClearInterval && (Object.keys(strophieWrapper.nonRosterClearInterval)).length > 0){
            $.each(strophieWrapper.nonRosterClearInterval,function(key,type){
                //console.log("clear",strophieWrapper.nonRosterClearInterval[key]);
                clearTimeout(strophieWrapper.nonRosterClearInterval[key]);
            });
        }
    }
    else{
        if(strophieWrapper.nonRosterClearInterval && strophieWrapper.nonRosterClearInterval[type] != undefined){
            clearTimeout(strophieWrapper.nonRosterClearInterval[type]);
        }
    }
}

/*reActivateNonRosterPolling
function to reactivate poll for non roster list 
* @inputs:source,updateChatImmediate(optional),nonRosterGroups(optional)
*/
function reActivateNonRosterPolling(source,updateChatImmediate,nonRosterGroups){
    //kills interval polling for non roster list
    //clearNonRosterPollingInterval();
    //console.log("dppLiveForAll",dppLiveForAll);
    //console.log("betaDppExpression",updateChatImmediate,nonRosterGroups);
    nonRosterGroups = ((nonRosterGroups == undefined || nonRosterGroups.length == 0) ? chatConfig.Params.nonRosterPollingGroups : nonRosterGroups);
    //console.log("reActivateNonRosterPolling",nonRosterGroups);
    if ((updateChatImmediate == true || strophieWrapper.getCurrentConnStatus() == true) && loggedInJspcUser != undefined) {
        var profileEligible = true;
        
        if(dppLiveForAll != "1" && betaDppExpression != undefined && betaDppExpression != ""){
            var splitArr = JSON.parse("[" + betaDppExpression + "]"),specialProfiles="";
          
            if(specialDppProfiles != undefined){
                specialProfiles = specialDppProfiles;
            }
       
            if(splitArr != undefined && (specialProfiles.indexOf(loggedInJspcUser) == -1) && (loggedInJspcUser % splitArr[0] >= splitArr[1])){
                profileEligible = false;
            }
        }
        //console.log("profileEligible",profileEligible);
        if(profileEligible == true){
            //console.log("in reActivateNonRosterPolling",source);
            $.each(nonRosterGroups,function(key,groupId){
                    //pollForNonRosterListing(groupId);
                    clearNonRosterPollingInterval(groupId);
                    var updateChatListImmediate = (updateChatImmediate != undefined) ? updateChatImmediate : false;
                    strophieWrapper.nonRosterClearInterval[groupId] = setTimeout(function(){
                                                                        pollForNonRosterListing(groupId,updateChatListImmediate);
                                                                    },100);
                    
            });
        }
    }
}

/*checkForValidNonRosterRequest
function to check whether request to non roster webservice is valid or not 
* @inputs:groupId
*/
function checkForValidNonRosterRequest(groupId){
    //return true;
    var selfSub = getMembershipStatus();
    //console.log("ankita",selfSub);
    //console.log("ankita1",chatConfig.Params[device].nonRosterListingRefreshCap[groupId][selfSub]);
    var lastUpdated = JSON.parse(localStorage.getItem("nonRosterCLUpdated")),d = new Date(),valid = true;
    var data = strophieWrapper.getRosterStorage("non-roster");
    if(lastUpdated && lastUpdated[groupId]){
        var currentTime = d.getTime(),timeDiff = (currentTime - lastUpdated[groupId]); //Time diff in milliseconds
        if(timeDiff <= chatConfig.Params[device].nonRosterListingRefreshCap[groupId][selfSub]){
            valid = false;
        }
    }
    if(data && valid == false){
        //data = {"3290997":{"rosterDetails":{"jid":"3290997@localhost","chat_status":"online","nick":"aloha_2008|4656179bccedf6fffb977aa43f44fdc4i3290997","fullname":"aloha_2008","groups":["dpp"],"subscription":"both","profile_checksum":"4656179bccedf6fffb977aa43f44fdc4i3290997","listing_tuple_photo":"/images/picture/120x120_f.png?noPhoto","last_online_time":"2016-11-03T09:48:12.355Z","ask":null,"addIndex":6,"nodeType":"non-roster"}},"3997986":{"rosterDetails":{"jid":"3997986@localhost","chat_status":"online","nick":"UYZ2063|5ff5ff3d2f3217d7158d7b2e40572eafi3997986","fullname":"UYZ2063","groups":["dpp"],"subscription":"both","profile_checksum":"5ff5ff3d2f3217d7158d7b2e40572eafi3997986","listing_tuple_photo":"/images/picture/120x120_f.png?noPhoto","last_online_time":"2016-11-03T09:48:12.355Z","ask":null,"addIndex":8,"nodeType":"non-roster"}},"5054004":{"rosterDetails":{"jid":"5054004@localhost","chat_status":"online","nick":"TXT8609|1d2bc71e6adf4550e4c8b638e4e30897i5054004","fullname":"TXT8609","groups":["dpp"],"subscription":"both","profile_checksum":"1d2bc71e6adf4550e4c8b638e4e30897i5054004","listing_tuple_photo":"/images/picture/120x120_f.png?noPhoto","last_online_time":"2016-11-03T09:48:12.355Z","ask":null,"addIndex":3,"nodeType":"non-roster"}},"5385186":{"rosterDetails":{"jid":"5385186@localhost","chat_status":"online","nick":"TTA9972|a7dd65ce4a7f2d222f63a95db1685d44i5385186","fullname":"TTA9972","groups":["dpp"],"subscription":"both","profile_checksum":"a7dd65ce4a7f2d222f63a95db1685d44i5385186","listing_tuple_photo":"/images/picture/120x120_f.png?noPhoto","last_online_time":"2016-11-03T09:48:12.355Z","ask":null,"addIndex":5,"nodeType":"non-roster"}},"5545059":{"rosterDetails":{"jid":"5545059@localhost","chat_status":"online","nick":"TSU9916|92e39acb3c9c0d9e95053cd2d32db79ei5545059","fullname":"TSU9916","groups":["dpp"],"subscription":"both","profile_checksum":"92e39acb3c9c0d9e95053cd2d32db79ei5545059","listing_tuple_photo":"/images/picture/120x120_f.png?noPhoto","last_online_time":"2016-11-03T09:48:12.355Z","ask":null,"addIndex":11,"nodeType":"non-roster"}},"5669960":{"rosterDetails":{"jid":"5669960@localhost","chat_status":"online","nick":"TRR4847|01e19fbeb41aa12facb278cda6a8393ci5669960","fullname":"TRR4847","groups":["dpp"],"subscription":"both","profile_checksum":"01e19fbeb41aa12facb278cda6a8393ci5669960","listing_tuple_photo":"/images/picture/120x120_f.png?noPhoto","last_online_time":"2016-11-03T09:48:12.355Z","ask":null,"addIndex":4,"nodeType":"non-roster"}},"5714570":{"rosterDetails":{"jid":"5714570@localhost","chat_status":"online","nick":"SAX9468|ff51de7e53b2096676938ed771c41ac7i5714570","fullname":"SAX9468","groups":["dpp"],"subscription":"both","profile_checksum":"ff51de7e53b2096676938ed771c41ac7i5714570","listing_tuple_photo":"/images/picture/120x120_f.png?noPhoto","last_online_time":"2016-11-03T09:48:12.355Z","ask":null,"addIndex":15,"nodeType":"non-roster"}},"6439626":{"rosterDetails":{"jid":"6439626@localhost","chat_status":"online","nick":"STU4834|c37191df737061a131ba30f91870be0di6439626","fullname":"STU4834","groups":["dpp"],"subscription":"both","profile_checksum":"c37191df737061a131ba30f91870be0di6439626","listing_tuple_photo":"/images/picture/120x120_f.png?noPhoto","last_online_time":"2016-11-03T09:48:12.356Z","ask":null,"addIndex":21,"nodeType":"non-roster"}},"6547446":{"rosterDetails":{"jid":"6547446@localhost","chat_status":"online","nick":"SST3318|34299a817ece0d6aec2c7932f31898e8i6547446","fullname":"SST3318","groups":["dpp"],"subscription":"both","profile_checksum":"34299a817ece0d6aec2c7932f31898e8i6547446","listing_tuple_photo":"/images/picture/120x120_f.png?noPhoto","last_online_time":"2016-11-03T09:48:12.356Z","ask":null,"addIndex":22,"nodeType":"non-roster"}},"6889709":{"rosterDetails":{"jid":"6889709@localhost","chat_status":"online","nick":"RYZ5781|efec1f895fc3aee614750406a3de481di6889709","fullname":"RYZ5781","groups":["dpp"],"subscription":"both","profile_checksum":"efec1f895fc3aee614750406a3de481di6889709","listing_tuple_photo":"/images/picture/120x120_f.png?noPhoto","last_online_time":"2016-11-03T09:48:12.355Z","ask":null,"addIndex":17,"nodeType":"non-roster"}},"6933026":{"rosterDetails":{"jid":"6933026@localhost","chat_status":"online","nick":"RYV9126|1cc3378df531ec91646480ff0bcc6b75i6933026","fullname":"RYV9126","groups":["dpp"],"subscription":"both","profile_checksum":"1cc3378df531ec91646480ff0bcc6b75i6933026","listing_tuple_photo":"/images/picture/120x120_f.png?noPhoto","last_online_time":"2016-11-03T09:48:12.355Z","ask":null,"addIndex":9,"nodeType":"non-roster"}}};
        processNonRosterData(data,groupId,"localstorage");
    }
    else{
        valid = true;
    }
    //console.log("checkForValidNonRosterRequest",valid,groupId,chatConfig.Params[device].nonRosterListingRefreshCap);
    return valid;
}

/*pollForNonRosterListing
function to poll for non roster webservice api 
* @inputs:type
*/
function pollForNonRosterListing(type,updateChatListImmediate){
    //console.log("pollForNonRosterListing",type,updateChatListImmediate);
    if(type == undefined || type == ""){
        type = "dpp";
    }
    var selfAuth = readCookie("AUTHCHECKSUM");
    if(selfAuth != undefined && selfAuth != "" && selfAuth != null){
        //console.log("selfAuth",selfAuth);
        var validRe,headerData = {'JB-Profile-Identifier':selfAuth};
        if(updateChatListImmediate != undefined && updateChatListImmediate == true){
            if(showChat == "1"){
                validRe = true;
            }
            else{
                validRe = false;
                var nonRosterCLUpdated = JSON.parse(localStorage.getItem("nonRosterCLUpdated"));
                if(nonRosterCLUpdated != undefined && nonRosterCLUpdated[type] != undefined){
                    nonRosterCLUpdated[type] = 0;
                    localStorage.setItem("nonRosterCLUpdated",JSON.stringify(nonRosterCLUpdated));
                }
                //localStorage.removeItem("nonRosterCLUpdated");
                //localStorage.removeItem("nonRosterChatListing"+loggedInJspcUser);
            }
            //headerData['Cache-Control'] = 'no-cache,no-store';
        }
        else{
            validRe = checkForValidNonRosterRequest(type);
            //headerData['Cache-Control'] = 'max-age='+chatConfig.Params[device].headerCachingAge+',public';
        }
        //console.log("validRe",type,validRe);
        if(validRe == true){
            var getInputData = "";
            if (typeof chatConfig.Params.nonRosterListingApiConfig[type]["extraGETParams"] != "undefined") {
                $.each(chatConfig.Params.nonRosterListingApiConfig[type]["extraGETParams"], function (k, v) {
                    if(getInputData == ""){
                        getInputData = "?"+k+"="+v;
                    }
                    else{
                        getInputData = getInputData+"&"+k+"="+v;
                    }
                });
            }
            //getInputData = getInputData+"&timestamp="+(new Date()).getTime();
            $.myObj.ajax({
                url: (listingWebServiceUrl[type]+getInputData),
                dataType: 'json',
                //data: postData,
                type: 'GET',
                cache:false,
                async: true,
                timeout: chatConfig.Params.nonRosterListingApiConfig[type]["timeoutTime"],
                headers:headerData,
                beforeSend: function (xhr) {},
                success: function (response) {
                    /*response = {
                    "data":{
        
                    "items":[
                        {
                        "profileid": "2865000",
                        "username": "WYZ6824",
                        "profileChecksum": "74cd670dc3ff8c388b823cf5c166ca84i2865000"
                        },
                        {
                        "profileid": "8925000",
                        "username": "ZZYV2509",
                        "profileChecksum": "d948e45111aee5677868d6b17bec9ca7i8925000"
                        },
                        {
                        "profileid": "7415000",
                        "username": "RTW1253",
                        "profileChecksum": "5f42a56a1d5df485dc3dc26bafca6d52i7415000"
                        },
                        {
                        "profileid": "8874000",
                        "username": "ZZYA1475",
                        "profileChecksum": "4f29b43a3c50e05531fd01132f7f1d66i8874000"
                        },
                        {
                        "profileid": "1764127",
                        "username": "YAS8573",
                        "profileChecksum": "1764127lr"
                        },
                        {
                        "profileid": "3599124",
                        "username": "nokumarriage",
                        "profileChecksum": "3599124lr"
                        }
                    ],
                    "pollTime":20000
                },
                "header": {
                    "status": 200,
                    "errorMsg": ""
                },
                "debugInfo": null
                };*/
            
                    if(response["header"]["status"] == 200){
                        //console.log("fetchNonRosterListing success",response);
                        if(response["data"]["pollTime"] != undefined && response["data"]["pollTime"] > 0){
                            //chatConfig.Params[device].nonRosterListingRefreshCap[type] = response["data"]["pollTime"];
                            //console.log("seting pollTime",chatConfig.Params[device].nonRosterListingRefreshCap);
                        }
                        var nonRosterCLUpdated = JSON.parse(localStorage.getItem("nonRosterCLUpdated"));
                        if(nonRosterCLUpdated == undefined){
                            nonRosterCLUpdated = {};
                        }
                        nonRosterCLUpdated[type] = (new Date()).getTime();
                        localStorage.setItem("nonRosterCLUpdated",JSON.stringify(nonRosterCLUpdated));
                        //add in listing, after non roster list has been fetched
                        processNonRosterData(response["data"]["items"],type,"api");
                    }
                },
                error: function (xhr) {
                    //console.log("fetchNonRosterListing error",xhr);
                    //return "error";
                }
            });
        }
    }
}

/*processNonRosterData
function to process the non roster data 
* @inputs:response,type
*/
function processNonRosterData(response,type,source){
    var operation = "create_list",reCreateList = true;
    //console.log("in processNonRosterData",source); 
    var newNonRoster = {},oldNonRoster = {},offlineNonRoster = {};
    if((Object.keys(strophieWrapper.NonRoster)).length>0){
        $.each(strophieWrapper.NonRoster,function(profileid,nodeObj){
            if (nodeObj[strophieWrapper.rosterDetailsKey]["groups"].indexOf(type) != -1) {
                oldNonRoster[profileid] = nodeObj;
            }
        });
    }
    /*if((Object.keys(oldNonRoster)).length == 0){
        oldNonRoster = strophieWrapper.getRosterStorage("non-roster");
    }*/
    if((Object.keys(response)).length > 0){
        if(source != "localstorage"){
            $.each(response,function(key,nodeObj){
                nodeObj["groupid"] = type;
                nodeObj["addIndex"] = key;
                var listObj = strophieWrapper.formatNonRosterObj(nodeObj);
                if (strophieWrapper.checkForGroups(listObj[strophieWrapper.rosterDetailsKey]["groups"]) == true && (strophieWrapper.Roster[nodeObj["profileid"]] == undefined || strophieWrapper.Roster[nodeObj["profileid"]][strophieWrapper.rosterDetailsKey]["groups"] == undefined || strophieWrapper.Roster[nodeObj["profileid"]][strophieWrapper.rosterDetailsKey]["groups"][0] == undefined)){
                    newNonRoster[nodeObj["profileid"]] = listObj;
                }
            });
        }
        else{
            newNonRoster = response;
        }
    }
    else{
        newNonRoster = {};
    }
    //console.log("oldNonRoster",oldNonRoster);
    //console.log("newNonRoster",newNonRoster);
    isResponseSame = checkForObjectsEquality(oldNonRoster,newNonRoster);
    if(isResponseSame == false){
        if((Object.keys(oldNonRoster)).length > 0){
            if(/*chatConfig.Params.nonRosterPollingGroups.length == 1 && */chatConfig.Params.nonRosterPollingGroups.indexOf(type) != -1){
                //only dpp is non roster group case
                offlineNonRoster = oldNonRoster;
            }
            //mark old list as offline
            strophieWrapper.onNonRosterPresenceUpdate("offline",offlineNonRoster);
        }
        //add new list
        strophieWrapper.onNonRosterListFetched(newNonRoster,type,operation);
    }
    else if((Object.keys(newNonRoster)).length == 0){
        //console.log("here",newNonRoster,oldNonRoster);
        //strophieWrapper.setRosterStorage({},"non-roster");
        
        var data = strophieWrapper.getRosterStorage("non-roster");
        if(data == undefined || (Object.keys(data)).length == 0){
            strophieWrapper.setRosterStorage({},"non-roster");
        }
    }
}

/*checkForObjectsEquality
function to check whether two objects are equal or not 
* @inputs:obj1,obj2
*/
function checkForObjectsEquality(obj1,obj2){
    if((Object.keys(obj1)).length == 0 && (Object.keys(obj2)).length == 0){
        //console.log("checkForObjectsEquality",true);
        return true;
    }
    if((Object.keys(obj1)).length == 0 || (Object.keys(obj2)).length == 0){
        //console.log("checkForObjectsEquality",false);
        return false;
    }
    else{
        //console.log("checkForObjectsEquality",(JSON.stringify(obj1) === JSON.stringify(obj2)));
        return (JSON.stringify(obj1) === JSON.stringify(obj2));
    }
}

/*manageListingPhotoReqFlag
function to set/reset listing photo request 
* @inputs:key,profileid
*/
function manageListingPhotoReqFlag(key,profileid){
    //console.log("in manageListingPhotoReqFlag",listingPhotoRequestCompleted);
    if(key == "set"){
        if(listingPhotoRequestCompleted == undefined){
            listingPhotoRequestCompleted = ",";
        }
        if(listingPhotoRequestCompleted.indexOf(","+profileid+",") == -1){
            listingPhotoRequestCompleted += profileid+",";
            //console.log("manageListingPhotoReqFlag set",profileid);
            //console.log(listingPhotoRequestCompleted);
        }
       //console.log("in manageListingPhotoReqFlag",listingPhotoRequestCompleted);
       
    }
    else if(key == "reset"){
        //console.log("manageListingPhotoReqFlag reset");
        listingPhotoRequestCompleted = ",";   
    }
    else if(key == "remove"){
        if($.isArray(profileid) == true){
            $.each(profileid,function(index,value){
                var replaceStr = value+",";
                listingPhotoRequestCompleted = listingPhotoRequestCompleted.replace(replaceStr,"");
                //console.log("manageListingPhotoReqFlag remove array",value);
            });
        }
        else if(profileid != undefined){
            var replaceStr = profileid+",";
            listingPhotoRequestCompleted = listingPhotoRequestCompleted.replace(replaceStr,"");
            //console.log("manageListingPhotoReqFlag remove profile",profileid);
        }
    }
}

/*isListPhotoReqValid
function to check validity of listing photo request based on request flag
* @inputs:profileid
*/
function isListPhotoReqValid(profileid){
    var validReq = true;
    if(listingPhotoRequestCompleted != undefined && listingPhotoRequestCompleted.indexOf(","+profileid+",") > -1){
        validReq = false;
    }
    //console.log("isListPhotoReqValid",profileid,validReq);
    //console.log(listingPhotoRequestCompleted);
    return validReq;
}
/*handle chat disconnection case
 */
function handleChatDisconnection() {
    //show error msg--design
    //console.log("disconnected from chat,reconnecting..");
    //reconnect to chat
    if (username && pass) {
        strophieWrapper.reconnect(chatConfig.Params[device].bosh_service_url, username, pass);
    }
}

/*manageHistoryLoader
*show/hide loader for msg history
*@params:user_jid,type
*/
function manageHistoryLoader(user_jid,type){
    if(typeof user_jid!= "undefined"){
        var user_id = user_jid.split("@")[0];
        if(type == "show" && $('chat-box[user-id="' + user_id + '"] .spinner').is(":visible") == false){
            $('chat-box[user-id="' + user_id + '"] .spinner2').removeClass("disp-none");
        } else if(type == "hide") {
            $('chat-box[user-id="' + user_id + '"] .spinner2').addClass("disp-none");
        }
    }
}
function chatLoggerPC(msgOrObj) {
    /*
    if (loggingEnabledPC) {
        if (typeof (window.console) != 'undefined') {
            try {
                throw new Error('Initiate Stack Trace');
            } catch (err) {
                var logStack = err.stack;
            }
            var fullTrace = logStack.split('\n');
            for (var i = 0; i < fullTrace.length; ++i) {
                fullTrace[i] = fullTrace[i].replace(/\s+/g, ' ');
            }
            var caller = fullTrace[1],
                callerParts = caller.split('@'),
                line = '';
            //CHROME & SAFARI
            if (callerParts.length == 1) {
                callerParts = fullTrace[2].split('('), caller = false;
                //we have an object caller
                if (callerParts.length > 1) {
                    caller = callerParts[0].replace('at Object.', '');
                    line = callerParts[1].split(':');
                    line = line[2];
                }
                //called from outside of an object
                else {
                    callerParts[0] = callerParts[0].replace('at ', '');
                    callerParts = callerParts[0].split(':');
                    caller = callerParts[0] + callerParts[1];
                    line = callerParts[2];
                }
            }
            //FIREFOX
            else {
                var callerParts2 = callerParts[1].split(':');
                line = callerParts2.pop();
                callerParts[1] = callerParts2.join(':');
                caller = (callerParts[0] == '') ? callerParts[1] : callerParts[0];
            }
            //console.log(' ');
            console.warn('Console log: ' + caller + ' ( line ' + line + ' )');
            //console.log(msgOrObj);
            console.log({
                'Full trace:': fullTrace
            });
            //console.log(' ');
        } else {
            //shout('This browser does not support //console.log!')
        }
    }
    */
}

/*open chatbox from search listing or view profile page
*@inputs:profilechecksum,detailsArr
*/
function openNewJSChat(profilechecksum,detailsArr){
    if(typeof profilechecksum!= "undefined" && typeof detailsArr!= "undefined"){
        var groupid = chatConfig.Params.groupIDMapping[detailsArr[3]];
        if(typeof groupid == "undefined"){
            groupid = "mysearch";
        }
        var jid=detailsArr[1]+"@"+openfireServerName,
            data= {"rosterDetails":
                    {
                        "jid":jid ,
                        "chat_status": "online",
                        "nick": detailsArr[0]+"|"+profilechecksum,
                        "fullname": detailsArr[0],
                        "groups": [groupid],
                        "subscription": "to",
                        "profile_checksum": profilechecksum,
                        "listing_tuple_photo": detailsArr[2],
                        "last_online_time": null,
                        "ask": null
                    }
                },
            nodeArr=[];
        nodeArr[detailsArr[1]] = data;

        var output = objJsChat.checkForNodePresence(detailsArr[1]);
        var alreadyExists = output["exists"];
        if(alreadyExists == true){
           groupid = output["groupID"];  
        }
        //console.log("added......",alreadyExists);
        if(alreadyExists == false){
            //create hidden element in chat listing
            objJsChat.createHiddenListNode(nodeArr);
          
            //write in localstorage
        	localStorage.setItem('jsNoRosterChat_'+detailsArr[1],JSON.stringify(data));
        }
        //open chat box
        objJsChat._chatPanelsBox(detailsArr[1],"online",jid,profilechecksum, groupid);
    } 
}

function retainHiddenListing(){
    var nodeArr = [];
    for (var i = 0; i < localStorage.length; i++){
        var key = localStorage.key(i);
        if(key && key.indexOf('jsNoRosterChat_')!='-1')
        {
            var nodeData = localStorage.getItem(key);
            nodeArr[key.split("_")[1]] = JSON.parse(nodeData);
        }
    }
    objJsChat.createHiddenListNode(nodeArr);
}

function removeLocalStorageForNonChatBoxProfiles(id)
{
	if(id)
	{
	    localStorage.removeItem('jsNoRosterChat_'+id);
	}
	else
	{
		for (var i = 0; i < localStorage.length; i++){
			var key = localStorage.key(i);
			if(key && key.indexOf('jsNoRosterChat_')!='-1')
			{
	        	localStorage.removeItem(key);
			}
		}
	}
}

/*getMessagesFromLocalStorage
 * Fetch messages from local storage
 */
function getMessagesFromLocalStorage(selfJID, other_id){
    var page = parseInt($("#moreHistory_"+other_id).attr("data-page"));
    
    $("#moreHistory_"+other_id).attr("data-page",page+1);
    var chunk = chatConfig.Params[device].moreMsgChunk;
    var oldMessages = JSON.parse(localStorage.getItem('chatMsg_'+selfJID+'_'+other_id));
    if(oldMessages){
        var pc = page*chunk;
        var messages = [];
        var oldMsgLength = oldMessages.length
        var limit = Math.min(pc+chunk,oldMsgLength);
        if(oldMsgLength>limit){
            $("#moreHistory_"+other_id).attr("data-localMsg","1");
        }
        else{
            $("#moreHistory_"+other_id).attr("data-localMsg","0");
        }
        for(var i=pc;i<limit;i++){
            messages.push(oldMessages[i]);
        }
    }
    else{
        $("#moreHistory_"+other_id).attr("data-localMsg","0");
    }
    return messages;
}

/*getChatHistory
 * fetch chat history on opening window again
 * @inputs: apiParams,key
 * @output: response
 */
function getChatHistory(apiParams,key) {
    var selfAuth = readCookie("AUTHCHECKSUM");
    if(selfAuth != undefined && selfAuth != "" && selfAuth != null){
        var getRequestUrl = listingWebServiceUrl["rosterRemoveMsg"],headerData={},setLocalStorage=false,fetchFromLocalStorage = false,oldHistory;
        var bare_from_jid = apiParams["extraParams"]["from"].split("/")[0],bare_to_jid = apiParams["extraParams"]["to"].split("/")[0];
        headerData["JB-Profile-Identifier"] = selfAuth;
        if (typeof apiParams["extraParams"] != "undefined") {
            $.each(apiParams["extraParams"], function (key, value) {
                if(getRequestUrl == ""){
                    getRequestUrl = getRequestUrl+"?"+key+"="+value;
                }
                else{
                    getRequestUrl = getRequestUrl+"&"+key+"="+value;
                }
            });
            if(typeof apiParams["extraParams"]["messageId"] == "undefined"){
                if(chatConfig.Params[device].storeMsgInLocalStorage == true){
                    oldHistory = localStorage.getItem("chatHistory_"+bare_from_jid+"_"+bare_to_jid);
                    if(typeof oldHistory!= "undefined"){
                        fetchFromLocalStorage = true;
                    }
                    setLocalStorage = true;
                }
            }
            else{
                fetchFromLocalStorage = false;
            }
        }
        /*var messageFromLocalStorage = getMessagesFromLocalStorage(apiParams["extraParams"]["from"].split("@")[0], apiParams["extraParams"]["to"].split("@")[0]);
        if(!(messageFromLocalStorage == undefined || messageFromLocalStorage == null || messageFromLocalStorage.length  == 0)){
            manageHistoryLoader(bare_to_jid,"hide");
            //call plugin function to append history in div
            objJsChat._appendChatHistory(apiParams["extraParams"]["from"], apiParams["extraParams"]["to"], messageFromLocalStorage,key);
        }
        else{*/
            $.myObj.ajax({
                url:getRequestUrl,
                type: 'GET',
                headers:headerData,
                cache: false,
                async: true,
                beforeSend: function (xhr) {},
                success: function (response) {
                    if (response["responseStatusCode"] == "0") {
                        if (typeof response["Message"] != "undefined") {
                            if(setLocalStorage == true){
                                localStorage.setItem("chatHistory_"+bare_from_jid+"_"+bare_to_jid,response["Message"]);
                            }
                            if(response["pagination"] == 0){
                                $("#moreHistory_"+bare_to_jid.split("@")[0]).val("0");
                            }
                            else{
                                $("#moreHistory_"+bare_to_jid.split("@")[0]).val("1");
                            }
                            manageHistoryLoader(bare_to_jid,"hide");
                            //call plugin function to append history in div
                            objJsChat._appendChatHistory(apiParams["extraParams"]["from"], apiParams["extraParams"]["to"], $.parseJSON(response["Message"]),key,response["canChat"]);
                            //objJsChat.storeMessagesInLocalHistory(apiParams["extraParams"]["from"].split('@')[0],apiParams["extraParams"]["to"].split('@')[0],$.parseJSON(response["Message"]),'history');
                        }
                        else{
                            $("#moreHistory_"+bare_to_jid.split("@")[0]).val("0");
                            manageHistoryLoader(bare_to_jid,"hide");
                        }
                    }
                    else{
                        manageHistoryLoader(bare_to_jid,"hide");
                        checkForSiteLoggedOutMode(response);
                    }
                },
                error: function (xhr) {
                    manageHistoryLoader(bare_to_jid,"hide");
                    //return "error";
                }
            });
       // }
    }
}

/*generateChatHistoryID
* @inputs: key("self"/"other")
*/
function generateChatHistoryID(key){
    var msg_id = strophieWrapper.getUniqueId();
    /*if(key == "sent"){
        msg_id = msg_id + "_self";
    }
    else{
        msg_id = msg_id + "_other";
    }*/
    return msg_id;
}

/*
 * set self name in chat header as well as localstorage
 * @inputs : nameStr
 * @returns : none
 */
function setChatSelfName(nameStr,target){
    var modifiedName;
    if(nameStr != undefined && nameStr != ""){
        if(target == "chatHeader"){
            var trimmedString = nameStr.length > chatConfig.Params[device].nameTrimmLength ? nameStr.substring(0, chatConfig.Params[device].nameTrimmLength - 3) + "..." : nameStr;
            var oldChatName = $(".chatName").html();
            if(showChat == "0" || (trimmedString && oldChatName != trimmedString)){
                $(".chatName").html(trimmedString);
                localStorage.setItem('name', JSON.stringify({
                    'selfName': nameStr,
                    'user': loggedInJspcUser
                }));
                modifiedName = trimmedString;
            }
        }
        else if(target == "storage"){
            localStorage.setItem('name', JSON.stringify({
                'selfName': nameStr,
                'user': loggedInJspcUser
            }));
            modifiedName = nameStr;
        }
        else if(target == "syncName"){
            var nameOnSite = selfUserChatName;
            if(((moduleChat == "profile" && my_action == "edit") || my_action == "jspcPerform") && $(".js-syncChatHeaderName").length != 0){
                nameOnSite = $(".js-syncChatHeaderName").html();
            }
            if(nameOnSite != undefined && nameOnSite != ""){
                nameOnSite = nameOnSite.length > chatConfig.Params[device].nameTrimmLength ? nameOnSite.substring(0, chatConfig.Params[device].nameTrimmLength - 3) + "..." : nameOnSite;
                if(nameOnSite && nameStr != nameOnSite){
                    setChatSelfName(nameOnSite,"storage");
                }
                modifiedName = nameOnSite;
            }
        }
    }
    else if(target == "syncName"){
        var nameOnSite = selfUserChatName;
        if(((moduleChat == "profile" && my_action == "edit") || my_action == "jspcPerform") && $(".js-syncChatHeaderName").length != 0){
            nameOnSite = $(".js-syncChatHeaderName").html();
        }
        if(nameOnSite != undefined && nameOnSite != ""){
            nameOnSite = nameOnSite.length > chatConfig.Params[device].nameTrimmLength ? nameOnSite.substring(0, chatConfig.Params[device].nameTrimmLength - 3) + "..." : nameOnSite;
            if(nameOnSite && nameStr != nameOnSite){
                setChatSelfName(nameOnSite,"storage");
            }
            modifiedName = nameOnSite;
        }
    }
    return modifiedName;
}

/*
 * request self name
 * @inputs none
 * @returns self name / username
 */
function getSelfName(){
    var selfName = localStorage.getItem('name'),
        flag = true,data,user,modifiedName;

    if (selfName) {
        data = JSON.parse(selfName);
        user = data['user'];
        selfName = data['selfName'];
        /*if (user == loggedInJspcUser) 
        {
            flag = false;
            modifiedName = setChatSelfName(selfName,"syncName");
            if(modifiedName != undefined){
                selfName =  modifiedName;
            }
        }*/
    }
    modifiedName = setChatSelfName(selfName,"syncName");
    if(modifiedName != undefined){
        flag = false;
        selfName =  modifiedName;
    }
    //console.log("getSelfName",flag);
    if(flag){
        var apiUrl = chatConfig.Params.selfNameUr;
        ////console.log("In self Name");
        $.myObj.ajax({
            url: apiUrl,
            async: false,
            success: function (response) {
                if (response["responseStatusCode"] == "0") {
                    selfName = response["name"];
                    ////console.log("Success In self Name",selfName);
                    localStorage.setItem('name', JSON.stringify({
                        'selfName': selfName,
                        'user': loggedInJspcUser
                    }));
                }
                else {
                    checkForSiteLoggedOutMode(response);
                }
            },
            error: function (xhr) {
                //return "error";
            }
        });
        ////console.log("ReturnIn self Name");
    }
    return selfName;
}

function checkForSiteLoggedOutMode(response){
    if(typeof response != "undefined" && response["responseStatusCode"] == "9"){
        window.location.href = "/";
        //location.reload();
    }
}

/*fetch membership status of current user
@return : membership
*/
function getMembershipStatus(){
    var membership = localStorage.getItem("self_subcription");
    //console.log("membership",membership);
    if(!membership){
        //console.log("not exists");
        if(self_subcription){
            localStorage.setItem("self_subcription",self_subcription);
            membership = self_subcription;
        }
    }
    if(membership && membership!= "Free"){
        return "Paid";
    }
    else{
        return "Free";
    }
}

/* requestListingPhoto
 * request listing photo through api
 * @inputs: apiParams
 * @return: response
 */
function requestListingPhoto(apiParams) {
    var apiUrl = chatConfig.Params.photoUrl,newApiParamsPid = {},exsistParamPid = {};
    var pid = [];
    pid = Object.keys(apiParams["profiles"]);
    if(pid.length > 0){
        $.each(pid,function(index, elem){
            if(elem != "length"){
                if(apiParams["initialList"] == true){
                    manageListingPhotoReqFlag("set",elem);
                }
                if(apiParams["initialList"]== true || isListPhotoReqValid(elem) == true){
                    //console.log("normal flow");
                    if(localStorage.getItem("listingPic_"+elem)) {
                        var timeStamp = localStorage.getItem("listingPic_"+elem).split("#")[1];
                        if(new Date().getTime() - timeStamp > chatConfig.Params[device].clearListingCacheTimeout){
                            //console.log("api request gone");
                            newApiParamsPid[elem] = apiParams["profiles"][elem];
                        } 
                        else{
                            //console.log("localStorage used");
                            exsistParamPid[elem] = apiParams["profiles"][elem];
                        }
                    }
                    else {
                      newApiParamsPid[elem] = apiParams["profiles"][elem];
                    }
                }
            }
        });
        if(apiParams["initialList"] == true && Object.keys(exsistParamPid).length != 0){
            manageListingPhotoReqFlag("remove",Object.keys(exsistParamPid));
        }
        var newApiParams;
        if(Object.keys(newApiParamsPid).length != 0) {
            newApiParams = {"profiles":newApiParamsPid,"photoType":apiParams.photoType,"type":apiParams["initialList"]};
        }
        //console.log("requestListingPhoto",newApiParams);
        if (typeof newApiParams != "undefined" && newApiParams) {
            $.myObj.ajax({
                url: apiUrl,
                dataType: 'json',
                type: 'POST',
                data: newApiParams,
                timeout: 60000,
                cache: false,
                beforeSend: function (xhr) {},
                success: function (response) {
                    if (response["statusCode"] == "0") {
                        //response = {"message":"Successful","statusCode":"0","profiles":{"a1":{"PHOTO":{"ProfilePic120Url":"https://secure.gravatar.com/avatar/ef65f74b4aa2107469060e6e8b6d9478?s=48&r=g&d=monsterid","MainPicUrl":"http:\/\/172.16.3.185\/1092\/13\/21853681-1397620904.jpeg"}},"a2":{"PHOTO":{"ProfilePic120Url":"https://secure.gravatar.com/avatar/ce41f41832224bd81f404f839f383038?s=48&r=g&d=monsterid","MainPicUrl":"http:\/\/172.16.3.185\/1140\/6\/22806868-1402139087.jpeg"}},"a3":{"PHOTO":{"ProfilePic120Url":"https://avatars0.githubusercontent.com/u/46974?v=3&s=96","MainPicUrl":"http:\/\/172.16.3.185\/1153\/15\/23075984-1403583209.jpeg"}},"a6":{"PHOTO":{"ProfilePic120Url":"","MainPicUrl":"http:\/\/xmppdev.jeevansathi.com\/uploads\/NonScreenedImages\/mainPic\/16\/29\/15997035ii6124c9f1a0ee0d7c209b7b81c3224e25iic4ca4238a0b923820dcc509a6f75849b.jpg"}},"a4":{"PHOTO":""}},"responseStatusCode":"0","responseMessage":"Successful","AUTHCHECKSUM":null,"hamburgerDetails":null,"phoneDetails":null};
                        $.each(response.profiles,function(index2, elem2){
                            localStorage.setItem("listingPic_"+index2,elem2.PHOTO.ProfilePic120Url+"#"+new Date().getTime());
                        });
                        objJsChat._addListingPhoto(response, "api");
                        objJsChat._addListingPhoto(Object.keys(exsistParamPid), "local");
                        //console.log("request",apiParams["initialList"]);
                    }
                    else{
                        checkForSiteLoggedOutMode(response);
                    }
                    if(apiParams["initialList"] != undefined && apiParams["initialList"] == true){
                        manageListingPhotoReqFlag("remove",Object.keys(newApiParamsPid));
                    }
                },
                error: function (xhr) {
                    if(apiParams["initialList"] != undefined && apiParams["initialList"] == true){
                        manageListingPhotoReqFlag("remove",Object.keys(newApiParamsPid));
                    }
                    //return "error";
                }
            });
        } else {
            objJsChat._addListingPhoto(Object.keys(exsistParamPid), "local");
        }
    }
}

/* requestListingPhoto
 * request listing photo through api
 * @inputs: apiParams
 * @return: response
 */
function logChatListingFetchTimeout() {
    var postData = {"username":loggedInJspcUser};
    $.myObj.ajax({
        url: "/api/v1/chat/logChatListingFetchTimeout",
        dataType: 'json',
        type: 'POST',
        data: postData,
        timeout: 60000,
        cache: false,
        beforeSend: function (xhr) {},
        success: function (response) {
        },
        error: function (xhr) {
            //return "error";
        }
    });
}

/*function initiateChatConnection
 * request sent to openfire to initiate chat and maintain session
 * @params:none
 */
function initiateChatConnection() {
    username = loggedInJspcUser + '@' + openfireServerName;    
    strophieWrapper.connect(chatConfig.Params[device].bosh_service_url, username, pass);

    /*
    updatePresenceIntervalId = setInterval(function(){
        updatePresenceAfterInterval();
    },chatConfig.Params[device].listingRefreshTimeout);
    */
    //console.log(updatePresenceIntervalId);
}
/*getConnectedUserJID
 * get jid of connected user
 * @inputs: none
 * @return jid
 */
function getConnectedUserJID() {
    var jid = strophieWrapper.connectionObj.jid;
    if (typeof jid != "undefined") {
        //return jid.split("/")[0];
        return jid;
    } else {
        return null;
    }
}
/*xmlToJson
 * converts xml stanza to json
 * @inputs: xml
 * @return: obj
 */
function xmlToJson(xml) {
    // Create the return object
    var obj = {};
    if (xml.nodeType == 1) { // element
        // do attributes
        if (xml.attributes.length > 0) {
            obj["attributes"] = {};
            for (var j = 0; j < xml.attributes.length; j++) {
                var attribute = xml.attributes.item(j);
                obj["attributes"][attribute.nodeName] = attribute.nodeValue;
            }
        }
    } else if (xml.nodeType == 3) { // text
        obj = xml.nodeValue;
    }
    // do children
    if (xml.hasChildNodes()) {
        for (var i = 0; i < xml.childNodes.length; i++) {
            var item = xml.childNodes.item(i);
            var nodeName = item.nodeName;
            if (typeof (obj[nodeName]) == "undefined") {
                obj[nodeName] = xmlToJson(item);
            } else {
                if (typeof (obj[nodeName].push) == "undefined") {
                    var old = obj[nodeName];
                    obj[nodeName] = [];
                    obj[nodeName].push(old);
                }
                obj[nodeName].push(xmlToJson(item));
            }
        }
    }
    return obj;
}
/*invokePluginLoginHandler
 *handles login success/failure cases
 * @param: state
 */
function invokePluginLoginHandler(state, loader) {
    //console.log("invoke plign handler");
    //console.log(state);
    if (state == "success") {
        createCookie("chatAuth", "true",chatConfig.Params[device].loginSessionTimeout);
        //setLogoutClickLocalStorage("unset");
        if(objJsChat && objJsChat.manageLoginLoader && typeof (objJsChat.manageLoginLoader) == "function"){
            objJsChat._appendLoggedHTML();
        }
    } else if (state == "failure" || state == "failurePlusLog") {
        //eraseCookie("chatAuth");
        //setLogoutClickLocalStorage("set");
        if(state == "failure"){
            eraseCookie("chatAuth");
            setLogoutClickLocalStorage("set");
        }
        else{
            setLogoutClickLocalStorage("unset");
        }
        if(objJsChat && objJsChat.manageLoginLoader && typeof (objJsChat.manageLoginLoader) == "function"){
            //objJsChat.addLoginHTML(true);
            if(state == "failure"){
                objJsChat.addLoginHTML(true);
            }
            else{
                objJsChat.addLoginHTML(true,true);
            }
            if(loader != false) {
                objJsChat.manageLoginLoader();
            }
            if(state == "failurePlusLog" && chatConfig.Params[device].logChatTimeout == true){
                logChatListingFetchTimeout();
            }
        }
    } else if (state == "session_sync") {
        if ($(objJsChat._logoutChat).length != 0 && readCookie('chatAuth') != "true") {
            $(objJsChat._logoutChat).click();
        }
        if ($(objJsChat._loginbtnID).length != 0 && readCookie('chatAuth') == "true"){
            $(objJsChat._loginbtnID).click();
        }
    } else if(state == "manageLogout"){
        if(localStorage.getItem('cout') == "1"){
            $(objJsChat._logoutChat).click();
        }
    } else if(state == "autoChatLogin"){
        //console.log("ankita",localStorage.getItem("logout_"+loggedInJspcUser));
        if(localStorage.getItem("logout_"+loggedInJspcUser) != "true"){
            //console.log("yes");
            if($(objJsChat._loginbtnID).length != 0){
                //console.log("click button");
                $(objJsChat._loginbtnID).click();
            }
        }
    }
}

/*updateNonRosterListOnCEAction
function to update non roster item in listing
* @inputs:actionParams
*/
function updateNonRosterListOnCEAction(actionParams){
    //console.log("updateNonRosterListOnCEAction",actionParams);
    var action = actionParams["action"],
    user_id = actionParams["user_id"],
    groupId = actionParams["groupId"];
    if(user_id != undefined && showChat == "1"){        
        switch(action){
            case "REMOVE":
            case "BLOCK":
                //remove from non roster list
                var checkIfExists = objJsChat.checkForNodePresence(user_id,chatConfig.Params.nonRosterPollingGroups);
                //console.log("updateNonRosterListOnCEAction",checkIfExists);
                if(checkIfExists && checkIfExists["exists"] == true && checkIfExists["groupID"] != undefined && chatConfig.Params.nonRosterPollingGroups.indexOf(checkIfExists["groupID"]) != -1){
                    var deleteIdArr = [];
                    deleteIdArr.push(user_id);
                    strophieWrapper.onNonRosterListDeletion(deleteIdArr);
                }
                break;
            case "ADD":
                var otherGender = actionParams["otherGender"];
                //opposite gender check
                if(loggedInJspcGender != undefined && otherGender != undefined && loggedInJspcGender != otherGender){
                    //remove this node from existing non roster list first if node is not a roster
                    updateNonRosterListOnCEAction({
                        "user_id":user_id,
                        "action":"REMOVE"
                    });
                    //now add this node in new non roster group
                    var chatStatus = actionParams["chatStatus"],
                    username = actionParams["username"],
                    profilechecksum = actionParams["profilechecksum"];
                    if(groupId != undefined && groupId != "" && chatConfig.Params.nonRosterPollingGroups.indexOf(groupId) != -1){
                        var nodeObj = {};
                        nodeObj[user_id] = strophieWrapper.formatNonRosterObj({
                                                            "chatStatus":chatStatus,
                                                            "profileid":user_id,
                                                            "username":username,
                                                            "profileChecksum":profilechecksum,
                                                            "groupid":groupId,
                                                            "addIndex":0
                                                        });
                        strophieWrapper.onNonRosterListFetched(nodeObj,groupId,"add_node");
                    }
                }
                break;
        }
    }
}

/*invokePluginAddlisting
function to add roster item or update roster item details in listing
* @inputs:listObject,key(create_list/add_node/update_status),user_id(optional)
*/
function invokePluginManagelisting(listObject, key, user_id) {
    if (key == "add_node" || key == "create_list") {
        if (key == "create_list") {
            //console.log("create_list",listObject);
            objJsChat.manageChatLoader("hide");
        }
        if(key == "add_node" && user_id != undefined && strophieWrapper.checkForGroups(listObject[user_id][strophieWrapper.rosterDetailsKey]["groups"]) == true && listObject[user_id][strophieWrapper.rosterDetailsKey]["groups"][0] != undefined && listObject[user_id][strophieWrapper.rosterDetailsKey]["nodeType"] != "non-roster"){
            //before adding new roster openfire node in list,check presence in nonroster list to remove it first
            updateNonRosterListOnCEAction({"user_id":user_id,"action":"REMOVE"});
        }
        objJsChat.addListingInit(listObject,key);
        if (key == "add_node") {
            var newGroupId = listObject[user_id][strophieWrapper.rosterDetailsKey]["groups"][0];
            //update chat box content if opened
            //console.log("adding ankita4",newGroupId);
            objJsChat._updateChatPanelsBox(user_id, newGroupId);
        }
        if (key == "create_list") {
            setTimeout(function(){
                objJsChat.noResultError();
            },500);
        }
    } else if (key == "update_status") {
        //update existing user status in listing
        if (typeof user_id != "undefined") {
            if (listObject[user_id][strophieWrapper.rosterDetailsKey]["chat_status"] == "offline") { //from online to offline
                
                objJsChat._removeFromListing("removeCall1", listObject);
            } else if (listObject[user_id][strophieWrapper.rosterDetailsKey]["chat_status"] == "online") { //from offline to online
             
                objJsChat.addListingInit(listObject,key);
            }
        }
    } else if (key == "delete_node") {
        
        //remove user from roster in listing
        if (typeof user_id != "undefined") {
            
            objJsChat._removeFromListing('delete_node', listObject);
            objJsChat.setListMigratedFlag(user_id);
            objJsChat._disableChatPanelsBox(user_id);
            $('#' + user_id + '_hover').remove();
        }
    }
}

function createCookie(name, value, days) {
    var expires;
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    } else {
        expires = "";
    }
    document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = escape(name) + "=",
        ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') {
            c = c.substring(1, c.length);
        }
        if (c.indexOf(nameEQ) === 0) {
            return unescape(c.substring(nameEQ.length, c.length));
        }
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}

function checkEmptyOrNull(item) {
    if (item != undefined && item != null && item != "") {
        return true;
    } else {
        return false;
    }
}
 
function checkNewLogin(profileid) {
    var computedChatEncrypt = CryptoJS.MD5(profileid);
    if (checkEmptyOrNull(readCookie('chatEncrypt'))) {
        var existingChatEncrypt = readCookie('chatEncrypt');
        if (existingChatEncrypt != computedChatEncrypt) {
            eraseCookie('chatAuth');
            eraseCookie('chatEncrypt');
            createCookie('chatEncrypt', computedChatEncrypt,chatConfig.Params[device].loginSessionTimeout);
            setLogoutClickLocalStorage("unset");
            clearChatMsgFromLS();
            localStorage.removeItem('tabState');
            localStorage.removeItem('chatBoxData');
            localStorage.removeItem('lastUId');
            localStorage.setItem('isCurrentJeevansathiTab',1);
        }
    } else {
        createCookie('chatEncrypt', computedChatEncrypt,chatConfig.Params[device].loginSessionTimeout);
        //setLogoutClickLocalStorage("unset");
    }
}

function checkAuthentication(timer,loginType) {
    var auth;
    var d = new Date();
    var n = d.getTime();
    //console.log("timestamp",n);
    var loggedInUserAuth = readCookie("AUTHCHECKSUM");
        var chatParams = {
            "authchecksum":loggedInUserAuth
        };
    if(loggedInUserAuth != undefined && loggedInUserAuth != ""){
        $.ajax({
            url: listingWebServiceUrl["chatAuth"]+"?p="+n,
            async: false,
            timeout: 60000,
            cache: false,
            type: 'POST',
            data: chatParams,
            success: function (authResponse) {
                if (authResponse.data) {
                    if(typeof authResponse.data.hash !== 'undefined'){
                        auth = 'true';
                        pass = authResponse.data.hash;
                        if(objJsChat && objJsChat.manageLoginLoader && typeof (objJsChat.manageLoginLoader) == "function"){
                            objJsChat.manageLoginLoader();
                        }
                        if(loginType == "first"){
                            initiateChatConnection();
                            objJsChat._loginStatus = 'Y';
                            objJsChat._startLoginHTML();
                        }
                    }
                    else{
                        if(timer<(chatConfig.Params[device].appendRetryLimit*4)){
                            setTimeout(function(){
                                checkAuthentication(timer+chatConfig.Params[device].appendRetryLimit,loginType);
                            },timer);
                        }
                        else{
                            auth = 'false';
                            invokePluginLoginHandler("failure");
                            if(objJsChat && objJsChat.manageLoginLoader && typeof (objJsChat.manageLoginLoader) == "function"){
                                objJsChat.manageLoginLoader();
                            }
                        }
                    }
                    localStorage.removeItem("cout");

                    /*pass = JSON.parse(CryptoJS.AES.decrypt(data.hash, "chat", {
                        format: CryptoJSAesJson
                    }).toString(CryptoJS.enc.Utf8));
                    */
                } else {
                    auth = 'false';
                    checkForSiteLoggedOutMode(data);
                    invokePluginLoginHandler("failure");
                }
            },
            error: function (xhr) {
                    auth = 'false';
                    invokePluginLoginHandler("failure");
                    if(objJsChat && objJsChat.manageLoginLoader && typeof (objJsChat.manageLoginLoader) == "function"){
                        objJsChat.manageLoginLoader();
                    }
                    //return "error";
            }
        });
    }
    return auth;
}

function logoutChat() {
    strophieWrapper.disconnect();
    eraseCookie("chatAuth");
    //console.log("setting");
    setLogoutClickLocalStorage("set");
    removeLocalStorageForNonChatBoxProfiles();
}
/*invokePluginReceivedMsgHandler
 * invokes msg handler function of plugin
 *@params :msgObj
 */
function invokePluginReceivedMsgHandler(msgObj) {
    ////console.log("in invokePluginReceivedMsgHandler");
    ////console.log(msgObj);
    if (typeof msgObj["from"] != "undefined") {
        if (typeof msgObj["body"] != "undefined" && msgObj["body"] != "" && msgObj["body"] != null && msgObj['msg_state'] != strophieWrapper.msgStates["FORWARDED"]) {
            ////console.log("appending RECEIVED");
            objJsChat._appendRecievedMessage(msgObj["body"], msgObj["from"], msgObj["msg_id"],msgObj["msg_type"]);
            //send Message receieved stanza
            strophieWrapper.sendReceivedReadEvent(msgObj["to"]+"@"+openfireServerName, msgObj["from"]+"@"+openfireServerName, msgObj["msg_id"], strophieWrapper.msgStates["MESSAGE_RECEIVED"]);
        }
        if (typeof msgObj["msg_state"] != "undefined") {
            switch (msgObj['msg_state']) {
            case strophieWrapper.msgStates["RECEIVED"]:
                objJsChat._changeStatusOfMessg(msgObj["receivedId"], msgObj["from"], "recieved");
                break;
            case strophieWrapper.msgStates["COMPOSING"]:
                objJsChat._handleMsgComposingStatus(msgObj["from"], strophieWrapper.msgStates["COMPOSING"]);
                break;
            case strophieWrapper.msgStates["PAUSED"]:
                objJsChat._handleMsgComposingStatus(msgObj["from"], strophieWrapper.msgStates["PAUSED"]);
                break;
            case strophieWrapper.msgStates["RECEIVER_RECEIVED_READ"]:
                strophieWrapper.sendReceivedReadEvent(msgObj["from"], msgObj["to"], msgObj["msg_id"], strophieWrapper.msgStates["RECEIVER_RECEIVED_READ"]);
                break;
            case strophieWrapper.msgStates["SENDER_RECEIVED_READ"]:
                objJsChat._changeStatusOfMessg(msgObj["msg_id"], msgObj["from"], "recievedRead");
                break;
            case strophieWrapper.msgStates["FORWARDED"]:
                if (typeof msgObj["body"] != "undefined" && msgObj["body"] != "" && msgObj["body"] != null) {
                    if (msgObj["forward_jid"] != strophieWrapper.getSelfJID()) objJsChat._appendSelfMessage(msgObj["body"], msgObj["to"], msgObj["msg_id"], "recieved");
                }
                break;
            }
            /*if(msgObj['msg_state'] == "received"){
                objJsChat._changeStatusOfMessg(msgObj["receivedId"],msgObj["from"],"recievedRead");
            }*/
        }
    }
}

/*play sound on receiving the chat message
 * @params:none
 */
function playChatNotificationSound(){
    //if current url is not jeevansathi tab and jspc chat is on
    var isCurrentJeevansathiTab = localStorage.getItem("isCurrentJeevansathiTab");
    //console.log("playChatNotificationSound",isCurrentJeevansathiTab);
    if(showChat == "1" && isCurrentJeevansathiTab == undefined || isCurrentJeevansathiTab == 0){
        //console.log("here playChatNotificationSound");
        var audio = new Audio(chatConfig.Params[device].audioChatFilesLocation+'chatNotificationSound.mp3');
        audio.play();
    }
}

/*update last read msg in localstorage
 * @params:user_id,msg_id
 */
function setLastReadMsgStorage(user_id,msg_id){
    if(typeof msg_id != "undefined"){
        localStorage.setItem("last_read_msg_"+user_id,msg_id);
    }
}

/*fetch last read msg id from localstorage
 * @params:user_id
 */
function fetchLastReadMsgFromStorage(user_id){
    var last_id = localStorage.getItem("last_read_msg_"+user_id);
    if(last_id && last_id!= "")
        return last_id;
    else
        return null;
}

/*send typing state to receiver through openfire
 * @params:from,to,typing_state
 */
function sendTypingState(from, to, typing_state) {
    strophieWrapper.typingEvent(from, to, typing_state);
}
var CryptoJSAesJson = {
        stringify: function (cipherParams) {
            var j = {
                ct: cipherParams.ciphertext.toString(CryptoJS.enc.Base64)
            };
            if (cipherParams.iv) {
                j.iv = cipherParams.iv.toString();
            }
            if (cipherParams.salt) {
                j.s = cipherParams.salt.toString();
            }
            return JSON.stringify(j);
        },
        parse: function (jsonStr) {
            var j = JSON.parse(jsonStr);
            var cipherParams = CryptoJS.lib.CipherParams.create({
                ciphertext: CryptoJS.enc.Base64.parse(j.ct)
            });
            if (j.iv) {
                cipherParams.iv = CryptoJS.enc.Hex.parse(j.iv);
            }
            if (j.s) {
                cipherParams.salt = CryptoJS.enc.Hex.parse(j.s);
            }
            return cipherParams;
        }
    }
    /*
     * Function to get profile image for login state
     */
function getProfileImage() {
    var imageUrl = localStorage.getItem('userImg'),
        flag = true;
    var d = new Date();
    var n = d.getTime();
    if (imageUrl) {
        var data = JSON.parse(imageUrl);
        var user = data['user'];
        if (user == loggedInJspcUser) {
            flag = false;
            imageUrl = data['img'];
        }
    }
    if (flag) {
        $.ajax({
            url: chatConfig.Params.photoUrl + "?photoType=ProfilePic120Url&t="+n,
            async: false,
            success: function (data) {
                if (data.statusCode == "0") {
                    imageUrl = data.profiles[0].PHOTO.ProfilePic120Url;
                    if (typeof imageUrl == "undefined" || imageUrl == "") {
                        if (loggedInJspcGender) {
                            if (loggedInJspcGender == "F") {
                                imageUrl = chatConfig.Params[device].noPhotoUrl["self120"]["F"];
                            } else if (loggedInJspcGender == "M") {
                                imageUrl = chatConfig.Params[device].noPhotoUrl["self120"]["M"];
                            }
                        }
                    }
                    localStorage.setItem('userImg', JSON.stringify({
                        'img': imageUrl,
                        'user': loggedInJspcUser
                    }));
                }
                else{
                    checkForSiteLoggedOutMode(data);
                }
            }
        });
    }
    return imageUrl;
}

function clearChatMsgFromLS(){
    var patt1 = new RegExp("chatMsg_");
    var patt2 = new RegExp("listingPic_");
    //var patt3 = new RegExp("chatListing");
    //var patt4 = new RegExp("presence_");
    var patt5 = new RegExp("nonRosterChatListing"),patt6 = new RegExp("_sentMsgRefTime");
    for(var key in localStorage){
        if(patt1.test(key) || patt2.test(key) || /*patt3.test(key) || patt4.test(key) || */patt5.test(key) || patt6.test(key)){
            localStorage.removeItem(key);
        }
    }
}
/*
 * Clear local storage
 */
function clearLocalStorage() {
    //var removeArr = ['userImg','bubbleData_new','chatBoxData','tabState','clLastUpdated','nonRosterCLUpdated'];
    var removeArr = ['userImg','bubbleData_new','chatBoxData','tabState','name','nonRosterCLUpdated','isCurrentJeevansathiTab'];
    $.each(removeArr, function (key, val) {
        localStorage.removeItem(val);
    });
    //localStorage.removeItem('chatBoxData');
    localStorage.removeItem('lastUId');
    clearChatMsgFromLS();
}
/*hit api for chat before acceptance
 * @input: apiParams
 * @output: response
 */
function handlePreAcceptChat(apiParams,receivedJId) {
    
    var outputData = {};
    if (typeof apiParams != "undefined") {
        var postData = "";
        if (typeof apiParams["postParams"] != "undefined" && apiParams["postParams"]) {
            postData = apiParams["postParams"];
        }
        
        $.myObj.ajax({
            url: apiParams["url"],
            dataType: 'json',
            type: 'POST',
            data: postData,
            cache: false,
            async: false,
            beforeSend: function (xhr) {},
            success: function (response) {
                
                if (response["responseStatusCode"] == "0") {
                   // console.log(response);
                    if (response["actiondetails"]) {
                            outputData["errorMsg"] = (response["errorMsg"] == undefined ? response["actiondetails"]["errmsglabel"] : response["errorMsg"]);
                            outputData["cansend"] = (response["cansend"] != undefined ? response["cansend"] : true);
                            outputData["sent"] = (response["sent"] != undefined ? response["sent"] : false);
                            outputData["msg_id"] = apiParams["postParams"]["chat_id"];
                            outputData['eoi_sent'] = (response["eoi_sent"] != undefined ? response["eoi_sent"] : false);
                            if(outputData["sent"] == true){
                                strophieWrapper.sendMessage(apiParams.postParams.chatMessage,receivedJId,true,outputData["msg_id"]);
                            }
                    } else {
                        outputData = response;
                        outputData["msg_id"] = apiParams["postParams"]["chat_id"];
                        if(response["sent"] == true){
                            outputData['eoi_sent'] = response['eoi_sent'];
                            strophieWrapper.sendMessage(apiParams.postParams.chatMessage,receivedJId,true,outputData["msg_id"]);
                        }
                    }
                }
                else{
                    checkForSiteLoggedOutMode(response);
                }
            },
            error: function (xhr) {
              
                outputData["sent"] = false;
                outputData["cansend"] = true;
                outputData["errorMsg"] = "Something went wrong";
                //return "error";
            }
        });
    }
    return outputData;
}
/*
 * Handle error/info case from button click
 */
function handleErrorInHoverButton(jid, data) {
    
    if (data.buttondetails && (data.buttondetails.buttons || data.buttondetails.button)) {
        //data.actiondetails.errmsglabel = "You have exceeded the limit of the number of interests you can send";
        if (data.actiondetails.errmsglabel) {
            objJsChat.hoverButtonHandling(jid, data, "info");
        } else {
            //Change button text
            objJsChat.hoverButtonHandling(jid, data);
        }
    } else {
        objJsChat.hoverButtonHandling(jid, data, "error");
    }
}
/*call api on click on contact engine buttons in chat
 * @params:contactParams
 * @return: response
 */
function contactActionCall(contactParams) {
    var response;
    if (typeof contactParams != "undefined") {
        var receiverJID = contactParams["receiverJID"],
            action = contactParams["action"],
            checkSum = contactParams["checkSum"],
            trackingParams = contactParams["trackingParams"],
            extraParams = contactParams["extraParams"],
            nickName = contactParams["nickName"],
            userId = (receiverJID.split("@"))[0];
        var url = chatConfig.Params["actionUrl"][action],
            channel = device;
        
        /*if (device == 'PC') {
            channel = 'pc';
        }*/
        var postData = {};
        postData["profilechecksum"] = checkSum;
        postData["channel"] = channel;
        postData["pageSource"] = "chat";
        if (typeof trackingParams != "undefined") {
            $.each(trackingParams, function (key, val) {
                postData[key] = val;
            });
        }
        if (typeof extraParams != "undefined") {
            $.each(extraParams, function (k, v) {
                postData[k] = v;
            });
        }
        $.myObj.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            data: postData,
            url: url,
            success: function (data) {
                response = data;
                
                if (response["responseStatusCode"] == "0") {
                    //console.log("success of chat block");
                    updateNonRosterListOnCEAction({"user_id":userId,"action":action});
                    /*updateRosterOnChatContactActions({
                        "receiverJID": receiverJID,
                        "nickName": nickName,
                        "action": action
                    });*/
                }
                else if(response["responseStatusCode"] == "1" && action=='BLOCK')
                {
                    hideCommonLoader();
                    showCustomCommonError(response.responseMessage,5000);
                    return;
                    
                }
                else{
                    checkForSiteLoggedOutMode(response);
                }
            },
            error: function (xhr) {
                response = false;
               
            }
        });
    } else {
        response = false;
    }
    
    return response;
}
/*update roster on user action in chat(hover box/chat box) via Strophie
 *@params: rosterParams
 */
function updateRosterOnChatContactActions(rosterParams) {
    if (typeof rosterParams != "undefined") {
        var receiverJID = rosterParams["receiverJID"],
            action = rosterParams["action"],
            nickName = rosterParams["nickName"],
            user_id = receiverJID.split("@")[0];
        if (chatConfig.Params[device].updateRosterFromFrontend == true) {
            if (typeof receiverJID != "undefined" && receiverJID) {
                var nodeArr = [];
                nodeArr[user_id] = strophieWrapper.Roster[user_id];
                ////console.log("obj");
                ////console.log(strophieWrapper.Roster[user_id]);
                if (typeof nodeArr != "undefined") {
                    if (action == "ACCEPT" || action == "DECLINE" || action == "BLOCK" || action == "INITIATE") {
                        setTimeout(function () {
                            //console.log("removing list from frontend in case of consumer delay");
                            invokePluginManagelisting(nodeArr, "delete_node", user_id);
                        }, 10000);
                    }
                }
                /*switch (action) {
                    case "ACCEPT":
                        strophieWrapper.removeRosterItem(receiverJID);
                        strophieWrapper.addRosterItem({
                            "jid": receiverJID,
                            "groupid": chatConfig.Params.categoryNames["Acceptance"],
                            "subscription": chatConfig.Params.groupWiseSubscription[chatConfig.Params.categoryNames["Acceptance"]],
                            "nick": nickName
                        });
                        break;
                    case "UNBLOCK":
                        var user_id = receiverJID.split("@")[0];
                        var existingGroupId = objJsChat._fetchChatBoxGroupID(user_id),
                            groupArr = [];
                        groupArr.push(existingGroupId);
                        if (typeof existingGroupId != "undefined" && strophieWrapper.checkForGroups(groupArr) == true) {
                            strophieWrapper.addRosterItem({
                                "jid": receiverJID,
                                "groupid": existingGroupId,
                                "subscription": chatConfig.Params.groupWiseSubscription[existingGroupId],
                                "nick": nickName
                            });
                        }
                        break;
                    case "BLOCK":
                        strophieWrapper.removeRosterItem(receiverJID);
                        break;
                    case "DECLINE":
                        strophieWrapper.removeRosterItem(receiverJID);
                        break;
                    case "INITIATE":
                        strophieWrapper.removeRosterItem(receiverJID);
                        strophieWrapper.addRosterItem({
                            "jid": receiverJID,
                            "groupid": chatConfig.Params.categoryNames["Interest Sent"],
                            "subscription": chatConfig.Params.groupWiseSubscription[chatConfig.Params.categoryNames["Interest Sent"]],
                            "nick": nickName
                        });
                        break;
                }*/
            }
        }
    }
}

function globalSleep(milliseconds) {
    var start = new Date().getTime();
    for (var i = 0; i < 1e7; i++) {
        if ((new Date().getTime() - start) > milliseconds) {
            break;
        }
    }
}

function setLogoutClickLocalStorage(key){
    if(key == "set"){
        localStorage.setItem("logout_"+loggedInJspcUser,"true");
    }
    else{
        localStorage.removeItem("logout_"+loggedInJspcUser);
    }
}

function getFromLocalStorage(key){
    return localStorage.getItem(key);
}

function setInLocalStorage(key, value){
    localStorage.setItem(key, value);
}

/*
function updatePresenceAfterInterval(){
    //console.log("In updatePresenceAfterInterval");
    var presenceData = JSON.parse(getFromLocalStorage("presence_"+loggedInJspcUser));
    if(presenceData) {
        var rosterDetails = JSON.parse(getFromLocalStorage('chatListing'+loggedInJspcUser));
        $.each(presenceData, function (uid, chatStatus) {
            strophieWrapper.updatePresence(uid, chatStatus);
            if(rosterDetails[uid]) {
                rosterDetails[uid]["rosterDetails"]["chat_status"] = chatStatus;
            }
        });
        setInLocalStorage('chatListing'+loggedInJspcUser,JSON.stringify(rosterDetails));
    }
}
*/

$(document).ready(function () {
    //console.log("Doc ready");
    var isCurrentJeevansathiTab = localStorage.getItem("isCurrentJeevansathiTab");
    if(isCurrentJeevansathiTab == undefined && showChat == "1"){
        localStorage.setItem("isCurrentJeevansathiTab",1);
    }
  
    if(typeof loggedInJspcUser!= "undefined")
        checkNewLogin(loggedInJspcUser);
    var checkDiv = $("#chatOpenPanel").length;
    $("#HideID").on('click',function(){
        /*
         * Check added as on hide profile user is deleted from openfire and if cookie is set then cant reconnect
         */
        eraseCookie("chatAuth");

    });
    if (showChat && (checkDiv != 0)) {
        var chatLoggedIn = readCookie('chatAuth');
        var loginStatus;
        $("#jspcChatout").on('click',function(){
            localStorage.removeItem("self_subcription");
            $(objJsChat._logoutChat).attr("data-siteLogout","true");
            $(objJsChat._logoutChat).click();
            //setLogoutClickLocalStorage("unset");  
        });
        
        //event to detect focus on page
        $(window).focus(function() {
            //console.log("Doc focus");
            invokePluginLoginHandler("manageLogout");
            if(strophieWrapper.synchronize_selfPresence == true){
                invokePluginLoginHandler("session_sync");
            }
            /*var dt = new Date();
            var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
            console.log("page focus in",time);*/
            localStorage.setItem("isCurrentJeevansathiTab",1);
        });

        //event to detect focus out of page
        $(window).on("blur",function() {
            //console.log("Doc blur");
            /*var dt = new Date();
            var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
            console.log("page focus out",time);*/
            //console.log("blur");
            localStorage.setItem("isCurrentJeevansathiTab",0);
        });

        

        
        $(window).on("offline", function () {
            strophieWrapper.currentConnStatus = Strophe.Status.DISCONNECTED;
        });
        $(window).on("online", function () {
            globalSleep(15000);
            //console.log("detected internet connectivity");
            //console.log("In online");
            chatLoggedIn = readCookie('chatAuth');
            //console.log(chatLoggedIn);
            if (chatLoggedIn == 'true' && loginStatus == "Y") {
                //console.log("In if of online");
                if (username && pass) {
                    //console.log("user pass exist");
                    strophieWrapper.reconnect(chatConfig.Params[device].bosh_service_url, username, pass);
                }
            }
        });
        if (chatLoggedIn == 'true') {
            if(objJsChat && objJsChat.manageLoginLoader && typeof (objJsChat.manageLoginLoader) == "function"){
                objJsChat.manageLoginLoader();
            }
            checkAuthentication(chatConfig.Params[device].loginRetryTimeOut,"second");
            loginStatus = "Y";
            initiateChatConnection();
        } else {
            loginStatus = "N";
        }
        
        imgUrl = getProfileImage();
        selfName = getSelfName();
        objJsChat = new JsChat({
            loginStatus: loginStatus,
            mainID: "#chatOpenPanel",
            //profilePhoto: "<path>",
            imageUrl: imgUrl,
            selfName: selfName,
            listingTabs: chatConfig.Params[device].listingTabs,
            rosterDetailsKey: strophieWrapper.rosterDetailsKey,
            listingNodesLimit: chatConfig.Params[device].groupWiseNodesLimit,
            groupBasedChatBox: chatConfig.Params[device].groupBasedChatBox,
            contactStatusMapping: chatConfig.Params[device].contactStatusMapping,
            maxMsgLimit:chatConfig.Params[device].maxMsgLimit,
            rosterDeleteChatBoxMsg:chatConfig.Params[device].rosterDeleteChatBoxMsg,
            rosterGroups:chatConfig.Params[device].rosterGroups,
            checkForDefaultEoiMsg:chatConfig.Params[device].checkForDefaultEoiMsg,
            setLastReadMsgStorage:chatConfig.Params[device].setLastReadMsgStorage,
            chatAutoLogin:chatConfig.Params[device].autoChatLogin,
            categoryTrackingParams:chatConfig.Params.categoryTrackingParams,
            groupBasedConfig:chatConfig.Params[device].groupBasedConfig
        });
        
        objJsChat.onEnterToChatPreClick = function () {
	    removeLocalStorageForNonChatBoxProfiles();
            //objJsChat._loginStatus = 'N';
            var chatLoggedIn = readCookie('chatAuth');
            //if (chatLoggedIn != 'true') 
            {
                if(objJsChat && objJsChat.manageLoginLoader && typeof (objJsChat.manageLoginLoader) == "function"){
                    objJsChat.manageLoginLoader();
                }
                var auth = checkAuthentication(chatConfig.Params[device].loginRetryTimeOut,"first");
                if (auth != "true") {
                    //console.log("123");
                    return;
                } else {
                    objJsChat._selfName = getSelfName();
                    //console.log("login my case",objJsChat._selfName);
                    if($("#selfImgDiv img") != undefined && $("#selfImgDiv img").attr("src") != undefined){
                        localStorage.setItem('userImg', JSON.stringify({
                            'img': $("#selfImgDiv img").attr("src"),
                            'user': loggedInJspcUser
                        }));
                    }
                    else{
                        var imgurl = getProfileImage();
                        $("#selfImgDiv img").attr("src",imgurl);
                    }
                    /*
                    initiateChatConnection();
                    objJsChat._loginStatus = 'Y';
                    */
                }
            }
            /*else if (chatLoggedIn == 'true'){
                //objJsChat._loginStatus = 'Y';
                location.reload();
            }*/
           
        }
        objJsChat.onChatLoginSuccess = function () {
            //trigger list creation
           
            strophieWrapper.triggerBindings();
        }
        objJsChat.onHoverContactButtonClick = function (params) {
              
                var checkSum = $("#" + params.id).attr('data-pchecksum');
                var paramsData = $("#" + params.id).attr('data-params');
                var receiverJID = $("#" + params.id).attr('data-jid');
                var nickName = $("#" + params.id).attr('data-nick');
                //checkSum = "802d65a19583249de2037f9a05b2e424i6341959";
                var trackingParamsArr = paramsData.split("&"),
                    trackingParams = {};
                $.each(trackingParamsArr, function (key, val) {
                    var v = val.split("=");
                    trackingParams[v[0]] = v[1];
                });
                idBeforeSplit = params.id.split('_');
                idAfterSplit = idBeforeSplit[0];
                action = idBeforeSplit[1];
                response = contactActionCall({
                    "receiverJID": receiverJID,
                    "action": action,
                    "checkSum": checkSum,
                    "trackingParams": trackingParams,
                    "nickName": nickName
                });
                if (response != false) {
                    
                    handleErrorInHoverButton(idAfterSplit, response);
                }
            }
            /*executed on click of contact engine buttons in chat box
             */
        objJsChat.onChatBoxContactButtonsClick = function (params) {
                if (typeof params != "undefined" && params) {
                    var userId = params["receiverID"],
                        checkSum = params["checkSum"],
                        trackingParams = params["trackingParams"],
                        extraParams = params["extraParams"],
                        nickName = params["nick"];
                    var response = contactActionCall({
                        "receiverJID": params["receiverJID"],
                        "action": params["buttonType"],
                        "checkSum": checkSum,
                        "trackingParams": trackingParams,
                        "extraParams": extraParams,
                        "nickName": nickName
                    });
                    return response;
                } else {
                    return false;
                }
            }
            /*
             * Sending typing event
             */
        objJsChat.sendingTypingEvent = function (from, to, typingState) {
            strophieWrapper.typingEvent(from, to, typingState);
        }
        objJsChat.onLogoutPreClick = function (fromSiteLogout) {
                //console.log("in onLogoutPreClick",fromSiteLogout);
                objJsChat._loginStatus = 'N';
                clearLocalStorage();
                //clearInterval(updatePresenceIntervalId);
                strophieWrapper.initialRosterFetched = false;
                strophieWrapper.initialNonRosterFetched = false;
                //clear polling of non roster groups listing
                clearNonRosterPollingInterval();
                strophieWrapper.disconnect();
                //strophieWrapper.Roster = {};
                eraseCookie("chatAuth");
                manageListingPhotoReqFlag("reset");
                ifChatListingIsCreated = 0;
                if(fromSiteLogout == "true"){
                    setLogoutClickLocalStorage("unset");
                }
                else{
                    setLogoutClickLocalStorage("set");
                }
            }
            //executed for sending chat message
        objJsChat.onSendingMessage = function (message, receivedJId, receiverProfileChecksum, contact_state) {
                
                var output;
                if (chatConfig.Params[device].contactStatusMapping[contact_state]["enableChat"] == true) {
                    if (chatConfig.Params[device].contactStatusMapping[contact_state]["useOpenfireForChat"] == true) {
                        
                        output = strophieWrapper.sendMessage(message, receivedJId);
                        
                    } else {
                        
                        var apiParams = {
                            "url": chatConfig.Params.preAcceptChat["apiUrl"],
                            "postParams": {
                                "profilechecksum": receiverProfileChecksum,
                                "chatMessage": message,
                                "chat_id":strophieWrapper.getUniqueId()
                            }
                        };
                        if (typeof chatConfig.Params.preAcceptChat["extraParams"] != "undefined") {
                            
                            $.each(chatConfig.Params.preAcceptChat["extraParams"], function (key, val) {
                                apiParams["postParams"][key] = val;
                            });
                        }
                       
                        output = handlePreAcceptChat(apiParams,receivedJId);
                       
                    }
                } else {
                    output = {};
                    output["errorMsg"] = "You are not allowed to chat";
                    output["cansend"] = false;
                    output["sent"] = false;
                }
                return output;
            }
            /*objJsChat.onPostBlockCallback = function (param) {
                
                //the function goes here which will send user id to the backend
            }*/
        objJsChat.onPreHoverCallback = function (pCheckSum, username, hoverNewTop, shiftright) {
          
           url = profileServiceUrl + '/profile/v1/profile';
           var authchecksum = readCookie("AUTHCHECKSUM");
	   		
            $.ajax({
                type: 'GET',
                async: false,
                data: {
                    view : "vcard",
                    pfids : pCheckSum,
                },
		        headers:{
			    "JB-Profile-Identifier" : authchecksum,
			    "JB-Raw-Data" : false
		        },
                url: url,
                success: function (response) {
                
		 
		        if(response.header.status == 200) { 
                                for(var i=0;i<response.data.items.length;i++) {
    				    var data = response.data.items[i];
    		
    				    data.jid = data.profileid;
    				    data.education = data.eduLevelNew;
    				    data.location = (typeof data.cityRes == "string" &&  data.cityRes.length) ? data.cityRes : data.countryRes;
    				    if (data.photo == '' && loggedInJspcGender) {
                            if (loggedInJspcGender == "F") {
                	                data.photo = chatConfig.Params[device].noPhotoUrl["self120"]["M"];
        	                    } else if (loggedInJspcGender == "M") {
                        	        data.photo = chatConfig.Params[device].noPhotoUrl["self120"]["F"];
                        	    }
                            } 
    			
        	                objJsChat.updateVCard(data, pCheckSum, function () {
                	            $('#' + username + '_hover').css({
                        	        'top': hoverNewTop,
                                    'visibility': 'visible',
        	                        'right': shiftright
                                });
    	
        	                }); 
        			}
                    //send the contact engine buttons check stanza
                    if(chatConfig.Params[device].enableLoadTestingStanza == true){
                        strophieWrapper.sendContactStatusRequest(username+"@"+openfireServerName,"check");
                    }
                } else {
			         checkForSiteLoggedOutMode({"responseStatusCode":9});
		        }
            }
        });
       }
       
       objJsChat.rosterDeleteChatBoxReponse = function(from,to){
           var headerData = {"Content-Type": "application/json"};
           var inputParams = JSON.stringify({
            "msg":"chatCheck",
            "from":from,
            "to": to,
            "check":"1",
            "id":generateChatHistoryID("received")
            });
            $.myObj.ajax({
                url: listingWebServiceUrl["rosterRemoveMsg"],
                dataType: 'json',
                type: 'POST',
                cache:false,
                async: true,
                timeout: 60000,
                headers:headerData,
                data: inputParams,
                beforeSend: function (xhr) {},
                success: function (response) {
                    if(response["header"]["status"] == 400){
                        var msg = response["data"]["buttondetails"]["infomsglabel"];
                        if(msg == undefined){
                            msg = objJsChat._rosterDeleteChatBoxMsg;
                        }
                        if($('chat-box[user-id="' + to + '"] #rosterDeleteMsg_'+ to + '').length == 0){
                            $('chat-box[user-id="' + to + '"] .chatMessage').append('<div id="rosterDeleteMsg_'+to+'" class="pt20 txtc color5">'+msg+'</div>');
                        }
                    }
                },
                error: function (xhr) {
                }
            });
       }
        objJsChat.start();
    }

});
