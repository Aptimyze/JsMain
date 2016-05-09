package org.gmail;

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

import java.util.ArrayList;
import java.util.Collection;
import java.util.HashMap;
/**
 * A sample plugin for Openfire.
 */
public class GmailPlugin implements Plugin,PacketInterceptor {

	    /**
     * used to send violation notifications
     */

     
     private PresenceRouter router;
     private MessageRouter messageRouter;
     public HashMap<String,String> map=new HashMap<String,String>();
     
    public void initializePlugin(PluginManager manager, File pluginDirectory) {
        // Your code goes here
        XMPPServer server = XMPPServer.getInstance();
     router = server.getPresenceRouter();
      messageRouter = XMPPServer.getInstance().getMessageRouter();
	InterceptorManager.getInstance().addInterceptor(this);
	
    }

    public void destroyPlugin() {
		//records.clear();
		InterceptorManager.getInstance().removeInterceptor(this);
        // Your code goes here
    }
   public void interceptPacket(org.xmpp.packet.Packet packet,Session session,boolean incoming,boolean processed)
   {
		try
		{
			
//Log.warn(packet.toXML());
			Connection con = null;
			//con = DbConnectionManager.getConnection();
			Statement st = null;
			//st = con.createStatement();
			//Log.warn(packet.toXML());
			if(packet instanceof Message)
			{
				
			}
			if(packet instanceof Presence)
			{
				 Presence originalPresence = (Presence) packet;
				 JID toJID=packet.getTo();
				 JID fromJID=packet.getFrom();
				 boolean modes;
				 int allow=0;
				String node,domain,resource,node1="",domain1="",resource1="",query="",pstatus;
				
				String botname="bot@"+XMPPServer.getInstance().getServerInfo().getXMPPDomain();
				//Log.warn(botname+" "+packet.toXML());
				if(fromJID!=null && toJID!=null && !toJID.getNode().equals("sathi") && !fromJID.getNode().equals("sathi") )
				{
					node=fromJID.getNode();
					domain=fromJID.getDomain();
					resource=fromJID.getResource();
					modes=originalPresence.isAvailable();
					pstatus=originalPresence.getStatus();
					if(pstatus==null)
						pstatus="testing";
					//Log.warn(""+originalPresence.getType()+" "+(originalPresence.getType()==Presence.Type.subscribe)+""+(originalPresence.getType()==Presence.Type.subscribed)+""+(originalPresence.getType()==Presence.Type.unsubscribe)+""+(originalPresence.getType()==Presence.Type.unsubscribed ));
					
					if(domain.equals("gmail.com"))
					{
						if(!(originalPresence.getType()==Presence.Type.subscribe || originalPresence.getType()==Presence.Type.subscribed || originalPresence.getType()==Presence.Type.unsubscribe || originalPresence.getType()==Presence.Type.unsubscribed ))
						{
							
							//Log.warn(packet.toXML());
							Message message = new Message();
							message.setTo(botname);
							message.setFrom(fromJID);
							if(map.containsKey(node))
							{
									if(originalPresence.getType()==Presence.Type.unavailable)
									{
										message.setSubject("0");
										message.setBody(""+fromJID+"");
										messageRouter.route(message);
										map.remove(node);
										
									}
										throw new PacketRejectedException("Presence already sent");
							}
							else
							{
								if(originalPresence.getType()!=Presence.Type.unavailable)
								{
									message.setSubject("1");
									message.setBody(""+fromJID+"");
									messageRouter.route(message);
									map.put(node,toJID.getNode());
								}
								throw new PacketRejectedException("Offline status already sent");
							}	
							
							
							
							
						}	
						else
						{
							
							Presence pp=new Presence(originalPresence.getType());
							pp.setTo(originalPresence.getFrom());
							pp.setFrom(originalPresence.getTo());
							router.route(pp);
							//Log.warn(" Done with it"+pp.toXML());
							
							//throw new PacketRejectedException("Presence sent"+pp.toXML());
							
							//Log.warn("Getting in subscription"+pp.toXML());
						}
					}
					
				
				}

				
			}
		}
		catch(Exception e)
		{
			 StringWriter sw = new StringWriter();
          PrintWriter pw = new PrintWriter(sw);
          e.printStackTrace(pw);
			Log.error(sw.toString());

		}

	}
    
    
}
