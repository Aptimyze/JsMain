<?php
/***************************************************************************************************************
* FILE NAME     : add_homepage_user.php
* DESCRIPTION   : Adds a user to Home Page
* CREATION DATE : 11 August, 2005
* CREATED BY    : Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*****************************************************************************************************************/

include("connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsivrFunctions.php");

$smarty->assign("cid",$cid);
$today=date("Y-m-d");

if(authenticated($cid))
{

	if($submit)
	{
		unset($FIELD);
		if($option!="2")
			$FIELD="USERNAME";

		
		if(trim($usernames))
		{
			$pidarr=array();
			$entryby=getname($cid);
			$message ='OPS';
			$flag=2;
			$noprofileid_flag=0;
			$usr_arr=explode(",",$usernames);
			if($option=="2")
			{
				for($i=0;$i<count($usr_arr);$i++)
				{
					if(!intval($usr_arr[$i]))
					{
						$invalid_arr[]=$usr_arr[$i];
                                                $noprofileid_flag=1;
						$usr_arr[$i]="";
					}
				}
			}
			$countk=0;
			if($option!="2")
			{
				for($i=0;$i<count($usr_arr);$i++)
				{
					$countk++;
					if($usr_arr[$i]!='')
					{
						$sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE ".$FIELD."='$usr_arr[$i]'";
						$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
						$row=mysql_fetch_array($result);
						if($row['PROFILEID'])
						{
							$pidarr[]=$row['PROFILEID'];
						}
						else
						{
							$invalid_arr[]=$usr_arr[$i];
							$noprofileid_flag=1;
						}
					}
				}
				$pidarr_str=implode(',',$pidarr);
			}
			else
			{
				for($i=0;$i<count($usr_arr);$i++)
				{
					if($usr_arr[$i])
						$pidarr[]=$usr_arr[$i];
				}
				$pidarr_str=implode(',',$pidarr);
			}

			if($marknow)
			{
				if($pidarr_str)
				{
			                // Unverify phone numbers  when marked Invalid
					/*		
					$seleMobileArr =array();
					$seleMobileStr="";
                        		$sqlUser= "SELECT J.PHONE_MOB FROM newjs.JPROFILE J,newjs.MOBILE_VERIFICATION_SMS M WHERE J.PROFILEID in($pidarr_str) and J.PHONE_MOB=M.MOBILE";
                        		$resUser=mysql_query_decide($sqlUser) or die(mysql_error_js());
                        		while($rowUser=mysql_fetch_assoc($resUser)){
                                		$seleMobileArr[] =$rowUser['PHONE_MOB'];
                        		}
                        		if($seleMobileArr){
                                		$seleMobileStr =implode("','",$seleMobileArr);
                                		$sql ="DELETE from newjs.MOBILE_VERIFICATION_SMS where MOBILE IN('$seleMobileStr')";
                                		mysql_query_decide($sql) or die(mysql_error_js());

                       			}
		                        $sql1 ="DELETE FROM newjs.MOBILE_VERIFICATION_IVR WHERE `PROFILEID` IN($pidarr_str)";
        		                mysql_query_decide($sql1) or die("$sql1".mysql_error_js());
	
        		                $sql2 ="DELETE FROM newjs.LANDLINE_VERIFICATION_IVR WHERE `PROFILEID` IN($pidarr_str)";
                        		mysql_query_decide($sql2) or die("$sql2".mysql_error_js());
			
					$sql = "UPDATE incentive.MAIN_ADMIN_POOL SET ALLOTMENT_AVAIL ='N' WHERE PROFILEID  IN ($pidarr_str)";
					mysql_query_decide($sql) or die("$sql".mysql_error_js());
					*/
				
					$sql="INSERT INTO incentive.INVALID_PHONE_LIST VALUES ('','$pidarr_str','$entryby',NOW())";
					mysql_query_decide($sql) or die("$sql".mysql_error_js());
				}

				//$pidarr=explode(",",$usernames);
				for($a=0;$a<count($pidarr);$a++)
				{
					if ($pidarr[$a]!= '')
					{
						//$sql = "REPLACE INTO incentive.INVALID_PHONE (PROFILEID,ENTRY_DT) VALUES('".trim($pidarr[$a])."',NOW())";
						//mysql_query_decide($sql) or die("$sql".mysql_error_js());

						$actionStatus ='I';
						$profileid =$pidarr[$a];	
						phoneUpdateProcess($profileid,'','',$actionStatus,$message,$entryby);

						//$sql = "INSERT INTO incentive.INVALID_PHONE_COUNT(PROFILEID, INVALID_DT, INVALID_BY) VALUES('".trim($pidarr[$a])."',NOW(),'$entryby')";
						//mysql_query_decide($sql) or die("$sql".mysql_error_js());
					}
				}
				
				
			}
			else
			{
				if($pidarr_str)
				{
					
					$sql = "UPDATE incentive.MAIN_ADMIN_POOL SET TIMES_TRIED=TIMES_TRIED+1 WHERE PROFILEID  IN ($pidarr_str)";
					mysql_query_decide($sql) or die("$sql".mysql_error_js());
				}
			}
			if($noprofileid_flag==1)
			{
				$invalid_usrname=implode(",",$invalid_arr);
			}
			$smarty->assign("count",count($invalid_arr));
			$smarty->assign("invalid_usrname",$invalid_usrname);
			$smarty->assign("option",$option);
			$smarty->assign("noprofileid_flag",$noprofileid_flag);
			$smarty->assign("flag",$flag);
			$smarty->display("invalid_phone_list.htm");
		}
		else
			$smarty->display("invalid_phone_list.htm");
	}
	else
	{
		$smarty->display("invalid_phone_list.htm");
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
