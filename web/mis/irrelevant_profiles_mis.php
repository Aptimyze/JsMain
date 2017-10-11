<?php
                                                                                                 
include("connect.inc");
$db=connect_misdb();

if(authenticated($cid))
{
	$smarty->assign("cid",$cid);
	if($CMDGo)
	{
		$smarty->assign("flag",1);
		$smarty->assign("dt",$mm."-".$yy);
		$st_date=$yy."-".$mm."-01 00:00:00";
		$end_date=$yy."-".$mm."-31 23:59:59";

		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}

		if($sourcegp)
		{
			$sql_s="SELECT DISTINCT SourceID FROM MIS.SOURCE WHERE GROUPNAME='$sourcegp'";
			$res_s=mysql_query_decide($sql_s,$db) or die(mysql_error_js());
			while($row_s=mysql_fetch_array($res_s))
			{
				$srcarr[]=$row_s['SourceID'];
			}
			$srcidstr="'".implode("','",$srcarr)."'";
		}

		$sql="SELECT count( * ) AS cnt, DAYOFMONTH( ENTRY_DT ) AS dd FROM newjs.JPROFILE WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND ACTIVATED IN ('Y','H','D') AND COUNTRY_RES = '88' ";
		if($sourcegp)
		{
			$sql.=" AND SOURCE IN ($srcidstr) ";
		}
		$sql.=" GROUP BY dd";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$dd=$row['dd']-1;
			$pakcnt[$dd]=$row['cnt'];
			$paktot+=$row['cnt'];
		}

		$sql="SELECT count( * ) AS cnt, DAYOFMONTH( ENTRY_DT ) AS dd FROM newjs.JPROFILE WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND ACTIVATED IN ('Y','H','D') AND MTONGUE = '1' ";
		if($sourcegp)
		{
			$sql.=" AND SOURCE IN ($srcidstr) ";
		}
		$sql.=" GROUP BY dd";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$dd=$row['dd']-1;
			$focnt[$dd]=$row['cnt'];
			$fotot+=$row['cnt'];
		}

		$sql="SELECT count( * ) AS cnt, DAYOFMONTH( ENTRY_DT ) AS dd,GENDER,AGE FROM newjs.JPROFILE WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND ACTIVATED IN ('Y','H','D') ";
		if($sourcegp)
		{
			$sql.=" AND SOURCE IN ($srcidstr) ";
		}
		$sql.=" GROUP BY GENDER,AGE,dd";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$gender=$row['GENDER'];
			$age=$row['AGE'];
			$dd=$row['dd']-1;
			if($gender=='M')
			{
				if($age<25)
				{
					$mcnt[$dd][0]+=$row['cnt'];
					$mtota[0]+=$row['cnt'];
				}
				else
				{
					$mcnt[$dd][1]+=$row['cnt'];
					$mtota[1]+=$row['cnt'];
				}
				$mtotb[$dd]+=$row['cnt'];
				$mtot+=$row['cnt'];
			}
			else
			{
				$fcnt[$dd]+=$row['cnt'];
				$ftot+=$row['cnt'];
			}
			$tot[$dd]+=$row['cnt'];
			$totall+=$row['cnt'];
		}
        
        $sql="SELECT count( * ) AS cnt, DAYOFMONTH( ENTRY_DT ) AS dd FROM newjs.JPROFILE WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND ACTIVATED IN ('Y','H','D') AND (MOB_STATUS = 'Y' OR LANDL_STATUS = 'Y')";
		if($sourcegp)
		{
			$sql.=" AND SOURCE IN ($srcidstr) ";
		}
		$sql.=" GROUP BY dd";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$dd=$row['dd']-1;
			$mobVerified[$dd]=$row['cnt'];
			$mobVerifiedtot+=$row['cnt'];
		} 

		$smarty->assign("pakcnt",$pakcnt);
		$smarty->assign("paktot",$paktot);
		$smarty->assign("focnt",$focnt);
		$smarty->assign("fotot",$fotot);
		$smarty->assign("mcnt",$mcnt);
		$smarty->assign("fcnt",$fcnt);
		$smarty->assign("mtota",$mtota);
		$smarty->assign("mtotb",$mtotb);
		$smarty->assign("mtot",$mtot);
		$smarty->assign("ftot",$ftot);
		$smarty->assign("tot",$tot);
		$smarty->assign("totall",$totall);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("source",$sourcegp);
        $smarty->assign("mobVerified",$mobVerified);
        $smarty->assign("mobVerifiedtot",$mobVerifiedtot);

		$smarty->display("irrelevant_profiles_mis.htm");
	}
	else
	{
		$sql="SELECT DISTINCT GROUPNAME FROM MIS.SOURCE";// WHERE GROUPNAME<>'NONE'";
	//      if($active)
	//              $sql.=" WHERE ACTIVE='Y'";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		$i=0;
		while($row=mysql_fetch_array($res))
		{
			$srcgrp[$i]=$row['GROUPNAME'];
			$i++;
		}
													 
		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}
		for($i=0;$i<12;$i++)
		{
			$mmarr[$i]=$i+1;
		}
													 
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("srcgrp",$srcgrp);
		$smarty->assign("srcgrpsel",$srcgp);
		$smarty->display("irrelevant_profiles_mis.htm");
	}
}
else
{
	$smarty->display("jsconnectError.htm");
}
?>
