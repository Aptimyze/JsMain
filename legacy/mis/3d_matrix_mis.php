<?php
/*      Filename        :	3d_matrix_mis MIS.
*       Description     :  	if a contacts b , mis shows compatibility of a wrt b
*/

include("connect.inc");
$db=connect_misdb();
$db2=connect_master();
if(authenticated($cid))
{
	if($CMDGo)
	{
		$date=$year."-".$month."-".$day;	
		if($day!='ALL')
		{
			$sql="SELECT COUNT(*) as cnt, TYPE , COMPATIBILITY FROM newjs.COMPATIBILITY WHERE DATE='$date' GROUP BY TYPE asc,COMPATIBILITY asc";
			
			/*if($gender=='M')
				$sql.=" AND GENDER='M'";
			elseif($gender=='F')
				$sql.=" AND GENDER='F'";*/
			
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			while($row=mysql_fetch_array($res))
			{
				$total[]=$row['cnt'];
				$type[]=$row['TYPE'];
				if($row['TYPE']=='A')
					$type_a+=$row['cnt'];
				if($row['TYPE']=='C')
					$type_c+=$row['cnt'];
				if($row['TYPE']=='D')
					$type_d+=$row['cnt'];
				if($row['TYPE']=='I')
					$type_i+=$row['cnt'];
				$compatibility[]=$row['COMPATIBILITY'];
			}
		}
		else
		{
			$st_date=$year."-".$month."-01";
			$end_date=$year."-".$month."-31";
			
			$sql="SELECT COUNT(*) as cnt, TYPE , COMPATIBILITY FROM newjs.COMPATIBILITY WHERE DATE BETWEEN '$st_date' AND '$end_date' GROUP BY TYPE asc,COMPATIBILITY asc";
			
                        /*if($gender=='M')
                                $sql.=" AND GENDER='M'";
                        elseif($gender=='F')
                                $sql.=" AND GENDER='F'";*/
			
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			while($row=mysql_fetch_array($res))
			{
				$total[]=$row['cnt'];
				$type[]=$row['TYPE'];
				if($row['TYPE']=='A')
					$type_a+=$row['cnt'];
				if($row['TYPE']=='C')
					$type_c+=$row['cnt'];
				if($row['TYPE']=='D')
					$type_d+=$row['cnt'];
				if($row['TYPE']=='I')
					$type_i+=$row['cnt'];
				$compatibility[]=$row['COMPATIBILITY'];
			}
		}
		
		mysql_data_seek($res,0);
		
		while($row=mysql_fetch_array($res))
		{
			if($row['TYPE']=='A')
				$percentage[]=round(($row['cnt']/$type_a)*100,2);
			if($row['TYPE']=='C')
				$percentage[]=round(($row['cnt']/$type_c)*100,2);
			if($row['TYPE']=='D')
				$percentage[]=round(($row['cnt']/$type_d)*100,2);
			if($row['TYPE']=='I')
				$percentage[]=round(($row['cnt']/$type_i)*100,2);
		}
		
		$smarty->assign("total",$total);
		$smarty->assign("type",$type);
		$smarty->assign("compatibility",$compatibility);
		$smarty->assign("percentage",$percentage);
		//$smarty->assign("gender",$gender);
		$smarty->assign("date",$date);
		$smarty->assign("day",$day);
		$smarty->assign("month",$month);
		$smarty->assign("year",$year);
		$smarty->assign("flag",'1');
		for($i=0;$i<31;$i++)
                {
                        $ddarr[$i]=$i+1;
                }
		$smarty->assign("ddarr",$ddarr);

	}
	else
	{
		for($i=0;$i<10;$i++)
		{
			$yyarr[$i]=$i+2006;
		}
		for($i=1;$i<=12;$i++)
		{
			$mmarr[]=$i;
		}
		for($i=1;$i<=31;$i++)
		{
			$dayarr[]=$i;
		}
		$smarty->assign("dayarr",$dayarr);
		$smarty->assign("yyarr",$yyarr);
		$smarty->assign("mmarr",$mmarr);
	}
			
	$smarty->assign("cid",$cid);
	$smarty->display("3d_matrix_mis.htm");
}
else
{
	$msg="Your session has been timed out<br>  ";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";
	$smarty->assign("MSG",$msg);
	$smarty->display("jsconnectError.tpl");

}
?>
