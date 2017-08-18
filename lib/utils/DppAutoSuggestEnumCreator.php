<?php
/*********************************************************************************************
Script name     :      	testResgistration.php 
Script Type     :       One time
Created On      :       26may Apr 13
Created By      :       Nitesh Sethi
Description     :       Storing auto suggest enum array values corresponding to user profile fields mapping 
**********************************************************************************************/
$socialRoot=realpath(dirname(__FILE__)."/../..");
$fp=fopen($socialRoot."/lib/model/lib/forms/ENUM/DppAutoSuggestEnum.php","w");
$flag_using_php5=1;
include_once($socialRoot."/crontabs/connect.inc");
$mysqlObj=new Mysql;
$myDbName=$activeServers[0];
	$db=$mysqlObj->connect("master");
	mysql_query("set session wait_timeout=10000",$db);
fwrite($fp,"<?php
/**
 * DppAutoSuggestEnum class
 * Creates constant variable used in auto suggest DPP classes.
 *
 * Below is the demonstration on how to use this class
 * <code>
 * //if want to fetch preset auto Suggested constant 
 * DppAutoSuggestEnum::\$INCOME_ARRAY;

 * </code>
 * 
 * PHP versions 4 and 5
 * @package   jeevansathi
 * @subpackage register
 * @author    Nitesh Sethi<nitesh.s@jeevansathi.com>
 * @copyright 2013 Nitesh Sethi
 */
class DppAutoSuggestEnum
{");
 
  
		
		fwrite($fp,"\npublic static \$HANDICAPPED=\"'N','1','2','3','4'\";");
		$sql_field= "SELECT DISTINCT A.ID,P.FIELD_NAME FROM newjs.AUTO_SUGGEST_DPP as A,reg.PROFILE_FIELDS as P where A.ID=P.ID ";
		$res_field=$mysqlObj->executeQuery($sql_field,$db) or die(mysql_error());
		$id="";
			$id1=-1;
		fwrite($fp,"\n\n\npublic static \$FIELD_ID_ARRAY=array(");
		while ($row1=mysql_fetch_array($res_field))
		{
			if($id!=$row1["ID"])
			{
				$id1++;
				if($id1){
					fwrite($fp,"\",");}
				$id=$row1["ID"];
			}
			fwrite($fp,"\"".$row1["ID"]."\"=>\"".$row1["FIELD_NAME"]);
		}
		fwrite($fp,"\");");
		fwrite($fp,"\n\n\npublic static \$AUTO_SUGGEST_ARRAY=array(");
		$sql_insert_backup1= "select ID,ACTUAL_VALUE,AUTO_SUGGEST_DPP_VALUE,METHOD_MAPPING from newjs.AUTO_SUGGEST_DPP ";
		$res_select1=$mysqlObj->executeQuery($sql_insert_backup1,$db) or die(mysql_error());
		$id="";
			$id1=-1;
		while ($row1=mysql_fetch_array($res_select1))
		{
			
			if($id!=$row1["ID"])
			{
				$id1++;
				if($id1){
					fwrite($fp,"\")),\n");}
				$id=$row1["ID"];
				fwrite($fp,"\"".$row1["ID"]."\"=>array(");
			}
			else
			fwrite($fp,"\"),\n\t");
		fwrite($fp,"\"".$row1["ACTUAL_VALUE"]."\"=>array(\"0\"=>\"".$row1["AUTO_SUGGEST_DPP_VALUE"]."\",\"1\"=>\"".$row1["METHOD_MAPPING"]);
			
		}
		fwrite($fp,"\")));");
	
	$arr=array();
		$arr["D"]["Y"] = "'M','A','N','D'";
		$arr["M"]["Y"] = "'M','A','D'";
		$arr["A"]["Y"] = "'M','A','D'";
		$arr["N"]["Y"] = "'N','D'";
		$arr["D"]["N"] = "'M','A','N','D'";
		$arr["M"]["N"] = "'M','A','N','D'";
		$arr["A"]["N"] = "'M','A','N','D'";
		$arr["N"]["N"] = "'M','A','N','D'";
		fwrite($fp,"\n\n\npublic static \$MANGLIK_ARRAY=array(");
		$id="";
			$id1=-1;
foreach($arr as $k=>$v)
foreach($v as$key=>$val)
{
	if($id!=$k)
			{
				$id1++;
				if($id1){
					fwrite($fp,"\"),\n");}
				$id=$k;
				fwrite($fp,"\"".$k."\"=>array(");
			}
			else
			fwrite($fp,"\",\n\t");	
		fwrite($fp,"\"".$key."\"=>\"".$val);
	
}
fwrite($fp,"\"));");


	
	$arr=array();
		$arr["F"]["N"]["18"]["22"] = "29";
		$arr["F"]["N"]["19"]["22"] = "30";
		$arr["F"]["N"]["20"]["23"] = "31";
		$arr["F"]["N"]["21"]["23"] = "31";
		$arr["F"]["N"]["22"]["24"] = "31";
		$arr["F"]["N"]["23"]["25"] = "31";
		$arr["F"]["N"]["24"]["25"] = "32";
		$arr["F"]["N"]["25"]["26"] = "32";
		$arr["F"]["N"]["26"]["26"] = "32";
		$arr["F"]["N"]["27"]["27"] = "33";
		$arr["F"]["N"]["28"]["28"] = "33";
		$arr["F"]["N"]["29"]["29"] = "34";
		$arr["F"]["N"]["30"]["29"] = "35";
		$arr["F"]["N"]["31"]["30"] = "37";
		$arr["F"]["N"]["32"]["31"] = "38";
		$arr["F"]["N"]["33"]["32"] = "39";
		$arr["F"]["N"]["34"]["33"] = "40";
		$arr["F"]["N"]["35"]["33"] = "41";
		$arr["F"]["N"]["36"]["34"] = "42";
		$arr["F"]["N"]["37"]["35"] = "43";
		$arr["F"]["N"]["38"]["36"] = "44";
		$arr["F"]["N"]["39"]["37"] = "46";
		$arr["F"]["N"]["40"]["37"] = "47";
		$arr["F"]["N"]["41"]["37"] = "48";
		$arr["F"]["N"]["42"]["38"] = "49";
		$arr["F"]["N"]["43"]["39"] = "50";
		$arr["F"]["N"]["44"]["40"] = "51";
		$arr["F"]["N"]["45"]["41"] = "52";
		$arr["F"]["N"]["46"]["42"] = "53";
		$arr["F"]["N"]["47"]["43"] = "54";
		$arr["F"]["N"]["48"]["44"] = "55";
		$arr["F"]["N"]["49"]["45"] = "56";
		$arr["F"]["N"]["50"]["46"] = "57";
		$arr["F"]["N"]["51"]["47"] = "58";
		$arr["F"]["N"]["52"]["48"] = "59";
		$arr["F"]["N"]["53"]["49"] = "60";
		$arr["F"]["N"]["54"]["50"] = "61";
		$arr["F"]["N"]["55"]["51"] = "62";
		$arr["F"]["N"]["56"]["52"] = "63";
		$arr["F"]["N"]["57"]["53"] = "64";
		$arr["F"]["N"]["58"]["54"] = "65";
		$arr["F"]["N"]["59"]["55"] = "66";
		$arr["F"]["N"]["60"]["56"] = "67";
		$arr["F"]["N"]["61"]["57"] = "68";
		$arr["F"]["N"]["62"]["58"] = "69";
		$arr["F"]["N"]["63"]["59"] = "70";
		$arr["F"]["N"]["64"]["60"] = "70";
		$arr["F"]["N"]["65"]["61"] = "70";
		$arr["F"]["N"]["66"]["62"] = "70";
		$arr["F"]["N"]["67"]["63"] = "70";
		$arr["F"]["N"]["68"]["64"] = "70";
		$arr["F"]["N"]["69"]["65"] = "70";
		$arr["F"]["N"]["70"]["66"] = "70";
		$arr["F"]["E"]["18"]["22"] = "28";
		$arr["F"]["E"]["19"]["23"] = "29";
		$arr["F"]["E"]["20"]["24"] = "30";
		$arr["F"]["E"]["21"]["25"] = "31";
		$arr["F"]["E"]["22"]["26"] = "32";
		$arr["F"]["E"]["23"]["27"] = "33";
		$arr["F"]["E"]["24"]["27"] = "34";
		$arr["F"]["E"]["25"]["27"] = "35";
		$arr["F"]["E"]["26"]["28"] = "36";
		$arr["F"]["E"]["27"]["28"] = "37";
		$arr["F"]["E"]["28"]["29"] = "37";
		$arr["F"]["E"]["29"]["29"] = "38";
		$arr["F"]["E"]["30"]["30"] = "39";
		$arr["F"]["E"]["31"]["30"] = "40";
		$arr["F"]["E"]["32"]["30"] = "41";
		$arr["F"]["E"]["33"]["31"] = "42";
		$arr["F"]["E"]["34"]["32"] = "43";
		$arr["F"]["E"]["35"]["33"] = "44";
		$arr["F"]["E"]["36"]["34"] = "45";
		$arr["F"]["E"]["37"]["34"] = "47";
		$arr["F"]["E"]["38"]["35"] = "48";
		$arr["F"]["E"]["39"]["35"] = "49";
		$arr["F"]["E"]["40"]["35"] = "50";
		$arr["F"]["E"]["41"]["35"] = "51";
		$arr["F"]["E"]["42"]["35"] = "52";
		$arr["F"]["E"]["43"]["36"] = "53";
		$arr["F"]["E"]["44"]["37"] = "54";
		$arr["F"]["E"]["45"]["38"] = "55";
		$arr["F"]["E"]["46"]["39"] = "56";
		$arr["F"]["E"]["47"]["40"] = "57";
		$arr["F"]["E"]["48"]["41"] = "58";
		$arr["F"]["E"]["49"]["42"] = "59";
		$arr["F"]["E"]["50"]["43"] = "60";
		$arr["F"]["E"]["51"]["44"] = "61";
		$arr["F"]["E"]["52"]["45"] = "62";
		$arr["F"]["E"]["53"]["46"] = "63";
		$arr["F"]["E"]["54"]["47"] = "64";
		$arr["F"]["E"]["55"]["48"] = "65";
		$arr["F"]["E"]["56"]["49"] = "66";
		$arr["F"]["E"]["57"]["50"] = "67";
		$arr["F"]["E"]["58"]["51"] = "68";
		$arr["F"]["E"]["59"]["52"] = "69";
		$arr["F"]["E"]["60"]["53"] = "70";
		$arr["F"]["E"]["61"]["54"] = "70";
		$arr["F"]["E"]["62"]["55"] = "70";
		$arr["F"]["E"]["63"]["56"] = "70";
		$arr["F"]["E"]["64"]["57"] = "70";
		$arr["F"]["E"]["65"]["58"] = "70";
		$arr["F"]["E"]["66"]["59"] = "70";
		$arr["F"]["E"]["67"]["60"] = "70";
		$arr["F"]["E"]["68"]["61"] = "70";
		$arr["F"]["E"]["69"]["62"] = "70";
		$arr["F"]["E"]["70"]["63"] = "70";
		$arr["M"]["N"]["21"]["18"] = "22";
		$arr["M"]["N"]["22"]["18"] = "23";
		$arr["M"]["N"]["23"]["19"] = "24";
		$arr["M"]["N"]["24"]["20"] = "25";
		$arr["M"]["N"]["25"]["21"] = "25";
		$arr["M"]["N"]["26"]["22"] = "26";
		$arr["M"]["N"]["27"]["22"] = "27";
		$arr["M"]["N"]["28"]["23"] = "28";
		$arr["M"]["N"]["29"]["24"] = "29";
		$arr["M"]["N"]["30"]["24"] = "30";
		$arr["M"]["N"]["31"]["25"] = "31";
		$arr["M"]["N"]["32"]["25"] = "32";
		$arr["M"]["N"]["33"]["26"] = "33";
		$arr["M"]["N"]["34"]["26"] = "34";
		$arr["M"]["N"]["35"]["26"] = "35";
		$arr["M"]["N"]["36"]["27"] = "36";
		$arr["M"]["N"]["37"]["27"] = "37";
		$arr["M"]["N"]["38"]["28"] = "38";
		$arr["M"]["N"]["39"]["28"] = "39";
		$arr["M"]["N"]["40"]["29"] = "40";
		$arr["M"]["N"]["41"]["30"] = "41";
		$arr["M"]["N"]["42"]["31"] = "42";
		$arr["M"]["N"]["43"]["32"] = "43";
		$arr["M"]["N"]["44"]["33"] = "44";
		$arr["M"]["N"]["45"]["34"] = "45";
		$arr["M"]["N"]["46"]["35"] = "46";
		$arr["M"]["N"]["47"]["36"] = "47";
		$arr["M"]["N"]["48"]["37"] = "48";
		$arr["M"]["N"]["49"]["38"] = "49";
		$arr["M"]["N"]["50"]["39"] = "50";
		$arr["M"]["N"]["51"]["40"] = "51";
		$arr["M"]["N"]["52"]["41"] = "52";
		$arr["M"]["N"]["53"]["42"] = "53";
		$arr["M"]["N"]["54"]["43"] = "54";
		$arr["M"]["N"]["55"]["44"] = "55";
		$arr["M"]["N"]["56"]["45"] = "56";
		$arr["M"]["N"]["57"]["46"] = "57";
		$arr["M"]["N"]["58"]["47"] = "58";
		$arr["M"]["N"]["59"]["48"] = "59";
		$arr["M"]["N"]["60"]["49"] = "60";
		$arr["M"]["N"]["61"]["50"] = "61";
		$arr["M"]["N"]["62"]["51"] = "62";
		$arr["M"]["N"]["63"]["52"] = "63";
		$arr["M"]["N"]["64"]["53"] = "64";
		$arr["M"]["N"]["65"]["54"] = "65";
		$arr["M"]["N"]["66"]["55"] = "66";
		$arr["M"]["N"]["67"]["56"] = "67";
		$arr["M"]["N"]["68"]["57"] = "68";
		$arr["M"]["N"]["69"]["58"] = "69";
		$arr["M"]["N"]["70"]["59"] = "70";
		$arr["M"]["E"]["21"]["18"] = "21";
		$arr["M"]["E"]["22"]["19"] = "22";
		$arr["M"]["E"]["23"]["20"] = "23";
		$arr["M"]["E"]["24"]["21"] = "24";
		$arr["M"]["E"]["25"]["22"] = "25";
		$arr["M"]["E"]["26"]["23"] = "26";
		$arr["M"]["E"]["27"]["24"] = "27";
		$arr["M"]["E"]["28"]["25"] = "28";
		$arr["M"]["E"]["29"]["25"] = "29";
		$arr["M"]["E"]["30"]["25"] = "30";
		$arr["M"]["E"]["31"]["25"] = "31";
		$arr["M"]["E"]["32"]["25"] = "32";
		$arr["M"]["E"]["33"]["25"] = "33";
		$arr["M"]["E"]["34"]["25"] = "34";
		$arr["M"]["E"]["35"]["25"] = "35";
		$arr["M"]["E"]["36"]["25"] = "36";
		$arr["M"]["E"]["37"]["26"] = "37";
		$arr["M"]["E"]["38"]["26"] = "38";
		$arr["M"]["E"]["39"]["26"] = "39";
		$arr["M"]["E"]["40"]["27"] = "40";
		$arr["M"]["E"]["41"]["28"] = "41";
		$arr["M"]["E"]["42"]["29"] = "42";
		$arr["M"]["E"]["43"]["30"] = "43";
		$arr["M"]["E"]["44"]["31"] = "44";
		$arr["M"]["E"]["45"]["32"] = "45";
		$arr["M"]["E"]["46"]["33"] = "46";
		$arr["M"]["E"]["47"]["34"] = "47";
		$arr["M"]["E"]["48"]["35"] = "48";
		$arr["M"]["E"]["49"]["36"] = "49";
		$arr["M"]["E"]["50"]["37"] = "50";
		$arr["M"]["E"]["51"]["38"] = "51";
		$arr["M"]["E"]["52"]["39"] = "52";
		$arr["M"]["E"]["53"]["40"] = "53";
		$arr["M"]["E"]["54"]["41"] = "54";
		$arr["M"]["E"]["55"]["42"] = "55";
		$arr["M"]["E"]["56"]["43"] = "56";
		$arr["M"]["E"]["57"]["44"] = "57";
		$arr["M"]["E"]["58"]["45"] = "58";
		$arr["M"]["E"]["59"]["46"] = "59";
		$arr["M"]["E"]["60"]["47"] = "60";
		$arr["M"]["E"]["61"]["48"] = "61";
		$arr["M"]["E"]["62"]["49"] = "62";
		$arr["M"]["E"]["63"]["50"] = "63";
		$arr["M"]["E"]["64"]["51"] = "64";
		$arr["M"]["E"]["65"]["52"] = "65";
		$arr["M"]["E"]["66"]["53"] = "66";
		$arr["M"]["E"]["67"]["54"] = "67";
		$arr["M"]["E"]["68"]["55"] = "68";
		$arr["M"]["E"]["69"]["56"] = "69";
		$arr["M"]["E"]["70"]["57"] = "70";
			
			
			fwrite($fp,"\n\n\n");
	fwrite($fp,"public static \$AGE_ARRAY=array(");
		$id="";
		$id2="";
		$id3="";
			$id1=-1;
			$id4=-1;
			$id5=-1;
foreach($arr as $k=>$v)
foreach($v as $key=>$val)
foreach($val as $K=>$V)
foreach($V as $KEY=>$VAL)
{
	if($id!=$k)
			{
				
				$id1++;
				if($id1){
					fwrite($fp,"\"))),\n");}
				$id=$k;
				fwrite($fp,"\"".$k."\"=>array(");
				$flag=1;
			}
				
	if($id2!=$key)
			{
				
				$id4++;
				if($id4 && $flag!=1){
					fwrite($fp,"\")),\n");}
				$id2=$key;
				fwrite($fp,"\"".$key."\"=>array(");
				$flag=2;
			}
			
	if($id3!=$K)
			{
				
				$id5++;
				if($id5 && $flag!=2 && $flag!=1){
					fwrite($fp,"\"),\n");}
				$id3=$K;
				fwrite($fp,"\"".$K."\"=>array(");
				$flag=3;
			}
	if(!$flag)
			fwrite($fp,"\",\n\t");					
			$flag=0;
			
		fwrite($fp,"\"".$KEY."\"=>\"".$VAL);
	
}
fwrite($fp,"\"))));");



$arr=array();
		$arr["M"]["1"]["1"]="17";
		$arr["M"]["2"]["1"]="17";
		$arr["M"]["3"]["1"]="17";
		$arr["M"]["4"]["1"]="17";
		$arr["M"]["5"]["1"]="17";
		$arr["M"]["6"]["1"]="17";
		$arr["M"]["7"]["1"]="17";
		$arr["M"]["8"]["1"]="17";
		$arr["M"]["9"]["1"]="17";
		$arr["M"]["10"]["1"]="17";
		$arr["M"]["11"]["1"]="17";
		$arr["M"]["12"]["1"]="17";
		$arr["M"]["13"]["10"]="15";
		$arr["M"]["14"]["10"]="15";
		$arr["M"]["15"]["11"]="15";
		$arr["M"]["16"]["11"]="16";
		$arr["M"]["17"]["12"]="16";
		$arr["M"]["18"]["12"]="17";
		$arr["M"]["19"]["13"]="18";
		$arr["M"]["20"]["13"]="18";
		$arr["M"]["21"]["13"]="18";
		$arr["M"]["22"]["13"]="18";
		$arr["M"]["23"]["14"]="19";
		$arr["M"]["24"]["14"]="19";
		$arr["M"]["25"]["13"]="19";
		$arr["M"]["26"]["13"]="20";
		$arr["M"]["27"]["13"]="21";
		$arr["M"]["28"]["13"]="22";
		$arr["M"]["29"]["13"]="23";
		$arr["M"]["30"]["13"]="24";
		$arr["M"]["31"]["13"]="25";
		$arr["M"]["32"]["13"]="26";
		$arr["M"]["33"]["13"]="27";
		$arr["M"]["34"]["13"]="28";
		$arr["M"]["35"]["13"]="29";
		$arr["M"]["36"]["13"]="30";
		$arr["M"]["37"]["13"]="31";
		$arr["F"]["1"]["1"]="20";
		$arr["F"]["2"]["2"]="20";
		$arr["F"]["3"]["3"]="20";
		$arr["F"]["4"]["4"]="20";
		$arr["F"]["5"]["5"]="20";
		$arr["F"]["6"]["6"]="20";
		$arr["F"]["7"]["7"]="20";
		$arr["F"]["8"]["8"]="20";
		$arr["F"]["9"]["9"]="20";
		$arr["F"]["10"]["10"]="20";
		$arr["F"]["11"]["11"]="20";
		$arr["F"]["12"]["12"]="20";
		$arr["F"]["13"]["17"]="23";
		$arr["F"]["14"]["18"]="23";
		$arr["F"]["15"]["18"]="24";
		$arr["F"]["16"]["18"]="24";
		$arr["F"]["17"]["18"]="24";
		$arr["F"]["18"]["19"]="24";
		$arr["F"]["19"]["19"]="25";
		$arr["F"]["20"]["19"]="25";
		$arr["F"]["21"]["20"]="25";
		$arr["F"]["22"]["21"]="26";
		$arr["F"]["23"]["22"]="27";
		$arr["F"]["24"]["23"]="28";
		$arr["F"]["25"]["24"]="29";
		$arr["F"]["26"]["25"]="30";
		$arr["F"]["27"]["26"]="31";
		$arr["F"]["28"]["27"]="32";
		$arr["F"]["29"]["28"]="33";
		$arr["F"]["30"]["28"]="34";
		$arr["F"]["31"]["28"]="35";
		$arr["F"]["32"]["28"]="36";
		$arr["F"]["33"]["28"]="37";
		$arr["F"]["34"]["28"]="37";
		$arr["F"]["35"]["28"]="37";
		$arr["F"]["36"]["28"]="37";
		$arr["F"]["37"]["28"]="37";
					fwrite($fp,"\n\n\n");
	fwrite($fp,"public static \$HEIGHT_ARRAY=array(");
		$id="";
		$id2="";
		$id3="";
			$id1=-1;
			$id4=-1;
			$id5=-1;
foreach($arr as $k=>$v)
foreach($v as $key=>$val)
foreach($val as $K=>$V)
{
	if($id!=$k)
			{
				
				$id1++;
				if($id1){
					fwrite($fp,"\")),\n");}
				$id=$k;
				fwrite($fp,"\"".$k."\"=>array(");
				$flag=1;
			}
				
	if($id2!=$key)
			{
				
				$id4++;
				if($id4 && $flag!=1){
					fwrite($fp,"\"),\n");}
				$id2=$key;
				fwrite($fp,"\"".$key."\"=>array(");
				$flag=2;
			}
			
	
	if(!$flag)
			fwrite($fp,"\",\n\t");					
			$flag=0;
			
		fwrite($fp,"\"".$K."\"=>\"".$V);
	
}
fwrite($fp,"\")));");


$arr=array();
$arr['M']['2']['0']="3";
$arr['M']['3']['0']="3";
$arr['M']['4']['0']="4";
$arr['M']['5']['0']="5";
$arr['M']['6']['0']="6";
$arr['M']['15']['']="";
$arr['M']['16']['0']="7";
$arr['M']['17']['0']="8";
$arr['M']['18']['0']="9";
$arr['M']['20']['0']="10";
$arr['M']['22']['0']="11";
$arr['M']['23']['0']="20";
$arr['M']['24']['0']="21";
$arr['M']['25']['']="";
$arr['M']['26']['']="";
$arr['M']['27']['']="";
$arr['M']['8']['0']="5";
$arr['M']['9']['0']="6";
$arr['M']['10']['0']="8";
$arr['M']['11']['0']="9";
$arr['M']['12']['0']="10";
$arr['M']['13']['0']="11";
$arr['M']['21']['0']="20";
$arr['M']['14']['']="";
$arr['F']['2']['3']="19";
$arr['F']['3']['3']="19";
$arr['F']['4']['4']="19";
$arr['F']['5']['5']="19";
$arr['F']['6']['6']="19";
$arr['F']['15']['4']="19";
$arr['F']['16']['6']="19";
$arr['F']['17']['7']="19";
$arr['F']['18']['7']="19";
$arr['F']['20']['8']="19";
$arr['F']['22']['9']="19";
$arr['F']['23']['10']="19";
$arr['F']['24']['11']="19";
$arr['F']['25']['20']="19";
$arr['F']['26']['21']="19";
$arr['F']['27']['22']="19";
$arr['F']['8']['5']="19";
$arr['F']['9']['6']="19";
$arr['F']['10']['6']="19";
$arr['F']['11']['7']="19";
$arr['F']['12']['8']="19";
$arr['F']['13']['9']="19";
$arr['F']['21']['10']="19";
$arr['F']['14']['11']="19";


					fwrite($fp,"\n\n\n");
	fwrite($fp,"public static \$INCOME_ARRAY=array(\"M\"=>array(");
$a=0;
foreach($arr as $k=>$v)
foreach($v as $key=>$val)
foreach($val as $K=>$V)
{
	if($k=="M")
	{
		fwrite($fp,"\"".$key."\"=>array(\"".$K."\"=>\"".$V."\"),\n");
	}
	if($k=="F")
	{
		if($a==0)
		{
			fwrite($fp,"),\"F\"=>array(");
		}
		fwrite($fp,"\"".$key."\"=>array(\"".$K."\"=>\"".$V."\"),\n");
		$a=1;		
	}
}
fwrite($fp,"));");

// for reading from csv and creating religion array.
/*
		$finalArr=array();
		$arr=array();
		$sql_insert_backup= "select * from  AUTO_SUGGEST_DPP where ID=15";
		$res_select=$mysqlObj->executeQuery($sql_insert_backup,$db) or die(mysql_error());
		$i=0;
	
		fwrite($fp,"\n\n\$arr=array();\n");
		while($row=mysql_fetch_array($res_select))
		{
				$arr=$row;
				
				
				$str=$row["AUTO_SUGGEST_DPP_VALUE"];
				$str=trim($str,"'");
				$religionArr=explode("','",$str);
				
				$religionArrValue=array();
				
				foreach($religionArr as$k=>$v)
				{
					
					$str1="";
					$sql_insert= "select PARENT from  CASTE where ID='".$v."'";
					$res=$mysqlObj->executeQuery($sql_insert,$db) or die(mysql_error());
					
					$arr1=mysql_fetch_array($res);
					if(!in_array($arr1["PARENT"],$religionArrValue))
					$religionArrValue[]=$arr1["PARENT"];
					
				}
					
					$str1=implode("','",$religionArrValue);
					unset($religionArrValue);
					$str1=trim($str1,"','");
					$finalArr[$arr["ACTUAL_VALUE"]]["RELIGION"]="'".$str1."'";
						if($str1)
					fwrite($fp,"$"."arr[\"".$arr["ACTUAL_VALUE"]."\"][\"RELIGION\"]=\"'".$str1."'\";\n");
						else
					fwrite($fp,"$"."arr[\"".$arr["ACTUAL_VALUE"]."\"][\"RELIGION\"]=\"\";\n"); 
		}

*/
	$arr=array();
$arr["1"]["RELIGION"]="'7'";
$arr["2"]["RELIGION"]="'3','1'";
$arr["3"]["RELIGION"]="'3'";
$arr["4"]["RELIGION"]="'3'";
$arr["5"]["RELIGION"]="'3'";
$arr["6"]["RELIGION"]="'3'";
$arr["7"]["RELIGION"]="'3'";
$arr["8"]["RELIGION"]="'3'";
$arr["9"]["RELIGION"]="'3'";
$arr["10"]["RELIGION"]="'3'";
$arr["11"]["RELIGION"]="'3'";
$arr["12"]["RELIGION"]="'3'";
$arr["13"]["RELIGION"]="'3'";
$arr["16"]["RELIGION"]="'1'";
$arr["17"]["RELIGION"]="'1','9'";
$arr["18"]["RELIGION"]="'1'";
$arr["19"]["RELIGION"]="'1'";
$arr["20"]["RELIGION"]="'1'";
$arr["21"]["RELIGION"]="'1'";
$arr["22"]["RELIGION"]="'1'";
$arr["23"]["RELIGION"]="'7','1'";
$arr["24"]["RELIGION"]="'1'";
$arr["25"]["RELIGION"]="'1'";
$arr["26"]["RELIGION"]="'1'";
$arr["27"]["RELIGION"]="'1'";
$arr["28"]["RELIGION"]="'1'";
$arr["29"]["RELIGION"]="'1'";
$arr["30"]["RELIGION"]="'1'";
$arr["31"]["RELIGION"]="'1'";
$arr["32"]["RELIGION"]="'1'";
$arr["33"]["RELIGION"]="'1'";
$arr["34"]["RELIGION"]="'1'";
$arr["35"]["RELIGION"]="'1'";
$arr["36"]["RELIGION"]="'1'";
$arr["37"]["RELIGION"]="'1'";
$arr["38"]["RELIGION"]="'1'";
$arr["39"]["RELIGION"]="'1'";
$arr["40"]["RELIGION"]="'1'";
$arr["41"]["RELIGION"]="'1'";
$arr["42"]["RELIGION"]="'1'";
$arr["43"]["RELIGION"]="'1'";
$arr["44"]["RELIGION"]="'1'";
$arr["45"]["RELIGION"]="'1'";
$arr["46"]["RELIGION"]="'1'";
$arr["47"]["RELIGION"]="'1'";
$arr["48"]["RELIGION"]="'1'";
$arr["49"]["RELIGION"]="'1'";
$arr["50"]["RELIGION"]="'1'";
$arr["51"]["RELIGION"]="'1'";
$arr["52"]["RELIGION"]="'1'";
$arr["53"]["RELIGION"]="'1'";
$arr["54"]["RELIGION"]="'1'";
$arr["55"]["RELIGION"]="'1'";
$arr["56"]["RELIGION"]="'1'";
$arr["57"]["RELIGION"]="'1'";
$arr["58"]["RELIGION"]="'1'";
$arr["59"]["RELIGION"]="'1'";
$arr["60"]["RELIGION"]="'1'";
$arr["61"]["RELIGION"]="'1'";
$arr["62"]["RELIGION"]="'1'";
$arr["63"]["RELIGION"]="'1'";
$arr["64"]["RELIGION"]="'1'";
$arr["65"]["RELIGION"]="'1'";
$arr["66"]["RELIGION"]="'1'";
$arr["70"]["RELIGION"]="'1'";
$arr["71"]["RELIGION"]="'1'";
$arr["72"]["RELIGION"]="'1'";
$arr["73"]["RELIGION"]="'1'";
$arr["74"]["RELIGION"]="'1'";
$arr["75"]["RELIGION"]="'1'";
$arr["76"]["RELIGION"]="'1'";
$arr["77"]["RELIGION"]="'1'";
$arr["78"]["RELIGION"]="'1'";
$arr["79"]["RELIGION"]="'1'";
$arr["80"]["RELIGION"]="'1'";
$arr["81"]["RELIGION"]="'1'";
$arr["82"]["RELIGION"]="'1'";
$arr["83"]["RELIGION"]="'1'";
$arr["84"]["RELIGION"]="'1'";
$arr["85"]["RELIGION"]="'1'";
$arr["86"]["RELIGION"]="'1'";
$arr["87"]["RELIGION"]="'1'";
$arr["88"]["RELIGION"]="'1'";
$arr["89"]["RELIGION"]="'1'";
$arr["90"]["RELIGION"]="'1'";
$arr["91"]["RELIGION"]="'1'";
$arr["92"]["RELIGION"]="'1'";
$arr["93"]["RELIGION"]="'1'";
$arr["94"]["RELIGION"]="'1'";
$arr["95"]["RELIGION"]="'1','9'";
$arr["96"]["RELIGION"]="'1'";
$arr["97"]["RELIGION"]="'1'";
$arr["98"]["RELIGION"]="'1'";
$arr["99"]["RELIGION"]="'1'";
$arr["100"]["RELIGION"]="'1'";
$arr["101"]["RELIGION"]="'1'";
$arr["102"]["RELIGION"]="'1'";
$arr["103"]["RELIGION"]="'1'";
$arr["104"]["RELIGION"]="'1'";
$arr["105"]["RELIGION"]="'1'";
$arr["106"]["RELIGION"]="'1'";
$arr["107"]["RELIGION"]="'1'";
$arr["108"]["RELIGION"]="'1'";
$arr["109"]["RELIGION"]="'1'";
$arr["110"]["RELIGION"]="'1'";
$arr["111"]["RELIGION"]="'1','9'";
$arr["112"]["RELIGION"]="'1'";
$arr["113"]["RELIGION"]="'1'";
$arr["114"]["RELIGION"]="'1'";
$arr["115"]["RELIGION"]="'1'";
$arr["116"]["RELIGION"]="'1'";
$arr["117"]["RELIGION"]="'1'";
$arr["118"]["RELIGION"]="'1'";
$arr["119"]["RELIGION"]="'1'";
$arr["120"]["RELIGION"]="'1'";
$arr["121"]["RELIGION"]="'7','1'";
$arr["122"]["RELIGION"]="'1'";
$arr["123"]["RELIGION"]="'1'";
$arr["124"]["RELIGION"]="'1'";
$arr["125"]["RELIGION"]="'1'";
$arr["126"]["RELIGION"]="'1'";
$arr["127"]["RELIGION"]="'1'";
$arr["128"]["RELIGION"]="'1'";
$arr["129"]["RELIGION"]="'1'";
$arr["130"]["RELIGION"]="'1'";
$arr["131"]["RELIGION"]="'1'";
$arr["133"]["RELIGION"]="'1'";
$arr["134"]["RELIGION"]="'1'";
$arr["135"]["RELIGION"]="'1'";
$arr["136"]["RELIGION"]="'1'";
$arr["137"]["RELIGION"]="'1'";
$arr["138"]["RELIGION"]="'1'";
$arr["139"]["RELIGION"]="'1'";
$arr["140"]["RELIGION"]="'1'";
$arr["141"]["RELIGION"]="'1'";
$arr["142"]["RELIGION"]="'1'";
$arr["143"]["RELIGION"]="'1'";
$arr["144"]["RELIGION"]="'1'";
$arr["145"]["RELIGION"]="'1'";
$arr["146"]["RELIGION"]="'1'";
$arr["147"]["RELIGION"]="'1'";
$arr["148"]["RELIGION"]="'1','6'";
$arr["149"]["RELIGION"]="'2'";
$arr["150"]["RELIGION"]="'151','2'";
$arr["151"]["RELIGION"]="'2'";
$arr["152"]["RELIGION"]="'2'";
$arr["153"]["RELIGION"]="'5','1'";
$arr["154"]["RELIGION"]="'4'";
$arr["155"]["RELIGION"]="'4'";
$arr["156"]["RELIGION"]="'1','4'";
$arr["157"]["RELIGION"]="'4'";
$arr["158"]["RELIGION"]="'4'";
$arr["159"]["RELIGION"]="'1','4'";
$arr["160"]["RELIGION"]="'4'";
$arr["161"]["RELIGION"]="'1','4'";
$arr["162"]["RELIGION"]="'7','1','2'";
$arr["163"]["RELIGION"]="'1'";
$arr["164"]["RELIGION"]="'1'";
$arr["165"]["RELIGION"]="'1','9'";
$arr["166"]["RELIGION"]="'1'";
$arr["167"]["RELIGION"]="'4'";
$arr["168"]["RELIGION"]="'1'";
$arr["169"]["RELIGION"]="'1'";
$arr["170"]["RELIGION"]="'1'";
$arr["171"]["RELIGION"]="'1'";
$arr["172"]["RELIGION"]="'4'";
$arr["173"]["RELIGION"]="'1','9'";
$arr["174"]["RELIGION"]="'1','9'";
$arr["175"]["RELIGION"]="'1','9'";
$arr["176"]["RELIGION"]="'1'";
$arr["177"]["RELIGION"]="'1'";
$arr["178"]["RELIGION"]="'1'";
$arr["179"]["RELIGION"]="'1'";
$arr["180"]["RELIGION"]="'1'";
$arr["181"]["RELIGION"]="'3'";
$arr["182"]["RELIGION"]="'1'";
$arr["183"]["RELIGION"]="'1'";
$arr["184"]["RELIGION"]="'1'";
$arr["185"]["RELIGION"]="'1'";
$arr["186"]["RELIGION"]="'1'";
$arr["187"]["RELIGION"]="'1'";
$arr["188"]["RELIGION"]="'1'";
$arr["189"]["RELIGION"]="'1'";
$arr["190"]["RELIGION"]="'1'";
$arr["191"]["RELIGION"]="'1'";
$arr["192"]["RELIGION"]="'1'";
$arr["193"]["RELIGION"]="'1'";
$arr["194"]["RELIGION"]="'1'";
$arr["195"]["RELIGION"]="'1'";
$arr["196"]["RELIGION"]="'3'";
$arr["197"]["RELIGION"]="'1'";
$arr["198"]["RELIGION"]="'1'";
$arr["199"]["RELIGION"]="'1'";
$arr["200"]["RELIGION"]="'1'";
$arr["201"]["RELIGION"]="'1'";
$arr["202"]["RELIGION"]="'1'";
$arr["203"]["RELIGION"]="'1'";
$arr["204"]["RELIGION"]="'1'";
$arr["205"]["RELIGION"]="'1'";
$arr["206"]["RELIGION"]="'1'";
$arr["207"]["RELIGION"]="'1'";
$arr["208"]["RELIGION"]="'1'";
$arr["209"]["RELIGION"]="'1'";
$arr["210"]["RELIGION"]="'1'";
$arr["211"]["RELIGION"]="'1'";
$arr["212"]["RELIGION"]="'1'";
$arr["213"]["RELIGION"]="'1'";
$arr["214"]["RELIGION"]="'1'";
$arr["215"]["RELIGION"]="'1'";
$arr["216"]["RELIGION"]="'1'";
$arr["217"]["RELIGION"]="'1'";
$arr["218"]["RELIGION"]="'1'";
$arr["219"]["RELIGION"]="'1'";
$arr["220"]["RELIGION"]="'1'";
$arr["221"]["RELIGION"]="'1'";
$arr["222"]["RELIGION"]="'4'";
$arr["223"]["RELIGION"]="'1'";
$arr["224"]["RELIGION"]="'1'";
$arr["225"]["RELIGION"]="'1','4'";
$arr["226"]["RELIGION"]="'1'";
$arr["227"]["RELIGION"]="'1'";
$arr["228"]["RELIGION"]="'1'";
$arr["229"]["RELIGION"]="'1'";
$arr["230"]["RELIGION"]="'1'";
$arr["231"]["RELIGION"]="'1'";
$arr["232"]["RELIGION"]="'1'";
$arr["233"]["RELIGION"]="'1'";
$arr["234"]["RELIGION"]="'1'";
$arr["235"]["RELIGION"]="'1'";
$arr["236"]["RELIGION"]="'1'";
$arr["237"]["RELIGION"]="'1'";
$arr["239"]["RELIGION"]="'1'";
$arr["241"]["RELIGION"]="'1'";
$arr["242"]["RELIGION"]="'1'";
$arr["243"]["RELIGION"]="'2'";
$arr["244"]["RELIGION"]="'3','1'";
$arr["245"]["RELIGION"]="'4'";
$arr["246"]["RELIGION"]="'1','9'";
$arr["247"]["RELIGION"]="'1'";
$arr["248"]["RELIGION"]="'1','4'";
$arr["249"]["RELIGION"]="'4'";
$arr["250"]["RELIGION"]="'1'";
$arr["251"]["RELIGION"]="'1'";
$arr["252"]["RELIGION"]="'1'";
$arr["253"]["RELIGION"]="'1'";
$arr["261"]["RELIGION"]="'1'";
$arr["262"]["RELIGION"]="'1'";
$arr["263"]["RELIGION"]="'3'";
$arr["264"]["RELIGION"]="'3'";
$arr["265"]["RELIGION"]="'8','3'";
$arr["266"]["RELIGION"]="'3','1'";
$arr["267"]["RELIGION"]="'3'";
$arr["268"]["RELIGION"]="'3'";
$arr["269"]["RELIGION"]="'3'";
$arr["270"]["RELIGION"]="'3'";
$arr["271"]["RELIGION"]="'7','3'";
$arr["272"]["RELIGION"]="'3'";
$arr["273"]["RELIGION"]="'3'";
$arr["275"]["RELIGION"]="'3'";
$arr["276"]["RELIGION"]="'3'";
$arr["277"]["RELIGION"]="'3'";
$arr["278"]["RELIGION"]="'1'";
$arr["279"]["RELIGION"]="'1'";
$arr["281"]["RELIGION"]="'1'";
$arr["282"]["RELIGION"]="'1'";
$arr["283"]["RELIGION"]="'1'";
$arr["284"]["RELIGION"]="'1'";
$arr["285"]["RELIGION"]="'1'";
$arr["286"]["RELIGION"]="'1'";
$arr["287"]["RELIGION"]="'1'";
$arr["288"]["RELIGION"]="'1'";
$arr["289"]["RELIGION"]="'1'";
$arr["290"]["RELIGION"]="'1'";
$arr["291"]["RELIGION"]="'1'";
$arr["292"]["RELIGION"]="'1'";
$arr["293"]["RELIGION"]="'1'";
$arr["294"]["RELIGION"]="'1'";
$arr["295"]["RELIGION"]="'1'";
$arr["296"]["RELIGION"]="'1'";
$arr["299"]["RELIGION"]="'1'";
$arr["300"]["RELIGION"]="'1'";
$arr["301"]["RELIGION"]="'1'";
$arr["302"]["RELIGION"]="'1'";
$arr["303"]["RELIGION"]="'1'";
$arr["304"]["RELIGION"]="'1'";
$arr["305"]["RELIGION"]="'1'";
$arr["306"]["RELIGION"]="'1'";
$arr["307"]["RELIGION"]="'1'";
$arr["309"]["RELIGION"]="'1'";
$arr["310"]["RELIGION"]="'1'";
$arr["311"]["RELIGION"]="'1'";
$arr["312"]["RELIGION"]="'1'";
$arr["314"]["RELIGION"]="'1'";
$arr["315"]["RELIGION"]="'1'";
$arr["316"]["RELIGION"]="'1'";
$arr["317"]["RELIGION"]="'1'";
$arr["318"]["RELIGION"]="'1'";
$arr["319"]["RELIGION"]="'1','9'";
$arr["320"]["RELIGION"]="'1'";
$arr["321"]["RELIGION"]="'1'";
$arr["322"]["RELIGION"]="'1'";
$arr["323"]["RELIGION"]="'1'";
$arr["324"]["RELIGION"]="'1'";
$arr["325"]["RELIGION"]="'1','4'";
$arr["326"]["RELIGION"]="'1'";
$arr["327"]["RELIGION"]="'1'";
$arr["328"]["RELIGION"]="'1'";
$arr["329"]["RELIGION"]="'1','4'";
$arr["330"]["RELIGION"]="'1'";
$arr["331"]["RELIGION"]="'1'";
$arr["332"]["RELIGION"]="'1'";
$arr["333"]["RELIGION"]="'1'";
$arr["334"]["RELIGION"]="'1','9'";
$arr["335"]["RELIGION"]="'1'";
$arr["336"]["RELIGION"]="'1'";
$arr["337"]["RELIGION"]="'1'";
$arr["338"]["RELIGION"]="'1'";
$arr["339"]["RELIGION"]="'1'";
$arr["340"]["RELIGION"]="'1'";
$arr["341"]["RELIGION"]="'7','1'";
$arr["342"]["RELIGION"]="'1'";
$arr["343"]["RELIGION"]="'1'";
$arr["344"]["RELIGION"]="'1'";
$arr["345"]["RELIGION"]="'1'";
$arr["346"]["RELIGION"]="'3','1'";
$arr["347"]["RELIGION"]="'7','1'";
$arr["348"]["RELIGION"]="'1'";
$arr["349"]["RELIGION"]="'1'";
$arr["350"]["RELIGION"]="'1'";
$arr["351"]["RELIGION"]="'1','9'";
$arr["352"]["RELIGION"]="'1'";
$arr["353"]["RELIGION"]="'1'";
$arr["354"]["RELIGION"]="'1'";
$arr["355"]["RELIGION"]="'1'";
$arr["356"]["RELIGION"]="'1'";
$arr["357"]["RELIGION"]="'7','1'";
$arr["358"]["RELIGION"]="'1'";
$arr["359"]["RELIGION"]="'1'";
$arr["360"]["RELIGION"]="'1'";
$arr["362"]["RELIGION"]="'7','1'";
$arr["364"]["RELIGION"]="'1'";
$arr["365"]["RELIGION"]="'1'";
$arr["366"]["RELIGION"]="'1'";
$arr["367"]["RELIGION"]="'1'";
$arr["368"]["RELIGION"]="'1'";
$arr["369"]["RELIGION"]="'1'";
$arr["370"]["RELIGION"]="'1'";
$arr["371"]["RELIGION"]="'9'";
$arr["372"]["RELIGION"]="'1'";
$arr["373"]["RELIGION"]="'1'";
$arr["374"]["RELIGION"]="'1'";
$arr["375"]["RELIGION"]="'1'";
$arr["376"]["RELIGION"]="'1'";
$arr["377"]["RELIGION"]="'1','4'";
$arr["378"]["RELIGION"]="'4','1'";
$arr["379"]["RELIGION"]="'1','4'";
$arr["380"]["RELIGION"]="'1'";
$arr["381"]["RELIGION"]="'1'";
$arr["382"]["RELIGION"]="'1'";
$arr["383"]["RELIGION"]="'1'";
$arr["384"]["RELIGION"]="'1'";
$arr["385"]["RELIGION"]="'1'";
$arr["386"]["RELIGION"]="'1'";
$arr["387"]["RELIGION"]="'1'";
$arr["388"]["RELIGION"]="'1'";
$arr["389"]["RELIGION"]="'1'";
$arr["390"]["RELIGION"]="'1'";
$arr["391"]["RELIGION"]="'1'";
$arr["392"]["RELIGION"]="'1'";
$arr["393"]["RELIGION"]="'1'";
$arr["394"]["RELIGION"]="'1'";
$arr["395"]["RELIGION"]="'1'";
$arr["396"]["RELIGION"]="'1'";
$arr["397"]["RELIGION"]="'1'";
$arr["398"]["RELIGION"]="'1'";
$arr["399"]["RELIGION"]="'1'";
$arr["400"]["RELIGION"]="'1'";
$arr["401"]["RELIGION"]="'1'";
$arr["402"]["RELIGION"]="'1'";
$arr["403"]["RELIGION"]="'1'";
$arr["405"]["RELIGION"]="'1'";
$arr["406"]["RELIGION"]="'3'";
$arr["361"]["RELIGION"]="";
$arr["363"]["RELIGION"]="";
$arr["412"]["RELIGION"]="'1'";
$arr["440"]["RELIGION"]="'1'";
$arr["442"]["RELIGION"]="'1'";
$arr["449"]["RELIGION"]="'1'";
$arr["464"]["RELIGION"]="'1'";
$arr["465"]["RELIGION"]="'1'";
$arr["407"]["RELIGION"]="'1'";
$arr["408"]["RELIGION"]="'1'";
$arr["409"]["RELIGION"]="'1'";
$arr["410"]["RELIGION"]="'1'";
$arr["411"]["RELIGION"]="'1'";
$arr["413"]["RELIGION"]="'1'";
$arr["414"]["RELIGION"]="'1'";
$arr["415"]["RELIGION"]="'1'";
$arr["416"]["RELIGION"]="'1'";
$arr["417"]["RELIGION"]="'1'";
$arr["418"]["RELIGION"]="'1'";
$arr["420"]["RELIGION"]="'1'";
$arr["421"]["RELIGION"]="'1'";
$arr["422"]["RELIGION"]="'1'";
$arr["423"]["RELIGION"]="'1'";
$arr["424"]["RELIGION"]="'1'";
$arr["425"]["RELIGION"]="'1'";
$arr["426"]["RELIGION"]="'1'";
$arr["427"]["RELIGION"]="'1'";
$arr["428"]["RELIGION"]="'1'";
$arr["429"]["RELIGION"]="'1'";
$arr["430"]["RELIGION"]="'1'";
$arr["431"]["RELIGION"]="'1'";
$arr["432"]["RELIGION"]="'1'";
$arr["433"]["RELIGION"]="'1'";
$arr["434"]["RELIGION"]="'1'";
$arr["435"]["RELIGION"]="'1'";
$arr["436"]["RELIGION"]="'1'";
$arr["437"]["RELIGION"]="'1'";
$arr["438"]["RELIGION"]="'1'";
$arr["439"]["RELIGION"]="'1'";
$arr["441"]["RELIGION"]="'1'";
$arr["443"]["RELIGION"]="'1'";
$arr["444"]["RELIGION"]="'1'";
$arr["445"]["RELIGION"]="'1'";
$arr["446"]["RELIGION"]="'1'";
$arr["447"]["RELIGION"]="'1'";
$arr["448"]["RELIGION"]="'1'";
$arr["450"]["RELIGION"]="'1'";
$arr["451"]["RELIGION"]="'1'";
$arr["452"]["RELIGION"]="'1'";
$arr["453"]["RELIGION"]="'1'";
$arr["454"]["RELIGION"]="'1'";
$arr["455"]["RELIGION"]="'1'";
$arr["456"]["RELIGION"]="'1'";
$arr["457"]["RELIGION"]="'1'";
$arr["458"]["RELIGION"]="'1'";
$arr["459"]["RELIGION"]="'1'";
$arr["460"]["RELIGION"]="'1'";
$arr["461"]["RELIGION"]="'1'";
$arr["462"]["RELIGION"]="'1'";
$arr["463"]["RELIGION"]="'1'";
$arr["466"]["RELIGION"]="'1'";
$arr["467"]["RELIGION"]="'1'";
$arr["468"]["RELIGION"]="'1'";
$arr["469"]["RELIGION"]="'1'";
$arr["470"]["RELIGION"]="'1'";
$arr["471"]["RELIGION"]="'4'";
$arr["472"]["RELIGION"]="'4'";
$arr["496"]["RELIGION"]="'10'";



				fwrite($fp,"\n\n\n");
	fwrite($fp,"public static \$RELIGION_ARRAY=array(");
		$id="";
			$id1=-1;
foreach($arr as $k=>$v)
foreach($v as $K=>$V)
{
		if($id!=$k)
			{
				
				$id1++;
				if($id1){
					fwrite($fp,"\"),\n");}
				$id=$k;
				fwrite($fp,"\"".$k."\"=>array(");
			}
		else
			fwrite($fp,"\",\n\t");					
		
			
			fwrite($fp,"\"".$K."\"=>\"".$V);
	
}
fwrite($fp,"\"));");

/*
// Reading from csv and making corresponding array for important caste
if (($handle = fopen("/home/nitesh/Desktop/test.csv", "r")) !== FALSE)
	{
			
			fwrite($fp," \$IMP_CASTE_ARRAY=array();");
			$caste="";
			while (($data = fgetcsv($handle, 1000, ";")) !== FALSE){
				
					if($caste==$data[0])
					{
						$i++;
						$casteArr[$data[0]][$i]=$data[1];						
						
					}
					else
					{
						$i=0;
						$casteArr[$data[0]][$i]=$data[1];
						$caste=$data[0];
					}
					
					fwrite($fp,"\n\$IMP_CASTE_ARRAY[\"".$data[0]."\"][\"".$i."\"]='".$data[1]."';");
					
				}*/
$IMP_CASTE_ARRAY=array();
$IMP_CASTE_ARRAY["Assamese"]["0"]='Hindu: Ahom';
$IMP_CASTE_ARRAY["Assamese"]["1"]='Hindu: Brahmin';
$IMP_CASTE_ARRAY["Assamese"]["2"]='Hindu: Kashyap';
$IMP_CASTE_ARRAY["Assamese"]["3"]='Hindu: Kayastha';
$IMP_CASTE_ARRAY["Assamese"]["4"]='Hindu: Kulita';
$IMP_CASTE_ARRAY["Assamese"]["5"]='Hindu: OBC';
$IMP_CASTE_ARRAY["Assamese"]["6"]='Hindu: Scheduled Caste';
$IMP_CASTE_ARRAY["Assamese"]["7"]='Hindu: Scheduled Tribe';
$IMP_CASTE_ARRAY["Bengali"]["0"]='Hindu: Brahmin';
$IMP_CASTE_ARRAY["Bengali"]["1"]='Hindu: Kashyap';
$IMP_CASTE_ARRAY["Bengali"]["2"]='Hindu: Kayastha';
$IMP_CASTE_ARRAY["Bengali"]["3"]='Hindu: Mahisya';
$IMP_CASTE_ARRAY["Bengali"]["4"]='Hindu: Namasudra/Namosudra';
$IMP_CASTE_ARRAY["Bengali"]["5"]='Hindu: Scheduled Caste';
$IMP_CASTE_ARRAY["Bihari"]["0"]='Hindu: Bania';
$IMP_CASTE_ARRAY["Bihari"]["1"]='Hindu: Brahmin';
$IMP_CASTE_ARRAY["Bihari"]["2"]='Hindu: Brahmin Bhumihar';
$IMP_CASTE_ARRAY["Bihari"]["3"]='Hindu: Brahmin Maithil';
$IMP_CASTE_ARRAY["Bihari"]["4"]='Hindu: Kayastha';
$IMP_CASTE_ARRAY["Bihari"]["5"]='Hindu: Kurmi';
$IMP_CASTE_ARRAY["Bihari"]["6"]='Hindu: Kushwaha';
$IMP_CASTE_ARRAY["Bihari"]["7"]='Hindu: Rajput';
$IMP_CASTE_ARRAY["Bihari"]["8"]='Hindu: Teli';
$IMP_CASTE_ARRAY["Bihari"]["9"]='Hindu: Yadav/Yadava';
$IMP_CASTE_ARRAY["Gujarati / Kutchi"]["0"]='Hindu: Brahmin';
$IMP_CASTE_ARRAY["Gujarati / Kutchi"]["1"]='Hindu: Brahmin Audichya';
$IMP_CASTE_ARRAY["Gujarati / Kutchi"]["2"]='Hindu: Kadava patel';
$IMP_CASTE_ARRAY["Gujarati / Kutchi"]["3"]='Hindu: Leva Patidar';
$IMP_CASTE_ARRAY["Gujarati / Kutchi"]["4"]='Hindu: Lohana';
$IMP_CASTE_ARRAY["Gujarati / Kutchi"]["5"]='Hindu: Patel';
$IMP_CASTE_ARRAY["Gujarati / Kutchi"]["6"]='Hindu: Patel Kadva';
$IMP_CASTE_ARRAY["Gujarati / Kutchi"]["7"]='Hindu: Patel Leva';
$IMP_CASTE_ARRAY["Gujarati / Kutchi"]["8"]='Hindu: Rajput';
$IMP_CASTE_ARRAY["Gujarati / Kutchi"]["9"]='Hindu: Vaishnav';
$IMP_CASTE_ARRAY["Gujarati / Kutchi"]["10"]='Hindu: Vaishnav Vanik';
$IMP_CASTE_ARRAY["Haryanvi"]["0"]='Hindu: Aggarwal';
$IMP_CASTE_ARRAY["Haryanvi"]["1"]='Hindu: Ahir';
$IMP_CASTE_ARRAY["Haryanvi"]["2"]='Hindu: Brahmin';
$IMP_CASTE_ARRAY["Haryanvi"]["3"]='Hindu: Brahmin Gaur';
$IMP_CASTE_ARRAY["Haryanvi"]["4"]='Hindu: Chamar';
$IMP_CASTE_ARRAY["Haryanvi"]["5"]='Hindu: Jat';
$IMP_CASTE_ARRAY["Haryanvi"]["6"]='Hindu: Rajput';
$IMP_CASTE_ARRAY["Haryanvi"]["7"]='Hindu: Saini';
$IMP_CASTE_ARRAY["Haryanvi"]["8"]='Hindu: Yadav/Yadava';
$IMP_CASTE_ARRAY["Himachali"]["0"]='Hindu: Brahmin';
$IMP_CASTE_ARRAY["Himachali"]["1"]='Hindu: Brahmin Pandit';
$IMP_CASTE_ARRAY["Himachali"]["2"]='Hindu: Chaudary';
$IMP_CASTE_ARRAY["Himachali"]["3"]='Hindu: Rajput';
$IMP_CASTE_ARRAY["Himachali"]["4"]='Hindu: Rajput Kumaoni';
$IMP_CASTE_ARRAY["Himachali"]["5"]='Hindu: Scheduled Caste';
$IMP_CASTE_ARRAY["Himachali"]["6"]='Hindu: Thakur';
$IMP_CASTE_ARRAY["Hindi-Delhi"]["0"]='Hindu: Aggarwal';
$IMP_CASTE_ARRAY["Hindi-Delhi"]["1"]='Hindu: Arora';
$IMP_CASTE_ARRAY["Hindi-Delhi"]["2"]='Hindu: Bania';
$IMP_CASTE_ARRAY["Hindi-Delhi"]["3"]='Hindu: Brahmin';
$IMP_CASTE_ARRAY["Hindi-Delhi"]["4"]='Hindu: Jat';
$IMP_CASTE_ARRAY["Hindi-Delhi"]["5"]='Hindu: Kayastha';
$IMP_CASTE_ARRAY["Hindi-Delhi"]["6"]='Hindu: Khatri';
$IMP_CASTE_ARRAY["Hindi-Delhi"]["7"]='Hindu: Rajput';
$IMP_CASTE_ARRAY["Hindi-MP"]["0"]='Hindu: Aggarwal';
$IMP_CASTE_ARRAY["Hindi-MP"]["1"]='Hindu: Brahmin';
$IMP_CASTE_ARRAY["Hindi-MP"]["2"]='Hindu: Brahmin Saryuparin';
$IMP_CASTE_ARRAY["Hindi-MP"]["3"]='Hindu: Kayastha';
$IMP_CASTE_ARRAY["Hindi-MP"]["4"]='Hindu: Rajput';
$IMP_CASTE_ARRAY["Hindi-UP"]["0"]='Hindu: Aggarwal';
$IMP_CASTE_ARRAY["Hindi-UP"]["1"]='Hindu: Brahmin';
$IMP_CASTE_ARRAY["Hindi-UP"]["2"]='Hindu: Brahmin Kanyakubj';
$IMP_CASTE_ARRAY["Hindi-UP"]["3"]='Hindu: Brahmin Saryuparin';
$IMP_CASTE_ARRAY["Hindi-UP"]["4"]='Hindu: Kayastha';
$IMP_CASTE_ARRAY["Hindi-UP"]["5"]='Hindu: Kshatriya';
$IMP_CASTE_ARRAY["Hindi-UP"]["6"]='Hindu: Rajput';
$IMP_CASTE_ARRAY["Hindi-UP"]["7"]='Hindu: Yadav/Yadava';
$IMP_CASTE_ARRAY["Kannada"]["0"]='Hindu: Adi Karnataka';
$IMP_CASTE_ARRAY["Kannada"]["1"]='Hindu: Billava';
$IMP_CASTE_ARRAY["Kannada"]["2"]='Hindu: Brahmin';
$IMP_CASTE_ARRAY["Kannada"]["3"]='Hindu: Brahmin Madhwa';
$IMP_CASTE_ARRAY["Kannada"]["4"]='Hindu: Brahmin Smartha';
$IMP_CASTE_ARRAY["Kannada"]["5"]='Hindu: Bunt/Shetty';
$IMP_CASTE_ARRAY["Kannada"]["6"]='Hindu: Gowda';
$IMP_CASTE_ARRAY["Kannada"]["7"]='Hindu: Kuruba';
$IMP_CASTE_ARRAY["Kannada"]["8"]='Hindu: Lingayat';
$IMP_CASTE_ARRAY["Kannada"]["9"]='Hindu: Scheduled Caste';
$IMP_CASTE_ARRAY["Kannada"]["10"]='Hindu: Vokkaliga';
$IMP_CASTE_ARRAY["Kashmiri"]["0"]='Hindu: Brahmin';
$IMP_CASTE_ARRAY["Kashmiri"]["1"]='Hindu: Brahmin Kashmiri Pandit';
$IMP_CASTE_ARRAY["Kashmiri"]["2"]='Hindu: Brahmin Pandit';
$IMP_CASTE_ARRAY["Kashmiri"]["3"]='Hindu: Khatri';
$IMP_CASTE_ARRAY["Kashmiri"]["4"]='Hindu: Rajput';
$IMP_CASTE_ARRAY["Kashmiri"]["5"]='Hindu: Scheduled Caste';
$IMP_CASTE_ARRAY["Konkani"]["0"]='Hindu: Bhandari';
$IMP_CASTE_ARRAY["Konkani"]["1"]='Hindu: Brahmin';
$IMP_CASTE_ARRAY["Konkani"]["2"]='Hindu: Brahmin Daivadnya';
$IMP_CASTE_ARRAY["Konkani"]["3"]='Hindu: Brahmin Gaud Saraswat (GSB)';
$IMP_CASTE_ARRAY["Konkani"]["4"]='Hindu: Brahmin Saraswat';
$IMP_CASTE_ARRAY["Konkani"]["5"]='Hindu: Konkani';
$IMP_CASTE_ARRAY["Konkani"]["6"]='Hindu: Kshatriya';
$IMP_CASTE_ARRAY["Konkani"]["7"]='Hindu: Maratha';
$IMP_CASTE_ARRAY["Malayalam"]["0"]='Hindu: Ezhava';
$IMP_CASTE_ARRAY["Malayalam"]["1"]='Hindu: Nair';
$IMP_CASTE_ARRAY["Malayalam"]["2"]='Hindu: Thiyya';
$IMP_CASTE_ARRAY["Malayalam"]["3"]='Hindu: Vishwakarma';
$IMP_CASTE_ARRAY["Marathi"]["0"]='Hindu: Brahmin';
$IMP_CASTE_ARRAY["Marathi"]["1"]='Hindu: Brahmin Deshastha';
$IMP_CASTE_ARRAY["Marathi"]["2"]='Hindu: Chambhar';
$IMP_CASTE_ARRAY["Marathi"]["3"]='Hindu: Dhangar';
$IMP_CASTE_ARRAY["Marathi"]["4"]='Hindu: Kunbi';
$IMP_CASTE_ARRAY["Marathi"]["5"]='Hindu: Mali';
$IMP_CASTE_ARRAY["Marathi"]["6"]='Hindu: Maratha';
$IMP_CASTE_ARRAY["Marathi"]["7"]='Hindu: Teli';
$IMP_CASTE_ARRAY["Oriya"]["0"]='Hindu: Brahmin';
$IMP_CASTE_ARRAY["Oriya"]["1"]='Hindu: Karana';
$IMP_CASTE_ARRAY["Oriya"]["2"]='Hindu: Khandayat';
$IMP_CASTE_ARRAY["Oriya"]["3"]='Hindu: Kshatriya';
$IMP_CASTE_ARRAY["Oriya"]["4"]='Hindu: Teli';
$IMP_CASTE_ARRAY["Punjabi"]["0"]='Hindu: Aggarwal';
$IMP_CASTE_ARRAY["Punjabi"]["1"]='Hindu: Arora';
$IMP_CASTE_ARRAY["Punjabi"]["2"]='Hindu: Brahmin';
$IMP_CASTE_ARRAY["Punjabi"]["3"]='Hindu: Brahmin Saraswat';
$IMP_CASTE_ARRAY["Punjabi"]["4"]='Hindu: Khatri';
$IMP_CASTE_ARRAY["Punjabi"]["5"]='Hindu: Rajput';
$IMP_CASTE_ARRAY["Rajasthani"]["0"]='Hindu: Aggarwal';
$IMP_CASTE_ARRAY["Rajasthani"]["1"]='Hindu: Brahmin';
$IMP_CASTE_ARRAY["Rajasthani"]["2"]='Hindu: Brahmin Gaur';
$IMP_CASTE_ARRAY["Rajasthani"]["3"]='Hindu: Jat';
$IMP_CASTE_ARRAY["Rajasthani"]["4"]='Hindu: Maheshwari';
$IMP_CASTE_ARRAY["Rajasthani"]["5"]='Hindu: Marwari';
$IMP_CASTE_ARRAY["Rajasthani"]["6"]='Hindu: Rajput';
$IMP_CASTE_ARRAY["Sikkim/ Nepali"]["0"]='Hindu: Brahmin';
$IMP_CASTE_ARRAY["Sikkim/ Nepali"]["1"]='Hindu: Chhetri';
$IMP_CASTE_ARRAY["Sikkim/ Nepali"]["2"]='Hindu: Kshatriya';
$IMP_CASTE_ARRAY["Sikkim/ Nepali"]["3"]='Hindu: Nepali';
$IMP_CASTE_ARRAY["Sindhi"]["0"]='Hindu: Sindhi';
$IMP_CASTE_ARRAY["Sindhi"]["1"]='Hindu: Sindhi Baibhand';
$IMP_CASTE_ARRAY["Sindhi"]["2"]='Hindu: Sindhi Larkana';
$IMP_CASTE_ARRAY["Sindhi"]["3"]='Hindu: Sindhi Sahiti';
$IMP_CASTE_ARRAY["Sindhi"]["4"]='Hindu: Sindhi Sakkhar';
$IMP_CASTE_ARRAY["Sindhi"]["5"]='Hindu: Sindhi Shikarpuri';
$IMP_CASTE_ARRAY["Tamil"]["0"]='Hindu: Adi Dravida';
$IMP_CASTE_ARRAY["Tamil"]["1"]='Hindu: Brahmin';
$IMP_CASTE_ARRAY["Tamil"]["2"]='Hindu: Brahmin Iyer';
$IMP_CASTE_ARRAY["Tamil"]["3"]='Hindu: Chettiar';
$IMP_CASTE_ARRAY["Tamil"]["4"]='Hindu: Gounder';
$IMP_CASTE_ARRAY["Tamil"]["5"]='Hindu: Kongu Vellala Gounder';
$IMP_CASTE_ARRAY["Tamil"]["6"]='Hindu: Mudaliar';
$IMP_CASTE_ARRAY["Tamil"]["7"]='Hindu: Nadar';
$IMP_CASTE_ARRAY["Tamil"]["8"]='Hindu: Pillai';
$IMP_CASTE_ARRAY["Tamil"]["9"]='Hindu: Vannia Kula Kshatriyar';
$IMP_CASTE_ARRAY["Tamil"]["10"]='Hindu: Vanniyar';
$IMP_CASTE_ARRAY["Telugu"]["0"]='Hindu: Arya Vysya';
$IMP_CASTE_ARRAY["Telugu"]["1"]='Hindu: Balija';
$IMP_CASTE_ARRAY["Telugu"]["2"]='Hindu: Balija Naidu';
$IMP_CASTE_ARRAY["Telugu"]["3"]='Hindu: Brahmin';
$IMP_CASTE_ARRAY["Telugu"]["4"]='Hindu: Kamma';
$IMP_CASTE_ARRAY["Telugu"]["5"]='Hindu: Kapu';
$IMP_CASTE_ARRAY["Telugu"]["6"]='Hindu: Mala';
$IMP_CASTE_ARRAY["Telugu"]["7"]='Hindu: Naidu';
$IMP_CASTE_ARRAY["Telugu"]["8"]='Hindu: Padmashali';
$IMP_CASTE_ARRAY["Telugu"]["9"]='Hindu: Reddy';
$IMP_CASTE_ARRAY["Telugu"]["10"]='Hindu: Yadav/Yadava';

fwrite($fp,"\n\n\npublic static \$IMP_CASTE_ARR=array(");
				foreach($IMP_CASTE_ARRAY as $K=>$V)
				{
					$sql_mtongue= "select VALUE from newjs.MTONGUE Where SMALL_LABEL='".$K."'";
					$res_mtongue=$mysqlObj->executeQuery($sql_mtongue,$db) or die(mysql_.error());
					$row_motngue=mysql_fetch_array($res_mtongue);
					$mtongue=$row_motngue["VALUE"];
					foreach ($V as $k=>$v)
					{
						$sql_caste1= "select VALUE from newjs.CASTE Where LABEL='".$v."' and REG_DISPLAY!='N'";
						$res_caste1=$mysqlObj->executeQuery($sql_caste1,$db) or die(mysql_.error());
						while($row1=mysql_fetch_array($res_caste1))
						{ 
							
							$casteFinalArr[$mtongue][$row1["VALUE"]]=$v;
						}						
					}
				}
				$id="";
				$id1=-1;
				foreach ($casteFinalArr as $k2=>$v2)
				{
					if($id!=$k2)
					{
						
						$id1++;
						if($id1){
							fwrite($fp,"\"),");}
						$id=$k2;
						fwrite($fp,"\n\n\"".$k2."\"=>array(\n");
					}
					else
					fwrite($fp,"\",");	
					
					$id3="";
					$id4=-1;
					foreach($v2 as $key2=>$val2)
						{
							if($id3!=$key2)
							{
								$id4++;
								if($id4){
									fwrite($fp,"\",");}
								$id3=$key2;
							}
							else
							fwrite($fp,"\",");
							
							fwrite($fp,"\"".$key2."\"=>\"".$val2);
						}
				}
				fwrite($fp,"\"));\n");
					
				
			/*	$value=1;
			foreach ($arr as $k=>$v)
			{
				$len=count($v);
				for($i=0;$i<$len;$i++)
				{
					foreach($v as $key=>$val)
					{
						if($value==$val)
						{
							$finalArr[$k][$key]=$val;
							$value++;
						}
					}
				}
				$value=1;
			}
		
		
			$sql_caste1= "select VALUE,LABEL,SORTBY from newjs.CASTE ORDER BY SORTBY ";
			$res_caste1=$mysqlObj->executeQuery($sql_caste1,$db) or die(mysql_.error());
			while($row1=mysql_fetch_array($res_caste1))
			{
				$casteArr[$row1["SORTBY"]][$row1["LABEL"]]=$row1["VALUE"];
				$casteMappingArr[$row1["LABEL"]]=$row1["VALUE"];
			}
			//print_r($casteArr);die;
			foreach($finalArr as $K1=>$V1)
			{
				foreach($V1 as $k=>$v)
				{
					$casteFinalArr[$K1][$k]=$v;
				}
						
				foreach($casteArr as $K=>$V)
				{
					foreach($V as $KEY=>$VAL)
					{
						if(!in_array($VAL, $V1))
						$casteFinalArr[$K1][$KEY]=$K;
					}
				}
			}
			//print_r($casteFinalArr);die;
			fwrite($fp,"public static \$IMP_CASTE=array(");
			$id="";
			$id1=-1;
			foreach ($casteFinalArr as $k2=>$v2)
			{
				if($id!=$k2)
				{
					
					$id1++;
					if($id1){
						fwrite($fp,"\"),");}
					$id=$k2;
					fwrite($fp,"\n\n\"".$k2."\"=>array(\n");
				}
				else
				fwrite($fp,"\",");	
				
				$id3="";
				$id4=-1;
				
				foreach($v2 as $key2=>$val2)
					{
						if($id3!=$key2)
						{
							$id4++;
							if($id4){
								fwrite($fp,"\",");}
							$id3=$key2;
						}
						else
						fwrite($fp,"\",");
						
						fwrite($fp,"\"".$key2."\"=>\"".$casteMappingArr[$key2]);
					}
					
				
				
			}
			fwrite($fp,")");*/
			
fwrite($fp,"\n\n}");

function impCasteArray($mtongue){
	$impCasteArr=AutoSuggestEnum::$IMP_CASTE_ARR;
	print_r($impCasteArr[$mtongue]);
	return($impCasteArr[$mtongue]);
}
?>
