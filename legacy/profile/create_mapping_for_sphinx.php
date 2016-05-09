<?php
chdir(dirname(__FILE__));
include ("connect.inc");
include "arrays.php";//check
$ftp=fopen("mapping_for_sphinx.php","w+");
if(!$ftp)
	exit;
fwrite($ftp,"<?php\r\n");
$db=connect_db();

fwrite($ftp,"//Indian cities with VALUE 4000 onwards\r\n");

fwrite($ftp,"\r\n//HAVE PHOTO\r\n");
fwrite($ftp,"\$SPHINX_HAVEPHOTO[".ord("Y")."]=\"Yes\";\t\t//Y\r\n");
fwrite($ftp,"\$SPHINX_HAVEPHOTO[".ord("N")."]=\"No\";\t\t//N\r\n");
fwrite($ftp,"\$SPHINX_HAVEPHOTO[\"Y\"]=\"Yes\";\t\t//Y\r\n");
fwrite($ftp,"\$SPHINX_HAVEPHOTO[\"N\"]=\"No\";\t\t//N\r\n");

fwrite($ftp,"\r\n//HAVE CHILD\r\n");
foreach($CHILDREN as $var=>$key)
{
	$rav=strrev($var);
	fwrite($ftp,"\$SPHINX_CHILDREN[".ord($rav)."]=\"".$key."\";\t\t//".$var."\r\n");
	fwrite($ftp,"\$SPHINX_CHILDREN[\"$var\"]=\"".$key."\";\t\t//".$var."\r\n");
}
unset($var);
unset($key);

fwrite($ftp,"\r\n//MANGLIK CHECK\r\n");
foreach($MANGLIK as $var=>$key)
{
	if($var=='M')
		$key='Manglik';
	if($var=='N')
		$key='Non Manglik';
	fwrite($ftp,"\$SPHINX_MANGLIK[\"$var\"]=\"".$key."\";\t\t//".$var."\r\n");
        fwrite($ftp,"\$SPHINX_MANGLIK[".ord($var)."]=\"".$key."\";\t\t//".$var."\r\n");
}
unset($var);
unset($key);

fwrite($ftp,"\r\n//MARITAL STATUS\r\n");
foreach($MSTATUS as $var=>$key)
{
	fwrite($ftp,"\$SPHINX_MSTATUS[\"$var\"]=\"".$key."\";\t\t//".$var."\r\n");
        fwrite($ftp,"\$SPHINX_MSTATUS[".ord($var)."]=\"".$key."\";\t\t//".$var."\r\n");
}
unset($var);
unset($key);

fwrite($ftp,"\r\n//GENDER\r\n");
foreach($GENDER as $var=>$key)
{
	fwrite($ftp,"\$SPHINX_GENDER[\"$var\"]=\"".$key."\";\t\t//".$var."\r\n");
        fwrite($ftp,"\$SPHINX_GENDER[".ord($var)."]=\"".$key."\";\t\t//".$var."\r\n");
}
unset($var);
unset($key);

fwrite($ftp,"\r\n//DIET\r\n");
foreach($DIET as $var=>$key)
{
	fwrite($ftp,"\$SPHINX_DIET[\"$var\"]=\"".$key."\";\t\t//".$var."\r\n");
        fwrite($ftp,"\$SPHINX_DIET[".ord($var)."]=\"".$key."\";\t\t//".$var."\r\n");
}
unset($var);
unset($key);

fwrite($ftp,"\r\n//SMOKE\r\n");
foreach($SMOKE as $var=>$key)
{
	fwrite($ftp,"\$SPHINX_SMOKE[\"$var\"]=\"".$key."\";\t\t//".$var."\r\n");
        fwrite($ftp,"\$SPHINX_SMOKE[".ord($var)."]=\"".$key."\";\t\t//".$var."\r\n");
}
unset($var);
unset($key);

fwrite($ftp,"\r\n//DRINK\r\n");
foreach($DRINK as $var=>$key)
{
	fwrite($ftp,"\$SPHINX_DRINK[\"$var\"]=\"".$key."\";\t\t//".$var."\r\n");
        fwrite($ftp,"\$SPHINX_DRINK[".ord($var)."]=\"".$key."\";\t\t//".$var."\r\n");
}
unset($var);
unset($key);
fwrite($ftp,"\r\n//SUBSCRIPTION\r\n");
foreach($SUBSCRIPTION as $var=>$key)
{
        fwrite($ftp,"\$SPHINX_SUBSCRIPTION[\"$var\"]=\"".$key."\";\t\t//".$var."\r\n");
        fwrite($ftp,"\$SPHINX_SUBSCRIPTION[".ord($var)."]=\"".$key."\";\t\t//".$var."\r\n");
}
unset($var);
unset($key);

fwrite($ftp,"\r\n//INCOME\r\n");
$sql="SELECT VALUE,LABEL FROM INCOME ORDER BY VALUE LIMIT 18";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
	$lab=str_replace("Rs.","Rs ",$row[LABEL]);
	$lab=str_replace("10,00,001 - 15,00,000","10lacs +",$lab);
	$rep=array(",00,001",",00,000");
        fwrite($ftp,"\$SPHINX_INCOME_DROP[".$row[VALUE]."]=\"".str_replace($rep,"lacs",$lab)."\";\t\t//".$row['VALUE']."\r\n");
}
mysql_free_result($result);

fwrite($ftp,"?>\r\n");
fclose($ftp);

?>

