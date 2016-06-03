package chat;
import java.sql.PreparedStatement;
import java.sql.SQLException;

import org.jivesoftware.smack.Chat;
import org.jivesoftware.smack.ChatManager;
import org.jivesoftware.smack.PacketListener;
import org.jivesoftware.smack.XMPPConnection;
import org.jivesoftware.smack.XMPPException;
import org.jivesoftware.smack.packet.Message;
import org.jivesoftware.smack.packet.Packet;


 public  class MessageParrot_multiThread implements PacketListener {
        private XMPPConnection xmppConnection;
        public  Chat chat ;
        public ChatManager chatManager;
        //public String helpMsg= " want to talk to you./n Please enter @yes to  stop present chat and start new chat</br> @no to continue present chat.</br> yes to start chat.</br> no to deny chat.</br> @end to end chat.</br> @hide to not be shown in the search result.</br>@show to get chat message.";
       /* public String helpMsg1=" wants to chat with you.Visit "+GtalkBot.domain_name+"/profile/viewprofile.php?username=";
        public String helpMsg2=" to view their profile."; 
        public String helpMsg3="To approve chat request, send ‘yes’.";
        public String helpMsg4="To decline chat request, send ‘no’.";
        */
        public String helpMsg1=GtalkBot.help_msg1;
        public String helpMsg2=GtalkBot.help_msg2;
        public String conflict_msg1=GtalkBot.conflict_msg1;
        public String conflict_msg2=GtalkBot.conflict_msg2;
        public String conflict_msg3=GtalkBot.conflict_msg3;
        //public String helpMsg5="To decline chat request, send ‘no’.";

        public PreparedStatement pst =null;
        public MessageParrot_multiThread(XMPPConnection conn,ChatManager chatmanager) {
            xmppConnection = conn;
            chatManager=chatmanager;
        } 
        
        public void processPacket(Packet packet) {
        	
            Message message = (Message)packet;
            	
            
            try{
	                if(message.getBody() != null ) {
	                 	System.out.println("msg from is>>>"+message.getFrom()+">>>and its content is>>>"+message.getBody()+">>>subject is>>>"+message.getSubject());
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
	                 		if(gmailID != null){//this block of code for nsg coming from js user
	                 			String chattingWith=(String)GtalkBot.hashTable.get(gmailID);
	                 			System.out.println(subject +" chatting with "+chattingWith);
	     	            		if(chattingWith == null){
	     	            			Thread GtalkChatThread=new Thread(new GtalkChatThread(gmailID,xmppConnection,msgFrom,nick));
	     	            			GtalkChatThread.start();
	     	            			GtalkBot.pendingMsgMap.put(gmailID, message);
	     	            			
			            			return;
	     	            		}else if(message.getBody().equals("ask_chatAuth")){
	     	            			Message message1=new Message();
	    	            			message1.setBody(message.getBody());
	    	            			message1.setTo(message.getTo());
	    	            			//message1.setSubject(gmailID);
	    	            			message1.setSubject(message.getSubject());
	    	            			message1.setFrom(message.getFrom());
	    	            			//message1.setThread(message.getThread());///check>>>>>>>>>>>>>>>
	    	            			message1.setThread(profileID);
	    	            			GtalkBot.pendingMsgMap.put(gmailID, message1);
	    	            			//String nick=msgFrom.substring(0,msgFrom.indexOf("@"));
	    	            			//String msgBody=nick+" wants to chat with you. Visit http://jeevansathi.com/"+nick+" to view their profile. To end the current chat and start chatting with "+nick+", send ‘@yes’. /n  To chat with multiple users at one point, log into http://jeevansathi.com and use our new chat!";
	    	            			//System.out.println("conflict_msg3 is >>>>"+conflict_msg3);
	    	            			String msgBody=nick+" "+conflict_msg1+nick+" "+conflict_msg2+" "+nick+" "+conflict_msg3;
	    	            			Message message2=new Message();
	    	            			//String msgBody=msgFrom+GtalkBot.helpMsg;
	    	            			message2.setType(Message.Type.chat);
	    	            			message2.setTo(gmailID);
	    	            			message2.setSubject(null);
	    	            			message2.setBody(msgBody);
	    	            			xmppConnection.sendPacket(message2);
	    	            			return;
	     	            		}else{
	     	            			
	     	            			//System.out.println("its corresponding listener in the MessageParrot  is >>>>"+((Chat)GtalkBot.jsChatThreadMap.get(msgFrom)).getListeners());
	     	            			return;
	     	            		}
	                 		}else{
	                 			//Then this message is coming from gtalk user
	                 			
	                 			
	                 			
	                 			Chat gtalk_inCoversation=(Chat)GtalkBot.gtalkChatThreadMap.get(msgFrom);
	                 			if(gtalk_inCoversation == null){
		                 			String tag_line=GtalkBot.tag_line;
		            				Message newMessage= new Message();
		            				newMessage.setTo(msgFrom);
		            				newMessage.setSubject(null);
		            				newMessage.setType(Message.Type.chat);
		            				//newMessage.setBody("plz enter command as for help msg then you can start chat");
		            				newMessage.setBody(tag_line);
			            			xmppConnection.sendPacket(newMessage);
			            			
	                 			}
		            			
	                 			return;
	                 		}
	                }
                 }catch(Exception e){
                 	e.printStackTrace();
                 }
            
            
            
            
         
        }
    }