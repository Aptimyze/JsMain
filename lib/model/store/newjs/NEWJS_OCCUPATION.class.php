<?php
/*
This class is used to send query to OCCUPATION table in newjs database
*/
class NEWJS_OCCUPATION extends TABLE
{
	public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }

	/*
        This function fetches ID,LABEL,VALUE,GROUP NAME from newjs.OCCUPATION table and newjs.OCCUPATION_GROUPING table
        @return - result set array
        */
	public function getFullTable()
	{
		try
                {
                        $sql = "SELECT SQL_CACHE O.ID AS ID, O.LABEL AS LABEL, O.VALUE AS VALUE, OG.LABEL AS GROUP_NAME FROM newjs.OCCUPATION O, newjs.OCCUPATION_GROUPING OG WHERE O.GROUPING = OG.VALUE ORDER BY O.SORTBY";
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

    /**
     * insert into newjs_occupation
     * @param  associative array $arrayData contains key as sorting number and data
     * @return true            on success
     */
    public function insert($arrayData)
    {
        try 
        {
            $sql = "INSERT INTO `OCCUPATION_TEST` (LABEL,VALUE,SORTBY,GROUPING) VALUES ";
            $insertString = "";
            foreach ($arrayData as $key => $value) {
                $insertString.= "('".$value['occupationValue']."',".$value['occupationNumber'].",".($key+1).",".$value['groupNumber']."),";
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
}
?>
