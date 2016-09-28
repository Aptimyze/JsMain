<?php
include_once("connect.inc");
$db=connect_misdb();

//$data=authenticated($cid);
$data=authenticated($checksum);
if(isset($data))
{
	if(!$today)
        $today=date("Y-m-d");
        $date_t= JSstrToTime($today);
        $today= gmdate('Y-m-d',$date_t - (43200));
        list($todYear,$todMonth,$todDay)=explode("-",$today);

	if($CMDGo)
        {
                $st_date=$syear."-".$smonth."-".$sday." 00:00:00";
                $end_date=$eyear."-".$emonth."-".$eday." 23:59:59";

		$profiles= array();
		$profs= array();
		$sum=$del_count=$mark_count=$i=$pay_count=0;
		$sql= "SELECT PROFILEID, DATEDIFF(DATE,M_DATE) AS dylft,STATUS FROM jsadmin.MARK_DELETE WHERE M_DATE BETWEEN '$st_date' AND '$end_date' AND DATE BETWEEN '$st_date' AND '$end_date'";
		$res= mysql_query_decide($sql) or die(mysql_error_js());
		while($row= mysql_fetch_array($res))
		{
			$pid= $row['PROFILEID'];
			$stat= $row['STATUS'];
			if($stat== 'D')
			{
				$del_count++;
				$dy=$row['dylft'];
				$sum+=$dy;
			}
			else
			{
				$profiles[]=$pid;
			}
			
		}
		if($del_count)
		$avg= $sum/$del_count;
		$sql1= "SELECT A.PROFILEID FROM jsadmin.MARK_DELETE AS A LEFT JOIN billing.PAYMENT_DETAIL AS B ON A.PROFILEID= B.PROFILEID WHERE A.STATUS='M' AND B.STATUS= 'REFUND' AND B.ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND A.DATE BETWEEN '$st_date' AND '$end_date'"; 
		$res1= mysql_query_decide($sql1) or die(mysql_error_js());
		while($row1= mysql_fetch_array($res1))
		{
			$profs[]=$row1['PROFILEID'];
			$pay_count++;
		}

		$len= count($profiles);
		while($len)
		{
			//if(mysql_num_rows($res)>0)
			{
				$p= $profiles[$len];
				if(in_array($p,$profs));
				else
					$mark_count++;
				if($len)
					$len--;
			}
		}
		$smarty->assign("st_date",$st_date);
                $smarty->assign("end_date",$end_date);
                $smarty->assign("avg",$avg);
                $smarty->assign("pay",$pay_count);
                $smarty->assign("del",$del_count);
		$smarty->assign("mark",$mark_count);
                $smarty->assign("GO","Y");
        }


	for($i=0;$i<31;$i++)
        {
                $ddarr[$i]=$i+1;
        }

	$k=0;
        while($k<=10)
        {
                $yyarr[]=$todYear-$k;
                $k++;
        }
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
        $smarty->assign("ddarr",$ddarr);
//print_r($mmarr);
	$smarty->assign("mmarr",$mmarr);
	$smarty->assign("yyarr",$yyarr);
        $smarty->assign("outside","$outside");
        $smarty->assign("checksum",$checksum);
	$smarty->display("delete_profiles.htm");
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
