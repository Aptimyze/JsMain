<?php
$path=realpath(dirname(__FILE__)."/..");
include_once($path."/profile/connect.inc");
include_once($path."/profile/connect_reg.inc");
include_once($path."/profile/arrays.php");
include_once($path."/profile/screening_functions.php");
include_once($path."/profile/cuafunction.php");
include_once($path."/profile/hits.php");
include_once($path."/profile/registration_functions.inc");
include_once(JsConstants::$docRoot."/commonFiles/sms_inc.php");
//include_once($path."/sugarcrm/custom/crons/JsSuccessAutoRegEmail.php");
include_once($path."/classes/Jpartner.class.php");
include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");
include_once(JsConstants::$docRoot."/classes/ProfileInsertLib.php");
$db=connect_db();
$db1=$db;
//This function will validate email address and will return activated status if email already exists
function validate_email($email,&$is_error,&$errors){		   
				$email_flag = checkemail($email);
                                 $old_email_flag = checkoldemail($email);
                                 //$af_email_flag = checkemail_af($email);
				if($email=="")
				{
					$is_error++;
					$errors[]='email_err4';
				} 
				 elseif($email_flag == 1 || $af_email_flag == 1)
                                 {
				      $is_error++;
				      $errors[email_err]=1;
				 }
				 elseif($email_flag == 2 || $old_email_flag == 2 || $af_email_flag == 2) // For Existing email
                                 {
                                      $activated = get_profile_active_status($email);

				      $errors[]='email_err5';
				      $is_error++;
				 }
				 elseif($email_flag == 3 || $old_email_flag == 3)
                                 {
                                        $is_error++;
					$errors[]='email_err2';
                                 }
                                 elseif($email_flag == 4)
                                 {
                                        $is_error++;
					$errors[]='email_err3';
                                 }
        return $activated;                  
   		}
function validate_password($password,&$is_error,&$errors){
		$entered_password=$password;
	$password = trim($password);
                if(!$password)
                {
                        $is_error++;
			$errors[]='passwrd_err1';
                }
                elseif($password)
                {
                        if(strlen($password)>40 || strlen($password)<6)
                        {
                                $is_error++;
				$errors[]='passwwrd_err2';
                        }
                        elseif($entered_password != $password)
                        {
                                $is_error++;
				$errors[]='passwrd_err3';
                        }
				}
}

function validate_gender($gender,&$is_error,&$errors){
                if(!$gender)
                {
                        $is_error++;
			$errors[]='gender_err1';
                }
}
function validate_dob($day,$month,$year,&$is_error,$gender,&$errors){
                if(!$day || !$month || !$year)
                {
                        $is_error++;
			$errors[]='dtOfBirth_err1';
                }
                elseif(!checkdate($month,$day,$year))
                {
                        $is_error++;
			$errors[]='dtOfBirth_err1';
                }
 		else
                {
	                $date_of_birth = $year."-".$month."-".$day;
                        $age = getAge($date_of_birth);

                        if($gender == "M" && $age < 21)
                        {
                                $is_error++;
				$errors[]='dtOfBirth_err2';
                        }
                        elseif($gender == "F" && $age < 18)
                        {
                                $is_error++;
				$errors[]='dtOfBirth_ERR3';
                        }
                }
				return $age;
}
function validate_maritalStatus($mstatus, $has_children,$gender,&$is_error,&$errors){
	if(!$mstatus)
                {
                        $is_error++;
			$errors[]='mstatus_err1';
			$errors[]='mstatus_err11';
                }
		if($mstatus !='N' && !$has_children)
		{
			$is_error++;
			$errors[]='has_children_err1';
		}
		if($mstatus=='M' && $gender=='F')
		{
			$is_error++;
			$errors[]='mstatus_err1';
			$errors[]='mstatus_err31';
		}
}
function validate_country($country_residence,&$is_error,&$errors)
{
                if($country_residence=="")
                {
                        $is_error++;
			$errors[]='countryResidence_err1';
                }
}

function validate_city($city_residence,&$is_error,&$errors)
{
				if($city_residence==="" )
				{
					$is_error++;	
					//$city_residence = '0';
					$errors[]='cityresidence_err1';
					//$is_error++; //commented in previous code also
				}
}
function validate_pincode($pincode,&$is_error,&$errors)
{
	$pinInititial = substr($pincode, 0, 4);
	//if(!($pinInititial == "1100" || $pinInititial == "2013" || $pinInititial == "1220" || $pinInititial == "2010" ||  $pinInititial =="1210" || $pinInititial == "1245"))
	//	$err=1;
	if($pincode=="" || strlen($pincode)!=6)
		$err=1;
	if($err)
	{
		$is_error++;
		$errors[]='pincode_err1';
	}
}
function validate_address(&$country_residence,&$city_residence,&$is_error,&$errors){
		$country_residence_val = explode("|x|",$country_residence);
                $country_residence_val = explode("|}|",$country_residence_val[0]);
                $country_residence = $country_residence_val[1];
                if($country_residence=="")
                {
                        $is_error++;
			$errors[]='countryresidence_err1';
                }

		//blank the city field if country is not india or usa
		if($country_residence!=51 && $country_residence!=128)
			$city_residence="";

 		if($city_residence!='0')
		{
			$city_residence_val = explode("|{|",$city_residence);
	                $city_residence = $city_residence_val[1];
			if($country_residence==51 || $country_residence==128)
			{
				if($city_residence=="" )
				{
					$is_error++;	
					//$city_residence = '0';
					$errors[]='cityresidence_err1';
					//$is_error++; //commented in previous code also
				}
			}
			}	
}
function validate_contact_number($phone,$mobile,$state_code,$country_code,$country_res,$country_code_mob,$showmobile,$showphone,&$is_error,&$errors){
                        if(!$phone)
                        {
                                if(!$mobile)
                                {
                                        $is_error++;
					$errors[]='phone_err1';
                                }
                        }
                        else
                        {
                                if($country_code=="")
                                {
                                        $is_error++;
					$errors[]='country_code_err1';
                                }
                                elseif($country_code=="" && checkrphone($country_code))
                                {
                                        $is_error++;
					$errors[]='country_code_err2';
                                }

                                if($country_res == "51")
                                {
                                        if(!$state_code)
                                        {
                                                $is_error++;
						$errors[]='state_code_err1';
                                        }
 					elseif($state_code && checkrphone($state_code))
                                        {
                                                $is_error++;
						$errors[]='state_code_err2';
                                        }
                                }
                                if($phone && checkrphone($phone))
                                {
                                        $is_error++;
					$errors[]='phone_err2';
                                }
                                if(!$showphone)
                                {
                                        $is_error++;
					$errors[]='show_phone_err1';
                                }
                        }
			if(!$mobile)
                        {
                                if(!$phone)
                                {
                                        $is_error++;
					$errors[]='phone_err1';
                                }
                        }
                        else
                        {
                                if($country_code_mob=="")
                                {
                                        $is_error++;
					$errors[]='country_code_mobile_err1';
                                }
                                elseif($country_code_mobile=="" && checkrphone($country_code_mob))
                                {
                                        $is_error++;
					$errors[]='country_code_mobile_err2';
                                }
 				elseif($mobile && checkrphone($mobile))
                                {
                                        $is_error++;
					$errors[]='mobile_err2';
                                }

                                if(!$showmobile)
                                {
                                        $is_error++;
					$errors[]='show_mobile_err1';
                                }
                        }
}
function validate_mtongue($mtongue,&$is_error,&$errors){
		if(!$mtongue)
                {
                        $is_error++;
			$errors[]='mtongue_err1';
				}
}
function validate_jamaat($jamaat,&$is_error,&$errors){
 		if(!$jamaat)
                 {
                         $is_error++;
 			$errors[]='jamaat_err1';
 				}
 }
