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

	if($switchOn || $switchOff)
	{
		if($switchOn)
			$toggledTo='Y';
	       	else
			$toggledTo='N';
		$sql="UPDATE Assisted_Product.AP_PROFILE_INFO SET SEND='$toggledTo' WHERE PROFILEID='$profileid'";
		mysql_query_decide($sql) or die("Error while switching auto apply on   ".mysql_error_js());
		$sql="INSERT INTO Assisted_Product.AP_TOGGLE_AUTO_APPLY_LOG(PROFILEID,TOGGLED_BY,TOGGLED_ON,TOGGLED_TO) VALUES('$profileid','$name',NOW(),'$toggledTo')";
		mysql_query_decide($sql) or die("Error while logging auto apply toggle   ".mysql_error_js());
		if($switchOn)
			$smarty->assign("message","Auto Apply has been turned on for $userName");
		else
			$smarty->assign("message","Auto Apply has been turned off for $userName");
	}
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
				if(in_array("T",$sub))
				{
					$smarty->assign("profileid",$profileid);
					$sql="SELECT SEND FROM Assisted_Product.AP_PROFILE_INFO WHERE PROFILEID='$profileid'";
					$res=mysql_query_decide($sql) or die("Error while fetching current status   ".mysql_error_js());
					$row=mysql_fetch_assoc($res);
					if($row["SEND"]=="Y")
					{
						$smarty->assign("buttonName","switchOff");
						$smarty->assign("buttonLabel","Turn Off");
					}
					else
					{
						$smarty->assign("buttonName","switchOn");
						$smarty->assign("buttonLabel","Turn On");
					}
				}
				else
					$smarty->assign("message","User does not have Auto Apply service");
			}
			else
				$smarty->assign("message","Invalid username / email id!");
		}
		else
			$smarty->assign("message","Please enter username / email id!!");
		
	}
	
	$smarty->assign("name",$name);
	$smarty->assign("role",$role);
	$smarty->assign("cid",$cid);

	$smarty->display("ap_toggle_auto_apply.htm");
}
else
{
	$msg="Your session has been timed out<br><br>";
	$smarty->assign("MSG",$msg);
	$smarty->display("jsadmin_msg.tpl");
}
?>
