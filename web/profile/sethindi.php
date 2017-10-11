<?php

$today=date("Y-m-d");
list($yy,$mm,$dd)=explode("-",$today);

include("connect.inc");

if($val=='hin')
{

	$db=connect_db();

	$sql="UPDATE MIS.LANG_COUNT SET LANGCOUNT=LANGCOUNT+1 WHERE ENTRY_DT='$today' AND LANG='$val'";
	if(mysql_query_decide($sql))
	{
		if(mysql_affected_rows_js()==0)
		{
			$sql="INSERT INTO MIS.LANG_COUNT VALUES ('',NOW(),'$val','1')";
			mysql_query_decide($sql);
		}
	}

	setcookie("JS_LANG","hin",0,"/",$domain);
	//setcookie("JS_LANG","hin",mktime(0,0,0,$mm,$dd,$yy)+86400,"/");
}
else
	setcookie("JS_LANG","",0,"/",$domain);

if($_SERVER['HTTP_REFERER'])
	$SITE_URL=$_SERVER['HTTP_REFERER'];
else
	$SITE_URL="http://www.jeevansathi.com";

header("Location: $SITE_URL");
?>
