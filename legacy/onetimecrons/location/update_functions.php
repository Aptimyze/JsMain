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

function normal_update($old_value,$new_value,$table_name,$column_name,$db_name,$primary_key,$mysqlObjM,$mysqlObjS,$dbM,$dbS)
{
	foreach($old_value as $k=>$v)
	{
		$select_statement = "SELECT ".$primary_key.",".$column_name." FROM ".$db_name.".".$table_name." WHERE ".$column_name." = \"".$v."\"";
//		echo $select_statement."<br />";
		$result = $mysqlObjS->executeQuery($select_statement,$dbS) or $mysqlObjS->logError($select_statement);
		while($row = $mysqlObjS->fetchArray($result))
		{
			$update_statement = "UPDATE ".$db_name.".".$table_name." SET ".$column_name." = \"".$new_value[$k]."\" WHERE ".$column_name." = \"".$row[$column_name]."\" AND ".$primary_key." = \"".$row[$primary_key]."\"";
//			echo $update_statement."<br />";
			$mysqlObjM->executeQuery($update_statement,$dbM) or $mysqlObjM->logError($update_statement);
		}
	} 
}

//'ABC','BCD','FGH'
function comma_separated_type1_update($old_value,$new_value,$table_name,$column_name,$db_name,$primary_key,$mysqlObjM,$mysqlObjS,$dbM,$dbS)
{
	foreach($old_value as $k=>$v)
	{
		$select_statement = "SELECT ".$primary_key.",".$column_name." FROM ".$db_name.".".$table_name." WHERE ".$column_name." REGEXP (\"'".$v."'\") = 1";
//              echo $select_statement."<br />";
                $result = $mysqlObjS->executeQuery($select_statement,$dbS) or $mysqlObjS->logError($select_statement);
                while($row = $mysqlObjS->fetchArray($result))
		{
			$data = explode(",",$row[$column_name]);
                        foreach ($data as $kk=>$vv)
                        {
				$vv = rtrim($vv,"'");
				$vv = ltrim($vv,"'");
                                if (trim($vv) == $v)
                                {
                                        $data[$kk] = "'".$new_value[$k]."'";
                                }
                        }
                        $newUpdateVal = implode(",",array_unique($data));

			if($table_name == "AP_TEMP_DPP" && $column_name == "PARTNER_CITYRES")
                                $update_statement = "UPDATE ".$db_name.".".$table_name." SET ".$column_name." = \"".$newUpdateVal."\" WHERE ".$column_name." = \"".$row[$column_name]."\" AND CREATED_BY = \"".$row["CREATED_BY"]."\" AND PROFILEID = ".$row["PROFILEID"];
                        else
                                $update_statement = "UPDATE ".$db_name.".".$table_name." SET ".$column_name." = \"".$newUpdateVal."\" WHERE ".$column_name." = \"".$row[$column_name]."\" AND ".$primary_key." = ".$row[$primary_key];
//			echo $update_statement."<br />";
			$mysqlObjM->executeQuery($update_statement,$dbM) or $mysqlObjM->logError($update_statement);
		}
	}
}

//ABC,BCD,LMN
function comma_separated_type3_update($old_value,$new_value,$table_name,$column_name,$db_name,$primary_key,$mysqlObjM,$mysqlObjS,$dbM,$dbS)
{
	foreach($old_value as $k=>$v)
        {
		$select_statement = "SELECT ".$primary_key.",".$column_name." FROM ".$db_name.".".$table_name." WHERE ".$column_name." REGEXP (\"^".$v.",|,".$v.",|,".$v."$|^".$v."$\") = 1";
//              echo $select_statement."<br />";
                $result = $mysqlObjS->executeQuery($select_statement,$dbS) or $mysqlObjS->logError($select_statement);
                while($row = $mysqlObjS->fetchArray($result))
                {
			$data = explode(",",$row[$column_name]);
                        foreach ($data as $kk=>$vv)
                        {
                                if (trim($vv) == $v)
                                {
                                        $data[$kk] = $new_value[$k];
                                }
                        }
                        $newUpdateVal = implode(",",array_unique($data));
                        $update_statement = "UPDATE ".$db_name.".".$table_name." SET ".$column_name." = \"".$newUpdateVal."\" WHERE ".$column_name." = \"".$row[$column_name]."\" AND ".$primary_key." = ".$row[$primary_key];
//                      echo $update_statement."<br />";
                        $mysqlObjM->executeQuery($update_statement,$dbM) or $mysqlObjM->logError($update_statement);
		}
	}
}

