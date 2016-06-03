<?php

/*****
	Modified on Jun 09, 2005 by shiv. City dropdown added.
*****/

/*****
	Modified on Jun 28, 2005 by shiv. Community dropdown added.
*****/

include("connect.inc");
$db=connect_misdb();
$db2=connect_master();

$data=authenticated($checksum);

if(isset($data))
{
	$jmmarr=array('Before Oct 2004','Oct','Nov','Dec','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar');
	$pmmarr=array('Oct','Nov','Dec','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar');

	if($city)
	{
		$sql="SELECT VALUE FROM incentive.BRANCH_CITY WHERE NEAR_BRANCH='$city'";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$cityvalarr[]=$row['VALUE'];
		}

		$citystr="'".implode("','",$cityvalarr)."'";
	}

	if($source)
	{
		$sql="SELECT SourceID FROM MIS.SOURCE WHERE GROUPNAME='$source'";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$srcidarr[]=$row['SourceID'];
		}

		$srcstr="'".implode("','",$srcidarr)."'";
	}

	$sql="SELECT COUNT(*) as cnt,MONTH(ENTRY_DT) as mm, YEAR(ENTRY_DT) as yy FROM newjs.JPROFILE WHERE ACTIVATED IN ('Y','H','D') AND INCOMPLETE<>'Y' ";
	if($source)
	{
		$sql.=" AND SOURCE IN ($srcstr) ";
	}
	if($city)
	{
		$sql.=" AND CITY_RES IN ($citystr) ";
	}
	if($community)
	{
		$sql.=" AND MTONGUE='$community' ";
	}
	$sql.=" GROUP BY yy,mm";

	$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		do
		{
			$cnt=$row['cnt'];
			$yy=$row['yy'];
			$mm=$row['mm'];

			if($yy<2004)
			{
				$mm=0;
				$regtot[$mm]+=$cnt;
			}
			if($yy==2004)
			{
				if($mm<10)
				{
					$mm=0;
					$regtot[$mm]+=$cnt;
				}
				else
				{
					$mm=$mm-9;
					$regtot[$mm]+=$cnt;
				}
			}
			if($yy==2005)
                        {
                                $mm=$mm+3;
                                $regtot[$mm]+=$cnt;
                        }
			if($yy==2006)
                        {
                                $mm=$mm+15;
                                $regtot[$mm]+=$cnt;
                        }
			$reg_mem_tot+=$cnt;
		}while($row=mysql_fetch_array($res));
	}

	$sql="SELECT COUNT(DISTINCT p.PROFILEID) as cnt,month(j.ENTRY_DT) as jmm,year(j.ENTRY_DT) as jyy,month(p.ENTRY_DT) as pmm,year(p.ENTRY_DT) as pyy FROM billing.PURCHASES p LEFT JOIN newjs.JPROFILE j ON j.PROFILEID=p.PROFILEID WHERE INCOMPLETE<>'Y' AND p.ENTRY_DT BETWEEN '2004-10-01 00:00:00' AND '2006-03-31 23:59:59' AND p.STATUS='DONE' ";

	if($source)
	{
		$sql.=" AND j.SOURCE IN ($srcstr) ";
	}
	if($city)
	{
		$sql.=" AND j.CITY_RES IN ($citystr) ";
	}
	if($community)
	{
		$sql.=" AND j.MTONGUE='$community' ";
	}

	$sql.=" GROUP BY jyy,jmm,pyy,pmm UNION SELECT COUNT(DISTINCT p.PROFILEID) as cnt,month(j.ENTRY_DT) as jmm,year(j.ENTRY_DT) as jyy,month(p.ENTRY_DT) as pmm,year(p.ENTRY_DT) as pyy FROM billing.oct_nov_record p LEFT JOIN newjs.JPROFILE j ON j.PROFILEID=p.PROFILEID WHERE INCOMPLETE<>'Y' AND p.ENTRY_DT BETWEEN '2004-10-01 00:00:00' AND '2006-03-31 23:59:59' AND p.STATUS='DONE'  ";

	if($source)
	{
		$sql.=" AND j.SOURCE IN ($srcstr) ";
	}
	if($city)
	{
		$sql.=" AND j.CITY_RES IN ($citystr) ";
	}
	if($community)
	{
		$sql.=" AND j.MTONGUE='$community' ";
	}

	$sql.=" GROUP BY jyy,jmm,pyy,pmm";

	$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		do
		{
			$cnt=$row['cnt'];
			$jmm=$row['jmm'];
			$jyy=$row['jyy'];
			$pmm=$row['pmm'];
			$pyy=$row['pyy'];

			if($pyy==2004)
			{
				if($pmm<10)
				{
					$pmm=0;
				}
				else
				{
					$pmm=$pmm-10;
				}
			}
			if($pyy>2004)
			{
				$pmm=$pmm+2;
			}
			if($pyy>2005)
			{
				$pmm=$pmm+12;
			}

			if($jyy<2004)
			{
				$jmm=0;
				$tot[$jmm][$pmm]+=$cnt;
			}
			if($jyy==2004)
			{
				if($jmm<10)
				{
					$jmm=0;
					$tot[$jmm][$pmm]+=$cnt;
				}
				else
				{
					$jmm=$jmm-9;
					$tot[$jmm][$pmm]=$cnt;
				}
			}
			if($jyy==2005)
			{
				$jmm=$jmm+3;
				$tot[$jmm][$pmm]=$cnt;
			}
			if($jyy==2006)
			{
				$jmm=$jmm+15;
				$tot[$jmm][$pmm]=$cnt;
			}
			$ptot[$pmm]+=$cnt;
			$jtot[$jmm]+=$cnt;
			$totall+=$cnt;
		}while($row=mysql_fetch_array($res));
	}

	for($i=0;$i<count($jmmarr);$i++)
	{
		for($j=0;$j<count($pmmarr);$j++)
		{
			if($regtot[$i])
			{
				$percent[$i][$j]=$tot[$i][$j]/$regtot[$i] * 100;
				$percent[$i][$j]=round($percent[$i][$j],1);
			}
		}
		if($regtot[$i])
		{
			$jtotpercent[$i]=$jtot[$i]/$regtot[$i] * 100;
			$jtotpercent[$i]=round($jtotpercent[$i],1);
		}
	}

	$sql="SELECT DISTINCT GROUPNAME FROM MIS.SOURCE";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
	while($row=mysql_fetch_array($res))
	{
		$srcarr[]=$row['GROUPNAME'];
	}

	$i=0;
	$sql="SELECT VALUE,LABEL FROM incentive.BRANCH_CITY WHERE NEAR_BRANCH<>'' AND NEAR_BRANCH=VALUE";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
	while($row=mysql_fetch_array($res))
	{
		$cityarr[$i]['VAL']=$row['VALUE'];
		$cityarr[$i]['LABEL']=$row['LABEL'];
		$i++;
	}

	$i=0;
	$sql="SELECT VALUE,SMALL_LABEL FROM newjs.MTONGUE WHERE 1";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
	while($row=mysql_fetch_array($res))
	{
		$communityarr[$i]['VAL']=$row['VALUE'];
		$communityarr[$i]['LABEL']=$row['SMALL_LABEL'];
		$i++;
	}

	$smarty->assign("jmmarr",$jmmarr);
	$smarty->assign("pmmarr",$pmmarr);
	$smarty->assign("regearlier",$regearlier);
	$smarty->assign("tot",$tot);
	$smarty->assign("totall",$totall);
	$smarty->assign("reg",$reg);
	$smarty->assign("regtot",$regtot);
	$smarty->assign("reg_mem_tot",$reg_mem_tot);
	$smarty->assign("ptot",$ptot);
	$smarty->assign("jtot",$jtot);
	$smarty->assign("percent",$percent);
	$smarty->assign("jtotpercent",$jtotpercent);
	$smarty->assign("source",$source);
	$smarty->assign("city",$city);
	$smarty->assign("community",$community);
	$smarty->assign("srcarr",$srcarr);
	$smarty->assign("cityarr",$cityarr);
	$smarty->assign("communityarr",$communityarr);
	$smarty->assign("checksum",$checksum);
	$smarty->display("bckup_paynext.htm");
}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}
?>
