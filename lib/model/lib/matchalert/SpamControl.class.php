<?php
/********************************************************************************
 * Library Class to record percentage of sent Emails of various service providers
 *
 * @Description     This class is used to analyse email rates for spam control
 * @author     		Akash Kumar
 ********************************************************************************/
class SpamControl
{
	 /**
	 * Function to UPDATE/INSERT number of email sent to different domains in spam control table
	 */
	public function emailRate()
	{ 
		  	$emailObject=new matchalerts_MAILER();      				           // Object for checking number of email of various services(like gmail,yahoo etc)
			$sentOutput=$emailObject->checkSentEmail();	
					
			unset($emailObject);
			    if(isset($sentOutput))
				{
					$dateObj=new EmailViewCount();              
					$dated=$dateObj->getDateFromLogicalDate($sentOutput['sent']);   // Exact date from No.of days after 01 jan 2006
					
					$emailObject=new matchalerts_SPAM_CONTROL();        	       // Update SPAM_CONTROL table with the data from above
					$updateOutput=$emailObject->updateSentEmail($sentOutput);
					 
						
						if(isset($updateOutput) && $updateOutput==0)              // IF UPDATE fails
						{	
							$insertOutput=$emailObject->insertSentEmail($sentOutput);
							
						}
					unset($emailObject);
				}
			
		
		return 1;                                                       // Successfull execution
	}
	/**
	* function to generate Alert after analysing drop in email open rate
	*/
	public function alert()
	{
			
			$emailObject=new matchalerts_SPAM_CONTROL();                 // Object for checking number of email of various services(like gmail,yahoo etc)
			$sentOutput=$emailObject->analyseEmailOpenrate();				
			unset($emailObject);
			    
			return $sentOutput;
						
		                                                   
	}
}
?>
