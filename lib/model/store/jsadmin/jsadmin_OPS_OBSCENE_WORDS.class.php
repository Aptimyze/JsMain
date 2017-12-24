<?php
class jsadmin_OPS_OBSCENE_WORDS extends TABLE{
       

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
		
		
		public function getObsceneWord($order = "")
        {
			try 
			{
				$sql = "SELECT SQL_CACHE WORD FROM jsadmin.OPS_OBSCENE_WORDS";
				if ($order == "ASC")
					$sql = $sql." ORDER BY WORD ASC";
				else if ($order == "DESC")
					$sql = $sql." ORDER BY WORD DESC";
				$prep=$this->db->prepare($sql);
				$prep->execute();
				while($res = $prep->fetch(PDO::FETCH_ASSOC))
				{
					$result[]= $res['WORD'];
				}
				
				return $result; 
			}	
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
		
		public function isPresentObsceneWord($word)
		{
			try
			{
				//$trimmedWord = strtolower(addslashes(stripslashes($word)));
				$trimmedWord = strtolower(stripslashes($word));

				$sql = "SELECT COUNT(*) AS COUNT FROM jsadmin.OPS_OBSCENE_WORDS WHERE WORD = (:WORD) OR WORD = (:TRIMMED_WORD)";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":WORD",$word,PDO::PARAM_STR);
				$prep->bindValue(":TRIMMED_WORD",$trimmedWord,PDO::PARAM_STR);
				$prep->execute();
				$res = $prep->fetch(PDO::FETCH_ASSOC);
				if($res['COUNT'] > 0)
					return true;
				return false;
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
		
		public function addObsceneWord($word)
		{
			try
			{
				//$word = strtolower(addslashes(stripslashes($word)));
				$word = strtolower(stripslashes($word));

				$sql="INSERT INTO jsadmin.OPS_OBSCENE_WORDS (WORD) VALUES (:WORD)";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":WORD",$word,PDO::PARAM_STR);
				$prep->execute();
				return $this->db->lastInsertId();
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
		
		public function deleteObsceneWord($word)
		{
			/*
			 * Input - a\\a/a\'a\\\'a
			 * DB - a\\\\a/a\\\'a\\\\\\\'a
			 * DELETE FROM OBSCENE_WORDS WHERE 
			 * WORD = ('a\\\\\\\\\\\\\\\\a/a\\\\\\\\\\\\\\\'a\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\'a') 
			 * OR WORD = ('a\\\\\\\\\\\\\\\\a/a\\\\\\\\\\\\\\\'a\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\'a')
			 */
			try
			{
				//$trimmedWord = strtolower(addslashes(stripslashes($word)));
				$trimmedWord = strtolower(stripslashes($word));
				
				$sql="DELETE FROM jsadmin.OPS_OBSCENE_WORDS WHERE WORD = (:WORD) OR WORD = (:TRIMMED_WORD) ";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":WORD",$word,PDO::PARAM_STR);
				$prep->bindValue(":TRIMMED_WORD",$trimmedWord,PDO::PARAM_STR);
				$prep->execute();
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
		
		
		
}
?>
