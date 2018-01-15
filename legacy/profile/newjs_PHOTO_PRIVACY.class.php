<?php
class NEWJS_PHOTO_PRIVACY extends TABLE 
{
	
	/**
     * @fn __construct
     * @brief Constructor function
     * @param $dbName - Database to which the connection would be made
     */
    public function __construct($dbname = "") 
    {
        parent::__construct($dbname);
    }
    public function UpdatePrivacy($profileId,$photoDisplay)
    {
		try
		{//echo("a");die;
			$as=date("Y-m-d G:i:s");
		$sql="UPDATE newjs.JPROFILE set PHOTO_DISPLAY = :PHOTODISPLAY, MOD_DT = '$as' where newjs.JPROFILE.PROFILEID=:PROFILEID";
		$prep = $this->db->prepare($sql);
        $prep->bindValue(":PROFILEID",$profileId,PDO::PARAM_INT);
        $prep->bindValue(":PHOTODISPLAY",$photoDisplay,PDO::PARAM_STR);
        $prep->execute();
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
}
        
