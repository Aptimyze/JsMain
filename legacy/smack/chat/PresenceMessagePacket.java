package chat;
import java.io.File;
import java.io.FileInputStream;
import java.io.InputStream;
import java.net.URL;
import java.sql.Connection;
import java.sql.DriverManager;

import java.util.ArrayList;
import java.util.Collection;
import java.util.HashMap;
import java.util.Hashtable;
import java.util.Properties;

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


 public  class PresenceMessagePacket implements PacketListener {
        private XMPPConnection xmppConnection;
        //private XMPPConnection clientConnection[10000000];
        public  Chat chat ;
        
        

	
	public String clientType=null;
	public String clientTo=null;
	public String clientFrom=null;
	public String toAll[]=new String[3];
	public String fromAll[]=new String[3];
	public  Connection db_con = null;
	String urls = GtalkBot.newjs_url;
	String userNames = GtalkBot.newjs_username;
	String passwords = GtalkBot.newjs_password;

	Properties pro;
	String xmpp_server_name;
	int	 xmpp_port;
	ConnectionConfiguration connConfigs;
	HashMap<String,String> m=new HashMap<String,String>();
		
        public PresenceMessagePacket(XMPPConnection conn) {
            xmppConnection = conn;
            try
            {
				InputStream in = getClass().getClassLoader().getResourceAsStream("conf/gTalkBot.properties");
				pro = new Properties();
				pro.load(in);
				xmpp_server_name = pro.getProperty("XMPP_SERVER_NAME");
				 xmpp_port = Integer.parseInt(pro.getProperty("XMPP_PORT"));
				
				connConfigs = new ConnectionConfiguration(xmpp_server_name, xmpp_port);
				
			}
			catch(Exception e)
			{
				System.out.println("Connection error process packet" );
				e.printStackTrace();
			}
        } 
        
        public void processPacket(Packet packet) {
	
		//System.out.println(packet.toXML());
		try
		{
			if(packet instanceof Message )
			{
				Message mr=(Message)packet;
				String email_id=mr.getBody();
				String subject=mr.getSubject();
				String from_user=mr.getFrom();
				//System.out.println(packet.toXML());
				if(subject!=null && (subject.equals("1") || (subject.equals("0"))))
				{
					(new EachPresence()).setPresence(subject,email_id,connConfigs);
					
				}
				else if(subject.equals("JS_USER_ONLINE") || subject.equals("JS_USER_OFFLINE"))
				{
					String[] parts=new String[3];
					parts=(new getJIDS()).getPartsJID(from_user);
					StatusUpdater st=new StatusUpdater();
					if(subject.equals("JS_USER_ONLINE"))
					{
						st.setCustomStatusforJS(Integer.parseInt(parts[0]),1);
					}
					else
					{
						st.setCustomStatusforJS(Integer.parseInt(parts[0]),0);
					}
					
				}
			}
		}catch(Exception e){ e.printStackTrace();}
    
    }
  }  
