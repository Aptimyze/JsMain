<?php
require_once('connect.inc');
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include('../profile/screening_functions.php');

include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/functions_edit_profile.php");
$symfonyFilePath=realpath($_SERVER['DOCUMENT_ROOT']."/../");
include_once($symfonyFilePath."/lib/model/lib/FieldMapLib.class.php");
include_once($symfonyFilePath."/lib/model/lib/Flag.class.php");
$mysqlObj=new Mysql;

//$db2=connect_737();
//$db=connect_db();

if(authenticated($cid))
{
	$user = getname($cid);
	if ($res_submit)
	{	
		$iserror = 0;
		if (trim($admin_comments) == "")
		{
			$iserror++;
			$comments_clr="red";
			$c_msg = "<font color=\"red\"> Enter your comments !! </font>";
			$smarty->assign("comments_clr",$comments_clr);
			$smarty->assign("c_msg",$c_msg);
		}
		if($MS)
		{
			if($Marital_Status=='' || (($Marital_Status=='A' || $Marital_Status=='M' || $Marital_Status=='S') && (trim($annulled_reason)=="" || trim($annulled_reason)=="If Annulled is selected, Please specify Reason" )))
		
			{
					$iserror++;
					$ms_clr="red";
					$smarty->assign("annulled_reason",htmlspecialchars($annulled_reason,ENT_QUOTES));
					$smarty->assign("ms_clr",$ms_clr);
			}
		}
		if ($dob)
		{
			if (trim($mod_dob) == "" || ($mod_dob ==0) || !(ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $mod_dob)))
			{
				$iserror++;
				$dob_clr="red";
				$smarty->assign("dob_clr",$dob_clr);
			}
			else
			{	
				$age = getAge($mod_dob);
				if (!$gender)
					$current_gender = $orig_gender;
				else
					$current_gender = $mod_gender;
				if ($current_gender == 'F' && $age <18)
				{
					
					$iserror++;
					$dob_clr="red";
					$msg_d = "<font color=\"red\"><br>Age of a female cannot be less than 18 years!!<br>Check date of birth</font>";
					$smarty->assign("dob_clr",$dob_clr);
                                	$smarty->assign("msg_d",$msg_d);
				}
				elseif ($current_gender == 'M' && $age <21)
                                {
                                        $iserror++;
                                        $dob_clr="red";
                                        $msg_d = "<font color=\"red\"><br>Age of a male cannot be less than 21 years!!<br>Check date of birth</font>";
                                        $smarty->assign("dob_clr",$dob_clr);
                                        $smarty->assign("msg_d",$msg_d);
                                }
			}
		}
		if ($gender)
		{
			if (trim($mod_gender) == "")
			{
				$iserror++;
				$gender_clr="red";
				$smarty->assign("gender_clr",$gender_clr);
			}
		}
                if ($religion)
		{       
			if ($mod_religion == $orig_religion )
			{
				$iserror++;
				$religion_clr="red";
				$smarty->assign("religion_clr",$religion_clr);
			}
		}
                
		if ($username)
		{
			if (trim($mod_username) == "")
			{
				$iserror++;
				$username_clr="red";
				$smarty->assign("username_clr",$username_clr);
			}
			else
			{
				$prof_names_sql = "SELECT COUNT(*) AS CNT FROM newjs.JPROFILE WHERE USERNAME = '".addslashes(stripslashes($mod_username))."'";
				$prof_names_res	= mysql_query_decide($prof_names_sql) or die("$prof_names_sql".mysql_error_js());
				$prof_names_row	= mysql_fetch_array($prof_names_res);

				if($prof_names_row["CNT"] > 0)
				{
					$iserror++;
					$msg_u="<font color=\"red\"><br>This username already exists!!<br>Choose another username</font>";
					$username_clr="red";
                                	$smarty->assign("username_clr",$username_clr);
					$smarty->assign("msg_u",$msg_u);
				}	
			}
		}
		if ($sub)
		{
			if ($act=='Y' && count($memberarr)==0)
			{
				$iserror++;
				$sub_clr="red";
				$smarty->assign("sub_clr",$sub_clr);
			}
			
		}
		if ($modifyall)
		{
			if (($is_dob && $mod_dob=="") && !(ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $mod_dob))) 
			{
				$iserror++;
				$dob_clr="red";
				$smarty->assign("dob_clr",$dob_clr);

			}

			if ($is_gender && !$mod_gender)
			{	
				$iserror++;
				$gender_clr="red";
				$smarty->assign("gender_clr",$gender_clr);
			}
                        if ($is_religion && $mod_religion == $orig_religion)
			{	
				$iserror++;
				$religion_clr="red";
				$smarty->assign("religion_clr",$religion_clr);
			}
			if ($is_user && $mod_username=="")
			{
				$iserror++;
				$username_clr="red";
				$smarty->assign("username_clr",$username_clr);
			}
			elseif ($is_user)
			{
				$prof_names_sql = "SELECT COUNT(*) AS CNT FROM newjs.JPROFILE WHERE USERNAME = '".addslashes(stripslashes($mod_username))."'";
                                $prof_names_res = mysql_query_decide($prof_names_sql) or die("$prof_names_sql".mysql_error_js());
                                $prof_names_row = mysql_fetch_array($prof_names_res);
                                                                                                                             
                                if($prof_names_row["CNT"] > 0)
                                {
                                        $iserror++;
                                        $msg_u="<font color=\"red\"><br>This username already exists!!<br>Choose another username</font>";
                                        $username_clr="red";
                                        $smarty->assign("username_clr",$username_clr);
                                        $smarty->assign("msg_u",$msg_u);
                                }
		
			}
			if (($is_sub && $act=='Y' && count($memberarr)==0))
			{	
				$iserror++;
				$sub_clr="red";
				$smarty->assign("sub_clr",$sub_clr);
			}
			if ($is_dob && $mod_dob!="")
                        {
                                $age = getAge($mod_dob);

                                if (!$is_gender)
                                        $current_gender = $orig_gender;
                                else
                                        $current_gender = $mod_gender;
                                if ($current_gender == 'F' && $age <18)
                                {
                                        $iserror++;
                                        $dob_clr="red";
                                        $msg_d = "<font color=\"red\"><br>Age of a female cannot be less than 18 years!!<br>Check date of birth</font>";
                                        $smarty->assign("dob_clr",$dob_clr);
                                        $smarty->assign("msg_d",$msg_d);
                                }
                                elseif ($current_gender == 'M' && $age <21)
                                {
                                        $iserror++;
                                        $dob_clr="red";
                                        $msg_d = "<font color=\"red\"><br>Age of a male cannot be less than 21 years!!<br>Check date of birth</font>";
                                        $smarty->assign("dob_clr",$dob_clr);
                                        $smarty->assign("msg_d",$msg_d);
                                }
                        }
		}
		if ($iserror > 0 )
		{
			$msg.= "<font color=\"red\">There are errors on this page.Please correct them first </font>";
			$request_sql      = "SELECT ID , PROFILEID , ORIG_USERNAME , ORIG_GENDER , ORIG_DTOFBIRTH ,ORIG_RELIGION,ORIG_CASTE, MEMBERSHIP_STATUS ,MSTATUS, USER , REQUEST_DT, REQUEST_FOR,CHANGE_DETAILS,COUNTRY,NEW_COUNTRY,CITY,NEW_CITY FROM jsadmin.PROFILE_CHANGE_REQUEST WHERE CHANGE_STATUS='' AND ID='$id'";
			$request_res = mysql_query_decide($request_sql) or die("$request_sql".mysql_error_js());
			$request_row= mysql_fetch_array($request_res);

			$id                     = $request_row['ID'];
			$pid			= $request_row['PROFILEID'];
			$orig_username          = $request_row['ORIG_USERNAME'];
			$orig_gender       	= $request_row['ORIG_GENDER'];
			$orig_dtofbirth    	= $request_row['ORIG_DTOFBIRTH'];
                        $orig_religion    	= $request_row['ORIG_RELIGION'];
                        $orig_caste     	= $request_row['ORIG_CASTE'];
			$orig_sub	 	= $request_row['MEMBERSHIP_STATUS'];
			$orig_ms		= $request_row['MSTATUS'];
			$requestby             	= $request_row['USER'];
			$change_details    	= $request_row['CHANGE_DETAILS'];
			$request_for		= $request_row['REQUEST_FOR'];
			$request		= explode(',',$request_for);
			$orig_country           = $request_row['COUNTRY'];
	                $new_country            = $request_row['NEW_COUNTRY'];
                	$orig_city              = $request_row['CITY'];
	                $new_city               = $request_row['NEW_CITY'];
	
			if (in_array('MS',$request))
			{
				$sql_sel="SELECT RELIGION FROM newjs.JPROFILE WHERE PROFILEID='$pid'";
				$res_sel=mysql_query_decide($sql_sel) or die(mysql_error_js());
				$row_sel=mysql_fetch_assoc($res_sel);
				if($row_sel['RELIGION']=='2')
					$smarty->assign("muslim",'1');
				if($orig_ms=='A' || $orig_ms=='M' || $orig_ms=='S')
				{
					$sql_a="select REASON from newjs.ANNULLED where PROFILEID='$pid'";
					$res_a=mysql_query_decide($sql_a) or die(mysql_error_js());
					if($row_a=mysql_fetch_row($res_a))
					{
						$smarty->assign("annulled_reason",$row_a[0]);
					}
				}

				$is_ms = 1;
			}
			if (in_array('DB',$request))
				$is_dob = 1;
                        if (in_array('REL',$request))
			{
                            $is_religion=1;
                            $is_caste=1;
                            $relarray =  FieldMap::getFieldLabel('religion','',1);
                            foreach($relarray as $ke => $va)
                            {
                                $relarr[$ke]= array("LABEL" => $va,"VALUE" => $ke);
                            }
                            $smarty->assign("REL_ARR",$relarr);
                            $rel_castearr =FieldMap::getFieldLabel('religion_caste','',1);
                            $caste =FieldMap::getFieldLabel('caste','',1);
                            foreach($rel_castearr as $key=>$value)
                            {
                                $carr= explode(',',$value);
                                foreach($carr as $v)
                                {
                                    $castearr[$key][$v] = array("VALUE" => $v,"LABEL" => $caste[$v]); 
                                }
                            }
                            $smarty->assign("CASTE_ARR",$castearr);
                            
                        }
			if (in_array('G',$request))
				$is_gender = 1;
			if (in_array('U',$request))
				$is_user = 1;
			if (in_array('A',$request) || in_array('D',$request))
			{
				$is_sub = 1;
                        	if(in_array('A',$request))
                                	$act = 'Y';
                        	elseif(in_array('D',$request))
                                	$act = 'N';
			}
			if (in_array('C',$request))
			{
                                $is_country = 1;
				if($orig_country)
				{
					$sql2 = "select LABEL from newjs.COUNTRY_NEW WHERE VALUE='$orig_country'";
					$res2 = mysql_query_decide($sql2) or logError("error",$sql2);
					$myrow2= mysql_fetch_row($res2);
					$COUNTRY_RES_VAL=$myrow2[0];
					$smarty->assign("COUNTRY_RES_VAL",$COUNTRY_RES_VAL);
				}
				$smarty->assign("COUNTRY_RES",create_dd($new_country,"Country_Residence"));
				if($orig_city)
				{
					$sql2 = "select LABEL from newjs.CITY_NEW WHERE VALUE='$orig_city'";
					$res2 = mysql_query_decide($sql2) or logError("error",$sql2);
					$myrow2= mysql_fetch_row($res2);
					$CITY_RES_VAL=$myrow2[0];
					$smarty->assign("CITY_RES_VAL",$CITY_RES_VAL);
				}
				$ret = "";
				$dont_made_other_selected=0;
				$sql_ci = "select VALUE, LABEL from newjs.CITY_NEW WHERE COUNTRY_VALUE='$new_country' AND TYPE!='STATE' order by SORTBY";
				$res_ci = mysql_query_decide($sql_ci) or logError("error",$sql_ci);
				$ret .= "<span><select style=\"width:185px;\" name=\"City_Res\" id=\"City_arr\" onchange=\"show_code();\">";
				while($myrow_ci = mysql_fetch_array($res_ci))
				{
					if($myrow_ci["VALUE"]==$new_city)
					{
						$ret .= "<option value=\"$myrow_ci[VALUE]\" selected>$myrow_ci[LABEL]</option>\n";
						$dont_made_other_selected=1;
					}
					else
                                	$ret .= "<option value=\"$myrow_ci[VALUE]\">$myrow_ci[LABEL]</option>\n";
                		}
				if(!$dont_made_other_selected && $new_city!="")
		                        $ret .= "<option value=\"0\" selected>Others</option>\n";
                		else
		                        $ret .= "<option value=\"0\">Others</option>\n";
                		$ret .= "</select></span>";
		                $smarty->assign("CITY_ARR",$ret);
			}

			$request_dt             = substr($request_row['REQUEST_DT'],0,10);
			list($yy,$mm,$dd)       = explode("-",$request_dt);
			$request_dt        	= my_format_date($dd,$mm,$yy);	

			//Getting connection on sharded server
			$myDbName=getProfileDatabaseConnectionName($pid,'',$mysqlObj);
			$myDb=$mysqlObj->connect($myDbName);		
	
			$recv_contacts_sql      = "SELECT COUNT(*) AS COUNT FROM newjs.CONTACTS WHERE RECEIVER = '$pid'";
			$recv_contacts_res      = $mysqlObj->executeQuery($recv_contacts_sql,$myDb) or die(mysql_error_js($myDb));
			$recv_contacts_row      = $mysqlObj->fetchArray($recv_contacts_res);
			$recvd_contact          = $recv_contacts_row["COUNT"];

			$send_contacts_sql      = "SELECT COUNT(*) AS COUNT FROM newjs.CONTACTS WHERE SENDER = '$pid'";
			$send_contacts_res      = $mysqlObj->executeQuery($send_contacts_sql,$myDb) or die(mysql_error_js($myDb));
			$send_contacts_row      = $mysqlObj->fetchArray($send_contacts_res);
			$send_contact           = $send_contacts_row["COUNT"];
			if ($orig_sub != '')
	                {
        	                $subscription           = explode(",",$orig_sub);
                	        for($i=0; $i<count($subscription); $i++)
                        	{
                                	switch($subscription[$i])
                                	{
                                        	case 'F':$sub_array[$i]="Full Member";
                                                	 break;
                                        	case 'H':$sub_array[$i]="Horoscope";
                                                	 break;
                                        	case 'B':$sub_array[$i]="Profile Highlighting";
                                                	break;
                                        	case 'K':$sub_array[$i]="Kundali MatchMaker";
                                                	 break;
                                        	case 'D':$sub_array[$i]="e-Classifieds";
                                                	 break;
                                        	case 'A':$sub_array[$i]="Astro Compatibility";
                                                	 break;
                                	}
                        	}
                	}
			if(is_array($memberarr))
			{
				if(in_array('F',$memberarr))
					$smarty->assign("FULL_MEM","Y");
				if(in_array('B',$memberarr))
					$smarty->assign("BOLD_LIST","Y");
				if(in_array('H',$memberarr))
					$smarty->assign("HORO","Y");
				if(in_array('K',$memberarr))
					$smarty->assign("KUNDALI","Y");
				
			//done due to new service added called eclassified NEW CHANGES
				if(in_array('D',$memberarr))
					$smarty->assign("ECLASSIFIED","Y");
				if(in_array('A',$memberarr))
					$smarty->assign("ASTRO","Y");
			}
			$smarty->assign("sub_arr",$sub_array);
			$smarty->assign("id",$id);
			$smarty->assign("pid",$pid);
			$smarty->assign("cid",$cid);
			$smarty->assign("user",$user);
			$smarty->assign("record_id",$record_id);
			
			$smarty->assign("admin_comments",$admin_comments);
			$smarty->assign("mod_dob",$mod_dob);
			$smarty->assign("mod_gender",$mod_gender);
			$smarty->assign("mod_username",$mod_username);
                        $smarty->assign("mod_religion",$mod_religion);
                        $smarty->assign("mod_caste",$mod_caste);
			$smarty->assign("mod_sub",$mod_sub);
			$smarty->assign("mod_country",$mod_country);
			$smarty->assign("marital",$Marital_Status);
			$smarty->assign("is_dob",$is_dob);
			$smarty->assign("is_ms",$is_ms);
			$smarty->assign("MS",$MS);
			$smarty->assign("del_contacts",$del_contacts);
			$smarty->assign("is_gender",$is_gender);
                        $smarty->assign("is_religion",$is_religion);
                        $smarty->assign("is_caste",$is_caste);
			$smarty->assign("is_user",$is_user);
			$smarty->assign("is_sub",$is_sub);
			$smarty->assign("is_country",$is_country);
			$smarty->assign("act",$act);
			$smarty->assign("annulled_reason",htmlspecialchars($annulled_reason,ENT_QUOTES));
			$smarty->assign("memberarr",$memberarr);
			$smarty->assign("iserror",$iserror);
			$smarty->assign("recvd_contact",$recvd_contact);
			$smarty->assign("send_contact",$send_contact);
			$smarty->assign("orig_username",$orig_username);
			$smarty->assign("orig_country",$orig_country);
	                $smarty->assign("orig_city",$orig_city);
			$smarty->assign("orig_gender",$orig_gender);
			$smarty->assign("orig_ms",$orig_ms);
			$smarty->assign("orig_dtofbirth",$orig_dtofbirth);
                        $smarty->assign("orig_religion",$orig_religion);
                        $smarty->assign("orig_caste",$orig_caste);
			$smarty->assign("orig_sub",$orig_sub);
			$smarty->assign("requestby",$requestby);
			$smarty->assign("request_dt",$request_dt);
			$smarty->assign("change_details",$change_details);
			$smarty->assign("msg",$msg);
			$smarty->assign("dob",$dob);
			$smarty->assign("gender",$gender);
			$smarty->assign("username",$username);	
			$smarty->assign("country",$country);
			$smarty->assign("sub",$sub);
			$smarty->assign("modifyall",$modifyall);

			/*Code to get History of Request from this user.*/
			$sql_history = "Select ID , USER , REQUEST_DT, REQUEST_FOR, ADMIN_COMMENTS FROM jsadmin.PROFILE_CHANGE_REQUEST WHERE PROFILEID = $request_row[PROFILEID] and ID <> $request_row[ID]";
			$res_history =  mysql_query_decide($sql_history) or die("$sql_history".mysql_error_js());
			$request_history_cnt = mysql_num_rows($res_history);

			while($myrow_history= mysql_fetch_array($res_history)){

				$request_dt             = substr($myrow_history['REQUEST_DT'],0,10);
				list($yy,$mm,$dd)       = explode("-",$request_dt);
				$request_dt             = my_format_date($dd,$mm,$yy);

				$request_for            = $myrow_history['REQUEST_FOR'];
				$request_for_temp       = explode(',',$request_for);

				if (in_array('MS',$request_for_temp))
					$request_for = "Marital Status Change";
				if (in_array('DB',$request_for_temp))
					$request_for = "DOB Change";
                                if (in_array('REL',$request_for_temp))
					$request_for = "Religion Change";
				if (in_array('G',$request_for_temp))
					$request_for = "Gender Change";
				if (in_array('U',$request_for_temp))
                                $request_for = "Username Change";
				if (in_array('A',$request_for_temp))
					$request_for = "Membership Activate";
				if (in_array('D',$request_for_temp))
					$request_for = "Membership De-activate";
				if (in_array('C',$request_for_temp))
					$request_for = "Country Change";


				$request_history[] = array(     "ID"=>$myrow_history['ID'],
								"REQUEST_FROM"=>$myrow_history['USER'],
								"REQUEST_DT"=>$request_dt,
								"REQUEST_FOR"=>$request_for,
								"ADMIN_COMMENTS"=>substr($myrow_history['ADMIN_COMMENTS'],0,10)
							);
			}
			$smarty->assign("request_history",$request_history);
	                $smarty->assign("request_history_cnt",$request_history_cnt);
			/*End of code to get History of Request from this user.*/

			$smarty->display("reply_request.htm");
		}
		else
		{
			$dup_value=get_from_duplication_check_fields($pid);
			if($dup_value=='new')
				$not_to_update_dup_value=true;
			else
				$dup_value=$dup_value[FIELDS_TO_BE_CHECKED];
			if ($modifyall)
			{
				$msgarr			= array();
				//$mod_profile_sql	= array();
				$mod_jsadmintbl_sql   	= "UPDATE jsadmin.PROFILE_CHANGE_REQUEST SET RESPONSE_DT=NOW()";
				//$edit_profile_sql	= "UPDATE newjs.JPROFILE SET ";
					
				
				if ($is_dob)
				{	
					// for deletion of the contacts made by the user on change of date of birth.
					//Changed by lavesh.
					if($del_contacts)
						delete_record($pid);

					$age = getAge($mod_dob);
					$mod_jsadmintbl_sql.=" ,NEW_DTOFBIRTH 	= '$mod_dob'";
					//$mod_profile_sql[]=" DTOFBIRTH  = '$mod_dob' , AGE = '$age'";
					$arrFields['DTOFBIRTH'] = $mod_dob;
					$arrFields['AGE'] = $age;
					$msgarr[] = " Date of Birth  updated successfully. ";
					$dup_value=Flag::setFlag('dtofbirth',$dup_value,'duplicationFieldsVal');
					$update_dup=true;

					update_astro_dob($pid,$mod_dob);
				}
				if($is_ms)
				{
					$mod_jsadmintbl_sql.=" ,NEW_MSTATUS 	= '$Marital_Status'";
					//$mod_profile_sql[]=" MSTATUS  = '$Marital_Status' ";
					$arrFields['MSTATUS'] = $Marital_Status;
					$msgarr[] = " Marital Status  updated successfully. ";
					
					if($Marital_Status=='A')
					{	
						$sql="replace newjs.ANNULLED (PROFILEID,REASON,SCREENED,UPDATE_DT) values ('$pid','".htmlspecialchars($annulled_reason,ENT_QUOTES)."','Y',now())";
						mysql_query_decide($sql) or die(mysql_error_js());
					}
					$dup_value=Flag::setFlag('mstatus',$dup_value,'duplicationFieldsVal');
					$update_dup=true;
					
				}
				
				if ($is_user)
				{
					$mod_jsadmintbl_sql.=" ,NEW_USERNAME  	= '".addslashes(stripslashes($mod_username))."'";
					//$mod_profile_sql[]=" USERNAME    = '".addslashes(stripslashes($mod_username))."'";
					$arrFields['USERNAME'] = addslashes(stripslashes($mod_username));
					$msgarr[] = " Username updated successfully. ";
				}
                                if ($is_religion)
				{
					$mod_jsadmintbl_sql.=" ,NEW_RELIGION  	= '".addslashes(stripslashes($mod_religion))."'";
					//$mod_profile_sql[]=" RELIGION    = '".addslashes(stripslashes($mod_religion))."'";
					$arrFields['RELIGION'] = addslashes(stripslashes($mod_religion));
					$msgarr[] = " Religion updated successfully. ";
                                        switch ($mod_religion)
                                        {
                                            case '5' : $mod_caste = "153";
                                                        break;
                                            case '6' : $mod_caste = "148";
                                                        break;
                                            case '7' : $mod_caste = "1";
                                                        break;
                                            case '10': $mod_caste = "496";
                                        }
                                        $mod_jsadmintbl_sql.=" ,NEW_CASTE  	= '".addslashes(stripslashes($mod_caste))."'";
					//$mod_profile_sql[]=" CASTE    = '".addslashes(stripslashes($mod_caste))."'";
					$arrFields['CASTE'] = addslashes(stripslashes($mod_caste));
					$msgarr[] = " Caste updated successfully. ";
                                        if($mod_religion!=$orig_religion)
                                            delete_record($pid);
				}
				
				if ($is_sub)
				{
                			if ($act == "N")
		                        	$subscription = "";
	                		else
		                	{
                		        	$subscription = implode(',',$memberarr);
		                 	}
					$mod_jsadmintbl_sql.=" , NEW_SUBSCRIPTION = '$subscription'";
					//$mod_profile_sql[]=" SUBSCRIPTION = '$subscription'";
					$arrFields['SUBSCRIPTION'] = $subscription;
					$msgarr[] = " Subscription status  updated successfully. ";
				}
				if ($is_gender)
				{
					$mod_jsadmintbl_sql.=" ,NEW_GENDER	= '$mod_gender'";
					//$mod_profile_sql[] =" GENDER = '$mod_gender'";
					$arrFields['GENDER'] = $mod_gender;
					$msgarr[] = " Gender updated successfully. ";
					$dup_value=Flag::setFlag('gender',$dup_value,'duplicationFieldsVal');
					$update_dup=true;

					//added by sriram.
                                        gender_related_changes($pid, $orig_gender);

				}
				if ($is_country)
                                {
                                        $mod_jsadmintbl_sql.=" , NEW_COUNTRY      = '$mod_country'";
                                        $mod_jsadmintbl_sql.=" , NEW_CITY      = '$City_Res`'";
                                        //$mod_profile_sql[]=" COUNTRY_RES = '$mod_country'";
                                        $arrFields['COUNTRY_RES'] = $mod_country;
                                        $arrFields['CITY_RES'] = $City_Res;
                                        //$mod_profile_sql[]=" CITY_RES = '$City_Res'";
                                        $msgarr[] = " Country and city updated successfully. ";

                                }
				$mod_jsadmintbl_sql.= ", ADMIN_COMMENTS = '".addslashes(stripslashes($admin_comments))."' ,  CHANGE_STATUS='Y' where ID='$id'";
				if (count($arrFields))
				{
					//$sql1 = implode(",",$mod_profile_sql);
					$jprofileUpdateObj = JProfileUpdateLib::getInstance();
					$jprofileUpdateObj->editJPROFILE($arrFields,$pid,"PROFILEID");
					//$sql= $edit_profile_sql.$sql1." where PROFILEID='$pid'";
					//mysql_query_decide($sql) or die(mysql_error_js());
				}

				mysql_query_decide($mod_jsadmintbl_sql) or die("$mod_jsadmintbl_sql".mysql_error_js());
				if(!$not_to_update_dup_value && $update_dup)
					insert_in_duplication_check_fields($pid,'edit',$dup_value);
			}
			else
			{
				$msgarr			= array();
				//$mod_profile_sql	= array();
				$mod_jsadmintbl_sql     = "UPDATE jsadmin.PROFILE_CHANGE_REQUEST SET RESPONSE_DT=NOW()";
				//$edit_profile_sql       = "UPDATE newjs.JPROFILE SET ";
																     
				if ($dob)
				{
					//Changed by lavesh.
					if($del_contacts)
						delete_record($pid);

					$age = getAge($mod_dob);
					$mod_jsadmintbl_sql.=" , NEW_DTOFBIRTH   = '$mod_dob'";
					//$mod_profile_sql[]=" DTOFBIRTH  = '$mod_dob' , AGE = '$age'";
					$arrFields['DTOFBIRTH'] = $mod_dob;
					$arrFields['AGE'] = $age;
					$msgarr[] = " Date of Birth  updated successfully. ";
					$dup_value=Flag::setFlag('dtofbirth',$dup_value,'duplicationFieldsVal');
					$update_dup=true;

					update_astro_dob($pid,$mod_dob);
				}
				if ($MS)
				{
			
					$mod_jsadmintbl_sql.=" ,NEW_MSTATUS 	= '$Marital_Status'";
					//$mod_profile_sql[]=" MSTATUS  = '$Marital_Status' ";
					$arrFields['MSTATUS'] = $Marital_Status;
					$msgarr[] = " Marital Status  updated successfully. ";
					if($Marital_Status=='A')
					{	
						$sql="replace newjs.ANNULLED (PROFILEID,REASON,SCREENED,UPDATE_DT) values ('$pid','".htmlspecialchars($annulled_reason,ENT_QUOTES)."','Y',now())";
						mysql_query_decide($sql) or die(mysql_error_js());
					}
					$dup_value=Flag::setFlag('mstatus',$dup_value,'duplicationFieldsVal');
					$update_dup=true;
				
				}
				if ($religion)
				{
					$mod_jsadmintbl_sql.=" , NEW_RELIGION    = '".addslashes(stripslashes($mod_religion))."'";
					//$mod_profile_sql[]="  RELIGION    = '".addslashes(stripslashes($mod_religion))."'";
					$arrFields['RELIGION'] = addslashes(stripslashes($mod_religion));
					$msgarr[] = " Religion  updated successfully. ";
                                        switch ($mod_religion)
                                        {
                                            case '5' : $mod_caste = "153";
                                                        break;
                                            case '6' : $mod_caste = "148";
                                                        break;
                                            case '7' : $mod_caste = "1";
                                                        break;
                                            case '10': $mod_caste = "496";
                                        }
                                        $mod_jsadmintbl_sql.=" , NEW_CASTE    = '".addslashes(stripslashes($mod_caste))."'";
					//$mod_profile_sql[]="  CASTE    = '".addslashes(stripslashes($mod_caste))."'";
					$arrFields['CASTE'] = addslashes(stripslashes($mod_caste));
					$msgarr[] = " Caste  updated successfully. ";
                                        if($mod_religion!=$orig_religion)
                                            delete_record($pid);
				}
				if ($username)
				{
					$mod_jsadmintbl_sql.=" , NEW_USERNAME    = '".addslashes(stripslashes($mod_username))."'";
					//$mod_profile_sql[]="  USERNAME    = '".addslashes(stripslashes($mod_username))."'";
					$arrFields['USERNAME'] = addslashes(stripslashes($mod_username));
					$msgarr[] = " Username  updated successfully. ";
				}
				if ($sub)
				{
                                        if ($act == "N")
                                                $subscription = "";
                                        else
                                        {
                                               $subscription = implode(',',$memberarr);
                                        }

					$mod_jsadmintbl_sql.=" , NEW_SUBSCRIPTION = '$subscription'";
					//$mod_profile_sql[]=" SUBSCRIPTION = '$subscription'";
					$arrFields['SUBSCRIPTION'] = $subscription;
					$msgarr[] = " Subscription status  updated successfully. ";
				}
				if ($gender)
				{
					$mod_jsadmintbl_sql.=" , NEW_GENDER      = '$mod_gender'";
					//$mod_profile_sql[]=" GENDER = '$mod_gender'";
					$arrFields['GENDER'] = $mod_gender;
					$msgarr[] = " Gender  updated successfully. ";
					$dup_value=Flag::setFlag('gender',$dup_value,'duplicationFieldsVal');
					$update_dup=true;

					//added by sriram.
                                        gender_related_changes($pid, $orig_gender);

				}
				if ($country)
                                {
                                        $mod_jsadmintbl_sql.=" , NEW_COUNTRY      = '$mod_country'";
					$mod_jsadmintbl_sql.=" , NEW_CITY      = '$City_Res`'";
     //                                    $mod_profile_sql[]=" COUNTRY_RES = '$mod_country'";
					// $mod_profile_sql[]=" CITY_RES = '$City_Res'";
					$arrFields['COUNTRY_RES'] = $mod_country;
					$arrFields['CITY_RES'] = $City_Res;
                                        $msgarr[] = " Country and city updated successfully. ";

                                }
				$mod_jsadmintbl_sql.= ", ADMIN_COMMENTS = '".addslashes(stripslashes($admin_comments))."' ,CHANGE_STATUS='Y' where ID='$id'";
				if (count($arrFields))
				{
					$jprofileUpdateObj = JProfileUpdateLib::getInstance();
					$jprofileUpdateObj->editJPROFILE($arrFields,$pid,"PROFILEID");	
					//$sql1 = implode(",",$mod_profile_sql);
					//$sql= $edit_profile_sql.$sql1." where PROFILEID='$pid'";
					//mysql_query_decide($sql) or die(mysql_error_js());
				}                                                                                                    
				mysql_query_decide($mod_jsadmintbl_sql) or die("$mod_jsadmintbl_sql".mysql_error_js());
				if(!$not_to_update_dup_value && $update_dup)
					insert_in_duplication_check_fields($pid,'edit',$dup_value);

			}
			
			if($country)
			{
				$sql    = "SELECT  ORIG_USERNAME , REQUEST_DT, REQUEST_FOR,CHANGE_DETAILS  FROM jsadmin.PROFILE_CHANGE_REQUEST WHERE ID='$id'";
                                $res    = mysql_query_decide($sql) or die(mysql_error_js());
                                $row    = mysql_fetch_array($res);
                                $orig_username = $row["ORIG_USERNAME"];
				
				$sql    = "SELECT EMAIL FROM newjs.JPROFILE WHERE USERNAME = '$orig_username'";
                                $res    = mysql_query_decide($sql) or die(mysql_error_js());
                                $row    = mysql_fetch_array($res);
                                $sendto = $row['EMAIL'];
				$reply  = "Dear $orig_username,\n\nYour request for change of Country has been approved. Please login to www.jeevansathi.com to view your updated profile.\n\nRegards,\nJeevansathi.com Team";
				$subject = "Approved - Profile details changed";
				$sendby  = "customerdesk@jeevansathi.com";
				$sql2    = "SELECT EMAIL FROM jsadmin.PSWRDS WHERE USERNAME = '$user'";
                                $res2    = mysql_query_decide($sql2) or die(mysql_error_js());
                                $row2   = mysql_fetch_array($res2);
                                $reply_to = $row2['EMAIL'];
				
				send_email($sendto,$reply,$subject,$sendby,"","","","","","","",$reply_to);
			}
			else
			{
				$sql    = "SELECT EMAIL FROM jsadmin.PSWRDS WHERE USERNAME = '$user'";
				$res    = mysql_query_decide($sql) or die(mysql_error_js());
				$row    = mysql_fetch_array($res);
				$sendby = $row['EMAIL'];

				$sql    = "SELECT EMAIL FROM jsadmin.PSWRDS WHERE USERNAME = '$requestby'";
				$res    = mysql_query_decide($sql) or die(mysql_error_js());
				$row    = mysql_fetch_array($res);
				$sendto = $row['EMAIL'];

				$sql    = "SELECT  ORIG_USERNAME , REQUEST_DT, REQUEST_FOR,CHANGE_DETAILS  FROM jsadmin.PROFILE_CHANGE_REQUEST WHERE ID='$id'";
				$res    = mysql_query_decide($sql) or die(mysql_error_js());
				$row    = mysql_fetch_array($res);
				$orig_username = $row["ORIG_USERNAME"];
																     
				$reply  = "<b><font color=\"#9F4000\"> Request for change of $orig_username's profile </b><br> Dated : ";
				$reply.=substr($row['REQUEST_DT'],0,10)."<br>";
				//$reply.=$row["REQUEST_FOR"]."<br>".$row["CHANGE_DETAILS"];
				$reply.="<br>".nl2br($row["CHANGE_DETAILS"]);
				$reply.=" <br><br><b> Reply : </b><br>".nl2br($admin_comments)."</font>";
                        	$subject= "Reply to request for change of $orig_username's profile ";
				send_email($sendto,$reply,$subject,$sendby);
			}
			if($record_id){
			echo	"<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/profile/sugarcrm_registration/registration_page1.php?sugar_incomplete=Y&secondary_source=C&from_sugar_exec=Y&record_id=$record_id&crq=1\"></  body></html>";
			}else{

                        $msg = implode("<br>",$msgarr);
                        $msg .= "<br><br><a href=\"show_editprofile_request.php?user=$user&cid=$cid\">Continue &gt;&gt;</a>";                        $smarty->assign("user",$user);
                        $smarty->assign("cid",$cid);
                        $smarty->assign("MSG",$msg);
                        $smarty->display("jsadmin_msg.tpl");
			}
		}
	}
	else
	{
		$request_sql      = "SELECT ID , PROFILEID , ORIG_USERNAME , ORIG_GENDER , ORIG_DTOFBIRTH ,ORIG_RELIGION, ORIG_CASTE , MEMBERSHIP_STATUS , MSTATUS,USER , REQUEST_DT, REQUEST_FOR,CHANGE_DETAILS,COUNTRY,NEW_COUNTRY,CITY,NEW_CITY FROM jsadmin.PROFILE_CHANGE_REQUEST WHERE CHANGE_STATUS='' AND ID='$id'";
                $request_res = mysql_query_decide($request_sql) or die("$request_sql".mysql_error_js());
                $request_row= mysql_fetch_array($request_res);

                $id                     = $request_row['ID'];
                $pid                    = $request_row['PROFILEID'];
                $orig_username          = $request_row['ORIG_USERNAME'];
                $orig_gender            = $request_row['ORIG_GENDER'];
                $orig_dtofbirth         = $request_row['ORIG_DTOFBIRTH'];
                $orig_religion          = $request_row['ORIG_RELIGION'];
                $orig_caste             = $request_row['ORIG_CASTE'];
                $orig_sub               = $request_row['MEMBERSHIP_STATUS'];
                $orig_ms           	= $request_row['MSTATUS'];
                $requestby              = $request_row['USER'];
                $change_details         = $request_row['CHANGE_DETAILS'];
		$orig_country		= $request_row['COUNTRY'];
		$new_country		= $request_row['NEW_COUNTRY'];
		if($orig_country)
		{
			$sql2 = "select LABEL from newjs.COUNTRY_NEW WHERE VALUE='$orig_country'";
                        $res2 = mysql_query_decide($sql2) or logError("error",$sql2);
                        $myrow2= mysql_fetch_row($res2);
                        $COUNTRY_RES_VAL=$myrow2[0];
                        $smarty->assign("COUNTRY_RES_VAL",$COUNTRY_RES_VAL);
		}
		$smarty->assign("COUNTRY_RES",create_dd($new_country,"Country_Residence"));
		$orig_city		= $request_row['CITY'];
		$new_city		= $request_row['NEW_CITY'];
		if($orig_city)
                {
                        $sql2 = "select LABEL from newjs.CITY_NEW WHERE VALUE='$orig_city'";
                        $res2 = mysql_query_decide($sql2) or logError("error",$sql2);
                        $myrow2= mysql_fetch_row($res2);
                        $CITY_RES_VAL=$myrow2[0];
                        $smarty->assign("CITY_RES_VAL",$CITY_RES_VAL);
                }
		$ret = "";
		$dont_made_other_selected=0;
		$sql_ci = "select VALUE, LABEL from newjs.CITY_NEW WHERE COUNTRY_VALUE='$new_country' AND TYPE!='STATE' order by SORTBY";
		$res_ci = mysql_query_decide($sql_ci) or logError("error",$sql_ci);
		$ret .= "<span><select style=\"width:185px;\" name=\"City_Res\" id=\"City_arr\" onchange=\"show_code();\">";
		while($myrow_ci = mysql_fetch_array($res_ci))
		{
			if($myrow_ci["VALUE"]==$new_city)
			{
				$ret .= "<option value=\"$myrow_ci[VALUE]\" selected>$myrow_ci[LABEL]</option>\n";
				$dont_made_other_selected=1;
			}
			else
				$ret .= "<option value=\"$myrow_ci[VALUE]\">$myrow_ci[LABEL]</option>\n";
		}
		if(!$dont_made_other_selected && $new_city!="")
			$ret .= "<option value=\"0\" selected>Others</option>\n";
		else
			$ret .= "<option value=\"0\">Others</option>\n";
		$ret .= "</select></span>";
		$smarty->assign("CITY_ARR",$ret);
                $request_for            = $request_row['REQUEST_FOR'];
                
                $request                = explode(',',$request_for);
           	if(in_array("MS",$request))
           	{

			$sql_sel="SELECT RELIGION FROM newjs.JPROFILE WHERE PROFILEID='$pid'";
			$res_sel=mysql_query_decide($sql_sel) or die(mysql_error_js());
			$row_sel=mysql_fetch_assoc($res_sel);	
			if($row_sel['RELIGION']=='2')
				$smarty->assign("muslim",'1');
			if($orig_ms=='A' || $orig_ms=='M' || $orig_ms=='S')
			{
				$sql_a="select REASON from newjs.ANNULLED where PROFILEID='$pid'";
				$res_a=mysql_query_decide($sql_a) or die(mysql_error_js());
				if($row_a=mysql_fetch_row($res_a))
				{
					$smarty->assign("annulled_reason",$row_a[0]);
				}
			}
			$is_ms=1;                
          	} 
                if (in_array('DB',$request))
                        $is_dob = 1;
                if (in_array('G',$request))
                        $is_gender = 1;
                if (in_array('U',$request))
                        $is_user = 1;
                if (in_array('REL',$request))
			{
                            $is_religion=1;
                        $is_caste=1;
                        $relarray =  FieldMap::getFieldLabel('religion','',1);
                        foreach($relarray as $ke => $va)
                        {
                            $relarr[$ke]= array("LABEL" => $va,"VALUE" => $ke);
                        }
                        $smarty->assign("REL_ARR",$relarr);
                        $rel_castearr =FieldMap::getFieldLabel('religion_caste','',1);
                        $caste =FieldMap::getFieldLabel('caste','',1);
                        foreach($rel_castearr as $key=>$value)
                        {
                            $carr= explode(',',$value);
                            foreach($carr as $v)
                            {
                                $castearr[$key][$v] = array("VALUE" => $v,"LABEL" => $caste[$v]); 
                            }
                        }
                        $smarty->assign("CASTE_ARR",$castearr);
                            
                        }
                if (in_array('A',$request) || in_array('D',$request))
		{
			$is_sub = 1;
			if(in_array('A',$request))
                        	$act = 'Y';
			elseif(in_array('D',$request))
				$act = 'N';
		}
		if (in_array('C',$request))
                        $is_country = 1;
                                                                                                                             
                $request_dt             = substr($request_row['REQUEST_DT'],0,10);
                list($yy,$mm,$dd)       = explode("-",$request_dt);
                $request_dt             = my_format_date($dd,$mm,$yy);
		$myDbName=getProfileDatabaseConnectionName($pid,'',$mysqlObj);
                $myDb=$mysqlObj->connect($myDbName);
		
                $recv_contacts_sql      = "SELECT COUNT(*) AS COUNT FROM newjs.CONTACTS WHERE RECEIVER = '$pid'";
                $recv_contacts_res      = $mysqlObj->executeQuery($recv_contacts_sql,$myDb) or die(mysql_error_js($myDb));
                $recv_contacts_row      = $mysqlObj->fetchArray($recv_contacts_res); 
                $recvd_contact          = $recv_contacts_row["COUNT"];
                                                                                                                             
                $send_contacts_sql      = "SELECT COUNT(*) AS COUNT FROM newjs.CONTACTS WHERE SENDER = '$pid'";
                $send_contacts_res      = $mysqlObj->executeQuery($send_contacts_sql,$myDb) or die(mysql_error_js($myDb)); 
                $send_contacts_row      = $mysqlObj->fetchArray($send_contacts_res); 
		$send_contact           = $send_contacts_row["COUNT"];
		if ($orig_sub != '')
                {
                        $subscription           = explode(",",$orig_sub);
                        for($i=0; $i<count($subscription); $i++)
                        {
                                switch($subscription[$i])
                                {
                                        case 'F':$sub_array[$i]="Full Member";
                                                 break;
                                        case 'H':$sub_array[$i]="Horoscope";
                                                 break;
                                        case 'B':$sub_array[$i]="Profile Highlighting";
                                                break;
                                        case 'K':$sub_array[$i]="Kundali MatchMaker";
                                                 break;
                                        case 'D':$sub_array[$i]="e-Classifieds";
                                                 break;
                                        case 'A':$sub_array[$i]="Astro Compatibility";
                                                 break;
                                }
                        }
                }

                $smarty->assign("sub_arr",$sub_array);
                $smarty->assign("id",$id);
                $smarty->assign("pid",$pid);
                $smarty->assign("cid",$cid);
		$smarty->assign("user",$user);
                $smarty->assign("is_dob",$is_dob);
                $smarty->assign("is_gender",$is_gender);
                $smarty->assign("is_user",$is_user);
                $smarty->assign("is_religion",$is_religion);
                $smarty->assign("is_caste",$is_caste);
		$smarty->assign("is_country",$is_country);
                $smarty->assign("is_sub",$is_sub);
                $smarty->assign("is_ms",$is_ms);
		$smarty->assign("act",$act);
                $smarty->assign("recvd_contact",$recvd_contact);
                $smarty->assign("send_contact",$send_contact);
                $smarty->assign("orig_username",$orig_username);
                $smarty->assign("orig_gender",$orig_gender);
                $smarty->assign("orig_dtofbirth",$orig_dtofbirth);
                $smarty->assign("orig_religion",$orig_religion);
                $smarty->assign("orig_caste",$orig_caste);
                $smarty->assign("mod_religion",$orig_religion);
                $smarty->assign("orig_sub",$orig_sub);
                $smarty->assign("orig_ms",$orig_ms);
		$smarty->assign("orig_country",$orig_country);
		$smarty->assign("orig_city",$orig_city);
                $smarty->assign("requestby",$requestby);
                $smarty->assign("request_dt",$request_dt);
                $smarty->assign("change_details",$change_details);

		/*Code to get History of Request from this user.*/
		$sql_history = "Select ID , USER , REQUEST_DT, REQUEST_FOR, ADMIN_COMMENTS FROM jsadmin.PROFILE_CHANGE_REQUEST WHERE PROFILEID = $request_row[PROFILEID] and ID <> $request_row[ID]";
		$res_history =  mysql_query_decide($sql_history) or die("$sql_history".mysql_error_js());
		$request_history_cnt = mysql_num_rows($res_history);

		while($myrow_history= mysql_fetch_array($res_history)){

			$request_dt             = substr($myrow_history['REQUEST_DT'],0,10);
                        list($yy,$mm,$dd)       = explode("-",$request_dt);
                        $request_dt             = my_format_date($dd,$mm,$yy);

			$request_for            = $myrow_history['REQUEST_FOR'];
                        $request_for_temp       = explode(',',$request_for);

                        if (in_array('MS',$request_for_temp))
                                $request_for = "Marital Status Change";
                        if (in_array('DB',$request_for_temp))
                                $request_for = "DOB Change";
                        if (in_array('REL',$request_for_temp))
                                $request_for = "Religion Change";
                        if (in_array('G',$request_for_temp))
                                $request_for = "Gender Change";
                        if (in_array('U',$request_for_temp))
                                $request_for = "Username Change";
                        if (in_array('A',$request_for_temp))
                                $request_for = "Membership Activate";
			if (in_array('D',$request_for_temp))
                                $request_for = "Membership De-activate";
			if (in_array('C',$request_for_temp))
                                $request_for = "Country Change";


			$request_history[] = array(	"ID"=>$myrow_history['ID'],
							"REQUEST_FROM"=>$myrow_history['USER'],	
							"REQUEST_DT"=>$request_dt,	
							"REQUEST_FOR"=>$request_for,	
							"ADMIN_COMMENTS"=>substr($myrow_history['ADMIN_COMMENTS'],0,10)
						);	
		}

		$smarty->assign("request_history",$request_history);
		$smarty->assign("request_history_cnt",$request_history_cnt);
		$smarty->assign("record_id",$record_id);
		/*End of code to get History of Request from this user.*/

                $smarty->display("reply_request.htm");
	}
}
else
{
        $msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->assign("user",$user);
        $smarty->display("jsadmin_msg.tpl");
}

function getAge($newDob)
{
        $today=date("Y-m-d");
        $datearray=explode("-",$newDob);
        $todayArray=explode("-",$today);
        $years=($todayArray[0]-$datearray[0]);
        if(intval($todayArray[1]) < intval($datearray[1]))
                $years--;
        elseif(intval($todayArray[1]) == intval($datearray[1]) && intval($todayArray[2]) < intval($datearray[2]))
                $years--;
                                                                                                                             
        return $years;
}

?>
