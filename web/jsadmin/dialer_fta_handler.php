<?php
	include_once("connect.inc");
	if($from_dialer=='Y')
        {
                if(isset($_COOKIE["CRM_LOGIN"]))
                        $cid = $_COOKIE["CRM_LOGIN"];
                $name= getname($cid);
                if($name!='')
                {
                        if($name==$agent_name)
                        {
                                $sql_trac="insert into incentive.DIALER_CONNECTIVITY_TRACKING (PROFILEID, AGENT, PHONE_NO, CALL_TIME) values ('$profileid','$agent_name','$PHONE_NO1',now())";
                                $sql_trac = mysql_query_decide($sql_trac) or die($sql_trac." : ".mysql_error_js());

                                include("showstat.php");
                        }
                        else
                        {
                                echo "Logged in username and agent name are different.<a href='$SITE_URL/jsadmin/login.php?profileid=$profileid&agent_name=$agent_name&campaign_name=$campaign_name&from_dialer=$from_dialer'>Click here</a> to login again.";
                        }
                }
                else
                {
                        header("Location: $SITE_URL/jsadmin/login.php?profileid=$profileid&agent_name=$agent_name&campaign_name=$campaign_name&from_dialer=$from_dialer");
                        exit;
                }
        }
?>
