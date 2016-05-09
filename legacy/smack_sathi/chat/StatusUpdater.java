package chat;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.sql.ResultSet;

import org.jivesoftware.smack.packet.Presence;
public class StatusUpdater {
	 //String userName = "localuser";
     //String password = "Km7Iv80l";
	
	String userName = GtalkBot.bot_js_url_username;
	String password = GtalkBot.bot_js_url_password;
     //String url = "jdbc:mysql://devjs.infoedge.com:3306/bot_jeevansathi";
	
     String url = GtalkBot.bot_js_url;
     
     
     public  Connection conn = null;
     public PreparedStatement pst =null;
     public PreparedStatement prof =null;
     public PreparedStatement user_online =null;
     public PreparedStatement user_offline =null;
     public PreparedStatement insertToTable=null;
     ResultSet rs=null;
     PreparedStatement setUpdate=null;
     public String status_msg="";
     public int on_off=1;
     public String from=null;
     
     //String sql = "update customer VALUES(?,?)";
    /* public StatusUpdater(){
    	 //System.out.println("GtalkBot name is >>>>"+GtalkBot.);
    	 userName = GtalkBot.bot_js_url_username;
    	 //System.out.println("userName in the StatusUpdater is >>>"+userName);
    	 
    	 password = GtalkBot.bot_js_url_password;
    	 //System.out.println("password in the StatusUpdater is >>>"+password);
     }*/
	public void setStatus(Presence presence){
		try {
			int prof_id=0;
			conn = MysqlInstance.getInstance();
			conn.setAutoCommit(false);

			from=presence.getFrom();
                        if(from.indexOf("/") != -1){
                                from=from.substring(0, from.indexOf("/"));
                        }
			//////////////////////////////////////////////////////////////////////////
			//Getting profileid from user_info ,, required to update user_online table
			prof = conn.prepareStatement("select PROFILEID,jeevansathi_ID from bot_jeevansathi.user_info where gmail_ID='"+from+"'");
				
				rs=prof.executeQuery();
				if(rs.next()){
					prof_id=Integer.parseInt(rs.getString("PROFILEID"));
					
				}
			user_online=null;
			user_offline=null;
			user_online=conn.prepareStatement("insert ignore into bot_jeevansathi.user_online set USER =?");
			user_offline=conn.prepareStatement("delete from bot_jeevansathi.user_online where USER=?");
			user_online.setInt(1, prof_id);
			user_offline.setInt(1, prof_id);
			////////////////////////////////////////////////////////////////////////////

			pst = conn.prepareStatement("update bot_jeevansathi.user_info set on_off_flag=?,status_message=? where gmail_ID =?");
			
			if(presence.getStatus() != null && !presence.getStatus().equals("")){
				//pst.setString(1, presence.getStatus());
				status_msg=presence.getStatus();
			}else if(presence.getMode() != null){
				//pst.setString(1, presence.getMode().toString());
				status_msg=presence.getMode().toString();
			}
			if(presence.getType().toString().equals("unavailable")){
				on_off=0;
				user_offline.executeUpdate();
			}else{
				on_off=1;
				user_online.executeUpdate();
			}
			//on_off=presence.getType().toString();
			
			//System.out.println("on_off is >>>>>"+on_off);
			//System.out.println("status_msg is >>>>"+status_msg);
			
			pst.setInt(1, on_off);
			pst.setString(2, status_msg);
			pst.setString(3, from);
			pst.executeUpdate();
			System.out.println("update  bot_jeevansathi.user_info for gmailid is >>>"+from+">>>and on_off_flag is >>>"+on_off);
		} catch (Exception e) {
			e.printStackTrace();
		}
		 finally{
	        	try {
					pst.close();
					//conn.close();
				} catch (SQLException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
	        	
	        }
		
	}
	
	public void insertNewData(String subscriptionFrom){
		try{
		conn = MysqlInstance.getInstance();
		conn.setAutoCommit(false);
		insertToTable=conn.prepareStatement("insert into bot_jeevansathi.user_info (gmail_ID,on_off_flag,show_in_search) values (?,?,?)");
		insertToTable.setString(1, subscriptionFrom.substring(0, subscriptionFrom.indexOf("/")));
    	insertToTable.setInt(2, 1);
    	insertToTable.setInt(3,1);
    	insertToTable.executeUpdate();
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		 finally{
	        	try {
	        		insertToTable.close();
					//conn.close();
				} catch (SQLException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
	        	
	        }
	}
	
	
public void setON_OFF(int show_hide,String msgFrom){
	try{
	
		conn = MysqlInstance.getInstance();
		conn.setAutoCommit(false);
		setUpdate=conn.prepareStatement("update bot_jeevansathi.user_info set show_in_search=? where gmail_ID =?");
		setUpdate.setInt(1, show_hide);
		setUpdate.setString(2, msgFrom);
    	//insertToTable.setInt(2, 1);
    	//insertToTable.setInt(3,1);
		setUpdate.executeUpdate();
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		 finally{
	        	try {
	        		setUpdate.close();
					//conn.close();
				} catch (SQLException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
	        	
	        }
	}
	
}
