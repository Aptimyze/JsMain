<?php
/*This class is used to insert in LEAD_CONVERSION table
 * @author Nitesh Sethi
 * @created 2013-06-30
*/
class MIS_LEAD_CONVERSION extends TABLE
{
        public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }
	
        
         /** Function updateRegisterEmail added by Nitesh
        This function is used to insert the email which has been filled the registration page1 on jeevansathi.
        * @param  $paramArr,
        * @param 
        * 
        **/
	
        public function insertConvertedLead($leadid)
        {
                if(!$leadid)
                        throw new jsException("","leadid IS BLANK IN insertConvertedLead() OF MIS_LEAD_CONVERSION.class.php");

                try
                {
                        $sql = "INSERT INTO MIS.LEAD_CONVERSION (LEADID,LEAD_CONVERTED,LEAD_COMPLETED) VALUES (:leadid,'Y','N')";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":leadid", $leadid, PDO::PARAM_INT);
                        $res->execute();
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
        			  
		 /** Function updateRegisterEmail added by Nitesh
        This function is used to update the email which has been filled the registration page2 on jeevansathi.
        * @param  $paramArr,
        * @param 
        * 
        **/
	
        public function updateLead($leadid)
        {
                if(!$leadid)
                        throw new jsException("","leadid IS BLANK IN updateLead() OF MIS_LEAD_CONVERSION.class.php");

                try
                {
                        $sql = "UPDATE MIS.LEAD_CONVERSION SET LEAD_COMPLETED='Y' WHERE LEADID=:leadid";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":leadid", $leadid, PDO::PARAM_INT);
                        $res->execute();
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
        
}
?>
