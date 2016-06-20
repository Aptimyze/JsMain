<?php
/*****************************************************************************************************************************		FILENAME      : useredit.php
*	   MODIIFICATION : Changes made to mark a profile 'INCOMPLETE' in case profile is new and YOURINFO field on edit
*			   page is empty or rendered empty after screening.
*			   Modified lines : 23 , 27 , 30 - 31 ,73 - 77 respectively. 
*	   DONE ON       : 19th May 2005 BY Shobha.
*
****************************************************************************************************************************/

include("time.php");
include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/flag.php");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once("../profile/arrays.php");
include("../profile/functions.inc");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
if(authenticated($cid))
{
	if($Submit)
	{
                $email_ev   = 1;
		$act	    = 1;

		$sql="SELECT USERNAME, SCREENING,ACTIVATED,PREACTIVATED from newjs.JPROFILE where PROFILEID='$pid'";
		$result=mysql_query_decide($sql) or die(mysql_error_js());
		$myrow=mysql_fetch_array($result);
		$username	= $myrow['USERNAME'];
		$activated	= $myrow['ACTIVATED'];
		$preactivated	= $myrow['PREACTIVATED'];
		$SCREENING	=	$myrow['SCREENING'];
		if ($activated == 'U')
			$act = 0;
	
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
                                        $screen=setFlag("$NAME[$i]",$screen);
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
						$str.= $NAME[$i]." = '".addslashes(stripslashes($_POST[$NAME[$i]]))."' ,";
					}
				}
				else
					$str .= $NAME[$i]." = '".addslashes(stripslashes($_POST[$NAME[$i]]))."' ,";

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
			$jprofileUpdateObj = JProfileUpdateLib::getInstance();
			$arrFields = $jprofileUpdateObj->convertUpdateStrToArray($str);
			$arrFields['SCREENING']=$screen;
			//$sql = " UPDATE newjs.JPROFILE set $str, SCREENING='$screen'";
			if($verify_email){
				//$sql.=", VERIFY_EMAIL='$verify_email'";
				$arrFields['VERIFY_EMAIL']=$verify_email;
			}


			if($INCOMPLETE!=""){
				$arrFields['SCREENING'] =0;
				$arrFields['PREACTIVATED'] =$activated;
				$arrFields['ACTIVATED']=N;
				$arrFields['INCOMPLETE']=Y;
				//$sql.=",SCREENING=0, PREACTIVATED='$activated',ACTIVATED='N' , INCOMPLETE='Y'";
			}
			else if($activated=='U' || ($activated=='H' && ($preactivated=='U' || $preactivated=='N')))
			{
				$arrFields['PREACTIVATED'] = $activated;
				$arrFields['ACTIVATED']=Y;
				//$sql.=", PREACTIVATED='$activated',ACTIVATED='Y'";
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
                $jprofileUpdateObj->editJPROFILE($arrFields,$pid,"PROFILEID");
                        //$sql.= " where PROFILEID = '$pid' ";
			//mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$sql_mod="INSERT into jsadmin.SCREENING_LOG(REF_ID,PROFILEID,USERNAME,$name,SCREENED_BY,SCREENED_TIME,ENTRY_TYPE,FIELDS_SCREENED) select '$ref_id',PROFILEID,USERNAME,$name,'$user',now(),'M','$count_screen' from newjs.JPROFILE where PROFILEID = '$pid' ";
			mysql_query_decide($sql_mod) or die(mysql_error_js());
			/*ADD START 10.07.2006 (Tripti) Adding an entry into NEW_EDIT_COUNT*/
			
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
			/*ADD END 10.07.2006 (Tripti) Adding an entry into NEW_EDIT_COUNT*/
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

			$sql= "INSERT into jsadmin.MAIN_ADMIN_LOG (PROFILEID, USERNAME,           SCREENING_TYPE, RECEIVE_TIME, SUBMIT_TIME, ALLOT_TIME, SUBMITED_TIME, ALLOTED_TO, STATUS, SUBSCRIPTION_TYPE, SCREENING_VAL,TIME_ZONE, SUBMITED_TIME_IST) SELECT PROFILEID, USERNAME, SCREENING_TYPE, RECEIVE_TIME, SUBMIT_TIME, ALLOT_TIME, now(), ALLOTED_TO, 'APPROVED', SUBSCRIPTION_TYPE, SCREENING_VAL,'$timezone', CONVERT_TZ(NOW(),'$timezone','IST') from jsadmin.MAIN_ADMIN where PROFILEID='$pid' and SCREENING_TYPE='O'";
			
			mysql_query_decide($sql) or die(mysql_error_js());

			$sql= "DELETE from jsadmin.MAIN_ADMIN where PROFILEID='$pid' and SCREENING_TYPE='O'";
			mysql_query_decide($sql) or die(mysql_error_js());

			if($INCOMPLETE=="")
				$msg = "User $username is successfully screened<br><br>";
			else
				$msg ="User $username is mark as incomplete<BR><BR>";
               	}
		else
			$msg="User $username is already screened<br><br>";                                                                                  
                $msg .= "<a href=\"userview.php?user=$user&cid=$cid\">";

                $msg .= "Continue &gt;&gt;</a>";

		$sql="SELECT HAVEPHOTO,EMAIL,USERNAME,PASSWORD,CITY_RES from newjs.JPROFILE where PROFILEID='$pid'";
                $r1=mysql_query_decide($sql) or die(mysql_error_js());
                $r2=mysql_fetch_array($r1);
                $to=$r2['EMAIL'];

		$smarty->assign('USERNAME',$r2['USERNAME']);
		$smarty->assign('PASSWORD',$r2['PASSWORD']);
	        $smarty->assign('EMAIL',$r2['EMAIL']); 
	
		//Mail only when incomplete is not checked
		if($INCOMPLETE=="")
		{
		 	//code added by nikhil on June 11 upgradation of register mail that sent when confirmation is done //
	               user_details($pid);//defined in profile/functions.inc
                                                                                                                             
        	        /* Nikhil code that started on 11 jun ends here  */



			$mail_msg = "We thank you for your interest in Jeevansathi.com.<br><br> This is to notify you that your profile submitted with us has been screened and will now be viewable by members , according to the privacy setting that you have dictated.";

			if(!$email_ev)
				$mail_msg .= "<br><br>".$bl_msg;

			$smarty->assign('MSG_IN_MAIL',$mail_msg);
			$MESSAGE=$smarty->fetch("automated_response_1.htm");
		
			//if($to && $verify_mail!='Y')
				//send_email($to,$MESSAGE);
			include_once(JsConstants::$docRoot."/ivr/jsivrFunctions.php");
			$phoneVerified = getPhoneStatus("",$pid);
			if($phoneVerified!='Y')
			{
				$email_sender=new EmailSender(MailerGroup::PHONE_VERIFICATION,1775);
				$emailTpl=$email_sender->setProfileId($pid);
				$profileObj=$emailTpl->getSenderProfile();
				$email_sender->send();
			}
			elseif($phoneVerified == "Y")
			{
				if ($to && $verify_mail != 'Y') 
				{
					CommonFunction::sendWelcomeMailer($pid);
				}
					//send_email($to, $MESSAGE);
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
                $smarty->assign("FROM","U");
		
		//code added by Tapan Arora for capturing data for Mis
		$smarty->assign("val",$val);
                //code addition ended
		
		$smarty->display("delete_page.tpl");
	}
	else
	{
		$sql="SELECT USERNAME, SCREENING,GENDER,AGE,COUNTRY_RES,CITY_RES,MSTATUS,MANGLIK,MTONGUE,RELIGION,CASTE,SUBCASTE,COUNTRY_BIRTH,CITY_BIRTH,GOTHRA,NAKSHATRA,MESSENGER_ID,YOURINFO,FAMILYINFO,SPOUSE,CONTACT,EDUCATION,PHONE_RES,PHONE_MOB,EMAIL,JOB_INFO,FATHER_INFO,SIBLING_INFO,PARENTS_CONTACT,RELATION,SOURCE from newjs.JPROFILE where PROFILEID=$pid";
		$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$myrow=mysql_fetch_array($result);

		$smarty->assign("USERNAME",$myrow["USERNAME"]);
		//if(strstr($myrow["SOURCE"],"mb"))
		if(substr($myrow["SOURCE"],0,2)=="mb")
			$smarty->assign("BUREAU","Y");
		$screen=$myrow['SCREENING'];
		
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
		$smarty->display("user_edit.tpl");
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
