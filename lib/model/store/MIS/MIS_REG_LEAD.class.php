<?php
/*This class is used to insert in REG_LEAD table
 * @author Hemant Agrawal
 * @created 2013-06-22
*/
class MIS_REG_LEAD extends TABLE
{
        public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }
	
        /** Function insert added by Hemant
        This function is used to insert record in the file.
        * @param  $paramArr,
        * @param 
        * 
        **/
	
        public function insert($paramArr=array())
        {
				$date = date("Y-m-d H:i:s");
				$ip = CommonFunction::getIP();
            
                try
                {
                        $sql = "INSERT IGNORE INTO MIS.REG_LEAD (EMAIL,RELATION,GENDER,DTOFBIRTH,ENTRY_DT,INCOMPLETE,MTONGUE,SOURCE,ISD,MOBILE,IPADD,LEAD_CONVERSION) VALUES (:EMAIL,:RELATION,:GENDER,:DOB,:DATE,'Y',:MTONGUE,:SOURCE,:ISD,:MOB,:IP,:LEAD_CONVERSION)";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":EMAIL", $paramArr[EMAIL], PDO::PARAM_STR);
                        $res->bindValue(":RELATION", $paramArr[RELATIONSHIP], PDO::PARAM_STR);
                        $res->bindValue(":GENDER", $paramArr[GENDER], PDO::PARAM_STR);
                        $res->bindValue(":DOB", $paramArr[DTOFBIRTH], PDO::PARAM_STR);
                        $res->bindValue(":DATE", $date, PDO::PARAM_STR);
                        $res->bindValue(":MTONGUE", $paramArr[MTONGUE], PDO::PARAM_STR);
                        $res->bindValue(":SOURCE", $paramArr[SOURCE], PDO::PARAM_STR);
                        $res->bindValue(":ISD", $paramArr[ISD], PDO::PARAM_STR);
                        $res->bindValue(":MOB", $paramArr[PHONE_MOB], PDO::PARAM_STR);
			$res->bindValue(":IP", $ip, PDO::PARAM_STR);
			$res->bindValue(":LEAD_CONVERSION", $paramArr[LEAD_CONVERSION], PDO::PARAM_STR);
                        $res->execute();
                        if($res->rowCount())
                        return true;
                        else
                        return false;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
        
         /** Function insert added by Nitesh
        This function is used to update the email which has been filled the registration page1 on jeevansathi.
        * @param  $paramArr,
        * @param 
        * 
        **/
	
        public function updateRegisterEmail($columnStr,$email)
        {
                if(!$email && $columnStr)
                        throw new jsException("","email or column string IS BLANK IN updateRegisterEmail() OF MIS_REG_LEAD.class.php");

                try
                {
                        $sql = "UPDATE MIS.REG_LEAD SET ".$columnStr." WHERE EMAIL=:email";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":email", $email, PDO::PARAM_STR);
                        $res->execute();
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
        
        /** Function insert added by Nitesh
        This function is used to insert record in the file.
        * @param  $paramArr,
        * @param 
        * 
        **/
	
        public function selectValues($email)
        {
				if(!$email)
                        throw new jsException("","email string IS BLANK IN selectValues() OF MIS_REG_LEAD.class.php");
                try
                {
                        $sql = "SELECT LEAD_CONVERSION FROM MIS.REG_LEAD WHERE EMAIL=:email";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":email", $email, PDO::PARAM_STR);
                        $res->execute();
                        $result=$res->fetch(PDO::FETCH_ASSOC);
						return($result['LEAD_CONVERSION']);
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
         /** Function insert added by Nitesh
        This function is used to replace a record in the file.
        * @param  $paramArr,
        * @param 
        * 
        **/
	
        public function replaceValues($paramArr=array())
        {
				$date = date("Y-m-d H:i:s");
				$ip = CommonFunction::getIP();
            
                try
                {
                        $sql = "REPLACE INTO MIS.REG_LEAD (EMAIL,RELATION,GENDER,DTOFBIRTH,ENTRY_DT,INCOMPLETE,MTONGUE,SOURCE,ISD,MOBILE,IPADD) VALUES (:EMAIL,:RELATION,:GENDER,:DOB,:DATE,'Y',:MTONGUE,:SOURCE,:ISD,:MOB,:IP)";
                        $res = $this->db->prepare($sql);
			if($paramArr[SOURCE]=='')
				$paramArr[SOURCE]='unknown';
                        $res->bindValue(":EMAIL", $paramArr[EMAIL], PDO::PARAM_STR);
                        $res->bindValue(":RELATION", $paramArr[RELATIONSHIP], PDO::PARAM_STR);
                        $res->bindValue(":GENDER", $paramArr[GENDER], PDO::PARAM_STR);
                        $res->bindValue(":DOB", $paramArr[DTOFBIRTH], PDO::PARAM_STR);
                        $res->bindValue(":DATE", $date, PDO::PARAM_STR);
                        $res->bindValue(":MTONGUE", $paramArr[MTONGUE], PDO::PARAM_STR);
                        $res->bindValue(":SOURCE", $paramArr[SOURCE], PDO::PARAM_STR);
                        $res->bindValue(":ISD", $paramArr[ISD], PDO::PARAM_STR);
                        $res->bindValue(":MOB", $paramArr[PHONE_MOB], PDO::PARAM_STR);
			$res->bindValue(":IP", $ip, PDO::PARAM_STR);
                        $res->execute();
                        return($this->db->lastInsertId());
                        
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
	public function getLastInsertId()
	{
		return($this->db->lastInsertId());
	}
        
}
?>
