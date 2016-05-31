<?php
/*
 * A table with same structure exist on test with name DISCOUNT_LOOKUP_UPLOAD
 */
class billing_DISCOUNT_LOOKUP extends TABLE {
    
    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }
    
    public function getTableData()
    {
        try{
            $sql = "SELECT SCORE_LOWER_LIMIT, SCORE_UPPER_LIMIT, GENDER, MTONGUE, SERVICE, 2_DISCOUNT, 3_DISCOUNT, 6_DISCOUNT, 12_DISCOUNT, L_DISCOUNT FROM billing.DISCOUNT_LOOKUP";
            $prep=$this->db->prepare($sql);
            $prep->execute();
            $i = 0;
            while($result = $prep->fetch(PDO::FETCH_ASSOC))
            {
                $outputArr[$i]["SCORE_LOWER_LIMIT"] = $result["SCORE_LOWER_LIMIT"];
                $outputArr[$i]["SCORE_UPPER_LIMIT"] = $result["SCORE_UPPER_LIMIT"];
                $outputArr[$i]["GENDER"] = $result["GENDER"];
                $outputArr[$i]["MTONGUE"] = $result["MTONGUE"];
                $outputArr[$i]["SERVICE"] = $result["SERVICE"];
		$outputArr[$i]["2_DISCOUNT"] = $result["2_DISCOUNT"];
                $outputArr[$i]["3_DISCOUNT"] = $result["3_DISCOUNT"];
                $outputArr[$i]["6_DISCOUNT"] = $result["6_DISCOUNT"];
                $outputArr[$i]["12_DISCOUNT"] = $result["12_DISCOUNT"];
                $outputArr[$i]["L_DISCOUNT"] = $result["L_DISCOUNT"];
                $i++;
            }
            return $outputArr;
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }
    
    public function insertData($params)
    {
        try{
            if(is_array($params) && $params){
                $sql = "INSERT INTO billing.DISCOUNT_LOOKUP (`SCORE_LOWER_LIMIT`, `SCORE_UPPER_LIMIT`, `GENDER`, `MTONGUE`, `SERVICE`,`2_DISCOUNT`, `3_DISCOUNT`, `6_DISCOUNT`, `12_DISCOUNT`, `L_DISCOUNT`) VALUES (:SCORE_LOWER_LIMIT, :SCORE_UPPER_LIMIT, :GENDER, :MTONGUE, :SERVICE, :2_DISCOUNT, :3_DISCOUNT, :6_DISCOUNT, :12_DISCOUNT, :L_DISCOUNT)";
                $res = $this->db->prepare($sql);
                $res->bindValue(":SCORE_LOWER_LIMIT", $params["SCORE_LOWER_LIMIT"], PDO::PARAM_INT);
                $res->bindValue(":SCORE_UPPER_LIMIT", $params["SCORE_UPPER_LIMIT"], PDO::PARAM_INT);
                $res->bindValue(":GENDER", $params["GENDER"], PDO::PARAM_STR);
                $res->bindValue(":MTONGUE", $params["MTONGUE"], PDO::PARAM_INT);
                $res->bindValue(":SERVICE", $params["SERVICE"], PDO::PARAM_STR);
		$res->bindValue(":2_DISCOUNT", $params["2_DISCOUNT"], PDO::PARAM_INT);
                $res->bindValue(":3_DISCOUNT", $params["3_DISCOUNT"], PDO::PARAM_INT);
                $res->bindValue(":6_DISCOUNT", $params["6_DISCOUNT"], PDO::PARAM_INT);
                $res->bindValue(":12_DISCOUNT", $params["12_DISCOUNT"], PDO::PARAM_INT);
                $res->bindValue(":L_DISCOUNT", $params["L_DISCOUNT"], PDO::PARAM_INT);
                $res->execute();
            }
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
    
    public function truncate()
    {
        try{
            $sql = "TRUNCATE TABLE billing.DISCOUNT_LOOKUP";
            $res = $this->db->prepare($sql);
            $res->execute();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
}

?>
