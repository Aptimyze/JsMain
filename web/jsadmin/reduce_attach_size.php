<?php
include("connect.inc");
$db=connect_db();
ini_set('memory_limit',-1);

//$sql="SELECT ID,FILENAME,FILETYPE,CONTENT FROM jsadmin.PHOTO_ATTACHMENTS WHERE LENGTH(CONTENT)>5242880 AND FILETYPE LIKE 'image%'";
$sql="SELECT ID,FILENAME,FILETYPE,CONTENT FROM jsadmin.PHOTO_ATTACHMENTS WHERE ID IN (338,365,368,509,518,930,1059,1083,1089,1210,1234,1438,1446,1455) ";
$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
while($row=mysql_fetch_assoc($result))
{
	$name=$row["FILENAME"];
	$id=$row["ID"];
	$fp=fopen("test/$name","wb");
	if($fp)
	{
		 fwrite($fp,$row["CONTENT"]);
                 fclose($fp);
                 unset($fp);
	}
	if(!strstr($row["FILETYPE"],"jpeg")||!strstr($row["FILETYPE"],"jpg"))
	{
		$pos=strpos($name,".");
		$usename=substr($name,0,$pos);
		$usename.=".jpeg";
		passthru("convert test/".$name." test/".$usename."");
	}
	else
	$usename=$name;
	passthru("convert -quality 85 test/".$usename." test/".$usename."");
	$size=filesize("test/$usename");
	$fp=fopen("test/$usename","rb");
	if($fp)
	{
		$content=fread($fp,$size);
	}
	$usecontent=addslashes($content);
	$sqlins="UPDATE jsadmin.PHOTO_ATTACHMENTS SET CONTENT='$usecontent',FILETYPE='image/jpeg',FILENAME='$usename' WHERE ID='$id'";
	mysql_query_decide($sqlins) or die("$sqlins".mysql_error_js());
}

?>
