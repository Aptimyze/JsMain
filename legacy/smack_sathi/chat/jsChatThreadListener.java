package chat;

import org.jivesoftware.smack.Chat;
import org.jivesoftware.smack.MessageListener;
import org.jivesoftware.smack.XMPPConnection;
import org.jivesoftware.smack.packet.Message;

public class jsChatThreadListener implements MessageListener{
	public String helpMsg1=GtalkBot.help_msg1;
    public String helpMsg2=GtalkBot.help_msg2;
    public String conflict_msg1=GtalkBot.conflict_msg1;
    public String conflict_msg2=GtalkBot.conflict_msg2;
    public String conflict_msg3=GtalkBot.conflict_msg3;
    private XMPPConnection xmppConnection;
    
    public jsChatThreadListener(XMPPConnection conn) {
        xmppConnection = conn;
        //chatManager=chatmanager;
    } 
    
	public void processMessage(Chat chat, Message message) {
		// TODO Auto-generated method stub
		//System.out.println("msg in the jsChatThread is >>>>"+message.getBody());
		
		try{
			 if(message.getBody() != null ) {
				 if(message.getFrom().contains("/")){
         			String from=message.getFrom();
         			message.setFrom(from.substring(0, from.indexOf("/")));
         		}
         		//message.get
         		String msgFrom=message.getFrom();
         		String nick=null;
        		String profileID=null;
        		String subject=message.getSubject();
        		if(subject != null){
        			nick=subject.substring(subject.indexOf("/")+1,subject.length());
        			profileID=subject.substring(0, subject.indexOf("@"));
        		}
        		String msg=message.getBody().trim();
        		
        		String gmailID=null;
        		if(profileID != null && !profileID.equals("")){
        			
        			gmailID=(String)GtalkBot.profile_gmailIdMap.get(profileID);
        		/* this is for getting gmail from profileid*/
        			if(gmailID  == null){
            			GmailGetter gmailGetter=new GmailGetter();
            			gmailID=gmailGetter.getGmail(Integer.parseInt(profileID));
            			if(gmailID != null){
            				GtalkBot.profile_gmailIdMap.put(profileID, gmailID);
            			}
        			}
        		}
        		
        		if(gmailID != null){
        			String chattingWith=(String)GtalkBot.hashTable.get(gmailID);
        			//System.out.println("gmailID is>>>"+gmailID);
        			Chat gtalkchat=(Chat)GtalkBot.gtalkChatThreadMap.get(gmailID);
        			if(chattingWith == null){
            			if(msg.equals("time_out")){
            				
	            			gtalkchat.sendMessage("User "+nick+" has aborted the request.");
	            			GtalkBot.pendingMsgMap.remove(gmailID);
            			}
            		}else if(chattingWith.equals(msgFrom)){
            			if(message.getBody().equals("ask_chatAuth"))
            	        {
            	            GtalkBot.hashTable.remove(gmailID);
            	            GtalkBot.profileMap.remove(gmailID);
            	            //String msgBody = (new StringBuilder(String.valueOf(msgFrom))).append(helpMsg1).append(nick).append(helpMsg2).append(helpMsg3).append(helpMsg4).toString();
            	            String msgBody=nick+" "+helpMsg1+nick+" "+helpMsg2;
            	            GtalkBot.pendingMsgMap.put(gmailID, message);
            	            gtalkchat.sendMessage(msgBody);
            	            return;
            	        }else{//for log out msg msg*************
            			
            			String msgBody=null;
            			if(message.getBody().trim().equals("has gone offline, you cannot chat any longer with the user.")){
            				GtalkBot.hashTable.remove(gmailID);
            				GtalkBot.profileMap.remove(gmailID);
            				//GtalkBot.profile_gmailIdMap.remove(message.getThread());
            				GtalkBot.profile_gmailIdMap.remove(profileID);
            				msgBody=nick+" "+message.getBody();
            			}else{
            				msgBody="*"+nick+":* "+message.getBody();
            			}
            			
            			gtalkchat.sendMessage(msgBody);
            			return;
            	        }
            			
            		}
        		}
			 }
			
		}catch(Exception ex){
			ex.printStackTrace();
		}
		
	}

}
