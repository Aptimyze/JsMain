<?php


class chat_ofVcard extends TABLE{
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }
    
    public function getVCardDetails($username){
        try{
            $sql = "select * from nitish.ofVCard WHERE username IN ($username)";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            while($row = $prep->fetch(PDO::FETCH_ASSOC)){
                $result[$row['username']] = $row['vcard'];
            }
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
        return $result;
    }
}
