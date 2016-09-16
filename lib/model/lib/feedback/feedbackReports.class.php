<?php
class feedbackReports

{

public static function getReportInvalidLog($startDate,$endDate){

      $reportInvalidOb = new JSADMIN_REPORT_INVALID_PHONE();
      $reportArray = $reportInvalidOb->getReportInvalidLog($startDate,$endDate);
      foreach ($reportArray as $key => $value) 
      {
         $profileArray[]=$value['SUBMITTEE'];
         $profileArray[]=$value['SUBMITTER'];

      }
      $j=0;
      $profileArrayForUniqueSubmitees = array();
//print_r($reportArray);die;
      for($i=0;$i<sizeof($reportArray);$i++) {
        if(!isset($profileArrayForUniqueSubmitees[$reportArray[$i]['SUBMITTEE']]))
         $profileArrayForUniqueSubmitees[$reportArray[$i]['SUBMITTEE']] = $reportArray[$i];
      }

     
      $countArray = array();

      foreach ($profileArrayForUniqueSubmitees as $key => $value) {
        # code...
                $timeOfMarking = ($value['SUBMIT_DATE']);
         $date = new DateTime($timeOfMarking);
         $date->sub(new DateInterval('P90D')); //get the date which was 90 days ago
         $lastDateToCheck = $date->format('Y-m-d H:i:s');
         $profileId = $key;
         $countLast90Days= (new JSADMIN_REPORT_INVALID_PHONE('newjs_slave'))->getReportInvalidCount($profileId,$timeOfMarking,$lastDateToCheck);
         $countArray[$profileId] = $countLast90Days;

      }
     
 

    if(is_array($profileArray))
    { 

     $profileDetails=(new JPROFILE('newjs_slave'))->getProfileSelectedDetails($profileArray,"PROFILEID,EMAIL,USERNAME");
      foreach ($reportArray as $key => $value) 
      { 
      $tempArray['submitee_id']=$profileDetails[$value['SUBMITTEE']]['USERNAME'];
      $tempArray['count']=$countArray[$value['SUBMITTEE']];
      $tempArray['submiter_id']=$profileDetails[$value['SUBMITTER']]['USERNAME'];
      $tempArray['comments']=$value['COMMENTS'];
      $tempArray['timestamp']=$value['SUBMIT_DATE'];
      $tempArray['submiter_email']=$profileDetails[$value['SUBMITTER']]['EMAIL'];
      $tempArray['submitee_email']=$profileDetails[$value['SUBMITTEE']]['EMAIL'];
      $resultArr[]=$tempArray;      
      unset($tempArray);
      # code...
      }
    }


return $resultArr;

}

	
}

?>
