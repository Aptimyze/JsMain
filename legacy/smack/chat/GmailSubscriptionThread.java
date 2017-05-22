package chat;

import java.io.PrintStream;
import java.sql.*;
import org.jivesoftware.smack.XMPPConnection;
import org.jivesoftware.smack.packet.IQ;
import org.jivesoftware.smack.packet.Presence;
import org.jivesoftware.smack.packet.RosterPacket;
import java.util.Properties;
import org.jivesoftware.smack.ConnectionConfiguration;

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
        	conn=MysqlInstance.getInstance();
            pst = conn.prepareStatement("select gmailid,profileid from bot_jeevansathi.gmail_invites");
            delete_pst = conn.prepareStatement("DELETE FROM bot_jeevansathi.gmail_invites where gmailid = ?");
            int count=0;
            ResultSet rs = pst.executeQuery();
           // System.out.println("rs is >>>>"+rs);
            while(rs.next()){
            	 String email = rs.getString("gmailid");
            	 String profileid=rs.getString("profileid");
            	 
            	 try
            	 {
					 //System.out.println(profileid+" "+email);
					 (new sendGmailRequest()).sendSubscriptions(profileid,email);
					delete_pst.setString(1, email);
					delete_pst.executeUpdate();
				}
				catch(Exception ex)
				{
						System.out.println("Excpetion in gmail sending request");
						ex.printStackTrace();
				}
					
	             Thread.sleep(1000);
	             
            	 }
            
            
            
           // System.out.println("GmailSubscription end");
           
            
        }catch(SQLException ex){
            ex.printStackTrace();
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
            Thread.sleep(3000);//1 hr sleep
        }
        catch(InterruptedException e)
        {
            e.printStackTrace();
        }
        
        }
        
    }
}
