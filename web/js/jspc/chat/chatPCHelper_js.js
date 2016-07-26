/*This file includes functions used for intermediate data transfer for JSPC chat from 
 * chat client(chatStrophieClient_js.js) to chat plugin(chat_js.js)
 */
var listingInputData = [],
    listCreationDone = false,
    objJsChat, pass, username;
var pluginId = '#chatOpenPanel',
    device = 'PC';
var pcHelperLogging = true;

function pcHelperLogger(message) {
    if (pcHelperLogging) {
        console.log(message);
    }
}

function readSiteCookie(name) {
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
/*
 * Function to get profile image for login state
 */
function getProfileImage() {
    $.ajax({
        url: "/api/v1/social/getMultiUserPhoto?photoType=ProfilePic120Url",
        async: false,
        success: function (data) {
            if (data.statusCode == "0") {
                imageUrl = data.profiles[0].PHOTO.ProfilePic120Url;
            }
        }
    });
    return imageUrl;
}


function requestListingPhoto(apiParams){
    var apiUrl = "/api/v1/social/getMultiUserPhoto";
    if(typeof apiParams!= "undefined" && apiParams){
        $.myObj.ajax({
            url: apiUrl,
            dataType: 'json',
            type: 'POST',
            data: apiParams,
            timeout: 60000,
            cache: false,
            beforeSend: function (xhr) {

            },
            success: function (response) {
                if(response["statusCode"] == "0"){
                    response = {"message":"Successful","statusCode":"0","profiles":{"a1":{"PHOTO":{"ProfilePic120Url":"https://secure.gravatar.com/avatar/ef65f74b4aa2107469060e6e8b6d9478?s=48&r=g&d=monsterid","MainPicUrl":"http:\/\/172.16.3.185\/1092\/13\/21853681-1397620904.jpeg"}},"a2":{"PHOTO":{"ProfilePic120Url":"https://secure.gravatar.com/avatar/ce41f41832224bd81f404f839f383038?s=48&r=g&d=monsterid","MainPicUrl":"http:\/\/172.16.3.185\/1140\/6\/22806868-1402139087.jpeg"}},"a3":{"PHOTO":{"ProfilePic120Url":"https://avatars0.githubusercontent.com/u/46974?v=3&s=96","MainPicUrl":"http:\/\/172.16.3.185\/1153\/15\/23075984-1403583209.jpeg"}},"a6":{"PHOTO":{"ProfilePic120Url":"","MainPicUrl":"http:\/\/xmppdev.jeevansathi.com\/uploads\/NonScreenedImages\/mainPic\/16\/29\/15997035ii6124c9f1a0ee0d7c209b7b81c3224e25iic4ca4238a0b923820dcc509a6f75849b.jpg"}},"a4":{"PHOTO":""}},"responseStatusCode":"0","responseMessage":"Successful","AUTHCHECKSUM":null,"hamburgerDetails":null,"phoneDetails":null};
                    objJsChat._addListingPhoto(response);
                }
            },
            error: function (xhr) {
                //return "error";
            }
        });  
    }
}

/*function initiateChatConnection
 * request sent to openfire to initiate chat and maintain session
 * @params:none
 */
function initiateChatConnection() {
    username = loggedInJspcUser + '@localhost';
	/*if(readSiteCookie("CHATUSERNAME")=="ZZXS8902")
        username = 'a1@localhost';
    else if(readSiteCookie("CHATUSERNAME")=="bassi")
        username = '1@localhost';
    else if(readSiteCookie("CHATUSERNAME")=="VWZ4557")
        username = 'a9@localhost';
    else if(readSiteCookie("CHATUSERNAME")=="ZZTY8164")
        username = 'a8@localhost';
    else if(readSiteCookie("CHATUSERNAME") == "ZZRS3292")
        username = 'a13@localhost';
    else if(readSiteCookie("CHATUSERNAME")=="ZZVV2929")
        username = 'a14@localhost';
    else if(readSiteCookie("CHATUSERNAME")=="ZZRR5723")
        username = 'a11@localhost';
    pass = '123';*/
    pcHelperLogger("user:" + username + " pass:" + pass);
    strophieWrapper.connect(chatConfig.Params[device].bosh_service_url, username, pass);
    pcHelperLogger(strophieWrapper.connectionObj);
}

function getConnectedUserJID() {
    var jid = strophieWrapper.connectionObj.jid;
    if (typeof jid != "undefined") {
        return jid.split("/")[0];
    } else {
        return null;
    }
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
function invokePluginLoginHandler(state) {
    if (state == "success") {
        createCookie("chatAuth", "true");
        objJsChat._appendLoggedHTML();
    } else {
        eraseCookie("chatAuth");
        objJsChat.addLoginHTML(true);
    }
}
/*invokePluginAddlisting
function to add roster item or update roster item details in listing
* @inputs:listObject,key(create_list/add_node/update_status),user_id(optional)
*/
function invokePluginManagelisting(listObject, key, user_id) {
    pcHelperLogger("calling invokePluginAddlisting");
    if (key == "add_node" || key == "create_list") {
        if (key == "create_list") {
            objJsChat.manageChatLoader("hide");
        }
        pcHelperLogger("adding nodes in invokePluginAddlisting");
        pcHelperLogger(listObject);
        objJsChat.addListingInit(listObject);
        if (key == "add_node") {
            objJsChat._updateChatPanelsBox(user_id);
            pcHelperLogger("update chat box");
        }
        if (key == "create_list") {
            objJsChat.noResultError();
        }
    } else if (key == "update_status") {
        //update existing user status in listing
        if (typeof user_id != "undefined") {
            pcHelperLogger("entered for user_id" + user_id);
            pcHelperLogger(listObject[user_id][strophieWrapper.rosterDetailsKey]["chat_status"]);
            if (listObject[user_id][strophieWrapper.rosterDetailsKey]["chat_status"] == "offline") { //from online to offline
                pcHelperLogger("removing from listing");
                objJsChat._removeFromListing("removeCall1", listObject);
            } else if (listObject[user_id][strophieWrapper.rosterDetailsKey]["chat_status"] == "online") { //from offline to online
                pcHelperLogger("adding in list");
                objJsChat.addListingInit(listObject);
            }
        }
    } else if (key == "delete_node") {
        pcHelperLogger(user_id);
        //remove user from roster in listing
        if (typeof user_id != "undefined") {
            pcHelperLogger("deleting node from roster-" + user_id);
            objJsChat._removeFromListing('delete_node', listObject);
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
    pcHelperLogger("in erase cookie function");
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

function checkAuthentication() {
    var auth;
    $.ajax({
        url: "/api/v1/chat/chatUserAuthentication",
        async: false,
        success: function (data) {
            pcHelperLogger(data.statusCode);
            if (data.responseStatusCode == "0") {
                pcHelperLogger("In chatUserAuthentication Login Done");
                //createCookie("chatAuth","true");
                //loginChat();
                auth = 'true';
                pass = JSON.parse(CryptoJS.AES.decrypt(data.hash, "chat", {
                    format: CryptoJSAesJson
                }).toString(CryptoJS.enc.Utf8));
            } else {
                pcHelperLogger(data.responseMessage);
                pcHelperLogger("In checkAuthentication failure");
                eraseCookie("chatAuth");
                auth = 'false';
            }
        }
    });
    return auth;
}

function logoutChat() {
    pcHelperLogger("In logout chat function")
        //converse.user.logout();
    strophieWrapper.disconnect();
    eraseCookie("chatAuth");
}
/*invokePluginReceivedMsgHandler
 * invokes msg handler function of plugin
 *@params :msgObj
 */
function invokePluginReceivedMsgHandler(msgObj) {
    if (typeof msgObj["from"] != "undefined") {
        if (typeof msgObj["body"] != "undefined" && msgObj["body"] != "" && msgObj["body"] != null) {
            pcHelperLogger("invokePluginReceivedMsgHandler-handle message");
            pcHelperLogger(msgObj);
            objJsChat._appendRecievedMessage(msgObj["body"], msgObj["from"], msgObj["msg_id"]);
        }
        if (typeof msgObj["msg_state"] != "undefined") switch (msgObj['msg_state']) {
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
                pcHelperLogger("send received read status to " + msgObj["to"] + " from " + msgObj["from"] + "-" + msgObj["msg_id"]);
                strophieWrapper.sendReceivedReadEvent(msgObj["from"], msgObj["to"], msgObj["msg_id"], strophieWrapper.msgStates["RECEIVER_RECEIVED_READ"]);
                break;
            case strophieWrapper.msgStates["SENDER_RECEIVED_READ"]:
                pcHelperLogger("received received read status to " + msgObj["to"] + " from " + msgObj["from"] + "-" + msgObj["msg_id"]);
                objJsChat._changeStatusOfMessg(msgObj["msg_id"], msgObj["from"], "recievedRead");
                break;
            }
            /*if(msgObj['msg_state'] == "received"){
                objJsChat._changeStatusOfMessg(msgObj["receivedId"],msgObj["from"],"recievedRead");
            }*/
    }
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
    if (imageUrl) {
        var user = JSON.parse(imageUrl);
        user = user['user'];
        if (user == loggedInJspcUser) {
            flag = false;
        }
    }
    if (flag) {
        $.ajax({
            url: "/api/v1/social/getMultiUserPhoto?photoType=ProfilePic120Url",
            async: false,
            success: function (data) {
                if (data.statusCode == "0") {
                    imageUrl = data.profiles[0].PHOTO.ProfilePic120Url;
                    localStorage.setItem('userImg', JSON.stringify({
                        'userImg': imageUrl,
                        'user': loggedInJspcUser
                    }));
                }
            }
        });
    }
    return imageUrl;
}
/*
 * Clear local storage
 */
function clearLocalStorage() {
    var removeArr = ['userImg'];
    $.each(removeArr, function (key, val) {
        localStorage.removeItem(val);
    });
}
/*hit api for chat before acceptance
 * @input: apiParams
 * @output: response
 */
function handlePreAcceptChat(apiParams) {
    pcHelperLogger(apiParams);
    var outputData = {};
    if (typeof apiParams != "undefined") {
        var postData = "";
        if (typeof apiParams["postParams"] != "undefined" && apiParams["postParams"]) {
            postData = apiParams["postParams"];
        }
        pcHelperLogger("postData");
        pcHelperLogger(postData);
        $.myObj.ajax({
            url: apiParams["url"],
            dataType: 'json',
            type: 'POST',
            data: postData,
            timeout: 60000,
            cache: false,
            async: false,
            beforeSend: function (xhr) {},
            success: function (response) {
                pcHelperLogger("in success of handlePreAcceptanceMsg");
                pcHelperLogger(response);
                outputData["canSend"] = response["cansend"];
                outputData["errorMsg"] = response["message"];
                outputData["msg_id"] = strophieWrapper.getUniqueId();
            },
            error: function (xhr) {
                pcHelperLogger("in error of handlePreAcceptanceMsg");
                pcHelperLogger(xhr);
                outputData["canSend"] = false;
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
    pcHelperLogger("@@1");
    if (data.buttondetails && data.buttondetails.button) {
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
$(document).ready(function () {
    pcHelperLogger("User");
    pcHelperLogger(loggedInJspcUser);
    checkNewLogin(loggedInJspcUser);
    var checkDiv = $("#chatOpenPanel").length;
    if (showChat && (checkDiv != 0)) {
        var chatLoggedIn = readCookie('chatAuth');
        var loginStatus;
        if (chatLoggedIn == 'true') {
            checkAuthentication();
            loginStatus = "Y";
            initiateChatConnection();
        } else {
            loginStatus = "N";
        }
        imgUrl = getProfileImage();
        objJsChat = new JsChat({
            loginStatus: loginStatus,
            mainID: "#chatOpenPanel",
            //profilePhoto: "<path>",
            imageUrl: imgUrl,
            profileName: "bassi",
            listingTabs: chatConfig.Params[device].listingTabs,
            rosterDetailsKey: strophieWrapper.rosterDetailsKey,
            listingNodesLimit: chatConfig.Params[device].groupWiseNodesLimit,
            groupBasedChatBox: chatConfig.Params[device].groupBasedChatBox,
            contactStatusMapping: chatConfig.Params[device].contactStatusMapping
        });
        objJsChat.onEnterToChatPreClick = function () {
            //objJsChat._loginStatus = 'N';
            pcHelperLogger("Checking variable");
            pcHelperLogger(chatLoggedIn);
            var chatLoggedIn = readCookie('chatAuth');
            if (chatLoggedIn != 'true') {
                var auth = checkAuthentication();
                if (auth != "true") {
                    pcHelperLogger("Before return");
                    return;
                } else {
                    pcHelperLogger("Initiate strophe connection in preclick");
                    initiateChatConnection();
                    objJsChat._loginStatus = 'Y';
                }
            }
            pcHelperLogger("In callback");
        }
        objJsChat.onChatLoginSuccess = function () {
            //trigger list creation
            pcHelperLogger("in triggerBindings");
            strophieWrapper.triggerBindings();
            //setCreateListingInterval();
        }
        objJsChat.onHoverContactButtonClick = function (params) {
                pcHelperLogger(params);
                checkSum = $("#" + params.id).attr('data-pchecksum');
                paramsData = $("#" + params.id).attr('data-params');
                checkSum = "802d65a19583249de2037f9a05b2e424i6341959";
                idBeforeSplit = params.id.split('_');
                idAfterSplit = idBeforeSplit[0];
                action = idBeforeSplit[1];
                url = chatConfig.Params["actionUrl"][action];
                $.ajax({
                    type: 'POST',
                    data: {
                        profilechecksum: checkSum,
                        params: paramsData,
                        source: "chat"
                    },
                    url: url,
                    success: function (data) {
                        handleErrorInHoverButton(idAfterSplit, data);
                    }
                });
            }
            /*executed on click of contact engine buttons in chat box
             */
        objJsChat.onChatBoxContactButtonsClick = function (params) {
                if (typeof params != "undefined" && params) {
                    var userId = params["receiverID"];
                    switch (params["buttonType"]) {
                    case "INITIATE":
                        //TODO: fire query to send interest              
                        break;
                    case "ACCEPT":
                        //TODO: fire query to accept interest
                        break;
                    case "DECLINE":
                        //TODO: fire query to decline interest
                        break;
                    case "CANCEL":
                        //TODO: fire query to cancel interest
                        break;
                    }
                    return true;
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
        objJsChat.onLogoutPreClick = function () {
                pcHelperLogger("In Logout preclick");
                objJsChat._loginStatus = 'N';
                clearLocalStorage();
                strophieWrapper.disconnect();
                eraseCookie("chatAuth");
            }
            //executed for sending chat message
        objJsChat.onSendingMessage = function (message, receivedJId, receiverProfileChecksum, contact_state) {
            pcHelperLogger("in start of SendingMessage");
            var output;
            if (chatConfig.Params[device].contactStatusMapping[contact_state]["enableChat"] == true) {
                if (chatConfig.Params[device].contactStatusMapping[contact_state]["useOpenfireForChat"] == true) {
                    pcHelperLogger("sending post acceptance msg");
                    output = strophieWrapper.sendMessage(message, receivedJId);
                    pcHelperLogger("sent post acceptance msg");
                } else {
                    pcHelperLogger("sending pre acceptance msg with " + contact_state);
                    var apiParams = {
                        "url": chatConfig.Params[device].preAcceptChat["apiUrl"],
                        "postParams": {
                            "profilechecksum": "4ddba5c85d628cf4faaaca776540cb1ei7575569", //receiverProfileChecksum
                            "chatMessage": message
                        }
                    };
                    if (typeof chatConfig.Params[device].preAcceptChat["extraParams"] != "undefined") {
                        pcHelperLogger("adding tracking in api inputs");
                        pcHelperLogger(chatConfig.Params[device].preAcceptChat["extraParams"]);
                        $.each(chatConfig.Params[device].preAcceptChat["extraParams"], function (key, val) {
                            apiParams["postParams"][key] = val;
                        });
                    }
                    pcHelperLogger("apiParams");
                    pcHelperLogger(apiParams);
                    output = handlePreAcceptChat(apiParams);
                    pcHelperLogger("sent pre acceptance msg");
                }
            } else {
                output = {};
                output["errorMsg"] = "You are not allowed to chat";
                output["canSend"] = false;
            }
            pcHelperLogger(output);
            pcHelperLogger("end of onSendingMessage");
            return output;
        }
        objJsChat.onPostBlockCallback = function (param) {
            pcHelperLogger('the user id to be blocked:' + param);
            //the function goes here which will send user id to the backend
        }
        objJsChat.onPreHoverCallback = function (pCheckSum, username, hoverNewTop, shiftright) {
            pcHelperLogger("In Helper preHoverCB");
            pcHelperLogger(pCheckSum);
            jid = [];
            jid[0] = "'" + pCheckSum + "'";
            url = "/api/v1/chat/fetchVCard";
            $.ajax({
                type: 'POST',
                async: false,
                data: {
                    jid: jid,
                    username: username
                },
                url: url,
                success: function (data) {
                    pcHelperLogger(data);
                    objJsChat.updateVCard(data, pCheckSum, function () {
                        $('#' + username + '_hover').css({
                            'top': hoverNewTop,
                            'visibility': 'visible',
                            'right': shiftright
                        });
                        pcHelperLogger("Callback done");
                    });
                }
            });
        }
        objJsChat.start();
    }
});