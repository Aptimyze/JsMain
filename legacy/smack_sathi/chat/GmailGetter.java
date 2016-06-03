package chat;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;

public class GmailGetter {

	public  Connection conn = null;
	public PreparedStatement stmt = null;
	public String query =null;
	ResultSet rs=null;
	//String userName = "localuser";
    //String password = "Km7Iv80l";
    //String url = "jdbc:mysql://devjs.infoedge.com:3306/newjs";
	String url = GtalkBot.newjs_url;
	String userName = GtalkBot.newjs_username;
	String password = GtalkBot.newjs_password;
	
    String gmail=null;
	public String getGmail(int profileID){
		
        try {
        	
			
			conn = MysqlInstance.getInstance();
			conn.setAutoCommit(false);
			stmt = conn.prepareStatement("select EMAIL from newjs.JPROFILE where PROFILEID=?");
			stmt.setInt(1, profileID);
			rs=stmt.executeQuery();
			while(rs.next()){
				gmail=rs.getString("EMAIL");
				System.out.println("gmail is >>>>"+gmail);
			}
			 //stmt = conn.createStatement();
			//query ="select EMAIL from JPROFILE where ";
			stmt.close();
			//conn.close();
		} catch (Exception e) {
			e.printStackTrace();
		}
		finally{
			rs=null;
			stmt=null;
			//conn=null;
		}
		
		
		
		//String query ="select * from customer";
        System.out.println ("Database connection established");
		return gmail;
		
	}
}
