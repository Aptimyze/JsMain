package chat;

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
	 public SubscriptionListener(XMPPConnection conn) {
         xmppConnection = conn;
         roster=xmppConnection.getRoster();
        // System.out.println("GtalkBot name is >>>>"+GtalkBot);
     } 
	 
    // Ignored events public void entriesAdded(Collection<String> addresses) {}
    public void entriesDeleted(Collection<String> addresses) {
    	//System.out.println("enties have been  added>>>>"+addresses);
    	/*Iterator iter=addresses.iterator();
		while (iter.hasNext()){
			//System.out.println("to be deleted is >>>>"+iter.next());
		}*/
    	
    }
    public void entriesUpdated(Collection<String> addresses) {
    	//System.out.println("Entries have been updated>>"+addresses);
    	
    }
    public void presenceChanged(Presence presence)
    {
    	//System.out.println("coming to presenceChanged method");
    	String from = presence.getFrom();
        from = from.substring(0, presence.getFrom().indexOf("/"));
        try
        {
        	//System.out.println("coming before setStatus");
            StatusUpdater stsUpdater = new StatusUpdater();
            stsUpdater.setStatus(presence);
            if(presence.getType().toString().equals("unavailable"))
            {
                String to = (String)GtalkBot.hashTable.get(from);
                String profileID1 = (String)GtalkBot.profileMap.get(from);
                if(to != null && profileID1 != null)
                {
                    GtalkBot.hashTable.remove(from);
                    GtalkBot.profileMap.remove(from);
                    GtalkBot.profile_gmailIdMap.remove(profileID1);
                    Message message1 = new Message();
                    message1.setTo(to);
                    message1.setType(Message.Type.headline);
                    message1.setBody("ending");
                    message1.setThread(profileID1);
                    message1.setSubject((new StringBuilder(String.valueOf(profileID1))).append("@gmail.com").toString());
                    xmppConnection.sendPacket(message1);
                    Message msgPacket = (Message)GtalkBot.pendingMsgMap.get(from);
                    if(msgPacket != null)
                    {
                        GtalkBot.pendingMsgMap.remove(from);
                    }
                }
            }
        }
        catch(Exception e)
        {
            e.printStackTrace();
        }
    }

	public void entriesAdded(Collection<String> arg0) {
		// TODO Auto-generated method stub
		//System.out.println("New entries have been added>>>"+arg0);
	
		try {
			
			//insertToTable = GtalkBot.conn.prepareStatement("insert into customer (cust_email,cust_status) values (?,?)");
			//StatusUpdater stsUpdater=new StatusUpdater();
			//int m=0;
			Iterator iter=arg0.iterator();
			while (iter.hasNext()){
				String subscriptionFrom=(String)iter.next();
				if(subscriptionFrom.indexOf("/") != -1){
					subscriptionFrom=subscriptionFrom.substring(0, subscriptionFrom.indexOf("/"));
				}
				RosterEntry rosterEntry=roster.getEntry(subscriptionFrom);
				if(rosterEntry.getType()== RosterPacket.ItemType.from){
					String nick=subscriptionFrom.substring(0, subscriptionFrom.indexOf("@"));
					System.out.println("nick in the subscriptionListener is >>>"+nick);
					RosterPacket.Item rosterItem = new RosterPacket.Item(subscriptionFrom,nick);
			        rosterItem.addGroupName("Default Friend");
			        rosterItem.setItemType(RosterPacket.ItemType.to);
			        rosterItem.setItemStatus(RosterPacket.ItemStatus.SUBSCRIPTION_PENDING);
			        
		//	      send the RosterPacket to update roster list in jabber database
			        RosterPacket rosterPacket = new RosterPacket();
			        rosterPacket.setType(IQ.Type.SET);
			        rosterPacket.addRosterItem(rosterItem);
			        rosterPacket.toXML();
			      //  System.out.println("GtalkBot.connection is >>>>"+xmppConnection);
			        xmppConnection.sendPacket(rosterPacket);
			        
			        Presence presence1 = new Presence(Presence.Type.subscribe);
			        presence1.setTo(subscriptionFrom);
			        String destination = xmppConnection.getUser();
			        presence1.setFrom(destination);
			        presence1.toXML();
			        xmppConnection.sendPacket(presence1);
		        
				}  
	        	
			}
			System.out.println("Done");
		} catch (Exception e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		}
		
		
	}
	
	

}
