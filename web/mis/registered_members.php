<?php

//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//end of it

include("connect.inc");

$db=connect_misdb();

$flag=0;

if($CMDGo)
{
	$smarty->assign("flag",1);

	if($get_today)
	{
		$dt_type="day";
	}

	if($dt_type=="day")
	{
		$smarty->assign("dflag",1);
		$smarty->assign("dt","$ddate_mon-$ddate_yyyy");

		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}

		if($get_today)
		{
			$ts=time();
			$ts-=2*24*60*60;
			$today=date("Y-m-d",$ts);

			$sql="SELECT count(*) as cnt,CITY_RES,COUNTRY_RES,DAYOFMONTH(ENTRY_DT) as dd FROM newjs.JPROFILE WHERE ENTRY_DT>='$today' AND ACTIVATED<>'D' AND INCOMPLETE<>'Y' GROUP BY dd,COUNTRY_RES,CITY_RES ORDER BY cnt DESC";
		}
		else
		{
			$start_date=$ddate_yyyy."-".$ddate_mon."-01";
			$end_date=$ddate_yyyy."-".$ddate_mon."-31";

			$sql="SELECT SUM(COUNT) as cnt,CITY_RES,COUNTRY_RES,DAYOFMONTH(ENTRY_DT) as dd FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$start_date' AND '$end_date' AND ACTIVATED<>'D' AND INCOMPLETE<>'Y' AND ENTRY_MODIFY='E' GROUP BY dd,COUNTRY_RES,CITY_RES ORDER BY cnt DESC";
		}
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			do
			{
				$counter=$row['cnt'];
				$city_resval=$row['CITY_RES'];
				$ctry_resval=$row['COUNTRY_RES'];
				$dd=$row['dd']-1;

				if($ctry_resval==51)
				{
					$city_res=get_label($ctry_resval,$city_resval);
					if(is_array($icityarr))
					{
						if(!in_array($city_res,$icityarr))
						{
							$icityarr[]=$city_res;
						}
					}
					else
					{
						$icityarr[]=$city_res;
					}
					$i=array_search($city_res,$icityarr);
					
					$icnt[$i][$dd]+=$counter;
					//$icnt1[$dd][$i]=$counter;
					$itota[$i]+=$counter;
					$itotb[$dd]+=$counter;
				}
				elseif($ctry_resval==128)
				{
					$city_res=get_label($ctry_resval,$city_resval);
					if(is_array($ucityarr))
					{
						if(!in_array($city_res,$ucityarr))
						{
							$ucityarr[]=$city_res;
						}
					}
					else
					{
						$ucityarr[]=$city_res;
					}
					$i=array_search($city_res,$ucityarr);
					
					$ucnt[$i][$dd]+=$counter;
					//$ucnt1[$dd][$i]=$counter;
					$utota[$i]+=$counter;
					$utotb[$dd]+=$counter;
				}
				else
				{
					$city_res=get_label($ctry_resval,$city_resval);
					if(is_array($ocityarr))
					{
						if(!in_array($city_res,$ocityarr))
						{
							$ocityarr[]=$city_res;
						}
					}
					else
					{
						$ocityarr[]=$city_res;
					}
					$i=array_search($city_res,$ocityarr);
					
					$ocnt[$i][$dd]+=$counter;
					//$ocnt1[$dd][$i]=$counter;
					$otota[$i]+=$counter;
					$ototb[$dd]+=$counter;
				}
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

		$start_date=$mdate_yyyy."-04-01";
		$end_date=$mdate_yyyyp1."-03-31";

		//$sql="SELECT count(*) as cnt,CITY_RES,COUNTRY_RES,MONTH(ENTRY_DT) as mm FROM newjs.JPROFILE WHERE ENTRY_DT BETWEEN '$start_date' AND '$end_date' AND ACTIVATED<>'D' AND INCOMPLETE<>'Y' GROUP BY mm,COUNTRY_RES,CITY_RES ORDER BY cnt DESC";
		$sql="SELECT SUM(COUNT) as cnt,CITY_RES,COUNTRY_RES,MONTH(ENTRY_DT) as mm FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$start_date' AND '$end_date' AND ACTIVATED<>'D' AND INCOMPLETE<>'Y' AND ENTRY_MODIFY='E' GROUP BY mm,COUNTRY_RES,CITY_RES ORDER BY cnt DESC";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			do
			{
				$counter=$row['cnt'];
				$city_resval=$row['CITY_RES'];
				$ctry_resval=$row['COUNTRY_RES'];

				$city_res=get_label($ctry_resval,$city_resval);

				$mm=$row['mm'];
				if($mm<=3)
				{
					$mm+=8;
				}
				else
				{
					$mm-=4;
				}

				if($ctry_resval==51)
				{
					if(is_array($icityarr))
					{
						if(!in_array($city_res,$icityarr))
						{
							$icityarr[]=$city_res;
						}
					}
					else
					{
						$icityarr[]=$city_res;
					}
					$i=array_search($city_res,$icityarr);

					$icnt[$i][$mm]+=$counter;
					//$icnt1[$mm][$i]=$counter;
					$itota[$i]+=$counter;
					$itotb[$mm]+=$counter;
				}
				elseif($ctry_resval==128)
				{
					if(is_array($ucityarr))
					{
						if(!in_array($city_res,$ucityarr))
						{
							$ucityarr[]=$city_res;
						}
					}
					else
					{
						$ucityarr[]=$city_res;
					}
					$i=array_search($city_res,$ucityarr);

					$ucnt[$i][$mm]+=$counter;
					//$ucnt1[$mm][$i]=$counter;
					$utota[$i]+=$counter;
					$utotb[$mm]+=$counter;
				}
				else
				{
					if(is_array($ocityarr))
					{
						if(!in_array($city_res,$ocityarr))
						{
							$ocityarr[]=$city_res;
						}
					}
					else
					{
						$ocityarr[]=$city_res;
					}
					$i=array_search($city_res,$ocityarr);

					$ocnt[$i][$mm]+=$counter;
					//$ocnt1[$mm][$i]=$counter;
					$otota[$i]+=$counter;
					$ototb[$mm]+=$counter;
				}
			}while($row=mysql_fetch_array($res));
		}
	}

	$smarty->assign("icnt",$icnt);
	$smarty->assign("itota",$itota);
	$smarty->assign("itotb",$itotb);
	$smarty->assign("ucnt",$ucnt);
	$smarty->assign("utota",$utota);
	$smarty->assign("utotb",$utotb);
	$smarty->assign("ocnt",$ocnt);
	$smarty->assign("otota",$otota);
	$smarty->assign("ototb",$ototb);

	$smarty->assign("icityarr",$icityarr);
	$smarty->assign("ucityarr",$ucityarr);
	$smarty->assign("ocityarr",$ocityarr);
	$smarty->assign("mmarr",$mmarr);
	$smarty->assign("ddarr",$ddarr);

	$smarty->display("registered_members.htm");
}
else
{	
	for($i=0;$i<12;$i++)
	{
		$mmarr[$i]=$i+1;
	}

	$smarty->assign("ddarr",$ddarr);
	$smarty->assign("mmarr",$mmarr);
	$smarty->display("registered_members.htm");
}

function get_label($ctry,$val)
{
	global $db;
	if($ctry==51)
	{
		$sql="SELECT LABEL FROM newjs.CITY_NEW WHERE VALUE='$val'";
	}
	elseif($ctry==128)
	{
		$sql="SELECT LABEL FROM newjs.CITY_NEW WHERE VALUE='$val'";
	}
	else
	{
		$sql="SELECT LABEL FROM newjs.COUNTRY WHERE VALUE='$ctry'";
	}
	$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
	$row=mysql_fetch_array($res);
	$label=$row['LABEL'];

	return $label;
}
// flush the buffer
if($zipIt)
	ob_end_flush();
?>