function validate_casteMuslim($casteMuslim,&$is_error,&$errors){
		if(!$casteMuslim)
                {
                        $is_error++;
			$errors[]='casteMuslim_err1';
				}
}
function validate_relationship($relationship,&$is_error,&$errors){
	if(!$relationship)
	{
		$is_error++;
		$errors[]='relationship_err1';
	}
}
function validate_height($height, &$is_error,&$errors){
	  if(!$height)
	  {      
		  $is_error++;
		  $errors[]='height_err1';
	  }
}
//It will return weither to check partner caste
function validate_religionandcaste($religion,&$is_error,&$caste,&$errors,$from_sugar){
	global $db;
	$res=true;
if(!$religion)
                {
                        $is_error++;
			$errors[]='religion_err1';
                }
                elseif($religion)
                {
					if($from_sugar=='N'){

                        $religion_temp = explode("|X|",$religion);
						$religion_val = $religion_temp[0];
					}
					else 
						$religion_val=$religion;
                        $check_partner_caste = 1;
                        if($religion_val == "5" || $religion_val == "6" || $religion_val == "7" || $religion_val == "8"|| $religion_val =="10")
                        {
                                $sql = "select value from newjs.CASTE where parent='$religion_val'";
                                $res = mysql_query_decide($sql) or logerror("due to some temporary problem your request could not be processed. please try after some time.",$sql,"showerrtemplate");
                                $row = mysql_fetch_array($res);
                                $caste = $row["value"];
                                $check_partner_caste = 0;
                        }
				}
		if($caste=="")
        {
           	$is_error++;
			$errors[]='caste_err1';
        }
		else
		{
			$string="\n".$db." db String";
			error_log($string,3,JsConstants::$docRoot . "/profile/logerror.txt");

			   $sql = "select parent,small_label from newjs.CASTE where value='$caste'";
                           $result = mysql_query_decide($sql) or logerror("due to some temporary problem your request could not be processed. please try after some time.",$sql,"showerrtemplate");
                           $myrow = mysql_fetch_array($result);
                           $caste_label = $myrow["small_label"];
                           if($religion != "" && $myrow['parent'] != $religion_val)
                           {
                                   $is_error++;
		         	   $errors[]='caste_err1';
                           }
		}
return $check_partner_caste;
}
function if_blank($values){
	foreach($values as $key => $value ){
		if($key!="phone" || $key != "mobile" || $key !="showmobile" || $key != "showphone"){
			if(!$value)
			return 0;
			return 1;
		}
			elseif($key=="phone"&& $value)
				$if_contact=1;
			elseif($key=="mobile"&& $value)
				$if_contact=1;
	}
		if(!$if_contact)
			return 0;
		return 1;
}
function register_user($post_values)	{
//function not in use
        mail("kunal.test02@gmail.com","auto_reg_functions.php :: register_user() in USE",print_r($_SERVER,true));
	global $smarty;
	global $protect_obj;
	$cookie=array();
$now = date("Y-m-d G:i:s");
$today=date("Y-m-d H:i:s");
	                $date_of_birth = $post_values['year']."-".$post_values['month']."-".$post_values['day'];
                        $age = getAge($date_of_birth);
                        $religion_temp = explode("|X|",$post_values['religion']);
                        $religion_val = $religion_temp[0];
		 	$sql = "SELECT LABEL FROM newjs.HEIGHT WHERE VALUE='".$post_values['height']."'";
                        $res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                        $myrow_height=mysql_fetch_array($res);
                        $height_label=$myrow_height["LABEL"];
                        $height_label=substr($height_label,0,10);

            /*TODO            $sql="SELECT LABEL FROM newjs.CITY_NEW WHERE VALUE='".$post_values['city_residence']."'";
                        $res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

                        $myrow_city=mysql_fetch_array($res);
                        $city_label=$myrow_city["LABEL"];
			 */
                        if($post_values['gender']=='M')
                        $gender_key="Male";
                        elseif($post_values['gender']=='F')
                        $gender_key="Female";

                     //TODO   $keyword=addslashes(stripslashes($gender_key.",".$age.",".$caste_label.",".$height_label.",".$city_label));
                        $keyword=addslashes(stripslashes($gender_key.",".$age.",".$caste_label.",".$height_label));
						$username=generate_userName();
				//		if($username)logError("Some problem ".$username,"dfsdfg","ShowErrTemplate");
		/*				while(1)
			{
				$past_values['username=username_gen();
				$sql="SELECT COUNT(*) as cnt FROM JPROFILE WHERE USERNAME='$past_values['username'";
				$res_username=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
				$row_username=mysql_fetch_array($res_username);

				$sql="SELECT COUNT(*) as cnt FROM JPROFILE_AFFILIATE WHERE USERNAME='$past_values['username'";
	                        $res_username2=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
	                        $row_username2=mysql_fetch_array($res_username2);
			
				 if($row_username['cnt']==0 && $row_username2['cnt']==0)
					break;
			}*/

			/* If offline user is registring the profile, then no mailers should be promted */
/*			if($cookie['OPERATOR']!="" && $post_values['source']!='onoffreg')
			{
				if($post_values['tieup_source']=='101')
				{
					$activated='Y';
				}
				else
				{
					$post_values['tieup_source']="ofl_prof";
					$activated='Y';
				}
				// till here
				$incomplete='N';
				$match_alerts='N';
				$promo='N';
				$service_messages='N';
				$ANNULLED_SCREEN='Y';
				$SCREENING=131071;
				send_email("nikhil.dhiman@jeevansathi.com","testmessage","1--".$profileid."---".$cookie['OPERATOR']."-----".$post_values['source']."-----".$post_values['tieup_source'],"offline@jeevansathi.com");

			}
			else if($cookie['OPERATOR']!="" && $post_values['source']=='onoffreg')
			{
				$service_messages='Y';
				$incomplete='N';
				$match_alerts='N';
				$promo='N';
			}
			else
			{
				$incomplete='Y';
				$activated='N';
				$ANNULLED_SCREEN='N';
				$SCREENING=0;
			}

			
			$country_codes = explode('+',$post_values['country_code']);
			$post_values['country_code'] = $country_codes[1];

 */
			 if($post_values['relationship']=='2')
			 	$smarty->assign("yourHeading","your Son");
			 elseif($post_values['relationship']=='2D')
			 	$smarty->assign("yourHeading","your Daughter");
			 elseif($post_values['relationship']=='6' || $post_values['relationship']=='6D')
			 	$smarty->assign("yourHeading","your Sibling");
			 elseif($post_values['relationship']=='4')
			 	$smarty->assign("yourHeading","your Relative");
			 elseif($post_values['relationship']=='5')
			 	$smarty->assign("yourHeading","your Client");

			 if($post_values['relationship']=='2D')
                               $post_values['relationship']= '2';
	                 elseif($post_values['relationship']=='6')
	                       $post_values['relationship']= '3';
	                 elseif($post_values['relationship']=='6D')
			        $post_values['relationship']= '3';

			 if($post_values['mstatus']=='N')
			       $post_values['has_children']='';

			 if($post_values['service_messages']=='')
			       $post_values['service_messages']='U';

			 if($post_values['promo']=='')
			       $post_values['promo']='U';

			 if($post_values['match_alerts']=='')
			       $post_values['match_alerts']='U';

			$post_values['email']=trim($post_values['email']);
		/* start: Added by Esha for password encrytion on entry in JPROFILE*/
			include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
			$post_values['password'] = PasswordHashFunctions::createHash($post_values['password']);
		/* end: Added by Esha for password encrytion on entry in JPROFILE*/
			$arrFields = array(
				"RELATION" => $post_values['relationship'],
				"EMAIL" => $post_values['email'],
				"PASSWORD" => $post_values['password'],
				"USERNAME" => $username,
				"GENDER" => $post_values['gender'],
				"DTOFBIRTH" => $date_of_birth,
				"MSTATUS" => $post_values['mstatus'],
				"HAVECHILD" => $post_values['has_children'],
				"HEIGHT" => $post_values['height'],
				"COUNTRY_RES" => $post_values['country_residence'],
				"CITIZENSHIP" => '',
				"ISD" => $post_values['country_code'],
				"STD" => $post_values['state_code'],
				"CITY_RES" => $post_values['city_residence'],
				"PHONE_RES" => $post_values['phone'],
				"SHOWPHONE_RES" => $post_values['showphone'],
				"PHONE_NUMBER_OWNER" => '',
				"PHONE_OWNER_NAME" => '',
				"PHONE_MOB" => $post_values['mobile'],
				"SHOWPHONE_MOB" => $post_values['showmobile'],
				"MOBILE_NUMBER_OWNER" => '',
				"MOBILE_OWNER_NAME" => '',
				"TIME_TO_CALL_START" => '',
				"TIME_TO_CALL_END" => '',
				"EDU_LEVEL_NEW" => $post_values['degree'],
				"OCCUPATION" => $post_values['occupation'],
				"INCOME" => $post_values['income'],
				"MTONGUE" => $post_values['mtongue'],
				"RELIGION" => $post_values['religion'],
				"SPEAK_URDU" => $post_values['speak_urdu'],
				"CASTE" => $post_values['caste'],
				"PERSONAL_MATCHES" => $post_values['match_alerts'],
				"PROMO_MAILS" => $post_values['promo'],
				"SERVICE_MESSAGES" => $post_values['service_messages'],
				"ENTRY_DT" => $now,
				"MOD_DT" => $now,
				"LAST_LOGIN_DT" => $today,
				"SORT_DT" => $now,
				"AGE" => $age,
				"IPADD" => $post_values['ip'],
				"SOURCE" => $post_values['tieup_source'],
				"ACTIVATED" => 'N',
				"INCOMPLETE" => 'Y',
				"KEYWORDS" => $keyword,
				"SCREENING" => 0,
				"YOURINFO" => '',
				"CRM_TEAM" => 'online',
			);
			$objInsert = ProfileInsertLib::getInstance();
			$result = $objInsert->insertJPROFILE($arrFields);
			if(false === $result) {
				$sql = "INSERT INTO JPROFILE (RELATION,EMAIL,PASSWORD,USERNAME,GENDER,DTOFBIRTH,MSTATUS,HAVECHILD,HEIGHT,COUNTRY_RES,CITIZENSHIP,ISD,STD,CITY_RES,PHONE_RES,SHOWPHONE_RES,PHONE_NUMBER_OWNER,PHONE_OWNER_NAME,PHONE_MOB,SHOWPHONE_MOB,MOBILE_NUMBER_OWNER,MOBILE_OWNER_NAME,TIME_TO_CALL_START,TIME_TO_CALL_END,EDU_LEVEL_NEW,OCCUPATION,INCOME,MTONGUE,RELIGION,SPEAK_URDU,CASTE,PERSONAL_MATCHES,PROMO_MAILS,SERVICE_MESSAGES,ENTRY_DT,MOD_DT,LAST_LOGIN_DT,SORT_DT,AGE,IPADD,SOURCE,ACTIVATED,INCOMPLETE,KEYWORDS,SCREENING,YOURINFO,CRM_TEAM) VALUES('".$post_values['relationship']."','".$post_values['email']."','".$post_values['password']."','".$username."','".$post_values['gender']."','".$date_of_birth."','".$post_values['mstatus']."','".$post_values['has_children']."','".$post_values['height']."','".$post_values['country_residence']."','','".$post_values['country_code']."','".$post_values['state_code']."','".$post_values['city_residence']."','".$post_values['phone']."','".$post_values['showphone']."','','','".$post_values['mobile']."','".$post_values['showmobile']."','','','','','".$post_values['degree']."','".$post_values['occupation']."','".$post_values['income']."','".$post_values['mtongue']."','".$post_values['religion']."','".$post_values['speak_urdu']."','".$post_values['caste']."','".$post_values['match_alerts']."','".$post_values['promo']."','".$post_values['service_messages']."','".$now."','".$now."','".$today."','".$now."','".$age."','".$post_values['ip']."','".$post_values['tieup_source']."','N','Y','".$keyword."',0,'','online')";
				logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			}
//			$sql = "INSERT INTO JPROFILE (RELATION,EMAIL,PASSWORD,USERNAME,GENDER,DTOFBIRTH,MSTATUS,HAVECHILD,HEIGHT,COUNTRY_RES,CITIZENSHIP,ISD,STD,CITY_RES,PHONE_RES,SHOWPHONE_RES,PHONE_NUMBER_OWNER,PHONE_OWNER_NAME,PHONE_MOB,SHOWPHONE_MOB,MOBILE_NUMBER_OWNER,MOBILE_OWNER_NAME,TIME_TO_CALL_START,TIME_TO_CALL_END,EDU_LEVEL_NEW,OCCUPATION,INCOME,MTONGUE,RELIGION,SPEAK_URDU,CASTE,PERSONAL_MATCHES,PROMO_MAILS,SERVICE_MESSAGES,ENTRY_DT,MOD_DT,LAST_LOGIN_DT,SORT_DT,AGE,IPADD,SOURCE,ACTIVATED,INCOMPLETE,KEYWORDS,SCREENING,YOURINFO,CRM_TEAM) VALUES('".$post_values['relationship']."','".$post_values['email']."','".$post_values['password']."','".$username."','".$post_values['gender']."','".$date_of_birth."','".$post_values['mstatus']."','".$post_values['has_children']."','".$post_values['height']."','".$post_values['country_residence']."','','".$post_values['country_code']."','".$post_values['state_code']."','".$post_values['city_residence']."','".$post_values['phone']."','".$post_values['showphone']."','','','".$post_values['mobile']."','".$post_values['showmobile']."','','','','','".$post_values['degree']."','".$post_values['occupation']."','".$post_values['income']."','".$post_values['mtongue']."','".$post_values['religion']."','".$post_values['speak_urdu']."','".$post_values['caste']."','".$post_values['match_alerts']."','".$post_values['promo']."','".$post_values['service_messages']."','".$now."','".$now."','".$today."','".$now."','".$age."','".$post_values['ip']."','".$post_values['tieup_source']."','N','Y','".$keyword."',0,'','online')";
//			mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");

			$id=mysql_insert_id_js();
			//Added By Lavesh Rawat for Sharding Purpose
			assignServerToProfile($id);
			//Added By Lavesh Rawat for Sharding Purpose
			$profileid=$id;
		/* Tracking Query for the Reg Count */
		$sql = "INSERT INTO MIS.REG_COUNT(PROFILEID,PAGE1) VALUES ('$profileid','Y')";
		mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		/* Sending to the 2nd Page for use in 3rd Page */

		$smarty->assign("PROFILEID",$profileid);
		$smarty->assign("RELIGION",$religion_val);
		$smarty->assign("CASTE",$post_values['caste']);

		/* Ends Here */

		// Inserting Default Entries in Jpartner 

		$jpartnerObj=new Jpartner;
		$mysqlObj=new Mysql;
		
		if(!$myDb)
		{
		        $myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
		        $myDb=$mysqlObj->connect("$myDbName");
		}

	        $jpartnerObj->setPROFILEID($profileid);

		if($post_values['gender']=='M')
		     $jpartnerObj->setGENDER('F');
		else
		     $jpartnerObj->setGENDER('M');
	
		if($post_values['gender']=='M')
	        {
                       if($age<25)
			       	$lage=18;
		       else
			       	$lage=$age-5;
			$hage=$age;
		}
		else
		{
			$lage=($age>29)?$age-2:(($age>26)?$age-1:(($age>22)?$age:21));
			$hage=($age>33)?$age+15:(($age==33)?47:(($age==32)?44:(($age==31)?42:$age+10)));
/*
			$hage=$age+5;
			if($post_values['age']<21)
				$lage=21;
			else
				$lage=$post_values['age'];
			if($hage > 70)
				$hage = 70;
*/		}
						
                $jpartnerObj->setLAGE($lage);
                $jpartnerObj->setHAGE($hage);
			
		if($post_values['gender']=='M')
		{
			$lheight=$post_values['height']-10;
			$hheight=$post_values['height'];
		}
		else
		{
			$lheight=$post_values['height'];
			$hheight = $post_values['height']+10;
		}	

                $jpartnerObj->setLHEIGHT($lheight);
                $jpartnerObj->setHHEIGHT($hheight);
                $jpartnerObj->setDPP('R');

		$MTONGUE=array(10,7,33,19,28,13);

		//Insert into partner table if mtongue selected is within all hindi mtongue

		if(in_array($post_values['mtongue'],$MTONGUE))
		{
			foreach($MTONGUE as $key=>$val)
				$mtongue_val.="'".$val."',";
			$mtongue_val=substr($mtongue_val,0,strlen($mtongue_val)-1);
		}
		else
			$mtongue_val="'".$post_values['mtongue']."'";
		
		$jpartnerObj->setPARTNER_MTONGUE($mtongue_val);
        $religion_val=$post_values['religion'];
		$religion_partner.="'".$religion_val."'"."'";
		$religion_partner=substr($religion_partner,0,strlen($religion_partner)-1);
		$jpartnerObj->setPARTNER_RELIGION($religion_partner);
		
		$caste_community = $post_values['caste']."-".$post_values['mtongue'];
		$sql = "SELECT MAP FROM newjs.CASTE_COMMUNITY_MAPPING WHERE CASTE_COMMUNITY = '$caste_community'";
		$res = mysql_query_decide($sql) or die(mysql_error()) or logError("error",$sql);
		$row = mysql_fetch_assoc($res);
		if($row)
		{
			$caste_community_arr = @explode(",",$row['MAP']);
			for($i=0;$i<count($caste_community_arr);$i++)
			{
				$temp_caste_arr = @explode("-",$caste_community_arr[$i]);
				if(!@in_array($temp_caste_arr[0],$mapped_caste_arr))
					$mapped_caste_arr[] = $temp_caste_arr[0];
			}
		}

		if(is_array($mapped_caste_arr))
		{
			if(!in_array($caste,$mapped_caste_arr))
			      $mapped_caste_arr[]=$post_values['caste'];
		}
		else      
			      $mapped_caste_arr[]=$post_values['caste'];

		$mapped_caste="'".@implode("','",$mapped_caste_arr)."'";
		$jpartnerObj->setPARTNER_CASTE($mapped_caste);
 
		if($post_values['mstatus']=="N")
			$jpartnerObj->setPARTNER_MSTATUS("'".$post_values['mstatus']."'");

		$jpartnerObj->updatePartnerDetails($myDb,$mysqlObj);
		
		/* Default Jpartner Insertion Ends Here */

		//Added by neha verma to store registration caused by sources leading to home page
	/*	if(isset($_COOKIE['JS_SOURCE_HOME']))
		{
			$source = $_COOKIE['JS_SOURCE_HOME'];
			$sql = "INSERT INTO MIS.REG_HOME (DATE,SOURCEID,PROFILEID) VALUES('$now','$source','$id')";
			$res= mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			setcookie("JS_SOURCE_HOME","",time() - 3600,"/");
		}*/
		//end of code added by neha
			
		//added by Neha Verma for archiving contact info
		//EMAIL

		if($post_values['email']!='')
		{
			$sql_id= "INSERT INTO newjs.CONTACT_ARCHIVE (PROFILEID,FIELD) VALUES($id,'EMAIL')";
			$res_id= mysql_query_decide($sql_id) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_id,"ShowErrTemplate");

			$changeid=mysql_insert_id_js();
			$sql_info= "INSERT INTO newjs.CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,NEW_VAL) VALUES($changeid,'$now','$ip','".$post_values['email']."')";
			$res_info= mysql_query_decide($sql_info) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		}
		//PHONE_RES
		if($post_values['phone']!='')
		{
			$post_values['country_code'] = explode('+',$post_values['country_code']);
	                $post_values['country_code'] = $post_values['country_code'][1];

			$post_values['phone'] = $post_values['country_code']."-".$post_values['state_code']."-".$post_values['phone'];
			$sql_id_ph= "INSERT INTO newjs.CONTACT_ARCHIVE (PROFILEID,FIELD) VALUES($id,'PHONE_RES')";
			$res_id_ph= mysql_query_decide($sql_id_ph) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");

			$changeid=mysql_insert_id_js();
			$sql_info_ph= "INSERT INTO newjs.CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,NEW_VAL) VALUES($changeid,'$now','$ip','".$post_values['phone']."')";
			$res_info_ph= mysql_query_decide($sql_info_ph) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		}
		//MOBILE_RES
		if($post_values['mobile'])
		{
			$post_values['country_code'] = explode('+',$post_values['country_code']);
			$post_values['country_code'] = $post_values['country_code'][1];

			$arch_mobile = $post_values['country_code_mob']."-".$post_values['mobile'];
			$sql_id_mob= "INSERT INTO newjs.CONTACT_ARCHIVE (PROFILEID,FIELD) VALUES($id,'PHONE_MOB')";
			$res_id_mob= mysql_query_decide($sql_id_mob) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");

			$changeid=mysql_insert_id_js();
			$sql_info_mob= "INSERT INTO newjs.CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,NEW_VAL) VALUES($changeid,'$now','$ip','$arch_mobile')";
			$res_info_mob= mysql_query_decide($sql_info_mob) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		}
		//end


		//Assingning profileid to offline OPERATOR
