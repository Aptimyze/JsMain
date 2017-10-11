<?php
include_once("connect.inc");
$data=authenticated($cid);
if($data)
{
        if($Submit)
        {
		$sql_main="SELECT PROFILEID FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO = '$gone_user'";
		$res_main=mysql_query_decide($sql_main) or die("$sql_main".mysql_error_js());
		while($row_main=mysql_fetch_array($res_main))
			$pid_arr[]=$row_main['PROFILEID'];
		if(count($pid_arr))
		{
			$del_ma=0;
			for($i=0;$i<count($pid_arr);$i++)
			{
				$pid=$pid_arr[$i];
				if($pid)
				{
					$sql="DELETE FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO = '$gone_user' AND PROFILEID=$pid";
					$res1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
					if($res1)
						$del_ma++;
				}
			}
			for($i=0;$i<count($pid_arr);$i++)
			{
				$pid=$pid_arr[$i];
				if($pid)
				{
					$sql="SELECT MAX( ID ) AS ID FROM incentive.CRM_DAILY_ALLOT WHERE ALLOTED_TO = '$gone_user' AND PROFILEID=$pid";
					$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
					if($row=mysql_fetch_array($res))
						$id_arr[]=$row['ID'];
				}
			}
			$del_cda=0;
			for($i=0;$i<count($id_arr);$i++)
			{
				$id=$id_arr[$i];
				if($id)
				{
					$sql="DELETE FROM incentive.CRM_DAILY_ALLOT WHERE ALLOTED_TO = '$gone_user' AND ID=$id";
					$res2=mysql_query_decide($sql) or die("$sql".mysql_error_js());
					if($res2)
						$del_cda++;
				}
			}
			if($del_cda || $del_ma)
				$smarty->assign("msg","Profiles released.<br><a href=\"$SITE_URL/jsadmin/nolongerworking.php?name=$user&cid=$cid\">Next</a>");
			else
				$smarty->assign("msg","Either you have entered invalid executive name or the profiles are already released.<br><a href=\"$SITE_URL/jsadmin/nolongerworking.php?name=$user&cid=$cid\">Back</a>");
		}
		else
			$smarty->assign("msg","Either you have entered invalid executive name or the profiles are already released.<br><a href=\"$SITE_URL/jsadmin/nolongerworking.php?name=$user&cid=$cid\">Back</a>");
	}		
	$name=getname($cid);
	$smarty->assign("name",$name);
        $smarty->assign("cid",$cid);
        unset($Submit);
        $smarty->display("nolongerworking.htm");
}
?>
