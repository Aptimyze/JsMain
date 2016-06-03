<?php

/*
 * Author: Esha Jain
 * This task send system generated reminder on first photo upload to all the profile who hav received eoi from the photo uploader profile
*/

class eoiReminderOnFirstPhotoUploadTask extends sfBaseTask
{
  protected function configure()
  {

    $this->namespace        = 'cron';
    $this->name             = 'eoiReminderOnFirstPhotoUpload';
    $this->briefDescription = 'send system generated reminder on first photo upload to all the profile who hav received eoi from the photo uploader profile';
    $this->detailedDescription = <<<EOF
The [eoiReminderOnFirstPhotoUpload|INFO] task send system generated reminder on first photo upload to all the profile who hav received eoi from the photo uploader profile
Call it with:

  [php symfony cron:eoiReminderOnFirstPhotoUpload] 
EOF;
$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));
  }

  protected function execute($arguments = array(), $options = array())
  {
  sfContext::createInstance($this->configuration);
  $fieldsArray="*";
  $yesterday=mktime(date("H"),date("i"),date("s"),date("m"),date("d")-1,date("Y"));
  $date=date("Y-m-d",$yesterday);
  $type = "R";

  $photoFirstObj = new PHOTO_FIRST;
  $profilesUploadedFirstPhoto = $photoFirstObj->profilesUploadedPhotoOnDate($date);
  //print_R($p);
  foreach($profilesUploadedFirstPhoto as $key=>$profileid)
  {
        $senderProfile = new Profile('',$profileid);
        $senderProfile->getDetail('', '', $fieldsArray);
	//echo "sender:";echo $senderProfile->getPROFILEID();
        if($senderProfile->getHAVEPHOTO()=='Y' && $senderProfile->getACTIVATED()=='Y')
        {
                $dbName = JsDbSharding::getShardNo($senderProfile->getPROFILEID());
                $contactsObj = new newjs_CONTACTS($dbName);
                $receivers = $contactsObj->getEoiReceiversFromProfile($senderProfile->getPROFILEID());
        }
	//echo "receivers"; print_r($receivers);
	if($receivers)
        {
		$customMessage = PresetMessage::getSystemPreset($senderProfile);
                foreach($receivers as $k=>$receiverProfileid)
                {
                        $receiverProfile = new Profile('',$receiverProfileid);
                        $receiverProfile->getDetail('', '', $fieldsArray);
			if($receiverProfile->getACTIVATED()=='Y')
			{
				$contact = new Contacts($senderProfile, $receiverProfile);
				$contactHandlerObj = new ContactHandler($senderProfile,$receiverProfile,"EOI",$contact,$type,"POST");
				$contactHandlerObj->setElement("STATUS",$type);
				$contactHandlerObj->setElement("MESSAGE",$customMessage);
				$event=ContactFactory::event($contactHandlerObj);
			}
                }
        }
  }
}
}
?>