/*		if($cookie['OPERATOR']!='')
		{
			if($post_values['tieup_source']=='101')
			{
				$assigned_101="REPLACE INTO jsadmin.ASSIGNED_101 (`PROFILEID`,`OPERATOR`,`LAST_LOGIN_DATE`) values('$id','".$cookie['OPERATOR']."',now())";
				mysql_query_decide($assigned_101) or logError("Due to some temporary problem your request could not be processed.Please try after some time.",$assigned_101,"ShowErrTemplate");
				$assigned_101="insert into jsadmin.ASSIGNLOG_101 (`PROFILEID`,`OPERATOR`,`DATE`) values('$id','".$cookie['OPERATOR']."',now())";
				mysql_query_decide($assigned_101) or logError("Due to some temporary problem your request could not be processed.Please try after some time.",$assigned_101,"ShowErrTemplate");
			}
			else if($post_values['source']=='onoffreg')
			{
				 $offline_reg="INSERT INTO newjs.OFFLINE_REGISTRATION (`PROFILEID`,`EXECUTIVE`,`SOURCE`,`DATE`) VALUES('$id','".$cookie['OPERATOR']."','".$post_value['source']."','$now')";
				 mysql_query_decide($offline_reg) or logError("Due to some temporary problem your request could not be processed.Please try after some time.",$offline_reg,"ShowErrTemplate");

			}
			else
			{
				$offline_assigned="REPLACE INTO jsadmin.OFFLINE_ASSIGNED (`PROFILEID`,`OPERATOR`,`LAST_LOGIN_DATE`) VALUES('$id','".$cookie['OPERATOR']."','$now')";
				mysql_query_decide($offline_assigned) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$offline_assigned,"ShowErrTemplate");

				$offline_assigned="INSERT INTO jsadmin.OFFLINE_ASSIGNLOG (`PROFILEID`,`OPERATOR`,`ASSIGN_DATE`) VALUES('$id','".$cookie['OPERATOR']."','$now')";
				mysql_query_decide($offline_assigned) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$offline_assigned,"ShowErrTemplate");
			}
		}
		else if($post_values['source']=='onoffreg' && $cookie['JS_LEAD']) 
                {
                        $lead=$cookie['JS_LEAD'];
                        $sql_up="UPDATE sugarcrm.leads l,sugarcrm.leads_cstm lc set username_c='$username',converted=1,status=6,refered_by='Registration done by self' where l.id=lc.id_c and l.id='$lead'";
                        mysql_query_decide($sql_up) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_up,"ShowErrTemplate");
                        $sql_op="SELECT user_name FROM sugarcrm.leads l, sugarcrm.users as u where l.assigned_user_id=u.id  and l.id='$lead'";
                        $res_op=mysql_query_decide($sql_op) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_op,"ShowErrTemplate");
                        $row_op=mysql_fetch_assoc($res_op);
                        $offline_reg="INSERT INTO newjs.OFFLINE_REGISTRATION (`PROFILEID`,`EXECUTIVE`,`SOURCE`,`DATE`) VALUES('$id','".$row_op['user_name']."','".$post_values['source']."','$now')";
                        mysql_query_decide($offline_reg) or logError("Due to some temporary problem your request could not be processed.Please try after some time.",$offline_reg,"ShowErrTemplate");

                }
		//CODE ADDED BY Tapan Arora for capture outer variable
		if($post_values['adnetwork'] || $post_values['account'] || $post_values['campaign'] || $post_values['adgroup'] || $keyword_tieup || $post_values['match'] || $post_values['lmd'])
		{
			$sql="INSERT INTO MIS.TRACK_TIEUP_VARIABLE VALUES('','".$post_values['adnetwork']."','".$post_values['account']."','".$post_values['campaign']."','".$post_values['adgroup']."','$keyword_tieup','".$post_values['match']."','".$post_values['lmd']."',$id,now())";
			mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		}
		//CODE Ended By Tapan Arora


		if(isset($cookie['SEARCH_REDIFF']))
		{
			$sql_rediff = "INSERT INTO MIS.REDIFF_SRCH_REG (PROFILEID,ENTRY_DT) VALUES ('$id','$now')";
			mysql_query_decide($sql_rediff) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_rediff,"ShowErrTemplate");
		}

		//Added By lavesh to email authority for informing about suspected email-id.
		if($suspected_check)
		send_email('vikas@jeevansathi.com',$id,"Profileid of suspected email-id","register@jeevansathi.com");
 */
		if($lang)
		{
			$sql="INSERT INTO MIS.LANG_REGISTER VALUES ('','$id','$lang')";
			mysql_query_decide($sql);
		}

		//Not to add when operator is registring the profile
		if($cookie['OPERATOR']=="")
		{
			$sql_incomp="INSERT IGNORE INTO newjs.INCOMPLETE_PROFILES VALUES('$id','$now')";
			mysql_query_decide($sql_incomp) or logError("Due to some temporary problem your request could not be processed. Please try after some time.".mysql_error_js(),$sql_incomp,"ShowErrTemplate");
		}


		$vpin=vpin_gen();
		$sql_incomp="INSERT IGNORE INTO infovision.INF_USER_PIN (PROFILEID,VPIN) VALUES ('$id','$vpin')";
		mysql_query_decide($sql_incomp) or logError("Due to some temporary problem your request could not be processed. Please try after some time.".mysql_error_js(),$sql_incomp,"ShowErrTemplate");

		if($post_values['hit_source']!='O')
		{
			$sql_name_insert="INSERT INTO NAMES VALUES ('$username')";
			mysql_query_decide($sql_name_insert) or logError("NAMES TABLE ENTRY NOT DONE.",$sql_name_insert,"ShowErrTemplate");
		}

