<?php
ini_set("max_execution_time","0");

/************************************************************************************************************************
*    FILENAME           : horoscope_by_mtongue.php
*    INCLUDED           : connect.inc
*    DESCRIPTION        : MIS for displaying horoscope request count on basis on mtongue
*    CREATED BY         : lavesh
***********************************************************************************************************************/
$flag_using_php5=1;
include('connect.inc');
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
$db=connect_misdb();
$db2=connect_master();
$mysqlObj=new Mysql;

if(authenticated($cid))
{
	if($CMDGo || $outside)
	{
		if($outside)
			list($Year,$Month) = explode("-",date("Y-m"));

	        $start_dt = $Year."-".$Month."-01 00:00:00";
                $end_dt = $Year."-".$Month."-31 23:59:59";

		$MIS_FOR = $Month." / ".$Year;
		$k=0;

		$sql_mtongue = "SELECT DISTINCT(VALUE) FROM newjs.MTONGUE";
		$res_mtongue = mysql_query_decide($sql_mtongue,$db) or die("$sql_mtongue".mysql_error_js());
		while($row_mtongue = mysql_fetch_array($res_mtongue))
		{
			$mtonguearr[] = $row_mtongue['VALUE'];
		}
		$mtongue_cnt = count ($mtonguearr);
		for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
		{
			//Sharding
			$myDbName=getActiveServerName($activeServerId,'slave');
			$myDb=$mysqlObj->connect("$myDbName");
			//Sharding
			
			$sql="SELECT  DISTINCT(A.PROFILEID) FROM newjs.HOROSCOPE_REQUEST A ,newjs.PROFILEID_SERVER_MAPPING B  WHERE A.DATE BETWEEN '$start_dt' AND '$end_dt' AND A.PROFILEID = B.PROFILEID AND B.SERVERID=$activeServerId";
			$res=$mysqlObj->executeQuery($sql,$myDb);
			while($row=$mysqlObj->fetchArray($res))
			{
				$senders.=$row['PROFILEID'].',';
			}
			if($senders)
			{
				$senders_activeServerId[$activeServerId]=rtrim($senders,',');
				unset($senders);
			}
		}
	

		for ($i = 0;$i < $mtongue_cnt;$i++)
		{
			@mysql_ping($db);
			$mtongue=$mtonguearr[$i];
			//$mtongue_val=label_select('MTONGUE',$mtongue);
			//$mtongue_val=$mtongue_val[0];
			$mtongue_val=$MTONGUE_DROP["$mtongue"];

		        $final_array[$k][1]=0;
		        $final_array[$k][0]=0;

			for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
			{
				@mysql_ping($db);
				//Sharding
				$myDbName=getActiveServerName($activeServerId,'slave');
				$myDb=$mysqlObj->connect("$myDbName");
				//Sharding
			
				$sharding_server_pids=$senders_activeServerId[$activeServerId];	
				if($sharding_server_pids)
				{
					$sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE MTONGUE=$mtongue AND PROFILEID IN ($sharding_server_pids)";		
					$res = mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
					while($row=mysql_fetch_array($res))
					{
						$senders.=$row['PROFILEID'].',';
					}
				
					if($senders)
					{
						$senders=rtrim($senders,',');

						$sql="SELECT PROFILEID_REQUEST_BY FROM newjs.HOROSCOPE_REQUEST WHERE PROFILEID in ($senders) AND DATE BETWEEN '$start_dt' AND '$end_dt'";

						$res=$mysqlObj->executeQuery($sql,$myDb);
						while($row=$mysqlObj->fetchArray($res))
						{
							$receivers.=$row['PROFILEID_REQUEST_BY'].',';
						}
						if($receivers)
						{
							$receivers.=rtrim($receivers,',');

							$sql="SELECT COUNT(*) AS CNT FROM newjs.JPROFILE WHERE  PROFILEID in ($receivers) AND MTONGUE= $mtongue ";
							$res = mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
							if($row=mysql_fetch_array($res))
							{
			                                        $final_array[$k][0]+=$row["CNT"];
                        			                $total_array[0]+=$row["CNT"];
							}

							$sql="SELECT COUNT(*) AS CNT FROM newjs.JPROFILE WHERE  PROFILEID in ($receivers) AND MTONGUE<>$mtongue ";
							$res = mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
							if($row=mysql_fetch_array($res))
							{
			                                        $final_array[$k][1]+=$row["CNT"];
                        			                $total_array[1]+=$row["CNT"];
							}	
						}	
					}			
					$used_mtongue[$k]=$mtongue_val;
					unset($senders);
					unset($receivers);
				}
			}
		        $k++;
			unset($senders);
		}

		$ddarr=array("SAME MTONGUE COUNT","DIFFERENT MTONGUE COUNT");
		$smarty->assign("used_mtongue",$used_mtongue);
		$smarty->assign("total_array",$total_array);
		$smarty->assign("final_array",$final_array);
		$smarty->assign("RESULT",1);
	}
	else
	{
		for($i=0;$i<12;$i++)
		{
			$mmarr[$i]=$i+1;
		}
															     
		for($i=0;$i<10;$i++)
		{
			$yyarr[$i]=$i+2007;
		}
	}

	$smarty->assign("yyarr",$yyarr);
	$smarty->assign("mmarr",$mmarr);
	$smarty->assign("ddarr",$ddarr);
	$smarty->assign("cid",$cid);
	$smarty->assign("horoscope_request",$horoscope_request);
	$smarty->assign("horoscope_req_total",$horoscope_req_total);
	$smarty->display("horoscope_by_mtongue.htm");
}
else
{
        $smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}

?>
