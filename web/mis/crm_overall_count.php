<?php

include("connect.inc");
$db=connect_misdb();
$db2=connect_master();

if(authenticated($cid))
{
	if($CMDGo)
	{
		$smarty->assign("flag","1");

		for($i=0;$i<31;$i++)
                {
                        $ddarr[$i]=$i+1;
                }

		$st_date=$year."-".$month."-01 00:00:00";
		$end_date=$year."-".$month."-31 23:59:59";

		$sql="SELECT USERNAME,PRIVILAGE,CENTER FROM jsadmin.PSWRDS ";
		if($branch)
			$sql.=" WHERE UPPER(CENTER)=UPPER('$branch')";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			$brc_cnt=0;
			do
			{
				$center=strtoupper($row['CENTER']);;

				if(is_array($brancharr))
				{
					if(!in_array($center,$brancharr))
					{
						$brancharr[$brc_cnt++]=$center;
					}
				}
				else
				{
					$brancharr[$brc_cnt++]=$center;
				}

				$i=array_search($center,$brancharr);
				$privilage=$row['PRIVILAGE'];
				$priv=explode("+",$privilage);
				if(in_array('IUO',$priv) || in_array('IUW',$priv) || in_array('IUI',$priv))
				{
					if(is_array($opsarr[$i]))
					{
						if(!in_array($row['USERNAME'],$opsarr[$i]))
						{
							$opsarr[$i][]=$row['USERNAME'];
						}
					}
					else
					{
						$opsarr[$i][]=$row['USERNAME'];
					}
				}
			}while($row=mysql_fetch_array($res));
		}

		mysql_free_result($res);

		for($i=0;$i<count($brancharr);$i++)
		{
			if($opsarr[$i])
				$opsstr="'".implode("','",$opsarr[$i])."'";

			$sql="SELECT DISTINCT PROFILEID , ALLOTED_TO,DAYOFMONTH(CONVINCE_TIME) as dd FROM incentive.MAIN_ADMIN WHERE CONVINCE_TIME BETWEEN '$st_date' AND '$end_date' AND ALLOTED_TO IN ($opsstr) AND WILL_PAY='Y' AND STATUS='F'";
			if($mode)
				$sql.=" AND MODE='$mode'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			if($row=mysql_fetch_array($res))
			{
				do
				{
					$profileid=$row['PROFILEID'];
					$alloted_to=$row['ALLOTED_TO'];
					$str=$profileid.",".$alloted_to;
					$dd=$row['dd']-1;
					$j=array_search($alloted_to,$opsarr[$i]);
					if(is_array($clproarr))
					{
						if(!in_array($str,$clproarr))
						{
							$clproarr[]=$str;
							$cnt[$i][$j][$dd]["cl"]+=1;//$row['cnt'];
							$tot[$i][$j]["cl"]+=1;//$row['cnt'];
						}
					}
					else
					{
						$clproarr[]=$str;
						$cnt[$i][$j][$dd]["cl"]+=1;//$row['cnt'];
						$tot[$i][$j]["cl"]+=1;//$row['cnt'];
					}
	//				$cnt[$i][$dd]["cl"]+=1;//$row['cnt'];
	//				$tot[$i]["cl"]+=1;//$row['cnt'];
				}while($row=mysql_fetch_array($res));
			}

			mysql_free_result($res);

			$sql="SELECT DISTINCT PROFILEID, ENTRYBY,DAYOFMONTH(CONVINCE_TIME) as dd FROM incentive.CLAIM WHERE CONVINCE_TIME BETWEEN '$st_date' AND '$end_date' AND ENTRYBY IN ($opsstr) AND WILL_PAY='Y'";
			if($mode)
				$sql.=" AND MODE='$mode'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			if($row=mysql_fetch_array($res))
			{
				do
				{
					$profileid=$row['PROFILEID'];
					$alloted_to=$row['ENTRYBY'];
					$str=$profileid.",".$alloted_to;
					$dd=$row['dd']-1;
					$j=array_search($alloted_to,$opsarr[$i]);
					if(is_array($clproarr))
					{
						if(!in_array($str,$clproarr))
						{
							$clproarr[]=$str;
							$cnt[$i][$j][$dd]["cl"]+=1;//$row['cnt'];
							$tot[$i][$j]["cl"]+=1;//$row['cnt'];
						}
					}
					else
					{
						$clproarr[]=$str;
						$cnt[$i][$j][$dd]["cl"]+=1;//$row['cnt'];
						$tot[$i][$j]["cl"]+=1;//$row['cnt'];
					}
	//				$cnt[$i][$dd]["cl"]+=$row['cnt'];
	//				$tot[$i]["cl"]+=$row['cnt'];
				}while($row=mysql_fetch_array($res));
			}

			mysql_free_result($res);

	//		$sql="SELECT COUNT(DISTINCT b.PROFILEID) AS cnt,i.ENTRYBY,DAYOFMONTH(ENTRY_DT) as dd FROM billing.PAYMENT_DETAIL b,incentive.CLAIM i WHERE i.CONVINCE_TIME<=DATE_ADD(b.ENTRY_DT,INTERVAL 1 DAY) AND WILL_PAY='Y'AND CONVINCE_TIME BETWEEN '$st_date' AND '$end_date' AND i.CONVINCE_TIME!=0 AND i.ENTRYBY IN ($noidaopsstr) AND i.PROFILEID=b.PROFILEID GROUP BY ENTRYBY,dd";
			$sql="SELECT DISTINCT b.PROFILEID,b.BILLID,i.ENTRYBY,DAYOFMONTH(i.CONVINCE_TIME) as dd FROM billing.PAYMENT_DETAIL b,incentive.CLAIM i WHERE i.CONVINCE_TIME<=DATE_ADD(b.ENTRY_DT,INTERVAL 1 DAY) AND WILL_PAY='Y'AND CONVINCE_TIME BETWEEN '$st_date' AND '$end_date' AND i.CONVINCE_TIME!=0 AND i.ENTRYBY IN ($opsstr) AND b.ENTRY_DT>=DATE_SUB(CURDATE(),INTERVAL 30 DAY) AND i.PROFILEID=b.PROFILEID";
			if($mode)
				$sql.=" AND i.MODE='$mode'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			if($row=mysql_fetch_array($res))
			{
				do
				{
					$profileid=$row['PROFILEID'];
					$billid=$row['BILLID'];
					$valid_id = 0;
			                $valid_id = check_validity($billid);
			                if($valid_id)
                			{
						$alloted_to=$row['ENTRYBY'];
						$str=$profileid.",".$alloted_to;
						$dd=$row['dd']-1;
						$j=array_search($alloted_to,$opsarr[$i]);
						if(is_array($pdproarr))
						{
							if(!in_array($str,$pdproarr))
							{
								$pdproarr[]=$str;
								$cnt[$i][$j][$dd]["pd"]+=1;//$row['cnt'];
								$tot[$i][$j]["pd"]+=1;//$row['cnt'];
							}
						}
						else
						{
							$pdproarr[]=$str;
							$cnt[$i][$j][$dd]["pd"]+=1;//$row['cnt'];
							$tot[$i][$j]["pd"]+=1;//$row['cnt'];
						}
		//				$cnt[$i][$dd]["pd"]+=$row['cnt'];
		//				$tot[$i]["pd"]+=$row['cnt'];
					}
				}while($row=mysql_fetch_array($res));
			}
			
			mysql_free_result($res);

	//		$sql="SELECT COUNT(DISTINCT b.PROFILEID) AS cnt,i.ALLOTED_TO,DAYOFMONTH(ENTRY_DT) as dd FROM billing.PAYMENT_DETAIL b,incentive.MAIN_ADMIN i WHERE i.CONVINCE_TIME<=DATE_ADD(b.ENTRY_DT,INTERVAL 1 DAY) AND WILL_PAY='Y' AND i.STATUS='F' AND CONVINCE_TIME BETWEEN '$st_date' AND '$end_date' AND i.CONVINCE_TIME!=0 AND i.ALLOTED_TO IN ($noidaopsstr) AND i.PROFILEID=b.PROFILEID GROUP BY ALLOTED_TO,dd";
			$sql="SELECT DISTINCT b.PROFILEID,b.BILLID,i.ALLOTED_TO,DAYOFMONTH(i.CONVINCE_TIME) as dd FROM billing.PAYMENT_DETAIL b,incentive.MAIN_ADMIN i WHERE i.CONVINCE_TIME<=DATE_ADD(b.ENTRY_DT,INTERVAL 1 DAY) AND WILL_PAY='Y' AND i.STATUS='F' AND CONVINCE_TIME BETWEEN '$st_date' AND '$end_date' AND i.CONVINCE_TIME!=0 AND i.ALLOTED_TO IN ($opsstr) AND i.PROFILEID=b.PROFILEID";
			if($mode)
				$sql.=" AND i.MODE='$mode'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			if($row=mysql_fetch_array($res))
			{
				do
				{
					$profileid=$row['PROFILEID'];
					$billid=$row['BILLID'];
					$valid_id = 0;
			                $valid_id = check_validity($billid);
			                if($valid_id)
                			{
						$alloted_to=$row['ALLOTED_TO'];
						$str=$profileid.",".$alloted_to;
						$dd=$row['dd']-1;
						$j=array_search($alloted_to,$opsarr[$i]);
						if(is_array($pdproarr))
						{
							if(!in_array($str,$pdproarr))
							{
								$pdproarr[]=$str;
								$cnt[$i][$j][$dd]["pd"]+=1;//$row['cnt'];
								$tot[$i][$j]["pd"]+=1;//$row['cnt'];
							}
						}
						else
						{
							$pdproarr[]=$str;
							$cnt[$i][$j][$dd]["pd"]+=1;//$row['cnt'];
							$tot[$i][$j]["pd"]+=1;//$row['cnt'];
						}
		//				$cnt[$i][$dd]["pd"]+=$row['cnt'];
		//				$tot[$i]["pd"]+=$row['cnt'];
					}
				}while($row=mysql_fetch_array($res));
			}
		}

		$smarty->assign("branch",$branch);
		$smarty->assign("brancharr",$brancharr);
		$smarty->assign("opsarr",$opsarr);
		$smarty->assign("mode",$mode);
		$smarty->assign("cnt",$cnt);
		$smarty->assign("tot",$tot);
                $smarty->assign("ddarr",$ddarr);
                $smarty->assign("month",$month);
                $smarty->assign("year",$year);
		$smarty->assign("cid",$cid);
		$smarty->display("crm_overall_count.htm");
	}
	else
	{
		$smarty->assign("flag","0");
		for($i=0;$i<12;$i++)
                {
                        $mmarr[$i]=$i+1;
                }
                for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}

		$sql="SELECT NAME FROM incentive.BRANCHES";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			do
			{
				$brancharr[]=$row['NAME'];
			}while($row=mysql_fetch_array($res));
		}

		$smarty->assign("brancharr",$brancharr);
                $smarty->assign("priv",$priv);
                $smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
                $smarty->assign("cid",$cid);
                $smarty->display("crm_overall_count.htm");
	}
}
else
{
	$smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}
?>
