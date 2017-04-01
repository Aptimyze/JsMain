 <?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

ini_set("max_execution_time","0");
include("connect.inc");
$fileName =  $_SERVER["SCRIPT_FILENAME"];
$http_msg=print_r($_SERVER,true);
mail("reshu.rajput@gmail.com,lavesh.rawat@gmail.com","For DLL Movement - $fileName",$http_msg);
$db1=connect_db();
$db2=connect_slave();

$sql="set session wait_timeout=10000";
mysql_query($sql,$db1) or die(mysql_error($db1));
mysql_query($sql,$db2) or die(mysql_error($db2));

logTime();
//check for new communities that are to be created
newcommunities($db1);

logTime();

    $sql="TRUNCATE TABLE MATRIMONIAL_PHOTO1";
    mysql_query($sql,$db1) or die(mysql_error());
	
    $sql="TRUNCATE TABLE GET_MAT_COUNT_TEMP";
    mysql_query($sql,$db1) or die(mysql_error());
    
 
    
	$sql="SELECT * FROM MATRIMONIAL";
	$result=mysql_query($sql,$db1) or die(mysql_error());
	$temp=0;

	if($myrow=mysql_fetch_array($result))
	{
		do
		{
			unset($commarr);
			$id=$myrow["ID"];
			$gender=$myrow["GENDER"];
			$fld=$myrow["FLD"];
			$comm=$myrow["COMMUNITY"];
			$orgcomm=$comm;
			
			if($fld=="MTONGUE")
			{
				$colname="MTONGUE";
			}
			if($fld=="RELIGION")
			{
				$colname="CASTE";
				//REVAMP JS_DB_CASTE
include_once(JsConstants::$docRoot."/commonFiles/RevampJsDbFunctions.php");
				$comm = get_all_caste_revamp_js_db($comm,$db1,0);
				//REVAMP JS_DB_CASTE
			}
			
			if($fld=="COUNTRY")
			{
				$colname="COUNTRY_RES";
			}
			if($fld=="CITY")
			{
				$colname="CITY_RES";
			}

			$temp+=1;
			$MIN=$temp;
			
			// profiles that are private are not to be considered. We don't take 'C' case for PRIVACY as these profiles are not present in SEARCH_MALE and SEARCH_FEMALE
			if($gender=='M')
			{
				$isql="SELECT PROFILEID FROM SEARCH_MALE WHERE $colname IN ('$comm') AND HAVEPHOTO='Y' AND PRIVACY NOT IN ('R','F')";
			}
			else 
			{
				$isql="SELECT PROFILEID FROM SEARCH_FEMALE WHERE $colname IN ('$comm') AND HAVEPHOTO='Y' AND PRIVACY NOT IN ('R','F')";
			}
			$iresult=mysql_query($isql,$db2) or die(mysql_error($db2));
			$count=0;
			
			// insert only those records that don't have privacy settings for photos
			while($myprofile=mysql_fetch_array($iresult))
			{
				$isql="select count(*) from JPROFILE where PROFILEID='" . $myprofile["PROFILEID"] . "' and PHOTO_DISPLAY NOT IN ('F','C','H')";
				$ires=mysql_query($isql,$db2) or die(mysql_error($db2));
				
				$rescount=mysql_fetch_row($ires);
				
				if($rescount[0]>0)
				{
					$sql="INSERT INTO MATRIMONIAL_PHOTO1(PROFILEID) values ('" . $myprofile["PROFILEID"] . "')";
					mysql_query($sql,$db1) or die(mysql_error($db1));
					$count++;
				}
				
				mysql_free_result($ires);
			}
			
			mysql_free_result($iresult);

			$temp+=$count-1;
			$MAX=$temp;
			$sql_update="INSERT into GET_MAT_COUNT_TEMP(GENDER,FLD,CODE,MIN,MAX) values('$gender','$fld','$orgcomm','$MIN','$MAX')";
			mysql_query($sql_update,$db1) or die(mysql_error($db1));
		}while($myrow=mysql_fetch_array($result));
	}

    
    // rename the tables. This is done because script runs for 3-4 minutes and we don't want to truncate the live table MATRIMONIAL_PHOTO during that time
    $sql="rename table MATRIMONIAL_PHOTO to MATRIMONIAL_TEMP_A, MATRIMONIAL_PHOTO1 to MATRIMONIAL_PHOTO, MATRIMONIAL_TEMP_A to MATRIMONIAL_PHOTO1";
    mysql_query($sql,$db1) or die(mysql_error($db1));

    // rename the tables. This is done because script runs for 3-4 minutes and we don't want to truncate the live table GET_MAT_COUNT during that time

    $sql="rename table GET_MAT_COUNT to GET_MAT_TEMP, GET_MAT_COUNT_TEMP to GET_MAT_COUNT, GET_MAT_TEMP to GET_MAT_COUNT_TEMP";
    mysql_query($sql,$db1) or die(mysql_error($db1));
    
logTime();

function newcommunities($db)
{
	include("newcommunity.php");
}

?>
