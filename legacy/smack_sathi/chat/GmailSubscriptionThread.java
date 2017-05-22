package chat;

import java.io.PrintStream;
import java.sql.*;
import org.jivesoftware.smack.XMPPConnection;
import org.jivesoftware.smack.packet.IQ;
import org.jivesoftware.smack.packet.Presence;
import org.jivesoftware.smack.packet.RosterPacket;

// Referenced classes of package chat:
//            GtalkBot

public class GmailSubscriptionThread implements Runnable
{

    String userName;
    String password;
    String url;
    String subs;
    public Connection conn;
    public XMPPConnection connection;
    public PreparedStatement pst;
    public PreparedStatement delete_pst;

    public GmailSubscriptionThread(XMPPConnection connect)
    {
        userName = GtalkBot.bot_js_url_username;
        password = GtalkBot.bot_js_url_password;
        url = GtalkBot.bot_js_url;
        subs = GtalkBot.subscription;
        conn = null;
        //connection = null;
        pst = null;
        delete_pst = null;
        connection = connect;
    }

    public void run()
    {

    	while(true && subs.equals("true") ){
        try
        {
        	System.out.println("GmailSubscription is running");
		conn =MysqlInstance.getInstance();
            pst = conn.prepareStatement("select gmailid from bot_jeevansathi.invites");
            delete_pst = conn.prepareStatement("DELETE FROM bot_jeevansathi.invites where gmailid = ?");
            int count=0;
            ResultSet rs = pst.executeQuery();
           // System.out.println("rs is >>>>"+rs);
            while(rs.next()){
            	 String email = rs.getString("gmailid");
            	 System.out.println("gmail id is >>>>>"+email);
            	 if(email != null && !email.equals("")){
	        	 Presence presence1 = new Presence(Presence.Type.subscribe);
	             presence1.setTo(email);
	             presence1.toXML();
	             connection.sendPacket(presence1);
	             String nick = email.substring(0, email.indexOf("@"));
	             
	             
	            // RosterPacket.Item rosterItem = new RosterPacket.Item(subscriptionFrom,nick);
	             
	             RosterPacket.Item rosterItem = new RosterPacket.Item(email, nick);
	             rosterItem.addGroupName("Default Friend");
	             rosterItem.setItemType(RosterPacket.ItemType.to);
	             rosterItem.setItemStatus(RosterPacket.ItemStatus.SUBSCRIPTION_PENDING);
	             RosterPacket rosterPacket = new RosterPacket();
	             rosterPacket.setType(IQ.Type.SET);
	             rosterPacket.addRosterItem(rosterItem);
	             rosterPacket.toXML();
	             connection.sendPacket(rosterPacket);
	             delete_pst.setString(1, email);
	             int i = delete_pst.executeUpdate();
	             count++;
	             if(count >1000){
	            	 break;
	             }
	             
	             Thread.sleep(1000);
	             
            	 }
            }
            
            
           // System.out.println("GmailSubscription end");
           
            
        }catch(SQLException ex){
            ex.printStackTrace();
        }catch(Exception ex){
        	ex.printStackTrace();
        }
        finally{
        }
        
        
        try
        {
            Thread.sleep(1*60*60*1000);//1 hr sleep
        }
        catch(InterruptedException e)
        {
            e.printStackTrace();
        }
        
        }
        
    }
}
