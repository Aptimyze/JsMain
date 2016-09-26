<?php
class SOURCE_CATEGORY_MAPPING extends TABLE
{
  private $fields = '';
  public function __construct($dbname="")
  {
          parent::__construct($dbname);
          $this->fields = "`SOURCE_GROUP` , `SOURCE_ID`,`SOURCE_CATEGORY`";
  }

	//Three function for innodb transactions
	public function startTransaction()
	{
		$this->db->beginTransaction();
	}
	public function commitTransaction()
	{
		$this->db->commit();
	}

	public function rollbackTransaction()
	{
		$this->db->rollback();
	}
  public function getSourceCategory(){
   
    $sql = "SELECT * FROM REGISTER.SOURCE_CATEGORY_MAPPING";
    
    $prep=$this->db->prepare($sql);	
    
    if(isset($filters['start_date']))
      $prep->bindValue(":START_DATE",$filters['start_date'],PDO::PARAM_STR);
    if(isset($filters['end_date']))
      $prep->bindValue(":END_DATE",$filters['end_date'],PDO::PARAM_STR);

    $prep->execute();
    $res=$prep->fetchAll();

    return $res;
  }
}
?>
