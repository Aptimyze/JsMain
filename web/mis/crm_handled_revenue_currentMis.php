<?php
include_once("connect.inc");
include_once("user_hierarchy.php");
$db=connect_master();

if(authenticated($cid))
{
                $name		=getname($cid);
		if($name =='vibhor' || $name =='shilpi.sharma')
			$name='anamika.singh';	
                $allotstr 	=user_hierarchy($name);	

		if(!$dateSelected){
                	$today=date("Y-m-d");
                	list($myear,$mmonth,$d)=explode("-",$today);
		}
                $st_date=$myear."-".$mmonth."-01 00:00:00";
                $end_date=$myear."-".$mmonth."-31 23:59:59";
		$smarty->assign("yy",$myear);
		$smarty->assign("mm",$mmonth);
		$privilage = explode("+",getprivilage($cid));
		if(in_array("ExcPrm",$privilage) || in_array("ExPrmO",$privilage))
			$smarty->assign("premium",'Y');

		//$sql="SELECT BILLID, AMOUNT, ENTRY_DT, DAYOFMONTH(ENTRY_DT) as dd, ALLOTED_TO, CENTER FROM incentive.MONTHLY_INCENTIVE_ELIGIBILITY WHERE ENTRY_DT >='$st_date' AND ENTRY_DT<='$end_date' AND ALLOTED_TO IN ($allotstr)";
		$sql="SELECT SUM(AMOUNT) AMOUNT, ENTRY_DT, DAYOFMONTH(ENTRY_DT) as dd, ALLOTED_TO, CENTER FROM incentive.MONTHLY_INCENTIVE_ELIGIBILITY WHERE ENTRY_DT >='$st_date' AND ENTRY_DT<='$end_date' AND ALLOTED_TO IN ($allotstr) GROUP BY ALLOTED_TO,DAYOFMONTH(ENTRY_DT)";

		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$amount		=$row['AMOUNT'];
			$alloted_to	=$row['ALLOTED_TO'];
			$center		=$row['CENTER'];

			$dd=$row['dd']-1;
			if(is_array($brancharr)){
				if(!in_array($center,$brancharr))
					$brancharr[]=$center;
			}
			else
				$brancharr[]=$center;

			$i=array_search($center,$brancharr);
			if(is_array($operatorarr[$i])){
				if(!in_array($alloted_to,$operatorarr[$i]))
					$operatorarr[$i][]=$alloted_to;
			}
			else
				$operatorarr[$i][]=$alloted_to;
			$j=array_search($alloted_to,$operatorarr[$i]);

			$amt[$i][$j][$dd]+=$amount;
			$amta[$i][$j]+=$amount;
			$amtb[$i][$dd]+=$amount;
			$amttot[$i]+=$amount;
			$amt_all[$dd]+=$amount;	
			$totamnt+=$amount;

			//calculate net off tax values for each branch and also for total.
			$net_off_tax[$i][$j] 	=net_off_tax_calculation($amta[$i][$j],$end_date);
			$net_off_tax_final[$i] 	=net_off_tax_calculation($amttot[$i],$end_date);
		}

		$net_off_tax_total = net_off_tax_calculation($totamnt,$end_date);	
		if(count($brancharr)>0)
			$smarty->assign("BRANCH","Y");
		
		$smarty->assign("flag","1");
		$smarty->assign("cid",$cid);
		$smarty->assign("amt",$amt);
		$smarty->assign("amta",$amta);
		$smarty->assign("amtb",$amtb);
		$smarty->assign("amttot",$amttot);
		$smarty->assign("net_off_tax",$net_off_tax);
		$smarty->assign("net_off_tax_final",$net_off_tax_final);
		$smarty->assign("brancharr",$brancharr);
		$smarty->assign("operatorarr",$operatorarr);
		$smarty->assign("amt_all",$amt_all);
		$smarty->assign("totamnt",$totamnt);
		$smarty->assign("net_off_tax_total",$net_off_tax_total);

                for($i=0;$i<31;$i++)
                        $ddarr[$i]=$i+1;
		$smarty->assign("ddarr",$ddarr);
                
		$smarty->display("crm_users_revenue_new.htm");
}
else
{
	$smarty->assign("user",$name);
        $smarty->display("jsconnectError.tpl");
}
?>
