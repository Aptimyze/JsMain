<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

$count=0;
include("$docRoot/crontabs/connect.inc");
connect_db();

$sql="select STORYID from newjs.INDIVIDUAL_STORIES";
$res=mysql_query_decide($sql);

while($row=mysql_fetch_assoc($res))
{
	$storyId=$row[STORYID];
	$sql1="select USERNAME from newjs.SUCCESS_STORIES where ID=$storyId";
	$rss=mysql_query_decide($sql1);
	$count++;
	if($rwss=mysql_fetch_assoc($rss))
	{
		$username=$rwss[USERNAME];
		$sql2="select CASTE,RELIGION,OCCUPATION,CITY_RES,COUNTRY_RES,MTONGUE from newjs.JPROFILE where USERNAME='$username'";
		$rjp=mysql_query_decide($sql2);
		
		if($rowj=mysql_fetch_assoc($rjp))
		{
			//echo "$count";
			$caste=$rowj[CASTE];
			$religion=$rowj[RELIGION];
			$occupation=$rowj[OCCUPATION];
			$city=$rowj[CITY_RES];
			$country=$rowj[COUNTRY_RES];
			$mtongue=$rowj[MTONGUE];
			//echo "\n".
			$up="update newjs.INDIVIDUAL_STORIES set CASTE='$caste', RELIGION='$religion', OCCUPATION='$occupation', CITY='$city',COUNTRY='$country', MTONGUE='$mtongue' where STORYID=$storyId";
			mysql_query_decide($up) or die(mysql_error_js());
			
		}
	}

}

?>
