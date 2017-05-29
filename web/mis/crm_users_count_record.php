<?php

include("connect.inc");
$db=connect_misdb();
$db2=connect_master();

if(authenticated($cid))
{
	if($CMDGo)
	{
		$smarty->assign("flag","1");
		$modearr=array('I','O','W','C');

		if($vtype=='M')
		{
			$st_date=$myear."-".$mmonth."-01 00:00:00";
			$end_date=$myear."-".$mmonth."-31 23:59:59";
		}
		elseif($vtype=='D')
		{
			$st_date=$dyear."-".$dmonth."-".$day." 00:00:00";
			$end_date=$dyear."-".$dmonth."-".$day." 23:59:59";
		}

//		$sql="SELECT DISTINCT PROFILEID, ALLOTED_TO,MODE FROM incentive.MAIN_ADMIN WHERE CONVINCE_TIME BETWEEN '$st_date' AND '$end_date' AND WILL_PAY='Y' AND STATUS='F' ";//GROUP BY ALLOTED_TO,MODE";

		$sql="(SELECT PROFILEID,CLAIM_TIME as claim_time,ALLOTED_TO as alloted_to,MODE FROM incentive.MAIN_ADMIN WHERE CONVINCE_TIME BETWEEN '$st_date' AND '$end_date' AND WILL_PAY='Y' AND STATUS='F') UNION (SELECT DISTINCT PROFILEID,CONVINCE_TIME as claim_time,ENTRYBY as alloted_to,MODE FROM incentive.CLAIM WHERE CONVINCE_TIME BETWEEN '$st_date' AND '$end_date' AND WILL_PAY='Y') ORDER BY PROFILEID,claim_time ASC";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			$ind=0;
			do
			{
				$profileid=$row['PROFILEID'];
				$alloted_to=$row['alloted_to'];
				$mode=$row['MODE'];
				$str=$profileid.",".$alloted_to;//.",".$mode;

				$sql="SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME='$alloted_to'";
				$res1=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
				$row1=mysql_fetch_array($res1);
				$center=strtoupper($row1['CENTER']);

				if(is_array($brancharr))
				{
					if(!in_array($center,$brancharr))
					{
						$brancharr[]=$center;
					}
				}
				else
				{
					$brancharr[]=$center;
				}
				$i=array_search($center,$brancharr);
				if(is_array($operatorarr[$i]))
				{
					if(!in_array($alloted_to,$operatorarr[$i]))
					{
						$operatorarr[$i][]=$alloted_to;
					}
				}
				else
				{
					$operatorarr[$i][]=$alloted_to;
				}

				$j=array_search($alloted_to,$operatorarr[$i]);
				$k=array_search($mode,$modearr);

				if(is_array($temparr))
				{
					if(!in_array($profileid,$temparr))
					{
						$flag[$ind]=0;
						$temp_branch[$ind]=$center;
						$temparr[$ind]=$profileid;
						$pmode[$ind]=$mode;
						$temp_allotarr[$ind]=$alloted_to;
						$ind++;
					}
				}
				else
				{
					$flag[$ind]=0;
					$temp_branch[$ind]=$center;
					$temparr[$ind]=$profileid;
					$pmode[$ind]=$mode;
					$temp_allotarr[$ind]=$alloted_to;
					$ind++;
				}

				if($pmode[$ind-1]=='O')
				{
					if(!$flag[$ind-1])
					{
						$i=array_search($temp_branch[$ind-1],$brancharr);
						$j=array_search($temp_allotarr[$ind-1],$operatorarr[$i]);
						$k=array_search('O',$modearr);

						$cnt[$i][$j][$k]+=1;
						$tota[$i][$j]+=1;
						$totb[$i][$k]+=1;
						$tot[$i]+=1;

						$flag[$ind-1]=1;
					}
				}
				else
				{
					if(is_array($profilearr))
					{
						if(!in_array($str,$profilearr))
						{
							$profilearr[]=$str;
							$cnt[$i][$j][$k]+=1;
							$tota[$i][$j]+=1;
							$totb[$i][$k]+=1;
							$tot[$i]+=1;
						}
					}
					else
					{
						$profilearr[]=$str;
						$cnt[$i][$j][$k]+=1;
						$tota[$i][$j]+=1;
						$totb[$i][$k]+=1;
						$tot[$i]+=1;
					}
				}
			}while($row=mysql_fetch_array($res));
		}

		$smarty->assign("brancharr",$brancharr);
		$smarty->assign("operatorarr",$operatorarr);
		$smarty->assign("modearr",$modearr);
		$smarty->assign("cnt",$cnt);
		$smarty->assign("tota",$tota);
		$smarty->assign("totb",$totb);
		$smarty->assign("tot",$tot);
		$smarty->assign("cid",$cid);
		$smarty->display("crm_users_count_record.htm");
	}
	else
	{
		$user=getname($cid);
		$smarty->assign("flag","0");
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
                $privilage=getprivilage($cid);
                $priv=explode("+",$privilage);
                if(in_array('MA',$priv) || in_array('MB',$priv))
                {
                        $smarty->assign("VIEWALL","Y");
                        //run query : select all branches
                        $sql="SELECT NAME FROM incentive.BRANCHES";
                        $res=mysql_query_decide($sql,$db) or die(mysql_error_js());
                        if($row=mysql_fetch_array($res))
                        {
                                do
                                {
                                        $brancharr[]=$row['NAME'];
                                }while($row=mysql_fetch_array($res));
                        }

                        $smarty->assign("brancharr",$brancharr);
                }
                else
                {
                        // run query : select branch of user
                        $sql="SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME='$user'";
                        $res=mysql_query_decide($sql,$db) or die(mysql_error_js());
                        if($row=mysql_fetch_array($res))
                        {
                                $branch=$row['CENTER'];
                        }

                        $smarty->assign("branch",$branch);
                }

                $smarty->assign("priv",$priv);
                $smarty->assign("ddarr",$ddarr);
                $smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
                $smarty->assign("cid",$cid);
                $smarty->display("crm_users_count_record.htm");
	}
}
else
{
	$smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}
?>
