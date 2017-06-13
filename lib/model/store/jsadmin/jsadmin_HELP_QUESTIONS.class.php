<?php

class jsadmin_HELP_QUESTIONS extends TABLE{
    
    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }
    
    public function getAll($id="",$active=""){
        try{
            $sql = "SELECT * FROM jsadmin.HELP_QUESTIONS";
            if($active){
                $sql.=" WHERE ACTIVE = 'Y'";
            }
            $prep = $this->db->prepare($sql);
            $prep->execute();
            while($row = $prep->fetch(PDO::FETCH_ASSOC)){
                $result[$row['CATEGORY']][$row["ID"]] = $row;
                if($row["ID"] == $id){
                    $question["ID"] = $id;
                    $question["QUESTION"] = $row["QUESTION"];
                    $question["ANSWER"] = $row["ANSWER"];
                    $question["CATEGORY"] = $row["CATEGORY"];
                    $question["ACTIVE"] = $row["ACTIVE"];
                }
            }
            return array($result, $question);
        } catch (Exception $ex) {

        }
    }
    
    public function insert($question,$answer,$category,$active){
        if(!($question && $answer && $category && active)){
            throw new jsException("Some input parameter missing");
        }
        try{
            $sql = "INSERT INTO jsadmin.HELP_QUESTIONS VALUES (null, :QUESTION, :ANSWER, :CATEGORY, :ACTIVE)";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":QUESTION",$question,PDO::PARAM_STR);
            $prep->bindValue(":ANSWER",$answer,PDO::PARAM_STR);
            $prep->bindValue(":CATEGORY",$category,PDO::PARAM_STR);
            $prep->bindValue(":ACTIVE",$active,PDO::PARAM_STR);
            $prep->execute();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
    
    public function update($id, $question,$answer,$category,$active){
        if(!($question && $answer && $category && $id)){
            throw new jsException("Some input parameter missing");
        }
        try{
            $sql = "UPDATE jsadmin.HELP_QUESTIONS SET QUESTION = :QUESTION, ANSWER = :ANSWER, CATEGORY = :CATEGORY, ACTIVE = :ACTIVE where ID = :ID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":ID",$id,PDO::PARAM_STR);
            $prep->bindValue(":QUESTION",$question,PDO::PARAM_STR);
            $prep->bindValue(":ANSWER",$answer,PDO::PARAM_STR);
            $prep->bindValue(":CATEGORY",$category,PDO::PARAM_STR);
            $prep->bindValue(":ACTIVE",$active,PDO::PARAM_STR);
            $prep->execute();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
}
