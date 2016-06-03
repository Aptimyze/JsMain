<?php
include("connect.inc");
$db=connect_misdb();
$db2=connect_master();

if(authenticated($cid))
{
	if($CMDGo)
	{
		$flag=1;
		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}
		if($branch)
		{
			$brancharr[]=$branch;
		}
		else
		{
			$sql="SELECT NAME FROM billing.BRANCHES";
			$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$brancharr[]=strtoupper($row['NAME']);
			}
		}

		$st_date=$year."-".$month."-01";
		$end_date=$year."-".$month."-31";

//		$sql="SELECT sum(if(billing.PAYMENT_DETAIL.TYPE='DOL',billing.PAYMENT_DETAIL.AMOUNT*45,billing.PAYMENT_DETAIL.AMOUNT)) as amt,DAYOFMONTH(billing.PAYMENT_DETAIL.ENTRY_DT) as dd,billing.PURCHASES.CENTER as center,billing.PURCHASES.WALKIN as eb FROM billing.PAYMENT_DETAIL,billing.PURCHASES WHERE billing.PAYMENT_DETAIL.STATUS='DONE' AND billing.PAYMENT_DETAIL.ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND billing.PURCHASES.BILLID=billing.PAYMENT_DETAIL.BILLID ";

		$sql="SELECT pd.PROFILEID, sum(if(pd.TYPE='DOL',pd.AMOUNT*pd.DOL_CONV_RATE,pd.AMOUNT)) as amt, pd.ENTRY_DT, p.CENTER as center, p.WALKIN as eb FROM billing.PURCHASES p, billing.PAYMENT_DETAIL pd WHERE pd.STATUS='DONE' AND pd.ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND p.BILLID=pd.BILLID";
		if($branch)
			$sql.=" AND p.CENTER='$branch'";
		$sql.=" GROUP BY pd.PROFILEID,eb";
//		$sql.=" GROUP BY pd.PROFILEID,dd,eb";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$profileid=$row['PROFILEID'];
			$entry_dt=$row['ENTRY_DT'];
			list($yy,$mm,$dd)=explode("+",$entry_dt);
			$dd=$dd-1;
			
			$center=strtoupper($row['center']);
			$sql="SELECT DISTINCT ALLOTED_TO FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid' AND CONVINCE_TIME<=$entry_dt AND WILL_PAY='Y' AND STATUS='F'";
			$res_cl=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			while($row_cl=mysql_fetch_array($res_cl))
			{
				if(is_array($alloted_to_arr))
				{
					if(!in_array($row_cl['ALLOTED_TO'],$alloted_to_arr))
					{
						$alloted_to_arr[]=$row['ALLOTED_TO'];
					}
				}
				else
				{
					$alloted_to_arr[]=$row['ALLOTED_TO'];
				}
			}
			mysql_free_result($res_cl);
			$sql="SELECT DISTINCT ENTRYBY FROM incentive.CLAIM WHERE PROFILEID='$profileid' AND CONVINCE_TIME<=$entry_dt AND WILL_PAY='Y' ";
			if(count($alloted_to_arr))
			{
				$alt_str=implode("','",$alloted_to_arr);
				$sql.=" AND ENTRYBY NOT IN ('$alt_str')";
			}
			$res_cl=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			while($row_cl=mysql_fetch_array($res_cl))
			{
				if(is_array($alloted_to_arr))
				{
					if(!in_array($row_cl['ENTRYBY'],$alloted_to_arr))
					{
						$alloted_to_arr[]=$row['ENTRYBY'];
					}
				}
				else
				{
					$alloted_to_arr[]=$row['ENTRYBY'];
				}
			}
			mysql_free_result($res_cl);
			if(count($alloted_to_arr)>0)
			{
				for($i=0;$i<count($alloted_to_arr);$i++)
				{
					$sql="SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME='$alloted_to_arr[$i]'";
					$res_cl=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
					$row_cl=mysql_fetch_array($res_cl);
					$center=strtoupper($row_cl['CENTER']);

					$k=array_search($center,$brancharr);
					$amt[$k][$dd]+=$row['amt']/count($alloted_to_arr);
					$tota[$k]+=$row['amt']/count($alloted_to_arr);
					$totb[$dd]+=$row['amt']/count($alloted_to_arr);
				}
			}
			else
			{
				$k=array_search($center,$brancharr);
				if($center=="HO")
				{
					if($row['eb']=='OFFLINE')
					{
						$amt[$k][$dd]["rj"]+=$row['amt'];
						$tota[$k]["rj"]+=$row['amt'];
					}
					elseif($row['eb']=='ONLINE')
					{
						$amt[$k][$dd]["ol"]+=$row['amt'];
						$tota[$k]["ol"]+=$row['amt'];
					}
					elseif($row['eb']=='ARAMEX')
					{
						$amt[$k][$dd]["ar"]+=$row['amt'];
						$tota[$k]["ar"]+=$row['amt'];
					}
					$totb[$dd]+=$row['amt'];;
				}
				else
				{
					$amt[$k][$dd]+=$row['amt'];
//					$amta[$k][$dd]=$row['amt'];
					$tota[$k]+=$row['amt'];
					$totb[$dd]+=$row['amt'];
				}
			}
		}
/*
print_r($amt);
echo "<br>";
print_r($tota);
echo "<br>";
print_r($totb);
*/
		$smarty->assign("brancharr",$brancharr);
		$smarty->assign("branch",$branch);
		$smarty->assign("amt",$amt);
		$smarty->assign("tot",$tot);
		$smarty->assign("tota",$tota);
		$smarty->assign("totb",$totb);
		$smarty->assign("tot1",$tot1);
		$smarty->assign("tot2",$tot2);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("qtrarr",$qtrarr);
		$smarty->assign("flag",$flag);
		$smarty->assign("year",$year);
		$smarty->assign("month",$month);

                $smarty->display("collectionmis_all.htm");
	}
	else
	{
		$user=getname($cid);
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
			$sql="SELECT * FROM billing.BRANCHES";
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
		$smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
		$smarty->assign("cid",$cid);
		$smarty->display("collectionmis_all.htm");
	}
}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}
?>
