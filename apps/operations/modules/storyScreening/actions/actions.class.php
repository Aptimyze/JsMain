<?php

/**
 * storyScreening actions.
 *
 * @package    jeevansathi
 * @subpackage storyScreening
 * @author     Rohit Khandelwal
 */
class storyScreeningActions extends sfActions
{
  /**
   * Automatically calls before the action to execute.
   *
   */
  public function preExecute()
  {
	  $request=sfContext::getInstance()->getRequest();     
      $this->cid=$request->getParameter("cid");
      $this->user=JsOpsCommon::getcidname($this->cid);
      $this->paramsArr = $request->getParameterHolder()->getAll();
  }
  
  /**
   * 
   * Action to be excecuted to view skipped success stories.
   * @param sfWebRequest $request
   */
  public function executeSkip(sfWebRequest $request)
  {
	$viewSkipStoriesObj = new ViewSkipStories($this->paramsArr);
	$this->values = $viewSkipStoriesObj->performAction();
	$this->VSKIP = 1;	
  }
 /**
  * Executes index action to screen(accept,hold,reject,skip) success story.
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
	$SuccessStoryDbObj = new NEWJS_SUCCESS_STORIES();
	
		if($request->getParameter('screenskip'))
		{
				$where['ID'] = $request->getParameter('id');
				$result = $SuccessStoryDbObj->fetchStoryDetail($where);
				$detailArr = $result[0];
				$this->showformskip = 1;
				if($detailArr)
				{                    
					$storyid=$detailArr['ID'];
					$USERNAME_H=$detailArr['USERNAME_H'];
					$USERNAME_W=$detailArr['USERNAME_W'];
					$EMAIL=$detailArr['EMAIL'];
					$SEND_EMAIL=$detailArr['SEND_EMAIL'];
					$dateandtime=$detailArr["DATETIME"];
					$result = $SuccessStoryDbObj->fetchProfileWith($USERNAME_H,$USERNAME_W,$EMAIL,$SEND_EMAIL,$storyid);
					if($result)
					{	
						$SuccessStoryDbObj->updateUploaded('X',$storyid);
						$this->redirect("/operations.php/storyScreening/view?cid=$this->cid&user=$this->user&FROM=SK");
					}
				}
		}
		else
		{	
			$result = $SuccessStoryDbObj->fetchProfile();
			$detailArr = $result[0];
			$this->row_total = $SuccessStoryDbObj->fetchUnscreenedStoryCount();
			
				$duplicate='';
				if($detailArr)
				{
					$storyid=$detailArr['ID'];
					$USERNAME_H=$detailArr['USERNAME_H'];
					$USERNAME_W=$detailArr['USERNAME_W'];
					$EMAIL=$detailArr['EMAIL'];
					$SEND_EMAIL=$detailArr['SEND_EMAIL'];
					$dateandtime=$detailArr['DATETIME'];

					$result = $SuccessStoryDbObj->fetchProfileWith($USERNAME_H,$USERNAME_W,$EMAIL,$SEND_EMAIL,$storyid);
					$result = $result[0];
					if($result)
					{
						if(($result['PIC_URL']!="" || $detailArr['PIC_URL']=="")|| ($result['PIC_URL']=="" && $detailArr['PIC_URL']==""))
						{
							$duplicate='X';
						}
						if($duplicate=="X")
							$id=$detailArr['ID'];
						else
							$id=$result["ID"];
                        $IndividualStoryDbObj = new newjs_INDIVIDUAL_STORIES();
                        
						$SuccessStoryDbObj->updateUploaded('X',$id);
						$IndividualStoryDbObj->updateStatusbyStoryId('X',$id);
						if($duplicate=="X")
							$this->forward('storyScreening','index');
					 }
				 }				 
			}
			$this->SCREEN = 1;
			list($year,$month,$day)=explode("-",$detailArr['WEDDING_DATE']);
			$this->year = $year;
			$this->wedding_date = $detailArr["WEDDING_DATE"];
			$this->dateandtime = $dateandtime; 
			$this->contact = $detailArr["CONTACT_DETAILS"];
			if(!$this->paramsArr['comments'])
				$this->comments = $detailArr["COMMENTS"];
			else
			$this->comments = $this->paramsArr['comments'];
			if($detailArr["PIC_URL"])
			{
			   $this->photo=$detailArr["PIC_URL"];
			}
			if($year==""||$year=="0000")
				$year=2007;
            $this->YEAR = $year;
			$this->username = $detailArr["USERNAME"];
			$this->name_h =$detailArr["NAME_H"];
			$this->name_w = $detailArr["NAME_W"];
			$this->user_h = $USERNAME_H;
			$this->user_w = $USERNAME_W;
			if($SEND_EMAIL=="")
				$this->email = $EMAIL;
			else
				$this->email = $SEND_EMAIL;
			$this->id = $detailArr["ID"];
		$this->NOPIC = $request->getParameter("nopic");
		$this->NOSTORY = $request->getParameter("nostory");
		$this->delete = $request->getParameter("delete");
  }
  	
  /**
	* 
	* Executes Unhold action to search and edit success story.
	* @param sfWebRequest $request
	*/
  public function executeUnhold(sfWebRequest $request)
  {
	$editStoriesObj = new EditStories($request);
	$successStoryObj = new SuccessStories('', $request->getParameter('id'));
    	
    if($request->getParameter('accept'))
    {
    	$editStoriesObj->acceptStories($this);
    	if(!$this->noExecute)
    	{
    		$this->redirect("/operations.php/storyScreening/view?cid=$this->cid&user=$this->user&FROM=AS");
    	}
    }
    elseif($request->getParameter('remove'))
    {
    		
		$successStoryObj->setUPLOADED('R');
		$successStoryObj->UpdateRecord();
		
		$individualStoryObj = new IndividualStories('', $request->getParameter('sid'));
		$individualStoryObj->setSTATUS('R');
		$individualStoryObj->UpdateRecord();
    }
    elseif($request->getParameter('reject'))
    {
		$successStoryObj->setUPLOADED('D');
		$successStoryObj->UpdateRecord();
    }
    elseif($request->getParameter('hold'))
	{
		$this->mail_name_h=$request->getParameter('mail_name_h');
		$this->mail_name_w=$request->getParameter('mail_name_w');
		$this->email=$request->getParameter('email');
		$this->id=$request->getParameter('id');
		$this->UNHOLDMAIL = 1;
	}
	elseif($request->getParameter('send'))
    {		
		$mail=trim($request->getParameter('mail'));
		$mail=nl2br($mail);
		SendMail::send_email($request->getParameter('email'),$mail,"Success Story Held","Promotions@jeevansathi.com");
		$successStoryObj->setUPLOADED('H');
		$successStoryObj->UpdateRecord();
	}
	if($this->paramsArr['unsearch'])
    {
    	$editStoriesObj->searchStories($this);    		
    }
	$this->rand=rand();    
    $this->UNHOLD=1;
 }
 /**
  * 
  * action to add offline success story.
  * @param sfWebRequest $request
  */
 public function executeOffline(sfWebRequest $request)
 {
	$offlineStoriesObj = new  OfflineStories($request);
    
    if($request->getParameter('Upload'))
    {
    	$offlineStoriesObj->uploadStory($this);
    	if(!$this->noExecute)
    	{
    		$this->redirect("/operations.php/storyScreening/view?cid=$this->cid&user=$this->user&FROM=OS");
    	}
    }
    		
    $this->OFFLINE = 1;
		
 }

