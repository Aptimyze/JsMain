<?php
include("connect.inc");
$db=connect_slave();

if($id)
{
	$sql="SELECT DATE,MATCHES FROM sugarcrm.LEAD_MATCHES_LOG WHERE LEAD='$id'";
	$res=mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($res))
	{
		while($row=mysql_fetch_array($res))
		{
			$date=$row['DATE'];
			$pids=$row['MATCHES'];
			if($pids)
			{
				$sql_sel="SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID IN ($pids)";
				$res_sel=mysql_query($sql_sel) or die(mysql_error());
				while($row_sel=mysql_fetch_array($res_sel))
				{
					$uname[]=$row_sel['USERNAME'];
				}
				$uname_str=@implode(", ",$uname);
				$data[$date]=$uname_str;
				unset($uname);
				unset($uname_str);
			}
			$smarty->assign("data",$data);
		}
	} 
	else
	{
		$smarty->assign("msg","No matchalert has been sent to this lead!!!");
	}
	$smarty->display("list_matches.htm");
}
?>
