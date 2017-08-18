<?php
/*
This class is used to send query to OCCUPATION_GROUPING table in newjs database
*/
class NEWJS_OCCUPATION_GROUPING extends TABLE
{
	public function __construct($dbname='')
  {
    parent::__construct($dbname);
  }

	/*
        This function fetches ID,LABEL,VALUE from newjs.OCCUPATION_GROUPING table
        @return - result set array
        */
        public function getFullTable()
        {
          try
          {
            $sql = "SELECT SQL_CACHE OG.LABEL AS LABEL, OG.VALUE AS VALUE from newjs.OCCUPATION_GROUPING OG ORDER BY OG.SORTBY";
            $res = $this->db->prepare($sql);
            $res->execute();
            while($row = $res->fetch(PDO::FETCH_ASSOC))
            {
              $output[] = $row;
            }
          }
          catch(PDOException $e)
          {
            throw new jsException($e);
          }
          return $output;	
        }

        public function createNewTable()
        {
          try
          {
            $sql = "CREATE TABLE newjs.OCCUPATION_GROUPING_NEW LIKE newjs.OCCUPATION_GROUPING";
            $res = $this->db->prepare($sql);
            $res->execute();
          }
          catch(PDOException $e)
          {
            throw new jsException($e);
          }
        }

        /**
     * insert into newjs_occupation_grouping
     * @param  associative array $arrayData contains key as sorting number and data
     * @return true            on success
     */
        public function insertDataToTable($arrayData)
        {
          try 
          {
            $sql = "INSERT INTO `OCCUPATION_GROUPING_NEW` (LABEL,VALUE,SORTBY) VALUES ";
            $insertString = "";
            foreach ($arrayData as $key => $value) {
              $insertString.= "('".$value['occupationGroupingValue']."',".$value['occupationGroupingNumber'].",".($key+1)."),";
            }
            $insertString = rtrim($insertString,",");
            $sql = $sql.$insertString;
            $res = $this->db->prepare($sql);
            $res->execute();
          } 
          catch(PDOException $e)
          {
            throw new jsException($e);
          }
          return true;
        }

        public function RenameTable()
        {
          try 
          {
            $sql = "RENAME TABLE newjs.OCCUPATION_GROUPING to newjs.OCCUPATION_GROUPING_BACKUP,newjs.OCCUPATION_GROUPING_NEW to newjs.OCCUPATION_GROUPING";
            $res = $this->db->prepare($sql);
            $res->execute();
          } 
          catch(PDOException $e)
          {
            throw new jsException($e);
          }
        }

        public function dropTable()
        {
         try 
         {
          $sql = "DROP TABLE newjs.OCCUPATION_GROUPING";
          $res = $this->db->prepare($sql);
          $res->execute();
        } 
        catch(PDOException $e)
        {
          throw new jsException($e);
        }       
      }
    }
    ?>