<?php
/**************************************************************************************
*	FILENAME	:	master_edit.php
*	DESCRIPTION	:	Presents a profile to be edited without screening
*	CREATED BY	:	Tripti Singh
*	CREATED ON	:	5th July,2006
*	Included	:	
***************************************************************************************/
include("connect.inc");
include("time1.php");
include(JsConstants::$docRoot."/commonFiles/flag.php");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
include_once("../profile/arrays.php");
if(authenticated($cid))
{
	$user=getname($cid);
	if($Medit)
	{
		$smarty->assign("s_user",$s_user);
		$smarty->assign("val",$val);
		$smarty->assign("sno",$sno);
		$smarty->assign("totalnew",$totalnew);
		$smarty->assign("totalqueue",$totalqueue);
		$smarty->assign("flag",$flag);
		if(!trim($username))
		if(!trim($username))
		{
			$smarty->assign("CHECK_USER","Y");
			$smarty->assign("cid",$cid);
			$smarty->assign("user",$user);
			$smarty->display("view_profile_count.htm");
		}
		else
		{
			$username=addslashes(stripslashes(trim($username)));
			$sql='SELECT PROFILEID, USERNAME, SCREENING, ENTRY_DT, MOD_DT, SUBSCRIPTION, GENDER, AGE, COUNTRY_RES, CITY_RES, MSTATUS, MANGLIK, MTONGUE, RELIGION, CASTE, SUBCASTE, COUNTRY_BIRTH, CITY_BIRTH, GOTHRA, NAKSHATRA, MESSENGER_ID, YOURINFO, FAMILYINFO, SPOUSE, CONTACT, EDUCATION, PHONE_RES, PHONE_MOB, EMAIL, JOB_INFO, FATHER_INFO, SIBLING_INFO, PARENTS_CONTACT, RELATION, SOURCE, ACTIVATED,INCOMPLETE from newjs.JPROFILE where USERNAME="'.$username.'"';
			$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$myrow=mysql_fetch_assoc($result);
			if(mysql_num_rows($result)<=0)
			{	
				$smarty->assign("INVALID","Y");
				$smarty->assign("cid",$cid);
				$smarty->assign("user",$user);
				$smarty->display("view_profile_count.htm");
			}
			elseif($myrow['INCOMPLETE']=='Y')
			{
				$smarty->assign("INCOMPLETE","Y");
				$smarty->assign("username",$username);
				$smarty->assign("cid",$cid);
				$smarty->assign("user",$user);
				$smarty->display("view_profile_count.htm");
                        }
			elseif($myrow['ACTIVATED'] == 'D')
			{
				$smarty->assign("DELETE","Y");
				$smarty->assign("username",$username);
				$smarty->assign("cid",$cid);
				$smarty->assign("user",$user);
				$smarty->display("view_profile_count.htm");
                        }
			elseif($myrow['SCREENING'] == '1099511627775')
			{
				$smarty->assign("SCREENED","Y");
				$smarty->assign("username",$username);
				$smarty->assign("cid",$cid);
				$smarty->assign("user",$user);
				$smarty->display("view_profile_count.htm");
			}	
			else
			{
				$pid=$myrow["PROFILEID"];
				$now=date('Y-m-d H:i:s');
				$sql_ma='SELECT PROFILEID from jsadmin.MAIN_ADMIN where USERNAME=TRIM("'.$username.'") AND SCREENING_TYPE="O" AND ALLOTED_TO <> "'.$user.'"AND ALLOT_TIME > DATE_SUB("'.$now.'", INTERVAL 30 MINUTE)';
				$result_ma=mysql_query_decide($sql_ma) or die("$sql_ma".mysql_error_js());
				if(mysql_num_rows($result_ma)>0)
				{
					$smarty->assign("NOT_SCREENED","Y");
					$smarty->assign("username",$username);
					$smarty->assign("cid",$cid);
					$smarty->assign("user",$user);
					$smarty->display("view_profile_count.htm");
				}
				else
				{
					$sql_m='SELECT PROFILEID from jsadmin.MAIN_ADMIN where USERNAME=TRIM("'.$username.'") AND SCREENING_TYPE="O" AND ALLOTED_TO = "'.$user.'"AND ALLOT_TIME > DATE_SUB("'.$now.'", INTERVAL 30 MINUTE)';
					$result_m=mysql_query_decide($sql_m) or die("$sql_m".mysql_error_js());
					if(mysql_num_rows($result_m)==0)
					{
						$sql_ma='SELECT PROFILEID from jsadmin.MAIN_ADMIN where USERNAME=TRIM("'.$username.'") AND SCREENING_TYPE="O" AND ALLOT_TIME < DATE_SUB("'.$now.'", INTERVAL 30 MINUTE)';
						$result_ma=mysql_query_decide($sql_ma) or die("$sql_ma".mysql_error_js());
						if(mysql_num_rows($result_ma)>0)
						{
							$sql_u="UPDATE jsadmin.MAIN_ADMIN set ALLOTED_TO='$user', ALLOT_TIME='$now' WHERE PROFILEID=$pid AND SCREENING_TYPE='O'";
							mysql_query_decide($sql_u) or die("$sql_u".mysql_error_js());
						}
						else
						{
							$receivetime=$myrow['MOD_DT'];
							$submittime=newtime($receivetime,0,$screen_time,0);
							$subscribe=$myrow['SUBSCRIPTION'];
							$screening_val=$myrow['SCREENING'];
							$username=$myrow['USERNAME'];
							$sql_i="INSERT IGNORE INTO jsadmin.MAIN_ADMIN (PROFILEID, USERNAME, RECEIVE_TIME, SUBMIT_TIME, ALLOT_TIME, ALLOTED_TO, SCREENING_TYPE, SUBSCRIPTION_TYPE, SCREENING_VAL) values('$pid','".addslashes($username)."','$receivetime','$submittime','".date("Y-m-d H:i")."', '$user','O', '$subscribe','$screening_val')";
							mysql_query_decide($sql_i) or die("$sql_i".mysql_error_js());
							if(mysql_affected_rows_js()<=0)
							{
								$smarty->assign("NOT_SCREENED","Y");
								$smarty->assign("username",$username);
								$smarty->assign("cid",$cid);
								$smarty->assign("user",$user);
								$smarty->display("view_profile_count.htm");
							}
							else
							{
								$objUpdate = JProfileUpdateLib::getInstance();
								$result = $objUpdate->editJPROFILE(array('ACTIVATED'=>'U'),$pid,'PROFILEID',"ACTIVATED='N'");
								if($result === false ) {
									die('Issue while updating JPROFILE at line 120');
								}
//								$sql_jp="UPDATE newjs.JPROFILE set ACTIVATED='U' WHERE PROFILEID=$pid AND ACTIVATED='N'";
//								mysql_query_decide($sql_jp) or die("$sql_jp".mysql_error_js());
							}
						}
					}
					if($myrow['ACTIVATED'] == 'N')
					{
						$objUpdate = JProfileUpdateLib::getInstance();
						$result = $objUpdate->editJPROFILE(array('ACTIVATED'=>'U'),$pid,'PROFILEID');
						if($result === false ) {
							die('Issue while updating JPROFILE at line 132');
						}
//						$sql_u="UPDATE newjs.JPROFILE SET ACTIVATED='U' WHERE PROFILEID=$pid";
//						 mysql_query_decide($sql_u) or die("$sql_u".mysql_error_js());
					}
					$smarty->assign("USERNAME",$username);	
					//if(strstr($myrow["SOURCE"],"mb"))
					if(substr($myrow["SOURCE"],0,2)=="mb")
						$smarty->assign("BUREAU","Y");
					$screen=$myrow['SCREENING'];
					if($myrow['SUBSCRIPTION'])
						$smarty->assign("PAID","Y");
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
						$sql_a ="select REASON,SCREENED from newjs.ANNULLED where PROFILEID='".$myrow['PROFILEID']."'";
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
										
					//code Added By Tapan Arora
                                        $sql="SELECT ACTIVATED FROM newjs.JPROFILE WHERE PROFILEID='$pid'";
                                        $result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                                        $row=mysql_fetch_array($result);
                                        if($row['ACTIVATED']=='U' or $row['ACTIVATED']=='N')
                                                $smarty->assign("val","new");
                                        elseif($row['ACTIVATED']=='Y')
                                                $smarty->assign("val","edit");

                                        //code Addition ended

					$smarty->assign("pid",$pid);
					$smarty->assign("username",$username);
					$smarty->assign("screen",$screen);
					$smarty->assign("cid",$cid);
					$smarty->assign("user",$user);
					$smarty->assign("medit",1);
					//$smarty->display("master_edit_profile.htm");
					$smarty->display("screen_new.htm");
				}
			}
		}
	}
	elseif($Submit)	//From master_edit_profile form while saving
	{
		$NAME=explode(",",$name); 
		for($i=0;$i<count($NAME);$i++)
		{
			if($NAME[$i]=='EMAIL')
				$NAME[$i]=trim($NAME[$i]);
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
			elseif($NAME[$i]=="PHONE_RES")
			{
                                $sqlp="SELECT STD FROM newjs.JPROFILE WHERE PROFILEID='$pid'";
                                $resp=mysql_query_decide($sqlp) or die("$sqlp".mysql_error_js());
                                $rowp=mysql_fetch_array($resp);
                                $std=$rowp["STD"];
				$phone_res=addslashes(stripslashes($_POST[$NAME[$i]]));
				$str .= $NAME[$i]." = '".$phone_res."' ,PHONE_WITH_STD='$std$phone_res'  ,";
			}
			else
				$str .= $NAME[$i]." = '".addslashes(stripslashes($_POST[$NAME[$i]]))."' ,";
			}
		//$count_screen=count($NAME);
		$str = rtrim($str,",");

		$objUpdate = JProfileUpdateLib::getInstance();
		$arrUpdateParams = $objUpdate->convertUpdateStrToArray($str);
//		$sql = " UPDATE newjs.JPROFILE set $str,";
		if($verify_email){
			$arrUpdateParams["VERIFY_EMAIL"] = $verify_email;
//			$sql.=" VERIFY_EMAIL='$verify_email',";
		}

		/*if (0)
			$sql.= "ACTIVATED = 'N' AND INCOMPLETE ='Y' ";
		else*/
//		$sql.= "ACTIVATED = 'Y' ";
//		$sql.= " where PROFILEID = '$pid' ";

		$arrUpdateParams["ACTIVATED"] = 'Y';
		$result = $objUpdate->editJPROFILE($arrUpdateParams,$pid,'PROFILEID');
		if($result === false ) {
			die('Issue while updating JPROFILE at line 132');
		}

		//mysql_query_decide($sql) or die("$sql".mysql_error_js());
		/*$sql_mod="INSERT into jsadmin.SCREENING_LOG(REF_ID,PROFILEID,USERNAME,$name,SCREENED_BY,SCREENED_TIME,ENTRY_TYPE,FIELDS_SCREENED) select '$ref_id',PROFILEID,USERNAME,$name,'$user',now(),'M','$count_screen' from newjs.JPROFILE where PROFILEID = '$pid' ";
		mysql_query_decide($sql_mod) or die(mysql_error_js());
		$sql= "INSERT into jsadmin.MAIN_ADMIN_LOG (PROFILEID, USERNAME, SCREENING_TYPE, RECEIVE_TIME, SUBMIT_TIME, ALLOT_TIME, SUBMITED_TIME, ALLOTED_TO, STATUS, SUBSCRIPTION_TYPE, SCREENING_VAL) SELECT PROFILEID, USERNAME, SCREENING_TYPE, RECEIVE_TIME, SUBMIT_TIME, ALLOT_TIME, now(), ALLOTED_TO, 'APPROVED', SUBSCRIPTION_TYPE, SCREENING_VAL from jsadmin.MAIN_ADMIN where PROFILEID='$pid' and SCREENING_TYPE='O'";  
		mysql_query_decide($sql) or die(mysql_error_js());

		$sql= "DELETE from jsadmin.MAIN_ADMIN where PROFILEID='$pid' and SCREENING_TYPE='O'";
		mysql_query_decide($sql) or die(mysql_error_js());*/

		$msg = "User $username is successfully edited<br><br>";

		$msg .= "<a href=\"master_edit.php?user=$user&cid=$cid\">";

                $msg .= "Continue &gt;&gt;</a>";

		/*$sql="SELECT EMAIL,USERNAME,PASSWORD from newjs.JPROFILE where PROFILEID='$pid'";
                $r1=mysql_query_decide($sql) or die(mysql_error_js());
                $r2=mysql_fetch_array($r1);
                $to=$r2['EMAIL'];

		$smarty->assign('USERNAME',$r2['USERNAME']);
		$smarty->assign('PASSWORD',$r2['PASSWORD']);
	        $smarty->assign('EMAIL',$r2['EMAIL']); 



		$mail_msg = "We thank you for your interest in Jeevansathi.com1.<br><br> This is to notify you that your profile submitted with us has been screened and will now be viewable by members , according to the privacy setting that you have dictated.";

		$smarty->assign('MSG_IN_MAIL',$mail_msg);
		$MESSAGE=$smarty->fetch("automated_response_1.htm");
		if($to && $verify_mail!='Y')
			send_email($to,$MESSAGE);*/

                $smarty->assign("name",$user);
                $smarty->assign("cid",$cid);
                $smarty->assign("MSG",$msg);
                $smarty->display("jsadmin_msg.tpl");
	}
	else
	{
		$smarty->assign("user",$user);
		$smarty->assign("cid",$cid);
		$smarty->display("master_edit.htm");
	}
}
else
{
	$msg="Your session has been timed out<br>";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";
	$smarty->assign("MSG",$msg);
	$smarty->display("jsadmin_msg.tpl");
}					
?>
