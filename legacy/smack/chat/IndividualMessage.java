package chat;

import java.io.PrintStream;
import java.sql.*;
import org.jivesoftware.smack.XMPPConnection;
import org.jivesoftware.smack.packet.IQ;
import org.jivesoftware.smack.packet.Presence;
import org.jivesoftware.smack.Roster;
import org.jivesoftware.smack.RosterEntry;
import java.util.Properties;
import org.jivesoftware.smack.ConnectionConfiguration;
import org.jivesoftware.smack.XMPPConnection;
import org.jivesoftware.smack.XMPPException;
import org.jivesoftware.smack.packet.Message;
import org.jivesoftware.smack.packet.Packet;

// Referenced classes of package chat:
//            GtalkBot

public class IndividualMessage implements Runnable
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
	public String xmppservername="";
	protected SubscriptionListener SubListener;
	protected SubscriptionHandler SubHandlerLis;
	protected MessageParrot MessageLis;
    public IndividualMessage(XMPPConnection connect,int profileid,String to_whom)
    {
		
		
        user_prof=profileid;
        //connection = null;
        pst = null;
        delete_pst = null;
        connection = connect;
        receiver=to_whom;
        xmppservername = GtalkBot.subscription;
    }

    public void run()
    {
		SubListener=XmppGmailCon.getRosterSub(user_prof,1,null);
		SubHandlerLis=XmppGmailCon.getSubHandler(user_prof,1,null);
		MessageLis=XmppGmailCon.getMesListener(user_prof,1,null);
		
    	while(true){
        try
        {
			

	    //Stop this loop when user logs out.			
	    if(XmppGmailCon.getInstance(user_prof,1,null)==null)
		break;
	
        	conn=MysqlInstance.getInstance();
            pst = conn.prepareStatement("select MESSAGE from bot_jeevansathi.CUS_MES_MAIL where PROFILEID='"+user_prof+"' and SENT='N' and TYPE='G'");
            //pst.setInt(user_prof);
            delete_pst = conn.prepareStatement("update bot_jeevansathi.CUS_MES_MAIL set SENT='Y' where PROFILEID = ? and TYPE='G'");
            int count=0;
            ResultSet rs = pst.executeQuery();
           // System.out.println("rs is >>>>"+rs);
            while(rs.next()){
            	 String message = rs.getString("MESSAGE");
		if(message.equals("UnsetConnection"))
		{
			(new EachPresence()).removeListeners(user_prof);
		}
		else
		{
	            	 //String profileid=rs.getString("profileid");
            		Message message1=new Message();
			message1.setTo(receiver);
			message1.setBody(message);
			connection.sendPacket(message1);
			Thread.sleep(10000);
		}
            }
			 try
			 {
				 //System.out.println(profileid+" "+email);
				 delete_pst.setInt(1, user_prof);
				delete_pst.executeUpdate();
			}
			catch(Exception ex)
			{
					System.out.println("Excpetion in gmail sending request");
					ex.printStackTrace();
			}
					
	             
	             
            
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
            Thread.sleep(900000);//1 hr sleep
        }
        catch(InterruptedException e)
        {
            e.printStackTrace();
        }
        
        }
        
    }
}
