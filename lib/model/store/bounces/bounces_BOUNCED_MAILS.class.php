<?php
class bounces_BOUNCED_MAILS extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
			$this->EMAIL = "STR";
        }

        public function checkEntry($email)
        {
                $sqlSel = "SELECT count(*) AS COUNT FROM bounces.BOUNCED_MAILS WHERE EMAIL=:EMAIL";
                $resSel = $this->db->prepare($sqlSel);
                $resSel->bindValue(":EMAIL",$email,constant('PDO::PARAM_'.$this->{'EMAIL'}));
                $resSel->execute();
		if($rowSelectDetail = $resSel->fetch(PDO::FETCH_ASSOC))
			$rowEmail =$rowSelectDetail['COUNT'];
                return $rowEmail;
        }

        public function deleteEntry($email)
        {
                try
                {
                        $sql="DELETE FROM bounces.BOUNCED_MAILS WHERE EMAIL=:EMAIL";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":EMAIL",$email, PDO::PARAM_STR);
                        $prep->execute();
                }
                catch(PDOException $e)
                {
                        /** echo the sql statement and error message **/
                        throw new jsException($e);
                }
        }

}
?>
