package org.removesathi;

import org.jivesoftware.openfire.container.Plugin;
import org.jivesoftware.openfire.plugin.*;
import org.jivesoftware.openfire.container.PluginManager;
import org.jivesoftware.openfire.MessageRouter;
import org.jivesoftware.openfire.PresenceRouter;
import org.jivesoftware.openfire.IQRouter;
import org.jivesoftware.openfire.PacketDeliverer;
import org.jivesoftware.openfire.XMPPServer;
import org.jivesoftware.util.EmailService;
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
import org.xmpp.packet.Roster;
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
public class GmailRemovePlugin implements Plugin {

	   /**
     * used to send violation notifications
     */

     
     private PresenceRouter router;
     private MessageRouter messageRouter;
          
    
    public void initializePlugin(PluginManager manager, File pluginDirectory) {
        // Your code goes here
   		XMPPServer server = XMPPServer.getInstance();
		router = server.getPresenceRouter();
		messageRouter = XMPPServer.getInstance().getMessageRouter();
		removeUser();
    }

    public void destroyPlugin() {
		//records.clear();
		
    }
   public void removeUser()
   {
		try
		{
				String[] userData=new String[2];
				userData=getProfileFromRoster();
				String botname="";
				String profileid="";
				//Log.warn(profileid+" "+botname);
			while(userData[0]!=null)
			{
				
				profileid=userData[0];
				botname=userData[1]+"@"+XMPPServer.getInstance().getServerInfo().getXMPPDomain();
				removeUserFromBot(profileid,botname);
				
				//make entry into gmail_invites and mailer logic
				send_gmail_invite(profileid);
				
				userData=getProfileFromRoster();	
			}
		}
		catch(Exception e)
		{
			
			Log.error("in exception"+e);

		}

	}
	public void removeUserFromBot(String profileid,String botname)
	{
		try{
			
			Roster roster=new Roster(Roster.Type.set);
			roster.setTo(botname);

			roster.setFrom(profileid);

			roster.addItem(profileid,Roster.Subscription.remove);
			//Log.warn(roster.toXML());
			IQRouter iqroute=XMPPServer.getInstance().getIQRouter();

			iqroute.route(roster);


			Presence pp1=new Presence(Presence.Type.unsubscribe);
			pp1.setTo(profileid);
			pp1.setFrom(botname);
			router.route(pp1);

			Presence pp2=new Presence(Presence.Type.unsubscribed);
			pp2.setTo(profileid);
			pp2.setFrom(botname);
			router.route(pp2);
		}
		catch(Exception ex)
		{
			System.out.println(" catch in remove user from bot"+ex);
		}
	}
	public void send_gmail_invite(String emailid)
	{
		
		PreparedStatement st=null;
		ResultSet rs=null;
		Connection congmail=null;
		int alreadyPresent=0;
		if(emailid==null)
			return;
		try{
			
			congmail = DbConnectionManager.getConnection(); 
			st = congmail.prepareStatement("select USERNAME,PROFILEID,SUBSCRIPTION from newjs.JPROFILE where EMAIL='"+emailid+"' and ACTIVATED!='D' and activatedKey=1");
			//Log.warn("select USERNAME,PROFILEID,SUBSCRIPTION from newjs.JPROFILE where EMAIL='"+emailid+"'");
			rs=st.executeQuery();
			if(rs.next()){
				String username=rs.getString("USERNAME");
				String profileid=rs.getString("PROFILEID");
				String subscription=rs.getString("SUBSCRIPTION");
				//Log.warn("--"+subscription);
				st=congmail.prepareStatement("select username,jid from openfire.ofRoster where username !='sathi' and jid='"+emailid+"'");
				//Log.error("select username,jid from openfire.ofRoster where username !='sathi' and jid='"+emailid+"'");
				rs=st.executeQuery();
				while(rs.next())
				{
					//Log.error(rs.getString("username")+" "+username.toLowerCase());
					if(!(rs.getString("username").equals(username.toLowerCase())))
					{
						//Log.error("GET");
							removeUserFromBot(emailid,rs.getString("username")+"@"+XMPPServer.getInstance().getServerInfo().getXMPPDomain());
					}		
					else
						alreadyPresent=1;
				}
				if(alreadyPresent==0)
				{
					st=congmail.prepareStatement("delete from bot_jeevansathi.user_info where gmail_ID='"+emailid+"' OR profileID='"+profileid+"'");
				//	Log.error("delete from bot_jeevansathi.user_info where gmail_ID='"+emailid+"' OR profileID='"+profileid+"'");
					st.execute();
					st=congmail.prepareStatement("insert into bot_jeevansathi.user_info(`gmail_ID`,`on_off_flag`,`show_in_search`,`profileID`,`jeevansathi_ID`) values('"+emailid+"','0','1','"+profileid+"','"+username+"')");
					//Log.error("insert into bot_jeevansathi.user_info(`gmail_ID`,`on_off_flag`,`show_in_search`,`profileID`,`jeevansathi_ID`) values('"+emailid+"','0','1','"+profileid+"','"+username+"')");
					st.execute();
					
					st=congmail.prepareStatement("insert into bot_jeevansathi.gmail_invites(profileid,gmailid) values('"+username+"','"+emailid+"')");
					//Log.error("insert into bot_jeevansathi.gmail_invites(profileid,gmailid) values('"+username+"','"+emailid+"')");
					st.execute();
					
					st=congmail.prepareStatement("insert into bot_jeevansathi.invite_send(PROFILEID,EMAIL) values('"+profileid+"','"+emailid+"')");
					//Log.error("insert into bot_jeevansathi.invite_send(PROFILEID,EMAIL) values('"+profileid+"','"+emailid+"')");
					st.execute();
					send_email(profileid,emailid,username,subscription);
				}
			}
			
		}
		catch(Exception ex)
		{
			Log.error("Error in sending invite"+ex.toString());
		}
		finally { 
                DbConnectionManager.closeConnection(rs,st, congmail); 
            }
	}
	public void send_email(String profileid,String emailid,String username,String subscription)
	{
		String domain=XMPPServer.getInstance().getServerInfo().getXMPPDomain();
		String  paidUser="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"><html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /><title>Jeevansathi</title></head><body><table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#cdcccc\" style=\"border: solid 1px #adacac;\">   <tr>     <td colspan=\"3\" height=\"11\"></td>   </tr>   <tr>     <td colspan=\"3\" align=\"center\"><table width=\"579\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#FFFFFF\">     <tr>         <td colspan=\"4\"><img src=\"http://ieplads.com/mailers/JS/gtalk_05sep11/gifs/bg1.jpg\" width=\"579\" height=\"8\" hspace=\"0\" vspace=\"0\" align=\"left\" /></td>         </tr>       <tr>         <td width=\"11\"></td>         <td align=\"left\"><img src=\"http://ieplads.com/mailers/JS/gtalk_05sep11/gifs/logo.gif\" width=\"202\" height=\"43\" hspace=\"0\" vspace=\"0\"/></td>         <td align=\"right\"><img src=\"http://ieplads.com/mailers/JS/gtalk_05sep11/gifs/naukri.gif\" width=\"159\" height=\"18\" hspace=\"0\" vspace=\"0\"/></td>         <td width=\"15\"></td>       </tr>       <tr>         <td colspan=\"4\"><img src=\"http://ieplads.com/mailers/JS/gtalk_05sep11/gifs/bg2.jpg\" width=\"579\" height=\"8\" hspace=\"0\" vspace=\"0\" align=\"left\" /></td>         </tr>     </table></td>   </tr>   <tr>     <td colspan=\"3\" height=\"17\"></td>   </tr>   <tr>     <td colspan=\"3\"><img src=\"http://ieplads.com/mailers/JS/gtalk_05sep11/gifs/image1.jpg\" width=\"600\" height=\"105\" hspace=\"0\" vspace=\"0\" align=\"left\" /></td>   </tr>   <tr>     <td width=\"78\" height=\"216\"><img src=\"http://ieplads.com/mailers/JS/gtalk_05sep11/gifs/image2.jpg\" width=\"78\" height=\"216\"   style=\"vertical-align:bottom;\"  /></td>     <td height=\"216\" width=\"443\" background=\"http://ieplads.com/mailers/JS/gtalk_05sep11/gifs/image3.jpg\"  bgcolor=\"#f1f1f1\" style=\"font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000000; text-align:justify;\">Dear "+username+",<br /> <br />      To make your <b>chat experience faster and more secure,</b> we have created a separate chat id exclusively for you! Just accept the friend request from <b>"+username.toLowerCase()+"@"+domain+"</b> on gtalk and start chatting with your prospective matches directly from gtalk. You can also manually add <b>"+username.toLowerCase()+"@"+domain+"</b> in case you have not received the friend request.<br /> <br /> Why wait? Begin a chat now! <br /> <br /> <i>Any issues? Please feel free to get in touch with our customer service team at</i> <span style=\"color:#c4161c;\"><b>18004196299 (toll-free)</b></span>.</td>     <td width=\"79\"><img src=\"http://ieplads.com/mailers/JS/gtalk_05sep11/gifs/image4.jpg\" width=\"79\" height=\"216\"   style=\"vertical-align:bottom;\"   /></td>   </tr>   <tr>     <td colspan=\"3\"><img src=\"http://ieplads.com/mailers/JS/gtalk_05sep11/gifs/image5.jpg\" width=\"600\" height=\"232\" /></td>   </tr>   <tr>     <td height=\"16\"></td>     <td height=\"85\" align=\"left\" valign=\"top\" background=\"http://ieplads.com/mailers/JS/gtalk_05sep11/gifs/image6.jpg\" bgcolor=\"f1f1f1\" style=\"font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000000;\"><i>All the best in your partner search!</i><br />       <br />       <b>With Best Regards</b><br />       <b>Team <span style=\"color:#c4161c;\">Jeevansathi</span></b> </td>     <td></td>   </tr> </table></body></html>";
		
		String freeUser="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"><html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /><title>Jeevansathi</title></head><body><table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#cdcccc\" style=\"border: solid 1px #adacac;\">   <tr>     <td colspan=\"3\" height=\"11\"></td>   </tr>   <tr>     <td colspan=\"3\" align=\"center\"><table width=\"579\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#FFFFFF\">     <tr>         <td colspan=\"4\"><img src=\"http://ieplads.com/mailers/JS/gtalk_05sep11/gifs/bg1.jpg\" width=\"579\" height=\"8\" hspace=\"0\" vspace=\"0\" align=\"left\" /></td>         </tr>       <tr>         <td width=\"11\"></td>         <td align=\"left\"><img src=\"http://ieplads.com/mailers/JS/gtalk_05sep11/gifs/logo.gif\" width=\"202\" height=\"43\" hspace=\"0\" vspace=\"0\"/></td>         <td align=\"right\"><img src=\"http://ieplads.com/mailers/JS/gtalk_05sep11/gifs/naukri.gif\" width=\"159\" height=\"18\" hspace=\"0\" vspace=\"0\"/></td>         <td width=\"15\"></td>       </tr>       <tr>         <td colspan=\"4\"><img src=\"http://ieplads.com/mailers/JS/gtalk_05sep11/gifs/bg2.jpg\" width=\"579\" height=\"8\" hspace=\"0\" vspace=\"0\" align=\"left\" /></td>         </tr>     </table></td>   </tr>   <tr>     <td colspan=\"3\" height=\"17\"></td>   </tr>   <tr>     <td colspan=\"3\"><img src=\"http://ieplads.com/mailers/JS/gtalk_05sep11/gifs/image1.jpg\" width=\"600\" height=\"105\" hspace=\"0\" vspace=\"0\" align=\"left\" /></td>   </tr>   <tr>     <td width=\"78\" height=\"216\"><img src=\"http://ieplads.com/mailers/JS/gtalk_05sep11/gifs/image2.jpg\" width=\"78\" height=\"216\"   style=\"vertical-align:bottom;\"  /></td>     <td height=\"216\" width=\"443\" background=\"http://ieplads.com/mailers/JS/gtalk_05sep11/gifs/image3.jpg\"  bgcolor=\"#f1f1f1\" style=\"font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000000; text-align:justify;\">Dear "+username+",<br />      <br />       To make your <b>chat experience faster and more secure,</b> we have created a separate chat id exclusively for you! Just accept the friend request from <b>"+username.toLowerCase()+"@"+domain+"</b> on G-talk and start chatting with your prospective matches from gtalk. You can also manually add <b>"+username.toLowerCase()+"@"+domain+"</b> in case you have not received the friend request.<br /> <br /> You will be able to receive chat requests as a free member. To initiate chat with members, <span style=\"color:#c4161c;\"><a href=\"http://www.jeevansathi.com/profile/mem_comparison.php\">become a paid member now! </a> </span> <br /> <br /> <i>Any issues? Please feel free to get in touch with our customer service team at</i> <span style=\"color:#c4161c;\"><b>18004196299 (toll-free)</b></span>.</td>     <td width=\"79\"><img src=\"http://ieplads.com/mailers/JS/gtalk_05sep11/gifs/image4.jpg\" width=\"79\" height=\"216\"   style=\"vertical-align:bottom;\"   /></td>   </tr>   <tr>     <td colspan=\"3\"><img src=\"http://ieplads.com/mailers/JS/gtalk_05sep11/gifs/image5.jpg\" width=\"600\" height=\"232\" /></td>   </tr>   <tr>     <td height=\"16\"></td>     <td height=\"85\" align=\"left\" valign=\"top\" background=\"http://ieplads.com/mailers/JS/gtalk_05sep11/gifs/image6.jpg\" bgcolor=\"f1f1f1\" style=\"font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000000;\"><i>All the best in your partner search!</i><br />       <br />       <b>With Best Regards</b><br />       <b>Team <span style=\"color:#c4161c;\">Jeevansathi</span></b> </td>     <td></td>   </tr> </table></body></html>";
		
		
		  
		  String from = "info@jeevansathi.com";
		  String to = emailid;
		  to="vidushi.engg@gmail.com";
		  String htmlBody="";
		 
		//  Log.warn(subscription+" "+subscription.indexOf("F"));
		  if(subscription.indexOf("F")!=-1)
			htmlBody=paidUser;
		else
			htmlBody=freeUser;
		String subject="Add "+username.toLowerCase()+"@"+domain+" on G-talk and chat securely!";
		//this is automatically put on a another thread for execution.
        EmailService.getInstance().sendMessage(to, to, from, from,subject,null, htmlBody) ;
			
	}
	public String[] getProfileFromRoster()
	{
		
		String[] userData=new String[2];
		PreparedStatement st=null;
		ResultSet rs=null;
		Connection congmail=null;
		try
			{
				Thread.sleep(1000);
				congmail = DbConnectionManager.getConnection(); 
				st = congmail.prepareStatement("select username,jid from bot_jeevansathi.removeusers limit 1");
				rs=st.executeQuery();
				if(rs.next()){
					userData[0]=rs.getString("jid");
					userData[1]=rs.getString("username");
					st=congmail.prepareStatement("delete from bot_jeevansathi.removeusers where username='"+userData[1]+"' and jid='"+userData[0]+"'");
					st.execute();
				}
			}
			catch(Exception ex)
			{
				Log.error("Error in getting jid"+ex);
			}
			finally { 
                DbConnectionManager.closeConnection(rs,st, congmail); 
            }
            
            return userData;
	}

    
    
}
