<?php
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
              $cc='eshajain88@gmail.com';
              $to='sanyam1204@gmail.com';
              $msg1='shortlisted_profiles in jsadmin is being hit. We can wrap this to JProfileUpdateLib';
              $subject="shortlisted_profiles";
              $msg=$msg1.print_r($_SERVER,true);
              send_email($to,$msg,$subject,"",$cc);

include("connect.inc");
include("../crm/func_sky.php");
include("matches_display_results.inc");

include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
$mysqlObj=new Mysql;

for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
        $myDbName=getActiveServerName($activeServerId);
        $myDbarr[$myDbName]=$mysqlObj->connect("$myDbName");
}

$db=connect_db();

if(authenticated($cid))
{
	comp_info($profileid);
	$sql="SELECT BILLID FROM jsadmin.OFFLINE_BILLING WHERE PROFILEID='$profileid' ORDER BY ENTRY_DATE DESC LIMIT 1";
	$res=mysql_query_decide($sql) or logError($sql);
	$row=mysql_fetch_assoc($res);
	$billid=$row["BILLID"];
	$sql="SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
	$res=mysql_query_decide($sql) or logError($sql);
	$row=mysql_fetch_assoc($res);
	$oc_id=$row["USERNAME"];
	if($searchid)
	{
		$sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE USERNAME='$searchid'";
		$res=mysql_query_decide($sql) or logError($sql);
		if(mysql_num_rows($res)>0)
		{
			$row=mysql_fetch_assoc($res);
			$searchpid=$row["PROFILEID"];
			$sql="SELECT COUNT(*) AS CNT FROM jsadmin.OFFLINE_MATCHES WHERE MATCH_ID='$searchpid' AND PROFILEID='$profileid' AND STATUS= 'SL' AND CATEGORY!=''";
			$res=mysql_query_decide($sql) or logError($sql);
			$row=mysql_fetch_assoc($res);
			if($row["CNT"]>0)
			{
				assigndetails($profileid,$searchpid);			
				$viewprofile=$smarty->fetch("displayprofile.htm");
				$smarty->assign("viewprofile",$viewprofile);
				$smarty->assign("cid",$cid);
				$smarty->assign("profileid",$profileid);
				$smarty->assign("SEARCHED_PROFILE",1);
				$smarty->assign("POOL",1);
			}
			else
			{
				$smarty->assign("cid",$cid);
				$smarty->assign("profileid",$profileid);
				$smarty->assign("NOTINREJ",1);
			}	
		}
		else
		{
			$smarty->assign("cid",$cid);
			$smarty->assign("profileid",$profileid);	
			$smarty->assign("WRONGID",1);
		}		
		$PAGELEN=10;
		if(!$j)
			$j=0;

		$sql="SELECT COUNT(*) AS CNT FROM jsadmin.OFFLINE_MATCHES WHERE PROFILEID='$profileid' AND CATEGORY!='' AND STATUS= 'SL'";
		$res=mysql_query_decide($sql) or logError($sql);
		$row=mysql_fetch_assoc($res);
		$totalcount=$row["CNT"];
		$sql="SELECT MATCH_ID AS PROFILEID FROM jsadmin.OFFLINE_MATCHES WHERE PROFILEID='$profileid' AND CATEGORY!='' AND STATUS='SL' ORDER BY MOD_DATE DESC LIMIT $j,$PAGELEN";
		$res=mysql_query_decide($sql) or logError($sql);
		if(mysql_num_rows($res))
		{
			displayresults($res,$j,"/jsadmin/shortlisted_profiles.php",$totalcount,'',"1",'',"cid=$cid&profileid=$profileid",'','','','','',"admin",$profileid,$cid);
		}
		else
			$smarty->assign("NOREC",1);		  		

		$smarty->display("shortlisted_matches.htm");
	}
	elseif($profile)
        {
        	if($action)
        	{
        		$sql= "SELECT ACC_MADE,ACC_UPGRADED,ACC_ALLOWED FROM jsadmin.OFFLINE_BILLING WHERE PROFILEID= '$profileid' AND BILLID='$billid'";
			$res= mysql_query_decide($sql) or die(mysql_error_js());
			$row= mysql_fetch_array($res);
			$acc_made= $row['ACC_MADE'];
			$acc_allowed=$row["ACC_ALLOWED"];
			$acc_upgraded=$row["ACC_UPGRADED"];
			foreach($profile as $key=>$value)
			{
				$sql="SELECT STATUS,CATEGORY from jsadmin.OFFLINE_MATCHES WHERE PROFILEID='$profileid' AND MATCH_ID='$value'";
				$res=mysql_query_decide($sql) or logError($sql);
				$row= mysql_fetch_array($res);
				$stat= $row['STATUS'];
				$cat= $row['CATEGORY'];
				if($stat!='ACC')
				{
					$acc_made= $acc_made+1;					
					$sql="UPDATE jsadmin.OFFLINE_MATCHES SET STATUS='ACC',MOD_DATE=now() WHERE PROFILEID='$profileid' AND MATCH_ID='$value'";
					mysql_query_decide($sql) or logError($sql);
				
					//Unsetting the contact status field.
					$sql_up="delete from newjs.CONTACTS_STATUS where PROFILEID='$value'";
					mysql_query_decide($sql_up) or logError($sql_up);


					//$sql="UPDATE jsadmin.OFFLINE_NUDGE_LOG SET SENDER_STATUS='R',RECEIVER_STATUS='R' WHERE (SENDER='$value' AND RECEIVER='$profileid' AND TYPE='NACC') OR (SENDER='$profileid' AND RECEIVER='$value' AND TYPE IN('N','NOW')";
					//mysql_query_decide($sql) or logError($sql);
					if($cat==1 || $cat==2)
					{
						$sql="INSERT INTO jsadmin.OFFLINE_NUDGE_LOG(SENDER,RECEIVER,DATE,TYPE) VALUES('$profileid','$value',NOW(),'ACC')";
						mysql_query_decide($sql) or die(mysql_error_js());//logError($sql);
					}
					if(($cat == 4)||($cat == 5)||($cat == 6))
					{
						if($cat == 4)
						{
							$sql="SELECT OPERATOR from jsadmin.OFFLINE_ASSIGNED WHERE PROFILEID='$value' ";
							$res=mysql_query_decide($sql) or logError($sql);
							$row= mysql_fetch_array($res);
							$oname= $row['OPERATOR'];		
							$sql="SELECT EMAIL from jsadmin.PSWRDS WHERE USERNAME='$oname' ";
							$res=mysql_query_decide($sql) or logError($sql);
							$row= mysql_fetch_array($res);
							$email= $row['EMAIL'];	
							$sql="SELECT PHONE_MOB FROM newjs.JPROFILE WHERE PROFILEID='$value'";
							$res=mysql_query_decide($sql) or logError($sql);
							$row=mysql_fetch_assoc($res);
							$phone_mob=$row["PHONE_MOB"];	
						}
						else 
						{
							$sql="SELECT EMAIL,PHONE_MOB from newjs.JPROFILE WHERE PROFILEID='$value' ";
							$res=mysql_query_decide($sql) or logError($sql);
							$row= mysql_fetch_array($res);
							$email= $row['EMAIL'];
							$phone_mob=$row["PHONE_MOB"];
						}
						$subject="Contact detail made available to offline customer";
						$from="offlinematches@jeevansathi.com";
						$Cc="";
						$Bcc="";
						$msg="One of our offline customer ".$oc_id." was interested in your profile. In accordance with your privacy settings, we have made available to the offline customer, your contact details. You can expect a call/contact from the offline customer soon. We wish you luck with this contact and your partner search.";
              	                               send_mail($email,$Cc,$Bcc,$msg,$subject,$from);
						$msg="We have shared your contact detail with ".$oc_id.". Please login to Jeevansathi to view the profile. This is in accordance with your privacy option.";
						//send_sms($msg,'',$phone_mob,$value,'',"Y");

					} 
					if($cat == 2)
					{
						$sql= "UPDATE newjs.CONTACTS SET TYPE= 'A' WHERE RECEIVER= '$profileid' AND TYPE='I' AND SENDER='$value'";
						$myDbName1=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
						$myDbName2=getProfileDatabaseConnectionName($value,'',$mysqlObj);
						$myDb1=$myDbarr[$myDbName1];
						$myDb2=$myDbarr[$myDbName2];
						if($myDbName1==$myDbName2)
						{
							$mysqlObj->executeQuery($sql,$myDb1) or die(mysql_error_js($myDb1));
						}
						else
						{
							$mysqlObj->executeQuery($sql,$myDb1) or die(mysql_error_js($myDb1));

							$mysqlObj->executeQuery($sql,$myDb2) or die(mysql_error_js($myDb2));

						}
						
					}         
				}
			}
			$acc_remain=$acc_allowed+$acc_upgraded-$acc_made;
			$sql1= "UPDATE jsadmin.OFFLINE_BILLING SET ACC_MADE='$acc_made'";
			if(!$acc_remain)
			$sql1.=",ACTIVE='N'";
			$sql1.=" WHERE PROFILEID= '$profileid' AND BILLID='$billid'";
			 mysql_query_decide($sql1) or die(mysql_error_js());
			if($acc_remain)
			{
				if(count($profile)>1)
					$smarty->assign("ACCEPTED","N");	
				else
					$smarty->assign("ACCEPTED",1);
				$smarty->assign("acc_remain",$acc_remain);
			}
			else
			{
				$sql="UPDATE newjs.JPROFILE SET ACTIVATED='D',activatedKey=0 WHERE PROFILEID='$profileid'";
				mysql_query_decide($sql) or logError($sql);
				comp_info($profileid);
			}
        	}
        	else
        	{
        		foreach($profile as $key=>$value)
				{
					$sql="SELECT CATEGORY FROM jsadmin.OFFLINE_MATCHES WHERE PROFILEID='$profileid' AND MATCH_ID='$value'";
					$res=mysql_query_decide($sql) or logError($sql);
					$row=mysql_fetch_assoc($res);
					$cat=$row["CATEGORY"];	
					$sql="UPDATE jsadmin.OFFLINE_MATCHES SET STATUS='REJ',MOD_DATE=now() WHERE PROFILEID='$profileid' AND MATCH_ID='$value'";			
					mysql_query_decide($sql) or logError($sql);

					$sql_up="delete from newjs.CONTACTS_STATUS where PROFILEID='$value'";
                                        mysql_query_decide($sql_up) or logError($sql_up);

					//$sql="UPDATE jsadmin.OFFLINE_NUDGE_LOG SET SENDER_STATUS='R',RECEIVER_STATUS='R' WHERE SENDER='$value' AND RECEIVER='$profileid' AND TYPE IN('N','NACC')";
					//mysql_query_decide($sql) or logError($sql);
					if($cat==1 || $cat==2)
					{
						$sql="INSERT INTO jsadmin.OFFLINE_NUDGE_LOG(SENDER,RECEIVER,DATE,TYPE) VALUES('$profileid','$value',NOW(),'REJ')";
						mysql_query_decide($sql) or logError($sql);
					}
				
				}
				if(count($profile)>1)
					$smarty->assign("REJECTED","N");	
				else
					$smarty->assign("REJECTED",1);
        	}
        	$PAGELEN=10;
		if(!$j)
		$j=0;
		$sql="SELECT COUNT(*) AS CNT FROM jsadmin.OFFLINE_MATCHES WHERE PROFILEID='$profileid' AND CATEGORY!='' AND STATUS= 'SL'";
		$res=mysql_query_decide($sql) or logError($sql);
		$row=mysql_fetch_assoc($res);
		$totalcount=$row["CNT"];
		$sql="SELECT MATCH_ID AS PROFILEID FROM jsadmin.OFFLINE_MATCHES WHERE PROFILEID='$profileid' AND CATEGORY!='' AND STATUS='SL' ORDER BY MOD_DATE DESC LIMIT $j,$PAGELEN";
		$res=mysql_query_decide($sql) or logError($sql);
		if(mysql_num_rows($res))
		{
			displayresults($res,$j,"/jsadmin/shortlisted_profiles.php",$totalcount,'',"1",'',"cid=$cid&profileid=$profileid",'','','','','',"admin",$profileid,$cid);
		}
		else
		$smarty->assign("NOREC",1);
		$smarty->assign("cid",$cid);
	        $smarty->assign("profileid",$profileid);
	        $smarty->display("shortlisted_matches.htm");	
        }      
	else
	{
	 	if($accid)
	 	{
			$sql="SELECT STATUS,CATEGORY FROM jsadmin.OFFLINE_MATCHES WHERE PROFILEID= '$profileid' AND MATCH_ID='$accid'";	
			$res= mysql_query_decide($sql) or die(mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$stat= $row['STATUS'];
				$cat=$row["CATEGORY"];
			}
			
			if($stat=='SL')
			{
				if(($flagr == '1'))
				{
					$sql="UPDATE jsadmin.OFFLINE_MATCHES SET STATUS= 'REJ',MOD_DATE=now() WHERE PROFILEID= '$profileid' AND MATCH_ID= '$accid'";
					mysql_query_decide($sql) or die(mysql_error_js());
					$sql_up="delete from newjs.CONTACTS_STATUS where PROFILEID='$accid'";
                                        mysql_query_decide($sql_up) or logError($sql_up);
					//$sql="UPDATE jsadmin.OFFLINE_NUDGE_LOG SET SENDER_STATUS='R',RECEIVER_STATUS='R' WHERE (SENDER='$accid' AND RECEIVER='$profileid' AND TYPE='NACC') OR (SENDER='$profileid' AND RECEIVER='$accid' AND TYPE IN('N','NNOW')";
					//mysql_query_decide($sql) or logError($sql);
					if($cat==1 || $cat==2)
					{
						$sql="INSERT INTO jsadmin.OFFLINE_NUDGE_LOG(SENDER,RECEIVER,DATE,TYPE) VALUES('$profileid','$accid',NOW(),'REJ')";
						mysql_query_decide($sql) or logError($sql);
					}
					
					if(count($profile)>1)
						$smarty->assign("REJECTED","N");	
					else
						$smarty->assign("REJECTED",1);
			
				}
				else
				{
					$sql="UPDATE jsadmin.OFFLINE_MATCHES SET STATUS= 'ACC',MOD_DATE=now() WHERE PROFILEID= '$profileid' AND MATCH_ID= '$accid'";
					$res= mysql_query_decide($sql) or die(mysql_error_js());
					$sql_up="delete from newjs.CONTACTS_STATUS where PROFILEID='$accid'";
                    mysql_query_decide($sql_up) or logError($sql_up);
					//$sql="UPDATE jsadmin.OFFLINE_NUDGE_LOG SET SENDER_STATUS='R',RECEIVER_STATUS='R' WHERE (SENDER='$accid' AND RECEIVER='$profileid' AND TYPE='NACC') OR (SENDER='$profileid' AND RECEIVER='$accid' AND TYPE IN('N','NNOW')";
					//mysql_query_decide($sql) or logError($sql);
					if($cat==1 || $cat==2)
					{
						$sql="INSERT INTO jsadmin.OFFLINE_NUDGE_LOG(SENDER,RECEIVER,DATE,TYPE) VALUES('$profileid','$accid',NOW(),'ACC')";
						mysql_query_decide($sql) or logError($sql);
					}
					if(($cat == 4)||($cat == 5)||($cat == 6))
					{
						if($cat == 4)
						{
							$sql="SELECT OPERATOR from jsadmin.OFFLINE_ASSIGNED WHERE PROFILEID='$accid' ";
							$res=mysql_query_decide($sql) or logError($sql);
							$row= mysql_fetch_array($res);
							$oname= $row['OPERATOR'];		
							$sql="SELECT EMAIL from jsadmin.PSWRDS WHERE USERNAME='$oname' ";
							$res=mysql_query_decide($sql) or logError($sql);
							$row= mysql_fetch_array($res);
							$email= $row['EMAIL'];		
							$sql="SELECT PHONE_MOB FROM newjs.JPROFILE WHERE PROFILEID='$accid'";
                                                        $res=mysql_query_decide($sql) or logError($sql);
                                                        $row=mysql_fetch_assoc($res);
                                                        $phone_mob=$row["PHONE_MOB"];
						}
						else 
						{
							$sql="SELECT PHONE_MOB,EMAIL from newjs.JPROFILE WHERE PROFILEID='$accid' ";
							$res=mysql_query_decide($sql) or logError($sql);
							$row= mysql_fetch_array($res);
							$email= $row['EMAIL'];
							$phone_mob=$row["PHONE_MOB"];							
						}
						//////////////////////						
						$subject="Contact detail made available to offline customer";
						$from="offlinematches@jeevansathi.com";
						$Cc="";
						$Bcc="";		              
						$msg="One of our offline customer ".$oc_id." was interested in your profile.In accordance with your privacy settings, we have made available to the offline customer, your contact details. You can expect a call/contact from the offline customer soon. We wish you luck with this contact and your partner search.";		                	                               
						send_mail($email,$Cc,$Bcc,$msg,$subject,$from);
                                                $msg="We have shared your contact detail with ".$oc_id.". Please login to Jeevansathi to view the profile. This is in accordance with your privacy option.";
                                                //send_sms($msg,'',$phone_mob,$accid,"","Y");

					}
					if($cat == 2)
					{
						$sql= "UPDATE newjs.CONTACTS SET TYPE= 'A' WHERE RECEIVER= '$profileid' AND TYPE='I' AND SENDER='$accid'";
						$myDbName1=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
                                                $myDbName2=getProfileDatabaseConnectionName($accid,'',$mysqlObj);
                                                $myDb1=$myDbarr[$myDbName1];
                                                $myDb2=$myDbarr[$myDbName2];
                                                if($myDbName1==$myDbName2)
                                                {
                                                        $mysqlObj->executeQuery($sql,$myDb1) or die(mysql_error_js($myDb1));
                                                }
                                                else
                                                {
                                                        $mysqlObj->executeQuery($sql,$myDb1) or die(mysql_error_js($myDb1));

                                                        $mysqlObj->executeQuery($sql,$myDb2) or die(mysql_error_js($myDb2));

                                                }
						
					}          
					$sql= "SELECT ACC_MADE,ACC_ALLOWED,ACC_UPGRADED FROM jsadmin.OFFLINE_BILLING WHERE PROFILEID= '$profileid' AND BILLID='$billid'";
					$res= mysql_query_decide($sql) or die(mysql_error_js());
					while($row= mysql_fetch_array($res))
					{
						$acc_made= $row['ACC_MADE'];
						$acc_allowed= $row['ACC_ALLOWED'];
						$acc_upgraded=$row["ACC_UPGRADED"];
					}
					$acc_made= $acc_made+1;
					$acc_remain=$acc_allowed+$acc_upgraded-$acc_made;
					$sql1= "UPDATE jsadmin.OFFLINE_BILLING SET ACC_MADE='$acc_made'";
					if($acc_remain<=0)
					$sql1.=",ACTIVE='N'";
					$sql1.=" WHERE PROFILEID= '$profileid' AND BILLID='$billid'";
					mysql_query_decide($sql1) or die(mysql_error_js());
					if($acc_remain<=0)
					{
						$sql1= "UPDATE newjs.JPROFILE SET ACTIVATED= 'D',activatedKey=0 WHERE PROFILEID= '$profileid'";
						$res= mysql_query_decide($sql1) or die(mysql_error_js());
						comp_info($profileid);
					}
					else
					{
						if(count($profile)>1)
							$smarty->assign("ACCEPTED","N");	
						else
							$smarty->assign("ACCEPTED",1);
						$smarty->assign("acc_remain",$acc_remain);
					}
				}
			}	
			else
			{
				if($stat=="ACC")
				$nmsg= "Selected Profile is already accepted!";
				else
				$nmsg="Selected Profile is already rejected!";
			}
			$smarty->assign("nmsg",$nmsg);
	
		}
		$PAGELEN=10;
		if(!$j)
		$j=0;
		$sql="SELECT COUNT(*) AS CNT FROM jsadmin.OFFLINE_MATCHES WHERE PROFILEID='$profileid' AND CATEGORY!='' AND STATUS= 'SL'";
		$res=mysql_query_decide($sql) or logError($sql);
		$row=mysql_fetch_assoc($res);
		$totalcount=$row["CNT"];
		$sql="SELECT MATCH_ID AS PROFILEID FROM jsadmin.OFFLINE_MATCHES WHERE PROFILEID='$profileid' AND CATEGORY!='' AND STATUS='SL' ORDER BY MOD_DATE DESC LIMIT $j,$PAGELEN";
		$res=mysql_query_decide($sql) or logError($sql);

		if(mysql_num_rows($res))
		{
		   displayresults($res,$j,"/jsadmin/shortlisted_profiles.php",$totalcount,'',"1",'',"cid=$cid&profileid=$profileid",'','','','','',"admin",$profileid,$cid);
		}
		else
		$smarty->assign("NOREC",1);
		$smarty->assign("accid",$accid);
		//$smarty->assign("flg",$flg);	
		$smarty->assign("cid",$cid);
		$smarty->assign("profileid",$profileid);
		$smarty->display("shortlisted_matches.htm");
	}
}
else
{
        $msg="Your session has been timed out<br><br>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}

?>
	
