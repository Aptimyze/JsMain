<?php

abstract class StrategyClass 
{
	private $ageHeightCasteRelaxation = "A,H,C";
	private $educationOccupationCityRelaxation = "E,O,R";

	public function getAgeHeightCasteRelaxation(){return $this->ageHeightCasteRelaxation;}
	public function getEducationOccupationCityRelaxation(){return $this->educationOccupationCityRelaxation;}
	
	public abstract function doProcessing();

	public function logRecordsNewMatchesMail($resultArr,$receiverProfileid,$db,$logic,$logiclevelArr,$is_more_link_required,$relaxCriteria)
	{
		$gap=MailerConfigVariables::getNoOfDays();

                foreach($resultArr as $k=>$v)
                {
                        $logiclevelValue=$logiclevelArr[$k];//---------
                        $insertArr[]="('$receiverProfileid','$v',$gap,$logiclevelValue)";//------------
                        $valueArr[]=$v;
                }

		if($insertArr)
                {
                        $sql="INSERT IGNORE INTO new_matches_emails.MAILER (RECEIVER";
                        $n=count($insertArr);
                        for($i=1;$i<=$n;$i++)
                        {
                                $sql.=",USER$i";
                        }
                        $valueStr=implode(",",$valueArr);
                        $sql.=",LOGIC_USED,LINK_REQUIRED,RELAX_CRITERIA) VALUES ($receiverProfileid,$valueStr,$logic,'$is_more_link_required','$relaxCriteria')";
                        mysql_query($sql,$db)  or logerror1("In matchalert_mailer.php",$sql);
                        $insertStr=implode(",",$insertArr);
                        $sql_log="INSERT INTO new_matches_emails.LOG_TEMP (RECEIVER,USER,DATE,LOGICLEVEL) VALUES $insertStr"; //--------
                        mysql_query($sql_log,$db)  or logerror1("In matchalert_mailer.php",$sql_log);
                }
	}
}
?>
