
var jid=profileId+"@"+JABBERSERVER;

var nick;
var vcard;
var status = '';
var onlstat = '';
var onlmsg = '';
var onlprio = '8';
var autoPopup = true;
var autoPopupAway = false;
var playSounds = true;
var focusWindows = true;
var timestamps = false;
var usersHidden = false;
var enableLog = false;
var loghost;

/* some globals */
var roster;
var fmd; // frames.main.document
var disco; // holds information from service discovery

var statusLed;
var statusMsg;

var onlstatus = new Object();
onlstatus["available"] = "online";
onlstatus["chat"] = "free for chat";
onlstatus["away"] = "away";
onlstatus["xa"] = "not available";
onlstatus["dnd"] = "do not disturb";
onlstatus["invisible"] = "invisible";
onlstatus["unavailable"] = "offline";


var subw;
function removeUser(aJid) {
  // get fulljid
  var fulljid = roster.getUserByJID(aJid).fulljid;

  var iq = new JSJaCIQ();
  iq.setType('set');
  var query = iq.setQuery('jabber:iq:roster');
  var item = query.appendChild(iq.getDoc().createElement('item'));
  item.setAttribute('jid',fulljid);
  item.setAttribute('subscription','remove');

  con.send(iq);
}

var vcardW; // my vcardW;

var searchW;

var ebW;

/* command line history */
var messageHistory = new Array();
var historyIndex = 0;
function getHistory(key, message) {
  if ((key == "up") && (historyIndex > 0)) historyIndex--;
  if ((key == "down") && (historyIndex < messageHistory.length)) historyIndex++;
  if (historyIndex >= messageHistory.length) {
    if (historyIndex == messageHistory.length) return '';
    return message;
  } else {
    return messageHistory[historyIndex];
  }
}

function addtoHistory(message) {
  if (is.ie5)
    messageHistory = messageHistory.concat(message);
  else
    messageHistory.push(message);
  historyIndex = messageHistory.length;
}

/* system sounds */
var soundPlaying = false;
function soundLoaded() {
	soundPlaying = false;
}

function playSound(action) {
  
}


function isGateway(aJid) {
  aJid = cutResource(aJid);
  for (var i in disco) {
    if (!disco[i].getNode) continue;
    if (i == aJid)
      if (disco[i].getNode().getElementsByTagName('identity').item(0)) {
	if (disco[i].getNode().getElementsByTagName('identity').item(0).getAttribute('category') == 'gateway')
	  return true;
      }
  }
  return false;
}

/************************************************************************
 *                       ******  CHANGESTATUS   *******
 ************************************************************************
 */

