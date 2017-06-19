<?php
/*********************************************************************************************
* FILE NAME     : connectmb.inc
* DESCRIPTION   : Has common functions required for Marriage Bureau
* CREATION DATE : 27 March, 2006
* CREATED BY    : Nikhil Tandon 
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
include('configmb.inc');
if(!function_exists('logError'))
       include('connecterror.inc');
include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/contacts_functions.php");
function authenticatedmb($checksum1="")
{
	global $TOUT1,$TOUT2,$_COOKIE,$smarty,$checksum4MB;
	if($checksum1=="")
	{
		if(isset($_COOKIE['JSMBLOGIN']))
		{
			$checksum1=$_COOKIE['JSMBLOGIN'];
			$checksum4MB=$checksum1;
		}
		else 
			return NULL;
	}
	else 
		$checksum2=$_COOKIE['JSMBLOGIN'];
	$arr=explode("i",$checksum1);
	
	if($arr[0]!=md5($arr[1]))
		return NULL;
	$id=$arr[1];
	$sql="select PROFILEID,SUBSCRIPTION,TIME1,USERNAME,ACTIVATED,SOURCE_ID from MB_CONNECT where ID='$id'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	
	if(mysql_num_rows($result) > 0)
		$myrow=mysql_fetch_array($result);
	if(mysql_num_rows($result) <= 0 || ((time()-$myrow["TIME1"]) > $TOUT1))
	{
		mysql_free_result($result);
		if($checksum2)
		{
			$arr=explode("i",$checksum2);
			if($arr[0]!=md5($arr[1]))
				return NULL;
			$id=$arr[1];
			$sql="select PROFILEID,SUBSCRIPTION,TIME1,USERNAME,ACTIVATED,SOURCE_ID from MB_CONNECT where ID='$id'";
			$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			if(mysql_num_rows($result) <= 0)
				return NULL;
			else 
			{
				$myrow=mysql_fetch_array($result);
				$checksum4MB=$checksum2;
			}	
		}
		else 
			return NULL;
	}
	mysql_free_result($result);
	// if first timeout has occured
	if (((time()-$myrow["TIME1"]) > $TOUT1))
		return NULL;
	$data["PROFILEID"]=$myrow["PROFILEID"];
	$data["SUBSCRIPTION"]=$myrow["SUBSCRIPTION"];
	$data["CHECKSUM"]=$checksum1;
	$data["USERNAME"]=$myrow["USERNAME"];
	$data["ACTIVATED"]=$myrow["ACTIVATED"];
	$data["SOURCE"]=$myrow["SOURCE_ID"];
	$tm=time();
	// update the time in connect only if it has not been updated in the last 5 minutes. This has been done to reduce the number of update queries
	if(($tm-$myrow["TIME1"]) > 300)
	{
		$sql="update MB_CONNECT set TIME1='$tm' where ID='$id'";
		mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	}

	//login_relogin_auth($data);
	//$smarty->assign("MBCURRENTACTIVATED",$data["ACTIVATED"]);
	//$smarty->assign("MBCURRENTUSERNAME",$data["USERNAME"]);
	//$smarty->assign("MBMYPROFILECHECKSUM",md5($data["PROFILEID"]) . "i" . $data["PROFILEID"]);
	return $data;
}
function loginmb($username,$password)
{
	global $TOUT1,$TOUT2,$smarty,$SITE_URL;
	$username=addslashes(stripslashes($username));
	$password=addslashes(stripslashes($password));
	$sql="select PROFILEID,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,USERNAME,ACTIVATED,MONEY from BUREAU_PROFILE where USERNAME='$username' and PASSWORD='$password' and ACTIVATED<>'D'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	if(mysql_num_rows($result) <= 0)
	{
		$sql="select PROFILEID,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,USERNAME,ACTIVATED,MONEY from BUREAU_PROFILE where EMAIL='$username' and PASSWORD='$password' and ACTIVATED<>'D'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		if(mysql_num_rows($result) <= 0)
			return NULL;
	}
	$myrow=mysql_fetch_array($result);
	$data["PROFILEID"]=$myrow["PROFILEID"];
	//for source to create profiles
	$source=generate_source4MB($data["PROFILEID"]);
	//till here
	$data["USERNAME"]=$myrow["USERNAME"];
	$data["ACTIVATED"]=$myrow["ACTIVATED"];
	$data["MONEY"]=$myrow["MONEY"];
	$userID=$data["PROFILEID"];
	$today=date("Y-m-d");
	$data["SUBSCRIPTION"]=trim($myrow["SUBSCRIPTION"]);
	$tm=time();
	$sql="delete from MB_CONNECT where TIME1 < ($tm - $TOUT2)";
	mysql_query_decide($sql) or die(mysql_error_js());
	$sql="insert into MB_CONNECT(ID,USERNAME,PASSWORD,PROFILEID,SUBSCRIPTION,SOURCE_ID,TIME1,ACTIVATED) values('','".addslashes(stripslashes($data["USERNAME"]))."','$password','" . $data["PROFILEID"] . "','" . $data["SUBSCRIPTION"] . "','$source','$tm','" . $data["ACTIVATED"] . "')";
	mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$id=mysql_insert_id_js();
	$checksum=md5($id) . "i" . $id;
	$data["CHECKSUM"]=$checksum;
	//$sql="insert ignore into MBLOGIN_HISTORY(PROFILEID,LOGIN_DT) values ('" . $data["PROFILEID"] . "',now())";
	//mysql_query_decide($sql);
	
	$sql="update BUREAU_PROFILE set LAST_LOGIN_DT=now(),SORT_DT=if(DATE_SUB(NOW(),INTERVAL 7 DAY)>SORT_DT,DATE_SUB(NOW(),INTERVAL 7 DAY),SORT_DT) where PROFILEID='" . $data["PROFILEID"] . "'";
	mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	
	// set a cookie with the name JSLOGIN that stores the checksum and expires when the session ends and is available on the entire domain
	
	setcookie("JSMBLOGIN",$checksum,0,"/",".jeevansathi.com");
	$smarty->assign("MBUSERNAME",$data["USERNAME"]);
	$smarty->assign("MBCURRENTACTIVATED",$data["ACTIVATED"]);
	$smarty->assign("MBMYPROFILECHECKSUM",md5($data["PROFILEID"]) . "i" . $data["PROFILEID"]);
	return $data;
}
function generate_source4MB($profileid)
{
	$len=strlen($profilid);
        $source="mb";
        for($i=4;$i>$len;$i--)
                $source=$source."0";
        $source=$source.$profileid;
	return $source;
}
function check_username_mb($username)
{
        $username_flag=0;
        if(trim($username)=="" || strlen($username)<2 || strlen($username)>20)
        {
                $username_flag=1; //return 1 if Username is blank or not in 2-20 range
		return $username_flag;
        }
         else
        {
                $result = mysql_query_decide("SELECT USERNAME FROM BUREAU_PROFILE where USERNAME='$username'") or logError("error",$sql);
                if(mysql_num_rows($result) > 0)
                        return 2;// return 2 if username already exists
        }
        return $username_flag;
}

//Function added by Lavesh to logout the user
function logoutmb($checksum)
{
        //setcookie("MB_AUTOLOGIN","",0,"/");
        if($checksum=="")
        {
                if(isset($_COOKIE['JSMBLOGIN']))
                        $checksum=$_COOKIE['JSMBLOGIN'];
                else
                        return NULL;
        }
        $arr=explode("i",$checksum);
        if($arr[0]!=md5($arr[1]))
                return false;
        $id=$arr[1];
        $sql="select PROFILEID from MB_CONNECT where ID='$id'";
        $result=mysql_query_decide($sql)or die(mysql_error_js());
        if(mysql_num_rows($result)>0)
        {
                $myrow=mysql_fetch_array($result);
                $profileid=$myrow["PROFILEID"];
        }
        mysql_free_result($result);
        $sql="delete from MB_CONNECT where ID='$id'";
        $result=mysql_query_decide($sql)or die ("bye");
        //setcookie("JSMBLOGIN","",0,"/",".jeevansathi.com");
	 setcookie("JSMBLOGIN","",0,"/","");
        //setcookie("CHATUSERNAME","",0,"/");
        //setcookie("CHATPROFILEID","",0,"/");
        if(!$result)
		 return false;
        else
                return true;
}
function timeoutmb()
{
	global $smarty;
	if(!$smarty)
	{
		include(JsConstants::$smartyDir);
	        $smarty=new Smarty;
        	$smarty->relative_dir="marriage_bureau/";
	}
	assign_template_pathmb();//to set path for mb
	$smarty->assign('relogin','Y');
	include('../marriage_bureau/login.php');
	exit;
}
function shownewjs_details($source,$profileid,$limit1,$limit2)
{
        global $smarty,$db;
	$db_211 = connect_211();
        $cnt=0;$i=0;
        mysql_select_db_js("newjs",$db);
        $sql="SELECT PROFILEID,USERNAME,ACTIVATED,INCOMPLETE FROM newjs.JPROFILE where SOURCE='$source' AND  ACTIVATED!='D'";
	if(!$limit1)
		$limit1=0;
	if($limit2)
		$sql.=" LIMIT $limit1,$limit2";
        $res=mysql_query_decide($sql,$db) or die(mysql_error_js($db));
        while($row=mysql_fetch_array($res))
        {
                $mb_profile[$i]["PROFILEID"]=$row['PROFILEID'];
                $mb_profile[$i]["USERNAME"]=$row['USERNAME'];
		if($row['INCOMPLETE']=='Y')$mb_profile[$i]["ACTIVATED"]="Incomplete";
		else if($row['ACTIVATED']=='Y')$mb_profile[$i]["ACTIVATED"]="Active";
		else if($row['ACTIVATED']=='H')$mb_profile[$i]["ACTIVATED"]="Hidden";
		else if($row['ACTIVATED']=='N' || $row['ACTIVATED']=='U')$mb_profile[$i]["ACTIVATED"]="Under Screening";
                $i++;
        }
        $csql = "Select FOUND_ROWS()";
        $cres = mysql_query_decide($csql,$db) or die(mysql_error_js($db));
        $crow = mysql_fetch_row($cres);
        $cnt = $crow[0];
        $showing_contacts=0;
	$total["contacted"]=0;
	$total["newcontacted"]=0;
	$total["viewed"]=0;
        for($j=0;$j<$i;$j++)
        {
		$mb_profile[$j]["contacts"]=0;
		$mb_profile[$j]["newcontacts"]=0;
		$mb_profile[$j]["viewed"]=0;

		//Added By lavesh as this is actual no of profiles a bureau has viewed.
		$sql1="SELECT count(*) as cnt FROM newjs.VIEW_LOG where VIEWER='".$mb_profile[$j][PROFILEID]."'";
		$result = mysql_query_decide($sql1,$db_211) or die(mysql_error_js($db_211));
		$row1=mysql_fetch_array($result);
		$total["viewed_corrected"]+=$row['cnt'];
		//Ends Here

                //only initial contact,or a profile seen before
		//Sharding of CONTACTS done by Sadaf
		$receiversIn=$mb_profile[$j]["PROFILEID"];
		$typeIn="'I'";
		$contactResult=getResultSet("SENDER",'','',$receiversIn,'',$typeIn);
		if(is_array($contactResult))
		{
			foreach($contactResult as $key=>$value)
			{
				$pppid=$contactResult[$key]["SENDER"];
				$sql1="SELECT count(*) as cnt FROM newjs.VIEW_LOG where VIEWER='".$mb_profile[$j]["PROFILEID"]."' AND VIEWED='$pppid'";
				$result = mysql_query_decide($sql1,$db_211) or die(mysql_error_js($db_211));
				$row1=mysql_fetch_array($result);
				if($row1['cnt']>0)
				{
					//he has viewed	
					$mb_profile[$j]["contacts"]++;
					$total["contacted"]++;
				}
				else
				{
					//newcontact..he has not viewed,entry in marriage_bureau.VIEWED not there
					$mb_profile[$j]["newcontacts"]++;
					$total["newcontacted"]++;
				}
			}
			unset($contactResult);
		}
		
		 //Added By lavesh
		//Sharding of CONTACTS done by Sadaf
		$receiversIn=$mb_profile[$j]["PROFILEID"];
		$typeIn="'D'";
		$contactResult=getResultSet("SENDER",'','',$receiversIn,'',$typeIn);
                
		if(is_array($contactResult))
		{
			foreach($contactResult as $key=>$value)
			{
				$pppid=$contactResult[$key]["SENDER"];
				$sql1="SELECT count(*) as cnt FROM newjs.VIEW_LOG where VIEWER='".$mb_profile[$j]["PROFILEID"]."' AND VIEWED='$pppid'";
				$result = mysql_query_decide($sql1,$db_211) or die(mysql_error_js($db_211));
				$row1=mysql_fetch_array($result);
				if($row1['cnt']>0)
				{
					//he has viewed
					$mb_profile[$j]["deletedcontacts"]++;
					$total["contacted"]++;
				}                 
			}
			unset($contactResult);
		}
                //Ends Here.
		
		//a contact that has been accepted
		//Sharding of CONTACTS done by Sadaf
		$receiversIn=$mb_profile[$j]["PROFILEID"];
		$typeIn="'A'";
		$contactResult=getResultSet("count(*) as cnt",'','',$receiversIn,'',$typeIn);

		if($contactResult[0]["cnt"]>0)
		{
			$mb_profile[$j]["viewed"]=$row['cnt'];
			$total["viewed"]+=$row['cnt'];
                }

		//Added By lavesh
                $sql="SELECT count(*) as cnt FROM marriage_bureau.VIEWED WHERE AGAINST_PROFILE='".$mb_profile[$j][PROFILEID]."'";
                $res = mysql_query_decide($sql,$db) or die(mysql_error_js($db));
                $row=mysql_fetch_array($res);
                if($mb_profile[$j]["viewed"]!=$row['cnt'])
                {
                        $mb_profile[$j]["declined_viewed"]=$row['cnt']-$mb_profile[$j]["viewed"];
                        $total["contacted"]+=$mb_profile[$j]["declined_viewed"];
                }


                //Ends Here.

		$total["totalcontacted"]=$total["contacted"]+$total["newcontacted"]+$total["viewed"];
        }
	$db=connect_dbmb();
	mysql_select_db_js("marriage_bureau",$db);
        $smarty->assign('mb_profile',$mb_profile);
        $smarty->assign('showing_contacts',$showing_contacts);
        $smarty->assign('profile_contacted',$profile_contacted);
	return $total;
}
function profileid_of_bureau($source)
{
	$source=substr($source,2,7);
	$length=strlen($source);
	$profileid="";
	for($i=0;$i<$length;$i++)
	{
		if($source[$i]=="0"){if(!$flag==1)$flag=0;}
		else $flag=1;
		if($flag==1)
                $profileid.=$source[$i];
	}
	return $profileid;
}
function newjsusername($profileid)
{
	$sql="SELECT USERNAME from newjs.JPROFILE WHERE PROFILEID='$profileid'";
	$res=mysql_query_decide($sql) or die(mysql_error_js());
	$row=mysql_fetch_array($res);
	$username=$row['USERNAME'];
	return $username;
}
function login_every_user($profileid)
{
	global $TOUT1,$TOUT2,$smarty;
	$sql="select PROFILEID,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,USERNAME,GENDER,ACTIVATED,SOURCE from JPROFILE where PROFILEID='$profileid'";
	$result=@mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	if(mysql_num_rows($result) <= 0)
		return NULL;
	$myrow=mysql_fetch_array($result);
	$data["PROFILEID"]=$myrow["PROFILEID"];
	$data["USERNAME"]=$myrow["USERNAME"];
	$data["GENDER"]=$myrow["GENDER"];
	$data["ACTIVATED"]=$myrow["ACTIVATED"];
	$data["SOURCE"]=$myrow["SOURCE"];
	if(substr($data["SOURCE"],0,2)=='mb')
		$data["BUREAU"]=1;
	else
		$data["BUREAU"]=0;
	$userID=$data["PROFILEID"];
	$today=date("Y-m-d");
	$data["SUBSCRIPTION"]=trim($myrow["SUBSCRIPTION"]);
	$tm=time();
	$sql="delete from CONNECT where TIME1 < ($tm - $TOUT2)";
	mysql_query_decide($sql);
	$sql="insert into CONNECT(ID,USERNAME,PASSWORD,PROFILEID,SUBSCRIPTION,TIME1,GENDER,ACTIVATED,SOURCE) values ('','".addslashes(stripslashes($data["USERNAME"]))."','$password','" . $data["PROFILEID"] . "','" . $data["SUBSCRIPTION"] . "','$tm','" . $data["GENDER"] . "','" . $data["ACTIVATED"] . "','" . $data["SOURCE"] . "')";
	
	@mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	
	$id=mysql_insert_id_js();
	//$checksum=md5($id) . "i" . $id;
	$checksum=js_encrypt_mb(md5($data["PROFILEID"])."i".$data["PROFILEID"]);
	$data["CHECKSUM"]=$checksum;

	$sql="insert ignore into LOGIN_HISTORY(PROFILEID,LOGIN_DT) values ('" . $data["PROFILEID"] . "',now())";
	@mysql_query_decide($sql);
	
	$sql="update JPROFILE set LAST_LOGIN_DT=now(),SORT_DT=if(DATE_SUB(NOW(),INTERVAL 7 DAY)>SORT_DT,DATE_SUB(NOW(),INTERVAL 7 DAY),SORT_DT) where PROFILEID='" . $data["PROFILEID"] . "'";
	@mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	return $data;
}
function get_email_id_mb($s)
{
	global $email,$profileofbureau;
	mysql_select_db_js('newjs');
	$sql="SELECT SOURCE FROM newjs.JPROFILE where PROFILEID='$s'";
	$res=mysql_query_decide($sql);
	$row=mysql_fetch_array($res);
	$source=$row["SOURCE"];
	$data=getdata_mb($source);
	return $data;
}
function getdata_mb($source)
{
	$source=substr($source,2,7);
	$length=strlen($source);
	$profileid="";
	$flag=0;
	for($i=0;$i<$length;$i++)
	{
		if($source[$i]=="0"){if(!$flag==1)$flag=0;}
		else $flag=1;
		if($flag==1)
			$profileid.=$source[$i];
	}
	//$sql="SELECT EMAIL,PROFILEID from marriage_bureau.BUREAU_PROFILE WHERE PROFILEID='$profileid'";
	$sql="select PROFILEID,NAME,ADDRESS,CITY,STATE,COUNTRY,PIN,TELEPHONE1,TELEPHONE2,STD,FAX,EMAIL from marriage_bureau.BUREAU_PROFILE where PROFILEID='$profileid'";	
	$res=mysql_query_optimizer($sql) or die(mysql_error_js());
	$row=mysql_fetch_array($res);
	$bureau=array("NAME" => $row["NAME"],
					"ADDRESS" => $row["ADDRESS"],
					"CITY" => $row["CITY"],
					"STATE" => $row["STATE"],
					"COUNTRY" => $row["COUNTRY"],
					"PIN"=>$row["PIN"], 
					"STD"=>$row["STD"], 
					"TELEPHONE1"=>$row["TELEPHONE1"],
					"TELEPHONE2"=>$row["TELEPHONE2"],
                                        "FAX" => $row["FAX"],
					"EMAIL" => $row["EMAIL"],
					"PROFILEID" => $row["PROFILEID"]);
	
	return $bureau;
}
        /*
        **** @function: js_encrypt
        **** input is plain text that is to be encrypted and output is cipher text
        */
        function js_encrypt_mb($plainText)
        {
                $_KEY = "Radhe Shaam";
                $_SUBKEY = "muhaafiz Khudi ke";

                //return rawurlencode(md5($this->_KEY . md5($plainText) . $this->_SUBKEY) . "|i|" . $plainText);
                return md5($_KEY . md5($plainText) . $_SUBKEY) . "|i|" . $plainText;
        }


        /*
        **** @function: js_decrypt
        **** input is cipher text that is to be decrypted and output is plain text or false on error
        */
        function js_decrypt_mb($cipherText)
        {
                $_KEY = "Radhe Shaam";
                $_SUBKEY = "muhaafiz Khudi ke";

                //$arrTmp = explode("|i|", rawurldecode($cipherText));
                $arrTmp = explode("|i|", $cipherText);
                $arrTmp[1]=stripslashes($arrTmp[1]);
                //this change was done for earlier usernmames which have special characters in them so as to remove backslas (/) that is added to them.
                if (md5($_KEY . md5($arrTmp[1]) . $_SUBKEY) == $arrTmp[0])
                        return $arrTmp[1];
                else
                        return false;
        }

?>
