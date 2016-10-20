var messageBinding;
var strophieWrapper = {
    connectionObj: null,
    Roster: {},
    NonRoster: {},
    initialRosterFetched: false,
    initialNonRosterFetched: false,
    nonRosterClearInterval:{},
    rosterDetailsKey: "rosterDetails",
    useLocalStorage: false,
    msgStates: {
        "INACTIVE": 'inactive',
        "ACTIVE": 'active',
        "COMPOSING": 'composing',
        "PAUSED": 'paused',
        "GONE": 'gone',
        "RECEIVED": 'received',
        "SENDER_RECEIVED_READ": 'sender_received_read',
        "RECEIVER_RECEIVED_READ": 'receiver_received_read',
        "FORWARDED": 'forwarded'
    },
    rosterGroups: chatConfig.Params.pc.rosterGroups,
    currentConnStatus: null,
    loggingEnabledStrophe: false,
    tryReconnection: true,
    syncMessageForSessions: true,
    synchronize_selfPresence: true,
    stropheLoggerPC: function (msgOrObj) {
        if (strophieWrapper.loggingEnabledStrophe) {
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
                console.log(' ');
                console.warn('Console log: ' + caller + ' ( line ' + line + ' )');
                console.log(msgOrObj);
                console.log({
                    'Full trace:': fullTrace
                });
                console.log(' ');
            } else {
                //shout('This browser does not support console.log!')
            }
        }
    },
    // stropheLoggerPC: function (message) {
    //     if (strophieWrapper.loggingEnabledStrophe) {
    //         console.log(message);
    //     }
    // },
    //connect to openfire
    connect: function (bosh_service_url, username, password) {
        strophieWrapper.connectionObj = new Strophe.Connection(chatConfig.Params[device].bosh_service_url);
        strophieWrapper.connectionObj.connect(username, password, strophieWrapper.onConnect);
        //strophieWrapper.stropheLoggerPC("Openfire wrapper");
    },
    getCurrentConnStatus: function () {
        return (strophieWrapper.currentConnStatus == Strophe.Status.CONNECTED);
    },
    //reconnect to openfire
    reconnect: function (bosh_service_url, username, password) {
        if (strophieWrapper.tryReconnection == true) {
            strophieWrapper.disconnect();
            //reconnect to chat if net connected
            strophieWrapper.connect(chatConfig.Params[device].bosh_service_url, username, pass);
        }
    },
    //executed after connection done
    onConnect: function (status) {
        strophieWrapper.currentConnStatus = status;
        //strophieWrapper.stropheLoggerPC("In onConnect function");
        if (status == Strophe.Status.CONNECTING) {
            //strophieWrapper.stropheLoggerPC("Connecting");
        } else if (status == Strophe.Status.CONNFAIL) {
            //strophieWrapper.stropheLoggerPC("CONNFAIL");
            $('#connect').get(0).value = 'connect';
        } else if (status == Strophe.Status.DISCONNECTING) {
            //strophieWrapper.stropheLoggerPC("DISCONNECTING");
        } else if (status == Strophe.Status.DISCONNECTED) {
            //strophieWrapper.stropheLoggerPC("DISCONNECTED");
            $('#connect').get(0).value = 'connect';
        } else if (status == Strophe.Status.AUTHFAIL) {
            //strophieWrapper.stropheLoggerPC("AUTHFAIL");
            invokePluginLoginHandler("failure");
        } else if (status == Strophe.Status.CONNECTED) {
            //strophieWrapper.stropheLoggerPC("CONNECTED");
            invokePluginLoginHandler("success");
        }
    },
    //trigger bindings
    triggerBindings: function () {
        //strophieWrapper.Roster = [];
        //send own presence
        if(strophieWrapper.syncMessageForSessions == true){
        	strophieWrapper.enableCarbons();
        }
        strophieWrapper.sendPresence();
        //fetch roster of logged in user 
        if (strophieWrapper.initialRosterFetched == false) {
            strophieWrapper.getRoster();
        }
        //binding event for presence update in roster
        strophieWrapper.connectionObj.addHandler(strophieWrapper.onPresenceReceived, null, 'presence', null);
        //binding event for message receive event
        /*
 	if(messageBinding)
            strophieWrapper.connectionObj.deleteHandler(messageBinding);
        messageBinding = 
	*/
	strophieWrapper.connectionObj.addHandler(strophieWrapper.onMessage, null, 'message', null, null, null);
        //binding event for new node push in roster
        strophieWrapper.connectionObj.addHandler(strophieWrapper.onRosterUpdate, Strophe.NS.ROSTER, 'iq', 'set');
        //binding event for message receipts
        //strophieWrapper.connectionObj.addHandler(strophieWrapper.onMessageReceipt, Strophe.NS.RECEIPTS, 'iq', 'set');
    },
    /*
     * On message receipt
     */
    /*onMessageReceipt: function (msg) {
    	console.log("on message request");
    	console.log(msg);
        //strophieWrapper.stropheLoggerPC("In message receipt handler");
        strophieWrapper.stropheLoggerPC(msg);
    },*/
    //send presence
    sendPresence: function () {
        if (strophieWrapper.getCurrentConnStatus()) {
            //strophieWrapper.stropheLoggerPC("in self sendPresence");
            strophieWrapper.connectionObj.send($pres().tree());
        } else {
            handleChatDisconnection();
        }
    },

    //fetch roster
    getRoster: function () {
        //kills interval polling for non roster list
        clearNonRosterPollingInterval();
        if (strophieWrapper.getCurrentConnStatus()) {
            var iq = $iq({
                type: 'get'
            }).c('query', {
                xmlns: Strophe.NS.ROSTER
            });
            strophieWrapper.connectionObj.sendIQ(iq, strophieWrapper.onRosterReceived);
        } else {
            handleChatDisconnection();
        }
    },
    //executed on new push/remove event in roster
    onRosterUpdate: function (iq) {
       //console.log("onRosterUpdate");
        //console.log(iq);
        //strophieWrapper.stropheLoggerPC(iq);
        var nodeObj = xmlToJson(iq);
        rosterObj = strophieWrapper.formatRosterObj(nodeObj["query"]["item"]);
        //strophieWrapper.stropheLoggerPC(rosterObj);
        var nodeArr = [],
            user_id = rosterObj[strophieWrapper.rosterDetailsKey]["jid"].split("@")[0],
            subscription = rosterObj[strophieWrapper.rosterDetailsKey]["subscription"],
            ask = rosterObj[strophieWrapper.rosterDetailsKey]["ask"];
        nodeArr[user_id] = rosterObj;
        if (strophieWrapper.checkForGroups(rosterObj[strophieWrapper.rosterDetailsKey]["groups"]) == true) {
            //nodeArr[user_id] = rosterObj;
            //strophieWrapper.stropheLoggerPC(nodeArr);
            //strophieWrapper.stropheLoggerPC(ask);
            if(typeof subscription == "undefined" || subscription != "remove"){
                if (ask == "unsubscribe") {
                    //console.log("got unsubscribe ask");
                    //strophieWrapper.stropheLoggerPC(strophieWrapper.Roster[user_id]);
                    //strophieWrapper.stropheLoggerPC("deleting node");
                    invokePluginManagelisting(nodeArr, "delete_node", user_id);
                    //console.log(strophieWrapper.Roster);
                    try{
                        delete strophieWrapper.Roster[user_id];
                        //var return1 = strophieWrapper.Roster.splice(user_id,1);
                        //console.log(return1);
                    }
                    catch(e){
                        //console.log(e);
                    }
                    //strophieWrapper.unauthorize(rosterObj[strophieWrapper.rosterDetailsKey]["jid"]);
                } else if (strophieWrapper.checkForSubscription(subscription) == true) {
                    //strophieWrapper.stropheLoggerPC("adding node");
                    //strophieWrapper.stropheLoggerPC(subscription);
                    //console.log("add node case");
                    if (typeof strophieWrapper.Roster[user_id] == "undefined" || typeof strophieWrapper.Roster[user_id][strophieWrapper.rosterDetailsKey]["subscription"]=="undefined") {
                        //console.log("adding new1");
                        invokePluginManagelisting(nodeArr, "add_node", user_id);
                    } else if (typeof strophieWrapper.Roster[user_id][strophieWrapper.rosterDetailsKey]["groups"] != "undefined") {
                        var oldGroupId = strophieWrapper.Roster[user_id][strophieWrapper.rosterDetailsKey]["groups"][0];
                        if (typeof oldGroupId == "undefined" || (oldGroupId && oldGroupId != rosterObj[strophieWrapper.rosterDetailsKey]["groups"][0])) {
                            var oldArr = [];
                            //console.log("adding new2");
                            oldArr[user_id] = strophieWrapper.Roster[user_id];
                            //strophieWrapper.stropheLoggerPC("moving node from " + oldGroupId);
                            if(typeof oldGroupId != "undefined"){
                                invokePluginManagelisting(oldArr, "delete_node", user_id);
                            }
                            //strophieWrapper.stropheLoggerPC("adding node");
                            //strophieWrapper.stropheLoggerPC(nodeArr);
                            //console.log("adding new 2");
                            invokePluginManagelisting(nodeArr, "add_node", user_id);
                        }
                    }
                    strophieWrapper.Roster[user_id] = rosterObj;
                    if (subscription == "to") {
                       //console.log("subcribing");
                        strophieWrapper.subscribe(rosterObj[strophieWrapper.rosterDetailsKey]["jid"], rosterObj[strophieWrapper.rosterDetailsKey]["nick"]);
                    }
                    setTimeout(function () {
//console.log("sent self presence");                        
strophieWrapper.sendPresence();
                    }, 5000);
                }
            }
            else if(subscription == "remove"){
                //console.log("got remove subscription 1",rosterObj);
                if(typeof strophieWrapper.Roster[user_id]!= "undefined"){
                    nodeArr[user_id] = strophieWrapper.Roster[user_id];
                    if (strophieWrapper.checkForGroups(nodeArr[user_id][strophieWrapper.rosterDetailsKey]["groups"]) == true) {
                        //console.log("removed..");
                        invokePluginManagelisting(nodeArr, "delete_node", user_id);
                        delete strophieWrapper.Roster[user_id];
                        //var return2 = strophieWrapper.Roster.splice(user_id,1);
                    }
                } 
                //case of remove subscription with group
            }
        }
        else if(subscription == "remove"){
            //console.log("got remove subscription 2",rosterObj);
            if(typeof strophieWrapper.Roster[user_id]!= "undefined"){
                nodeArr[user_id] = strophieWrapper.Roster[user_id];
                if (strophieWrapper.checkForGroups(nodeArr[user_id][strophieWrapper.rosterDetailsKey]["groups"]) == true) {
                    //console.log("removed..");
                    invokePluginManagelisting(nodeArr, "delete_node", user_id);
                    delete strophieWrapper.Roster[user_id];
                    //var return3 = strophieWrapper.Roster.splice(user_id,1);
                }
            }  
        }
        strophieWrapper.connectionObj.addHandler(strophieWrapper.onRosterUpdate, Strophe.NS.ROSTER, 'iq', 'set');
        //return true;
    },
    //subscribe user in roster for presence updates
    subscribe: function (jid, nick, message) {
        if (strophieWrapper.getCurrentConnStatus()) {
            var pres = $pres({
                to: jid,
                type:"subscribe"
            });
            if (message && message !== "") {
                pres.c("status").t(message).up();
            }
            if (nick && nick !== "") {
                pres.c('nick', {
                    'xmlns': Strophe.NS.NICK
                }).t(nick).up();
            }
            //strophieWrapper.stropheLoggerPC("subscribing -" + jid + "with nick " + nick);
            strophieWrapper.stropheLoggerPC(pres);
            strophieWrapper.connectionObj.send(pres);
        } else {
            handleChatDisconnection();
        }
    },
    //authorize user on getting subscribe request
    authorize: function (jid, message) {
        if (strophieWrapper.getCurrentConnStatus()) {
            //strophieWrapper.stropheLoggerPC("authorizing - " + jid);
            var pres = $pres({
                to: jid,
                type: "subscribed"
            });
            if (message && message != "") {
                pres.c("status").t(message);
            }
            strophieWrapper.connectionObj.send(pres);
        } else {
            handleChatDisconnection();
        }
    },

    /** Function: unauthorize
     * Unauthorize presence subscription
     *
     * Parameters:jid,message
     */
    unauthorize: function(jid, message)
    {
        if (strophieWrapper.getCurrentConnStatus()) {
            var pres = $pres({to: jid, type: "unsubscribed"});
            if(message && message != ""){
                pres.c("status").t(message);
            }
            strophieWrapper.connectionObj.send(pres);
        }
        else{
            handleChatDisconnection();
        }
    },

    enableCarbons: function () {
        /*if (!this.message_carbons) {
            return;
        }*/
        var carbons_iq = new Strophe.Builder('iq', {
            from: strophieWrapper.getSelfJID(),
            id: 'enablecarbons',
            type: 'set'
        }).c('enable', {
            xmlns: Strophe.NS.CARBONS
        });
        strophieWrapper.connectionObj.addHandler(function (iq) {
            if ($(iq).find('error').length > 0) {
                //console.log("error in carbons");
            } else {
                //console.log("carbons enabled");
            }
        }.bind(this), null, "iq", null, "enablecarbons");
        strophieWrapper.connectionObj.send(carbons_iq);
    },
    //executed after presence has been fetched
    onPresenceReceived: function (presence) {
       //console.log("onPresenceReceived from- ",$(presence).attr('from'));
       //console.log(presence);
        var presence_type = $(presence).attr('type'),
            chat_status = "offline"; // unavailable, subscribed, etc...
        var from = $(presence).attr('from'),
            user_id = from.split("@")[0]; // the jabber_id of the contact
        //console.log(presence);
        if (presence_type != 'error') {
            if (presence_type === 'unavailable') {
                chat_status = "offline";
            } else {
                var show = $(presence).find("show").text(); // this is what gives away, dnd, etc.
                if (show === 'chat' || show === '') {
                    chat_status = "online";
                    //strophieWrapper.sendPresence();
                } else {
                    // etc...
                }
            }
        }
        //console.log("RECEIVED presence for "+from+"-"+chat_status);
        if (strophieWrapper.isItSelfUser(user_id) == false) {
            //strophieWrapper.stropheLoggerPC("start of onPresenceReceived for " + user_id);
            //strophieWrapper.stropheLoggerPC(from);
            if (presence_type != 'error') {
//console.log("authorizing",presence_type);
	            //strophieWrapper.stropheLoggerPC(presence);
	            //strophieWrapper.authorize(from.split("/")[0]);
	//            strophieWrapper.authorize(from);

	if(presence_type == "subscribe"){
//console.log("sent presence again");
strophieWrapper.authorize(from);
strophieWrapper.sendPresence();
}
	            strophieWrapper.updatePresence(user_id, chat_status);
        	}
            //strophieWrapper.stropheLoggerPC("end of onPresenceReceived for " + user_id + "---" + chat_status);
            //strophieWrapper.stropheLoggerPC(strophieWrapper.Roster[user_id]);
        } else {
            if (strophieWrapper.synchronize_selfPresence == true) {
                if (from != strophieWrapper.getSelfJID()) {
                    //console.log("updating self presence for different resource - " + from + chat_status);
                    /*if (chat_status == "offline") {
                        console.log("logout");
                        invokePluginLoginHandler("logout");
                    }*/
                    /*else if(chat_status == "online"){
                        console.log("login");
                        invokePluginLoginHandler("login");
                    }*/
                }
            }
        }
        return true;
    },
    //update chat_status of roster items
    updatePresence: function (user_id, chat_status) {
        //strophieWrapper.stropheLoggerPC("start of updatePresence");
        var updatedObj = {
            "chat_status": chat_status
        };
        if (chat_status == "online") {
            updatedObj["last_online_time"] = new Date();
        }
        strophieWrapper.Roster[user_id] = strophieWrapper.mergeRosterObj(strophieWrapper.Roster[user_id], strophieWrapper.mapRosterObj(updatedObj));
        if (strophieWrapper.initialRosterFetched == true) {
            //strophieWrapper.stropheLoggerPC("change in status after initialRosterFetched done for " + user_id);
            //strophieWrapper.stropheLoggerPC(strophieWrapper.Roster[user_id]);
            var nodeArr = [];
            nodeArr[user_id] = strophieWrapper.Roster[user_id];
            //strophieWrapper.stropheLoggerPC(nodeArr);
            invokePluginManagelisting(nodeArr, "update_status", user_id);
        }
    },
    //check if this userid is self id
    isItSelfUser: function (user_id) {
        if (user_id == strophieWrapper.getSelfJID().split("@")[0]) {
            return true;
        } else {
            return false;
        }
    },

    //executed after non-roster list has been fetched
    onNonRosterListFetched: function(response,groupid){
        console.log("in onNonRosterListFetched");
        if(response["data"] != undefined && response["data"].length > 0){
            $.each(response["data"],function(key,nodeObj){
                nodeObj["groupid"] = groupid;
                if (strophieWrapper.isItSelfUser(nodeObj["profileid"]) == false) {
                    var listObj = strophieWrapper.formatNonRosterObj(nodeObj);
                    if (strophieWrapper.checkForGroups(listObj[strophieWrapper.rosterDetailsKey]["groups"]) == true && strophieWrapper.Roster[nodeObj["profileid"]] == undefined){
                        strophieWrapper.NonRoster[nodeObj["profileid"]] = strophieWrapper.mergeRosterObj(strophieWrapper.NonRoster[nodeObj["profileid"]], listObj);
                    }
                }
                //console.log("converted",strophieWrapper.Roster[nodeObj["profileid"]]);
            });
            console.log("adding",strophieWrapper.NonRoster);
            strophieWrapper.initialNonRosterFetched = true;
            invokePluginManagelisting(strophieWrapper.NonRoster, "create_list");
        }
    },

    //executed after roster has been fetched
    onRosterReceived: function (iq) {
        //console.log("in onRosterReceived");
        //console.log(iq);
        $(iq).find("item").each(function () {
            var subscription = $(this).attr("subscription"),
                jid = $(this).attr("jid"),
                user_id = jid.split("@")[0];
            if (strophieWrapper.checkForSubscription(subscription) == true && strophieWrapper.isItSelfUser(user_id) == false) {
                var listObj = strophieWrapper.formatRosterObj(xmlToJson(this)),
                    status = "offline",
                    last_online_time = null;
                if (strophieWrapper.checkForGroups(listObj[strophieWrapper.rosterDetailsKey]["groups"]) == true) {
                    if (typeof strophieWrapper.Roster[user_id] !== "undefined") {
                        status = strophieWrapper.Roster[user_id][strophieWrapper.rosterDetailsKey]["chat_status"];
                        last_online_time = strophieWrapper.Roster[user_id][strophieWrapper.rosterDetailsKey]["last_online_time"];
                    }
                    listObj[strophieWrapper.rosterDetailsKey]["chat_status"] = status;
                    listObj[strophieWrapper.rosterDetailsKey]["last_online_time"] = last_online_time;
                    strophieWrapper.Roster[user_id] = strophieWrapper.mergeRosterObj(strophieWrapper.Roster[user_id], listObj);
                    if (subscription == "to") {
                    	//console.log("subscribe to -"+jid);
                        strophieWrapper.subscribe(jid, listObj[strophieWrapper.rosterDetailsKey]["nick"]);
                    }
                }
            }
        });
        //strophieWrapper.stropheLoggerPC("end of onRosterReceived");
        //strophieWrapper.stropheLoggerPC(strophieWrapper.Roster);
        //strophieWrapper.stropheLoggerPC("setting roster fetched flag");
        strophieWrapper.initialRosterFetched = true;
        //strophieWrapper.connectionObj.addHandler(strophieWrapper.onPresenceReceived, null, 'presence', null);
        invokePluginManagelisting(strophieWrapper.Roster, "create_list");
        strophieWrapper.setRosterStorage(strophieWrapper.Roster);
        setTimeout(function () {
          strophieWrapper.sendPresence();
        }, 1000);
        strophieWrapper.connectionObj.addHandler(strophieWrapper.onPresenceReceived, null, 'presence', null);
    	//strophieWrapper.sendPresence();

        //start for polling of non-roster group listings --onrosterreceived or there later
        strophieWrapper.getNonRosterList();
            
   	},

    //fetch non roster list
    getNonRosterList:function(){
        $.each(chatConfig.Params.nonRosterPollingGroups,function(key,groupId){
            //console.log("fetchNonRosterListing",chatConfig.Params.nonRosterListingApiConfig[groupId]["pollingFreq"]);
            fetchNonRosterListing(groupId);
        });
    },

    //executed on msg receipt
    onMessage: function (iq) {
        //strophieWrapper.stropheLoggerPC("got message");
        //console.log("in onMessage");
        //console.log(iq);
        //strophieWrapper.stropheLoggerPC(iq);
        var msgObject = strophieWrapper.formatMsgObj(iq);
        //strophieWrapper.stropheLoggerPC(msgObject);
        //if(msgObject["msg_state"] == strophieWrapper.msgStates["FORWARDED"] && getSelfJID() != msgObject[]
        invokePluginReceivedMsgHandler(msgObject);
        return true;
    },
    //parser for roster object
    formatRosterObj: function (obj) {
        var listing_tuple_photo = "";
        if (loggedInJspcGender) {
            if (loggedInJspcGender == "M") {
                listing_tuple_photo = chatConfig.Params[device].noPhotoUrl["listingTuple"]["F"];
            } else if (loggedInJspcGender == "F") {
                listing_tuple_photo = chatConfig.Params[device].noPhotoUrl["listingTuple"]["M"];
            }
        }
        //strophieWrapper.stropheLoggerPC("in formatRosterObj");
        var chat_status = obj["attributes"]["chat_status"] || "offline",
            newObj = {};
        var fullname = "";
        if (typeof obj["attributes"]["name"] != "undefined") {
            fullname = obj["attributes"]["name"].split("|");
        }
        newObj[strophieWrapper.rosterDetailsKey] = {
            "jid": obj["attributes"]["jid"],
            "chat_status": chat_status,
            "nick": fullname,
            "fullname": fullname[0],
            "groups": [],
            "subscription": obj["attributes"]["subscription"],
            "profile_checksum": fullname[1],
            "listing_tuple_photo": listing_tuple_photo,
            "last_online_time": null,
            "ask": obj["attributes"]["ask"]
        };
        if (typeof obj["group"] != "undefined") {
            newObj[strophieWrapper.rosterDetailsKey]["groups"].push(obj["group"]["#text"]);
        }
        return newObj;
    },

    //parser for non roster object
    formatNonRosterObj: function (obj) {
        var listing_tuple_photo = "";
        if (loggedInJspcGender) {
            if (loggedInJspcGender == "M") {
                listing_tuple_photo = chatConfig.Params[device].noPhotoUrl["listingTuple"]["F"];
            } else if (loggedInJspcGender == "F") {
                listing_tuple_photo = chatConfig.Params[device].noPhotoUrl["listingTuple"]["M"];
            }
        }
        //strophieWrapper.stropheLoggerPC("in formatRosterObj");
        var chat_status = obj["chat_status"] || "online",
            newObj = {};
        newObj[strophieWrapper.rosterDetailsKey] = {
            "jid": obj["profileid"]+"@"+openfireServerName,
            "chat_status": chat_status,
            "nick": obj["username"]+"|"+obj["profileChecksum"],
            "fullname": obj["username"],
            "groups": [],
            "subscription": "both",
            "profile_checksum": obj["profileChecksum"],
            "listing_tuple_photo": listing_tuple_photo,
            "last_online_time": new Date(),
            "ask": null
        };
        if (typeof obj["groupid"] != "undefined") {
            newObj[strophieWrapper.rosterDetailsKey]["groups"].push(obj["groupid"]);
        }
        return newObj;
    },
    //merge second roster obj to first one
    mergeRosterObj: function (obj1, obj2) {
        if (typeof obj1 == "undefined") {
            obj1 = {};
            obj1[strophieWrapper.rosterDetailsKey] = {};
        }
        if (typeof obj2 !== "undefined") {
            $.each(obj2[strophieWrapper.rosterDetailsKey], function (key, val) {
                obj1[strophieWrapper.rosterDetailsKey][key] = val;
            });
        }
        return obj1;
    },
    //map input object to roster object
    mapRosterObj: function (inputObj) {
        var outputObj = {};
        outputObj[strophieWrapper.rosterDetailsKey] = {};
        if (typeof inputObj !== "undefined") {
            $.each(inputObj, function (key, val) {
                outputObj[strophieWrapper.rosterDetailsKey][key] = val;
            });
        }
        return outputObj;
    },
    //get self jid of connected user
    getSelfJID: function (splitBySlash) {
        var jid = strophieWrapper.connectionObj.jid || null;
        if (jid != null && splitBySlash == true) {
            jid = jid.split("/")[0];
        }
        return jid;
    },
    //set listing data in roster
    setRosterStorage: function (rosterData) {
        if (strophieWrapper.useLocalStorage == true) {
            localStorage.setItem('chatListing', JSON.stringify(rosterData));
        }
    },
    //fetch roster data from localstorage
    getRosterStorage: function () {
        var data;
        if (strophieWrapper.useLocalStorage == true) {
            data = JSON.parse(localStorage.getItem('chatListing'));
        } else data = null;
        return data;
    },
    //check for subscription of user
    checkForSubscription: function (subscription) {
        if (subscription == "to" || subscription == "both") {
            return true;
        } else {
            return false;
        }
    },
    //check for groups of user
    checkForGroups: function (groupArr) {
        if (typeof groupArr == "undefined" || groupArr.length == 0) {
            return false;
        } else {
            $.each(groupArr, function (index, val) {
                if (strophieWrapper.rosterGroups.indexOf(val) == -1) {
                    return false;
                }
            });
            return true;
        }
    },
    //sending Message
    sendMessage: function (message, to,is_eoi,msg_id) {
	/*
  	if(messageBinding)
            strophieWrapper.connectionObj.deleteHandler(messageBinding);
        messageBinding = strophieWrapper.connectionObj.addHandler(strophieWrapper.onMessage, null, 'message', null, null, null);
	*/

        var outputObj,messageId;
        try {
            if (message && to && strophieWrapper.getCurrentConnStatus()) {
                if(typeof msg_id!= "undefined"){
                    messageId = msg_id;
                }
                else{
            	   messageId = strophieWrapper.connectionObj.getUniqueId();
                }
                //console.log("sent",messageId);
                var reply,msg_type;
                if(typeof is_eoi!= "undefined" && is_eoi == true){
                   msg_type = "eoi"; 
                }
                else{
                    msg_type = "accept";
                }
                
                reply = $msg({
                    from: strophieWrapper.getSelfJID(),
                    to: to,
                    type: 'chat',
                    id:messageId
                    }).cnode(Strophe.xmlElement('msg_type', msg_type)).up().cnode(Strophe.xmlElement('body', message)).up().c('active', {
                        xmlns: "http://jabber.org/protocol/chatstates"
                });
                //console.log(reply);
                strophieWrapper.connectionObj.send(reply);
                if (strophieWrapper.syncMessageForSessions == true) {
                    // Forward the message, so that other connected resources are also aware of it.
                    //append it as self sent message
                    /* setTimeout(function(){
                        console.log("sending msg forward");
                        strophieWrapper.connectionObj.send(
                         $msg({ to: strophieWrapper.getSelfJID(true), type: 'chat', id: messageId })
                             .c('forwarded', {xmlns:'urn:xmpp:forward:0'})
                             .cnode(reply.tree()));
                        //console.log("sent forwarded msg"+messageId);
                     },2000);*/
                }
                outputObj = {
                    "msg_id": messageId,
                    "cansend": true,
                    "sent": true
                };
                return outputObj;
            } else {
                outputObj = {
                    "msg_id": strophieWrapper.getUniqueId(),
                    "cansend": true,
                    "sent": false,
                    "errorMsg": 'You are currently offline, please check your internet connection and try again'
                };
                if (strophieWrapper.getCurrentConnStatus() == false) {
                    handleChatDisconnection();
                }
                return outputObj;
            }
        } catch (e) {
            outputObj = {
                "msg_id": strophieWrapper.getUniqueId(),
                "cansend": true,
                "errorMsg": "Something went wrong",
                "sent": false
            };
        }
        return outputObj;
    },
    getUniqueId: function (suffix) {
        var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            var r = Math.random() * 16 | 0,
                v = c == 'x' ? r : r & 0x3 | 0x8;
            return v.toString(16);
        });
        if (typeof (suffix) == "string" || typeof (suffix) == "number") {
            return uuid + ":" + suffix;
        } else {
            return uuid + "";
        }
    },
    /*format msg object*/
    formatMsgObj: function (msg) {
        //console.log("in formatMsgObj");
        //console.log(msg);
        var outputObj = {
            "from": msg.getAttribute('from').split("@")[0],
            "to": msg.getAttribute('to').split("@")[0],
            "type": msg.getAttribute('type'),
            "msg_id": msg.getAttribute('id')
        };
        var $message = $(msg),
            msg_state;
        if ($message.find(strophieWrapper.msgStates["FORWARDED"]).length != 0) {
            msg_state = strophieWrapper.msgStates["FORWARDED"];
        } else if ($message.find(strophieWrapper.msgStates["COMPOSING"]).length != 0) {
            msg_state = strophieWrapper.msgStates["COMPOSING"];
        } else if ($message.find(strophieWrapper.msgStates["PAUSED"]).length != 0) {
            msg_state = "paused";
        } else if ($message.find(strophieWrapper.msgStates["GONE"]).length != 0) {
            msg_state = strophieWrapper.msgStates["GONE"];
        } else if ($message.find(strophieWrapper.msgStates["ACTIVE"]).length != 0) {
            msg_state = strophieWrapper.msgStates["ACTIVE"];
        } else if ($message.find(strophieWrapper.msgStates["INACTIVE"]).length != 0) {
            msg_state = strophieWrapper.msgStates["INACTIVE"];
        } else if ($message.find(strophieWrapper.msgStates["RECEIVER_RECEIVED_READ"]).length != 0) {
            msg_state = strophieWrapper.msgStates["SENDER_RECEIVED_READ"];
        } else if ($message.find(strophieWrapper.msgStates["RECEIVED"]).length != 0) {
            msg_state = strophieWrapper.msgStates["RECEIVED"];
        }
        //strophieWrapper.stropheLoggerPC("in formatMsgObj");
        //strophieWrapper.stropheLoggerPC(msg_state);
        if (typeof msg_state != "undefined") {
            outputObj["msg_state"] = msg_state;
        }
        //var received = msg.getElementsByTagName(strophieWrapper.msgStates["RECEIVED"]);
        if (msg_state == strophieWrapper.msgStates["FORWARDED"]) {
            var forwardObj = msg.getElementsByTagName(strophieWrapper.msgStates["FORWARDED"]);
            //console.log("in from");
            //console.log(msg);
            var msg1 = forwardObj[0].getElementsByTagName("message");
            //console.log(outputObj);
            outputObj["to"] = msg1[0].getAttribute("to").split("@")[0];
            outputObj["forward_jid"] = msg1[0].getAttribute('from');
            outputObj["msg_id"] = msg1[0].getAttribute("id");
        }
        //strophieWrapper.stropheLoggerPC(received);
        if (outputObj["type"] == "chat") {
            var body = msg.getElementsByTagName("body");
            ////strophieWrapper.stropheLoggerPC(body);
            if (typeof body != "undefined" && body.length > 0) {
                outputObj["body"] = Strophe.getText(body[0]);
                var msg_type = msg.getElementsByTagName("msg_type");
                if(typeof msg_type != "undefined" && msg_type.length > 0){
                    outputObj["msg_type"] = Strophe.getText(msg_type[0]);
                }
                else{
                    outputObj["msg_type"] = null;
                }
            }
            else {
                outputObj["body"] = null;
            }
        } /*else if (msg_state == strophieWrapper.msgStates["RECEIVED"]) {
            var rec = received[0];
            if (typeof rec != "undefined") {
                outputObj["receivedId"] = rec.getAttribute('id');
            }
        }*/
        //console.log(outputObj);
        return outputObj;
    },
    /*
     * Disconnect strophe connection
     */
    disconnect: function () {
        strophieWrapper.connectionObj.disconnect();
    },
    /* addMessageHandler
     ** add a message handler that handles XEP-0184 message receipts
     */
    /*addReceiptHandler: function (handler, type, from, options) {
        var that = this;
        var proxyHandler = function (msg) {
        	console.log("RECEIVED receipt");
        	console.log(msg);
            that._processReceipt(msg);
            // call original handler
            return handler(msg);
        };
        this._conn.addHandler(proxyHandler, Strophe.NS.RECEIPTS, 'message', type, null, from, options);
    },*/
    /*
     * sending typing event
     */
    typingEvent: function (from, to, typingState) {
        if (strophieWrapper.getCurrentConnStatus()) {
            if (from && to && typingState) {
                var id = strophieWrapper.connectionObj.getUniqueId();
                var sendStatus = $msg({
                    from: from,
                    to: to,
                    type: 'chat',
                    id: id,
                }).c(typingState, {
                    xmlns: "http://jabber.org/protocol/chatstates"
                });
                strophieWrapper.connectionObj.send(sendStatus);
            }
        } else {
            handleChatDisconnection();
        }
    },
    /*
     * sending typing event
     */
    sendReceivedReadEvent: function (from, to, msg_id, state) {
        if (strophieWrapper.getCurrentConnStatus()) {
            if (from && to && state) {
                var sendStatus = $msg({
                    from: from,
                    to: to,
                    type: 'chat',
                    id: msg_id
                }).c(state, {
                    xmlns: "http://jabber.org/protocol/chatstates"
                });
                strophieWrapper.connectionObj.send(sendStatus);
            }
        } else {
            handleChatDisconnection();
        }
    },
    //remove user from roster
    removeRosterItem: function (jid) {
        if (strophieWrapper.getCurrentConnStatus()) {
            var user_id = jid.split("@")[0];
            if (typeof strophieWrapper.Roster[user_id] != "undefined") {
                var iq = $iq({
                    type: 'set'
                }).c('query', {
                    xmlns: Strophe.NS.ROSTER
                }).c('item', {
                    jid: jid,
                    subscription: "remove"
                });
                strophieWrapper.connectionObj.sendIQ(iq, function (status) {
                    //strophieWrapper.stropheLoggerPC("Removed stanza: " + jid);
                });
            } else {
                //strophieWrapper.stropheLoggerPC("user does not exist in roster");
            }
        } else {
            handleChatDisconnection();
        }
    },
    //add user in roster
    addRosterItem: function (rosterParams) {
        if (strophieWrapper.getCurrentConnStatus()) {
            if (typeof rosterParams != "undefined") {
                var groups = [];
                groups.push(rosterParams["groupid"]);
                if (typeof groups != "undefined" && strophieWrapper.checkForGroups(groups) == true) {
                    var user_id = rosterParams["jid"].split("@")[0];
                    if (typeof strophieWrapper.Roster[user_id] != "undefined") {
                        var iq = $iq({
                            from: rosterParams["jid"],
                            type: 'set',
                            id: strophieWrapper.getUniqueId('roster')
                        }).c('query', {
                            xmlns: Strophe.NS.ROSTER
                        }).c('item', {
                            jid: rosterParams["jid"],
                            name: rosterParams["nick"],
                            subscription: 'both'
                        });
                        iq.c('group').t(rosterParams["groupid"]).up();
                        //strophieWrapper.stropheLoggerPC("in addRosterItem");
                        //console.log(iq);
                        strophieWrapper.connectionObj.sendIQ(iq, function (status) {
                            //strophieWrapper.stropheLoggerPC("roster adding stanza: "+jid);
                        });
                    } else {
                        //strophieWrapper.stropheLoggerPC("user cannot be addeded in roster");
                    }
                }
            }
        } else {
            handleChatDisconnection();
        }
    }
}
