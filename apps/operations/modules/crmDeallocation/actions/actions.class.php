<?php

/**
 * crmDeallocation actions.
 *
 * @package    jeevansathi
 * @subpackage crmDeallocation
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class crmDeallocationActions extends sfActions
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
  public function executeNoLongerWorking(sfWebRequest $request)
  {
	$this->cid=$request->getParameter("cid");
        $agentAllocDetailsObj   =new AgentAllocationDetails();
        $execName =$agentAllocDetailsObj->fetchAgentName($this->cid);
	$this->names=$request->getParameter("name");
	if($request->getParameter("Submit")=='Release')
	{
		$gone_user=$request->getParameter("gone_user");
		$processObj=new PROCESS();
		$processObj->setProcessName("DeAllocation");
		$agentBucketObj=new AgentBucketHandler();
		$processObj->setUsername("$gone_user");
		$processObj->setMethod("MANUAL");
		$processObj->setSubMethod("NO_LONGER_WORKING");
		$processObj->setExecutive($execName);
		$msg=$agentBucketObj->deallocate($processObj);
		$this->msg=$msg;
	}
	$this->setTemplate('NoLongerWorking');
  }
  public function executeReleaseProfile(sfWebRequest $request)
  {
	$this->cid=$request->getParameter("cid");
        $agentAllocDetailsObj   =new AgentAllocationDetails();
        $this->name =$agentAllocDetailsObj->fetchAgentName($this->cid);
	if($request->getParameter("Submit")=='Release')
	{
		$privilage=$agentAllocDetailsObj->getprivilage($this->cid);
		$priv = explode("+",$privilage);
		$release_user=$request->getParameter('release_user');
		if($release_user)
		{
        		$crmProfileObj=new JPROFILE();
        		$detail=$crmProfileObj->get($release_user,"USERNAME","PROFILEID");
			$pid=$detail['PROFILEID'];
			if($pid)
			{
				$mainAdminObj=new incentive_MAIN_ADMIN();
				$detail=$mainAdminObj->get($pid,"PROFILEID","ALLOTED_TO");
				$username=$detail['ALLOTED_TO'];
			}
			if((in_array("SLHD",$priv)||in_array("P",$priv)||in_array("MG",$priv)) && $username)
				$release=True;
			elseif((in_array("TRNG",$priv)||in_array("SLMNTR",$priv)||in_array("SLSUP",$priv)||in_array("IUI",$priv)||in_array("IUO",$priv) || in_array("RSP",$priv))&&$username==$this->name)
				$release=True;
		}
		if($pid&&$release)
		{	
			$processObj=new PROCESS();
			$processObj->setProcessName("DeAllocation");
        		$agentBucketObj=new AgentBucketHandler();
        		$processObj->setMethod("MANUAL");
        		$processObj->setSubMethod("RELEASE_PROFILE");
			$profiles[]=$pid;
        		$processObj->setProfiles($profiles);
			$processObj->setUsername($username);
			$processObj->setExecutive($this->name);
        		$msg=$agentBucketObj->deallocate($processObj);
			$this->msg=$msg;
		}
		else
			$this->msg="Wrong Username";
	}
	$this->setTemplate('ReleaseProfile');
  }
  public function executeReleaseProfileForMyTeam(sfWebRequest $request)
  {
    $this->cid = $request->getParameter("cid");
    $agentAllocDetailsObj = new AgentAllocationDetails();
    $this->name = $agentAllocDetailsObj->fetchAgentName($this->cid);
	if($request->getParameter("Submit") == 'Release')
	{
		$privilage = $agentAllocDetailsObj->getprivilage($this->cid);
		$priv = explode("+",$privilage);
		$release_user = $request->getParameter('release_user');
		if($release_user)
		{
            $crmProfileObj = new JPROFILE();
            $detail = $crmProfileObj->get($release_user,"USERNAME","PROFILEID");
			$pid = $detail['PROFILEID'];
			if($pid)
			{
				$mainAdminObj = new incentive_MAIN_ADMIN();
				$detail = $mainAdminObj->get($pid,"PROFILEID","ALLOTED_TO");
				$username = $detail['ALLOTED_TO'];
                
                if($username)
                {
                    $agents = $agentAllocDetailsObj->fetchAgentsByHierarchy($this->name);
                    if(in_array($username, $agents) && (in_array("SLSUP",$priv) || in_array("FPSUP",$priv) || in_array("INBSUP",$priv) || in_array("SUPPRM",$priv) || in_array("UpSSup",$priv) || in_array("RnwSup",$priv) || in_array("SupFld",$priv))){
                        $release = True;
                    }
                    else{
                        $msg = "Username deletion outside your privilege.";
                    }
                }
			}
		}
		if($pid && $release)
		{	
			$processObj = new PROCESS();
			$processObj->setProcessName("DeAllocation");
            $agentBucketObj = new AgentBucketHandler();
            $processObj->setMethod("MANUAL");
            $processObj->setSubMethod("RELEASE_PROFILE");
			$profiles[] = $pid;
            $processObj->setProfiles($profiles);
			$processObj->setUsername($username);
			$processObj->setExecutive($this->name);
            $msg = $agentBucketObj->deallocate($processObj);
			$this->msg = $msg;
		}
		else if($pid && $username)
            $this->msg = $msg;
        else
			$this->msg="Wrong Username";
	}
	$this->setTemplate('ReleaseProfileForMyTeam');
  }
  public function executeLogout(sfWebRequest $request)
  {
	$this->cid=$request->getParameter("cid");	
	$agentDeAllocObj=new AgentDeAllocation();
	$lout=$agentDeAllocObj->logout($this->cid);
	setcookie("OPERATOR",'',0,"/",$domain);
	if($lout)
	{
		$msg="You have successfully logged out";
		$this->MSG=$msg;
	}	
	$this->setTemplate('jsadminMsg');	
  }	
}
