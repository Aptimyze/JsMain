<?php
/**
* store class related to mailer information lke url,name,subject,stagger....
*/
class mmmjs_MAIL_DATA_NEW extends TABLE
{
	public function __construct($dbname="matchalerts_slave_localhost")
        {
                parent::__construct($dbname);
        }

	/**
	* This function will record mailer information .....
	* @param $mail - associative array with key as column name & value as value for the respective column
	*/
        public function insertEntry($mail)
        {
		try
		{
			$sql="REPLACE INTO mmmjs.MAIL_DATA_NEW(MAILER_ID,TEMPLATE_NAME,F_EMAIL,F_NAME,SUBJECT,DATA,DUMP,BROWSERURL, STAGGER, SCHEDULE_TIME) VALUES( :mailer_id, :template_name,:from,:from_name,:subject,:data,:no,:browserUrl, :stagger, :schedule_time)";
			$res = $this->db->prepare($sql);
			$res->bindValue(":mailer_id", $mail["mailer_id"], PDO::PARAM_INT);
			$res->bindValue(":template_name", $mail["template_name"], PDO::PARAM_STR);
			$res->bindValue(":from", $mail["f_email"], PDO::PARAM_STR);
			$res->bindValue(":from_name", $mail["f_name"], PDO::PARAM_STR);
			$res->bindValue(":subject", $mail["subject"], PDO::PARAM_STR);
			$res->bindValue(":data", $mail["data"], PDO::PARAM_STR);
			$res->bindValue(":no", "N", PDO::PARAM_STR);
			$res->bindValue(":browserUrl", $mail["browserUrl"], PDO::PARAM_STR);
			$res->bindValue(":stagger", $mail["stagger"], PDO::PARAM_INT);		
			$res->bindValue(":schedule_time", $mail["schedule_time"], PDO::PARAM_STR);	
			$res->execute();
		}
		catch(PDOException $e)
          	{	
			throw new jsException($e);
          	}
	}


	/**
	* retrieve fields based upon where conditions
	* @param $whereParamArray - associative array with key(column name) & value
	* @param $fields - columns to be selected
	* @return $arr - associative array (result of the query)	
	* @throws - PDO Exception 
	*/
        public function get($whereParamArray,$fields='*')
        {
                if(!$fields)
                        $fields='*';
		$arr = NULL;
                $sql = "SELECT $fields from mmmjs.MAIL_DATA_NEW";

                if($whereParamArray["MAILER_ID"])
                        $sqlWhere[] = " MAILER_ID=:MAILER_ID ";

                if($sqlWhere)
                {
                        $sql.=" WHERE ";
                        $sql.= implode('AND',$sqlWhere);
                }
                $res = $this->db->prepare($sql);

                if($sqlWhere)
                {
                	if($whereParamArray["MAILER_ID"])
                                $res->bindValue(":MAILER_ID",$whereParamArray["MAILER_ID"],PDO::PARAM_STR);
                }
                $res->execute();
                while($row = $res->fetch(PDO::FETCH_ASSOC))
                        $arr[] = $row;
		return $arr;
        }

        /**
        * This function retrive mailer info based on mailerid(id)
        * @param id id of mailer 
        * @return array
        */
	public function retrieveEntry($id)
	{
		try
		{
			$sql = "select * from mmmjs.MAIL_DATA_NEW where  MAILER_ID = :id";
			$res=$this->db->prepare($sql);
			$res->bindValue(":id", $id, PDO::PARAM_INT);
			$res->execute();
			return $res->fetch(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e)
		{	
			throw new jsException($e);
		}
	}

	/**
	 * update fields in the table
	 * @param $wherefields - associative array with key (column name) & value
	 * @param $setfields - associative array with key (column name) & value
	 * @throws - PDO Exception 
	 */
	public function update($wherefields, $setfields)
	{
		try
		{
			if($wherefields && $setfields)
			{
				$sql = "UPDATE mmmjs.MAIL_DATA_NEW set ";
				$count = 0;
				foreach( $setfields as $key => $value)
				{
					if($count == 0)
					{
						$sql.=" $key = :$key ";
						$count++;
					}
					else
					{
						$sql.=" AND $key = :$key ";
					}
				}
				$sql.= "WHERE ";
				$count = 0;
				foreach( $wherefields as $key => $value)
				{
					if($count == 0)
					{
						$sql.=" $key IN ($value) ";
						$count++;
					}
					else
					{
						$sql.=" AND $key IN ($value) ";
					}
				}
				$res = $this->db->prepare($sql);
				foreach($setfields as $key => $value)
				{
					$res->bindValue(":$key", $value);
				}
				$res->execute();
			}
		}
		catch(PDOException $e)
		{	throw new jsException($e);
		}
	}
}
?>
