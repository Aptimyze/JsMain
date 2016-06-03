<?php
	include_once("connect.inc");
	include_once("/usr/local/scripts/DocRoot.php");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
	//$master=connect_db();
	$slave = connect_slave();
	$time   =       date("Y-m-d H:i:s",mktime(date("H")-5,date("i"),date("s"),date("m"),date("d"),date("Y")));
        $verificationWays = array('OPS','KNW','IVR','SMS');
	$res = checkPhoneVerifiedWithMsg($time,$slave);print_r($res);
        foreach($verificationWays as $k=>$v)
        {
                if(!$res[$v])
                {
                        $cc='esha.jain@jeevansathi.com';
			$to='tanu.gupta@jeevansathi.com';
                        $subject=$msg." verification might not be working";
                        $msg='No verification is done via '.$v.' from '.$time.' till now.<br/><br/>Warm Regards';
                        send_email($to,$msg,$subject,"",$cc);
                }
        }
        function checkPhoneVerifiedWithMsg($time,$slave)
        {
			$ver = array();
			$sql = "SELECT count(*) COUNT,MSG FROM jsadmin.PHONE_VERIFIED_LOG WHERE ENTRY_DT >='".$time."' GROUP BY MSG";
			$res=mysql_query($sql,$slave) or die(mysql_error($slave));
			while($row = mysql_fetch_array($res)){
				$ver[$row['MSG']] = $row['COUNT'];
			}
			return $ver;
        }
?>
