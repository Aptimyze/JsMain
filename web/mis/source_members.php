<?php

include("connect.inc");

$db=connect_misdb();

if(authenticated($cid))
{
	if($CMDGo)
	{
		$sec_src=array();
		if($self){
			$sec_src[]="'S'";
		}
		if($sug_mailer)
			$sec_src[]="'M'";
		if($js_mailer)
			$sec_src[]="'I'";
		if($sug_called)
			$sec_src[]="'C'";
		$sec_sources=implode(",",$sec_src);
if($self)
	$sec_condition=" AND ( j.SEC_SOURCE IS NULL or j.SEC_SOURCE IN ( $sec_sources )) ";
else 
	$sec_condition=" AND j.SEC_SOURCE IN ( $sec_sources ) ";
		$smarty->assign("flag","1");
		$srcarr[]="Unknown";
		$sql="SELECT DISTINCT GROUPNAME FROM MIS.SOURCE";// WHERE GROUPNAME<>'NONE'";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$srcarr[]=$row['GROUPNAME'];
		}

		$mmarr=array('Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar');

		$yyp1=$yy+1;

		$st_date=$yy."-04-01 00:00:00";
		$end_date=$yyp1."-03-31 23:59:59";

		if($sec_sources)
			$sql="SELECT COUNT(DISTINCT j.PROFILEID) as cnt,m.GROUPNAME as src, month(p.ENTRY_DT) as mm FROM MIS.SOURCE m,newjs.JPROFILE j,billing.PAYMENT_DETAIL p use index (ENTRY_DT) WHERE p.ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND j.INCOMPLETE<>'Y' $sec_condition AND p.STATUS='DONE' AND m.SourceID=j.SOURCE AND p.PROFILEID=j.PROFILEID GROUP BY src,mm";
		else
		$sql="SELECT COUNT(DISTINCT j.PROFILEID) as cnt,m.GROUPNAME as src, month(p.ENTRY_DT) as mm FROM MIS.SOURCE m,newjs.JPROFILE j,billing.PAYMENT_DETAIL p use index (ENTRY_DT) WHERE p.ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND j.INCOMPLETE<>'Y' AND p.STATUS='DONE' AND m.SourceID=j.SOURCE AND p.PROFILEID=j.PROFILEID GROUP BY src,mm";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			do
			{
				$src=$row['src'];
				$counter=$row['cnt'];
				$i=array_search($src,$srcarr);
				$mm=$row['mm'];
				if($mm<=3)
				{
					$mm+=8;
				}
				else
				{
					$mm-=4;
				}
				$cnt[$i][$mm]=$counter;
				$tota[$i]+=$counter;
				$totb[$mm]+=$counter;
				$totall+=$counter;
			}while($row=mysql_fetch_array($res));
		}

		for($i=0;$i<count($srcarr);$i++)
		{
			for($j=0;$j<count($mmarr);$j++)
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

		$smarty->assign("yy",$yy);
		$smarty->assign("yyp1",$yyp1);
		$smarty->assign("cnt",$cnt);
		$smarty->assign("tota",$tota);
		$smarty->assign("totb",$totb);
		$smarty->assign("totall",$totall);
		$smarty->assign("per",$per);
		$smarty->assign("pertot",$pertot);

		$smarty->assign("srcarr",$srcarr);
		$smarty->assign("mmarr",$mmarr);

		$smarty->display("source_members.htm");
	}
	else
	{
		for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}

		$smarty->assign("cid",$cid);
		$smarty->assign("yyarr",$yyarr);
		$smarty->display("source_members.htm");
	}
}
else
{
	$smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}
?>
