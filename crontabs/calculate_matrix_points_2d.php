<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/**********************************************************************************************
  FILENAME : calculate_matrix_points_2d.php
  DESCRIPTION : inserts data count of total memebers into different quadrant of matrix for 2d logic
  MODIFIED BY : Puneet Makkar
  MODIFIED ON : 09 May,2006
**********************************************************************************************/
include("connect.inc");
$db=connect_db();
                                                                                                                             
$sql="SELECT SCORE_POINTS,FRESHNESS_POINTS ,COUNT(*) AS CNT FROM SEARCH_MALE GROUP BY SCORE_POINTS, FRESHNESS_POINTS ORDER BY SCORE_POINTS DESC , FRESHNESS_POINTS DESC"; 
$result=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
											     
for($i=0;$i<16;$i++)
	$total[$i]=0;

while($myrow=mysql_fetch_array($result))
{
	if($myrow['SCORE_POINTS']==300 && $myrow['FRESHNESS_POINTS']==300)  
		$total[0]=$myrow['CNT'];	
	elseif($myrow['SCORE_POINTS']==300 && $myrow['FRESHNESS_POINTS']==150)  
		$total[1]=$myrow['CNT'];	
	elseif($myrow['SCORE_POINTS']==300 && $myrow['FRESHNESS_POINTS']==100)  
		$total[2]=$myrow['CNT'];	
	elseif($myrow['SCORE_POINTS']==150 && $myrow['FRESHNESS_POINTS']==300)  
		$total[3]=$myrow['CNT'];	
	elseif($myrow['SCORE_POINTS']==150 && $myrow['FRESHNESS_POINTS']==150)  
		$total[4]=$myrow['CNT'];	
	elseif($myrow['SCORE_POINTS']==150 && $myrow['FRESHNESS_POINTS']==100)  
		$total[5]=$myrow['CNT'];	
	elseif($myrow['SCORE_POINTS']==-50 && $myrow['FRESHNESS_POINTS']==300)  
		$total[6]=$myrow['CNT'];	
	elseif($myrow['SCORE_POINTS']==-50 && $myrow['FRESHNESS_POINTS']==150)  
		$total[7]=$myrow['CNT'];	
	elseif($myrow['SCORE_POINTS']==-50 && $myrow['FRESHNESS_POINTS']==100)  
		$total[8]=$myrow['CNT'];	
	elseif( ($myrow['SCORE_POINTS']+$myrow['FRESHNESS_POINTS']) ==49 )  
		$total[9]+=$myrow['CNT'];	
	elseif( ($myrow['SCORE_POINTS']+$myrow['FRESHNESS_POINTS']) ==48 )  
		$total[10]+=$myrow['CNT'];	
	elseif( ($myrow['SCORE_POINTS']+$myrow['FRESHNESS_POINTS']) ==47 )  
		$total[11]+=$myrow['CNT'];	
	elseif( ($myrow['SCORE_POINTS']+$myrow['FRESHNESS_POINTS']) ==46 )  
		$total[12]+=$myrow['CNT'];	
	elseif( ($myrow['SCORE_POINTS']+$myrow['FRESHNESS_POINTS']) ==45 )  
		$total[13]+=$myrow['CNT'];	
	elseif( ($myrow['SCORE_POINTS']+$myrow['FRESHNESS_POINTS']) ==44 )  
		$total[14]+=$myrow['CNT'];	
	elseif( ($myrow['SCORE_POINTS']+$myrow['FRESHNESS_POINTS']) ==43 )  
		$total[15]+=$myrow['CNT'];	
}
														     
$insert="INSERT INTO MIS.DATA_MATRIX_2D VALUES (now(),'M','$total[0]','$total[1]','$total[2]','$total[3]','$total[4]','$total[5]','$total[6]','$total[7]','$total[8]','$total[9]','$total[10]','$total[11]','$total[12]','$total[13]','$total[14]','$total[15]')";
mysql_query($insert) or  mail("puneetmakkar@jeevansathi.com","Error in calculating matrix points for 2d on search_male  date ".date(),"");


$sql="SELECT SCORE_POINTS,FRESHNESS_POINTS ,COUNT(*) AS CNT FROM SEARCH_FEMALE GROUP BY SCORE_POINTS, FRESHNESS_POINTS ORDER BY SCORE_POINTS DESC , FRESHNESS_POINTS DESC";
$result=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
															     
for($i=0;$i<16;$i++)
        $total[$i]=0;
                                                                                                                             
while($myrow=mysql_fetch_array($result))
{
        if($myrow['SCORE_POINTS']==300 && $myrow['FRESHNESS_POINTS']==300)
                $total[0]=$myrow['CNT'];
        elseif($myrow['SCORE_POINTS']==300 && $myrow['FRESHNESS_POINTS']==150)
                $total[1]=$myrow['CNT'];
        elseif($myrow['SCORE_POINTS']==300 && $myrow['FRESHNESS_POINTS']==100)
                $total[2]=$myrow['CNT'];
        elseif($myrow['SCORE_POINTS']==150 && $myrow['FRESHNESS_POINTS']==300)
                $total[3]=$myrow['CNT'];
        elseif($myrow['SCORE_POINTS']==150 && $myrow['FRESHNESS_POINTS']==150)
                $total[4]=$myrow['CNT'];
        elseif($myrow['SCORE_POINTS']==150 && $myrow['FRESHNESS_POINTS']==100)
                $total[5]=$myrow['CNT'];
        elseif($myrow['SCORE_POINTS']==-50 && $myrow['FRESHNESS_POINTS']==300)
                $total[6]=$myrow['CNT'];
        elseif($myrow['SCORE_POINTS']==-50 && $myrow['FRESHNESS_POINTS']==150)
                $total[7]=$myrow['CNT'];
        elseif($myrow['SCORE_POINTS']==-50 && $myrow['FRESHNESS_POINTS']==100)
                $total[8]=$myrow['CNT'];
	elseif( ($myrow['SCORE_POINTS']+$myrow['FRESHNESS_POINTS']) ==49 )  
		$total[9]+=$myrow['CNT'];	
	elseif( ($myrow['SCORE_POINTS']+$myrow['FRESHNESS_POINTS']) ==48 )  
		$total[10]+=$myrow['CNT'];	
	elseif( ($myrow['SCORE_POINTS']+$myrow['FRESHNESS_POINTS']) ==47 )  
		$total[11]+=$myrow['CNT'];	
	elseif( ($myrow['SCORE_POINTS']+$myrow['FRESHNESS_POINTS']) ==46 )  
		$total[12]+=$myrow['CNT'];	
	elseif( ($myrow['SCORE_POINTS']+$myrow['FRESHNESS_POINTS']) ==45 )  
		$total[13]+=$myrow['CNT'];	
	elseif( ($myrow['SCORE_POINTS']+$myrow['FRESHNESS_POINTS']) ==44 )  
		$total[14]+=$myrow['CNT'];	
	elseif( ($myrow['SCORE_POINTS']+$myrow['FRESHNESS_POINTS']) ==43 )  
		$total[15]+=$myrow['CNT'];	
}
                                                                                                                             
$insert="INSERT INTO MIS.DATA_MATRIX_2D VALUES (now(),'F','$total[0]','$total[1]','$total[2]','$total[3]','$total[4]','$total[5]','$total[6]','$total[7]','$total[8]','$total[9]','$total[10]','$total[11]','$total[12]','$total[13]','$total[14]','$total[15]')";
mysql_query($insert) or  mail("puneetmakkar@jeevansathi.com","Error in calculating matrix points for 2d on search_female date ".date(),"");

?>
