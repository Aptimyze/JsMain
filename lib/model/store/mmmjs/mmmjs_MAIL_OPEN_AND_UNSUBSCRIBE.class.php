<?php
/**
* store class for total open and unsubscrive mailer links
*/
class mmmjs_MAIL_OPEN_AND_UNSUBSCRIBE extends TABLE
{
	public function  __construct($dbname="matchalerts_slave_localhost")
	{
		parent::__construct($dbname);
	}

	/**
	* Retreive function
	*/
	public function get($fields, $where, $limit, $file = '', $table = '')
	{
	
		try
		{

			if($table == '') $table = 'mmmjs.MAIL_OPEN_AND_UNSUBSCRIBE';
			$sql = "SELECT $fields FROM $table ";
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

				if($limit)
					$sql.=" limit :LIMIT";
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
                                $sql.=" limit :LIMIT";
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
