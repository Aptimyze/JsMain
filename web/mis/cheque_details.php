<?php
include("connect.inc");

$db=connect_misdb();

if(authenticated($cid))
{
	if($CMDGo)
	{
		$smarty->assign("flag","1");
		$yyp1=$yy+1;
		$mmarr=array('Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar');

		$st_date=$yy."-04-01";
		$end_date=$yyp1."-03-31";

		if($branch)
		{
			$brancharr[]=$branch;
		}
		else
		{
			$sql="SELECT NAME FROM billing.BRANCHES";
                        $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        while($row=mysql_fetch_array($res))
			{
				$brancharr[]=$row['NAME'];
                        }
		}

		if($get_today)
		{
			$today=date("Y-m-d");
			$sql="SELECT COUNT(*) as cnt,b.CENTER FROM billing.PAYMENT_DETAIL a,billing.PURCHASES b WHERE a.BILLID=b.BILLID AND a.BOUNCE_DT='$today' AND b.STATUS='BOUNCE' ";
			if($branch)
			{
				$sql.=" AND b.CENTER='$branch'";
			}
			$sql.=" GROUP BY b.CENTER";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$center=strtoupper($row['CENTER']);
				$i=array_search($center,$brancharr);
				$bounce_today[$i]+=$row['cnt'];
			}
			
			$sql="SELECT COUNT(*) as cnt,b.CENTER FROM billing.PAYMENT_DETAIL a,billing.PURCHASES b WHERE a.BILLID=b.BILLID AND a.ENTRY_DT>='$today'";
			if($branch)
			{
				$sql.=" AND UPPER(CENTER)='".strtoupper($branch)."'";
			}
			$sql.=" GROUP BY b.CENTER";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$center=strtoupper($row['CENTER']);
				$i=array_search($center,$brancharr);
				$total_today[$i]+=$row['cnt'];
			}
		}
		else
		{
			$sql="SELECT SUM(COUNT) as cnt,STATUS,MONTH(ENTRY_DT) as mm,BRANCH FROM MIS.CHEQUE_DETAILS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' ";
			if($branch)
			{
				$sql.=" AND BRANCH='$branch' ";
			}
			$sql.=" GROUP BY mm,BRANCH,STATUS";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			while($row=mysql_fetch_array($res))
			{
				$center=strtoupper($row['BRANCH']);
				$i=array_search($center,$brancharr);
				$mm=$row['mm'];
				if($mm<=3)
				{
					$mm+=8;
				}
				else
				{
					$mm-=4;
				}
				$status=$row['STATUS'];
				if($status=='B')
				{
					$bounce_cnt[$i][$mm]+=$row['cnt'];
					$total_bouncea[$i]+=$row['cnt'];
					$total_bounceb[$mm]+=$row['cnt'];
					$total_bounceall+=$row['cnt'];
				}
				else
				{
					$total_cnt[$i][$mm]+=$row['cnt'];
					$totala[$i]+=$row['cnt'];
					$totalb[$mm]+=$row['cnt'];
					$totalall+=$row['cnt'];
				}
			}
		}

		$smarty->assign("get_today",$get_today);
		$smarty->assign("cid",$cid);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yy",$yy);
		$smarty->assign("branch",$branch);
		$smarty->assign("brancharr",$brancharr);
		$smarty->assign("bounce_cnt",$bounce_cnt);
		$smarty->assign("total_bouncea",$total_bouncea);
		$smarty->assign("total_bounceb",$total_bounceb);
		$smarty->assign("total_bounceall",$total_bounceall);
		$smarty->assign("total_cnt",$total_cnt);
		$smarty->assign("totala",$totala);
		$smarty->assign("totalb",$totalb);
		$smarty->assign("totalall",$totalall);
		$smarty->assign("bounce_today",$bounce_today);
		$smarty->assign("total_today",$total_today);

		$smarty->display("cheque_details.htm");
	}
	else
	{
                $user=getname($cid);
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
                        $sql="SELECT NAME FROM billing.BRANCHES";
                        $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        if($row=mysql_fetch_array($res))
                        {
                                $i=0;
                                do
                                {
                                        $branch[$i]=$row['NAME'];
                                        $i++;
                                }while($row=mysql_fetch_array($res));
                        }

                        $smarty->assign("branch",$branch);
                }
                elseif(in_array('MC',$priv) || in_array('MD',$priv))
                {
                        // run query : select branch of user
                        $sql="SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME='$user'";
                        $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        if($row=mysql_fetch_array($res))
                        {
                                $branch=strtoupper($row['CENTER']);
                        }

                        $smarty->assign("ONLYBRANCH","Y");
                        $smarty->assign("branch",$branch);
                }

                $smarty->assign("priv",$priv);
                $smarty->assign("yyarr",$yyarr);
                $smarty->assign("cid",$cid);
                $smarty->display("cheque_details.htm");
	}
}
else
{
	$smarty->display("jsconnectError.tpl");
}
?>
