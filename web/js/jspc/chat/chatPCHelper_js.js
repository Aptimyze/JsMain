/*This file includes functions used for intermediate data transfer for JSPC chat from 
* converse client(converse.js) to chat plugin(chat_js.js)
*/

var listingInputData = [],listCreationDone=false,objJsChat,pass;  //listing data sent to plugin-array of objects

//var decrypted = JSON.parse(CryptoJS.AES.decrypt(api response, "chat", {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8));

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
    var username = loggedInJspcUser+'@localhost';
    
    /*var username = 'a1@localhost';
    if(readSiteCookie("CHATUSERNAME")=="bassi")
        username = 'a8@localhost';
    else if(readSiteCookie("CHATUSERNAME")=="ZZTY8164")
        username = 'a2@localhost';8/
    
    console.log("Nitish"+username);

    console.log(chatConfig.Params[device].bosh_service_url);
    console.log("user:"+username+" pass:"+pass);

    strophieWrapper.connect(chatConfig.Params[device].bosh_service_url,username,pass);
    console.log(strophieWrapper.connectionObj);
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

/*invokePluginLoginHandler
*handles login success/failure cases
* @param: state
*/
function invokePluginLoginHandler(state)
{
    if(state == "success")
    {
        createCookie("chatAuth","true");
        objJsChat._appendLoggedHTML();
    }
    else
    {
        eraseCookie("chatAuth");
        objJsChat.addLoginHTML(true);
    }
}

/*invokePluginAddlisting
function to add roster item or update roster item details in listing
* @inputs:listObject,key(create_list/add_node/update_status),user_id(optional)
*/

