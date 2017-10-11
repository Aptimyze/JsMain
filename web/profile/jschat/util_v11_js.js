var chatHTML0="<div class='chat_box d_inl' style='margin-left:0px;  bottom:33px;'><div class='top_strip'><a  class='cur_pointer'><span class='closeNew rf' onclick='closeParentDiv(this,&quot;";
var chatHTML000="&quot;,true);'></span></a><a  class='cur_pointer' id='pop'><span  class='pop_out rf' onclick='popOut(this,&quot;";
var chatHTML0000="&quot;);'></span></a><a  class='cur_pointer'><span class='minusNew rf' onclick='minimizeParentDiv(this);'></span></a></div><div class='chat_img lf'><img src='";
var chatHTML00="' alt='' title='person image' id='profile_pic' class='circleimage' height='46px' width='46px'/></div><div class='lf per_info'><div class='lf c_id' id='user_name'><a  style='cursor:pointer' onclick='viewprofile(&quot;";
var chatHTML1="&quot;)'>";
var chatHTML12="</a></div><div class='lf gray' style='margin: 11px 8px 4px -60px; cursor: pointer;visibility:hidden;' ";
var chatHTML2=">Block user</div>";
var chatHTML2_2="</div><div class='clear'></div><div id='chat_show' class='lf d_inl' style='width:99%; height:162px; background:#fff; margin-left:2px; display:none; float:right; margin-right:1px;'><div style='height:146px; width:188px; overflow:auto; padding-left: 5px;' id='";

var chatHTML3="'></div><div><form name='chatform' style='border:0px;margin:0px;padding:0px;'><input type='hidden' name='to' value='";
var chatHTML4="'><input type='hidden' name = 'thread' value='";
var chatHTML4_1="'><input type='hidden' name = 'username' value='";
var chatHTML5="'><textarea id='msgbox' wrap='virtual' class='type_chat_active' rows='2' cols='2'  onKeyPress='return msgboxKeyPressed(this,event);' onKeyDown='return msgboxKeyDown(this,event,0);'></textarea></form></div></div><div class='clear'></div><div id='";
var chatHTML6="' class='chat_panel' style='text-align: center; display:block;overflow:auto;overflow-x:hidden'><img style='margin-top: 27px;' title='Loading Jeevansathi chat' alt='Loading Jeevansathi chat' src='IMG_URL/profile/browser/images/loader_small.gif'/><div class='mar_top_6 b'>Requesting permission to chat with ";
var chatHTML7="</div><div class='mar_top_6 b'><span name='countDownTime' id='countDownTime'>300</span> seconds left</div></div>                </div>";
var first_mes_show='<div class="b" style="overflow: auto;height: 141px; width: 188px;text-align:left;padding: 5px 0px 0px 5px;" >Write a message to start chatting.</div><div><form style="border: 0px none ; margin: 0px; padding: 0px;" name="firstform"><input type="hidden" value="FIRST_aJID" name="to"><input type="hidden" value="FIRST_THREAD" name="thread"><input type="hidden" value="FIRST_USERNAME" name="username"><textarea id="msgbox"  onKeyPress="return msgboxKeyPressed(this,event,1);"  cols="2" rows="2" class="type_chat_active" wrap="virtual" ></textarea></form></div></div>';
var toBeSentId;
var jid;
var subscript;  
var profileId;
var userCheckSum;
var onlineusers;
var dpp_onlineusers;
var fav_onlineusers;
var old_onlineusers;
var logined_userName;
var online_update_orNot=true;
var site_url="~$SITE_URL`";
var iframeHeight=0;
var start_chat_no=1;
var end_chat_no=2;

var first_request={};
var store_request={};
var result="";
var bot_name={};
function checkOnlineLink(){
	
}

function blockUser(param){ 
	var form=param.parentNode.parentNode.previousSibling.previousSibling.previousSibling.lastChild.firstChild;
	var jid=form.to.value;
   var iq = new JSJaCIQ();
   iq.setType('set');
   
  con.send(iq);
	
	
}

function showMoreOption(param){
	
}
function acceptOrDecline(from,param,param1,profileID){

	var aMessage = new JSJaCMessage();
	aMessage.setType('chatAuth');
	aMessage.setTo(from);
	aMessage.setBody(param);
	aMessage.setThread(profileId);
	con.send(aMessage);
	var randomnumber=Math.floor(Math.random()*1000001);//generate random number in between 1-100000
	if(param == "accept"){
		var user = roster.getUserByJID(from);
		user.chatAuth="accept";
		set_cookies(top.profileId,user.profileId,"A");
		js_window.getElementById("chat_auth_"+profileID).style.display="none";
		js_window.getElementById("chat_auth_"+profileID).previousSibling.previousSibling.style.display="block";		
		js_window.getElementById("chatUpdater").src="jsChat_chatRequest.php?senderId="+profileID+"&receiverId="+profileId+"&status=a&type=log_ad&randomNumber="+randomnumber;
		var sen_d=profileID;
		var rec_d=profileId;
		enable_block_quit("","quit_user_"+profileID+"","visible");
		var url_to_log="/profile/log_chat_data.php?REC="+rec_d+"&SEN="+sen_d+"&action=A";
		
		//Update the request table that request is accepted .
		top.send_ajax_request_chat(url_to_log,"","","POST");
		
		putMsgHistoryHTML(user);
	}else{
		var user = roster.getUserByJID(from);
		user.chatAuth=null;
                set_cookies(top.profileId,user.profileId,"N");
		closeParentDiv(param1,from);
		js_window.getElementById("chatUpdater").src="jsChat_chatRequest.php?senderId="+profileID+"&receiverId="+profileId+"&status=d&type=log_ad&randomNumber="+randomnumber;
		var sen_d=profileID;
		var rec_d=profileId;
		
	
		var url_to_log="/profile/log_chat_data.php?REC="+rec_d+"&SEN="+sen_d+"&action=D";
		//Update the request table that request is declined .
		top.send_ajax_request_chat(url_to_log,"","","POST");
	}
}


