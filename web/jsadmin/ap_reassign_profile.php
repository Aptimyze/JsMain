<?php
include("connect.inc");

$db=connect_db();

if(authenticated($cid))
{
	$user=getname($cid);
	if($reassignProfile)
	{
		$userName=$searchedUserName;
		if($newOperator==$myOperator)
		{
			$searchProfile=1;
			$smarty->assign("errorMessage","Profile is already assigned to the selected operator");
		}
		else
		{
			$sql="UPDATE Assisted_Product.AP_PROFILE_INFO SET SE='$newOperator' WHERE PROFILEID='$profileid'";
			mysql_query_decide($sql) or die("Error while reassigning profile   ".$sql."   ".mysql_error_js());
			if(mysql_affected_rows_js()!=0)
			{
				$sql="INSERT INTO Assisted_Product.AP_ASSIGN_LOG(PROFILEID,USER,DATE,ASSIGNED_BY) VALUES('$profileid','$newOperator',NOW(),'$user')";
				mysql_query_decide($sql) or die("Error while logging assigning of profile   ".$sql."   ".mysql_error_js());
				$smarty->assign("errorMessage","$userName is reassigned to $newOperator");
			}
			else
			{	
				$searchProfile=1;
				$smarty->assign("errorMessage","Some error occurred. Please try again");
			}	
			$smarty->assign("userName",$userName);
		}
	}
	if($searchProfile)
	{
		$userName=trim($userName);
		if($userName)
		{
			$sql="SELECT PROFILEID,SUBSCRIPTION FROM newjs.JPROFILE WHERE USERNAME='$userName'";
			$res=mysql_query_decide($sql) or die("Error while fetching profileid   ".$sql."   ".mysql_error_js());
			if(mysql_num_rows($res))
			{
				$row=mysql_fetch_assoc($res);
				$profileid=$row["PROFILEID"];
				if($row["SUBSCRIPTION"])
				{
					$sub=explode(",",$row["SUBSCRIPTION"]);
					if(in_array("T",$sub))
					{
						$sqlOp="SELECT SE FROM Assisted_Product.AP_PROFILE_INFO WHERE PROFILEID='$profileid'";
						$resOp=mysql_query_decide($sqlOp) or die("Error while fetching SE name  ".$sqlOp."   ".mysql_error_js());
						if(mysql_num_rows($resOp))
						{
							$rowOp=mysql_fetch_assoc($resOp);			
							$operator=$rowOp["SE"];
							$smarty->assign("myOperator",$operator);
							$sqlOp="SELECT USERNAME FROM jsadmin.PSWRDS WHERE PRIVILAGE LIKE '%SE%' AND ACTIVE='Y'";
							$resOp=mysql_query_decide($sqlOp) or die("Error while fetching list of SE's   ".$sqlOp."   ".mysql_error_js());
							if(mysql_num_rows($resOp))
							{
								while($rowOp=mysql_fetch_assoc($resOp))
									$operators[]=$rowOp["USERNAME"];
								$smarty->assign("operators",$operators);
							}
						}
						else
							$smarty->assign("errorMessage","User is not assigned due to some error");
					}
					else
						$smarty->assign("errorMessage","User is not billed for Auto apply");
				}
				else
					$smarty->assign("errorMessage","User is not billed for Auto apply");
				$smarty->assign("profileid",$profileid);
			}
			else
				$smarty->assign("errorMessage","Invalid username");
			$smarty->assign("userName",$userName);
		}
		else
			$smarty->assign("errorMessage","Please enter username");
	}
	$smarty->assign("user",$user);
	$smarty->assign("cid",$cid);
	$smarty->display("ap_reassign_profile.htm");
}
else
{
	$msg="Your session has been timed out<br><br>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
