<?php
class send_saved_search_mail extends TABLE
{
       public function __construct($dbname = "") {
                parent::__construct($dbname);
        }

        //This function truncates the send_saved_search_mail table
        public function truncateSavedSearchData()
        {
        	try
                {
                        $sql="TRUNCATE TABLE search.send_saved_search_mail";
						$res = $this->db->prepare($sql);
                        $res->execute();
                }
                catch (PDOException $e)
                {
						//add mail/sms
                        throw new jsException($e);
                }
        }

        //This function uses the $receiverData array and enters the details in the send_saved_search_mail table
        public function insertReceiverData($receiverData)
        {
            try
            {
                $sql = "INSERT INTO search.send_saved_search_mail (RECEIVER,SEARCH_NAME,SEARCH_ID) VALUES";
                $COUNT = 1;
                foreach($receiverData as $key=>$value)
                {
                            $valueToInsert .= "(:KEY".$COUNT.",";
                            $bind["KEY".$COUNT]["VALUE"] = $value["PROFILEID"];
                            $bind["KEY".$COUNT]["TYPE"] = "INT";
                            $COUNT++;
                            $valueToInsert .=":KEY".$COUNT.",";
                            $bind["KEY".$COUNT]["VALUE"] = $value["SEARCH_NAME"];
                            $bind["KEY".$COUNT]["TYPE"] = "STRING";
                            $COUNT++;
                            $valueToInsert .=":KEY".$COUNT.",";
                            $bind["KEY".$COUNT]["VALUE"] = $value["ID"];
                            $bind["KEY".$COUNT]["TYPE"] = "INT";
                            $COUNT++;
                            $valueInsert .= rtrim($valueToInsert,',')."),";
                            $valueToInsert="";

                }
                $valueInsert = rtrim($valueInsert,',');
                $sql .=$valueInsert;
                $pdoStatement = $this->db->prepare($sql);
                foreach($bind as $key=>$val)
                {
                    if($val["TYPE"] == "STRING")
                        $pdoStatement->bindValue($key, $val["VALUE"], PDO::PARAM_STR);
                    else
                        $pdoStatement->bindValue($key, $val["VALUE"], PDO::PARAM_INT);
                }
                $pdoStatement->execute();
            }
            catch (PDOException $e)
            {
                    //add mail/sms
                    throw new jsException($e);
            }
        }


        public function countNotSentMails()
        {
                try{
                        $sql = "SELECT count(*) as CNT FROM search.send_saved_search_mail WHERE SENT=N";
                        $res = $this->db->prepare($sql);
                        $res->execute();
                        $row = $res->fetch(PDO::FETCH_ASSOC);
                        return $row['CNT'];
                }
                catch(PDOException $e)
                {
                   throw new jsException($e);
                }
                return $output;
        }