function viewprofile(username){
	if(username != null){
		document.getElementById("jeevansathi").src="viewprofile.php?username="+username;
		return false;
	}
}
function putMsgHTML(msg,myself) { 
  var iframe_id=msg.getThread();
	if(typeof(myself)=="undefined")
		myself=0;
var msgHTML = '';
  var nick='';
  var nickName='';
  var body = '';
  var chat_auth=null;
  var chat_iframe=null;
  var err = false;
  var user = null;
  var from=msg.getFrom();
  var to =msg.getTo();
  var userName=null;
	if(from == null){
		from=jid;
	}
	if(from.indexOf("/") != -1){
		from=from.substring(0,from.indexOf("/"));
	}
	if(isNaN(parseInt(from)))	
	{
		from=msg.getSubject();
	}
	if(from.indexOf("gmail") != -1){
		chat_auth="gtalk_chat_auth_"+iframe_id;
		chat_iframe="gtalk_chat_iframe_"+iframe_id;
		user=roster.getUserByJID(from);
	}else if(from !=jid){
		chat_auth="chat_auth_"+iframe_id;
		chat_iframe="chat_iframe_"+iframe_id;
		user=roster.getUserByJID(from);
	}else{
			
		if(isNaN(parseInt(to))){
			var sub=msg.getSubject();
			if(sub.indexOf("/") != -1)
				sub=sub.substring(0,sub.indexOf("/"));
			user=roster.getUserByJID(sub);
			iframe_id=user.profileId;
			chat_auth="gtalk_chat_auth_"+iframe_id;
			chat_iframe="gtalk_chat_iframe_"+iframe_id;
		}else{
			
			user=roster.getUserByJID(to);
			iframe_id=user.profileId;
			chat_auth="chat_auth_"+iframe_id;
			chat_iframe="chat_iframe_"+iframe_id;
		}
	}
	
	if(user != null){
		nickName=user.nickName;
		userName=user.userName;
	}
	if(msg.getType() == 'headline'){
		var body=msg.getBody();
		if(body == "accept"){
			var subscription="<div class='lf t11 p10' style='color: rgb(154, 153, 153);'>"+nickName+" has accepted your chat request and is now Available for chat.</div>";
			js_window.getElementById(chat_auth).style.display="none";
			js_window.getElementById(chat_auth).previousSibling.previousSibling.style.display="block";
			js_window.getElementById(chat_iframe).innerHTML +=subscription;
			user.chatW=chat_iframe;
			putMsgHistoryHTML(user);
			blinkOrNot(js_window,chat_iframe);
			return ;
		 }
		else if(body=="logout")
		{
			var subscription="<span>&nbsp;&nbsp;</span><div class='t12 b' style='margin: 10px; color: rgb(154, 153, 153);'>"+nickName+" has gone offline, you cannot chat any longer with the user </div>";
			enable_block_quit("","quit_user_"+iframe_id+"","hidden");
                        js_window.getElementById(chat_iframe).innerHTML +=subscription;
                        //js_window.getElementById(chat_iframe).parentNode.removeChild(js_window.getElementById(chat_iframe).nextSibling);
                        js_window.getElementById(chat_iframe).nextSibling.style.display='none';
                        user.msgHistory.length=0;
                        blinkOrNot(js_window,chat_iframe);
                        user.chatAuth=null;
			set_cookies(top.profileId,user.profileId,"N");
                        js_window.getElementById(chat_iframe).scrollTop=js_window.getElementById(chat_iframe).scrollHeight;
			return;
		}
		else if(body=="Busy")
		{
			var subscription="<span>&nbsp;&nbsp;</span><div class='t12 b' style='margin: 10px; color: rgb(154, 153, 153);'>The user is busy in chat with other user. </div><div class='b rf' style='margin-right: 10px;'><a class='blink' href='#' onclick='closeParentDiv(this,&quot;"+from+"&quot;)'>Close</a></div>";
			js_window.getElementById(chat_auth).innerHTML =subscription;
                        user.chatAuth=null;
			set_cookies(top.profileId,user.profileId,"N");
                        blinkOrNot(js_window,chat_iframe);
                        return ;

		}
		else if(body == "decline"){
			var subscription="<span>&nbsp;&nbsp;</span><div class='t12 b' style='margin: 10px; color: rgb(154, 153, 153);'>The user has declined to chat with you. </div><div class='b rf' style='margin-right: 10px;'><a class='blink' href='#' onclick='closeParentDiv(this,&quot;"+from+"&quot;)'>Close</a></div>";
	    	js_window.getElementById(chat_auth).innerHTML =subscription;
	   		user.chatAuth=null;
			set_cookies(top.profileId,user.profileId,"N");
	   		blinkOrNot(js_window,chat_iframe);
			return ;
		}else if(body == "ending"){
			if(myself)
				var subscription="<div class='chat_text t11' style='border: medium none ;'><span class='d_grey'>You have ended chat</span></div>";
			else
				var subscription="<div class='chat_text t11' style='border: medium none ;'><span class='d_grey'>"+nickName+"  has ended chat</span></div>";
			enable_block_quit("","quit_user_"+iframe_id+"","hidden");
			js_window.getElementById(chat_iframe).innerHTML +=subscription;
			//js_window.getElementById(chat_iframe).parentNode.removeChild(js_window.getElementById(chat_iframe).nextSibling);
			js_window.getElementById(chat_iframe).nextSibling.style.display='none';
			user.msgHistory.length=0;
			blinkOrNot(js_window,chat_iframe);
			user.chatAuth=null;
			set_cookies(top.profileId,user.profileId,"N");
			js_window.getElementById(chat_iframe).scrollTop=js_window.getElementById(chat_iframe).scrollHeight;
			return;
			}
	}else if(msg.getType() == 'chatAuth'){
		if(msg.getBody() == "ask_chatAuth"){
		top.document.getElementById("sound").innerHTML='<embed src="/profile/jschat/sounds/chat_queue.swf" width="1" height="1" quality="high" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash">';
		try{
			set_title(" "+nickName+" wants to chat with you");			
		}catch(e)
		{
		}
		var firstmes=msgFormat(msg.getFirstMes());
		if(firstmes)
		{
			user.msgHistory.push("you->"+firstmes);
		}
		//Enabling block option.
		enable_block_quit("block_user_"+iframe_id+"","","visible");	

		var me=logined_userName;
		var subscription="<div class='b d_grey' style='margin: 0px 10px 10px; line-height: 18px;'>";
		if(firstmes)
			subscription=subscription+"<div class='lf' style='color:#000000'>"+nickName+" :</div><div class='chat_text ' style='font-size:11px;text-align:left'>"+firstmes+"</div>";
			
		subscription=subscription+"<span class='black'><BR>Dear "+me+",</span><br/>You have a chat request from "+nickName+"<a class='blink'  style='cursor:pointer' onclick='viewprofile(&quot;"+nickName+"&quot;)'>( view profile)</a>.</div><div style='margin: auto; text-align: center; width: 188px;'><input class='chat_button accept' type='button' value='Accept' onclick='top.acceptOrDecline(&quot;"+from+"&quot;,&quot;accept&quot;,this,&quot;"+iframe_id+"&quot;);'/><input class='decline chat_button mar_left_10 d_inl' type='button' value='Decline' onclick='top.acceptOrDecline(&quot;"+from+"&quot;,&quot;decline&quot;,this,&quot;"+iframe_id+"&quot;);'/><BR></div>";
		js_window.getElementById(chat_iframe).parentNode.style.display="none";
		
		var randomnumber=Math.floor(Math.random()*1000001);
		js_window.getElementById("chatUpdater").src="jsChat_chatRequest.php?senderId="+iframe_id+"&receiverId="+profileId+"&status=x&type=log_ad&randomNumber="+randomnumber;
	
		
		if(js_window.getElementById(chat_auth)){
			js_window.getElementById(chat_auth).style.display="block";
			js_window.getElementById(chat_auth).innerHTML =subscription;
			js_window.getElementById(chat_iframe).nextSibling.style.display="block";
		}else if(js_window.getElementById(chat_iframe)){
			
		}
		user.chatW=chat_iframe;
		blinkOrNot(js_window,chat_iframe);
		
		return;
	}else if(msg.getBody() == 'time_out'){
	var subscription="<span>&nbsp;&nbsp;</span><div class='t12 b' style='margin: 10px; color: rgb(154, 153, 153);'>User "+userName+" has aborted the request. Please  <a class='blink' href='#' onclick='sendChatRequest(&quot;"+from+"&quot;,this)'>click here </a>to invite him for a chat</div>";
	js_window.getElementById(chat_auth).innerHTML =subscription;
	blinkOrNot(js_window,chat_iframe);
	user.canChat=null;
	if(user.chatAuth=="pending"){
		user.chatAuth="time_out";
		set_cookies(top.profileId,user.profileId,"T");
	}else{
		user.chatAuth=null;
		set_cookies(top.profileId,user.profileId,"N");
	}
	return ;
	
	}else if(msg.getBody()== "accept"){
		
		var subscription="<div class='lf t11 p10' style='color: rgb(154, 153, 153);'>"+nickName+" has accepted your chat request and is now Available for chat.</div>";
		user_quit=msg.getThread();
		enable_block_quit("block_user_"+user_quit+"","quit_user_"+user_quit+"","visible");
		//eval("var iduser=document.getElementById('quit_user_"+user_quit+"');");
                //iduser.innerHTML="quit chat";
		js_window.getElementById(chat_auth).style.display="none";
		js_window.getElementById(chat_auth).previousSibling.previousSibling.style.display="block";
	    js_window.getElementById(chat_iframe).innerHTML +=subscription;
		user.chatW=chat_iframe;
		putMsgHistoryHTML(user);
	    blinkOrNot(js_window,chat_iframe);
		return ;
	}else if(msg.getBody()== "decline"){
		var subscription="<span>&nbsp;&nbsp;</span><div class='t12 b' style='margin: 10px; color: rgb(154, 153, 153);'>The user has declined to chat with you. </div><div class='b rf' style='margin-right: 10px;'><a class='blink' href='#' onclick='closeParentDiv(this,&quot;"+from+"&quot;)'>Close</a></div>";
	    js_window.getElementById(chat_auth).innerHTML =subscription;
	    blinkOrNot(js_window,chat_iframe);
	    user.chatAuth=null;
	    set_cookies(top.profileId,user.profileId,"N");
		return ;
	}else if(msg.getBody()== "invalid"){
                var subscription="<span>&nbsp;&nbsp;</span><div class='t12 b' style='margin: 10px; color: rgb(154, 153, 153);'>Buy membership to initiate chat with jeevansathi users. </div><div class='b rf' style='margin-right: 10px;'><a class='blink' href='#' onclick='closeParentDiv(this,&quot;"+from+"&quot;)'>Close</a></div>";
            js_window.getElementById(chat_auth).innerHTML =subscription;
            blinkOrNot(js_window,chat_iframe);
            user.chatAuth=null;
	    set_cookies(top.profileId,user.profileId,"N");
                return ;
        }
	else if(msg.getBody()== "REINITIATE"){
                var subscription="<span>&nbsp;&nbsp;</span><div class='t12 b' style='margin: 10px; color: rgb(154, 153, 153);'>Lost connection to this user. Please re-initate chat request. </div><div class='b rf' style='margin-right: 10px;'></div>";
            js_window.getElementById(chat_iframe).innerHTML =subscription;
	    js_window.getElementById(chat_iframe).nextSibling.style.display='none';
            blinkOrNot(js_window,chat_iframe);
            user.chatAuth=null;

	    //Disabling quit option
	    enable_block_quit("","quit_user_"+user.profileId+"","hidden");	

            set_cookies(top.profileId,user.profileId,"N");
	   if(js_window.getElementById(chat_auth).style.display == "block"){
                                js_window.getElementById(chat_auth).style.display="none";
                                js_window.getElementById(chat_auth).previousSibling.previousSibling.style.display="block";
                        }
                return ;
        }
	}else if(msg.getType() == "logout"){
		 if(msg.getBody() =="@logout"){
			var subscription="<div class='chat_text t11' style='border: medium none ;'><span class='d_grey'>"+nickName+" has gone offline, you cannot chat any longer with the user</span></div>";
			js_window.getElementById(chat_iframe).innerHTML +=subscription;
			js_window.getElementById(chat_iframe).nextSibling.style.display="none";
			blinkOrNot(js_window,chat_iframe);
			user.chatAuth=null;
			set_cookies(top.profileId,user.profileId,"N");
			js_window.getElementById(chat_iframe).scrollTop=js_window.getElementById(chat_iframe).scrollHeight;
			if(js_window.getElementById(chat_auth).style.display == "block"){
				js_window.getElementById(chat_auth).style.display="none";
				js_window.getElementById(chat_auth).previousSibling.previousSibling.style.display="block";
			}
			
			return;
		}
	}
	
	
	
	if (msg.getType() == 'error') {
		var error = aJSJaCPacket.getNode().getElementsByTagName('error').item(0);
		if (error && error.getElementsByTagName('text').item(0))
			body = error.getElementsByTagName('text').item(0).firstChild.nodeValue;
		err = true;
	}	else
		body = msg.getBody();

	var now;
	if (msg.jwcTimestamp)
		now = msg.jwcTimestamp;
	else
		now = new Date();

	var mtime = (now.getHours()<10)? "0" + now.getHours() : now.getHours();
	mtime += ":";
	mtime += (now.getMinutes()<10)? "0" + now.getMinutes() : now.getMinutes();
	mtime += ":";
	mtime += (now.getSeconds()<10)? "0" + now.getSeconds() : now.getSeconds();
	
	body = msgFormat(body);
	if(err){
		msgHTML += "<span style='color:red;'>&nbsp;";
	}else if(from == jid){
		 msgHTML +="<div class='b lf' style='padding-right:5px;'>me:</div>";
		user.msgHistory.push("me->"+body);
	}else{
		 msgHTML +="<div class='b lf' style='margin-right:5px;'>"+nickName+":</div>";
		user.msgHistory.push("you->"+body);
	}
	
	
	msgHTML +="<div class='chat_text'>"+body+"</div>";
	if (err)
		msgHTML += '</span>';
	
//************added end*******************************************
	if(js_window.getElementById(chat_iframe)){
		js_window.getElementById(chat_iframe).innerHTML +=msgHTML;
		js_window.getElementById(chat_iframe).scrollTop=js_window.getElementById(chat_iframe).scrollHeight;
		blinkOrNot(js_window,chat_iframe);
	}
}


function putMsgHistoryHTML(user){
	var chat_iframe='';
	var msgHTML='';
	if(user.msgHistory.length<=0)
		return;
	else{
		var jabberID=user.fulljid;
		if(jabberID.indexOf("gmail") != -1)
			chat_iframe="gtalk_chat_iframe_"+jabberID.substring(0,jabberID.indexOf("@"));
		else
			chat_iframe="chat_iframe_"+jabberID.substring(0,jabberID.indexOf("@"));
			
		for(var i=0; i< user.msgHistory.length;i++){
			var msg=user.msgHistory[i];
			if(msg.substring(0,2) =='me'){
				msgHTML +="<div class='b lf' style='padding-right:5px;'>me:</div>";
				msgHTML +="<div class='chat_text'>"+msg.substring(4,msg.length)+"</div>";
			}else{
				msgHTML +="<div class='b lf' style='margin-right:5px;'>"+user.nickName+":</div>";				
				msgHTML +="<div class='chat_text'>"+msg.substring(5,msg.length)+"</div>";
			}
		}
			js_window.getElementById(chat_iframe).innerHTML +=msgHTML;
	
			
	}	
	
}

function popMsgs(user) {

	if(user){
	 
	  while (user.chatmsgs.length>0) {
		var msg;
		if (is.ie5||is.op) {
		  msg = user.chatmsgs[0];
		  user.chatmsgs = user.chatmsgs.slice(1,user.chatmsgs.length);
		} else
		  msg = user.chatmsgs.shift();
		putMsgHTML(msg);

	  }
	  
	}
}


function openUserInfo() {
	return jwcMain.openUserInfo(user.jid);
}

function openUserHistory() {
	return jwcMain.openUserHistory(user.jid);
}

function updateUserPresence() {
	
}

function go_for_chat_init(el)
{
	var body=el.form.elements["msgbox"].value;
	var ajid=el.form.to.value;
	var profileID=el.form.thread.value;
	var fr_key=ajid+""+profileID;
	var sc_key=profileID+""+ajid;
	var user=first_request[sc_key];
	if(ajid.indexOf("gmail") == -1)
		eval("var chat_id=document.getElementById('chat_auth_"+profileID+"')");
	else
		eval("var chat_id=document.getElementById('gtalk_chat_auth_"+profileID+"')");
		chat_id.innerHTML=first_request[fr_key]+"<div style='text-align:left'><BR><BR><b> me: </b>"+body+"</div>";
		user=roster.getUserByJID(ajid);
		chatInit(ajid,profileID,user,body);	
		user.msgHistory.push("me->"+body);
	
}
function submitClicked(el) {
	var body=el.form.elements["msgbox"].value;
	if (body == '') // don't send empty message
		return false;
	var aMessage = new JSJaCMessage();
	aMessage.setType('chat');
	var ajid=el.form.to.value;
	var profileID=el.form.thread.value;
	aMessage.setUserName(logined_userName);
	//aMessage.setThread(profileID);
	aMessage.setThread(profileId);
	if(ajid.indexOf("gmail") == -1){
		aMessage.setTo(ajid);
	} else{
		rec_user=el.form.username.value;
		aMessage.setTo(bot_name[rec_user]); 
		//aMessage.setTo(ajid);
		aMessage.setSubject(ajid+"/"+logined_userName);

	}
	
	
	aMessage.setBody(body);
	con.send(aMessage);
	// insert into chat window
	putMsgHTML(aMessage);

	// add message to our message history
	jwcMain.addtoHistory(body);
	el.form.elements["msgbox"].value='';
	el.form.elements["msgbox"].focus();
	return false;
}
function quitchat(user_prof) {
	
//        var body=el.form.elements["msgbox"].value;
        var aMessage = new JSJaCMessage();
        aMessage.setType('headline');
        var ajid=user_prof;
        var profileID=user_prof+"@"+JABBERSERVER;
        aMessage.setUserName(logined_userName);
        //aMessage.setThread(profileID);
        aMessage.setThread(profileId);
	aMessage.setTo(profileID);
        aMessage.setBody("ending");
        con.send(aMessage);
        // insert into chat window
        putMsgHTML(aMessage,1);
	enable_block_quit("","quit_user_"+ajid+"","hidden");
        // add message to our message history
        return false;
}
function enable_block_quit(block_id,quit_id,visible_or_not)
{
	var quitid="";
	var blockid="";
	if(block_id)
	{
		blockid=document.getElementById(block_id);
	}
	if(quit_id)
		var quitid=document.getElementById(quit_id);
	if(quitid)
	{
		quitid.style.visibility=visible_or_not;
	}
	if(blockid)
		blockid.style.visibility=visible_or_not;
}

