package chat;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.Collection;
import java.util.HashMap;
import java.util.Hashtable;
import java.util.Properties;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import org.jivesoftware.smack.AccountManager;

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

public class EachPresence
{
	String toAll[]=new String[3];
	String prof_id="",profileid="";
	public PreparedStatement stmt = null;
	public String query =null;
	ResultSet rs=null;
    public PreparedStatement pst =null;
	String urls = GtalkBot.newjs_url;
	String userNames = GtalkBot.newjs_username;
	String passwords = GtalkBot.newjs_password;
	//Getting mysql connection.
	public Statement st = null;
	
	protected Connection db_con=null;
	protected SubscriptionListener SubListener;
	protected SubscriptionHandler SubHandlerLis;
	protected MessageParrot MessageLis;
	protected XMPPConnection connections;
	public EachPresence()
	{
		
	}
	public void removeListeners(int prof_id)
	{
		try
		{
			int id_con=prof_id;
			connections=XmppGmailCon.getInstance(id_con,1,null);
			SubListener=XmppGmailCon.getRosterSub(id_con,1,null);
			SubHandlerLis=XmppGmailCon.getSubHandler(id_con,1,null);
			MessageLis=XmppGmailCon.getMesListener(id_con,1,null);
			//System.out.println(SubListener+" "+SubHandlerLis+" "+MessageLis+" "+connections+" "+id_con);
			if(connections!=null)
			{
				//System.out.println("hellos"+SubListener[id_con]);
				//Removing roster listener.
				if(SubListener!=null)
				{
					Roster roster = connections.getRoster();
					roster.removeRosterListener(SubListener);
					XmppGmailCon.getRosterSub(id_con,0,null);
					SubListener=null;
				}	
				
				//Removing Subscription listener.
				if(SubHandlerLis!=null)
				{
					connections.removePacketListener(SubHandlerLis);
					XmppGmailCon.getSubHandler(id_con,0,null);
					SubHandlerLis=null;
				}	
				
				if(MessageLis!=null)
				{
					connections.removePacketListener(MessageLis);
					XmppGmailCon.getMesListener(id_con,0,null);
					MessageLis=null;
				}
				System.out.println(XmppGmailCon.getMesListener(id_con,1,null)+" "+SubHandlerLis+" "+MessageLis+" "+connections+" "+id_con);
				
				//Presence offlinePres = new Presence(Presence.Type.unavailable, "", 1, Presence.Mode.away);
	                        //connections.sendPacket(offlinePres);
				connections.disconnect();
				//connections[id_con].removePacketListener(this);
				XmppGmailCon.getInstance(id_con,0,null);
				connections=null;
				Thread.sleep(1000);
			}
		}
		catch(Exception ex)
		{
			ex.printStackTrace();
		}	
		
	}
	public void setPresence(String subject,String email_id,ConnectionConfiguration connConfigs)
	{
		int register_prof=0;
		try
		{
			//System.out.println(SubListener[146189]+" "+MessageLis[146189]);
			db_con=MysqlInstance.getInstance();
			getJIDS gt=new getJIDS();
			toAll=gt.getPartsJID(email_id);
			email_id=toAll[0]+"@"+toAll[1];
			if(toAll[1].equals("gmail.com"))
			{
				stmt = db_con.prepareStatement("select PROFILEID,jeevansathi_ID from bot_jeevansathi.user_info where gmail_ID='"+email_id+"'");
				
				rs=stmt.executeQuery();
				if(rs.next()){
					profileid=rs.getString("jeevansathi_ID");
					prof_id=rs.getString("PROFILEID");
					GtalkBot.ProfileMapGtalk.put(email_id,prof_id);
					GtalkBot.UserGtalkMap.put(email_id,profileid+"@"+GtalkBot.xmpp_server_name);
				}
				//profileid="144111";
				if(!profileid.equals(""))
				{
					if(subject.equals("1"))
					{
						
						int id_con=Integer.parseInt(prof_id);
						connections=XmppGmailCon.getInstance(id_con,1,null);
						//Trying to login him again
						//System.out.println(connections+" "+id_con+" "+XmppGmailCon.xmppCon);
						if(connections!=null)
						{
							this.removeListeners(id_con);
								
						}
						if(connections==null)
						{
							//Getting connection for particular bot of user..
							connections = new XMPPConnection(connConfigs);
			
							//Setting it back to xmppGmailGon
							XmppGmailCon.getInstance(id_con,1,connections);
							connections.connect();
							try
							{
								//if(!connections[id_con].isAuthenticated())
									connections.login(profileid, "jeevan");
									register_prof=0;
							}
							catch(XMPPException notregister)
							{
								try{
									System.out.println("i am in register section for user"+profileid);
									try{
										AccountManager manager = connections.getAccountManager();
										manager.createAccount(profileid, "jeevan");
										Thread.sleep(1000);
										connections.disconnect();
										Thread.sleep(1000);
										connections.connect();
										register_prof=1;
										Thread.sleep(2000);
									}
									catch(Exception ex)
									{
										System.out.println("i am in account creation section");
										ex.printStackTrace();
									}
									
									//if(!connections[id_con].isAuthenticated())
									try
									{
										
										connections.login(profileid, "jeevan");
									}
									catch(Exception ex)
									{
										System.out.println("i am in login section ");
										ex.printStackTrace();
									}	
										
								}catch(Exception e)
								{
									System.out.println("exception in login or registration");
								}
								
							}
							
							try{
								
								//Send subscription request
								
								Roster rosters=null;
								rosters = connections.getRoster();
								Collection<RosterEntry> entries = rosters.getEntries();
								int pass=0;
								for(RosterEntry r:entries)
								{
									if(r.getUser().equals(email_id))
										pass=1;
									//System.out.println(r.getUser()+" "+email_id);	
								}
								if(pass==0)
								{
									Presence presences = new Presence(Presence.Type.subscribe);
									presences.setTo(email_id);
									//presence.setStatus(GtalkBot.tag_line);
									connections.sendPacket(presences);
								}	
							}
							catch(Exception ex)
							{
								System.out.println("Exception in sending subscription "+profileid);
								ex.printStackTrace();
							}
							try
							{
								
								//Sent presence avilable to gtalk user
								Presence presence = new Presence(Presence.Type.available);
								//presences.setTo(email_id);
								presence.setStatus(GtalkBot.tag_line);
								connections.sendPacket(presence);
								
							//	Thread PreSend = new Thread(new PresenceSender(connections[id_con],id_con,GtalkBot.tag_line,email_id));
								//PreSend.start();
							
							}
							catch(Exception e)
							{
								System.out.println("Exception in sending presence "+profileid);
							}
						
							
						/*	
							//packet listener
							PacketFilter filter = new MessageTypeFilter(Message.Type.chat);
					
							MessageParrot msgParrot = new MessageParrot(connections);
							connections.addPacketListener(msgParrot, filter);
							XmppGmailCon.getMesListener(id_con,1,msgParrot);
							
							 Roster.setDefaultSubscriptionMode(Roster.SubscriptionMode.accept_all);
							 Roster roster = connections.getRoster();
							 
							//roster.
							SubscriptionListener subscriptionListener = new SubscriptionListener(connections);
							roster.addRosterListener(subscriptionListener);
							XmppGmailCon.getRosterSub(id_con,1,subscriptionListener);
							
							//Subscription packet listener
							SubscriptionHandler SubPacLis=new SubscriptionHandler(connections);
							// Add a listener waiting for subscription packets
							connections.addPacketListener(SubPacLis, new PacketFilter() {
								public boolean accept(Packet aPacket) {
									if (aPacket instanceof Presence) {
										Presence p = (Presence) aPacket;
										return ((Presence.Type.subscribe.equals(p.getType())) 
											|| (Presence.Type.unsubscribe.equals(p.getType()))
											|| (Presence.Type.subscribed.equals(p.getType()))
											|| (Presence.Type.unsubscribed.equals(p.getType())));
									}
									return false;
								}
							});
							XmppGmailCon.getSubHandler(id_con,1,SubPacLis);
							*/
							register_prof=1;
							if(register_prof==0)
							{
								//Setting online status ..
								StatusUpdater st=new StatusUpdater();
								st.setCustomStatus(email_id,1,id_con);
							//	Thread IndividualMes = new Thread(new IndividualMessage(connections[id_con],id_con,email_id));
							//	IndividualMes.start();
								
							}
                                                        else if(register_prof==1)
                                                        {
                                                                //System.out.println("Comming");
                                                                if(connections!=null)
                                                                {
                                                                        this.removeListeners(id_con);

                                                                }
                                                                try{
                                                                        GtalkBot.ProfileMapGtalk.remove(email_id);
                                                                        GtalkBot.UserGtalkMap.remove(email_id);
                                                                        GtalkBot.pendingMsgMap.remove(email_id);
                                                                        GtalkBot.hashTable.remove(email_id);
                                                                        GtalkBot.profileMap.remove(email_id);
                                                                        GtalkBot.chatMap.remove(email_id);
                                                                        GtalkBot.profile_gmailIdMap.remove(prof_id);
                                                                }
                                                                catch(Exception ex)
                                                                {
                                                                        System.out.println("Exception in hashmap");
                                                                        ex.printStackTrace();
                                                                }
                                                        }      
	
							
						}	
						
					}
					if(subject.equals("0"))
					{
						int id_con=Integer.parseInt(prof_id);
						connections=XmppGmailCon.getInstance(id_con,1,null);
						//Setting offline status 
						StatusUpdater st=new StatusUpdater(connections);
						st.setCustomStatus(email_id,0,id_con);
						//System.out.println("Commingis");
						if(connections!=null)
						{
							this.removeListeners(id_con);
							
						}
						try{
							GtalkBot.ProfileMapGtalk.remove(email_id);
							GtalkBot.UserGtalkMap.remove(email_id);
							GtalkBot.pendingMsgMap.remove(email_id);
							GtalkBot.hashTable.remove(email_id);
							GtalkBot.profileMap.remove(email_id);
							GtalkBot.chatMap.remove(email_id);
							GtalkBot.profile_gmailIdMap.remove(prof_id);
						}
						catch(Exception ex)
						{
							System.out.println("Exception in hashmap");
							ex.printStackTrace();
						}	
						
						
						
					}
				}
			}	
		}
		catch(SQLException ex){ 
			try
			{
				ex.printStackTrace();
				
				db_con=MysqlInstance.getInstance();
				//db_con=null;
				//db_con = DriverManager.getConnection (urls, userNames, passwords);
			}
			catch(Exception e){ e.printStackTrace();}
		}	
		catch(Exception e){ e.printStackTrace();}			
	}
}
