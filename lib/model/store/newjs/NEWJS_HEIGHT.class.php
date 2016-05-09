<?php
/*
This class is used to send query to HEIGHT table in newjs database
*/
class NEWJS_HEIGHT extends TABLE
{
	public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }

	/*
        This function fetches ID,LABEL,VALUE from newjs.HEIGHT table
        @return - result set array
        */
	public function getFullTable()
	{
		try
                {
                        $sql = "SELECT SQL_CACHE ID,LABEL,VALUE FROM newjs.HEIGHT";
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

	public function getHeightLabel($height_val)
	{
        	try{

                    $sql = "SELECT LABEL from newjs.HEIGHT WHERE VALUE=:HEIGHT_VAL";

                    $prep = $this->db->prepare($sql);
                    $prep->bindValue(":HEIGHT_VAL",$height_val,PDO::PARAM_INT);
                    $prep->execute();
                    $res=$prep->fetch(PDO::FETCH_ASSOC);
                    $height = $res['LABEL'];

        	}

        	catch(Exception $e){
                	throw new jsException($e);
        	}
        	return $height;
    	}

}
?>
