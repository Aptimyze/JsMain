<?php

/**
 * ShowProfileStatsAction actions.
 *
 * @package    jeevansathi
 * @subpackage commoninterface
 * @author     Manoj	 
 */
class ShowProfileStatsAction extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function execute($request)
  {
    $sectionFlag = "all";
    $request=sfContext::getInstance()->getRequest();
    $this->profileid =$request->getParameter('profileid');
    $this->cid = $request->getParameter('cid');
    $agentName =$name =$request->getParameter('name');	
    $this->curlReq =$request->getParameter('curlReq');
    	
    if(!$this->curlReq){
      $key ='SHOW_STAT_PAGE_'.$name;
      $name ='jstech';	
      $time =5;
      if(JsMemcache::getInstance()->get($key)){
        JsMemcache::getInstance()->set($key, $name, $time);
        exit("Please refresh after 5 seconds.");
      }else
        JsMemcache::getInstance()->set($key, $name, $time);
    }

    $this->loginProfile=OPERATOR::getInstance();
    $this->loginProfile->getDetail($this->profileid,"","*");
    
    $this->photoDisplay = $this->loginProfile->getPHOTO_DISPLAY();
    $apiProfileSectionObj=  ApiProfileSections::getApiProfileSectionObj($this->loginProfile,'','1');
    $editDetailsObj = new EditDetails();
    
    $jpartnerObj=$editDetailsObj->getJpartnerObj($this);
    $this->loginProfile->setJpartner($jpartnerObj);
    
    $this->profilePicUrl = $editDetailsObj->getProfilePicUrl($this);
    
    $this->otherDetailsArr =$editDetailsObj->getOtherDetails($this,$this->cid);
  
    $this->profCompScoreArr =$editDetailsObj->getProfCompScoreDetails($this); 
      
    $myProfileArr = array();
    $ResponseOut = $editDetailsObj->getEditDetailsValues($this,$apiProfileSectionObj,$sectionFlag,$myProfileArr,"1");
    unset($editDetailsObj);
    $this->profileDetailArr = $this->getAlteredArrData($myProfileArr);

	$agentAllocDetailsObj   =new AgentAllocationDetails();
	$crmUtilityObj          =new crmUtility();
        $privilege              =$agentAllocDetailsObj->getprivilage($this->cid);
	$this->linkArr          =$crmUtilityObj->fetchPrivilegeLinks($privilege);

        // Profile Stats
        $showCrmStatsObj                =new ShowProfileStats($this->loginProfile);
        $this->detailedProfileStatsData =$showCrmStatsObj->getDetailedProfileStats();

        $this->mainProfileStatsData     =$showCrmStatsObj->geMainProfileStats($this->profileDetailArr);

        $this->detailedProfileStatsData["ALBUM_COUNT"] = $this->profilePicUrl["album_count"];

	// Bottom Link
	$this->checksum=md5($this->profileid)."i".$this->profileid;	
	$this->username =$this->loginProfile->getUSERNAME();
	$allotedAgent =$this->mainProfileStatsData['ALLOTED_AGENT'];
	if($allotedAgent==$agentName)
		$this->isAlloted =1;
  }

  //This function alters the $myProfileArr to a desired form
  public function getAlteredArrData($myProfileArr)
  {
    $myProfileAlteredArr = array();
    foreach($myProfileArr as $key=>$val)
    {
      foreach($val as $key_1=>$val_1)
      {
        if($key !='profileCompletion' && $key!='Album')
        {
          $myProfileAlteredArr[$key][$val_1['key']] = $val_1;
        }
        else
        {
          $myProfileAlteredArr[$key][$key_1]=$val_1;
        }
      }
    }
    return  $myProfileAlteredArr;
  }

}
