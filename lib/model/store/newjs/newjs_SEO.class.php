<?php
class newjs_SEO extends TABLE{
    public function __construct($dbname="")
    {
        parent::__construct($dbname);
        $this->tableName = "newjs.SEO";
	$this->ID_BIND_TYPE = "INT";
	$this-> FIELD_BIND_TYPE = "STR";
	$this->NAME_BIND_TYPE = "STR";
	$this->VALUE_BIND_TYPE = "STR";
        $this->DESCRIPTION_BIND_TYPE = "STR";
        $this->URL_BIND_TYPE = "STR";
        $this->SOURCE_BIND_TYPE = "STR";
        $this->PHOTO_URL_BIND_TYPE = "STR";
        $this->MAP_SS_BIND_TYPE = "STR";
        $this->URL1_BIND_TYPE = "STR";
        $this->URL2_BIND_TYPE = "STR";
    }
    public function getURL1List()
    {
        try
        {
            $sql ="SELECT URL1 FROM newjs.SEO";
            $res=$this->db->prepare($sql);
            $res->execute();
            while($result = $res->fetch(PDO::FETCH_ASSOC))
                $url1Arr[] =$result['URL1'];
                return $url1Arr;
        }
        catch(PDOException $e){
            throw new jsException($e);
        }
    }
}
?>
