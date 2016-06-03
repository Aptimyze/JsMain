package chat;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.Statement;
import java.sql.SQLException;
import java.text.SimpleDateFormat;
import java.util.Date;

import org.jivesoftware.smack.packet.Message;

public class ChatRequestUpdater {
	public  Connection conn = null;
    public PreparedStatement pst =null;
    public PreparedStatement pst_logad=null;
    public String userplane_url=GtalkBot.userplane_url;
    public String userplane_username=GtalkBot.bot_js_url_username;
    public String userplane_password=GtalkBot.bot_js_url_password;
    public String status=null;
    public Statement st = null;
    
    
    
    
	public void updateChatRequest(Message msgPacket,String status){
		String receiverId=null; 
		String senderID=msgPacket.getThread();
		String receiverString=msgPacket.getSubject();
		int pos=receiverString.indexOf("/");
		this.status=status;
		if(pos !=-1){
			String receiverSubstr=receiverString.substring(0, pos);
			receiverId=receiverSubstr.substring(0, receiverSubstr.indexOf("@"));
		}
		Date date=new Date();
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd hh:mm:ss");
		SimpleDateFormat sdf1 = new SimpleDateFormat("yyyy-MM-dd");
		String insertion_date = sdf.format(date); 
		String insertion_date1 = sdf1.format(date); 
		try {
			conn = MysqlInstance.getInstance();
			
			st = conn.createStatement();
			String query="UPDATE userplane.`LOG_CHAT_REQUEST` SET ACTION='"+status.toUpperCase()+"' WHERE SEN ='"+senderID+"' AND REC='"+receiverId+"' order by ID DESC LIMIT 1";
                        st.executeUpdate(query);
			
			conn.setAutoCommit(false);
			//pst = conn.prepareStatement("insert into userplane.CHAT_REQUESTS (`SENDER`,`RECEIVER`,`TIMEOFINSERTION`) values(?,?,?)");
			pst=conn.prepareStatement("insert into userplane.LOG_AD (`SENDER`,`RECEIVER`,`STATUS`,`TIMEOFINSERTION`) values(?,?,?,?)");
			pst_logad=conn.prepareStatement("insert ignore into userplane.USERS_AD (`PROFILEID`,`DAYZ`) values(?,?)");
			
			
			pst.setString(1, senderID);
			pst.setString(2, receiverId);
			pst.setString(3, status);
			pst.setString(4, insertion_date);

			pst.executeUpdate();
			
			
			pst_logad.setString(1, receiverId);
			//Date dat=new Date("yyyy-MM-dd");
			pst_logad.setString(2, insertion_date1);
			//pst_logad.setDate(2, new Date(insertion_date1));
			//pst_logad.setDate(2,dat);
			pst_logad.executeUpdate();
			
			//System.out.println("inserted into table");
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		 finally{
	        	try {
					pst.close();
					pst_logad.close();
					//conn.close();
				} catch (SQLException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
	        	
	        }
		
		//String timeofInsertion=
		
	}

}
