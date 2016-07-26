var strophieWrapper = {
    connectionObj: null,
    Roster: [],
    initialRosterFetched: false,
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
        "RECEIVER_RECEIVED_READ": 'receiver_received_read'
    },
    rosterGroups: chatConfig.Params.PC.rosterGroups,
    loggingEnabled: true,
    commonLogger: function (message) {
        if (strophieWrapper.loggingEnabled) {
            console.log(message);
        }
    },
    //connect to openfire
    connect: function (bosh_service_url, username, password) {
        strophieWrapper.connectionObj = new Strophe.Connection(chatConfig.Params[device].bosh_service_url);
        strophieWrapper.connectionObj.connect(username, password, strophieWrapper.onConnect);
        strophieWrapper.commonLogger("Openfire wrapper");
        strophieWrapper.commonLogger(username);
        strophieWrapper.commonLogger(password);
    },
    //executed after connection done
    onConnect: function (status) {
        strophieWrapper.commonLogger("In onConnect function");
        if (status == Strophe.Status.CONNECTING) {
            strophieWrapper.commonLogger("Connecting");
        } else if (status == Strophe.Status.CONNFAIL) {
            strophieWrapper.commonLogger("CONNFAIL");
            $('#connect').get(0).value = 'connect';
        } else if (status == Strophe.Status.DISCONNECTING) {
            strophieWrapper.commonLogger("DISCONNECTING");
        } else if (status == Strophe.Status.DISCONNECTED) {
            strophieWrapper.commonLogger("DISCONNECTED");
            $('#connect').get(0).value = 'connect';
        } else if (status == Strophe.Status.AUTHFAIL) {
            strophieWrapper.commonLogger("AUTHFAIL");
            //append login failure panel
            invokePluginLoginHandler("failure");
        } else if (status == Strophe.Status.CONNECTED) {
            strophieWrapper.commonLogger("CONNECTED");
            //append listing panel
            invokePluginLoginHandler("success");
        }
    },
    //trigger bindings
    triggerBindings: function () {
        strophieWrapper.Roster = [];
        //send own presence
        strophieWrapper.sendPresence();
        //fetch roster of logged in user  
        strophieWrapper.getRoster();
        //binding event for presence update in roster
        strophieWrapper.connectionObj.addHandler(strophieWrapper.onPresenceReceived, null, 'presence', null);
        //binding event for message receive event
        strophieWrapper.connectionObj.addHandler(strophieWrapper.onMessage, null, 'message', null, null, null);
        //binding event for new node push in roster
        strophieWrapper.connectionObj.addHandler(strophieWrapper.onRosterUpdate, Strophe.NS.ROSTER, 'iq', 'set');
        //binding event for message receipts
        strophieWrapper.connectionObj.addHandler(strophieWrapper.onMessageReceipt, Strophe.NS.RECEIPTS, 'iq', 'set');
    },
    /*
     * On message receipt
     */
    onMessageReceipt: function (msg) {
        strophieWrapper.commonLogger("In message receipt handler");
        strophieWrapper.commonLogger(msg);
    },
    //send presence
    sendPresence: function () {
        strophieWrapper.commonLogger("in self sendPresence");
        strophieWrapper.connectionObj.send($pres().tree());
    },
    //fetch roster
    getRoster: function () {
        strophieWrapper.commonLogger("in getRoster");
        var iq = $iq({
            type: 'get'
        }).c('query', {
            xmlns: Strophe.NS.ROSTER
        });
        strophieWrapper.connectionObj.sendIQ(iq, strophieWrapper.onRosterReceived);
    },
    //executed on new push/remove event in roster
    onRosterUpdate: function (iq) {
        strophieWrapper.commonLogger("in onRosterPush");
        strophieWrapper.commonLogger(iq);
        var nodeObj = xmlToJson(iq);
        rosterObj = strophieWrapper.formatRosterObj(nodeObj["query"]["item"]);
        strophieWrapper.commonLogger(rosterObj);
        var nodeArr = [],
            user_id = rosterObj[strophieWrapper.rosterDetailsKey]["jid"].split("@")[0],
            subscription = rosterObj[strophieWrapper.rosterDetailsKey]["subscription"],
            ask = rosterObj[strophieWrapper.rosterDetailsKey]["ask"];
        if (strophieWrapper.checkForGroups(rosterObj[strophieWrapper.rosterDetailsKey]["groups"]) == true) {
            nodeArr[user_id] = strophieWrapper.Roster[user_id] = rosterObj;
            strophieWrapper.commonLogger(nodeArr);
            strophieWrapper.commonLogger(ask);
            if (ask == "unsubscribe") {
                strophieWrapper.commonLogger(strophieWrapper.Roster[user_id]);
                strophieWrapper.commonLogger("deleting node");
                invokePluginManagelisting(nodeArr, "delete_node", user_id);
                strophieWrapper.Roster.splice(user_id);
                //unauthorize if required
            } else if (strophieWrapper.checkForSubscription(subscription) == true) {
                strophieWrapper.commonLogger("adding node");
                strophieWrapper.commonLogger(subscription);
                invokePluginManagelisting(nodeArr, "add_node", user_id);
                if (subscription == "to") {
                    strophieWrapper.subscribe(rosterObj[strophieWrapper.rosterDetailsKey]["jid"], rosterObj[strophieWrapper.rosterDetailsKey]["nick"]);
                }
                setTimeout(function () {
                    strophieWrapper.sendPresence();
                }, 5000);
            }
        }
        strophieWrapper.connectionObj.addHandler(strophieWrapper.onRosterUpdate, Strophe.NS.ROSTER, 'iq', 'set');
    },
    //subscribe user in roster for presence updates
    subscribe: function (jid, nick, message) {
        var pres = $pres({
            to: jid,
            type: "subscribe"
        });
        if (message && message !== "") {
            pres.c("status").t(message).up();
        }
        if (nick && nick !== "") {
            pres.c('nick', {
                'xmlns': Strophe.NS.NICK
            }).t(nick).up();
        }
        strophieWrapper.commonLogger("subscribing -" + jid + "with nick " + nick);
        strophieWrapper.commonLogger(pres);
        strophieWrapper.connectionObj.send(pres);
    },
    //authorize user on getting subscribe request
    authorize: function (jid, message) {
        strophieWrapper.commonLogger("authorizing - " + jid);
        var pres = $pres({
            to: jid,
            type: "subscribed"
        });
        if (message && message != "") {
            pres.c("status").t(message);
        }
        strophieWrapper.connectionObj.send(pres);
    },
    //executed after presence has been fetched
    onPresenceReceived: function (presence) {
        var presence_type = $(presence).attr('type'),
            chat_status = "offline"; // unavailable, subscribed, etc...
        var from = $(presence).attr('from'),
            user_id = from.split("@")[0]; // the jabber_id of the contact
        strophieWrapper.commonLogger("start of onPresenceReceived for " + user_id);
        strophieWrapper.commonLogger(from);
        strophieWrapper.commonLogger($(presence));
        strophieWrapper.authorize(from.split("/")[0]);
        if (presence_type != 'error') {
            if (presence_type === 'unavailable') {
                chat_status = "offline";
            } else {
                var show = $(presence).find("show").text(); // this is what gives away, dnd, etc.
                if (show === 'chat' || show === '') {
                    chat_status = "online";
                } else {
                    // etc...
                }
            }
        }
        strophieWrapper.updatePresence(user_id, chat_status);
        strophieWrapper.commonLogger("end of onPresenceReceived for " + user_id + "---" + chat_status);
        strophieWrapper.commonLogger(strophieWrapper.Roster[user_id]);
        return true;
    },
    //update chat_status of roster items
    updatePresence: function (user_id, chat_status) {
        strophieWrapper.commonLogger("start of updatePresence");
        var updatedObj = {
            "chat_status": chat_status
        };
        if (chat_status == "online") {
            updatedObj["last_online_time"] = new Date();
        }
        strophieWrapper.Roster[user_id] = strophieWrapper.mergeRosterObj(strophieWrapper.Roster[user_id], strophieWrapper.mapRosterObj(updatedObj));
        if (strophieWrapper.initialRosterFetched == true) {
            strophieWrapper.commonLogger("change in status after initialRosterFetched done for " + user_id);
            strophieWrapper.commonLogger(strophieWrapper.Roster[user_id]);
            var nodeArr = [];
            nodeArr[user_id] = strophieWrapper.Roster[user_id];
            strophieWrapper.commonLogger(nodeArr);
            invokePluginManagelisting(nodeArr, "update_status", user_id);
        }
        strophieWrapper.commonLogger(strophieWrapper.Roster[user_id]);
        strophieWrapper.commonLogger("end of updatePresence for " + user_id);
        strophieWrapper.commonLogger(strophieWrapper.initialRosterFetched);
    },
    //executed after roster has been fetched
    onRosterReceived: function (iq) {
        strophieWrapper.commonLogger("in onRosterReceived");
        strophieWrapper.commonLogger(iq);
        $(iq).find("item").each(function () {
            var subscription = $(this).attr("subscription"),
                jid = $(this).attr("jid"),
                user_id = jid.split("@")[0];
            if (strophieWrapper.checkForSubscription(subscription) == true && user_id != strophieWrapper.getSelfJID().split("@")[0]) {
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
                        strophieWrapper.subscribe(jid, listObj[strophieWrapper.rosterDetailsKey]["nick"]);
                    }
                }
            }
        });
        strophieWrapper.commonLogger("end of onRosterReceived");
        strophieWrapper.commonLogger(strophieWrapper.Roster);
        strophieWrapper.commonLogger("setting roster fetched flag");
        strophieWrapper.initialRosterFetched = true;
        //strophieWrapper.connectionObj.addHandler(strophieWrapper.onPresenceReceived, null, 'presence', null);
        invokePluginManagelisting(strophieWrapper.Roster, "create_list");
        strophieWrapper.setRosterStorage(strophieWrapper.Roster);
    },
    //executed on msg receipt
    onMessage: function (iq) {
        strophieWrapper.commonLogger("got message");
        strophieWrapper.commonLogger(iq);
        var msgObject = strophieWrapper.formatMsgObj(iq);
        strophieWrapper.commonLogger(msgObject);
        invokePluginReceivedMsgHandler(msgObject);
        return true;
    },
    //parser for roster object
    formatRosterObj: function (obj) {
    	var listing_tuple_photo = "";
    	if(loggedInJspcGender){
    		if(loggedInJspcGender == "M")
    			listing_tuple_photo = chatConfig.Params[device].noPhotoUrl["listingTuple"]["F"];
    		else if(loggedInJspcGender == "F")
    			listing_tuple_photo = chatConfig.Params[device].noPhotoUrl["listingTuple"]["M"];
    	}
        strophieWrapper.commonLogger("in formatRosterObj");
        var chat_status = obj["attributes"]["chat_status"] || "offline",
            newObj = {};
        var fullname = "";
        if (typeof obj["attributes"]["name"] != "undefined") {
            fullname = obj["attributes"]["name"].split("|");
        }
        console.log(loggedInJspcGender);

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
    getSelfJID: function () {
        return strophieWrapper.connectionObj.jid;
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
                if ($.inArray(val, strophieWrapper.rosterGroups) == -1) {
                    return false;
                }
            });
            return true;
        }
    },
    //sending Message
    sendMessage: function (message, to) {
        var outputObj;
        try {
            if (message && to) {
                var reply = $msg({
                    from: username,
                    to: to,
                    type: 'chat',
                }).cnode(Strophe.xmlElement('body', message)).up().c('active', {
                    xmlns: "http://jabber.org/protocol/chatstates"
                });
                var messageId = strophieWrapper.connectionObj.receipts.sendMessage(reply);
                outputObj = {
                    "msg_id": messageId,
                    "canSend": true
                };
                return outputObj;
            }
        } catch (e) {
            outputObj = {
                "msg_id": strophieWrapper.getUniqueId(),
                "canSend": false,
                "errorMsg": e
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
        var outputObj = {
            "from": msg.getAttribute('from').split("@")[0],
            "to": msg.getAttribute('to').split("@")[0],
            "type": msg.getAttribute('type'),
            "msg_id": msg.getAttribute('id')
        };
        var $message = $(msg),
            msg_state;
        if ($message.find(strophieWrapper.msgStates["COMPOSING"]).length != 0) {
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
        strophieWrapper.commonLogger("in formatMsgObj");
        strophieWrapper.commonLogger(msg_state);
        if (typeof msg_state != "undefined") {
            outputObj["msg_state"] = msg_state;
        }
        var received = msg.getElementsByTagName(strophieWrapper.msgStates["RECEIVED"]);
        strophieWrapper.commonLogger(received);
        if (outputObj["type"] == "chat") {
            var body = msg.getElementsByTagName("body");
            //strophieWrapper.commonLogger(body);
            if (typeof body != "undefined" && body.length > 0) outputObj["body"] = Strophe.getText(body[0]);
            else outputObj["body"] = null;
        } else if (msg_state == strophieWrapper.msgStates["RECEIVED"]) {
            var rec = received[0];
            if (typeof rec != "undefined") {
                outputObj["receivedId"] = rec.getAttribute('id');
            }
        }
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
    addReceiptHandler: function (handler, type, from, options) {
        var that = this;
        var proxyHandler = function (msg) {
            that._processReceipt(msg);
            // call original handler
            return handler(msg);
        };
        this._conn.addHandler(proxyHandler, Strophe.NS.RECEIPTS, 'message', type, null, from, options);
    },
    /*
     * sending typing event
     */
    typingEvent: function (from, to, typingState) {
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
    },
    /*
     * sending typing event
     */
    sendReceivedReadEvent: function (from, to, msg_id, state) {
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
    }
}