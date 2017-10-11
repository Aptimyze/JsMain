<?php

/*
 * Author: Esha Jain
 * This task takes all the profiles in FTO.FTO_CURRENT_STATE and change the FTO state of the all the profiles(which has completed the FTO eligible/active period) to FTO expire
 */

class misAutologinAlertTask extends sfBaseTask
{
  protected function configure()
  {

    $this->namespace        = 'alert';
    $this->name             = 'misAutologinAlert';
    $this->briefDescription = 'alert mails to set of people on using backend autologin more than a limit';
    $this->detailedDescription = <<<EOF
      The [cronDuplication|INFO] task gets all the profiles for which duplication check needs to be done and runs that particular duplication check.
      Call it with:

      [php symfony alert:misAutologinAlert] 
EOF;
$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));
  }

  protected function execute($arguments = array(), $options = array())
  {
	if(!sfContext::hasInstance())
		sfContext::createInstance($this->configuration);
	$this->uniqueLoginLimit = 20;
	$this->profileLoginLimit = 5;
	$this->autologinTrackingObj = new MIS_AUTOLOGIN_TRACKING("newjs_slave");
	$uniqueLoginData = $this->getUniqueLoginAlertData();
	$profileLoginData = $this->getProfileLoginAlertData();
	$to='rohan.mathur@jeevansathi.com, anamika.singh@jeevansathi.com, rajeev.joshi@jeevansathi.com, rizwan@naukri.com';
	$msg='';
	$subject="autologin Alert";
	if(is_array($uniqueLoginData))
	{
		$msg.='Tries for unique logins more than '.$this->uniqueLoginLimit.'.<br/>';
		$msg.="<br/>";
		$msg.="<table>";
		foreach($uniqueLoginData as $k=>$v)
		{
			if($k==0)
			{
				$msg.="<tr>";
				foreach($v as $x=>$y)
					$msg.="<td>".$x."</td>";
				$msg.="</tr>";
			}
			$msg.="<tr>";
			foreach($v as $x=>$y)
			{
				$msg.="<td>".$y."</td>";
			}
			$msg.="</tr>";
		}
		$msg.="</table>";
		$msg.="<br/>";
		$msg.="<br/>";
	}
	if(is_array($profileLoginData))
	{
		$msg.='Tries for login on same profile more than '.$this->profileLoginLimit.'.<br/>';
		$msg.="<br/>";
		$msg.="<table>";
		foreach($profileLoginData as $k=>$v)
		{
			if($k==0)
			{
				$msg.="<tr>";
				foreach($v as $x=>$y)
					$msg.="<td>".$x."</td>";
				$msg.="<tr/>";
			}
			$msg.="<tr>";
			foreach($v as $x=>$y)
			{
				$msg.="<td>".$y."</td>";
			}
			$msg.="</tr>";
		}
		$msg.="</table>";
	}
	if($msg)
	{
		$yesterday = mktime(date("H"),date("i"),date("s"),date("m"),date("d")-1,date("Y"));
		$date=date("Y-m-d",$yesterday);
		$msg="Data for date ".$date."<br/><br/>".$msg;
		SendMail::send_email($to, $msg, $subject);
	}
}
  protected function getUniqueLoginAlertData()
  {
	$yesterday = mktime(date("H"),date("i"),date("s"),date("m"),date("d")-1,date("Y"));
	$greatorThan['TIME']=date("Y-m-d",$yesterday)." 00:00:00";
	$lessThan['TIME']=date("Y-m-d",$yesterday)." 23:59:59";
	$fields = "COUNT( DISTINCT PROFILEID ) AS COUNT, EXECUTIVE_NAME";
	$groupBy="EXECUTIVE_NAME";
	$having = "COUNT>".$this->uniqueLoginLimit;
	$uniqueLoginData = $this->autologinTrackingObj->getArray("","",$greatorThan,$fields,$lessThan,"","","","","","",$groupBy,$having);
	return $uniqueLoginData;
  }
  protected function getProfileLoginAlertData()
  {
//	$profileLoginLimit "SELECT COUNT(*) AS COUNT,EXECUTIVE_NAME,PROFILEID FROM `AUTOLOGIN_TRACKING` GROUP BY EXECUTIVE_NAME,PROFILEID HAVING COUNT>2"
	$yesterday = mktime(date("H"),date("i"),date("s"),date("m"),date("d")-1,date("Y"));
	$greatorThan['TIME']=date("Y-m-d",$yesterday)." 00:00:00";
	$lessThan['TIME']=date("Y-m-d",$yesterday)." 23:59:59";
	$fields = "COUNT(*) AS COUNT, EXECUTIVE_NAME, PROFILEID";
	$groupBy="EXECUTIVE_NAME,PROFILEID";
	$having = "COUNT>".$this->profileLoginLimit;
	$profileLoginData = $this->autologinTrackingObj->getArray("","",$greatorThan,$fields,$lessThan,"","","","","","",$groupBy,$having);
	return $profileLoginData;
  }
}
