<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");
 
/*view_for_mis.php
TRANSFERS DATA FROM VIEW_FOR_MIS TABLE to new_table then to VIEW_FOR_MIS_MAIN and finally new_table & VIEW_FOR_MIS table is TRUNCATED
*/
include("connect.inc");
$dbS=connect_slave();
$today=date("Y-m-d");
$my_time = strtotime($today);
$my_time-=24*60*60;
$today=date("Y-m-d",$my_time);


//calculating no of views for males
$sql="SELECT FRESHNESS_POINTS, SCORE_POINTS, COUNT(*) AS CNT FROM MIS.VIEW_FOR_MIS AS V, SEARCH_MALE AS S WHERE S.PROFILEID= V.PROFILEID AND DATE='$today' GROUP BY FRESHNESS_POINTS,SCORE_POINTS";
$result=mysql_query($sql,$dbS) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                                                                                                                             
for($i=0;$i<16;$i++)
        $total[$i]=0;
while($myrow=mysql_fetch_array($result))
{
        if($myrow['SCORE_POINTS']==300 && $myrow['FRESHNESS_POINTS']==300)
        {
                $total[0]+=$myrow['CNT'];
        }
        elseif($myrow['SCORE_POINTS']==300 && $myrow['FRESHNESS_POINTS']==150)
        {
                $total[1]+=$myrow['CNT'];
        }
        elseif($myrow['SCORE_POINTS']==300 && $myrow['FRESHNESS_POINTS']==100)
        {
                $total[2]+=$myrow['CNT'];
        }
        elseif($myrow['SCORE_POINTS']==150 && $myrow['FRESHNESS_POINTS']==300)
        {
                $total[3]+=$myrow['CNT'];
        }
        elseif($myrow['SCORE_POINTS']==150 && $myrow['FRESHNESS_POINTS']==150)
        {
                $total[4]+=$myrow['CNT'];
        }
        elseif($myrow['SCORE_POINTS']==150 && $myrow['FRESHNESS_POINTS']==100)
        {
                $total[5]+=$myrow['CNT'];
        }
	 elseif($myrow['SCORE_POINTS']==-50 && $myrow['FRESHNESS_POINTS']==300)
        {
                $total[6]+=$myrow['CNT'];
        }
        elseif($myrow['SCORE_POINTS']==-50 && $myrow['FRESHNESS_POINTS']==150)
        {
                $total[7]+=$myrow['CNT'];
        }
        elseif($myrow['SCORE_POINTS']==-50 && $myrow['FRESHNESS_POINTS']==100)
        {
                $total[8]+=$myrow['CNT'];
        }
        elseif( ($myrow['SCORE_POINTS']+$myrow['FRESHNESS_POINTS']) ==49 )
        {
                $total[9]+=$myrow['CNT'];
        }
        elseif( ($myrow['SCORE_POINTS']+$myrow['FRESHNESS_POINTS']) ==48 )
        {
                $total[10]+=$myrow['CNT'];
        }
        elseif( ($myrow['SCORE_POINTS']+$myrow['FRESHNESS_POINTS']) ==47 )
        {
                $total[11]+=$myrow['CNT'];
        }
        elseif( ($myrow['SCORE_POINTS']+$myrow['FRESHNESS_POINTS']) ==46 )
        {
                $total[12]+=$myrow['CNT'];
        }
        elseif( ($myrow['SCORE_POINTS']+$myrow['FRESHNESS_POINTS']) ==45 )
        {
                $total[13]+=$myrow['CNT'];
        }
        elseif( ($myrow['SCORE_POINTS']+$myrow['FRESHNESS_POINTS']) ==44 )
        {
                $total[14]+=$myrow['CNT'];
        }
	 elseif( ($myrow['SCORE_POINTS']+$myrow['FRESHNESS_POINTS']) ==43 )
        {
                $total[15]+=$myrow['CNT'];
        }
}

@mysql_close($dbS);
$db=connect_db();

$insert="INSERT INTO MIS.DATA_MATRIX_2D_VIEWS VALUES ('$today','M','$total[0]','$total[1]','$total[2]','$total[3]','$total[4]','$total[5]','$total[6]','$total[7]','$total[8]','$total[9]','$total[10]','$total[11]','$total[12]','$total[13]','$total[14]','$total[15]')";
mysql_query($insert,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$insert,"ShowErrTemplate");
                                                                                                                         
                                                                                                                             
@mysql_close($db);
$dbS=connect_slave();
//calculating no of views for females
$sql="SELECT FRESHNESS_POINTS, SCORE_POINTS, COUNT(*) AS CNT FROM MIS.VIEW_FOR_MIS AS V, SEARCH_FEMALE AS S WHERE S.PROFILEID= V.PROFILEID AND DATE='$today' GROUP BY FRESHNESS_POINTS,SCORE_POINTS";
$result=mysql_query($sql,$dbS) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                                                                                                                             
for($i=0;$i<16;$i++)
        $total[$i]=0;
