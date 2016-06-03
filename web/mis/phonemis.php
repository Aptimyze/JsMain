<?php
include("connect.inc");
$db=connect_misdb();
$db2=connect_master();

$data=authenticated($checksum);

if(isset($data))
//if(1)
{

	if($CMDGo)
	{
		$flag=1;

		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}
		$typearr=array('Residence Public','Residence Hidden','Mobile Public','Mobile Hidden');

//		$sql="SELECT COUNT(*) as cnt,ALLOTED_TO,dayofmonth(left(SUBMITED_TIME,10)) as dd FROM jsadmin.MAIN_ADMIN_LOG WHERE SUBMITED_TIME BETWEEN '$dyear-$dmonth-01 00:00:00' AND '$dyear-$dmonth-31 23:59:59' AND SCREENING_TYPE='$stype' GROUP BY ALLOTED_TO,dd";

echo		$sql="SELECT COUNT( * ) AS cnt, SHOWPHONE_RES, DAYOFMONTH( ENTRY_DT ) AS dd FROM newjs.JPROFILE WHERE ACTIVATED <> 'D' AND INCOMPLETE <> 'Y' AND ENTRY_DT BETWEEN '2004-12-01' AND '2004-12-31' AND (PHONE_RES != '') GROUP BY dd, SHOWPHONE_RES";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			do
			{
				$dd=$row['dd']-1;
				$cnt[$i][$dd]=$row['cnt'];
				$cnt1[$dd][$i]=$row['cnt'];
				$tota[$i]+=$cnt[$i][$dd];
				$totb[$dd]+=$cnt1[$dd][$i];
			}while($row=mysql_fetch_array($res));
		}
		$smarty->assign("cnt",$cnt);
		$smarty->assign("tota",$tota);
		$smarty->assign("totb",$totb);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("flag",$flag);
		$smarty->assign("dyear",$dyear);
		$smarty->assign("dmonth",$dmonth);
		$smarty->assign("typearr",$typearr);

                $smarty->display("phonemis.htm");
	}
	else
	{
		for($i=0;$i<12;$i++)
                {
                        $mmarr[$i]=$i+1;
                }
                for($i=0;$i<10;$i++)
                {
                        $yyarr[$i]=$i+2000;
                }

		$smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
		$smarty->assign("CHECKSUM",$checksum);
                $smarty->display("phonemis.htm");
	}
}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}
?>
