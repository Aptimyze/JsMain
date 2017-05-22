<?php
	include_once("connect.inc");
	$SITE_URL =JsConstants::$crmUrl;
	$content ='No Data exist';
	if($from_dialer=='Y')
	{
		if(isset($_COOKIE["CRM_LOGIN"]))
	                $cid = $_COOKIE["CRM_LOGIN"];
		$name= getname($cid);
		if($name!='')
		{
			if($name==$agent_name){
                                if($campaign_name=='OB_Sales'){
                                	$phone  = validatePhoneNo($phone);
                                        if($phone){
                                                $db = connect_crmSlave();
                                                $profileid = getProfile($phone,$db);
                                        }
                                }
				if($profileid){
					$content =getCurlDataReq($profileid,$name,$cid,$SITE_URL);
				}
				echo $content;
			}
			else{
				echo "Logged in username and agent name are different.Login again...";
				$smarty->assign("username","$username");
				$smarty->assign("from_dialer","$from_dialer");
				$smarty->display("jsconnectError.tpl");
			}
		}
		else{
			$smarty->assign("username","$username");
			$smarty->assign("from_dialer","$from_dialer");
			$smarty->display("jsconnectError.tpl");
		}
	}
        function getCurlDataReq($profileid,$name,$cid,$SITE_URL)
        {
                $tuCurl = curl_init();
                curl_setopt($tuCurl, CURLOPT_URL, "$SITE_URL/operations.php/crmAllocation/agentAllocation?profileid=$profileid&name=$name&cid=$cid&dialer_check=1");
                curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1);
	    	curl_setopt($tuCurl, CURLOPT_TIMEOUT, 20);
    		curl_setopt($tuCurl, CURLOPT_CONNECTTIMEOUT, 20);
                $tuData = curl_exec($tuCurl);
                curl_close($tuCurl);
                return $tuData;
        }
        function validatePhoneNo($phone='')
	{
                $phone=preg_replace("/[a-zA-Z!(\' ')@#$+^&*-]/", "",$phone);
                $phone=substr($phone,-10);
                $phoneNumeric =is_numeric($phone);
                if(!$phoneNumeric)
                        $phone ='';
                return $phone;
        }
        function getProfile($phone,$db)
	{
        	$JsMemcacheObj =JsMemcache::getInstance();
        	$key ='sales_campaign_'.$phone;
        	$profileid = $JsMemcacheObj->get($key);
		if(!$profileid)
			$profileid =getProfileDetails($phone,$db);
		return $profileid;
        }
	function getProfileDetails($phone, $db)
	{
		$profileid ='';
                $sql = "SELECT PROFILEID FROM newjs.JPROFILE WHERE `PHONE_WITH_STD`='$phone' OR `PHONE_MOB` IN('0$phone','+91$phone','91$phone','$phone') AND ACTIVATED!='D'";
		$res = mysql_query($sql,$db) or die($sql.":".mysql_error_js());
		if($myrow = mysql_fetch_array($res)){
			$profileid =$myrow['PROFILEID'];		
	        }
		if(!$profileid){
	                $sql1 = "SELECT PROFILEID FROM newjs.JPROFILE_CONTACT WHERE `ALT_MOBILE` IN('0$phone','+91$phone','91$phone','$phone')";
			$res1 = mysql_query($sql1,$db) or die($sql1.":".mysql_error_js());
			if($myrow1 = mysql_fetch_array($res1)){
				$profileid =$myrow['PROFILEID'];
			}
	        }
		return $profileid;
	}

?>
