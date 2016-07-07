/*This file includes functions used for intermediate data transfer for JSPC chat from 
* converse client(converse.js) to chat plugin(chat_js.js)
*/
var listingInputData = [],listCreationDone=false;  //listing data sent to plugin-array of objects

function readSiteCookie(name) {
    var nameEQ = escape(name) + "=",ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return unescape(c.substring(nameEQ.length, c.length));
    }
    return null;
}

var pluginId = '#chatOpenPanel',device = 'PC';

/*function initiateChatConnection
* request sent to openfire to initiate chat and maintain session
* @params:none
*/     
function initiateChatConnection()
{
    //only for dev env--------------------start:ankita
    var username = "a1@localhost";
    if(readSiteCookie("CHATUSERNAME")=="ZZTY8164")
        username = "a2@localhost";
    else if(readSiteCookie("CHATUSERNAME")=="ZZXS8902")
        username = "a1@localhost";
    //only for dev env--------------------end:ankita
    try
    {
        //initialise converse settings and fetch data and then execute callback function
        require(['converse'], function (converse) {
            converse.initialize({
                bosh_service_url: chatConfig.Params[device].bosh_service_url,
                keepalive: chatConfig.Params[device].keepalive,
                message_carbons: true, //why req?? - ankita
                //play_sounds: true,
                roster_groups: chatConfig.Params[device].roster_groups,
                hide_offline_users: chatConfig.Params[device].hide_offline_users,
                debug:false,
                //prebind:true,
                auto_login:true,
                //jid:username,
                //password:"123",
                //sid:sid,
                //rid:1,
                show_controlbox_by_default:true,
                use_vcards:chatConfig.Params[device].use_vcards,
                authentication:'login',
                listing_data:{},
                credentials_url: '/api/v1/chat/fetchCredentials?jid='+username,
                rosterDisplayGroups:chatConfig.Params[device].rosterDisplayGroups,
                listCreationDone:false,
                //prebind_url: 'http://localhost/api/v1/chat/authenticateChatSession?jid=a1@localhost',  
            }),function(){
                console.log("calling callback");
            }
        });
    }
    catch(e)
    {
        console.log("Exception thrown in initiateChatConnection function - "+e);
    }
}

/*function fetchConverseSettings
* get value of converse settings' key
* @params:key
* @return:value
*/
function fetchConverseSettings(key)
{
    var value = converse.settings.get(key);
    return value;
}

/*function setConverseSettings
* set value of converse settings' key
* @params:key,value
* @return:none
*/
function setConverseSettings(key,value)
{
    converse.settings.set(key,value);
}

/*function mapListingJsonToHTML
* map json data for listing to html
* @params:jsonData
* @return:listingHTML
*/
function mapListingJsonToHTML(jsonData)  //used for old plugin - ankita
{
    var listingHTML = '<div id="listing_tab1">';
    $.each(jsonData,function( index, val ){
        listingHTML = listingHTML+ '<div id="'+chatConfig.Params[device].rosterDisplayGroups[index]+'"><div class="f12 fontreg nchatbdr2"><p class="nchatt1 fontreg pl15">'+index+'</p></div><ul class="chatlist">';
        $.each(val,function( key, details ){
              listingHTML = listingHTML+'<li class="clearfix profileIcon" id="profile_'+details.fullname+'"><img id="pic_'+key.fullname+'" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">'+details.fullname+'</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li>';
        });
        listingHTML = listingHTML+'</ul></div>';
    });
    listingHTML = listingHTML + '</div>';
    return listingHTML;   
}

/*function updateListing
* update listing
* @params:rosterData
*/
function updateListing(rosterData) //used for old plugin - ankita
{
    //console.log("roster update");
    //console.log(rosterData);
    var newRosterHTML = "",groupid;
    groupid = chatConfig.Params[device].rosterDisplayGroups[rosterData.groups[0]]; //group id mapping
    //handle if no user in group - case ankita
    //if this new user doesn't exist already
    if($("#"+groupid).find("ul.chatlist").find("li#profile_"+rosterData.fullname).length === 0)
    {
        newRosterHTML = '<li class="clearfix profileIcon" id="profile_'+rosterData.fullname+'"> <img id="pic_'+rosterData.fullname+'" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">'+rosterData.fullname+'</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li>';
        //append newRosterHTML
        $("#"+groupid).find("ul.chatlist").append(newRosterHTML);
    }     
}

