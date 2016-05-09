package chat;

import org.jivesoftware.smack.Chat;
import org.jivesoftware.smack.ChatManager;
import org.jivesoftware.smack.XMPPConnection;

public class JsChatThread implements Runnable{
	
	String threadName=null;
	XMPPConnection xmppConnection=null;
	JsChatThread(String name,XMPPConnection connection){
		threadName=name;
		xmppConnection=connection;
	}
	
	public void run() {
		// TODO Auto-generated method stub
		
		
		ChatManager chatManager=(ChatManager)xmppConnection.getChatManager();
		Chat jschat=chatManager.createChat(threadName, new jsChatThreadListener(xmppConnection));
		GtalkBot.jsChatThreadMap.put(threadName,jschat);
		
	}

}
