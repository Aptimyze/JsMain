<?php
//This class is used to execute queries on newjs.SUBCASTE table

class NEWJS_SUBCASTE extends TABLE implements AutoSuggestor {

    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }

    public function viewRecords ($like, $limit)
    {
        $caste_id = $_REQUEST['caste'];
        try {
            $sql = "select SQL_CACHE DISTINCT(SSM.LABEL) from newjs.SUBCASTE_SPELLINGS_MAP SSM, newjs.SUBCASTE_CASTE_ID_MAP SCIM where SSM.SPELLING like :LIKE and SSM.SUBCASTE_ID = SCIM.SUBCASTE_ID and SCIM.RELATED_CASTE_ID=:CASTE_ID order by SSM.SORT_BY LIMIT :RANGE";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":LIKE", $like, PDO::PARAM_STR);
            $prep->bindValue(":RANGE", $limit, PDO::PARAM_INT);
            $prep->bindValue(":CASTE_ID", $caste_id, PDO::PARAM_INT);
            $prep->execute();
            $row_count = $prep->rowCount();
            $suggestions = array();
            while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $suggestions[] = $result[LABEL];
            }
            if (($limit - $row_count) > 0) {
                $sql2 = "select SQL_CACHE DISTINCT(SSM.LABEL) from newjs.SUBCASTE_SPELLINGS_MAP SSM where SSM.SPELLING like :LIKE order by SSM.SORT_BY LIMIT :RANGE";
                $prep1 = $this->db->prepare($sql2);
                $prep1->bindValue(":LIKE", $like, PDO::PARAM_STR);
                $prep1->bindValue(":RANGE", $limit - $row_count, PDO::PARAM_INT);
                $prep1->execute();
                while($result1 = $prep1->fetch(PDO::FETCH_ASSOC)) {
                    if (in_array($result1[LABEL], $suggestions)) {
                    
                    } else {
                        $suggestions[] = $result1[LABEL];
                    }
                }
            }
            return $suggestions;
        }
        catch (Exception $e) {
            throw new jsException($e);
        }
    }

    public function match ($subcaste,$caste)
    {
        try {
            $sql = "select SQL_CACHE DISTINCT(SSM.LABEL) from newjs.SUBCASTE_SPELLINGS_MAP SSM, newjs.SUBCASTE_CASTE_ID_MAP SCIM where SSM.SPELLING=:subcaste and SSM.SUBCASTE_ID = SCIM.SUBCASTE_ID and SCIM.RELATED_CASTE_ID=:caste";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":subcaste", $subcaste, PDO::PARAM_STR);
            $prep->bindValue(":caste", $caste, PDO::PARAM_INT);
            $prep->execute();
            $row_count = $prep->rowCount();
            $suggestions = array();
            if($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $suggestions[] = $result[LABEL];
		return $suggestions;
            }
                $sql2 = "select SQL_CACHE DISTINCT(SSM.LABEL) from newjs.SUBCASTE_SPELLINGS_MAP SSM where SSM.SPELLING=:subcaste";
                $prep1 = $this->db->prepare($sql2);
                $prep1->bindValue(":subcaste", $subcaste, PDO::PARAM_STR);
                $prep1->execute();
                while($result1 = $prep1->fetch(PDO::FETCH_ASSOC)) {
                    if (in_array($result1[LABEL], $suggestions)) {

                    } else {
                        $suggestions[] = $result1[LABEL];
			    return $suggestions;
                    }
                }
        }
        catch (Exception $e) {
            throw new jsException($e);
        }
    }
}
