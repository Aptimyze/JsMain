package chat;

import java.sql.Connection;
import java.sql.SQLException;
import java.util.HashMap;

import javax.sql.DataSource;

import org.apache.commons.dbcp.BasicDataSource;

public class StandardLoneConnectonPool {

	/**
	 * @param args
	 */
	public  void main() {
		// TODO Auto-generated method stub
		DataSource ds=getDataSource();
		try {
			Connection conn=ds.getConnection();
			System.out.println("conn is >>>"+conn);
			try {
		    	//this
				
				int q[]=new int[10];
			
				//this.wait();
               Thread.sleep(1000);
            } catch (InterruptedException e) {
               e.printStackTrace();
            }
			conn.close();
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		HashMap <Integer,String> map=new HashMap<Integer,String>();
		map.put(1, "cool");
		
		synchronized (this) {
		        try {
			    	this.wait();
	               //Thread.sleep(1000);
	            } catch (InterruptedException e) {
	               e.printStackTrace();
	            }
		}	

	}
	public static DataSource getDataSource(){
		BasicDataSource ds = new BasicDataSource();
		ds.setDriverClassName("com.mysql.jdbc.Driver");
		ds.setUsername("localuser");
		ds.setPassword("Km7Iv80l");
		ds.setUrl("jdbc:mysql://testjs-chat.infoedge.com:3306/bot_jeevansathi");
		ds.setMaxActive(11);
		ds.setMaxIdle(1);
		return ds;
	}

}
