<?php
		 	$sql = "SELECT LABEL FROM newjs.HEIGHT WHERE VALUE='$height'";
                        $res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                        $myrow_height=mysql_fetch_array($res);
                        $height_label=$myrow_height["LABEL"];
                        $height_label=substr($height_label,0,10);

                        $sql="SELECT LABEL FROM newjs.CITY_NEW WHERE VALUE='$city_residence'";
                        $res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

                        $myrow_city=mysql_fetch_array($res);
                        $city_label=$myrow_city["LABEL"];

                        if($gender=='M')
                        $gender_key="Male";
                        elseif($gender=='F')
                        $gender_key="Female";

                        $keyword=addslashes(stripslashes($gender_key.",".$age.",".$Caste_label.",".$height_label.",".$city_label));
						$username=generate_username();
				$incomplete='Y';
				$activated='N';
				$ANNULLED_SCREEN='N';
				$SCREENING=0;

			
			$country_code = explode('+',$country_code_mob);
			$country_code = $country_code[1];


			 if($relationship=='2')
			 	$smarty->assign("yourHeading","your Son");
			 elseif($relationship=='2D')
			 	$smarty->assign("yourHeading","your Daughter");
			 elseif($relationship=='6' || $relationship=='6D')
			 	$smarty->assign("yourHeading","your Sibling");
			 elseif($relationship=='4')
			 	$smarty->assign("yourHeading","your Relative");
			 elseif($relationship=='5')
			 	$smarty->assign("yourHeading","your Client");

			 if($relationship=='2D')
                               $relationship = '2';
	                 elseif($relationship=='6')
	                       $relationship = '3';
	                 elseif($relationship=='6D')
			        $relationship = '3';

			 
			if($mstatus=='N')
			       $has_children='';

			if($service_email=='')
				$service_email='U';
			if($service_sms=='')
				$service_sms='U';
			if($service_call=='')
				$service_call='U';

			if($promo=='')
				$promo='U';

			if($memb_sms=='')
                               $memb_sms='U';

			if($memb_ivr=='')
                               $memb_ivr='U';

			if($memb_mails=='')
				$memb_mails='U';
			
			$email=trim($email);
			
			//Field for identifying the team to which profile belong
                        if($source=='onoffreg' || $source=='ofl_prof')
                                $crm_team = 'offline';
                        else
                                $crm_team = 'online';

		if($service_email=='S')
			$match_def='A';
		else
			$match_def='U';

		if($memb_sms=='S')
			$sms_def='Y';
		else
			$sms_def='N';

	       $phone_with_std=$state_code.$phone;

	       if($phone==""){
	       		$phone_with_std="";
				$state_code="";
		}
               
	       $show_horoscope='Y'; /* Inserting  Y by default at the time of Registration as per Bug 43859 */
		   $secondary_source=mysql_escape_string($secondary_source);
		   if(!$secondary_source)
			   $secondary_source='S';

	       $sql = "INSERT INTO newjs.JPROFILE (RELATION,EMAIL,PASSWORD,USERNAME,GENDER,DTOFBIRTH,MSTATUS,HAVECHILD,HEIGHT,COUNTRY_RES,CITIZENSHIP,ISD,STD,CITY_RES,PHONE_RES,PHONE_WITH_STD,SHOWPHONE_RES,PHONE_NUMBER_OWNER,PHONE_OWNER_NAME,PHONE_MOB,SHOWPHONE_MOB,MOBILE_NUMBER_OWNER,MOBILE_OWNER_NAME,TIME_TO_CALL_START,TIME_TO_CALL_END,EDU_LEVEL_NEW,OCCUPATION,INCOME,MTONGUE,RELIGION,SPEAK_URDU,CASTE,PROMO_MAILS,SERVICE_MESSAGES,ENTRY_DT,MOD_DT,LAST_LOGIN_DT,SORT_DT,AGE,IPADD,SOURCE,ACTIVATED,INCOMPLETE,KEYWORDS,SCREENING,YOURINFO,CRM_TEAM,PERSONAL_MATCHES,GET_SMS,SHOW_HOROSCOPE,SEC_SOURCE)VALUES('$relationship','$email','$password','$username','$gender','$date_of_birth','$mstatus','$has_children','$height','$country_residence','','$country_code','$state_code','$city_residence','$phone','$phone_with_std','$showphone','','','$mobile','$showmobile','','','','','$degree','$occupation','$income','$mtongue','$religion_val','$speak_urdu','$caste','$promo','$service_email','$now','$now','$today','$now','$age','$ip','$tieup_source','N','Y','$keyword',0,'','$crm_team','$match_def','$sms_def','$show_horoscope','$secondary_source')";                     		 
	        mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");

		/* Added By Lavesh Rawat for Sharding Purpose */
		$id=mysql_insert_id_js();
		assignServerToProfile($id);
		$profileid=$id;

		$sql2="INSERT INTO newjs.JPROFILE_ALERTS(PROFILEID,MEMB_CALLS,OFFER_CALLS,SERV_CALLS_SITE,SERV_CALLS_PROF,MEMB_MAILS,CONTACT_ALERT_MAILS,KUNDLI_ALERT_MAILS,PHOTO_REQUEST_MAILS,SERVICE_MAILS,SERVICE_SMS,SERVICE_MMS,SERVICE_USSD,PROMO_USSD,PROMO_MMS) VALUES ('$profileid','$memb_ivr','$memb_ivr','$service_call','$service_call','$memb_mails','$service_email','$service_email','$service_email','$service_email','$service_sms','$service_sms','$service_sms','$memb_sms','$memb_sms')";
		mysql_query_decide($sql2) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql2,"ShowErrTemplate");
		/* Tracking Query for the Reg Count */
		$sql = "INSERT INTO MIS.REG_COUNT(PROFILEID,PAGE1) VALUES ('$profileid','Y')";
		mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		/* Sending to the 2nd Page for use in 3rd Page */

		$smarty->assign("PROFILEID",$profileid);
		$smarty->assign("RELIGION",$religion_val);
		$smarty->assign("CASTE",$caste);

		/* Ends Here */



		/* Trac#1021 code ends*/

		/* Inserting Default Entries in Jpartner */

		$jpartnerObj=new Jpartner;
		$mysqlObj=new Mysql;
		
		if(!$myDb)
		{
		        $myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
		        $myDb=$mysqlObj->connect("$myDbName");
		}

	        $jpartnerObj->setPROFILEID($profileid);

		if($gender=='M')
		     $jpartnerObj->setGENDER('F');
		else
		     $jpartnerObj->setGENDER('M');
	
		if($gender=='M')
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
		}
						
                $jpartnerObj->setLAGE($lage);
                $jpartnerObj->setHAGE($hage);
			
		if($gender=='M')
		{
			$lheight=$height-10;
			$hheight=$height;
		}
		else
		{
			$lheight=$height;
			$hheight = $height+10;
		}	

                $jpartnerObj->setLHEIGHT($lheight);
                $jpartnerObj->setHHEIGHT($hheight);
                $jpartnerObj->setDPP('R');

		$MTONGUE=array(10,7,33,19,28,13);

		//Insert into partner table if mtongue selected is within all hindi mtongue

		if(in_array($mtongue,$MTONGUE))
		{
			foreach($MTONGUE as $key=>$val)
				$mtongue_val.="'".$val."',";
			$mtongue_val=substr($mtongue_val,0,strlen($mtongue_val)-1);
		}
		else
			$mtongue_val="'".$mtongue."'";
		
		$jpartnerObj->setPARTNER_MTONGUE($mtongue_val);

                //$religion_partner.="'".$religion_val."'"."'";
                //$religion_partner=substr($religion_partner,0,strlen($religion_partner)-1);
                $religion_partner=setFilterReligion($religion_val,$mtongue);
                $jpartnerObj->setPARTNER_RELIGION($religion_partner);
		
		$caste_community = $caste."-".$mtongue;
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
			      $mapped_caste_arr[]=$caste;
		}
		else      
			      $mapped_caste_arr[]=$caste;

		$mapped_caste="'".@implode("','",$mapped_caste_arr)."'";
		$jpartnerObj->setPARTNER_CASTE($mapped_caste);

		if($mstatus=="N")
			$jpartnerObj->setPARTNER_MSTATUS("'".$mstatus."'");

		$jpartnerObj->updatePartnerDetails($myDb,$mysqlObj);
		
		/* Default Jpartner Insertion Ends Here */

		//Added by neha verma to store registration caused by sources leading to home page
		if(isset($_COOKIE['JS_SOURCE_HOME']))
		{
			$source = $_COOKIE['JS_SOURCE_HOME'];
			$sql = "INSERT INTO MIS.REG_HOME (DATE,SOURCEID,PROFILEID) VALUES('$now','$source','$id')";
			$res= mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			setcookie("JS_SOURCE_HOME","",time() - 3600,"/");
		}
		//end of code added by neha
		
		if($email!='')
		{
			$sql_id= "INSERT INTO newjs.CONTACT_ARCHIVE (PROFILEID,FIELD) VALUES($id,'EMAIL')";
			$res_id= mysql_query_decide($sql_id) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_id,"ShowErrTemplate");

			$changeid=mysql_insert_id_js();
			$sql_info= "INSERT INTO newjs.CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,NEW_VAL) VALUES($changeid,'$now','$ip','$email')";
			$res_info= mysql_query_decide($sql_info) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		}
		//PHONE_RES
		if($phone!='')
		{
			$country_code = explode('+',$country_code);
	                $country_code = $country_code[1];

			$phone = $country_code."-".$state_code."-".$phone;
			$sql_id_ph= "INSERT INTO newjs.CONTACT_ARCHIVE (PROFILEID,FIELD) VALUES($id,'PHONE_RES')";
			$res_id_ph= mysql_query_decide($sql_id_ph) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");

			$changeid=mysql_insert_id_js();
			$sql_info_ph= "INSERT INTO newjs.CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,NEW_VAL) VALUES($changeid,'$now','$ip','$phone')";
			$res_info_ph= mysql_query_decide($sql_info_ph) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		}
		//MOBILE_RES
		if($mobile)
		{
			$country_code = explode('+',$country_code);
			$country_code = $country_code[1];

			$arch_mobile = $country_code_mob."-".$mobile;
			$sql_id_mob= "INSERT INTO newjs.CONTACT_ARCHIVE (PROFILEID,FIELD) VALUES($id,'PHONE_MOB')";
			$res_id_mob= mysql_query_decide($sql_id_mob) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");

			$changeid=mysql_insert_id_js();
			$sql_info_mob= "INSERT INTO newjs.CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,NEW_VAL) VALUES($changeid,'$now','$ip','$arch_mobile')";
			$res_info_mob= mysql_query_decide($sql_info_mob) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		}
		//end


		//Assingning profileid to offline OPERATOR
		if($_COOKIE['OPERATOR']!='')
		{
			if($tieup_source=='101')
			{
				$assigned_101="REPLACE INTO jsadmin.ASSIGNED_101 (`PROFILEID`,`OPERATOR`,`LAST_LOGIN_DATE`) values('$id','".$_COOKIE['OPERATOR']."',now())";
				mysql_query_decide($assigned_101) or logError("Due to some temporary problem your request could not be processed.Please try after some time.",$assigned_101,"ShowErrTemplate");
				$assigned_101="insert into jsadmin.ASSIGNLOG_101 (`PROFILEID`,`OPERATOR`,`DATE`) values('$id','".$_COOKIE['OPERATOR']."',now())";
				mysql_query_decide($assigned_101) or logError("Due to some temporary problem your request could not be processed.Please try after some time.",$assigned_101,"ShowErrTemplate");
			}
			else if($source=='onoffreg')
			{
				 $offline_reg="INSERT INTO newjs.OFFLINE_REGISTRATION (`PROFILEID`,`EXECUTIVE`,`SOURCE`,`DATE`) VALUES('$id','".$_COOKIE['OPERATOR']."','$source','$now')";
				 mysql_query_decide($offline_reg) or logError("Due to some temporary problem your request could not be processed.Please try after some time.",$offline_reg,"ShowErrTemplate");

			}
			else
			{
				$offline_assigned="REPLACE INTO jsadmin.OFFLINE_ASSIGNED (`PROFILEID`,`OPERATOR`,`LAST_LOGIN_DATE`) VALUES('$id','".$_COOKIE['OPERATOR']."','$now')";
				mysql_query_decide($offline_assigned) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$offline_assigned,"ShowErrTemplate");

				$offline_assigned="INSERT INTO jsadmin.OFFLINE_ASSIGNLOG (`PROFILEID`,`OPERATOR`,`ASSIGN_DATE`) VALUES('$id','".$_COOKIE['OPERATOR']."','$now')";
				mysql_query_decide($offline_assigned) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$offline_assigned,"ShowErrTemplate");
			}
		}
		else if($source=='onoffreg' && $_COOKIE['JS_LEAD']) 
                {
                        $lead=$_COOKIE['JS_LEAD'];
                        $sql_up="UPDATE sugarcrm.leads l,sugarcrm.leads_cstm lc set username_c='$username',converted=1,status=6,refered_by='Registration done by self' where l.id=lc.id_c and l.id='$lead'";
                        mysql_query_decide($sql_up) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_up,"ShowErrTemplate");
                        $sql_op="SELECT user_name FROM sugarcrm.leads l, sugarcrm.users as u where l.assigned_user_id=u.id  and l.id='$lead'";
                        $res_op=mysql_query_decide($sql_op) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_op,"ShowErrTemplate");
                        $row_op=mysql_fetch_assoc($res_op);
                        $offline_reg="INSERT INTO newjs.OFFLINE_REGISTRATION (`PROFILEID`,`EXECUTIVE`,`SOURCE`,`DATE`) VALUES('$id','".$row_op['user_name']."','$source','$now')";
                        mysql_query_decide($offline_reg) or logError("Due to some temporary problem your request could not be processed.Please try after some time.",$offline_reg,"ShowErrTemplate");

                }
		//CODE ADDED BY Tapan Arora for capture outer variable
		if($adnetwork || $account || $campaign || $adgroup || $keyword_tieup || $match || $lmd)
		{
			$sql="INSERT INTO MIS.TRACK_TIEUP_VARIABLE VALUES('','$adnetwork','$account','$campaign','$adgroup','$keyword_tieup','$match','$lmd',$id,now())";
			mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		}
		//CODE Ended By Tapan Arora


		if(isset($_COOKIE['SEARCH_REDIFF']))
		{
			$sql_rediff = "INSERT INTO MIS.REDIFF_SRCH_REG (PROFILEID,ENTRY_DT) VALUES ('$id','$now')";
			mysql_query_decide($sql_rediff) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_rediff,"ShowErrTemplate");
		}

		//Added By lavesh to email authority for informing about suspected email-id.
		if($suspected_check)
		send_email('vikas@jeevansathi.com',$id,"Profileid of suspected email-id","register@jeevansathi.com");

		if($lang)
		{
			$sql="INSERT INTO MIS.LANG_REGISTER VALUES ('','$id','$lang')";
			mysql_query_decide($sql);
		}

		//Not to add when operator is registring the profile
		if($_COOKIE['OPERATOR']=="")
		{
			$sql_incomp="INSERT IGNORE INTO newjs.INCOMPLETE_PROFILES VALUES('$id','$now')";
			mysql_query_decide($sql_incomp) or logError("Due to some temporary problem your request could not be processed. Please try after some time.".mysql_error_js(),$sql_incomp,"ShowErrTemplate");
		}


		$vpin=vpin_gen();
		$sql_incomp="INSERT IGNORE INTO infovision.INF_USER_PIN (PROFILEID,VPIN) VALUES ('$id','$vpin')";
		mysql_query_decide($sql_incomp) or logError("Due to some temporary problem your request could not be processed. Please try after some time.".mysql_error_js(),$sql_incomp,"ShowErrTemplate");

		if($hit_source!='O')
		{
			$sql_name_insert="INSERT INTO NAMES VALUES ('$username')";
			mysql_query_decide($sql_name_insert) or logError("NAMES TABLE ENTRY NOT DONE.",$sql_name_insert,"ShowErrTemplate");
		}


		if(substr($source,0,2)!="mb")
		{
			$cookies['PROFILEID']=$id;
			$cookies['USERNAME']=$username;
			$cookies['GENDER']=$gender;
			$cookies['SUBSCRIPTION']='';
			$cookies['ACTIVATED']='N';
			$cookies['SOURCE']=$tieup_source;

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


		// Mailer on Registration

		if ('C' == $secondary_source) {
		  first_time_registration_mail($id);	
    		}

		if($fname)
		{
			$name_of_user=explode(" ",$fname);
			$smarty->assign("fname_user",$name_of_user[0]);
			$smarty->assign("lname_user",$name_of_user[1]);
		}

		// Redirecting to the Second page

		$smarty->assign('PHONE',$phone);
		$smarty->assign('MOBILE',$mobile);
		$smarty->assign('STATE_CODE',$state_code);
		
		// For updating Lead Table in MIS
		if($email)
		{
			$sql="UPDATE MIS.REG_LEAD SET LEAD_CONVERSION ='Y' WHERE EMAIL='$email'";
                        mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");

			$sql="UPDATE MIS.MINI_REG_AJAX_LEAD SET CONVERTED ='Y' WHERE EMAIL='$email'";
                        mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		}
		
		if($leadid)
		{
			$sql="INSERT INTO MIS.LEAD_CONVERSION (LEADID,LEAD_CONVERTED,LEAD_COMPLETED) VALUES ('$leadid','Y','N')";
                        mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		}

		setcookie ("SULEKHACO", "", time()-3600,"/");
		include_once("registration_page2.php");
		die;
		function generate_username(){
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
					break;
			}
			return $username;
		}
?>
