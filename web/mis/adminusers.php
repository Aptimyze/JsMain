<?php
include("connect.inc");
$db=connect_misdb();

$data=authenticated($checksum);

if(isset($data))
{

	if($CMDGo)
	{
		$flag=1;

		if($user_type=='N')
		{
			$sql="SELECT USERNAME,PRIVILAGE FROM jsadmin.PSWRDS WHERE PRIVILAGE LIKE '%NU%'";
			$stype="O";
		}
		elseif($user_type=='P')
		{
			$sql="SELECT USERNAME,PRIVILAGE FROM jsadmin.PSWRDS WHERE PRIVILAGE LIKE '%PU%'";
			$stype="P";
		}
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js($db));
		if($row=mysql_fetch_array($res))
		{
			do
			{
				$operators[]=$row['USERNAME'];
			}while($row=mysql_fetch_array($res));
		}
		

		if($type=='M')
		{
			$mflag=1;
			$myearp1=$myear+1;

			$mmarr=array('Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar');

			$sql="SELECT COUNT(*) as cnt,ALLOTED_TO,MONTH(SUBMITED_TIME_IST) as mm FROM jsadmin.MAIN_ADMIN_LOG WHERE SUBMITED_TIME_IST BETWEEN '$myear-04-01 00:00:00' AND '$myearp1-03-31 23:59:59' AND SCREENING_TYPE='$stype' AND STATUS != 'APP-APPROVED' AND STATUS !=  'APP-EDITED' GROUP BY ALLOTED_TO,mm";

			$res=mysql_query_decide($sql,$db) or die(mysql_error_js($db));
			while($row=mysql_fetch_array($res))
			{
				$mm=$row['mm'];
				if($mm<=3)
				{
					$mm+=8;
				}
				else
				{
					$mm-=4;
				}
				$i=array_search($row['ALLOTED_TO'],$operators);
				$cnt[$i][$mm]=$row['cnt'];
				$cnt1[$mm][$i]=$row['cnt'];
				$tota[$i]+=$cnt[$i][$mm];
				$totb[$mm]+=$cnt1[$mm][$i];
			}
		}
		elseif($type=='D')
		{
			$dflag=1;

			for($i=0;$i<31;$i++)
			{
				$ddarr[$i]=$i+1;
			}
			if($dmonth<10)
				$dmonth="0".$dmonth;

			$sql="SELECT COUNT(*) as cnt,ALLOTED_TO,dayofmonth(left(SUBMITED_TIME_IST,10)) as dd FROM jsadmin.MAIN_ADMIN_LOG WHERE SUBMITED_TIME_IST BETWEEN '$dyear-$dmonth-01 00:00:00' AND '$dyear-$dmonth-31 23:59:59' AND SCREENING_TYPE='$stype' AND STATUS != 'APP-APPROVED' AND STATUS !=  'APP-EDITED' GROUP BY ALLOTED_TO,dd";

			$res=mysql_query_decide($sql,$db) or die(mysql_error_js($db));
			if($row=mysql_fetch_array($res))
			{
				do
				{
					$dd=$row['dd']-1;
//					$dd=array_search($row['dd'],$ddarr);
					$i=array_search($row['ALLOTED_TO'],$operators);
					$cnt[$i][$dd]=$row['cnt'];
					$cnt1[$dd][$i]=$row['cnt'];
					$tota[$i]+=$cnt[$i][$dd];
					$totb[$dd]+=$cnt1[$dd][$i];
				}while($row=mysql_fetch_array($res));
			}
		}
		$smarty->assign("cnt",$cnt);
		$smarty->assign("tota",$tota);
		$smarty->assign("totb",$totb);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("flag",$flag);
                $smarty->assign("mflag",$mflag);
                $smarty->assign("dflag",$dflag);
		$smarty->assign("myear",$myear);
		$smarty->assign("myearp1",$myearp1);
		$smarty->assign("dyear",$dyear);
		$smarty->assign("dmonth",$dmonth);
		$smarty->assign("operators",$operators);

                $smarty->display("adminusers.htm");
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

		$smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
		$smarty->assign("CHECKSUM",$checksum);
		$smarty->display("adminusers.htm");
	}
}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}
?>
