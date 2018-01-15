<?php
/*
 *	Author:Sanyam Chopra
 *	This task will fetch profileId's and EntryDate of profiles with incomplete status not equal to "Y" and Mstatus= " ", in the last 24Hrs
 *  from the given time and update the Incomplete status to "Y" and also save the profileId along with EntryDate and current time in a test file
 */

class UpdateIncorrectIncompleteStatusTask extends sfBaseTask{
	/**
   * 
   * Configuration details for cron:cronUpdateIncorrectIncompleteStatus	
   * 
   * @access protected
   * @param none
   */
  protected function configure()
  {
    $this->namespace           = 'cron';
    $this->name                = 'cronUpdateIncorrectIncompleteStatus';
    $this->briefDescription    = 'Checks for incorrect incomplete status of a profile and accordingly updates it';
    $this->detailedDescription = <<<EOF
     The [cronUpdateIncorrectIncompleteStatus|INFO] fetches data from JPROFILE of profile's whose incomplete status is wrong and updates the incomplete status and also saves the profileId along with Entry Date in a log file:
     [php symfony cron:cronUpdateIncorrectIncompleteStatus] 
EOF;
  }
  protected function execute($arguments = array(), $options = array())
  {
  	$today = date("Y-m-d H:i:s"); 
  	$backDays = "1";
  	$requiredDate = date("Y-m-d H:i:s",JSstrToTime('now -'.$backDays.' days'));
  	$jprofileObj = new JPROFILE("newjs_slave");
  	$incompleteProfileArray = $jprofileObj->getArray(array("MSTATUS"=>"''"),array("INCOMPLETE"=>"'Y'"),array("ENTRY_DT"=>$requiredDate),"PROFILEID,ENTRY_DT");
  	if($incompleteProfileArray != "")
  	{
  	$jprofileUpdateObj = new JPROFILE("newjs_master");
  	foreach($incompleteProfileArray as $k=>$v){
  		$resultArr[$v["PROFILEID"]]=$v["ENTRY_DT"];
  		file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/correctedIncompleteProfiles.txt","ProfileId:".$v['PROFILEID']." , EntryDate:".$v['ENTRY_DT']." , UpdatedOn:".$today."\n",FILE_APPEND);
  	}
  	$profileIdArray=array_keys($resultArr);
  	if($jprofileUpdateObj->updateIncompleteProfileStatus($profileIdArray))
  		echo("Profile information successfully updated \n");
  	else
  		echo("Error in updating profile info. Please try again");
  }
  else{
  	echo("No incorrect profile's in last 24 Hrs. Try changing the no.of days and try aggregate_info(object)");
  }
  }
}
