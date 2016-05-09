package chat;
import java.sql.PreparedStatement;
import java.sql.SQLException;

import org.jivesoftware.smack.PacketListener;
import org.jivesoftware.smack.packet.IQ;
import org.jivesoftware.smack.packet.Packet;
import org.jivesoftware.smack.packet.Presence;
import org.jivesoftware.smack.packet.RosterPacket;



public class PresenceListener implements PacketListener{
	
	 public PreparedStatement pst =null;
	 public void processPacket(Packet packet) {
         Presence presence = (Presence)packet;
         String  from = presence.getFrom();
         System.out.println("coming to presenceListener");
         //System.out.println("from is >>>>>"+presence.getFrom()+">>>to is>>>>"+presence.getTo());
         
        // System.out.println("presence type is>>>>"+presence.getType());
         //System.out.println("status msg is >>>>>"+presence.getStatus());
      //  StatusUpdater stsUpadter=new StatusUpdater();
      //  stsUpadter.setStatus(presence);
         /*
         try {
			pst = GtalkBot.conn.prepareStatement("update customer set cust_status=? where cust_email =?");
			if(presence.getStatus() != null){
				pst.setString(1, presence.getStatus());
			}else{
				pst.setString(1, presence.getType().toString());
			}
			
			System.out.println("after substring mail to is>>>"+presence.getFrom().substring(0, presence.getFrom().indexOf("/")));
          pst.setString(2, presence.getFrom().substring(0, presence.getFrom().indexOf("/")));
          pst.executeUpdate();
         
         }catch(SQLException e){
        	 e.printStackTrace();
         }
         */
      
     }

}
