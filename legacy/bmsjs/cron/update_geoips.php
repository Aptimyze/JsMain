<?php
/**
 * @author: Sandipan Aich
 * @CreationDate: 29 Dec 2009
 * @Description: This script would run as cron on the first of every month in order to update the IP ranges
 */

shell_exec("apnaget http://geolite.maxmind.com/download/geoip/database/GeoIPCountryCSV.zip");
shell_exec("unzip GeoIPCountryCSV.zip");
shell_exec("rm -f GeoIPCountryCSV.zip");
$fp = fopen("GeoIPCountryWhois.csv","r");
while(!feof($fp))
{
	$values_string = trim(fgets($fp),"\n\r");
	$arr = explode(",",$values_string);
	if(trim($arr[5],'"') != 'India')
	continue;
	else
	{	$queries[] = "INSERT INTO bms2.COUNTRY_IP(BEGIN_IP,END_IP,BEGIN_IP_NUM,END_IP_NUM,COUNTRY_CODE,COUNTRY_NAME) VALUES($values_string)";}
}
shell_exec("rm GeoIPCountryWhois.csv");

chdir(dirname(__FILE__));
include_once("../classes/Mysql.class.php");

$mysql = new Mysql;
$mysql->connect();
$sql1="SELECT MAX(ID) AS MAXID FROM bms2.COUNTRY_IP";
$res = $mysql->query($sql1) or die($sql1." did not execute ...".mysql_error());
$row = $mysql->fetchAssoc($res);
$maxid = $row['MAXID']?$row['MAXID']:0;

$queries[] = "DELETE FROM bms2.COUNTRY_IP WHERE ID <= $maxid";
foreach($queries as $key => $sql)
{
	$mysql->query($sql) or die($sql." did not execute ...".mysql_error());
}
fclose($fp);
?>
