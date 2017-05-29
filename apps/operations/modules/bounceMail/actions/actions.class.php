<?php

/**
 * bounceMail actions.
 *
 * @package    jeevansathi
 * @subpackage bounceMail
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class bounceMailActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('default', 'module');
  }

  /**
  * Executes Bounce Mail Detection action and calls the JPROFILE table in newjs to fetch Email from username or profileid entered
  * and then calls bounces_BOUNCED_MAILS to check if an entry exists in the table and accordingly echo's the result
  *
  * @param sfRequest $request A request object
  */
  public function executeBounceMailDetection(sfWebRequest $request)
  {
  	$this->cid = $request->getParameter("cid");
	$agentAllocDetailsObj   =new AgentAllocationDetails();
    $this->name =$agentAllocDetailsObj->fetchAgentName($this->cid);
  	$this->username = $request->getParameter("userData");
  	if($request->getParameter('isSubmit'))
  	{
  		if(strpos($this->username,'@')==false)
  		{
  			if(ctype_digit($this->username))
  			{
  				$this->email=$this->EmailFromProfileId($this->username);
  			}
  			else
  			{
  				$this->email=$this->EmailFromUsername($this->username);
  			}
  			
  			if($this->email=='')
  			{
  				echo("error");die; 
  			}
  			
  		}
  		else
  		{
  			$this->email=$this->username;
  		}
  		
  		$bounceObj = new bounces_BOUNCED_MAILS
  		();
  		$result = $bounceObj->checkEntry($this->email);
  		if($result =='0')
  		{
  			echo($result);die;
  		}
  		else
  		{
  			echo($this->email);die;
  		}
  		
  	}
  	elseif($request->getParameter('isDelete'))
  	{	
  		$this->deleteEmail=$request->getParameter("email");
  		$deleteBounceObj = new bounces_BOUNCED_MAILS();
  		$deleteBounceObj->deleteEntry($this->deleteEmail);
  		$this->dateTime=date("Y-m-d H:i:s");
  		$deleteBounceMailObj = new bounces_DELETED_BOUNCE_MAILS();
  		$deleteBounceMailObj->insertDeletedEntry($this->name,$this->dateTime,$this->deleteEmail);
  		echo("Deleted");die;
  	}
  	
  }

	//This function is used to fetch Email from given username 
  	public function EmailFromUsername($username)
  	{	
  		$jprofObj = new JPROFILE();
  		$this->reqdEmail = $jprofObj->getEmailFromUsername($username);  		
  		return $this->reqdEmail["EMAIL"];

  	}
  	//This function is used to fetch Email from given profileid 
  	public function EmailFromProfileId($profileId)
  	{	
  		$jprofileObj = new JPROFILE();
  		$this->reqdEmail = $jprofileObj->getEmailFromprofileId($profileId);  
  		return $this->reqdEmail["EMAIL"];
  	}

  
}