while($myrow=mysql_fetch_array($result))
{
        if($myrow['SCORE_POINTS']==300 && $myrow['FRESHNESS_POINTS']==300)
        {
                $total[0]+=$myrow['CNT'];
        }
        elseif($myrow['SCORE_POINTS']==300 && $myrow['FRESHNESS_POINTS']==150)
        {
                $total[1]+=$myrow['CNT'];
        }
        elseif($myrow['SCORE_POINTS']==300 && $myrow['FRESHNESS_POINTS']==100)
        {
                $total[2]+=$myrow['CNT'];
        }
        elseif($myrow['SCORE_POINTS']==150 && $myrow['FRESHNESS_POINTS']==300)
        {
                $total[3]+=$myrow['CNT'];
        }
	 elseif($myrow['SCORE_POINTS']==150 && $myrow['FRESHNESS_POINTS']==150)
        {
                $total[4]+=$myrow['CNT'];
        }
        elseif($myrow['SCORE_POINTS']==150 && $myrow['FRESHNESS_POINTS']==100)
        {
                $total[5]+=$myrow['CNT'];
        }
        elseif($myrow['SCORE_POINTS']==-50 && $myrow['FRESHNESS_POINTS']==300)
        {
                $total[6]+=$myrow['CNT'];
        }
        elseif($myrow['SCORE_POINTS']==-50 && $myrow['FRESHNESS_POINTS']==150)
        {
                $total[7]+=$myrow['CNT'];
        }
        elseif($myrow['SCORE_POINTS']==-50 && $myrow['FRESHNESS_POINTS']==100)
        {
                $total[8]+=$myrow['CNT'];
        }
        elseif( ($myrow['SCORE_POINTS']+$myrow['FRESHNESS_POINTS']) ==49 )
        {
                $total[9]+=$myrow['CNT'];
        }
        elseif( ($myrow['SCORE_POINTS']+$myrow['FRESHNESS_POINTS']) ==48 )
        {
                $total[10]+=$myrow['CNT'];
        }
        elseif( ($myrow['SCORE_POINTS']+$myrow['FRESHNESS_POINTS']) ==47 )
        {
                $total[11]+=$myrow['CNT'];
        }
        elseif( ($myrow['SCORE_POINTS']+$myrow['FRESHNESS_POINTS']) ==46 )
        {
                $total[12]+=$myrow['CNT'];
        }
	 elseif( ($myrow['SCORE_POINTS']+$myrow['FRESHNESS_POINTS']) ==45 )
        {
                $total[13]+=$myrow['CNT'];
        }
        elseif( ($myrow['SCORE_POINTS']+$myrow['FRESHNESS_POINTS']) ==44 )
        {
                $total[14]+=$myrow['CNT'];
        }
        elseif( ($myrow['SCORE_POINTS']+$myrow['FRESHNESS_POINTS']) ==43 )
        {
                $total[15]+=$myrow['CNT'];
        }
}
                                                                                                                             
@mysql_close($dbS);
$db=connect_db();
$insert="INSERT INTO MIS.DATA_MATRIX_2D_VIEWS VALUES ('$today','F','$total[0]','$total[1]','$total[2]','$total[3]','$total[4]','$total[5]','$total[6]','$total[7]','$total[8]','$total[9]','$total[10]','$total[11]','$total[12]','$total[13]','$total[14]','$total[15]')";
mysql_query($insert,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$insert,"ShowErrTemplate");	

$sql="RENAME TABLE MIS.VIEW_FOR_MIS TO MIS.tmp_table,MIS.new_table TO MIS.VIEW_FOR_MIS,MIS.tmp_table TO MIS.new_table";
$result=mysql_query($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
$flag=1;
$sql=" insert into MIS.VIEW_FOR_MIS_MAIN select count(*),GENDER,PAID,PHOTO,MTONGUE,'',DATE_SUB(CURDATE(),INTERVAL 1 DAY),MATCHALERT,VISITORALERT from MIS.new_table group by GENDER,MTONGUE,PAID,PHOTO,MATCHALERT,VISITORALERT";
$result=mysql_query($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
if(!$result)
	$flag=0;
$sql=" insert into MIS.VIEW_FOR_MIS_MAIN select count(*),GENDER,PAID,PHOTO,'',CASTE,DATE_SUB(CURDATE(),INTERVAL 1 DAY),MATCHALERT,VISITORALERT from MIS.new_table group by GENDER,CASTE,PAID,PHOTO,MATCHALERT,VISITORALERT";
$result=mysql_query($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
if(!$result)
	$flag=0;
if($flag)
{
	$ddl=connect_ddl();
	$sql="TRUNCATE TABLE MIS.new_table";
	$result=mysql_query($sql,$ddl) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
}
?>
