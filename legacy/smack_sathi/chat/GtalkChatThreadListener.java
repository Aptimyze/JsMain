package chat;

import org.jivesoftware.smack.Chat;
import org.jivesoftware.smack.ChatManager;
import org.jivesoftware.smack.MessageListener;
import org.jivesoftware.smack.XMPPConnection;
import org.jivesoftware.smack.packet.Message;

public class GtalkChatThreadListener implements MessageListener{
	
	 public String helpMsg1=GtalkBot.help_msg1;
	 public String helpMsg2=GtalkBot.help_msg2;
	 public String conflict_msg1=GtalkBot.conflict_msg1;
	 public String conflict_msg2=GtalkBot.conflict_msg2;
	 public String conflict_msg3=GtalkBot.conflict_msg3;
	 private XMPPConnection xmppConnection;
	 public GtalkChatThreadListener(XMPPConnection conn) {
         xmppConnection = conn;
         //chatManager=chatmanager;
     } 
	 
	 public void processMessage(Chat chat, Message message) {
		// TODO Auto-generated method stub
		//System.out.println("msg in the gtalkChatThread is >>>>"+message.getBody());
		
		try{
			if(message.getBody() != null ) {
				if(message.getFrom().contains("/")){
        			String from=message.getFrom();
        			message.setFrom(from.substring(0, from.indexOf("/")));
        		}
        		//message.get
        		String msgFrom=message.getFrom();
        		String msg=message.getBody().trim();

    			boolean isUserExit=GtalkBot.hashTable.containsKey(msgFrom);
    			boolean isCommand=GtalkBot.commandList.contains(msg);
    			
    			Chat gtalkchat=(Chat)GtalkBot.gtalkChatThreadMap.get(msgFrom);
    			
    			if(msg.equals("@hide") ||  msg.equals("@show")){
        			StatusUpdater stsUpdater=new StatusUpdater();
        			if(msg.equals("@hide")){
        				//System.out.println("setting ON_OFF IN STATUSUPDATER");
        				stsUpdater.setON_OFF(0,msgFrom);
        				gtalkchat.sendMessage("You will now not appear in search results for online members");
        				//return;
        			}else{
        				//System.out.println("setting ON_OFF IN STATUSUPDATER");
        				stsUpdater.setON_OFF(1,msgFrom);
        				gtalkchat.sendMessage("You will now appear in search results for online members");
        			}
       				
       			    return;
       			}
    			
    			
    			
    			if(isUserExit == true && !msg.equals("@end") && !msg.equals("@yes") && !msg.equals("@no")){
    				
    				//System.out.println("@@@@@@@@@@@@@@@@@");
    				String to=(String)GtalkBot.hashTable.get(message.getFrom());
    				//System.out.println("to is >>>>"+to);
    				String thread=(String)GtalkBot.profileMap.get(message.getFrom());
    				Message newMessage= new Message();
    				newMessage.setThread(thread);
        			newMessage.setTo(to);
        			newMessage.setType(Message.Type.chat);
        			newMessage.setBody(msg);
        			newMessage.setSubject(thread+"@gmail.com");
        			/*
        			Chat jsChatTalk=(Chat)GtalkBot.jsChatThreadMap.get(to);
        			jsChatTalk.sendMessage(newMessage);
        			*/
        			//jsChatTalk.
        			xmppConnection.sendPacket(newMessage);
					return;
    			}else if(msg.equals("yes")){
    				Message msgPacket=(Message)GtalkBot.pendingMsgMap.get(msgFrom);
    				if(msgPacket != null){
    					
    					
    					
    					String gmailId=(String)GtalkBot.profile_gmailIdMap.get(msgPacket.getSubject().substring(0,msgPacket.getSubject().indexOf("@")) );
    					GtalkBot.hashTable.put(gmailId, msgPacket.getFrom());
    					GtalkBot.profileMap.put(gmailId, msgPacket.getSubject().substring(0,msgPacket.getSubject().indexOf("@")) );
    					
    					//System.out.println("coming herein the GtalkChat Thread MAp");
    					Message message1=new Message();
    					message1.setTo( msgPacket.getFrom());
    					message1.setType(Message.Type.headline);
    					message1.setBody("accept");
    					//message1.setThread(msgPacket.getThread());
    					message1.setThread(msgPacket.getSubject().substring(0,msgPacket.getSubject().indexOf("@")) );
    					String subject_nick=msgPacket.getSubject();
    					message1.setSubject(subject_nick.substring(0, subject_nick.indexOf("/")));
    					
    					//Chat jsChatTalk=(Chat)GtalkBot.jsChatThreadMap.get(msgPacket.getFrom());
            			//jsChatTalk.sendMessage(message1);
    					//GtalkBot.connection.sendPacket(message1);
    					
    					xmppConnection.sendPacket(message1);
    					GtalkBot.pendingMsgMap.remove(msgFrom);
    					GtalkBot.chatMap.put(msgFrom, subject_nick.substring(subject_nick.indexOf("/")+1,subject_nick.length()) );
    					System.out.println("sending back authenticated acceptance");
    					
    					
    					gtalkchat.sendMessage("You have approved "+subject_nick.substring(subject_nick.indexOf("/")+1,subject_nick.length()) +"'s chat request");
    					
    					return;
    				}else{
    					System.out.println("Message packet is not stored.");
    				}
    				//sending back the gtalk user that u have accepted the user request
    				
    				
    				
    			}else if(msg.equals("no")){
    				Message msgPacket=(Message)GtalkBot.pendingMsgMap.get(msgFrom);
    				Message message1=new Message();
    				message1.setTo(msgPacket.getFrom());
    				message1.setType(Message.Type.headline);
    				message1.setBody("decline");
    				String subject_nick=msgPacket.getSubject();
					message1.setSubject(subject_nick.substring(0, subject_nick.indexOf("/")));
					message1.setThread(subject_nick.substring(0, subject_nick.indexOf("@")));
					
					/*
					Chat jsChatTalk=(Chat)GtalkBot.jsChatThreadMap.get(msgPacket.getFrom());
        			jsChatTalk.sendMessage(message1);
					*/
    				xmppConnection.sendPacket(message1);
    				GtalkBot.pendingMsgMap.remove(msgFrom);//msgFrom
    				
    				gtalkchat.sendMessage("You have declined "+subject_nick.substring(subject_nick.indexOf("/")+1,subject_nick.length()) +"'s chat request");
    				//this=null;
    				//this.finalize();
    				
    				Chat ur_chat=(Chat)GtalkBot.jsChatThreadMap.get(msgPacket.getFrom());
    				ur_chat=null;
    				GtalkBot.jsChatThreadMap.remove(msgPacket.getFrom());
    				
    				
    				//Chat ur_chat=(Chat)GtalkBot.jsChatThreadMap.get(msgFrom);
    				gtalkchat.removeMessageListener(this);
    				gtalkchat=null;
    				
    				GtalkBot.gtalkChatThreadMap.remove(msgFrom);
    				
					return;
    			}else if(msg.equals("@end")){
    				
    				String to=(String)GtalkBot.hashTable.get(msgFrom);
    				String profileID1=(String)GtalkBot.profileMap.get(msgFrom);
    				Message msgPacket=(Message)GtalkBot.pendingMsgMap.get(msgFrom);
    				
    				String toWhom=(String)GtalkBot.chatMap.get(msgFrom);
					if(toWhom != null){
						
						gtalkchat.sendMessage("You have ended your chat with "+toWhom);
						
						/////////////////upto this is done////////////////////
        				GtalkBot.hashTable.remove(msgFrom);
        				GtalkBot.profileMap.remove(msgFrom);
        				GtalkBot.profile_gmailIdMap.remove(profileID1);
        				Message message1=new Message();
    					message1.setTo(to);
    					
    					
    					//System.out.println("Message.Type.headline is >>>"+Message.Type.headline);
    					message1.setType(Message.Type.headline);
    					message1.setBody("ending");
    					message1.setThread(profileID1);
    					message1.setSubject(profileID1+"@gmail.com");
    					
    					//Chat jsChatTalk=(Chat)GtalkBot.jsChatThreadMap.get(to);
            			//jsChatTalk.sendMessage(message1);
    					
    					xmppConnection.sendPacket(message1);
        				if(msgPacket != null){
        					GtalkBot.pendingMsgMap.remove(msgFrom);
        				}
        				
        				Chat ur_chat=(Chat)GtalkBot.jsChatThreadMap.get(to);
        				ur_chat=null;
        				GtalkBot.jsChatThreadMap.remove(to);
        				
        				//Chat ur_chat=(Chat)GtalkBot.jsChatThreadMap.get(msgFrom);
        				GtalkBot.gtalkChatThreadMap.remove(msgFrom);
        				gtalkchat.removeMessageListener(this);
        				gtalkchat=null;
        				
        				
					}else{
						String subject123=msgPacket.getSubject();
						
    					String nick123=subject123.substring(subject123.indexOf("/")+1, subject123.length());
    					
    					 String msgBody=nick123+" "+helpMsg1+nick123+" "+helpMsg2;
    					 
    					 gtalkchat.sendMessage(msgBody);
    					//message3.setBody(msgBody);
    					//xmppConnection.sendPacket(message3);
					}
					return;
    			}else if(msg.equals("@yes")){
    				String to=(String)GtalkBot.hashTable.get(msgFrom);
    				String thread=(String)GtalkBot.profileMap.get(msgFrom);
    				Message message1=new Message();
    				message1.setThread(thread);
    				message1.setType(Message.Type.headline);
    				message1.setSubject(thread+"@gmail.com");
    				message1.setTo(to);
    				message1.setBody("ending");
    				
    				//Chat jsChatTalk=(Chat)GtalkBot.jsChatThreadMap.get(to);
        			//jsChatTalk.sendMessage(message1);
    				
    				xmppConnection.sendPacket(message1);
    				
    				
    				Chat ur_chat=(Chat)GtalkBot.jsChatThreadMap.get(to);
    				ur_chat=null;
    				GtalkBot.jsChatThreadMap.remove(to);
    				
    				
    				
    				//xmppConnection.sendPacket(message1);
    				GtalkBot.hashTable.remove(msgFrom);
    				GtalkBot.profileMap.remove(msgFrom);
    				//System.out.println("message has been sent to old user to whom it was chatting");
    				Message msgPacket=(Message)GtalkBot.pendingMsgMap.get(msgFrom);
    				
    				
    				
    				if(msgPacket != null){
    					String gmailId=(String)GtalkBot.profile_gmailIdMap.get(msgPacket.getThread());
    					GtalkBot.hashTable.put(gmailId, msgPacket.getFrom());
    					GtalkBot.profileMap.put(gmailId, msgPacket.getThread());
    					
    					
    					Thread jsChatThread = new Thread(new JsChatThread(msgPacket.getFrom(),xmppConnection));
        				jsChatThread.start();
        				
    					Message message2=new Message();
    					message2.setTo( msgPacket.getFrom());
    					message2.setType(Message.Type.headline);
    					message2.setBody("accept");
    					//message2.setSubject(msgPacket.getSubject());
    					String subject_nick=msgPacket.getSubject();
    					message2.setSubject(subject_nick.substring(0, subject_nick.indexOf("/")));
    					message2.setThread(msgPacket.getThread());
        				xmppConnection.sendPacket(message2);
    					
    					GtalkBot.pendingMsgMap.remove(msgFrom);
    					gtalkchat.sendMessage("You have approved "+subject_nick.substring(subject_nick.indexOf("/")+1,subject_nick.length()) +" chat request");
    					
    					return;
    				}else{
    					System.out.println("Message packet is not stored.");
    				}
    				
    			}else if(msg.equals("@no")){
    				
    				Message msgPacket=(Message)GtalkBot.pendingMsgMap.get(msgFrom);
    				if(msgPacket != null){
    					String gmailId=(String)GtalkBot.profile_gmailIdMap.get(msgPacket.getThread());
    					Message message2=new Message();
    					message2.setTo( msgPacket.getFrom());
    					message2.setType(Message.Type.headline);
    					message2.setBody("decline");
    					//message2.setSubject(msgPacket.getSubject());
    					String subject_nick=msgPacket.getSubject();
    					message2.setSubject(subject_nick.substring(0, subject_nick.indexOf("/")));
    					message2.setThread(subject_nick.substring(0,subject_nick.indexOf("@")));
    					xmppConnection.sendPacket(message2);
    					GtalkBot.pendingMsgMap.remove(msgFrom);
    					gtalkchat.sendMessage("You have declined "+subject_nick.substring(subject_nick.indexOf("/")+1,subject_nick.length()) +" chat request");
    					return;
    				}
    			}else{
    				String tag_line=GtalkBot.tag_line;
    				/*Message newMessage= new Message();
    				newMessage.setTo(msgFrom);
    				newMessage.setSubject(null);
    				newMessage.setType(Message.Type.chat);
    				//newMessage.setBody("plz enter command as for help msg then you can start chat");
    				newMessage.setBody(tag_line);
        			xmppConnection.sendPacket(newMessage);
        			*/
        			gtalkchat.sendMessage(tag_line);
        			return;
    			}
    			
    			
				
			}
		}catch(Exception ex){
			ex.printStackTrace();
		}
		
	 }

}