function changeStatus(val,away,prio) {
  
  Debug.log("changeStatus: "+val+","+away+","+prio, 2);

  onlstat = val;
  if (away)
    onlmsg = away;
  
  if (prio && !isNaN(prio))
    onlprio = prio;
  
  if (!con.connected() && val != 'offline') {
    init();
    return;
  }
  
  var aPresence = new JSJaCPresence();
  
  switch(val) {
  case "unavailable":
    val = "invisible";
      aPresence.setType('invisible');
    break;
  case "offline":
    val = "unavailable";
    aPresence.setType('unavailable');
    con.send(aPresence);
    con.disconnect();
    cleanUp();
    return;
    break;
  case "available":
    val = 'available'; // needed for led in status bar
    if (away)
      aPresence.setStatus(away);
    if (prio && !isNaN(prio))
      aPresence.setPriority(prio);
    else
      aPresence.setPriority(onlprio);			
    break;
  case "chat":
    if (prio && !isNaN(prio))
      aPresence.setPriority(prio);
    else
      aPresence.setPriority(onlprio);			
  default:
    if (away)
      aPresence.setStatus(away);
    
    if (prio && !isNaN(prio))
      aPresence.setPriority(prio);
    else
      aPresence.setPriority('0');
    
    aPresence.setShow(val);
  }
  
  con.send(aPresence);
  
  
}
var act_lth=0;
var tit_mes="";
function handleMessage(aMessage) {

  Debug.log(aMessage.getDoc().xml,2);

  if (aMessage.getType() == 'error')
    return;
  
  var from = cutResource(aMessage.getFrom());
  var subject =aMessage.getSubject();
  var type = aMessage.getType();
  var profileID=aMessage.getThread();
  var imageId=aMessage.getImage();
  var userName=aMessage.getUserName();
 
  Debug.log("from: "+from+"\naMessage.getFrom(): "+aMessage.getFrom(),3);
  if(subject && subject != "" && subject !=" "){
	user = roster.getUserByJID(subject);
	from=	aMessage.getSubject(); 
   }else{
	user = roster.getUserByJID(from);
   }
  if (user == null) {// users not in roster (yet)
    Debug.log("creating new user "+from,3);
    user = roster.addUser(new RosterUser(from));
    user.nickName=userName;//here it is assumed that this condition will occur only for js user.
   	user.userName=userName;//here it is assumed that this condition will occur only for js user.
    user.profileId=profileID;
    user.imageId=imageId;
    top.roster=roster;
  }
if(user.nickName == "" && 	user.userName== ""){
	user.nickName=userName;
   	user.userName=userName;
}

  Debug.log("got user jid: "+user.jid,3);

  var aRoster = roster;
  // set current timestamp
  var x;
  for (var i=0; i<aMessage.getNode().getElementsByTagName('x').length; i++)
    if (aMessage.getNode().getElementsByTagName('x').item(i).getAttribute('xmlns') == 'jabber:x:delay') {
      x = aMessage.getNode().getElementsByTagName('x').item(i);
      break;
    }
  
  if (x) {
    Debug.log("found offline message: "+x.getAttribute('stamp'),3);
    var stamp = x.getAttribute('stamp');
    aMessage.jwcTimestamp = new Date(Date.UTC(stamp.substring(0,4),stamp.substring(4,6)-1,stamp.substring(6,8),stamp.substring(9,11),stamp.substring(12,14),stamp.substring(15,17)));
  } else
    aMessage.jwcTimestamp = new Date();
  if(type== 'logout'){
  	var body=aMessage.getBody();
		if(body == '@logout'){
			user.chatmsgs = user.chatmsgs.concat(aMessage);
			 var sen_d=profileID;
	                var rec_d=profileId;
                	var url_to_log="/profile/log_chat_data.php?REC="+rec_d+"&SEN="+sen_d+"&action=L";
			
        	        //Update the request table that request is received .
	                top.send_ajax_request_chat(url_to_log,"","","POST");
			if(user.chatW && user.chatW !=""){
				top.popMsgs(user);
				return ;
			}else if(user.popout != null ){
				user.popout.popMsgs();
			}
			  top.update_title(subject,type,userName,aMessage.getBody());
		}
	}else if(type == 'chatAuth' || type == 'headline'){
	var body=aMessage.getBody();
	//ask_chatAuth
	if(body=="ask_chatAuth"){
			
		var sen_d=from.substring(0,from.indexOf("@"));
		var rec_d=profileId;
		if(user.chatAuth!=null)
		{
                        var aMessages = new JSJaCMessage();
                        aMessages.setType('chatAuth');
                        aMessages.setTo(sen_d+"@"+JABBERSERVER);
                        aMessages.setBody("REINITIATE");
                        aMessages.setThread(rec_d);
                        con.send(aMessages);
                        result_invalid=1;
			user.chatAuth=null;
			top.set_cookies(top.profileId,user.profileId,"N");
                        return;

		}
		var url_to_log="/profile/log_chat_data.php?REC="+rec_d+"&SEN="+sen_d+"&received=1";
		var result_invalid = $.ajax({url: url_to_log,async: false}).responseText;
		if(result_invalid==2)
		{
			var aMessages = new JSJaCMessage();
			aMessages.setType('chatAuth');
			aMessages.setTo(sen_d+"@"+JABBERSERVER);
			aMessages.setBody("invalid");
			aMessages.setThread(rec_d);
			con.send(aMessages);
			result_invalid=1;	
			return;
		}
		//Update the request table that request is received .
		top.send_ajax_request_chat(url_to_log,"","","POST");
		user.chatmsgs = user.chatmsgs.concat(aMessage);
		user.chatAuth="pending";
		top.set_cookies(top.profileId,user.profileId,"P");

		if(user.chatW && user.chatW !=""){
			top.popMsgs(user);
		}else if(user.popout != null){
			user.popout.popMsgs();
		}else{
			 user.canChat="yes";//plz rmv this is most sensitive var in term of business purpose.
			top.openWindow(from,user.imageId,profileID,user.nickName);
			playSound('chat_recv');
			
		}
		
		top.update_title(subject,type,userName,aMessage.getBody());

		return ;	 	
	}
	else if(body =="time_out"){
		var sen_d=profileID;
		var rec_d=profileId;
		var url_to_log="/profile/log_chat_data.php?REC="+rec_d+"&SEN="+sen_d+"&action=T";
		
		//Update the request table that request is received .
		top.send_ajax_request_chat(url_to_log,"","","POST");
		
		user.chatmsgs = user.chatmsgs.concat(aMessage);
		user.chatAuth=null;
		top.set_cookies(top.profileId,user.profileId,"N");
		if(user.chatW && user.chatW !=""){
			top.popMsgs(user);
		}else if(user.popout != null){
			user.popout.popMsgs();
		}else{
			user.chatAuth="time_out";
			top.set_cookies(top.profileId,user.profileId,"T");
		    user.canChat="yes";//plz rmv this is most sensitive var in term of business purpose.
		   top.openWindow(from,user.imageId,profileID,user.nickName);
		   playSound('chat_recv');
		}

		top.update_title(subject,type,userName,aMessage.getBody());
		
		return ;
		}else if(body=="accept"){
		user.chatAuth="accept";	
		top.set_cookies(top.profileId,user.profileId,"A");
		user.chatmsgs = user.chatmsgs.concat(aMessage);
		if(user.chatW && user.chatW !=""){
			top.popMsgs(user);
		}else if(user.popout != null){
			 user.popout.popMsgs();
             playSound('chat_recv');
		}
		top.update_title(subject,type,userName,aMessage.getBody());

		return ;
	}else if(body == "ending"){
		if(user.chatAuth!="accept")
	        {
			user.chatAuth=null;
			top.set_cookies(top.profileId,user.profileId,"N");
                	var sen_d=from.substring(0,from.indexOf("@"));
        	        var rec_d=profileId;
			
	                if(1)
                	{
	                        return;
                	}
        	}
		user.chatmsgs = user.chatmsgs.concat(aMessage);
		if(user.chatW && user.chatW !=""){
			top.popMsgs(user);
		}else if(user.popout != null){
			user.popout.popMsgs();
		}else{
			 top.openWindow(from,user.imageId,profileID,user.nickName);
			playSound('chat_recv');
		}

		top.update_title(subject,type,userName,aMessage.getBody());
		
		return ;	
	}else {	
		user.chatmsgs = user.chatmsgs.concat(aMessage);
		user.chatAuth=null;
		top.set_cookies(top.profileId,user.profileId,"N");
		if(user.chatW && user.chatW !=""){
			top.popMsgs(user);
		}else if(user.popout != null){
			user.popout.popMsgs();
		}else{
			user.canChat="yes";//plz rmv this is most sensitive var in term of business purpose.
			top.openWindow(from,user.imageId,profileID,user.nickName);
			playSound('chat_recv');
		}
		top.update_title(subject,type,userName,aMessage.getBody());

		return ;	
	}
   }else if (type == 'chat') {
	if(user.chatAuth==null)
	{
		var sen_d=from.substring(0,from.indexOf("@"));
                var rec_d=profileId;
                if(1)
                {
                        var aMessages = new JSJaCMessage();
                        aMessages.setType('chatAuth');
                        aMessages.setTo(sen_d+"@"+JABBERSERVER);
                        aMessages.setBody("REINITIATE");
                        aMessages.setThread(rec_d);
                        con.send(aMessages);
                        result_invalid=1;
                        return;
                }
	}
	top.update_title(subject,type,userName,aMessage.getBody());

    user.chatmsgs = user.chatmsgs.concat(aMessage);
    if (user.chatW && user.chatW !="") {
       top.popMsgs(user);
      playSound('chat_recv');
    }else if(user.popout != null){
    	user.popout.popMsgs();
    } else if (autoPopup && (autoPopupAway || onlstat == "available" || onlstat == "chat")) {
     user.canChat="yes";
 	top.openWindow(from,user.imageId,profileID,user.nickName);
      playSound('chat_recv');
    } else {
      if (focusWindows) window.focus();
      playSound('chat_queue');
    }

  }
}

