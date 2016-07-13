var strophieWrapper = {
	connectionObj: {},

	connect: function(bosh_service_url,username,password){
		strophieWrapper.connectionObj = new Strophe.Connection(chatConfig.Params[device].bosh_service_url);
    	strophieWrapper.connectionObj.connect(username,'123',strophieWrapper.onConnect);
	},
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
	        console.log($pres().tree());
	        strophieWrapper.connectionObj.send($pres().tree());

	        //fetch roster for initial listing
	        var iq = $iq({type: 'get'}).c('query', {xmlns: 'jabber:iq:roster'});
	        strophieWrapper.connectionObj.sendIQ(iq,strophieWrapper.onRosterReceived);
	        strophieWrapper.connectionObj.addHandler(strophieWrapper.onMessage, null, 'message', null, null,  null); 
	    }
	},

	on_presence : function(presence){
		console.log(presence);
		var presence_type = $(presence).attr('type'),chat_status="offline"; // unavailable, subscribed, etc...
		var from = $(presence).attr('from'); // the jabber_id of the contact
		if (presence_type != 'error'){
			if (presence_type === 'unavailable'){
				// Mark contact as offline
				chat_status = "offline";
			}
			else{
				var show = $(presence).find("show").text(); // this is what gives away, dnd, etc.
				if (show === 'chat' || show === ''){
				// Mark contact as online
				chat_status = "online";
				}
				else{
				// etc...
				}
			}
		}
		console.log(from+" ----- "+chat_status);
		return true;
	},
	
	/*function callbackOnRosterData
	* executed after roster data has been fetched
	* @inputs: data
	*/
	onRosterReceived :function(data){
	    console.log("in callbackOnRosterData ankita1...");
	    console.log(data);
	    console.log(xmlToJson(data));
	    $(data).find('item').each(function(){
	        var jid = $(this).attr('jid'); 
	    // You can probably put them in a unordered list and and use their jids as ids.
	    });
	    strophieWrapper.connectionObj.addHandler(strophieWrapper.on_presence, null, 'presence');
	    //connection.send($pres());
	    //console.log(data["query"]["item"]);
	    //invokePluginManagelisting(data["query"]["item"],"add_node");
	    //sendMessage();
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
	}
}