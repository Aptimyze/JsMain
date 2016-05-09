package performance;

import java.util.Date;

import org.jivesoftware.smack.Chat;
import org.jivesoftware.smack.ChatManager;
import org.jivesoftware.smack.MessageListener;
import org.jivesoftware.smack.PacketListener;
import org.jivesoftware.smack.XMPPConnection;
import org.jivesoftware.smack.XMPPException;
import org.jivesoftware.smack.packet.Message;
import org.jivesoftware.smack.packet.Packet;

public class UserParrot implements PacketListener{
	Date date=new Date();
	public long start_time;
	private XMPPConnection xmppConnection;
	private ChatManager chatManager;
	public UserParrot(XMPPConnection conn,ChatManager chatmanager) {
        xmppConnection = conn;
        chatManager=chatmanager;
        start_time=date.getTime();
    } 
	/*public void processMessage(Chat chat, Message message) {
		// TODO Auto-generated method stub
		System.out.println("U R ="+message.getTo());
		System.out.println("msg from ="+message.getFrom());
		System.out.println("message is>>>>"+message.getBody());
		long presentTime=date.getTime()-start_time;
		System.out.println("Differece in messaging time is >>>>"+presentTime);
		start_time=date.getTime();
		/*try {
			//chat.sendMessage("hey h r u????");
			 Message message1=new Message();
  			//String msgBody=msgFrom+helpMsg;
  			message1.setType(Message.Type.chat);
  			message1.setBody("hey h r u?????");
  			chat.sendMessage(message1);
			
		} catch (XMPPException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}*/
		
	//}
	public void processPacket(Packet packet) {
		// TODO Auto-generated method stub
		 Message message = (Message)packet;
		
//		 TODO Auto-generated method stub
		 System.out.println("exact ur >>>"+xmppConnection.getUser() );
		System.out.println("U R ="+message.getTo());
		System.out.println("msg from ="+message.getFrom());
		System.out.println("message is>>>>"+message.getBody());
		long presentTime=date.getTime()-start_time;
		System.out.println("Differece in messaging time is >>>>"+presentTime);
		start_time=date.getTime();
		
			try {
				Thread.sleep(5000);
			} catch (InterruptedException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
			//chat.sendMessage("hey h r u????");
			 Message message1=new Message();
  			//String msgBody=msgFrom+helpMsg;
			 message1.setTo(message.getTo());
  			message1.setType(Message.Type.chat);
  			message1.setBody("hey h r u?????");
  			//chat.sendMessage(message1);
  			xmppConnection.sendPacket(message1);
		
	}

}
