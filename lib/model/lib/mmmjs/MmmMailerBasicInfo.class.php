<?php
/** 
* class for new mailer of mmmjs
*/
class MmmMailerBasicInfo
{
	/**
	* This function will get details of array based on crieteria passed..
	* @param whereParamArray array passed.
	* @param fields columns/details to be fetched.
	* @return array details of mailers
	*/
	public function getInfo($whereParamArray,$fields="*")
	{
		$mmmjs_MAIN_MAILER = new mmmjs_MAIN_MAILER;
		$arr = $mmmjs_MAIN_MAILER->get($whereParamArray, $fields,'','N');	
		return $arr;
	}


	/** 
	* converts the parameter to comma separated strings
	* @param $x - an array or string or integer
	* @return comma separated strings
	*/
	private function convertToString($x)
	{
		if(is_array($x))
			$y = "'".implode("','", $x)."'";
		else if(is_numeric($x))
			$y = $x;
		else if(is_string($x) && !strpos($x,"'") && strpos($x,","))
			$y = substr_replace($x, ",", "','");
		else
			$y = $x;
		return $y;
	}

	/** 
	* Library function to create a new mailer .....
	* @param $mailerArr - associative array with key(column name) and value
	* @return $autoid - newly created mailer id
	*/
	public function createNewMailer($mailerArr)
	{
		$newMailer = new mmmjs_MAIN_MAILER;
		$data["uniqueid"]      = uniqid(rand(), true);
		$data["mailer_name"]   = $mailerArr["mailer_name"];
		$data["client_name"]   = $mailerArr["client_name"];
		$data["mail_type"]     = $mailerArr["mail_type"];
		$data["response_type"] = $mailerArr["response_type"];
		$data["company"]       = $mailerArr["company"];
		$data["pos"]           = $mailerArr["pos"];
		$data["mailer_for"]    = $mailerArr["mailer_for"];
		$data["data"]          = $mailerArr["uniqueid"];
		$autoid                = $newMailer->insertEntry($data);
		return $autoid;
	}

	
	/**
	* This function get site for which mailerid is running .....
	* @param $id mailer id
	* @return enum(J/9) corresponding to site.
	*/
	public function getSiteEnumFromMailerId($id)
	{
		$arr = $this->retreiveMailerInfo($id,"MAILER_FOR");
		return $arr['MAILER_FOR'];
	}


	/**
	* This function will retreive details of existing mailer based on id .....
	* @param id unqiue id of mailer table.
	* @return array containing mailer info.
	* @return array containg info.
	**/
	public function retreiveMailerInfo($id,$param="*")
	{
		$mmmjs_MAIN_MAILER = new mmmjs_MAIN_MAILER;
		$whereParamArray["MAILER_ID"] = $id;
		$res = $mmmjs_MAIN_MAILER->get($whereParamArray,$param,"",""); 
		return $res[0];
	}


        /**
        * This function will retreive all mailer based on website enum .....
        * @param siteEnum char website enum(J/9)
        * @return array containing info of mailerid and mailername
	* @param mailerType type of mailer
        **/
	public function retrieveAllMailers($siteEnum='',$mailerType='',$isMailerPeriodRequired = 'Y')
	{
		$mmmjs_MAIN_MAILER = new mmmjs_MAIN_MAILER;
		$fields = "MAILER_ID, MAILER_NAME";
		$whereParamArray = array();
		if($siteEnum)
			$whereParamArray["MAILER_FOR"] = $siteEnum;

		if($mailerType)
			$whereParamArray["MAIL_TYPE"] = $mailerType;

		$arr = $mmmjs_MAIN_MAILER->get($whereParamArray, $fields,"",$isMailerPeriodRequired);
		foreach($arr as $value)
		{
			$ret[$value['MAILER_ID']] = $value['MAILER_NAME'];
		}
		return $ret;
	}

	/**
        * This function will retreive all mailer based on status
        * @return array containing mailer info.
	*/
	public function retrieveMailersByStatus($status)
	{
		$whereParamArray['STATUS']=$status;
		$fields = "MAILER_ID, MAILER_NAME";
		$ret = array();
		$arr = $this->getInfo($whereParamArray,$fields);
		foreach($arr as $key => $value)
			$ret[$value['MAILER_ID']] = $value['MAILER_NAME'];
		return $ret;
	}


	/**
	* This function will update the Rtime
	*/
	public function updateRTime($mailerId)
	{
		$mmmjs_MAIN_MAILER = new mmmjs_MAIN_MAILER;
		$wherefields['MAILER_ID'] = $mailerId;
		$setfields['RTIME'] = date('Y-m-d H:i:s');
		$mmmjs_MAIN_MAILER->update($wherefields, $setfields);
	}

	/**
	* This function get runtime for mailerid.
	* @param $id mailer id
	* @return date
	*/
	public function getRunStartDate($mailerId)
	{
		$rtime = $this->retreiveMailerInfo($mailerId,"RTIME");
		return substr($rtime['RTIME'], 0, 10);
	}


	/**
	* This function update the status of mailer id .....
	* All posible mailer status are mentioned at MmmConfig::$status.
	* @param mailerIds ids to be updated
	* @param status updated status  value.
	*/
	public function updateStatus($mailerIds, $status)
	{
		$mmmjs_MAIN_MAILER = new mmmjs_MAIN_MAILER;
		$setfields['STATUS'] = $status;
		$wherefields['MAILER_ID'] = $this->convertToString($mailerIds);
		$mmmjs_MAIN_MAILER->update($wherefields, $setfields);
	}
	
	public function updateLastRtime($mailerId)
    {
        $mmmjs_MAIN_MAILER = new mmmjs_MAIN_MAILER;
        $mmmjs_MAIN_MAILER->updateLastRtime($mailerId);
    }

	public function getNamebyId($ids,$WithKeyId='')
	{
		$newMailer = new mmmjs_MAIN_MAILER;
		return $newMailer->getNames($ids,$WithKeyId);
	}

	/** 
	* List of mailer for which test mail need to be fired 
	* @return array containing list of mailer info
	**/
	public function listMailerMarkedForTetsing()
	{
		$mmmjs_MAIN_MAILER = new mmmjs_MAIN_MAILER;
		$whereParamArray["STATUS"] = MmmConfig::$status["MARKED_FOR_TESTING"];
		$mailersMainInfo = $mmmjs_MAIN_MAILER->get($whereParamArray,'MAILER_ID,MAIL_TYPE,MAILER_FOR');
		unset($whereParamArray);
		return $mailersMainInfo;
	}

        public function getUniqueId($mailerId)
        {
                $mmmjs_MAIN_MAILER = new mmmjs_MAIN_MAILER;
                $fields = "UNIQUEID, MAILER_NAME";
                $whereParamArray["MAILER_ID"] = $mailerId;
                $arr = $mmmjs_MAIN_MAILER->get($whereParamArray, $fields, "");
                return $arr[0];
        }
}
?>