 /**
  * 
  * action to search and remove success story.
  * @param sfWebRequest $request
  */
 public function executeRemove(sfWebRequest $request)
 {
	if($request->getParameter('doremove'))
   	{
   		$successStoryObj = new SuccessStories('', $request->getParameter('id'));
		$successStoryObj->setUPLOADED('R');
		$successStoryObj->UpdateRecord();
			
		$individualStoryObj = new IndividualStories('', $request->getParameter('sid'));
		$individualStoryObj->setSTATUS('R');
		$individualStoryObj->UpdateRecord();
   		$this->MSG = $request->getParameter('msg');
   		$this->redirect("/operations.php/storyScreening/view?cid=$this->cid&user=$this->user&FROM=RS");
   	}
   	elseif($request->getParameter('cancelremove'))
	{
		$this->redirect("/operations.php/storyScreening/remove?cid=$this->cid&user=$this->user");
	}
	elseif($request->getParameter('search'))
	{
		$removeStoriesObj = new RemoveStories($this->paramsArr);
		$removeStoriesObj->searchStories($this);
	}
	$this->REMOVE = 1;
 }
 
 /**
  * 
  * action to accept uploaded story.
  * @param sfWebRequest $request
  */
 public function executeAccept(sfWebRequest $request)
 {
 	$acceptStoryObj = new AcceptStories($request);
	$acceptStoryObj->acceptStory($this);
	$this->redirect("/operations.php/storyScreening/index?cid=".$this->cid."&user=".$this->user."&nopic=".$this->NOPIC."&nostory=".$this->NOSTORY."&delete=".$this->delete);
 }

 /**
  * 
  * action to reject a story.
  * @param sfWebRequest $request
  */
 public function executeReject(sfWebRequest $request)
 {
	$successStoryObj = new SuccessStories('', $this->paramsArr['id']);
	$successStoryObj->setUPLOADED('D');
	$successStoryObj->UpdateRecord();
	$this->redirect("/operations.php/storyScreening/index?cid=$this->cid&user=$this->user");
 }
	
 /**
  * 
  * action to put a story on hold.
  * @param sfWebRequest $request
  */
 public function executeHold(sfWebRequest $request)
 {
	$this->MAIL = 1;
	$this->name_h = $this->paramsArr['name_h'];
	$this->name_w = $this->paramsArr['name_w'];
	$this->id = $this->paramsArr['id'];
	$this->email=$this->paramsArr['email'];
	if($this->paramsArr['skip'])
		$this->skip = $this->paramsArr['skip'];
	$this->FROM = "HS";
  }
  /**
   * 
   * action to skip a story.
   * @param sfWebRequest $request
   */
  public function executeSkipStory(sfWebRequest $request)
  {
	$this->FROM='SS';
	$this->id=$this->paramsArr['id'];
	$this->c = 1;
	$this->skip = 1;		
  }
 /**
  * 
  * action to show confirmation message template after performing any action.
  * @param sfWebRequest $request
  */
 public function executeView(sfWebRequest $request)
 {
	$successStoryObj = new SuccessStories('', $request->getParameter('id'));
	//if the action is called from skip story.
	if($request->getParameter("FROM")=="SS")
	{
		$this->c = $request->getParameter('c');
		$id=$request->getParameter('id');
		$comments = $request->getParameter('comments');
		$successStoryObj->setSKIP_COMMENTS($comments);
		$successStoryObj->setUPLOADED('S');
		$successStoryObj->UpdateRecord();
	}
	//if the action is called from hold story.
	elseif($request->getParameter("FROM")=="HS")
	{
	
		$mail=trim($this->paramsArr['mail']);
		$mail=nl2br($mail);
		SendMail::send_email($this->paramsArr['email'],$mail,"Success Story Held","Promotions@jeevansathi.com");
		
		$successStoryObj->setUPLOADED('H');
		$successStoryObj->UpdateRecord();
	}
	$this->fromPage = $request->getParameter("FROM");
 }
	
	
}
?>
