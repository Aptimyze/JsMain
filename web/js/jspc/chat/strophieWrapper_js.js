var strophieWrapper = {
	connectionObj: null,
	Roster: [],
	initialRosterFetched: false,
	rosterSubscriptionAllowed : ["to","both"],
	rosterDetailsKey:"rosterDetails",
	useLocalStorage:false,

	//connect to openfire
	connect: function(bosh_service_url,username,password){
		strophieWrapper.connectionObj = new Strophe.Connection(chatConfig.Params[device].bosh_service_url);
    	strophieWrapper.connectionObj.connect(username,'123',strophieWrapper.onConnect);
	},

	//executed after connection done
	onConnect: function(status){
	    console.log("In onConnect function");
	    if (status == Strophe.Status.CONNECTING) {
		console.log("Connecting");
	    } else if (status == Strophe.Status.CONNFAIL) {
	        console.log("CONNFAIL");
		$('#connect').get(0).value = 'connect';
	    } else if (status == Strophe.Status.DISCONNECTING) {
	        console.log("DISCONNECTING");
		} else if (status == Strophe.Status.DISCONNECTED) {
	        console.log("DISCONNECTED");
			$('#connect').get(0).value = 'connect';
			//invokePluginLoginFailureHandler("failure");
	    } else if(status == Strophe.Status.AUTHFAIL){
            console.log("AUTHFAIL");
            //invokePluginLoginHandler("failure");
        } else if (status == Strophe.Status.CONNECTED) {
	        console.log("CONNECTED");
	        //append listing panel
	        invokePluginLoginHandler("success");
	    }
	},

	triggerBindings: function(){
        strophieWrapper.Roster = [];
        strophieWrapper.sendPresence();
        /*console.log($pres().tree());
        strophieWrapper.connectionObj.send($pres().tree());*/
        strophieWrapper.getRoster();
        //send own presence
        //binding event for presence update in roster
        strophieWrapper.connectionObj.addHandler(strophieWrapper.onPresenceReceived, null, 'presence', null);
        //binding event for message receive event
        strophieWrapper.connectionObj.addHandler(strophieWrapper.onMessage, null, 'message', null, null,  null); 
   		//binding event for new node push in roster
   		//strophieWrapper.connectionObj.addHandler(strophieWrapper.onRosterPush,Strophe.NS.ROSTER,'iq','set');
		//strophieWrapper.getRoster();
    },
	
	//send presence
	sendPresence : function(){
		console.log("in sendPresence");
        strophieWrapper.connectionObj.send($pres().tree());
    },

    //fetch roster
	getRoster: function(){
		console.log("in getRoster");
        var iq = $iq({type: 'get'}).c('query', {xmlns: Strophe.NS.ROSTER});
	    strophieWrapper.connectionObj.sendIQ(iq,strophieWrapper.onRosterReceived);
	},

	//executed on new push event in roster
	onRosterPush: function(iq){
		console.log("in onRosterPush");
		var user_id=$(iq).attr("jid"),listObj = {},subscription = $(iq).get("subscription");
		console.log("subscription "+subscription);
		if($.inArray(subscription,strophieWrapper.rosterSubscriptionAllowed))
		{
			listObj[user_id] = strophieWrapper.formatRosterObj(xmlToJson(iq));
			console.log("adding 1 node");
			console.log(listObj);
			if(typeof strophieWrapper.Roster[user_id] !== "undefined")
				strophieWrapper.Roster.splice(user_id);
			strophieWrapper.Roster.push(listObj);
			var nodeArr = [];
			nodeArr.push(listObj);
			invokePluginManagelisting(nodeArr,"add_node");
		}
	},

	//executed after presence has been fetched
	onPresenceReceived : function(presence){
		var presence_type = $(presence).attr('type'),chat_status="offline"; // unavailable, subscribed, etc...
		var from = $(presence).attr('from'),user_id = from.split("@")[0]; // the jabber_id of the contact
		console.log("start of onPresenceReceived for "+user_id);
		if (presence_type != 'error'){
		if (presence_type === 'unavailable'){
			console.log(from+" is offline");
			chat_status = "offline";
		}else{
			var show = $(presence).find("show").text(); // this is what gives away, dnd, etc.
				if (show === 'chat' || show === ''){
					console.log(from+" is online");
					chat_status = "online";
				}else{
					// etc...
				}
			}
		}
		strophieWrapper.updatePresence(user_id,chat_status);
		console.log("end of onPresenceReceived");
		console.log(strophieWrapper.Roster[user_id]);
		return true;
    },
	
	//update chat_status of roster items
	updatePresence: function(user_id,chat_status){
		console.log("start of updatePresence");
		console.log(strophieWrapper.Roster[user_id]);
		//if(typeof strophieWrapper.Roster[user_id] == "undefined")
		{
			console.log("presence before list 123");
			console.log(strophieWrapper.Roster[user_id]);
			console.log(chat_status+" 123");
			strophieWrapper.Roster[user_id] = strophieWrapper.mergeRosterObj(strophieWrapper.Roster[user_id],strophieWrapper.mapRosterObj({"chat_status":chat_status}));
			console.log(strophieWrapper.Roster[user_id]);
			console.log("end of updatePresence");
		}
		console.log("end of updatePresence for "+user_id);
		if(strophieWrapper.initialRosterFetched == true){
			console.log("change in status after initialRosterFetched done for "+user_id);
			var nodeArr = [];
			nodeArr[user_id].push(strophieWrapper.Roster[user_id]);
			invokePluginManagelisting(nodeArr,"update_status");
		}
	},

	//executed after roster has been fetched
	onRosterReceived :function(iq){
	    console.log("in onRosterReceived");
	    console.log(iq);

	 	console.log("start of onRosterReceived 246....");
	    console.log(strophieWrapper.Roster);
		$(iq).find("item").each(function() {
			var subscription = $(this).attr("subscription");
			console.log("here "+subscription + $(this).attr("jid"));
			console.log(typeof subscription);
			console.log($.inArray(subscription,strophieWrapper.rosterSubscriptionAllowed));
			var jid = $(this).attr("jid"),user_id = jid.split("@")[0];
			if((subscription == "to" || subscription == "both" ||$.inArray(subscription,strophieWrapper.rosterSubscriptionAllowed)) && user_id != strophieWrapper.getSelfJID().split("@")[0])
			{
				console.log("true");
				var listObj = strophieWrapper.formatRosterObj(xmlToJson(this));
				console.log("change roster for "+user_id);
				console.log(strophieWrapper.Roster[user_id]);
				console.log(listObj);
				var status = "offline";
				if(typeof strophieWrapper.Roster[user_id] !== "undefined")
					status = strophieWrapper.Roster[user_id]["rosterDetails"]["chat_status"];
				
				listObj["rosterDetails"]["chat_status"] = status;
				strophieWrapper.Roster[user_id] = strophieWrapper.mergeRosterObj(strophieWrapper.Roster[user_id],listObj);
				//var pres = $pres({to: this.attr('jid'), type: "subscribe"});
				//console.log($pres);
				//strophieWrapper.connectionObj.send(pres);
			}
		});
		console.log("end of onRosterReceived");
		console.log(strophieWrapper.Roster);
        //strophieWrapper.connectionObj.addHandler(strophieWrapper.onPresenceReceived, null, 'presence', null);
        //strophieWrapper.sendPresence();
	    //console.log(data["query"]["item"]);
	    console.log("hide loader-manvi");
	    console.log("calling invokePluginManagelisting");
	    console.log(strophieWrapper.Roster);
	    invokePluginManagelisting(strophieWrapper.Roster,"add_node");
	    strophieWrapper.initialRosterFetched = true;
	    strophieWrapper.setRosterStorage(strophieWrapper.Roster);
	},

	onMessage :function(msg) {
	    var to = msg.getAttribute('to');
	    var from = msg.getAttribute('from');
	    var type = msg.getAttribute('type');
	    var elems = msg.getElementsByTagName('body');
	    if (type == "chat" && elems.length > 0) {
		var body = elems[0];
		console.log('ECHOBOT: I got a message from ' + from + ': ' + 
		    Strophe.getText(body));
		var text = Strophe.getText(body) + " (this is echo)";
	    
		//var reply = $msg({to: from, from: to, type: 'chat', id: 'purple4dac25e4'}).c('active', {xmlns: "http://jabber.org/protocol/chatstates"}).up().cnode(body);
	            //.cnode(Strophe.copyElement(body)); 
		//connection.send(reply.tree());
		console.log('ECHOBOT: I sent ' + from + ': ' + Strophe.getText(body));
	    }
	    // we must return true to keep the handler alive.  
	    // returning false would remove it after it finishes.
	    return true;
	},
	/*getUniqueId: function(suffix) {
        var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = Math.random() * 16 | 0,
                v = c == 'x' ? r : r & 0x3 | 0x8;
            return v.toString(16);
        });
        if (typeof(suffix) == "string" || typeof(suffix) == "number") {
            return uuid + ":" + suffix;
        } else {
            return uuid + "";
        }
    },*/

    //parser for roster object
    formatRosterObj: function(obj){
    	var chat_status = obj["attributes"]["chat_status"] || "offline";
    	var newObj = {},fullname = obj["attributes"]["name"].split("|");
		newObj[strophieWrapper.rosterDetailsKey] = { 
								"jid":obj["attributes"]["jid"],
								"chat_status":chat_status,
								"fullname":fullname[0],
								"groups":[],
								"subscription":obj["attributes"]["subscription"],
								"profile_checksum":fullname[2],
								"listing_tuple_photo":fullname[1]
							};
		newObj[strophieWrapper.rosterDetailsKey]["groups"].push(obj["group"]["#text"]);
		return newObj;
    },

    //merge second roster obj to first one
    mergeRosterObj:function(obj1,obj2){
    	if(typeof obj1 == "undefined")
    	{
    		obj1 = {};
    		obj1[strophieWrapper.rosterDetailsKey] = {};
    	}
    	if(typeof obj2 !== "undefined")
	    {
	    	console.log("here");
	    	$.each(obj2[strophieWrapper.rosterDetailsKey],function(key,val){
	    		console.log(key+"-"+val);
	    		obj1[strophieWrapper.rosterDetailsKey][key] = val;
	    		console.log(obj1);
	    	});
	    }
    	return obj1;
    },

    //map input object to roster object
    mapRosterObj: function(inputObj){
    	var outputObj = {};
    	outputObj[strophieWrapper.rosterDetailsKey] = {};
    	if(typeof inputObj !== "undefined")
    	{
	    	$.each(inputObj,function(key,val){
	    		outputObj[strophieWrapper.rosterDetailsKey][key] = val;
	    	});
   		}
   		return outputObj;
    },

    //get self jid of connected user
    getSelfJID: function(){
    	return strophieWrapper.connectionObj.jid;
    },

    //set listing data in roster
    setRosterStorage: function(rosterData){
    	if(strophieWrapper.useLocalStorage == true)
    	{
    		localStorage.setItem('chatListing', JSON.stringify(rosterData));
    	}
    },

    getRosterStorage: function(){
    	var data;
    	if(strophieWrapper.useLocalStorage == true){
    		data = JSON.parse(localStorage.getItem('chatListing'));
    	}
    	else
    		data = null;
    	return data;
    }
}