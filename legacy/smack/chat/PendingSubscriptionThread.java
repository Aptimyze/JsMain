package chat;

import java.util.Collection;

import org.jivesoftware.smack.Roster;
import org.jivesoftware.smack.RosterEntry;
import org.jivesoftware.smack.XMPPConnection;
import org.jivesoftware.smack.packet.IQ;
import org.jivesoftware.smack.packet.Presence;
import org.jivesoftware.smack.packet.RosterPacket;

public class PendingSubscriptionThread{
	private XMPPConnection xmppConnection;
	Roster roster=null;
	public PendingSubscriptionThread(XMPPConnection conn) {
	    xmppConnection = conn;
	    
	}
	public void run(Roster roster) {//this is for sendning subscription to thos user whose sub type is from
		try {
			
			Collection<RosterEntry> entries = roster.getEntries();
			System.out.println("pendning subscription thread is started>>"+entries.size());
			int q=0;
			 for (RosterEntry r : entries) {
				 	q++;
				 	//System.out.println(q+"...user in the PendingSubscription is>>"+r.getUser()+">>>and its type is >>"+r.getType());
		            if(r.getType() == RosterPacket.ItemType.from){
		            	System.out.println("sending packet for user>>>"+r.getUser());
		            	String subscriptionFrom=r.getUser();
		            	
		            	
		            	String nick=subscriptionFrom.substring(0, subscriptionFrom.indexOf("@"));
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
			
		} catch (Exception e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		}
	}

}
