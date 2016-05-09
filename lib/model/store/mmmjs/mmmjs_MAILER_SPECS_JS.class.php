<?php
/**
* This class with store the criteria of the formed mailer.
*/
class mmmjs_MAILER_SPECS_JS extends TABLE
{
        public function __construct($dbname="matchalerts_slave_localhost")
        {
                parent::__construct($dbname);
        }

	/**
	* insert row in the table
	* @param $mailer - associative array key(column name) & value
	* @throws - PDO Exception 
	*/
        public function insertEntry($mailer)
        {
        	try
                {
			$fields = array('MAILER_ID', 'TYPE', 'GENDER', 'CASTE', 'MANGLIK', 'MTONGUE', 'MSTATUS', 'HAVECHILD', 'MIN_AGE', 'MAX_AGE', 'MIN_HEIGHT', 'MAX_HEIGHT', 'BTYPE', 'COMPLEXION', 'DIET', 'SMOKE', 'DRINK', 'HANDICAPPED', 'OCCUPATION', 'COUNTRY_RES', 'CITY_RES', 'COUNTRY_BIRTH', 'RES_STATUS', 'EDU_LEVEL', 'RELATION', 'HAVEPHOTO', 'INCOMPLETE', 'SHOWPHONE_RES', 'SHOWPHONE_MOB', 'SUBSCRIPTION', 'LINCOME', 'HINCOME', 'LINCOME_DOL', 'HINCOME_DOL', 'ENTRY_DT1', 'ENTRY_DT2', 'MODIFY_DT1', 'MODIFY_DT2', 'LASTLOGIN_DT1', 'LASTLOGIN_DT2', 'UPPER_LIMIT');
            		$sql = "replace into mmmjs.MAILER_SPECS_JS(";
			foreach ($fields as $key => $value)
			{	
				$sql.=($value.', ');
			}
			$sql = substr($sql, 0, -2).') values (';
			foreach ($fields as $key => $value)
			{
				$sql.=(':'.strtolower($value).', ');
			}
			$sql = substr($sql, 0, -2).' )';
	                $res=$this->db->prepare($sql);
			foreach ($fields as $key => $value)
			{
				$lower = strtolower($value);
				if(is_string($mailer[$lower]))
					$res->bindValue(':'.$lower, $mailer[$lower], PDO::PARAM_STR);
				else
					$res->bindValue(':'.$lower, $mailer[$lower], PDO::PARAM_INT);
			}	
            		$res->execute();
                        //return array('id' => $mailer['mailer_id'], 'mailer_id' => $mailer['mailer_id']);
          	}
          	catch(PDOException $e)
          	{	throw new jsException($e);
          	}
        }

	/**
	* retrieve row from the table based on id
	* @param $id - primary key
	* @return row of the table
	* @throws - PDO Exception 
	*/
        public function retrieveEntry($id)
        {
        	try
                {
            		$sql = "select * from mmmjs.MAILER_SPECS_JS where  MAILER_ID = :id";
	                $res=$this->db->prepare($sql);
			$res->bindValue(":id", $id, PDO::PARAM_STR);
            		$res->execute();
			$row = $res->fetch(PDO::FETCH_ASSOC);
                        return $row;
          	}
          	catch(PDOException $e)
          	{	throw new jsException($e);
          	}
        }
}    
?>    
