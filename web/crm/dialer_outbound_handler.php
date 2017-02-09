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
			if($name==$agent_name)
			{
				$sql_trac="insert into incentive.DIALER_CONNECTIVITY_TRACKING (PROFILEID, AGENT, PHONE_NO, CALL_TIME,PRIORITY,SCORE,CAMPAIGN_NAME) values ('$profileid','$agent_name','$PHONE_NO1',now(),'$Priority','$score','$campaign_name')";
                                $sql_trac = mysql_query_decide($sql_trac) or die($sql_trac." : ".mysql_error_js());
				if($profileid)
					$content =getCurlDataReq($profileid,$name,$cid,$SITE_URL);
				echo $content;
			}
			else
			{
				echo "Logged in username and agent name are different.Login again...";
				$smarty->assign("username","$username");
				$smarty->assign("from_dialer","$from_dialer");
				$smarty->display("jsconnectError.tpl");
			}
		}
		else
		{
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

?>
