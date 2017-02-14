<?php
/***********************************************************************************************************************
* FILE NAME     : sms_mis.php
* DESCRIPTION   : Displays SMS associated MIS. 
* INCLUDES      : connect.inc
* CREATION DATE : 19 may 2006
* CREATED BY    : Lavesh Rawat
************************************************************************************************************************/

include_once("connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
$mysql=new Mysql;

$db=connect_misdb();

if(authenticated($cid) || $JSIndicator==1)
{
	if($outside)
        {
                $CMDGo='Y';
		if(!$today)				
	                $today=date("Y-m-d");
                list($year,$month,$d)=explode("-",$today);
        }

	if($CMDGo)
	{
		$smarty->assign("flag",1);
		$mmarr=array('Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar');
		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}

		$st_date=$year."-".$month."-01"." 00:00:00";
		$end_date=$year."-".$month."-31"." 23:59:59";

		//SMS SEARCHLOG count MIS.

		$sql="SELECT DAYOFMONTH(ENTRY_DT) as dd,SOURCE from newjs.SMS_SEARCHLOG WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date'";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		while($row=mysql_fetch_array($res))
		{
			$source=$row['SOURCE'];
			$dd=$row['dd']-1;
			if($source=='WIB')
				$cnt[0][$dd]+=1;
			elseif($source=='WAP')
				$cnt[1][$dd]+=1;
			elseif($source=='SMS')
				$cnt[2][$dd]+=1;	
			else
				$cnt[3][$dd]+=1;
		}

		if($cnt[0])
			$tot[0]=array_sum($cnt[0]);
		if($cnt[1])	
			$tot[1]=array_sum($cnt[1]);
		if($cnt[2])
			$tot[2]=array_sum($cnt[2]);
		if($cnt[3])
                        $tot[3]=array_sum($cnt[3]);

		$tot[4]=$tot[0]+$tot[1]+$tot[2]+$tot[3];
		
                for($k=0;$k<31;$k++)
		{
			$col[$k]=$cnt[0][$k]+$cnt[1][$k]+$cnt[2][$k]+$cnt[3][$k];                                                                                                            
		}	

 
		//SMS CHECKSTATUS Count MIS.
		$sql="SELECT count(*) as cnt,DAYOFMONTH(ENTRY_DT) as dd1 from newjs.SMS_CHECKSTATUS_LOG WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' GROUP BY dd1";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));

                if($row=mysql_fetch_array($res))
                {
			do
                        {
				$dd1=$row['dd1']-1;
				$cnt1[$dd1]=$row['cnt'];
				$tot1+=$row['cnt'];
			}while($row=mysql_fetch_array($res));
		}

		//SMS CONTACT_LOG Count MIS.(Initial Contact)
		$sql="SELECT count(*) as NUM,DAYOFMONTH(CONTACT_DT) as dd2 from newjs.SMS_CONTACT_LOG WHERE CONTACT_DT BETWEEN '$st_date' AND '$end_date' AND TYPE='I' GROUP BY dd2";
                $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));

                if($row=mysql_fetch_array($res))
                {
			do
			{
				$dd2=$row['dd2']-1;
				$cnt2[$dd2]=$row['NUM'];
				$tot2+=$row['NUM'];
			}while($row=mysql_fetch_array($res));
		}

		//SMS CONTACT_LOG Count MIS.(Contact Accepted)
		$sql="SELECT count(*) as NUM,DAYOFMONTH(CONTACT_DT) as dd3 from newjs.SMS_CONTACT_LOG WHERE CONTACT_DT BETWEEN '$st_date' AND '$end_date' AND TYPE='A' GROUP BY dd3";
                $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));

	        if($row=mysql_fetch_array($res))
                {
                        do
                        {
                                $dd3=$row['dd3']-1;
                                $cnt3[$dd3]=$row['NUM'];
                                $tot3+=$row['NUM'];
                        }while($row=mysql_fetch_array($res));
                }

		//Chat Invitation Accepted.
		$sql="SELECT count(*) as NUM,DAYOFMONTH(TIME) as dd4 from newjs.CHAT_INVITATION WHERE TIME BETWEEN '$st_date' AND '$end_date' AND ACCEPTED='Y' GROUP BY dd4";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                if($row=mysql_fetch_array($res))
                {
                        do
                        {
                                $dd4=$row['dd4']-1;
                                $cnt4[$dd4]=$row['NUM'];
                                $tot4+=$row['NUM'];
                        }while($row=mysql_fetch_array($res));
                }

		//Chat Invitation opem
		$sql="SELECT count(*) as NUM,DAYOFMONTH(TIME) as dd5 from newjs.CHAT_INVITATION WHERE TIME BETWEEN '$st_date' AND '$end_date' AND ACCEPTED='N' GROUP BY dd5";
                $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                if($row=mysql_fetch_array($res))
                {
                        do
                        {
                                $dd5=$row['dd5']-1;
                                $cnt5[$dd5]=$row['NUM'];
                                $tot5+=$row['NUM'];
                        }while($row=mysql_fetch_array($res));
                }
	
		//Getting the connection on all slave sharded servers.
		for($i=0;$i<$noOfShardedServers;$i++)
                {
			$myDbName=$slave_activeServers[$i];
			$myDbArray[$myDbName]=$mysql->connect("$myDbName");
		}
			
		//Initial Request by mobile, Accepted By logging
		$sql="SELECT DISTINCT SENDER,RECEIVER FROM newjs.SMS_CONTACT_LOG WHERE TYPE='I'";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));

		while($row=mysql_fetch_array($res))
		{
			$sen=$row['SENDER'];
			$rec=$row['RECEIVER'];
			
			//case when sender is accepted by sms should not be considered.
			$sql1="SELECT count(*) as cnt from newjs.SMS_CONTACT_LOG WHERE SENDER='$rec' AND RECEIVER='$sen' AND TYPE='A'";	
			$res1=mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js($db));
			$row1=mysql_fetch_array($res1);
			$ignore=$row1['cnt'];

			if(!$ignore)
			{
				$myDbName=getProfileDatabaseConnectionName($sen,'slave',$mysql);
				$myDb=$myDbArray[$myDbName];
				$sql2="SELECT DAYOFMONTH(TIME) as dd6 from newjs.CONTACTS WHERE SENDER='$sen' AND RECEIVER='$rec' AND TYPE='A' AND TIME BETWEEN '$st_date' AND '$end_date'";
				$res2=$mysql->executeQuery($sql2,$myDb) or die("$sql2".mysql_error_js($myDb));

				if($row2=mysql_fetch_array($res2))
				{
					$dd6=$row2['dd6']-1;
					$cnt6[$dd6]+=1;
					$tot6+=1;
				} 
			}
		}

		$smarty->assign("col",$col);
		$smarty->assign("cnt",$cnt);
                $smarty->assign("tot",$tot);
		$smarty->assign("cnt1",$cnt1);
		$smarty->assign("cnt2",$cnt2);
		$smarty->assign("cnt3",$cnt3);
		$smarty->assign("cnt4",$cnt4);
		$smarty->assign("cnt5",$cnt5);
		$smarty->assign("cnt6",$cnt6);
		//if(($tot2+$tot3)>0)
	                //$smarty->assign("final_tot",$tot2+$tot3);
		$smarty->assign("tot1",$tot1);
		$smarty->assign("tot2",$tot2);
		$smarty->assign("tot3",$tot3);
		$smarty->assign("tot4",$tot4);
		$smarty->assign("tot5",$tot5);
		$smarty->assign("tot6",$tot6);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("year",$year);
		$smarty->assign("month",$month);
		$smarty->assign("cid",$cid);
                $smarty->display("sms_mis.htm");
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
		$smarty->assign("cid",$cid);
		$smarty->display("sms_mis.htm");
	}
}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}
?>
