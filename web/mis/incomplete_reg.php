<?php

/***********************************************************************************************************************
* FILE NAME     : incomplete_reg.php
* DESCRIPTION   : Displays number of members converted from Incomplete to Complete
* CREATION DATE : 13 October, 2005
* CREATED BY  	: Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
************************************************************************************************************************/

include("connect.inc");
$db=connect_misdb();

$user=getname($cid);
$smarty->assign("user",$user);
$smarty->assign("cid",$cid);
 
if(authenticated($cid))
{
        if($Submit)
        {
		if(!$Day1)
		{
			$count = 0;
			$loopcnt = 31;

			for($i=1;$i<=$loopcnt;$i++)
			{
				$dcount[$i-1] = $i;
				if(strlen(trim($i))==1)
					$dt = $Year1."-".$Month1."-0".$i;
				else
					$dt = $Year1."-".$Month1."-".$i;

				$sql="SELECT COUNT(*) AS CNT FROM newjs.INCOMPLETE_PROFILES LEFT JOIN newjs.JPROFILE ON INCOMPLETE_PROFILES.PROFILEID=JPROFILE.PROFILEID WHERE JPROFILE.ENTRY_DT BETWEEN '".$dt." 00:00:00' AND '".$dt." 23:59:59' AND INCOMPLETE_PROFILES.REG_DATE < '".$dt."'";
				$res=mysql_query_decide($sql,$db) or die(mysql_error_js($db));
				$row=mysql_fetch_array($res);

				$day_count[$i-1] = $row['CNT'];
				$count += $row['CNT'];

			}
			$row['CNT'] = $count;
			if($Month1 == "01")
				$month_name = "January";
			elseif($Month1 == "02")
				$month_name = "February";
			elseif($Month1 == "03")
                                $month_name = "March";
			elseif($Month1 == "04")
                                $month_name = "April";
			elseif($Month1 == "05")
                                $month_name = "May";
			elseif($Month1 == "06")
                                $month_name = "June";
			elseif($Month1 == "07")
                                $month_name = "July";
			elseif($Month1 == "08")
                                $month_name = "August";
			elseif($Month1 == "09")
                                $month_name = "September";
			elseif($Month1 == "10")
                                $month_name = "October";
			elseif($Month1 == "11")
                                $month_name = "November";
			elseif($Month1 == "12")
                                $month_name = "December";

			$smarty->assign("flag","1");
			$smarty->assign("month_name",$month_name);
			$smarty->assign("year",$Year1);
			$smarty->assign("dcount",$dcount);
			$smarty->assign("day_count",$day_count);
		}
		else
		{
			$dt = $Year1."-".$Month1."-".$Day1;

			$sql="SELECT COUNT(*) AS CNT FROM newjs.INCOMPLETE_PROFILES LEFT JOIN newjs.JPROFILE ON INCOMPLETE_PROFILES.PROFILEID=JPROFILE.PROFILEID WHERE JPROFILE.ENTRY_DT BETWEEN '".$dt." 00:00:00' AND '".$dt." 23:59:59' AND INCOMPLETE_PROFILES.REG_DATE < '".$dt."'";
			$res=mysql_query_decide($sql,$db) or die(mysql_error_js($db));
			$row=mysql_fetch_array($res);
		}
	
		$smarty->assign("total",$row['CNT']);
		$smarty->assign("date",$Day1."-".$Month1."-".$Year1);
		$smarty->assign("view","1");
		$smarty->display("incomplete_reg.htm");
        }
        else
        {
		$smarty->assign("view","0");
		$smarty->display("incomplete_reg.htm");
        }
}
else
{
        $msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"../jsadmin/index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("../jsadmin/jsadmin_msg.tpl");
}
		
?>
