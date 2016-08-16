<?php
ini_set("max_execution_time","0");

/************************************************************************************************************************
*    FILENAME           : requesthoroscopemis.php
*    INCLUDED           : connect.inc 
*    DESCRIPTION        : MIS for displaying horoscope request count + unique count.
*    Modified BY        : lavesh
***********************************************************************************************************************/
$flag_using_php5=1;
include('connect.inc');
$db = connect_misdb();
                                                                                                                             
$data = authenticated($cid);

$mysqlObj=new Mysql;
                                                                                                                             
if($data)
{
	if($outside)
	{
		list($Year,$Month) = explode("-",date("Y-m"));
		$submit = 1;
		$year_month_wise="month_wise";
	}
	if($submit)
	{
		if($year_month_wise=="month_wise")
		{
			$start_dt = $Year."-".$Month."-01 00:00:00";
			$end_dt = $Year."-".$Month."-31 23:59:59";
			$MIS_FOR = $Month." / ".$Year;
			$smarty->assign("MIS_FOR",$MIS_FOR);

			for($i=1; $i<=31;$i++)
				$ddarr[] = $i;

			for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
			{
				//Sharding
				$myDbName=getActiveServerName($activeServerId,'slave');
				$myDb=$mysqlObj->connect("$myDbName");
				//Sharding
							
				$sql="SELECT COUNT(*) AS CNT,DAYOFMONTH(DATE) AS DAY FROM newjs.HOROSCOPE_REQUEST A, newjs.PROFILEID_SERVER_MAPPING B WHERE DATE BETWEEN '$start_dt' AND '$end_dt' AND A.PROFILEID = B.PROFILEID AND B.SERVERID=$activeServerId GROUP BY DAY";
				$res=$mysqlObj->executeQuery($sql,$myDb);
				while($row=$mysqlObj->fetchArray($res))
				{
					$nod=$row['DAY'] - 1;
					$horoscope_request[$nod]+=$row['CNT'];
					$horoscope_req_total += $row['CNT'];
				}

				$sql="SELECT COUNT(DISTINCT(PROFILEID_REQUEST_BY)) as c1 ,DAYOFMONTH(DATE) AS DAY FROM newjs.HOROSCOPE_REQUEST A, newjs.PROFILEID_SERVER_MAPPING B WHERE DATE BETWEEN '$start_dt' AND '$end_dt'  AND A.PROFILEID_REQUEST_BY = B.PROFILEID AND B.SERVERID=$activeServerId GROUP BY DAY";
				$res=$mysqlObj->executeQuery($sql,$myDb);
				while($row=$mysqlObj->fetchArray($res))
				{
					$nod=$row['DAY'] - 1;
					$unique_rec[$nod]+=$row['c1'];
					$unique_rec_total+= $row['c1'];
				}	
				$sql="SELECT COUNT(DISTINCT(A.PROFILEID)) as c2 ,DAYOFMONTH(DATE) AS DAY FROM newjs.HOROSCOPE_REQUEST A, newjs.PROFILEID_SERVER_MAPPING B WHERE DATE BETWEEN '$start_dt' AND '$end_dt'  AND A.PROFILEID = B.PROFILEID AND B.SERVERID=$activeServerId GROUP BY DAY";
				$res=$mysqlObj->executeQuery($sql,$myDb);
				while($row=$mysqlObj->fetchArray($res))
				{
					$nod=$row['DAY'] - 1;
					$unique_sen[$nod]+=$row['c2'];
					$unique_sen_total += $row['c2'];
				}	
			}
		}
		else
		{
			$start_dt = $Year."-"."01-01 00:00:00";
			$end_dt = $Year."-"."12-31 23:59:59";

			$smarty->assign("MIS_FOR",$year);

			$ddarr = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');

                        for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
                        {
                                //Sharding
                                $myDbName=getActiveServerName($activeServerId,'slave');
                                $myDb=$mysqlObj->connect("$myDbName");
                                //Sharding

				$sql="SELECT COUNT(*) AS CNT, MONTH(DATE) AS MONTH FROM newjs.HOROSCOPE_REQUEST A, newjs.PROFILEID_SERVER_MAPPING B  WHERE DATE BETWEEN '$start_dt' AND '$end_dt' AND A.PROFILEID = B.PROFILEID AND B.SERVERID=$activeServerId GROUP BY MONTH";
				$res=$mysqlObj->executeQuery($sql,$myDb);
				while($row=$mysqlObj->fetchArray($res))
				{
					$moy = $row['MONTH'] - 1;
					$horoscope_request[$moy]+=$row['CNT'];
					$horoscope_req_total += $row['CNT'];
				}

				$sql="SELECT COUNT(DISTINCT(PROFILEID_REQUEST_BY)) as c1 , MONTH(DATE) AS MONTH FROM newjs.HOROSCOPE_REQUEST A, newjs.PROFILEID_SERVER_MAPPING B WHERE DATE BETWEEN '$start_dt' AND '$end_dt' AND A.PROFILEID_REQUEST_BY = B.PROFILEID AND B.SERVERID=$activeServerId GROUP BY MONTH";
				$res=$mysqlObj->executeQuery($sql,$myDb);
				while($row=$mysqlObj->fetchArray($res))
				{
					$moy = $row['MONTH'] - 1;
					$unique_rec[$moy]+=$row['c1'];
					$unique_rec_total += $row['c1'];
				}
				$sql="SELECT COUNT(DISTINCT(A.PROFILEID)) as c2 ,MONTH(DATE) AS MONTH FROM newjs.HOROSCOPE_REQUEST A, newjs.PROFILEID_SERVER_MAPPING B WHERE DATE BETWEEN '$start_dt' AND '$end_dt' AND A.PROFILEID = B.PROFILEID AND B.SERVERID=$activeServerId GROUP BY MONTH";
				$res=$mysqlObj->executeQuery($sql,$myDb);
				while($row=$mysqlObj->fetchArray($res))
				{
					$moy = $row['MONTH'] - 1;
					$unique_sen[$moy]+=$row['c2'];
					$unique_sen_total += $row['c2'];
				}
			}
  
		}
		$smarty->assign("RESULT",1);
	}
	else
	{
		$mmarr = array(
                                array("NAME" => "Jan", "VALUE" => "01"),
                                array("NAME" => "Feb", "VALUE" => "02"),
                                array("NAME" => "Mar", "VALUE" => "03"),
                                array("NAME" => "Apr", "VALUE" => "04"),
                                array("NAME" => "May", "VALUE" => "05"),
                                array("NAME" => "Jun", "VALUE" => "06"),
                                array("NAME" => "Jul", "VALUE" => "07"),
                                array("NAME" => "Aug", "VALUE" => "08"),
                                array("NAME" => "Sep", "VALUE" => "09"),
                                array("NAME" => "Oct", "VALUE" => "10"),
                                array("NAME" => "Nov", "VALUE" => "11"),
                                array("NAME" => "Dec", "VALUE" => "12"),
                                );

		for($i=2007; $i<=date('Y');$i++)
			$yyarr[] = $i;
	}

	$smarty->assign("yyarr",$yyarr);
	$smarty->assign("mmarr",$mmarr);
	$smarty->assign("ddarr",$ddarr);
	$smarty->assign("cid",$cid);
	$smarty->assign("horoscope_request",$horoscope_request);
	$smarty->assign("horoscope_req_total",$horoscope_req_total);
	$smarty->assign("unique_rec",$unique_rec);
	$smarty->assign("unique_rec_total",$unique_rec_total);
	$smarty->assign("unique_sen",$unique_sen);
	$smarty->assign("unique_sen_total",$unique_sen_total);

	$smarty->display("requesthoroscopemis.htm");
}
else
{
        $smarty->assign("user",$user);
        $smarty->display("jsconnectError.tpl");
}




