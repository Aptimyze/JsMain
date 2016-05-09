<?php
//include("connect.inc");
//$db=connect_misdb();
//$db2=connect_master();

if(authenticated($cid))
{
	if($CMDGo)
	{
		$smarty->assign("flag","1");

		$sql="SELECT VALUE,SMALL_LABEL FROM newjs.MTONGUE WHERE 1";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		$i=0;
		while($row=mysql_fetch_array($res))
		{
			$valarr[]=$row['VALUE'];
			$commarr1[]=$row['SMALL_LABEL'];
			$commarr[$i]["VAL"]=$row['VALUE'];
			$commarr[$i]["LABEL"]=$row['SMALL_LABEL'];
			$i++;
		}

                $sql="SELECT DISTINCT GROUPNAME FROM MIS.SOURCE";
                $res=mysql_query_decide($sql,$db) or die(mysql_error_js());
                $i=0;
                while($row=mysql_fetch_array($res))
                {
                        $srcgrp[$i]=$row['GROUPNAME'];
                        $i++;
                }

                $sql="SELECT VALUE,LABEL FROM newjs.TOP_COUNTRY WHERE 1";
                $res=mysql_query_decide($sql,$db) or die(mysql_error_js());
                $i=0;
                while($row=mysql_fetch_array($res))
                {
                        $ctryarr[$i]['VAL']=$row['VALUE'];
                        $ctryarr[$i]['LABEL']=$row['LABEL'];
                        $i++;
                }

                $smarty->assign("srcgrp",$srcgrp);
                $smarty->assign("ctryarr",$ctryarr);
                $smarty->assign("commarr",$commarr);

		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}

		$st_date=$yy."-".$mm."-01 00:00:00";
		$end_date=$yy."-".$mm."-31 23:59:59";

		//$sql="SELECT COUNT(*) as cnt,MTONGUE,DAYOFMONTH(b.ENTRY_DT) as dd FROM billing.PURCHASES b LEFT JOIN newjs.JPROFILE j ON b.PROFILEID=j.PROFILEID WHERE INCOMPLETE<>'Y' AND b.STATUS='DONE' AND b.ENTRY_DT BETWEEN '$st_date' AND '$end_date' GROUP BY MTONGUE,dd";
		// $sql="SELECT COUNT(*) as cnt,MTONGUE,DAYOFMONTH(b.ENTRY_DT) as dd FROM billing.PURCHASES b LEFT JOIN newjs.JPROFILE j ON b.PROFILEID=j.PROFILEID WHERE INCOMPLETE<>'Y' AND b.STATUS='DONE' AND b.ENTRY_DT BETWEEN '$st_date' AND '$end_date' ";
		$sql = "SELECT j.MTONGUE, DAYOFMONTH( b.ENTRY_DT ) AS dd, INCOMPLETE";
		if($sourcegp)
			$sql .= ", j.SOURCE";
		if($gender)
			$sql .= ", j.GENDER";
		if($mstatus)
			$sql .= ", j.MSTATUS";
		if($country)
			$sql .= ", j.COUNTRY_RES";
		if($subs=='P' || $subs=='F')
			$sql .= ", j.SUBSCRIPTION";

		$sql .= " FROM billing.PURCHASES b LEFT JOIN newjs.JPROFILE j ON b.PROFILEID = j.PROFILEID WHERE b.STATUS =  'DONE' AND b.ENTRY_DT BETWEEN  '$st_date' AND  '$end_date'";
		$sql = "SELECT COUNT(*) AS cnt, Temp.MTONGUE, Temp.dd FROM (".$sql.") AS Temp WHERE Temp.INCOMPLETE <>  'Y'";

		if($sourcegp)
		{
			$sql1="SELECT SourceID FROM MIS.SOURCE WHERE GROUPNAME='$sourcegp'";
			$res1=mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js($db));
			while($row1=mysql_fetch_array($res1))
			{
				$srcarr[]=$row1['SourceID'];
			}
			if($srcarr)
			{
				$srcstr="'".implode("','",$srcarr)."'";
				$sql.=" AND Temp.SOURCE IN ($srcstr) ";
			}
		}
		if($gender)
                {
                        $sql.=" AND Temp.GENDER='$gender' ";
                }
                if($mstatus)
                {
                        $sql.=" AND Temp.MSTATUS='$mstatus' ";
                }
                if($country)
                {
                        $sql.=" AND Temp.COUNTRY_RES='$country'";
                }
                if($subs=='P')
                {
                        $sql.=" AND Temp.SUBSCRIPTION<>'' ";
                }
                elseif($subs=='F')
                {
                        $sql.=" AND Temp.SUBSCRIPTION='' ";
                }
                $sql .= "  GROUP BY Temp.MTONGUE, Temp.dd";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$dd=$row['dd']-1;

			$i=array_search($row['MTONGUE'],$valarr);

			$cnt[$i][$dd]+=$row['cnt'];
			$tota[$i]+=$row['cnt'];
			$totb[$dd]+=$row['cnt'];
			$totall+=$row['cnt'];
		}

		for($i=0;$i<count($commarr);$i++)
		{
			for($j=0;$j<count($ddarr);$j++)
			{
				if($totb[$j])
				{
					$per[$i][$j]=$cnt[$i][$j]/$totb[$j] * 100;
					$per[$i][$j]=round($per[$i][$j],1);
				}
			}
			if($totall)
			{
				$pertot[$i]=$tota[$i]/$totall * 100;
				$pertot[$i]=round($pertot[$i],1);
			}
		}

                for($i=0;$i<12;$i++)
                {
                        $mmarr[$i]=$i+1;
                }

                for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}

                $smarty->assign("gender",$gender);
                $smarty->assign("sourcegp",$sourcegp);
                $smarty->assign("country",$country);
                $smarty->assign("mstatus",$mstatus);
                $smarty->assign("subs",$subs);

                $smarty->assign("yyarr",$yyarr);
                $smarty->assign("mmarr",$mmarr);
		$smarty->assign("cnt",$cnt);
		$smarty->assign("tota",$tota);
		$smarty->assign("totb",$totb);
		$smarty->assign("totall",$totall);
		$smarty->assign("per",$per);
		$smarty->assign("pertot",$pertot);

		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("commarr1",$commarr1);

		$smarty->assign("mm",$mm);
		$smarty->assign("yy",$yy);
	        $smarty->assign("select","community");
		$smarty->assign("cid",$cid);
		$smarty->display("community_payments.htm");
	}
	else
	{
		for($i=0;$i<12;$i++)
		{
			$mmarr[$i]=$i+1;
		}

		for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}

		$smarty->assign("yyarr",$yyarr);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("flag","0");
		$smarty->assign("cid",$cid);
		$smarty->display("community_payments.htm");
	}
}
else
{
	$smarty->display("jsconnectError.tpl");
}
?>
