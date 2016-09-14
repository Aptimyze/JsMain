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
    
      echo json_encode($resultArr);
                        return sfView::NONE;
                        die;
                
	}
}
  public function executeReportInvalidLog(sfWebRequest $request)
  { 
      $startDate=$request->getParameter('RAStartDate');
      $endDate=$request->getParameter('RAEndDate');
      $reportInvalidOb = new JSADMIN_REPORT_INVALID_PHONE();
      $reportArray = $reportInvalidOb->getReportInvalidLog($startDate,$endDate);
  
      foreach ($reportArray as $key => $value) 
      {
         $profileArray[]=$value['SUBMITTEE'];
         $profileArray[]=$value['SUBMITTER'];
      # code...
      }
  

    if(is_array($profileArray))
    { 
      $daysForCount = 90;
      $countArray= (new JSADMIN_REPORT_INVALID_PHONE('newjs_slave'))->getReportInvalidCount($profileArray,$daysForCount);
//die("no");
      $profileDetails=(new JPROFILE())->getProfileSelectedDetails($profileArray,"PROFILEID,EMAIL,USERNAME");

      foreach ($reportArray as $key => $value) 
      { 
      $tempArray['submitee_id']=$profileDetails[$value['SUBMITTEE']]['USERNAME'];
      $tempArray['count']=$countArray[$value['SUBMITTEE']];
      $tempArray['submiter_id']=$profileDetails[$value['SUBMITTER']]['USERNAME'];
      $tempArray['comments']=$value['COMMENTS'];
      $tempArray['timestamp']=$value['SUBMIT_DATE'];
      $tempArray['comments']=$value['OTHER_REASON'];
      $tempArray['submiter_email']=$profileDetails[$value['SUBMITTER']]['EMAIL'];
      $tempArray['submitee_email']=$profileDetails[$value['SUBMITTEE']]['EMAIL'];
      $resultArr[]=$tempArray;      
      unset($tempArray);
      # code...
      }
    }
     // print_r($resultArr);
      //die("correctly done");
        echo json_encode($resultArr);
                        return sfView::NONE;
                        die;
                
  }



}
?>
