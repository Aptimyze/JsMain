<?php
/***********************************************************************************************
 *	FILENAME	: screen_new.php
 *	DESCRIPTION	: Provides new profiles dynamically to the screening user.
 *	CREATED BY	: Ankit Aggarwal
 *	CREATE DATE	: 4th July 2009
 *
 ***********************************************************************************************/
// Disable caching of the current document:
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Pragma: no-cache');
include ("time1.php");

global $screeningRep;
$screeningRep = false;

include ("connect.inc");
include(JsConstants::$docRoot."/commonFiles/flag.php");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once ("../profile/arrays.php");
include_once ("../profile/connect_functions.inc");
//include_once("../profile/contact.inc");
include_once ("../profile/screening_functions.php");
include ("../profile/functions.inc");
include ("../billing/comfunc_sums.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/authentication.class.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
use MessageQueues as MQ;
$protect_obj = new protect;
global $screen_time;
global $FLAGS_VAL;
$dbObj = new newjs_OBSCENE_WORDS();
global $obscene;
$obscene = $dbObj->getObsceneWord();

$nameOfUserObj = new NameOfUser;

if (authenticated($cid)) {
	$user = getname($cid);
	//Memcache functionality added by Vibhor for avoiding users to refresh the page using F5
	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		include_once ("../../lib/model/lib/JsMemcache.class.php");
		$memcacheObj = new JsMemcache;
		$key = "PROF_SCREEN_USER_" . $user;
		if ($memcacheObj->get($key)) {
			exit("Please refresh after 5 seconds.");
		} else $memcacheObj->set( $key, 5,2);
		unset($memcacheObj);
	}
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		include_once ("../../lib/model/lib/JsMemcache.class.php");
		$memcacheObj = new JsMemcache;
		$key = "PROF_SCREENED_USER_" . $user;
		if ($memcacheObj->get($key)) {
			$memcacheObj->set( $key, 2,2);
			exit("Please dont click on submit button again and again.");
		} else $memcacheObj->set($key, 2,2);
		unset($memcacheObj);
	}
	
        // VA Whitelisting
