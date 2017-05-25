<?php
/**
*       Filename        :       voucher_clients.php
*       Description     :       Script to show the Voucher details to Clients.
*       Created by      :       Tanu Gupta
*       Created on      :       22-02-2007
**/
include("connect.inc");
$db=connect_slave();
include ("../crm/display_result.inc");

$clientid=client_authenticated($cid);

if($clientid or $login)
{
	if($login)
	{
		$cid=client_login($username,$password);
		if($cid)
			$clientid=client_authenticated($cid);
		else
			unset($clientid);
	}

	if($clientid)
	{
		$PAGELEN=25;
		$LINKNO=5;
		if (!$j )
			$j = 0;
		$k=$j+1;
		$smarty->assign("cid",$cid);
		$sql_track="INSERT INTO billing.VOUCHER_CLIENTS_LOGIN_TRACK(REMOTE_ADDR,REFERER_URL,ENTRY_DT,CLIENTID,VALUE_OF_J) values ('$_SERVER[REMOTE_ADDR]/".addslashes(stripslashes($_SERVER['HTTP_X_FORWARDED_FOR']))."','".addslashes(stripslashes($_SERVER['HTTP_VIA']))."',now(),'$clientid','$j')";
		$res_track=mysql_query_decide($sql_track) or die(mysql_error_js($res_track));

		$sql2="SELECT CLIENTID,TYPE,CONTACTS_DISPLAY FROM billing.VOUCHER_CLIENTS WHERE CLIENTID='$clientid'";
		$res2=mysql_query_decide($sql2) or die(mysql_error_js($res2));
		$row2=mysql_fetch_array($res2);

		if($row2['CONTACTS_DISPLAY'])
		{
			$contacts_display=explode(",",$row2["CONTACTS_DISPLAY"]);
			if(in_array("Y",$contacts_display))
			{
				$smarty->assign("add_display","1");
				$smarty->assign("email_display","1");
				$smarty->assign("phone_display","1");
				$smarty->assign("city_display","1");
			}
			else
			{
				if(in_array("A",$contacts_display))
				$smarty->assign("add_display","1");
				if(in_array("E",$contacts_display))
				$smarty->assign("email_display","1");
				if(in_array("P",$contacts_display))
				$smarty->assign("phone_display","1");
				if(in_array("C",$contacts_display))
				$smarty->assign("city_display","1");
			}
		}

		if($row2['TYPE']=='P')//For Printed Vouchers
		{
			$smarty->assign("printed",1);
			$sql_count="SELECT COUNT(*) as cnt FROM billing.VOUCHER_OPTIN WHERE OPTIONS_AVAILABLE REGEXP '$row2[CLIENTID]'";
			$res_count=mysql_query_decide($sql_count);
			$row_count=mysql_fetch_array($res_count);
			$count1=$row_count['cnt'];
			$sql_count="SELECT COUNT(*) as cnt FROM billing.VOUCHER_SUCCESSSTORY WHERE OPTIONS_AVAILABLE REGEXP '$row2[CLIENTID]'";
			$res_count=mysql_query_decide($sql_count) or die(mysql_error_js());
			$row_count=mysql_fetch_assoc($res_count);
			$voucher_count=$row_count["cnt"]+$count1;
			if($clientid=="HS01")
			{
				$voucher_count=5000;
			}
			$smarty->assign("voucher_count",$voucher_count);
			$sql1="SELECT PROFILEID,NAME,CONTACT,CITY_RES,PINCODE,PHONE_RES,PHONE_MOB FROM billing.VOUCHER_OPTIN WHERE OPTIONS_AVAILABLE REGEXP '$row2[CLIENTID]' LIMIT $j,$PAGELEN";
			$res1=mysql_query_decide($sql1) or die(mysql_error_js($res1));
			$i=0;
			while($row1=mysql_fetch_array($res1))
			{
				$client[$i]['SNO']=$k;
				$client[$i]['NAME']=$row1['NAME'];
				$client[$i]['CONTACT']=$row1['CONTACT'];
				$client[$i]['CITY_RES']=$row1['CITY_RES'];
				$client[$i]['PINCODE']=$row1['PINCODE'];
				$client[$i]['PHONE_RES']=$row1['PHONE_RES'];
				$client[$i]['PHONE_MOB']=$row1['PHONE_MOB'];
				$sql="SELECT USERNAME,EMAIL FROM newjs.JPROFILE WHERE PROFILEID='$row1[PROFILEID]'";
				$res=mysql_query_decide($sql) or die(mysql_error_js($res));
				$row=mysql_fetch_array($res);
				$client[$i]['USERNAME']=$row['USERNAME'];
				$client[$i]['EMAIL']=$row['EMAIL'];
				if($row1["CITY_RES"])
				{
					$sql="SELECT LABEL FROM newjs.CITY_NEW WHERE VALUE='$row1[CITY_RES]'";
					$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
					$row=mysql_fetch_assoc($res);
					$client[$i]["CITY_RES"]=$row["LABEL"];
				}
				else
				$client[$i]["CITY_RES"]='';
				$i++;
				$k++;
			}
			if(($k>$count1)&&($count1-$j<$PAGELEN)&&($j<$count1))
			{
				$left=0;
				$end=$PAGELEN-($k-$j)+1;
				$sql1="SELECT PROFILEID,NAME_H,NAME_W,USERNAME_H,USERNAME_W,CONTACT,EMAIL,CITY_RES,PHONE_RES,PHONE_MOB FROM billing.VOUCHER_SUCCESSSTORY WHERE OPTIONS_AVAILABLE REGEXP '$row2[CLIENTID]' LIMIT 0,$end";
	                        $res1=mysql_query_decide($sql1) or die(mysql_error_js($res1));
                        	while($row1=mysql_fetch_array($res1))
                       		{
                               		$client[$i]['SNO']=$k;
					if($row1['NAME_H'])
	                               	$client[$i]['NAME']=$row1['NAME_H'];
					elseif($row1['NAME_W'])
					$client[$i]['NAME']=$row1['NAME_W'];
					else
					$client[$i]['NAME']='';
					if($row1['USERNAME_H'])
					$client[$i]['USERNAME']=$row1['USERNAME_H'];
					elseif($row1['USERNAME_W'])
					$client[$i]['USERNAME']=$row1['USERNAME_W'];
					else
					$client[$i]['USERNAME']='';
			                $client[$i]['CONTACT']=$row1['CONTACT'];
					$client[$i]['EMAIL']=$row1['EMAIL'];
					if($row1['CITY_RES'])
					{
        	       	                	$sqldet="SELECT LABEL FROM newjs.CITY_NEW WHERE VALUE='$row1[CITY_RES]'";
						$resdet=mysql_query_decide($sqldet) or die("$sqldet".mysql_error_js());
						$rowdet=mysql_fetch_assoc($resdet);
						$client[$i]['CITY_RES']=$rowdet['LABEL'];
						mysql_free_result($resdet);
					}
					else
					$client[$i]['CITY_RES']='';
	               	                $client[$i]['PHONE_RES']=$row1['PHONE_RES'];
        	               	        $client[$i]['PHONE_MOB']=$row1['PHONE_MOB'];
					if($row1['PROFILEID'])
					{
		                        	$sqldet="SELECT PINCODE FROM newjs.JPROFILE WHERE PROFILEID='$row1[PROFILEID]'";
	        	                        $resdet=mysql_query_decide($sqldet) or die(mysql_error_js($resdet));
        	        	                $rowdet=mysql_fetch_array($resdet);
                	                        $client[$i]['PINCODE']=$rowdet['PINCODE'];
						mysql_free_result($resdet);
                       			}
					else
					$client[$i]['PINCODE']='';	
	                                $i++;	
        	                        $k++;
					$left++;
                	        }
			}
			if($j>=$count1)
			{
				$start=$j-$count1;
                                $sql1="SELECT PROFILEID,NAME_H,NAME_W,USERNAME_H,USERNAME_W,EMAIL,CONTACT,CITY_RES,PHONE_RES,PHONE_MOB FROM billing.VOUCHER_SUCCESSSTORY WHERE OPTIONS_AVAILABLE REGEXP '$row2[CLIENTID]' LIMIT $start,$PAGELEN";
                                $res1=mysql_query_decide($sql1) or die("$sql1".mysql_error_js());
				$i=0;
                                while($row1=mysql_fetch_array($res1))
                                {
                                        $client[$i]['SNO']=$k;
                                        if($row1['NAME_H'])
					$client[$i]['NAME']=$row1['NAME_H'];
					elseif($row1['NAME_W'])
					$client[$i]['NAME']=$row1['NAME_W'];
					else
					$client[$i]['NAME']='';
					if($row1['USERNAME_H'])
					$client[$i]['USERNAME']=$row1['USERNAME_H'];
					elseif($row1['USERNAME_W'])
					$client[$i]['USERNAME']=$row1['USERNAME_W'];
					else
					$client[$i]['USERNAME']='';
                                        $client[$i]['CONTACT']=$row1['CONTACT'];
                                        $client[$i]['PHONE_RES']=$row1['PHONE_RES'];
                                        $client[$i]['PHONE_MOB']=$row1['PHONE_MOB'];
					$client[$i]['EMAIL']=$row1['EMAIL'];
					if($row1['CITY_RES'])
					{
						$sqldet="SELECT LABEL FROM newjs.CITY_NEW WHERE VALUE='$row1[CITY_RES]'";
						$resdet=mysql_query_decide($sqldet) or die("$sqldet".mysql_error_js());
						$rowdet=mysql_fetch_assoc($resdet);
						$client[$i]['CITY_RES']=$rowdet['LABEL'];
						mysql_free_result($resdet);
					}
					else
					$client[$i]['CITY_RES']='';					
					if($row1['PROFILEID'])
					{
                                        	$sqldet="SELECT PINCODE FROM newjs.JPROFILE WHERE PROFILEID='$row1[PROFILEID]'";
	                                        $resdet=mysql_query_decide($sqldet) or die("$sqldet".mysql_error_js());
        	                                $rowdet=mysql_fetch_array($resdet);
                	                        $client[$i]['PINCODE']=$rowdet['PINCODE'];
						mysql_free_result($resdet);
                        	        }
					else
					$client[$i]['PINCODE']='';
                                        $i++;
                                        $k++;
                                }

			}
			
		}
		else //For e-Vouchers
		{
			if($from_email)
			$sql="SELECT COUNT(*) AS COUNT FROM billing.VOUCHER_NUMBER WHERE CLIENTID='$row2[CLIENTID]' AND ISSUED='Y' AND SOURCE='EMAIL'";
			else
			$sql="SELECT COUNT(*) AS COUNT FROM billing.VOUCHER_NUMBER WHERE CLIENTID='$row2[CLIENTID]' AND ISSUED='Y' AND SOURCE!='EMAIL'";
			$res=mysql_query_decide($sql) or die($res);
			$row=mysql_fetch_array($res);
			$voucher_count=$row['COUNT'];
			$smarty->assign("voucher_count",$voucher_count);

			if($from_email)
			$sql1="SELECT PROFILEID,VOUCHER_NO,ISSUE_DATE FROM billing.VOUCHER_NUMBER WHERE CLIENTID='$row2[CLIENTID]' AND ISSUED='Y' AND SOURCE='EMAIL' ORDER BY ISSUE_DATE LIMIT $j,$PAGELEN";
			else
			$sql1="SELECT PROFILEID,STORYID,SOURCE,VOUCHER_NO,ISSUE_DATE FROM billing.VOUCHER_NUMBER WHERE CLIENTID='$row2[CLIENTID]' AND ISSUED='Y' AND SOURCE!='EMAIL' ORDER BY ISSUE_DATE LIMIT $j,$PAGELEN";
			$res1=mysql_query_decide($sql1) or die(mysql_error_js());
			$i=0;
			while($row1=mysql_fetch_array($res1))
			{
				if($row1["SOURCE"]=="SUCCESS")
				{
					$sql="SELECT NAME_H,NAME_W,CONTACT,CITY_RES,PHONE_MOB,PHONE_RES,EMAIL,USERNAME_H,USERNAME_W,PROFILEID FROM billing.VOUCHER_SUCCESSSTORY WHERE STORYID ='$row1[STORYID]'";
					$res=mysql_query_decide($sql) or die(mysql_error_js());
					$row=mysql_fetch_array($res);
					$client[$i]['SNO']=$k;
					$client[$i]['CONTACT']=$row['CONTACT'];
					$client[$i]['VOUCHER_NO']=$row1['VOUCHER_NO'];
					$client[$i]['ISSUE_DATE']=$row1['ISSUE_DATE'];
					$client[$i]['EMAIL']=$row['EMAIL'];
					if($row['USERNAME_H'])
					$client[$i]['USERNAME']=$row['USERNAME_H'];
					elseif($row['USERNAME_W'])
					$client[$i]['USERNAME']=$row['USERNAME_W'];
					else
					$client[$i]['USERNAME']='';
					if($row['NAME_H'])
					$client[$i]['NAME']=$row['NAME_H'];
					elseif($row['NAME_W'])
					$client[$i]['NAME']=$row['NAME_W'];
					else
					$client[$i]['NAME']='';
					if($row['CITY_RES'])
					{				
						$sqldet = "select SQL_CACHE LABEL from newjs.CITY_NEW WHERE VALUE='$row[CITY_RES]'";
						$resdet = mysql_query_decide($sqldet);
						$rowdet= mysql_fetch_array($resdet);
						$client[$i]['CITY_RES']=$rowdet['LABEL'];
						mysql_free_result($resdet);
					}
					else
					$client[$i]['CITY_RES']='';
					$client[$i]['PHONE_RES']=$row['PHONE_RES'];
					$client[$i]['PHONE_MOB']=$row['PHONE_MOB'];
					if($row['PROFILEID'])
					{
						$sqldet="SELECT PINCODE FROM newjs.JPROFILE WHERE PROFILEID='$row[PROFILEID]'";
						$resdet=mysql_query_decide($sqldet) or die("$sqldet".mysql_error_js());
						$rowdet=mysql_fetch_array($resdet);
						$client[$i]['PINCODE']=$rowdet['PINCODE'];
						mysql_free_result($resdet);
					}
					else
					$client[$i]['PINCODE']='';
				}
				else
				{
					if(!$from_email)
					{
						$sql="SELECT NAME,CONTACT,CITY_RES,PINCODE,PHONE_RES,PHONE_MOB FROM billing.VOUCHER_OPTIN WHERE PROFILEID='$row1[PROFILEID]' ORDER BY ID DESC";
						$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
						$row=mysql_fetch_assoc($result);
						$client[$i]['NAME']=$row['NAME'];
						$client[$i]['CONTACT']=$row['CONTACT'];
						$client[$i]['PINCODE']=$row['PINCODE'];
						$client[$i]['PHONE_RES']=$row['PHONE_RES'];
						$client[$i]['PHONE_MOB']=$row['PHONE_MOB'];
						$sqldet="SELECT LABEL FROM newjs.CITY_NEW WHERE VALUE='$row[CITY_RES]'";
						$resdet=mysql_query_decide($sqldet) or die("$sqldet".mysql_error_js());
						$rowdet=mysql_fetch_assoc($resdet);
						$client[$i]['CITY_RES']=$rowdet['LABEL'];
						mysql_free_result($resdet);
					}
					$client[$i]['VOUCHER_NO']=$row1['VOUCHER_NO'];
                                        $client[$i]['ISSUE_DATE']=$row1['ISSUE_DATE'];
					$client[$i]['SNO']=$k;
					$sqldet="SELECT EMAIL,USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$row1[PROFILEID]'";
					$resdet=mysql_query_decide($sqldet) or die("$sqldet".mysql_error_js());
					$rowdet=mysql_fetch_assoc($resdet);
					$client[$i]['USERNAME']=$rowdet['USERNAME'];
					$client[$i]['EMAIL']=$rowdet['EMAIL'];
				}
				$i++;
				$k++;
			}
			
			
		}
		
		$smarty->assign("client",$client);
		if( $j )
			$cPage = ($j/$PAGELEN) + 1;
		else
			$cPage = 1;
		$TOTALREC=$voucher_count;
		if(!$from_email)
		pagelink($PAGELEN,$TOTALREC,$cPage,$LINKNO,$cid,"voucher_clients.php");//For Pagination
		else
		pagelink($PAGELEN,$TOTALREC,$cPage,$LINKNO,"$cid&from_email=1","voucher_clients.php");
		if($clientid=="JOH65")
		{
			if($from_email)
			$smarty->assign("from_email_link",0);
			else
			$smarty->assign("from_email_link",1);
			$smarty->assign("toggle",1);
		}
		$smarty->assign("left",$left);
		$smarty->display("voucher_clients.htm");
	}
	else
	{
		$smarty->assign("login_error","Your User Name or Password has been wrong. Please fill the correct User Name and Password.");
		$smarty->assign("username",$username);
		$smarty->display("clients_login.htm");
	}
}
else
{
	$smarty->assign("login_error","Kindly login.");
	$smarty->assign("username",$username);
	$smarty->display("clients_login.htm");
}
function client_login($username,$password)
{
	if(trim($username) && trim($password))
	{
		$sql2="SELECT ID FROM billing.VOUCHER_CLIENTS WHERE USERNAME='$username' AND PASSWORD='$password'";
		$res2=mysql_query_decide($sql2) or die(mysql_error_js($res2));
		$row2=mysql_fetch_array($res2);
		if(mysql_num_rows($res2)==0)
			return null;
		else
		{
			$cid=md5($row2['ID'])."i".$row2['ID'];
			return $cid;
		}
	}
	else
		return null;
}
function client_authenticated($checksum)
{
	$id=explode('i',$checksum);
	if(md5($id[1])==$id[0])
	{
		$sql="SELECT CLIENTID FROM billing.VOUCHER_CLIENTS WHERE ID='$id[1]'";
		$res=mysql_query_decide($sql) or die(mysql_error_js($res));
		$row=mysql_fetch_array($res);
		if(mysql_num_rows($res)==0)
			return null;
		else
			return $row['CLIENTID'];
	}
	else
		return null;
}
?>
