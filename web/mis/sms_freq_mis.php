<?php
/******************************************************************************************************************
Filename    : sms_freq_mis.php
Description : Display the MIS for SMS frequency distribution[2716]
Created By  : Neha Verma
Created On  : 15 Feb 2008
*******************************************************************************************************************/


include_once("connect.inc");

$db2=connect_master();

//$data=authenticated($cid);
$data=authenticated($checksum);
$db= connect_misdb();
$_SERVER['DOCUMENT_ROOT']=JsConstants::$docRoot;
include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");

$mysql=new Mysql;
for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
        $myDbName=getActiveServerName($activeServerId,"slave");
        $myDb[$myDbName]=$mysql->connect("$myDbName","slave");


}

if(isset($data))
{
	if(!$today)
        $today=date("Y-m-d");
	$date_t= JSstrToTime($today);
        $today= gmdate('Y-m-d',$date_t - (43200));
        list($todYear,$todMonth,$todDay)=explode("-",$today);

	if($outside)
        {
                $CMDGo='Y';
		if(!$today)
			$today=date("Y-m-d");
//		$date_t= JSstrToTime($today);
  //              $today= gmdate('Y-m-d',$date_t - (86400));
                list($eyear,$emonth,$eday)=explode("-",$today);
		$date_t= JSstrToTime($today);
  		$lw_date= gmdate('Y-m-d',$date_t - (5*86400));
		list($syear,$smonth,$sday)=explode("-",$lw_date);
        }

	if($CMDGo)
	{
		$st_date=$syear."-".$smonth."-".$sday." 00:00:00";
                $end_date=$eyear."-".$emonth."-".$eday." 23:59:59";
		$k=0;
		while($k<=7)
		{
		$numArray[]=$k;
		$k++;
		}	
		$smarty->assign("numArray",$numArray);
		$count=0;
		if(!$outside)
		{
			$l=0;
			while($l<=6)
			{
				$numArray1[]=$l;
				$l++;
			}	
			
		}
		$smarty->assign("numArray1",$numArray1);
		$sql= "SELECT COUNT(*) AS CNT, PROFILEID FROM newjs.DAILY_CONTACT_SMS WHERE DATE BETWEEN '$st_date' AND '$end_date' GROUP BY PROFILEID ";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		if(mysql_num_rows($res)>0)
		{
			$sms= array();
			$num= array();
			$log_sum= array();
			$paid= array();
			$sub= array();
			while($row= mysql_fetch_array($res))
			{
				$pid= $row['PROFILEID'];
				$count= $row['CNT'];
				if($count>$max)
					$max=$count;
	
				$myDbName=getProfileDatabaseConnectionName($pid);
			
			        if(!$myDb[$myDbName])
			                $myDb[$myDbName]=$mysql->connect("$myDbName","slave");

				$sql1= "SELECT COUNT(*) AS CNT1 FROM newjs.LOGIN_HISTORY WHERE PROFILEID='$pid' AND LOGIN_DT BETWEEN '$st_date' AND '$end_date' GROUP BY PROFILEID ";
				$res1=$mysql->executeQuery($sql1,$myDb[$myDbName]);
				while($row1=$mysql->fetchArray($res1))
				{
					$login_cnt= $row1['CNT1'];
					$num[$count]++;
				}
				$sms[$count]++;
				$log_sum[$count]+=$login_cnt;
				$sql2= "SELECT SUBSCRIPTION FROM newjs.JPROFILE WHERE PROFILEID=$pid ";
				$res2= mysql_query_decide($sql2,$db) or die(mysql_error_js());
				$row2= mysql_fetch_assoc($res2);
				$subs= $row2['SUBSCRIPTION'];
				if($subs!="")
					$sub[$count]++;
						
			
			}
			
		}
/***************************************************************************************************************/
		$pd= array();
		$sql_p= "SELECT DISTINCT(PROFILEID) FROM newjs.DAILY_CONTACT_SMS WHERE DATE BETWEEN '$st_date' AND '$end_date'";
		$res_p= mysql_query_decide($sql_p,$db) or die(mysql_error_js());
		while($row_p=mysql_fetch_array($res_p))
		{
			$p= $row_p['PROFILEID'];
			$pd[$p]=1;
		}

		$cnt_m=0;
		$sql_male= "SELECT DISTINCT(SEARCH_MALE.PROFILEID) AS CNT FROM newjs.SEARCH_MALE LEFT JOIN newjs.DAILY_CONTACT_SMS ON SEARCH_MALE.PROFILEID = DAILY_CONTACT_SMS.PROFILEID WHERE DAILY_CONTACT_SMS.PROFILEID IS NULL OR DATE NOT BETWEEN '$st_date' AND '$end_date'";
		$res_male= mysql_query_decide($sql_male,$db) or die(mysql_error_js());
		while($row_male= mysql_fetch_array($res_male))
		{
			$prof=$row_male['CNT'];
			if(array_key_exists($prof,$pd));
			else
			{
				$profiles[]=$prof;
				$cnt_m++;
			}
		}
		$sms[0]=$cnt_m;
		$tot_profiles_m=implode(',',$profiles);
	/*	$sql2= "SELECT COUNT(*) AS CNT FROM newjs.LOGIN_HISTORY WHERE PROFILEID IN ($tot_profiles) And LOGIN_DT BETWEEN '$st_date' AND '$end_date' GROUP BY PROFILEID";
		$res2= mysql_query_decide($sql2) or die(mysql_error_js());
		while($row2= mysql_fetch_array($res2))
		{
			$log_sum[0]+= $row2['CNT'];
		}
		
		$sql2_male= "SELECT SUBSCRIPTION AS PAID FROM newjs.JPROFILE WHERE PROFILEID IN ($tot_profiles) ";
		$res2_male= mysql_query_decide($sql2_male) or die(mysql_error_js());
		while($row2_male= mysql_fetch_array($res2_male))
		{
			$p= $row2_male['PID'];
			$subs= $row2_male['PAID'];
                        if($subs!="")
                                $sub[0]++;

			$num[0]++;
		}

/******************************************************************************************************************/
$cnt_f=0;
$sql_female= "SELECT DISTINCT(SEARCH_FEMALE.PROFILEID) AS CNT FROM newjs.SEARCH_FEMALE LEFT JOIN newjs.DAILY_CONTACT_SMS ON SEARCH_FEMALE.PROFILEID = DAILY_CONTACT_SMS.PROFILEID WHERE DAILY_CONTACT_SMS.PROFILEID IS NULL OR DATE NOT BETWEEN '$st_date' AND '$end_date'";
                $res_female= mysql_query_decide($sql_female,$db) or die(mysql_error_js());
                while($row_female= mysql_fetch_array($res_female))
		{
                        $prof=$row_female['CNT'];
                        if(array_key_exists($prof,$pd));
                        else
			{
				$profiles_f[]=$prof;
                                $cnt_f++;
			}
                }
		
               	$sms[0]+=$cnt_f;
		$tot_profiles_f= implode(',',$profiles_f);
		$tot_profiles= $tot_profiles_m.",".$tot_profiles_f;
		foreach($myDb as $key=>$val)
                {
                        $conn=$myDb[$key];
                        $sql2= "SELECT COUNT(*) AS CNT FROM newjs.LOGIN_HISTORY WHERE PROFILEID IN ($tot_profiles) And LOGIN_DT BETWEEN '$st_date' AND '$end_date' GROUP BY PROFILEID";
                        $res2=$mysql->executeQuery($sql2,$conn);
                        while($row2=$mysql->fetchArray($res2))
                        {
                                $log_sum[0]+= $row2['CNT'];
                        }
                }

                $sql2_male= "SELECT SUBSCRIPTION AS PAID FROM newjs.JPROFILE WHERE PROFILEID IN ($tot_profiles) ";
                $res2_male= mysql_query_decide($sql2_male,$db) or die(mysql_error_js());
                while($row2_male= mysql_fetch_array($res2_male))
                {
                        $p= $row2_male['PID'];
                        $subs= $row2_male['PAID'];
                        if($subs!="")
                                $sub[0]++;

                        $num[0]++;
                }

/***************************************************************************************************************/
		for($i=0;$i<=6;$i++)
		{
			if($sms[$i]!=0)
			{
				$paid[$i]=($sub[$i]/$sms[$i])*100;
				$login[$i]=$log_sum[$i]/$sms[$i];
			}
		}
		for($j=7;$j<=$max;$j++)
		{
			$lg_sum=$log_sum[$j]+$lg_sum;
			$tot_sms=$sms[$j]+$tot_sms;
			$tot_sub=$sub[$j]+$tot_sub;

		}
		if($tot_sms>0)
                {
                        $login[$i]=$lg_sum/$tot_sms;
                        $paid[$i]=($tot_sub/$tot_sms)*100;
			$sms[$i]=$tot_sms;
                }

//	print_r($sms);	

		$smarty->assign("st_date",$st_date);
		$smarty->assign("end_date",$end_date);
		$smarty->assign("sms",$sms);
		$smarty->assign("login",$login);
		$smarty->assign("paid",$paid);
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
	$smarty->display("sms_freq_mis.htm");
}
else
{
        $smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}
?>
