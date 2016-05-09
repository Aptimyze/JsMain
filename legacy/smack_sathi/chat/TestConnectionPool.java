package chat;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.Statement;
import java.sql.ResultSet;
import java.text.DateFormat;
import java.util.Date;

public class TestConnectionPool {

	/**
	 * @param args
	 */
	public static void main(String[] args) {
		
		// TODO Auto-generated method stub
		/*String url = "jdbc:mysql://localhost:3306/chat";
		ConnectionPool pool1 = new ConnectionPool("localPool",
		                                         1,
		                                         10,
		                                         180000,  // milliseconds
		                                         url,
		                                         "root",
		                                         "root");

		pool1.init(5);
		
		
		Connection con = null;
		//long timeout = 2000;  // 2 second timeout
		try
		{
		System.out.println("conection pool is >>>>"+pool1);
		  con = pool1.getConnection();
		  if (con != null){
			  System.out.println("1111111111");
		   /// ...use the connection...
			  Statement stmt = con.createStatement();
			  String query ="select * from customer";
			  ResultSet rs=stmt.executeQuery(query);
			  while(rs.next()){
				  String email=rs.getString("cust_email");
				  System.out.println("email is >>>"+email);
			  }
		  } else{
		    //...do something else (timeout occurred)...
			  System.out.println("222222222 connection is nul");
		  }
		}
		catch (SQLException ex)
		{
		  //...deal with exception...
			ex.printStackTrace();
		}
		finally
		{
		  try { 
			  con.close(); 
		  }
		  catch (SQLException e) { //...
			  }
		  }*/
		
		
		try {
			String userName="openfire";
			String password="password";
			String url="jdbc:mysql://devjs.infoedge.com:3306/openfire";
			Class.forName ("com.mysql.jdbc.Driver").newInstance ();
			
			//System.out.println("url name is >>>>"+url+">>>>>userName is >>>"+userName+">>>>password is >>>"+password);
			Connection conn = DriverManager.getConnection (url, userName, password);
			//conn.setAutoCommit(false);
			
			System.out.println("connection to openfire is >>>"+conn);
			
			Statement stmt=conn.createStatement();
			ResultSet rs=stmt.executeQuery("SELECT * FROM ofUser WHERE username NOT IN (SELECT username FROM ofPresence)");
			
			while(rs.next()){
				String username=rs.getString("username");
				System.out.println("username is >>>>"+username);
				System.out.println("rs count is >>>>"+rs.getRow() );
				/*String offlinePresence=rs.getString("offlinePresence");
				System.out.println("offlinePresence is >>>>"+offlinePresence);
				
				String offlineDate=rs.getString("offlineDate");
				System.out.println("offlineDate is >>>>"+offlineDate);
				Date dt=new Date();
				//dt.getTime();
				//DateFormat df = DateFormat.getDateInstance();
				System.out.println("formating date is >>>>"+dt.getTime());*/
				 /* for (int i = 0; i < a.length; ++i) {
				    output.println(df.format(myDate[i]) + "; ");
				  }*/

			}
		} catch (InstantiationException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (IllegalAccessException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (ClassNotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch(SQLException ex){
			ex.printStackTrace();
		}
		 
		
		}


		

	}