/*function createListingPanel
* creates listing panel after login and json data generation
* @params:none
*/ 
function createListingPanel()   //used for old plugin--ankita
{
    //get json data for listing
    var listingData = fetchConverseSettings("listing_data");
    console.log("createListingPanel");
    //console.log(listingData);
    $(pluginId).setChatPluginOption("listingJsonData",listingData);
    //map json data to listing html
    var listingHTML = mapListingJsonToHTML(listingData);
    //console.log(listingHTML);
    $(pluginId).setChatPluginOption("Tab1Data" ,listingHTML);
    //show listing panel by appending html
    var ChatPluginObj = $(pluginId).chatplugin();
    ChatPluginObj.addListingBody();
}
// Changes XML to JSON
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
        for(var i = 0; i < xml.childNodes.length; i++) {
            var item = xml.childNodes.item(i);
            var nodeName = item.nodeName;
            if (typeof(obj[nodeName]) == "undefined") {
                obj[nodeName] = xmlToJson(item);
            } else {
                if (typeof(obj[nodeName].push) == "undefined") {
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

/*invokePluginAddlisting
function to add roster item with vcard details or update roster item details in listing
* @inputs:listObject,vcardObj,key(create_list/add_node/update_status)
*/

function invokePluginManagelisting(listObject,vcardObj,key){
    //console.log(listObject);
    //console.log(vcardObj); //to be cached---ankita
    var listNodeObj = {"rosterDetails":{},"vcardDetails":vcardObj},nodeArr = [];
    if(typeof listObject.attributes == "undefined")
        listNodeObj["rosterDetails"] = listObject;
    else
        listNodeObj["rosterDetails"] = listObject.attributes;
    if(key=="add_node"){
        if(listCreationDone == false){   
            //create list with n nodes
            listingInputData.push(listNodeObj);
            console.log(chatConfig.Params[device].initialRosterLimit["nodesCount"]);
            if(listingInputData.length == chatConfig.Params[device].initialRosterLimit["nodesCount"]){
                key = "create_list";
                console.log("list created after adding "+listingInputData.length+" nodes");
                //plugin.addInList(listingInputData,key); 
                console.log(listingInputData);
                listCreationDone = true;
                setConverseSettings("listCreationDone",true);
            }
        }else{
           //add single node after list creation
            nodeArr.push(listNodeObj);
            console.log("adding single node");
            console.log(nodeArr);
            //plugin.addInList(nodeArr,key);
        }
    }else if(key=="update_status"){             
        //update existing user status in listing
        nodeArr.push(listNodeObj);
        console.log("updating status");
        console.log(nodeArr);
        //plugin.addInList(nodeArr,key);
    }else if(key=="delete_node")
    {
        //remove user from roster in listing
        nodeArr.push(listNodeObj);
        console.log("deleting node from roster");
        console.log(nodeArr);
        //plugin.addInList(nodeArr,key);
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
    var nameEQ = escape(name) + "=",ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return unescape(c.substring(nameEQ.length, c.length));
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
    console.log("in erase cookie function");
    console.log("erasing cookie");
}

$(document).ready(function(){
 setCreateListingInterval();   //to be decided where to call - ankita
});

/*setCreateListingInterval
* sets time interval after which json data will be sent to plugin to create list if not created
* @params: none
*/
function setCreateListingInterval()
{
    setTimeout(function(){
        if(listCreationDone==false)
        {
            console.log("triggering list creation as time interval exceeded");
            listCreationDone = true;
            setConverseSettings("listCreationDone",true);
            console.log(listingInputData);
            //plugin.addInList(listingInputData,"create_list");    
        }
    },chatConfig.Params[device].initialRosterLimit["timeInterval"]);
}

function checkAuthentication(){
    var auth;
    $.ajax({
        url: "/api/v1/chat/chatUserAuthentication",
        async: false,
        success: function(data){
            console.log(data.statusCode);
            if(data.responseStatusCode == "0"){
                console.log("In chatUserAuthentication Login Done");
                createCookie("chatAuth","true");
                //loginChat();
                auth = 'true';
            }
            else{
                console.log(data.responseMessage);
                console.log("In checkAuthentication failure");
                eraseCookie("chatAuth");
                auth = 'false';
            }
        }
    });
    return auth;
}

function logoutChat(){
    console.log("In logout chat function")
    converse.user.logout();
    eraseCookie("chatAuth");
}

$(document).ready(function(){
    var checkDiv = $("#chatOpenPanel").length;
    if(showChat && (checkDiv != 0)){

        var chatLoggedIn = readCookie('chatAuth');
        var loginStatus;
        if(chatLoggedIn == 'true'){
            loginStatus = "Y";
            initiateChatConnection();
        }
        else{
            loginStatus = "N";
        }

        var objJsChat = new JsChat({
        loginStatus: loginStatus,
        mainID:"#chatOpenPanel",
        //profilePhoto: "<path>",
        profileName: "bassi",
        listingTabs:chatConfig.Params[device].listingTabs
    });
    
    objJsChat.onEnterToChatPreClick = function(){
        //objJsChat._loginStatus = 'N';
        console.log("Checking variable");
        console.log(chatLoggedIn);

    
        if(chatLoggedIn != 'true'){
            var auth = checkAuthentication();
            if(auth != "true"){
                console.log("Before return");
                return ;
            }
            else{
                initiateChatConnection();
                objJsChat._loginStatus = 'Y';
            }
        }
        console.log("In callback");
    }
    
    objJsChat.onLogoutPreClick = function(){
        console.log("In Logout preclick");
        objJsChat._loginStatus = 'N';
        logoutChat();
    }

    objJsChat.start();
   }
});