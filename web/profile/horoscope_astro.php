<?php
	///to zip the file before sending it
        $zipIt = 0;
        if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
                $zipIt = 1;
        if($zipIt)
                ob_start("ob_gzhandler");
        //end of it

        include_once("connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/flag.php");
	include_once("search.inc");

	$db=connect_db();

	$data=authenticated($checksum);
	if($SHOW_WHAT=='payment')
	{
		$smarty->display("astro_payment.htm");
		die;
	}
        $smarty->assign("GENDERSAME",$GENDERSAME);
	if($HOROSCOPE=='N' || $ERROR_MES)
	{
		if($profilechecksum)
			list($chksum,$profileid) = explode("i",$profilechecksum);
		else
			die("ERROR#illegal request");

		if(check_astro_details($profileid,"Y"))
		{
			$message="<span style='text-align:left'>This user has chosen to hide the horoscope/astro details.";
				
		}
		else if(!$data['PROFILEID'])
		{
			if($profilechecksum)
				list($chksum,$profileid) = explode("i",$profilechecksum);
			else
				die("ERROR#illegal request");

			$sql = "SELECT GENDER  FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
		        $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		        if($row = mysql_fetch_array($result))
				$gender=$row['GENDER'];
			if($gender=='M')
				$his_her="his";
			else
				$his_her='her';
			$message="<span style='text-align:left'>This user has not uploaded a horoscope. If you liked the profile and would like to match your horoscope with $his_her, you can log in and then request for horoscope</span>. <a href='".$SITE_URL."/profile/login.php' class='thickbox' onclick='javascript:show_login_layer(\"".$SITE_URL."/profile/login.php?SHOW_LOGIN_WINDOW=1\");return false'>Click here to login</a>";
		//	$message="<span style='text-align:left'>This user has not uploaded horoscope, If you liked  profile and want to match horoscope you can  log in and then request for horoscope</span>. <a href='login.php' class='thickbox' onclick='javascript:show_login_layer(\"login.php?SHOW_LOGIN_WINDOW=1\");return false'>Click here to login</a>";
		}
		else
		{
			list($chksum,$profileid) = explode("i",$profilechecksum);
			$message=is_horoscope_request($data['PROFILEID'],$profileid,$dt,isPaid($data[SUBSCRIPTION]));
			if($data['GENDER']=='M')
				$his_her="her";
			else
				$his_her="his";

			if(!$message)		
				$message=' This user has not uploaded  horoscope. If you liked the profile and would like to match your horoscope with '.$his_her.', you can <br><br><div ><input type="button" style="width: 170px;" value="Request Horoscope" class="b green_btn" onclick="javascript:show_login_layer(\''.$SITE_URL.'/profile/horos_req_layer.php?width=512&view_username='.$_GET["view_username"].'&profilechecksum='.$profilechecksum.'\')"></div>';
		}
		if($SAMEGENDER || $FILTER)
		{
			if($SAMEGENDER)
				$message="You cannot see horoscope since you both have same gender.";
			else if($FILTER)
				$message="You are filtered by $_GET[view_username].";
		}
		$smarty->assign("MESSAGE",$message);
		if($from_edit)
			$smarty->display("profile_edit_horoscope1.htm");
		else
			 $smarty->display("contact/horoscope.htm");
		die;
		
	}
	if($data)
	{
		if(!$profilechecksum)
		{
			$profileid = $data['PROFILEID'];
			$smarty->assign("SELF_PROFILE","Y");
		}
		else
			list($chksum,$profileid) = explode("i",$profilechecksum);
	}
	else
	{
		list($chksum,$profileid) = explode("i",$profilechecksum);
	}

	$sql = "SELECT USERNAME , PROFILEID , DTOFBIRTH , BTIME , NAKSHATRA  FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$row = mysql_fetch_array($result);

	if($data['PROFILEID'])
                if(!check_astro_details($data['PROFILEID'],'Y'))
		{
			$smarty->assign("NO_ASTRO",1);
                        $smarty->assign("OWN_PROFILECHECKSUM",createChecksumForSearch($data['PROFILEID']));
		}
		else
		{
                        if($from_jspcEdit)
                          $smarty->assign("FROM_JSPC_EDIT",1);
			if((!strstr($data['SUBSCRIPTION'],'A'))&&($compatibility_subscription != '1'))
			{
				$smarty->assign("COMPATIBILITY_SUBSCRIPTION",'N');
			}
			else
			{
				$smarty->assign("PROFILECHECKSUM",createChecksumForSearch($profileid));
				
			}
		}
		$smarty->assign("PROFILECHECKSUM",$profilechecksum);

	$sql = "SELECT * FROM newjs.ASTRO_DETAILS WHERE PROFILEID='$profileid'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$row1 = mysql_fetch_array($result);
	list($astrodata['BIRTH_YR'],$astrodata['BIRTH_MON'],$astrodata['BIRTH_DAY']) = explode("-",$row1['DTOFBIRTH']);
	list($astrodata['BIRTH_HR'],$astrodata['BIRTH_MIN'],$astrodata['BIRTH_SEC']) = explode(":",$row1['BTIME']);

	$astrodata['DTOFBIRTH']=$row1['DTOFBIRTH'];
	$astrodata['BTIME'] = $row1['BTIME'];
	$astrodata['BPLACE'] = $row1['CITY_BIRTH'];
	$astrodata['BSTATE'] = $row1['PLACE_BIRTH'];
	$astrodata['PLACE_BIRTH']=$row1['PLACE_BIRTH'];

	$astrodata['LATITUDE']= $row1['LATITUDE'];
	$astrodata['LONGITUDE']= $row1['LONGITUDE'];
	$astrodata['TIMEZONE']=$row1['TIMEZONE'];
	$astrodata['DST']=$row1['DST'];

	$astrodata['LAGNA_DEGREES_FULL'] = $row1['LAGNA_DEGREES_FULL'];
	$astrodata['SUN_DEGREES_FULL'] = $row1['SUN_DEGREES_FULL'];
	$astrodata['MOON_DEGREES_FULL'] = $row1['MOON_DEGREES_FULL'];
	$astrodata['MARS_DEGREES_FULL'] = $row1['MARS_DEGREES_FULL'];
	$astrodata['MERCURY_DEGREES_FULL'] = $row1['MERCURY_DEGREES_FULL'];
	$astrodata['JUPITER_DEGREES_FULL'] = $row1['JUPITER_DEGREES_FULL'];
	$astrodata['VENUS_DEGREES_FULL'] = $row1['VENUS_DEGREES_FULL'];
	$astrodata['SATURN_DEGREES_FULL'] = $row1['SATURN_DEGREES_FULL'];
	$astrodata['RAHU_DEGREES_FULL'] = $row1['RAHU_DEGREES_FULL'];
	$astrodata['KETU_DEGREES_FULL'] = $row1['KETU_DEGREES_FULL'];
	$astrodata['MOON_RETRO_COMBUST'] = $row1['MOON_RETRO_COMBUST'];
	$astrodata['MARS_RETRO_COMBUST'] = $row1['MARS_RETRO_COMBUST'];
	$astrodata['MERCURY_RETRO_COMBUST'] = $row1['MERCURY_RETRO_COMBUST'];
	$astrodata['JUPITER_RETRO_COMBUST'] = $row1['JUPITER_RETRO_COMBUST'];
	$astrodata['VENUS_RETRO_COMBUST'] = $row1['VENUS_RETRO_COMBUST'];
	$astrodata['SATURN_RETRO_COMBUST'] = $row1['SATURN_RETRO_COMBUST'];
	$astrodata['VARA'] = $row1['VARA'];
	$astrodata['MASA'] = $row1['MASA'];

	$astrodata['USERNAME'] = $row['USERNAME'];
	$astrodata['NAKSHATRA'] = $row['NAKSHATRA'];

	$smarty->assign("profileid",$profileid);
	$smarty->assign('astrodata',$astrodata);
  $smarty->assign('RANDOM_NUM',time());
	//$smarty->assign('horoscope_type',"S");
	if($row1['TYPE']=='U' || $row1['TYPE']=='')//if the person has uploaded the horoscope for uploaded horoscope
	{
		$smarty->assign('horoscope_type',"U");
	}
	if($from_edit)
		$smarty->display("profile_edit_horoscope1.htm");
	else{
                if(!$from_jspcEdit){
                $sql="SELECT COUNT(*) c from HOROSCOPE where PROFILEID='$profileid'";
                    $result=mysql_query_decide($sql) or die(mysql_error_js());
                    $myrow= mysql_fetch_array($result);
                    if($myrow['c']==0)
                    {
                            $smarty->assign('horoscope_type',"showVedic");
                    }
                 }   
		 $smarty->display("contact/horoscope.htm");
        }
