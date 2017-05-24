package chat;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.sql.Statement;
import java.sql.DriverManager;
import org.apache.commons.dbcp.BasicDataSource;
import java.sql.DatabaseMetaData;

public class MysqlInstance {
   private static Connection conn1 = null;
  
	private static String urls = GtalkBot.newjs_url;
	private static String userNames = GtalkBot.newjs_username;
	private static String passwords = GtalkBot.newjs_password;
	private static BasicDataSource ds;
	public static Statement st_ds = null;
	protected static Connection[] con_arr=new Connection[3];
	static int which_call=0;
	static int conn_establish=0;
	int []arr=new int[3];
	
	private synchronized static Connection MysqlInstances() {
		try
		{
			
			
				Class.forName ("com.mysql.jdbc.Driver").newInstance ();
			
				conn1= DriverManager.getConnection (urls, userNames, passwords);
			
		}
		catch(Exception ex)
		{
				System.out.println("Error in getting mysql instance");
				ex.printStackTrace();
		}
		return conn1;	
	  // Exists only to defeat instantiation.
	}
	public synchronized static void closeConnectionMysql()
	{
		
		for(int i=0;i<3;i++)
		{
			try{
					
				con_arr[i].close();
				//con_arr[i]=null;
			}catch(Exception ex)
			{
				//con_arr[i]=null;
				System.out.println("Error in close mysql connection");
				ex.printStackTrace();
			}
		}
		conn_establish=0;			
	}
	public static Connection getInstance() {
		try
		{
			int allow=1;
			which_call=which_call%3;
			try{
				while(allow==1)
				{
					
					if(con_arr[which_call]==null)
					{
						con_arr[which_call]=MysqlInstances();
						allow=2;
					}
					else
					{
						if(con_arr[which_call].isClosed())
						{
								
								con_arr[which_call]=MysqlInstances();
								allow=2;
						}
						try
						{
							DatabaseMetaData db=con_arr[which_call].getMetaData();
							
							allow=2;
						}catch(SQLException ex)
						{
							con_arr[which_call]=MysqlInstances();
							allow=2;
						}	
					}
					
				}		
			}
			catch(NullPointerException ex)
			{
				System.out.println("HI");
			}
			
						
		}

		catch(Exception e)
		{
		   e.printStackTrace();
		   	
		 
		}
	   if(which_call>=3)
		which_call=0;
	  return con_arr[which_call++];
	}
}
