<?php
/*
 * This Class provide functions for profileInformation.MYJSAPP_CONFIG table
 * @author Reshu Rajput
 * @created 12 Dec 2013
*/

class MYJSAPP_CONFIG extends TABLE
{
        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }


	/* This function is used to retrieve configuration details of myjs	
	 * @param $fields Columns to query
         * @param $where additional where parameter
         * @return output according to where
	*/
	
	public function getConfig($fields="",$where=null)
	{
	   try
           {
		$fields=$fields?$fields:"*";
		$sql = "SELECT $fields FROM profileInformation.MYJSAPP_CONFIG";
                if(is_array($where))
                {
			$sql.=" WHERE ";
			$size= sizeof($where);
			$i=0;
                	foreach($where as $key=>$val)
                        {
                        	$sql.="$key='$val'";
				$i++;
				if($i<$size)
					$sql.=" AND ";
                        }
              	}
		$sql = $sql." ORDER BY SORT_ORDER";
                $res = $this->db->prepare($sql);
                $res->execute();
		while($row = $res->fetch(PDO::FETCH_ASSOC))
		{
                                $output[$row['INFO_TYPE']] = $row;
				unset($output[$row['INFO_TYPE']]['INFO_TYPE']);
		}
           } 
           catch(PDOException $e){
                throw new jsException($e);
           }
           return $output;

	}
		
}
?>