/*
		if(substr($post_values['source'],0,2)!="mb")
		{
			$cookies['PROFILEID']=$id;
			$cookies['USERNAME']=$username;
			$cookies['GENDER']=$post_values['gender'];
			$cookies['SUBSCRIPTION']='';
			$cookies['ACTIVATED']='N';
			$cookies['SOURCE']=$post_values['tieup_source'];

			$protect_obj->setcookies($cookies);
			$checksum=md5($id)."i".$id;
			$checksum=$protect_obj->js_encrypt($checksum);
		}

		setcookie("JS_SOURCE","",0,"/",$domain);
		//cookie deleted by Tapan Arora after registration
		setcookie("JS_ADNETWORK","",0,"/");
		setcookie("JS_ACCOUNT","",0,"/");
		setcookie("JS_CAMPAIGN","",0,"/");
		setcookie("JS_ADGROUP","",0,"/");
		setcookie("JS_KEYWORD","",0,"/");
		setcookie("JS_MATCH","",0,"/");
		//code ended by Tapan Arora
		setcookie("JS_LMD","",0,"/");

 */
		// Mailer on Registration

		first_time_registration_mail($id);	
	   smsPasswordAfterRegistration($profileid,$post_values['mobile'],$post_values['email'],$post_values['password'],$username);	
		// Mailer Intergrated for the duplicate contact number holders.
		/* Commenting this issue as per Mantis 4781 */
		/*
		if($mobile)
		{
			$sql = "SELECT PROFILEID,USERNAME FROM newjs.JPROFILE WHERE ACTIVATED = 'Y' AND PHONE_MOB = '$mobile'";
			$res = mysql_query_decide($sql)  or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_a,"ShowErrTemplate");

			while($row = mysql_fetch_array($res))
			{
			       $oldprofile[] = $row['PROFILEID'];
			       $username1[] = $row['USERNAME'];
			}
			
			if(is_array($oldprofile))
			{
				$oldprofile=implode(",",$oldprofile);
				$username1=implode(",",$username1);
			}
			if($username1)
			{
				$contact = "Mobile";
				$msg = "Hi,</br>User Name <b>$username</b> has registered with the $contact number $mobile.</br>We already have profile $username1 with the same number";
				$cc ="productsupport@jeevansathi.com";
				$to ="mahesh@jeevansathi.com";
				$from = "info@jeevansathi.com";
				$subject = "Duplicate $contact Number Tracked";
			        if($whichMachine!='test')
					send_email($to,$msg,$subject,$from,$cc);
			}
		}

		if($phone)
		{
			$sql = "SELECT PROFILEID,USERNAME FROM newjs.JPROFILE WHERE ACTIVATED = 'Y' AND PHONE_RES = '$phone'";
			$res = mysql_query_decide($sql)  or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_a,"ShowErrTemplate");
		
			while($row = mysql_fetch_array($res))
			{
			       $oldprofile[] = $row['PROFILEID'];
			       $username1[] = $row['USERNAME'];
			}
			
			if(is_array($oldprofile))
			{
				$oldprofile=implode(",",$oldprofile);
				$username1=implode(",",$username1);
			}
			if($username1)
			{
				$contact = "Phone";
				$msg = "Hi,</br>User Name <b>$username</b> has registered with the $contact number $phone.</br>We already have profile $username1 with the same number";

				$cc ="productsupport@jeevansathi.com";
				$to ="mahesh@jeevansathi.com";
				$from = "info@jeevansathi.com";
				$subject = "Duplicate $contact Number Tracked";
			        if($whichMachine!='test')
					send_email($to,$msg,$subject,$from,$cc);
			}
		}
		*/	

		// Redirecting to the Second page

		
		// For updating Lead Table in MIS
