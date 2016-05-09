package org.gmailsathi;

import org.jivesoftware.openfire.container.Plugin;
import org.jivesoftware.openfire.plugin.*;
import org.jivesoftware.openfire.container.PluginManager;
import org.jivesoftware.openfire.MessageRouter;
import org.jivesoftware.openfire.PresenceRouter;
import org.jivesoftware.openfire.PacketDeliverer;
import org.jivesoftware.openfire.XMPPServer;
import org.jivesoftware.openfire.container.Plugin;
import org.jivesoftware.openfire.container.PluginManager;
import org.jivesoftware.openfire.interceptor.InterceptorManager;
import org.jivesoftware.openfire.interceptor.PacketInterceptor;
import org.jivesoftware.openfire.interceptor.PacketRejectedException;
import org.jivesoftware.openfire.session.Session;
import org.jivesoftware.util.JiveGlobals;
import org.xmpp.packet.JID;
import org.xmpp.packet.Message;
import org.xmpp.packet.Packet;
import org.xmpp.packet.Presence;
import org.xmpp.packet.Presence.Type;
import org.dom4j.DocumentHelper;
import org.dom4j.Element;
import org.dom4j.QName;
import org.jivesoftware.database.DbConnectionManager;
import org.xmpp.component.ComponentManagerFactory;
import org.jivesoftware.openfire.handler.PresenceUpdateHandler;
import org.xmpp.packet.IQ;
import org.xmpp.packet.JID;
import org.jivesoftware.util.Log;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.util.Comparator;

import java.io.File;
import java.util.*;
import java.io.File;
import java.sql.ResultSet;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.sql.Statement;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import java.io.PrintWriter;
import java.io.StringWriter;
import java.lang.String;
import java.util.ArrayList;
import java.util.Collection;
import java.util.HashMap;
import java.lang.Math;

import org.jivesoftware.openfire.StreamID;
/**
 * A sample plugin for Openfire.
 */
public class GmailSathiPlugin implements Plugin,PacketInterceptor {

	    /**
     * used to send violation notifications
     */

     
     private PresenceRouter router;
     private MessageRouter messageRouter;
     public HashMap<String,String> map=new HashMap<String,String>();
     public HashMap<String,String> gmailfetch=new HashMap<String,String>();
     public HashMap<String,String> userfetch=new HashMap<String,String>();
     public HashMap<String,String> profilefetch=new HashMap<String,String>();
     public HashMap<String,String> chattingWithWhom=new HashMap<String,String>();
     public HashMap<String,String> chatAcceptList=new HashMap<String,String>();
     public HashMap<String,Long> timelastchat=new HashMap<String,Long>();
     public HashMap<Integer,Integer> dupreq=new HashMap<Integer,Integer>();
	 public String helpMsg1;
	 public String helpMsg2;
	 public long currenttime;

	 public ArrayList commandList;

	 
     
    public void initializePlugin(PluginManager manager, File pluginDirectory) {
        // Your code goes here
   		XMPPServer server = XMPPServer.getInstance();
		router = server.getPresenceRouter();
		messageRouter = XMPPServer.getInstance().getMessageRouter();
		InterceptorManager.getInstance().addInterceptor(this);
		
		this.helpMsg1="wants to chat with you.Visit http://www.jeevansathi.com/profile/viewprofile.php?username=";
		this.helpMsg2="to view their profile.To approve chat request, send 'yes'.To decline chat request, send 'no'";
		commandList = new ArrayList();
        commandList.add("@yes");commandList.add("'@yes'");
        commandList.add("@no");commandList.add("'@no'");
        commandList.add("yes");commandList.add("'yes'");
        commandList.add("no");commandList.add("'no'");
        commandList.add("@end");commandList.add("'no'");
        commandList.add("@show");commandList.add("'@show'");
        commandList.add("@hide");commandList.add("'@hide'");
    }

