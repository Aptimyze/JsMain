<?php
/****
        File          : registered_members_us.php
        Description   : This file shows mis of members registered from US
        Modification  : Authentication on master , mis queries on slave
        Date          : 24th May, 2005 by shiv
****/

include("connect.inc");

$db2=connect_db();	
$db=connect_slave();	

$flag=0;

if($CMDGo)
{
	$smarty->assign("flag",1);
	if($dt_type=="day")
	{
		$smarty->assign("dflag",1);
		$smarty->assign("dt","$ddate_mon-$ddate_yyyy");

		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}

		$start_date=$ddate_yyyy."-".$ddate_mon."-01 00:00:00";
		$end_date=$ddate_yyyy."-".$ddate_mon."-31 23:59:59";

		$sql="SELECT count(*) as cnt,CITY_RES,COUNTRY_RES,DAYOFMONTH(ENTRY_DT) as dd FROM newjs.JPROFILE WHERE ENTRY_DT BETWEEN '$start_date' AND '$end_date' AND ACTIVATED<>'D' AND INCOMPLETE<>'Y' AND COUNTRY_RES='128' GROUP BY dd ORDER BY cnt DESC";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			do
			{
				$counter=$row['cnt'];
				$dd=$row['dd']-1;
				$utotb[$dd]+=$counter;
				$utotall+=$counter;
			}while($row=mysql_fetch_array($res));
		}
	}
	elseif($dt_type=="mnt")
	{
		$smarty->assign("mflag",1);
		$mdate_yyyyp1=$mdate_yyyy+1;
		$smarty->assign("dt",$mdate_yyyy);
		$smarty->assign("dt1",$mdate_yyyyp1);

		$mmarr=array('Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar');

		$start_date=$mdate_yyyy."-04-01 00:00:00";
		$end_date=$mdate_yyyyp1."-03-31 23:59:59";

		$sql="SELECT count(*) as cnt,CITY_RES,COUNTRY_RES,MONTH(ENTRY_DT) as mm FROM newjs.JPROFILE WHERE ENTRY_DT BETWEEN '$start_date' AND '$end_date' AND ACTIVATED<>'D' AND INCOMPLETE<>'Y' AND COUNTRY_RES='128' GROUP BY mm ORDER BY cnt DESC";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			do
			{
				$counter=$row['cnt'];

				$mm=$row['mm'];
				if($mm<=3)
				{
					$mm+=8;
				}
				else
				{
					$mm-=4;
				}
				$utotb[$mm]+=$counter;
				$utotall+=$counter;
			}while($row=mysql_fetch_array($res));
		}
	}

	$smarty->assign("ucnt",$ucnt);
	$smarty->assign("utota",$utota);
	$smarty->assign("utotb",$utotb);
	$smarty->assign("utotall",$utotall);

	$smarty->assign("ucityarr",$ucityarr);
	$smarty->assign("mmarr",$mmarr);
	$smarty->assign("ddarr",$ddarr);

	$smarty->display("registered_members_us.htm");
}
else
{	
	for($i=0;$i<12;$i++)
	{
		$mmarr[$i]=$i+1;
	}

	$smarty->assign("ddarr",$ddarr);
	$smarty->assign("mmarr",$mmarr);
	$smarty->display("registered_members_us.htm");
}
?>
