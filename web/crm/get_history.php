<?php

include_once("connect.inc");
include("history.php");
include_once("../mis/user_hierarchy.php");

if(authenticated($cid))
{	
	$flag=0;
	$name= getname($cid);
	if($GetHistory)
	{	
		$flag=1;
		$sql = "SELECT PROFILEID,EMAIL,SUBSCRIPTION,CITY_RES, COUNTRY_RES FROM newjs.JPROFILE WHERE USERNAME='$USERNAME'";
		$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		if($myrow=mysql_fetch_array($result))
		{
			$profileid=$myrow['PROFILEID'];
			$temp_email=explode("@",$myrow['EMAIL']);
			$email=$temp_email[0]."@xxx.com";
			$smarty->assign("USERNAME",$USERNAME);
			$smarty->assign("PROFILEID",$profileid);
			$smarty->assign("EMAIL",$email);

			$CITY_RES = $myrow['CITY_RES'];
			$SUBSCRIPTION = $myrow['SUBSCRIPTION'];
			$COUNTRY_RES = $myrow['COUNTRY_RES'];

			$privilage=getprivilage($cid);
			$priv=explode("+",$privilage);

			if(in_array("SLHD",$priv) || in_array("SLSUP",$priv) || in_array("P",$priv) || in_array("MG",$priv) || in_array("TRNG",$priv))
				$limit =0;
			else{
				$limitCount =getHistoryCount($profileid);
				if($limitCount>=5)
					$limit =$limitCount;
				else
					$limit =5;
			}

			if(isset($child) && $child == 1){
				$smarty->assign("INBOUND","Y");
				$limitCount =getHistoryCount($profileid);
				if($limitCount>=5)
					$limit =$limitCount;
				else
					$limit =5;	
			}

			if(in_array("IA",$priv))
			{
				$admin=1;
				$smarty->assign("ADMIN","Y");
			}

			if(in_array('IUI',$priv))
			{
				$smarty->assign("INBOUND","Y");
			}

			$username_str = rtrim(ltrim(user_hierarchy($name,1),"'"),"'");
			$allotarr = explode("','",$username_str);

			if($allotarr)
			{
				$allot_str=implode("','",$allotarr);

				//code added by sriram for manual inbound allotment.
				$sql_inbound = "SELECT ALLOTED_TO, STATUS FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid'";
				$res_inbound = mysql_query_decide($sql_inbound) or die("$sql_inbound".mysql_error_js());

				if($row = mysql_fetch_array($res_inbound))
				{		
					if(in_array($row['ALLOTED_TO'],$allotarr))
					{
						$smarty->assign("NOTFOUND","");
						$orig_alloted_to=$row['ALLOTED_TO'];

						$user_values=gethistory($USERNAME,$limit);
						$smarty->assign("ROW",$user_values);

						if($orig_alloted_to==$name || $child==1)
						{
							$smarty->assign("SHOW_FOLLOW","Y");
							//if($row['STATUS']=='P' && $myrow['SUBSCRIPTION']<>'')
							//	$smarty->assign("ALREADY_PAID","Y");
							//if($myrow['SUBSCRIPTION']<>'')
							//	$smarty->assign("ALREADY_PAID","Y");

	/*
							if($myrow['SUBSCRIPTION']=='')
								$smarty->assign("ALREADY_PAID","");
							else
								$smarty->assign("ALREADY_PAID","Y");
	*/
							}
							else
								$smarty->assign("SHOW_FOLLOW","N");
							$smarty->assign("orig_alloted_to",$orig_alloted_to);
						}
						else
						{	
							$smarty->assign("NOTFOUND","Y");
						}
					}
					else
					{
						$sql_city = "SELECT LEFT(PRIORITY,4) AS CITY FROM incentive.BRANCH_CITY WHERE VALUE='$CITY_RES'";
						$res_city = mysql_query_decide($sql_city) or die("$sql_city".mysql_error_js());
						$row_city = mysql_fetch_array($res_city);

						$sql_center = "SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME='$name'";
						$res_center = mysql_query_decide($sql_center) or die("$sql_center".mysql_error_js());
						$row_center = mysql_fetch_array($res_center);
						$center = strtoupper($row_center['CENTER']);
						if($center=="NOIDA")
							$smarty->assign("ncr","Y");

						$sql_calling_city = "SELECT LEFT(PRIORITY,4) AS CITY FROM incentive.BRANCH_CITY WHERE UPPER(LABEL)='$center'";
						$res_calling_city = mysql_query_decide($sql_calling_city) or die("$sql_calling_city".mysql_error_js());
						$row_calling_city = mysql_fetch_array($res_calling_city);
						
						if($center=="NOIDA")
							$smarty->assign("OUT_OF_REGION","N");
						elseif($row_city['CITY'] != $row_calling_city['CITY'])
						{
							$smarty->assign("OUT_OF_REGION","Y");
							$no_history=1;
						}
						elseif($SUBSCRIPTION <> '')
							$smarty->assign("ALREADY_PAID","Y");


						if(!$no_history)
						{
							$user_values=gethistory($USERNAME,$limit);
							$smarty->assign("ROW",$user_values);
						}
						$smarty->assign("NOT_ALLOTED","Y");
						$smarty->assign("SHOW_FOLLOW","I");
					}
				}
			}
			else
			{
				$smarty->assign("wrong_username","Y");
			}
			$smarty->assign("flag",$flag);
			$smarty->assign("cid",$cid);
			$smarty->assign("name",$name);
			$smarty->display("get_history.htm");
		}
		else
		{	
			$smarty->assign("flag",$flag);
			$smarty->assign("name",$name);
			$smarty->assign("cid",$cid);
			$smarty->display("get_history.htm");
		}
	}
	else
	{
		$msg="Your session has been timed out<br>  ";
		$msg .="<a href=\"index.php\">";
		$msg .="Login again </a>";
		$smarty->assign("MSG",$msg);
		$smarty->display("crm_msg.tpl");
	}
	?>
