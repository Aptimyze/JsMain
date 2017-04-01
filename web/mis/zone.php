<?php
include("connect.inc");
$db=connect_misdb();
                                                                                                 
$data=authenticated($checksum);
                                                                                                 
if(isset($data))
{
	if($SUBMIT)
	{
		$zone_arr=array('COMFORT','RUSH','REDALERT','EXPIRED');
		$smarty->assign("zone_arr",$zone_arr);	
		$flag=1;
                if($user_type=='N')
                {
                        $sql="SELECT USERNAME,PRIVILAGE FROM jsadmin.PSWRDS WHERE PRIVILAGE LIKE
'%NU%'";
                        $stype="O";
                }
                elseif($user_type=='P')
                {
                        $sql="SELECT USERNAME,PRIVILAGE FROM jsadmin.PSWRDS WHERE PRIVILAGE LIKE
'%PU%'";
                        $stype="P";
                }
                $res=mysql_query_decide($sql,$db) or die(mysql_error_js());
                if($row=mysql_fetch_array($res))
                {
                        do
                        {
                                $privilage=$row['PRIVILAGE'];
                                $priv=explode("+",$privilage);
                                if(0)//count($priv)>2)
                                {
                                }
                                else
                                {
                                        $operators[]=$row['USERNAME'];
                                }
                        }while($row=mysql_fetch_array($res));
                }

		if($type=='M')
		{

		}
		elseif($type=='D')
		{
			$dflag=1;
                        for($i=0;$i<31;$i++)
       	                {
               	                $ddarr[$i]=$i+1;
                       	}
			for($i=0;$i<=3;$i++)
			{
				if($i==0)
				{
					$start_time='ALLOT_TIME';
					$end_time='DATE_SUB(SUBMIT_TIME,INTERVAL 6 HOUR)';
				}
				if($i==1)
                                {
                                        $start_time='DATE_SUB(SUBMIT_TIME,INTERVAL 6 HOUR)';
                                        $end_time='DATE_SUB(SUBMIT_TIME,INTERVAL 3 HOUR)';
                                }
				if($i==2)
                                {
                                        $start_time='DATE_SUB(SUBMIT_TIME,INTERVAL 3 HOUR)';
                                        $end_time='SUBMIT_TIME';
                                }
				if($i==3)
                                {
                                        $start_time='SUBMIT_TIME';
                                        $end_time='now()';
                                }
				$sql="SELECT COUNT(*) as cnt,ALLOTED_TO,dayofmonth(left(SUBMITED_TIME,10)) as dd FROM jsadmin.MAIN_ADMIN_LOG WHERE SUBMITED_TIME BETWEEN $start_time AND $end_time AND ALLOT_TIME BETWEEN '$dyear-$dmonth-01 00:00:00' AND '$dyear-$dmonth-31 23:59:59'   AND SCREENING_TYPE='$stype' GROUP BY ALLOTED_TO,dd ";
        	                $res=mysql_query_decide($sql,$db) or die(mysql_error_js());
                	        if($row=mysql_fetch_array($res))
                        	{
                                	do
                               		{
                                        	$dd=$row['dd']-1;
	                                        $j=array_search($row['ALLOTED_TO'],$operators);
        	                                $cnt[$i][$j][$dd]=$row['cnt'];
                	                }while($row=mysql_fetch_array($res));
				}
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
                                                                                                
        	$smarty->display("zone.htm");
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
                $smarty->display("zone.htm");
        }
}
else
{
        $smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}
?>
