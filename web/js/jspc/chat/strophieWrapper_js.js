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
	    console.log("AIn onConnect function");
	    if (status == Strophe.Status.CONNECTING) {
		console.log("BIn onConnect function");
	    } else if (status == Strophe.Status.CONNFAIL) {
	        console.log("CIn onConnect function");
		$('#connect').get(0).value = 'connect';
	    } else if (status == Strophe.Status.DISCONNECTING) {
	        console.log("DIn onConnect function");
		} else if (status == Strophe.Status.DISCONNECTED) {
	        console.log("EIn onConnect function");
		$('#connect').get(0).value = 'connect';
	    } else if (status == Strophe.Status.CONNECTED) {
	        console.log("FIn onConnect function");
	        console.log("Presence");
	        /*console.log($pres().tree());
	        strophieWrapper.connectionObj.send($pres().tree());*/

	        //send own presence
	        strophieWrapper.sendPresence();
	        //get roster
	        strophieWrapper.getRoster();
	        strophieWrapper.connectionObj.addHandler(strophieWrapper.onMessage, null, 'message', null, null,  null); 
	    }
	},

	//send presence
	sendPresence : function(){
        strophieWrapper.connectionObj.send($pres());
    },

    //fetch roster
	getRoster: function(){
	    var iq = $iq({type: 'get'}).c('query', {xmlns: 'jabber:iq:roster'});
	    strophieWrapper.connectionObj.sendIQ(iq,strophieWrapper.onRosterReceived);
	},

	//executed after presence has been fetched
	onPresenceReceived : function(presence){
        var presence_type = $(presence).attr('type'); // unavailable, subscribed, etc...
        var from = $(presence).attr('from'); // the jabber_id of the contact...
        if(!strophieWrapper.presenceMessage[from])
            console.log(presence);
        if (presence_type != 'error'){
            if (presence_type === 'unavailable'){
                console.log("Contact: ", $(presence).attr('from'), " is offline");
                strophieWrapper.presenceMessage[from] = "offline";
            }else{
                var show = $(presence).find("show").text(); // this is what gives away, dnd, etc.
                if ( (show === 'chat' || show === '') && (!strophieWrapper.presenceMessage[from])){
                    // Mark contact as online
                    console.log("Contact: ", $(presence).attr('from'), " is online");
                    strophieWrapper.presenceMessage[from] = "online";
                    strophieWrapper.sendPresence();
                } else if (show === 'away'){
                    console.log("Contact: ", $(presence).attr('from'), " is offline");
                    strophieWrapper.presenceMessage[from] = "offline";
                }
            }
        }
        return true;
    },
	
	//executed after roster has been fetched
	onRosterReceived :function(iq){
	    console.log("in callbackOnRosterData ankita2...");
	    console.log(iq);
	    console.log(xmlToJson(iq));
		$(iq).find("item").each(function() {
			strophieWrapper.Roster.push(xmlToJson(this));
		});
		console.log(strophieWrapper.Roster);
        strophieWrapper.connection.addHandler(strophieWrapper.onPresenceReceived,null,"presence");
	    //connection.send($pres());
	    //console.log(data["query"]["item"]);
	    //invokePluginManagelisting(data["query"]["item"],"add_node");
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
    
    disconnect: function(){
        console.log("In wrapper disconnect");
        strophieWrapper.connectionObj.disconnect();
    }
}