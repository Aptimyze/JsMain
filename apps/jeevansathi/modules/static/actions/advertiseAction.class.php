<?php


class advertiseAction extends sfActions
{
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	
	private $publickey="6LeWlLsSAAAAAHZDUB6EouaKvfP5Qa8qGrXuhL8k";
	private $privatekey="6LeWlLsSAAAAAG__dOabvQFfWcBkKlL7PxzEeArS";
	
	public function execute($request)
	{ 		

		$type=$request->getParameter("type");
		$this->bms_1 = 28;
		$this->bms_2 = 28;
		
		
		
		
		$this->rightPanelStory = IndividualStories::showSuccessPoolStory();
		

	  	$arradver = $request->getParameter('advertis');
	  	$this->form = new advertiseForm($arradver);
	  	$this->THNX='0';
	  	$this->CAPFLAG='0';
	  	$this->show_Recapache();
	  	if ($request->isMethod('post'))
	    {
	    	$this->form->bind($arradver);	     
	        if($this->check_recaptha() != 'incorrect-captcha-sol')  
	        { 	
		      	if($this->form->isValid())
		      	{
	           		$valueadver = new NEWJS_ADVERTISE;
	           		$valueadver-> insertAdvertiseData($arradver);
	           		$this->FwdMail($arradver);
	           		$this->THNX='1';
	          	}

          	}  
          	else
	          	{
	          		$this->CAPFLAG='1';
	          		
	          	}        	          
	    }
	   
	}	
	private function check_recaptha()
	{
		
		include_once(sfConfig::get("sf_web_dir")."/classes/recaptchalib.php");
	 	$resp = recaptcha_check_answer ($this->privatekey,
					FetchClientIP(),
					$_POST["recaptcha_challenge_field"],
					$_POST["recaptcha_response_field"]);

	 	if ($resp->is_valid) { 	return 1; }
		return $resp->error;
	}
	private function show_Recapache()
	{
		include_once(sfConfig::get("sf_web_dir")."/classes/recaptchalib.php");
		$this->RECAP=recaptcha_get_html($this->publickey,$error);	

	}
	private function FwdMail($arradver)
	{
		$msg="Organisation: ".$arradver['organisation']."<br/>Name: ".$arradver['name']."<br/>Business: ".$arradver['business']."<br/>Address :".$arradver['address']."<br/>Phone: ".$arradver['phone']."<br/>Email: ".$arradver['email']."<br/>Details: ".$arradver['details'];
		$sender = $arradver['email'];
		$to = "suresh@naukri.com,rakesh.varma@naukri.com";
		$subject=$arradver['organisation']." wants to advertise with us";
		SendMail::send_email($to,$msg,$subject,$sender);

	}
}
?>