function special_update($old_value,$new_value,$table_name,$column_name,$db_name,$mysqlObjM,$mysqlObjS,$dbM,$dbS)
{
	foreach($old_value as $k=>$v)
	{
		$update_statement = "UPDATE ".$db_name.".".$table_name." SET ".$column_name." = \"".$new_value[$k]."\" WHERE ".$column_name." = \"".$v."\"";
		$mysqlObjM->executeQuery($update_statement,$dbM) or $mysqlObjM->logError($update_statement);
	}
}

function pipe_separated_update($old_value,$new_value,$table_name,$column_name,$db_name,$primary_key,$mysqlObjM,$mysqlObjS,$dbM,$dbS)
{
        foreach($old_value as $k=>$v)
        {
                $select_statement = "SELECT ".$primary_key.",".$column_name." FROM ".$db_name.".".$table_name." WHERE ".$column_name." REGEXP (\"\\\\|".$v."#\") = 1";
//              echo $select_statement."<br />";
                $result = $mysqlObjS->executeQuery($select_statement,$dbS) or $mysqlObjS->logError($select_statement);
                while($row = $mysqlObjS->fetchArray($result))
                {
                        $tempOldVal = trim($row[$column_name],"|");
                        $data = explode("|",$tempOldVal);
                        foreach ($data as $kk=>$vv)
                        {
                                $mapArr = explode("#",$vv);
                                if (trim($mapArr[0]) == $v)
                                {
                                        $mapArr[0] = $new_value[$k];
                                        $data[$kk] = implode("#",$mapArr);
                                }
                        }
                        if(count($data)>1)
                        {
                                foreach($data as $kk=>$vv)
                                {
                                        $mapArr = explode("#",$vv);
                                        $casteArr[] = $mapArr[0];
                                        if(!$percentArr[$mapArr[0]])
                                                $percentArr[$mapArr[0]]=0;
                                        $percentArr[$mapArr[0]] = $percentArr[$mapArr[0]] + $mapArr[1];
                                }
                                $casteArr = array_unique($casteArr);
                                unset($data);
                                foreach($casteArr as $kk=>$vv)
                                {
                                        $data[] = $vv."#".$percentArr[$vv];
                                }
                                unset($casteArr);
                                unset($percentArr);
                        }
                        $newUpdateVal = "|".implode("|",$data)."|";
                        $update_statement = "UPDATE ".$db_name.".".$table_name." SET ".$column_name." = \"".$newUpdateVal."\" WHERE ".$column_name." = \"".$row[$column_name]."\" AND ".$primary_key." = ".$row[$primary_key];
                      //echo $update_statement."<br />";
                        $mysqlObjM->executeQuery($update_statement,$dbM) or $mysqlObjM->logError($update_statement);
                }
        }
}

