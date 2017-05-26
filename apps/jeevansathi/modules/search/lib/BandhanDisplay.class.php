<?php
/*
This class handles the display format for bandhan.com and doctors republic
*/
class BandhanDisplay
{
	private $responseObj;
	private $dataArr;

	public function __construct($responseObj="",$dataArr="")
        {
		if($responseObj)		
			$this->responseObj = $responseObj;
		elseif($dataArr)
			$this->dataArr = $dataArr;
		else
			die("No parameter passed in constructor of BandhanDisplay.class.php");
        }

	/*
	This function generates the output for the solr response obtained. It stores it in a string and displays it iteratively.
	@param - logic; which if equals to 2 then only profile urls are returned
	*/
	public function generateDisplay($logic)
	{
		if($logic!=2)
		{
			if($this->responseObj && is_array($this->responseObj->getSearchResultsPidArr()) && count($this->responseObj->getSearchResultsPidArr()))
			{
				$profArrObj = new ProfileArray;
				$profileIdArr["PROFILEID"] = implode(",",$this->responseObj->getSearchResultsPidArr());
				$profileObjArray = $profArrObj->getResultsBasedOnJprofileFields($profileIdArr);
				unset($profArrObj);
				unset($profileIdArr);
				$paObj = new PictureArray($profileObjArray);
				$photoUrlArr = $paObj->getProfilePhoto();
				unset($profileObjArray);
				unset($paObj);
			}
			elseif($this->dataArr && is_array($this->dataArr))
			{
				/*
				$idStr = "";
				foreach($this->dataArr as $k=>$v)
					$idStr = $idStr.$v["PROFILEID"].",";
				$idStr = rtrim($idStr,",");

				$profArrObj = new ProfileArray;
                                $profileIdArr["PROFILEID"] = $idStr;
				unset($idStr);
                                $profileObjArray = $profArrObj->getResultsBasedOnJprofileFields($profileIdArr);
                                unset($profArrObj);
                                unset($profileIdArr);
                                $paObj = new PictureArray($profileObjArray);
                                $photoUrlArr = $paObj->getProfilePhoto();
                                unset($profileObjArray);
                                unset($paObj);	
				*/
			}
		}

		if($this->responseObj && is_array($this->responseObj->getResultsArr()))
		{
			foreach($this->responseObj->getResultsArr() as $k=>$v)
			{
				$str="";
				$str.= "<item>\n";
				if($logic!=2)
				{
					$str.= "<profile_id>".$v["id"]."</profile_id>\n";
					$str.= "<username><![CDATA[".$v["USERNAME"]."]]></username>\n";
					$str.= "<gender>".FieldMap::getFieldLabel("gender",$v["GENDER"])."</gender>\n";
					if($v["MANGLIK"])
						$str.= "<manglik>".FieldMap::getFieldLabel("manglik",$v["MANGLIK"])."</manglik>\n";
					else
						$str.= "<manglik></manglik>\n";
					if($v["CASTE"])
						$str.= "<caste>".CommonFunction::myencode(FieldMap::getFieldLabel("caste",$v["CASTE"]))."</caste>\n";
					else
						$str.= "<caste></caste>\n";
					if($v["MSTATUS"])
						$str.= "<marital_status>".FieldMap::getFieldLabel("mstatus",$v["MSTATUS"])."</marital_status>\n";
					else
						$str.= "<marital_status></marital_status>\n";
					if($v["MTONGUE"])
						$str.= "<mother_tongue>".CommonFunction::myencode(FieldMap::getFieldLabel("community",$v["MTONGUE"]))."</mother_tongue>\n";
					else	
						$str.= "<mother_tongue></mother_tongue>\n";
					if($v["RELIGION"])
						$str.= "<religion>".FieldMap::getFieldLabel("religion",$v["RELIGION"])."</religion>\n";
					else
						$str.= "<religion></religion>\n";
					if($v["OCCUPATION"])
						$str.= "<profession>".CommonFunction::myencode(FieldMap::getFieldLabel("occupation",$v["OCCUPATION"]))."</profession>\n";
					else
						$str.= "<profession></profession>\n";
					if($v["COUNTRY_RES"])
						$str.= "<country>".CommonFunction::myencode(FieldMap::getFieldLabel("country",$v["COUNTRY_RES"]))."</country>\n";
					else
						$str.= "<country></country>\n";
					if($v["CITY_RES"])
						$str.= "<city_or_state>".CommonFunction::myencode(FieldMap::getFieldLabel("city",$v["CITY_RES"]))."</city_or_state>\n";
					else
						$str.= "<city_or_state></city_or_state>\n";
					if($v["HEIGHT"])
						$str.= "<height>".FieldMap::getFieldLabel("height_without_meters",$v["HEIGHT"])."</height>\n";
					else
						$str.= "<height></height>\n";
					if($v["EDU_LEVEL"])
						$str.= "<education_level>".CommonFunction::myencode(FieldMap::getFieldLabel("education_label",$v["EDU_LEVEL"]))."</education_level>\n";
					else
						$str.= "<education_level></education_level>\n";
					if($v["EDU_LEVEL_NEW"])
						$str.= "<education>".CommonFunction::myencode(FieldMap::getFieldLabel("education",$v["EDU_LEVEL_NEW"]))."</education>\n";
					else
						$str.= "<education></education>\n";
					if($v["SMOKE"])
						$str.="<smoke>".FieldMap::getFieldLabel("smoke",$v["SMOKE"])."</smoke>\n";
					else
						$str.="<smoke></smoke>\n";
					if($v["DRINK"])
						$str.="<drink>".FieldMap::getFieldLabel("drink",$v["DRINK"])."</drink>\n";
					else
						$str.="<drink></drink>\n";
					if($v["HAVECHILD"])
						$str.="<children>".FieldMap::getFieldLabel("children",$v["HAVECHILD"])."</children>\n";
					else
						$str.="<children></children>\n";
					if($v["BTYPE"])
						$str.="<body_type>".FieldMap::getFieldLabel("bodytype",$v["BTYPE"])."</body_type>\n";
					else
						$str.="<body_type></body_type>\n";
					if($v["COMPLEXION"])
						$str.="<complexion>".FieldMap::getFieldLabel("complexion",$v["COMPLEXION"])."</complexion>\n";
					else
						$str.="<complexion></complexion>\n";
					if($v["DIET"])
						$str.="<diet>".FieldMap::getFieldLabel("diet",$v["DIET"])."</diet>\n";
					else
						$str.="<diet></diet>\n";
					if($v["HANDICAPPED"])
						$str.="<handicapped>".FieldMap::getFieldLabel("handicapped",$v["HANDICAPPED"])."</handicapped>\n";
					else
						$str.="<handicapped></handicapped>\n";
					if($v["RELATION"])
						$str.="<posted_by>".FieldMap::getFieldLabel("relationship",$v["RELATION"])."</posted_by>\n";
					else
						$str.="<posted_by></posted_by>\n";
					if($v["AGE"])
						$str.="<age>".$v["AGE"]." years</age>\n";
					else
						$str.="<age></age>\n";
					if($v["INCOME"])
						$str.="<income>".FieldMap::getFieldLabel("income_map",$v["INCOME"])."</income>\n";
					else
						$str.="<income></income>\n";
				}
				$str.= "<profile_link>".sfConfig::get('app_site_url')."/profile/matrimonial-".CommonUtility::statName($v["id"],$v["USERNAME"]).".htm</profile_link>\n";
				if($logic!=2)
				{
					if($photoUrlArr[$v["id"]] && $photoUrlArr[$v["id"]]->getSearchPicUrl())
						$str.= "<photo_link>".$photoUrlArr[$v["id"]]->getSearchPicUrl()."</photo_link>\n";
					else
						$str.= "<photo_link>".PictureService::getRequestOrNoPhotoUrl("requestPhoto","SearchPicUrl",$v["GENDER"])."</photo_link>\n";
				}
				$str.= "</item>\n";
				echo $str;
			}
		}
		elseif($this->dataArr && is_array($this->dataArr))
		{
			foreach($this->dataArr as $k=>$v)
			{
				$str="";
				$str.= "<item>\n";
				if($logic!=2)
				{
					$str.= "<profile_id>".$v["PROFILEID"]."</profile_id>\n";
					$str.= "<username><![CDATA[".$v["USERNAME"]."]]></username>\n";
					$str.= "<gender>".FieldMap::getFieldLabel("gender",$v["GENDER"])."</gender>\n";
					if($v["MANGLIK"])
						$str.= "<manglik>".FieldMap::getFieldLabel("manglik",$v["MANGLIK"])."</manglik>\n";
					else
						$str.= "<manglik></manglik>\n";
					if($v["CASTE"])
						$str.= "<caste>".CommonFunction::myencode(FieldMap::getFieldLabel("caste",$v["CASTE"]))."</caste>\n";
					else
						$str.= "<caste></caste>\n";
					if($v["MSTATUS"])
						$str.= "<marital_status>".FieldMap::getFieldLabel("mstatus",$v["MSTATUS"])."</marital_status>\n";
					else
						$str.= "<marital_status></marital_status>\n";
					if($v["MTONGUE"])
						$str.= "<mother_tongue>".CommonFunction::myencode(FieldMap::getFieldLabel("community",$v["MTONGUE"]))."</mother_tongue>\n";
					else	
						$str.= "<mother_tongue></mother_tongue>\n";
					if($v["RELIGION"])
						$str.= "<religion>".FieldMap::getFieldLabel("religion",$v["RELIGION"])."</religion>\n";
					else
						$str.= "<religion></religion>\n";
					if($v["OCCUPATION"])
						$str.= "<profession>".CommonFunction::myencode(FieldMap::getFieldLabel("occupation",$v["OCCUPATION"]))."</profession>\n";
					else
						$str.= "<profession></profession>\n";
					if($v["COUNTRY_RES"])
						$str.= "<country>".CommonFunction::myencode(FieldMap::getFieldLabel("country",$v["COUNTRY_RES"]))."</country>\n";
					else
						$str.= "<country></country>\n";
					if($v["CITY_RES"])
						$str.= "<city_or_state>".CommonFunction::myencode(FieldMap::getFieldLabel("city",$v["CITY_RES"]))."</city_or_state>\n";
					else
						$str.= "<city_or_state></city_or_state>\n";
					if($v["HEIGHT"])
						$str.= "<height>".FieldMap::getFieldLabel("height_without_meters",$v["HEIGHT"])."</height>\n";
					else
						$str.= "<height></height>\n";
					if($v["EDU_LEVEL"])
						$str.= "<education_level>".CommonFunction::myencode(FieldMap::getFieldLabel("education_label",$v["EDU_LEVEL"]))."</education_level>\n";
					else
						$str.= "<education_level></education_level>\n";
					if($v["EDU_LEVEL_NEW"])
						$str.= "<education>".CommonFunction::myencode(FieldMap::getFieldLabel("education",$v["EDU_LEVEL_NEW"]))."</education>\n";
					else
						$str.= "<education></education>\n";
					if($v["SMOKE"])
						$str.="<smoke>".FieldMap::getFieldLabel("smoke",$v["SMOKE"])."</smoke>\n";
					else
						$str.="<smoke></smoke>\n";
					if($v["DRINK"])
						$str.="<drink>".FieldMap::getFieldLabel("drink",$v["DRINK"])."</drink>\n";
					else
						$str.="<drink></drink>\n";
					if($v["HAVECHILD"])
						$str.="<children>".FieldMap::getFieldLabel("children",$v["HAVECHILD"])."</children>\n";
					else
						$str.="<children></children>\n";
					if($v["BTYPE"])
						$str.="<body_type>".FieldMap::getFieldLabel("bodytype",$v["BTYPE"])."</body_type>\n";
					else
						$str.="<body_type></body_type>\n";
					if($v["COMPLEXION"])
						$str.="<complexion>".FieldMap::getFieldLabel("complexion",$v["COMPLEXION"])."</complexion>\n";
					else
						$str.="<complexion></complexion>\n";
					if($v["DIET"])
						$str.="<diet>".FieldMap::getFieldLabel("diet",$v["DIET"])."</diet>\n";
					else
						$str.="<diet></diet>\n";
					if($v["HANDICAPPED"])
						$str.="<handicapped>".FieldMap::getFieldLabel("handicapped",$v["HANDICAPPED"])."</handicapped>\n";
					else
						$str.="<handicapped></handicapped>\n";
					if($v["RELATION"])
						$str.="<posted_by>".FieldMap::getFieldLabel("relationship",$v["RELATION"])."</posted_by>\n";
					else
						$str.="<posted_by></posted_by>\n";
					if($v["AGE"])
						$str.="<age>".$v["AGE"]." years</age>\n";
					else
						$str.="<age></age>\n";
					if($v["INCOME"])
						$str.="<income>".FieldMap::getFieldLabel("income_map",$v["INCOME"])."</income>\n";
					else
						$str.="<income></income>\n";
				}
				$str.= "<profile_link>".sfConfig::get('app_site_url')."/profile/matrimonial-".CommonUtility::statName($v["PROFILEID"],$v["USERNAME"]).".htm</profile_link>\n";
				if($logic!=2)
				{
					$profArrObj = new ProfileArray;
					$profileIdArr["PROFILEID"] = $v["PROFILEID"];
					$profileObjArray = $profArrObj->getResultsBasedOnJprofileFields($profileIdArr);
					unset($profArrObj);
					unset($profileIdArr);
					$paObj = new PictureArray($profileObjArray);
					$photoUrlArr = $paObj->getProfilePhoto();
					unset($profileObjArray);
					unset($paObj);

					if($photoUrlArr[$v["PROFILEID"]] && $photoUrlArr[$v["PROFILEID"]]->getSearchPicUrl())
						$str.= "<photo_link>".$photoUrlArr[$v["PROFILEID"]]->getSearchPicUrl()."</photo_link>\n";
					else
						$str.= "<photo_link>".PictureService::getRequestOrNoPhotoUrl("requestPhoto","SearchPicUrl",$v["GENDER"])."</photo_link>\n";
					unset($photoUrlArr);
				}
				$str.= "</item>\n";
				echo $str;
			}
		}
		unset($photoUrlArr);
	}
}
?>