/*		if($post_values['email'])
		{
			$sql="UPDATE MIS.REG_LEAD SET LEAD_CONVERSION ='Y' WHERE EMAIL='".$post_values['email']."'";
                        mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		}
		
		if($post_values['leadid'])
		{
			$sql="INSERT INTO MIS.LEAD_CONVERSION (LEADID,LEAD_CONVERTED,LEAD_COMPLETED) VALUES ('".$post_values['leadid'].",'Y','N')";
                        mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		}

		include_once("registration_page2.php");
		die;*/
		return $username;
	}
function generate_userName(){
			while(1)
			{
				$username=username_gen();
				$sql="SELECT COUNT(*) as cnt FROM JPROFILE WHERE USERNAME='$username'";
				$res_username=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
				$row_username=mysql_fetch_array($res_username);

				$sql="SELECT COUNT(*) as cnt FROM JPROFILE_AFFILIATE WHERE USERNAME='$username'";
	                        $res_username2=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
	                        $row_username2=mysql_fetch_array($res_username2);
			
				 if($row_username['cnt']==0 && $row_username2['cnt']==0)
			return $username;
			}
}

/**
	* this function checks and validates form values obtained from page 1 of registration.
	* @returns array of error messages
	* @parameter $page1_values
 **/
function verify_page1($page1_values,&$errors){
	$is_error=0;
validate_email($page1_values['email'],$is_error,$errors);
validate_password($page1_values['password'],$is_error,$errors);
validate_gender($page1_values['gender'],$is_error,$errors);
validate_dob($page1_values['day'],$page1_values['month'],$page1_values['year'],$is_error,$page1_values['gender'],$errors);
validate_maritalStatus($page1_values['mstatus'], $page1_values['has_children'],$page1_values['gender'],$is_error,$errors);
validate_contact_number($page1_values['phone'],$page1_values['mobile'],$page1_values['state_code'],$page1_values['country_code'],$page1_values['country_residence'],$page1_values['country_code'],$page1_values['showmobile'],$page1_values['showphone'],$is_error,$errors);
validate_mtongue($page1_values['mtongue'],$is_error,$errors);
validate_religionandcaste($page1_values['religion'],$is_error,$page1_values['caste'],$errors,'Y');
validate_height($page1_values['height'],$is_error,$errors);
validate_relationship($page1_values['relationship'],$is_error,$errors);
validate_city($page1_values['city_residence'],$is_error,$errors);
validate_country($page1_values['country_residence'],$is_error,$errors);


//return $errors;
return $is_error;
}
function smsPasswordAfterRegistration($profileid,$mobileNumber,$email,$password,$username){
        include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
        include_once(JsConstants::$docRoot."/classes/ShortURL.class.php");

        $forgotPasswordStr = ResetPasswordAuthentication::getResetLoginStr($profileid);
        $forgotPasswordUrl = JsConstants::$siteUrl."/common/resetPassword?".$forgotPasswordStr;
	$shortURL = new ShortURL();
	$forgotPasswordUrl = $shortURL->setShortURL($forgotPasswordUrl);
	$password_message="Your profile is created on Jeevansathi.com. UserID:$username or Email:\"$email\".Create Password through:\"".$forgotPasswordUrl."\". Call 1800-419-6299 for help";
	$from="919282443838";
	$xmlData = generateReceiverXmlData($profileid, $password_message,
		                $from, $mobileNumber);
	sendSMS($xmlData, "priority");
}
//function to auto create password for leads.

