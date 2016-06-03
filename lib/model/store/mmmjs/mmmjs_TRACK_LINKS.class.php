<?php
/**
* store class for 
*/
class mmmjs_TRACK_LINKS extends TABLE
{
    public function  __construct($dbname="matchalerts_slave_localhost")
    {
        parent::__construct($dbname);
    }

    public function trackLink($email,$mailerId,$url)
    {

		 $sql = "UPDATE mmmjs.TRACK_LINKS SET COUNT = COUNT + 1 WHERE DATE = CURDATE() AND MAILER_ID = '".$mailerId."' AND EMAIL = '".$email."' AND LINK = '".$url."'";
         $res = $this->db->prepare($sql);
         $res->execute();

		if(!$res->rowCount()){
			$insert = "INSERT INTO mmmjs.TRACK_LINKS (DATE,EMAIL,MAILER_ID,LINK,COUNT) VALUES(CURDATE(),'".$email."','".$mailerId."','".$url."',1)";
			$res1=$this->db->prepare($insert);
            $res1->execute();
		}
    }
}

?>

