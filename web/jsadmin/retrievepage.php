<?php

include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/flag.php");
include("../sugarcrm/custom/crons/housekeepingConfig.php");
include("../sugarcrm/include/utils/systemProcessUsersConfig.php");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
global $partitionsArray;
global $process_user_mapping;

$processUserId=$process_user_mapping["retrieve_profile"];
if(!$processUserId)
        $processUserId=1;

$updateTime=date("Y-m-d H:i:s");

if(authenticated($cid))
{

	$sum=setAllFlags();
	if($Retrieve)
	{ 
		/*code added by Aman sharma for adding reason and comments*/
		if($retflag!='Y')
		{
			 $smarty->assign("retflag",'Y');
			 $smarty->assign("user",$user);
			 $smarty->assign("cid",$cid);
			 $smarty->assign("Profileid",$Profileid);
			 $smarty->display("retrieve_page.tpl");
		}
		//End of added code
		else
		{		
                /*$c=0;
		$count_rec = 0; // count of records that cant be retrieved
                foreach( $_POST as $key => $value )
                {
                        //if( substr($key, 0, 2) == "cb" )
                       // {
                                $c=$c+1;
                                $proid[]=ltrim($key, "cb");
                       // }
                }
                if(count($proid)>0)
		{
			$pid="'".implode($proid,"','")."'";*/
			$sql="SELECT PROFILEID,SCREENING,ACTIVATED,PREACTIVATED,INCOMPLETE,USERNAME,YOURINFO from newjs.JPROFILE where PROFILEID=$Profileid";
			$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			while($myrow=mysql_fetch_array($result))
			{
				$profileid=$myrow['PROFILEID'];
				$screening=$myrow['SCREENING'];
			/*	$sql_pid = "Select * from jsadmin.DELETED_PROFILES where PROFILEID = $profileid ORDER BY ID desc";    // check if profile has been permanently deleted
				$result_id = mysql_query_decide($sql_pid) or die(mysql_error_js());
				if(mysql_num_rows($result_id))
				{
					$row_id = mysql_fetch_array($result_id);
					$msg = "ProfileId <font color=blue>$row_id[USERNAME]</font> cannot be retrieved.<br>";
					$msg .= "Reason for deletion : $row_id[REASON]<br>";
					$msg .= "Comments : $row_id[COMMENTS]<br>";	
					$msg .= "Deleted by : $row_id[USER] on $row_id[TIME]<br>";	
						$ret_scr="y";
					$count_rec = $count_rec + 1  ;
				}
				else
				{*/
					//Update archived data if any...
					$date=date("Y-m-d");
					$sqljs="update newjs.JSARCHIVED set STATUS='N',ACT_DATE='$date' where PROFILEID='$profileid' and STATUS='Y'";
					mysql_query_decide($sqljs) or die(mysql_error_js());
					$arrFields = array();
					$arrFields['ACTIVATED']=$myrow['PREACTIVATED'];
					if(mysql_affected_rows_js()){
						$arrFields['MOB_STATUS']='N';
						$arrFields['LANDL_STATUS']='N';
						$arrFields['PHONE_FLAG']='';
						$jsarch_user=", MOB_STATUS='N',LANDL_STATUS='N',PHONE_FLAG='' ";
					}
					$jprofileUpdateObj = JProfileUpdateLib::getInstance(); 
					
					if($screening==$sum)
					{
						$arrFields['activatedKey']=1;
						$arrFields['JSARCHIVED']=0;
						$arrFields['ACTIVATE_ON']=date("Y-m-d H:i");
						$exrtaWhereCond = "";
						$jprofileUpdateObj->editJPROFILE($arrFields,$profileid,"PROFILEID",$exrtaWhereCond);
						//$sql="UPDATE newjs.JPROFILE set ACTIVATED=PREACTIVATED,activatedKey=1,JSARCHIVED=0, ACTIVATE_ON='".date("Y-m-d H:i")."'$jsarch_user  where PROFILEID='$profileid'";
						//mysql_query_decide($sql) or die(mysql_error_js());
					}
					else
					{
						$arrFields['activatedKey']=1;
						$arrFields['JSARCHIVED']=0;
						$arrFields['ACTIVATE_ON']='0';
						$exrtaWhereCond = "";
						$jprofileUpdateObj->editJPROFILE($arrFields,$profileid,"PROFILEID",$exrtaWhereCond);
						//$sql1="UPDATE newjs.JPROFILE set ACTIVATED=PREACTIVATED,activatedKey=1,JSARCHIVED=0,ACTIVATE_ON='0'$jsarch_user where PROFILEID='$profileid'"; 
						//mysql_query_decide($sql1) or die(mysql_error_js());
					}

					//added by sriram to prevent the query on CONTACTS table being run several times on page reload.
					if($myrow['ACTIVATED']=='D')
					{
                                                if($myrow['PREACTIVATED'] == 'Y' && strlen($myrow['YOURINFO'])<100){
                                                    $activated_without_yourInfoObj = new JSADMIN_ACTIVATED_WITHOUT_YOURINFO();
                                                    $activated_without_yourInfoObj->insert($myrow['PROFILEID']);
                                                }
						$producerObj=new Producer();
                       	if($producerObj->getRabbitMQServerConnected())
                       	{
                           $sendMailData = array('process' =>'DELETE_RETRIEVE','data'=>array('type' => 'RETRIEVE','body'=>array('profileId'=>$profileid)), 'redeliveryCount'=>0 );
                           $producerObj->sendMessage($sendMailData);
                       	}
                       	else
                       	{
                           $path = $_SERVER['DOCUMENT_ROOT']."/profile/retrieveprofile_bg.php $profileid > /dev/null ";
                           $cmd_1 = JsConstants::$php5path." -q ".$path;
                       	}
						$path_2 = $_SERVER['DOCUMENT_ROOT']."/profile/send_mail_sms.php ".$profileid." > /dev/null ";
                                                $cmd_2= "cd ../profile/ ; ".JsConstants::$php5path." -q ".$path_2;
						if($jsarch_user)
						{
							if($cmd_1!="")
								$cmd="$cmd_1 ; $cmd_2";
							else
								$cmd="$cmd_2";
						}
						else if($cmd_1!="")
						{
							$cmd="$cmd_1 &";
						}

						if($cmd!="")
						{
							passthru($cmd);
						}

					}
					//end of - added by sriram to prevent the query on CONTACTS table being run several times on page reload.

					//Added by Sadaf to change status of lead connected to profile to registered/registered incomplete
					if($myrow["INCOMPLETE"]=='Y')
					{
						$newStatus='24';
						$newDisposition='23';
					}
					else
					{
						$newStatus='26';
						$newDisposition='30';
					}
					$sql_log="UPDATE sugarcrm.leads,sugarcrm.leads_cstm SET status=$newStatus,disposition_c=$newDisposition,modified_user_id='$processUserId',date_modified='$updateTime' WHERE id=id_c AND jsprofileid_c=\"$myrow[USERNAME]\" AND deleted!='1'";
					mysql_query_decide($sql_log) or die("error while updating leads info :  $sql_log  ".mysql_error_js());
					if(is_array($partitionsArray))
					{
						foreach($partitionsArray as $partition=>$partitionArray)
						{
							$partitionLeadsCstm="sugarcrm_housekeeping.".$partition."_leads_cstm";
							$partitionLeads="sugarcrm_housekeeping.".$partition."_leads";
							$sql_log="UPDATE $partitionLeads,$partitionLeadsCstm SET status=$newStatus,disposition_c=$newDisposition,modified_user_id='$processUserId',date_modified='$updateTime' WHERE id=id_c AND jsprofileid_c=\"$myrow[USERNAME]\" AND deleted!='1'";
		                                        mysql_query_decide($sql_log) or die("error while updating leads info :  $sql_log  ".mysql_error_js());
						}
					}
					//end of changes added by Sadaf

					$user=getname($cid);				
					$tm = date("Y-M-d",time());
					 $sql_log = "Insert into jsadmin.DELETED_PROFILES(PROFILEID,REASON,COMMENTS,RETRIEVED_BY,TIME) VALUES('$profileid','".addslashes(stripslashes($reason))."','".addslashes(stripslashes($comments))."','$user','$tm') ";
                                        mysql_query_decide($sql_log) or die("$sql_log".mysql_error_js());



	/* Changes made on 26 May,2005 end here*/
				}

		/*	if($c==0)
				$msg = "Please check the records to retrieve<br><br>";
			else
			{
				$num_rec_retrieved = $c - $count_rec ;*/	
				$msg .= "<br>You have successfully retrieved $c records<br><br>";
			//}
													 
			$msg .= "<a href=\"retrievepage.php?user=$user&cid=$cid\">";
													 

			$msg .= "Continue &gt;&gt;</a>";
			$smarty->assign("ret_scr",$ret_scr);
			$smarty->assign("name",$user);
			$smarty->assign("cid",$cid);
			$smarty->assign("MSG",$msg);
			$smarty->display("jsadmin_msg.tpl");
		}
	}
	elseif($CMDSearch)
	{
		//If condition added by Sadaf to handle condition of no username / email
		if(trim($username) || trim($email))
		{
			$sql="SELECT PROFILEID, USERNAME, EMAIL, MOD_DT, ACTIVATE_ON, SUBSCRIPTION from newjs.JPROFILE where ACTIVATED='D' AND ";
			if($year1!="" && $month1!="" && $day1!="" && $year2!="" && $month2!="" && $day2!="")
			{
				$date1=$year1."-".$month1."-".$day1;
				$date2=$year2."-".$month2."-".$day2;
				$sql_condition[]="ENTRY_DT between '$date1' and '$date2'";
			}
			//Query optimized by Sadaf
			$sqlmain=$sql;
			if(trim($username)!="" && trim($email)!="")
			{
				//$sql_condition[]="USERNAME='$username'";
				$sql=$sqlmain." USERNAME='$username' UNION ".$sqlmain." EMAIL='$email'";
				
			}
			elseif(trim($email)!="")
			{
				//$sql_condition[]="EMAIL='$email'";
				$sql=$sqlmain." EMAIL='$email'";
			}
			elseif(trim($username)!="")
			{
				$sql=$sqlmain." USERNAME='$username'";
			}

			/*if(count($sql_condition)>0)
			{
				$sql_condition_string=implode($sql_condition," OR ");
				$sql.="(".$sql_condition_string.")";
			}*/
			$orderby=" ORDER BY ACTIVATE_ON DESC";
			$sql.=$orderby;
			$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$i=1;
			while($myrow=mysql_fetch_array($result))
			{
				$Username=$myrow['USERNAME'];
				$Email=$myrow['EMAIL'];
				//$Del_dt=$myrow['ACTIVATE_ON'];
				$Del_dt=$myrow['MOD_DT'];
				$Profileid=$myrow['PROFILEID'];
				$profilechecksum=md5($Profileid) . "i" . $Profileid;
				if($myrow["SUBSCRIPTION"]=="")
					$color="fieldsnew";
				else
					$color="fieldsnewgreen";

	
				$sql_del="select USER,TIME from jsadmin.DELETED_PROFILES where PROFILEID=$Profileid order by ID DESC LIMIT 1";
				$result_del=mysql_query_decide($sql_del) or die("$sql_del".mysql_error_js());
				if(mysql_num_rows($result_del))
				{
					$myrow_del=mysql_fetch_array($result_del);
					if($myrow_del["USER"]!='')
					{
						$del_scr="y";
					}
					else
					{
						$del_scr="N";
					}
					$Del_dt=$myrow_del['TIME'];
				 }
				else
				{
					$del_scr="N";
				}
				//Check if profile is archived
				$prof_archived=0;
				$sqljs="select DEACTIVE_DATE,PROFILEID from newjs.JSARCHIVED where PROFILEID='$Profileid' and STATUS ='Y'";
                                $resjs=mysql_query_decide($sqljs) or die("$sql_del".mysql_error_js());
                                if($jsarch=mysql_fetch_array($resjs))
                                {
                                        $prof_archived=1;
					$Del_dt=$jsarch['DEACTIVE_DATE'];
                                }
		
							 
				$values[] = array("Sno"=>$i,
					  "Profileid"=>$Profileid,
					  "Profilechecksum"=>$profilechecksum,
					  "Username"=>$Username,
					  "Email" => $Email,
					  "Del_dt" => $Del_dt,
					  "bandcolor"=>$color,
					   "deletedby"=>$myrow_del['USER'],
					  "del_scr"=>$del_scr,
					  "prof_arch"=>$prof_archived
					 );
				$i++;
											 
			}
			
			$smarty->assign("del_scr",$del_scr);
			$smarty->assign("SHOWSEARCH","Y");
			$smarty->assign("ROW",$values);
		}
		else
			$smarty->assign("NOENTRY",1);
		$smarty->assign("user",$user);
		$smarty->assign("cid",$cid);
		$smarty->display("retrieve_page.tpl");
	}
	else
	{
		$smarty->assign("ROW",$values);
		$smarty->assign("user",$user);
		$smarty->assign("cid",$cid);
		$smarty->display("retrieve_page.tpl");
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

// function to retrive the contacts made and received by this profile
/*function retrievecontacts($profileid)
{
	$sql="select CONTACTID from newjs.DELETED_PROFILE_CONTACTS where SENDER='$profileid'";
	$result=mysql_query_decide($sql);
	
	while($myrow=mysql_fetch_array($result))
	{
		$contactid=$myrow["CONTACTID"];
		
		$sql="insert ignore into newjs.CONTACTS select * from newjs.DELETED_PROFILE_CONTACTS where CONTACTID='$contactid'";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		
		if($res)
		{
			$sql="delete from newjs.DELETED_PROFILE_CONTACTS where CONTACTID='$contactid'";
			mysql_query_decide($sql) or die(mysql_error_js());
		}
	}
	
	mysql_free_result($result);
	
	$sql="select CONTACTID from newjs.DELETED_PROFILE_CONTACTS where RECEIVER='$profileid'";
	$result=mysql_query_decide($sql) or die(mysql_error_js());
	
	while($myrow=mysql_fetch_array($result))
	{
		$contactid=$myrow["CONTACTID"];
		
		$sql="insert ignore into newjs.CONTACTS select * from newjs.DELETED_PROFILE_CONTACTS where CONTACTID='$contactid'";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		
		if($res)
		{
			$sql="delete from newjs.DELETED_PROFILE_CONTACTS where CONTACTID='$contactid'";
			mysql_query_decide($sql) or die(mysql_error_js());
		}
	}
	
	mysql_free_result($result);
}*/
	
?>
