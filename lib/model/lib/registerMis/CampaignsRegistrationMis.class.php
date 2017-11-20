<?php
class CampaignsRegistrationMis
{
	//This function gets the MIS Data based on the grouping selected
	public function getRegistrationMisData($params)
	{
		
		//create object of JPROFILE
		$jprofileObj = JPROFILE::getInstance("newjs_slave");
		$resultArr = $jprofileObj->getRegistrationMisCampaignsData($params["start_date"],$params["end_date"],$params['source_names']);
		$finalArr = $this->organiseRegistrationData($resultArr);
		return ($finalArr);
	}

	//This function organises the data in the required format based on the report_type and range_format
	public function organiseRegistrationData($dataArr)
	{
                //CLICK_URL QUALITY DEVICE PCS
                foreach($dataArr as $key=>$value)
		{
                        $dataArr[$key]["DEVICE"] = "DEVICE";
                        $dataArr[$key]["COUNTRY_RES"] = FieldMap::getFieldLabel('country',$value["COUNTRY_RES"]);
                        //print_r($value);
                        if($value["COUNTRY_RES"] == 51){
                                $stateCode = substr($value["CITY_RES"], 0,2);
                                $dataArr[$key]["STATE"] = FieldMap::getFieldLabel('state_india',$stateCode);
                                $dataArr[$key]["CITY_RES"] = FieldMap::getFieldLabel('city_india',$value["CITY_RES"]);
                        }elseif($value["COUNTRY_RES"] == 128){
                                $dataArr[$key]["STATE"] = "";
                                $dataArr[$key]["CITY_RES"] = FieldMap::getFieldLabel('city_usa',$value["CITY_RES"]);
                        }else{
                                $dataArr[$key]["STATE"] = "";
                                $dataArr[$key]["CITY_RES"] = "";
                        }
                        $dataArr[$key]["MTONGUE"] = FieldMap::getFieldLabel('community_small',$value["MTONGUE"]);
                        $dataArr[$key]["CASTE"] = FieldMap::getFieldLabel('caste',$value["CASTE"]);
                        $dataArr[$key]["EDU_LEVEL_NEW"] = FieldMap::getFieldLabel('education',$value["EDU_LEVEL_NEW"]);
                        $dataArr[$key]["OCCUPATION"] = FieldMap::getFieldLabel('occupation',$value["OCCUPATION"]);
                        $dataArr[$key]["RELATION"] = FieldMap::getFieldLabel('relation',$value["RELATION"]);
                        $dataArr[$key]["INCOME"] = FieldMap::getFieldLabel('income_level',$value["INCOME"]);                        
                        $dataArr[$key]["INCOME"] = str_replace(",", "", $dataArr[$key]["INCOME"]);
                        $dataArr[$key]["RELIGION"] = FieldMap::getFieldLabel('religion',$value["RELIGION"]);
                        $cScoreObject = ProfileCompletionFactory::getInstance(null,null,$value["PROFILEID"]);
                        $dataArr[$key]["PCS"] = $cScoreObject->getProfileCompletionScore();
                        if($value["PHOTO_UPLOADED"] == ""){
                                $dataArr[$key]["PHOTO_UPLOADED"] = "N";
                        }
                        if($value["ACTIVATED_STATUS"] == ""){
                                $dataArr[$key]["ACTIVATED_STATUS"] = "N";
                        }
                        if($value["IS_PAID"] == ""){
                                $dataArr[$key]["IS_PAID"] = "N";
                        }
                }
                return $dataArr;
	}

	public function createCSVFromatData($params,$groupedData,$displayDate)
	{
		$csvData = '';
                $iterate = "PROFILEID,ENTRY_DT,MTONGUE,RELIGION,CASTE,OCCUPATION,EDU_LEVEL_NEW,INCOME,RELATION,GENDER,AGE,COUNTRY_RES,STATE,CITY_RES,CAMPAIGN,ADGROUP,SOURCE,MEDIUM,CAMPAIGN,ADNAME,KEYWORD,GROUPNAME,INCOMPLETE,ACTIVATED_STATUS,IS_QUALITY,CHANNEL,PHOTO_UPLOADED,PCS,IS_PAID";
                $csvData .= "Profile ID,Registration Date,Mother Tongue,Religion,Caste,Occupation,Highest Degree,Income,Posted by,Gender,Age,Country,State,City,Campaign ID,Adgroup ID,source ID,utm_medium,utm_campaign,utm_term,Keyword,Source Group Name,Incomplete (Y/N),Activated within 3 days (Y/H/N/D),Quality (Y/N),Device (Mobile/Desktop),Photo uploaded (Y- (0-20)/N),Profile Completion Score,Conversion (30 day)"."\n";
                $iterate = explode(",",$iterate);
		foreach($groupedData as $key=>$value)
		{
			foreach ($iterate as $val1)
			{
				$csvData .= $value[$val1].",";
			}
			$csvData .="\n";
		}
		return($csvData);
	}
}