        //This function fetches details of receiver along with the Searchname and search id
        public function fetchReceiverDetails($totalScript="1",$currentScript="0",$limit="")
        {
            try
            {
                $result = NULL;
                $sql = "SELECT RECEIVER, SEARCH_NAME, SEARCH_ID FROM search.send_saved_search_mail WHERE RECEIVER%:TOTAL_SCRIPT=:SCRIPT AND SENT=:STATUS";
                if($limit)
                    $sql.= " limit 0,:LIMIT";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":TOTAL_SCRIPT",$totalScript,PDO::PARAM_INT);
                $prep->bindValue(":SCRIPT",$currentScript,PDO::PARAM_INT);
                $prep->bindValue(":STATUS",'N',PDO::PARAM_STR);
                if($limit)
                  $prep->bindValue(":LIMIT",$limit,PDO::PARAM_INT);
                $prep->execute();
                while($row = $prep->fetch(PDO::FETCH_ASSOC))
                {
                    $result[] = $row;
                }
            return $result;
            }
            catch (PDOException $e)
            {
                throw new jsException($e);
            }
        }

        /**
    * update
    * @param pid
    */
    public function update($sno,$flag,$searchId)
    {
        try
        {
            $sql = "UPDATE search.send_saved_search_mail SET SENT=:FLAG WHERE SNO=:SNO AND SEARCH_ID =:SEARCHID";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":SNO",$sno,PDO::PARAM_INT);
                        $prep->bindValue(":FLAG",$flag,PDO::PARAM_STR);
                        $prep->bindValue(":SEARCHID",$searchId,PDO::PARAM_INT);
                        $prep->execute();
        }
        catch (PDOException $e)
        {
            throw new jsException($e);
        }
    }

    public function updateUserMailerData($dataArr)
    {
        try
        {
            $sql .= "UPDATE search.send_saved_search_mail SET ";
            $count = 1;
            $i =1;
            $whereCond = " WHERE SEARCH_ID = :SEARCHID";
            foreach($dataArr as $searchId=>$value)
            {
                foreach($value as $user=>$userId)
                {
                    if($count<=10 && in_array($user,savedSearchMailerEnums::$userArray))
                    {
                        $sql .=$user."=:USER".$count." ,";
                    }
                    $count++;

                }
                if(array_key_exists("SENT", $value))
                {
                    $sql .="SENT =:SENT ,";
                }

            }
            $sql = rtrim($sql,",");
            $sql = $sql.$whereCond;
            $pdoStatement = $this->db->prepare($sql);
            foreach($dataArr as $searchId=>$value)
            {
                if($i=1)
                    $pdoStatement->bindValue(":SEARCHID",$searchId,PDO::PARAM_INT);
                foreach($value as $user=>$userId)
                {
                    if($i<=10 && in_array($user,savedSearchMailerEnums::$userArray))
                    {
                        $pdoStatement->bindValue(":USER".$i,$userId,PDO::PARAM_INT);
                    }
                    $i++;
                }
                if(array_key_exists("SENT", $value))
                {
                    $pdoStatement->bindValue(":SENT",$value["SENT"],PDO::PARAM_STR);
                }
            }

            $pdoStatement->execute();
        }

        catch (PDOException $e)
        {
            throw new jsException($e);
        }
        
    }

   /* This function is used to get all the profile which need to recieve savedSearchMail i.e having SENT<>Y 
    * @param fields : fields to get if different from default
    * @param totalScript : number of script which can be executed
    * @param script : current script number
    * @param limit : limit if required
    * @return result : details of mailer to be sent 
    */
    public function getMailerProfiles($fields="",$totalScript="1",$script="0",$limit="")
    {
        try 
        {
            $defaultFields ="SNO,RECEIVER,USER1,USER2,USER3,USER4,USER5,USER6,USER7,USER8,USER9,USER10,SEARCH_NAME,SEARCH_ID";

            $selectfields = $fields?$fields:$defaultFields;
            $sql = "SELECT $selectfields FROM search.send_saved_search_mail where SENT IN ('U') AND  MOD(SNO,:TOTAL_SCRIPT)=:SCRIPT";
            if($limit)
                $sql.= " limit 0,:LIMIT";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":TOTAL_SCRIPT",$totalScript,PDO::PARAM_INT);
            $prep->bindValue(":SCRIPT",$script,PDO::PARAM_INT);
            if($limit)
                  $prep->bindValue(":LIMIT",$limit,PDO::PARAM_INT);
            $prep->execute();
            
            while($row = $prep->fetch(PDO::FETCH_ASSOC))
            {
                if(!$fields)
                {
                    $fieldsArray = explode(",",$defaultFields);
                    foreach($fieldsArray as $k=>$v)
                    {
                        $result[$row["SNO"]][$v]=$row[$v];
                    }
                }
                else
                    $result[] = $row;
                unset($result[$row["SNO"]]["SNO"]);
            }
            return $result;         
        }
        catch (PDOException $e)
        {
            throw new jsException($e);
        }
    }
            
}