//trends update
function trends_update($old_value,$new_value,$table_name,$column_name,$db_name,$primary_key,$mysqlObjM,$mysqlObjS,$dbM,$dbS)
{
	$arr1 = array('MP12','MP06','MP03','MP01','MP20','BI07','BI04','BI02','BI01','UP23','UP15','UP28','UP27','UP07');
        foreach($old_value as $k=>$v)
        {
                $select_statement = "SELECT ".$primary_key.",".$column_name." FROM ".$db_name.".".$table_name." WHERE ".$primary_key." = \"".$v."\"";
                $result = $mysqlObjS->executeQuery($select_statement,$dbS) or $mysqlObjS->logError($select_statement);
                $row = $mysqlObjS->fetchArray($result);
                if(trim($row[$column_name]))
                {
			if(in_array($v,$arr1))
			{
				$update_statement = "UPDATE ".$db_name.".".$table_name." SET ".$primary_key." = \"".$new_value[$k]."\" WHERE ".$primary_key." = \"".$v."\"";
			}
			else
			{
                        	$update_statement = "UPDATE ".$db_name.".".$table_name." SET ".$column_name." = ".$column_name." + ".$row[$column_name]." WHERE ".$primary_key." = \"".$new_value[$k]."\"";
			}
                        $mysqlObjM->executeQuery($update_statement,$dbM) or $mysqlObjM->logError($update_statement);
                }
        }

        $delete_statement = "DELETE FROM ".$db_name.".".$table_name." WHERE ".$primary_key." IN ('".implode("','",$old_value)."')";
        $mysqlObjM->executeQuery($delete_statement,$dbM) or $mysqlObjM->logError($delete_statement);

        $select_statement = "SELECT VALUE FROM newjs.CITY_NEW WHERE ID>3373";
        $result = $mysqlObjM->executeQuery($select_statement,$dbM) or $mysqlObjM->logError($select_statement);
        while($row = $mysqlObjS->fetchArray($result))
        {
                $insert_statement = "INSERT INTO ".$db_name.".".$table_name."(".$primary_key.",".$column_name.") VALUES ('".$row["VALUE"]."',0.001)";
                $mysqlObjM->executeQuery($insert_statement,$dbM) or $mysqlObjM->logError($insert_statement);
        }
}

//scoring_new|caste|caste
function scoring_update($old_value,$new_value,$table_name,$column_name,$db_name,$mysqlObjM,$mysqlObjS,$dbM,$dbS)
{
	$arr1 = array('MP12','MP06','MP03','MP01','MP20','BI07','BI04','BI02','BI01','UP23','UP15','UP28','UP27','UP07','UT');

        foreach($old_value as $k=>$v)
        {
                $select_statement = "SELECT city,profile_type,bias FROM ".$db_name.".".$table_name." WHERE city = \"".$v."\"";
                $result = $mysqlObjS->executeQuery($select_statement,$dbS) or $mysqlObjS->logError($select_statement);
                while($row = $mysqlObjS->fetchArray($result))
		{
                if(trim($row[$column_name]))
                {
			if(in_array($v,$arr1))
			{
				$update_statement = "UPDATE ".$db_name.".".$table_name." SET ".$column_name." = \"".$new_value[$k]."\" WHERE ".$column_name." = \"".$v."\"";
			}
			else
			{
                        	$update_statement = "UPDATE ".$db_name.".".$table_name." SET bias = bias + ".$row["bias"]." WHERE ".$column_name." = \"".$new_value[$k]."\" AND profile_type = '".$row["profile_type"]."'";
			}
			//echo $update_statement."<br>";
                        $mysqlObjM->executeQuery($update_statement,$dbM) or $mysqlObjM->logError($update_statement);
                }
		}
        }
	
        $delete_statement = "DELETE FROM ".$db_name.".".$table_name." WHERE ".$column_name." IN ('".implode("','",$old_value)."')";
        $mysqlObjM->executeQuery($delete_statement,$dbM) or $mysqlObjM->logError($delete_statement);

        $select_statement = "SELECT VALUE FROM newjs.CITY_NEW WHERE ID>3373 AND VALUE!='UK'";
        $result = $mysqlObjM->executeQuery($select_statement,$dbM) or $mysqlObjM->logError($select_statement);
        while($row = $mysqlObjS->fetchArray($result))
        {
                $insert_statement = "INSERT INTO ".$db_name.".".$table_name."(city,profile_type,bias) VALUES (\"".$row["VALUE"]."\",\"F\",0),(\"".$row["VALUE"]."\",\"R\",0),(\"".$row["VALUE"]."\",\"O\",0)";
                $mysqlObjM->executeQuery($insert_statement,$dbM) or $mysqlObjM->logError($insert_statement);
        }
}

