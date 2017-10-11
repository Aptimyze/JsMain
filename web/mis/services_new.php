<?php
include("connect.inc");
$db=connect_misdb();
$db2=connect_master();

$data=authenticated($checksum);
$flag=0;

if(isset($data))
{
	if($CMDGo)
	{
		$flag=1;
		$yearp1=$year+1;
		$mmarr=array('Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar');
//		$ssarr=array('Full Member : 2 Months','Full Member : 3 Months','Full Member : 4 Months','Full Member : 5 Months','Full Member : 6 Months','Full Member : 12 Months', );
		$ssarr=array('P2','P3','P4','P5','P6','P12','P2,B2','P3,B3','P4,B4','P5,B5','P6,B6','P12,B12');

			$sql="SELECT COUNT(*) as cnt,SERVICEID as sid,month(ENTRY_DT) as mm,ADDON_SERVICEID as addon FROM billing.PURCHASES WHERE SERVICEID in ('P2','P3','P4','P5','P6','P12') and STATUS='DONE' AND ENTRY_DT BETWEEN '$year-04-01 00:00:00' AND '$yearp1-03-31 23:59:59' GROUP BY sid,mm,addon";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			do
			{
				$sid=$row['sid'];
				$addon=$row['addon'];
//				$sname=getsname($sid,$addon);
				if(!$addon)
					$sname=$sid;
				else
					$sname=$sid.",".$addon;
				$i=array_search($sname,$ssarr);
				$mm=$row['mm'];
				if($mm<=3)
				{
					$mm+=8;
				}
				else
				{
					$mm-=4;
				}
					$tot[$i][$mm]=$row['cnt'];
					$tot1[$mm][$i]=$row['cnt'];
					$tota[$i]+=$tot[$i][$mm];
					$totb[$mm]+=$tot1[$mm][$i];
			}while($row=mysql_fetch_array($res));
		}
		for($i=0;$i<count($ssarr);$i++)
		{
			$ssarr[$i]=getsname($ssarr[$i]);
		}
		$smarty->assign("year",$year);
		$smarty->assign("yearp1",$yearp1);
		$smarty->assign("flag",$flag);
		$smarty->assign("ssarr",$ssarr);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("tot",$tot);
		$smarty->assign("tota",$tota);
		$smarty->assign("totb",$totb);

		$smarty->display("services_new.htm");
	}
	else
	{
		$user=getname($checksum);
		for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}
		$privilage=getprivilage($checksum);
		$priv=explode("+",$privilage);

		if(in_array('MA',$priv) || in_array('MB',$priv))
		{
			$smarty->assign("VIEWALL","Y");
			//run query : select all branches
			$sql="SELECT * FROM billing.BRANCHES";
			$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
			if($row=mysql_fetch_array($res))
			{
				$i=0;
				do
				{
					$branch[$i]["id"]=$row['ID'];
					$branch[$i]["name"]=$row['NAME'];

					$i++;
				}while($row=mysql_fetch_array($res));
			}
			$smarty->assign("branch",$branch);
		}
		elseif(in_array('MC',$priv) || in_array('MD',$priv))
		{
			// run query : select branch of user
			$sql="SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME='$user'";
			$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
			if($row=mysql_fetch_array($res))
			{ 
				$branch=$row['CENTER'];
			}
			$smarty->assign("branch",$branch);
		}

		$smarty->assign("priv",$priv);
		$smarty->assign("flag",$flag);
//		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yyarr",$yyarr);
		$smarty->assign("CHECKSUM",$checksum);
		$smarty->display("services_new.htm");
	}
}
else
{
        $smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}

function getsname($services_id)
{
	$services_id=str_replace(",","','",$services_id);
	$sql="SELECT NAME from billing.SERVICES where SERVICEID in ('$services_id')";			 $result=mysql_query_decide($sql) or die(mysql_error_js());
	while($myrow=mysql_fetch_array($result))
	{
		$sname[]=$myrow['NAME'];
	}
	if(count($sname)>0)
		$sname=implode("<br>",$sname);
	return $sname;
}
?>
