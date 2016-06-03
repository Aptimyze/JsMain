<?php 
	include_once(JsConstants::$docRoot."/classes/Mysql.class.php");
	$fp=fopen(JsConstants::$docRoot."/new_matchalert/matchalert_dropdowns.php","w");
	
	if(!$fp)
		exit;
	
	fwrite($fp,"<?php\r\n");

	$mysqlObj = new Mysql;	
	$db=$mysqlObj->connect("alerts") or die("Unable to connect to slave");

	$sql = "SELECT SQL_CACHE * FROM matchalerts.hidden_weights";
	$result = $mysqlObj->executeQuery($sql,$db,'',1) or $mysqlObj->logError($sql,1);
	while($row = $mysqlObj->fetchArray($result))
	{
	        fwrite($fp,"\$HIDDEN_WEIGHTS_ARRAY[\"".$row["label"]."\"][\"label\"] = \"".$row["label"]."\";\r\n");
	        fwrite($fp,"\$HIDDEN_WEIGHTS_ARRAY[\"".$row["label"]."\"][\"constant\"] = \"".$row["constant"]."\";\r\n");
	        fwrite($fp,"\$HIDDEN_WEIGHTS_ARRAY[\"".$row["label"]."\"][\"bias_age\"] = \"".$row["bias_age"]."\";\r\n");
	        fwrite($fp,"\$HIDDEN_WEIGHTS_ARRAY[\"".$row["label"]."\"][\"bias_community\"] = \"".$row["bias_community"]."\";\r\n");
	        fwrite($fp,"\$HIDDEN_WEIGHTS_ARRAY[\"".$row["label"]."\"][\"bias_occupation\"] = \"".$row["bias_occupation"]."\";\r\n");
	        fwrite($fp,"\$HIDDEN_WEIGHTS_ARRAY[\"".$row["label"]."\"][\"bias_edu_level\"] = \"".$row["bias_edu_level"]."\";\r\n");
	        fwrite($fp,"\$HIDDEN_WEIGHTS_ARRAY[\"".$row["label"]."\"][\"bias_height\"] = \"".$row["bias_height"]."\";\r\n");
	        fwrite($fp,"\$HIDDEN_WEIGHTS_ARRAY[\"".$row["label"]."\"][\"bias_income\"] = \"".$row["bias_income"]."\";\r\n");
	        fwrite($fp,"\$HIDDEN_WEIGHTS_ARRAY[\"".$row["label"]."\"][\"bias_caste\"] = \"".$row["bias_caste"]."\";\r\n");
	        fwrite($fp,"\$HIDDEN_WEIGHTS_ARRAY[\"".$row["label"]."\"][\"bias_btype\"] = \"".$row["bias_btype"]."\";\r\n");
	        fwrite($fp,"\$HIDDEN_WEIGHTS_ARRAY[\"".$row["label"]."\"][\"bias_cczone\"] = \"".$row["bias_cczone"]."\";\r\n");
	}
	mysql_free_result($result);

	$sql = "SELECT SQL_CACHE * FROM matchalerts.final_weights";
	$result = $mysqlObj->executeQuery($sql,$db,'',1) or $mysqlObj->logError($sql,1);
	while($row = $mysqlObj->fetchArray($result))
	{
	        fwrite($fp,"\$FINAL_WEIGHTS_ARRAY[\"constant\"] = \"".$row["constant"]."\";\r\n");
	        fwrite($fp,"\$FINAL_WEIGHTS_ARRAY[\"H1\"] = \"".$row["H1"]."\";\r\n");
	        fwrite($fp,"\$FINAL_WEIGHTS_ARRAY[\"H2\"] = \"".$row["H2"]."\";\r\n");
	        fwrite($fp,"\$FINAL_WEIGHTS_ARRAY[\"H3\"] = \"".$row["H3"]."\";\r\n");
	        fwrite($fp,"\$FINAL_WEIGHTS_ARRAY[\"H4\"] = \"".$row["H4"]."\";\r\n");
	        fwrite($fp,"\$FINAL_WEIGHTS_ARRAY[\"H5\"] = \"".$row["H5"]."\";\r\n");
	}
	mysql_free_result($result);

	$sql = "SELECT SQL_CACHE VALUE,ZONE_CODE FROM matchalerts.zone_mapping_table";
	$result = $mysqlObj->executeQuery($sql,$db,'',1) or $mysqlObj->logError($sql,1);
	while($row = $mysqlObj->fetchArray($result))
	{
	        fwrite($fp,"\$STATE_ZONE_ARRAY[\"".$row["VALUE"]."\"] = \"".$row["ZONE_CODE"]."\";\r\n");
	}
	mysql_free_result($result);

 	fwrite($fp,"?>\r\n");
	fclose($fp);
?>
