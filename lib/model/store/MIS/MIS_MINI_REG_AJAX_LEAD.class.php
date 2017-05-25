<?php
/*This class is used to insert in MINI_REG_AJAX_LEAD table
 * @author Nitesh Sethi
 * @created 2013-06-30
 * 
*/
class MIS_MINI_REG_AJAX_LEAD extends TABLE
{
        public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }
	
        
         /** Function updateRegisterEmail added by Nitesh
        This function is used to update the email which has been filled the registration page1 on jeevansathi.
        * @param  $paramArr,
        * @param 
        * 
        **/
	
        public function updateRegisterEmail($email)
        {
                if(!$email)
                        throw new jsException("","email IS BLANK IN updateRegisterEmail() OF MIS_MINI_REG_AJAX_LEAD.class.php");

                try
                {
                        $sql = "UPDATE MIS.MINI_REG_AJAX_LEAD SET CONVERTED ='Y' WHERE EMAIL=:email";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":email", $email, PDO::PARAM_STR);
                        $res->execute();
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
        
        
       /** Function insertLead added by Hemant
        This function is used to insert the email  and mobile which has been filled the mini reg on jeevansathi.
        * 
        * @param $email
        * @param $mobile
        * @param $source
        * 
        **/
	
        public function insertLead($email,$mobile,$source)
        {
                try
                {
						$date =  date("Y-m-d G:i:s");
						$ip = CommonFunction::getIP();
                        $sql = "INSERT INTO MIS.MINI_REG_AJAX_LEAD (EMAIL,MOBILE,DATE,IPADD,SOURCE) VALUES (:EMAIL,:MOBILE,:DATE,:IP,:SOURCE)";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":EMAIL", $email, PDO::PARAM_STR);
                        $res->bindValue(":MOBILE", $mobile, PDO::PARAM_STR);
                        $res->bindValue(":IP", $ip, PDO::PARAM_STR);
                        $res->bindValue(":DATE", $date, PDO::PARAM_STR);
                        $res->bindValue(":SOURCE", $source,PDO::PARAM_STR);
                        $res->execute();
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
}
?>
