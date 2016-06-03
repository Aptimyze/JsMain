<?php
include_once("connect.inc");

/*
created by nikhil tandon to track unique logins and chat requests  
*/
function cal_date($wday,$tstamp)
{
   return $tstamp-($wday*(24*3600));
}

function getweekday($m,$d,$y)
{
   $tstamp=mktime(0,0,0,$m,$d,$y);
  
   $Tdate = getdate($tstamp);
  
   return ($Tdate["wday"]);
}

$db=connect_misdb();
$db2=connect_master();

if(authenticated($cid))
{
	if($Submit)
	{	
		for($i=0;$i<31;$i++)
                {
                        $ddarr[$i]=$i+1;
			$wday_arr[]=getweekday($month,$ddarr[$i],$year);
		}
		$smarty->assign("flag",1);

		if($month<10)
		{
			$st_date=$year."-0".$month."-01";
			$end_date=$year."-0".$month."-31";
			//for chat
			$st_date_c=$year."-0".$month."-01 00:00:00";
			$end_date_c=$year."-0".$month."-31 23:59:59";
		}
		else
		{
			$st_date=$year."-".$month."-01";
			$end_date=$year."-".$month."-31";
			//for chat
			$st_date_c=$year."-".$month."-01 00:00:00";
			$end_date_c=$year."-".$month."-31 23:59:59";
		}

		//unique users logging in everyday
		$sql="SELECT count(*) as cnt,DAYOFMONTH(DAYZ) as dd FROM userplane.USERS_AD WHERE DAYZ BETWEEN '$st_date' AND '$end_date' group by dd";
                $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		$dd=0;
		$finaltotal["logins"]=0;
		$finaltotal["chats"]=0;
		$finaltotal["failconnect"]=0;
		if($row=mysql_fetch_array($res))
                {
                        do
                        {
				$dd=$row['dd']-1;
				$total_day[$dd]["logins"]=$row['cnt'];
				$finaltotal["logins"]+=$row['cnt'];
			}while($row=mysql_fetch_array($res));
                }
		//chat requests in a day
		$sql="SELECT count(*) as cnt,DAYOFMONTH(TIMEOFINSERTION) as dd FROM userplane.CHAT_REQUESTS WHERE TIMEOFINSERTION BETWEEN '$st_date_c' AND '$end_date_c' group by dd";
                $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                if($row=mysql_fetch_array($res))
                {
                        do
                        {
				$dd=$row['dd']-1;
				$total_day[$dd]["chats"]=$row['cnt'];
                                $finaltotal["chats"]+=$row['cnt'];
                        }while($row=mysql_fetch_array($res));
                }
		
		$sql="SELECT count(*) as cnt,DAYOFMONTH(DAYZ) as dd FROM userplane.USERS_CNC WHERE DAYZ BETWEEN '$st_date' AND '$end_date' group by dd";	
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                $dd=0;
                if($row=mysql_fetch_array($res))
                {
                        do
                        {
                                $dd=$row['dd']-1;
                                $total_day[$dd]["failconnect"]=$row['cnt'];
                                $finaltotal["failconnect"]+=$row['cnt'];
                        }while($row=mysql_fetch_array($res));
                }
	
		$sql="SELECT COUNT as cnt,DAYOFMONTH(DAYZ) as dd FROM userplane.SITE_CHAT_MIS WHERE DAYZ BETWEEN '$st_date' AND '$end_date' group by dd";
                $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                $dd=0;
                if($row=mysql_fetch_array($res))
                {
                        do
                        {
                	    $dd=$row['dd']-1;
			$total_day[$dd]["site_ad"]=$row['cnt'];
			$finaltotal["site_ad"]+=$row['cnt'];
                        }while($row=mysql_fetch_array($res));
                }

		$smarty->assign("finaltotal",$finaltotal);
		$smarty->assign("total_day",$total_day);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("year",$year);
		$smarty->assign("month",$month);
		$smarty->assign("wday_arr",$wday_arr);
		$smarty->assign("cid",$cid);
		$smarty->display("chatmis.htm");
	}
	else
	{
		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}

		for($i=0;$i<12;$i++)
                {
                        $mmarr[$i]=$i+1;
                }

                for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}

		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
		$smarty->assign("cid",$cid);
		$smarty->display("chatmis.htm");
	}
}
else
{
	$smarty->display("jsconnectError.tpl");
}
?>
