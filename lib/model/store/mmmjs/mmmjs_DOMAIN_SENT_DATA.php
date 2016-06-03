<?php

class mmmjs_DOMAIN_SENT_DATA extends TABLE
{
    public function __construct($dbname="matchalerts_slave_localhost")
        {
                parent::__construct($dbname);
        }

        public function domainData($mailerId)
        {

            $domainArray = array('gmail','yahoo','hotmail','rediff','%');
            $domainCount = array();
            $mailerTable = $mailerId.'mailer';

	    if(!strstr($mailerTable,'mmmjs'))
		$mailerTable = "mmmjs.".$mailerTable;
	
            foreach($domainArray as $domain)
            {

                $sql = "SELECT count(*) as CNT from $mailerTable where EMAIL LIKE ('%@$domain.%') AND SENT = 1";
                $res = $this->db->prepare($sql);
                $res->execute();
                $row = $res->fetch(PDO::FETCH_ASSOC);
                $domainCount[] = $row['CNT'];

            }

           $sql = "INSERT IGNORE INTO mmmjs.DOMAIN_SENT_DATA (DATE,MAILER_ID,GMAIL_SENT,YAHOO_SENT,HOTMAIL_SENT,REDIFF_SENT,OTHERS_SENT) VALUES(CURDATE(),$mailerId,$domainCount[0],$domainCount[1],$domainCount[2],$domainCount[3],$domainCount[4]-$domainCount[0]-$domainCount[1]-$domainCount[2]-$domainCount[3])";
            $res = $this->db->prepare($sql);
            $res->execute();

        }

}

?>

