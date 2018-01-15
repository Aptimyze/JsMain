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

import java.io.InputStream;

public class sendGmailRequest
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
	
	public Connection db_con=null;
	Properties pro;
	String xmpp_server_name;
	int	 xmpp_port;
	ConnectionConfiguration connConfigs;
	public sendGmailRequest()
	{
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
	
	public void sendSubscriptions(String profileid,String emailid)
	{
		try
		{
			
			
			db_con=MysqlInstance.getInstance();
			//int id_con=Integer.parseInt(prof_id);
			//connections = new XMPPConnection(connConfigs);
			(new EachPresence()).setPresence("1",emailid,connConfigs);
			
							
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}				
							
							
	}
}	