function is_horoscope_request($profileid,$chkprofilechecksum,$dt='',$is_paid='')
{
        //Sharding Concept added by Lavesh Rawat on table HOROSCOPE_REQUEST
        //affectedDb list of database need to be updated as 2 shards can have same entry.
        $mysqlObj=new Mysql;

        $myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
        $myDb=$mysqlObj->connect("$myDbName");
        $affectedDb[0]=$myDb;

        $myDbName=getProfileDatabaseConnectionName($chkprofilechecksum,'',$mysqlObj);
        $viewedDb=$mysqlObj->connect("$myDbName");
        if(!in_array($viewedDb,$affectedDb))
                $affectedDb[1]=$viewedDb;

       
	$sql_chk="SELECT CNT FROM HOROSCOPE_REQUEST WHERE PROFILEID='$profileid' and PROFILEID_REQUEST_BY='$chkprofilechecksum'";
	$result_chk = $mysqlObj->executeQuery($sql_chk,$myDb);
	$myrow_chk=$mysqlObj->fetchArray($result_chk);
	if($myrow_chk['CNT'])
	{
		$message="You already made request for Horoscope.";
		if(!$is_paid)
		{
			$message.=' Call the person and ask for the Horoscope. To view contact details <BR><BR><input type="button" class="b green_btn" value="Buy Membership" style="width:146px;"  onclick="javascript:{document.location=\''.$SITE_URL.'/profile/mem_comparison.php?from_source=Horoscope_Request_From_Detailed\';}">';
		}
	}
	return $message;
}
?>
