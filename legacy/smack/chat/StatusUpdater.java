package chat;


import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.sql.Statement;

import org.apache.commons.lang.StringEscapeUtils;
import org.jivesoftware.smack.packet.Presence;
import org.jivesoftware.smack.Roster;
import org.jivesoftware.smack.RosterEntry;
import org.jivesoftware.smack.RosterListener;
import org.jivesoftware.smack.XMPPConnection;
import org.jivesoftware.smack.packet.IQ;
import org.jivesoftware.smack.packet.Message;
import org.jivesoftware.smack.packet.Presence;
import org.jivesoftware.smack.packet.RosterPacket;

public class StatusUpdater {
	 //String userName = "localuser";
     //String password = "Km7Iv80l";
	
	
     public PreparedStatement insertToTable=null;
     PreparedStatement setUpdate=null;
     public String status_msg="";
     public int on_off=1;
     public String from=null;
     
     public Statement st = null;
     public XMPPConnection xmppConnection;
	 public StatusUpdater(XMPPConnection conn) {
		
			 //xmppConnection=null;
	         xmppConnection = conn;
	         
		 
        // System.out.println("GtalkBot name is >>>>"+GtalkBot);
     }
     public StatusUpdater() {
		
			 //xmppConnection=null;
	         //xmppConnection = conn;
	         
		 
        // System.out.println("GtalkBot name is >>>>"+GtalkBot);
     }
	public void setStatus(Presence presence){
		
			
		try{
			//Class.forName ("com.mysql.jdbc.Driver").newInstance ();
			//Connection conn1=GtalkBot.mysqlConnection;
			 Connection conn1 = MysqlInstance.getInstance();

			
			st = conn1.createStatement();

			if(presence.getStatus() != null && !presence.getStatus().equals("")){
			
				status_msg=presence.getStatus();
			}else if(presence.getMode() != null){
			
				status_msg=presence.getMode().toString();
			}
			if(presence.getType().toString().equals("unavailable")){
				on_off=0;
			}else{
				on_off=1;
			}
			from=presence.getFrom();
		
			if(from.indexOf("/") != -1){
				from=from.substring(0, from.indexOf("/"));
			}
			
			//String query="update bot_jeevansathi.user_info set on_off_flag='"+on_off+"',status_message='"+StringEscapeUtils.escapeSql(status_msg)+"' where gmail_ID ='"+from+"'";
			String query="update bot_jeevansathi.user_info set on_off_flag='"+on_off+"' where gmail_ID ='"+from+"'";
			//System.out.println(query);
			st.executeUpdate(query);
			//conn1.close();
			}catch(Exception ex){
				ex.printStackTrace();
			}
			if(st != null){
				try {
					st.close();
					st=null;
				} catch (SQLException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
			}
	        	
	        	
	        
		
	}
	public void sub_unsub_users(String gmailID,int sub_unsub)
	{
		try{
	
			Connection mysqlConnection1=MysqlInstance.getInstance();
			
			setUpdate=mysqlConnection1.prepareStatement("insert ignore into bot_jeevansathi.SUB_UNSUB set EMAIL =?,SUB_TAKEN=?,`DATE`=now(),`TYPE`='G'");
			setUpdate.setString(1, gmailID);
			setUpdate.setInt(2, sub_unsub);
			setUpdate.executeUpdate();
		}
		catch(Exception ex){
			ex.printStackTrace();
		}
			
	}
public void setCustomStatus(String gmailID,int onoff,int profileid)
{
	try{
	
		Connection mysqlConnection1=MysqlInstance.getInstance();
		if(onoff==1)
			setUpdate=mysqlConnection1.prepareStatement("insert ignore into bot_jeevansathi.user_online set USER =?");
		else
			setUpdate=mysqlConnection1.prepareStatement("delete from bot_jeevansathi.user_online where USER=?");
				
		setUpdate.setInt(1, profileid);
		setUpdate.executeUpdate();
		setUpdate=null;
		setUpdate=mysqlConnection1.prepareStatement("update bot_jeevansathi.user_info set on_off_flag=? where gmail_ID =?");
		setUpdate.setInt(1, onoff);
		setUpdate.setString(2, gmailID);
		if(onoff==0)
		{
			String chattingWith=(String)GtalkBot.hashTable.get(gmailID);
			if(chattingWith!=null)
			{
				String user_jeev=(String)GtalkBot.UserGtalkMap.get(gmailID);
				String profid=(String)GtalkBot.profileMap.get(gmailID);
				Message message1=new Message();
				message1.setTo(chattingWith);
				message1.setFrom(user_jeev);
				//System.out.println("profileid is "+profid);
				message1.setSubject(profid+"@gmail.com");
				//String parts[]=new String[3];
				//parts=(new getJIDS()).getPartsJID(chattingWith);
				
				message1.setThread(profid);
				message1.setType(Message.Type.headline);
				message1.setBody("logout");
				//System.out.println(message1.toXML());
				xmppConnection.sendPacket(message1);
				GtalkBot.hashTable.remove(gmailID);
				GtalkBot.UserGtalkMap.remove(gmailID);
			}
		}
		
		setUpdate.executeUpdate();
		}catch(Exception ex){
			ex.printStackTrace();
		}
}
public void setCustomStatusforJS(int profileid,int onoff)
{
	try{
	
		Connection mysqlConnection1=MysqlInstance.getInstance();
		String sql="insert into userplane.users set userID =?";
		System.out.println(sql+" "+profileid+" "+onoff);
		if(onoff==1)
			setUpdate=mysqlConnection1.prepareStatement("insert into userplane.users set userID =?");
		else
			setUpdate=mysqlConnection1.prepareStatement("delete from userplane.users where userID=?");
			System.out.println(setUpdate);	
		setUpdate.setInt(1, profileid);
		setUpdate.executeUpdate();
		
		
		}catch(Exception ex){
			ex.printStackTrace();
		}
}
public void setON_OFF(int show_hide,String msgFrom){
	try{
	
		Connection mysqlConnection1=MysqlInstance.getInstance();
		
		setUpdate=mysqlConnection1.prepareStatement("update bot_jeevansathi.user_info set show_in_search=? where gmail_ID =?");
		setUpdate.setInt(1, show_hide);
		setUpdate.setString(2, msgFrom);
    	//insertToTable.setInt(2, 1);
    	//insertToTable.setInt(3,1);
		setUpdate.executeUpdate();
		String profid=(String)(GtalkBot.ProfileMapGtalk.get(msgFrom));
		//System.out.println(GtalkBot.ProfileMapGtalk);
		int profileid=Integer.parseInt(profid);
		if(show_hide==1)
			setUpdate=mysqlConnection1.prepareStatement("insert ignore into bot_jeevansathi.user_online set USER =?");
		else
			setUpdate=mysqlConnection1.prepareStatement("delete from  bot_jeevansathi.user_online where USER =?");
			
		setUpdate.setInt(1, profileid);
		setUpdate.executeUpdate();
		}catch(Exception ex){
			ex.printStackTrace();
		}
		if(setUpdate != null){
			try {
				setUpdate.close();
				setUpdate=null;
			} catch (SQLException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		}
	}
	
}
