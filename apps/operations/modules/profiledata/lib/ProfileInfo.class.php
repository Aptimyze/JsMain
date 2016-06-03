<?php
class ProfileInfo1
{
	private $dbObj;
	private $profileid;
	private $editData;
	function __construct($pid)
	{
		$this->profileid=$pid;
	}
	function profileDelete($pid)
	{
		
		$this->dbObj1 = new NEWJS_PROFILE_DEL_REASON("newjs_slave");
		$this->dbObj2 = new JSADMIN_DELETED_PROFILES("newjs_slave");
		$deletedArr = $this->dbObj1->profileDeletionData($pid);
		$res = $this->dbObj2->profileDeletionData($pid);
		
		foreach($deletedArr as $key=>$val)
		{
			$deletedArr[$key]["REASON"] = $val["DEL_REASON"].".".$val["SPECIFIED_REASON"];
			$deletedArr[$key]["DELETED_BY"] = "Self";
		}
		
		
		foreach($res as $key=>$val)
		{
			
			if($val["RETRIEVED_BY"] == '')
			{
				$result[$key]["REASON"] = $val["REASON"]."(".$val[COMMENTS].")";
				$result[$key]["PROFILE_DEL_DATE"] = $val["TIME"];
				$result[$key]["DELETED_BY"] = $val[USER];
			}
			else
			{
				$retrievedArr[$key]["DATE"] = $val["TIME"];
				$retrievedArr[$key]["REASON"] = $val["REASON"]."(".$val[COMMENTS].")";		
				$retrievedArr[$key]["RETRIEVED_BY"] = $val["RETRIEVED_BY"];
			}
		}
	
		
		if(is_array($result))
		{
			$count = count($deletedArr);
			foreach($result as $k=>$v)
			{
				$deletedArr[$count]["PROFILE_DEL_DATE"] = $v["PROFILE_DEL_DATE"];
				$deletedArr[$count]["REASON"] = $v["REASON"];
				$deletedArr[$count]["DELETED_BY"] = $v["DELETED_BY"];
				$count++;
			}
			
		}
                $jsarchived=new newjs_ARCHIVED("newjs_slave");
                $jsarch=$jsarchived->getArchived($pid);
                if($jsarch)
                {
                        $deletedArr[$count]["REASON"]="deleted by system due to inactivity";
                        $deletedArr[$count]["PROFILE_DEL_DATE"]=$jsarch[DEACTIVE_DATE];
                        $deletedArr[$count]["DELETED_BY"]="System";
                }
	
		
		$finalArr[0] = $deletedArr;
		$finalArr[1] = $retrievedArr; 
		
		return $finalArr;
	}
	
