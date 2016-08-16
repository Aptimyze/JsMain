<?php
include("connect.inc");
$db=connect_misdb();

if(authenticated($cid))
{
	$smarty->assign("cid",$cid);
	if($CMDGo)
	{
		$st_date=$yy."-".$mm."-01";
		$end_date=$yy."-".$mm."-31";

		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}

		$sql="SELECT ALLOTED_TO,DAYOFMONTH(ENTRY_DT) as dd,ASSIGN,FOLLOW,PAYMENT,CARRY FROM MIS.CRM_DAILY_ALLOT WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' GROUP BY ALLOTED_TO,dd";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$alloted_to=$row['ALLOTED_TO'];
			$center=strtoupper(getcenter_for_operator($alloted_to));
			$dd=$row['dd']-1;
			if(is_array($brancharr))
			{
				if(!in_array($center,$brancharr))
				{
					if($branch=='' || strtoupper($branch)==$center)
						$brancharr[]=$center;
				}
			}
			else
			{
				if($branch=='' || strtoupper($branch)==$center)
					$brancharr[]=$center;
			}

			if($branch=='' || strtoupper($branch)==$center)
			{
				$i=array_search($center,$brancharr);
				if(is_array($operatorarr[$i]))
				{
					if(!in_array($alloted_to,$operatorarr[$i]))
						$operatorarr[$i][]=$alloted_to;
				}
				else
				{
					$operatorarr[$i][]=$alloted_to;
				}
				$j=array_search($alloted_to,$operatorarr[$i]);
				$assign_cnt[$i][$j][$dd]+=$row['ASSIGN'];
				$follow_cnt[$i][$j][$dd]+=$row['FOLLOW'];
				$payment_cnt[$i][$j][$dd]+=$row['PAYMENT'];
				$carry_cnt[$i][$j][$dd]+=$row['CARRY'];
			}
		}

		$sql="SELECT ALLOTED_TO,DAYOFMONTH(FOLLOW_DT) as dd,HOT_COUNT,WARM_COUNT,COLD_COUNT,NOCONTACT_COUNT,EMPTY_COUNT FROM MIS.CRM_DAILY_COUNT WHERE FOLLOW_DT BETWEEN '$st_date' AND '$end_date' GROUP BY ALLOTED_TO,dd";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$alloted_to=$row['ALLOTED_TO'];
			$center=strtoupper(getcenter_for_operator($alloted_to));
			$dd=$row['dd']-1;

			if($branch=='' || strtoupper($branch)==$center)
			{
				$i=array_search($center,$brancharr);
				$j=array_search($alloted_to,$operatorarr[$i]);
				$hcnt[$i][$j][$dd]+=$row['HOT_COUNT'];
				$wcnt[$i][$j][$dd]+=$row['WARM_COUNT'];
				$ccnt[$i][$j][$dd]+=$row['COLD_COUNT'];
				$ncnt[$i][$j][$dd]+=$row['NOCONTACT_COUNT'];
				$ecnt[$i][$j][$dd]+=$row['EMPTY_COUNT'];
				$tota[$i][$j]+=$row['HOT_COUNT']+$row['WARM_COUNT']+$row['COLD_COUNT']+$row['NOCONTACT_COUNT']+$row['EMPTY_COUNT'];
				$totb[$i][$j][$dd]+=$row['HOT_COUNT']+$row['WARM_COUNT']+$row['COLD_COUNT']+$row['NOCONTACT_COUNT'];//+$row['EMPTY_COUNT'];
				$totall[$i]+=$row['HOT_COUNT']+$row['WARM_COUNT']+$row['COLD_COUNT']+$row['NOCONTACT_COUNT']+$row['EMPTY_COUNT'];
			}
		}
		if($brancharr)
			$smarty->assign("BRANCH","Y");

		$smarty->assign("assign_cnt",$assign_cnt);
		$smarty->assign("follow_cnt",$follow_cnt);
		$smarty->assign("payment_cnt",$payment_cnt);
		$smarty->assign("carry_cnt",$carry_cnt);
		$smarty->assign("hcnt",$hcnt);
		$smarty->assign("wcnt",$wcnt);
		$smarty->assign("ccnt",$ccnt);
		$smarty->assign("ncnt",$ncnt);
		$smarty->assign("ecnt",$ecnt);
		$smarty->assign("tota",$tota);
		$smarty->assign("totb",$totb);
		$smarty->assign("totall",$totall);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("brancharr",$brancharr);
		$smarty->assign("operatorarr",$operatorarr);
		$smarty->assign("flag",1);
		$smarty->display("crm_daily_work_mis.htm");
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

                        $smarty->assign("ONLYBRANCH","Y");
                        $smarty->assign("branch",$branch);
                }

                $smarty->assign("priv",$priv);
                $smarty->assign("ddarr",$ddarr);
                $smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
		$smarty->display("crm_daily_work_mis.htm");
	}
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
