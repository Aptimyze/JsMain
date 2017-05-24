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
		$select_statement = "SELECT ".$primary_key.",".$column_name." FROM ".$db_name.".".$table_name." WHERE ".$column_name." = ".$v;
//		echo $select_statement."<br />";
		$result = $mysqlObjS->executeQuery($select_statement,$dbS) or $mysqlObjS->logError($select_statement);
		while($row = $mysqlObjS->fetchArray($result))
		{
			$update_statement = "UPDATE ".$db_name.".".$table_name." SET ".$column_name." = ".$new_value[$k]." WHERE ".$column_name." = ".$row[$column_name]." AND ".$primary_key." = ".$row[$primary_key];
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

			if($table_name == "AP_TEMP_DPP" && $column_name == "PARTNER_CASTE")
				$update_statement = "UPDATE ".$db_name.".".$table_name." SET ".$column_name." = \"".$newUpdateVal."\" WHERE ".$column_name." = \"".$row[$column_name]."\" AND CREATED_BY = \"".$row["CREATED_BY"]."\" AND PROFILEID = ".$row["PROFILEID"];
			else			
                        	$update_statement = "UPDATE ".$db_name.".".$table_name." SET ".$column_name." = \"".$newUpdateVal."\" WHERE ".$column_name." = \"".$row[$column_name]."\" AND ".$primary_key." = ".$row[$primary_key];
//			echo $update_statement."<br />";
			$mysqlObjM->executeQuery($update_statement,$dbM) or $mysqlObjM->logError($update_statement);
		}
	}
}

