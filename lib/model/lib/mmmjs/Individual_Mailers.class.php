<?php 

/***
* This class handles the individual mailer which have information of mails and paramters like EMAIL,PROFILEID,PHONE.....
*/

class Individual_Mailers
{ 
	/**
	* Convert mailer_id to tableName..
	* @param $mailer_id - int(mailer-id)
	* @return name of the table
	*/
	private function tableName($mailer_id)
	{	
		if(strpos($mailer_id,"mailer")) //error handling if tableName is passed.
			return $mailer_id;
		return "mmmjs.".$mailer_id."mailer";
	}


	/*
	* Perform the dump operations bases on id or array of values from csv .....
	* @param mailedId int
	* @param site J/99
	* @param format value=csv if csv format.
	* @param dumpData data to be added (ex for csv format case)
	*/
	public function createDumpPerform($mailerId,$site,$format='',$dumpData='')
	{
		try
		{
			$this->createTable($mailerId);

			if($format=='test')
			{
				$count = $this->getCountOfMails($mailerId);
				if($count==0)
				{
					$mailerspec = SearchQueryFactory::getObject($site,'',$mailerId);
					$mailerspec->createDump($mailerId,'TestDump');
				}
			}
			else
			{
				if($format=='csv')
				{
					$MmmCsvService = new MmmCsvService;
					$individual = new mmmjs_INDIVIDUAL_MAILERS;
					$tableName = $this->tableName($mailerId);	
					$individual->truncateTable($tableName); // Added By Neha : To truncate table before populating.
					$MmmCsvService->createDump($dumpData,$mailerId);
				}
				else
				{
					$mailerspec = SearchQueryFactory::getObject($site,'',$mailerId);
					if($mailerspec->showMailerSpecs($mailerId)!=NULL)
					{
						$mailerspec->createDump($mailerId);
						$this->updateDumpStatus($mailerId);
					}
				}
			}
		}
		catch(Exception $e)
                {       
			throw new jsException($e);
		}
	}


	/*
	* creates a mailer table of the format [mailerId]mailer
	* @param id int mailerId
	*/	
	private function createTable($mailerId)
	{
		if(!$mailerId)
			throw new jsException("","mailer id is blank in createTable() of Individual_Mailers.class.php");
		try
		{
			$individual = new mmmjs_INDIVIDUAL_MAILERS;
			$individual->createTable($this->tableName($mailerId));
		}
		catch(Exception $e)
                {       
			throw new jsException($e);
		}
	}


	/**
	* This function will populate the mailer based on query and mailerid.
	* @param mailerid id for which data to be populated.
	* @param query query based on which mailer will be populated.
	*/
	public function populateTableBasedOnMysqlQuery($mailerId,$query)
	{
		try
		{
	                $individual = new mmmjs_INDIVIDUAL_MAILERS;
			$arr["tableName"] = $this->tableName($mailerId);
			$arr["query"] = $query;
        	        $individual->populateTableBasedOnSearchQuery($arr);
		}
		catch(Exception $e)
                {       
			throw new jsException($e);
		}
	}


        /**
        * This function will populate the mailer based on Array and mailerid.
        * @param mailerid id for which data to be populated.
        * @param query query based on which mailer will be populated.
        */
        public function populateTableBasedOnArray($dumpDataArray,$mailerId)
        {
                try
                {
                        $individual = new mmmjs_INDIVIDUAL_MAILERS;

			$arr["tableName"] = $this->tableName($mailerId);
			$arr["dumpDataArr"] = $dumpDataArray;
			foreach($dumpDataArray[0] as $k=>$v)
				$tempArr[] = $k;
			$arr["keys"] = implode(",",$tempArr);
                        $individual->populateTableBasedOnArray($arr);
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }

	/**
	* Update the username by name of users.
	* @param mailerid id for which data to be populated/updated.
	*/
	public function updateUserNames($mailerId)
	{
		try
		{
			$mmmjs_INDIVIDUAL_MAILERS = new mmmjs_INDIVIDUAL_MAILERS;
			$mmmjs_INDIVIDUAL_MAILERS->updateUserNames($this->tableName($mailerId));
		}
		catch(Exception $e)
                {       
			throw new jsException($e);
		}
	}
	

	/**
	* This function will mark the status to be dumo done.
	* @param mailerid id
	*/
	private function updateDumpStatus($mailer_id)
	{
		try
		{
			$writeMail = new  MmmMailerDetailedInfo;
			$writeMail->updateDumpStatus($mailer_id);
		}
		catch(Exception $e)
                {       
			throw new jsException($e);
		}
	}


	/** ??
	* This function will retreive the mailer information based on specifictaion specified like maileid.
	* Staggering is also handled by this function based on $specs['n'] & $specs['day'].
	* @param $specs mailer id + other details.
	* @return array containing mailer info.
	*/
	public function retrieveEmails($specs)
	{
		$individual = new mmmjs_INDIVIDUAL_MAILERS;
		return $individual->retrieve($this->tableName($specs['mailer_id']), $specs['limit'], $specs['totalStaggerTime'], $specs['day']);
	}


	/**
	*
	*/
	public function updateStatus($specs,$sent='')
	{
		if(is_array($specs))
		{	
			$individual = new mmmjs_INDIVIDUAL_MAILERS;
			if($specs['profileId'])
			{
				if(is_array($specs['profileId']))
				{
					$profileId = convertValueToQuotseparated(implode(',', $specs['profileId']));
				}
				else
				{
					$profileId = $specs['profileId'];
				}
                                $individual->updateStatus($profileId, $this->tableName($specs['mailer_id']),$sent);
			}				
		}
	}	

	/**
	* 
	*/
	public function unsubscribe($profileId)
	{
		if($profileId)
		{
			$jprofile = new JPROFILE;
			$jprofile->edit(array("UDATE" => date("y-m-d"), "PROMO_MAILS" => "U"), $profileId, "PROFILEID");
		}		
	}

	public function getCountOfMails($mailerId,$sent='')
	{
		$table = $this->tableName($mailerId);
		$mmmjs_INDIVIDUAL_MAILERS = new mmmjs_INDIVIDUAL_MAILERS;
		$cnt = $mmmjs_INDIVIDUAL_MAILERS->getCountOfMails($table,$sent);
		return $cnt;
	}

	public function getTotalCountTillYesterday($mailerId){
		$mmmjs_INDIVIDUAL_MAILERS = new mmmjs_INDIVIDUAL_MAILERS;
		$cnt = $mmmjs_INDIVIDUAL_MAILERS->getTotalCountTillYesterday($mailerId);
		return $cnt;

	}

}
