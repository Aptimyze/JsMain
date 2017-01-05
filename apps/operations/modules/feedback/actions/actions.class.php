<?php


/**
 * feedback actions.
 *
 * @package    jeevansathi
 * @subpackage storyScreening
 * @author     Palash Chordia
 */
class feedbackActions extends sfActions
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
  * Executes index action to screen(accept,hold,reject,skip) success story.
  * @param sfRequest $request A request object
  */
 
  public function executeReportAbuse(sfWebRequest $request)
  {
  	$this->setTemplate('reportAbuse');

}

 public function executeReportInvalid(sfWebRequest $request)
  {
    $this->setTemplate('reportInvalid');

}

	


	public function executeReportAbuseLog(sfWebRequest $request)
	{

	   	$startDate=$request->getParameter('RAStartDate');
	   	$endDate=$request->getParameter('RAEndDate');
	   	$reportAbuseOb = new REPORT_ABUSE_LOG('newjs_slave');
		  $reportArray = $reportAbuseOb->getReportAbuseLog($startDate,$endDate);
		  foreach ($reportArray as $key => $value) 
      {
			   $profileArray[]=$value['REPORTEE'];
			   $profileArray[]=$value['REPORTER'];
			# code...
		  }

    if(is_array($profileArray))
    {
        $profileDetails=(new JPROFILE('newjs_slave'))->getProfileSelectedDetails($profileArray,"PROFILEID,EMAIL,USERNAME");
        $countArray= (new REPORT_ABUSE_LOG('newjs_slave'))->getReportAbuseCount($profileArray);
        foreach ($reportArray as $key => $value) 
        {
			$tempArray['reportee_id']=$profileDetails[$value['REPORTEE']]['USERNAME'];
			$tempArray['count']=$countArray[$value['REPORTEE']];
     		$tempArray['reporter_id']=$profileDetails[$value['REPORTER']]['USERNAME'];;
      		$tempArray['reason']=$value['REASON'];
      		$tempArray['timestamp']=$value['DATE'];
			$tempArray['comments']=$value['OTHER_REASON'];
			$tempArray['reporter_email']=$profileDetails[$value['REPORTER']]['EMAIL'];
			$tempArray['reportee_email']=$profileDetails[$value['REPORTEE']]['EMAIL'];
			$resultArr[]=$tempArray;
			unset($tempArray);
			# code...
		  }
      ob_end_clean();
      if(sizeof($resultArr) == 0 )
          die;
      echo json_encode($resultArr);
                        return sfView::NONE;
                        die;
                
	}
}
  public function executeReportInvalidLog(sfWebRequest $request)
  {
      $startDate=$request->getParameter('RAStartDate');
      $endDate=$request->getParameter('RAEndDate');
      $resultArr=(new feedbackReports())->getReportInvalidLog($startDate,$endDate);
      ob_end_clean();
      if(sizeof($resultArr) == 0 )
          die;
      echo json_encode($resultArr);
      return sfView::NONE;
      die;

  }


public function executeReportAbuseForUser(sfWebRequest $request)
{
  $this->linkToInterface = JsConstants::$siteUrl."/operations.php/feedback/reportAbuseForUser"; 
  $this->crmUser = $this->user;
  $this->setTemplate('reportAbuseForUser');
}

}
?>
