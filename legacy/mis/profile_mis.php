<?php
include_once("connect.inc");
$db=connect_misdb();
$db2=connect_master();

$data=authenticated($checksum);

if(isset($data))
{
	$searchMonth='';
        $searchYear='';
        $monthDays=0;
	if(!$today)
        $today=date("Y-m-d");
        list($todYear,$todMonth,$todDay)=explode("-",$today);
	if($type)
	{
		$searchFlag=1;
		$searchMonth=$month;
		$searchYear=$year;
		  if(($searchMonth=='01')||($searchMonth=='03')||($searchMonth=='05')||($searchMonth=='07')||($searchMonth=='08')||($searchMonth=='10')||($searchMonth=='12'))
			$monthDays=31;
			elseif(($searchMonth=='04')||($searchMonth=='06')||($searchMonth=='09')||($searchMonth=='11'))
				$monthDays=30;
				elseif(($searchYear%4==0)&&($searchYear%100!=0)||($searchYear%400==0))
					$monthDays=29;
					else
                                $monthDays=28;
		$monthArray=array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'May','06'=>'Jun','07'=>'Jul','08'=>'Aug','09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
			foreach($monthArray as $i=>$value)
			{
				if($i== $searchMonth)
					$month_name= $value;
			}

		$k=1;
		while($k<=$monthDays)
		{
		$monthDaysArray[]=$k;
		$k++;
		}
	}
	if($submit1 && $type== 't_date')
	{
		$data=array();	
		
 		$all_sql="SELECT COUNT( * ) AS CNT, CENTER AS LOC, DAY( A.ENTRY_DATE ) AS DAY_NO FROM billing.PURCHASES AS B, jsadmin.OFFLINE_BILLING AS A WHERE A.BILLID = B.BILLID AND A.ENTRY_DATE BETWEEN '$searchYear-$searchMonth-01 00:00:00' AND '$searchYear-$searchMonth-$monthDays 23:59:59' GROUP BY DAY( A.ENTRY_DATE )";
		$all_res= mysql_query_decide($all_sql) or die(mysql_error_js());
		while($all_row= mysql_fetch_array($all_res))
		{
			$billid= $all_row['BILLID'];
			$day= $all_row['DAY_NO'];
			$data[$day]=$all_row['CNT'];	
			$total+=$all_row['CNT'];
			
		}
		$smarty->assign('monthDaysArray',$monthDaysArray);
	        $smarty->assign('searchFlag',$searchFlag);
                $smarty->assign('month_name',$month_name);
               	$smarty->assign('searchYear',$searchYear);
		$smarty->assign("data",$data);
		$smarty->assign("total",$total);
	}
/**************************************************************************************************/
	elseif($submit1 && $type)
	{
		$revenue=array();	
		$flag_type= 'Y';
		
		if($type== 'location')
		{
			if($location== 'all')
				$all_sql= "SELECT COUNT( * ) AS CNT,C.CENTER AS LOC, DAY( A.ENTRY_DATE ) AS DAY_NO FROM billing.PURCHASES AS B, jsadmin.OFFLINE_BILLING AS A, jsadmin.PSWRDS AS C WHERE A.BILLID = B.BILLID AND B.ENTRYBY=C.USERNAME AND A.ENTRY_DATE BETWEEN '$searchYear-$searchMonth-01 00:00:00' AND '$searchYear-$searchMonth-$monthDays 23:59:59' GROUP BY C.CENTER, DAY( A.ENTRY_DATE )";
			else
				{
					$all_sql= "SELECT COUNT( * ) AS CNT, C.CENTER AS LOC, DAY( A.ENTRY_DATE ) AS DAY_NO FROM billing.PURCHASES AS B, jsadmin.OFFLINE_BILLING AS A, jsadmin.PSWRDS AS C WHERE A.BILLID = B.BILLID AND B.ENTRYBY=C.USERNAME AND C.CENTER ='$location' AND A.ENTRY_DATE BETWEEN '$searchYear-$searchMonth-01 00:00:00' AND '$searchYear-$searchMonth-$monthDays 23:59:59' GROUP BY DAY( A.ENTRY_DATE )";			
					$loc=$location;
				}
			
			$all_res= mysql_query_decide($all_sql) or die(mysql_error_js());
			if(mysql_num_rows($all_res)>0)
			{
				while($all_row= mysql_fetch_array($all_res))
				{
					if($location=="all")
					$loc= $all_row['LOC'];
					$day= $all_row['DAY_NO'];
					$count[$loc][$day]+= $all_row['CNT'];
					$total[$loc]+= $all_row['CNT'];
					$tot_d[$day]+= $all_row['CNT'];
					$tot+= $all_row['CNT'];
				}
			}
			else
			{
				$msg= "No billing is done in selected location";

				$smarty->assign("nodata","Y");
				$smarty->assign("msg",$msg);
			}
		}
/*************************************************************************************************/
		if($type== 'operator')
		{
			if($operator== 'all')
				$all_sql= "SELECT COUNT(*) AS CNT, B.ENTRYBY AS OP, DAY(C.ENTRY_DATE) AS DAY_NO FROM billing.PURCHASES AS  B, jsadmin.OFFLINE_BILLING AS C WHERE C.BILLID= B.BILLID AND C.ENTRY_DATE BETWEEN '$searchYear-$searchMonth-01 00:00:00' AND '$searchYear-$searchMonth-$monthDays 23:59:59' GROUP BY B.ENTRYBY, DAY(C.ENTRY_DATE)";
			else	
			{
				$all_sql= "SELECT COUNT(*) AS CNT, B.ENTRYBY AS OP, DAY(C.ENTRY_DATE) AS DAY_NO FROM billing.PURCHASES AS  B, jsadmin.OFFLINE_BILLING AS C WHERE C.BILLID= B.BILLID AND B.ENTRYBY= '$operator' AND C.ENTRY_DATE BETWEEN '$searchYear-$searchMonth-01 00:00:00' AND '$searchYear-$searchMonth-$monthDays 23:59:59' GROUP BY DAY(C.ENTRY_DATE)";
				$op=$operator;
			}
			$all_res= mysql_query_decide($all_sql) or die(mysql_error_js());
			if(mysql_num_rows($all_res)>0)
			{
				while($all_row= mysql_fetch_array($all_res))
				{
					if($operator=="all")
					$op= $all_row['OP'];
					$cnt= $all_row['CNT'];
					$day= $all_row['DAY_NO'];
					$count[$op][$day]+= $cnt;
					$total[$op]+= $cnt;
					$tot_d[$day]+= $cnt;
					$tot+= $cnt;
			 	
				}
			}
			else
			{
				$msg= "No billing is done by selected operator";

				$smarty->assign("nodata","Y");
				$smarty->assign("msg",$msg);
			}
			
		}
		$smarty->assign('tot',$tot);
		$smarty->assign('month_name',$month_name);
		$smarty->assign('monthDaysArray',$monthDaysArray);
		$smarty->assign('searchFlag',$searchFlag);
		$smarty->assign('flag_type',$flag_type);
		$smarty->assign("count",$count);
		$smarty->assign('searchYear',$searchYear);
		$smarty->assign("total",$total);
		$smarty->assign("tot_d",$tot_d);
	}
	else
	{        
		$sql= "SELECT DISTINCT CENTER FROM jsadmin.PSWRDS WHERE PRIVILAGE LIKE '%OB%'";
		$res= mysql_query_decide($sql) or die(mysql_error_js());
		while($row= mysql_fetch_array($res))
			$op_center[]= $row['CENTER'];
		$sql= "SELECT DISTINCT USERNAME FROM jsadmin.PSWRDS WHERE PRIVILAGE LIKE '%OB%'";
		$res= mysql_query_decide($sql) or die(mysql_error_js());
		while($row= mysql_fetch_array($res))
			$op_uname[]= $row['USERNAME'];
		$smarty->assign("op_center",$op_center);
		$smarty->assign("op_uname",$op_uname);
	}	

	$k=0;
	while($k<=5)
	{
		$yearArray[]=$todYear-$k;
		$k++;
	}
	$monthArray=array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'May','06'=>'Jun','07'=>'Jul','08'=>'Aug','09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
	$smarty->assign("checksum",$checksum);
	$smarty->assign("cid",$cid);                
	$smarty->assign('yearArray',$yearArray);
	$smarty->assign('monthArray',$monthArray);
	$smarty->assign('todYear',$todYear);
	$smarty->assign('todMonth',$todMonth);
	$smarty->display("profile_mis.tpl");
		
}
else
{
        $smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}

?>
