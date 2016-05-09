<?php
/**
*       Filename        :       inbound_walkin.php
*       Created By      :       Abhinav
**/
/**
*       Included        :       connect.inc
*       Description     :       contains functions related to database connection and login authentication
**/
include("connect.inc");
include("mainmenunew.php");
include("viewprofilenew.php");
include(JsConstants::$docRoot."/commonFiles/flag.php");
include("history.php");

if(authenticated($cid))
{
	$smarty->assign("MODE",$mode);
        $name= getname($cid);
	if($history || $submit)
	{
		if($history)
		{
			if(trim($USERNAME)== '')
			{
				$smarty->assign("check_username","Y");
			}
			else
			{
				$USERNAME = addslashes(stripslashes($USERNAME));
				$sql = "SELECT PROFILEID FROM newjs.JPROFILE WHERE USERNAME='$USERNAME'";
				$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				$wrongUsername=mysql_num_rows($result);
				if($wrongUsername == "0")
				{
					$smarty->assign("wrong_username","Y"); 
				}
				else
				{
					$myrow=mysql_fetch_assoc($result);
                         		$profileid=$myrow['PROFILEID'];				

			        $privilage = explode("+",getprivilage($cid));
        			if(in_array("SLHD",$privilage) || in_array("SLSUP",$privilage) || in_array("P",$privilage) || in_array("MG",$privilage) || in_array("TRNG",$privilage))
                			$limit =0;
                                else
                                {
                                        $limitCount =getHistoryCount($profileid);
                                        if($limitCount>=5)
                                                $limit =$limitCount;
                                        else
                                                $limit =5;
                                }

				$sql="SELECT EMAIL,PHONE_RES, PHONE_MOB from newjs.JPROFILE where USERNAME='$USERNAME'";
				$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				if(mysql_num_rows($result)>0)
				{
					$myrow=mysql_fetch_array($result);
					$user_values=gethistory($USERNAME,$limit); 
				}
				else
					$smarty->assign("CHECK_USERNAME","Y");
				
				$smarty->assign("EMAIL",$myrow['EMAIL']);
				$smarty->assign("RES_NO",$myrow['PHONE_RES']);
				$smarty->assign("MOB_NO",$myrow['PHONE_MOB']);
				$smarty->assign("PROFILEID",$myrow['PROFILEID']);
	//			$smarty->assign("ROW",$values);
				$smarty->assign("ROW",$user_values);
				$pmsg=viewprofile($USERNAME,"internal");
				$msg=profileview($profileid,$checksum);
				$smarty->assign("msg",$msg);
				$smarty->assign("pmsg",$pmsg);
				$smarty->assign("USERNAME",stripslashes($USERNAME));
				}
			}
			$smarty->assign("cid",$cid);
			$smarty->display("inbound_walkin.htm");
		}
		elseif($submit)
		{			
			$is_error=0;
			
			if(!$USERNAME)
			{
				$smarty->assign("check_username","Y");
				$is_error++;
			}
			else
			{
				$sql = "SELECT PROFILEID,CITY_RES FROM newjs.JPROFILE WHERE USERNAME='".addslashes(stripslashes($USERNAME))."'";
				$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				if($myrow=mysql_fetch_array($result))
					  $profileid=$myrow['PROFILEID'];
				else
				{
					$is_error++;
					$smarty->assign("wrong_username","Y");
				}
				$sql="SELECT NAME AS CITY FROM incentive.LOCATION WHERE VALUE='".$myrow['CITY_RES']."'";
                                $res1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                                $row1=mysql_fetch_assoc($res1);
				$sql="SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME='$name'";
                                $res2=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                                $row2=mysql_fetch_assoc($res2);
                                $center=strtoupper($row2['CENTER']);

                                if($row1['CITY']!=$center && $center!='NOIDA')
                                {
					$msg="Profile Out Of Region";
					$smarty->assign('MSG',$msg);
					$smarty->display("crm_msg.tpl");	
					die;
				}

			}

			if(!$EMAIL || checkemail($EMAIL))
			{
				$smarty->assign("check_email","Y");
				$is_error++;
			}
			if($follow=="F" && $follow_time==0)
			{
				$smarty->assign("check_status","Y");
				$is_error++;
			}
			if($is_error>=1)
			{	
				$smarty->assign("USERNAME",stripslashes($USERNAME));
				$smarty->assign("EMAIL",$EMAIL);
				$smarty->assign("RES_NO",$RES_NO);
				$smarty->assign("MOB_NO",$MOB_NO);
				$smarty->assign("follow",$follow);
				$smarty->assign("follow_time",$follow_time);
				$smarty->assign("chour",$chour);
				$smarty->assign("cmin",$cmin);
				$smarty->assign("MODE",$mode);
				$smarty->assign("STATUS",$STATUS);
				$smarty->assign("ALTERNATE_NO",$ALTERNATE_NO);
				$smarty->assign("WILL_PAY",$WILL_PAY);		
				$smarty->assign("COMMENTS",$COMMENTS);	
				$checksum=md5($profileid)."i".$profileid;
				$smarty->assign("cid",$cid);
				$smarty->display("inbound_walkin.htm");
			}
			else
			{	
				$convincearr=array($chour,$cmin,"00");
				$convince=implode(":",$convincearr);
				if($follow!='F')
				{
					$sql2 = "INSERT INTO incentive.CLAIM (PROFILEID,USERNAME,CONVINCE_TIME,ENTRY_TIME,ENTRYBY,STATUS,MODE,RES_NO,MOB_NO,EMAIL,WILL_PAY) VALUES ('$profileid','".addslashes($USERNAME)."',now(),now(),'$name','$follow','$mode','".addslashes($RES_NO)."','".addslashes($MOB_NO)."','".addslashes($EMAIL)."','$WILL_PAY')";
					mysql_query_decide($sql2) or die("$sql2".mysql_error_js());
				}
				$privilage=getprivilage($cid);
				$priv=explode("+",$privilage);
				if(in_array('IUO',$priv))
				{
					$alloted_to='';						
					$sql_c="SELECT ALLOTED_TO FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid' AND MODE='O'";
					$res_c=mysql_query_decide($sql_c) or die("$sql_c".mysql_error_js());
					if($row_c=mysql_fetch_array($res_c))
					{
						$alloted_to=$row_c['ALLOTED_TO'];
						if($alloted_to==$name)// || $alloted_to=='')
						{
							$sql3="UPDATE incentive.MAIN_ADMIN SET MODE='O',STATUS='F',CLAIM_TIME=if(CONVINCE_TIME='0000-00-00 00:00:00',NOW(),if(CONVINCE_TIME<CURDATE(),CONVINCE_TIME,CLAIM_TIME)),CONVINCE_TIME=NOW(),FOLLOWUP_TIME='$follow_time' WHERE PROFILEID='$profileid' AND ALLOTED_TO='$name'";
						}
						else
						{
							$sqlj="SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
							$resj=mysql_query_decide($sqlj) or die("$sqlj".mysql_error_js());
							$rowj=mysql_fetch_array($resj);
							$username=$rowj['USERNAME'];
							if($follow=='F')
							{
								$sqli="INSERT INTO incentive.CLAIM(PROFILEID,USERNAME,CONVINCE_TIME,ENTRY_TIME,ENTRYBY,MODE,RES_NO,MOB_NO,EMAIL,WILL_PAY) VALUES('$profileid','".addslashes($username)."',now(),now(),'$name','$mode','".addslashes($RES_NO)."','".addslashes($MOB_NO)."','".addslashes($EMAIL)."','$WILL_PAY')";
								mysql_query_decide($sqli) or die("$sqli".mysql_error_js());
							}

							$sql3="UPDATE incentive.MAIN_ADMIN SET STATUS='F',FOLLOWUP_TIME='$follow_time' WHERE PROFILEID='$profileid' AND ALLOTED_TO='$alloted_to'";
						}
						mysql_query_decide($sql3) or die("$sql3".mysql_error_js());         
					}
					else
					{
						$sqli = "INSERT INTO incentive.MAIN_ADMIN (PROFILEID,CONVINCE_TIME,ALLOT_TIME,ALLOTED_TO,STATUS,ALTERNATE_NO,FOLLOWUP_TIME,MODE,RES_NO,MOB_NO,EMAIL,WILL_PAY) VALUES('$profileid',now(),now(),'$name','F','".addslashes($ALTERNATE_NO)."','$follow_time','O','".addslashes($RES_NO)."','".addslashes($MOB_NO)."','".addslashes($EMAIL)."','$WILL_PAY')";
						mysql_query_decide($sqli) or die("$sqli".mysql_error_js());
					}
				}
				else
				{
					$alloted_to='';
                                        $sql_c="SELECT ALLOTED_TO FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid' AND MODE='O'";
                                        $res_c=mysql_query_decide($sql_c) or die("$sql_c".mysql_error_js());
                                        if($row_c=mysql_fetch_array($res_c))
					{
                                                $alloted_to=$row_c['ALLOTED_TO'];
						if($follow=='F')
						{
							$sqli="INSERT INTO incentive.CLAIM(PROFILEID,USERNAME,CONVINCE_TIME,ENTRY_TIME,ENTRYBY,MODE,RES_NO,MOB_NO,EMAIL,WILL_PAY) VALUES('$profileid','".addslashes($username)."',now(),now(),'$name','$mode','".addslashes($RES_NO)."','".addslashes($MOB_NO)."','".addslashes($EMAIL)."','$WILL_PAY')";
							mysql_query_decide($sqli) or die("$sql".mysql_error_js());
						}

						$sql3="UPDATE incentive.MAIN_ADMIN SET STATUS='F',FOLLOWUP_TIME='$follow_time' WHERE PROFILEID='$profileid' AND ALLOTED_TO='$alloted_to'";
					}
					else
					{
						$sql3 = "INSERT INTO incentive.MAIN_ADMIN (PROFILEID,CONVINCE_TIME,ALLOT_TIME,ALLOTED_TO,STATUS,ALTERNATE_NO,FOLLOWUP_TIME,MODE,RES_NO,MOB_NO,EMAIL,WILL_PAY) VALUES('$profileid',now(),now(),'$name','FO','".addslashes($ALTERNATE_NO)."','$follow_time','$mode','".addslashes($RES_NO)."','".addslashes($MOB_NO)."','".addslashes($EMAIL)."','$WILL_PAY')";
					}
					mysql_query_decide($sql3) or die("$sql3".mysql_error_js());
				}
			}
			$sql4 = "INSERT INTO incentive.HISTORY (PROFILEID,USERNAME,ENTRYBY,MODE,COMMENT,ENTRY_DT) VALUES ('$profileid','".addslashes($USERNAME)."','$name','$mode','".addslashes($COMMENTS)."',now())";
			mysql_query_decide($sql4) or die("$sql4".mysql_error_js());


                        $msg .= "Below is the details you have feeded<br><br>";
                        $msg .= "Mode :$mode_value <br>";
			$msg .= "Name : $USERNAME  <br>";
                        $msg .= "Email: $EMAIL     <br>";
                        $msg .= "Phone No :$RES_NO <br>";
                        $msg .= "Mobile No :$MOB_NO<br>";
                        $msg .= "Alternate No:$ALTERNATE_NO   <br> ";
                        $msg .= "Follow up status : $follow   <br>"; 
                        $msg .= "Follow up Time :$follow_time <br>";
                        $msg .= "Payment Status : $WILL_PAY  <br>";
                        $msg .= "Comments : ".nl2br($COMMENTS)."  <br><br>";          
                        $msg .= "<a href=\"inbound_walkin.php?name=$name&cid=$cid&mode=$mode\">";
			$msg .= "Continue &gt;&gt;</a>";
			$msg .= "<a href=\"../billing/entryfrm.php?cid=$cid&pid=$profileid&username=$USERNAME&source=I\">";
			$msg .="<br><br>Enter Billing Details &gt;&gt;</a>";
			$smarty->assign("name",$name);
			$smarty->assign("cid",$cid);
			$smarty->assign("MODE",$mode);
			$smarty->assign("MSG",$msg);
                                                                                 
	                  

                        $smarty->display("crm_msg.tpl");
		}	
	
	}
	else
	{	
		$smarty->assign("follow_time","0000-00-00 00:00:00");
		$smarty->assign("name",$name);
		$smarty->assign("cid",$cid);
		$smarty->display("inbound_walkin.htm");
	}
}
else//user timed out
{
	$msg="Your session has been timed out<br>  ";
	$msg .="<a href=\"index.php\">";
	$msg .="Login again </a>";
	$smarty->assign("MSG",$msg);
	$smarty->display("crm_msg.tpl");
}
?>