//COMMUNITY PAGES
function community_pages_update1($old_value,$new_value,$table_name,$column_name,$db_name,$primary_key,$mysqlObjM,$mysqlObjS,$dbM,$dbS)
{
        foreach($old_value as $k=>$v)
        {
                $select_statement = "SELECT ".$primary_key.",".$column_name." FROM ".$db_name.".".$table_name." WHERE ".$column_name." REGEXP (\"^".$v.",|,".$v.",|,".$v."$|^".$v."$\") = 1 AND TYPE = \"CITY\"";
//              echo $select_statement."<br />";
                $result = $mysqlObjS->executeQuery($select_statement,$dbS) or $mysqlObjS->logError($select_statement);
                while($row = $mysqlObjS->fetchArray($result))
                {
                        $data = explode(",",$row[$column_name]);
                        foreach ($data as $kk=>$vv)
                        {
                                if (trim($vv) == $v)
                                {
                                        $data[$kk] = $new_value[$k];
                                }
                        }
                        $newUpdateVal = implode(",",array_unique($data));
                        $update_statement = "UPDATE ".$db_name.".".$table_name." SET ".$column_name." = \"".$newUpdateVal."\" WHERE ".$column_name." = \"".$row[$column_name]."\" AND ".$primary_key." = ".$row[$primary_key]." AND TYPE = \"CITY\"";
//                      echo $update_statement."<br />";
                        $mysqlObjM->executeQuery($update_statement,$dbM) or $mysqlObjM->logError($update_statement);
                }
        }
}

function community_pages_update2($old_value,$new_value,$table_name,$column_name,$db_name,$primary_key,$mysqlObjM,$mysqlObjS,$dbM,$dbS)
{
        foreach($old_value as $k=>$v)
        {
                $select_statement = "SELECT ".$primary_key.",".$column_name." FROM ".$db_name.".".$table_name." WHERE ".$column_name." REGEXP (\"^".$v.",|,".$v.",|,".$v."$|^".$v."$\") = 1 AND PARENT_TYPE = \"CITY\"";
//              echo $select_statement."<br />";
                $result = $mysqlObjS->executeQuery($select_statement,$dbS) or $mysqlObjS->logError($select_statement);
                while($row = $mysqlObjS->fetchArray($result))
                {
                        $data = explode(",",$row[$column_name]);
                        foreach ($data as $kk=>$vv)
                        {
                                if (trim($vv) == $v)
                                {
                                        $data[$kk] = $new_value[$k];
                                }
                        }
                        $newUpdateVal = implode(",",array_unique($data));
                        $update_statement = "UPDATE ".$db_name.".".$table_name." SET ".$column_name." = \"".$newUpdateVal."\" WHERE ".$column_name." = \"".$row[$column_name]."\" AND ".$primary_key." = ".$row[$primary_key]." AND PARENT_TYPE = \"CITY\"";
//                      echo $update_statement."<br />";
                        $mysqlObjM->executeQuery($update_statement,$dbM) or $mysqlObjM->logError($update_statement);
                }
        }
}

