<?php

/**
 * Description of MOBILE_API_BROWSER_NOTIFICATION_LAYER
 * The table MOBILE_API.BROWSER_NOTIFICATION_LAYER is use to store the number of times the layer has to be shown to the user both on JSPC and JSMS
 * JIRA ID: (JSC-1368 and JSC-1417)
 * @author nitish
 */
class MOBILE_API_BROWSER_NOTIFICATION_LAYER extends TABLE{
    
    public function __construct($dbname = "") {
        parent::__construct($dbname);
        $this->ID_BIND_TYPE = "INT";
        $this->PROFILEID_BIND_TYPE = "INT";
		$this->MOBILE_COUNT_BIND_TYPE = "INT";
		$this->DESKTOP_COUNT_BIND_TYPE = "IT";
		$this->MOBILE_LAST_CLICK_BIND_TYPE = "STR";
        $this->DESKTOP_LAST_CLICK_BIND_TYPE = "STR";
        $this->MOBILE_LAYER_BIND_TYPE = "STR";
        $this->DESKTOP_LAYER_BIND_TYPE = "STR";
    }
    
    /**
     * update details in MOBILE_API_BROWSER_NOTIFICATION_LAYER
     * @param : $criteria,$value,$updateStr
     * @return : none
     */
    public function updateEntryDetails($criteria="PROFILEID",$value="",$updateArr,$extraWhereClause="",$inWhereStr="")
    {
    	if(!$value && !$inWhereStr)
            throw new jsException("value or inWhereStr IS BLANK in updateEntryDetails func of MOBILE_API_BROWSER_NOTIFICATION_LAYER class");
    	if(!$updateArr)
            throw new jsException("updateArr IS BLANK in updateEntryDetails func of MOBILE_API_BROWSER_NOTIFICATION_LAYER class");
        $updateStr="";
        foreach ($updateArr as $key1 => $val1) {
            if($key1 == $val1){
                $updateStr = $updateStr."$key1=$key1+1,";
            }
            else{
                $updateStr = $updateStr."$key1=:$key1,";
                $extraBind[$key1]=$val1;
            }
        }
        $updateStr = substr($updateStr,0,-1);
        try {    
            $sql = "UPDATE MOBILE_API.BROWSER_NOTIFICATION_LAYER set $updateStr WHERE ";
            if($inWhereStr)
                $sql = $sql."$criteria IN :$criteria";
            else
                $sql = $sql."$criteria = :$criteria";
            if(is_array($extraWhereClause))
            {
	            foreach($extraWhereClause as $key=>$val)
	            {
	                $sql.=" AND $key=:$key";
	                $extraBind[$key]=$val;
	            }
            }
       
            $res = $this->db->prepare($sql);
            if($inWhereStr)
                $res->bindValue(":$criteria", $value, PDO::PARAM_STR);
            else
                $res->bindValue(":$criteria", $value, constant('PDO::PARAM_'.$this->{$criteria.'_BIND_TYPE'}));
            if(is_array($extraBind))
            {
            	foreach($extraBind as $key=>$val)
            		$res->bindValue(":$key", $val,constant('PDO::PARAM_'.$this->{$key.'_BIND_TYPE'}));
            }
            $res->execute();
        }
        catch(PDOException $e){
                throw new jsException($e);
        }
        return NULL;
    }
    
    /*function to get notification rows with matched conditions
    * @params :$value,$criteria,$fields,$orderby,$limit,$extraWhereClauseArr,$offset
    * @return : $result
    */
    public function getArray($value="",$criteria="PROFILEID",$fields="*",$orderby="",$limit="",$extraWhereClauseArr="",$offset="")
    {
        if(!value){
            throw new jsException("","$criteria IS BLANK");
        }
        try{
            $sql = "SELECT $fields FROM MOBILE_API.BROWSER_NOTIFICATION_LAYER WHERE $criteria = :$criteria";
            
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
            if($limit && $offset)
            {
                $sql=$sql." OFFSET $offset";
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
                $result[] = $row;
            }
            return $result;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
    
    /*
     * @desc: Insert rows into table
     * @params: $paramsArr
     * @return: none
     */
    public function insert($paramsArr){
        try{
            $sql = "INSERT IGNORE INTO MOBILE_API.BROWSER_NOTIFICATION_LAYER VALUES (null,:PROFILEID,:MOBILE_COUNT,:DESKTOP_COUNT,:MOBILE_LAST_CLICK,:DESKTOP_LAST_CLICK,:MOBILE_LAYER,:DESKTOP_LAYER)";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$paramsArr["PROFILEID"],PDO::PARAM_INT);
            $prep->bindValue(":MOBILE_COUNT",$paramsArr["MOBILE_COUNT"],PDO::PARAM_INT);
            $prep->bindValue(":DESKTOP_COUNT",$paramsArr["DESKTOP_COUNT"],PDO::PARAM_INT);
            $prep->bindValue(":MOBILE_LAST_CLICK",$paramsArr["MOBILE_LAST_CLICK"],PDO::PARAM_STR);
            $prep->bindValue(":DESKTOP_LAST_CLICK",$paramsArr["DESKTOP_LAST_CLICK"],PDO::PARAM_STR);
            $prep->bindValue(":MOBILE_LAYER",$paramsArr["MOBILE_LAYER"],PDO::PARAM_STR);
            $prep->bindValue(":DESKTOP_LAYER",$paramsArr["DESKTOP_LAYER"],PDO::PARAM_STR);
            $prep->execute();
        } catch (Exception $ex) {
            throw  new jsException($ex);
        }
    }
    
    /*
     * @desc: get all data
     * @params: none
     * @return: all data of the table
     */
    public function getAll(){
        try{
            $sql = "SELECT * FROM MOBILE_API.BROWSER_NOTIFICATION_LAYER";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            while($row = $prep->fetch(PDO::FETCH_ASSOC)){
                $result[$row['PROFILEID']] = 1;
            }
            return $result;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
}
