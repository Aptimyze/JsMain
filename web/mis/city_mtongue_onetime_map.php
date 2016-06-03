<?php
                                                                                                                             
ini_set('max_execution_time','0');
include("connect.inc");
$db=connect_misdb();
$db2=connect_master();
                                                                                                                             
$year='2007';
$month='03';
                                                                                                                             
$sql = "SELECT PROFILEID,SCORE,ENTRY_DT FROM incentive.MAIN_ADMIN_POOL WHERE ENTRY_DT BETWEEN '$year-$month-01' AND '$year-$month-31'";
$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
while($row=mysql_fetch_array($res))
{
        $pid=$row['PROFILEID'];
        $score=$row['SCORE'];
        $ent_dt=$row['ENTRY_DT'];
        $score=get_round_score($score);
                                                                                                                             
        $sql1="SELECT USERNAME,SUBSCRIPTION,CITY_RES,MTONGUE FROM newjs.JPROFILE WHERE PROFILEID='$pid'";
        $res1=mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js($db));
        $row1=mysql_fetch_array($res1);
                                                                                                                             
        if($row1["SUBSCRIPTION"])
                $subs='P';
        else
                $subs='F';

	$uname=$row1["USERNAME"];
        $city=$row1["CITY_RES"];
        $mtongue=$row1["MTONGUE"];

        $sql1="SELECT STATUS FROM billing.PURCHASES WHERE PROFILEID='$pid'";
        $res1=mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js($db));
        $row1=mysql_fetch_array($res1);
        if($row1["STATUS"]=='DONE')
                $ever_paid='P';
        else
                $ever_paid='F';
                                                                                                                             

        $sql1="SELECT MAPPING FROM newjs.SCORE_MTON_CITY_MAP WHERE CITY='$city' AND COMMUNITY='$mtongue'";
        $res1=mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js($db));
        if($row1=mysql_fetch_array($res1))
        {
                $map=$row1["MAPPING"];
                                                                                                                             
                $sql_i="INSERT INTO MIS.MAPPING_MTON_CITY VALUES('','$pid','$uname','$ent_dt','$score','$map','$subs','$ever_paid')";
                mysql_query_decide($sql_i,$db2) or die("$sql_i".mysql_error_js($db2));
        }
        else
        {
                $sql_i="INSERT INTO MIS.MAPPING_MTON_CITY VALUES('','$pid','$uname','$ent_dt','$score','O','$subs','$ever_paid')";
                mysql_query_decide($sql_i,$db2) or die("$sql_i".mysql_error_js($db2));
        }
        unset($city);
        unset($mtongue);
        unset($score);
}
?>
                                                                                                                             


