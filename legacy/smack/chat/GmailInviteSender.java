package chat;

public class GmailInviteSender implements Runnable {

	public void run() {
		// TODO Auto-generated method stub
		while(true){
			System.out.println("coming to GmailInviteSender");
			try {
	               Thread.sleep(5*1000);
	            } catch (InterruptedException e) {
	               e.printStackTrace();
	            }
		}
		//System.out.println("coming to GmailInviteSender");
		
		/*while (true) {
            try {
               Thread.sleep(1000);
            } catch (InterruptedException e) {
               e.printStackTrace();
            }
         }*/
	}

}
