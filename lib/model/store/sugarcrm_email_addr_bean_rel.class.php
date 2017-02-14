<?php
class sugarcrm_email_addr_bean_rel extends TABLE
{
    public function __construct($dbname="")
    {
            parent::__construct($dbname);
    }

    public function getEmailAddressID($profileid)
    {
    	try{

			$sql = "SELECT email_address_id FROM sugarcrm.email_addr_bean_rel where bean_id=:PROFILEID";
		    $prep = $this->db->prepare($sql);
		    $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
		    $prep->execute();
		    $res=$prep->fetch(PDO::FETCH_ASSOC);
            $email_address_id = $res['email_address_id'];

    	}

        catch(Exception $e){
                throw new jsException($e);
        }
        return $email_address_id;
    }
}
?>