/************************************************************************
 * handleMessageError
 ************************************************************************
 */
var error_messages = new Array();
var errorW;
function handleMessageError(aJSJaCPacket) {
  if (aJSJaCPacket.getType() != 'error')
    return;
  
  Debug.log(aJSJaCPacket.getDoc().xml,2);
  
  var user = roster.getUserByJID(cutResource(aJSJaCPacket.getFrom()));
  
   if (user.chatW && user.chatW != "") {
    var error = aJSJaCPacket.getNode().getElementsByTagName('error').item(0);
    if (error) {
      if (error.getElementsByTagName('text').item(0)) {
		top.putMsgHTML(aJSJaCPacket);
		playSound('error');
		return;
      }
    }
  }
  
  error_messages = error_messages.concat(aJSJaCPacket);
  
  if (!errorW || errorW.closed)
    errorW = open("jschat/error_message.html","errorW"+makeWindowName(jid),"width=360,height=270,dependent=yes,resizable=yes");
  else if (error_messages.length > 0 && errorW.document.forms[0])
    errorW.document.forms[0].nextButton.disabled = false;
  
  playSound('error');
  
  errorW.focus();
}

/************************************************************************
 * handlePresence
 ************************************************************************
 */

function handlePresence(presence) {
  Debug.log(presence.getDoc().xml,2);

  var from = cutResource(presence.getFrom());
  var type = presence.getType();
  var show = presence.getShow();
  var status = presence.getStatus();inter

  var aRoster = roster;
  var x;
 
  var user = roster.getUserByJID(from);
  if (!user) { // presence from unsubscribed user
    Debug.log("presence from "+from+" not found on roster", 2);
    return;
  }
  
  /* handle presence for MUC */
  x = null; // reset
  for (var i=0; i<presence.getNode().getElementsByTagName('x').length; i++)
    if (presence.getNode().getElementsByTagName('x').item(i).getAttribute('xmlns') == 'http://jabber.org/protocol/muc#user') {
      x = presence.getNode().getElementsByTagName('x').item(i);
      break;
    }
 
  if (show) {
    if (user.status == 'unavailable')
      playSound('online');
    // fix broken pressenc status
    if (show != 'chat' && show != 'away' && show != 'xa' && show != 'dnd')
      show = 'available';
    user.status = show;
  } else if (type) {
    if (type == 'unsubscribe') {
      user.subscription = 'from';
      user.status = 'stalker';
    } else if (user.status != 'stalker')
      user.status = 'unavailable';
    if (aRoster.name == 'GroupchatRoster' && !nickChanged) { // it's a groupchat roster
      // remove user
      if (!user.chatW || user.chatW.closed)
	aRoster.removeUser(user); // we don't need offline users in there
    }
    playSound('offline');
  } else {
    if (user.status == 'unavailable') // user was offline before
      playSound('online');
    user.status = 'available';
  }

  if (status)
    user.statusMsg = status;
  else
    user.statusMsg = null;
  
  // update presence indicator of chat window
  if (user.chatW && !user.chatW.closed && user.chatW.updateUserPresence) 
    user.chatW.updateUserPresence();

}

