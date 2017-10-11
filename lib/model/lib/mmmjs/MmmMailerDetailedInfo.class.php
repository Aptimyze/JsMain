<?php
/** 
* Class to handle mailer information.
*/
class MmmMailerDetailedInfo
{
	/* 
	* This function will record mailer information .....
	* @param keyValueArr key value pair of data to be added.
	*/
	public function addMailerInfo($keyValueArr)
	{
		$newMail= new mmmjs_MAIL_DATA_NEW;
		$newMail->insertEntry($keyValueArr);
	}


        /** 
	* Retrieve mailer info.
        * @param mailerId id of mailer for which details need to be fetched.
	* @param parameters to be retreived.
        * @return array containing mailer info of the specified id.
        **/
        public function getMailerInfo($mailerId,$parameters="*")
        {
                $mmmjs_MAIL_DATA_NEW = new mmmjs_MAIL_DATA_NEW;
                $whereParamArray["MAILER_ID"] = $mailerId;
                $mailersDetilsArr = $mmmjs_MAIL_DATA_NEW->get($whereParamArray,$parameters);
		if($mailersDetilsArr)
	               return $mailersDetilsArr[0];
		return NULL;
        }

	/**
	* This function update the status of dump as 'Y' which indicates dumo has been created.
	*/
	public function updateDumpStatus($mailerId)
	{
		$mmmjs_MAIL_DATA_NEW = new mmmjs_MAIL_DATA_NEW;
		$wherefields['MAILER_ID'] = $mailerId;
		$setfields['DUMP'] = 'Y';
		$mmmjs_MAIL_DATA_NEW->update($wherefields, $setfields);
	}

	/**
	* This function retrive if dump of the mailer is created or not.
	* @param mailerId id of mailer 
	* @return 'Y' if dump was created for the mailer.
	*/
	public function getDumpStatus($mailerId)
	{
		$mmmjs_MAIL_DATA_NEW = new mmmjs_MAIL_DATA_NEW;
		$arr = $this->getMailerInfo($mailerId,'DUMP');
		if($arr)
		{
			$dumpStatus = $arr['DUMP'];
			return $dumpStatus;
		}
		return NULL;
	}
}
?>
