package chat;
import org.jivesoftware.smack.Chat;
import org.jivesoftware.smack.MessageListener;
import org.jivesoftware.smack.XMPPConnection;
import org.jivesoftware.smack.XMPPException;
import org.jivesoftware.smack.packet.Message;

public class MessageReply implements MessageListener {
	private XMPPConnection xmppConnection;
       // private Message msg = new Message("manu1@xmpp.jeevansathi.com", Message.Type.chat);//manu.naukri@gmail.com
    String toBeSentID=null;
	public MessageReply(String  toBeSent,XMPPConnection conn) {
		toBeSentID = toBeSent;
		xmppConnection = conn;
    }
	
        // gtalk seems to refuse non-chat messages
        // messages without bodies seem to be caused by things like typing
        public void processMessage(Chat chat, Message message) {
        	System.out.println("message packet is>>>>"+message);
        	
            if(message.getType().equals(Message.Type.chat) && message.getBody() != null) {
               //System.out.println("Received: " + message.getBody());
               
               message.setSubject(null);
               message.setFrom("manu2@xmpp.infoedge.com");
               if(toBeSentID.contains("/")){
            	   toBeSentID=toBeSentID.substring(0, toBeSentID.indexOf("/"));
               }
               //System.out.println("toBesentId is>>>>>"+toBeSentID);
               message.setTo(toBeSentID);
               xmppConnection.sendPacket(message);
              
            } else {
               System.out.println("I got a message I didn''t understand");
            }
        }
    }