function invokePluginManagelisting(listObject,key,user_id){
    console.log("calling invokePluginAddlisting");
    if(key=="add_node" || key=="create_list"){
        if(key=="create_list")
        {
            objJsChat.hideChatLoader();
        }
        console.log("adding nodes in invokePluginAddlisting");
        console.log(listObject);
        objJsChat.addListingInit(listObject);
        if(key == "create_list")
        {
            objJsChat.noResultError();
        }
    }else if(key=="update_status"){             
        //update existing user status in listing
        if(typeof user_id != "undefined")
        {
            console.log("entered for user_id"+user_id);
            if(listObject[user_id]["rosterDetails"]["chat_status"] == "offline")  //from online to offline
            {
                console.log("removing from listing");
                objJsChat._removeFromListing("removeCall1",listObject);
            }
            else if(listObject[user_id]["rosterDetails"]["chat_status"] == "online") //from offline to online
            {
                console.log("adding in list");
                objJsChat.addListingInit(listObject);
            }
        }
    } else if(key=="delete_node"){
        //remove user from roster in listing
        //nodeArr.push(listNodeObj);
        var userId = (listObject["rosterDetails"]["jid"]).split("@");
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
/*function setCreateListingInterval()
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
}*/

function checkAuthentication(){
    var auth;
    $.ajax({
        url: "/api/v1/chat/chatUserAuthentication",
        async: false,
        success: function(data){
            console.log(data.statusCode);
            if(data.responseStatusCode == "0"){
                console.log("In chatUserAuthentication Login Done");
                //createCookie("chatAuth","true");
                //loginChat();
                auth = 'true';
		pass = JSON.parse(CryptoJS.AES.decrypt(data.hash, "chat", {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8));

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
    //converse.user.logout();
    strophieWrapper.disconnect();
    eraseCookie("chatAuth");
}


function invokePluginReceivedMsgHandler(msgObj)
{
    console.log("invokePluginReceivedMsgHandler");
    console.log(msgObj);
    objJsChat._appendRecievedMessage(msgObj["body"],msgObj["from"],msgObj["msg_id"]); 
}


/*var CryptoJSAesJson = {
    stringify: function (cipherParams) {
        var j = {ct: cipherParams.ciphertext.toString(CryptoJS.enc.Base64)};
        if (cipherParams.iv) j.iv = cipherParams.iv.toString();
        if (cipherParams.salt) j.s = cipherParams.salt.toString();
        return JSON.stringify(j);
    },
    parse: function (jsonStr) {
        var j = JSON.parse(jsonStr);
        var cipherParams = CryptoJS.lib.CipherParams.create({ciphertext: CryptoJS.enc.Base64.parse(j.ct)});
        if (j.iv) cipherParams.iv = CryptoJS.enc.Hex.parse(j.iv)
        if (j.s) cipherParams.salt = CryptoJS.enc.Hex.parse(j.s)
        return cipherParams;
    }
}*/
var CryptoJSAesJson = {
    stringify: function (cipherParams) {
        var j = {ct: cipherParams.ciphertext.toString(CryptoJS.enc.Base64)};
        if (cipherParams.iv) j.iv = cipherParams.iv.toString();
        if (cipherParams.salt) j.s = cipherParams.salt.toString();
        return JSON.stringify(j);
    },
    parse: function (jsonStr) {
        var j = JSON.parse(jsonStr);
        var cipherParams = CryptoJS.lib.CipherParams.create({ciphertext: CryptoJS.enc.Base64.parse(j.ct)});
        if (j.iv) cipherParams.iv = CryptoJS.enc.Hex.parse(j.iv);
        if (j.s) cipherParams.salt = CryptoJS.enc.Hex.parse(j.s);
        return cipherParams;
    }
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
            checkAuthentication();
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
        var chatLoggedIn = readCookie('chatAuth');
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
        console.log("show loader---manvi");
        //trigger list creation
        console.log("in triggerBindings");
        strophieWrapper.triggerBindings();
        //setCreateListingInterval();
    }
    
    objJsChat.onHoverContactButtonClick = function(params){
        console.log(params);
        checkSum = $("#"+params.id).attr('data-pchecksum');
        params = $("#"+params.id).attr('data-params');
        checkSum = "7619da4377fbf2a405ede0d0535a716ei9513636";
        
        url = "/api/v2/contacts/postEOI";
        $.ajax({
            type: 'POST',
            data: {profilechecksum: checkSum,params: params,source: "chat"},
            url: url,
            success: function(data) {
                console.log(data);
                console.log(data.actiondetails.errmsglabel);
                $("#"+params.id).html(data.actiondetails.errmsglabel);
            }
        });
        
    }
    
    objJsChat.onLogoutPreClick = function(){
        console.log("In Logout preclick");
        objJsChat._loginStatus = 'N';
        strophieWrapper.disconnect();
        eraseCookie("chatAuth");
    }

    objJsChat.onSendingMessage = function(message,to){
        console.log("In helper file onSendingMessage");
        strophieWrapper.sendMessage(message,to);
        //sendMessage(message,to);
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
    
    objJsChat.onPreHoverCallback = function(pCheckSum,username,hoverNewTop,shiftright){
        console.log("In Helper preHoverCB");
        console.log(pCheckSum);
        jid = [];
        jid[0] = "'"+pCheckSum+"'";
        url = "/api/v1/chat/fetchVCard";
        $.ajax({
            type: 'POST',
            data: {jid: jid,username: username},
            url: url,
            success: function(data) {
                console.log(data);
                objJsChat.updateVCard(data,pCheckSum,function(){
                    $('#'+username+'_hover').css({ 
                        'top':  hoverNewTop,                     
                        'visibility': 'visible',
                        'right':shiftright
                    });
                    console.log("Callback done");
                });
            }
        });
        /*
        var res = 
            {
                "vCard": 
                {
                    "a6": 
                    {
                        "NAME": "nitish",
                        "EMAIL": "nitish@gmail.com",
                        "PHOTO": "url",
                        "AGE":"3",
                        "HEIGHT":"5 9",
                        "COMMUNITY":"Sikh: Arora Punjabi",
                        "EDUCATION":"MBA/PGDM, B.Com",
                        "PROFFESION":"Software",
                        "SALARY":"Rs. 15 - 20lac",
                        "CITY":"New Delhi",
                        "buttonDetails":
                        {
                            "buttons":
                            [
                                {
                                "action":"INITIATE",
                                "label":"Send Interest",
                                "iconid":null,
                                "primary":"true",
                                "secondary":"true",
                                "params":"&stype=P17",
                                "enable":true,
                                "id":"INITIATE"
                                },
                                 {
                                "action":"INITIATE",
                                "label":"Send Interest",
                                "iconid":null,
                                "primary":"true",
                                "secondary":"true",
                                "params":"&stype=P17",
                                "enable":true,
                                "id":"INITIATE"
                                }
                            ],
                            "button":null,
                            "infomsgiconid":null,
                            "infomsglabel":null,
                            "infobtnlabel":null,
                            "infobtnvalue":null,
                            "infobtnaction":null
                        },
                        "responseStatusCode": "0",
                        "responseMessage": "Successful",
                        "AUTHCHECKSUM": null,
                        "hamburgerDetails": null,
                        "phoneDetails": null
                    }  
                }
            }
        ;
        */
    }

    objJsChat.start();
   }
});
