<?php
	include_once("connect.inc");
	$db = connect_db();
	if($from_dialer_inbound=='Y')
	{
		if(isset($_COOKIE["CRM_LOGIN"]))
	                $cid = $_COOKIE["CRM_LOGIN"];
		$name= getname($cid);
		$phone=preg_replace("/[a-zA-Z!(\' ')@#$+^&*-]/", "",$phone);
                if(strlen($phone)>10)
	                $phone=substr($phone,-10);

		if($name!='' && $phone!='')
		{
			if($name==$agent_name)
			{
				$sql_db = "(SELECT USERNAME,EMAIL,ENTRY_DT,SUBSCRIPTION,DATE(LAST_LOGIN_DT) LAST_LOGIN_DT FROM newjs.JPROFILE WHERE PHONE_WITH_STD='$phone') UNION (SELECT USERNAME,EMAIL,ENTRY_DT,SUBSCRIPTION,LAST_LOGIN_DT FROM newjs.JPROFILE WHERE PHONE_MOB IN ('0$phone','+91$phone','91$phone','$phone'))";
				$res_db = mysql_query($sql_db,$db) or die("no_response");
				$no_of_profiles=mysql_num_rows($res_db);
				if(!$no_of_profiles)
					include("get_history.php");
				elseif($no_of_profiles>1)
				{
					$sno=0;
					while($myrow = mysql_fetch_array($res_db))
					{
						$sno++;
						if($myrow['SUBSCRIPTION']!='')
							$paid='YES';
						else
							$paid='NO';
						$ordersusersarr[]=array("SNO"=> $sno,
									"USERNAME" => addslashes(stripslashes($myrow['USERNAME'])),
			                                                "EMAIL" => $myrow['EMAIL'],
                                                			"ENTRY_DT" => $myrow['ENTRY_DT'],
									"PAID" => $paid,
			                                                "LAST_LOGIN_DT" => $myrow['LAST_LOGIN_DT']);

					}
					$smarty->assign("ordersusersarr",$ordersusersarr);
					$smarty->assign("name",$name);
					$smarty->assign("cid",$cid);
					$smarty->display("dialer_inbound_handler.htm");
				}
				else
				{
					if($row_on = mysql_fetch_array($res_db))
                        			$uname=$row_on["USERNAME"];
					header("Location: $SITE_URL/crm/get_history.php?GetHistory=1&USERNAME=$uname&name=$name&cid=$cid");
		                        exit;
				}
			}
			else
			{
				/*echo "Logged in username and agent name are different.<a href='$SITE_URL/crm/login.php?phone=$phone&agent_name=$agent_name&campaign_name=$campaign_name&from_dialer_inbound=$from_dialer_inbound>Click here</a> to login again.";*/
				echo "Logged in username and agent name are different.Login again...";
				$smarty->assign("username","$username");
				$smarty->assign("from_dialer_inbound","$from_dialer_inbound");
				$smarty->display("jsconnectError.tpl");
			}
		}
		else
		{
			/*header("Location: $SITE_URL/crm/login.php?phone=$phone&agent_name=$agent_name&campaign_name=$campaign_name&from_dialer_inbound=$from_dialer_inbound");
			exit;*/
			$smarty->assign("username","$username");
			$smarty->assign("from_dialer_inbound","$from_dialer_inbound");
			$smarty->display("jsconnectError.tpl");
		}
	}
?>
