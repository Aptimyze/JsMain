<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
include(JsConstants::$cronDocRoot."/crontabs/connect.inc");

$db=connect_db();


////	Code to remove deleted/screened profiles from MAIN_ADMIN table starts here

$ts=time();
$curdate=date("Y-m-d H:i:s",$ts);
$ts-=7*24*60*60;
$olddate=date("Y-m-d",$ts);
unset($ts);
$ts=time();
$ts-=2*24*60*60;
$twodayback=date("Y-m-d",$ts);
unset($ts);

@mysql_select_db("jsadmin",$db);

$sql="DELETE MAIN_ADMIN.* FROM MAIN_ADMIN left join newjs.JPROFILE  on MAIN_ADMIN.PROFILEID=newjs.JPROFILE.PROFILEID WHERE newjs.JPROFILE.ACTIVATED='D' AND MAIN_ADMIN.ALLOT_TIME<='$curdate'";
mysql_query($sql) or logError($sql,$db);
unset($curdate);

$sql="DELETE jsadmin.MAIN_ADMIN.* FROM jsadmin.MAIN_ADMIN  left join newjs.JPROFILE  on MAIN_ADMIN.PROFILEID=newjs.JPROFILE.PROFILEID WHERE newjs.JPROFILE.SCREENING=1099511627775 AND MAIN_ADMIN.ALLOT_TIME<='$olddate'";
//$sql="DELETE a.* FROM jsadmin.MAIN_ADMIN a left join newjs.JPROFILE b on a.PROFILEID=b.PROFILEID WHERE b.SCREENING=1099511627775 AND a.ALLOT_TIME<='$olddate'";
mysql_query($sql) or logError($sql,$db);
//unset($olddate);

////	Code to remove deleted/screened profiles from MAIN_ADMIN table ends here


////	Code to allot unalloted profiles of over 7 days to mahesh

//query commented by shiv on 9th aug 2007 to remove hidden profiles from screening. they will be screened when unhidden
//$sql="SELECT PROFILEID,USERNAME,MOD_DT, SUBSCRIPTION, SCREENING FROM newjs.JPROFILE WHERE SCREENING<'1099511627775' AND ACTIVATED IN ('Y','H') AND MOD_DT<='$olddate'";
$sql="SELECT PROFILEID,USERNAME,MOD_DT, SUBSCRIPTION, SCREENING FROM newjs.JPROFILE WHERE SCREENING<'1099511627775' AND ACTIVATED='Y' AND MOD_DT<='$olddate'";
$res=mysql_query($sql) or logError($sql,$db);
while($row=mysql_fetch_array($res))
{
	$receivetime=$row['MOD_DT'];
	$submittime=newtime($receivetime,0,$screen_time,0);

	$sql_i="INSERT IGNORE INTO jsadmin.MAIN_ADMIN (PROFILEID, USERNAME, RECEIVE_TIME, SUBMIT_TIME, ALLOT_TIME, ALLOTED_TO, SCREENING_TYPE, SUBSCRIPTION_TYPE, SCREENING_VAL) values('".$row['PROFILEID']."','".addslashes($row['USERNAME'])."','$receivetime','$submittime',NOW(), 'mahesh','O', '".$row['SUBSCRIPTION']."','".$row['SCREENING']."')";
	mysql_query($sql_i) or logError($sql_i,$db);
}

//$sql="SELECT PROFILEID,USERNAME,MOD_DT, SUBSCRIPTION, SCREENING FROM newjs.JPROFILE WHERE SCREENING<'1099511627775' AND ACTIVATED IN ('N','U') AND INCOMPLETE = 'N' AND MOD_DT<='$olddate'";
$sql="SELECT PROFILEID,USERNAME,MOD_DT, SUBSCRIPTION, SCREENING,ENTRY_DT FROM newjs.JPROFILE WHERE ACTIVATED IN ('N','U') AND INCOMPLETE = 'N' AND ENTRY_DT<='$twodayback'";
$res=mysql_query($sql) or logError($sql,$db);
$objUpdate = JProfileUpdateLib::getInstance();
while($row=mysql_fetch_array($res))
{
	$receivetime=$row['ENTRY_DT'];
	$submittime=newtime($receivetime,0,$screen_time,0);
	$row[SCREENING]=0;

	$sql_i="INSERT IGNORE INTO jsadmin.MAIN_ADMIN (PROFILEID, USERNAME, RECEIVE_TIME, SUBMIT_TIME, ALLOT_TIME, ALLOTED_TO, SCREENING_TYPE, SUBSCRIPTION_TYPE, SCREENING_VAL) values('".$row['PROFILEID']."','".addslashes($row['USERNAME'])."','$receivetime','$submittime',NOW(), 'mahesh','O', '".$row['SUBSCRIPTION']."','".$row['SCREENING']."')";
	mysql_query($sql_i) or logError($sql_i,$db);
	//$sql_up="update newjs.JPROFILE set ACTIVATED='N',SCREENING=0 where ACTIVATED IN ('N','U') AND INCOMPLETE = 'N' AND PROFILEID=$row[PROFILEID]";
  //mysql_query($sql_up) or logError($sql,$db);
  $arrFields = array('ACTIVATED'=>'N','SCREENING'=>0);
  $objUpdate->editJPROFILE($arrFields,$row[PROFILEID],"PROFILEID","ACTIVATED IN ('N','U') AND INCOMPLETE = 'N'");
}
unset($objUpdate);
////	Code to allot unalloted profiles of over 7 days to mahesh ends here



