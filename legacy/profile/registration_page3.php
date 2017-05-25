<?php
$start_tm=microtime(true);
//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
$zipIt = 1;
if($zipIt && !$dont_zip_now && $dont_zip_more!=1)
{
        $dont_zip_more=1;
        ob_start("ob_gzhandler");
}

$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path."/profile/connect.inc");
include_once($path."/profile/arrays.php");
include_once($path."/profile/screening_functions.php");
include_once($path."/profile/cuafunction.php");
include_once($path."/profile/hits.php");
include_once(JsConstants::$docRoot."/commonFiles/flag.php");
include_once($path."/profile/registration_functions.inc");

include_once($path."/classes/Jpartner.class.php");
include_once($path."/classes/authentication.class.php");
include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");

$db = connect_db();
//$IMG_URL = $SITE_URL."/profile/images/registration_revamp_new/";
//$smarty->assign("IMG_URL",$IMG_URL);
/* Changes Done for the SEM Track #20 */

$sem_url=$_SERVER['HTTP_HOST'];
$sem_url_1=explode(".",$sem_url);
if($sem_url_1[0]=='www')
{
        unset($sem_url_1[0]);
        $sem_url=implode('.',$sem_url_1);
}

$sql_sem="SELECT GA_CODE FROM MIS.SEM_GACODE WHERE URL='$sem_url' AND ACTIVE='Y'";
$res_sem=mysql_query_decide($sql_sem) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$res_sem,"ShowErrTemplate");
if($row_sem=mysql_fetch_array($res_sem))
{
        $pixel_code=$row_sem['GA_CODE'];
        $smarty->assign('SEM_PIXEL',$pixel_code);
	$smarty->assign('sem','1');
        $checksum_1=$checksum;
}


$smarty->assign("checksum",$checksum);

$now = date("Y-m-d G:i:s");
if($profileid)
{
	//Modified by Jaiswal to prefill values received from sugarcrm db
	if($record_id) {
        $smarty->assign("RECORD_ID", $record_id);
		$sql="select USERNAME,SCREENING,DTOFBIRTH,COUNTRY_RES,AGE,CASTE,GENDER,MTONGUE,RELIGION from newjs.JPROFILE where activatedKey=1 and PROFILEID='$profileid'";
    }
	else	
		$sql="select USERNAME,SCREENING,RELIGION,COUNTRY_RES,AGE,CASTE,MTONGUE,GENDER from newjs.JPROFILE where activatedKey=1 and PROFILEID='$profileid'";
	$res=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
	$row=mysql_fetch_array($res);
	$current_screening_flag = $row['SCREENING'];
	$USERNAME= $row['USERNAME'];
	$religion=$row['RELIGION'];
	if($record_id){
		$bdate_arr=explode("-",$row['DTOFBIRTH']);
		$year=$bdate_arr[0];
		$month=$bdate_arr[1];
		$day=$bdate_arr[2];
	}
	$country_residence=$row['COUNTRY_RES'];
	$CASTE=$row['CASTE'];
	$MTONGUE=$row['MTONGUE'];
	$gender=$row['GENDER'];
	if($gender=='M')
		$genderLabel="Male";
	else
		$genderLabel="Female";
	$age=$row["AGE"];
}
if($record_id && !$submit_pg3){
	$sql_sugar="select no_of_brothers_c,no_of_brothers_married_c,no_of_sisters_c, no_of_sisters_married_c, father_occupation_c, subcaste_c, manglik_c, gothra_c  from sugarcrm.leads_cstm where id_c='$record_id'";
	$res_sugar=mysql_query_decide($sql_sugar);
	$sugar_row=mysql_fetch_assoc($res_sugar);
	if($sugar_row){
        $gotra=$sugar_row['gothra_c'];
        $manglik=($sugar_row['manglik_c'] == 'Y')?'M':$sugar_row['manglik_c'];
        $subcaste=$sugar_row['subcaste_c'];
		$father_occupation=$sugar_row['father_occupation_c'];
		$brothers=$sugar_row['no_of_brothers_c'];
		$married_brothers=$sugar_row['no_of_brothers_married_c'];
		$sisters=$sugar_row['no_of_sisters_c'];
		$married_sisters=$sugar_row['no_of_sisters_married_c'];
        $smarty->assign("manglik", $manglik);
        $smarty->assign("gotra", $gotra);
        $smarty->assign("subcaste", $subcaste);
		$smarty->assign("brothers",$brothers);
		$smarty->assign("married_brothers",$married_brothers);
		$smarty->assign("sisters",$sisters);
		$smarty->assign("married_sisters",$married_sisters);
	}
}
//Changes by Jaiswal ends here
$smarty->assign("YEAR_OF_BIRTH",$year);
$smarty->assign("MONTH_OF_BIRTH",$month);
$smarty->assign("DAY_OF_BIRTH",$day);
$smarty->assign("COUNTRY_RESI",$country_residence);
$smarty->assign("AGE",$age);
$smarty->assign("MTONGUE",$MTONGUE);
$smarty->assign("CASTE",$CASTE);

