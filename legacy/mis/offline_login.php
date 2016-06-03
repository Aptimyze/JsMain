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
	if($submit1 && $operator!='' && $operator!='all')
	{
		$data=array();	
		if($operator)
		{
			$all_sql="SELECT LOGIN,LOGOUT,DAY( DATE ) AS DAY_NO  FROM jsadmin.LOGIN_DETAILS WHERE OPERATOR='$operator' AND DATE BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays' ORDER BY ID";
			$all_res= mysql_query_decide($all_sql) or die(mysql_error_js());
			while($all_row= mysql_fetch_array($all_res))
			{
				$day=$all_row['DAY_NO'];
				$cnt[$day]++;
				$login[$day][$cnt[$day]]=$all_row['LOGIN'];
				$logout[$day][$cnt[$day]]=$all_row['LOGOUT'];
				list($h,$m,$s)=explode(":",$all_row['LOGIN']);
				list($ho,$mo,$so)=explode(":",$all_row['LOGOUT']);
				if($all_row['LOGOUT']=="00:00:00")
					$time='0';
				else	
					$time=round(((mktime($ho,$mo,$so)-mktime($h,$m,$s))/3600),2);
				$diff[$day][$cnt[$day]]=$time;
				$tot_diff[$day]+=$time;
				
			}
		}
		$smarty->assign('monthDaysArray',$monthDaysArray);
	        $smarty->assign('searchFlag',$searchFlag);
                $smarty->assign('month_name',$month_name);
               	$smarty->assign('searchYear',$searchYear);
		$smarty->assign("login",$login);
		$smarty->assign("logout",$logout);
		$smarty->assign("diff",$diff);
		$smarty->assign("tot_diff",$tot_diff);
		$smarty->assign("operator",$operator);
	}
/**************************************************************************************************/
	else
	{
		if($operator=='all' && $submit1)
			$smarty->assign("msg","Please select an operator!!!");	        
		$sql= "SELECT DISTINCT USERNAME FROM jsadmin.PSWRDS WHERE PRIVILAGE LIKE '%OB%' OR  PRIVILAGE LIKE '%OA%'";
		$res= mysql_query_decide($sql) or die(mysql_error_js());
		while($row= mysql_fetch_array($res))
			$op_uname[]= $row['USERNAME'];
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
	$smarty->display("offline_login.htm");
		
}
else
{
        $smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}

?>
