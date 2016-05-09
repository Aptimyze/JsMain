<?php
/**
 * store class for MAIL_OPEN_INDIVIDUAL
*/
class mmmjs_MAIL_OPEN_INDIVIDUAL extends TABLE
{
	public function  __construct($dbname="matchalerts_slave_localhost")
	{
		parent::__construct($dbname);
	}

	/**
	* This function will select info from the table.
	* @param fields
	* @param where 
	* @param limit
	*/
	public function get($fields, $where, $limit)
	{
		try
		{
			$sql = "SELECT $fields FROM mmmjs.MAIL_OPEN_INDIVIDUAL_NEW ";
			if($where)	
			{
				$sql.="WHERE ";
				$count = 0;
				foreach($where as $key => $value)
				{
					if($count == 0)
					{
						$sql.=" $key =:$key ";
						$count++;
					}
					else
					{
						$sql.=" AND $key IN :$key ";
					}
				}
			}

			if($limit)
				$sql.=" limit :LIMIT";

			$res=$this->db->prepare($sql);

			if($where)
			{
				foreach($where as $key => $value)
				{
					$res->bindValue(":$key", $value, PDO::PARAM_INT);
				}
			}
			if($limit)
				$res->bindValue(":LIMIT", $limit, PDO::PARAM_INT);
			$res->execute();

			$arr=array();

			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$arr[]=$row;
			}
			return $arr;
		}
		catch(PDOException $e)
		{	
			throw new jsException($e);
		}
	}
}
?>
