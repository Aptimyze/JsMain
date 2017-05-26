<?php
class Operator extends LoggedInProfile{
	/**
	 * @fn getDetail
	 * @brief fetches profile detail. sets the detail to Profile Object.
	 * @param $value Query criteria value
	 * @param $criteria Query criteria column
	 * @param $fields Columns to query
	 * @param $effect RAW or DECORATED; 
	  Use RAW for getting direct results from JPROFILE, 
	  Use DECORATED for getting results to display
	 * @return Profile detail array;
	 */
        
	public function getDetail($value="", $criteria="", $fields="", $effect="RAW"){
		if(!$criteria){ $criteria = 'PROFILEID'; if(!$value) $value=$this->getPROFILEID();} 
		$this->$criteria=$value;
		//Partition key 
		//$addWhereParam["activatedKey"]=1;
		$res = $this->JPROFILE->get($value, $criteria, $fields,$addWhereParam);//Fetches results from JPROFILE
		if($res==null)
		{
			$addWhereParam["activatedKey"]=0;
			$res = $this->JPROFILE->get($value, $criteria, $fields,$addWhereParam);//Fetches results from JPROFILE
			
		}
		$detail = $this->setDetail($res, $effect);//Sets profile detail to the object		
		
		return $detail;
	}

        public static function getInstance($dbName="",$profileid="")
        {
                return new Operator($dbName,$profileid);
        }

        public function __construct($dbname="", $profileid=""){
                $this->JPROFILE = JPROFILE::getInstance($dbname);
                if(!$profileid)  $this->setPROFILEID(sfContext::getInstance()->getRequest()->getAttribute('profileid'));
                else $this->setPROFILEID($profileid);
        }

}
?>
