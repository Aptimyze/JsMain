<?php
/***********************************************************************************************
*	FILENAME      	: skipped_profile.php
*	MODIIFICATION	: similar to useredit.php but for screening skipped profiles
*	DONE BY		: Tripti Singh
*	DONE ON       	: 5th July 2006 
*
********************************************************************************************/
// Disable caching of the current document:
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Pragma: no-cache');

include("time1.php");
include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/flag.php");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once("../profile/arrays.php");
include_once("../profile/screening_functions.php");
include("../profile/functions.inc");
if(authenticated($cid))
{
	if($Submit)
	{
                $email_ev   = 1;
		$do_gender_related_changes = 0;

		if($_POST["GENDER"]=="")
                {
                        $critical_message = print_r($_POST,true);
                        mail("sriram.viswanathan@jeevansathi.com","Screening Post Vars from skipped profile","$critical_message");
                }

		$sql="SELECT USERNAME,SCREENING,DTOFBIRTH, GENDER, EMAIL, ACTIVATED, PREACTIVATED,STD from newjs.JPROFILE where PROFILEID='$pid'";
		$result=mysql_query_decide($sql) or die(mysql_error_js());
		$myrow=mysql_fetch_array($result);
		$SCREENING=$myrow['SCREENING'];
		$previous_date_of_birth = $myrow['DTOFBIRTH'];
	        $previous_gender = $myrow['GENDER'];
        	$to_notify = $myrow['EMAIL'];
		$username	= $myrow['USERNAME'];
		$activated	= $myrow['ACTIVATED'];
		$preactivated	= $myrow['PREACTIVATED'];
		$std		= $myrow['STD'];	
		if ($name!="")
		{
			$NAME=explode(",",$name); 
			for($i=0;$i<count($NAME);$i++)
			{
				 if($NAME[$i]=='EMAIL')
                                {
                                        $NAME[$i]=trim($NAME[$i]);
                                }

                                if($NAME[$i]=="PHONE_RES")
                                        $screen=setFlag("PHONERES",$screen);
                                elseif($NAME[$i]=="PHONE_MOB")
                                        $screen=setFlag("PHONEMOB",$screen);
                                elseif($NAME[$i]=="CITY_BIRTH")
                                        $screen=setFlag("CITYBIRTH",$screen);
                                elseif($NAME[$i]=="MESSENGER_ID")
                                        $screen=setFlag("MESSENGER",$screen);
                                else
                                {
                                        if($NAME[$i] != "GENDER" && $NAME[$i] != "MSTATUS" && $NAME[$i] != "PHOTO_DISPLAY" && $NAME[$i] != "DTOFBIRTH")
                                                $screen=setFlag("$NAME[$i]",$screen);
                                }
			}
	
			for($i=0;$i<count($NAME);$i++)
			{
				if($NAME[$i]=="EMAIL")
				{
					$email=addslashes(stripslashes($_POST[$NAME[$i]]));
					$sql="SELECT COUNT(*) as cnt FROM newjs.JPROFILE WHERE EMAIL='$email' AND PROFILEID<>'$pid'";
					$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
					$row=mysql_fetch_array($res);
					if($row['cnt']>0)
					{
						$email='abc'.$pid."@jsxyz.com";
						$str.= $NAME[$i]." = '$email',";
						$verify_email='Y';
					}
					else
					{
						$email=addslashes(stripslashes($_POST[$NAME[$i]]));
						if(checkemail($email))
						{
							header("Location: $SITE_URL/jsadmin/skipped_profile.php?cid=$cid&email_err=1&email_filled=$email&email_profileid=$pid&val=$val");die;
						}
						else
							$str.= $NAME[$i]." = '".addslashes(stripslashes($_POST[$NAME[$i]]))."' ,";
					}
				}
				else
				{
					if($NAME[$i] == "DTOFBIRTH")
					{
						list($prev_year,$prev_month,$prev_day) = explode("-",$previous_date_of_birth);
						$DTOFBIRTH = $year_of_birth."-".$month_of_birth."-".$day_of_birth;
						if(mktime(0,0,0,$prev_month,$prev_day,$prev_year) != mktime(0,0,0,$month_of_birth,$day_of_birth,$year_of_birth))
						{
							$subject = "Change of Date of Birth";
							$mail_msg = "Dear $username,\nThis is with reference to the Date of Birth selected by you in the registration form. The one selected by you from the drop down values and the one mentioned as a text does not match. We are taking the date of birth mentioned as text as correct and are making the change in the date of birth field. Please write back to us with the exact date of birth if it is incorrect within three days of receiving this mail.\n\nWishing you success in your search.\n\nRegards,\nTeam Jeevansathi";
							send_email($to_notify,nl2br($mail_msg),$subject,"","","sriram.viswanathan@jeevansathi.com","","text/html");
							$str .= "AGE = '".getAge($DTOFBIRTH)."' , ";

							update_astro_dob($pid,$DTOFBIRTH);
						}
						$str .= $NAME[$i]." = '".addslashes(stripslashes($DTOFBIRTH))."' ,";
					}
					elseif($NAME[$i] == "GENDER")
					{
						if($previous_gender != $_POST[$NAME[$i]] && $_POST[$NAME[$i]] != '')
						{
							if($_POST[$NAME[$i]] == "M")
								$notify_gender = "male";
							elseif($_POST[$NAME[$i]] == "F")
								$notify_gender = "female";

							$subject = "Change of Gender";
							$mail_msg = "Dear $username,\nThis is with reference to the Gender selected by you in the registration form. The one selected by you from the drop down values and the details provided by you in the text field are both contradictory. The information furnished by you suggests your gender to be ".$notify_gender.", hence we are changing the Gender to ".$notify_gender.".Please write back to us with the correct gender incase there is any discrepancy within three days of receiving this mail.\n\nWishing you success in your search.\n\nRegards,\nTeam Jeevansathi";
							send_email($to_notify,nl2br($mail_msg),$subject,"","","sriram.viswanathan@jeevansathi.com","","text/html");
							$do_gender_related_changes = 1;
						}
						$str .= $NAME[$i]." = '".addslashes(stripslashes($_POST[$NAME[$i]]))."' ,";
					}
					elseif($NAME[$i]=="PHONE_RES")
					{
						$phone_res=addslashes(stripslashes($_POST[$NAME[$i]]));
						$str .= $NAME[$i]." = '".$phone_res."' ,PHONE_WITH_STD='$std$phone_res'  ,";
					}
					else
						$str .= $NAME[$i]." = '".addslashes(stripslashes($_POST[$NAME[$i]]))."' ,";
				}

                                if($NAME[$i]=="YOURNAME" && $_POST[$NAME[$i]]=="" )
                                {
					$bl_msg="<b>Please Note : </b>We have removed the content that you had put in \
						Other Information about yourself<br>.Please add related/valid/clear \
						information in this field. Better description will get you better results.\
						<br><br>Please" ;

					$bl_msg.="<a href = \"http://www.jeevansathi.com/profile/editprofile.php?checksum=&mail=Y\"> click here </a>";
					$bl_msg.=" to edit your profile <br>";
                                }
			}
			$count_screen=count($NAME);
			$sql_pre="INSERT into jsadmin.SCREENING_LOG(PROFILEID,USERNAME,$name,SCREENED_BY,SCREENED_TIME,ENTRY_TYPE,FIELDS_SCREENED) select PROFILEID,USERNAME,$name,'$user',now(),'P','$count_screen' from newjs.JPROFILE where PROFILEID = '$pid' ";
			mysql_query_decide($sql_pre) or die(mysql_error_js());
			$ref_id=mysql_insert_id_js();
			$sql_up= " UPDATE jsadmin.SCREENING_LOG set REF_ID='$ref_id' where ID='$ref_id' ";
			mysql_query_decide($sql_up) or die(mysql_error_js());
			$str = rtrim($str,","); 
			$sql = " UPDATE newjs.JPROFILE set $str, SCREENING='$screen'";
			if($verify_email)
				$sql.=",VERIFY_EMAIL='$verify_email'";
		
			if($INCOMPLETE!="")
				$sql.=", SCREENING=0, PREACTIVATED='$activated',ACTIVATED='N' , INCOMPLETE='Y'";
			else if($activated=='U' || ($activated=='H' && ($preactivated=='U' || $preactivated=='N')))
                        {
			       $sql.=", PREACTIVATED='$activated',ACTIVATED='Y'";
				if($Annulled_Reason)
				{
					$areason=htmlspecialchars($Annulled_Reason,ENT_QUOTES);
					$sql_a ="Update newjs.ANNULLED set SCREENED='Y',REASON='$areason',UPDATE_DT=now() where PROFILEID='$pid'";
					mysql_query_decide($sql_a) or die("$sql_a".mysql_error_js());
				}
			}
	
                        /*if (0)
                                $sql.= "ACTIVATED = 'N' AND INCOMPLETE ='Y' ";
                        else
                                $sql.= "ACTIVATED = 'Y' ";*/

                        $sql.= " where PROFILEID = '$pid' ";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$sql_mod="INSERT into jsadmin.SCREENING_LOG(REF_ID,PROFILEID,USERNAME,$name,SCREENED_BY,SCREENED_TIME,ENTRY_TYPE,FIELDS_SCREENED) select '$ref_id',PROFILEID,USERNAME,$name,'$user',now(),'M','$count_screen' from newjs.JPROFILE where PROFILEID = '$pid' ";
			mysql_query_decide($sql_mod) or die(mysql_error_js());

			//added by sriram.
			if($do_gender_related_changes)
			{
				//make changes related to gender at various places.
				gender_related_changes($pid,$previous_gender);
			}

			$now=date("Y-m-d");
			$sql_ne="SELECT COUNT(*) as cnt from MIS.NEW_EDIT_COUNT where SCREEN_DATE='$now' AND SCREENED_BY='$user'";
			$result_ne=mysql_query_decide($sql_ne) or die(mysql_error_js());
			$row_ne=mysql_fetch_assoc($result_ne);
			if($row_ne['cnt']==0)
			{
				$sql_ins="INSERT INTO MIS.NEW_EDIT_COUNT (SCREEN_DATE,SCREENED_BY) VALUES('$now','$user')";
				mysql_query_decide($sql_ins) or die(mysql_error_js());
			}
			if($activated=='U' || ($activated=='H' && ($preactivated=='U' || $preactivated=='N')))
			{	
				$sql_ne="UPDATE MIS.NEW_EDIT_COUNT SET NEW=NEW+1 WHERE SCREEN_DATE='$now' AND SCREENED_BY='$user'";
				mysql_query_decide($sql_ne) or die(mysql_error_js());
			}
			else
			{
				$sql_ne="UPDATE MIS.NEW_EDIT_COUNT SET EDIT=EDIT+1 WHERE SCREEN_DATE='$now' AND SCREENED_BY='$user'";
				mysql_query_decide($sql_ne) or die(mysql_error_js());
			}			
			
                        $sql="SELECT RECEIVE_TIME FROM jsadmin.MAIN_ADMIN WHERE PROFILEID='$pid' and SCREENING_TYPE='O'"; 
                             $res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                             $resf=mysql_fetch_array($res);
                             $rec_time=$resf['RECEIVE_TIME'];
                             $date_time=explode(" ",$rec_time);
                             $date_y_m_d=explode("-",$date_time[0]);
                             $time_h_m_s=explode(":",$date_time[1]);
                             $timestamp=mktime($time_h_m_s[0],$time_h_m_s[1],$time_h_m_s[2],$date_y_m_d[1],$date_y_m_d[2],$date_y_m_d[0]);
                             $timezone=date("T",$timestamp);
                             if($timezone=="EDT")
                                $timezone="EST5EDT";

			 $sql= "INSERT into jsadmin.MAIN_ADMIN_LOG (PROFILEID, USERNAME,           SCREENING_TYPE, RECEIVE_TIME, SUBMIT_TIME,ALLOT_TIME, SUBMITED_TIME, ALLOTED_TO, STATUS, SUBSCRIPTION_TYPE, SCREENING_VAL,TIME_ZONE, SUBMITED_TIME_IST) SELECT PROFILEID, USERNAME, SCREENING_TYPE, RECEIVE_TIME, SUBMIT_TIME, ALLOT_TIME, now(), ALLOTED_TO, 'APPROVED', SUBSCRIPTION_TYPE, SCREENING_VAL,'$timezone', CONVERT_TZ(NOW(),'$timezone','IST') from jsadmin.MAIN_ADMIN where PROFILEID='$pid' and SCREENING_TYPE='O'";
			mysql_query_decide($sql) or die(mysql_error_js());

			$sql= "DELETE from jsadmin.MAIN_ADMIN where PROFILEID='$pid' and SCREENING_TYPE='O'";
			mysql_query_decide($sql) or die(mysql_error_js());

			if($INCOMPLETE=="")
				$msg = "User $username is successfully screened<br><br>";
			else
				$msg="User $username is mark as incomplete<BR><BR>";
               	}
		else
		{
			$msg="User $username is already screened<br><br>";
			$find_sql="SELECT SUBMITED_TIME,ALLOTED_TO FROM jsadmin.MAIN_ADMIN_LOG where PROFILEID='$pid' AND SCREENING_TYPE='O' ORDER BY SUBMITED_TIME DESC LIMIT 0,1";
			$find_result=mysql_query_decide($find_sql);
			$find_row=mysql_fetch_assoc($find_result);
			$screened_by=$find_row['ALLOTED_TO'];
			$screened_time=$find_row['SUBMITED_TIME'];
			$ins_sql="INSERT INTO jsadmin.TRACK_SCREENING (`USERNAME`,`CURRENT_USER`,`CURRENT_TIME`,`SCREENED_BY`,`SCREENED_TIME`) VALUES('$username','$user',now(),'$screened_by','$screened_time')";
			mysql_query_decide($ins_sql) or die(mysql_error_js());
		}
                $msg .= "<a href=\"view_skipped_profiles.php?user=$user&cid=$cid&val=$val\">";

                $msg .= "Continue &gt;&gt;</a>";

		$sql="SELECT HAVEPHOTO,EMAIL,USERNAME,PASSWORD,CITY_RES from newjs.JPROFILE where PROFILEID='$pid'";
                $r1=mysql_query_decide($sql) or die(mysql_error_js());
                $r2=mysql_fetch_array($r1);
                $to=$r2['EMAIL'];

		$smarty->assign('USERNAME',$r2['USERNAME']);
		$smarty->assign('PASSWORD',$r2['PASSWORD']);
	        $smarty->assign('EMAIL',$r2['EMAIL']);

		//Mail only when incomplete checkbox is not checked 
		if($INCOMPLETE=="")
		{
                	//code added by nikhil on June 11 upgradation of register mail that sent when confirmation is done //
	               user_details($pid);//defined in profile/funtions.inc
                                                                                                                             
        	        /* Nikhil code that started on 11 jun ends here  */

			$mail_msg = "We thank you for your interest in Jeevansathi.com.<br><br> This is to notify you that your profile submitted with us has been screened and will now be viewable by members , according to the privacy setting that you have dictated.";

			if(!$email_ev)
				$mail_msg .= "<br><br>".$bl_msg;

			$smarty->assign('MSG_IN_MAIL',$mail_msg);
			$MESSAGE=$smarty->fetch("automated_response_1.htm");

			if($to && $verify_mail!='Y')
				send_email($to,$MESSAGE);
		}
		else
                {
                        if($why_inc!="Please provide reason why this profile is incomplete" && $why_inc!="")
                        {
                                $inc_reason=htmlspecialchars($why_inc,ENT_QUOTES);
                                $sql="replace into jsadmin.INCOMPLETE(PROFILEID,REASON) values ('$pid','$inc_reason')";
                                mysql_query_decide($sql) or die(mysql_error_js());

                        }


                }
                $smarty->assign("name",$user);
                $smarty->assign("cid",$cid);
                $smarty->assign("MSG",$msg);
                $smarty->display("jsadmin_msg.tpl");
	}
	elseif($Submit1)
	{
                $smarty->assign("user",$user);
                $smarty->assign("pid",$pid);
                $smarty->assign("cid",$cid);
		$smarty->assign("c","1");
		$smarty->assign("FROM","SK");
		//code added by Tapan Arora for capturing data for Mis
		$sql1="SELECT SCREENING_VAL FROM jsadmin.MAIN_ADMIN WHERE PROFILEID='$pid'";
		$result1=mysql_query_decide($sql1) or die("$sql1".mysql_error_js());
		$row1=mysql_fetch_array($result1);
		if($row1['SCREENING_VAL'])
		{
			$val="edit";
			$smarty->assign("val","edit");
		}
		else
		{
			$val="new";
			$smarty->assign("val","new");
		}
		//code addition ended	
		$smarty->display("delete_page.tpl");
	}
	elseif($OnHold)
        {
                $smarty->assign("user",$user);
                $smarty->assign("pid",$pid);
                $smarty->assign("cid",$cid);
                $smarty->assign("val",$val);
                $smarty->assign("open_fields",$name);
                $smarty->display("onhold.htm");
        }
	else
	{
		if($email_profileid!="")
                                $pid = $email_profileid;

		$sql="SELECT USERNAME, SCREENING,GENDER,AGE,COUNTRY_RES,CITY_RES,MSTATUS,MANGLIK,MTONGUE,RELIGION,CASTE,SUBCASTE,COUNTRY_BIRTH,CITY_BIRTH,GOTHRA,NAKSHATRA,MESSENGER_ID,YOURINFO,FAMILYINFO,SPOUSE,CONTACT,EDUCATION,PHONE_RES,PHONE_MOB,EMAIL,JOB_INFO,FATHER_INFO,SIBLING_INFO,PARENTS_CONTACT,RELATION,SOURCE,SUBSCRIPTION,DTOFBIRTH,PHOTO_DISPLAY from newjs.JPROFILE where PROFILEID=$pid";
		$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$myrow=mysql_fetch_array($result);

		$smarty->assign("USERNAME",$myrow["USERNAME"]);
		//if(strstr($myrow["SOURCE"],"mb"))
		if(substr($myrow["SOURCE"],0,2)=="mb")
			$smarty->assign("BUREAU","Y");
		if($myrow['SUBSCRIPTION'])
			$smarty->assign("PAID","Y");
		$screen=$myrow['SCREENING'];

		if($myrow["GENDER"] == "")
		{
			$critical_msg = print_r($myrow,true);
			mail("sriram.viswanathan@jeevansathi.com","Gender Blank from JPROFILE in skipped profile","$critical_msg");
		}	

		/********Code Added by sriram on June 21 2007********/
		$smarty->assign("MSTATUS",$myrow['MSTATUS']);
		$smarty->assign("PHOTO_PRIVACY",$myrow['PHOTO_DISPLAY']);
		//populate marital status.
		for($i = 0; $i < count($MSTATUS); $i++)
		{
			$marital_status_arr[$i]["VALUE"] = key($MSTATUS);
			$marital_status_arr[$i]["LABEL"] = $MSTATUS[key($MSTATUS)];
			next($MSTATUS);
		}
		$smarty->assign("marital_status_arr",$marital_status_arr);

		//fetch date of birth
		list($year_of_birth,$month_of_birth,$day_of_birth) = explode("-",$myrow['DTOFBIRTH']);
		$smarty->assign("day_of_birth",$day_of_birth);
		$smarty->assign("month_of_birth",$month_of_birth);
		$smarty->assign("year_of_birth",$year_of_birth);

		populate_day_month_year();

		//defining allowed continuous number's limit.
		$allowed_cont_num_len = 6;

		//added by sriram to create an array of all the OPEN FIELDS.
		//some fields in TABLE donot match the FLAGS_VAL array values, hence the if conditions.
		for($i=0 ; $i < count($FLAGS_VAL); $i++)
		{
			if(key($FLAGS_VAL) == "CITYBIRTH")
				$open_fields[] = "CITY_BIRTH";
			if(key($FLAGS_VAL) == "MESSENGER")
				$open_fields[] = "MESSENGER_ID";
			if(key($FLAGS_VAL) == "PHONERES")
				$open_fields[] = "PHONE_RES";
			if(key($FLAGS_VAL) == "PHONEMOB")
				$open_fields[] = "PHONE_MOB";
			else
				$open_fields[] = key($FLAGS_VAL);

			next($FLAGS_VAL);
		}
		for($i = 0; $i < count($open_fields); $i++)
		{
			//check for obscene words.
			if($myrow[$open_fields[$i]])
			{
				//if(check_obscene_word($myrow[$open_fields[$i]]))
				if(check_obscene_word($myrow[$open_fields[$i]]))
				{
					$smarty->assign("OBSCENE_".$open_fields[$i],"Y");
				}
			}

			//check for continuous numbers in open fields.
			if($open_fields[$i] != "PHONE_RES" && $open_fields[$i] != "PHONE_MOB")
			{
				if(check_for_continuous_numerics($myrow[$open_fields[$i]],$allowed_cont_num_len))
					$smarty->assign("EXCEED_NUMERIC_".$open_fields[$i],"Y");
			}
			//check for proper usage of words.
			if($open_fields[$i] != "EMAIL")
			{
				if(check_for_intelligent_usage($myrow[$open_fields[$i]]))
				{
					$smarty->assign("INTELLIGENT_USAGE_".$open_fields[$i],"Y");
				}
			}
		}
		$obscene_message = "Please check this field for Obscene Words.";
		$exceed_numeric_message = "Please check this field for 6 or more continous numeric values.";
		$intelligent_usage_message = "Please check this field for proper usage of words.";
		$email_err_msg = "'$email_filled' is an invalid email address.";

		$warning_message_start = "<tr class='fieldsnew'><td>&nbsp;</td><td><font color='red'>";
		$warning_message_end = "</font></td>";
		
		$smarty->assign("OBSCENE_MESSAGE",$warning_message_start.$obscene_message.$warning_message_end);
		$smarty->assign("EXCEED_NUMERIC_MESSAGE",$warning_message_start.$exceed_numeric_message.$warning_message_end);
		$smarty->assign("INTELLIGENT_USAGE_MESSAGE",$warning_message_start.$intelligent_usage_message.$warning_message_end);
		$smarty->assign("EMAIL_ERR_MSG",$warning_message_start.$email_err_msg.$warning_message_end);
		$item = array("GENDER","MSTATUS","DTOFBIRTH","PHOTO_DISPLAY");

		/********End of - Code Added by sriram on May 22 2007********/

		$smarty->assign("POSTED_BY",($RELATIONSHIP["$myrow[RELATION]"]));
		$smarty->assign("SHOW_AGE",$myrow["AGE"]);
		$smarty->assign("SHOW_GENDER",$myrow["GENDER"]);
		$smarty->assign("SHOW_COUNTRY",label_select("COUNTRY",$myrow["COUNTRY_RES"]));
		$smarty->assign("SHOW_RELIGION",label_select("RELIGION",$myrow["RELIGION"]));
		$smarty->assign("SHOW_CASTE",label_select("CASTE",$myrow["CASTE"]));
		$smarty->assign("SHOW_COUNTRY_BIRTH",label_select("COUNTRY",$myrow["COUNTRY_BIRTH"]));
		$smarty->assign("SHOW_CITY_BIRTH",$myrow['CITY_BIRTH']);

		if($myrow["COUNTRY_RES"]=='51')
			$smarty->assign("SHOW_CITYRES",label_select("CITY_INDIA",$myrow["CITY_RES"]));
		elseif($myrow["COUNTRY_RES"]=='128')
			$smarty->assign("SHOW_CITYRES",label_select("CITY_USA",$myrow["CITY_RES"]));
		else
			$smarty->assign("SHOW_CITYRES","");

		$smarty->assign("SHOW_MSTATUS",$MSTATUS["$myrow[MSTATUS]"]);
		$smarty->assign("SHOW_MTONGUE",label_select("MTONGUE",$myrow["MTONGUE"]));

		$subcaste_set=isFlagSet("SUBCASTE",$screen);
		$citybirth_set=isFlagSet("CITYBIRTH",$screen);
		$gothra_set=isFlagSet("GOTHRA",$screen);
		$nakshatra_set=isFlagSet("NAKSHATRA",$screen);
		$messenger_set=isFlagSet("MESSENGER",$screen);
		$yourinfo_set=isFlagSet("YOURINFO",$screen);
		$familyinfo_set=isFlagSet("FAMILYINFO",$screen);
		$spouse_set=isFlagSet("SPOUSE",$screen);
		$contact_set=isFlagSet("CONTACT",$screen);
		$education_set=isFlagSet("EDUCATION",$screen);
		$phoneres_set=isFlagSet("PHONERES",$screen);
		$phonemob_set=isFlagSet("PHONEMOB",$screen);
		$email_set=isFlagSet("EMAIL",$screen);
		$jobinfo_set=isFlagSet("JOB_INFO",$screen);
		$fatherinfo_set=isFlagSet("FATHER_INFO",$screen);
		$siblinginfo_set=isFlagSet("SIBLING_INFO",$screen);
		$parentscontact_set=isFlagSet("PARENTS_CONTACT",$screen);
		
		
		if(!$subcaste_set)
		{
			$item[]="SUBCASTE";
			$smarty->assign("SHOWSUBCASTE","Y");
			$smarty->assign("SUBCASTEvalue",$myrow['SUBCASTE']);
		}
		if(!$citybirth_set)
		{
			$item[]="CITY_BIRTH";
			$smarty->assign("SHOWCITY","Y");
			$smarty->assign("CITY_BIRTHvalue",$myrow['CITY_BIRTH']);
		}
		if(!$gothra_set)
		{
			$item[]="GOTHRA";
			$smarty->assign("SHOWGOTHRA","Y");
			$smarty->assign("GOTHRAvalue",$myrow['GOTHRA']);
		}
		if(!$nakshatra_set)
		{
			$item[]="NAKSHATRA";
			$smarty->assign("SHOWNAKSHATRA","Y");
			$smarty->assign("NAKSHATRAvalue",$myrow['NAKSHATRA']);
		}
		if(!$messenger_set)
		{
			$item[]="MESSENGER_ID";
			$smarty->assign("SHOWMESSENGER","Y");
			$smarty->assign("MESSENGER_IDvalue",$myrow['MESSENGER_ID']);
		}
		if(!$yourinfo_set)
		{
			$item[]="YOURINFO";
			$smarty->assign("SHOWYOURINFO","Y");
			$smarty->assign("YOURINFOvalue",$myrow['YOURINFO']);
		}
		if(!$familyinfo_set)
		{
			$item[]="FAMILYINFO";
			$smarty->assign("SHOWFAMILYINFO","Y");
			$smarty->assign("FAMILYINFOvalue",$myrow['FAMILYINFO']);
		}
		if(!$spouse_set)
		{
			$item[]="SPOUSE";
			$smarty->assign("SHOWSPOUSE","Y");
			$smarty->assign("SPOUSEvalue",$myrow['SPOUSE']);
		}
		if(!$contact_set)
		{
			$item[]="CONTACT";
			$smarty->assign("SHOWCONTACT","Y");
			$smarty->assign("CONTACTvalue",$myrow['CONTACT']);
		}
		if(!$education_set)
		{
			$item[]="EDUCATION";
			$smarty->assign("SHOWEDUCATION","Y");
			$smarty->assign("EDUCATIONvalue",$myrow['EDUCATION']);
		}
		if(!$phoneres_set)
		{
			$item[]="PHONE_RES";
			$smarty->assign("SHOWPHONERES","Y");
			$smarty->assign("PHONE_RESvalue",$myrow['PHONE_RES']);
		}
		if(!$phonemob_set)
		{
			$item[]="PHONE_MOB";
			$smarty->assign("SHOWPHONEMOB","Y");
			$smarty->assign("PHONE_MOBvalue",$myrow['PHONE_MOB']);
		}
		if(!$email_set)
		{
			$item[]="EMAIL";
			$smarty->assign("SHOWEMAIL","Y");
			$smarty->assign("EMAILvalue",$myrow['EMAIL']);
		}
		if(!$jobinfo_set)
		{
			$item[]="JOB_INFO";
			$smarty->assign("SHOWJOBINFO","Y");
			$smarty->assign("JOB_INFOvalue",$myrow['JOB_INFO']);
		}
		if(!$fatherinfo_set)
		{
			$item[]="FATHER_INFO";
			$smarty->assign("SHOWFATHERINFO","Y");
			$smarty->assign("FATHER_INFOvalue",$myrow['FATHER_INFO']);
		}
		if(!$siblinginfo_set)
		{
			$item[]="SIBLING_INFO";
			$smarty->assign("SHOWSIBLINGINFO","Y");
			$smarty->assign("SIBLING_INFOvalue",$myrow['SIBLING_INFO']);
		}
		if(!$parentscontact_set)
		{
			$item[]="PARENTS_CONTACT";
			$smarty->assign("SHOWPARENTSCONTACT","Y");
			$smarty->assign("PARENTS_CONTACTvalue",$myrow['PARENTS_CONTACT']);
		}
		if(count($item)>0)
		{
			$itemstring=implode(",",$item);
			$smarty->assign("names",$itemstring);
		}
		if($myrow['MSTATUS']=='A')
		{
			$sql_a ="select REASON,SCREENED from newjs.ANNULLED where PROFILEID='$pid'";
			$result_a=mysql_query_decide($sql_a) or die($sql_a.mysql_error_js());
			if($row_a=mysql_fetch_row($result_a))
			{
				if($row_a[1]=='N')
				{
					$smarty->assign("Annulled_Reason",$row_a[0]);
					$smarty->assign("SHOWANNULLED","Y");
				}
			}
                }
		$smarty->assign("pid",$pid);
		$smarty->assign("screen",$screen);
		$smarty->assign("cid",$cid);
		$smarty->assign("user",$user);
		$smarty->assign("val",$val);
		$smarty->assign("email_err",$email_err);
		$smarty->display("skipped_profile.htm");
	}
}
else
{
	$msg="Your session has been timed out<br><br>";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";
	$smarty->assign("MSG",$msg);
	$smarty->display("jsadmin_msg.tpl");
}
?>
