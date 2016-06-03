package chat;

import org.jivesoftware.smack.Chat;
import org.jivesoftware.smack.ChatManager;
import org.jivesoftware.smack.XMPPConnection;
import org.jivesoftware.smack.XMPPException;
import org.jivesoftware.smack.packet.Message;

public class GtalkChatThread implements Runnable{
	public String threadName=null;
	public String nick=null;
	public String toWhomChatting=null;
	public XMPPConnection xmppConnection=null;
	public Chat gtalkChat=null;
	public Message message=null;
	
	public String helpMsg1=GtalkBot.help_msg1;
    public String helpMsg2=GtalkBot.help_msg2;
    public String conflict_msg1=GtalkBot.conflict_msg1;
    public String conflict_msg2=GtalkBot.conflict_msg2;
    public String conflict_msg3=GtalkBot.conflict_msg3;
	GtalkChatThread(String name,XMPPConnection connection,String msgFrom,String nickName){
		threadName=name;
		xmppConnection=connection;
		toWhomChatting=msgFrom;
		nick=nickName;
		
	}
	
	public void run() {
		// TODO Auto-generated method stub
		
		try {
			 ChatManager chatManager = xmppConnection.getChatManager();
			Chat gtalkChat=chatManager.createChat(threadName, new GtalkChatThreadListener(xmppConnection));
 			GtalkBot.gtalkChatThreadMap.put(threadName, gtalkChat);
 			
 			//System.out.println("toWhomChatting  is >>>"+toWhomChatting);
 			Chat jschat=chatManager.createChat(toWhomChatting, new jsChatThreadListener(xmppConnection));
 			GtalkBot.jsChatThreadMap.put(toWhomChatting,jschat);
 			
			//System.out.println("coming to GtalkChat thread");
			String msgBody=nick+" "+helpMsg1+nick+" "+helpMsg2;
			gtalkChat.sendMessage(msgBody);
		} catch (XMPPException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		return;
		
	}

}
