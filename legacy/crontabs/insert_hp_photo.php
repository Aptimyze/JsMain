<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


include("connect.inc");
$fileName =  $_SERVER["SCRIPT_FILENAME"];
$http_msg=print_r($_SERVER,true);
mail("reshu.rajput@gmail.com,lavesh.rawat@gmail.com","For DLL Movement - $fileName",$http_msg);

connect_db();
logTime();
$filename="$_SERVER[DOCUMENT_ROOT]/profile/getcount.php";
if(is_writable($filename))
{
	if(!$handle=fopen($filename,'w'))
	{
		echo "Cannot open file ($filename)";
                exit;
        }

	fwrite($handle,"<?php\n");
//	fwrite($handle,"function getcount()\n{");
	$maleid_min=1;
	fwrite($handle,"\$maleid_min=$maleid_min;\n");
	$sqltrun="TRUNCATE TABLE HOMEPAGE_PHOTO";
	mysql_query($sqltrun) or logError($sql);

	$sql="INSERT INTO HOMEPAGE_PHOTO(PROFILEID) SELECT PROFILEID FROM JPROFILE WHERE GENDER='M' AND ACTIVATED='Y' AND HAVEPHOTO='Y' AND PHOTOGRADE='A' AND PHOTO_DISPLAY='A'";
	//$sql="INSERT INTO HOMEPAGE_PHOTO(PROFILEID) SELECT PROFILEID FROM JPROFILE WHERE GENDER='M' AND ACTIVATED='Y' AND HAVEPHOTO='Y'";
	mysql_query($sql) or logError($sql);
	$maleid_max=mysql_affected_rows();
	fwrite($handle,"\$maleid_max=$maleid_max;\n");
	$femaleid_min=$maleid_max+1;
	fwrite($handle,"\$femaleid_min=$femaleid_min;\n");
	$sql="INSERT INTO HOMEPAGE_PHOTO(PROFILEID) SELECT PROFILEID FROM JPROFILE WHERE GENDER='F' AND ACTIVATED='Y' AND HAVEPHOTO='Y' AND PHOTOGRADE='A' AND PHOTO_DISPLAY='A'";
	//$sql="INSERT INTO HOMEPAGE_PHOTO(PROFILEID) SELECT PROFILEID FROM JPROFILE WHERE GENDER='F' AND ACTIVATED='Y' AND HAVEPHOTO='Y'";
        mysql_query($sql) or logError($sql);
	$femaleid_max=$femaleid_min + mysql_affected_rows() - 1;
	fwrite($handle,"\$femaleid_max=$femaleid_max;\n");
//	fwrite($handle,"}");
	fwrite($handle,"?>");
	fclose($handle);
}
else
{
	echo "The file $filename is not writable";
}
logTime();
?>
