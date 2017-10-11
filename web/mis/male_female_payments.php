<?php
//include("connect.inc");
//b=connect_misdb();
//b2=connect_master();

if(authenticated($cid))
{
	if($CMDGo)
	{
		$smarty->assign("flag","1");

		$genderarr=array('Male','Female');
		$agearr=array('18-21','22-25','26-29','30-33','34+');

		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}

		$st_date=$yy."-".$mm."-01 00:00:00";
		$end_date=$yy."-".$mm."-31 23:59:59";

		$sql="SELECT VALUE,SMALL_LABEL FROM newjs.MTONGUE WHERE 1";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		$i=0;
		while($row=mysql_fetch_array($res))
		{
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

		$smarty->assign("commarr",$commarr);
		$smarty->assign("srcgrp",$srcgrp);
		$smarty->assign("ctryarr",$ctryarr);

		//$sql="SELECT COUNT(*) as cnt,j.GENDER,DAYOFMONTH(b.ENTRY_DT) as dd,AGE FROM billing.PURCHASES b LEFT JOIN newjs.JPROFILE j ON b.PROFILEID=j.PROFILEID WHERE INCOMPLETE<>'Y' AND b.STATUS='DONE' AND b.ENTRY_DT BETWEEN '$st_date' AND '$end_date' GROUP BY AGE,j.GENDER,dd";
		// $sql="SELECT COUNT(*) as cnt,j.GENDER,DAYOFMONTH(b.ENTRY_DT) as dd,AGE FROM billing.PURCHASES b LEFT JOIN newjs.JPROFILE j ON b.PROFILEID=j.PROFILEID WHERE INCOMPLETE<>'Y' AND b.STATUS='DONE' AND b.ENTRY_DT BETWEEN '$st_date' AND '$end_date' ";
		$sql = "SELECT j.GENDER, DAYOFMONTH( b.ENTRY_DT ) AS dd, AGE, INCOMPLETE";
		if($sourcegp)
			$sql .= ", j.SOURCE";
		if($community)
			$sql .= ", j.MTONGUE";
		if($mstatus)
			$sql .= ", j.MSTATUS";
		if($country)
			$sql .= ", j.COUNTRY_RES";
		if($subs=='P' || $subs=='F')
			$sql .= ", j.SUBSCRIPTION";

		$sql .= " FROM billing.PURCHASES b LEFT JOIN newjs.JPROFILE j ON b.PROFILEID = j.PROFILEID WHERE b.STATUS =  'DONE' AND b.ENTRY_DT BETWEEN  '$st_date' AND  '$end_date'";
		$sql = "SELECT COUNT(*) as cnt, Temp.GENDER, Temp.dd, Temp.AGE FROM (".$sql.") AS Temp WHERE Temp.INCOMPLETE <>  'Y'";

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
                if($community)
                {
                        $sql.=" AND Temp.MTONGUE='$community' ";
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
                $sql .= "  GROUP BY Temp.AGE, Temp.GENDER, Temp.dd";
                
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$dd=$row['dd']-1;

			if($row['AGE']<=21)
				$k=0;
			elseif($row['AGE']>21 && $row['AGE']<=25)
				$k=1;
			elseif($row['AGE']>25 && $row['AGE']<=29)
				$k=2;
			elseif($row['AGE']>29 && $row['AGE']<=33)
				$k=3;
			else
				$k=4;

			if($row['GENDER']=='M')
				$i=0;
			elseif($row['GENDER']=='F')
				$i=1;

			$cnt[$i][$dd]+=$row['cnt'];
			$tota[$i]+=$row['cnt'];
			$totb[$dd]+=$row['cnt'];
			$totall+=$row['cnt'];

			$cnt2[$i][$k][$dd]+=$row['cnt'];
			$tot2a[$i][$k]+=$row['cnt'];
			$tot2b[$i][$dd]+=$row['cnt'];
			$totall2[$i]+=$row['cnt'];
		}

		for($i=0;$i<count($genderarr);$i++)
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

		for($i=0;$i<count($genderarr);$i++)
		{
			for($k=0;$k<count($agearr);$k++)
			{
				for($j=0;$j<count($ddarr);$j++)
				{
					if($tot2b[$i][$j])
					{
						$per2[$i][$k][$j]=$cnt2[$i][$k][$j]/$tot2b[$i][$j] * 100;
						$per2[$i][$k][$j]=round($per2[$i][$k][$j],1);
					}
				}
				if($totall2[$i])
				{
					$pertot2[$i][$k]=$tot2a[$i][$k]/$totall2[$i] * 100;
					$pertot2[$i][$k]=round($pertot2[$i][$k],1);
				}
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

		$smarty->assign("community",$community);
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

		$smarty->assign("cnt2",$cnt2);
		$smarty->assign("tot2a",$tot2a);
		$smarty->assign("tot2b",$tot2b);
		$smarty->assign("totall2",$totall2);
		$smarty->assign("per2",$per2);
		$smarty->assign("pertot2",$pertot2);

		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("genderarr",$genderarr);
		$smarty->assign("agearr",$agearr);

		$smarty->assign("mm",$mm);
		$smarty->assign("yy",$yy);
		$smarty->assign("select","age");
		$smarty->assign("cid",$cid);

		$smarty->display("male_female_payments.htm");
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
		$smarty->display("male_female_payments.htm");
	}
}
else
{
	$smarty->display("jsconnectError.tpl");
}
?>