/************************************************************************
 * handleIQSet
 ************************************************************************
 */

function handleIQSet(iq) {
  if (iq.getType() != "set") {
    Debug.log("not handling iq:\n"+iq.getDoc().xml,3);
    return;
  }
  
  Debug.log("got iq type 'set':\n"+iq.getDoc().xml,2);
  
  if (iq.getQueryXMLNS() != 'jabber:iq:roster' ) { // only handle roster items so far
    Debug.log("not handling iq:\n"+iq.getDoc().xml,1);
    return;
  }
  
  for (var i=0; i<iq.getQuery().childNodes.length; i++) {
    var item = iq.getQuery().childNodes.item(i);
    var user = roster.getUserByJID(cutResource(item.getAttribute('jid')));
    if (user) {
      user.subscription = item.getAttribute('subscription');
      if (item.getAttribute('subscription') == 'remove') {
	Debug.log("removing user " + user.jid,2);
        roster.removeUser(user);
      } else { // update user
        user.name = item.getAttribute('name')? htmlEnc(item.getAttribute('name')) : item.getAttribute('jid');
        user.groups = new Array('');
	for (var j=0; j<item.childNodes.length; j++)
	  if (item.childNodes.item(j).nodeName == 'group')
	    user.groups = user.groups.concat(item.childNodes.item(j).firstChild.nodeValue);
        roster.updateGroups();
      }
    } else {// got a new user
      if (isGateway(item.getAttribute('jid'))) { // auto add gateways
	// get name
	var name = cutResource(item.getAttribute('jid'));
	for (var i in disco) {
	  if (typeof(disco[i]) != 'object') continue;
	  if (i == cutResource(item.getAttribute('jid')))
	    name = disco[i].getQuery().getElementsByTagName('identity').item(0).getAttribute('name');
	}

	// add to roster
	var aUser = new RosterUser(cutResource(item.getAttribute('jid')),item.getAttribute('subscription'),["Gateways"],name);
	roster.addUser(aUser);
	top.roster=roster;
	// set name and group
	var aIQ = new JSJaCIQ();
	aIQ.setType('set');
	var query = aIQ.setQuery('jabber:iq:roster');
	var aItem = query.appendChild(aIQ.getDoc().createElement('item'));
	aItem.setAttribute('jid',item.getAttribute('jid'));
	aItem.setAttribute('name',name);
	aItem.appendChild(iq.getDoc().createElement('group')).appendChild(iq.getDoc().createTextNode('Gateways'));
	
	con.send(aIQ);
      } else { // new but not a gateway
        var name = item.getAttribute('name')? item.getAttribute('name') : item.getAttribute('jid');
	if (name.indexOf('@') != -1)
	  name = name.substring(0,name.indexOf('@'));
	
	item.setAttribute('name',name);
        var groups = new Array('');
	for (var j=0; j<item.childNodes.length; j++)
	  if (item.childNodes.item(j).nodeName == 'group')
	    groups = groups.concat(item.childNodes.item(j).firstChild.nodeValue);
	
	roster.addUser(new RosterUser(cutResource(item.getAttribute('jid')),item.getAttribute('subscription'),groups,name));
	top.roster=roster;
	var aIQ = new JSJaCIQ();
	aIQ.setType('set');
	var query = aIQ.setQuery('jabber:iq:roster');
	
	var aItem = item.cloneNode(true);
	aItem.removeAttribute('subscription');
	query.appendChild(aItem);
	
	con.send(aIQ); // set stripped name
	
	if (item.getAttribute('subscription') == "from" && item.getAttribute('ask') != 'subscribe')
	  openSubscription(item.getAttribute('jid')); // subscribe to user
      }
    }
  }
}

