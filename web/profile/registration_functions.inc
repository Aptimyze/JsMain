<?php
	function createXmlTag($root_tag,$maintag,$subtag,$value,$attribute_name="",$attribute_val="")
	{
		global $xml;

		$root = $xml->getElementsByTagName($root_tag)->item(0);

		if($xml->getElementsByTagName($maintag)->item(0))
			$element = $xml->getElementsByTagName($maintag)->item(0);
		else
		{
			$element = $xml->createElement($maintag);
			$element = $root->appendChild($element);
			$element = $xml->getElementsByTagName($maintag)->item(0);
		}

		$subelement = $xml->createElement($subtag);
		$subelement = $element->appendChild($subelement);
		$subelement_val = $xml->createTextNode($value);
		$subelement->appendChild($subelement_val);

		if($attribute_name && $attribute_val)
		{
			$attr = $xml->createAttribute($attribute_name);
			$attr = $subelement->appendChild($attr);
			$attr_val = $xml->createTextNode($attribute_val);
			$attr_val = $attr->appendChild($attr_val);
		}
	}

	function loadMyXml($load_file, $common_from_file="")
	{
		global $xml,$xsl;

		$file_extension = substr($load_file,-3);

		$file_string = file_get_contents($load_file);

		if($common_from_file)
		{
			$common_file_string = file_get_contents($common_from_file);

			$pos = strpos($file_string,"\n");
			$initial_temp_string = substr($file_string,0,$pos+1);
			$final_temp_string = substr($file_string,$pos+1);

			$pos = strpos($final_temp_string,">");

			$initial_part = substr($final_temp_string,0,$pos+1);
			$last_part = substr($final_temp_string,$pos+1,strlen($final_temp_string));

			$final_file_string = $intial_temp_string.$initial_part.$common_file_string.$last_part;
		}
		else
			$final_file_string = $file_string;

		$final_file_string = trim_whitespace($final_file_string);

		if($file_extension == "xml")
			$xml->loadXML("$final_file_string");
		elseif($file_extension == "xsl")
			$xsl->loadXML("$final_file_string");
	}

	function trim_whitespace($string)
        {
		$string = str_replace("\t","",$string);
		$string = str_replace("\n\r","",$string);
		$string = str_replace("\r\n","",$string);
		$string = str_replace("\n","",$string);
		$string = str_replace("\r","",$string);

                $space_counter=0;
                $string_length = strlen($string);
                for($i=0;$i<$string_length;$i++)
                {
                        $character = substr($string,$i,1);
                        if($character == " ")
                                $space_counter++;
                        else
                                $space_counter = 0;

                        if($space_counter <= 1)
                                $return_string .= $character;
                }
                return $return_string;
        }

	function profile_percent_new($profileid)
	{
		global $xml, $proc;

		$percent = 0;

		$sql = "SELECT * FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
		$res = mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
                $row = mysql_fetch_array($res);

		$gender = $row['GENDER'];
		$phone_num = $row['PHONE_RES'];
                $mobile_num = $row['PHONE_MOB'];
		$diet = $row['DIET'];
		$drink = $row['DRINK'];
		$smoke = $row['SMOKE'];
		$body_type = $row['BTYPE'];
		$complexion = $row['COMPLEXION'];
		//$spoken_languages = $row['SPOKEN_LANGUAGES'];
		$family_type = $row['FAMILY_TYPE'];
		$family_status = $row['FAMILY_STATUS'];
		//$father_occ = $row['FATHER_OCC'];
		$father_occ = $row['FAMILY_BACK'];
		$mother_occ = $row['MOTHER_OCC'];
		$brothersT = $row['T_BROTHER'];
		$brothersM = $row['M_BROTHER'];
		$sistersT = $row['T_SISTER'];
		$sistersM = $row['M_SISTER'];
		//$live_with_parents = $row['LIVE_WITH_PARENTS'];
		$live_with_parents = $row['PARENT_CITY_SAME'];
		$about_family = $row['FAMILYINFO'];
		$about_education = $row['EDUCATION'];
		$work_status = $row['WORK_STATUS'];
		$about_work = $row['JOB_INFO'];
		$manglik = $row['MANGLIK'];
		$horoscope_match = $row['HOROSCOPE_MATCH'];
		$about_yourself = $row['YOURINFO'];
		$photo_display = $row['PHOTO_DISPLAY'];
			
		/* For Spoken Langauges */

		$sql_hobby = "SELECT * FROM newjs.JHOBBY WHERE PROFILEID='$profileid'";
                $res_hobby = mysql_query_decide($sql_hobby) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_hobby,"ShowErrTemplate");
		$row_hobby = mysql_fetch_array($res_hobby);
                $hobby = $row_hobby['HOBBY'];
                $hobbyArr = explode(",",$hobby);
		$sql_hobby_MAP = "SELECT SQL_CACHE * FROM newjs.HOBBIES";
                $res_hobby_MAP = mysql_query_decide($sql_hobby_MAP) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_hobby_MAP,"ShowErrTemplate");
                $map_array =array();
                while($row_hobby_MAP = mysql_fetch_array($res_hobby_MAP))
	        {
		      $value = $row_hobby_MAP['VALUE'];
                      $type  = $row_hobby_MAP['TYPE'];
		      $map_array[$value] = $type;
		}
		$i=0;
		foreach($hobbyArr as $key=>$val)
		{
                      $typeVal[$i] = $map_array[$val];
                      $i++;
	        }
		$typeVal_iniq = array_unique($typeVal);
                foreach($typeVal_iniq as $key1=>$val1)
		{
                       if($val1 =='LANGUAGE')
		       {
				$spoken_lang = 1;
		       }
		}

		/* End here for the Spoken Languages */

		$page1_completion_percent = 30;

		$percent += $page1_completion_percent;

		if($phone_num != "" && $mobile_num != "")
			$percent += 2;

		//$sql = "SELECT COUNT(*) AS COUNT FROM MOBILE_VERIFICATION_SMS WHERE MOBILE='$mobile_num'";
		//$res = mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		//$row = mysql_fetch_array($res);

                // ivrCheck for phone Validation
                $phoneCheck = getPhoneStatus($row);
		if($phoneCheck=='Y')
			$percent += 10;

		//---- About Me Start ---
		if($diet)
			$percent += 1;

		if($drink)
			$percent += 1;

		if($smoke)
			$percent += 1;

		if($body_type)
			$percent += 1;

		if($complexion)
			$percent += 1;

		if($spoken_languages )
			$percent +=1;

		//----Family Start -----
		if($family_type !=0)
			$percent += 1;
	
		if($family_status !=0)
			$percent += 1;	 

		if($father_occ)
			$percent += 1;

		if($mother_occ)
			$percent += 1;

		if($brothersT || $brothersM)
			$percent += 1;

		if($sistersT || $sistersM)
			$percent += 1;

		if($live_with_parents)
			$percent += 1;

		if($about_family){
                        if(strlen($about_family) < 100)
                                $percent += 1;
                        elseif(strlen($about_family) >= 100)
                                $percent += 3;
		}
	
		//--- Education Start ---	
		if($about_education)
			$percent += 4;

		if($work_status)
			$percent += 1;

		if($about_work)
			$percent += 4;

		//----Religion Start ----
		if($manglik)
			$percent += 1;

		if($horoscope_match)
			$percent += 1;

		$sql_horo = "SELECT COUNT(*) AS COUNT FROM newjs.ASTRO_DETAILS WHERE PROFILEID='$profileid'";
		$res_horo = mysql_query_decide($sql_horo) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_horo,"ShowErrTemplate");
		$row_horo = mysql_fetch_array($res_horo);
		if($row_horo['COUNT'] > 0)
			$horoscope_filled = 1;

		if($horoscope_filled)
			$percent += 5;

		//------ Christianity Start ------
		$sql_christian = "SELECT * FROM JP_CHRISTIAN WHERE PROFILEID='$profileid'";
		$res_christian = mysql_query_decide($sql_christian) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_christian,"ShowErrTemplate");
		$row_christian = mysql_fetch_array($res_christian);

		$diocese = $row_christian['DIOCESE'];
		$baptised = $row_christian['BAPTISED'];
		$read_bible = $row_christian['READ_BIBLE'];
		$offer_tithe = $row_christian['OFFER_TITHE'];
		$spread_gospel = $row_christian['SPREAD_GOSPEL'];

		if($diocese)
			$percent += 2;

		if($baptised)
			$percent += 2;

		if($read_bible)
			$percent += 1;

		if($offer_tithe)
			$percent += 1;

		if($spread_gospel)
			$percent += 1;

		//------ Islam Start ---------
		$sql_muslim = "SELECT * FROM JP_MUSLIM WHERE PROFILEID='$profileid'";
		$res_muslim = mysql_query_decide($sql_muslim) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_muslim,"ShowErrTemplate");
		$row_muslim = mysql_fetch_array($res_muslim);

		$namaz = $row_muslim['NAMAZ'];
		$zakat = $row_muslim['ZAKAT'];
		$fasting = $row_muslim['FASTING'];
		$umrah_hajj = $row_muslim['UMRAH_HAJJ'];
		$quran = $row_muslim['QURAN'];
		$sunnah_beard = $row_muslim['SUNNAH_BEARD'];
		$sunnah_cap = $row_muslim['SUNNAH_CAP'];

		if($namaz)
			$percent += 1;
		if($zakat)
			$percent += 1;
		if($fasting)
			$percent += 1;
		if($umrah_hajj)
			$percent += 1;
		if($quran)
			$percent += 1;
		if($sunah_beard)
			$percent += 1;
		if($sunnah_cap)
			$percent += 1;

		//------- Jainism Start ------
		$sql_jain = "SELECT SAMPRADAY FROM JP_JAIN WHERE PROFILEID='$profileid'";
		$res_jain = mysql_query_decide($sql_jain) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_jain,"ShowErrTemplate");
		$row_jain = mysql_fetch_array($res_jain);
		$sampraday = $row_jain['SAMPRADAY'];

		if($sampraday)
			$percent += 7;

		// ------ Sikh Start -------
		$sql_sikh = "SELECT * FROM JP_SIKH WHERE PROFILEID='$profileid'";
		$res_sikh = mysql_query_decide($sql_sikh) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_muslim,"ShowErrTemplate");
		$row_sikht = mysql_fetch_array($res_sikh);

		$amritdhari = $row_sikh['AMRITDHARI'];
		$cut_hair = $row_sikh['CUT_HAIR'];
		$trim_beard = $row_sikh['TRIM_BEARD'];
		$wear_turban = $row_sikh['WEAR_TURBAN'];
		$clean_shaven = $row_sikh['CLEAN_SHAVEN'];

		if($gender == "M")
		{
			if($amritdhari == "Y")
			{
				$percent += 7;
			}
			elseif($amritdhari == "N")
			{
				$percent += 3;
				if($cut_hair)
					$percent += 1;
				if($trim_beard)
					$percent += 1;
				if($wear_turban)
					$percent += 1;
				if($clean_shaven)
					$percent += 1;
			}
		}
		elseif($gender == "F")
		{
			if($amritdhari == "Y")
			{
				$percent += 7;
			}
			elseif($amritdhari == "N")
			{
				$percent += 3;
				if($cut_hair)
					$percent += 4;
			}
		}

		//----- Paris Start -----
		$sql_parsi = "SELECT * FROM newjs.JP_PARSI WHERE PROFILEID='$profileid'";
		$res_parsi = mysql_query_decide($sql_parsi) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_parsi,"ShowErrTemplate");
		$row_parsi = mysql_fetch_array($res_parsi);

		$zarathushtri = $row_parsi['ZARATHUSHTRI'];
		$parents_zarathushtri = $row_parsi['PARENTS_ZARATHUSHTRI'];

		if($zarathushtri)
			$percent += 4;
		if($parents_zarathushtri)
			$percent += 3;

		//----- Myself Start -----		

		//Symfony Photo Modification - start
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
		$mainphoto = SymfonyPictureFunctions::haveScreenedMainPhoto($profileid);

			if($mainphoto!=NULL)
		//Symfony Photo Modification - end
		{
			if($photo_display == "A")
				$percent += 16;
			else
				$percent += 8;
			
		}

		if($about_yourself)
		{
			if(strlen($about_yourself) < 200)
				$percent += 5;
			elseif(strlen($about_yourself) >= 200)
				$percent += 10;
		}

		// return total percent Value
		return $percent;
	}

	function validate_username($username,$email='')
	{
		$flag = 0;
		//sleep(88);
		$username = trim($username);
		$ch=$username[0];
		//should start with a character
		if((($ch < "a" || "z" < $ch) && ($ch < "A" || "Z" < $ch) ))
			$flag = 1;
		//should be of atleast 8 characters
		elseif(strlen($username) < 6)
			$flag = 2;
		//should not contain any obscene words
		elseif(check_obscene_word($username))
			$flag = 3;
		//should not contain more than 5 consecutive integers
		elseif(check_for_continuous_numerics($username))
			$flag = 4;
		//should not contain some mis-used words.
		elseif(check_for_intelligent_usage($username))
			$flag = 5;
		//should not contain any special characters apart from _ (underscore)
		elseif(isvalid_username($username))
			$flag = 6;
		//should be available.
		elseif(!valid_new_username($username) || check_username($username) || check_username_jprofile($username))
			$flag = 7;
		//should not equal to the email id
		elseif(check_same_username_email($username,$email))
			$flag = 8;
		return $flag;
	}

	function get_profile_active_status($email)
	{
		$sql = "SELECT PROFILEID FROM newjs.OLDEMAIL WHERE OLD_EMAIL = '$email'";
		$res = mysql_query_decide($sql) or logError("error",$sql);
		if($row = mysql_fetch_array($res))
			$profileid = $row['PROFILEID'];

		if($profileid)
			$sql = "SELECT ACTIVATED FROM newjs.JPROFILE WHERE PROFILEID = '$profileid'";
		else
			$sql = "SELECT ACTIVATED FROM newjs.JPROFILE WHERE EMAIL = '$email'";

		$res = mysql_query_decide($sql) or logError("error",$sql);
		$row = mysql_fetch_array($res);
		if($email)
		{
			$sql="select PROFILEID,USERNAME,EMAIL from newjs.JSARCHIVED where EMAIL='$email' and STATUS='Y'";
                        $res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                        if($rows=mysql_fetch_assoc($res))
			{
				return 'JA';
			}
		}
		return $row['ACTIVATED'];
	}
   /**
     * This function is for updating about_yourself field and is used in sugarcrm registration functions.
     *
     */	 
	function update_about_yourself($about_yourself,$profileid,$db,$record_id="",$partitionLeads="sugarcrm.leads",$partitionLeadsCstm="sugarcrm.leads_cstm",$process='1'){
			include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
			$jprofileUpdateObj = JProfileUpdateLib::getInstance();
			$length=strlen($about_yourself);
			$now = date("Y-m-d G:i:s");
				if($length <= '99')
				{
					$arrFields['YOURINFO']=$about_yourself;
					$arrFields['INCOMPLETE']='Y';
					$arrFields['MOD_DT']=$now;
//					$sql_page2="UPDATE newjs.JPROFILE SET YOURINFO='$about_yourself',INCOMPLETE='Y',MOD_DT='$now' WHERE PROFILEID=$profileid";
//					mysql_query_decide($sql_page2) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_page2,"ShowErrTemplate");
					$jprofileUpdateObj->editJPROFILE($arrFields,$profileid,"PROFILEID");
				}
				elseif($length >= '100')
				{
					$arrFields['YOURINFO']=$about_yourself;
					$arrFields['INCOMPLETE']='N';
					$arrFields['ENTRY_DT']=$now;
					$arrFields['MOD_DT']=$now;
//					$sql_page2="UPDATE newjs.JPROFILE SET YOURINFO='$about_yourself',INCOMPLETE='N',ENTRY_DT='$now',MOD_DT='$now' WHERE PROFILEID=$profileid";
//					mysql_query_decide($sql_page2) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_page2,"ShowErrTemplate");
					$jprofileUpdateObj->editJPROFILE($arrFields,$profileid,"PROFILEID");

					//Fto state change after completion of page2
					$sql_incomp="SELECT count(*) CNT FROM MIS.INCOMPLETE_SCREENING where PROFILEID=$profileid";
					$res=mysql_query_decide($sql_incomp) or logError("Due to some temporary problem your request could not be processed. Please try after some     time.",$sql_incomp,"ShowErrTemplate");
					$row=mysql_fetch_row($res);
					$incomp=$row[0];
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
				/* Tracking Query for the Reg Count */
				$sql = "UPDATE MIS.REG_COUNT SET PAGE2='Y' WHERE PROFILEID='$profileid'";
				mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
				/* Ends Here */
			

			$sql_pg="UPDATE MIS.REG_LEAD SET INCOMPLETE='N' WHERE EMAIL='$email'";
			mysql_query_decide($sql_pg) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_pg,"ShowErrTemplate");
				if($record_id){
					if($process!='1')
                                        {
                                                include_once($_SERVER['DOCUMENT_ROOT']."/sugarcrm/include/utils/systemProcessUsersConfig.php");
                                                $processUserId=$process_user_mapping[$process];
                                                if(!$processUserId)
                                                        $processUserId=1;
                                        }
                                        else
                                                $processUserId='1';
					$nowDate =date("Y-m-d H:i:s");
					$sql_lead="UPDATE $partitionLeads,$partitionLeadsCstm SET `status`='26',`date_modified`='$nowDate',disposition_c='30',modified_user_id='$processUserId' where id=id_c AND id='$record_id'";
					mysql_query_decide($sql_lead);

				}
				}
	}	
?>
