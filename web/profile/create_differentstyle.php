<?php
include ("connect.inc");
include JsConstants::$docRoot."/profile/arrays.php";//check
$ftp=fopen(JsConstants::$docRoot."/profile/differentstyle.php","w+");
if(!$ftp)
	exit;
fwrite($ftp,"<?php\r\n");
$db=connect_db();
fwrite($ftp,"//Indian cities with label 100 onwards\r\n");

$sql="SELECT * FROM CITY_NEW";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
	$row[ID]=$row[ID]+100;	
	fwrite($ftp,"\$SPHINX_CITY[".$row[ID]."]=\"".$row[LABEL]."\";\t\t//".$row['VALUE']."\r\n");
} 
mysql_free_result($result);//check

fwrite($ftp,"\r\n//American cities with label 0 onwards\r\n");
$sql="SELECT * FROM CITY_NEW";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
        fwrite($ftp,"\$SPHINX_CITY[".$row[ID]."]=\"".$row[LABEL]."\";\t\t//".$row['VALUE']."\r\n");
}
mysql_free_result($result);

fwrite($ftp,"\r\n//HAVE PHOTO\r\n");
fwrite($ftp,"\$SPHINX_HAVEPHOTO[".ord("Y")."]=\"Yes\";\t\t//Y\r\n");
fwrite($ftp,"\$SPHINX_HAVEPHOTO[".ord("N")."]=\"No\";\t\t//N\r\n");

fwrite($ftp,"\r\n//HAVE CHILD\r\n");
foreach($CHILDREN as $var=>$key)
{
	$rav=strrev($var);
	fwrite($ftp,"\$SPHINX_CHILDREN[".ord($rav)."]=\"".$key."\";\t\t//".$var."\r\n");
}
unset($var);
unset($key);

fwrite($ftp,"\r\n//MANGLIK CHECK\r\n");
foreach($MANGLIK as $var=>$key)
{
        fwrite($ftp,"\$SPHINX_MANGLIK[".ord($var)."]=\"".$key."\";\t\t//".$var."\r\n");
}
unset($var);
unset($key);

fwrite($ftp,"\r\n//MARITAL STATUS\r\n");
foreach($MSTATUS as $var=>$key)
{
        fwrite($ftp,"\$SPHINX_MSTATUS[".ord($var)."]=\"".$key."\";\t\t//".$var."\r\n");
}
unset($var);
unset($key);

fwrite($ftp,"\r\n//GENDER\r\n");
foreach($GENDER as $var=>$key)
{
        fwrite($ftp,"\$SPHINX_GENDER[".ord($var)."]=\"".$key."\";\t\t//".$var."\r\n");
}
unset($var);
unset($key);

fwrite($ftp,"\r\n//DIET\r\n");
foreach($DIET as $var=>$key)
{
        fwrite($ftp,"\$SPHINX_DIET[".ord($var)."]=\"".$key."\";\t\t//".$var."\r\n");
}
unset($var);
unset($key);

fwrite($ftp,"\r\n//SMOKE\r\n");
foreach($SMOKE as $var=>$key)
{
        fwrite($ftp,"\$SPHINX_SMOKE[".ord($var)."]=\"".$key."\";\t\t//".$var."\r\n");
}
unset($var);
unset($key);

fwrite($ftp,"\r\n//DRINK\r\n");
foreach($DRINK as $var=>$key)
{
        fwrite($ftp,"\$SPHINX_DRINK[".ord($var)."]=\"".$key."\";\t\t//".$var."\r\n");
}
unset($var);
unset($key);


fwrite($ftp,"?>\r\n");
fclose($ftp);

?>