function community_pages_update3($old_value,$new_value,$table_name,$column_name,$db_name,$primary_key,$mysqlObjM,$mysqlObjS,$dbM,$dbS)
{
        foreach($old_value as $k=>$v)
        {
                $select_statement = "SELECT ".$primary_key.",".$column_name." FROM ".$db_name.".".$table_name." WHERE ".$column_name." REGEXP (\"^".$v.",|,".$v.",|,".$v."$|^".$v."$\") = 1 AND MAPPED_TYPE = \"CITY\"";
//              echo $select_statement."<br />";
                $result = $mysqlObjS->executeQuery($select_statement,$dbS) or $mysqlObjS->logError($select_statement);
                while($row = $mysqlObjS->fetchArray($result))
                {
                        $data = explode(",",$row[$column_name]);
                        foreach ($data as $kk=>$vv)
                        {
                                if (trim($vv) == $v)
                                {
                                        $data[$kk] = $new_value[$k];
                                }
                        }
                        $newUpdateVal = implode(",",array_unique($data));
                        $update_statement = "UPDATE ".$db_name.".".$table_name." SET ".$column_name." = \"".$newUpdateVal."\" WHERE ".$column_name." = \"".$row[$column_name]."\" AND ".$primary_key." = ".$row[$primary_key]." AND MAPPED_TYPE = \"CITY\"";
//                      echo $update_statement."<br />";
                        $mysqlObjM->executeQuery($update_statement,$dbM) or $mysqlObjM->logError($update_statement);
                }
        }
}

function scoring_update1($old_value,$new_value,$table_name,$column_name,$db_name,$mysqlObjM,$mysqlObjS,$dbM,$dbS)
{
	$update_statement = "UPDATE ".$db_name.".".$table_name." SET ".$column_name." = 'UK' WHERE ".$column_name." = 'UT'";
	$mysqlObjM->executeQuery($update_statement,$dbM) or $mysqlObjM->logError($update_statement);

	$update_statement = "UPDATE ".$db_name.".".$table_name." SET CityZone = CASE City WHEN 'DD' THEN 'West' WHEN 'DN' THEN 'West' WHEN 'MA' THEN 'East' WHEN 'SI' THEN 'East' WHEN 'SI01' THEN 'East' END WHERE City IN ('DD','DN','MA','SI','SI01')";
	$mysqlObjM->executeQuery($update_statement,$dbM) or $mysqlObjM->logError($update_statement);
	
	$insert_statement = "INSERT INTO ".$db_name.".".$table_name."(City,CityZone) VALUES ('AN','South'),('DE','North'),('GO','West'),('PO','South'),('PH','North')";
	$mysqlObjM->executeQuery($insert_statement,$dbM) or $mysqlObjM->logError($insert_statement);

	$select_statement = "SELECT City,CityZone FROM ".$db_name.".".$table_name." WHERE LENGTH(City) = 2";
	$result = $mysqlObjM->executeQuery($select_statement,$dbM) or $mysqlObjM->logError($select_statement);
        while($row = $mysqlObjS->fetchArray($result))
        {
		$zoneArr[$row["City"]]=$row["CityZone"];
	}

	$select_statement = "SELECT VALUE FROM newjs.CITY_NEW WHERE ID>3373 AND TYPE!='STATE'";
        $result = $mysqlObjM->executeQuery($select_statement,$dbM) or $mysqlObjM->logError($select_statement);
	$insert_statement = "INSERT INTO ".$db_name.".".$table_name."(City,CityZone) VALUES ";
        while($row = $mysqlObjS->fetchArray($result))
        {
		$index = substr($row["VALUE"],0,2);
                $insert_statement = $insert_statement."(\"".$row["VALUE"]."\",\"".$zoneArr[$index]."\"),";
        }
	$insert_statement = rtrim($insert_statement,",");
      	$mysqlObjM->executeQuery($insert_statement,$dbM) or $mysqlObjM->logError($insert_statement);

	foreach($old_value as $k=>$v)
        {
		$update_statement = "UPDATE ".$db_name.".".$table_name." SET ".$column_name." = \"".$new_value[$k]."\" WHERE ".$column_name." = \"".$v."\"";
		$mysqlObjM->executeQuery($update_statement,$dbM) or $mysqlObjM->logError($update_statement);
	}	

	foreach($zoneArr as $k=>$v)
	{
		$update_statement = "UPDATE ".$db_name.".".$table_name." SET CityZone = \"".$v."\" WHERE SUBSTRING(City,1,2) = \"".$k."\"";
		$mysqlObjM->executeQuery($update_statement,$dbM) or $mysqlObjM->logError($update_statement);
	}
}
?>
