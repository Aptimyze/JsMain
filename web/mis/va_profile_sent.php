<?php
/**************************************************************************************************************************
Filename     :  va_profile_sent.php
Description  :  File to display sent profiles as visitor alerts (Issue 2764)
Created On   :  12 March 2008
Created By   :  Vibhor Garg
***************************************************************************************************************************/
$flag_using_php5=1;
include_once("connect.inc");
$db=connect_misdb();
$db2=connect_master();
$data=authenticated($checksum);
$mysqlObj=new Mysql;
$myDb1=$mysqlObj->connect("11Master");
if(isset($data))
{
	$searchMonth='';
        $searchYear='';
        $monthDays=0;
        if(!$today)
        $today=date("Y-m-d");       
	list($todYear,$todMonth,$todDay)=explode("-",$today);        
	if($outside)
        {
                $go="Y";
                $month=$todMonth;
                $year=$todYear;
                $monthDays=$todDay-1;
        }
	if($go)
	{
		$searchYear=$year;
		$searchMonth=$month;
		if(!$monthDays)
		{
			if(($searchMonth=='01')||($searchMonth=='03')||($searchMonth=='05')||($searchMonth=='07')||($searchMonth=='08')||($searchMonth=='10')||($searchMonth=='12'))
				$monthDays=31;
			elseif(($searchMonth=='04')||($searchMonth=='06')||($searchMonth=='09')||($searchMonth=='11'))
                                $monthDays=30;
                        elseif(($searchYear%4==0)&&($searchYear%100!=0)||($searchYear%400==0))
                                $monthDays=29;
                                else
                                $monthDays=28;
		}
                $k=1;
                while($k<=$monthDays)
                {
                        $monthDaysArray[]=$k;
                        $k++;
                }
		$sql="SELECT PROFILE_SENT,ALERT_SENT,SENT_DATE FROM visitoralert.VISITOR_ALERT_RECORD WHERE SENT_DATE BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays'";
                $result=mysql_query($sql,$myDb1) or die("$sql".mysql_error_js($myDb1));
		$ptotal=0;
		$atotal=0;
                for($i=1;$i<=count($monthDaysArray);$i++)
		{
			if($row=mysql_fetch_assoc($result))
			{
				$sentdate=$row["SENT_DATE"];
				$day=explode("-",$sentdate);
				if($day[2]<10)
					$day[2]=substr($day[2],1);
				$profiles_sent[$day[2]]=$row["PROFILE_SENT"];
				$alerts_sent[$day[2]]=$row["ALERT_SENT"]; 
				$ptotal+=$profiles_sent[$day[2]];
                                $atotal+=$alerts_sent[$day[2]];
			}
			                       
                }
		$smarty->assign("profiles_sent",$profiles_sent);
                $smarty->assign("alerts_sent",$alerts_sent);
		$smarty->assign("profile_sent_total",$ptotal);
		$smarty->assign("alert_sent_total",$atotal);
		$smarty->assign("searchFlag","1");
		$smarty->assign("searchMonth",$searchMonth);
		$smarty->assign("searchYear",$searchYear);
		$smarty->assign("monthDays",$monthDays);
		$smarty->assign("monthDaysArray",$monthDaysArray);
		$smarty->assign("CHECKSUM",$checksum);
		$smarty->display("va_profile_sent.htm");
		

	}
	else
	{                
		$k=0;
                while($k<=5)
                {
                        $yearArray[]=$todYear-$k;
                        $k++;
                }
                $monthArray=array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'May','06'=>'Jun','07'=>'Jul','08'=>'Aug','09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
                $smarty->assign('yearArray',$yearArray);
                $smarty->assign('monthArray',$monthArray);
                $smarty->assign('todYear',$todYear);
                $smarty->assign('todMonth',$todMonth);
		$smarty->assign("CHECKSUM",$checksum);
		$smarty->display("va_profile_sent.htm");
	}
}
else
{
	$smarty->assign('user',$user);
        $smarty->display("jsconnectError.tpl");
}
?>
