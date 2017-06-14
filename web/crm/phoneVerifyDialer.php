<?php
	include_once("connect.inc");
	$SITE_URL ='https://crm.jeevansathi.com';
	if($from_dialer_phone=='Y')
	{
		if(isset($_COOKIE["CRM_LOGIN"]))
	                $cid = $_COOKIE["CRM_LOGIN"];
		$name= getname($cid);
		if($name!='')
		{
				$content =getCurlDataReq($profileid,$name,$cid,$SITE_URL);
				echo $content;
		}
		else
		{
			/*header("Location: $SITE_URL/crm/login.php?profileid=$profileid&from_dialer_phone=$from_dialer_phone");
			exit;*/
			$smarty->assign("username","$username");
			$smarty->assign("from_dialer_phone","$from_dialer_phone");
			$smarty->display("jsconnectError.tpl");
		}
	}
        function getCurlDataReq($profileid,$name,$cid,$SITE_URL)
        {
                $tuCurl = curl_init();
                curl_setopt($tuCurl, CURLOPT_URL, "$SITE_URL/jsadmin/offline_verify_user.php?userlist=$profileid&submitlist=1&name=$name&cid=$cid&dialer_check=1");
                curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1);
	    	curl_setopt($tuCurl, CURLOPT_TIMEOUT, 20);
    		curl_setopt($tuCurl, CURLOPT_CONNECTTIMEOUT, 20);
                //curl_setopt($tuCurl, CURLOPT_GET, 1);
                $tuData = curl_exec($tuCurl);
                curl_close($tuCurl);
                return $tuData;
        }

?>
