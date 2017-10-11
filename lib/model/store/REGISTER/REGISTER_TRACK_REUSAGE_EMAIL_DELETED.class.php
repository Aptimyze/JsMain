<?php

/*This class is used to insert in  TRACK_REUSAGE_EMAIL_DELETED table*/
class REGISTER_TRACK_REUSAGE_EMAIL_DELETED extends TABLE
{

	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}
        /*
        This function is used to insert record in the TRACK_REUSAGE_EMAIL_DELETED table for an entry 
        of that email
        @param- profile id, email that is duplicate, channel through which page has been accessed
        */
        public function insert($email,$channel,$landingPage='REGISTER')
        {
          try{
            $date=date("Y-m-d H:i:s");
            $sql1="INSERT INTO REGISTER.TRACK_REUSAGE_EMAIL_DELETED(EMAIL,CHANNEL,TIME,LANDING_PAGE) VALUES (:EMAIL,:CHANNEL,:DATE,:LANDING_PAGE)";
            $res1=$this->db->prepare($sql1);
            $res1->bindValue(":EMAIL", $email, PDO::PARAM_STR);
            $res1->bindValue(":DATE", $date, PDO::PARAM_STR);
            $res1->bindValue(":CHANNEL", $channel, PDO::PARAM_STR);
            $res1->bindValue(":LANDING_PAGE", $landingPage, PDO::PARAM_STR);
            $res1->execute();
          }
          catch(PDOException $e){
              jsException::log($e);
              return false;
          }

        }
    
}

