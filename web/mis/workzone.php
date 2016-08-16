<?php
/**
*	FILENAME	:	workzone.php
*	DESCRIPTION	:	Displays screened profiles stats
*	MODIFIED BY	:	Tripti Singh
*	MODIFY DATE	:	7th July, 2006
**/
include("connect.inc");
$db=connect_misdb();
$data=authenticated($checksum);                                                                                                
if(isset($data))
{
	if($SUBMIT)
	{
		$zone_arr=array('TOTAL','COMFORT','RUSH','REDALERT','EXPIRED');
		$smarty->assign("zone_arr",$zone_arr);	
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

		if($perspective=='U')
			$criteria="CONVERT_TZ(RECEIVE_TIME,TIME_ZONE,'IST')";
		elseif($perspective=='O')
			$criteria="CONVERT_TZ(ALLOT_TIME,TIME_ZONE,'IST')";
		
		$res=mysql_query_decide($sql,$db) or die($sql.mysql_error_js());
		$num_of_operators=mysql_num_rows($res);	
                if($row=mysql_fetch_assoc($res))	//Select all users of screening
                {	
                        do
                        {
                                //$privilage=$row['PRIVILAGE'];
                                //$priv=explode("+",$privilage);
				$operators[]=$row['USERNAME'];
			}while($row=mysql_fetch_assoc($res));
                }
		$table="jsadmin.MAIN_ADMIN_LOG";	//Data is to be analyzed from this table
		$operators[]="Total";
		//For displaying total profiles screened in a day
		if($type=='M')
		{
			$mflag = 1; 	// mflag tells us that user has selected month view
			for ($i = 0; $i < 12; $i++)
				$mmarr[$i] = $i + 1; // Array to store months.
			$mmarr[$i] = "Total"; 	// extra index for Total screened profiles
			
			$t1 = $criteria;
			$t2 = "DATE_SUB(CONVERT_TZ(SUBMIT_TIME,TIME_ZONE,'IST'),INTERVAL 10 HOUR)";
			$t3 = "DATE_SUB(CONVERT_TZ(SUBMIT_TIME,TIME_ZONE,'IST'),INTERVAL 8 HOUR)";
			$t4 = "CONVERT_TZ(SUBMIT_TIME,TIME_ZONE,'IST')";
				//Count no. of screened profiles of a user in a particular month
			$sql="SELECT COUNT(*) as cnt,ALLOTED_TO, MONTH(CONVERT_TZ(ALLOT_TIME,TIME_ZONE,'IST')) as dd, if(CONVERT_TZ(SUBMITED_TIME,TIME_ZONE,'IST') BETWEEN $t1 AND $t2,1,if(CONVERT_TZ(SUBMITED_TIME,TIME_ZONE,'IST') BETWEEN $t2 AND $t3,2,if(CONVERT_TZ(SUBMITED_TIME,TIME_ZONE,'IST') BETWEEN $t3 AND $t4,3,4))) as time_zone FROM $table WHERE CONVERT_TZ(ALLOT_TIME,TIME_ZONE,'IST') BETWEEN '$myear-01-01 00:00:00' AND '$myear-12-31 23:59:59' AND SCREENING_TYPE='$stype' GROUP BY ALLOTED_TO,dd,time_zone";
				//$sql="SELECT COUNT(*) as cnt, ALLOTED_TO, MONTH(SUBMITED_TIME) as dd FROM $table WHERE SUBMITED_TIME BETWEEN $start_time AND $end_time AND ALLOT_TIME BETWEEN '$myear-01-01 00:00:00' AND '$myear-12-31 23:59:59' AND SCREENING_TYPE='$stype' GROUP BY ALLOTED_TO,dd";
			$res = mysql_query_decide($sql,$db) or die($sql.mysql_error_js());
			if($row = mysql_fetch_array($res))
                        {
		               	do
                        	{
					$dd = $row['dd']-1;
					$time=$row['time_zone'];
	                		$j = array_search($row['ALLOTED_TO'],$operators);
					if ($j !== FALSE)
					{
						//Save properties in an array zone wise
						$cnt[$time][$j][$dd] = $row['cnt'];
						$cnt[$time][$j][12] += $row['cnt'];
						$cnt[$time][$num_of_operators][$dd] += $row['cnt'];
						$cnt[$time][$num_of_operators][12] += $row['cnt'];
						$cnt[0][$j][$dd]+= $row['cnt'];
						$cnt[0][$j][12] += $row['cnt'];
						$cnt[0][$num_of_operators][$dd] += $row['cnt'];
						$cnt[0][$num_of_operators][12] += $row['cnt'];
					}
				}while($row = mysql_fetch_array($res));
			}
			//}	
		}// end of $type=='M'
		elseif($type=='D')
		{
			$dflag=1;
                        for($i=0;$i<31;$i++)
               	                $ddarr[$i]=$i+1;
			$ddarr[$i]="Total";
			if($dmonth<10)
				$dmonth="0".$dmonth;
			$t1 = $criteria;
			$t2 = "DATE_SUB(CONVERT_TZ(SUBMIT_TIME,TIME_ZONE,'IST'),INTERVAL 10 HOUR)";
			$t3 = "DATE_SUB(CONVERT_TZ(SUBMIT_TIME,TIME_ZONE,'IST'),INTERVAL 8 HOUR)";
			$t4 = "CONVERT_TZ(SUBMIT_TIME,TIME_ZONE,'IST')";
			//Count no. of screened profiles of a user in a particular month
			$sql="SELECT COUNT(*) as cnt,ALLOTED_TO, dayofmonth(CONVERT_TZ(ALLOT_TIME,TIME_ZONE,'IST')) as dd, if(CONVERT_TZ(SUBMITED_TIME,TIME_ZONE,'IST') BETWEEN $t1 AND $t2,1,if(CONVERT_TZ(SUBMITED_TIME,TIME_ZONE,'IST') BETWEEN $t2 AND $t3,2,if(CONVERT_TZ(SUBMITED_TIME,TIME_ZONE,'IST') BETWEEN $t3 AND $t4,3,4))) as time_zone FROM $table WHERE CONVERT_TZ(ALLOT_TIME,TIME_ZONE,'IST') BETWEEN '$dyear-$dmonth-01 00:00:00' AND '$dyear-$dmonth-31 23:59:59' AND SCREENING_TYPE='$stype' GROUP BY ALLOTED_TO,dd,time_zone";
			
			//$sql="SELECT COUNT(*) as cnt,ALLOTED_TO,dayofmonth(ALLOT_TIME) as dd FROM $table WHERE SUBMITED_TIME BETWEEN $start_time AND $end_time AND ALLOT_TIME BETWEEN '$dyear-$dmonth-01 00:00:00' AND '$dyear-$dmonth-31 23:59:59' AND SCREENING_TYPE='$stype' GROUP BY ALLOTED_TO,dd ";
       	                $res=mysql_query_decide($sql,$db) or die($sql.mysql_error_js());
               	        if($row=mysql_fetch_array($res))
                       	{
	                 	do
				{
                                       	$dd = $row['dd']-1;
					$time=$row['time_zone'];
					$j = array_search($row['ALLOTED_TO'],$operators);
					if ($j !== FALSE)
					{	
						//Save properties in an array zone wise
						$cnt[$time][$j][$dd] = $row['cnt'];
						$cnt[$time][$j][31] += $row['cnt'];
						$cnt[$time][$num_of_operators][$dd] += $row['cnt'];
						$cnt[$time][$num_of_operators][31] += $row['cnt'];
						$cnt[0][$j][$dd]+= $row['cnt'];
						$cnt[0][$j][31] += $row['cnt'];
						$cnt[0][$num_of_operators][$dd] += $row['cnt'];
						$cnt[0][$num_of_operators][31] += $row['cnt'];
					}
				}while($row = mysql_fetch_array($res));
			}
		}
        	$smarty->assign("cnt",$cnt);
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
                                                                                                
        	$smarty->display("workzone.htm");
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
                $smarty->display("workzone.htm");
        }
}
else
{
        $smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}
?>
