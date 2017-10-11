<?php

include("connect.inc");
$_SERVER['ajax_error']=1;
$db=connect_db();

if(!$j)
	$j=0;
$PAGELEN=20;
$mysql=new Mysql;

if(!($profileid))
	die("");

if($profileid && !is_numeric($profileid))
{
	include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
	ValidationHandler::getValidationHandler("","profileid is non integer in web/profile/myjs_iplog.php","Y");
}
if($j && !is_numeric($j))
{
	include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
	ValidationHandler::getValidationHandler("","variable j is non integer in web/profile/myjs_iplog.php","Y");
}

$myDbName=getProfileDatabaseConnectionName($profileid);
$myDb=$mysql->connect("$myDbName");
$sql="select SQL_CALC_FOUND_ROWS IPADDR,`TIME` from newjs.LOG_LOGIN_HISTORY where PROFILEID='$profileid' order by `TIME` DESC limit $j,$PAGELEN";
$res=$mysql->executeQuery($sql,$myDb);
$i=0;
while($row=$mysql->fetchArray($res))
{
	$IP_LOGIN[]=$i;
	$IP[]=$row['IPADDR'];
	$TIME[]=$row['TIME'];
}

$sql="select COUNT(*) as cnt from newjs.LOG_LOGIN_HISTORY where PROFILEID='$profileid'";
$resultcount=mysql_query_decide($sql,$myDb) or logError($ERROR_STRING,$sql,"ShowErrTemplate");
$countrow=mysql_fetch_row($resultcount);
$num_of_res=$countrow[0];
$next=-1;
$previous=-1;

if($num_of_res>$j+$PAGELEN)
	$next=$j+$PAGELEN;
if($j>0)
	$previous=$j-$PAGELEN;

if(is_array($TIME))
{
	if($country)
	{
		for($i=0;$i<count($TIME);$i++)
		{
			$time=$TIME[$i];
			$sql="SELECT CONVERT_TZ('$time','SYSTEM','right/Asia/Calcutta')";
			$res=mysql_query_decide($sql);
			if($row=mysql_fetch_array($res))
				$TIME[$i]=$row[0];
		
		}
	}
	for($i=0;$i<count($TIME);$i++)
	{
		$row['TIME']=$TIME[$i];
		$date_time=explode(" ",$row['TIME']);
	
		$time=explode("-",$date_time[0]);

		$day=$time[2];
		$month=$time[1];
		$year=$time[0];
		$timed=explode(":",$date_time[1]);
		$hour=$timed[0];
		$min=$timed[1];
	
		$row['TIME']=my_format_date($day,$month,$year,'',$hour,$min);	
		$DATE[]=$row['TIME'];
	}

	if(is_array($DATE))
	{
		$size=count($DATE);
		$response=$size."$";
		for($i=0;$i<$size;$i++)
		{
			$responseArr[]=$IP[$i]."#".$DATE[$i];	
		}
		$responsestr=implode(",",$responseArr);
		$response.=$responsestr;
		$response=$response."$".$next."$".$previous;;
	}

	echo $response;
	/*
	echo "IP--->";
	echo "<br>";
	print_r($IP);
	echo "<br>";
	echo "<br>";
	echo "<br>";
	echo "TIME--->";
	echo "<br>";
	print_r($TIME);
	echo "<br>";
	echo "<br>";
	echo "<br>";
	echo "IPLOGIN--->";
	print_r($IP_LOGIN);
	*/
}