//var user;
var chatAuth;
var srcW;
var cFrame;
var scrollHeight=0;
var countDownInterval= null;

function chatInit(ajid,profileID,userName,body_mes,no_history) {

var user;
var chat_auth=null;
var chat_iframe=null;
cDate = new Date();
if(typeof(no_history)=='undefined')
	no_history=0;

user = roster.getUserByJID(ajid);
if(!user) {

user = roster.addUser(new RosterUser(cutResource(ajid)));
user.userName=userName;

jwcMain.roster=roster;
}	
user.profileId=profileID;
if(ajid.indexOf("gmail") != -1){
	chat_auth="gtalk_chat_auth_"+profileID;
	chat_iframe="gtalk_chat_iframe_"+profileID;
}else{
	chat_auth="chat_auth_"+profileID;
	chat_iframe="chat_iframe_"+profileID;
}
chatAuth=user.chatAuth;

//Enable it when regressed throughly..
/*if(typeof(current_auth(top.profileId,user.profileId))!="undefined")
{
	chatAuth=current_auth(top.profileId,user.profileId);
//	set_cookies(top.profileId,user.profileId,"P");	
	
}

else if(chatAuth==null)
	set_cookies(top.profileId,user.profileId,"N");
else if(chatAuth=="accept")
	set_cookies(top.profileId,user.profileId,"A");
else if(chatAuth=="decline")
	set_cookies(top.profileId,user.profileId,"D");	
else if(chatAuth=="ending")
	set_cookies(top.profileId,user.profileId,"E");	
else if(chatAuth=="pending")
	set_cookies(top.profileId,user.profileId,"P");
*/
if(chatAuth ==null || chatAuth =="ending" || chatAuth== "decline"){
	authCheck(ajid,profileID,body_mes);
	if(countDownInterval ==null){
		countDownInterval = setInterval("setCountDownTime()", 1000);
	}
}else if(chatAuth =="accept"){
		js_window.getElementById(chat_auth).style.display="none";
		js_window.getElementById(chat_auth).previousSibling.previousSibling.style.display="block";
		js_window.getElementById(chat_auth).style.display="none";
		if(!no_history)
			putMsgHistoryHTML(user);
			
		//Enabling back block quit	
		enable_block_quit("block_user_"+profileID,"quit_user_"+profileID,"visible");
}else if(chatAuth =="pending"){
	iframe_id=profileID;
	var nick=userName;
	var me=logined_userName;
	var subscription="<div class='b d_grey' style='margin: 0px 10px 10px; line-height: 18px;'><span class='black'>Dear "+me+",</span><br/>You have a chat request from "+nick+"<a class='blink' href='#'>( view profile)</a>.</div><div style='margin: auto; text-align: center; width: 188px;'><input class='chat_button accept' type='button' value='Accept' onclick='top.acceptOrDecline(&quot;"+ajid+"&quot;,&quot;accept&quot;,this,&quot;"+iframe_id+"&quot;);'/><input class='chat_button decline mar_left_10 d_inl' type='button' value='Decline' onclick='top.acceptOrDecline(&quot;"+ajid+"&quot;,&quot;decline&quot;,this,&quot;"+iframe_id+"&quot;);'/></div>";
	js_window.getElementById(chat_auth).innerHTML =subscription;
	blinkOrNot(js_window,chat_iframe);

}




  user.chatW=chat_iframe;
  popMsgs(user);
}
var af_ajid;
var af_aMessage;
var af_GTALK_BOT_NAME;

function authCheck(ajid,profileID,body_mes){
		
	var aMessage = new JSJaCMessage();
	aMessage.setBody("ask_chatAuth");
	aMessage.setThread(profileId);
	aMessage.setImage(userCheckSum);
	aMessage.setUserName(logined_userName);
	var rec_d=ajid.substring(0,ajid.indexOf("@"));
	var sen_d=profileId;
	var mes="";
	
	
	if(typeof(body_mes)!='undefined')
	{
			mes=escape(body_mes);
				
	
			aMessage.setFirstMes(body_mes);
	}
	var randomnumber=Math.floor(Math.random()*1000001);
	
	var url_to_log="/profile/log_chat_data.php?REC="+rec_d+"&SEN="+sen_d+"&entry=1&MES="+mes+"&random="+randomnumber;
	af_aMessage=aMessage;
	af_ajid=ajid;
	//Log initial request sent to users.
	//Only send initiation message if logging is succesfull.
	send_ajax_request_chat(url_to_log,"","send_init()","POST");
	js_window.getElementById("chatUpdater").src="jsChat_chatRequest.php?senderId="+profileId+"&receiverId="+profileID+"&type=chat_request&randomNumber="+randomnumber;
	
	
}
function send_init()
{

	var aMessage=af_aMessage;
	var ajid=af_ajid;
	rec_user = roster.getUserByJID(af_ajid);
	if(ajid.indexOf("gmail") == -1){
                aMessage.setType('chatAuth');
                aMessage.setTo(ajid);
        } else{
                aMessage.setType('chat');
                aMessage.setTo(bot_name[rec_user.userName]);
                aMessage.setSubject(ajid+"/"+logined_userName);
        }

        var user = roster.getUserByJID(ajid);
        if(!user) {
                user = roster.addUser(new RosterUser(aJid));
                jwcMain.roster=roster;
        }
        user.chatAuth=null;
	set_cookies(top.profileId,user.profileId,"N");
        con.send(aMessage);
}
function resetCountDownTime(){
	countDownInterval = setInterval("setCountDownTime()", 1000);
}

function sendSessionTimeOut(to,rec_user){
	var aMessage = new JSJaCMessage();
		aMessage.setThread(profileId);
		aMessage.setImage(profileId);
		aMessage.setUserName(logined_userName);
		if(to.indexOf("gmail") == -1){
			aMessage.setBody("time_out");
			aMessage.setType('chatAuth');
			aMessage.setTo(to);
		}else{
			
			aMessage.setType('chat');
			aMessage.setTo(bot_name[rec_user]);
			aMessage.setBody("time_out");
			aMessage.setSubject(to+"/"+logined_userName);
		}
		con.send(aMessage);	 	
}
function setCountDownTime(){
	var countDownWindows= js_window.getElementsByName("countDownTime");
	if(countDownWindows.length >0){
		for(var q=0;q<countDownWindows.length;q++){
			var countDownWIndow=countDownWindows[q];
			if(countDownWIndow.parentNode.parentNode.style.display=="block"){
				var secondLeft=countDownWIndow.innerHTML;
				if(secondLeft == "0"){
					var form=countDownWIndow.parentNode.parentNode.previousSibling.previousSibling.lastChild.firstChild;
					var to=form.to.value;
					var rec_user=form.username.value;
					var subscription="<span>&nbsp;&nbsp;</span><div class='t12 b' style='margin: 10px; color: rgb(154, 153, 153);'>The user hasn’t responded. Please click here to <a class='blink' href='#' onclick='closeParentDiv(this,&quot;"+to+"&quot;)'>Close</a> the window  or <a class='blink' href='#' onclick='sendChatRequest(&quot;"+to+"&quot;,this)'>retry</a></div>";
					countDownWIndow.parentNode.parentNode.innerHTML =subscription;
					sendSessionTimeOut(to,rec_user);
					return ;
				}else{
					countDownWIndow.innerHTML=parseInt(secondLeft)-1;
				}
			}
		}
	}else{
		clearInterval(countDownInterval);
		countDownInterval=null;
		
	}
}
function displayTimestamp() {

}


function msgboxKeyPressed(el,e,FirstMes) {
        var keycode;
        
		var body=el.form.elements["msgbox"].value;	
		if(typeof(FirstMes)!="undefined")
			el.form.elements["msgbox"].value=body.substring(0,500);
		
        if (window.event) { 
                e  = window.event; 
                keycode = window.event.keyCode; 
        }
        else if (e){ 
                 keycode = e.which;
             }else return true;

	switch (keycode) {
	case 13:
		if (e.shiftKey) {
				return false;
			
		} else
		{
			if(FirstMes)
			{
				go_for_chat_init(el);
			}
			else
			return submitClicked(el);
		}
		break;
	}
	return true;
}

function msgboxKeyDown(el,e) {
	var keycode;
	if (window.event) { e  = window.event; keycode = window.event.keyCode; }
	else if (e) keycode = e.which;
	else return true;

	switch (keycode) {
	case 38:				// shift+up
		if (e.ctrlKey) {
			el.value = jwcMain.getHistory('up', el.value);
			el.focus(); el.select();
		}
		break;
	case 40:				// shift+down 
		if (e.ctrlKey) {
			el.value = jwcMain.getHistory('down', el.value);
			el.focus(); el.select();
		}
		break;
	case 76:
		if (e.ctrlKey) {   // ctrl+l
			chat.document.body.innerHTML = '';
			return false;
		}
		break;
	case 27:
		window.close();
		break;
	}
	return true;
}


function sendChatRequest(aJid,el){
	var profileID=aJid.substring(0,aJid.indexOf("@"));
	var user = roster.getUserByJID(aJid);
	var iframe_id=null;
	if(aJid.indexOf("gmail") != -1){
		iframe_id="gtalk_chat_auth_"+profileID;
	}else{
		iframe_id="chat_auth_"+profileID;
	}		
	if(subscript.length == 0){
		if(aJid.indexOf("gmail") != -1){
			js_window.getElementById(iframe_id).innerHTML="<div class='mar_top_6 b fontlig' style='color: #666666;font-size: 13px;padding: 0px 5px; margin-top:20px'>To initiate chat, you need to be a premium member.</div><div class='mar_top_6 b' style='text-align:center;     background-color: #dc4e5c;margin-top: 10px; padding; top:5px;width: 150px;color: white; margin-left:25px;height: 20px;'><a style='color: white;'  href='/profile/mem_comparison.php?from_source=from_source=CHAT_UPGRADE' target='_blank'>View membership plans</a> </div>";
		}else{
			js_window.getElementById(iframe_id).innerHTML="&nbsp;&nbsp;&nbsp;</br><div class='mar_top_6 b fontlig' style='color: #666666;font-size: 13px;font-family: roboto; padding: 0px 5px; margin-top:20px'>To initiate chat, you need to be a premium member.</div><div class='mar_top_6 b' style='text-align:center;     background-color: #dc4e5c; margin-top: 10px;padding-top:5px;width: 150px;color: white;margin-left:25px;height: 20px;'><a style='color: white;' href='/profile/mem_comparison.php?from_source=from_source=CHAT_UPGRADE' target='_blank'>View membership plans</a> </div>";
		}
	}else{
			var key_json=profileID+"PARAM";
			var param_scr=store_request[key_json];
			key_json=profileID+"username";
			var username_scr=store_request[key_json];
			key_json=profileID+"have_photo";
			var have_photo_scr=store_request[key_json];
			key_json=profileID+"ajaxResponse";
			var ajaxResponse_scr=store_request[key_json];
			closeParentDiv(el,aJid);
			openWindow(aJid,param_scr,profileID,username_scr,have_photo_scr,ajaxResponse_scr);
		
	}
}


