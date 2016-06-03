package performance;

import chat.GmailSubscriptionThread;

public class performanceStator {

	/**
	 * @param args
	 */
	public static void main(String[] args) {
		// TODO Auto-generated method stub
		
		for(int i=5000; i<6000;i++){
			String name="manu"+i;
			String pass="manu"+i;
			int count=i;
			Thread GmailAdderThread = new Thread(new UserThread(name,pass,count));
			GmailAdderThread.start();
			try {
				Thread.sleep(2000);
			} catch (InterruptedException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		}
		
		 while (true) {
	            try {
	               Thread.sleep(1000);
	            } catch (InterruptedException e) {
	               e.printStackTrace();
	            }
	         }

	}

}