function createJSPassword($len){
    return substr(md5(rand().rand()), 0, $len);
} 


function degreeDropDown(){
	global $smarty,$degree,$EDUCATION_GROUPING_DROP;
	global $db;
		$option_string="";
		$sql = "SELECT SQL_CACHE el.VALUE AS VALUE, el.LABEL AS LABEL, el.GROUPING AS GROUPING FROM EDUCATION_LEVEL_NEW el, EDUCATION_GROUPING eg WHERE el.GROUPING = eg.VALUE ORDER BY eg.SORTBY,el.SORTBY";
		$res = mysql_query_decide($sql,$db) or logError("error",$sql);
		$i=0;
		$first_group = true;

		while($row = mysql_fetch_array($res))
		{
			$group = $row['GROUPING'];

			//array to group degrees.
			if(isset($group_old) && $group_old != $group)
			$i++;
			$group_values[$i] .= $row['VALUE']."|#|";

			if($group_old != $group)
			{
				$group_count++;
				if($first_group)
				{
					$optg=$EDUCATION_GROUPING_DROP[$group];
				        $option_string.= "<optgroup label=\"&nbsp;\"></optgroup><optgroup label=\"$optg\">";
					$first_group = false;
				}
				elseif($group == count($EDUCATION_GROUPING_DROP))
				{
				        $option_string.= "</optgroup><optgroup label=\"&nbsp;\">";
					$optg='';
				}
				else
				{
					$optg=$EDUCATION_GROUPING_DROP[$group];
				        $option_string.= "</optgroup><optgroup label=\"&nbsp;\"></optgroup><optgroup label=\"$optg\">";
				}
			}
			if($group_count == (count($EDUCATION_GROUPING_DROP)+1) && !$done_once)
			{
				$done_once = 1;
				$option_string.= "</optgroup><optgroup label=\"&nbsp;\"></optgroup>";
			}
	
			if($degree == $row['VALUE'])
				$option_string.= "<option value=\"$row[VALUE]\" selected=\"yes\">$row[LABEL]</option>";
			else
				$option_string.= "<option value=\"$row[VALUE]\">$row[LABEL]</option>";
	
			$group_old = $group;
		}
		$smarty->assign("degree",$option_string);
		unset($option_string);
}
function occupationDropDown(){
	global $db,$smarty,$occupation;
		$option_string="";
		$sql = "SELECT SQL_CACHE VALUE, LABEL from OCCUPATION ORDER BY SORTBY";
		$res = mysql_query_decide($sql,$db) or logError("error",$sql);
		while($row = mysql_fetch_array($res))
		{
			if($occupation == $row['VALUE'])
				$option_string.= "<option value=\"$row[VALUE]\" selected=\"yes\">$row[LABEL]</option>";
			else
				$option_string.= "<option value=\"$row[VALUE]\">$row[LABEL]</option>";
		}
		
		$smarty->assign('occupation',$option_string);
		unset($option_string);
		
}
function incomeDropDown(){
	global $smarty,$db,$income;
	$option_string="";
		$sql = "SELECT SQL_CACHE VALUE, LABEL, TYPE from INCOME WHERE VISIBLE <> 'N' ORDER BY SORTBY";
		$res = mysql_query_decide($sql,$db) or logError("error",$sql);
		while($row = mysql_fetch_array($res))
		{
			if($row['TYPE'] == "RUPEES" && !$indian_currency_label)
			{
				$indian_currency_label = 1;
				$optg="Income in Indian Rupee";
				$option_string.= "<optgroup label=\"$optg\">";
			}
			elseif($row['TYPE'] == "DOLLARS" && !$us_currency_label)
			{
				$us_currency_label=1;
				$optg="Income in US Dollars";
				$option_string.= "</optgroup><optgroup label= \"&nbsp;\"></optgroup><optgroup label=\"$optg\">";
			}

			if($row['VALUE'] == "15")
				$option_string.= "</optgroup><optgroup label= \"&nbsp;\"></optgroup>";

			if($income == $row['VALUE'])
				$option_string.= "<option value=\"$row[VALUE]\" selected=\"yes\">$row[LABEL]</option>";
			else
				$option_string.= "<option value=\"$row[VALUE]\">$row[LABEL]</option>";	
		}

		$smarty->assign('income',$option_string);
		unset($option_string);
}
/*
require_once($path."/nusoap/nusoap.php");
$soapServer=new soap_server();
$soapServer->configureWSDL('mysoap', 'urn:mysoap');
$soapServer->wsdl->addComplexType(
	'response',
	'complexType',
	'Array',
	'',
	 'SOAP-ENC:Array',
	    array(),
	       array(
		           array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'xsd:string[]')
			    ),
		     'xsd:string'
		 );
$soapServer->wsdl->addComplexType(
	    'name_value',
	    'complexType',
	     'struct',
	      'all',
	      '',
	        array(
	            array('name'=>'name', 'type'=>'xsd:string'),
	            array('name'=>'value', 'type'=>'xsd:int'),
															        )
		);
 
$soapServer->wsdl->addComplexType(
	'page1_values',
	'complexType',
	'struct',
	'all',
	'',
	array(
								'email'=>array('name'=>'email', 'type' => 'xsd:string' ),
							//	'age'=>array('name'=>'age', 'type' => 'xsd:string' ),
								'password'=>array('name'=>'password', 'type' => 'xsd:string' ),
								'height'=>array('name'=>'height', 'type' => 'xsd:string' ),
								'city_residence'=>array('name'=>'city_residence', 'type' => 'xsd:string' ),
								'gender'=>array('name'=>'gender', 'type' => 'xsd:string' ),
								'caste'=>array('name'=>'caste', 'type' => 'xsd:int' ),
								'religion'=>array('name'=>'religion', 'type' => 'xsd:string' ),
								'phone'=>array('name'=>'phone', 'type' => 'xsd:string' ),
								'mobile'=>array('name'=>'mobile', 'type' => 'xsd:string' ),
								'showphone'=>array('name'=>'showphone', 'type' => 'xsd:string' ),
								'showmobile'=>array('name'=>'showmobile', 'type' => 'xsd:string' ),
								'state_code'=>array('name'=>'state_code', 'type' => 'xsd:string' ),
								'country_code'=>array('name'=>'country_code', 'type' => 'xsd:string' ),
							//	'now'=>array('name'=>'now', 'type' => 'xsd:string' ),
							//	'today'=>array('name'=>'today', 'type' => 'xsd:string' ),
								'mtongue'=>array('name'=>'mtongue', 'type' => 'xsd:string' ),
								'mstatus'=>array('name'=>'mstatus', 'type' => 'xsd:string' ),
								'has_children'=>array('name'=>'has_children', 'type' => 'xsd:string' ),
								'relationship'=>array('name'=>'relationship', 'type' => 'xsd:string' ),
						//		'citizenship'=>array('name'=>'citizenship', 'type' => 'xsd:string' ),
								'country_code_mob'=>array('name'=>'country_code_mob', 'type' => 'xsd:string' ),
								'day'=>array('name'=>'day', 'type' => 'xsd:string' ),
								'month'=>array('name'=>'month', 'type' => 'xsd:string' ),
								'year'=>array('name'=>'year', 'type' => 'xsd:string' ),
								'country_res'=>array('name'=>'country_res', 'type' => 'xsd:string' )
							)
						);
//$soapServer->register('verify_page1',array('page1_values'=>'tns:page1_values'),array('return'=>'SOAP-ENC:Array'),'urn:mysoap','urn:mysoap#verify_page1','rpc','encoded','');
$soapServer->register('verify_page1',array('page1_values'=>'tns:page1_values'),array('return'=>'xsd:int'),'urn:mysoap','urn:mysoap#verify_page1','rpc','encoded','');
$soapServer->register('register_mine',array('page1_values'=>'tns:page1_values'),array('return'=>'xsd:int'),'urn:mysoap','urn:mysoap#register_mine','rpc','encoded','');
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$soapServer->service($HTTP_RAW_POST_DATA);*/
?>
