<?php

	include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/flag.php");
	
	$dn=connect_slave();
	
	$timeval = date("YmdHis",time()-24*60*60);
	
	$fields[0]["FIELDNAME"]="SUBCASTE";
	$fields[0]["FLAGNAME"]="SUBCASTE";
	$fields[1]["FIELDNAME"]="CITY_BIRTH";
	$fields[1]["FLAGNAME"]="CITYBIRTH";
	$fields[2]["FIELDNAME"]="NAKSHATRA";
	$fields[2]["FLAGNAME"]="NAKSHATRA";
	$fields[3]["FIELDNAME"]="YOURINFO";
	$fields[3]["FLAGNAME"]="YOURINFO";
	$fields[4]["FIELDNAME"]="GOTHRA";
	$fields[4]["FLAGNAME"]="GOTHRA";
	$fields[5]["FIELDNAME"]="FAMILYINFO";
	$fields[5]["FLAGNAME"]="FAMILYINFO";
	$fields[6]["FIELDNAME"]="SPOUSE";
	$fields[6]["FLAGNAME"]="SPOUSE";
	$fields[7]["FIELDNAME"]="FATHER_INFO";
	$fields[7]["FLAGNAME"]="FATHER_INFO";
	$fields[8]["FIELDNAME"]="SIBLING_INFO";
	$fields[8]["FLAGNAME"]="SIBLING_INFO";
	$fields[9]["FIELDNAME"]="JOB_INFO";
	$fields[9]["FLAGNAME"]="JOB_INFO";
	$fields[10]["FIELDNAME"]="EDUCATION";
	$fields[10]["FLAGNAME"]="EDUCATION";
	
	for($i=0;$i<count($fields);$i++)
	{
		$sql="select " . $fields[$i]["FIELDNAME"] . ",SCREENING,USERNAME from JPROFILE where TIMESTAMP > '$timeval' and ACTIVATED='Y' and " . $fields[$i]["FIELDNAME"] . " REGEXP '[0-9]{7,}' and " . $fields[$i]["FIELDNAME"] . " NOT REGEXP '[0-9]{4,}000'";
		$result=mysql_query_decide($sql) or die(mysql_error_js());
		
		while($row=mysql_fetch_array($result))
		{
			if(isFlagSet($fields[$i]["FLAGNAME"],$row["SCREENING"]))
				$arr[] = $row["USERNAME"];
		}
	}

	if(is_array($arr))
	{	
	$arrunique=array_unique($arr);
	
	for($i=0;$i<count($arrunique);$i++)
	{
		if($arrunique[$i]!="")
			echo $arrunique[$i] . "\n";
	}
	}
?>
