package chat;

import org.jivesoftware.smack.PacketListener;
import org.jivesoftware.smack.packet.Packet;
import org.jivesoftware.smack.packet.Presence;
import org.jivesoftware.smack.packet.Presence;
import org.jivesoftware.smack.Roster;
import org.jivesoftware.smack.RosterEntry;
import org.jivesoftware.smack.RosterListener;
import org.jivesoftware.smack.XMPPConnection;

import java.util.Collection;
import java.util.Iterator;

public class SubscriptionHandler implements PacketListener {
	XMPPConnection connection;
	
	public SubscriptionHandler(XMPPConnection conn)
	{
		connection=conn;
	}
	public void processPacket(Packet aPacket) {
		// The specified PacketFilter has reduced incoming packets to subscription requests only
		// Now identify if it is a subscribe or unsubscribe and handle it
		try{
			if (aPacket instanceof Presence) {
				Presence packet=(Presence)aPacket;
					String froms=aPacket.getFrom();
					String tos=aPacket.getTo();
					
					String[] parts=new String[3];
					parts=(new getJIDS()).getPartsJID(froms);
				if(parts[1].equals("gmail.com"))
				{	
					String subscriptionFrom=parts[0]+"@"+parts[1];
					if(packet.getType()==Presence.Type.unsubscribe || packet.getType()==Presence.Type.unsubscribed)
					{
						
	
						
						try{
								(new StatusUpdater()).sub_unsub_users(subscriptionFrom,0);
						}catch(Exception ex)
						{
							System.out.println("Unsubscriptioni error"+aPacket.toXML());
							ex.printStackTrace();
						}	
						
					}
					else if(packet.getType()==Presence.Type.subscribe || packet.getType()==Presence.Type.subscribed)
					{
						try{
								(new StatusUpdater()).sub_unsub_users(subscriptionFrom,1);
								
						}catch(Exception ex)
						{
							System.out.println("Subscription error"+aPacket.toXML());
							ex.printStackTrace();
						}	
						
					}
				}	
				

			}
		}
		catch(Exception ex)
		{
			System.out.println("Exception in subscription handling -->"+aPacket.toXML());
			ex.printStackTrace();
		}	
	}
}
