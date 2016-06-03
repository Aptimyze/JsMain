package chat;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.sql.Statement;
import java.sql.DriverManager;

import org.jivesoftware.smack.Chat;
import org.jivesoftware.smack.ChatManager;
import org.jivesoftware.smack.PacketListener;
import org.jivesoftware.smack.XMPPConnection;
import org.jivesoftware.smack.XMPPException;
import org.jivesoftware.smack.packet.Message;
import org.jivesoftware.smack.packet.Packet;
import org.jivesoftware.smack.util.StringUtils;
import org.jivesoftware.smack.packet.DefaultPacketExtension;
import org.jivesoftware.smack.ConnectionConfiguration;
import org.jivesoftware.smack.Roster;
import org.jivesoftware.smack.RosterEntry;
import org.jivesoftware.smack.filter.MessageTypeFilter;
import org.jivesoftware.smack.filter.PacketFilter;
import org.jivesoftware.smack.packet.Presence;
import org.jivesoftware.smackx.packet.VCard;
import org.apache.commons.dbcp.BasicDataSource;
import org.jivesoftware.smack.packet.IQ;


 public  class MessageParrot implements PacketListener {
	private XMPPConnection xmppConnection;
	//private XMPPConnection clientConnection[10000000];
	public  Chat chat ;

	public String helpMsg1=GtalkBot.help_msg1;
	public String helpMsg2=GtalkBot.help_msg2;
	public String conflict_msg1=GtalkBot.conflict_msg1;
	public String conflict_msg2=GtalkBot.conflict_msg2;
	public String conflict_msg3=GtalkBot.conflict_msg3;
       

	public PreparedStatement pst =null;

	//Getting mysql connection.
	public Statement st = null;
	public  Connection conn1 = null;
	public String clientType=null;
	public String clientTo=null;
	public String clientFrom=null;
	public String toAll[]=new String[3];
	public String fromAll[]=new String[3];
	public MessageParrot(XMPPConnection conn) {
		xmppConnection = conn;
	} 
	

	public void processPacket(Packet packet) 
	{
		try
		{
				
			String urls = GtalkBot.newjs_url;
			String userNames = GtalkBot.newjs_username;
			String passwords = GtalkBot.newjs_password;


			conn1 = MysqlInstance.getInstance();
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}

		Message message = (Message)packet;
	
	
		try
		{
			if(message.getBody() != null ) 
			{
				if(message.getFrom().contains("/"))
				{
					String from=message.getFrom();
					message.setFrom(from.substring(0, from.indexOf("/")));
				}
				//message.get
				String msgFrom=message.getFrom();
				
				String nick=null;
				String profileID=null;
				String subject=message.getSubject();
				if(subject != null)
				{
					nick=subject.substring(subject.indexOf("/")+1,subject.length());
					profileID=subject.substring(0, subject.indexOf("@"));
				}
				
				
				String msg=message.getBody().trim();
				String gmailID=null;
				if(profileID != null && !profileID.equals(""))
				{
					gmailID=(String)GtalkBot.profile_gmailIdMap.get(profileID);
					/* this is for getting gmail from profileid*/
					if(gmailID  == null)
					{
						GmailGetter gmailGetter=new GmailGetter();
						gmailID=gmailGetter.getGmail(Integer.parseInt(profileID));
						if(gmailID != null)
						{
							GtalkBot.profile_gmailIdMap.put(profileID, gmailID);
						}
					}
				}
				
				if(gmailID != null)
				{	//this block of code for nsg coming from js user
					String chattingWith=(String)GtalkBot.hashTable.get(gmailID);
					if(chattingWith!=null)
					{
						//System.out.println(chattingWith+" "+message.getThread());
						String temp_user=message.getThread()+"@"+GtalkBot.xmpp_server_name;
						if(!chattingWith.equals(temp_user))
						{
							Message message1=new Message();
							message1.setTo(message.getFrom());
							message1.setFrom(message.getTo());
							message1.setSubject(profileID+"@gmail.com");
							message1.setThread(profileID);
							message1.setType(Message.Type.headline);
							message1.setBody("Busy");
							xmppConnection.sendPacket(message1);
							return;
						}
					}		
					if(chattingWith == null)
					{
						if(!msg.equals("time_out"))
						{
							String firstmes=message.getFirstMes();
							String msgBody="";
							if(firstmes!=null)	
								msgBody="*"+nick+":* "+firstmes+"\n"+nick+" "+helpMsg1+nick+" "+helpMsg2;
							else
								msgBody=""+nick+" "+helpMsg1+nick+" "+helpMsg2;
							Message message1=new Message();
							message1.setType(Message.Type.chat);
							message1.setBody(msgBody);
							message1.setTo(gmailID);
							message1.setSubject(null);
							message1.setFrom(message.getFrom());
							GtalkBot.pendingMsgMap.put(gmailID, message);
							xmppConnection.sendPacket(message1);
							try
							{
						
								st = conn1.createStatement();
								int sen_prof=Integer.parseInt(message.getThread());
								int rec_prof=Integer.parseInt(profileID);
						
								String query="UPDATE userplane.`LOG_CHAT_REQUEST` SET RECEIVED ='1' WHERE SEN ='"+sen_prof+"' AND REC='"+rec_prof+"' order by ID DESC LIMIT 1";
								//System.out.println(query);
								st.executeUpdate(query);
								//conn1.close();
							}
							catch(Exception ex)
							{
								System.out.println("problem in connection... ");
								ex.printStackTrace();
							}
							return;
							}
							else
							{
								Message message1=new Message();
								message1.setBody("User "+nick+" has aborted the request.");
								message1.setTo(gmailID );
								message1.setSubject(null);
								GtalkBot.pendingMsgMap.remove(gmailID);
								xmppConnection.sendPacket(message1);

								//Updating table that time out occurs.
								st = conn1.createStatement();
								try
								{	
									int sen_prof=Integer.parseInt(message.getThread());
									int rec_prof=Integer.parseInt(profileID);
			
									String query="UPDATE userplane.`LOG_CHAT_REQUEST` SET ACTION='T' WHERE SEN ='"+sen_prof+"' AND REC='"+rec_prof+"' order by ID DESC LIMIT 1";
									st.executeUpdate(query);
									//conn1.close();	
								}
								catch(Exception ex)
								{
									System.out.println("problem in timeout ");
									ex.printStackTrace();
								}
								return;
							}
						}
						else 
							if(chattingWith.equals(msgFrom))
							{
						
								if(message.getBody().equals("ask_chatAuth"))
								{
									GtalkBot.hashTable.remove(gmailID);
									GtalkBot.profileMap.remove(gmailID);
									String firstmes=message.getFirstMes();
									String msgBody="";
									if(firstmes!=null)
										msgBody="*"+nick+":* "+firstmes+"\n"+nick+" "+helpMsg1+nick+" "+helpMsg2;
									else
										msgBody=""+nick+" "+helpMsg1+nick+" "+helpMsg2;

									Message message1 = new Message();
									message1.setType(Message.Type.chat);
									message1.setBody(msgBody);
									message1.setTo(gmailID);
									message1.setSubject(null);
									GtalkBot.pendingMsgMap.put(gmailID, message);
									xmppConnection.sendPacket(message1);
									//Updating table that time out occurs.
									st = conn1.createStatement();
									try{

										st = conn1.createStatement();
										int sen_prof=Integer.parseInt(message.getThread());
										int rec_prof=Integer.parseInt(profileID);

										String query="UPDATE userplane.`LOG_CHAT_REQUEST` SET RECEIVED ='1' WHERE SEN ='"+sen_prof+"' AND REC='"+rec_prof+"' order by ID DESC LIMIT 1";
										st.executeUpdate(query);
										//conn1.close();
									} 
									catch(Exception ex)
									{
										System.out.println("problem in connection... ");
										ex.printStackTrace();
									}
									return;
								}
								else
								{//for log out msg msg*************
						
									Message modifiedMsg=new Message();
									modifiedMsg.setTo(gmailID);
									modifiedMsg.setSubject(null);
									modifiedMsg.setType(Message.Type.chat);
									modifiedMsg.setBody("*"+nick+":* "+message.getBody());
									if(message.getBody().trim().equals("has gone offline, you cannot chat any longer with the user."))
									{
										modifiedMsg.setBody(nick+" "+message.getBody());
										GtalkBot.hashTable.remove(gmailID);
										GtalkBot.profileMap.remove(gmailID);
										//GtalkBot.profile_gmailIdMap.remove(message.getThread());
										GtalkBot.profile_gmailIdMap.remove(profileID);
									}
									xmppConnection.sendPacket(modifiedMsg);
									return;
								}
							}
							else 
							{// for conflict msg
								Message message1=new Message();
								message1.setBody(message.getBody());
								message1.setTo(message.getTo());
								//message1.setSubject(gmailID);
								message1.setSubject(message.getSubject());
								message1.setFrom(message.getFrom());
								message1.setThread(profileID);
								GtalkBot.pendingMsgMap.put(gmailID, message1);
								String msgBody=nick+" "+conflict_msg1+nick+" "+conflict_msg2+" "+nick+" "+conflict_msg3;
								Message message2=new Message();
								//String msgBody=msgFrom+GtalkBot.helpMsg;
								message2.setType(Message.Type.chat);
								message2.setTo(gmailID);
								message2.setSubject(null);
								message2.setBody(msgBody);
								xmppConnection.sendPacket(message2);
								
								return;
							}
						}
						else
						{//this block of code for msg coming from gtalk user.
					
							boolean isUserExit=GtalkBot.hashTable.containsKey(msgFrom);
							boolean isCommand=GtalkBot.commandList.contains(msg);
							
							
							if(msg.equals("@hide") ||  msg.equals("@show"))
							{
								StatusUpdater stsUpdater=new StatusUpdater();
								if(msg.equals("@hide"))
								{
									//System.out.println("setting ON_OFF IN STATUSUPDATER");
									stsUpdater.setON_OFF(0,msgFrom);
									
									Message newMessage= new Message();
									newMessage.setTo(msgFrom);
									newMessage.setSubject(null);
									newMessage.setBody("You will now not appear in search results for online members");
									xmppConnection.sendPacket(newMessage);
									//return;
									
									
									return;
								}
								else
								{
									
									//System.out.println("setting ON_OFF IN STATUSUPDATER");
									stsUpdater.setON_OFF(1,msgFrom);
									
									Message newMessage= new Message();
									newMessage.setTo(msgFrom);
									newMessage.setSubject(null);
									newMessage.setBody("You will now appear in search results for online members");
									xmppConnection.sendPacket(newMessage);
									
								}
						
								return;
							}
					
							//System.out.println(isUserExit+msg+isCommand);	
							if(isUserExit == true && !msg.equals("@end") && !msg.equals("@yes") && !msg.equals("@no"))
							{
								String to=(String)GtalkBot.hashTable.get(message.getFrom());
								//System.out.println("to is >>>>"+to);
								String thread=(String)GtalkBot.profileMap.get(message.getFrom());
								Message newMessage= new Message();
								newMessage.setThread(thread);
								newMessage.setTo(to);
								newMessage.setType(Message.Type.chat);
								newMessage.setBody(msg);
								newMessage.setSubject(thread+"@gmail.com");
								xmppConnection.sendPacket(newMessage);
								return;
							}
							else if(isCommand == false)
							{
								String tag_line=GtalkBot.tag_line;
								Message newMessage= new Message();
								newMessage.setTo(msgFrom);
								newMessage.setSubject(null);
								newMessage.setType(Message.Type.chat);
								//newMessage.setBody("plz enter command as for help msg then you can start chat");
								newMessage.setBody(tag_line);
								xmppConnection.sendPacket(newMessage);
								return;
								
							}
							else if(msg.equals("yes"))
							{
								Message msgPacket=(Message)GtalkBot.pendingMsgMap.get(msgFrom);
								if(msgPacket != null)
								{
									ChatRequestUpdater chatUpdater=new ChatRequestUpdater();
									chatUpdater.updateChatRequest(msgPacket,"a");
									String gmailId=(String)GtalkBot.profile_gmailIdMap.get(msgPacket.getSubject().substring(0,msgPacket.getSubject().indexOf("@")) );
									GtalkBot.hashTable.put(gmailId, msgPacket.getFrom());
									GtalkBot.profileMap.put(gmailId, msgPacket.getSubject().substring(0,msgPacket.getSubject().indexOf("@")) );

									Message message1=new Message();
									message1.setTo( msgPacket.getFrom());
									message1.setType(Message.Type.headline);
									message1.setBody("accept");
									//message1.setThread(msgPacket.getThread());
									message1.setThread(msgPacket.getSubject().substring(0,msgPacket.getSubject().indexOf("@")) );
									String subject_nick=msgPacket.getSubject();
									message1.setSubject(subject_nick.substring(0, subject_nick.indexOf("/")));
									xmppConnection.sendPacket(message1);
									GtalkBot.pendingMsgMap.remove(msgFrom);
									GtalkBot.chatMap.put(msgFrom, subject_nick.substring(subject_nick.indexOf("/")+1,subject_nick.length()) );
									//System.out.println("sending back authenticated acceptance");

									Message message2=new Message();
									message2.setTo(msgFrom);
									message2.setBody("You have approved "+subject_nick.substring(subject_nick.indexOf("/")+1,subject_nick.length()) +"'s chat request");
									xmppConnection.sendPacket(message2);
									return;
								}
								else
								{
									//System.out.println("Message packet is not stored.");
									
									String tag_line=GtalkBot.tag_line;
									Message newMessage= new Message();
									newMessage.setTo(msgFrom);
									newMessage.setSubject(null);
									newMessage.setType(Message.Type.chat);
									//newMessage.setBody("plz enter command as for help msg then you can start chat");
									newMessage.setBody(tag_line);
									xmppConnection.sendPacket(newMessage);
									return;
								}
								//sending back the gtalk user that u have accepted the user request
						
						
						
							}
							else if(msg.equals("no"))
							{
								Message msgPacket=(Message)GtalkBot.pendingMsgMap.get(msgFrom);
								
						if(msgPacket != null)
						{
							
							ChatRequestUpdater chatUpdater=new ChatRequestUpdater();
							chatUpdater.updateChatRequest(msgPacket,"d");

							Message message1=new Message();
							message1.setTo(msgPacket.getFrom());
							message1.setType(Message.Type.headline);
							message1.setBody("decline");
							String subject_nick=msgPacket.getSubject();
							message1.setSubject(subject_nick.substring(0, subject_nick.indexOf("/")));
							message1.setThread(subject_nick.substring(0, subject_nick.indexOf("@")));
							xmppConnection.sendPacket(message1);
							GtalkBot.pendingMsgMap.remove(msgFrom);//msgFrom


							Message message2=new Message();
							message2.setTo(msgFrom);
							message2.setBody("You have declined "+subject_nick.substring(subject_nick.indexOf("/")+1,subject_nick.length()) +"'s chat request");
							xmppConnection.sendPacket(message2);
							return;
						}
						else
						{
							System.out.println("Message packet is not stored.");

							String tag_line=GtalkBot.tag_line;
							Message newMessage= new Message();
							newMessage.setTo(msgFrom);
							newMessage.setSubject(null);
							newMessage.setType(Message.Type.chat);
							//newMessage.setBody("plz enter command as for help msg then you can start chat");
							newMessage.setBody(tag_line);
							xmppConnection.sendPacket(newMessage);
							return;
						}
					}
					else if(msg.equals("@end"))
					{
						String to=(String)GtalkBot.hashTable.get(msgFrom);
						String profileID1=(String)GtalkBot.profileMap.get(msgFrom);
						Message msgPacket=(Message)GtalkBot.pendingMsgMap.get(msgFrom);
						
						String toWhom=(String)GtalkBot.chatMap.get(msgFrom);
						//System.out.println("toWhom is >>>>>"+toWhom);
						if(toWhom !=null && to!=null)
						{
							Message message3=new Message();
							try
							{
								message3.setTo(msgFrom);
								message3.setBody("You have ended your chat with "+toWhom);
								xmppConnection.sendPacket(message3);
							}
							catch(Exception e)
							{
								System.out.println("hi how are you ,, i think you are offline");
							}	
							
							GtalkBot.hashTable.remove(msgFrom);
							GtalkBot.profileMap.remove(msgFrom);
							GtalkBot.profile_gmailIdMap.remove(profileID1);
							GtalkBot.chatMap.remove(msgFrom);
							
							Message message1=new Message();
							message1.setTo(to);
							
							//System.out.println("Message.Type.headline is >>>"+Message.Type.headline);
							message1.setType(Message.Type.headline);
							message1.setBody("ending");
							message1.setThread(profileID1);
							message1.setSubject(profileID1+"@gmail.com");
							xmppConnection.sendPacket(message1);
							if(msgPacket != null){
								GtalkBot.pendingMsgMap.remove(msgFrom);
							}
							return;
						}
						else if(msgPacket != null)
						{
							Message message3=new Message();
							message3.setTo(msgFrom);
							String subject123=msgPacket.getSubject();
							message3.setType(Message.Type.chat );
							String nick123=subject123.substring(subject123.indexOf("/")+1, subject123.length());
							
							 String msgBody=nick123+" "+helpMsg1+nick123+" "+helpMsg2;
							
							message3.setBody(msgBody);
							xmppConnection.sendPacket(message3);
							return;
						}
						else
						{
							String tag_line=GtalkBot.tag_line;
							Message newMessage= new Message();
							newMessage.setTo(msgFrom);
							newMessage.setSubject(null);
							newMessage.setType(Message.Type.chat);
							//newMessage.setBody("plz enter command as for help msg then you can start chat");
							newMessage.setBody(tag_line);
							xmppConnection.sendPacket(newMessage);
							return;
						}
						
					}
					else if(msg.equals("@yes"))
					{
						String to=(String)GtalkBot.hashTable.get(msgFrom);
						String thread=(String)GtalkBot.profileMap.get(msgFrom);
						//System.out.println("to is >>>"+to+">>>thread is >>>>"+thread);
						Message msgPacket=(Message)GtalkBot.pendingMsgMap.get(msgFrom);
						if(msgPacket != null && to!= null && thread != null)
						{
							
							Message message1=new Message();
							message1.setThread(thread);
							message1.setType(Message.Type.headline);
							message1.setSubject(thread+"@gmail.com");
							message1.setTo(to);
							message1.setBody("ending");
							xmppConnection.sendPacket(message1);
							GtalkBot.hashTable.remove(msgFrom);
							GtalkBot.profileMap.remove(msgFrom);
							GtalkBot.profileMap.remove(msgFrom);
							
							GtalkBot.chatMap.remove(msgFrom);
							
							System.out.println("message has been sent to old user to whom it was chatting");
							
							ChatRequestUpdater chatUpdater=new ChatRequestUpdater();
							chatUpdater.updateChatRequest(msgPacket,"a");
							
							String gmailId=(String)GtalkBot.profile_gmailIdMap.get(msgPacket.getThread());
							GtalkBot.hashTable.put(gmailId, msgPacket.getFrom());
							GtalkBot.profileMap.put(gmailId, msgPacket.getThread());
							String subject_nick=msgPacket.getSubject();
							GtalkBot.chatMap.put(msgFrom, subject_nick.substring(subject_nick.indexOf("/")+1,subject_nick.length()) );
							
							Message message2=new Message();
							message2.setTo( msgPacket.getFrom());
							message2.setType(Message.Type.headline);
							message2.setBody("accept");
							//message2.setSubject(msgPacket.getSubject());
							
							message2.setSubject(subject_nick.substring(0, subject_nick.indexOf("/")));
							message2.setThread(msgPacket.getThread());
							xmppConnection.sendPacket(message2);
							GtalkBot.pendingMsgMap.remove(msgFrom);
							
							Message message3=new Message();
							message3.setTo(msgFrom);
							message3.setBody("You have approved "+subject_nick.substring(subject_nick.indexOf("/")+1,subject_nick.length()) +" chat request");
							xmppConnection.sendPacket(message3);
							
							
							return;
						}
						else
						{
							System.out.println("Message packet is not stored.");
							
							String tag_line=GtalkBot.tag_line;
							Message newMessage= new Message();
							newMessage.setTo(msgFrom);
							newMessage.setSubject(null);
							newMessage.setType(Message.Type.chat);
							//newMessage.setBody("plz enter command as for help msg then you can start chat");
							newMessage.setBody(tag_line);
							xmppConnection.sendPacket(newMessage);
							return;
						}
						
					}else if(msg.equals("@no"))
					{
						
						Message msgPacket=(Message)GtalkBot.pendingMsgMap.get(msgFrom);
						if(msgPacket != null)
						{
							String gmailId=(String)GtalkBot.profile_gmailIdMap.get(msgPacket.getThread());
							ChatRequestUpdater chatUpdater=new ChatRequestUpdater();
							chatUpdater.updateChatRequest(msgPacket,"d");
							
							Message message2=new Message();
							message2.setTo( msgPacket.getFrom());
							message2.setType(Message.Type.headline);
							message2.setBody("decline");
							//message2.setSubject(msgPacket.getSubject());
							String subject_nick=msgPacket.getSubject();
							message2.setSubject(subject_nick.substring(0, subject_nick.indexOf("/")));
							message2.setThread(subject_nick.substring(0,subject_nick.indexOf("@")));
							xmppConnection.sendPacket(message2);
							GtalkBot.pendingMsgMap.remove(msgFrom);
							
							Message message3=new Message();
							message3.setTo(msgFrom);
							message3.setBody("You have declined "+subject_nick.substring(subject_nick.indexOf("/")+1,subject_nick.length()) +" chat request");
							
							xmppConnection.sendPacket(message3);
							
							
							return;
						}
						else
						{
							String tag_line=GtalkBot.tag_line;
							Message newMessage= new Message();
							newMessage.setTo(msgFrom);
							newMessage.setSubject(null);
							newMessage.setType(Message.Type.chat);
							//newMessage.setBody("plz enter command as for help msg then you can start chat");
							newMessage.setBody(tag_line);
							xmppConnection.sendPacket(newMessage);
							return;
						}
					}
					
				}
			}
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
	}
}
