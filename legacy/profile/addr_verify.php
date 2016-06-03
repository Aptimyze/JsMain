<?php

//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//end of it
	
include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
include(JsConstants::$docRoot."/commonFiles/flag.php");
$db=connect_db();
$data=authenticated();

if(($_GET['echecksum']) && ($addr_mailer==1) && !$data['PROFILEID']){
        $epid=$protect_obj->js_decrypt($_GET['echecksum']);
        if($_GET["checksum"]==$epid)
        {
                $epid_arr=explode("i",$epid);
                $profileid=$epid_arr[1];
                if($profileid){
                        $sql="SELECT USERNAME,PASSWORD FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID=$profileid";
                        $res=mysql_query_decide($sql) or die($sql.mysql_error());
                        $row=mysql_fetch_assoc($res);
                        $_POST['username']=$row['USERNAME'];
                        $_POST['password']=$row['PASSWORD'];
                        $smarty->assign('LOGIN',1);
                        $username=$row['USERNAME'];
                        $password=$row['PASSWORD'];
                        $smarty->assign('USERNAME',$username);
                        $data =login($username,$password);
			$parenturl=$_SERVER['REQUEST_URI'];
			header("Location:".$SITE_URL."/profile/intermediate.php?parentUrl=".$parenturl);
			exit;
                }
        }
}
if(!$data){
        TimedOut();
        exit;
}


if($data)
{
	$profileid=$data['PROFILEID'];
	$smarty->assign("CHECKSUM",$data["CHECKSUM"]);

	// Update the address 
	if($frmSave=='Save')
	{
		$sql="select CONTACT,SCREENING from newjs.JPROFILE where  activatedKey=1 and PROFILEID='$profileid'";
		$result=mysql_query_decide($sql) or logError("error",$sql);
		$editrow=mysql_fetch_array($result);
		$curflag=$editrow["SCREENING"];
		if($address!=$editrow["CONTACT"])		
			$curflag=removeFlag("CONTACT",$curflag);

		$sql_update_addr ="UPDATE newjs.JPROFILE SET `SCREENING`='$curflag',`CONTACT`='$address',`CITY_RES`='$city',`PINCODE`='$pincode' WHERE PROFILEID='$profileid'";
                mysql_query_decide($sql_update_addr) or logError("error",$sql_update_addr);
		
		header("location:$SITE_URL/profile/addr_confrm.php?addrStatus=1");
	}

	// Address of the profile 
	$sql_addr ="SELECT CONTACT,CITY_RES,COUNTRY_RES,PINCODE FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
	$res_addr = mysql_query_decide($sql_addr) or logError("error",$sql_addr);
	while($row_addr = mysql_fetch_array($res_addr))
	{
		$contact =$row_addr["CONTACT"];
		$city_res =$row_addr["CITY_RES"];
		$country_res =$row_addr["COUNTRY_RES"];
		$pincode =$row_addr["PINCODE"];
	}
	$city_res =$CITY_DROP[$city_res];
	$country_res  =$COUNTRY_DROP[$country_res];

	// top cities declaration of India
	$city_value =array();
	$city_label =array();
	$city_value=array("DE00","MH04","KA02","AP03","MH08","TN02","WB05","");
	$city_label=array("New Delhi","Mumbai","Bangalore","Hyderabad/Secunderabad","Pune","Chennai","Kolkata","");

	// fetch cities from India
	$sql_city = "SELECT SQL_CACHE VALUE,LABEL,STD_CODE FROM newjs.CITY_NEW WHERE COUNTRY_VALUE='51' AND TYPE!='STATE' ORDER BY SORTBY";
	$res_city = mysql_query_decide($sql_city) or logError("error",$sql_city);
	while($row_city = mysql_fetch_array($res_city))
	{
		$city_value[] = $row_city['VALUE'];
	        $city_label[] = $row_city['LABEL'];
	}
}

$smarty->assign("city_value",$city_value);
$smarty->assign("city_label",$city_label);	
$smarty->assign("contact",$contact);
$smarty->assign("city_res",$city_res);
$smarty->assign("country_res",$country_res);
$smarty->assign("pincode",$pincode);

$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
$smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
$smarty->assign("FOOT",$smarty->fetch("footer.htm"));
$smarty->display("addr_verify.htm");

?>

