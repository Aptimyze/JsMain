<?php

include("connect.inc");

$db=connect_misdb();	
$db2=connect_master();

include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");

$mysqlObj=new Mysql;

if(authenticated($cid))
{
	if($CMDGo)
	{
		$smarty->assign("flag","1");
		$typearr=array("Initial","Accepted","Declined");
		if($dt_type=="D")
		{
			unset($cnt);
			unset($tota);
			unset($totb);
			$smarty->assign("dflag",1);
			$smarty->assign("dt","$ddate_mon-$ddate_yyyy");

			for($i=0;$i<31;$i++)
			{
				$ddarr[$i]=$i+1;
			}
			for($i=0;$i<$noOfActiveServers;$i++)
			{
			        $myDbName=$slave_activeServers[$i];
			        $myDb=$mysqlObj->connect("$myDbName");

				$sql="SELECT COUNT(*) as cnt,TYPE,DAYOFMONTH(TIME) as dd FROM newjs.CONTACTS as ct,newjs.PROFILEID_SERVER_MAPPING as psm WHERE ct.SENDER=psm.PROFILEID and TIME BETWEEN '$ddate_yyyy-$ddate_mon-01 00:00:00' AND '$ddate_yyyy-$ddate_mon-31 23:59:59' and psm.SERVERID=$i GROUP BY TYPE,dd";
				$res=$mysqlObj->executeQuery($sql,$myDb) or die(mysql_error_js($myDb));
				if($row=mysql_fetch_array($res))
				{
					do
					{
						$type=$row['TYPE'];
						if($type=='I')
							$i=0;
						if($type=='A')
							$i=1;
						if($type=='D')
							$i=2;
						$counter=$row['cnt'];
						$dd=$row['dd']-1;
						$cnt[$i][$dd]=$counter;
						$cnt1[$dd][$i]=$counter;
						$tota[$i]+=$cnt[$i][$dd];
						$totb[$dd]+=$cnt1[$dd][$i];
					}while($row=mysql_fetch_array($res));
				}
			}
		}
		elseif($dt_type=="M")
		{
			unset($cnt);
			unset($tota);
			unset($totb);
			$smarty->assign("mflag",1);
			$mdate_yyyyp1=$mdate_yyyy+1;
			$smarty->assign("dt",$mdate_yyyy);
			$smarty->assign("dt1",$mdate_yyyyp1);

			$mmarr=array('Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar');
			
			for($i=0;$i<$noOfActiveServers;$i++)
                        {
                                $myDbName=$slave_activeServers[$i];
                                $myDb=$mysqlObj->connect("$myDbName");


				$sql="SELECT COUNT(*) as cnt,TYPE,MONTH(TIME) as mm FROM newjs.CONTACTS  as ct,newjs.PROFILEID_SERVER_MAPPING as psm WHERE ct.SENDER=psm.PROFILEID and TIME BETWEEN '$mdate_yyyy-04-01 00:00:00' AND '$mdate_yyyyp1-03-31 23:59:59'  and psm.SERVERID=$i  GROUP BY TYPE,mm";
				$res=$mysqlObj->executeQuery($sql,$myDb) or die(mysql_error_js($myDb));
				if($row=mysql_fetch_array($res))
				{
					do
					{
						$type=$row['TYPE'];
						$counter=$row['cnt'];
						if($type=='I')
							$i=0;
						if($type=='A')
							$i=1;
						if($type=='D')
							$i=2;
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
						$cnt1[$mm][$i]=$counter;
						$tota[$i]+=$cnt[$i][$mm];
						$totb[$mm]+=$cnt1[$mm][$i];
					}while($row=mysql_fetch_array($res));
				}
			}
		}

		$smarty->assign("cnt",$cnt);
		$smarty->assign("tota",$tota);
		$smarty->assign("totb",$totb);

		$smarty->assign("typearr",$typearr);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("ddarr",$ddarr);

		$smarty->assign("cid",$cid);
		$smarty->display("contactsmisnew.htm");
	}
	else
	{
		$smarty->assign("flag","0");
		for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}
		for($i=0;$i<12;$i++)
		{
			$mmarr[$i]=$i+1;
		}

		$smarty->assign("cid",$cid);
		$smarty->assign("yyarr",$yyarr);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("mmarr",$mmarr);
		$smarty->display("contactsmisnew.htm");
	}
}
else
{
	$smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}
?>
