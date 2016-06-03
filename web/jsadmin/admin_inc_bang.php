<?php
/**********************************************************************************\
                FILE NAME       :       admin_inc.php
                FILES INCLUDED  :       connect.inc
		FUNCTION DEFINED:	time_day(date,int)
                DETAILS         :       Allot the profiles to the CRM department for tele sales
					on daily basis	 
\**********************************************************************************/
//ini_set("memory_limit","64M");
include("connect.inc");

if(authenticated($cid))
{
	if($CMDGo)
	{
		$branch=strtoupper($branch);
		$sql="SELECT USERNAME, incentive.BRANCHES.VALUE as NEAR_BRANCH from jsadmin.PSWRDS, incentive.BRANCHES where PRIVILAGE like '%IUO%' and UPPER(PSWRDS.CENTER)=UPPER(BRANCHES.NAME) AND UPPER(PSWRDS.CENTER)='$branch' AND ACTIVE='Y'";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$user[]=$row['USERNAME'];
		}

		if($user)
		{
			$opsstr="'".implode("','",$user)."'";
			$sql="SELECT PROFILEID FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO IN ($opsstr) AND CONVINCE_TIME=0 AND STATUS NOT IN ('F','C','P')";
			$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$cnt= mysql_num_rows($result);
			while($myrow=mysql_fetch_array($result))
			{
					$proid[]=$myrow['PROFILEID'];
			}
			mysql_free_result($result);

			$cnt_proid=count($proid);	
			$cnt_user=count($user);
			$j=0;
			for($i=0;$i<$cnt_proid;$i++)
			{
				$proid_value=$proid[$i];
				$user_value=$user[$j];

				$sql="UPDATE incentive.MAIN_ADMIN set ALLOTED_TO='$user_value' WHERE PROFILEID='$proid_value'";
				mysql_query_decide($sql) or die("$sql".mysql_error_js());
				$j=$j+1;
				if($j==$cnt_user)
					$j=0;
			}
			$success="Success";
			$msg= " Profiles Alloted Successfully<br>  ";
			$msg .="<a href=\"mainpage.php?cid=$cid\">";
			$msg .="Continue </a>";
		}
		else
		{
			$success="Failure";
			$msg= " No Record Found. Try again<br>  ";
			$msg .="<a href=\"admin_inc_bang.php?cid=$cid\">";
			$msg .="Continue </a>";
		}

		$operator_name=getname($cid);

		$shiv_msg="NO. of profile : ".count($proid).".\n Script run by : $operator_name at time ".date("Y-m-d H:i:s")." for branch : $branch \n Allotment was : $success";
		mail("shiv.narayan@jeevansathi.com","CRM Reshuffle",$shiv_msg);

		$smarty->assign("MSG",$msg);
		$smarty->display("jsadmin_msg.tpl");
	}
	else
	{
		$sql="SELECT LABEL FROM incentive.BRANCH_CITY WHERE NEAR_BRANCH<>'' AND NEAR_BRANCH=VALUE";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$brancharr[]=$row['LABEL'];
		}

		$smarty->assign("brancharr",$brancharr);
		$smarty->assign("cid",$cid);
		$smarty->display("admin_inc_bang.htm");
	}
}
else
{
	$smarty->display("jsconnectError.tpl");
}
?>
