<?php
/**
* This class handles searches/saves related to search saved by users for future purpose. 
* @author : Lavesh Rawat
* @package Search
* @subpackage SearchTypes
* @copyright 2014 Lavesh Rawat
* @link http://devjs.infoedge.com/mediawiki/index.php/SEARCH
* @since 2012-08-10
*/
class UserSavedSearches extends SearchParamters
{
	private $ID;
	private $pid;
	public function __construct($loggedInProfileObj)
	{
		parent::__construct();
		$this->possibleSearchParamters = SearchConfig::$possibleSearchParamters;
		$this->loggedInProfileObj = $loggedInProfileObj;
		/* Save Search is only relevant in logged in case, else show timedout page. */
		if($loggedInProfileObj->getPROFILEID())
			$this->pid =  $loggedInProfileObj->getPROFILEID();
		else
		{
			$context = sfContext::getInstance();
			$context->getController()->forward("static", "logoutPage"); //Logout page + log(section shd not call)
                	throw new sfStopException();
		}
	}
	
	/*
	* Sets SearchParamtersObj corresponding to id(primary key of teh SEARCH_AGENT table.
	* @param $id the unique id 
	*/
	public function getSearchCriteria($id,$fromMailer= '')
	{
 		$paramArr['ID'] = $id;
		$paramArr['PROFILEID'] = $this->pid;
		/**
		* called the store(SEARCH_AGENT) to get details for the id.
		* set these details to SearchParamtersObj.
		*/	
		$SEARCH_AGENTobj = new SEARCH_AGENT;
		$arr = $SEARCH_AGENTobj->get($paramArr,$this->possibleSearchParamters);
		if(is_array($arr[0]))
		{
			foreach($arr[0] as $field=>$value)
				if(strstr($this->possibleSearchParamters,$field))
					eval ('$this->set'.$field.'($value);');
			//$this->field = $value;
			if(MobileCommon::isApp()=='I')
                                $this->setSEARCH_TYPE(SearchTypesEnums::iOSSaveSearch);
                        elseif(MobileCommon::isApp()=='A')
                                $this->setSEARCH_TYPE(SearchTypesEnums::AppSaveSearch);
                        elseif(MobileCommon::isMobile())
                                $this->setSEARCH_TYPE(SearchTypesEnums::WapSaveSearch);
                        else
                                $this->setSEARCH_TYPE(SearchTypesEnums::SaveSearch);
			$this->setNEWSEARCH_CLUSTERING('');
		}
		else
		{
			if($fromMailer == 1)
			{
				throw new jsException('',"Error Processing Request in SavedSearchCalculationTask");	
			}
			else
			{
				//Log an entry : still perform a search with gender=oppisite gender. 
	                if($this->loggedInProfileObj->getGENDER()=='F')
				$this->setGENDER('M');
	                else
				$this->setGENDER('F');
			}
		}
	}

	/*
        * This Function is to save search criteria by id 
        */
	public function SaveSearchbyid($request,$loggedInProfileObj)
	{
		$saveSearchName = trim($request->getParameter('saveSearchName'));		//save search name given by user to save search
		$searchId = $request->getParameter('searchId');	
		$SearchParamtersLayer = new SearchParamtersLayer;
		$SearchParamtersObj = $SearchParamtersLayer->setSearchParamters($request, $loggedInProfileObj);
		$paramarr = array("name"=>$saveSearchName,"id"=>$searchId,"SearchParam"=>$SearchParamtersObj,"loggedInObj"=>$loggedInProfileObj);
		$validationSaveObj = new ValidateSaveSearch;
		$errorDetails = $validationSaveObj->savesearch($paramarr);
		if($errorDetails)
		{
			$saveDetails["errorMsg"] = $errorDetails;
			$saveDetails["successMsg"] = null;
		}
		else
		{
			$UserSavedSearches = new UserSavedSearches($loggedInProfileObj);
			$success = $UserSavedSearches->SaveSearch($SearchParamtersObj, $saveSearchName, $saveSearchId); //Insert into database
			if ($success == '0')  //If no row inserted then error
			{
				$saveDetails["errorMsg"] = SaveSearchMsgEnum::$InsertError;
				$saveDetails["successMsg"] = null;
			}
			else
			{
				$saveDetails["errorMsg"] = null;
				$saveDetails["successMsg"] = str_replace("<Name>", $saveSearchName, SaveSearchMsgEnum::$SuccessSaved);
			}		
		}
		return $saveDetails;
	}

        /*
        * This Function is to save search criteria
        * @param SearchParamtersObj object-array storing the deatils of search perfomed.
        * @return searchId unique id of searching
        */
        public function SaveSearch($SearchParamtersObj,$saveSearchName,$replaceId="")
        {
                $SEARCH_AGENTObj = new SEARCH_AGENT;
       
	        $possibleSearchParamters = explode(",",$this->possibleSearchParamters);
                foreach($possibleSearchParamters as $v)
                {
                        if($v)
                        {
                                $getter = "get".$v;
                                $vv = $SearchParamtersObj->$getter();
                                if($vv || $vv=='0')
					if(!in_array(htmlspecialchars(stripslashes($vv)),searchConfig::$dont_all_labels))
						$updateArr[$v]="'".$vv."'";
                        }
                }
                /* Addition Things need to be stored */
                $key = 'PROFILEID';
		$updateArr[$key]=$this->pid;

                $key = 'SEARCH_NAME';
                $updateArr[$key] = "'".$saveSearchName."'";
                /* Addition Things need to be stored */
		if($replaceId)
		{
			$key = "ID";
			$updateArr[$key] = "'".$replaceId."'";
		}			
                $saveId = $SEARCH_AGENTObj->addRecords($updateArr);
		$SearchParamtersObj->setID($saveId);
                return $saveId;
        }

        /**
        * This function is used to count the number of save search of a profile.
        */
	public function countRecord()
	{
                $SEARCH_AGENTObj = new SEARCH_AGENT;
                $count = $SEARCH_AGENTObj->countRecord($this->pid);
                return $count;
	}

        /**
        * This function is used to delete a save search of a profile.
        * @param id int unique auto-increment id of the table.
        * @return int (1 if records is deleted successfully)
        */
        public function deleteRecord($id)
        {
                $SEARCH_AGENTObj = new SEARCH_AGENT;
                $isSuccess = $SEARCH_AGENTObj->deleteRecord($id,$this->pid);
                return $isSuccess;
		}

        /**
        * This function is used to get the list of saved searches for a profile.
	* @param id(optional) if data of a particular id is needed
        * @return arr having saved searches names and their ids.
        */
	public function getSavedSearches($id="",$getAllData='')
	{
		$paramArr["PROFILEID"] = $this->pid;
		if($id)
			$paramArr["ID"] = $id;
		$fields = "SQL_CACHE SEARCH_NAME,ID";
		if($getAllData)
			$fields.=",".$this->possibleSearchParamters;
                $SEARCH_AGENTObj = new SEARCH_AGENT(SearchConfig::getSearchDb());
		$arr = $SEARCH_AGENTObj->get($paramArr,$fields);
		if($arr)
		{
			if($id)//return search name
				return $arr[0]["SEARCH_NAME"];
			else	//return raw data
				return $arr;	
		}
		else
			return null;
	}
	/* setters and getters*/
	function setID($ID) { $this->ID = $ID; }
	function getID() { return $this->ID; }
	/* setters and getters*/
}
?>