function sendCleanUPMsg(ajid,profileID){
	
	var user = roster.getUserByJID(ajid);
	if(user == null)
		user = roster.addUser(new RosterUser(cutResource(ajid)));//check
		
	var iframe_id= null;
	if(ajid.indexOf("gmail") != -1){
		iframe_id="gtalk_chat_auth_"+profileID;
	}else{
		iframe_id="chat_auth_"+profileID;
	}
	if(js_window.getElementById(iframe_id).lastChild.firstChild==null)
		return;	
	var countDownTime1=js_window.getElementById(iframe_id).lastChild.firstChild.innerHTML;
	if( countDownTime1 && countDownTime1 != 0 && user.chatAuth == null && subscript.length != 0){		
		var aMessage = new JSJaCMessage();
		aMessage.setBody("time_out");
		aMessage.setThread(profileId);
		aMessage.setImage(profileId);
		aMessage.setUserName(logined_userName);
		if(ajid.indexOf("gmail") == -1){
			aMessage.setType('chatAuth');
			aMessage.setTo(ajid);
		}else{
			aMessage.setType('chat');
			aMessage.setTo(bot_name[user.userName]);
			aMessage.setSubject(ajid+"/"+logined_userName);
		}
		con.send(aMessage);
	}else{
		
		if (user.chatAuth == "pending" || user.chatAuth == "time_out"){
			var randomnumber=Math.floor(Math.random()*1000001);
			js_window.getElementById("chatUpdater").src="jsChat_chatRequest.php?senderId="+profileID+"&receiverId="+profileId+"&status=d&type=log_ad&randomNumber="+randomnumber;
		}
		
	}					
	return;	
}
function cleanUp(ref,param) {
	var ajid=ref;
	var profileID=ref.substring(0,ref.indexOf("@"));
	if(ref!=null && roster!=null)
	{
		var user=roster.getUserByJID(ref);
		
		if(user.chatAuth==null)
		{
			user.chatW=null;
			return;
		}
	}
	if(param == true){	
		sendCleanUPMsg(ajid,profileID);
	}		
	
	
	
	if(ref != null && roster != null){
		var user=roster.getUserByJID(ref);
		//roster.removeUser(user);
		if(user)
			user.chatW=null;
	}
	
}

