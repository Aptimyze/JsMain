/*This file includes functions used for intermediate data transfer for JSPC chat from 
* converse client(converse.js) to chat plugin(chat_js.js)
*/
var listingInputData = [],listCreationDone=false,objJsChat;  //listing data sent to plugin-array of objects

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
    /*
    //only for dev env--------------------start:ankita
    var username = "a1@localhost";
    if(readSiteCookie("CHATUSERNAME")=="ZZTY8164")
        username = "a12@localhost";
    else if(readSiteCookie("CHATUSERNAME")=="ZZXS8902")
        username = "a1@localhost";
    else if(readSiteCookie("CHATUSERNAME")=="bassi")
        username = "a8@localhost";
    else if(readSiteCookie("CHATUSERNAME")=="ZZRS3292")
        username = "a11@localhost";
    //only for dev env--------------------end:ankita
    console.log("Username:");
    console.log(username);
    try
    {
        //initialise converse settings
        require(['converse'], function (converse) {
            converse.initialize({
                bosh_service_url: chatConfig.Params[device].bosh_service_url,
                keepalive: chatConfig.Params[device].keepalive,
                message_carbons: true,
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
    */
    var username = 'a1@localhost';
    if(readSiteCookie("CHATUSERNAME")=="bassi")
        username = 'a8@localhost';
    console.log(chatConfig.Params[device].bosh_service_url);
    strophieWrapper.connect(chatConfig.Params[device].bosh_service_url,username,"123");
    console.log(strophieWrapper.connectionObj);
}



function sendMessage() {
    var message = "$('#message').get(0).value";
    var to = 'a2@localhost';
    if(message && to){
	var reply = $msg({
	    to: to,
	    type: 'chat'
	})
	.cnode(Strophe.xmlElement('body', message)).up()
	.c('active', {xmlns: "http://jabber.org/protocol/chatstates"});
	connection.send(reply);
	log('I sent ' + to + ': ' + message);
    }
}

/*function fetchConverseSettings
* get value of converse settings' key
* @params:key
* @return:value
*/
/*
function fetchConverseSettings(key)
{
    var value = converse.settings.get(key);
    return value;
}
*/
/*function setConverseSettings
* set value of converse settings' key
* @params:key,value
* @return:none
*/
/*
function setConverseSettings(key,value)
{
    converse.settings.set(key,value);
}
*/

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
function to add roster item or update roster item details in listing
* @inputs:listObject,key(create_list/add_node/update_status)
*/

