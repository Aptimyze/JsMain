<?php
/************************************************************************************************************************
 *    FILENAME           : cronLogScreenQueueCountTask.class.php 
 *    DESCRIPTION        : logs screening queue counts every hour
 *    CREATED BY         : Esha Jain
 ***********************************************************************************************************************/
class cronLogScreenQueueCountTask extends sfBaseTask
{
    protected function configure()
    {
        $this->namespace           = 'cron';
        $this->name                = 'cronLogScreenQueueCount';
$this->addOptions(array(
new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
));
        $this->briefDescription    = 'Sends All Initial-contact Request To Reciever Through One Mail Only.When This Script is run
 All Recievers will recieve appropriate mail';
        $this->detailedDescription = <<<EOF
      The [cronSendEoiEmail|INFO] tSends All Initial-contact Request To Reciever Through One Mail Only.When This Script is run
 All Recievers will recieve appropriate mail:

      [php symfony cron:cronLogScreenQueueCount] 
EOF;
    }
    protected function execute($arguments = array(), $options = array())
    {
        if(!sfContext::hasInstance())
                sfContext::createInstance($this->configuration);
         //open rate tracking by nitesh as per vibhor        
          $jprofileObj   = new JPROFILE("newjs_slave");
	  $final['PROFILE_NEW'] = $jprofileObj->getNewScreenProfileCount();
	  $final['PROFILE_EDIT'] = $jprofileObj->getEditScreenProfileCount();
	$acceptCounts= 	$jprofileObj->getPhotoScreenAcceptQueueCount();
	$final['PHOTO_ACCEPT_REJ_NEW']=($acceptCounts['NEW_PHOTO_ACCEPT'])?$acceptCounts['NEW_PHOTO_ACCEPT']:0;
	$final['PHOTO_ACCEPT_REJ_EDIT']=($acceptCounts['EDIT_PHOTO_ACCEPT'])?$acceptCounts['EDIT_PHOTO_ACCEPT']:0;
	$processCounts = $jprofileObj->getPhotoScreenProcessQueueCount();
	$final['PHOTO_PROCESS_NEW']= ($processCounts['NEW_PHOTO_PROCESS'])?$processCounts['NEW_PHOTO_PROCESS']:0;
	$final['PHOTO_PROCESS_EDIT']= ($processCounts['EDIT_PHOTO_PROCESS'])?$processCounts['EDIT_PHOTO_PROCESS']:0;
	$m = new MIS_SCREENING_QUEUE_COUNTS;
	$hr = date("H");
	$date = date("Y-m-d");
	$m->insertRecord($final,$hr,$date);
    }
}
