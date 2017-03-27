<?php
class PICTURE_NEW_BKP extends TABLE
{
	private $validSet;
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
                 $this->validSet = array_merge(ProfilePicturesTypeEnum::$PICTURE_SIZES_FIELDS, Array("TITLE","KEYWORD","PICTUREID","ORDERING","PROFILEID","PICFORMAT"));

        }

        /**
        This function is used to insert pictures info (profile pic/album) of screened table (PICTURE_NEW table).
        * @param paramArr array contains key value pair for insertion
        * @returns true on sucess
        **/
        public function ins($paramArr=array())
        {
		foreach($paramArr as $key=>$val)
		{
			if(in_array($key,$this->validSet))
			{
				$set[] = ":".$key;
				$fieldsSet[]= $key;
				${$key} = $val;
			}
		}
		$setValues = implode(",",$set);
                $fieldsSetString = implode(",",$fieldsSet);
		
                try
                {
                        $sql = "REPLACE INTO PICTURE.PICTURE_NEW_BKP ($fieldsSetString) VALUES ($setValues)";
                        $res = $this->db->prepare($sql);
                        $pdoIntSet = array("PROFILEID","PICTUREID","ORDERING");
			foreach($fieldsSet as $index=>$field)
			{
				$pdoType = in_array($field,$pdoIntSet)?PDO::PARAM_INT:PDO::PARAM_STR;
				$res->bindParam(":".$field, ${$field},$pdoType);
			}
                        
                        $res->execute();
                        return true;
                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
        }
}
?>