function newtime($t1,$d,$h,$m)
{
//changing time t1 to GMT from EST and then addin 5hrs 30 min to convert in IST
        $t1=strftime("%Y-%m-%d %H:%M",JSstrToTime("$t1 + 10 hours 30 minutes"));

        global $db;
        $office_hour1=9;
        $office_hour2=22;
        $office_work=$office_hour2-$office_hour1;
        $t1_year=substr($t1,0,4);
        $t1_month=substr($t1,5,2);
        $t1_day=substr($t1,8,2);
        $t1_hour=substr($t1,11,2);
        if($t1_hour>=$office_hour2)
        {
                $timeadjust=0;
                $t1=strftime("%Y-%m-%d $office_hour1:30",JSstrToTime("$t1 + 1 days"));
        }
        elseif($t1_hour<$office_hour1)
        {
                $timeadjust=0;
                $t1=strftime("%Y-%m-%d $office_hour1:30",JSstrToTime("$t1"));
        }
        elseif($t1_hour>$office_hour1 && $t1_hour<$office_hour2)
        {
                list($date,$time)=explode(" ",$t1);
                list($yy,$mm,$dd)=explode("-",$date);
                list($hr,$min,$sec)=explode(":",$time);
                $t1_timestamp=mktime($hr,$min,$sec,$mm,$dd,$yy);
                $t2_timestamp=mktime($office_hour2,0,0,$mm,$dd,$yy);
                $timeadjust=$t2_timestamp - $t1_timestamp;

                $t1=strftime("%Y-%m-%d $office_hour1:30",JSstrToTime("$t1 + 1 days"));
        }
        $daygap= (int) ($h/$office_work);
        $total_days=$d+$daygap;
        $hoursgap= $h%$office_work;
        $newdate=strftime("%Y-%m-%d %H:%M",JSstrToTime("$t1 + $total_days days $hoursgap hours $m minutes"));
        list($date_new,$time_new)=explode(" ",$newdate);
        list($yy,$mm,$dd)=explode("-",$date_new);
        list($hr,$min)=explode(":",$time_new);
        $sec=0;

        $newdate=mktime($hr,$min,$sec,$mm,$dd,$yy);
        $newdate=date("Y-m-d H:i",$newdate - $timeadjust);
        $newhour=strftime("%H",JSstrToTime("$newdate"));
        if($newhour>=$office_hour2 || $newhour<$office_hour1)
        {
                $hoursgap=11+$hoursgap;
                $newdate=strftime("%Y-%m-%d %H:%M",JSstrToTime("$t1 + $total_days days $hoursgap hours $m minutes"));
        }
        $time1_new=($t1_year."-".$t1_month."-".$t1_day);
        $time2_new=strftime("%Y-%m-%d",JSstrToTime("$newdate"));
        $return_date=$newdate;
        $flag=0;

        $sql= "SELECT count(DATE) NUM from jsadmin.HOLIDAY where DATE>='$time1_new' and DATE<='$time2_new'";
        $result=mysql_query($sql) or logError($sql,$db);
        $myrow=mysql_fetch_row($result);
        $holidays=$myrow[0];
        $return_date=strftime("%Y-%m-%d %H:%M",JSstrToTime("$newdate + $holidays days 0 hours 0 minutes" ));

        while($flag==0)
        {
                $sql1= "SELECT count(DATE) NUM from jsadmin.HOLIDAY where DATE='".strftime("%Y-%m-%d",JSstrToTime("$return_date"))."'";
                $result1=mysql_query($sql1) or logError($sql1,$db);
                $myrow1=mysql_fetch_row($result1);
                if($myrow1[0]>0)
                {
                        $return_date=strftime("%Y-%m-%d %H:%M",JSstrToTime("$return_date + 1 day"));
                }
                else
                        $flag=1;
        }

        list($ret_date,$ret_time)=explode(" ",$return_date);
        list($yy,$mm,$dd)=explode("-",$ret_date);
        list($hr,$min)=explode(":",$ret_time);
        $sec=0;
        $return_dt=mktime($hr,$min,$sec,$mm,$dd,$yy);
        $hourdiff = "+10.5";
        $timeadjust = ($hourdiff * 60 * 60);

        $return_date=date("Y-m-d H:i:s",$return_dt - $timeadjust);

        return $return_date;
}

?>
