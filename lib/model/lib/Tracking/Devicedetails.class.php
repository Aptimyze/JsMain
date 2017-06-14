<?php

	/**
	* Class for getting information about device from which logged in
	* using the 'User-Agent' from Server variable
	*/
	class Devicedetails
	{

		public function systemInfo()
		{
		    $user_agent = $_SERVER['HTTP_USER_AGENT'];
		    $os_platform    = "Unknown OS Platform";
		    $os_array       = array('/windows phone 8/i'    =>  'Windows Phone 8',
		        '/windows phone os 7/i' =>  'Windows Phone 7',
		        '/windows nt 6.3/i'     =>  'Windows 8.1',
		        '/windows nt 6.2/i'     =>  'Windows 8',
		        '/windows nt 6.1/i'     =>  'Windows 7',
		        '/windows nt 6.0/i'     =>  'Windows Vista',
		        '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
		        '/windows nt 5.1/i'     =>  'Windows XP',
		        '/windows xp/i'         =>  'Windows XP',
		        '/windows nt 5.0/i'     =>  'Windows 2000',
		        '/windows me/i'         =>  'Windows ME',
		        '/win98/i'              =>  'Windows 98',
		        '/win95/i'              =>  'Windows 95',
		        '/win16/i'              =>  'Windows 3.11',
		        '/macintosh|mac os x/i' =>  'Mac OS X',
		        '/mac_powerpc/i'        =>  'Mac OS 9',
		        '/linux/i'              =>  'Linux',
		        '/ubuntu/i'             =>  'Ubuntu',
		        '/iphone/i'             =>  'iPhone',
		        '/ipod/i'               =>  'iPod',
		        '/ipad/i'               =>  'iPad',
		        '/android/i'            =>  'Android',
		        '/blackberry/i'         =>  'BlackBerry',
		        '/webos/i'              =>  'Mobile');
		    $found = false;
		    // $addr = new RemoteAddress;
		    $device = '';
		    foreach ($os_array as $regex => $value) 
		    { 
		        if($found)
		           break;
		       else if (preg_match($regex, $user_agent)) 
		       {
			        $os_platform    =   $value;
			        $device = !preg_match('/(windows|mac|linux|ubuntu)/i',$os_platform)
			        ?'MOBILE':(preg_match('/phone/i', $os_platform)?'MOBILE':'SYSTEM');
		    	}
			}
			$device = !$device? 'SYSTEM':$device;
			return array('os'=>$os_platform,'device'=>$device);
		}

		public function getBrowser() 
		{ 
		    $useragent = $_SERVER['HTTP_USER_AGENT'];
		        // $useragent="Mozilla/5.0 (Linux; Android 6.0.1; Redmi Note 3 Build/MMB29M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.83 Mobile Safari/537.36";
		    $u_agent = $useragent; 
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
		        'pattern'    => $pattern
		    );
		}

		public function mobiledevice()
		{
			$useragent=$_SERVER['HTTP_USER_AGENT'];
			    // $useragent="Mozilla/5.0 (Linux; Android 6.0.1; Redmi Note 3 Build/MMB29M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.83 Mobile Safari/537.36";
			$x=explode('(', $useragent);
			return explode(')', $x[1])[0];
		}

		public static function deviceInfo()
		{
			$request = sfContext::getInstance()->getRequest();

			if($_SERVER["HTTP_USER_AGENT"] == "JsAndroid")
			{
				$deviceBrand = $request->getParameter('DEVICE_BRAND');
				$deviceModel = $request->getParameter('DEVICE_MODEL');
		        $deviceInfo = 'Android App on '.$deviceBrand .' - '. $deviceModel;
			}
			elseif ($_SERVER["HTTP_USER_AGENT"] == "JsApple")
			{
				$deviceInfo = 'IOS App';
			}
			else
			{
				$deviceInfo = '';

				$browserDetails = Devicedetails::getBrowser();

				$deviceInfo .= $browserDetails['name'].' version '.$browserDetails['version'];

				$sysInfo = Devicedetails::systemInfo();

				if($sysInfo["device"] == 'MOBILE')
				{
					$deviceInfo .= ' on '.Devicedetails::mobiledevice();
				}
				else
				{
					$deviceInfo .= ' on '.$browserDetails['platform'];
				}
			}

			return $deviceInfo;
		}
	}
?>