    public void destroyPlugin() {
		//records.clear();
		InterceptorManager.getInstance().removeInterceptor(this);
		gmailfetch.clear();
		profilefetch.clear();
		chattingWithWhom.clear();
		this.chattingWithWhomClear("ALL");
		timelastchat.clear();
		timelastchatClear("ALL");
		chatAcceptList.clear();
		chatAcceptListClear("ALL");
		userfetch.clear();
		
        // Your code goes here
    }
   public void interceptPacket(org.xmpp.packet.Packet packet,Session session,boolean incoming,boolean processed)  throws PacketRejectedException
   {
		try
		{

			currenttime=System.currentTimeMillis()/1000L;
			
			boolean js_user=true;
			String nick,profileID;
			

			
			if(packet instanceof Message  && !processed)
			{
				
				Message message=(Message) packet;
				//iF TO IS NOT DR
				//Log.warn("message text-->"+message.toXML());
				if(message.getTo()==null || message.getFrom()==null)
					return;
				
				//Getting first message tag and username tag
				org.dom4j.Element element = packet.getElement();
				String firstmes=element.elementText("firstmes");
				String username=element.elementText("userName");
				
				JID toJID=message.getTo();
				JID fromJID=message.getFrom();
				String body=message.getBody();
				String thread=message.getThread();
				String subject=message.getSubject();
				String msg=message.getBody();
				if(msg==null)
				{
						if((fromJID.getDomain()).equals("gmail.com"))
							return;
				}	
				msg=msg.trim();
				String gmailID;
				
				
				//If message is from plugin to gmail
			
			
				if(!isParsableToInt(fromJID.getNode()) && (fromJID.getDomain()).equals(XMPPServer.getInstance().getServerInfo().getXMPPDomain()))
					return;
					
				//If both are js user	
				if(isParsableToInt(fromJID.getNode()) && isParsableToInt(toJID.getNode()))
					return;
			
				//If message is from plugin to js user
				if(isParsableToInt(toJID.getNode()) &&  (fromJID.getDomain()).equals("gmail.com"))
					return;
			
				if(subject==null)
					js_user=false;
					
				//Will return if not containg sender/receiver as sathi 
				if(!(toJID.getNode().equals("sathi") || fromJID.getNode().equals("sathi")))
				return;
				
				//Logging any message coming.
				if(message.getBody()!=null)
					Log.warn("Sathi->"+message.getTo()+"-->"+message.getFrom()+"-->"+message.getBody());
				
				if(message.getType()!=Message.Type.chat)
				{
					if(message.getType()==Message.Type.error && !js_user)
					{
						Log.warn("Message type Newer-->"+message.getType());
						String errorFrom=fromJID.getNode()+"@"+fromJID.getDomain();
						showOfflinePres(errorFrom);
						sendLogoutMes(message.getTo(),errorFrom);
						showOnlineOffline(errorFrom,0);
						throw new PacketRejectedException("Busy");
					}
					else
						Log.warn("Message type no chat--> "+message.getType());
					return;					
				}
				//Log.warn(packet.toXML());
				//If message is coming from js user	
				if(js_user)
				{
			
					nick=subject.substring(subject.indexOf("/")+1,subject.length());
					profileID=subject.substring(0,subject.indexOf("@"));
					gmailID=this.getGmailId(profileID);
			
					String chattingWith=this.checkAlreadyTalking(gmailID,thread);
					
					//Gtalk user already chatting with someone else
					if(chattingWith.equals("end"))
					{
						Message message1=new Message();
						message1.setTo(message.getFrom());
						message1.setFrom(message.getTo());
						message1.setSubject(profileID+"@gmail.com");
						message1.setThread(profileID);
						message1.setType(Message.Type.headline);
						message1.setBody("Busy");
						messageRouter.route(message1);
						throw new PacketRejectedException("Sathi rejection");
					}
					
					//If chat initiated by js user
					if(msg.equals("ask_chatAuth"))
					{
						chatInit(message,gmailID,thread,currenttime,chattingWith,firstmes,nick,profileID);
						throw new PacketRejectedException("Sathi rejection");
					}
					//If chat is called timed out by js_user
					if(msg.equals("time_out"))
					{
						chatTimeOut(message,gmailID,nick,profileID);
						throw new PacketRejectedException("Sathi rejection");
					}
					if(timeExceed(message,gmailID,true))
						throw new PacketRejectedException("Sathi rejection");
					chatMessage(message,gmailID,nick,profileID);
					throw new PacketRejectedException("Sathi rejection");
					
				}	
				else
				{
					//---------------
					boolean a=false;
					if(a)
						return;
					//---------------
					String msgFrom=fromJID.getNode()+"@"+fromJID.getDomain();
					if(msg.length()>8)
					{
						if((msg.substring(0,6)).equals("@block"))
						{
							String block_user=msg.substring(7);
							boolean found=this.BlockUnblock(msgFrom,block_user,1);
							sendBlockUnblockMes(message,1,block_user,found);
							if(found)
							if(this.chatAcceptListGet(getProfileId(msgFrom)))
								sendEndingMes(message,msgFrom);
								
							throw new PacketRejectedException("@block");
						}
						else if((msg.substring(0,8)).equals("@unblock"))
						{
							String block_user=msg.substring(9);
							boolean found=this.BlockUnblock(msgFrom,block_user,0);
							sendBlockUnblockMes(message,0,block_user,found);
							throw new PacketRejectedException("@unblock");
						}
					}	
					//Wants to get him/her self hide in online search
					else if(msg.equals("@hide") || msg.equals("'@hide'"))
					{
						setON_OFF(0,msgFrom,message);
						throw new PacketRejectedException("Sathi rejection gmail");
					}
					//Wants to show him/her self in search
					if(msg.equals("@show") || msg.equals("'@show'"))
					{
						setON_OFF(1,msgFrom,message);
						throw new PacketRejectedException("Sathi rejection gmail");
					}
					//Chat accepted
					String isReceiver=this.chattingWithWhomGet(msgFrom);
					if(isReceiver!=null)
					{
						if(msg.equals("yes") || msg.equals("'yes'"))
						{
							int ret=checkYesNo(message,msgFrom,1);
							if(ret==2)
								throw new PacketRejectedException("Sathi rejection gmail");
							if(ret==0)
							{
								sendAutoMes(message);
								throw new PacketRejectedException("Sathi rejection gmail");
							}
						}
						//Chat declined
						else if(msg.equals("no") || msg.equals("'no'"))
						{
							int ret=checkYesNo(message,msgFrom,0);
							if(ret==2)
								throw new PacketRejectedException("Sathi rejection gmail");
							if(ret==0)
							{
								sendAutoMes(message);
								throw new PacketRejectedException("Sathi rejection gmail");
							}
						}
						else if(msg.equals("@end") || 	msg.equals("'@end'"))
						{
							
							if(this.chatAcceptListGet(getProfileId(msgFrom)))
							{
								sendEndingMes(message,msgFrom);
								throw new PacketRejectedException("Sathi rejection gmail");
							}
							else
							{
								sendYesNoAgain(message);
								throw new PacketRejectedException("Sathi rejection gmail");
							}
						}
						else
						{
							if(!this.chatAcceptListGet(getProfileId(msgFrom)))
							{
								sendYesNoAgain(message);
								throw new PacketRejectedException("Sathi rejection gmail");
							}	
						}
					}
					
					//Normal message without sender at the other end.
					if(this.chattingWithWhomGet(msgFrom)==null)
					{
						sendAutoMes(message);
						throw new PacketRejectedException("Sathi rejection gmail");
					}
					else
					{
						if((currenttime-this.timelastchatGet(msgFrom))>1200)
						{
							removeEntriesFromHashMap(msgFrom);
						}	
							
					}
					if(timeExceed(message,msgFrom,true))
					{
						throw new PacketRejectedException("Sathi rejection gmail");
					}
					sendMessage(message,msgFrom);
					throw new PacketRejectedException("Sathi rejection gmail");
					
				}	
					
				
			}
			
			if(packet instanceof Presence  && !processed)
			{
				//Log.warn(packet.getTo()+" "+processed+" "+incoming);
				Presence originalPresence = (Presence) packet;
				JID toJID=packet.getTo();
				JID fromJID=packet.getFrom();
				boolean modes;
				int allow=0;
				String node,domain,resource,node1="",domain1="",resource1="",query="",pstatus;
				
				if(fromJID==null || toJID==null)
					return;
				if(toJID.getNode().equals("sathi"))
				{
					//Log.warn(packet.toXML());
					//Log.warn("HELLOS"+originalPresence.toXML());
					node=fromJID.getNode();
					domain=fromJID.getDomain();
					resource=fromJID.getResource();
					modes=originalPresence.isAvailable();
					pstatus=originalPresence.getStatus();
					if(pstatus==null)
						pstatus="testing";
					String prsFrom=node+"@"+domain;

					if(domain.equals("gmail.com"))
					{
						//Log.warn(packet.getFrom()+" "+processed+" "+incoming);
						Presence pp=new Presence(originalPresence.getType());
						pp.setTo(originalPresence.getFrom());
						pp.setFrom(originalPresence.getTo());
						pp.setStatus("Welcome to Jeevansathi.com's chat assistant. To end an ongoing chat, send '@end'.  To be available for chat, send '@show'. To stop getting chat requests, send '@hide'. To block user send '@block Profile ID' (eg. @block ABC1234). To unblock user send '@unblock Profile ID' (eg. @unblock ABC1234). ");
						router.route(pp);
						String profid=getProfileId(prsFrom);
						//Log.warn(prsFrom+"--------------"+profid);
						if(profid==null)
							return;
						
						if(originalPresence.getType()==Presence.Type.unavailable)
						{
							//Log.warn("unavaiable");
							//Make him offline
							sendLogoutMes(originalPresence.getTo(),prsFrom);
							showOnlineOffline(prsFrom,0);
						}
						else if(originalPresence.getType()==Presence.Type.subscribe || originalPresence.getType()==Presence.Type.subscribed)
						{
							subunsub(prsFrom,1);
							//Log.warn("sub");
							//Make him register
							
						}
						else if(originalPresence.getType()==Presence.Type.unsubscribe || originalPresence.getType()==Presence.Type.unsubscribed)
						{
							//Log.warn("unsub");
							subunsub(prsFrom,0);
							
						}
						else if(originalPresence.getType()==null || originalPresence.getType()==Presence.Type.probe)
						{
							//Log.warn("no presence");
							//Make him online
							showOnlineOffline(prsFrom,1);
							
						}
						else
						{
							Log.warn("Coming "+originalPresence.getType()+"");
						}
						throw new PacketRejectedException("Sathi rejection presence");
					}
					else
					{
						//Log.warn("Domain not equals"+domain);
					}
					
				}	
				
			}
		}
		catch(Exception e)
		{
			
			Log.error("in exception"+e);

		}

	}
	public boolean timeExceed(Message message,String gmailID,boolean mestosend)
	{
	
		if(this.timelastchatGet(gmailID)!=null)
		{
			//Log.warn(currenttime+" "+timelastchat.get(gmailID)+" "+(currenttime-timelastchat.get(gmailID)));
			if((currenttime-this.timelastchatGet(gmailID))>1800)
			{
				if(mestosend)
				{
					String gmailProfid=getProfileId(gmailID);
					String jsProfid=this.chattingWithWhomGet(gmailID);
					String tosend=jsProfid+"@"+XMPPServer.getInstance().getServerInfo().getXMPPDomain();
					Message message1=new Message();
					message1.setTo(tosend);
					message1.setFrom(message.getTo());
					message1.setType(Message.Type.headline);
					message1.setBody("ending");
					message1.setThread(gmailProfid);
					message1.setSubject(gmailProfid+"@gmail.com");
					messageRouter.route(message1);
					
					Message message2=new Message();
					message2.setTo(message.getFrom());
					message2.setFrom(message.getTo());
					JID to=message.getTo();
					String user=userfetchGet(jsProfid);
					if(user==null)
						user="";
						
					message2.setBody("Chat with "+user+" has been disconnected because of inactivity.");
					messageRouter.route(message2);
				}
					
				removeEntriesFromHashMap(gmailID);
				
				return true;
			}
		}
		return false;	
	}
		public void sendBlockUnblockMes(Message message,int what,String user,boolean found)
	{
		Message message2=new Message();
		message2.setTo(message.getFrom());
		message2.setFrom(message.getTo());
		JID to=message.getTo();
		if(!found)
		{
			message2.setBody(user+" not found, Please provide correct username.");
		}
		else
		{
			if(what==1)
				message2.setBody("You have blocked "+user+" from further sending chat request");
			else
				message2.setBody(user+" is now allowed to send chat request to you.");
		}		
		messageRouter.route(message2);
	}
	public boolean BlockUnblock(String msgFrom,String block_user, int what)
	{
		
		String destinationUserID=null;
		String userID=null;
		PreparedStatement st=null;
		ResultSet rs=null;
		Connection conprof=null;
		if(msgFrom==null || block_user==null || msgFrom.equals("") || block_user.equals(""))
			return false;
		try
		{
			conprof = DbConnectionManager.getConnection(); 
			String sql="select USERNAME from newjs.NAMES where USERNAME=?";
			
			st=conprof.prepareStatement(sql);
			
			st.setString(1,block_user);
			
			rs=st.executeQuery();
			
			if(rs.next())
			{
				String temp_user=rs.getString("USERNAME");
				if(rs.next())
					temp_user=block_user;
				block_user=temp_user;	
			}
			conprof = DbConnectionManager.getConnection(); 
			sql="select PROFILEID from newjs.JPROFILE where USERNAME=? limit 1";
			st = conprof.prepareStatement(sql);
			st.setString(1,block_user);
			rs=st.executeQuery();
			
			if(rs.next()){
				destinationUserID=rs.getString("PROFILEID");
			}
			sql="select PROFILEID from newjs.JPROFILE where EMAIL=? limit 1";
			st=conprof.prepareStatement(sql);
			st.setString(1,msgFrom);
			rs=st.executeQuery();
			if(rs.next())
			{
				userID=rs.getString("PROFILEID");
			}
			if(userID!=null && destinationUserID!=null)
			{
				if(what==1)
					sql="insert ignore into userplane.blocked(userID,destinationUserID) values(?,?)";
				else
					sql="delete from userplane.blocked where userID=? and destinationUserID=?";
				
				st=conprof.prepareStatement(sql);
				st.setString(1,userID);
				st.setString(2,destinationUserID);
				st.execute();	
				return true;
			}
			
			
		}
		catch(Exception ex)
		{
			Log.error("Error in block unblocking"+ex);
		}
		finally { 
			DbConnectionManager.closeConnection(st, conprof); 
		}
		return false;
	}
	public void sendMessage(Message message,String msgFrom)
	{
		String gmailProfid=getProfileId(msgFrom);
		String jsProfid=this.chattingWithWhomGet(msgFrom);
		
		String to=jsProfid+"@"+XMPPServer.getInstance().getServerInfo().getXMPPDomain();
		//System.out.println("to is >>>>"+to);
		
		Message newMessage= new Message();
		newMessage.setThread(gmailProfid);
		newMessage.setSubject(gmailProfid+"@gmail.com");
		newMessage.setTo(to);
		newMessage.setFrom(message.getTo());
		newMessage.setType(Message.Type.chat);
		newMessage.setBody(message.getBody());
		messageRouter.route(newMessage);
		this.timelastchatPut(msgFrom,currenttime);
		return;
	}
	public void sendYesNoAgain(Message message)
	{
		Message message1=new Message();
		message1.setTo(message.getFrom());
		message1.setFrom(message.getTo());
		message1.setType(Message.Type.chat);
		message1.setBody("Type 'yes' to accept, 'no' to decline");
		messageRouter.route(message1);
	}	
	public void sendAutoMes(Message message)
	{
		Message message1=new Message();
		message1.setTo(message.getFrom());
		message1.setFrom(message.getTo());
		message1.setType(Message.Type.chat);
		message1.setBody("Welcome to Jeevansathi.com's chat assistant. To end an ongoing chat, send '@end'.  To be available for chat, send '@show'. To stop getting chat requests, send '@hide'. To block user send '@block Profile ID' (eg. @block ABC1234). To unblock user send '@unblock Profile ID' (eg. @unblock ABC1234). ");
		messageRouter.route(message1);
	}
	public  void sendLogoutMes(JID toJID,String msgFrom)
	{
		String receiver=this.chattingWithWhomGet(msgFrom);
		if(receiver!=null)
		{
			String gmailProfid=getProfileId(msgFrom);
			String jsProfid=receiver;
			
			String tosend=jsProfid+"@"+XMPPServer.getInstance().getServerInfo().getXMPPDomain();
			Message message1=new Message();
			message1.setTo(tosend);
			message1.setFrom(toJID);
			message1.setType(Message.Type.headline);
			message1.setBody("logout");
			message1.setThread(gmailProfid);
			message1.setSubject(gmailProfid+"@gmail.com");
			messageRouter.route(message1);
			
		}removeEntriesFromHashMap(msgFrom);	
	}	
	public  void sendEndingMes(Message message,String msgFrom)
	{
		String gmailProfid=getProfileId(msgFrom);
		String jsProfid=this.chattingWithWhomGet(msgFrom);
		String tosend=jsProfid+"@"+XMPPServer.getInstance().getServerInfo().getXMPPDomain();
		Message message1=new Message();
		message1.setTo(tosend);
		message1.setFrom(message.getTo());
		message1.setType(Message.Type.headline);
		message1.setBody("ending");
		message1.setThread(gmailProfid);
		message1.setSubject(gmailProfid+"@gmail.com");
		messageRouter.route(message1);
		
		Message message2=new Message();
		message2.setTo(message.getFrom());
		message2.setFrom(message.getTo());
		JID to=message.getTo();
		
		message2.setBody("You have ended chat");
		messageRouter.route(message2);
		
		removeEntriesFromHashMap(msgFrom);
	}
	public int checkYesNo(Message message,String msgFrom,int type)
	{
		String receiver=this.chattingWithWhomGet(msgFrom);
		if(receiver!=null)
		{
			String gmailProfid=getProfileId(msgFrom);
			if(this.chatAcceptListGet(gmailProfid))
			{
				return 1;
			}
			else
			{
				
				String jsProfid=receiver;
				
				this.chatAcceptListPut(gmailProfid,jsProfid);
				String tosend=jsProfid+"@"+XMPPServer.getInstance().getServerInfo().getXMPPDomain();
				Message message1=new Message();
				message1.setTo(tosend);
				message1.setFrom(message.getTo());
				message1.setType(Message.Type.headline);
				if(type==1)
					message1.setBody("accept");
				else
					message1.setBody("decline");
					

				message1.setThread(gmailProfid);
				
				message1.setSubject(gmailProfid+"@gmail.com");

				messageRouter.route(message1);
				
				Message message2=new Message();
				message2.setTo(message.getFrom());
				message2.setFrom(message.getTo());
				JID to=message.getTo();
				String user=userfetchGet(jsProfid);
				if(type==1)
					message2.setBody("You have approved "+user+"'s chat request");
				else
					message2.setBody("You have declined "+user+"'s chat request");
					
			
				messageRouter.route(message2);
				
				int sen_prof=Integer.parseInt(jsProfid);
				int rec_prof=Integer.parseInt(gmailProfid);
				if(type==1)
					updateLOGCHATREQUEST("A",sen_prof,rec_prof);
				else
				{
					updateLOGCHATREQUEST("D",sen_prof,rec_prof);	
					removeEntriesFromHashMap(msgFrom);
				}	
				return 2;
				
			}
		}
		return 0;
	}
	public void setON_OFF(int onoff,String msgFrom,Message message)
	{
		Connection conpres=null;
		String body="You will now not appear in search results for online members";
		if(onoff==1)
		{
			body="You will now appear in search results for online members";
		}
		Message newMessage= new Message();
		newMessage.setTo(message.getFrom());
		newMessage.setFrom(message.getTo());
		newMessage.setSubject(null);
		newMessage.setBody(body);
		messageRouter.route(newMessage);
		
		PreparedStatement st=null;
		try
		{
		
			 conpres = DbConnectionManager.getConnection();
		   
		    
			st = conpres.prepareStatement("update bot_jeevansathi.user_info set show_in_search="+onoff+" where gmail_ID ='"+msgFrom+"'");
			st.execute();
			
			showOnlineOffline(msgFrom,onoff);
			
			//if@show is passed	
		}
		catch(Exception e)
		{
			Log.error("Exception in setting hide/show "+onoff+msgFrom+e);
		}
		finally 
		{ 
			DbConnectionManager.closeConnection(st, conpres); 
        }
		
	}
	public void subunsub(String msgFrom,int sub_unsub)
	{
		PreparedStatement st=null;
		Connection consub=null;
		try
		{
			consub = DbConnectionManager.getConnection();
			String profid=getProfileId(msgFrom);
			if(profid!=null)
			{
				st=consub.prepareStatement("insert ignore into bot_jeevansathi.SUB_UNSUB set EMAIL ='"+msgFrom+"',SUB_TAKEN='"+sub_unsub+"',`DATE`=now(),`TYPE`='G'");
				st.execute();
			}
		}
		catch(Exception ex)
		{
			Log.warn("Exception in online offline records --> "+msgFrom+" "+sub_unsub+" "+ex);
		}
		finally 
		{ 
			DbConnectionManager.closeConnection(st, consub); 
        }	
	}
	public void showOfflinePres(String msgFrom)
	{
		PreparedStatement st=null;
		Connection conOff=null;
		try
		{
			conOff = DbConnectionManager.getConnection();
			String profid=getProfileId(msgFrom);
			if(profid!=null)
			{
			
				st=conOff.prepareStatement("insert ignore into bot_jeevansathi.user_online_sathi set USER ='"+profid+"'");
				st.execute();	
			}
		}
		catch(Exception ex)
		{
			Log.warn("Exception in online offline records"+msgFrom+" "+ex);
		}
		finally 
		{ 
			DbConnectionManager.closeConnection(st, conOff); 
        }
			
	}
	public void showOnlineOffline(String msgFrom,int onoff)
	{
		PreparedStatement st=null;
		Connection conOff=null;
		try
		{
			conOff = DbConnectionManager.getConnection();
			String profid=getProfileId(msgFrom);
			if(profid!=null && onoff==0)
			{
				st=conOff.prepareStatement("delete from  bot_jeevansathi.user_online where USER ='"+profid+"'");
				st.execute();
			}
			if(profid!=null && onoff==1)
			{
				st=conOff.prepareStatement("insert ignore into bot_jeevansathi.user_online set USER ='"+profid+"'");
				st.execute();
			}
		}
		catch(Exception ex)
		{
			Log.warn("Exception in online offline records"+msgFrom+" "+onoff+" "+ex);
		}
		finally 
		{ 
			DbConnectionManager.closeConnection(st, conOff); 
        }
	}
	public void chatMessage(Message message,String gmailID,String nick,String profileID)
	{
		Message modifiedMsg=new Message();
		modifiedMsg.setTo(gmailID);
		modifiedMsg.setFrom(message.getTo());
		modifiedMsg.setSubject(null);
		modifiedMsg.setType(Message.Type.chat);
		modifiedMsg.setBody("*"+nick+":* "+message.getBody());
		if(message.getBody().trim().equals("has gone offline, you cannot chat any longer with the user."))
		{
			modifiedMsg.setBody(nick+" "+message.getBody());
			
			removeEntriesFromHashMap(gmailID);
			
			
		}
		else
		{
			this.timelastchatPut(gmailID,currenttime);
		}
		
		messageRouter.route(modifiedMsg);
		
	}
	public void removeEntriesFromHashMap(String gmailID)
	{
		String receiver=chattingWithWhomGet(gmailID);
		if(receiver!=null)
		{
			String profId=getProfileId(gmailID);
			chatAcceptList.remove(profId);
			this.chatAcceptListClear(profId);
		}
		
		//Removing entries from both hashmap
		chattingWithWhom.remove(gmailID);
		this.chattingWithWhomClear(gmailID);
		
		//Storing
		timelastchat.remove(gmailID);
		timelastchatClear(gmailID);
		
	}
	public void chatTimeOut(Message message,String gmailID,String nick,String profileID)
	{
		PreparedStatement st=null;

		{
			Message message1=new Message();
			message1.setBody("User "+nick+" has aborted the request.");
			message1.setTo(gmailID);
			message1.setSubject(null);

			messageRouter.route(message1);
		}	
		
		removeEntriesFromHashMap(gmailID);
		
		int sen_prof=Integer.parseInt(message.getThread());
		int rec_prof=Integer.parseInt(profileID);
		updateLOGCHATREQUEST("T",sen_prof,rec_prof);

	}
	public void chatInit(Message message,String gmailID,String thread,long currenttime,String chattingWith,String firstmes,String nick,String profileID)
	{
		try
		{
			String msg=message.getBody().trim();
			
			
			
			//IF timeout is not called, that means initiation
			if(msg.equals("ask_chatAuth"))
			{
				//if(chattingWithWhom.containsKey(gmailID))
				
					
				String msgBody="";
				if(firstmes!=null)	
					msgBody="*"+nick+":* "+firstmes+"\n"+nick+" "+this.helpMsg1+nick+" "+this.helpMsg2;
				else
					msgBody=""+nick+" "+this.helpMsg1+nick+" "+this.helpMsg2;
				Message message1=new Message();
				message1.setType(Message.Type.chat);
				message1.setBody(msgBody);
				message1.setTo(gmailID);
				message1.setSubject(null);
				message1.setFrom(message.getTo());

				messageRouter.route(message1);

				//Storing with whom gmailid is mapped with js user profileid
				//chattingWithWhom.put(gmailID,thread);
				this.chattingWithWhomPut(gmailID,thread,currenttime);
				
				//Storing username , required for sending message
				//userfetchPut(thread,nick);
				
				//Storing
				this.timelastchatPut(gmailID,currenttime);

				int sen_prof=Integer.parseInt(message.getThread());
				int rec_prof=Integer.parseInt(profileID);

				updateLOGCHATREQUEST("1",sen_prof,rec_prof);
				

			}
		}
		catch(Exception ex)
		{
			Log.warn("exception"+ex);
		}
	}
	public String userfetchGet(String profid)
	{
		String profileid=null;
		PreparedStatement st=null;
		ResultSet rs=null;
		Connection conprof=null;
		try
		{
			conprof = DbConnectionManager.getConnection(); 
			String ss="select jeevansathi_ID from bot_jeevansathi.user_info where profileID='"+profid+"' limit 1";
			
			st = conprof.prepareStatement(ss);
			
			rs=st.executeQuery();
			if(rs.next()){
				profileid=rs.getString("jeevansathi_ID");
			}
						
		}
		catch(Exception ex)
		{
			Log.error("Error in getting gmailID"+ex);
		}
		finally { 
			DbConnectionManager.closeConnection(st, conprof); 
		}
		return profileid;
		
	}
	public void chatAcceptListClear(String gmailID)
	{
		String profileid=null;
		PreparedStatement st=null;
		ResultSet rs=null;
		Connection conprof=null;
		
		
		try
		{
			conprof = DbConnectionManager.getConnection(); 
			String ss="";
			ss="delete from  bot_jeevansathi.CHATACCEPTLIST where SENDER='"+gmailID+"'";
			if(gmailID.equals("ALL"))
				ss="delete from  bot_jeevansathi.CHATACCEPTLIST ";
			st = conprof.prepareStatement(ss);
			st.execute();
		}
		
		catch(Exception ex)
		{
			Log.error("Error in getting gmailID"+ex);
		}
		finally { 
			DbConnectionManager.closeConnection(st, conprof); 
		}
	
	}
	public Boolean chatAcceptListGet(String gmailID)
	{
		String profileid=null;
		PreparedStatement st=null;
		ResultSet rs=null;
		Connection conprof=null;
		
		
		try
		{
			String ss="";
			conprof = DbConnectionManager.getConnection(); 
			ss="select RECEIVER from bot_jeevansathi.CHATACCEPTLIST where SENDER='"+gmailID+"'";
			
			st = conprof.prepareStatement(ss);
			//st.setString(1, gmail);
			rs=st.executeQuery();
			if(rs.next()){
				profileid=rs.getString("RECEIVER");
			}
						
		}
		catch(Exception ex)
		{
			Log.error("Error in getting gmailID"+ex);
		}
		finally { 
			DbConnectionManager.closeConnection(st, conprof); 
		}
		if(profileid!=null)
			return true;
		else
			return false;
		
	}
	public void chatAcceptListPut(String sender,String receiver)
	{
		
		PreparedStatement st=null;
		ResultSet rs=null;
		Connection conprof=null;
		try
		{
			String ss="";
			conprof = DbConnectionManager.getConnection(); 
			ss="insert into bot_jeevansathi.CHATACCEPTLIST(SENDER,RECEIVER) values('"+sender+"','"+receiver+"')";		
			st = conprof.prepareStatement(ss);
			st.execute();
		}
		catch(Exception ex)
		{
			Log.error("Error in getting gmailID"+ex);
		}
		finally { 
			DbConnectionManager.closeConnection(st, conprof); 
		}

	}		
	public void timelastchatClear(String gmailID)
	{
		String profileid=null;
		PreparedStatement st=null;
		ResultSet rs=null;
		Connection conprof=null;
		
		
		try
		{
			String ss="";
			conprof = DbConnectionManager.getConnection(); 
			ss="delete from  bot_jeevansathi.TIMELASTCHAT where WHO='"+gmailID+"'";
			if(gmailID.equals("ALL"))
				ss="delete from  bot_jeevansathi.TIMELASTCHAT ";
			st = conprof.prepareStatement(ss);
			st.execute();
		}
		catch(Exception ex)
		{
			Log.error("Error in getting gmailID"+ex);
		}
		finally { 
			DbConnectionManager.closeConnection(st, conprof); 
		}
	
	}
	public Long timelastchatGet(String gmailID)
	{
		Long time=null;
		PreparedStatement st=null;
		ResultSet rs=null;
		Connection conprof=null;
		
		
		try
		{
			conprof = DbConnectionManager.getConnection(); 
			String ss="select TIME from bot_jeevansathi.TIMELASTCHAT where WHO='"+gmailID+"'";;
			
			st = conprof.prepareStatement(ss);
			//st.setString(1, gmail);
			rs=st.executeQuery();
			if(rs.next()){
				time=rs.getLong("TIME");
			}
						
		}
		catch(Exception ex)
		{
			Log.error("Error in getting gmailID"+ex);
		}
		finally { 
			DbConnectionManager.closeConnection(st, conprof); 
		}
		return time;
	}
	public void timelastchatPut(String gmailID,Long currenttime)
	{
		
		PreparedStatement st=null;
		ResultSet rs=null;
		Connection conprof=null;
		try
		{
			conprof = DbConnectionManager.getConnection(); 
			String ss="insert into bot_jeevansathi.TIMELASTCHAT(WHO,TIME) values('"+gmailID+"','"+currenttime+"')";		
			st = conprof.prepareStatement(ss);
			st.execute();

		}
		catch(Exception ex)
		{
			Log.error("Error in getting gmailID"+ex);
		}
		finally { 
			DbConnectionManager.closeConnection(st, conprof); 
		}

	}	
	public void chattingWithWhomClear(String gmailID)
	{
		String profileid=null;
		PreparedStatement st=null;
		ResultSet rs=null;
		Connection conprof=null;
		
		
		try
		{
			String ss="";
			conprof = DbConnectionManager.getConnection(); 
			ss="delete from  bot_jeevansathi.CHATTINGWITHWHOM where SENDER='"+gmailID+"'";
			if(gmailID.equals("ALL"))
				ss="delete from  bot_jeevansathi.CHATTINGWITHWHOM ";
			st = conprof.prepareStatement(ss);
			st.execute();
		}
		catch(Exception ex)
		{
			Log.error("Error in getting gmailID"+ex);
		}
		finally { 
			DbConnectionManager.closeConnection(st, conprof); 
		}
	
	}
	public String chattingWithWhomGet(String gmailID)
	{
		String profileid=null;
		PreparedStatement st=null;
		ResultSet rs=null;
		Connection conprof=null;
		
		
		try
		{
			conprof = DbConnectionManager.getConnection(); 
			String ss="select RECEIVER from bot_jeevansathi.CHATTINGWITHWHOM where SENDER='"+gmailID+"' order by STARTCHAT desc limit 1";
			
			st = conprof.prepareStatement(ss);
			
			rs=st.executeQuery();
			if(rs.next()){
				profileid=rs.getString("RECEIVER");
			}
						
		}
		catch(Exception ex)
		{
			Log.error("Error in getting gmailID"+ex);
		}
		finally { 
			DbConnectionManager.closeConnection(st, conprof); 
		}
		return profileid;
	}
	public void chattingWithWhomPut(String gmailID,String thread,Long currenttime)
	{
		String profileid=null;
		PreparedStatement st=null;
		ResultSet rs=null;
		Connection conprof=null;
		try
		{
			conprof = DbConnectionManager.getConnection(); 
			//Log.warn("select STARTCHAT  from bot_jeevansathi.CHATTINGWITHWHOM where gmail_ID='"+gmail+"'");
			String ss="insert into bot_jeevansathi.CHATTINGWITHWHOM(SENDER,RECEIVER,STARTCHAT) values('"+gmailID+"','"+thread+"','"+currenttime+"')";
			
			st = conprof.prepareStatement(ss);
			
			st.execute();
			
			
		}
		catch(Exception ex)
		{
			Log.error("Error in getting gmailID"+ex);
		}
		finally { 
			DbConnectionManager.closeConnection(st, conprof); 
		}

	}
	public String checkAlreadyTalking(String gmailid,String thread)
	{
		String receiver=this.chattingWithWhomGet(gmailid);
		if(receiver!=null)
		{
			if(!this.chatAcceptListGet(getProfileId(gmailid)))
				if(timeExceed(null,gmailid,false))
					return "";
			String chattingwith=receiver;
			if(chattingwith!=null)
			{
				if(!chattingwith.equals(thread))
				{
					return "end";
				}
				return chattingwith;
			
			}
			return "";
		}
		return "";	
	}
	public String getProfileId(String gmail)
	{
		String profileid=null;
		PreparedStatement st=null;
		ResultSet rs=null;
		Connection conprof=null;
		//if(profilefetch.containsKey(gmail))
		if(false)
		{
			profileid=this.profilefetch.get(gmail);
		}	
		else
		{
			
			//int profileID=Integer.parseInt(profileid);
		
			try
			{
				conprof = DbConnectionManager.getConnection(); 
				//Log.warn("select profileID  from bot_jeevansathi.user_info where gmail_ID='"+gmail+"'");
				st = conprof.prepareStatement("select profileID  from bot_jeevansathi.user_info where gmail_ID='"+gmail+"'");
				//st.setString(1, gmail);
				rs=st.executeQuery();
				if(rs.next()){
				//Log.warn(rs.getString("profileID"));	
					profileid=rs.getString("profileID");

				}
				//this.gmailfetch.put(profileid,gmail);
				//this.profilefetch.put(gmail,profileid);
				
				
			}
			catch(Exception ex)
			{
				Log.error("Error in getting gmailID"+ex);
			}
			finally { 
                DbConnectionManager.closeConnection(st, conprof); 
            }
            
		}
		return profileid;		
	}	
	public String getGmailId(String profileid)
	{
		String gmail=null;
		PreparedStatement st=null;
		ResultSet rs=null;
		Connection congmail=null;
		//if(this.gmailfetch.containsKey(profileid))
		if(false)
		{
			return this.gmailfetch.get(profileid);
		}	
		else
		{
			
			int profileID=Integer.parseInt(profileid);
		
			try
			{
				congmail = DbConnectionManager.getConnection(); 
			
				st = congmail.prepareStatement("select gmail_ID  from bot_jeevansathi.user_info where profileID=?");
				st.setInt(1, profileID);
				rs=st.executeQuery();
				while(rs.next()){
					gmail=rs.getString("gmail_ID");

				}
				//this.gmailfetch.put(profileid,gmail);
				//this.profilefetch.put(gmail,profileid);
				
				
			}
			catch(Exception ex)
			{
				Log.error("Error in getting gmailID"+ex);
			}
			finally { 
                DbConnectionManager.closeConnection(rs,st, congmail); 
            }
            return gmail;
		}		
	}
	public void updateLOGCHATREQUEST(String action,int sen_prof,int rec_prof)
	{
		PreparedStatement st=null;
		Connection conlog=null;
		try
		{
			conlog = DbConnectionManager.getConnection();

		
			if(!action.equals("1"))
				st = conlog.prepareStatement("UPDATE userplane.`LOG_CHAT_REQUEST` SET ACTION='"+action+"' WHERE SEN ='"+sen_prof+"' AND REC='"+rec_prof+"' order by ID DESC LIMIT 1");
			if(action.equals("1"))
				st = conlog.prepareStatement("UPDATE userplane.`LOG_CHAT_REQUEST` SET RECEIVED ='"+action+"' WHERE SEN ='"+sen_prof+"' AND REC='"+rec_prof+"' order by ID DESC LIMIT 1");
				
				
				
			st.execute();
		}
		catch(Exception e)
		{
			Log.error("Exception in updating timeout"+e);
		}
		finally 
		{ 
				DbConnectionManager.closeConnection(st, conlog); 
		}
	}
	public boolean isParsableToInt(String i)
	{
		try
		{
		Integer.parseInt(i);
		return true;
		}
		catch(NumberFormatException nfe)
		{
		return false;
		}
	}
    
    
}
