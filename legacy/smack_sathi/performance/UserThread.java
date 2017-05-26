package performance;

import java.util.Random;

import org.jivesoftware.smack.Chat;
import org.jivesoftware.smack.ChatManager;
import org.jivesoftware.smack.ConnectionConfiguration;
import org.jivesoftware.smack.XMPPConnection;
import org.jivesoftware.smack.XMPPException;
import org.jivesoftware.smack.filter.MessageTypeFilter;
import org.jivesoftware.smack.filter.PacketFilter;
import org.jivesoftware.smack.packet.Message;
import org.jivesoftware.smack.packet.Presence;

import chat.GtalkBot;
import chat.MessageParrot;

public class UserThread implements Runnable {
	
	String name=null;
	int count ;
	String pass; 
	 public UserThread(String name,String pass,int count)
	    {
		 this.name=name;
		 this.count=count;
		 this.pass=pass;
	    }
	
	
	public void run() {
		// TODO Auto-generated method stub
		
		//ConnectionConfiguration connConfig = new ConnectionConfiguration("federategtalk.infoedge.com", 5222);
		ConnectionConfiguration connConfig = new ConnectionConfiguration("devjs.infoedge.com", 5222);
        XMPPConnection connection = new XMPPConnection(connConfig);
        try {
        	System.out.println("logined by userid>>>>"+name);
			connection.connect();
			connection.login(name, pass);
			Presence presence = new Presence(Presence.Type.available);
			connection.sendPacket(presence);
			
			//ChatManager chatmanager = connection.getChatManager();
			
			
			
			PacketFilter filter = new MessageTypeFilter(Message.Type.chat);
	        ChatManager chatmanager = connection.getChatManager();
	        UserParrot msgParrot = new UserParrot(connection,chatmanager);
	        connection.addPacketListener(msgParrot, filter);
			
			
			
			 Random randomGenerator = new Random();
			 
			 for(int i=0; i<10;i++){
				 int randomInt = randomGenerator.nextInt(1000)+5000;
				 System.out.println("randomint is >>>>"+randomInt);
				 if(randomInt != this.count ){
					// Chat chat = chatmanager.createChat("manu"+randomInt+"@xmpp.jeevansathi.com", new UserParrot(connection));
					 //chat.sendMessage("hey h r u?????");
					 
					 Message message1=new Message();
         			//String msgBody=msgFrom+helpMsg;
					 message1.setTo("manu"+randomInt+"@devjs.infoedge.com");
         			message1.setType(Message.Type.chat);
         			message1.setBody("hey h r u?????");
         			connection.sendPacket(message1);
         			//chat.sendMessage(message1);
         			System.out.println("msg sent");
         			try {
						Thread.sleep(2000);
					} catch (InterruptedException e) {
						// TODO Auto-generated catch block
						e.printStackTrace();
					}
					 
				 }
			 }
			 System.out.println("exit!!!!!!!!!!");
			/*
			ChatManager chatmanager = connection.getChatManager();
	        UserParrot msgParrot = new UserParrot(connection,chatmanager);
			connection.addConnectionListener(arg0);
			*/
			
		} catch (XMPPException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		
	}

}
