<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

function read_file($filename)
{
	$file = fopen($filename, "r") or exit("Unable to open file!");
	$x=1;
	while(!feof($file))
	{
        	if($x!=1)
        	{
        	        $data[] = trim(fgets($file));
        	}
        	else
        	{
                	fgets($file);
                	$x++;
        	}
	}
	fclose($file);
	return $data;
}

//scoring_new|caste|caste
function scoring_update($table_name,$db_name,$mysqlObjM,$dbM)
{
	$select_statement = "SELECT VALUE FROM newjs.OCCUPATION WHERE VALUE>44";
	$result = $mysqlObjM->executeQuery($select_statement,$dbM) or $mysqlObjM->logError($select_statement);
	while($row = $mysqlObjM->fetchArray($result))
       	{
		$insert_statement = "INSERT INTO ".$db_name.".".$table_name."(occupation,gender,profile_type,bias) VALUES (".$row["VALUE"].",\"M\",\"F\",0),(".$row["VALUE"].",\"F\",\"F\",0),(".$row["VALUE"].",\"M\",\"R\",0),(".$row["VALUE"].",\"F\",\"R\",0),(".$row["VALUE"].",\"M\",\"O\",0),(".$row["VALUE"].",\"F\",\"O\",0)";
		$mysqlObjM->executeQuery($insert_statement,$dbM) or $mysqlObjM->logError($insert_statement);
	}
}

//trends update
function trends_update($table_name,$column_name,$db_name,$primary_key,$mysqlObjM,$dbM)
{
	$select_statement = "SELECT VALUE FROM newjs.OCCUPATION WHERE VALUE>44";
        $result = $mysqlObjM->executeQuery($select_statement,$dbM) or $mysqlObjM->logError($select_statement);
        while($row = $mysqlObjM->fetchArray($result))
        {
                $insert_statement = "INSERT INTO ".$db_name.".".$table_name."(".$primary_key.",".$column_name.") VALUES (".$row["VALUE"].",0.001)";
                $mysqlObjM->executeQuery($insert_statement,$dbM) or $mysqlObjM->logError($insert_statement);
        }
}

?>
