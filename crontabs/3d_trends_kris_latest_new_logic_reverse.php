<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

// THIS FILE IS COMMITED IN HTDOCS/MIS


// it calculates reverse score for a profile. 

$time_ini = microtime_float();

include("connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect_functions.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/commonFiles/incomeCommonFunctions.inc");
$mysqlObj=new Mysql;

$db=connect_slave();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);

$db2=connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db2);

ini_set(max_execution_time,0);
ini_set(memory_limit,-1);
ini_set(mysql.connect_timeout,-1);
ini_set(default_socket_timeout,25920000); 
ini_set(log_errors_max_len,0);

$ts=time();
$ts-=24*60*60;
$today=date("Y-m-d",$ts);
list($year1,$month1,$day1)=explode('-',$today);
$date1=$year1."-".$month1."-".$day1." 00:00:00";
$date2=$year1."-".$month1."-".$day1." 23:59:59";

for($serverId=0;$serverId<$noOfActiveServers;$serverId++)
{
	$myDbName=$slave_activeServers[$serverId];
	$myDb=$mysqlObj->connect($myDbName);
	mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$myDb);
	$sql="SELECT DISTINCT(SENDER) from newjs.CONTACTS,newjs.PROFILEID_SERVER_MAPPING where TYPE='I' AND TIME between '$date1' and '$date2' and PROFILEID=SENDER and SERVERID='$serverId'";
	$res0=$mysqlObj->executeQuery($sql,$myDb);
	while($row0=mysql_fetch_assoc($res0))
	{
		$sql="SELECT PROFILEID,AGE,HEIGHT,MTONGUE,CASTE,MANGLIK,CITY_RES,COUNTRY_RES,EDU_LEVEL_NEW,OCCUPATION,MSTATUS,INCOME from newjs.JPROFILE where PROFILEID='$row0[SENDER]'";	
		$res_main=mysql_query($sql,$db);
		while($row1=mysql_fetch_array($res_main))
		{
			$my_profileid=$row1['PROFILEID'];
			$my_age=$row1['AGE'];
			$my_height=$row1['HEIGHT'];
			$my_mtongue=$row1['MTONGUE'];
			$my_caste=$row1['CASTE'];
			$my_manglik=$row1['MANGLIK'];
			$my_city=$row1['CITY_RES'];
			$my_country=$row1['COUNTRY_RES'];
			$my_education=$row1['EDU_LEVEL_NEW'];
			$my_occupation=$row1['OCCUPATION'];
			$my_mstatus=$row1['MSTATUS'];
			$my_income=$row1['INCOME'];
			$my_income=getTrendsSortBy($my_income);	
			$profileid_str='';
			$receiver_str='';
			$spammer_time='';
			$sql3="select A.RECEIVER,A.TYPE,DATEDIFF(now(),A.TIME) as TIME_DIFF from  newjs.CONTACTS as A where A.SENDER='" . $row1["PROFILEID"] . "' $spammer_time order by A.TIME DESC ";
			 $res3=$mysqlObj->executeQuery($sql3,$myDb) or die(mysql_error($myDb));
				
			while($row3=mysql_fetch_assoc($res3))
			{
				$SEND_DETAILS[$row3['RECEIVER']]=$row3;
				$receiver_str.=",".$row3['RECEIVER'];
			}

			if(is_array($SEND_DETAILS))
			{
				$receiver_str=substr($receiver_str,1,strlen($receiver_str));
				$sql3="select PROFILEID from twowaymatch.TRENDS where PROFILEID IN($receiver_str)";
				$res=mysql_query($sql3,$db) or die("--".$sql3."---".mysql_error($db));
				while($row3=mysql_fetch_assoc($res))
				{
					//unset($SEND_DETAILS[$row3['PROFILEID']]);
					$REAL_DETAILS[$row3['PROFILEID']]=$SEND_DETAILS[$row3['PROFILEID']];
				}
				unset($SEND_DETAILS);
				$i=0;
				$SEND_DETAILS=$REAL_DETAILS;
				$profileid_str='';
				if($SEND_DETAILS)
				{
					foreach($SEND_DETAILS as $key=>$val)
					{
						//Only first 20 contacts
						if($i>=20)
							break;
						$i++;
				
						$row3=$val;
						$profileid_str.="'".$row3['RECEIVER']."',";
					}
				}
				$profileid_str=substr($profileid_str,0,-1);
			}
			unset($SEND_DETAILS);

			unset($REAL_DETAILS);

			if($profileid_str)
			{
				$sql_array[]="( W_CASTE * SUBSTRING( CASTE_VALUE_PERCENTILE, LOCATE( '#', CASTE_VALUE_PERCENTILE, POSITION( '|$my_caste#' IN CASTE_VALUE_PERCENTILE ) ) +1, LOCATE( '|', CASTE_VALUE_PERCENTILE, POSITION( '|$my_caste#' IN CASTE_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', CASTE_VALUE_PERCENTILE, POSITION( '|$my_caste#' IN CASTE_VALUE_PERCENTILE ) ) -1 ) )";
				
				$sql_array[]="( W_MTONGUE * SUBSTRING( MTONGUE_VALUE_PERCENTILE, LOCATE( '#', MTONGUE_VALUE_PERCENTILE, POSITION( '|$my_mtongue#' IN MTONGUE_VALUE_PERCENTILE ) ) +1, LOCATE( '|', MTONGUE_VALUE_PERCENTILE, POSITION( '|$my_mtongue#' IN MTONGUE_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', MTONGUE_VALUE_PERCENTILE, POSITION( '|$my_mtongue#' IN MTONGUE_VALUE_PERCENTILE ) ) -1 ) )";
				$sql_array[]="( W_AGE * SUBSTRING( AGE_VALUE_PERCENTILE, LOCATE( '#', AGE_VALUE_PERCENTILE, POSITION( '|$my_age#' IN AGE_VALUE_PERCENTILE ) ) +1, LOCATE( '|', AGE_VALUE_PERCENTILE, POSITION( '|$my_age#' IN AGE_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', AGE_VALUE_PERCENTILE, POSITION( '|$my_age#' IN AGE_VALUE_PERCENTILE ) ) -1 ) )";
					
				$sql_array[]="( W_INCOME * SUBSTRING( INCOME_VALUE_PERCENTILE, LOCATE( '#', INCOME_VALUE_PERCENTILE, POSITION( '|$my_income#' IN INCOME_VALUE_PERCENTILE ) ) +1, LOCATE( '|', INCOME_VALUE_PERCENTILE, POSITION( '|$my_income#' IN INCOME_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', INCOME_VALUE_PERCENTILE, POSITION( '|$my_income#' IN INCOME_VALUE_PERCENTILE ) ) -1 ) )";
				$sql_array[]="( W_HEIGHT * SUBSTRING( HEIGHT_VALUE_PERCENTILE, LOCATE( '#', HEIGHT_VALUE_PERCENTILE, POSITION( '|$my_height#' IN HEIGHT_VALUE_PERCENTILE ) ) +1, LOCATE( '|', HEIGHT_VALUE_PERCENTILE, POSITION( '|$my_height#' IN HEIGHT_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', HEIGHT_VALUE_PERCENTILE, POSITION( '|$my_height#' IN HEIGHT_VALUE_PERCENTILE ) ) -1 ) )";
				$sql_array[]="( W_EDUCATION * SUBSTRING( EDUCATION_VALUE_PERCENTILE, LOCATE( '#', EDUCATION_VALUE_PERCENTILE, POSITION( '|$my_education#' IN EDUCATION_VALUE_PERCENTILE ) ) +1, LOCATE( '|', EDUCATION_VALUE_PERCENTILE, POSITION( '|$my_education#' IN EDUCATION_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', EDUCATION_VALUE_PERCENTILE, POSITION( '|$my_education#' IN EDUCATION_VALUE_PERCENTILE ) ) -1 ) )";
				
				$sql_array[]="( W_OCCUPATION * SUBSTRING( OCCUPATION_VALUE_PERCENTILE, LOCATE( '#', OCCUPATION_VALUE_PERCENTILE, POSITION( '|$my_occupation#' IN OCCUPATION_VALUE_PERCENTILE ) ) +1, LOCATE( '|', OCCUPATION_VALUE_PERCENTILE, POSITION( '|$my_occupation#' IN OCCUPATION_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', OCCUPATION_VALUE_PERCENTILE, POSITION( '|$my_occupation#' IN OCCUPATION_VALUE_PERCENTILE ) ) -1 ) )";
				
				$sql_array[]="( W_CITY * SUBSTRING( CITY_VALUE_PERCENTILE, LOCATE( '#', CITY_VALUE_PERCENTILE, POSITION( '|$my_city#' IN CITY_VALUE_PERCENTILE ) ) +1, LOCATE( '|', CITY_VALUE_PERCENTILE, POSITION( '|$my_city#' IN CITY_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', CITY_VALUE_PERCENTILE, POSITION( '|$my_city#' IN CITY_VALUE_PERCENTILE ) ) -1 ) )";
				
				if($my_mstatus!='N')
					$sql_array[]="( W_MSTATUS * MSTATUS_M_P)";
				else			
					$sql_array[]="( W_MSTATUS * MSTATUS_N_P)";

				if($my_manglik=='M')	
					$sql_array[]="( W_MANGLIK * MANGLIK_M_P)";
				else			
					$sql_array[]="( W_MANGLIK * MANGLIK_N_P)";
			
				if($my_country=='51')	
					$sql_array[]="( W_NRI * NRI_N_P)";
				else			
					$sql_array[]="( W_NRI * NRI_M_P)";
				
				$sql_final="(".implode("+",$sql_array).")";
				unset($sql_array);	
				
				$sql4="select SUM($sql_final)/count(*) as score , count(*) as cnt FROM twowaymatch.TRENDS where PROFILEID IN ($profileid_str)"; 
				$res4=mysql_query($sql4,$db);
				$row4=mysql_fetch_array($res4);
		
				$sql5="UPDATE twowaymatch.TRENDS SET AVERAGE_REVERSE_SCORE = $row4[score] , REVERSE_COUNT=$row4[cnt] where PROFILEID='$my_profileid' "; 
				mysql_query($sql5,$db2);
			}		
		}		
	}
}	

		$time_end = microtime_float();
		$time = $time_end - $time_ini;
		$time = $time/3600;

		mail("vikas.jayna@jeevansathi.com","Reverse Scores calculated for Trends table in $time hours ".date('Y-m-d'),date('Y-m-d'));

		//echo 'Hours taken '.$time;
		//echo " script completed";


	function microtime_float()
	{
	    list($usec, $sec) = explode(" ", microtime());
	    return ((float)$usec + (float)$sec);
	}
	?>
