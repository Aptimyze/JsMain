<?php
/*This class is used to insert in NEWJS_INVALID_DOMAINS table
 * @author Nitesh Sethi
 * @created 2013-08-14
*/
class NEWJS_INVALID_DOMAINS extends TABLE
{
        public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }
	
        /** Function insert added by Nitesh
        This function is used to select record from the file.
        * @param  $paramArr,
        * @param 
        * 
        **/
	
        public function selectValues($domain)
        {
				if(!$domain)
                        throw new jsException("","domain string IS BLANK IN selectValues() OF  NEWJS_INVALID_DOMAINS.class.php");
                try
                {
                        $sql = "SELECT DOMAIN FROM newjs.INVALID_DOMAINS where DOMAIN=:domain";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":domain", $domain, PDO::PARAM_STR);
                        $res->execute();
                        $result=$res->fetch(PDO::FETCH_ASSOC);
						return ($result['DOMAIN']);
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
        
}
?>