//        if($pid && !is_numeric($pid)){
//            $http_msg=print_r($_SERVER,true);
//            mail("ankitshukla125@gmail.com","Screen_new pid whitelisting","PID :$pid:$http_msg");
//        }
            
	if ($Submit || $Submit1) {
		unsetMemcache5Sec($user);
		$check = screening_recheck($pid);
		if ($check == 1) {
			$Submit = 0;
			$Submit1 = 0;
			$smarty->assign("RESCREEN", 'Y');
		} else delete_temp_screening($pid);
	}
	if ($Submit) {
		
		
		$PROFILECHECKSUM = md5($pid) . "i" . $pid;
			$echecksum = $protect_obj->js_encrypt($PROFILECHECKSUM,$to);
		//Setting the value in memcache, that will be checked in authentication function while user is online.
		set_memcache_value($pid);
		$do_gender_related_changes = 0;
		$email_ev = 1;
		$act = 1;
		$user_id_flag = 0;
		if ($_POST["GENDER"] == "") {
			$critical_message = print_r($_POST, true);
			//mail("ankit.aggarwal@jeevansathi.com","Screening Post Vars","$critical_message");
			
		} else $gender = $_POST["GENDER"];
		$sql = "SELECT SERVICE_MESSAGES,USERNAME, SUBSCRIPTION,DTOFBIRTH, GENDER, EMAIL, ACTIVATED, SCREENING, PREACTIVATED,COUNTRY_RES,CITY_RES,SOURCE,STD from newjs.JPROFILE where activatedKey=1 and PROFILEID='$pid'";
		$result = mysql_query_decide($sql) or die(mysql_error_js());
		$myrow = mysql_fetch_array($result);
		$previous_date_of_birth = $myrow['DTOFBIRTH'];
		$previous_gender = $myrow['GENDER'];
		$to_notify = $myrow['EMAIL'];
		$username = $myrow['USERNAME'];
		$activated = $myrow['ACTIVATED'];
		$screening_val = $myrow['SCREENING'];
		$preactivated = $myrow['PREACTIVATED'];
		$subscription = $myrow['SUBSCRIPTION'];
		$country_res = $myrow['COUNTRY_RES'];
		$city_res = $myrow['CITY_RES'];
		$service_mes = $myrow['SERVICE_MESSAGES'];
		$source = $myrow['SOURCE'];
		$std = $myrow['STD'];
                $activatedWithoutYourInfo = $_POST["activatedWithoutYourInfo"];
		if ($activated == 'U' || ($activated == 'Y' && (!areAllBitsSet($screening_val) || $activatedWithoutYourInfo)) || ($activated == 'H' && ($preactivated == 'U' || $preactivated == 'N' || $preactivated == 'Y'))) {
			if ($name != "" || $name_hob != "" || $name_contact != "" || $name_edu != "") {
				if ($name != "") {
					$NAME = explode(",", $name);
					for ($i = 0;$i < count($NAME);$i++) {
						if ($NAME[$i] == 'EMAIL') {
							$NAME[$i] = trim($NAME[$i]);
						}
						if ($NAME[$i] == "PHONE_RES") $screen = setFlag("PHONE_RES", $screen);
						elseif ($NAME[$i] == "PHONE_MOB") $screen = setFlag("PHONE_MOB", $screen);
						elseif ($NAME[$i] == "CITY_BIRTH") $screen = setFlag("CITYBIRTH", $screen);
						elseif ($NAME[$i] == "MESSENGER_ID") $screen = setFlag("MESSENGER_ID", $screen);
						else {
							if ($NAME[$i] != "GENDER" && $NAME[$i] != "MSTATUS" && $NAME[$i] != "PHOTO_DISPLAY" && $NAME[$i] != "DTOFBIRTH") $screen = setFlag("$NAME[$i]", $screen);
						}
					}
					if ($fullname != "") $screen = setFlag("NAME", $screen);
					$arrProfileUpdateParams = array();
					for ($i = 0;$i < count($NAME);$i++) {
						if ($NAME[$i] == "EMAIL") {
							$email = addslashes(stripslashes($_POST[$NAME[$i]]));
							$sql = "SELECT COUNT(*) as cnt FROM newjs.JPROFILE WHERE  EMAIL='$email' AND PROFILEID<>'$pid'";
							$res = mysql_query_decide($sql) or die("$sql" . mysql_error_js());
							$row = mysql_fetch_array($res);
							if ($row['cnt'] > 0) {
								$email = 'abc' . $pid . "@jsxyz.com";
								$str.= $NAME[$i] . " = '$email',";
								$verify_email = 'Y';
								$arrProfileUpdateParams[$NAME[$i]] = $email;
							} else {
								$email = addslashes(stripslashes($_POST[$NAME[$i]]));
								if (checkemail($email)) {
									header("Location: $SITE_URL/jsadmin/screen_new.php?cid=$cid&email_err=1&email_filled=$email&email_profileid=$pid&val=$val");
									die;
								} else {
									$str.= $NAME[$i] . " = '" . addslashes(stripslashes($_POST[$NAME[$i]])) . "' ,";
									$arrProfileUpdateParams[$NAME[$i]] = addslashes(stripslashes($_POST[$NAME[$i]]));
								}
							}
						} else {
							if ($NAME[$i] == "DTOFBIRTH") {
								list($prev_year, $prev_month, $prev_day) = explode("-", $previous_date_of_birth);
								$DTOFBIRTH = $year_of_birth . "-" . $month_of_birth . "-" . $day_of_birth;
								if (!$day_of_birth || !$month_of_birth || !$year_of_birth || !checkdate($month_of_birth, $day_of_birth, $year_of_birth)) {
									header("Location: $SITE_URL/jsadmin/screen_new.php?cid=$cid&date_err=1&email_profileid=$pid&val=$val");
									die;
								} else {
									$date_of_birth = $year_of_birth . "-" . $month_of_birth . "-" . $day_of_birth;
									$age = getAge($date_of_birth);
									if (($gender == "M" && $age < 21) || ($gender == "F" && $age < 18)) {
										header("Location: $SITE_URL/jsadmin/screen_new.php?cid=$cid&date_err=1&email_profileid=$pid&val=$val");
										die;
									} else {
										if (mktime(0, 0, 0, $prev_month, $prev_day, $prev_year) != mktime(0, 0, 0, $month_of_birth, $day_of_birth, $year_of_birth)) {
											$subject = "Change of Date of Birth";
											$mail_msg = "Dear $username,\nThis is with reference to the Date of Birth selected by you in the registration form. The one selected by you from the drop down values and the one mentioned as a text does not match. We are taking the date of birth mentioned as text as correct and are making the change in the date of birth field. Please write back to us with the exact date of birth if it is incorrect within three days of receiving this mail.\n\nWishing you success in your search.\n\nRegards,\nTeam Jeevansathi";
											//send_email($to_notify,nl2br($mail_msg),$subject,"","","ankit.aggarwal@jeevansathi.com","","text/html");
											$iAge = getAge($DTOFBIRTH);
											$str.= "AGE = '" . $iAge . "' , ";
											update_astro_dob($pid, $DTOFBIRTH);
											$arrProfileUpdateParams["AGE"] = $iAge;
										}
										$str.= $NAME[$i] . " = '" . addslashes(stripslashes($DTOFBIRTH)) . "' ,";
										$arrProfileUpdateParams[$NAME[$i]] = addslashes(stripslashes($DTOFBIRTH));
									}
								}
							} elseif ($NAME[$i] == "GENDER") {
								if ($previous_gender != $_POST[$NAME[$i]] && $_POST[$NAME[$i]] != '') {
									if ($_POST[$NAME[$i]] == "M") $notify_gender = "male";
									elseif ($_POST[$NAME[$i]] == "F") $notify_gender = "female";
									$subject = "Change of Gender";
									$mail_msg = "Dear $username,\nThis is with reference to the Gender selected by you in the registration form. The one selected by you from the drop down values and the details provided by you in the text field are both contradictory. The information furnished by you suggests your gender to be " . $notify_gender . ", hence we are changing the Gender to " . $notify_gender . ".Please write back to us with the correct gender incase there is any discrepancy within three days of receiving this mail.\n\nWishing you success in your search.\n\nRegards,\nTeam Jeevansathi";
									//send_email($to_notify,nl2br($mail_msg),$subject,"","","ankit.aggarwal@jeevansathi.com","","text/html");
									$do_gender_related_changes = 1;
								}
								$str.= $NAME[$i] . " = '" . addslashes(stripslashes($_POST[$NAME[$i]])) . "' ,";
								$arrProfileUpdateParams[$NAME[$i]] = addslashes(stripslashes($_POST[$NAME[$i]]));
							} elseif ($NAME[$i] == "USERNAME") {
								if ($gen_new) {
									$Username = username_gen();
									$user_id_flag = 1;
									$gen_new = 0;
									makes_username_changes($pid, $Username);
									$str.= $NAME[$i] . " = '" . addslashes(stripslashes($Username)) . "' ,";
									$arrProfileUpdateParams[$NAME[$i]] = addslashes(stripslashes($Username));
								}
							} elseif ($NAME[$i] == "MSTATUS") {
								$mstatus = addslashes(stripslashes($_POST[$NAME[$i]]));
								if ($mstatus == '') {
									header("Location: $SITE_URL/jsadmin/screen_new.php?cid=$cid&mstatus_err=1&email_profileid=$pid&val=$val");
									die;
								} else {
									$str .= $NAME[$i] . " = '" . addslashes(stripslashes($mstatus)) . "' ,";
									$arrProfileUpdateParams[$NAME[$i]] = addslashes(stripslashes($mstatus));
								}
							}
							/*
							                 elseif($NAME[$i]=="PHONE_RES")
							                                         {
							                 $phone_res=addslashes(stripslashes($_POST[$NAME[$i]]));
							                                         	$str .= $NAME[$i]." = '".$phone_res."' ,PHONE_WITH_STD='$std$phone_res'  ,";
							                                         }
							*/
							else {
								$str.= $NAME[$i] . " = '" . addslashes(stripslashes($_POST[$NAME[$i]])) . "' ,";
								$arrProfileUpdateParams[$NAME[$i]] = $_POST[$NAME[$i]];
							}
						}
						if ($NAME[$i] == "YOURINFO") {
							if(strlen($_POST[$NAME[$i]])<100){
								
								if ($_POST[$NAME[$i]] == "") {
									$bl_msg = "<b>Please Note : </b>We have removed the content that you had put in 'About me' section of your profile as it was inappropriate.";
                                                                        if(!$activatedWithoutYourInfo)
                                                                            $bl_msg .= " So, your profile <b>has been marked incomplete.</b>";
                                                                        $bl_msg .= " Add relevant/valid/clear information in this field to complete your profile. Better description will also get you better results.
								<br><br>Please";
									$bl_msg.= "<a href = \"http://www.jeevansathi.com/profile/viewprofile.php?echecksum=$echecksum&checksum=$PROFILECHECKSUM&ownview=1&CMGFRMMMMJS=Y&EditWhatNew=incompletProfile\"> click here </a>";
									$bl_msg.= " to edit your profile <br>";
								
								
							} elseif ($_POST[$NAME[$i]] != "" && trim($_POST[$NAME[$i]]) == "") {
								header("Location: $SITE_URL/jsadmin/screen_new.php?cid=$cid&info_err=1&email_profileid=$pid&val=$val");
								
							}
							else
							{
							$bl_msg = "<b>Please Note : </b>We have modified the content that you had put in the 'About me' section of your profile as it was inappropriate.";
                                                        if(!$activatedWithoutYourInfo)
                                                            $bl_msg .= " So, your profile <b>has been marked incomplete.</b>";
                                                        $bl_msg .= " Add relevant/valid/clear information in this field to complete your profile. Better description will also get you better results.
								<br><br>Please";
									$bl_msg.= "<a href = \"http://www.jeevansathi.com/profile/viewprofile.php?echecksum=$echecksum&checksum=$PROFILECHECKSUM&ownview=1&CMGFRMMMMJS=Y&EditWhatNew=incompletProfile\"> click here </a>";
									$bl_msg.= " to edit your profile <br>";
							
							}
                                                        if(!$activatedWithoutYourInfo)
                                                            $INCOMPLETE="Y";
                                                        else{
                                                            if(strlen($_POST[$NAME[$i]])<50)
                                                                $arrProfileUpdateParams[$NAME[$i]] = "";
                                                            $completeWithoutYourInfo = "Y";
                                                        }
							$instantNotificationObj = new InstantAppNotification("INCOMPLETE_SCREENING");
                			$instantNotificationObj->sendNotification($pid);
						}
					}
						
				}
			}		
				if ($_POST['PHONE_FLAG'] == "I") phoneUpdateProcess($pid, '', '', 'I', 'OPS', $user);
				if ($fullname) $str_name = "NAME=" . "'" . addslashes(stripslashes($_POST[$fullname])) . "'";
				if ($str_name) $count_screen = count($NAME) + 1;
				else $count_screen = count($NAME);
				$log_name = $name;
				$log_val = array();
				if ($name) {
					$sql_jp = "SELECT $name from newjs.JPROFILE where PROFILEID=$pid and activatedKey=1";
					$res_jp = mysql_query_decide($sql_jp) or die(mysql_error_js());
					$log_val = mysql_fetch_row($res_jp);
				}
				if ($name_edu) {
					$NAME_EDU = explode(",", $name_edu);
					$sql_edu = "SELECT $name_edu from newjs.JPROFILE_EDUCATION where PROFILEID=$pid";
					$res_edu = mysql_query_decide($sql_edu) or die(mysql_error_js());
					if ($res_edu) {
						$row_edu = mysql_fetch_row($res_edu);
						if($row_edu){
							$log_name = $log_name ? "$log_name,$name_edu" : $name_edu;
							$log_val = array_merge($log_val, $row_edu);
						}
					}
					$count_screen = $count_screen + count($NAME_EDU);
					$arrEducationUpdateParams = array();
					foreach ($NAME_EDU as $value) {
						$str_edu.= $value . " = '" . addslashes(stripslashes($_POST[$value])) . "' ,";
						$screen = setFlag($value, $screen);
						$arrEducationUpdateParams[$value] = addslashes(stripslashes($_POST[$value]));
					}
					$str_edu = rtrim($str_edu, ",");
				}
				if ($name_contact) {
					$NAME_CONTACT = explode(",", $name_contact);
					$sql_contact = "SELECT $name_contact from newjs.JPROFILE_CONTACT where PROFILEID=$pid";
					$res_contact = mysql_query_decide($sql_contact) or die(mysql_error_js());
					if ($res_contact) {
						$row_contact = mysql_fetch_row($res_contact);
						if($row_contact){
							$log_val = array_merge($log_val, $row_contact);
							$log_name = $log_name ? "$log_name,$name_contact" : $name_contact;
						}
					}
					$count_screen = $count_screen + count($NAME_CONTACT);
					$arrContactUpdateParams = array();
					foreach ($NAME_CONTACT as $value) {
						$str_contact.= $value . " = '" . addslashes(stripslashes($_POST[$value])) . "' ,";
						$screen = setFlag($value, $screen);
						$arrContactUpdateParams[$value] = addslashes(stripslashes($_POST[$value]));
					}
					$str_contact = rtrim($str_contact, ",");
				}
				if ($name_hob) {
					$NAME_HOB = explode(",", $name_hob);
					$sql_hob = "SELECT $name_hob from newjs.JHOBBY where PROFILEID=$pid";
					$res_hob = mysql_query_decide($sql_hob) or die(mysql_error_js());
					if ($res_hob) {
						$row_hob = mysql_fetch_row($res_hob);
						if($row_hob){
							$log_val = array_merge($log_val, $row_hob);
							$log_name = $log_name ? "$log_name,$name_hob" : $name_hob;
						}
					}
					$count_screen = $count_screen + count($NAME_HOB);
					$arrHobbyUpdateParams = array();
					foreach ($NAME_HOB as $value) {
						$str_hob.= $value . " = '" . addslashes(stripslashes($_POST[$value])) . "' ,";
						$screen = setFlag($value, $screen);
						$arrHobbyUpdateParams[$value] = addslashes(stripslashes($_POST[$value]));
					}
					$str_hob = rtrim($str_hob, ",");
				}
				foreach($log_val as $item)
					$log_val1[]=addslashes(stripslashes($item));
				$log_values = implode("','", $log_val1);
				$sql_pre = "INSERT into jsadmin.SCREENING_LOG(PROFILEID,$log_name,SCREENED_BY,SCREENED_TIME,ENTRY_TYPE,FIELDS_SCREENED) values ('$pid','$log_values','$user',now(),'P','$count_screen') ";
				mysql_query_decide($sql_pre) or die(mysql_error_js()."at line 253");
				$ref_id = mysql_insert_id_js();
				if ($str_name) $sql_up = " UPDATE jsadmin.SCREENING_LOG set REF_ID='$ref_id',$str_name where ID='$ref_id' ";
				else $sql_up = " UPDATE jsadmin.SCREENING_LOG set REF_ID='$ref_id' where ID='$ref_id' ";
				mysql_query_decide($sql_up) or die(mysql_error_js()."at line 257");
				$str = rtrim($str, ",");
				$sub = explode(",", $subscription);
				if ((in_array("D", $sub) && in_array("S", $sub)) || in_array("H", $sub) || in_array("K", $sub)) {
					if (in_array("D", $sub)) {
						if (isFlagSet("PHONE_RES", $screen) && isFlagSet("PHONE_MOB", $screen) && isFlagSet("CONTACT", $screen) && isFlagSet("PARENTS_CONTACT", $screen) && isFlagSet("MESSENGER_ID", $screen) && isFlagSet("EMAIL", $screen)) {
							evalue_privacy($pid, $subscription);
						}
					}
					$sqlbill = "SELECT BILLID,VERIFY_SERVICE FROM billing.PURCHASES WHERE PROFILEID='$pid' AND STATUS='DONE' ORDER BY BILLID DESC LIMIT 1";
					$resbill = mysql_query_decide($sqlbill) or die("$sqlbill" . mysql_error_js());
					$rowbill = mysql_fetch_assoc($resbill);
					if ($rowbill["VERIFY_SERVICE"] == 'A') {
						$sqlbill = "UPDATE billing.PURCHASES SET VERIFY_SERVICE='Y' WHERE BILLID='$rowbill[BILLID]'";
						mysql_query_decide($sqlbill) or die("$sqlbill" . mysql_error_js());
					}
				}
//				if ($str) $sql = " UPDATE newjs.JPROFILE set $str, SCREENING='$screen'";
//				else $sql = " UPDATE newjs.JPROFILE set SCREENING='$screen'";
        //if($activatedWithoutYourInfo){
            $activated_without_yourInfoObj = new JSADMIN_ACTIVATED_WITHOUT_YOURINFO();
            $activated_without_yourInfoObj->delete($pid);
        //}
        global $screeningRep;
        if($screeningRep)
            $objUpdate = JProfileUpdateLib::getInstance("newjs_masterRep");
        else
            $objUpdate = JProfileUpdateLib::getInstance();
        //JPROFILE Columns
        $arrProfileUpdateParams['SCREENING']= $screen;
				if ($str_edu) {         
					//$sql_ed = "UPDATE newjs.JPROFILE_EDUCATION set $str_edu where PROFILEID=$pid";
					//mysql_query_decide($sql_ed) or die("$sql_ed" . mysql_error_js()."at line 278");
          $result = $objUpdate->updateJPROFILE_EDUCATION($pid,$arrEducationUpdateParams);
          if(false === $result) {
            die('Mysql error while updating JPROFILE_EDUCATION at line 328');
          }
          unset($arrEducationUpdateParams);
				}
				if ($str_contact) {
          
          // $memObject=new UserMemcache;
          // $memObject->delete("JPROFILE_CONTACT_".$profileid);
          // unset($memObject);
					//$sql_contact = "UPDATE newjs.JPROFILE_CONTACT set $str_contact where PROFILEID=$pid";
          //mysql_query_decide($sql_contact) or die("$sql_contact" . mysql_error_js()."at line 282");
          
          $result = $objUpdate->updateJPROFILE_CONTACT($pid,$arrContactUpdateParams);
          if(false === $result) {
            die('Mysql error while updating JPROFILE_CONTACT at line 342');
          }
          unset($arrContactUpdateParams);
				}
        
				if ($str_hob) {
//					$sql_hob = "UPDATE newjs.JHOBBY set $str_hob where PROFILEID=$pid";
//					mysql_query_decide($sql_hob) or die("$sql_hob" . mysql_error_js()."at line 286");
          
          $result = $objUpdate->updateJHOBBY($pid,$arrHobbyUpdateParams);
          if(false === $result) {
            die('Mysql error while updating JHOBBY at line 357');
          }
          unset($arrHobbyUpdateParams);
				}
        
				if ($fullname) {
					$fname = addslashes(stripslashes($_POST[$fullname]));
					$sql_name = "UPDATE incentive.NAME_OF_USER set NAME='$fname' where PROFILEID=$pid";
                                        $nameOfUserObj->removeNameFromCache($pid);
					mysql_query_decide($sql_name) or die("$sql_name" . mysql_error_js());
				}
        if ($verify_email) {
          //$sql.= ", VERIFY_EMAIL='$verify_email'";
          $arrProfileUpdateParams['VERIFY_EMAIL'] = $verify_email;
        }
				if ($INCOMPLETE != "")
				{
					//$sql.= ",SCREENING=0,PREACTIVATED='$activated',ACTIVATED='N' , INCOMPLETE='Y'";
          //$arrProfileUpdateParams['SCREENING'] = 0;
          $arrProfileUpdateParams['PREACTIVATED'] = $activated;
          $arrProfileUpdateParams['ACTIVATED'] = 'N';
          $arrProfileUpdateParams['INCOMPLETE'] = 'Y';
          
					$sql_incomplete = "insert ignore into MIS.INCOMPLETE_SCREENING(`PROFILEID`,`DATE`) values($pid,now())";
					mysql_query_decide($sql_incomplete) or die("$sql_incomplete" . mysql_error_js());
				}
				else if ($activated == 'U' || ($activated == 'H' && ($preactivated == 'U' || $preactivated == 'N'))) {
					//$sql.= ", PREACTIVATED='$activated',ACTIVATED='Y'";
          $arrProfileUpdateParams['PREACTIVATED'] = $activated;
          $arrProfileUpdateParams['ACTIVATED'] = 'Y';
          if ($val == "new") {
            $updateFTOState = 1;
          }
          else {
            $updateFTOState = 0;
          }
					$addInUserCreation = 1; //Adding entry in chat user

					//Adding entry to bot_jeevansathi.MAIL_INVITE table if email of gmail
					//Required to give them gmail chat invite
					//if(strstr($to_notify,'@gmail.com') && $service_mes=='S')
					if ($service_mes == 'S') {
						$sql_ins = "insert ignore into bot_jeevansathi.SEND_INVITE(`EMAIL`,`DATE`) values('$to_notify',now())";
						mysql_query_decide($sql_ins) or die("$sql_ins" . mysql_error_js());
					}
					if ($Annulled_Reason) {
						$areason = htmlspecialchars($Annulled_Reason, ENT_QUOTES);
						$sql_a = "Update newjs.ANNULLED set SCREENED='Y',REASON='$areason',COURT='$Court',UPDATE_DT=now() where PROFILEID='$pid'";
						mysql_query_decide($sql_a) or die("$sql_a" . mysql_error_js());
					}
				}
		/*
			changing to get original and modified your info here and saving in table Profile
		 */
        // $your_info = mysql_real_escape_string($arrProfileUpdateParams['YOURINFO']);
        // $your_info_original = mysql_real_escape_string($_POST['YOURINFO_ORIGINAL']);

      
				/*if (0)
				 $sql.= "ACTIVATED = 'N' AND INCOMPLETE ='Y' ";*/
				/*else
				 $sql.= "ACTIVATED = 'Y' ";*/
				//$sql.= " where PROFILEID = '$pid' and activatedKey=1 ";
				//mysql_query_decide($sql) or die("$sql" . mysql_error_js());
        //Update JPROFILE Store
        $result = $objUpdate->editJPROFILE($arrProfileUpdateParams,$pid,'PROFILEID','activatedKey=1');
	unsetMemcache5Sec($user);
	    /*
	    	check for whether your_info_original was set or not.
	    */
	   // commented since the code was written for benchmarking purpose
   //      if ( strlen($your_info_original) !== 0 )
   //      {
	  //       $sql_junk_character_check = "INSERT INTO  `PROFILE`.`JUNK_CHARACTER_TEXT` (  `id` ,  `PROFILEID` ,  `original_text` ,  `modified_custom`) VALUES('',  '$pid',  '$your_info_original',  '$your_info');";
			// $result = mysql_query($sql_junk_character_check);
   //      }


        if(false === $result) {
          die('Mysql error while updating JPROFILE at line 385');
        }
				/* duplication_fields_insertion() call inserted by Reshu Rajput, here "invalid_dup_fields" is any dummy string
                                * to be passed to use the older version of the function with least modifications
                                * This call is made to do the insertion in duplicates_check_fields table when new account is there
                                */
                        
                                if($val == "new")
                                         duplication_fields_insertion("invalid_dup_fields",$pid);
        if ($updateFTOState === 1) {
         $profileObj=new Profile('',$pid);
			$profileObj->getDetail();
          $action = FTOStateUpdateReason::SCREEN;
          $profileObj->getPROFILE_STATE()->updateFTOState($profileObj,$action);
        }

				//Log modified values
				$log_name = $name;
				$log_val = array();
				if ($name) {
					$sql_jp = "SELECT $name from newjs.JPROFILE where PROFILEID=$pid and activatedKey=1";
					$res_jp = mysql_query_decide($sql_jp) or die(mysql_error_js());
					$log_val = mysql_fetch_row($res_jp);
				}
				if ($name_edu) {
					$sql_edu = "SELECT $name_edu from newjs.JPROFILE_EDUCATION where PROFILEID=$pid";
					$res_edu = mysql_query_decide($sql_edu) or die(mysql_error_js());
					if ($res_edu) {
						$row_edu = mysql_fetch_row($res_edu);
						if($row_edu){
							$log_val = array_merge($log_val, $row_edu);
							$log_name = $log_name ? "$log_name,$name_edu" : $name_edu;
						}
					}
				}
				if ($name_contact) {
					$sql_contact = "SELECT $name_contact from newjs.JPROFILE_CONTACT where PROFILEID=$pid";
					$res_contact = mysql_query_decide($sql_contact) or die(mysql_error_js());
					if ($res_contact) {
						$row_contact = mysql_fetch_row($res_contact);
						if($row_contact){
							$log_val = array_merge($log_val, $row_contact);
							$log_name = $log_name ? "$log_name,$name_contact" : $name_contact;
						}
					}
				}
				if ($name_hob) {
					$sql_hob = "SELECT $name_hob from newjs.JHOBBY where PROFILEID=$pid";
					$res_hob = mysql_query_decide($sql_hob) or die(mysql_error_js());
					if ($res_hob) {
						$row_hob = mysql_fetch_row($res_hob);
						if($row_hob){
							$log_name = $log_name ? "$log_name,$name_hob" : $name_hob;
							$log_val = array_merge($log_val, $row_hob);
						}
					}
				}
				$log_val1=array();
				foreach($log_val as $item)
					$log_val1[]=addslashes(stripslashes($item));
				$log_values = implode("','", $log_val1);
                                if($val == "new")
                                    $screenNewEdit = 2;
                                
                                else
                                    $screenNewEdit = 3;
				$sql_mod = "INSERT into jsadmin.SCREENING_LOG(REF_ID,PROFILEID,$log_name,SCREENING,SCREENED_BY,SCREENED_TIME,ENTRY_TYPE,FIELDS_SCREENED) VALUES ('$ref_id',$pid,'$log_values',$screenNewEdit,'$user',now(),'M','$count_screen')";
				mysql_query_decide($sql_mod) or die(mysql_error_js()."at line 367");
				//added by sriram.
				if ($do_gender_related_changes) {
					//make changes related to gender at various places.
					gender_related_changes($pid, $previous_gender);
				}
				$now = date("Y-m-d");
				$sql_ne = "SELECT COUNT(*) as cnt from MIS.NEW_EDIT_COUNT where SCREEN_DATE='$now' AND SCREENED_BY='$user'";
				$result_ne = mysql_query_decide($sql_ne) or die(mysql_error_js());
				$row_ne = mysql_fetch_assoc($result_ne);
				if ($row_ne['cnt'] == 0) {
					$sql_ins = "INSERT INTO MIS.NEW_EDIT_COUNT (SCREEN_DATE,SCREENED_BY) VALUES('$now','$user')";
					mysql_query_decide($sql_ins) or die(mysql_error_js());
				}
				if ($activated == 'U' || ($activated == 'H' && ($preactivated == 'U' || $preactivated == 'N'))) {
					$sql_ne = "UPDATE MIS.NEW_EDIT_COUNT SET NEW=NEW+1 WHERE SCREEN_DATE='$now' AND SCREENED_BY='$user'";
					mysql_query_decide($sql_ne) or die(mysql_error_js());
				} else {
					$sql_ne = "UPDATE MIS.NEW_EDIT_COUNT SET EDIT=EDIT+1 WHERE SCREEN_DATE='$now' AND SCREENED_BY='$user'";
					mysql_query_decide($sql_ne) or die(mysql_error_js());
				}
				$sql = "SELECT RECEIVE_TIME,SCREENING_VAL FROM jsadmin.MAIN_ADMIN WHERE PROFILEID='$pid' and SCREENING_TYPE='O'";
				$res = mysql_query_decide($sql) or die("$sql" . mysql_error_js());
				$resf = mysql_fetch_array($res);
				$rec_time = $resf['RECEIVE_TIME'];
				$date_time = explode(" ", $rec_time);
				$date_y_m_d = explode("-", $date_time[0]);
				$time_h_m_s = explode(":", $date_time[1]);
				$timestamp = mktime($time_h_m_s[0], $time_h_m_s[1], $time_h_m_s[2], $date_y_m_d[1], $date_y_m_d[2], $date_y_m_d[0]);
				$screeningValMainAdmin = $resf[SCREENING_VAL];
				$timezone = date("T", $timestamp);
				if ($timezone == "EDT") $timezone = "EST5EDT";
if($arrProfileUpdateParams['ACTIVATED']=="N")
{
	$STATUS = "DELETED";
}
else
{
	$STATUS= "APPROVED";
}

if($activated=="U" || $activated=="N")
{
$screeningValMainAdmin = 0;
}
				$sql = "INSERT into jsadmin.MAIN_ADMIN_LOG (PROFILEID, USERNAME, SCREENING_TYPE, RECEIVE_TIME, SUBMIT_TIME, ALLOT_TIME, SUBMITED_TIME, ALLOTED_TO, STATUS, SUBSCRIPTION_TYPE, SCREENING_VAL,TIME_ZONE, SUBMITED_TIME_IST) SELECT PROFILEID, USERNAME, SCREENING_TYPE, RECEIVE_TIME, SUBMIT_TIME, ALLOT_TIME, now(), ALLOTED_TO, '$STATUS', SUBSCRIPTION_TYPE, '$screeningValMainAdmin','$timezone', CONVERT_TZ(NOW(),'$timezone','IST') from jsadmin.MAIN_ADMIN where PROFILEID='$pid' and SCREENING_TYPE='O'";
				mysql_query_decide($sql) or die(mysql_error_js());
				$sql = "DELETE from jsadmin.MAIN_ADMIN where PROFILEID='$pid' and SCREENING_TYPE='O'";
				mysql_query_decide($sql) or die(mysql_error_js());
				if ($INCOMPLETE != "") $msg = "User $username is mark as incomplete<BR><BR>";
				else {
					$sql = "SELECT GROUPNAME FROM MIS.SOURCE WHERE SourceID='$source'";
					$ressource = mysql_query_decide($sql) or die(mysql_error_js());
					if (mysql_num_rows($ressource)) {
						$mysource = mysql_fetch_array($ressource);
						$groupname = $mysource["GROUPNAME"];
						$smarty->assign("groupname", $groupname);
					}
					include_once ("ap_common.php");
					makeProfileLive($pid, $city_res, $subscription, 1);
					if ($val == "new") {
						include_once("../profile/InstantSMS.php");
						$sms=new InstantSMS("PROFILE_APPROVE",$pid);
						$sms->send();
						$sms=new InstantSMS("DETAIL_CONFIRM",$pid);
						$sms->send();
						$sms=new InstantSMS("MTONGUE_CONFIRM",$pid);
						$sms->send();
						try
						{
							$producerObj=new Producer();
							if($producerObj->getRabbitMQServerConnected())
							{
								$sendMailData = array('process' => MQ::SCREENING_Q_EOI, 'data' => array('type' => 'SCREENING','body' => array('profileId' => $pid)), 'redeliveryCount' => 0);
								$producerObj->sendMessage($sendMailData);
							}
						}
						catch(Exception $e) {}
					//	$parameters = array("KEY" => "SI_APPROVE", "PROFILEID" => $pid, "DATA" => $pid);
					//	sendSingleInstantSms($parameters);
					}
					$msg = "User $username is successfully screened<br><br>";
					$smarty->assign("profileid", $pid);
					if ($country_res != 51) $smarty->assign("NRI", 1);
					else $smarty->assign("NRI", 0);
					$smarty->assign("valid", 1);
					$smarty->assign("from_screening", 1);
				}
			} else {
				$msg = "User $username is already screened 1<br><br>";
				$find_sql = "SELECT SUBMITED_TIME,ALLOTED_TO FROM jsadmin.MAIN_ADMIN_LOG where PROFILEID='$pid' AND SCREENING_TYPE='O' ORDER BY SUBMITED_TIME DESC LIMIT 0,1";
				$find_result = mysql_query_decide($find_sql);
				$find_row = mysql_fetch_assoc($find_result);
				$screened_by = $find_row['ALLOTED_TO'];
				$screened_time = $find_row['SUBMITED_TIME'];
				$ins_sql = "INSERT INTO jsadmin.TRACK_SCREENING (`USERNAME`,`CURRENT_USER`,`CURRENT_TIME`,`SCREENED_BY`,`SCREENED_TIME`) VALUES('$username','$user',now(),'$screened_by','$screened_time')";
				mysql_query_decide($ins_sql) or die(mysql_error_js());
			}
		} else {
			file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/screen.txt","activated:".$activated."preactivated".$preactivated."screening_val:".$screening_val."activatedWithoutYourInfo:".$activatedWithoutYourInfo."\n\n",FILE_APPEND);

			$msg = "User $username is already screened 2<br><br>";
			$find_sql = "SELECT SUBMITED_TIME,ALLOTED_TO FROM jsadmin.MAIN_ADMIN_LOG where PROFILEID='$pid' AND SCREENING_TYPE='O' ORDER BY SUBMITED_TIME DESC LIMIT 0,1";
			$find_result = mysql_query_decide($find_sql);
			$find_row = mysql_fetch_assoc($find_result);
			$screened_by = $find_row['ALLOTED_TO'];
			$screened_time = $find_row['SUBMITED_TIME'];
			$ins_sql = "INSERT INTO jsadmin.TRACK_SCREENING (`USERNAME`,`CURRENT_USER`,`CURRENT_TIME`,`SCREENED_BY`,`SCREENED_TIME`) VALUES('$username','$user',now(),'$screened_by','$screened_time')";
			mysql_query_decide($ins_sql) or die(mysql_error_js());
			$sql = "DELETE from jsadmin.MAIN_ADMIN where PROFILEID='$pid' and SCREENING_TYPE='O'";
			mysql_query_decide($sql) or die(mysql_error_js());
		}
		if ($medit == 1) {
			$msg.= "<br><p align=\"center\"><a href=\"view_profile_count.php?user=$user&cid=$cid&val=$val\">";
			$msg.= "Continue &gt;&gt;</a>";
		} else if ($from_skipped == 1) {
                	$msg .= "<a href=\"view_skipped_profiles.php?user=$user&cid=$cid&val=$val\">";
                	$msg .= "Continue &gt;&gt;</a>";
		  } else {
			$msg.= "<br><p align=\"center\"><a href=\"screen_new.php?user=$user&cid=$cid&val=$val\">";
			$msg.= "Continue to next profile &gt;&gt;</a>";
			$msg.= "<br><br><a href=\"mainpage.php?user=$user&cid=$cid\">";
			$msg.= "Exit screening</a></p>";
		}
		$sql = "SELECT GENDER,FAMILYINFO,SPOUSE,EMAIL,USERNAME,PASSWORD,HAVEPHOTO,CITY_RES from newjs.JPROFILE where  PROFILEID='$pid'";
		$r1 = mysql_query_decide($sql) or die(mysql_error_js());
		$r2 = mysql_fetch_array($r1);
		$to = $r2['EMAIL'];
		$smarty->assign('username', $r2['USERNAME']);
		$smarty->assign('password', $r2['PASSWORD']);
		$smarty->assign('email', $r2['EMAIL']);
		//Mail only when incomplete checkbox is not checked
		if ($service_mes == 'S') if ($INCOMPLETE != "" || $completeWithoutYourInfo=="Y") {
			if ($why_inc != "Please provide reason why this profile is incomplete" && $why_inc != "") {
				$inc_reason = htmlspecialchars($why_inc, ENT_QUOTES);
				$sql = "replace into jsadmin.INCOMPLETE(PROFILEID,REASON) values ('$pid','$inc_reason')";
				mysql_query_decide($sql) or die(mysql_error_js());
			}
			$from = "info@jeevansathi.com";
                        
			$smarty->assign("bl_msg", $bl_msg);
                        if(!$activatedWithoutYourInfo){
                            $subject = "Your profile on Jeevansathi.com has been marked incomplete";
                        }
                        else{
                            $subject = "'About me' mentioned in your Profile has been edited/removed as it was inappropriate";
                        }
			$smarty->assign("CHECKSUM", $PROFILECHECKSUM);
			$smarty->assign("echecksum", $echecksum);
			$smarty->assign("myprofilechecksum", $PROFILECHECKSUM);
			$msgI = $smarty->fetch("incomplete_mailer.htm");
			send_email($to, $msgI, $subject, $from);
			
		} else {
			//code added by nikhil on June 11 upgradation of register mail that sent when confirmation is done //
			 //defined in profile/functions.inc
			/* Nikhil code that started on 11 jun ends here  */
			$mail_msg = "We thank you for your interest in Jeevansathi.com.<br><br> This is to notify you that your profile submitted with us has been screened and will now be viewable by members , according to the privacy setting that you have dictated.";
			if (!$email_ev) $mail_msg.= "<br><br>" . $bl_msg;
			$smarty->assign('username', $r2['USERNAME']);
			$PROFILE_CHECKSUM = createChecksumForSearch($pid);
			$smarty->assign("PROFILE_CHECKSUM", $PROFILE_CHECKSUM);
			$myprofilechecksum = md5($pid) . "i" . ($pid);
			$smarty->assign("myprofilechecksum", $myprofilechecksum);
			$checksum = $protect_obj->js_encrypt($myprofilechecksum, $to);
			$smarty->assign("CHECKSUM", $checksum);
		
			if ($val == 'edit') {
				
				$cScoreObject = ProfileCompletionFactory::getInstance(null,null,$pid);
				$p_percent = $cScoreObject->getProfileCompletionScore();
				$arrMsgDetails= $cScoreObject->GetIncompleteDetails();
				$arrLinkDetails = array(
				'ME'=>"ABOUT_ME",
                                'BASIC'=>"BASIC_DETAILS",
				'CAREER'=>"EDU_OCC",
				'RELIGION'=>"PROFILE_RELIGION",
				'ASTRO'=>"UPLOAD_HOROSCOPE",
				'FAMILY'=>"PROFILE_FAMILY",
				'LIFE'=>"COMPLETE_PROFILE_LIFESTYLE",
				'HOBBY'=>"PROFILE_HOBBIES",
				'PHOTO'=>"UPLOAD_PHOTO");
				$email_sender_automated=new EmailSender(MailerGroup::SCREENING_EDIT,1783);
				$emailTplAutomated=$email_sender_automated->setProfileId($pid);
				$smartyObjAutomated = $emailTplAutomated->getSmarty();
				
				//$p_percent = profile_percent($pid, "", "", "", "", 3);
				$smartyObjAutomated->assign("PROFILE_PERCENT", $p_percent);
				$smartyObjAutomated->assign("arrMsgDetails", $arrMsgDetails);
				$smartyObjAutomated->assign("arrLinkDetails", $arrLinkDetails);
				$email_sender_automated->send();
				
				//$MESSAGE = $smarty->fetch("automated_response_2.htm");
				//if ($to && $verify_mail != 'Y') send_email($to, $MESSAGE);
			}
			else{
				$path = $symfonyFilePath=$_SERVER['DOCUMENT_ROOT'];
				//require_once("$path/profile/symfony.inc");
				include_once(JsConstants::$docRoot."/ivr/jsivrFunctions.php");
				$phoneVerified = getPhoneStatus("",$pid);
				if($phoneVerified!='Y')
				{	$email_sender=new EmailSender(MailerGroup::PHONE_VERIFICATION,1775);
					$emailTpl=$email_sender->setProfileId($pid);
					$profileObj=$emailTpl->getSenderProfile();
                                        $profileState=JsCommon::getProfileState($profileObj);
				}
                                else{
                                    $profileState="F";
                                }
				
			if($profileState=="F" || $profileState=="P")
			 {
				$smarty->assign('gender', $r2['GENDER']);
				//$emailTpl->getSmarty()->assign('gender', $r2['GENDER']);
				//Symfony Photo Modification
				$sql1 = "SELECT COUNT(*) AS CNT FROM newjs.PICTURE_FOR_SCREEN_NEW WHERE PROFILEID='$pid'";
				$result1 = mysql_query_decide($sql1) or logError($sql1);
				$row = mysql_fetch_array($result1);
				if ($row[CNT] == 0) // no photos for screening
				{
					$sql1 = "SELECT COUNT(*) AS CNT FROM newjs.PICTURE_NEW WHERE PROFILEID='$pid'";
					$result1 = mysql_query_decide($sql1) or logError($sql1);
					$row = mysql_fetch_array($result1);
					if ($row[CNT] == 0) $var[1] = 1; //no screened photos
					else $var[1] = 0; //screened photos present
					
				} else $var[1] = 0; //photos for screening present
				if ($r2['FAMILYINFO'] == '') $var[2] = 1;
				else $var[2] = 0;
				if ($r2['SPOUSE'] == '') $var[3] = 1;
				else $var[3] = 0;
				if (($var[1] + $var[2] + $var[3]) == 0) $smarty->assign('show', 1);
				$smarty->assign('MSG_IN_MAIL', $mail_msg);
				$smarty->assign('user_id_flag', $user_id_flag);
				$smarty->assign("condition", $var);
				$MESSAGE = $smarty->fetch("automated_response_1.htm");
				if($phoneVerified == "Y")
				{
					if ($to && $verify_mail != 'Y') 
					{
                                            if(!$activatedWithoutYourInfo){
                                                try
						{
							$producerObj=new Producer();
							if($producerObj->getRabbitMQServerConnected())
							{
								$sendMailData = array('process' => MQ::SCREENING_MAILER, 'data' => array('type' => 'WELCOME_MAILER','body' => array('profileId' => $pid)), 'redeliveryCount' => 0);
								$producerObj->sendMessage($sendMailData);
							}
                                                        else{
                                                            CommonFunction::sendWelcomeMailer($pid);
                                                        }
						}
						catch(Exception $e) {}
                                            }
                                        }
						//send_email($to, $MESSAGE);
				}
				else
					$email_sender->send();
			}
				
			else
			{
				
				if($profileState==FTOSubStateTypes::FTO_ACTIVE_BELOW_LOW_THRESHOLD || $profileState==FTOSubStateTypes::FTO_ACTIVE_BETWEEN_LOW_HIGH_THRESHOLD|| $profileState==FTOSubStateTypes::FTO_ACTIVE_ABOVE_HIGH_THRESHOLD)
					{
						$outputInputM2 =SearchCommonFunctions::getDppMatches($pid,'fto_offer');
					}
				else
					{
						$outputInputM2 = SearchCommonFunctions::getDppMatches($pid,'fto_offer',SearchSortTypesEnums::popularSortFlag);
					}
				$inputM2 = $outputInputM2["SEARCH_RESULTS"];
				$p_list=new PartialList;
				$p_list->addPartial('suggested_profiles','suggested_profiles',$inputM2,false);
				$email_sender->send('',$p_list);
			}
		}
		}
                
        $cScoreObject = ProfileCompletionFactory::getInstance(null,null,$pid);
        $cScoreObject->updateProfileCompletionScore();
		$smarty->assign("name", $user);
		$smarty->assign("cid", $cid);
		$smarty->assign("MSG", $msg);
		$smarty->display("jsadmin_msg.tpl");
/*
		if ($medit != 1 && $from_skipped != 1) {
			header('Location: screen_new.php?user='.$user.'&cid='.$cid.'&val='.$val);
		}
*/
	} elseif ($Submit1) 
	{
		//Setting the value in memcache, that will be checked in authentication function while user is online.
		set_memcache_value($pid);
		$smarty->assign("user", $user);
		$smarty->assign("pid", $pid);
		$smarty->assign("cid", $cid);
		$smarty->assign("c", "1");
		$smarty->assign("FROM", "U");
		if ($medit == 1) $smarty->assign("medit", $medit);
		else $smarty->assign("medit", 2);
		$smarty->assign("val", $val);
		$smarty->display("delete_page.tpl");
	} elseif ($OnHold) {
		$smarty->assign("user", $user);
		$smarty->assign("pid", $pid);
		$smarty->assign("cid", $cid);
		$smarty->assign("val", $val);
		$smarty->assign("open_fields", $name);
		$smarty->display("onhold.htm");
	} elseif ($Skip) {
		$smarty->assign("user", $user);
		$smarty->assign("pid", $pid);
		$smarty->assign("cid", $cid);
		$smarty->assign("c", "1");
		$smarty->assign("FROM", "U");
		if ($medit == 1) $smarty->assign("medit", $medit);
		else $smarty->assign("medit", 2);
		$smarty->assign("val", $val);
		$smarty->display("skip_page.htm");
	} else {
		$smarty->assign("from_skipped", $from_skipped);
		$user = getname($cid);
		if ($val == "new" || $val == "edit") {
			$time = time();
			$now = date('Y-m-d H:i:s', $time);
			$flag = $j = 0;

//Get A Lock
$lockingObj = new LockingService;
$key = $lockingObj->semgetLock(5678);
//Get A Lock

			if(!$profileid){
			if ($email_profileid == "") {
				if ($val == "new") $sql = "SELECT PROFILEID, ALLOTED_TO, ALLOT_TIME FROM jsadmin.MAIN_ADMIN WHERE SCREENING_TYPE='O' AND SCREENING_VAL=0 AND SKIP_FLAG = 'N' AND (ALLOTED_TO='$user' OR ALLOT_TIME < DATE_SUB('$now', INTERVAL 30 MINUTE)) ORDER BY RECEIVE_TIME ASC";
				else $sql = "SELECT PROFILEID, ALLOTED_TO, ALLOT_TIME FROM jsadmin.MAIN_ADMIN WHERE SCREENING_TYPE='O' AND SCREENING_VAL>0 AND  SKIP_FLAG = 'N' AND (ALLOTED_TO='$user' OR ALLOT_TIME < DATE_SUB('$now', INTERVAL 30 MINUTE)) ORDER BY RECEIVE_TIME ASC";
echo "<!--";echo $sql;echo "-->";
$ankit[] = $sql;
				$res = mysql_query_decide($sql) or die("$sql" . mysql_error_js());
				if ($row = mysql_fetch_array($res)) {
					do {
						$profileid = $row['PROFILEID'];
						$allot_time = $row['ALLOT_TIME'];
						if ($allot_time == $now) {
							$stop = 1;
							break;
						} else {
							$sql_u = "UPDATE jsadmin.MAIN_ADMIN set ALLOTED_TO='$user', ALLOT_TIME='$now' WHERE PROFILEID=$profileid AND SCREENING_TYPE='O' AND ALLOT_TIME<>'$now'";
							mysql_query_decide($sql_u) or die("$sql_u" . mysql_error_js());
							if (mysql_affected_rows_js()) {
								if ($val == "new") {
//									$sql_u = "UPDATE newjs.JPROFILE SET ACTIVATED='U' WHERE PROFILEID='$profileid'";
//									mysql_query_decide($sql_u) or die("$sql_u" . mysql_error_js());
                    markProfileUnderScreening($profileid);
								}
								$stop = 1;
								break;
							} else {
							$stop = 0;
							$profileid = '';
							}
						}
					}
					while ($row = mysql_fetch_array($res));
				}
			} if (!$stop && $email_profileid == "") {
				if ($val == "new") $sql = "SELECT J.PROFILEID,J.ACTIVATED, USERNAME, ENTRY_DT, MOD_DT, SUBSCRIPTION, SCREENING,'' AS PROID FROM newjs.JPROFILE J LEFT JOIN newjs.JPROFILE_CONTACT C ON J.PROFILEID=C.PROFILEID WHERE ACTIVATED='N' AND INCOMPLETE = 'N' AND MSTATUS != '' AND SCREENING<1099511627775 and SUBSCRIPTION<>'' and activatedKey=1  and MOD_DT < date_sub(now(), interval 10 minute) AND (J.MOB_STATUS='Y' OR J.LANDL_STATUS='Y' OR C.ALT_MOB_STATUS='Y') UNION SELECT J.PROFILEID, J.ACTIVATED, USERNAME, ENTRY_DT, MOD_DT, SUBSCRIPTION, SCREENING,A.PROFILEID AS PROID FROM newjs.JPROFILE J LEFT JOIN newjs.JPROFILE_CONTACT C ON J.PROFILEID=C.PROFILEID LEFT JOIN jsadmin.ACTIVATED_WITHOUT_YOURINFO A ON J.PROFILEID=A.PROFILEID WHERE A.PROFILEID IS NOT NULL AND INCOMPLETE = 'N' AND MSTATUS != '' AND SCREENING<1099511627775 and SUBSCRIPTION<>'' and activatedKey=1  and MOD_DT < date_sub(now(), interval 10 minute) AND (J.MOB_STATUS='Y' OR J.LANDL_STATUS='Y' OR C.ALT_MOB_STATUS='Y') ORDER BY ENTRY_DT ASC;";
				else $sql = "SELECT jp.PROFILEID, jp.USERNAME, jp.ENTRY_DT, jp.MOD_DT, jp.SUBSCRIPTION, jp.SCREENING FROM newjs.JPROFILE jp LEFT JOIN jsadmin.MAIN_ADMIN mad ON jp.PROFILEID=mad.PROFILEID LEFT JOIN jsadmin.ACTIVATED_WITHOUT_YOURINFO A ON jp.PROFILEID=A.PROFILEID WHERE mad.PROFILEID IS NULL AND (jp.ACTIVATED='Y' AND A.PROFILEID IS NULL)  AND jp.INCOMPLETE <> 'Y' AND jp.SUBSCRIPTION<>'' AND jp.SCREENING<1099511627775 and jp.activatedKey=1 and jp.MOD_DT < date_sub(now(), interval 10 minute) ORDER BY jp.MOD_DT ASC";
echo "<!--";echo $sql;echo "-->";
$ankit[] = $sql;
				$result = mysql_query_decide($sql) or die("$sql" . mysql_error_js());
				if ($myrow = mysql_fetch_array($result)) {
					do {
						$receivetime = $myrow['MOD_DT'];
						$submittime = newtime($receivetime, 0, $screen_time, 0);
						$profileid = $myrow['PROFILEID'];
						$username = $myrow['USERNAME'];
						$subscribe = $myrow['SUBSCRIPTION'];
						$screening_val = $myrow['SCREENING'];
						$activated_1 = $myrow['ACTIVATED'];
						if ($val == "new") {
							$screening_val = "0";
						}
						$sql_i = "INSERT IGNORE INTO jsadmin.MAIN_ADMIN (PROFILEID, USERNAME, RECEIVE_TIME, SUBMIT_TIME, ALLOT_TIME, ALLOTED_TO, SCREENING_TYPE, SUBSCRIPTION_TYPE, SCREENING_VAL) values('$profileid','" . addslashes($username) . "','$receivetime','$submittime','" . date("Y-m-d H:i") . "', '$user','O', '$subscribe','$screening_val')";
						mysql_query_decide($sql_i) or die("$sql_i" . mysql_error_js());
						if (mysql_affected_rows_js()) {
							if ($val == "new") {
//								$sql_u = "UPDATE newjs.JPROFILE SET ACTIVATED='U' WHERE PROFILEID='$profileid'";
//								mysql_query_decide($sql_u) or die("$sql_u" . mysql_error_js());
                                                            $activatedWithoutYourInfoCase = $myrow['PROID'];
            if(!$activatedWithoutYourInfoCase || $activated_1=='N'){
                markProfileUnderScreening($profileid);
                $smarty->assign("activatedWithoutYourInfo", 0);
            }
            else
                  $smarty->assign("activatedWithoutYourInfo", 1);
							}
							$stop = 1;
							break;
						} else {
						$stop = 0;
						$profileid = '';
						}
					}
					while ($myrow = mysql_fetch_array($result));
				}
				if (!$stop) {
					if ($val == "new") $sql = "SELECT J.PROFILEID, J.ACTIVATED,USERNAME, ENTRY_DT, MOD_DT, SUBSCRIPTION, SCREENING,'' AS PROID FROM newjs.JPROFILE J LEFT JOIN newjs.JPROFILE_CONTACT C ON J.PROFILEID=C.PROFILEID WHERE ACTIVATED='N' AND INCOMPLETE = 'N' AND MSTATUS !='' and activatedKey=1 and MOD_DT < date_sub(now(), interval 10 minute) AND (J.MOB_STATUS='Y' OR J.LANDL_STATUS='Y' OR C.ALT_MOB_STATUS='Y') AND J.SCREENING<1099511627775 UNION SELECT J.PROFILEID,J.ACTIVATED, USERNAME, ENTRY_DT, MOD_DT, SUBSCRIPTION, SCREENING,A.PROFILEID AS PROID FROM newjs.JPROFILE J LEFT JOIN newjs.JPROFILE_CONTACT C ON J.PROFILEID=C.PROFILEID LEFT JOIN jsadmin.ACTIVATED_WITHOUT_YOURINFO A ON J.PROFILEID=A.PROFILEID WHERE A.PROFILEID IS NOT NULL AND INCOMPLETE = 'N' AND MSTATUS !='' and activatedKey=1 and MOD_DT < date_sub(now(), interval 10 minute) AND (J.MOB_STATUS='Y' OR J.LANDL_STATUS='Y' OR C.ALT_MOB_STATUS='Y') AND J.SCREENING<1099511627775 ORDER BY ENTRY_DT ASC";
					else $sql = "SELECT jp.PROFILEID, jp.USERNAME, jp.ENTRY_DT, jp.MOD_DT, jp.SUBSCRIPTION, jp.SCREENING FROM newjs.JPROFILE jp LEFT JOIN jsadmin.MAIN_ADMIN mad ON jp.PROFILEID=mad.PROFILEID LEFT JOIN jsadmin.ACTIVATED_WITHOUT_YOURINFO A ON jp.PROFILEID=A.PROFILEID WHERE mad.PROFILEID IS NULL AND (ACTIVATED='Y' AND A.PROFILEID IS NULL) AND INCOMPLETE <> 'Y' AND SCREENING<1099511627775 and activatedKey=1 and MOD_DT < date_sub(now(), interval 10 minute) ORDER BY MOD_DT ASC";
echo "<!--";echo $sql;echo "-->";
$ankit[] = $sql;
					$result = mysql_query_decide($sql) or die(mysql_error_js());
					if ($myrow = mysql_fetch_array($result)) {
						do {
							$receivetime = $myrow['MOD_DT'];
							$submittime = newtime($receivetime, 0, $screen_time, 0);
							$profileid = $myrow['PROFILEID'];
							$username = $myrow['USERNAME'];
							$subscribe = $myrow['SUBSCRIPTION'];
							$screening_val = $myrow['SCREENING'];
							$activated_1 = $myrow['ACTIVATED'];
							if ($val == "new") {
								$screening_val = "0";
							}
							$sql_i = "INSERT IGNORE INTO jsadmin.MAIN_ADMIN (PROFILEID, USERNAME, RECEIVE_TIME, SUBMIT_TIME, ALLOT_TIME, ALLOTED_TO, SCREENING_TYPE, SUBSCRIPTION_TYPE, SCREENING_VAL) values('$profileid','" . addslashes($username) . "','$receivetime','$submittime','" . date("Y-m-d H:i") . "', '$user','O', '$subscribe','$screening_val')";
							mysql_query_decide($sql_i) or die("$sql_i" . mysql_error_js());
							if (mysql_affected_rows_js()) {
								if ($val == "new") {
//									$sql_u = "UPDATE newjs.JPROFILE SET ACTIVATED='U' WHERE PROFILEID='$profileid'";
//									mysql_query_decide($sql_u) or die("$sql_u" . mysql_error_js());
                                                                    $activatedWithoutYourInfoCase = $myrow['PROID'];
            if(!$activatedWithoutYourInfoCase || $activated_1=='N'){
                  markProfileUnderScreening($profileid);
                  $smarty->assign("activatedWithoutYourInfo", 0);
            }
            else
                  $smarty->assign("activatedWithoutYourInfo", 1);
								}
								$stop = 1;
								break;
							} else{ 
								$profileid='';
								$stop = 0;
							}
						}
						while ($myrow = mysql_fetch_array($result));
					}
				}
			} if ($email_profileid != "") $profileid = $email_profileid;
			}
//Release Lock         
if($ankit){ 
$ankit11 = implode("-----------",$ankit);
file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/screen1.txt",$profileid."--".$ankit11."\n\n",FILE_APPEND);
}
$lockingObj->semreleaseLock(5678);
//Release Lock
			
			if (!$profileid) {
				$msg.= "<br><p align=\"center\"><a href=\"screen_new.php?user=$user&cid=$cid&val=$val\">";
				$msg.= "Continue to next profile &gt;&gt;</a>";
				$msg.= "<br><br><a href=\"mainpage.php?user=$user&cid=$cid\">";
				$msg.= "Exit screening</a></p>";
				$smarty->assign("name", $user);
				$smarty->assign("cid", $cid);
				$smarty->assign("MSG", $msg);
				$smarty->display("jsadmin_msg.tpl");
				exit;
			}
//			$profileid = "3187961";
			$sql = "SELECT USERNAME, SCREENING,AGE,COUNTRY_RES,CITY_RES,MANGLIK,MTONGUE,RELIGION,CASTE,SUBCASTE,COUNTRY_BIRTH,CITY_BIRTH,GOTHRA,NAKSHATRA,MESSENGER_ID,YOURINFO,FAMILYINFO,SPOUSE,CONTACT,EDUCATION,EDU_LEVEL_NEW,PHONE_RES,PHONE_MOB,EMAIL,JOB_INFO,FATHER_INFO,SIBLING_INFO,PARENTS_CONTACT,ANCESTRAL_ORIGIN,PHONE_OWNER_NAME,MOBILE_OWNER_NAME,RELATION,SOURCE,SUBSCRIPTION,GENDER,MSTATUS,DTOFBIRTH,PHOTO_DISPLAY,PHONE_FLAG,COMPANY_NAME,HAVE_JCONTACT,HAVE_JEDUCATION,GOTHRA_MATERNAL,INCOME from newjs.JPROFILE where activatedKey=1 and PROFILEID=$profileid";
			$result = mysql_query_decide($sql) or die("$sql" . mysql_error_js());
			$myrow = mysql_fetch_array($result);

			//----------------jugad--------------------
			if($myrow["USERNAME"]==""){
				$sql = "DELETE FROM jsadmin.MAIN_ADMIN WHERE PROFILEID=$profileid AND SCREENING_TYPE='O'";
				$result = mysql_query_decide($sql) or die("$sql" . mysql_error_js());

				$oldUrl = curPageURL();
$lavesh  = $_GET["lavesh"];
if($lavesh)
	$lavesh+=1;
else
	$lavesh=1;
$oldUrl.="&lavesh=$lavesh";
				//$memcacheObj->setDataToMem(5,$key,0);
				unsetMemcache5Sec($user);
				header("Location:".$oldUrl);die;
			}
			//----------------jugad--------------------

			//**********************query added by Aman for screening Recheck on 15-05-2007*******************************************//
			//Added by Vibhor
			$sql_name = "SELECT NAME from incentive.NAME_OF_USER where PROFILEID=$profileid";
			$result_name = mysql_query_decide($sql_name) or die("$sql_name" . mysql_error_js());
			$myrow_name = mysql_fetch_array($result_name);
			$fname = addslashes(stripslashes($myrow_name['NAME']));
			$sql_rep = "REPLACE INTO SCREEN_TEMP_CHECK (PROFILEID,USERNAME,SUBCASTE,CITY_BIRTH,GOTHRA,NAKSHATRA,MESSENGER_ID,YOURINFO,FAMILYINFO,      SPOUSE,CONTACT,EDUCATION,PHONE_RES,PHONE_MOB,EMAIL,JOB_INFO,FATHER_INFO,SIBLING_INFO,PARENTS_CONTACT,NAME,ANCESTRAL_ORIGIN,PHONE_OWNER_NAME,             MOBILE_OWNER_NAME,DTOFBIRTH,PHOTO_DISPLAY,MSTATUS,PROFILE_HANDLER_NAME,GOTHRA_MATERNAL,COMPANY_NAME) SELECT PROFILEID,USERNAME,SUBCASTE,CITY_BIRTH,GOTHRA,NAKSHATRA,MESSENGER_ID,YOURINFO,FAMILYINFO,SPOUSE,CONTACT,EDUCATION,PHONE_RES,PHONE_MOB,EMAIL,JOB_INFO,FATHER_INFO,SIBLING_INFO,PARENTS_CONTACT,'$fname',ANCESTRAL_ORIGIN,PHONE_OWNER_NAME,MOBILE_OWNER_NAME,DTOFBIRTH,PHOTO_DISPLAY,MSTATUS,PROFILE_HANDLER_NAME,GOTHRA_MATERNAL,COMPANY_NAME from newjs.JPROFILE where activatedKey=1 and PROFILEID=$profileid";
			mysql_query_decide($sql_rep) or die("$sql_rep" . mysql_error_js());
			$diocese = '';
			if ($myrow["RELIGION"] == 3) {
				$sql_ch = "SELECT DIOCESE from newjs.JP_CHRISTIAN where PROFILEID=$profileid";
				$result_ch = mysql_query_decide($sql_ch) or die("$sql_ch" . mysql_error_js());
				$myrow_ch = mysql_fetch_array($result_ch);
				$diocese = $myrow_ch["DIOCESE"];
				if ($diocese) {
					$diocese = mysql_real_escape_string($diocese);
					$sql_rep = "UPDATE SCREEN_TEMP_CHECK SET GOTHRA='$diocese' where PROFILEID=$profileid";
					mysql_query_decide($sql_rep) or die("$sql_rep" . mysql_error_js());
				}
			}
			if ($myrow["HAVE_JEDUCATION"]) {
				$sql_edu = "SELECT * from newjs.JPROFILE_EDUCATION where PROFILEID=$profileid";
				$result_edu = mysql_query_decide($sql_edu) or die("$sql_edu" . mysql_error_js());;
				$myrow_edu = mysql_fetch_array($result_edu);
				if(is_array($myrow_edu))foreach($myrow_edu as $myrow_edu_key => $myrow_edu_val)
                                        $myrow_edu1[$myrow_edu_key]=mysql_real_escape_string($myrow_edu_val);
				$temp_ed = "UPDATE SCREEN_TEMP_CHECK set PG_COLLEGE='" . $myrow_edu1['PG_COLLEGE'] . "',OTHER_UG_DEGREE='" . $myrow_edu1['OTHER_UG_DEGREE'] . "',OTHER_PG_DEGREE='" . $myrow_edu1['OTHER_PG_DEGREE'] . "',SCHOOL='" . $myrow_edu1['SCHOOL'] . "',COLLEGE='" . $myrow_edu1['COLLEGE'] . "' where PROFILEID=$profileid";
				mysql_query_decide($temp_ed) or die("$temp_ed" . mysql_error_js());
			}
			if ($myrow["HAVE_JCONTACT"]) {
				$sql_contact = "SELECT * from newjs.JPROFILE_CONTACT where PROFILEID=$profileid";
				$result_contact = mysql_query_decide($sql_contact) or die("$sql_contact" . mysql_error_js());;
				$myrow_contact = mysql_fetch_array($result_contact);
				if(is_array($myrow_contact))foreach($myrow_contact as $myrow_contact_key => $myrow_contact_val)
					$myrow_contact1[$myrow_contact_key]=mysql_real_escape_string($myrow_contact_val);
				$temp_contact = "UPDATE SCREEN_TEMP_CHECK set BLACKBERRY='" . $myrow_contact1['BLACKBERRY'] . "',LINKEDIN_URL='" . $myrow_contact1['LINKEDIN_URL'] . "',FB_URL='" . $myrow_contact1['FB_URL'] . "',ALT_MOBILE_OWNER_NAME='" . $myrow_contact1['ALT_MOBILE_OWNER_NAME'] . "',ALT_MESSENGER_ID='" . $myrow_contact1['ALT_MESSENGER_ID'] .  "' where PROFILEID=$profileid";
				mysql_query_decide($temp_contact) or die("$temp_contact" . mysql_error_js());
			}
			$sql_hob = "SELECT FAV_FOOD,FAV_BOOK,FAV_VAC_DEST,FAV_MOVIE,FAV_TVSHOW from newjs.JHOBBY where PROFILEID=$profileid";
			$result_hob = mysql_query_decide($sql_hob) or die("$sql_hob" . mysql_error_js());;
			if ($result_hob) {
				$myrow_hob = mysql_fetch_array($result_hob);
				if(is_array($myrow_hob))foreach($myrow_hob as $myrow_hob_key => $myrow_hob_val)
					$myrow_hob1[$myrow_hob_key]=mysql_real_escape_string($myrow_hob_val);
				$temp_hob = "UPDATE SCREEN_TEMP_CHECK set FAV_MOVIE='" . $myrow_hob1['FAV_MOVIE'] . "',FAV_TVSHOW='" . $myrow_hob1['FAV_TVSHOW'] . "',FAV_FOOD='" . $myrow_hob1['FAV_FOOD'] . "',FAV_BOOK='" . $myrow_hob1['FAV_BOOK'] . "',FAV_VAC_DEST='" . $myrow_hob1['FAV_VAC_DEST'] . "' where PROFILEID=$profileid";
				mysql_query_decide($temp_hob) or die("$temp_hob" . mysql_error_js());
			}
			//**********************End of query**************************************************************************************//
			$smarty->assign("USERNAME", $myrow["USERNAME"]);
			//if(strstr($myrow["SOURCE"],"mb"))
			if (substr($myrow["SOURCE"], 0, 2) == "mb") $smarty->assign("BUREAU", "Y");
			$screen = $myrow['SCREENING'];
			if ($myrow['SUBSCRIPTION']) $smarty->assign("PAID", "Y");
			$smarty->assign("POSTED_BY", ($RELATIONSHIP["$myrow[RELATION]"]));
			$smarty->assign("SHOW_AGE", $myrow["AGE"]);
			if ($myrow["GENDER"] == "") {
				$critical_msg = print_r($myrow, true);
				//mail("ankit.aggarwal@jeevansathi.com","Gender Blank from JPROFILE","$critical_msg");
				
			}
			$income=FieldMap::getFieldLabel("income_level",$myrow["INCOME"]);
			$education=FieldMap::getFieldLabel("education",$myrow["EDU_LEVEL_NEW"]);
			$smarty->assign("SHOW_INCOME", $income);
			$smarty->assign("SHOW_EDUCATION", $education);
			$smarty->assign("SHOW_GENDER", $myrow["GENDER"]);
			$smarty->assign("SHOW_COUNTRY", label_select("COUNTRY_NEW", $myrow["COUNTRY_RES"]));
			$smarty->assign("SHOW_RELIGION", label_select("RELIGION", $myrow["RELIGION"]));
			$smarty->assign("SHOW_CASTE", label_select("CASTE", $myrow["CASTE"]));
			$smarty->assign("SHOW_COUNTRY_BIRTH", label_select("COUNTRY_NEW", $myrow["COUNTRY_BIRTH"]));
			$smarty->assign("SHOW_CITY_BIRTH", $myrow['CITY_BIRTH']);
			if ($myrow["COUNTRY_RES"] == '51') $smarty->assign("SHOW_CITYRES", label_select("CITY_INDIA", $myrow["CITY_RES"]));
			elseif ($myrow["COUNTRY_RES"] == '128') $smarty->assign("SHOW_CITYRES", label_select("CITY_USA", $myrow["CITY_RES"]));
			else $smarty->assign("SHOW_CITYRES", "");
			$smarty->assign("SHOW_MSTATUS", $MSTATUS["$myrow[MSTATUS]"]);
			$smarty->assign("SHOW_MTONGUE", label_select("MTONGUE", $myrow["MTONGUE"]));
			$subcaste_set = isFlagSet("SUBCASTE", $screen);
			$citybirth_set = isFlagSet("CITYBIRTH", $screen);
			$gothra_set = isFlagSet("GOTHRA", $screen);
			$nakshatra_set = isFlagSet("NAKSHATRA", $screen);
			$messenger_set = isFlagSet("MESSENGER_ID", $screen);
			$yourinfo_set = isFlagSet("YOURINFO", $screen);
			$familyinfo_set = isFlagSet("FAMILYINFO", $screen);
			$spouse_set = isFlagSet("SPOUSE", $screen);
			$contact_set = isFlagSet("CONTACT", $screen);
			$education_set = isFlagSet("EDUCATION", $screen);
			$phoneres_set = isFlagSet("PHONE_RES", $screen);
			$phonemob_set = isFlagSet("PHONE_MOB", $screen);
			$email_set = isFlagSet("EMAIL", $screen);
			$jobinfo_set = isFlagSet("JOB_INFO", $screen);
			$fatherinfo_set = isFlagSet("FATHER_INFO", $screen);
			$siblinginfo_set = isFlagSet("SIBLING_INFO", $screen);
			$parentscontact_set = isFlagSet("PARENTS_CONTACT", $screen);
			$uname_set = isFlagSet("USERNAME", $screen);
			$name_set = isFlagSet("NAME", $screen);
			$ancestral_set = isFlagSet("ANCESTRAL_ORIGIN", $screen);
			$phoneOwnerName_set = isFlagSet("PHONE_OWNER_NAME", $screen);
			$mobileOwnerName_set = isFlagSet("MOBILE_OWNER_NAME", $screen);
			$social_new_fields['COMPANY_NAME']['TBL'] = 'jpr';
			$social_new_fields['COMPANY_NAME']['LABEL'] = 'Name of Organization';
			$social_new_fields['PROFILE_HANDLER_NAME']['TBL'] = 'jpr';
			$social_new_fields['PROFILE_HANDLER_NAME']['LABEL'] = 'Person handling Profile';
			$social_new_fields['GOTHRA_MATERNAL']['TBL'] = 'jpr';
			$social_new_fields['GOTHRA_MATERNAL']['LABEL'] = 'Gothra (Maternal)';
			$social_new_fields['PG_COLLEGE']['TBL'] = 'edu';
			$social_new_fields['PG_COLLEGE']['LABEL'] = 'PG College';
			$social_new_fields['SCHOOL']['TBL'] = 'edu';
			$social_new_fields['SCHOOL']['LABEL'] = 'Name of School';
			$social_new_fields['COLLEGE']['TBL'] = 'edu';
			$social_new_fields['COLLEGE']['LABEL'] = 'Name of College';
			$social_new_fields['OTHER_UG_DEGREE']['TBL'] = 'edu';
			$social_new_fields['OTHER_UG_DEGREE']['LABEL'] = 'Other Graduation Degree';
			$social_new_fields['OTHER_PG_DEGREE']['TBL'] = 'edu';
			$social_new_fields['OTHER_PG_DEGREE']['LABEL'] = 'Other PG Degree';
			$social_new_fields['ALT_MOBILE_OWNER_NAME']['TBL'] = 'contact';
			$social_new_fields['ALT_MOBILE_OWNER_NAME']['LABEL'] = 'Alternate Mobile Owner';
			$social_new_fields['ALT_MESSENGER_ID']['TBL'] = 'contact';
			$social_new_fields['ALT_MESSENGER_ID']['LABEL'] = 'Alternate Messenger Id';
			$social_new_fields['LINKEDIN_URL']['TBL'] = 'contact';
			$social_new_fields['LINKEDIN_URL']['LABEL'] = 'LinkedIn Url/Id';
			$social_new_fields['FB_URL']['TBL'] = 'contact';
			$social_new_fields['FB_URL']['LABEL'] = 'Facebook Url/Id';
			$social_new_fields['BLACKBERRY']['TBL'] = 'contact';
			$social_new_fields['BLACKBERRY']['LABEL'] = 'Blackberry Pin';
			$social_new_fields['FAV_FOOD']['TBL'] = 'hob';
			$social_new_fields['FAV_FOOD']['LABEL'] = 'Food I Cook';
			$social_new_fields['FAV_TVSHOW']['TBL'] = 'hob';
			$social_new_fields['FAV_TVSHOW']['LABEL'] = 'Favourite TV Show';
			$social_new_fields['FAV_MOVIE']['TBL'] = 'hob';
			$social_new_fields['FAV_MOVIE']['LABEL'] = 'Favourite Movies';
			$social_new_fields['FAV_BOOK']['TBL'] = 'hob';
			$social_new_fields['FAV_BOOK']['LABEL'] = 'Favourite Books';
			$social_new_fields['FAV_VAC_DEST']['TBL'] = 'hob';
			$social_new_fields['FAV_VAC_DEST']['LABEL'] = 'Favourite Vacation Destination';
			//defining allowed continuous number's limit.
			$allowed_cont_num_len = 6;
			if ($val == "new") $item = array("GENDER", "MSTATUS", "DTOFBIRTH");
			else $item = array("GENDER");
			foreach ($social_new_fields as $key => $value) {
				if (!isFlagSet($key, $screen)) {
					switch ($value['TBL']) {
						case 'jpr':
							$item[] = $key;
							$openFields[$key]['Value'] = $myrow[$key];
						break;
						case 'edu':
							$item_edu[] = $key;
							$openFields[$key]['Value'] = $myrow_edu[$key];
						break;
						case 'contact':
							$item_contact[] = $key;
							$openFields[$key]['Value'] = $myrow_contact[$key];
						break;
						case 'hob':
							$item_hob[] = $key;
							$openFields[$key]['Value'] = $myrow_hob[$key];
							$openFields[$key]['type'] = 'A';
						break;
					}
					$openFields[$key]['LABEL'] = $social_new_fields[$key]['LABEL'];
					if (check_obscene_word($openFields[$key]['Value'])) $openFields[$key]['OBSCENE_ERR'] = 'Y';
					if ($key != 'ALT_MOBILE') if (check_for_continuous_numerics($openFields[$key]['Value'], $allowed_cont_num_len)) $openFields[$key]['EXCEED_ERR'] = 'Y';
					if (check_for_intelligent_usage($openFields[$key]['Value']) && false) $openFields[$key]['INTELLIGENT_USE_ERR'] = 'Y';
				}
			}
			$smarty->assign("openFields", $openFields);
			/********Code Added by sriram on May 22 2007********/
			$smarty->assign("MSTATUS", $myrow['MSTATUS']);
			$smarty->assign("PHOTO_PRIVACY", $myrow['PHOTO_DISPLAY']);
			//populate marital status.
			for ($i = 0;$i < count($MSTATUS);$i++) {
				if (1) //$MSTATUS[key($MSTATUS)] != "Separated" && $MSTATUS[key($MSTATUS)] != "Other")
				{
					$marital_status_arr[$i]["VALUE"] = key($MSTATUS);
					$marital_status_arr[$i]["LABEL"] = $MSTATUS[key($MSTATUS) ];
				}
				next($MSTATUS);
			}
			@sort($marital_status_arr);
			$smarty->assign("marital_status_arr", $marital_status_arr);
			//fetch date of birth
			list($year_of_birth, $month_of_birth, $day_of_birth) = explode("-", $myrow['DTOFBIRTH']);
			$smarty->assign("day_of_birth", $day_of_birth);
			$smarty->assign("month_of_birth", $month_of_birth);
			$smarty->assign("year_of_birth", $year_of_birth);
			populate_day_month_year();
			//added by sriram to create an array of all the OPEN FIELDS.
			//some fields in TABLE donot match the FLAGS_VAL array values, hence the if conditions.
			for ($i = 0;$i < count($FLAGS_VAL);$i++) {
				if (key($FLAGS_VAL) == "CITYBIRTH") $open_fields[] = "CITY_BIRTH";
				if (key($FLAGS_VAL) == "MESSENGER_ID") $open_fields[] = "MESSENGER_ID";
				if (key($FLAGS_VAL) == "PHONE_RES") $open_fields[] = "PHONE_RES";
				if (key($FLAGS_VAL) == "PHONE_MOB") $open_fields[] = "PHONE_MOB";
				else $open_fields[] = key($FLAGS_VAL);
				next($FLAGS_VAL);
			}
			for ($i = 0;$i < count($open_fields);$i++) {
				//check for obscene words.
				if ($myrow[$open_fields[$i]]) {
					//if(check_obscene_word($myrow[$open_fields[$i]]))
					if (check_obscene_word($myrow[$open_fields[$i]])) {
						$smarty->assign("OBSCENE_" . $open_fields[$i], "Y");
					}
				}
				//check for continuous numbers in open fields.
				if ($open_fields[$i] != "PHONE_RES" && $open_fields[$i] != "PHONE_MOB") {
					if (check_for_continuous_numerics($myrow[$open_fields[$i]], $allowed_cont_num_len)) $smarty->assign("EXCEED_NUMERIC_" . $open_fields[$i], "Y");
				}
				//check for proper usage of words.
				if ($open_fields[$i] != "EMAIL") {
					if (check_for_intelligent_usage($myrow[$open_fields[$i]])) {
						$smarty->assign("INTELLIGENT_USAGE_" . $open_fields[$i], "Y");
					}
				}
				if ($open_fields[$i] == "YOURINFO") {
					if (!check_for_minimum_character($myrow[$open_fields[$i]])) {
						$smarty->assign("MINIMUM_CHARACTERS_" . $open_fields[$i], "Y");
					}
				}
			}
			$obscene_message = "Please check this field for Obscene Words.";
			$exceed_numeric_message = "Please check this field for 6 or more continous numeric values.";
			$intelligent_usage_message = "Please check this field for proper usage of words.";
			$email_err_msg = "'$email_filled' is an invalid email address.";
			$mstatus_err_msg = "Please select marital status.";
			$date_err_msg = "Please select valid date.";
			$info_err_msg = "Please enter atleast 100 characters.";
			$minimum_characters_err_msg="'About me' should be at least 100 characters long. Copy from old 'about me' if required.";
			if ($email_err) $id = "m_id";
			elseif ($mstatus_err) $id = "m_id1";
			elseif ($date_err) $id = "m_id2";
			elseif ($info_err) $id = "info_span";
			$warning_message_start = "<tr class='fieldsnew'><td>&nbsp;</td><td id='$id'><font color='red'>";
			$warning_message_end = "</font></td></tr>";
			$smarty->assign("OBSCENE_MESSAGE", $warning_message_start . $obscene_message . $warning_message_end);
			$smarty->assign("EXCEED_NUMERIC_MESSAGE", $warning_message_start . $exceed_numeric_message . $warning_message_end);
			$smarty->assign("INTELLIGENT_USAGE_MESSAGE", $warning_message_start . $intelligent_usage_message . $warning_message_end);
			$smarty->assign("EMAIL_ERR_MSG", $warning_message_start . $email_err_msg . $warning_message_end);
			$smarty->assign("MSTATUS_ERR_MSG", $warning_message_start . $mstatus_err_msg . $warning_message_end);
			$smarty->assign("DATE_ERR_MSG", $warning_message_start . $date_err_msg . $warning_message_end);
			$smarty->assign("INFO_ERR_MSG", $warning_message_start . $info_err_msg . $warning_message_end);
			$smarty->assign("MINIMUM_CHARACTERS_ERR_MSG", $warning_message_start . $minimum_characters_err_msg . $warning_message_end);
			/*
				        if($val == "new")
				        $item[] = "GENDER";
				        
				        $item[] = "MSTATUS";
				        $item[] = "DTOFBIRTH";
				        $item[] = "PHOTO_DISPLAY";
			*/
			/*if($ADMIN)
				        {
				        $item[] = "DTOFBIRTH";
				        $item[] = "PHOTO_DISPLAY";
				        }*/
			/********End of - Code Added by sriram on May 22 2007********/
			if (!$uname_set) {
				$item[] = "USERNAME";
				$smarty->assign("SHOWUSERNAME", "Y");
				$smarty->assign("USERNAMEvalue", strip_tags($myrow['USERNAME']));
			} else $item[] = "USERNAME";
			if (!$subcaste_set) {
				$item[] = "SUBCASTE";
				$smarty->assign("SHOWSUBCASTE", "Y");
				$smarty->assign("SUBCASTEvalue", strip_tags($myrow['SUBCASTE']));
				$obsceneWord=getObsceneWords($myrow['SUBCASTE'],$obscene);
				$smarty->assign("OBSCENE_MESSAGE_SUBCASTE", $warning_message_start . $obscene_message ." {".$obsceneWord."}" . $warning_message_end);
			}
			if (!$citybirth_set) {
				$item[] = "CITY_BIRTH";
				$smarty->assign("SHOWCITY", "Y");
				$smarty->assign("CITY_BIRTHvalue", strip_tags($myrow['CITY_BIRTH']));
				$obsceneWord=getObsceneWords($myrow['CITY_BIRTH'],$obscene);
				$smarty->assign("OBSCENE_MESSAGE_CITYBIRTH", $warning_message_start . $obscene_message ." {".$obsceneWord."}" . $warning_message_end);
			}
			if (!$gothra_set) {
				$item[] = "GOTHRA";
				$smarty->assign("SHOWGOTHRA", "Y");
				if ($diocese) $smarty->assign("GOTHRAvalue", $diocese);
				else $smarty->assign("GOTHRAvalue", strip_tags($myrow['GOTHRA']));
				$obsceneWord=getObsceneWords($myrow['GOTHRA'],$obscene);
				$smarty->assign("OBSCENE_MESSAGE_GOTHRA", $warning_message_start . $obscene_message ." {".$obsceneWord."}" . $warning_message_end);
				}
			if (!$nakshatra_set) {
				$item[] = "NAKSHATRA";
				$smarty->assign("SHOWNAKSHATRA", "Y");
				$smarty->assign("NAKSHATRAvalue", strip_tags($myrow['NAKSHATRA']));
				$obsceneWord=getObsceneWords($myrow['NAKSHATRA'],$obscene);
				$smarty->assign("OBSCENE_MESSAGE_NAKSHATRA", $warning_message_start . $obscene_message ." {".$obsceneWord."}" . $warning_message_end);
			}
			if (!$messenger_set) {
				$item[] = "MESSENGER_ID";
				$smarty->assign("SHOWMESSENGER", "Y");
				$smarty->assign("MESSENGER_IDvalue", strip_tags($myrow['MESSENGER_ID']));
				$obsceneWord=getObsceneWords($myrow['MESSENGER_ID'],$obscene);
				$smarty->assign("OBSCENE_MESSAGE_MESSENGER", $warning_message_start . $obscene_message ." {".$obsceneWord."}" . $warning_message_end);

			}
			if (!$yourinfo_set) {
				$item[] = "YOURINFO";
				$smarty->assign("SHOWYOURINFO", "Y");
				$smarty->assign("YOURINFOvalue", strip_tags($myrow['YOURINFO']));
				$obsceneWord=getObsceneWords($myrow['YOURINFO'],$obscene);
				$smarty->assign("OBSCENE_MESSAGE_INFO", $warning_message_start . $obscene_message ." {".$obsceneWord."}" . $warning_message_end);
				if($val=="edit")
				{
					$sql = "SELECT YOUR_INFO_OLD from newjs.YOUR_INFO_OLD where PROFILEID=$profileid";
				$result = mysql_query_decide($sql) or die("$sql_ch" . mysql_error_js());
				$arr = mysql_fetch_array($result);
				$YOUR_INFO_OLD = $arr["YOUR_INFO_OLD"];
					if ($YOUR_INFO_OLD)
					{
						$smarty->assign("SHOW_OLD_INFO", "Y");
						$smarty->assign("YOUR_INFO_OLD", $YOUR_INFO_OLD);
					}
				}
			}
			if (!$familyinfo_set) {
				$item[] = "FAMILYINFO";
				$smarty->assign("SHOWFAMILYINFO", "Y");
				$smarty->assign("FAMILYINFOvalue", strip_tags($myrow['FAMILYINFO']));
					$obsceneWord=getObsceneWords($myrow['FAMILYINFO'],$obscene);
				$smarty->assign("OBSCENE_MESSAGE_FAMILY", $warning_message_start . $obscene_message ." {".$obsceneWord."}" . $warning_message_end);
			}
			if (!$spouse_set) {
				$item[] = "SPOUSE";
				$smarty->assign("SHOWSPOUSE", "Y");
				$smarty->assign("SPOUSEvalue", strip_tags($myrow['SPOUSE']));
				$obsceneWord=getObsceneWords($myrow['SPOUSE'],$obscene);
				$smarty->assign("OBSCENE_MESSAGE_SPOUSE", $warning_message_start . $obscene_message ." {".$obsceneWord."}" . $warning_message_end);

			}
			if (!$contact_set) {
				$item[] = "CONTACT";
				$smarty->assign("SHOWCONTACT", "Y");
				$smarty->assign("CONTACTvalue", strip_tags($myrow['CONTACT']));
				$obsceneWord=getObsceneWords($myrow['CONTACT'],$obscene);
				$smarty->assign("OBSCENE_MESSAGE_CONTACT", $warning_message_start . $obscene_message ." {".$obsceneWord."}" . $warning_message_end);

			}
			if (!$education_set) {
				$item[] = "EDUCATION";
				$smarty->assign("SHOWEDUCATION", "Y");
				$smarty->assign("EDUCATIONvalue", strip_tags($myrow['EDUCATION']));
				$obsceneWord=getObsceneWords($myrow['EDUCATION'],$obscene);
				$smarty->assign("OBSCENE_MESSAGE_EDUCATION", $warning_message_start . $obscene_message ." {".$obsceneWord."}" . $warning_message_end);

			}
			if (!$phoneres_set) {
				$item[] = "PHONE_RES";
				$smarty->assign("SHOWPHONERES", "Y");
				$smarty->assign("PHONE_RESvalue", strip_tags($myrow['PHONE_RES']));
			}
			if (!$phonemob_set) {
				$item[] = "PHONE_MOB";
				$smarty->assign("SHOWPHONEMOB", "Y");
				$smarty->assign("PHONE_MOBvalue", strip_tags($myrow['PHONE_MOB']));
			}
			if (!$phoneres_set || !$phonemob_set) {
				$smarty->assign("PHONE_FLAG", $myrow['PHONE_FLAG']);
			}
			if (!$email_set) {
				$item[] = "EMAIL";
				$smarty->assign("SHOWEMAIL", "Y");
				$smarty->assign("EMAILvalue", strip_tags($myrow['EMAIL']));
				$obsceneWord=getObsceneWords($myrow['EMAIL'],$obscene);
				$smarty->assign("OBSCENE_MESSAGE_EMAIL", $warning_message_start . $obscene_message ." {".$obsceneWord."}" . $warning_message_end);
			}
			if (!$jobinfo_set) {
				$item[] = "JOB_INFO";
				$smarty->assign("SHOWJOBINFO", "Y");
				$smarty->assign("JOB_INFOvalue", strip_tags($myrow['JOB_INFO']));
				$obsceneWord=getObsceneWords($myrow['JOB_INFO'],$obscene);
				$smarty->assign("OBSCENE_MESSAGE_JOBINFO", $warning_message_start . $obscene_message ." {".$obsceneWord."}" . $warning_message_end);
			}
			if (!$fatherinfo_set) {
				$item[] = "FATHER_INFO";
				$smarty->assign("SHOWFATHERINFO", "Y");
				$smarty->assign("FATHER_INFOvalue", strip_tags($myrow['FATHER_INFO']));
				$obsceneWord=getObsceneWords($myrow['FATHER_INFO'],$obscene);
				$smarty->assign("OBSCENE_MESSAGE_FATHERINFO", $warning_message_start . $obscene_message ." {".$obsceneWord."}" . $warning_message_end);
			}
			if (!$siblinginfo_set) {
				$item[] = "SIBLING_INFO";
				$smarty->assign("SHOWSIBLINGINFO", "Y");
				$smarty->assign("SIBLING_INFOvalue", strip_tags($myrow['SIBLING_INFO']));
				$obsceneWord=getObsceneWords($myrow['SIBLING_INFO'],$obscene);
				$smarty->assign("OBSCENE_MESSAGE_SIBLINGINFO", $warning_message_start . $obscene_message ." {".$obsceneWord."}" . $warning_message_end);
			}
			if (!$parentscontact_set) {
				$item[] = "PARENTS_CONTACT";
				$smarty->assign("SHOWPARENTSCONTACT", "Y");
				$smarty->assign("PARENTS_CONTACTvalue", strip_tags($myrow['PARENTS_CONTACT']));
				$obsceneWord=getObsceneWords($myrow['PARENTS_CONTACT'],$obscene);
				$smarty->assign("OBSCENE_MESSAGE_PARENTSCONTACT", $warning_message_start . $obscene_message ." {".$obsceneWord."}" . $warning_message_end);
			}
			if (!$name_set) {
				$sql_name = "SELECT NAME from incentive.NAME_OF_USER where PROFILEID=$profileid";
				$result_name = mysql_query_decide($sql_name) or die("$sql_name" . mysql_error_js());
				$myrow_name = mysql_fetch_array($result_name);
				$item_name = "NAME";
				$smarty->assign("SHOWNAME", "Y");
				$smarty->assign("NAMEvalue", $myrow_name['NAME']);
				$obsceneWord=getObsceneWords($myrow['NAME'],$obscene);
				$smarty->assign("OBSCENE_MESSAGE_NAME", $warning_message_start . $obscene_message ." {".$obsceneWord."}" . $warning_message_end);
			}
			if (!$ancestral_set) {
				$item[] = "ANCESTRAL_ORIGIN";
				$smarty->assign("SHOWANCESTRAL_ORIGIN", "Y");
				$smarty->assign("ANCESTRAL_ORIGINvalue", strip_tags($myrow['ANCESTRAL_ORIGIN']));
				$obsceneWord=getObsceneWords($myrow['ANCESTRAL_ORIGIN'],$obscene);
				$smarty->assign("OBSCENE_MESSAGE_ANCESTRAL", $warning_message_start . $obscene_message ." {".$obsceneWord."}" . $warning_message_end);
			}
			if (!$phoneOwnerName_set) {
				$item[] = "PHONE_OWNER_NAME";
				$smarty->assign("SHOWPHONE_OWNER_NAME", "Y");
				$smarty->assign("PHONE_OWNER_NAMEvalue", strip_tags($myrow['PHONE_OWNER_NAME']));
				$obsceneWord=getObsceneWords($myrow['PHONE_OWNER_NAME'],$obscene);
				$smarty->assign("OBSCENE_MESSAGE_PHONEOWNER", $warning_message_start . $obscene_message ." {".$obsceneWord."}" . $warning_message_end);
			}
			if (!$mobileOwnerName_set) {
				$item[] = "MOBILE_OWNER_NAME";
				$smarty->assign("SHOWMOBILE_OWNER_NAME", "Y");
				$smarty->assign("MOBILE_OWNER_NAMEvalue", strip_tags($myrow['MOBILE_OWNER_NAME']));
				$obsceneWord=getObsceneWords($myrow['MOBILE_OWNER_NAME'],$obscene);
				$smarty->assign("OBSCENE_MESSAGE_MOBILEOWNER", $warning_message_start . $obscene_message ." {".$obsceneWord."}" . $warning_message_end);
			}
			if (count($item) > 0) {
				$itemstring = implode(",", $item);
				$smarty->assign("names", $itemstring);
				$smarty->assign("fullname", $item_name);
			}
			if (count($item_edu) > 0) {
				$smarty->assign("names_edu", implode(",", $item_edu));
			}
			if (count($item_contact) > 0) {
				$smarty->assign("names_contact", implode(",", $item_contact));
			}
			if (count($item_hob) > 0) {
				$smarty->assign("names_hob", implode(",", $item_hob));
			}
			if ($myrow['MSTATUS'] == 'A') {
				$sql_a = "select REASON,SCREENED,COURT from newjs.ANNULLED where PROFILEID='$profileid'";
				$result_a = mysql_query_decide($sql_a) or die($sql_a . mysql_error_js());
				if ($row_a = mysql_fetch_row($result_a)) {
					if ($row_a[1] == 'N') {
						$smarty->assign("Annulled_Reason", $row_a[0]);
						$smarty->assign("SHOWANNULLED", "Y");
						$smarty->assign("Court", $row_a[2]);
						$smarty->assign("SHOWCOURT", "Y");
					}
				}
			}
			$smarty->assign("pid", $profileid);
			$smarty->assign("screen", $screen);
			$smarty->assign("cid", $cid);
			$smarty->assign("user", $user);
			$smarty->assign("medit", $medit);
			$smarty->assign("val", $val);
			$smarty->assign("email_err", $email_err);
			$smarty->assign("mstatus_err", $mstatus_err);
			$smarty->assign("date_err", $date_err);
			$smarty->assign("info_err", $info_err);
			$smarty->display("screen_new.htm");
		}
	}
} else {
	$msg = "Your session has been timed out<br><br>";
	$msg.= "<a href=\"index.htm\">";
	$msg.= "Login again </a>";
	$smarty->assign("MSG", $msg);
	$smarty->display("jsadmin_msg.tpl");
}
//screening_recheck function has been added to ensure that profile has not been changed during screening//
function screening_recheck($profileid) {
	$sql_rep = "SELECT USERNAME,PHOTO_DISPLAY,MSTATUS,SUBCASTE,CITY_BIRTH,NAKSHATRA,MESSENGER_ID,YOURINFO,FAMILYINFO,SPOUSE,CONTACT,EDUCATION,PHONE_RES,PHONE_MOB,EMAIL,JOB_INFO,FATHER_INFO,SIBLING_INFO,PARENTS_CONTACT,NAME,ANCESTRAL_ORIGIN,PHONE_OWNER_NAME,MOBILE_OWNER_NAME,COMPANY_NAME,PROFILE_HANDLER_NAME,GOTHRA_MATERNAL,FAV_FOOD,FAV_TVSHOW,FAV_MOVIE,FAV_BOOK,FAV_VAC_DEST,LINKEDIN_URL,FB_URL,BLACKBERRY,ALT_MOBILE_OWNER_NAME,ALT_MESSENGER_ID,OTHER_UG_DEGREE,OTHER_PG_DEGREE,SCHOOL,COLLEGE,PG_COLLEGE from jsadmin.SCREEN_TEMP_CHECK where PROFILEID=$profileid";
	$result_rep = mysql_query_decide($sql_rep) or die("$sql_rep" . mysql_error_js());
	$row_rep = mysql_fetch_assoc($result_rep);
	if (is_array($row_rep)) {
		//Added by Vibhor
		$sql_name = "SELECT NAME from incentive.NAME_OF_USER where PROFILEID=$profileid";
		$result_name = mysql_query_decide($sql_name) or die("$sql_name" . mysql_error_js());
		$myrow_name = mysql_fetch_assoc($result_name);
		$fname = addslashes(stripslashes($myrow_name['NAME']));
		$sql_jp = "SELECT USERNAME,PHOTO_DISPLAY,MSTATUS,SUBCASTE,CITY_BIRTH,NAKSHATRA,MESSENGER_ID,YOURINFO,FAMILYINFO,SPOUSE,CONTACT,EDUCATION,PHONE_RES,PHONE_MOB,EMAIL,JOB_INFO,FATHER_INFO,SIBLING_INFO,PARENTS_CONTACT,ANCESTRAL_ORIGIN,PHONE_OWNER_NAME,MOBILE_OWNER_NAME,COMPANY_NAME,PROFILE_HANDLER_NAME,GOTHRA_MATERNAL from newjs.JPROFILE where activatedKey=1 and PROFILEID=$profileid";
		$result_jp = mysql_query_decide($sql_jp) or die("$sql_jp" . mysql_error_js());
		$row_jp = mysql_fetch_assoc($result_jp);
		if (count(array_diff_assoc($row_jp, $row_rep)) > 0) return 1;
		$sql_edu="SELECT OTHER_UG_DEGREE,OTHER_PG_DEGREE,SCHOOL,COLLEGE,PG_COLLEGE from newjs.JPROFILE_EDUCATION where PROFILEID=$profileid";
		$result_edu = mysql_query_decide($sql_edu) or die("$sql_edu" . mysql_error_js());
		if ($result_edu) {
			$row_edu = mysql_fetch_assoc($result_edu);
			if($row_edu)
			if (count(array_diff_assoc($row_edu, $row_rep)) > 0) return 1;
		}
		$sql_contact="SELECT LINKEDIN_URL,FB_URL,BLACKBERRY,ALT_MOBILE_OWNER_NAME,ALT_MESSENGER_ID from newjs.JPROFILE_CONTACT where PROFILEID=$profileid";
		$result_contact = mysql_query_decide($sql_contact) or die("$sql_contact" . mysql_error_js());
		if ($result_contact) {
			$row_contact = mysql_fetch_assoc($result_contact);
			if($row_contact)
			if (count(array_diff_assoc($row_contact, $row_rep)) > 0) return 1;
		}
		$sql_hob = "SELECT FAV_FOOD,FAV_TVSHOW,FAV_MOVIE,FAV_BOOK,FAV_VAC_DEST from newjs.JHOBBY where PROFILEID=$profileid";
		$result_hob = mysql_query_decide($sql_hob) or die("$sql_hob" . mysql_error_js());
		if ($result_hob) {
			$row_hob = mysql_fetch_assoc($result_hob);
			if($row_hob)
			if (count(array_diff_assoc($row_hob, $row_rep)) > 0) return 1;
		}
	}
	return 0;
}
function delete_temp_screening($profileid) {
	$sql_del = "DELETE FROM jsadmin.SCREEN_TEMP_CHECK WHERE PROFILEID='$profileid'";
	mysql_query_decide($sql_del) or die($sql_del);
}
function username_gen() {
	$arr = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
	$username = '';
	$sql = "INSERT INTO newjs.AUTOID VALUES ('')";
	mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql, "ShowErrTemplate");
	$num = mysql_insert_id_js();
	$i = 0;
	$ind = strlen($num) - 4;
	$a = substr($num, 0, $ind);
	while ($i < strlen($a)) {
		$b = $a[$i] * 25;
		$ret = $b % 26;
		$username.= $arr[$ret];
		$i++;
	}
	$b = substr($num, $ind);
	$username.= $b;
	return $username;
}
function createChecksumForSearch($profileid) {
	$checksum = '';
	if ($profileid) {
		$start_tag = "start";
		$end_tag = "end";
		$checksum = md5($profileid) . "i" . $profileid;
		//$checksum=md5($start_tag.$profileid.$end_tag).$profileid;
		
	}
	return $checksum;
}
function should_be_incomplete($pid) {
	$count = 0;
	$sql_in = "SELECT GENDER,DTOFBIRTH,COUNTRY_RES,RELATION,HAVEPHOTO,YOURINFO from newjs.JPROFILE where activatedKey=1 and PROFILEID=$pid";
	$result_in = mysql_query_decide($sql_in) or die("$sql_in" . mysql_error_js());
	$row_in = mysql_fetch_array($result_in);
	if (is_array($row_in)) {
		$age = getAge($row_in["DTOFBIRTH"]);
		if ($row_in["GENDER"] == 'F' && $age > 24) $count = $count + 1;
		if ($row_in["GENDER"] == 'M' && $age > 27) $count = $count + 1;
		if ($row_in["RELATION"] == 2 || $row_in["RELATION"] == 3) $count = $count + 1;
		if ($row_in["HAVEPHOTO"] == 'Y') $count = $count + 1;
		if ($row_in["COUNTRY_RES"] == '22' || $row_in["COUNTRY_RES"] == '103' || $row_in["COUNTRY_RES"] == '126' || $row_in["COUNTRY_RES"] == '128') $count = $count + 1;
		if (strlen($row_in["YOURINFO"]) > 200) $count = $count + 1;
	}
	if ($count < 2) return 1;
	else return 0;
}
function getAge($newDob) {
	$today = date("Y-m-d");
	$datearray = explode("-", $newDob);
	$todayArray = explode("-", $today);
	$years = ($todayArray[0] - $datearray[0]);
	if (intval($todayArray[1]) < intval($datearray[1])) $years--;
	elseif (intval($todayArray[1]) == intval($datearray[1]) && intval($todayArray[2]) < intval($datearray[2])) $years--;
	return $years;
}
	function getObsceneWords($message,$obscene)
	{

		$string_removed_special_characters = preg_replace('/[^a-zA-Z0-9\'\s]/','',$message);
		$string_replaced_special_characters = preg_replace('/[^a-zA-Z\'\s]/', ' ', $message);
		$string_replaced_special_characters = preg_replace('/[\.]/', '', $string_replaced_special_characters);

		$messageArr = array_unique(array_merge(explode(" ",$string_removed_special_characters),explode(" ",$string_replaced_special_characters)));
   		$result = array_intersect($messageArr, $obscene);
   		$resultstr=implode(',',array_values($result));
   		return $resultstr;
	}
  
  /**
   * markProfileUnderScreening
   * @param type $iProfileID
   */
  function markProfileUnderScreening($iProfileID)
	  {
    global $screeningRep;
    if($screeningRep)
        $objUpdate = JProfileUpdateLib::getInstance("newjs_masterRep");
    else
        $objUpdate = JProfileUpdateLib::getInstance();
    $arrFields = array('ACTIVATED'=>'U');
    $result = $objUpdate->editJPROFILE($arrFields,$iProfileID,"PROFILEID");
    if(false === $result) {
      die('Mysql error while marking profile under screening at line 1410');
    }
  }
function unsetMemcache5Sec($user)
{
                include_once ("../../lib/model/lib/JsMemcache.class.php");
                $memcacheObj = new JsMemcache;
                $key = "PROF_SCREEN_USER_" . $user;
		$memcacheObj->remove($key);
                unset($memcacheObj);
}
function curPageURL() {
            $pageURL = 'http';
            if(isset($_SERVER["HTTPS"]))
            if ($_SERVER["HTTPS"] == "on") {
                $pageURL .= "s";
            }
            $pageURL .= "://";
            if ($_SERVER["SERVER_PORT"] != "80") {
                $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
            } else {
                $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
            }
            return $pageURL;
}
?>