//caste-mtongue,caste-mtongue,caste-mtongue
function comma_separated_type2_update($old_value,$new_value,$table_name,$column_name,$db_name,$primary_key,$mysqlObjM,$mysqlObjS,$dbM,$dbS)
{
	foreach($old_value as $k=>$v)
        {
		$select_statement = "SELECT ".$primary_key.",".$column_name." FROM ".$db_name.".".$table_name." WHERE ".$column_name." REGEXP (\"^".$v."-|,".$v."-\") = 1";
//		echo $select_statement."<br />";
                $result = $mysqlObjS->executeQuery($select_statement,$dbS) or $mysqlObjS->logError($select_statement);
                while($row = $mysqlObjS->fetchArray($result))
                {
			$data = explode(",",$row[$column_name]);
			foreach ($data as $kk=>$vv)
			{
				$mapArr = explode("-",$vv);
				if (trim($mapArr[0]) == $v)
				{
					$mapArr[0] = $new_value[$k];
					$data[$kk] = implode("-",$mapArr);
				}
			}
			$newUpdateVal = implode(",",array_unique($data));
			$update_statement = "UPDATE ".$db_name.".".$table_name." SET ".$column_name." = \"".$newUpdateVal."\" WHERE ".$column_name." = \"".$row[$column_name]."\" AND ".$primary_key." = ".$row[$primary_key];
//                      echo $update_statement."<br />";
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

//caste-mtongue+caste-mtongue+caste-mtongue
function plus_separated_update($old_value,$new_value,$table_name,$column_name,$db_name,$primary_key,$mysqlObjM,$mysqlObjS,$dbM,$dbS)
{
	foreach($old_value as $k=>$v)
        {
		$select_statement = "SELECT ".$primary_key.",".$column_name." FROM ".$db_name.".".$table_name." WHERE ".$column_name." REGEXP (\"^".$v."-|\\\\+".$v."-\") = 1";
//		echo $select_statement."<br />";
                $result = $mysqlObjS->executeQuery($select_statement,$dbS) or $mysqlObjS->logError($select_statement);
                while($row = $mysqlObjS->fetchArray($result))
                {
			$data = explode("+",$row[$column_name]);
			foreach ($data as $kk=>$vv)
			{
				$mapArr = explode("-",$vv);
				if (trim($mapArr[0]) == $v)
				{
					$mapArr[0] = $new_value[$k];
					$data[$kk] = implode("-",$mapArr);
				}
			}
			$newUpdateVal = implode("+",array_unique($data));
			$update_statement = "UPDATE ".$db_name.".".$table_name." SET ".$column_name." = \"".$newUpdateVal."\" WHERE ".$column_name." = \"".$row[$column_name]."\" AND ".$primary_key." = ".$row[$primary_key];
//                      echo $update_statement."<br />";
                        $mysqlObjM->executeQuery($update_statement,$dbM) or $mysqlObjM->logError($update_statement);
		}
	}
}

//|caste#percentile|caste#percentile|caste#percentile|
function pipe_separated_update($old_value,$new_value,$table_name,$column_name,$db_name,$primary_key,$mysqlObjM,$mysqlObjS,$dbM,$dbS)
{
	foreach($old_value as $k=>$v)
        {
		$select_statement = "SELECT ".$primary_key.",".$column_name." FROM ".$db_name.".".$table_name." WHERE ".$column_name." REGEXP (\"\\\\|".$v."#\") = 1";
//		echo $select_statement."<br />";
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

//religion_caste,religion_caste,religion_caste
function comma_separated_type4_update($old_value,$new_value,$table_name,$column_name,$db_name,$primary_key,$mysqlObjM,$mysqlObjS,$dbM,$dbS)
{
	foreach($old_value as $k=>$v)
        {
		$select_statement = "SELECT ".$primary_key.",".$column_name." FROM ".$db_name.".".$table_name." WHERE ".$column_name." REGEXP (\"_".$v."$|_".$v.",\") = 1";
//		echo $select_statement."<br />";
                $result = $mysqlObjS->executeQuery($select_statement,$dbS) or $mysqlObjS->logError($select_statement);
                while($row = $mysqlObjS->fetchArray($result))
                {
			$data = explode(",",$row[$column_name]);
			foreach ($data as $kk=>$vv)
			{
				$mapArr = explode("_",$vv);
				if (trim($mapArr[1]) == $v)
				{
					$mapArr[1] = $new_value[$k];
					$data[$kk] = implode("_",$mapArr);
				}
			}
			$newUpdateVal = implode(",",array_unique($data));
			$update_statement = "UPDATE ".$db_name.".".$table_name." SET ".$column_name." = \"".$newUpdateVal."\" WHERE ".$column_name." = \"".$row[$column_name]."\" AND ".$primary_key." = \"".$row[$primary_key]."\"";
//                      echo $update_statement."<br />";
                        $mysqlObjM->executeQuery($update_statement,$dbM) or $mysqlObjM->logError($update_statement);
		}
	}
}

//this function is for mapping of new caste values to mtongues
function update_caste_mtongue_mapping($old_value,$new_value,$table_name,$column_name,$db_name,$primary_key,$mysqlObjM,$mysqlObjS,$dbM,$dbS)
{
	foreach($old_value as $k=>$v)
        {
                $select_statement = "SELECT ".$primary_key.",".$column_name." FROM ".$db_name.".".$table_name." WHERE ".$column_name." REGEXP (\"^".$v.",|,".$v.",|,".$v."$\") = 1";
//              echo $select_statement."<br />";
                $result = $mysqlObjS->executeQuery($select_statement,$dbS) or $mysqlObjS->logError($select_statement);
                while($row = $mysqlObjS->fetchArray($result))
                {
			$newUpdateVal = $row[$column_name].",".$new_value[$k];
                        $update_statement = "UPDATE ".$db_name.".".$table_name." SET ".$column_name." = \"".$newUpdateVal."\" WHERE ".$column_name." = \"".$row[$column_name]."\" AND ".$primary_key." = ".$row[$primary_key];
//                      echo $update_statement."<br />";
                        $mysqlObjM->executeQuery($update_statement,$dbM) or $mysqlObjM->logError($update_statement);
                }
        }

	$newCasteRemainArr = array(407,408,409,414,424,434,441,443,467,467,468,469);
	$newCasteRemainMTongue = array(3,16,31,12,3,25,31,3,17,31,17,31);

	foreach ($newCasteRemainArr as $k=>$v)
	{
		$select_statement = "SELECT ".$primary_key.",".$column_name." FROM ".$db_name.".".$table_name." WHERE MTONGUE = ".$newCasteRemainMTongue[$k];
		$result = $mysqlObjS->executeQuery($select_statement,$dbS) or $mysqlObjS->logError($select_statement);
		while($row = $mysqlObjS->fetchArray($result))
		{
			$newUpdateVal = $row[$column_name].",".$v;
			$update_statement = "UPDATE ".$db_name.".".$table_name." SET ".$column_name." = \"".$newUpdateVal."\" WHERE ".$column_name." = \"".$row[$column_name]."\" AND ".$primary_key." = ".$row[$primary_key];
//		echo $update_statement."<br />";
            	$mysqlObjM->executeQuery($update_statement,$dbM) or $mysqlObjM->logError($update_statement);
		}
	}
}

//scoring_new|caste|caste
function scoring_update($old_value,$new_value,$table_name,$column_name,$db_name,$mysqlObjM,$mysqlObjS,$dbM,$dbS)
{
	$delete_statement = "DELETE FROM ".$db_name.".".$table_name." WHERE ".$column_name." IN (".implode(",",$old_value).")";
	$mysqlObjM->executeQuery($delete_statement,$dbM) or $mysqlObjM->logError($delete_statement);
	
	$select_statement = "SELECT VALUE FROM newjs.CASTE WHERE VALUE>406";
	$result = $mysqlObjS->executeQuery($select_statement,$dbS) or $mysqlObjS->logError($select_statement);
	while($row = $mysqlObjS->fetchArray($result))
       	{
		$insert_statement = "INSERT INTO ".$db_name.".".$table_name."(caste,profile_type,bias) VALUES (\"".$row["VALUE"]."\",\"F\",0),(\"".$row["VALUE"]."\",\"R\",0)";
		$mysqlObjM->executeQuery($insert_statement,$dbM) or $mysqlObjM->logError($insert_statement);
	}
}

//trends update
function trends_update($old_value,$new_value,$table_name,$column_name,$db_name,$primary_key,$mysqlObjM,$mysqlObjS,$dbM,$dbS)
{
	foreach($old_value as $k=>$v)
        {
		$select_statement = "SELECT ".$primary_key.",".$column_name." FROM ".$db_name.".".$table_name." WHERE ".$primary_key." = ".$v;
		$result = $mysqlObjS->executeQuery($select_statement,$dbS) or $mysqlObjS->logError($select_statement);
		$row = $mysqlObjS->fetchArray($result);
		if(trim($row[$column_name]))
		{
			$update_statement = "UPDATE ".$db_name.".".$table_name." SET ".$column_name." = ".$column_name." + ".$row[$column_name]." WHERE ".$primary_key." = ".$new_value[$k];
			$mysqlObjM->executeQuery($update_statement,$dbM) or $mysqlObjM->logError($update_statement);
		}
	}
	$delete_statement = "DELETE FROM ".$db_name.".".$table_name." WHERE ".$primary_key." IN (".implode(",",$old_value).")";
	$mysqlObjM->executeQuery($delete_statement,$dbM) or $mysqlObjM->logError($delete_statement);

	$select_statement = "SELECT VALUE FROM newjs.CASTE WHERE VALUE>406";
        $result = $mysqlObjS->executeQuery($select_statement,$dbS) or $mysqlObjS->logError($select_statement);
        while($row = $mysqlObjS->fetchArray($result))
        {
                $insert_statement = "INSERT INTO ".$db_name.".".$table_name."(".$primary_key.",".$column_name.") VALUES (".$row["VALUE"].",0)";
                $mysqlObjM->executeQuery($insert_statement,$dbM) or $mysqlObjM->logError($insert_statement);
        }
}

//this function is for mapping of unmapped old caste values to mtongues
function update_caste_mtongue_mapping1($old_value,$new_value,$table_name,$column_name,$db_name,$primary_key,$mysqlObjM,$mysqlObjS,$dbM,$dbS)
{
	foreach($old_value as $k=>$v)
        {
		$select_statement = "SELECT ".$primary_key.",".$column_name." FROM ".$db_name.".".$table_name." WHERE ".$primary_key." REGEXP (\"^".$v.",|,".$v.",|,".$v."$|^".$v."$\") = 1 ";
              //echo $select_statement."<br />";
                $result = $mysqlObjS->executeQuery($select_statement,$dbS) or $mysqlObjS->logError($select_statement);
		if($mysqlObjS->numRows($result))
		{
                	while($row = $mysqlObjS->fetchArray($result))
                	{
				if($row[$column_name])
					$newUpdateVal = $row[$column_name].",".$new_value[$k];
				else
					$newUpdateVal = $new_value[$k];
				$update_statement = "UPDATE ".$db_name.".".$table_name." SET ".$column_name." = \"".$newUpdateVal."\" WHERE ".$column_name." = \"".$row[$column_name]."\" AND ".$primary_key." = \"".$row[$primary_key]."\"";
             			//echo $update_statement."<br />";
				$mysqlObjM->executeQuery($update_statement,$dbM) or $mysqlObjM->logError($update_statement);
			}
		}
		else
		{
			$insert_statement = "INSERT INTO ".$db_name.".".$table_name."(ID,MTONGUE,CASTE,HINDU_CASTE) VALUES (\"\",\"".$v."\",\"".$new_value[$k]."\",\"\")";
             		//echo $insert_statement."<br />";
			$mysqlObjM->executeQuery($insert_statement,$dbM) or $mysqlObjM->logError($insert_statement);
		}
	}
}

//COMMUNITY PAGES
function community_pages_update1($old_value,$new_value,$table_name,$column_name,$db_name,$primary_key,$mysqlObjM,$mysqlObjS,$dbM,$dbS)
{
        foreach($old_value as $k=>$v)
        {
                $select_statement = "SELECT ".$primary_key.",".$column_name." FROM ".$db_name.".".$table_name." WHERE ".$column_name." REGEXP (\"^".$v.",|,".$v.",|,".$v."$|^".$v."$\") = 1 AND TYPE = \"CASTE\"";
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
                        $update_statement = "UPDATE ".$db_name.".".$table_name." SET ".$column_name." = \"".$newUpdateVal."\" WHERE ".$column_name." = \"".$row[$column_name]."\" AND ".$primary_key." = ".$row[$primary_key]." AND TYPE = \"CASTE\"";
//                      echo $update_statement."<br />";
                        $mysqlObjM->executeQuery($update_statement,$dbM) or $mysqlObjM->logError($update_statement);
                }
        }
}

function community_pages_update2($old_value,$new_value,$table_name,$column_name,$db_name,$primary_key,$mysqlObjM,$mysqlObjS,$dbM,$dbS)
{
        foreach($old_value as $k=>$v)
        {
                $select_statement = "SELECT ".$primary_key.",".$column_name." FROM ".$db_name.".".$table_name." WHERE ".$column_name." REGEXP (\"^".$v.",|,".$v.",|,".$v."$|^".$v."$\") = 1 AND PARENT_TYPE = \"CASTE\"";
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
                        $update_statement = "UPDATE ".$db_name.".".$table_name." SET ".$column_name." = \"".$newUpdateVal."\" WHERE ".$column_name." = \"".$row[$column_name]."\" AND ".$primary_key." = ".$row[$primary_key]." AND PARENT_TYPE = \"CASTE\"";
//                      echo $update_statement."<br />";
                        $mysqlObjM->executeQuery($update_statement,$dbM) or $mysqlObjM->logError($update_statement);
                }
        }
}

function community_pages_update3($old_value,$new_value,$table_name,$column_name,$db_name,$primary_key,$mysqlObjM,$mysqlObjS,$dbM,$dbS)
{
        foreach($old_value as $k=>$v)
        {
                $select_statement = "SELECT ".$primary_key.",".$column_name." FROM ".$db_name.".".$table_name." WHERE ".$column_name." REGEXP (\"^".$v.",|,".$v.",|,".$v."$|^".$v."$\") = 1 AND MAPPED_TYPE = \"CASTE\"";
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
                        $update_statement = "UPDATE ".$db_name.".".$table_name." SET ".$column_name." = \"".$newUpdateVal."\" WHERE ".$column_name." = \"".$row[$column_name]."\" AND ".$primary_key." = ".$row[$primary_key]." AND MAPPED_TYPE = \"CASTE\"";
//                      echo $update_statement."<br />";
                        $mysqlObjM->executeQuery($update_statement,$dbM) or $mysqlObjM->logError($update_statement);
                }
        }
}

?>
