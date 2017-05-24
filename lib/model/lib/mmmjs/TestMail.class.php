<?php
/**
* This class will handle test emails handling.
*/
class TestMail
{
        /**
	* Add permanent email-ids based on site .....
	* @param arr (array of email id to be added + site)
	*/
	public function addPermanentTestEmail($arr)
	{
		if(!empty($arr['emailIds']))
		{	
			$temp = str_replace(' ', '', $arr['emailIds']);
			$newArr['emailIds'] = explode(',',$temp);
			
			if($arr['site'])
				$newArr['site'] = $arr['site'];
			else
				throw new jsException("", "site is blank in addPermanentTestEmail() of class TestMail");
			$testMail= new mmmjs_TEST_MAILERS;
			$testMail->insert($newArr);
		}
	}


        /**
        * Delete test mailer email ids .....
        * @param mail_ids array containing list of email ids
        */
	public function deletePermanentTestEmail($mail_ids)
	{
		if(!empty($mail_ids))
		{
			$testMail= new mmmjs_TEST_MAILERS;
			$testMail->delete($mail_ids);
		}
	}


	/**
	* This function will retreive test mailer based on website .....
	* @param site 
	* @return $arr list of test email
	*/
	public function showPermanentTestEmail($site)
	{	
		$testMail= new mmmjs_TEST_MAILERS;
		return $testMail->retrieveBySite($site);
	}


        /*
	* Add temporary email-ids based on site .....
	* @param arr (array of email id to be added + site)
	*/
	public function addTemporaryTestEmail($arr)
	{
                if(!empty($arr['emailIds']))
                {
                        $temp = str_replace(' ', '', $arr['emailIds']);
                        $newArr['emailIds'] = explode(',',$temp);
                        $newArr['mailer_id'] = $arr['mailer_id'];

			$testMail = new mmmjs_TEST_MAILERS_TEMP;
                        $testMail->insert($newArr);
		}
	}


        /**
        * Delete test mailer email ids .....
        * @param mail_ids array containing list of email ids
        */
	public function deleteTemporaryTestEmail($id)
	{
		if(!empty($id))
		{
			$testMail= new mmmjs_TEST_MAILERS_TEMP;
			$testMail->delete($id);
 		}
	}


	/**
	* This function will retreive tempory email id which are specified for particular mailer.
	* @param mailer_id 
	* @return array ? 
	*/
	public function showTemporaryTestEmail($mailer_id)
	{
		if($mailer_id != NULL)
		{	
			$testMail= new mmmjs_TEST_MAILERS_TEMP;
			return $testMail->retrieveByMailer_id($mailer_id);
		}
	}

	/**
	* This function will return all test email if for a particular mail (both the permamant + temporary)
	* @return array ? 
	*/
	public function getAllTestEmails($site, $mailerId, $addDefaultField = 'N')
	{
		$P = $this->showPermanentTestEmail($site);
		$T = $this->showTemporaryTestEmail($mailerId);

                $individual = new Individual_Mailers;
		$queryArr['mailer_id'] = $mailerId;
		$queryArr['limit'] = 1;
                $arr = $individual->retrieveEmails($queryArr);
		$res = array();
		if($addDefaultField == 'Y')
		{	
			$i = 0;
			if(is_array($P))
		 	foreach($P as $key => $value)
			{
				if(is_array($arr))
				foreach($arr as $k=>$v)	
				{
					$k=$k."#".$i;
					$res[$k] = $v;
					$res[$k]['EMAIL'] = $value;
					$i++;
				}
			}	
			if(is_array($T))
		 	foreach($T as $key => $value)
			{
				if(is_array($arr))
				foreach($arr as $k=>$v)	
				{
					$k=$k."#".$i;
					$res[$k] = $v;
					$res[$k]['EMAIL'] = $value;
					$i++;
				}
			}	
		}
		return $res;
	}
}
?>
