<?php
/**
 * profile actions.
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class viewAction extends sfAction
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
	public $loginData;
	public $loginProfile;
	public $profile;
	
	public function preExecute()
	{
	$this->start_tm=microtime(true);
	
	/*$this->mysqlObj=new Mysql;
	$this->jpartnerObj=new Jpartner;
	$this->jpartnerObj_logged=new Jpartner;	*/
	}
	public function execute($request)
	{
		
		global $smarty;
		
		//Contains login credentials
		$this->loginData=$request->getAttribute("loginData");
		//chnage: Static function
		(new ProfileCommon($this->loginData));
		
		//Return profile object
		if($this->loginData["PROFILEID"])
		{
			$this->loginProfile=LoggedInProfile::getInstance();
      if($this->loginProfile->getAGE()== "")
        $this->loginProfile->getDetail($this->loginData["PROFILEID"],"PROFILEID");
		}		
		else
			$this->loginProfile=null;
		
		$this->profile=$this->returnProfile();
		
		//common_assign($this->loginData);
		
		//Checks is autologin is required or not.,, globally lib
		$this->autologin();
		
		//Show timeout page on conditions, ///From_mail grep
        $this->condition_timedout();

        //If page is for edit action
	    if($request->getParameter("ownview"))
                if($this->loginProfile)
                        $this->profile=$this->loginProfile;
				else
					ProfileCommon::showTimeOut($this);
	
		//Redirect if edit page..
             if($this->loginProfile) 
		if($this->loginProfile && $this->profile){
			if($this->profile->getPROFILEID()==$this->loginProfile->getPROFILEID() && !strlen($request->getParameter('preview')))
			{
				$this->forward("profile","edit");
			}
		}
		//Forward to different module if PRINT PAGE..
		//if($request->getParameter("PRINT"))
			//$this->forward("profile","print");		
	
		$call_me=$request->getParameter("CALL_ME");
		$strloc=strpos($call_me,"layer_photocheck.php");
		
		if($request->getParameter("subject")=="viewphotos" || $strloc !==false)
		{
			$request->setParameter("CALL_ME","");
			$request->setParameter("ID_CHECKED","");
			if($strloc !==false)
				$request->setParameter("after_login_call","");
			$this->forward("profile","album");
		}		
		
		//Redirect to view page.
		$this->forward("profile","detailed");
		
		return sfView::NONE;
		//ProfileCommon::old_smarty_assign($smarty,$this);	
	}
	private function autologin()
	{
		$request=$this->getRequest();
		
		$data=$this->loginData;
		
		$CAME_FROM_CONTACT_MAIL=$request->getParameter("CAME_FROM_CONTACT_MAIL");
		$clicksource=$request->getParameter(clicksource);
		$enable_auto_loggedin=$request->getParameter("enable_auto_loggedin");
		$checksum=$request->getParameter("checksum");
		
		$protect_obj=$request->getAttribute("protect_obj");
		
		if($data[PROFILEID]){
			 return ;
		 }
		
		if($CAME_FROM_CONTACT_MAIL || $clicksource=='matchalert1' || $enable_auto_loggedin)
		{
            $echecksum=$request->getParameter("echecksum");
            $epid=$protect_obj->js_decrypt($echecksum,"Y");
			if($checksum==$epid)
			{
				$epid_arr=explode("i",$epid);
				$profileid=$epid_arr[1];
				$profileid=$this->NoAllowedAutoLogin($profileid);	
				if($profileid)
				{
					$ViewerObj=LoggedInProfile::getInstance("newjs_master",$profileid);
					$ViewerObj->getDetail($profileid,"PROFILEID","PROFILEID,USERNAME,PASSWORD");
					$username=$_POST['username']=$ViewerObj->getUSERNAME();
					$password=$_POST['password']=$ViewerObj->getPASSWORD();
					$data =$protect_obj->login($username,$password);
					if($data[PROFILEID])
					{
						$this->redirectuser();
					}
					
				}
			}
		}
	}
	private function NoAllowedAutoLogin($profileid)
	{
		$prof_array=array(8264221,6802915);
		if(in_array($profileid,$prof_array))
		        return '';
		return $profileid;

	}
	private function redirectuser()
	{
		$request_uri=$_SERVER['REQUEST_URI'];
		$request_uri=str_replace("&echecksum=","&esum=",$request_uri);
		$request_uri=str_replace("?echecksum=","?esum=",$request_uri);
		
		//var_dump(sfConfig::get);die;
		$SITE_URL=sfConfig::get("app_site_url");
		$url=$SITE_URL."/".$request_uri;
		$this->redirect($url);
		
	}
	private function returnProfile()
	{
		$request=sfContext::getInstance()->getRequest();
		$protect_obj=$request->getAttribute("protect_obj");
		$uPID=$request->getParameter("uPID");
		$username=$request->getParameter('username');
		
		//If coming through canonical url.
		if(!$username)
		{
			$canurl=$request->getParameter("canurl");
			if($canurl)
			{
				$arr=explode("-",$canurl);
				$username=str_replace("_____","-",$arr[count($arr)-1]);
        
        if(!$request->getParameter("stype")){
          $request->setParameter("stype",'Z');
        }
        
			}
			
		}	
		if($request->getParameter('profilechecksum'))
		{
			$profileid=JsCommon::getProfileFromChecksum($request->getParameter('profilechecksum'));
		}
		elseif($uPID)
		{
			if(!$request->getParameter("stype"))
				$request->setParameter("stype",10);
			$userId=substr($uPID,0,strlen($uPID)-2);
			$userName=substr($uPID,strlen($uPID)-2,1);
			$rotator=substr($uPID,strlen($uPID)-1,1);
			
			for($tempcnt=0;$tempcnt<strlen($userId);$tempcnt++)
			{
				$newpos=$tempcnt-$rotator;
				if($newpos<0)
					$newpos=$newpos+strlen($userId);
				else
					$newpos=$newpos;
				$userIdOrg[$newpos]=$userId{$tempcnt};
			}

			ksort($userIdOrg);

			if(count($userIdOrg)>1)
				$userProId=implode("",$userIdOrg);
			else
				$userProId=$userIdOrg[0];
			$profileid=$userProId;
		}
		elseif($username)
		{
			$username_temp=$protect_obj->get_correct_username($username);
			if($username_temp)
				$username=$username_temp;
				
			//Change this later
			$profile = Profile::getInstance("newjs_masterRep");
			$profile->getDetail($username,'USERNAME',"","RAW");
			//$profileid=JSCOMMON::getProfileFromUsername($username);	
					
		}
		if($profileid)
		{
			$profile = Profile::getInstance("newjs_masterRep");
			$profile->getDetail($profileid,'PROFILEID',"","RAW");
		}			
				
		return $profile;
	}
	
	
	
	private function condition_timedout()
	{
		$request=$this->getRequest();
		$data=$this->loginData;
		
		if(!$data[PROFILEID] && $request->getParameter("From_Mail"))
		{
			ProfileCommon::showTimeOut($this);			
		}	
		
	}

	
}
