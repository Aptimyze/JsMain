<?php
/**
  This class will provide the browser details of the user.
*/

class BrowserCheck
{
  /**
  This function is used to get the browser details of the user.
  @return An array which contains the name and version of the user's browser.
  */
  public function IsHtml5Browser()
  {
	//In absence of nikhil, i am correcting this bug
        if(MobileCommon::isApp())
                return false;

	$u_agent = $_SERVER['HTTP_USER_AGENT'];
	//UcBrowser check
	$explode=".";
	$allowed=0;
	if(preg_match('/UCBrowser/i',$u_agent))
	{
		$allowed=9.9;
        	$pattern = '(UCBrowser\/([0-9.]*))';
	}
	else if(preg_match('/iPhone/i',$u_agent))
        {
                $allowed=7.0;
                $pattern = '(iPhone\ OS\ ([0-9_]*))';
		$explode="_";
        }
	else if(preg_match('/Chrome/i',$u_agent))
	{
		$allowed=39;
		$pattern='(Chrome\/([0-9.]*))';
	}
	else if(preg_match('/Android/i',$u_agent))
	{
		$allowed=1;
		$pattern='(Android\/([0-9.]*))';
	}
	else if(preg_match('/windows/i',$u_agent))
        {
                $allowed=55;
                $pattern='(Windows\/([0-9.]*))';
        }
	else if(preg_match('/safari/i',$u_agent))
        {
                $allowed=66;
                $pattern='(safari\/([0-9.]*))';
        }
	if(preg_match('/Opera mini/i',$u_agent))
                $allowed=44;
	else if(preg_match('/Firefox/i',$u_agent))
        {
                $allowed=33;
                $pattern='(Firefox\/([0-9.]*))';
        }
	
	if(preg_match('/iPad/i',$u_agent))
                $allowed=1;
	if($allowed)
		return true;
	return false;
	if(preg_match($pattern, $u_agent, $matches))
	{
		
	}
	if(!$matches[1])
		return false;
	else
	{
		$version=$matches[1];
		$arr=explode($explode,$matches[1]);
		if(!$arr[1])
			$arr[1]="0";
		$version=floatval($arr[0].".".$arr[1]);
		if($version>=$allowed)
			return true;
	}
	return false;
		
  }
  public function getBrowser()
  {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
                $platform = 'linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        }
        elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
        {
                $bname = 'Internet Explorer';
                $ub = "MSIE";
        }
        elseif(preg_match('/Firefox/i',$u_agent))
        {
                $bname = 'Mozilla Firefox';
                $ub = "Firefox";
        }
        elseif(preg_match('/Chrome/i',$u_agent))
        {
                $bname = 'Google Chrome';
                $ub = "Chrome";
        }
        elseif(preg_match('/Safari/i',$u_agent))
        {
                $bname = 'Apple Safari';
                $ub = "Safari";
        }
        elseif(preg_match('/Opera/i',$u_agent))
        {
                $bname = 'Opera';
                $ub = "Opera";
        }
        elseif(preg_match('/Netscape/i',$u_agent))
        {
                $bname = 'Netscape';
                $ub = "Netscape";
        }

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
                // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
                //we will have two since we are not using 'other' argument yet
                //see if version is before or after the name
                if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                        $version= $matches['version'][0];
                }
                else {
                        $version= $matches['version'][1];
                }
        }
        else {
                $version= $matches['version'][0];
        }

        // check if we have a number
        if ($version==null || $version=="") {$version="?";}

        return array(
                'userAgent' => $u_agent,
                'name'      => $bname,
                'version'   => $version,
                'platform'  => $platform,
                'pattern'    => $pattern,
                'browname'    => $ub
        );
  }

	/*
        This function is used to detect if the device is mobile or tablet and depending on the OS and browser and their verisons, it tells if photo upload is supported or not.
        @return 1 or 0
        */
        public static function checkPhotoUploadSupport()
        {
                $userAgentObj = new phpUserAgent($_SERVER['HTTP_USER_AGENT']);
                if($userAgentObj->getDeviceType()=="MOBILE")
                {
                        if($userAgentObj->getOperatingSystem()=="mac")
                        {
                                if($userAgentObj->getOperatingSystemVersion()>=6)
                                        $supported = 1;
                                else
                                        $supported = 2;
                        }
                        elseif($userAgentObj->getOperatingSystem()=="android")
                        {
                                if($userAgentObj->getOperatingSystemVersion())
                                {
                                        if($userAgentObj->getOperatingSystemVersion()>=2.2)
                                                $supported = 1;
                                        else
                                                $supported = 2;
                                }
                                else
                                {
                                        if($userAgentObj->getBrowserName()=="firefox")
                                                $supported = 1;
                                        else
                                                $supported = 2;
                                }
                        }
                        elseif($userAgentObj->getOperatingSystem()=="windows phone")
                        {
                                $supported = 2;
                        }
                        else
                        {
                                $supported = 3;
                        }
                }
                else
                        $supported = 1;
                unset($userAgentObj);
                return $supported;
        }
}
?>
