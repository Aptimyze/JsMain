var strophieWrapper = {
	connectionObj: null,
	Roster: [],
	presenceMessage: null,

	//connect to openfire
	connect: function(bosh_service_url,username,password){
		strophieWrapper.connectionObj = new Strophe.Connection(chatConfig.Params[device].bosh_service_url);
    	strophieWrapper.connectionObj.connect(username,'123',strophieWrapper.onConnect);
	},

	//executed after connection done
	onConnect: function(status)
	{
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
	    } else if(status == Strophe.Status.AUTHFAIL){
            console.log("AUTHFAIL");
        } else if (status == Strophe.Status.CONNECTED) {
	        console.log("CONNECTED");
	        console.log("Presence");
	        /*console.log($pres().tree());
	        strophieWrapper.connectionObj.send($pres().tree());*/

	        //send own presence
	        strophieWrapper.sendPresence();
	        //get roster
	        strophieWrapper.getRoster();
	        console.log("after getRoster");
	        strophieWrapper.connectionObj.addHandler(strophieWrapper.onPresenceReceived, null, 'presence', null);
	        strophieWrapper.connectionObj.addHandler(strophieWrapper.onMessage, null, 'message', null, null,  null); 
	    }
	},

	//send presence
	sendPresence : function(){
		console.log("Presence");
        strophieWrapper.connectionObj.send($pres().tree());
    },

    //fetch roster
	getRoster: function(){
		var iq = $iq({type: 'get', 'id': strophieWrapper.getUniqueId('roster')})
                        .c('query', {xmlns: 'jabber:iq:roster'});
	    strophieWrapper.connectionObj.sendIQ(iq,strophieWrapper.onRosterReceived);
	},

	//executed after presence has been fetched
	onPresenceReceived : function(presence){
		var presence_type = $(presence).attr('type'); // unavailable, subscribed, etc...
		var from = $(presence).attr('from'); // the jabber_id of the contact
		if (presence_type != 'error'){
		if (presence_type === 'unavailable'){
			// Mark contact as offline
			console.log(from+" is offline");
			}else{
				var show = $(presence).find("show").text(); // this is what gives away, dnd, etc.
				if (show === 'chat' || show === ''){
				// Mark contact as online
				console.log(from+" is online");
				}else{
				// etc...
				}
			}
		}
		return true;
    },
	
	//executed after roster has been fetched
	onRosterReceived :function(iq){
	    console.log("in onRosterReceived ankita3...");
	    console.log(iq);
	    var data = xmlToJson(iq);
	    console.log(data);
		$(iq).find("item").each(function() {
			strophieWrapper.Roster.push(xmlToJson(this));
			//var pres = $pres({to: this.attr('jid'), type: "subscribe"});
			//console.log($pres);
			//strophieWrapper.connectionObj.send(pres);
		});
		console.log("end of strophieWrapper roster");
		console.log(strophieWrapper.Roster);
        //strophieWrapper.connectionObj.addHandler(strophieWrapper.onPresenceReceived, null, 'presence', null);
        strophieWrapper.sendPresence();
	    //console.log(data["query"]["item"]);
	    //invokePluginManagelisting(strophieWrapper.Roster,"add_node");
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
	getUniqueId: function(suffix) {
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
    }
}