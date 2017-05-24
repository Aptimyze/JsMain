<?php
/*This class is used to insert in REG_LEAD table
 * @author Hemant Agrawal
 * @created 2013-06-22
*/
class NEWJS_REGISTRATION_PAGE1 extends TABLE
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
            
                try
                {
					   $id=$this->getIdByEmail($paramArr['EMAIL']);
					   if($id){
						   $sql = "REPLACE INTO newjs.REGISTRATION_PAGE1 (EMAIL,RELATION,GENDER,DTOFBIRTH,ENTRY_DT,MOD_DT,MTONGUE,SOURCE,ISD,PHONE_MOB,CONVERTED,IPADD,PASSWORD,SEC_SOURCE,REGID) VALUES (:EMAIL,:RELATION,:GENDER,:DOB,:DATE,:DATE,:MTONGUE,:SOURCE,:ISD,:MOB,:CONVERTED,:IP,:PASSWORD,:SEC_SOURCE,:ID)";
					   }
					   else{
						   $sql = "INSERT INTO newjs.REGISTRATION_PAGE1 (EMAIL,RELATION,GENDER,DTOFBIRTH,ENTRY_DT,MOD_DT,MTONGUE,SOURCE,ISD,PHONE_MOB,CONVERTED,IPADD,PASSWORD,SEC_SOURCE) VALUES (:EMAIL,:RELATION,:GENDER,:DOB,:DATE,:DATE,:MTONGUE,:SOURCE,:ISD,:MOB,:CONVERTED,:IP,:PASSWORD,:SEC_SOURCE)";
					   }
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":EMAIL", $paramArr[EMAIL], PDO::PARAM_STR);
                        $res->bindValue(":RELATION", $paramArr[RELATION], PDO::PARAM_STR);
                        $res->bindValue(":GENDER", $paramArr[GENDER], PDO::PARAM_STR);
                        $res->bindValue(":DOB", $paramArr[DTOFBIRTH], PDO::PARAM_STR);
                        $res->bindValue(":DATE", $date, PDO::PARAM_STR);
                        $res->bindValue(":MTONGUE", $paramArr[MTONGUE], PDO::PARAM_STR);
                        $res->bindValue(":SOURCE", $paramArr[SOURCE], PDO::PARAM_STR);
                        $res->bindValue(":ISD", $paramArr[ISD], PDO::PARAM_STR);
                        $res->bindValue(":MOB", $paramArr[PHONE_MOB], PDO::PARAM_STR);
                        $res->bindValue(":CONVERTED", 'N', PDO::PARAM_STR);
						$res->bindValue(":IP", CommonFunction::getIP(), PDO::PARAM_STR);
						$res->bindValue(":PASSWORD", $paramArr[PASSWORD], PDO::PARAM_STR);
						$res->bindValue(":SEC_SOURCE", $paramArr[SEC_SOURCE], PDO::PARAM_STR);
					    if($id)$res->bindValue(":ID",$id);
                        $res->execute();
						if(!$id)$id=$this->db->lastInsertId();
						return $id;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
        public function setConverted($id)
        {
				$date = date("Y-m-d H:i:s");
                try
                {
                        $sql = "UPDATE REGISTRATION_PAGE1 SET CONVERTED='Y',MOD_DT=:DATE where REGID=:ID";
                        $res = $this->db->prepare($sql);
						$res->bindValue(":ID", $id, PDO::PARAM_INT);
						$res->bindValue(":DATE", $date, PDO::PARAM_STR);
                        $res->execute();
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
        
        public function getIdByEmail($email)
        {
        		try{
                        $sql = "SELECT REGID FROM newjs.REGISTRATION_PAGE1 where EMAIL=:EMAIL";
                        $res = $this->db->prepare($sql);
						$res->bindValue(":EMAIL", $email, PDO::PARAM_STR);
                        $res->execute();
						$row = $res->fetch(PDO::FETCH_ASSOC);
						return $row['REGID'];
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
        public function getLeadInfo($id)
        {
        		try{
                        $sql = "SELECT EMAIL,RELATION,GENDER,DTOFBIRTH,MTONGUE,SOURCE,ISD,PHONE_MOB,PASSWORD,SEC_SOURCE FROM newjs.REGISTRATION_PAGE1 where REGID=:ID";
                        $res = $this->db->prepare($sql);
						$res->bindValue(":ID", $id, PDO::PARAM_INT);
                        $res->execute();
						$row = $res->fetch(PDO::FETCH_ASSOC);
						return $row;
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
}
?>