  /**
   * 
   * @return type
   */
	function getAllModified()
	{
		$notInArray = array(PROFILEID, MOD_DT, IPADD, SCREENING, SHOWPHONE_RES, SHOWPHONE_MOB, PHOTO_DISPLAY, PHOTOSCREEN, KEYWORDS, SHOWADDRESS, SHOWMESSENGER, SHOW_PARENTS_CONTACT, PHONE_NUMBER_OWNER, PHONE_OWNER_NAME, MOBILE_NUMBER_OWNER);
    $bUseMongoDB = JsConstants::$useMongoDb;
    if(false === $bUseMongoDB) {
      $this->dbObj = new NEWJS_EDIT_LOG("newjs_slave");
      $this->editData=$this->dbObj->getDetails($this->profileid);     
      $arrUpdateFields = $this->editData;
    } else {
      $profileEditLog = new PROFILE_EDIT_LOG();
      $arrResult = $profileEditLog->getLegalDetails($this->profileid);
      unset($profileEditLog);

      $jprofile = new JPROFILE();
      $arrLatestData = $jprofile->get($this->profileid, "PROFILEID", "*");
      unset($this->editData);

      $arrUpdateFields = array();
      $arrSkip = array('MOD_DT', 'IPADD');

      foreach ($arrLatestData as $key => $value) {
        if (in_array($key, $arrSkip)) {
          $arrUpdateFields[0][$key] = $arrResult[0][$key];
        }
        else {
          $arrUpdateFields[0][$key] = $arrLatestData[$key];
        }
      }

      for ($itr = 0; $itr < count($arrResult); $itr++) {
        $arrUpdateFields[] = $arrResult[$itr];
      }
    }
    $counter = 0;

    foreach ($arrUpdateFields as $key => $val) {
      foreach ($val as $kk => $vv) {
        if ($vv && $vv != '0000-00-00' && $vv != '0000-00-00 00:00:00' && !in_array($kk, $notInArray)) {
          $modDt = $val['MOD_DT'];
          $ipAdd = $val['IPADD'];

          for($itr=$counter+1;$itr<count($arrUpdateFields);$itr++){
            if(array_key_exists($kk, $arrUpdateFields[$itr])){
              $modDt = $arrUpdateFields[$itr]['MOD_DT'];
              $ipAdd = $arrUpdateFields[$itr]['IPADD'];
              break;
            }
          }
          
          //TimeZone Conversion
          if ($bUseMongoDB && date_default_timezone_get() != "Asia/Calcutta") {
            $date = new DateTime($modDt);
            $date->setTimezone(new DateTimeZone('Asia/Calcutta'));
            $modDt = $date->format('Y-m-d H:i:s');
          }

          if ($data[$kk]) {
            $cnt = count($data[$kk]) - 1;
            if ($data[$kk][$cnt][0] != $vv)
              $data[$kk][] = array($vv, $modDt, $ipAdd);
          }
          else
            $data[$kk][] = array($vv, $modDt, $ipAdd);
        }
      }
      $counter = $counter + 1;
    }
    foreach ($data as $key => $val) {
      if (count($val) <= 1)
        unset($data[$key]);
    }

    $modArr = $this->modification($data);
    return $modArr;
  }
	
