<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

	include("connect.inc");
	
	$db=connect_db();
	
	$sql="truncate table jeevansathi.PHONE_TABLE";
	mysql_query($sql) or die($sql);
	
	$sql="insert into jeevansathi.PHONE_TABLE select USERNAME,PASSWORD,GENDER,PHONE_RES,PHONE_MOB,CONTACT,CITY_RES from newjs.JPROFILE where (PHONE_RES<>'' or PHONE_MOB<>'' or CONTACT<>'') and CITY_RES in ('GU01','KA02','GU04','MP02','PH00','TN02','TN04','AP03','MP08','RA07','KE03','WB05','MH04','MH05','UP25','MH08','GU10','AP11','MH02','DE00','HA03') and SUBSCRIPTION=''";
	mysql_query($sql) or die($sql);
	
	$sql="update jeevansathi.PHONE_TABLE,newjs.CITY_NEW set jeevansathi.PHONE_TABLE.CITY_RES=newjs.CITY_NEW.LABEL where jeevansathi.PHONE_TABLE.CITY_RES=newjs.CITY_NEW.VALUE";
	mysql_query($sql) or die($sql);
?>