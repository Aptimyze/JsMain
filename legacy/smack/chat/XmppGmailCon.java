package chat;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.sql.Statement;
import java.sql.DriverManager;
import org.apache.commons.dbcp.BasicDataSource;
import org.jivesoftware.smack.XMPPConnection;
import org.jivesoftware.smack.ConnectionConfiguration;
import java.util.Collection;
import java.util.HashMap;
public class XmppGmailCon {
	
   static HashMap<String,XMPPConnection> m=new HashMap<String,XMPPConnection>();
   
   public static HashMap<Integer,XMPPConnection> xmppCon =new HashMap<Integer,XMPPConnection>();
   public static HashMap<Integer,SubscriptionListener> sublis=new HashMap<Integer,SubscriptionListener>();
   public static HashMap<Integer,SubscriptionHandler> subhan=new HashMap<Integer,SubscriptionHandler>();
   public static HashMap<Integer,MessageParrot> meslis=new HashMap<Integer,MessageParrot>();
   public static int che_con=0;
   public static int sub_con=0;
   public static int sub_han=0;
   public static int mes_lis=0;
	protected XmppGmailCon() {
	  // Exists only to defeat instantiation.
	}
	public static XMPPConnection getInstance(int pid,int what,XMPPConnection connection) {
		try{
				Integer Key=new Integer(pid);
				
				if(connection!=null)
				{
					xmppCon.put(Key,connection);
				}
				else if(what==0)
				{
					if(xmppCon.containsKey(Key))
						xmppCon.remove(Key);
				}
				else
				{
					return xmppCon.get(Key);
				}
					
		}
		catch(Exception ex)
		{
			ex.printStackTrace();
		}
		return null;
	}
	public static SubscriptionListener getRosterSub(int pid, int what, SubscriptionListener subLis) {
		try{
			Integer Key=new Integer(pid);
			if(subLis!=null)
			{
				sublis.put(Key,subLis);
			}
                        //Remove from hashmap
                        else if(what==0)
                        {
                                if(sublis.containsKey(Key))
                                        sublis.remove(Key);
                        }
                        else
                        {
                                return sublis.get(Key);
                        }
		}
		catch(Exception ex)
		{
			ex.printStackTrace();
		}
		return null;
	}
	public static SubscriptionHandler getSubHandler(int pid, int what, SubscriptionHandler subLis) 
	{
		try
		{
                        Integer Key=new Integer(pid);
                        //Remove from hashmap
			if(subLis!=null)
                        {
                                subhan.put(Key,subLis);
                        }
                        else if(what==0)
                        {
                                if(subhan.containsKey(Key))
                                        subhan.remove(Key);
                        }
                        else
                        {
				return subhan.get(Key);
                                
                        }
                }
                catch(Exception ex)
                {
                        ex.printStackTrace();
                }
                return null;

	}
	public static MessageParrot getMesListener(int pid, int what, MessageParrot subLis) {
		try
                {
                        Integer Key=new Integer(pid);
                        //Remove from hashmap

			if(subLis!=null)
                        {
                                meslis.put(Key,subLis);
                        }
                        else if(what==0)
                        {
                                if(meslis.containsKey(Key))
                                        meslis.remove(Key);
                        }
                        else
                        {
				return meslis.get(Key);
                               
                        }
                }
                catch(Exception ex)
                {
                        ex.printStackTrace();
                }
                return null;
	}
	

}