/****Tracking purpose********/
$smarty->assign("USERNAME",$USERNAME);
$smarty->assign("TIEUP_SOURCE",$tieup_source);

if($skip_to_next_page_edu)
{
	if($sem){
		logout(); /* Logout from current sem domain */
		$SEM_URL="http://www.jeevansathi.com";
		header("Location:$SEM_URL/profile/registration_page4.php?checksum=$checksum_1&sem=1&groupname=$groupname");die;
	}
	else
	{
		include("registration_page4.php");
		die;
	}
}


/*******Ends here***********/
if($submit_pg3 || $submit_pg3_x || $submit_pg3_y)
{
	$is_error=0;
	if($name_of_user && !ereg("^[a-zA-Z\.\, ]+$",$name_of_user))
	{
		$smarty->assign("name_of_user_Error",1);
		$is_error=1;
	}
	if($is_error==0)
	{
		
		if($profileid)
		{
			if($name_of_user)
			{
				if($gender == "M")
					$name_of_user = "Mr.".$name_of_user;
				elseif($gender == "F")
					$name_of_user = "Ms.".$name_of_user;
				$sql_name = "REPLACE INTO incentive.NAME_OF_USER(PROFILEID,NAME) VALUES('$profileid','".addslashes(stripslashes($name_of_user))."')";
				mysql_query_decide($sql_name) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_name,"ShowErrTemplate");


			}
		}	
		$sql_upd_jp = "UPDATE newjs.JPROFILE SET ";

		// Hindu Section starts from here

		if($subcaste)
		{
			$jprofile_update[] = "SUBCASTE='".addslashes(stripslashes(mysql_real_escape_string($subcaste)))."'";
			$current_screening_flag = removeFlag("SUBCASTE",$current_screening_flag);
		}
		if($gotra)
		{
			$jprofile_update[] = "GOTHRA='".addslashes(stripslashes(mysql_real_escape_string($gotra)))."'";
			$current_screening_flag = removeFlag("GOTHRA",$current_screening_flag);
		}
		if($ancestral_origin)
		{
			$jprofile_update[] = "ANCESTRAL_ORIGIN='".addslashes(stripslashes(mysql_real_escape_string($ancestral_origin)))."'";
			$current_screening_flag = removeFlag("ANCESTRAL_ORIGIN",$current_screening_flag);
		}
		if($manglik)
		{
			$jprofile_update[] = "MANGLIK='".$manglik."'";
		}
		if($nakshatra)
		{
				if($nakshatra == 1)
					$nakshatra = "Don't Know";
				elseif($nakshatra == 2)
					$nakshatra = "Anuradha/Anusham/Anizham";
				elseif($nakshatra == 3)
					$nakshatra = "Ardra/Thiruvathira";
				elseif($nakshatra == 4)
					$nakshatra = "Ashlesha/Ayilyam";
				elseif($nakshatra == 5)
					$nakshatra = "Ashwini/Ashwathi";
				elseif($nakshatra == 6)
					$nakshatra = "Bharani";
				elseif($nakshatra == 7)
					$nakshatra = "Chitra/Chitha";
				elseif($nakshatra == 8)
					$nakshatra = "Dhanista/Avittam";
				elseif($nakshatra == 9)
					$nakshatra = "Hastha/Atham";
				elseif($nakshatra == 10)
					$nakshatra = "Jyesta/Kettai";
				elseif($nakshatra == 11)
					$nakshatra = "Krithika/Karthika";
				elseif($nakshatra == 12)
					$nakshatra = "Makha/Magam";
				elseif($nakshatra == 13)
					$nakshatra = "Moolam/Moola";
				elseif($nakshatra == 14)
					$nakshatra = "Mrigasira/Makayiram";
				elseif($nakshatra == 15)
					$nakshatra = "Poorvabadrapada/Puratathi";
				elseif($nakshatra == 16)
					$nakshatra = "Poorvashada/Pooradam";
				elseif($nakshatra == 17)
					$nakshatra = "Poorvapalguni/Puram/Pubbhe";
				elseif($nakshatra == 18)
					$nakshatra = "Punarvasu/Punarpusam";
				elseif($nakshatra == 19)
					$nakshatra = "Pushya/Poosam/Pooyam";
				elseif($nakshatra == 20)
					$nakshatra = "Revathi";
				elseif($nakshatra == 21)
					$nakshatra = "Rohini";
				elseif($nakshatra == 22)
					$nakshatra = "Shatataraka/Sadayam/Sadabist";
				elseif($nakshatra == 23)
					$nakshatra = "Shravan/Thiruvonam";
				elseif($nakshatra == 24)
					$nakshatra = "Swati/Chothi";
				elseif($nakshatra == 25)
					$nakshatra = "Uttrabadrapada/Uthratadhi";
				elseif($nakshatra == 26)
					$nakshatra = "Uttarapalguni/Uthram";
				elseif($nakshatra == 27)
					$nakshatra = "Uttarashada/Uthradam";
				elseif($nakshatra == 28)
					$nakshatra = "Vishaka/Vishakam";
				$jprofile_update[] = "NAKSHATRA='".addslashes($nakshatra)."'";
		}
		if($rashi)
		{
			$jprofile_update[] = "RASHI='".$rashi."'";
		}
		if($horoscope_match)
		{
			$jprofile_update[] = "HOROSCOPE_MATCH='".$horoscope_match."'";
		}
		
		// Common Section Starts from here for All Religion

		if($family_values)
		{
			$jprofile_update[] = "FAMILY_VALUES='".$family_values."'";
		}
		if($family_type)
		{
		       $jprofile_update[] = "FAMILY_TYPE='".$family_type."'";
		}
		if($family_status)
		{
			$jprofile_update[] = "FAMILY_STATUS='".$family_status."'";
		}
		if($father_occupation)
		{
			$jprofile_update[] = "FAMILY_BACK='".$father_occupation."'";
		}
		if($mother_occupation)
		{
			 $jprofile_update[] = "MOTHER_OCC='".$mother_occupation."'";
		}
		if($brothers!='')
		{
			$jprofile_update[] = "T_BROTHER='".$brothers."'";
		}
		if($married_brothers!='')
		{
			$jprofile_update[] = "M_BROTHER='".$married_brothers."'";
		}
		if($sisters!='')
		{
			$jprofile_update[] = "T_SISTER='".$sisters."'";
		}
		if($married_sisters!='')
		{
			$jprofile_update[] = "M_SISTER='".$married_sisters."'";
		}
		if($live_with_parents)
		{
			$jprofile_update[] = "PARENT_CITY_SAME='".$live_with_parents."'";
		}
		if($about_family)
		{
			$jprofile_update[] = "FAMILYINFO='".mysql_real_escape_string(stripslashes($about_family))."'";
			$current_screening_flag = removeFlag("FAMILYINFO",$current_screening_flag);
		}

		$jprofile_update[] = "MOD_DT='".$now."'";

		if(count($jprofile_update) > 0)
		{
			if(strtolower($tieup_source)=='ofl_prof')
	                {
			      $current_screening_flag="1099511627775";
		        }
			$jprofile_update[]="SCREENING=$current_screening_flag";
			$jprofile_update_str = @implode(", ",$jprofile_update);
			$sql_upd_jp .= $jprofile_update_str." WHERE PROFILEID='$profileid'  and activatedKey=1";
			mysql_query_decide($sql_upd_jp) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_upd_jp,"ShowErrTemplate");
		}
		
		unset($jprofile_update);

			// Muslim section starts from here 

			if($maththab)
			{
				$fields[] = "MATHTHAB";
				if($maththab == "150")
					$maththab ="8";
				elseif($maththab == "258")
					$maththab ="5";
				elseif($maththab == "259")
					$maththab ="6";
				elseif($maththab == "260")
					$maththab ="7";
				elseif($maththab == "254")
					$maththab ="1";
				elseif($maththab == "255")
					$maththab ="2";
				elseif($maththab == "256")
					$maththab ="3";
				elseif($maththab == "257")
					$maththab ="4";
				$values[] = $maththab;
			}
			if($speak_urdu)
			{
				$sql="UPDATE newjs.JPROFILE SET SPEAK_URDU='$speak_urdu',MOD_DT='$now' WHERE PROFILEID='$profileid' and activatedKey=1";
				$sql="UPDATE newjs.JPROFILE SET SPEAK_URDU='$speak_urdu' WHERE PROFILEID='$profileid' and activatedKey=1";
				mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			}
			if($namaz)
			{
				$fields[] = "NAMAZ";
				$values[] = $namaz;
			}
			if($zakat)
			{
				$fields[] = "ZAKAT";
				$values[] = $zakat;
			}
			if($fasting)
			{
				$fields[] = "FASTING";
				$values[] = $fasting;
			}
			if($umrah_hajj)
			{
				$fields[] = "UMRAH_HAJJ";
				$values[] = $umrah_hajj;
			}
			if($quran)
			{
				$fields[] = "QURAN";
				$values[] = $quran;
			}
			if($sunnah_beard)
			{
				$fields[] = "SUNNAH_BEARD";
				$values[] = $sunnah_beard;
			}
			if($sunnah_cap)
			{
				$fields[] = "SUNNAH_CAP";
				$values[] = $sunnah_cap;
			}
			if($hijab)
			{
				$fields[] = "HIJAB";
				$values[] = $hijab;
			}
			if($hijab_marriage)
			{
				$fields[] = "HIJAB_MARRIAGE";
				$values[] = $hijab_marriage;
			}
			if($working_marriage)
			{
				$fields[] = "WORKING_MARRIAGE";
				$values[] = $working_marriage;
			}
			if(count($fields) > 0)
			{
				$fields[] = "PROFILEID";
				$values[] = $profileid;
				
				$fields_str = @implode(",",$fields);
				$values_str = "'".@implode("','",$values)."'";

				$sql = "INSERT ignore INTO newjs.JP_MUSLIM($fields_str) VALUES($values_str)";
				mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

				unset($fields);
				unset($values);
			}

			// Section for Sikh Starts from here

			if($amritdhari)
			{
				$fields[] = "AMRITDHARI";
				$values[] = $amritdhari;
			}
			if($cut_hair)
			{
				$fields[] = "CUT_HAIR";
				$values[] = $cut_hair;
			}
			if($trim_beard)
			{
				$fields[] = "TRIM_BEARD";
				$values[] = $trim_beard;
			}
			if($wear_turban)
			{
				$fields[] = "WEAR_TURBAN";
				$values[] = $wear_turban;
			}
			if($clean_shaven)
			{
				$fields[] = "CLEAN_SHAVEN";
				$values[] = $clean_shaven;
			}

			if(count($fields) > 0)
			{
				$fields[] = "PROFILEID";
				$values[] = $profileid;

				$fields_str = @implode(",",$fields);
				$values_str = "'".@implode("','",$values)."'";

				$sql = "REPLACE INTO newjs.JP_SIKH($fields_str) VALUES($values_str)";
				mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

				unset($fields);
				unset($values);
			}
			
			// Section for the Jain starts from here

			if($sampraday)
			{
				$sql = "REPLACE INTO newjs.JP_JAIN(PROFILEID,SAMPRADAY) VALUES('$profileid','$sampraday')";
				mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			}

			// Section from the Parsi starts from here

			if($zarathushtri)
			{
				$fields[] = "ZARATHUSHTRI";
				$values[] = $zarathushtri;
			}
			if($parents_zarathushtri)
			{
				$fields[] = "PARENTS_ZARATHUSHTRI";
				$values[] = $parents_zarathushtri;
			}
			if(count($fields) > 0)
			{
				$fields[] = "PROFILEID";
				$values[] = $profileid;
			
				$fields_str = @implode(",",$fields);
				$values_str = "'".@implode("','",$values)."'";

				$sql = "REPLACE INTO newjs.JP_PARSI($fields_str) VALUES($values_str)";
				mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				unset($fields);
				unset($values);
			}

			//Section for the Christain starts from here

			if($diocese)
			{
				$fields[] = "DIOCESE";
				$values[] = addslashes(stripslashes(mysql_real_escape_string($diocese)));
				$current_screening_flag = removeFlag("GOTHRA",$current_screening_flag);
			}
			if($baptised)
			{
				$fields[] = "BAPTISED";
				$values[] = $baptised;
			}
			if($read_bible)
			{
				$fields[] = "READ_BIBLE";
				$values[] = $read_bible;
			}
			if($offer_tithe)
			{
				$fields[] = "OFFER_TITHE";
				$values[] = $offer_tithe;
			}
			if($spreading_gospel)
			{
				$fields[] = "SPREADING_GOSPEL";
				$values[] = $spreading_gospel;
			}

			if(count($fields) > 0)
			{
				$fields[] = "PROFILEID";
				$values[] = $profileid;
				
				$fields_str = @implode(",",$fields);
				$values_str = "'".@implode("','",$values)."'";

				$sql = "INSERT ignore INTO newjs.JP_CHRISTIAN($fields_str) VALUES($values_str)";
				mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

				unset($fields);
				unset($values);
			}
		
		/* Tracking Query for the Reg Count */
		$sql = "UPDATE MIS.REG_COUNT SET PAGE3='Y' WHERE PROFILEID='$profileid'";
		mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		/* Ends Here */
		
		if($pixel_code)
                {
			logout(); /* Logout from current sem domain */
                        header("Location: $SEM_URL/profile/registration_page4.php?checksum=$checksum_1&sem=1&groupname=$groupname");
                }
		else
		{
			//include 4th page.
			include_once("registration_page4.php");
			exit;
		}
	}
	else
	{
		$smarty->assign("name_of_user",$name_of_user);
		$smarty->assign("gotra",$gotra);
		$smarty->assign("subcaste",$subcaste);
		$smarty->assign("manglik",$manglik);
		$smarty->assign("sampraday",$sampraday);
		$smarty->assign("horoscope_match",$horoscope_match);
		$smarty->assign("horo",$horo);
		$smarty->assign("diocese",$diocese);
		$smarty->assign("baptised",$baptised);
		$smarty->assign("read_bible",$read_bible);
		$smarty->assign("offer_tithe",$offer_tithe);
		$smarty->assign("spreading_gospel",$spreading_gospel);
		$smarty->assign("maththab",$maththab);
		$smarty->assign("speak_urdu",$speak_urdu);
		$smarty->assign("namaz",$namaz);
		$smarty->assign("zakat",$zakat);
		$smarty->assign("fasting",$fasting);
		$smarty->assign("umrah_hajj",$umrah_hajj);
		$smarty->assign("quran",$quran);
		$smarty->assign("sunnah_beard",$sunnah_beard);
		$smarty->assign("sunnah_cap",$sunnah_cap);
		$smarty->assign("working_marriage",$working_marriage);
		$smarty->assign("hijab",$hijab);
		$smarty->assign("willing_hijab",$willing_hijab);
		$smarty->assign("zarathushtri",$zarathushtri);
		$smarty->assign("parents_zarathushtri",$parents_zarathushtri);
		$smarty->assign("amritdhari",$amritdhari);
		$smarty->assign("cut_hair",$cut_hair);
		$smarty->assign("trim_beard",$trim_beard);
		$smarty->assign("wear_turban",$wear_turban);
		$smarty->assign("clean_shaven",$clean_shaven);
		$smarty->assign("family_values",$family_values);
		$smarty->assign("family_type",$family_type);
		$smarty->assign("family_status",$family_status);
		$smarty->assign("brothers",$brothers);
		$smarty->assign("married_brothers",$married_brothers);
		$smarty->assign("sisters",$sisters);
		$smarty->assign("married_sisters",$married_sisters);
		$smarty->assign("live_with_parents",$live_with_parents);
		$smarty->assign("about_family",$about_family);
		$smarty->assign("wordcount",$wordcount);
	}
}
else
{
        $sql = "INSERT INTO newjs.ASTRO_PULLING_REQUEST (PROFILEID,ENTRY_DT,PENDING,TYPE) VALUES('$profileid',NOW(),'Y','E')";
        mysql_query_decide($sql) or logError("error",$sql);
	function pixelcode($VAR)
	{
		  if($VAR)
		  {
				 $sql="SELECT SQL_CACHE PIXELCODE FROM MIS.PIXELCODE WHERE GROUPNAME='$VAR'";
				 $res=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
				 $row=mysql_fetch_array($res);
				 return $row[PIXELCODE];
		  }
	}
	if($groupname)
		   $VAR = $groupname;
	elseif($GROUPNAME)
		   $VAR = $GROUPNAME;
	elseif($SOURCE)
		   $VAR = $SOURCE;
	$pixelcode = pixelcode($VAR);

	$sql="SELECT LABEL FROM newjs.CITY_NEW WHERE VALUE='$city_res'";
	$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$row=mysql_fetch_array($res);
	$city=$row[LABEL];
	$pixelcode=str_replace('~$CITY`',$city,$pixelcode);
	$pixelcode=str_replace('~$USERNAME`',$USERNAME,$pixelcode);
	$pixelcode=str_replace('~$AGE`',$age,$pixelcode);
	$pixelcode=str_replace('~$GENDER`',$genderLabel,$pixelcode);
	$pixelcode=str_replace('~$PROFILEID`',$profileid,$pixelcode);
	$pixelcode=str_replace('~$ADNETWORK1`',$adnetwork1,$pixelcode);
	$smarty->assign("pixelcode",$pixelcode);
	$smarty->assign("reg_comp_frm_ggl",$reg1_comp_frm_ggl);
	$smarty->assign("reg_comp_frm_ggl_nri",$reg1_comp_frm_ggl_nri);
	$smarty->assign("REGISTRATION",$smarty->fetch("registration_tracking.htm"));
}	
			/* Drop down Popualtion Starts Here */

			$option_string="";
			$sql = "SELECT SQL_CACHE LABEL, VALUE FROM FAMILY_BACK ORDER BY SORTBY";
			$res = mysql_query_decide($sql) or logError("error",$sql);
			while($row = mysql_fetch_array($res))
			{
				if($father_occupation == $row['VALUE'])
				      	$option_string.= "<option value=\"$row[VALUE]\" selected=\"yes\">$row[LABEL]</option>";
				else
				       $option_string.= "<option value=\"$row[VALUE]\">$row[LABEL]</option>";
			}
			$smarty->assign('FATHER_OCCUPATION',$option_string);
			
			$option_string="";
			$sql = "SELECT SQL_CACHE LABEL, VALUE FROM MOTHER_OCC ORDER BY SORTBY";
			$res = mysql_query_decide($sql) or logError("error",$sql);
			while($row = mysql_fetch_array($res))
			{
				if($mother_occupation == $row['VALUE'])
				      	$option_string.= "<option value=\"$row[VALUE]\" selected=\"yes\">$row[LABEL]</option>";
				else
				       $option_string.= "<option value=\"$row[VALUE]\">$row[LABEL]</option>";
			}
			$smarty->assign('MOTHER_OCCUPATION',$option_string);

			//apply condition for KANNAD later.
			$option_string="";
			$sql = "SELECT SQL_CACHE OTHERS, KANNAD, VALUE FROM newjs.NAKSHATRA";
			$res = mysql_query_decide($sql) or logError("error",$sql);
			while($row = mysql_fetch_array($res))
			{
				if($nakshatra == $row['VALUE'])
				      	$option_string.= "<option value=\"$row[VALUE]\" selected=\"yes\">$row[OTHERS]</option>";
				else
				       $option_string.= "<option value=\"$row[VALUE]\">$row[OTHERS]</option>";
			}
			$smarty->assign('NAKSHATRA',$option_string);

			$option_string="";
			$sql = "SELECT SQL_CACHE LABEL,VALUE FROM newjs.RASHI";
			$res = mysql_query_decide($sql) or logError("error",$sql);
			while($row = mysql_fetch_array($res))
			{
				if($rashi == $row['VALUE'])
				      	$option_string.= "<option value=\"$row[VALUE]\" selected=\"yes\">$row[LABEL]</option>";
				else
				       $option_string.= "<option value=\"$row[VALUE]\">$row[LABEL]</option>";

			}
			$smarty->assign('RASHI',$option_string);

			if($caste=='243')
				$smarty->assign('HIDE_MATHTHAB','1');

			if($caste=='175')
				$smarty->assign('SHOW_SAMPRADAY','1');
			if($caste && $caste!='243')
			{
				$option_string="";
				$sql="SELECT LABEL,VALUE FROM newjs.CASTE WHERE PARENT='$caste'";
				$res = mysql_query_decide($sql) or logError("error",$sql);
				while($row = mysql_fetch_array($res))
				{
					if($maththab == $row['VALUE'])
						$option_string.= "<option value=\"$row[VALUE]\" selected=\"yes\">$row[LABEL]</option>";
					else
						$option_string.= "<option value=\"$row[VALUE]\">$row[LABEL]</option>";
				}
				$smarty->assign('MATHTHAB',$option_string);
			}

			/* Drop Down Population Ends Here */
			
			$smarty->assign("GENDER",$gender);

			/* Religion Assignment */
			if($religion == '1')
				$smarty->assign("HINDU","1");
			else if($religion == '2' && $gender=='M')
				$smarty->assign("MUSLIM_BOY","1");
			else if($religion == '2' && $gender=='F')
				$smarty->assign("MUSLIM_GIRL","1");
			else if($religion == '3')
				$smarty->assign("CHRISTIAN","1");
			else if($religion == '4')
				$smarty->assign("SIKH","1");
			else if($religion == '5')
				$smarty->assign("PARSI","1");
			else if($religion == '6')
				$smarty->assign("JEWISH","1");
			else if($religion == '7')
				$smarty->assign("BUDDHIST","1");
			else if($religion == '9')
				$smarty->assign("JAIN","1");
			/* Ends Here */

			$smarty->assign('p_percent',profile_percent_new($profileid));			
		$smarty->assign("IS_FTO_LIVE",FTOLiveFlags::IS_FTO_LIVE);
		 if(!isset($_COOKIE["ISEARCH"]))
			             $smarty->assign('ISEARCH_COOKIE_NOTSET','1');
/* Tracking Contact Center, as per Mantis 4724 Starts here */
		$end_time=microtime(true)-$start_tm;
		$smarty->assign("TRACK_FOOT",BrijjTrackingHelper::getTailTrackJs($end_time,true,2,"http://track.99acres.com/images/zero.gif","JSREGPAGE3URL"));
		/* Ends Here */			             
			$smarty->display("registration_pg3.htm");

			// flush the buffer
			if($zipIt && !$dont_zip_now)
			ob_end_flush();
?>