function makeWindowName(wName) {
  wName = wName.replace(/@/,"at");
  wName = wName.replace(/\./g,"dot");
  wName = wName.replace(/\//g,"slash");
  wName = wName.replace(/&/g,"amp");
  wName = wName.replace(/\'/g,"tick");
  wName = wName.replace(/=/g,"equals");
  wName = wName.replace(/#/g,"pound");
  wName = wName.replace(/:/g,"colon");	
  wName = wName.replace(/%/g,"percent");
  wName = wName.replace(/-/g,"dash");
  wName = wName.replace(/ /g,"blank");
  wName = wName.replace(/\*/g,"asterix");
  return wName;
}

var jwcMain;
var roster;
var con;
var js_window;
function refresh(){
	if(roster ==null && con == null){
	var child_window=document.getElementById("testiframe");
	var child =child_window.contentWindow;
	jwcMain=child;
    roster=child.roster;
	con=child.con;
	jid=child.jid;
	}
	js_window=this.window.document;
	if(window.addEventListener){
		window.addEventListener("beforeunload", checkforcookies, false);
	}
	
	var jeevansathi_window=document.getElementById("jeevansathi").contentWindow;
	jeevansathi_window.focus();
	
}

function populateChatWindow(){
	if(con){
		var singleChat=readCookie("singleChat");
		if(singleChat != null){			
			if(roster ==null ){
			var child_window=document.getElementById("testiframe");
			var child =child_window.contentWindow;
			jwcMain=child;
			roster=child.roster;
			}
			
			var JID_ARRAY=singleChat.split(",");
			for(var i=0;i<JID_ARRAY.length;i++){
				if(JID_ARRAY[i] != ""){
					
					var profileInformation_array=JID_ARRAY[i].split("|");
					var jid=profileInformation_array[0];
					var profileID=jid.substring(0,jid.indexOf("@"));
					var userName=profileInformation_array[1];
					var chatAuth=profileInformation_array[2];
					
					var user = roster.getUserByJID(jid);
					if(!user) {
						user = roster.addUser(new RosterUser(jid));
						user.userName=userName;
						user.chatAuth=chatAuth;
						jwcMain.roster=roster;
					}
					openWindow(jid,"",profileID,userName);
				}
			}
		}
	}
}


function checkLogin_afterStart(){
	if(!con || !con.connected()){
		js_window.getElementById("browseBottom").removeChild(js_window.getElementById("browseBottom").firstChild);
		var str="<div class='wid75chat'><div class='chat_bar'><div class='content'><div class='chat_box_cont clearfix'><div class='chat_bubble lf'></div> <div id='pre_div' class='v_h'>    <div id='pre_invisible_icon' style='display:inline;cursor: pointer; margin-left:57px;' class='prev_grey_arr lf d_inl' onclick='top.show_pre_invisible();'></div><div class='lf t11 b' style='margin:10px 0 0 2px; width:12px;'><a  class='prev_text' id='pre_invisible_cnt'>0</a></div></div><div id='tab_pre_invisible' style='float:left;'></div><div id='tab_wrapper' style='float:left;'></div><div id='tab_post_invisible' style='float:right;'></div><div id='post_div' class='v_h'><div class='lf t11 b' style='margin:10px 0 0 10px'><a  class='prev_text' id='post_invisible_cnt'>0</a></div><div id='post_invisible_icon' style='display:inline;cursor: pointer; cursor: hand;' class='next_grey_arr lf d_inl' onclick='top.show_post_invisible();'>&nbsp;</div></div><div style='float:right; width:450px; margin-right:-21px; z-index:10000; position:relative;font-size:10px; margin-top:10px'><div id='chat_logout' style='cursor:pointer; margin-left:10px; padding-left:10px; width:106px; border-left:1px solid #929292' class='go_offline_icon b blink rf color11 f11 fontreg txtc'  onclick='top.logOut();'>[Logout from chat]<div id='go_offline' class='go_offline_tooltip' ><img src='IMG_URL/profile/browser/images/go_offline_tooltip.gif' ></div></div> <span id='SHOW_DPP'><div class='go_offline_icon b blink rf color11 f11 fontreg color11' style='cursor:pointer; margin-left:10px; padding-left:10px;width:auto;border-left:1px solid #929292' onclick='onlineDBPSearch()'><span id='DPP_number' onmouseover='DPPMouseOver()' onmouseout='DPPMouseOut()' class='prev_text blink b'>[Desired Partner Profiles]</span><div id='DPP' class='desired_partner_tooltip'><img src='~$IMG_URL`/profile/browser/images/desired_partner_tooltip.gif'></div></div></span><div class='rf b' style='cursor:pointer;' onclick='searchBand_onlineprofile()'><span id='prof' class='prev_text blink b'>Online Now </span><span id='onlineUser' class='prev_text blink b'></span></div></div></div></div></div></div><div class='clear'> </div><div id='chat_pre_invisible' style='display:none'></div><div class='chat_box_cont'><div id='chat_wrapper' style='width:300px;'></div></div><div id='chat_post_invisible' style='display:none'></div></div>";
		js_window.getElementById("browseBottom").innerHTML=str;
	}
}

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
    messageHistory.push(message);
  historyIndex = messageHistory.length;
}

var invisible_pre_count=0;
var invisible_post_count=0;
var tab_count=0;

function leftShiftChatWindow(js_window){
		var st_index=start_chat_no;
		var st_wrap="";
		var lt_wrap="";
		for(st_index=start_chat_no;st_index<end_chat_no;st_index++)
		{
			st_wrap="chat_wrapper_"+st_index;
			lt_wrap="chat_wrapper_"+(st_index+1);
			js_window.getElementById(st_wrap).innerHTML=js_window.getElementById(lt_wrap).innerHTML;
		}
}

function rightShiftChatWindow(js_window){
		var st_index=end_chat_no;
		var st_wrap="";
		var lt_wrap="";
		for(st_index=end_chat_no;st_index>start_chat_no;st_index--)
		{
			st_wrap="chat_wrapper_"+st_index;
			lt_wrap="chat_wrapper_"+(st_index-1);
			js_window.getElementById(st_wrap).innerHTML=js_window.getElementById(lt_wrap).innerHTML;
		}
	
}

function closeOpenedChatWindow(param){
	for(var i=1; i<= param ; i++){
		js_window.getElementById("chat_wrapper_"+i).firstChild.style.display="none";		
	}
}

function addTabToPostInvisible(){
	var id="tab_post_invisible_"+invisible_post_count;
	var gen_id="tab_wrapper_"+end_chat_no;
	js_window.getElementById(gen_id).className="normal_chat lf";
	var chat_id="<div id='"+id+"' style='display:none'>"+js_window.getElementById(gen_id).innerHTML+"</div>";
	js_window.getElementById("tab_post_invisible").innerHTML +=chat_id;
}

function removeTabFromPostInvisible(){
	var lastChildElement=js_window.getElementById("tab_post_invisible").lastChild;
	var str=lastChildElement.innerHTML;
	var gen_id="tab_wrapper_"+end_chat_no;
	js_window.getElementById(gen_id).innerHTML=str;
	js_window.getElementById("tab_post_invisible").removeChild(js_window.getElementById("tab_post_invisible").lastChild);
}
function addTabToPreInvisible(){
	var id="tab_pre_invisible_"+invisible_pre_count;
	var gen_id="tab_wrapper_"+start_chat_no;
	js_window.getElementById(gen_id).className="normal_chat lf";
	var chat_id="<div id='"+id+"' style='display:none'>"+js_window.getElementById(gen_id).innerHTML+"</div>";
	js_window.getElementById("tab_pre_invisible").innerHTML +=chat_id;
}

function removeTabFromPreInvisible(){
	var lastChildElement=js_window.getElementById("tab_pre_invisible").lastChild;
	var str=lastChildElement.innerHTML;
	var gen_id="tab_wrapper_"+start_chat_no;
	js_window.getElementById(gen_id).innerHTML=str;
	js_window.getElementById("tab_pre_invisible").removeChild(js_window.getElementById("tab_pre_invisible").lastChild);
}

function addChatToPostInvisible(){
	invisible_post_count=invisible_post_count+1;
	var id="chat_post_invisible_"+invisible_post_count;
	var gen_id="chat_wrapper_"+end_chat_no;
	js_window.getElementById(gen_id).firstChild.style.display="none";
	var chat_id="<div id='"+id+"'>"+js_window.getElementById(gen_id).innerHTML+"</div>";
	js_window.getElementById("chat_post_invisible").innerHTML +=chat_id;
	js_window.getElementById("post_invisible_cnt").innerHTML=invisible_post_count;
}

function removeChatFromPostInvisible(){
	var lastChildElement=js_window.getElementById("chat_post_invisible").lastChild;
	var str=lastChildElement.innerHTML;
	var gen_id="chat_wrapper_"+end_chat_no;
	js_window.getElementById(gen_id).innerHTML=str;
	js_window.getElementById("chat_post_invisible").removeChild(js_window.getElementById("chat_post_invisible").lastChild);
	invisible_post_count=invisible_post_count-1;
	js_window.getElementById("post_invisible_cnt").innerHTML=invisible_post_count;
	
	if(invisible_post_count ==0){
		js_window.getElementById("post_div").className="v_h";
	}
}



function addChatToPreInvisible(){
	invisible_pre_count=invisible_pre_count+1;
	var id="chat_pre_invisible_"+invisible_pre_count;
	var gen_id="chat_wrapper_"+start_chat_no;
	js_window.getElementById(gen_id).firstChild.style.display="none";
	var chat_id="<div id='"+id+"'>"+js_window.getElementById(gen_id).innerHTML+"</div>";
	js_window.getElementById("chat_pre_invisible").innerHTML +=chat_id;
	js_window.getElementById("pre_invisible_cnt").innerHTML=invisible_pre_count;
}

function removeChatFromPreInvisible(){
	var lastChildElement=js_window.getElementById("chat_pre_invisible").lastChild;
	var str=lastChildElement.innerHTML;
	var gen_id="chat_wrapper_"+start_chat_no;
	js_window.getElementById(gen_id).innerHTML=str;
	js_window.getElementById("chat_pre_invisible").removeChild(js_window.getElementById("chat_pre_invisible").lastChild);
	invisible_pre_count=invisible_pre_count-1;
	js_window.getElementById("pre_invisible_cnt").innerHTML=invisible_pre_count;
	if(invisible_pre_count ==0){
		js_window.getElementById("pre_div").className="v_h";
	}
} 

function checkWheatherOpenedOrNot(js_window,aJid,profileID){
	
	var user=roster.getUserByJID(aJid);
	if(user){
		var popOut=user.popout;
		if(popOut){
			//popOut.open();
			popOut.focus();
			return true;
		}
	}
	
	
	var iFrmaeNode=null;
	var chat_iframe=null;
	//var chat_auth=null;
	 
	
	
	if(aJid.indexOf("gmail") != -1){
			chat_iframe="gtalk_chat_iframe_"+profileID;
		}else{
			chat_iframe="chat_iframe_"+profileID;		}
	if(js_window.getElementById("chat_wrapper") != ""){	
				iFrmaeNode=js_window.getElementById(chat_iframe);
				if(iFrmaeNode != null){
				var chat_window_id=iFrmaeNode.parentNode.parentNode.parentNode.parentNode.id;
				if(chat_window_id.indexOf("chat_pre_invisible") != -1){
					var count=chat_window_id.replace("chat_pre_invisible_","");
					count=parseInt(count);
					for(var q=invisible_pre_count;q>=count;q--){
						show_pre_invisible();
					}
					closeOpenedChatWindow(tab_count);
					normalizePreviousTab();
					var gen_id="chat_wrapper_"+start_chat_no;
					
					js_window.getElementById(gen_id).firstChild.style.display="block";
					gen_id="tab_wrapper_"+start_chat_no;
					js_window.getElementById(gen_id).className="bot_name_bg lf ";
					js_window.getElementById(gen_id).lastChild.className="c_id_btm t12 d_inl";
					
					return true;
				}else if(chat_window_id.indexOf("chat_post_invisible") != -1){
					var count=chat_window_id.replace("chat_post_invisible_","");
					count=parseInt(count);
					for(var q=invisible_post_count;q>=count;q--){
						show_post_invisible();
					}
					closeOpenedChatWindow(tab_count);
					normalizePreviousTab();
					var gen_id="chat_wrapper_"+end_chat_no;
					js_window.getElementById(gen_id).firstChild.style.display="block";
					gen_id="tab_wrapper_"+end_chat_no;
					js_window.getElementById(gen_id).className="bot_name_bg lf ";
					js_window.getElementById(gen_id).lastChild.className="c_id_btm t12 d_inl";
					return true;
				}else{
					var tab_id=chat_window_id.replace("chat", "tab");
					closeOpenedChatWindow(tab_count);
					normalizePreviousTab();
					js_window.getElementById(chat_window_id).firstChild.style.display="block";
					js_window.getElementById(tab_id).className="bot_name_bg lf ";
					js_window.getElementById(tab_id).lastChild.className="c_id_btm t12 d_inl";
					return true;
				}
		}
	}
	return false;
}

//var jwcMain;
//var js_window;
function calculateImageId(profileID){
	var photochecksum_new = parseInt(parseInt(profileID)/1000) +"/" +MD5(new String(parseInt(profileID)+5));
	return photochecksum_new;
}
function openWindow(aJid,param,profileID,userName,have_photo,ajaxResponse){
	if(typeof(ajaxResponse)!='undefined')
	{
		var key_json=profileID+"PARAM";
		store_request[key_json]=param;
		key_json=profileID+"username";
		store_request[key_json]=userName;
		key_json=profileID+"have_photo";
		store_request[key_json]=have_photo;
		key_json=profileID+"ajaxResponse";
		store_request[key_json]=ajaxResponse;
		
	}
	if(roster ==null && con == null){
	var child_window=document.getElementById("testiframe");
	var child =child_window.contentWindow;
	jwcMain=child;
	roster=child.roster;
	con=child.con;
	jid=child.jid;
	}
		
	var chatDivId;
	var chatHTML=null;
	var img_src= null;
	var nick=nickNameOf(userName);
	js_window=this.window.document;
	
	if(aJid.indexOf("/") != -1){
		aJid=aJid.substring(0,aJid.indexOf("/"));
	}
	
	if(aJid.indexOf("@") == -1){
		aJid=aJid+"@"+JABBERSERVER;
	}
	
	toBeSentId=aJid;
	
	var user = roster.getUserByJID(toBeSentId);
	if(!user) {
		user = roster.addUser(new RosterUser(aJid));
		user.userName=userName;
		if(param != '')
			user.imageId=param;
		jwcMain.roster=roster;
	}


	if(param != '' ){
		 
		 img_src="/profile/chatPhoto.php?profilechecksum="+param;
	}else if(user.imageId != null){
		 
		 img_src="/profile/chatPhoto.php?profilechecksum="+user.imageId;
	}
	
	var chatHTML_notPaid;
	user.nickName=userName;
	checkLogo(aJid);
	var bol=checkWheatherOpenedOrNot(js_window,aJid,profileID);
	if(bol == false && js_window.getElementById("chat_wrapper") != null){
		chatDivId="chat_wrapper_"+profileID;
//		var block_user='block_user_'+profileID;
		if(aJid.indexOf("gmail") != -1)
		{
			chatHTML00_1=" id='gmail_block_user_"+profileID+"' onclick='javascript:block_unblock(\""+profileID+"\")' ";
			
		}
		else
		{
			chatHTML00_1=" id='block_user_"+profileID+"' onclick='javascript:block_unblock(\""+profileID+"\")' ";
                      
		}
		if(aJid.indexOf("gmail") != -1){
			chatHTML=chatHTML0+aJid+chatHTML0000+img_src+chatHTML00+nick+chatHTML1+userName+chatHTML12+chatHTML00_1+chatHTML2+chatHTML2_2+"gtalk_chat_iframe_"+profileID+chatHTML3+toBeSentId+chatHTML4+profileID+chatHTML4_1+userName+chatHTML5+"gtalk_chat_auth_"+profileID+chatHTML6+userName+chatHTML7;
			user.chatW="gtalk_chat_iframe_"+profileID;
		}else{
			chatHTML=chatHTML0+aJid+chatHTML0000+img_src+chatHTML00+nick+chatHTML1+userName+chatHTML12+chatHTML00_1+chatHTML2+chatHTML2_2+"chat_iframe_"+profileID+chatHTML3+toBeSentId+chatHTML4+profileID+chatHTML4_1+userName+chatHTML5+"chat_auth_"+profileID+chatHTML6+userName+chatHTML7;
			user.chatW="chat_iframe_"+profileID;
		}
		var within_limit=1;
		var st_index=start_chat_no;
		while(st_index<=end_chat_no)
		{
			var gen_id="chat_wrapper_"+st_index;
			
			if(js_window.getElementById(gen_id)==null)
			{
					var cnt=st_index-1;
					
					var margin_left=((cnt*92)-99)+"px";
					closeOpenedChatWindow(cnt);
					var str="<div id='"+gen_id+"' style='margin-left:"+margin_left+"'>"+"<div id='"+chatDivId+"'  style='display:block;'>"+chatHTML;
			
					js_window.getElementById("chat_wrapper").innerHTML +=str;
					within_limit=0;
					st_index=end_chat_no;
					
		    }
			st_index++;
	    }
		
	if(within_limit){
		js_window.getElementById("pre_div").className="v_v";		
		invisible_pre_count=invisible_pre_count+1;
		var id="chat_pre_invisible_"+invisible_pre_count;
		var gen_id="chat_wrapper_"+start_chat_no;
		js_window.getElementById(gen_id).firstChild.style.display="none";
		var chat_id="<div id='"+id+"' style='display:none'>"+js_window.getElementById(gen_id).innerHTML+"</div>";
		js_window.getElementById("chat_pre_invisible").innerHTML +=chat_id;
		
		leftShiftChatWindow(js_window);	
		var str="<div id='"+chatDivId+"'  display:block'>"+chatHTML;		
		closeOpenedChatWindow(end_chat_no-1);
		var gen_id="chat_wrapper_"+end_chat_no;
		js_window.getElementById(gen_id).innerHTML=str;
		js_window.getElementById("pre_invisible_cnt").innerHTML=invisible_pre_count;
		
	}
	
	var chat_auth=null;
	if(aJid.indexOf("gmail") != -1){
		chat_auth="gtalk_chat_auth_"+profileID;
	}else{
		chat_auth="chat_auth_"+profileID;
	}
	openTab(nick,aJid);
	
	if(user.canChat == null && subscript.length == 0){
		js_window.getElementById(chat_auth).innerHTML="&nbsp;&nbsp;&nbsp;</br><div class='mar_top_6 b fontlig' style='color: #666666;font-size: 13px;font-family: roboto; padding: 0px 5px; margin-top:20px'>To initiate chat, you need to be a premium member.</div><div class='mar_top_6 b' style='text-align:center;     background-color: #dc4e5c; margin-top: 10px;padding-top:5px;width: 150px;color: white;margin-left:25px;height: 20px;'><a style='color: white;' href='/profile/mem_comparison.php?from_source=from_source=CHAT_UPGRADE' target='_blank'>View membership plans</a> </div>";
		return true;
	}
	if(typeof(ajaxResponse)!='undefined')
	{	
		if(ajaxResponse.MES && ajaxResponse.MES !="" && ajaxResponse.MES != "Yes, you can chat"){
		
			user.chatAuth=null;
			set_cookies(top.profileId,user.profileId,"F");
			var subscription="<span>&nbsp;&nbsp;</span><div class='t12 b' style='margin: 10px; color: rgb(154, 153, 153);'>"+ajaxResponse.MES+"</div><div class='b rf' style='margin-right: 10px;'><a class='blink' href='#' onclick='closeParentDiv(this,&quot;"+aJid+"&quot;)'>Close</a></div>";
		    js_window.getElementById(chat_auth).innerHTML =subscription;
	   	
			return ;
		
		}else{
			var user_check = roster.getUserByJID(aJid);
			var allow_resp=1;
			if(user_check)
			{
				if(user_check.chatAuth=='accept')
				{
					allow_resp=0;
					chatInit(aJid,profileID,userName,undefined,1);
				}
			}
			if(typeof(ajaxResponse)!='undefined' && allow_resp==1)
			{
				if(ajaxResponse.NewBeta!=null)
				{
					if(ajaxResponse.NewBeta==0)
						bot_name[userName]=GTALK_BOT_NAME;
					else
						 bot_name[userName]=userName+"@"+JABBERSERVER;
				}
				if(ajaxResponse.FRST_MES==1)
				{
					var key_json=aJid+""+profileID;
					first_request[key_json]=document.getElementById(chat_auth).innerHTML;
					var key_json=profileID+""+aJid;
					first_request[key_json]=userName;
					var chat_auth_id=document.getElementById(chat_auth);
					var tmp_dt=first_mes_show.replace("FIRST_aJID",aJid);
					tmp_dt=tmp_dt.replace("FIRST_THREAD",profileID);
					tmp_dt=tmp_dt.replace("FIRST_USERNAME",userName);
					chat_auth_id.innerHTML=tmp_dt;
				}
				else
					chatInit(aJid,profileID,userName);
			
			
			}	
			else	
				chatInit(aJid,profileID,userName);
		}
	}
	else
		chatInit(aJid,profileID,userName);
	}
check_block_status(profileID);
//	abc(id_user);
	return false;
	
}
function block_unblock(id_user)
{
	eval("var iduser=document.getElementById('block_user_"+id_user+"');");
	if(iduser)
	{
		if(iduser.innerHTML=="Block user")
		{
			iduser.innerHTML="Unblock";
			iduser.style.color='red';
		}
		else
		{
			iduser.innerHTML="Block user";
	                iduser.style.color='gray';
		}
		var requestedURL="/profile/block_user.php?cid="+id_user;
		top.send_ajax_request_chat(requestedURL,"","","GET");	
	}
}
function check_block_status(id_user)
{
	var requestedURL="/profile/block_user.php?cid="+id_user+"&jst_check=1";
	var func="updateblock_status("+id_user+")";
	//eval("var iduser=document.getElementById('quit_user_"+id_user+"');");
	//iduser.innerHTML="";
	
	send_ajax_request_chat(requestedURL,"",func,"GET");
}
function updateblock_status(id_user)
{
	eval("var iduser=document.getElementById('block_user_"+id_user+"');");
	if(iduser)
		if(result==1)
		{
			iduser.innerHTML="Unblock";
			iduser.style.color='red';	
		}
		else
		{
			iduser.innerHTML="Block user";
			iduser.style.color='gray';
		}
		
}
function goToMembershipPage(){
	window.location.href="/profile/mem_comparison.php";
}

function show_pre_invisible(){
	if(invisible_pre_count >0){
		js_window.getElementById("post_div").className="v_v";
		addChatToPostInvisible();
		rightShiftChatWindow(js_window);
		removeChatFromPreInvisible();
		shiftRightTab();
		if(js_window.getElementById("pre_invisible_icon").className== "prev_orng_arr lf d_inl cur_pointer"){
			var tab_gen_id="tab_wrapper_"+start_chat_no;
			var chat_gen_id="chat_wrapper_"+start_chat_no;
		  var ele=js_window.getElementById(tab_gen_id);
		  if(ele.lastChild.className== "lf blink_white"){
		  	js_window.getElementById("pre_invisible_icon").className="prev_grey_arr lf d_inl";
		  	
			if(js_window.getElementById(chat_gen_id).firstChild.style.display=="none"){
				js_window.getElementById(tab_gen_id).className="blink_chat lf";
			}else{
				
			    js_window.getElementById(tab_gen_id).lastChild.className="c_id_btm t12 d_inl";
			}
		 }
		}
	}
}

function show_post_invisible(){
	if(invisible_post_count>0){
		js_window.getElementById("pre_div").className="v_v";
		addChatToPreInvisible();
		leftShiftChatWindow(js_window);
		removeChatFromPostInvisible();
		shiftLeftTab();
	}
	
	if(js_window.getElementById("post_invisible_icon").className== "next_orng_arr lf d_inl cur_pointer"){
		  var tab_gen_id="tab_wrapper_"+end_chat_no;
		  var chat_gen_id="chat_wrapper_"+end_chat_no;
		  var ele=js_window.getElementById(tab_gen_id);
		  if(ele.lastChild.className== "lf blink_white"){
		  	js_window.getElementById("post_invisible_icon").className="next_grey_arr lf d_inl";
		  	if(js_window.getElementById(chat_gen_id).firstChild.style.display=="none"){
		  		js_window.getElementById(tab_gen_id).className="blink_chat lf";
		    }else{
		    	
			    js_window.getElementById(tab_gen_id).lastChild.className="c_id_btm t12 d_inl";
			}
			
		 }
		}
}

function openTab(nick,aJid){
	var tabElement=js_window.getElementById("tab_wrapper").innerHTML;
	if(tab_count <end_chat_no){
		normalizePreviousTab();
		tab_count=tab_count+1;
		var toBeAddedTab="<div id='tab_wrapper_"+tab_count+"' class='bot_name_bg lf ' style='position:relative; z-index:10;' onclick='top.showChatWindow("+tab_count+")'><div id='tabName' class='c_id_btm t12 d_inl'>"+nick+"</div></div>";
		js_window.getElementById("tab_wrapper").innerHTML +=toBeAddedTab;
	}else{
		normalizePreviousTab();
		shiftTab(nick,aJid);
		var gen_id="tab_wrapper_"+end_chat_no;
		js_window.getElementById(gen_id).className="bot_name_bg lf ";
	}
}

function normalizePreviousTab(){
	for(var i=tab_count ; i>0 ; i--){ 
		var wrap_id = "tab_wrapper_"+i;
		js_window.getElementById(wrap_id).className="normal_chat lf";
		js_window.getElementById(wrap_id).lastChild.className="c_id_btm t12 d_inl";
	} 
}

function leftShiftTabBar(js_window){
	var st_id="";
	var end_id="";
	for(st_index=start_chat_no;st_index<end_chat_no;st_index++)
	{
		st_id="tab_wrapper_"+st_index;
		end_id="tab_wrapper_"+(st_index+1);
		js_window.getElementById(st_id).className=js_window.getElementById(end_id).className;
		js_window.getElementById(st_id).innerHTML=js_window.getElementById(end_id).innerHTML;
	}	
		
	js_window.getElementById(end_id).className="normal_chat lf";
	
	
	
}

function rightShiftTabBar(js_window){
	var st_id="";
	var end_id="";
	for(st_index=end_chat_no;st_index>start_chat_no;st_index--)
	{
		st_id="tab_wrapper_"+st_index;
		end_id="tab_wrapper_"+(st_index-1);
		js_window.getElementById(st_id).className=js_window.getElementById(end_id).className;
		js_window.getElementById(st_id).innerHTML=js_window.getElementById(end_id).innerHTML;
	}	
	js_window.getElementById(end_id).className="normal_chat lf";	
	
}
function shiftTab(nick,aJid){ //equvalent to shiftLeftTab
	addTabToPreInvisible();	
	leftShiftTabBar(js_window);
	var toBeAddedTab="<div id='tabName' class='c_id_btm t12 d_inl'>"+nick+"</div>";
	var gen_id="tab_wrapper_"+end_chat_no;
	js_window.getElementById(gen_id).innerHTML=toBeAddedTab;
}

function shiftRightTab(){
	addTabToPostInvisible();
	rightShiftTabBar(js_window);
	removeTabFromPreInvisible();
}

function shiftLeftTab(){
	addTabToPreInvisible();
	leftShiftTabBar(js_window);
	removeTabFromPostInvisible();
}

function showChatWindow(tabCount){
	var id="chat_wrapper_"+tabCount;
	var tab="tab_wrapper_"+tabCount;
	
	var st_index=start_chat_no;
	var chat_gen_id="";
	var tab_gen_id="";
	while(st_index<=end_chat_no)
	{
		chat_gen_id="chat_wrapper_"+st_index;
		tab_gen_id="tab_wrapper_"+st_index;
		st_index++;
		if(js_window.getElementById(chat_gen_id)){
			js_window.getElementById(chat_gen_id).firstChild.style.display="none";
			js_window.getElementById(tab_gen_id).className="normal_chat lf";
		}
	}
	
	if(js_window.getElementById(tab)){
		js_window.getElementById(tab).className="bot_name_bg lf";
		js_window.getElementById(tab).lastChild.className="c_id_btm t12 d_inl";
	}
	if(js_window.getElementById(id))
		js_window.getElementById(id).firstChild.style.display="block";
	
}

function chatShift(count){
	for(var i=count; i<=end_chat_no ; i++){
		var q=i+1;
		if(js_window.getElementById("chat_wrapper_"+q)){
			js_window.getElementById("chat_wrapper_"+i).innerHTML=js_window.getElementById("chat_wrapper_"+q).innerHTML;
		}else{
			js_window.getElementById("chat_wrapper").removeChild(js_window.getElementById("chat_wrapper_"+i));
			break;
		}
	 }
}

function tabShift(count){
	for(var i=count; i<=end_chat_no ; i++){
		var q=i+1;
		
		if(js_window.getElementById("tab_wrapper_"+q)){
			js_window.getElementById("tab_wrapper_"+i).className=js_window.getElementById("tab_wrapper_"+q).className;
			js_window.getElementById("tab_wrapper_"+i).innerHTML=js_window.getElementById("tab_wrapper_"+q).innerHTML;
		}else{
			js_window.getElementById("tab_wrapper").removeChild(js_window.getElementById("tab_wrapper_"+i));
			break;
		}
	 }
}
function minimizeParentDiv(ref){
	var id=ref.parentNode.parentNode.parentNode.parentNode.parentNode.id;
	var count=id.replace("chat_wrapper_","");
	var tab_id=id.replace("chat", "tab");
	js_window.getElementById(id).firstChild.style.display="none";
	js_window.getElementById(tab_id).className="normal_chat lf";
}

function closeCorrespondingDiv(jabberDivId,JID){
	cleanUp(JID);
	var id=jabberDivId;
	var jabberId=jabberDivId.replace("chat_wrapper_","");
	var count=id.replace("chat_wrapper_","");
	var tab_id=id.replace("chat", "tab");
	count=parseInt(count);
	if(invisible_post_count > 0){
		
		for(var i=count; i<= end_chat_no ; i++){
			
			if(i == end_chat_no){
				var chat_gen_id="chat_wrapper_"+i;
				var tab_gen_id="tab_wrapper_"+i;
				js_window.getElementById(chat_gen_id).innerHTML="";
				js_window.getElementById(tab_gen_id).className="normal_chat lf";
				js_window.getElementById(tab_gen_id).innerHTML="";
				removeTabFromPostInvisible();
				removeChatFromPostInvisible();
			}else{
				var q=i+1;
				js_window.getElementById("tab_wrapper_"+i).className=js_window.getElementById("tab_wrapper_"+q).className;
				js_window.getElementById("tab_wrapper_"+i).innerHTML=js_window.getElementById("tab_wrapper_"+q).innerHTML;
				js_window.getElementById("chat_wrapper_"+i).innerHTML=js_window.getElementById("chat_wrapper_"+q).innerHTML;
			}
		}
	
		
	}else if(invisible_pre_count > 0){
		for(var i=count; i >= 1 ; i--){
			if(i == 1 ){
				var chat_gen_id="chat_wrapper_"+i;
				var tab_gen_id="tab_wrapper_"+i;
				js_window.getElementById(chat_gen_id).innerHTML="";
				js_window.getElementById(tab_gen_id).className="normal_chat lf";
				js_window.getElementById(tab_gen_id).innerHTML="";	
				removeTabFromPreInvisible();
				removeChatFromPreInvisible();
					
			}else{
				var q=i-1;
				js_window.getElementById("tab_wrapper_"+i).className=js_window.getElementById("tab_wrapper_"+q).className;
				js_window.getElementById("tab_wrapper_"+i).innerHTML=js_window.getElementById("tab_wrapper_"+q).innerHTML;
				js_window.getElementById("chat_wrapper_"+i).innerHTML=js_window.getElementById("chat_wrapper_"+q).innerHTML;
			}
		}
	}else{
		chatShift(count);
		tabShift(count);
		tab_count=tab_count-1;
	}
	
	//sendLogOutMsgToGtalk
	
}

function closeParentDiv_fromTab(ref,JID){
	var id ;
	var param=true;
	if(ref == null)
	 return ;
	 if(JID != null)
		cleanUp(JID,param);
	if(ref.parentNode.parentNode){ //ref.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode
		id=ref.parentNode.parentNode.id;
	}else{
		 return ;
	}
	var count=id.replace("tab_wrapper_","");
	
	count=parseInt(count);
	util_tab(count);
	return;
}
function closeParentDiv(ref,JID,param){ 
	var id ;
	
	if(ref == null)
	 return ;
	 if(JID != null)
		cleanUp(JID,param);
	if(ref.parentNode.parentNode.parentNode.parentNode.parentNode){ //ref.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode
		id=ref.parentNode.parentNode.parentNode.parentNode.parentNode.id;
	}else{
		 return ;
	}
	var jabberDivId=js_window.getElementById(id).firstChild.id;
	var jabberId=jabberDivId.replace("chat_wrapper_","");
	var count=id.replace("chat_wrapper_","");
	var tab_id=id.replace("chat", "tab");
	count=parseInt(count);
	
	util_tab(count);
}

function util_tab(count){
	if(invisible_post_count > 0){
		
		for(var i=count; i<= end_chat_no ; i++){
			
			if(i == end_chat_no){
				var chat_gen_id="chat_wrapper_"+i;
				var tab_gen_id="tab_wrapper_"+i;
				js_window.getElementById(chat_gen_id).innerHTML="";
				js_window.getElementById(tab_gen_id).className="normal_chat lf";
				js_window.getElementById(tab_gen_id).innerHTML="";
				removeTabFromPostInvisible();
				removeChatFromPostInvisible();
			}else{
				var q=i+1;
				js_window.getElementById("tab_wrapper_"+i).className=js_window.getElementById("tab_wrapper_"+q).className;
				js_window.getElementById("tab_wrapper_"+i).innerHTML=js_window.getElementById("tab_wrapper_"+q).innerHTML;
				js_window.getElementById("chat_wrapper_"+i).innerHTML=js_window.getElementById("chat_wrapper_"+q).innerHTML;
			}
		}
	
		
	}else if(invisible_pre_count > 0){
		for(var i=count; i >= start_chat_no ; i--){
			if(i == start_chat_no ){
				var chat_gen_id="chat_wrapper_"+i;
				var tab_gen_id="tab_wrapper_"+i;
				js_window.getElementById(chat_gen_id).innerHTML="";
				js_window.getElementById(tab_gen_id).className="normal_chat lf";
				js_window.getElementById(tab_gen_id).innerHTML="";	
				removeTabFromPreInvisible();
				removeChatFromPreInvisible();
					
			}else{
				var q=i-1;
				js_window.getElementById("tab_wrapper_"+i).className=js_window.getElementById("tab_wrapper_"+q).className;
				js_window.getElementById("tab_wrapper_"+i).innerHTML=js_window.getElementById("tab_wrapper_"+q).innerHTML;
				js_window.getElementById("chat_wrapper_"+i).innerHTML=js_window.getElementById("chat_wrapper_"+q).innerHTML;
			}
		}
	}else{
		chatShift(count);
		tabShift(count);
		tab_count=tab_count-1;
	}
	
}
function popOut(ref,aJID){
	
	var wrap_id=ref.parentNode.parentNode.parentNode.parentNode.id;
	var tab_id=ref.parentNode.parentNode.parentNode.parentNode.parentNode.id;
	ref.parentNode.previousSibling.firstChild.className="";
	ref.parentNode.nextSibling.firstChild.className="";
	var toBeadd="<SPAN onclick='popIn(this,&quot;"+aJID+"&quot;);' class='pop_in rf' id='pop'/>";
	ref.parentNode.innerHTML=toBeadd;
	
	var user = roster.getUserByJID(aJID);	
	user.popout =window.open("popOutChat.php?jid="+escape(aJID)+"&divId="+tab_id,"chatW"+user.nickName,"width=202,height=254,resizable=no");
	
}

function MM_openBrWindow(theURL,winName,features) { //v2.0

  window.open(theURL,winName,features);
}


function popIn(ref,aJID){
	var wrap_id=ref.parentNode.parentNode.parentNode.parentNode.id;
	var tab_id=ref.parentNode.parentNode.parentNode.parentNode.parentNode.id;
	var toBeadd="<SPAN onclick='popOut(this,&quot;"+aJID+"&quot;);' class='pop_out rf' id='pop'/>";
	ref.parentNode.innerHTML=toBeadd;
	
	var cssText=js_window.getElementById(wrap_id).firstChild.style.cssText;
	  if(cssText.indexOf("155") != -1){
                cssText=cssText.replace("155px","33px");
                js_window.getElementById(wrap_id).firstChild.style.cssText=cssText;
                //var id=ref.id;
                var tab_id=tab_id.replace("chat","tab");
                js_window.getElementById(tab_id).className="bot_name_bg lf ";
       }


	
}

function getMsgHistory(aJid){
	return js_window.getElementById("chat_iframe_"+aJid.substring(0,aJid.indexOf("@"))).innerHTML;
}
function blinkOrNot(js_window,ref){
	var iFrmaeNode=js_window.getElementById(ref);
	if(iFrmaeNode != null){
		var chat_window_id=iFrmaeNode.parentNode.parentNode.parentNode.parentNode.id;
		  if(chat_window_id.indexOf("chat_wrapper_") != -1  && js_window.getElementById(chat_window_id).firstChild.style.display=="none"){
			var tab_id=chat_window_id.replace("chat","tab");
			js_window.getElementById(tab_id).className="blink_chat lf";
			js_window.getElementById(tab_id).lastChild.className="lf blink_white";
		  }else if(chat_window_id.indexOf("chat_pre_invisible_") != -1){
			
			var tab_id=chat_window_id.replace("chat","tab");
			js_window.getElementById(tab_id).lastChild.className="lf blink_white";
			js_window.getElementById("pre_invisible_icon").className="prev_orng_arr lf d_inl cur_pointer";
			
		 }else if(chat_window_id.indexOf("chat_post_invisible_") != -1){
		 	var tab_id=chat_window_id.replace("chat","tab");
			js_window.getElementById(tab_id).lastChild.className="lf blink_white";
			js_window.getElementById("post_invisible_icon").className="next_orng_arr lf d_inl cur_pointer";
		}
	}

}

function sendMsgBeforeLogOut(){
	if(roster != null && con != null){
		var users=roster.users;
		if(users != null){
			for(var i=0;i<users.length;i++){
				var user=users[i];
				var jabberId=user.jid;
				var chatW=user.chatW;
				if(user.chatAuth !="ending" && user.chatAuth !="decline"){
				if(jabberId.indexOf("gmail") == -1){
					var aMessage = new JSJaCMessage();
					aMessage.setType('logout');
					aMessage.setTo(jabberId);
					aMessage.setBody("@logout");
					aMessage.setThread(profileId);
					con.send(aMessage);
				}else{
					var aMessage = new JSJaCMessage();
					aMessage.setType('chat');
						
					aMessage.setTo(bot_name[user.userName]);
					aMessage.setSubject(jabberId+"/"+logined_userName);
					aMessage.setBody("has gone offline, you cannot chat any longer with the user.");
					aMessage.setThread(profileId);
					con.send(aMessage);
					
				}
			}
				if(user.popout)
					user.popout.close();
					
				roster.removeUser(user);
			}
		}
	}
	
	
	
	
	
}

function actualLogOut(){
	logOutVar="true";
	js_window.getElementById("browseBottom").removeChild(js_window.getElementById("browseBottom").firstChild);
	var str="<div class='wid75chat'><div class='chat_bar' style='background: transparent none repeat scroll 0% 0%;'><div class='content'><div class='chat_box_cont t11' style='width:200px;  height: 33px; float:right'><div style='padding: 5px;margin: 5px 10px 5px 0;cursor: pointer;background-color: #34495E;color: white;border-radius: 4px;cursor: pointer;' class='lf d_inl b' onclick='chatLogin();searchBand_onlineprofile();'><span id='prof' class='prev_text  b'> Available for chat </span><span id='onlineUser' class='prev_text  b'></span></div><div id='login' class='prev_text  b rf' style='padding: 5px;margin: 5px 5px 5px 0;cursor: pointer;background-color: #34495E;color: white;border-radius: 4px;cursor: pointer;' onclick='chatLogin();'>Login to chat</div></div></div><div class='clear'> </div></div></div>";
	js_window.getElementById("browseBottom").innerHTML=str;
	if(jwcMain && typeof("jswcMain.logout")=="function"){
		jwcMain.logout();
	}else{
		var child_window=document.getElementById("testiframe");
		var child =child_window.contentWindow;
		jwcMain=child;
		if(jwcMain && jwcMain.changeStatus )
		    jwcMain.changeStatus("offline");
	}
	js_window.getElementById("testiframe").src="jsChat_logOut.php?profileId="+profileId;
	/*if(navigator.appName.indexOf("Internet Explorer") != -1){
		document.getElementById("jeevansathi").height=parseInt(iframeHeight)+17;
	}else{
		document.getElementById("jeevansathi").height=parseInt(iframeHeight);
	}*/
	
	toBeSentId=null;
	jid=null;
	jwcMain=null;
	srcW=null; 
	cFrame=null;
	roster=null;
    con=null;
    invisible_pre_count=0;
	invisible_post_count=0;
	tab_count=0;
	resizeIframe("jeevansathi");
}

function cool(){
	
}
function logOut(){
	logOutVar="true";
	/*if(navigator.appName.indexOf("Internet Explorer") != -1){
		document.getElementById("jeevansathi").height=parseInt(iframeHeight)+17;
	}else{
		document.getElementById("jeevansathi").height=parseInt(iframeHeight);
	}*/
	sendMsgBeforeLogOut();
	resizeIframe("jeevansathi");
	setTimeout("actualLogOut()",1000);
	
}

var timeInterval;
function chatLogin(bb_vis,regis_true){
logOutVar="false";
var str=document.getElementById("testiframe").src;
var url_chat="/profile/jsChat.php";
if(typeof(regis_true)!='undefined')
	url_chat="/profile/jsChat.php?registration=true";

document.getElementById("testiframe").src=url_chat;
var str1="<div class='wid75chat'><div class='chat_bar' style='background:none;'><div class='content'><div class='chat_box_cont t11' style='width:200px; float:right; height:33px;><div class='chat_bubble lf mar_left_10'></div><div class='lf d_inl' style='margin:10px 0 0 500px'><span id='login' class='prev_text blink b'>Logging into chat</span></div><img src='IMG_URL/profile/browser/images/loader_small.gif' alt='loading' width='29' height='30' title='loading' /></div></div></div><div class='clear'> </div></div>";
document.getElementById("browseBottom").innerHTML=str1;
if(typeof(bb_vis)!='undefined')
	{
		document.getElementById("browseBottom").style.visibility='visible';
	}

}

//to be modified
function checkLoginedOrNot(){
	if(con){
		var str="<div class='wid75chat'><div class='chat_bar'><div class='content'><div class='chat_box_cont t11'><div class='chat_bubble lf'></div> <div id='pre_div' class='v_h'><div id='pre_invisible_icon' style='display:inline;cursor: pointer; margin-left:57px;' class='prev_grey_arr lf d_inl' onclick='top.show_pre_invisible();'></div><div class='lf t11 b' style='margin:10px 0 0 2px; width:12px;'><a  class='prev_text' id='pre_invisible_cnt'>0</a></div></div><div id='tab_pre_invisible' style='float:left;'></div><div id='tab_wrapper' style='float:left;'></div><div id='tab_post_invisible' style='float:right;'></div><div id='post_div' class='v_h'><div class='lf t11 b' style='margin:10px 0 0 10px'><a  class='prev_text' id='post_invisible_cnt'>0</a></div><div id='post_invisible_icon' style='display:inline;cursor: pointer; cursor: hand;' class='next_grey_arr lf d_inl' onclick='top.show_post_invisible();'>&nbsp;</div></div><div class='rf t11' style='margin:10px 0 0 2px'><span id='logOut'  class='prev_text blink'  style='cursor:hand;cursor:pointer' onclick='logOut();'>Go offline</span></div>  <div class='active_status_btm rf'></div>    <div class='rf t11 b' style='margin:10px 10px 0 5px; font-size:11px; onclick='searchBand_onlineprofile()'><span id='onlineUser'></span>&nbsp;&nbsp; Online Now</div></div></div></div><div class='clear'> </div><div id='chat_pre_invisible' style='display:none'></div><div class='chat_box_cont t11'><div id='chat_wrapper' style='width:300px;'></div></div><div id='chat_post_invisible' style='display:none'></div></div>";
		js_window.getElementById("browseBottom").innerHTML=str;
	}
}	




function nickNameOf(aJid){
	var nick=aJid;
	if(nick.length >=10){
		nick=nick.substring(0,8)+"...";
	}
	return nick;
}

function checkLogo(aJid){
	 if(aJid.indexOf("gmail") != -1){
                chatHTML11="</a></div><div >";
        }else{
                chatHTML11="</a></div><div>";
        }

}


function updateOnlineUsers(onlineUsers,online_bookmark_user,onlineUsers_DPP){
	
	if(js_window == null)
		js_window=this.window.document;
	if(js_window.getElementById("onlineUser") != null){
		js_window.getElementById("DPP_number").innerHTML=" [Desired Partner Profiles]";
	}else if(con){
		var str="<div class='wid75chat'><div class='chat_bar'><div class='content'><div class='chat_box_cont clearfix'><div class='chat_bubble lf'></div> <div id='pre_div' class='v_h'>    <div id='pre_invisible_icon' style='display:inline;cursor: pointer; margin-left:57px;' class='prev_grey_arr lf d_inl' onclick='top.show_pre_invisible();'></div><div class='lf t11 b' style='margin:10px 0 0 2px; width:12px;'><a  class='prev_text' id='pre_invisible_cnt'>0</a></div></div><div id='tab_pre_invisible' style='float:left;'></div><div id='tab_wrapper' style='float:left;'></div><div id='tab_post_invisible' style='float:right;'></div><div id='post_div' class='v_h'><div class='lf t11 b' style='margin:10px 0 0 10px'><a  class='prev_text' id='post_invisible_cnt'>0</a></div><div id='post_invisible_icon' style='display:inline;cursor: pointer; cursor: hand;' class='next_grey_arr lf d_inl' onclick='top.show_post_invisible();'>&nbsp;</div></div><div style='float:right; width:450px; margin-right:-21px; z-index:10000; position:relative;font-size:10px; margin-top:10px'><div id='chat_logout' style='cursor:pointer; margin-left:10px; padding-left:10px; width:106px; border-left:1px solid #929292' class='go_offline_icon b blink rf color11 f11 fontreg txtc'  onclick='top.logOut();'>[Logout from chat]<div id='go_offline' class='go_offline_tooltip' ><img src='IMG_URL/profile/browser/images/go_offline_tooltip.gif' ></div></div> <span id='SHOW_DPP'><div class='go_offline_icon b blink rf color11 f11 fontreg color11' style='cursor:pointer; margin-left:10px; padding-left:10px;width:auto;border-left:1px solid #929292' onclick='onlineDBPSearch()'><span id='DPP_number' onmouseover='DPPMouseOver()' onmouseout='DPPMouseOut()' class='prev_text blink b'>[Desired Partner Profiles]</span><div id='DPP' class='desired_partner_tooltip'><img src='~$IMG_URL`/profile/browser/images/desired_partner_tooltip.gif'></div></div></span><div class='rf b' style='cursor:pointer;' onclick='searchBand_onlineprofile()'><span id='prof' class='prev_text blink b'>Online Now </span><span id='onlineUser' class='prev_text blink b'></span></div></div></div></div></div></div><div class='clear'> </div><div id='chat_pre_invisible' style='display:none'></div><div class='chat_box_cont'><div id='chat_wrapper' style='width:300px;'></div></div><div id='chat_post_invisible' style='display:none'></div></div>";
		js_window.getElementById("browseBottom").innerHTML=str;
		if(iframeHeight != 0){
			if(navigator.appName.indexOf("Internet Explorer") != -1){
				document.getElementById("jeevansathi").height=parseInt(iframeHeight)-17;
			}else{
				document.getElementById("jeevansathi").height=parseInt(iframeHeight)-33;
			}	
		}
		if(searchId){
		}else{
		}	
		js_window.getElementById("DPP_number").innerHTML=" [Desired Partner Profiles]";
	}
}

function DPPMouseOver(){
	document.getElementById("DPP").className="desired_partner_tooltip_hov";
	
}
function DPPMouseOut(){
	document.getElementById("DPP").className="desired_partner_tooltip";
}
function fav_prof_mouseHover(){
	document.getElementById("fav_prof").className="fav_prof_tooltip_hov";
}
function fav_prof_mouseOut(){
	document.getElementById("fav_prof").className="fav_prof_tooltip";
}

function go_offline_mouseHover(){
	document.getElementById("go_offline").className="go_offline_tooltip_hov";
}
function go_offline_mouseOut(){
	document.getElementById("go_offline").className="go_offline_tooltip";
}

var searchId="";
function searchBand_onlineprofile(){
	online_update_orNot=false;
	if(searchId !=""){
		document.getElementById("jeevansathi").src="/search/perform?searchId="+searchId+"&onlineArr=1";
	}else{
		document.getElementById("jeevansathi").src="/search/perform?onlineArr=1&STYPE=O";
	}
}
function onlineDBPSearch(){
	document.getElementById("jeevansathi").src="/search/partnermatches?onlineArr=1&STYPE=O";
	if(document.getElementById("onlineUser") && document.getElementById("onlineUser").parentNode.onclick == null){
		top.document.getElementById("prof").className="prev_text blink b";
	}
}

function fav_prof_onlineprofile(){
	
	document.getElementById("jeevansathi").src="contacts_made_received.php?page=favorite&filter=M&onlineArr=1&chatBar=1";
	if(document.getElementById("onlineUser") && document.getElementById("onlineUser").parentNode.onclick == null){
		
		top.document.getElementById("prof").className="prev_text blink b";
	}
	
}

function both_function_after_logOut(){
	online_update_orNot=false;
	chatLogin();
	setTimeout("searchBand_onlineprofile()",1000);
}

//for cookies

function createCookie(name,value,min) {
	if (min) {
		var date = new Date();
		date.setTime(date.getTime()+(min*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	
	//document.cookie = name+"="+value+expires+"; path=/ ";
	document.cookie = name+"="+value+"; path=/ ";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}

function createChatCookie(){
	
	if(roster != null){
		var users=roster.users;
		var singleChat="";
		if(users != null){
			for(var i=0;i<users.length;i++){
				var user=users[i];
				var jabberId=user.jid;
				var chatW=user.chatW;
				//var profileId=user.profileId;
				var chatAuth=user.chatAuth;
				var userName=user.userName;
				var popOut=user.popout;
				if(chatW != null || popOut != null){
					singleChat +=jabberId +"|"+userName+"|"+chatAuth+",";
				}
			}
		}
		if(singleChat !=""){
			createCookie("singleChat",singleChat,35);
		}
	}
	
	
}

var xmlhttp;

function GetXmlHttpObject()
{
if (window.XMLHttpRequest)
  {
  // code for IE7+, Firefox, Chrome, Opera, Safari
  return new XMLHttpRequest();
  }
if (window.ActiveXObject)
  {
  // code for IE6, IE5
  return new ActiveXObject("Microsoft.XMLHTTP");
  }
return null;
}

function stateChanged()
{
if (xmlhttp.readyState==4)
{
document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
}
}

function checkAjaxResponse(responseText){
	
}



function ajaxChatRequest(aJid,param,profileID,userName,have_photo,checksum){
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  	return;
	
	var url="ajax_chatRequest.php";
	var randomnumber=Math.floor(Math.random()*1000001);
	url=url+"?receiversid="+profileId+"&sendersid="+profileID+"&senderusername="+userName+"&receiverusername="+logined_userName+"&checksum="+checksum+"&randomnumber="+randomnumber;
	
	xmlhttp.onreadystatechange=function() {
     if (xmlhttp.readyState==4) {
       var responseText=xmlhttp.responseText;
       
        eval(responseText);
      
        if (con == null || !con.connected()) {
		chatLogin();
		setTimeout("openWindow('"+aJid+"','"+param+"','"+profileID+"','"+userName+"','"+have_photo+"')",2000);
		return;
     }else
       	openWindow(aJid,param,profileID,userName,have_photo,ajaxResponse);
     
     }
   } 
	
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
}






function againUpdate(){
	
	if(online_update_orNot ==true){
		top.searchId="";
		top.document.getElementById("prof").className="prev_text blink b";
		if(top.document.getElementById("SHOW_DPP"))
		top.document.getElementById("SHOW_DPP").style.display='inline';
	}

}


function change_online_update_orNot(){
	if(top.online_update_orNot == false){
		top.online_update_orNot=true;
		top.searchId="";
		
		top.document.getElementById("prof").className="prev_text blink b";
		if(top.document.getElementById("SHOW_DPP"))
		top.document.getElementById("SHOW_DPP").style.display='inline';
	}
	
}
function send_ajax_request_chat(url,before_call_func,after_call_func,method)
{
	var ajaxRequest;  // The variable that makes Ajax possible!
	result="";
	if(method=="")
		method="GET";
        try
        {
                // Opera 8.0+, Firefox, Safari
                ajaxRequest = new XMLHttpRequest();
        }
        catch (e)
        {
                // Internet Explorer Browsers
                try
                {
                        ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
                }
                catch (e)
                {
                        try
                        {
                                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                        }
                        catch (e)
                        {
                                // Something went wrong
                                alert("Your browser broke!");
                                return false;
                        }
                }
        }
        // Create a function that will receive data sent from the server
        ajaxRequest.onreadystatechange = function()
        {
                if(ajaxRequest.readyState == 4)
                {
			if(ajaxRequest.status==200)
			{
				//Please defined this variable in the script tag where this send_ajax function is called, this is required since result is required at function called below
				result= ajaxRequest.responseText;
				if(after_call_func)
					eval(after_call_func);
				//Nikhil setting onclick on every a tagname
        	                //set_onclick_on_all_link();
        	        }
			else
			{
				result="A_E";
				if(after_call_func)
                                        eval(after_call_func);
			}
                }
        }
	if(method=='POST')
	{
		var send_params = url.replace(/^[^\?]+\??/,'');
		var check_url=url.replace(/[\?].+/,'');
		//send_params=send_params.substr(1,send_params.length);
		ajaxRequest.open("POST",check_url, true);
                ajaxRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                ajaxRequest.setRequestHeader("Content-length", send_params.length);
                ajaxRequest.setRequestHeader("Connection", "close");
                ajaxRequest.send(send_params);
	}
	else
	        ajaxRequest.open("GET",url, true);
	if(before_call_func)
		eval(before_call_func+"()");

	//This is required only for those ajax request that send data through GET
	if(method!='POST')
		ajaxRequest.send(null);
}
function set_title()
{

        if(top.isfocus==1)
        {
                top.document.title="Jeevansathi.com";
        }
        else
        {
                var arr_t=tit_mes.split("");
                var leng=arr_t.length;
                if(act_lth==0)
                        act_lth=leng;
                var str_t="";
                var blk_spc="";
                for(var i=0;i<(leng-act_lth);i++)
                        blk_spc=blk_spc+".";
                for(var i=0;i<act_lth;i++)
                {
                        str_t=str_t+arr_t[i];
                }
                act_lth--;

                top.document.title=blk_spc+str_t;
                setTimeout("set_title()",500);
        }
}
function update_title(subject,type,userName,body_content)
{

        if(type=="chatAuth")
        {
                if(body_content=="ask_chatAuth")
                {
                        tit_mes=" "+userName+" wants to chat with you";
                        //Below condtion is required in util class, to prevent unautorized request.. 
                        return ;
                }
                {
                        if(body_content=='decline')
                                tit_mes=" Chat request declined";
                        else if(body_content=='accept')
                                tit_mes=" Chat request accepted";
                }

        }

        top.document.getElementById("sound").innerHTML='<embed src="/profile/jschat/sounds/chat_queue.swf" width="1" height="1" quality="high" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash">';

        if(type=="chat")
        {
                tit_mes=" "+userName+" sends you message";
        }
        if(tit_mes=="")
                tit_mes="Jeevansathi.com";
        act_lth=0;
        top.set_title(tit_mes);

        //alert(window.focus());
}
function current_auth(co_sen,co_rec)
{
	var co_dta=readCookie("jws");
	var sub_arr={};
	sub_arr['A']="accept";
	sub_arr['D']="decline";
	sub_arr['T']="time_out";
	sub_arr['P']="pending";
	sub_arr['N']=null;
	sub_arr['F']="filter";

	if(co_dta==null)
		return null;
	var mat_str=""+co_sen+"([A-Z])"+co_rec+"";
	if(co_dta.match(mat_str)!=null)
	{
		var matches = co_dta.match(mat_str);
			return sub_arr[matches[1]];
	}
	return null;

}
function return_auth(co_sen,co_rec)
{
	var co_dta=readCookie("jws");

	if(co_dta==null)
		return null;
	var mat_str=""+co_sen+"([A-Z])"+co_rec+"";
	if(co_dta.match(mat_str)!=null)
	{
		var matches = co_dta.match(mat_str);
		return matches[1];
	}
	return null;
}
function set_cookies(co_sen,co_rec,type)
{
	var co_dta=readCookie("jws");
	if(co_dta==null)
		co_dta="";
	var cur_type=return_auth(co_sen,co_rec);	
	
	if(cur_type!=null || cur_type=="")
	{
		var src_str=eval("/"+co_sen+"[A-Z]"+co_rec+"/");
		var rep_str=co_sen+type+co_rec;
		co_dta=co_dta.replace(src_str,rep_str);	
	}
	else
		co_dta=co_dta+"::"+co_sen+type+co_rec;
	createCookie("jws",co_dta);
}


