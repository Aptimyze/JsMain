<?php
class archiveStore extends TABLE{
       

    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }
    
    public function archiveData($dbName, $tableName, $argsArr, $argsCondArr, $argsCondOppArr, $startDt, $maxId)
    {
        try
        {
            $year = date("Ymd", strtotime($startDt));
            $sqlLogging = "";

	    /* Not required	
            $check = "SELECT * FROM information_schema.tables WHERE table_schema = '{$dbName}' AND table_name = '{$tableName}_BACKUP_{$year}' LIMIT 1";
            $sqlLogging .= $check."\n";
            $res = $this->db->prepare($check);
            $res->execute();
            $result = $res->rowCount();

            if(!$result){
                $sql2 = "CREATE TABLE {$dbName}.{$tableName}_BACKUP_{$year} LIKE {$dbName}.{$tableName}";
                $sqlLogging .= $sql2."\n";
                $prep2 = $this->db->prepare($sql2);
                $prep2->execute();
            }*/

	    // Create TEMP table  	
            $check = "SELECT * FROM information_schema.tables WHERE table_schema = '{$dbName}' AND table_name = '{$tableName}_BACKUP_TEMP_{$year}' LIMIT 1";
            $sqlLogging .= $check."\n";
            $res = $this->db->prepare($check);
            $res->execute();
            $result2 = $res->rowCount();

            if(!$result2){
            	// Creating Temp Table to store data
	            $sql3="CREATE TABLE {$dbName}.{$tableName}_BACKUP_TEMP_{$year} LIKE {$dbName}.{$tableName}";
	            $sqlLogging .= $sql3."\n";
	            $prep3 = $this->db->prepare($sql3);
	            $prep3->execute();
	        }

	    // Get MAX(ID) from MAIN table	
	    $maxIdSql = "SELECT MAX($maxId) AS MAXID FROM {$dbName}.{$tableName}";
	    $sqlLogging .= $maxIdSql."\n";
	    $res = $this->db->prepare($maxIdSql);
            $res->execute();
            $maxIdVal = $res->fetch(PDO::FETCH_ASSOC);
            $maxIdVal = $maxIdVal['MAXID'];
	        
            // Fetching required data from MAIN table and storing in TEMP table
  	    $sqlSel = "SELECT * FROM {$dbName}.{$tableName} WHERE 1=1 ";
            if(!empty($argsArr)){
                foreach($argsArr as $key=>$val){
                    $sqlSel .= " AND {$key} {$argsCondOppArr[$key]} '{$val}'";
                }
            }
            $sqlLogging .= $sqlSel."\n";
            $prepSel = $this->db->prepare($sqlSel);
            $prepSel->execute();
            while ($result = $prepSel->fetch(PDO::FETCH_ASSOC)){
            	$sql2 = "INSERT IGNORE INTO {$dbName}.{$tableName}_BACKUP_TEMP_{$year} VALUES(";
            	if(is_array($result)){
			foreach($result as $key=>$val){
				$val =str_replace("'",'',$val);
				$val =preg_replace(array('/[^a-z0-9]/i', '/[-]+/',"'") , '-', $val);
				$result1[$key] =$val;		
			}	
            		$sql2 .= "'".implode("','", $result1)."'";
			unset($result1);	
            	} else {
            		$sql2 .= "'{$result}'";
            	}
            	$sql2 .= ")";
            	$prep2 = $this->db->prepare($sql2);
            	$prep2->execute();
            }

            // Renaming tables - MAIN table to TEMP_2
            $sqlRen1 = "RENAME TABLE {$dbName}.{$tableName} TO {$dbName}.{$tableName}_BACKUP_TEMP_{$year}_2";
            $sqlLogging .= $sqlRen1."\n";
            $prepRen1 = $this->db->prepare($sqlRen1);
            $prepRen1->execute();

	    // Renaming tables - TEMP table to MAIN 	
            $sqlRen2 = "RENAME TABLE {$dbName}.{$tableName}_BACKUP_TEMP_{$year} TO {$dbName}.{$tableName}";
            $sqlLogging .= $sqlRen2."\n";
            $prepRen2 = $this->db->prepare($sqlRen2);
            $prepRen2->execute();

	    /* Removed to avoid heavy Query computation	
            // Finally transfer temp backup data to main backup table
            $sqlIns = "INSERT INTO {$dbName}.{$tableName}_BACKUP_{$year} SELECT * FROM {$dbName}.{$tableName}_BACKUP_TEMP_{$year}_2 WHERE 1=1 ";
            if(!empty($argsArr)){
                foreach($argsArr as $key=>$val){
                    $sqlIns .= "AND {$key} {$argsCondArr[$key]} '{$val}'";
                }
            }
            $sqlLogging .= $sqlIns."\n";
            $prepIns = $this->db->prepare($sqlIns);
            $prepIns->execute();
	    */

            // Transfer any new remaining entries from temp backup to Main DB -- Provide locking of table
            /*$sqlIns = "INSERT INTO {$dbName}.{$tableName} SELECT * FROM {$dbName}.{$tableName}_BACKUP_TEMP_{$year}_2 WHERE 1=1 AND $maxId > '$maxIdVal'";
            $sqlLogging .= $sqlIns."\n";
            $prepIns = $this->db->prepare($sqlIns);
            $prepIns->execute();*/

            // Fetching extra required data and storing in main DB table
            $sqlSel = "SELECT * FROM {$dbName}.{$tableName}_BACKUP_TEMP_{$year}_2 WHERE 1=1 AND $maxId > '$maxIdVal'";
            $sqlLogging .= $sqlSel."\n";
            $prepSel = $this->db->prepare($sqlSel);
            $prepSel->execute();
            while ($result = $prepSel->fetch(PDO::FETCH_ASSOC)){
                $sql2 = "INSERT IGNORE INTO {$dbName}.{$tableName} VALUES(";
                if(is_array($result)){
                       foreach($result as $key=>$val){
                                $val =str_replace("'",'',$val);
                                $val =preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , '-', $val);
                                $result1[$key] =$val;
                        }
                        $sql2 .= "'".implode("','", $result1)."'";
			unset($result1);
                } else {
                        $sql2 .= "'{$result}'";
                }
                $sql2 .= ")";
                $prep2 = $this->db->prepare($sql2);
                $prep2->execute();
            }

            $sqlDel = "DELETE FROM {$dbName}.{$tableName}_BACKUP_TEMP_{$year}_2 WHERE 1=1 ";
            if(!empty($argsArr)){
                foreach($argsArr as $key=>$val){
                    $sqlDel .= " AND {$key} {$argsCondOppArr[$key]} '{$val}'";
                }
            }
            $sqlLogging .= $sqlDel."\n";
            $prepIns = $this->db->prepare($sqlDel);
            $prepIns->execute();

            // Added to avoid query computation - Rename table TEMP_2 to BACKUP_ 
            $sqlRen2 = "RENAME TABLE {$dbName}.{$tableName}_BACKUP_TEMP_{$year}_2 TO {$dbName}.{$tableName}_BACKUP_{$year}";
            $sqlLogging .= $sqlRen2."\n";
            $prepRen2 = $this->db->prepare($sqlRen2);
            $prepRen2->execute();
            // Added to avoid query computation Ends    

            print $sqlLogging;
        }
        catch(PDOException $e)
        {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }
}
?>
