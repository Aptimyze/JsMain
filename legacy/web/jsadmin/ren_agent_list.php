<?php
include_once("connect.inc");
$data=authenticated($cid);
if($data)
{
        if($Submit)
        {
		if($users != '')
		{
			$pos = strpos($users,',');
			if($pos === false) 
			{
				$count1=1;
				$userlist=$users;
			}
			else
			{
				$userarray=explode(",",$users);
				$count1=count($userarray); 
				$users=implode("','",$userarray);
				$userlist=implode("'),('",$userarray);
			}
			$sql_main="SELECT COUNT(*) AS CNT FROM jsadmin.PSWRDS WHERE USERNAME IN ('$users') AND ACTIVE='Y'";
			$res_main=mysql_query_decide($sql_main) or die("$sql_main".mysql_error_js());
			while($row_main=mysql_fetch_array($res_main))
				$count2=$row_main['CNT'];
			if($count1==$count2)
			{
				$sql="TRUNCATE TABLE jsadmin.RENEWAL_AGENT";
                                $res1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				$sql="INSERT INTO jsadmin.RENEWAL_AGENT VALUES ('$userlist')";
				$res1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				$smarty->assign("msg","List updated.");
			}
			else
				$smarty->assign("msg","You have entered invalid executive name(s).<br><a href=\"$SITE_URL/jsadmin/ren_agent_list.php?name=$user&cid=$cid\">Back</a>");
		}
		else
			$smarty->assign("msg","You have entered invalid executive name(s).<br><a href=\"$SITE_URL/jsadmin/ren_agent_list.php?name=$user&cid=$cid\">Back</a>");
	}		
	$name=getname($cid);
	$smarty->assign("name",$name);
        $smarty->assign("cid",$cid);
        unset($Submit);
        $smarty->display("ren_agent_list.htm");
}
?>
