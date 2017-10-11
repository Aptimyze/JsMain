<?php
chdir(dirname(__FILE__));
include ("connect.inc");
include "arrays.php";//check
$ftp=fopen("mapping_for_sphinx1.php","w+");
if(!$ftp)
	exit;
fwrite($ftp,"<?php\r\n");
$db=connect_db();
fwrite($ftp,"//Indian cities with VALUE 4000 onwards\r\n");

$sql="SELECT VALUE FROM CITY_NEW WHERE TYPE!='STATE'";
$result=mysql_query($sql);
$i=4000;
while($row=mysql_fetch_array($result))
{
        fwrite($ftp,"\$SPHINX_CITY_RMAP['".$row[VALUE]."']=\"".$i."\";\t\t//".$row['VALUE']."\r\n");
        $i++;
}
mysql_free_result($result);//check


fwrite($ftp,"\r\n//HAVE PHOTO\r\n");
fwrite($ftp,"\$SPHINX_ORIGINAL_HAVEPHOTO[".ord("Y")."]=\"Yes\";\t\t//Y\r\n");
fwrite($ftp,"\$SPHINX_ORIGINAL_HAVEPHOTO[".ord("N")."]=\"No\";\t\t//N\r\n");

fwrite($ftp,"\r\n//HAVE CHILD\r\n");
foreach($CHILDREN as $var=>$var)
{
	$rav=strrev($var);
	fwrite($ftp,"\$SPHINX_ORIGINAL_CHILDREN[".ord($rav)."]=\"".$var."\";\t\t//".$var."\r\n");
}
unset($var);
unset($var);

fwrite($ftp,"\r\n//MANGLIK CHECK\r\n");
foreach($MANGLIK as $var=>$var)
{
        fwrite($ftp,"\$SPHINX_ORIGINAL_MANGLIK[".ord($var)."]=\"".$var."\";\t\t//".$var."\r\n");
}
unset($var);
unset($var);

fwrite($ftp,"\r\n//MARITAL STATUS\r\n");
foreach($MSTATUS as $var=>$var)
{
        fwrite($ftp,"\$SPHINX_ORIGINAL_MSTATUS[".ord($var)."]=\"".$var."\";\t\t//".$var."\r\n");
}
unset($var);
unset($var);

fwrite($ftp,"\r\n//GENDER\r\n");
foreach($GENDER as $var=>$var)
{
        fwrite($ftp,"\$SPHINX_ORIGINAL_GENDER[".ord($var)."]=\"".$var."\";\t\t//".$var."\r\n");
}
unset($var);
unset($var);

fwrite($ftp,"\r\n//DIET\r\n");
foreach($DIET as $var=>$var)
{
        fwrite($ftp,"\$SPHINX_ORIGINAL_DIET[".ord($var)."]=\"".$var."\";\t\t//".$var."\r\n");
}
unset($var);
unset($var);

fwrite($ftp,"\r\n//SMOKE\r\n");
foreach($SMOKE as $var=>$var)
{
        fwrite($ftp,"\$SPHINX_ORIGINAL_SMOKE[".ord($var)."]=\"".$var."\";\t\t//".$var."\r\n");
}
unset($var);
unset($var);

fwrite($ftp,"\r\n//DRINK\r\n");
foreach($DRINK as $var=>$var)
{
        fwrite($ftp,"\$SPHINX_ORIGINAL_DRINK[".ord($var)."]=\"".$var."\";\t\t//".$var."\r\n");
}

unset($var);
unset($var);
fwrite($ftp,"\r\n//SUBCRIPTION\r\n");
foreach($SUBSCRIPTION as $var=>$var)
{
        fwrite($ftp,"\$SPHINX_ORIGINAL_SUBSCRIPTION[".ord($var)."]=\"".$var."\";\t\t//".$var."\r\n");
}

unset($var);
unset($var);


fwrite($ftp,"?>\r\n");
fclose($ftp);

?>