function handleConError(e) {
	
  switch (e.getAttribute('code')) {
  case '401':
    //alert("Authorization failed");
    if (!con.connected())
      window.close();
    break;
  case '409':
    //alert("Registration failed!\n\nPlease choose a different username!");
    break;
  case '503':
  	top.actualLogOut();
    //alert("Service unavailable");
    break;
  case '500':
    if (!con.connected() && !logoutCalled && onlstat != 'offline'){
    	var type=e.getAttribute('type');
    	
     if(type=="cancel"){
     	//alert("Either you might have been signed in from  different machine OR session expired");
		top.actualLogOut();
	}else{
     if (confirm("Internal Server Error.\n\nDisconnected.\n\nReconnect?")){
			changeStatus(onlstat,onlmsg);
		}else{
			top.actualLogOut();
		}
	}
	}
    break;
  default:
    alert("An Error Occured:\nCode: "+e.getAttribute('code')+"\nType: "+e.getAttribute('type')+"\nCondition: "+e.firstChild.nodeName); // this shouldn't happen :)
    break;
  }
}

function handleDisconnect() {
  if (logoutCalled || onlstat == 'offline')
    return;
  
  fmd.getElementById('roster').innerHTML = '';
  
}

function handleConnected() {

  Debug.log("Connected",0);
  
  con.send(new JSJaCPresence());
}