	function modification($modArr)
	{
		foreach($modArr as $key=>$val)
		{
			foreach($val as $kk=>$vv)
			{					
				
				if($key == 'CASTE' || $key == 'RELIGION' || $key == 'OCCUPATION' || $key == 'MSTATUS' || $key == 'HANDICAPPED')
					$newModArr[$key][$kk][0] = FieldMap::getFieldLabel(strtolower($key), $vv[0]);
				elseif($key=='MTONGUE')
						$newModArr[$key][$kk][0] = FieldMap::getFieldLabel("community", $vv[0]);
				elseif($key=='CITY_RES' || $key == 'CITY_BIRTH')
					$newModArr[$key][$kk][0]=FieldMap::getFieldLabel("city_india", $vv[0]);
				elseif($key=='EDU_LEVEL')
					$newModArr[$key][$kk][0]=FieldMap::getFieldLabel("education_label", $vv[0]);
				elseif($key=='COUNTRY_RES' || $key == 'COUNTRY_BIRTH')
					$newModArr[$key][$kk][0]=FieldMap::getFieldLabel("country", $vv[0]);
				elseif($key=='MOTHER_OCC' || $key == 'FAMILY_BACK')
					$newModArr[$key][$kk][0]=FieldMap::getFieldLabel("occupation", $vv[0]);
				elseif($key=='INCOME' || $key == 'FAMILY_INCOME')
						$newModArr[$key][$kk][0] = FieldMap::getFieldLabel("income_map", $vv[0]);
				
				else
					$newModArr[$key][$kk][0]= $vv[0];
					
				$newModArr[$key][$kk][1]= $vv[1];	
				$newModArr[$key][$kk][2]= $vv[2];	
					
				
			}			
		}
			//print_r($newModArr);die;
			return $newModArr;
	}	

	
	//Profile information 
	public  function setPageInformation($actObj,$profileObj)
	{
		
		//To show caste label.
		$actObj->casteLabel=JsOpsCommon::getCasteLabel($profileObj);
		$actObj->sectLabel=JsOpsCommon::getSectLabel($profileObj);

		$actObj->religionSelf=$profileObj->getDecoratedReligion();
		
		//About her section 
		$actObj->YOURINFO=$profileObj->getDecoratedYourInfo();
		$moreAbtArr["YOURINFO"]=$actObj->YOURINFO;
		$moreAbtArr[Family]=$profileObj->getDecoratedFamilyInfo();
		
		$moreAbtArr[Education]=$profileObj->getDecoratedEducationInfo();
		$moreAbtArr[Occupation]=$profileObj->getDecoratedJobInfo();
		$actObj->moreAboutArr=$moreAbtArr;
		
		
		//About her section ends here.
		
		
		//Content Right to profile pic
		
			
		$actObj->TopUsername=$profileObj->getUSERNAME();
		
		//Basic information
		$actObj->AGE=$profileObj->getAGE();
		$actObj->HEIGHT=$profileObj->getDecoratedHeight();
		$actObj->PROFILEGENDER=$profileObj->getDecoratedGender();
		
		$actObj->MTONGUE=$profileObj->getDecoratedCommunity();
		
		
		if($this->CasteAllowed($profileObj->getRELIGION()))
			$actObj->CASTE=$profileObj->getDecoratedCaste();
			
		$actObj->SUBCASTE=$profileObj->getDecoratedSubcaste();
		$actObj->MSTATUS=$profileObj->getDecoratedMaritalStatus();
		$actObj->CHILDREN=$profileObj->getDecoratedHaveChild();
		//$actObj->Annulled_Reason=ProfileCommon::getANNULLED($profileObj->getPROFILEID(),$profileObj->getMSTATUS());
		$actObj->EDU_LEVEL_NEW=$profileObj->getDecoratedEducation();
		$actObj->OCCUPATION=$profileObj->getDecoratedOccupation();
		$actObj->CITY_RES=$profileObj->getDecoratedCity();
		$actObj->COUNTRY_RES=$profileObj->getDecoratedCountry();
		$actObj->INCOME=$profileObj->getDecoratedIncomeLevel();
		$actObj->GOTHRA=$profileObj->getDecoratedGothra();
		
		
		
		$actObj->GOTHRA_MATERNAL=$profileObj->getDecoratedGothraMaternal();
		
		$actObj->RELATION=$profileObj->getDecoratedRelation();
		$actObj->PHONE_MOB=$profileObj->getPHONE_MOB();
		$actObj->PHONE_RES=$profileObj->getPHONE_RES();
		$actObj->EMAIL=$profileObj->getEMAIL();
		$actObj->MESSENGER=$profileObj->getMESSENGER_ID();
		$actObj->ADDRESS=$profileObj->getCONTACT();
		$actObj->P_ADDRESS=$profileObj->getPARENTS_CONTACT();
		
		
		
		$profileSections=new ProfileSections($profileObj);
		$actObj->lifeAttrArray=$profileSections->getLifeAttr();
		$actObj->Hobbies=$profileSections->getHobbies();
		$astroKundali=$profileSections->getAstroKundali();
		
		if($astroKundali["Time of Birth"]=="Not Available")
			$astroKundali["Time of Birth"]="";
		$actObj->AstroKundaliArr=$astroKundali;
		
		$actObj->educationAndOccArr=$profileSections->getEducationAndOcc();
		$actObj->familyArr=$profileSections->getFamilyDetails();
		$actObj->ReligionAndEth=$profileSections->getRelgionAndEthnicity($actObj->casteLabel,$actObj->sectLabel);
		
		
	}
	public function CasteAllowed($religion)
	{
		if(in_array($religion,array(1,2,3,4,9)))
			return true;
		else	
			return false;	
	}
		/**
	 * Dpp of profile
	 * @profileid profileid of user
	 * @return $jpartnerObj jpartner of profile
	 *         null if jpartner not found.
	 */
	public function getDpp($profileid,$type="raw")
	{
		include_once(sfConfig::get("sf_web_dir")."/classes/Jpartner.class.php");
		include_once(sfConfig::get("sf_web_dir")."/classes/shardingRelated.php");
			$jpartnerObj=new JPartnerDecorated();
		$mysqlObj=new Mysql;
		$myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
		$myDb=$mysqlObj->connect("$myDbName");
		$jpartnerObj->setPartnerDetails($profileid,$myDb,$mysqlObj);
		//if($jpartnerObj->isPartnerProfileExist($myDb,$mysqlObj,$profileid))
			return $jpartnerObj;
		//return null;
	}
	
}


?>
