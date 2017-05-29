<?php
/*************************************************************************************************************************
Filename    :   ap_pull_profile.php
Description :   Out of queue servicing of profiles for Assisted Product. [4982]
**************************************************************************************************************************/

include("connect.inc");
include("ap_common.php");

$db=connect_db();

if(authenticated($cid))
{
	$name=getname($cid);
	$role=fetchRole($cid);

	if($searchProfile)
	{
		$userName=trim($userName);
		$smarty->assign("userName",$userName);
		$sub=array();
		if($userName)
		{
			$sql="SELECT PROFILEID,SUBSCRIPTION,CITY_RES FROM newjs.JPROFILE WHERE USERNAME='$userName' UNION SELECT PROFILEID,SUBSCRIPTION,CITY_RES FROM newjs.JPROFILE WHERE EMAIL='$userName'";
			$res=mysql_query_decide($sql) or die("Error while fetching profile id    ".mysql_error_js());
			if(mysql_num_rows($res))
			{
				$row=mysql_fetch_assoc($res);
				$profileid=$row["PROFILEID"];
				$cityRes=$row["CITY_RES"];
				if($row["SUBSCRIPTION"])
					$sub=explode(",",$row["SUBSCRIPTION"]);
				if($role=='QA')
				{
					if(in_array("T",$sub))
					{
						$sql="SELECT STATUS FROM Assisted_Product.AP_DPP_FILTER_ARCHIVE WHERE PROFILEID='$profileid' AND STATUS!='OBS' ORDER BY DPP_ID DESC";
						$res=mysql_query_decide($sql) or die("Error while checking dpp status   ".mysql_error_js());
						$row=mysql_fetch_assoc($res);
						if($row["STATUS"]=='RQA' || $row["STATUS"]=='NQA')
						{
							$sql="DELETE FROM Assisted_Product.AP_QUEUE WHERE ASSIGNED_TO='$name' AND ASSIGNED_FOR='$row[STATUS]'";
							$res=mysql_query_decide($sql) or die("Error while deleting qa entry from queue   ".mysql_error_js());
							$sql="DELETE FROM Assisted_Product.AP_QUEUE WHERE PROFILEID='$profileid' AND ASSIGNED_FOR='$row[STATUS]'";
							mysql_query_decide($sql) or die("Error while deleting profile entry from queue   ".mysql_error_js());
							$sql="INSERT IGNORE INTO Assisted_Product.AP_QUEUE(PROFILEID,ASSIGNED_TO,ASSIGN_TIME,ASSIGNED_FOR) VALUES('$profileid','$name',NOW(),'$row[STATUS]')";
	                                                mysql_query_decide($sql) or die("Error while assigning profile to QA   ".mysql_error_js());
							if($row["STATUS"]=='NQA')
								$new=1;
							else
								$new=0;
							header("Location: ".$SITE_URL."/jsadmin/ap_dpp.php?cid=".$cid."&new=".$new."&outOfQueue=1");
						}
						else
						{
							$new=isProfileNew($profileid);
							header("Location: ".$SITE_URL."/jsadmin/ap_dpp.php?cid=".$cid."&pulledProfile=".$profileid."&new=".$new."&outOfQueue=1");
						}
			
					}
					else
						$smarty->assign("errorMessage","User does not have Auto Apply service");
				}	
				if($role=='DIS')
				{
					if(in_array("L",$sub))
                                        {
						/*$sql="SELECT COUNT(*) AS COUNT FROM Assisted_Product.AP_DISPATCHER_CITIES WHERE DISPATCHER='$name' AND CITY='$cityRes'";
						$res=mysql_query_decide($sql) or die("Error while checking if profile's city matches dispatcher cities    ".mysql_error_js());
						$row=mysql_fetch_assoc($res);
						if($row["COUNT"])
						{*/
							$sql="SELECT SERVICED FROM Assisted_Product.AP_SERVICE_TABLE WHERE PROFILEID='$profileid' AND NEXT_SERVICE_DATE=CURDATE()";
							$res=mysql_query_decide($sql) or die("Error while checking if service date of profile is today   $sql   ".mysql_error_js());
							if($row=mysql_fetch_assoc($res))
							{
								if($row["SERVICED"]=='Y')
									$smarty->assign("errorMessage","Profile has already been serviced today");
								else
									$assign=1;				
							}
							else
							{
								$sql="SELECT COMPLETED FROM Assisted_Product.AP_MISSED_SERVICE_LOG WHERE PROFILEID='$profileid' AND COMPLETED=''";
								$res=mysql_query_decide($sql) or die("Error while checking entry in missed service log   ".mysql_error_js());
								if($row=mysql_fetch_assoc($res))
									$assign=1;	
								else
									$smarty->assign("errorMessage","Profile is not to be serviced today and no record of uncompleted missed service found");
							}
						/*}
						else
							$smarty->assign("errorMessage","User is not from a city assigned to you");*/
						if($assign)
						{
							$sql="DELETE FROM Assisted_Product.AP_QUEUE WHERE ASSIGNED_TO='$name' AND ASSIGNED_FOR='DIS'";
                                                        $res=mysql_query_decide($sql) or die("Error while deleting qa entry from queue   ".mysql_error_js());
                                                        $sql="DELETE FROM Assisted_Product.AP_QUEUE WHERE PROFILEID='$profileid' AND ASSIGNED_FOR='DIS'";
                                                        mysql_query_decide($sql) or die("Error while deleting profile entry from queue   ".mysql_error_js());
                                                        $sql="INSERT IGNORE INTO Assisted_Product.AP_QUEUE(PROFILEID,ASSIGNED_TO,ASSIGN_TIME,ASSIGNED_FOR) VALUES('$profileid','$name',NOW(),'DIS')";
                                                        mysql_query_decide($sql) or die("Error while assigning profile to dispatcher   ".mysql_error_js());
                                                        header("Location: ".$SITE_URL."/jsadmin/ap_list.php?cid=".$cid."&list=TBD&outOfQueue=1");
						}
                                        }
                                        else
                                                $smarty->assign("errorMessage","User does not have Profile Home Delivery service");
				}
			}
			else
				$smarty->assign("errorMessage","Invalid username / email id!");
		}
		else
			$smarty->assign("errorMessage","Please enter username / email id!!");
		
	}
	
	$smarty->assign("name",$name);
	$smarty->assign("role",$role);
	$smarty->assign("cid",$cid);

	$smarty->display("ap_pull_profile.htm");
}
else
{
	$msg="Your session has been timed out<br><br>";
	$smarty->assign("MSG",$msg);
	$smarty->display("jsadmin_msg.tpl");
}
?>
