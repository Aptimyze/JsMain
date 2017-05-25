<?php
/********************************************************************************
 * Library Class to Count email view after for email open rate
 *
 * @Description     This class counts & stores open email i.e tracks the email
 * @author     		Akash Kumar
 ********************************************************************************/
class EmailViewCount
{
	private $validEmail=array('gmail'=>'GMAIL','yahoo'=>'YAHOO','rediff'=>'REDIFF','hotmail'=>'HOTMAIL','other'=>'OTHERS'); //Valid Email ID's in case on addition of enw email id just an entry is required
		
	 /**
	 * Function to update data of open email in TOP_VIEW_COUNT table for open email tracking
	 * @param $openEmail Array of profileid,logic used,sent date,frequency 
	 * @return 1 for success & 0 for no parameter found
	 */
	public function increment($openEmail,$dated)  //openemail is array DATA of open email
	{ 
		if(is_array($openEmail) && $dated)
		{
			
			$emailOpen=new matchalerts_TOP_VIEW_COUNT();                 // Object for updating open email in TOP_VIEW_COUNT table
			$res=$emailOpen->updateOpenEmail($openEmail);
			if(!in_array($openEmail['email'], $this->validEmail)) // Check for whether email is in traking list or other
			{
				$openEmail['email']='OTHERS';
			}
			$spamControl=new matchalerts_SPAM_CONTROL();                 // Object for updating open email in TOP_VIEW_COUNT table
			$result=$spamControl->updateSpamControl($openEmail,$dated);					
			if($result==0)
			{
				$result=$spamControl->insertSpamControl($openEmail,$dated);
			}
			unset($emailOpen);
			unset($spamControl);
			
			return $res;
		}
		else
		return 0; //0 for no parameter is given and so, no row updated
			
	}

	/*This function is used for open tracking of new matches email
	*@param openEmail : Array of open email tracking parameters
	*@return 1 or 0	
	*/
	public function openNewMatchesEmailTracking($openEmail)
	{
		if(is_array($openEmail))
		{
			$newMatchesTopViewCountObj =new new_matches_emails_TOP_VIEW_COUNT();                
                        $newMatchesTopViewCountObj->update($openEmail);
			$date = $this->getDateFromLogicalDate($openEmail["sent"]);
			$matchAlertTrackingObj=	new MATCHALERT_TRACKING_NEW_MATCHES_EMAILS_TRACKING();
			$matchAlertTrackingObj->updateNewMatchesTracking(array("MAIL_OPEN"=>1),$date);
			return 1;
		}
		return 0;	
	}
	 /**
	 * Function to get the no. of days since 1 Jan 2006.
	 */
	public function getLogicalDate()
	{ 
		$today=mktime(0,0,0,date("m"),date("d"),date("Y")); //timestamp for today
        $zero=mktime(0,0,0,01,01,2006); //timestamp for 1 Jan 2006
        $gap=floor(($today-$zero)/(24*60*60)); //$gap is the no. of days since 1 Jan 2006.
		return $gap;	
		                                                   
	}
	 /**
	 * Function to get date from logical date.
	 */
	public function getDateFromLogicalDate($emaildate)
	{ 
		$DateRef = '2006-01-01';              // Reference Date
		return date('Y-m-d', strtotime($DateRef . "+".$emaildate." days"));	
		                                                   
	}
	 /**
	 * Function to get the email domain from Email ID.
	 */
	public function getEmailDomain($emailId)
	{ 
		$email= explode('.',explode('@', $emailId)[1])[0];  // get email Domain from email ID
		if(array_key_exists($email, $this->validEmail))     // if email domain to be tracked separatly or under Other
		{
			$email=$this->validEmail[$email];
		}
		else
		{
			$email="OTHERS";
		}
		return $email;
		
	}
}
?>
