<?php
/**
 *CLASS DppAutoSuggest
 * The return Jpartner obj after setting all the suggested dpp values 
 * @param ProfileObj PROFILE object
 * <BR>
 * How to call this file<BR> 
 * <code>
    * $dppObj=new DppAutoSuggest($profileObj); *$profileFieldArr=array("AGE","MTONGUE","HEIGHT","COUNTRY_RES","CITY_RES","MSTATUS","MTONGUE","RELIGION","CASTE","DIET","SMOKE","DRINK","COMPLEXION","BTYPE","OCCUPATION","MANGLIK","HANDICAPPED","AGE","INCOME");
    *$dppObj->insertJpartnerDPP($profileFieldArr);
* </code>
 * PHP versions 4 and 5
 * @package   jeevansathi
 * @subpackage   registration Revamp
 * @author   Nitesh
 * @copyright 2013 
  */
Class DppAutoSuggest
{
	private $profileObj;
	private $JpartnerObj;
	
	
	/*
	 * set Jpartner Obj if not set
	 * @profileidobj profile obj of user
	 */
	
	function __construct($profileObj)
	{
		include_once(sfConfig::get("sf_web_dir")."/classes/Jpartner.class.php");
		if($profileObj instanceof Profile)
			$this->profileObj=$profileObj;
		else
			{
				echo "Err:No Profile Object Defined";
				return;
			}
		if(!($this->JpartnerObj instanceof Jpartner))
		{
			$this->setJpartnerObj($this->profileObj->getPROFILEID());
			
		}
			
	}
	/*
	 * set Jpartner Obj
	 * @profileid profileid of user
	 */
	function setJpartnerObj($profileid)
	{
			$this->JpartnerObj=new Jpartner;
			$this->JpartnerObj->setPROFILEID($profileid);
			$this->mysqlObj=new Mysql;
			$this->myDbName=getProfileDatabaseConnectionName($profileid,'',$this->mysqlObj);
			$this->myDb=$this->mysqlObj->connect($this->myDbName);
			$this->JpartnerObj->setPartnerDetails($profileid,$this->myDb,$this->mysqlObj);
		
	}
	/*
	 * get Jpartner Obj
	 */
	function getJpartnerObj()
	{
			return $this->JpartnerObj;
		
	}
	
	/**
	* set Jpartner object auto suggested DPP fields
	* @return Jpartner Obj
	 * @fieldsArray array of fields needed to be set for a user
	 */
	function insertJpartnerDPP($fieldsArray)
	{
		$fieldsIdArray=DppAutoSuggestEnum::$FIELD_ID_ARRAY;
		foreach($fieldsIdArray as $key=>$value)
		{
			if(in_array($value,$fieldsArray))
			{
				$functionName="";
					if($value=="CITY_RES" ) $functionName="setPartner_CITYRES";
					elseif($value=="COUNTRY_RES" ) $functionName="setPartner_COUNTRYRES";
					elseif($value=="COMPLEXION" ) $functionName="setPartner_COMP";
					elseif($value=="OCCUPATION" ) $functionName="setPartner_OCC";
					elseif($value=="HANDICAPPED" ) $functionName="setHANDICAPPED";
					elseif($value=="EDU_LEVEL_NEW") $functionName="setPartner_ELEVEL_NEW";
					
					$AutoSuggestValue= DppAutoSuggestValue::getAutoSuggestValue($value,$key,$this->profileObj);
					if(is_array($AutoSuggestValue))
					{
						if($value=="INCOME")
						{
							call_user_func_array(array($this->JpartnerObj, "setL".$value), array($AutoSuggestValue["minIR"]));
							call_user_func_array(array($this->JpartnerObj, "setH".$value), array($AutoSuggestValue["maxIR"]));
							call_user_func_array(array($this->JpartnerObj, "setL".$value."_DOL"), array($AutoSuggestValue["minID"]));
							call_user_func_array(array($this->JpartnerObj, "setH".$value."_DOL"), array($AutoSuggestValue["maxID"]));
						}
						else
						{
							$KEY=array_keys($AutoSuggestValue);
							call_user_func_array(array($this->JpartnerObj, "setL".$value), array($KEY[0]));
							call_user_func_array(array($this->JpartnerObj, "setH".$value), array($AutoSuggestValue[$KEY[0]]));
						}
					}
					elseif($functionName)
					call_user_func_array(array($this->JpartnerObj, $functionName), array($AutoSuggestValue));
					else
					call_user_func_array(array($this->JpartnerObj, "setPartner_".$value), array($AutoSuggestValue));
					
			}
		}
		
		//depends upon the requiremnt whether japrtner object values needed to be  set or just need to return japrtner object 
		
		$this->JpartnerObj->updatePartnerDetails($this->myDb,$this->mysqlObj);
		
		//return $this->JpartnerObj;
	}
	
}


?>
