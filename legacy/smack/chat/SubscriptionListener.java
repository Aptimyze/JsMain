package chat;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.util.Collection;
import java.util.Iterator;

import org.jivesoftware.smack.Roster;
import org.jivesoftware.smack.RosterEntry;
import org.jivesoftware.smack.RosterListener;
import org.jivesoftware.smack.XMPPConnection;
import org.jivesoftware.smack.packet.IQ;
import org.jivesoftware.smack.packet.Message;
import org.jivesoftware.smack.packet.Presence;
import org.jivesoftware.smack.packet.RosterPacket;

public class SubscriptionListener implements RosterListener{
	 private XMPPConnection xmppConnection;
	 public PreparedStatement pst =null;
	 public PreparedStatement insertToTable=null;
	 Roster roster=null;
	
	 public  Connection connection = null;
	 
	 public SubscriptionListener(XMPPConnection conn) {
		
			// xmppConnection=null;
	         xmppConnection = conn;
	         roster=xmppConnection.getRoster();
		 
        // System.out.println("GtalkBot name is >>>>"+GtalkBot);
     } 
	 
    // Ignored events public void entriesAdded(Collection<String> addresses) {}
    public void entriesDeleted(Collection<String> addresses) {
		//System.out.println("Coming to no section");
    	
    	
    }
    public void entriesUpdated(Collection<String> arg0) {
		//System.out.println("In updation"+arg0);
		//System.out.println("Entries updated");
		
    }	
    public void presenceChanged(Presence presence)
    {
		
           
       //System.out.println("In presence changed"+presence.toXML());
        
    }

	public void entriesAdded(Collection<String> arg0) {
		
		// TODO Auto-generated method stub

	
		try {
			
			
			int m=0;
			int n=0;
			//System.out.println("total collection size is >>>>"+arg0.size());
			Iterator iter=arg0.iterator();
			while (iter.hasNext()){
				String subscriptionFrom=(String)iter.next();
				//System.out.println("bane >>>>"+subscriptionFrom);
				if(subscriptionFrom.indexOf("/") != -1){
					subscriptionFrom=subscriptionFrom.substring(0, subscriptionFrom.indexOf("/"));
				}
				RosterEntry rosterEntry=roster.getEntry(subscriptionFrom);
				
				if(rosterEntry.getType()== RosterPacket.ItemType.from){
					String nick=subscriptionFrom.substring(0, subscriptionFrom.indexOf("@"));
					
					RosterPacket.Item rosterItem = new RosterPacket.Item(subscriptionFrom,nick);
			        rosterItem.addGroupName("Default Friend");
			        
			        rosterItem.setItemType(RosterPacket.ItemType.to);
			       
			        
		
			        RosterPacket rosterPacket = new RosterPacket();
			        rosterPacket.setType(IQ.Type.SET);
			        rosterPacket.addRosterItem(rosterItem);
			        rosterPacket.toXML();
		
			        xmppConnection.sendPacket(rosterPacket);
			        
			        n++;
				}  
				m++;
			}
			//Sent presence avilable to gtalk user
			
			System.out.println("Done");
		} catch (Exception e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		}
		
		
	}
	
	

}