function invokePluginManagelisting(listObject,key){
    console.log("calling invokePluginAddlisting");
    /*var listNodeObj = {"rosterDetails":{},"vcardDetails":vcardObj},nodeArr = [];
    listNodeObj["rosterDetails"] = listObject;
    if(typeof listObject.attributes == "undefined")
        listNodeObj["rosterDetails"] = listObject;
    else
        listNodeObj["rosterDetails"] = listObject.attributes;*/

    if(key=="add_node"){
        //if(listCreationDone == false){   
            //create list with n nodes
            //nodeArr = listObject.splice(0,chatConfig.Params[device].initialRosterLimit["nodesCount"]);
            //console.log(nodeArr);
            //listingInputData.push(listNodeObj);
            //console.log("adding node before list creation");
            //if(nodeArr.length == chatConfig.Params[device].initialRosterLimit["nodesCount"]){
               // key = "create_list";
                //console.log("list created after adding "+listingInputData.length+" nodes");
                //listCreationDone = true;
                console.log("adding "+listObject.length+" nodes in invokePluginAddlisting");
                //objJsChat.addListingInit(listObject);
                console.log(listObject);
                //setConverseSettings("listCreationDone",true);
            //}
        /*} else{
           //add single node after list creation
            nodeArr.push(listNodeObj);
            console.log("adding single node");
            console.log(nodeArr);
            objJsChat.addListingInit(nodeArr);
        }*/   //add node case
    } else if(key=="update_status"){             
        //update existing user status in listing
        nodeArr.push(listNodeObj);
        console.log("updating status");
        console.log(nodeArr);
        if(listNodeObj["rosterDetails"]["chat_status"] == "offline")  //from online to offline
        {
            console.log("removing from listing");
            objJsChat._removeFromListing("removeCall1",nodeArr);
        }
        else if(listNodeObj["rosterDetails"]["chat_status"] == "online") //from offline to online
        {
            console.log("adding in list");
            objJsChat.addListingInit(nodeArr);
        }
    } else if(key=="delete_node"){
        //remove user from roster in listing
        //nodeArr.push(listNodeObj);
        var userId = (listNodeObj["rosterDetails"]["jid"]).split("@");
        console.log("deleting node from roster-"+userId[0]);
        //console.log(nodeArr);
        objJsChat._removeFromListing("removeCall2",userId[0]);
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
            createCookie('chatEncrypt', computedChatEncrypt);
        }
    } else {
        createCookie('chatEncrypt', computedChatEncrypt);
    }
}


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
            //setConverseSettings("listCreationDone",true);
            console.log(listingInputData);
            //plugin.addInList(listingInputData,"create_list"); 
            objJsChat.addListingInit(listingInputData);   
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
/*
function logoutChat(){
    console.log("In logout chat function")
    converse.user.logout();
    eraseCookie("chatAuth");
}
*/

function invokePluginReceivedMsgHandler(msgObj)
{
    console.log("invokePluginReceivedMsgHandler");
    console.log(msgObj);
    if(msgObj["message"] != "") 
        objJsChat._appendRecievedMessage(msgObj["message"],msgObj["from"].substr(0, msgObj["from"].indexOf('@')),msgObj["msgid"]); 
}


$(document).ready(function(){
    console.log("User");
    console.log(loggedInJspcUser);
    checkNewLogin(loggedInJspcUser);
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

        objJsChat = new JsChat({
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
                console.log("Initiate strophe connection in preclick");
                initiateChatConnection();
                objJsChat._loginStatus = 'Y';
            }
        }
        console.log("In callback");
    }

    objJsChat.onChatLoginSuccess = function(){
        //trigger list creation if nodes in roster lesser than limit
        //setCreateListingInterval();
    }
    
    objJsChat.onLogoutPreClick = function(){
        console.log("In Logout preclick");
        objJsChat._loginStatus = 'N';
        logoutChat();
    }

    objJsChat.onSendingMessage = function(){
        //var x = converse.listen.on('messageSend',"MEssagesend");
        //console.log(x);
        /*
        console.log("Converse");
        //converse.emit('showSentOTRMessage',"msg");
        //console.log("Helper file onSendingMessage"+converse.ChatBoxView());
        converse.ChatBoxView().sendMessage("hi");
        */
       //console.log(converse.initialize.ChatBoxView);
       //console.log(converse.initialize.ChatBoxView());
       //converse.initialize.ChatBoxView.sendMessage("Hihello");
       
       /*
       var msg = converse.env.$msg({
          from: 'a1@localhost',
          to: 'a6@localhost',
          type: 'chat',
          body: "from INtermediate file"
       });
       converse.send(msg);
       */
    }

    objJsChat.onPostBlockCallback= function(param){

       console.log('the user id to be blocked:'+ param);
       //the function goes here which will send user id to the backend
    }

    objJsChat.start();
    
   /*var i =0;
       setInterval(function(){ 
           i++;
           var data= [
                       {
                           "rosterDetails": {
                               "chat_status": "offline",
                               "fullname": "a12",
                               "Groups": ["dpp"],
                               "id": "a12@localhost",
                               "jid": "a12@localhost"
                           }
                       }
                   ];
                   console.log("removing");
       objJsChat._removeFromListing('removeCall1',data); 


       }, 30000);*/
   }
});