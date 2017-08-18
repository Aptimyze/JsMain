<?php
	include_once("connect.inc");
	include_once("/usr/local/scripts/DocRoot.php");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
	//$master=connect_db();
	$slave = connect_slave();
	$time   =       date("Y-m-d H:i:s",mktime(date("H")-5	,date("i"),date("s"),date("m"),date("d"),date("Y")));
        $verificationWays = array('OPS','KNW','IVR','SMS','OTP');
	$res = checkPhoneVerifiedWithMsg($time,$slave);print_r($res);
	
        foreach($verificationWays as $k=>$v)
        {
                if(!$res[$v])
                {
                        $cc='esha.jain@jeevansathi.com';
						$to='nitesh.s@jeevansathi.com';
                        $subject=$msg." verification might not be working";
                        $msg.='No verification is done via '.$v.' from '.$time.' till now.<br/>';
                        send_email($to,$msg,$subject,"",$cc);
                }
        }
        
        $errorStr=checkOTPChannelCountryWise($time,$slave);
       if($errorStr || $msg)
		{
			$cc='nitesh.s@jeevansathi.com,vidushi@naukri.com';
			$to='sunendra.gupta@jeevansathi.com';
                        $subject="OTP verification might not be working";
                        $msg='No verification is done in past 5 hours. <br/>Error '.$errorStr.'<br/>Warm Regards';
                        send_email($to,$msg,$subject,"",$cc);
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
        
        function checkOTPChannelCountryWise($time,$slave)
        {
			$ver = array();
			$sql = "SELECT count(*) COUNT,ISD,CHANNEL FROM MIS.OTP_LOG WHERE DATE >='".$time."' GROUP BY ISD,CHANNEL";
			$res=mysql_query($sql,$slave) or die(mysql_error($slave));
			while($row = mysql_fetch_array($res)){
				$ver[$row['CHANNEL']][$row['ISD']] = $row['COUNT'];
			}
			$errorStr="";
			if(count($ver))
			{
				$verificationChannel = array('I','A','P','MS');
				foreach($verificationChannel as $k=>$v)
				{
					if(!$ver[$v])
					{
						$errorStr.="<br/> ".$v." channel has no phone verfication from OTP\n";
					}
					else
					{
						$indianFlag=0;
						$internationalFlag=0;
						foreach($ver[$v] as $K =>$V)
						{
							if($K==91)
								$indianFlag=1;
							if($K!=91)
								$internationalFlag=1;
						}
						if(!$indianFlag || !$internationalFlag)
						{
							if(!$indianFlag)
								$errorStr.="<br/> ".$v." channel has no phone verfication from Indian number\n";
							if(!$internationalFlag)
								$errorStr.="<br/> ".$v." channel has no phone verfication from International number\n";
						}
						
					}
				}
				return  $errorStr;
			}
			else
				return "OTP NOT WORKING";
        }
?>
