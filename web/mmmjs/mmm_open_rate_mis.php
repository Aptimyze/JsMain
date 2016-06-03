<?php
include("connect.inc");

//$db=connect_db();

/**** THIS ROUTINE WILL CHECK YOUR AUTHENTICATION AND IF YOUR "cid" HAS EXPIRED THEN IT WILL REDIRECT TO LOGIN PAGE**********************/
                                                                                                 
$ip = getenv('REMOTE_ADDR');
if(authenticated($cid))
{
        $auth=1;
        $un = getuser($cid,$ip);
        $tm=getIST();
        //setcookie ("cid", $cid,$tm+3600);
}
if(!$auth)
{
        $smarty->display("mmm_relogin.htm");
        die;
}
                                                                                                 
$smarty->assign("cid",$cid);
                                                                                                 
/****************************AUTHENTICATION ROUTINE ENDS HERE*********************************/


if($CMDGo)
{
	if($dt_type=='mnt')
	{
		$select="SUM(OPEN_COUNT)";
		$st_date=$myy."-01-01";
		$end_date=$myy."-12-31";
		$sql1="MONTH(DATE)";
		for($i=0;$i<12;$i++)
		{
			$ddarr[$i]=$i+1;
		}
		$smarty->assign("ddarr",$ddarr);
		$flag=1;
	}
	elseif($dt_type=='day')
	{
		$select="OPEN_COUNT";
		$st_date=$dyy."-".$dmm."-01";
		$end_date=$dyy."-".$dmm."-31";
		$sql1="DAYOFMONTH(DATE)";
		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}
		$smarty->assign("ddarr",$ddarr);
		$flag=1;
	}
	elseif($dt_type=='dayrange')
	{
		$select="OPEN_COUNT";
		$st_date=$dyy1."-".$dmm1."-".$ddd1;
		$end_date=$dyy2."-".$dmm2."-".$ddd2;
		$sql1="DATE";
		$stamp1=mktime(0,0,0,$dmm1,$ddd1,$dyy1);
		$stamp2=mktime(0,0,0,$dmm2,$ddd2,$dyy2);
		if($stamp1>$stamp2)
		{
			$temp=$st_date;
			$st_date=$end_date;
			$end_date=$temp;
			$temp=$stamp1;
			$stamp1=$stamp2;
			$stamp2=$temp;
		}
		$i=0;
		while($stamp1<=$stamp2)
		{
			unset($date);
			unset($day);
			unset($month);
			unset($year);
			$date=date("Y-m-d",$stamp1);
			list($year,$month,$day)=explode("-",$date);
			$stamp1=mktime(0,0,0,$month,$day+1,$year);
			$ddarr[$i]=$date;
			$i++;	
		}
		$smarty->assign("ddarr",$ddarr);
		$flag=1;
	}
	if($flag)
	{
		$k=0;
		$i=0;
		$sql="SELECT $select as cnt,MAILER_ID,$sql1 as dd FROM MAIL_UNSUBSCRIBE WHERE DATE BETWEEN '$st_date' AND '$end_date' GROUP BY dd,MAILER_ID";
		$res=mysql_query($sql) or die("$sql \n".mysql_error());
		while($row=mysql_fetch_array($res))
		{
			$dd=array_search($row['dd'],$ddarr);
			if(is_array($mailer_idarr))
			{
				if(!in_array($row['MAILER_ID'],$mailer_idarr))
				{
					$mailer_idarr[$k]=$row['MAILER_ID'];
					$mailer_arr[$k]=getmailername($row['MAILER_ID']);
					$k++;
				}
			}
			else
			{
				$mailer_idarr[$k]=$row['MAILER_ID'];
				$mailer_arr[$k]=getmailername($row['MAILER_ID']);
				$k++;
			}

			$i=array_search($row['MAILER_ID'],$mailer_idarr);
			$cnt[$i][$dd]+=$row['cnt'];
			$tota[$i]+=$row['cnt'];
			$totb[$dd]+=$row['cnt'];
			$totall+=$row['cnt'];
		}
		$sql="SELECT SUM(SENT) as cnt,MAILER_ID FROM MAIL_SENT WHERE DATE BETWEEN '$st_date' AND '$end_date' GROUP BY MAILER_ID";
		$res=mysql_query($sql) or die("$sql \n".mysql_error());
		while($row=mysql_fetch_array($res))
		{
			$i=array_search($row['MAILER_ID'],$mailer_idarr);
			$senttot[$i]=$row['cnt'];
		}

		$smarty->assign("cnt",$cnt);
		$smarty->assign("tota",$tota);
		$smarty->assign("totb",$totb);
		$smarty->assign("totall",$totall);
		$smarty->assign("senttot",$senttot);
		$smarty->assign("mailer_arr",$mailer_arr);
		$smarty->assign("mailer_idarr",$mailer_idarr);
		$smarty->assign("flag",$flag);
	}
	else
	{
		for($i=0;$i<12;$i++)
		{
			$mmarr[$i]=$i+1;
		}
		for($i=0;$i<10;$i++)
		{
			$yyarr[$i]=$i+2005;
		}
		for($i=0;$i<=30;$i++)        {
			$ddarr[$i]=$i+1;
		}

		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yyarr",$yyarr);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("NOMODE",1);
	}
	$smarty->display("mmm_open_rate_mis.htm");
}
else
{
	for($i=0;$i<12;$i++)
	{
		$mmarr[$i]=$i+1;
	}
	for($i=0;$i<10;$i++)
	{
		$yyarr[$i]=$i+2005;
	}
	for($i=0;$i<=30;$i++)
	{
		$ddarr[$i]=$i+1;
	}

	$smarty->assign("mmarr",$mmarr);
	$smarty->assign("yyarr",$yyarr);
	$smarty->assign("ddarr",$ddarr);
	$smarty->display("mmm_open_rate_mis.htm");
}
?>
