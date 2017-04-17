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



  public function executeReportInvalidContactsQC(sfWebRequest $request)
  {
      $today=date("Y-m-d");
      list($todYear,$todMonth,$todDay)=explode("-",$today);
      $k=-5;
      while($k<=0)
      {
        $yearArray[]=$todYear+$k;
        $k++;
      }
      $monthArray=array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'May','06'=>'Jun','07'=>'Jul','08'=>'Aug','09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
      $this->typearr=$typearr;
      $this->yearArray = $yearArray;
      $this->monthArray = $monthArray;
      $this->todYear = $todYear;
      $this->todMonth = $todMonth;
      $this->setTemplate('reportInvalidContactsQC');



}

  public function executeReportInvalidContactsQCLog(sfWebRequest $request)
  {

      $reportInvalidOb = new JSADMIN_REPORT_INVALID_PHONE('newjs_slave');
      $month = $request->getParameter('month');
      $year = $request->getParameter('year');
      $date = new DateTime();
      $date->setDate ( $year , $month , 1 );
      $endDate = new DateTime();
      $endDate->setDate ( $year , ($month+1) , 1 );
      $endDate->modify('-1 day');

      $opsUserArray = (new jsadmin_OPS_PHONE_VERIFIED_LOG('newjs_slave'))->getOPSUserProcessedCount($date->format('Y-m-d'),$endDate->format('Y-m-d'));
      foreach ($opsUserArray as $key => $value) {
        # code...
        $tempDate =date("d",strtotime($value['DT']));
        $OPSArray[$value['OPS_USERID']][$tempDate][$value['PHONE_STATUS']] = $value['CNT']; 
        $totalArray[intval($tempDate)][$value['PHONE_STATUS']]+= $value['CNT'];
      }
      $i=0;

      foreach ($OPSArray as $key => $value) {
        # code...
        $newOPSArray[$i]['user'] = $key;
        foreach ($value as $key2 => $value2) {
              $newOPSArray[$i][intval($key2)] =  array('B'=>$value2['B'],'N'=>$value2['N']); 
          # code...
        }
        $i++;
      }
      $date->modify('-1 day');
      $reportArray = $reportInvalidOb->getTotalReportInvalidCount($date->format('Y-m-d'),$endDate->format('Y-m-d'));
      foreach ($reportArray as $key => $value) {
        $tempDate = strtotime("+1 day", strtotime($value['DT']));
        $newReportArray[intval(date("d", $tempDate))] = $value['CNT'];
        # code...
      }
      $resultArr['INVALID_REPORT']=$newReportArray;
      $resultArr['OPS']=$newOPSArray;
      $resultArr['TOTALARRAY']=$totalArray;

      ob_end_clean();
      if(sizeof($resultArr) == 0 )
          die;
      echo json_encode($resultArr);
      return sfView::NONE;
      die;

  }

  public function executeReportInvalidLog(sfWebRequest $request)
  {
     $reportInvalidOb = new JSADMIN_REPORT_INVALID_PHONE();
      $reportArray = $reportInvalidOb->getReportInvalidLog($startDate,$endDate);

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

public function executeDeleteRequestForUser(sfWebRequest $request)
{ 
  $this->setTemplate('deleteRequestForUser');
}

  public function executeSendDeleteRequestForUser(sfWebRequest $request)
  {       
          $this->linkToGoBackToDeleteRequest = JsConstants::$siteUrl."/operations.php/feedback/deleteRequestForUser";
          $dataArray = $request->getParameter('feed');
          $userName = $dataArray['username'];

          $profileObj = NEWJS_JPROFILE::getInstance();
          $userPFID = $profileObj->getProfileIdFromUsername($userName);
          if(!$userPFID || $userPFID == NULL)
          {
            $error[message] = "username is not correct"; 
            echo json_encode($error);
            exit;
          }

          $sendingObject = new RequestUserToDelete();

          if($dataArray['requestBySelf'] == '1')
          {  
            $sendingObject->deleteRequestedBySelf($userPFID);
            $requestedBy = 'Self';
          }
          else
          { 
          $sendingObject->deleteRequestedByOther($userPFID);
            $requestedBy = 'Other';  
          }
          $crmUserName = $this->user;

          $loggingObj = new MIS_REQUEST_DELETIONS_LOG();
          $loggingObj->logThis($crmUserName,$userPFID,$requestedBy);

          $response[responseStatusCode] = '0';
          $response[message] = "Successfully sent"; 
            echo json_encode($response);
            exit;

  }

}
?>
