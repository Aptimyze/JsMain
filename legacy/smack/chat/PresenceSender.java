package chat;

import java.io.PrintStream;
import java.sql.*;
import org.jivesoftware.smack.XMPPConnection;
import org.jivesoftware.smack.packet.IQ;
import org.jivesoftware.smack.packet.Presence;
import org.jivesoftware.smack.packet.RosterPacket;
import java.util.Properties;
import org.jivesoftware.smack.ConnectionConfiguration;
import org.jivesoftware.smack.XMPPConnection;
import org.jivesoftware.smack.XMPPException;
import org.jivesoftware.smack.packet.Message;
import org.jivesoftware.smack.packet.Packet;
import org.jivesoftware.smack.Roster;
import org.jivesoftware.smack.RosterEntry;
import java.util.Collection;
// Referenced classes of package chat:
//            GtalkBot

public class PresenceSender implements Runnable
{

	String userName;
	String password;
	String url;
	String subs;
	public Connection conn;
	public XMPPConnection connection;
	public PreparedStatement pst;
	public PreparedStatement delete_pst;
	public int user_prof=1;
	public String receiver="";
	public String tagline="";
	public XMPPConnection connections;
	
    public PresenceSender(XMPPConnection connect,int profileid,String tag_line,String to_whom)
    {
		
		
        user_prof=profileid;
        tagline=tag_line;
        //connection = null;
        connection = connect;
        receiver=to_whom;
        
        
    }

    public void run()
    {
		connections=XmppGmailCon.getInstance(user_prof,1,null);
		while(true){
        try
        {
			

			//Stop this loop when user logs out.			
			if(connections==null)
			break;
			Roster rosters=null;
			/*rosters = connections[user_prof].getRoster();
			Collection<RosterEntry> entries = rosters.getEntries();
			int pass=0;
			for(RosterEntry r:entries)
			System.out.println(r.toString());
			Presence presence = new Presence(Presence.Type.available);
			presence.setTo(receiver);
			presence.setStatus(GtalkBot.tag_line);
			connection.sendPacket(presence);
			*/
			Thread.sleep(150000);
    
        }catch(Exception ex){
        	ex.printStackTrace();
        }
        finally{
	        try
	        {
	            //conn.close();
	        }
	        catch(Exception e)
	        {
	            e.printStackTrace();
	        }
        }
        
        
        try
        {
            Thread.sleep(3);//1 hr sleep
        }
        catch(InterruptedException e)
        {
            e.printStackTrace();
        }
        
        }
        
    }
}
