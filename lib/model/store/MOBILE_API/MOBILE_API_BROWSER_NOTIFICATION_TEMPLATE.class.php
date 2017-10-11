<?php

class MOBILE_API_BROWSER_NOTIFICATION_TEMPLATE extends TABLE{
    
    public function __construct($dbName = "") {
        parent::__construct($dbName);
    }
    
    /*returns template details of all notifications(only active if $activatedOnly set to true)
    * @inputs : $activatedOnly
    * @return : $result
    */
    public function getAll($activatedOnly=true)
    {
        try{
            $sql = "SELECT * FROM MOBILE_API.BROWSER_NOTIFICATION_TEMPLATE";
            if($activatedOnly==true)
                $sql = $sql." WHERE STATUS=:STATUS";
            $prep = $this->db->prepare($sql);
            if($activatedOnly==true)
               $prep->bindValue(":STATUS", 'Y', PDO::PARAM_STR); 
            $prep->execute();
            while($row = $prep->fetch(PDO::FETCH_ASSOC)){
                $result[$row["NOTIFICATION_KEY"]][$row['ID']] = $row;
            }
            return $result;
        }catch(Exception $ex){
            throw new jsException($ex);
        }
        
    }
    
    /*returns template details of selected notification
    * @inputs : $value,$criteria,$fields,$orderby,$limit,$extraWhereClauseArr
    * @return : $result
    */
    public function getArray($value="",$criteria="NOTIFICATION_KEY",$fields="*",$orderby="",$limit="",$extraWhereClauseArr="")
    {
        if(!value){
            throw new jsException("","$criteria IS BLANK");
        }
        try{
            $sql = "SELECT $fields FROM MOBILE_API.BROWSER_NOTIFICATION_TEMPLATE WHERE $criteria = :$criteria";
            
            if($extraWhereClauseArr && is_array($extraWhereClauseArr))
            {
                foreach ($extraWhereClauseArr as $key => $v) 
                {
                    $sql= $sql." AND $key = :$key ";
                    $extraBind[$key]=$v;
                }
            }
            if($orderby){
                $sql.= " ORDER BY $orderby ";
            }
            if($limit){
                $sql.= " LIMIT $limit";
            }
            
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":$criteria", $value, PDO::PARAM_STR);
            if(is_array($extraBind))
            {
                foreach($extraBind as $key=>$val)
                {
                    $prep->bindValue(":$key", $val);
                }
            }
            $prep->execute();
            while($row = $prep->fetch(PDO::FETCH_ASSOC))
            {
                $result[$row["NOTIFICATION_KEY"]][$row['ID']] = $row;
            }
            return $result;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
}