function getPrefs(iq) {
  
    onlprio = DEFAULTPRIORITY;
  
  // send presence
  if (onlstat == '')
    onlstat = 'available';
  changeStatus(onlstat,onlmsg,onlprio);
  
  playSound('connected');
  
}

var bookmarks;

var annotations;

var con, Debug, srcW;
function init() {
	
	
	 if (top.con)//added for frequent log out
		return;
	
	top.subscript=subscript;
	top.profileId=profileId;
	
  /* initialise debugger */
  if (!Debug || typeof(Debug) == 'undefined' || !Debug.start) {
    if (typeof(Debugger) != 'undefined')
      Debug = new Debugger(DEBUG_LVL,'jschat ' + cutResource(jid));
    else {
      Debug = new Object();
      Debug.log = function() {};
      Debug.start = function() {};
    }
  }
  if (DEBUG &&  (!USE_DEBUGJID || DEBUGJID == cutResource(jid)))
    Debug.start();
  
 
  Debug.log("jid: "+jid+"\npass: "+pass,2);

  document.title = "jeevansathi - " + nick;

  roster = new Roster();
  
  var oArg = {oDbg: Debug, httpbase: HTTPBASE, timerval: timerval};
  
  if (BACKEND_TYPE == 'binding')
    con = new JSJaCHttpBindingConnection(oArg);
  else
    con = new JSJaCHttpPollingConnection(oArg);
  
  /* register handlers */
  con.registerHandler('iq',handleIQSet);
  con.registerHandler('presence',handlePresence);
  con.registerHandler('message',handleMessage);
  con.registerHandler('message',handleMessageError);
  con.registerHandler('ondisconnect',handleDisconnect);
  con.registerHandler('onconnect',handleConnected);
  con.registerHandler('onerror',handleConError);
  //var resource=jid.substring(jid.indexOf('/')+1)+"/"+Math.floor(Math.random()*200);
  var resource=jid.substring(jid.indexOf('/')+1);
  /* connect to remote */
  oArg = {domain:JABBERSERVER,username:jid.substring(0,jid.indexOf('@')),resource:resource,pass:pass,register:register}

 oArg.authtype = 'nonsasl';
 con.connect(oArg);
  if(!top.con){
		top.con=con;
		top.roster=roster;
		top.jid=jid;
		top.jwcMain=this.window;
		top.onlineusers=onlineUsers;
		top.userCheckSum=user_checksum;
		
		
		if(!top.searchId)
			top.old_onlineusers=onlineUsers;
			
		top.logined_userName=userName;
	}
if(typeof(top.updateOnlineUsers)=="function")
  top.updateOnlineUsers(1,1,1);
}

/************************************************************************
 *                       ******  LOGOUT  *******
 ************************************************************************
 */

function cleanUp() {
  /* close dependent windows */
  if (roster)
    roster.cleanUp();
  
  if (subw && !subw.closed)
    subw.close();
  
  if (typeof(ow) != 'undefined' && ow && !ow.closed)
    ow.close();
  
  if (searchW && !searchW.closed)
    searchW.close();
  
  if (ebW && !ebW.closed)
    ebW.close();
  if(fmd)
  	fmd.getElementById('roster').innerHTML = '';

}

var logoutCalled = false;
function logout() {
	if ( !con || !con.connected())// added by me for script error
    	return;
	
  logoutCalled = true;
  cleanUp();
  onlineUsers=0;
  
  
  var aPresence = new JSJaCPresence();
  aPresence.setType('unavailable');
  con.send(aPresence);
  con.disconnect();
}

/************************************************************************
 *                     ******  INITIALISE VARS  *******
 ************************************************************************
 */

/* quick hack - need this info before onload */
/* get args */
getArgs();


nick = jid.substring(0,jid.indexOf('@'));


function updateStyleIE() {
  if (roster)
    roster.updateStyleIE();
}
