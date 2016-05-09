<?php
class sugarcrm_email_addresses extends TABLE
{
    public function __construct($dbname="")
    {
            parent::__construct($dbname);
    }

    public function getEmailAddress($email_address_id)
    {
    	try{

			$sql = "SELECT email_address FROM sugarcrm.email_addresses where id=:EMAIL_ID";
		
		    $prep = $this->db->prepare($sql);
		    $prep->bindValue(":EMAIL_ID",$email_address_id,PDO::PARAM_STR);
		    $prep->execute();
		    $res=$prep->fetch(PDO::FETCH_ASSOC);
            $email_address = $res['email_address'];

    	}

        catch(Exception $e){
                throw new jsException($e);
        }
        return $email_address;
    }
}
?>
