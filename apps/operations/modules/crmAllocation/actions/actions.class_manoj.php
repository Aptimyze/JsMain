<?php

/**
 * crmAllocation actions.
 *
 * @package    jeevansathi
 * @subpackage crmAllocation
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class crmAllocationActions extends sfActions
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

  // Get Outbound Process List for Executives
  public function executeOutboundProcessList(sfWebRequest $request)
  {
	$this->cid      =$request->getParameter("cid");
	$this->name     =$request->getParameter("name");

        $processObj             =new PROCESS();
	$agentAllocDetailsObj   =new AgentAllocationDetails();
        $processObj->setExecutive($this->name);
	$processObj->setProcessName('Allocation');
        $processObj->setMethod('OutboundProcessCount');
	//$processObj->setcountSet();

	/* Product Comment Required*/
	
        $branch = getCenterForExec($this->name);
        if($branch== 'NOIDA')
                $this->ncr = 'Y';
	

        $privilege		=$agentAllocDetailsObj->getprivilage($this->cid);
        $priv 			=explode("+",$privilege);
        if((in_array("IUO",$priv) && in_array("PRALL",$priv)) || (in_array("EXCPRM",$priv)))
                $this->show_links='Y';
        elseif(in_array("IUI",$priv) && $branch == "NOIDA")
                $this->show_links='N';

	$profilesArr =$agentAllocDetailsObj->fetchProfiles($processObj);
	$this->followupProfilesForDayCnt 	=$profilesArr['FOLLOWUP']['COUNT'];
	$this->newProfilesForDayCnt	 	=$profilesArr['NEW_PROFILES']['COUNT'];
	$this->subscriptionExpiringProfilesCnt  =$profilesArr['SUB_EXPIRY']['COUNT'];
	$this->renewalProfilesCnt		=$profilesArr['UN_RENEWAL']['COUNT'];
	$this->upsellProfilesCnt		=$profilesArr['UN_UPSELL']['COUNT'];
	$this->profilesRenewalNotDueCnt		=$profilesArr['RENEWAL_NOT_DUE']['COUNT'];
	$this->prevHandledProfilesCnt		=$profilesArr['HANDLED']['COUNT'];

	$this->setTemplate('outboundProcessList');
  }

  // Get New Profiles for the Day
  public function executeOutboundProcess(sfWebRequest $request)
  {
        $this->cid	=$request->getParameter("cid");
        $this->name	=$request->getParameter("name");
	$this->flag	=$request->getParameter("flag");	
	$j		=$request->getParameter("j");

	$pageLength=25;	// page length set
	$totalLinks=10;	// total links to be shown in the page
	if (!$j ){
	        $j =0;
		$cPage =1;
	}
	else
		$cPage = ($j/$pageLength) + 1;	

	$processObj		=new PROCESS();
	$crmUtilityObj		=new crmUtility();
	$processObj->setExecutive($this->name);
	$processObj->setProcessName('Allocation');
	$processObj->setMethod('OutboundProcess');
	$processObj->setIdAllot($j);
	$processObj->setLimit($pageLength);
	
	if($this->flag=='F'){
		$getold		=$request->getParameter("getold");
		$this->yy1    	=$request->getParameter("yy1");
		$this->mm1    	=$request->getParameter("mm1");
		$this->dd1    	=$request->getParameter("dd1");
		$this->yy2    	=$request->getParameter("yy2");
		$this->mm2    	=$request->getParameter("mm2");
		$this->dd2    	=$request->getParameter("dd2");
		if($getold){
			list($st_dt,$end_dt)=explode("--",$getold);
                        $st_dt.=" 00:00:00";
                        $end_dt.=" 23:59:59";
		}else{
			$st_dt=$yy1."-".$mm1."-".$dd1." 00:00:00";
			$end_dt=$yy2."-".$mm2."-".$dd2." 23:59:59";
			$getold=$yy1."-".$mm1."-".$dd1."--".$yy2."-".$mm2."-".$dd2;
		}
		$processObj->setStartDate($st_dt);
		$processObj->setEndDate($end_dt);
		$processObj->setSubMethod('FOLLOWUP');

                for($i=0;$i<31;$i++)
                        $this->ddarr[$i]=$i+1;
                for($i=0;$i<12;$i++)
                        $this->mmarr[$i]=$i+1;
                for($i=0;$i<10;$i++)
                        $this->yyarr[$i]=$i+2004;
	}
	elseif($this->flag=='N')
                $processObj->setNewProfilesMethod('NEW_PROFILES');
	elseif($this->flag=='S')
                $processObj->setSubMethod('SUB_EXPIRY');
	elseif($this->flag=='R')
                $processObj->setSubMethod('UN_RENEWAL');
	elseif($this->flag=='U')
                $processObj->setSubMethod('UN_UPSELL');
	elseif($this->flag=='RND')
                $processObj->setSubMethod('RENEWAL_NOT_DUE');
	elseif($this->flag=='C')
                $processObj->setSubMethod('HANDLED');

	$subMethod 		=$processObj->getSubMethod();
	$profilesArr 		=$agentAllocDetailsObj->fetchProfiles($processObj);
	$totalRec		=$profilesArr["$subMethod"]['COUNT'];
	$this->profiles 	=$profilesArr["$subMethod"]['PROFILES'];

	$SITE_URL		=sfConfig::get("app_site_url");
	$linkUrl		=$SITE_URL."/operations.php/crmAllocation/outboundProcess";
        $this->pageLinkVar 	=$crmUtilityObj->pagelink($pageLength,$totalRec,$cPage,$totalLinks,$this->cid,$linkUrl,'',$this->flag,$getold);
	$this->totalPages 	=ceil($totalRec/$pageLength);
	$this->currentPage	=$cPage;
	$this->setTemplate('outboundProcess');
  }

  // allocate the profile to the agent by Outbound Process
  function executeAgentAllocation(sfWebRequest $request)
  {
        $this->cid      =$request->getParameter("cid");
        $this->name     =$request->getParameter("name");
	$profileid      =$request->getParameter("profileid");

        $agentAllocDetailsObj	=new AgentAllocationDetails();
        $privilege 		=$agentAllocDetailsObj->getprivilage($this->cid);
        $priv 			=explode("+",$privilege);

        if(in_array("SLHD",$priv) || in_array("SLSUP",$priv) || in_array("P",$priv) || in_array("MG",$priv) || in_array("TRNG",$priv))
                $limit =0;
        else{
                $limitCount =getHistoryCount($profileid);
                if($limitCount>=5)
                        $limit =$limitCount;
                else
                        $limit =5;
        }



        $this->setTemplate('agentAllocation');
  }

}
