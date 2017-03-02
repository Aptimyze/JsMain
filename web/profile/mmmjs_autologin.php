<?
/**
Scripts redirect the user to respective page
if echecksum & checksum is passed to it
**/
if(strtolower($_SERVER[REQUEST_METHOD])=="head")
die();
include("connect.inc");
include_once("mobile_detect.php");
$db=connect_db();
$request_uri=$_SERVER[REQUEST_URI];
$parameters=explode("?",$request_uri);
$url=$parameters[0];
$parameters[1]=urldecode($parameters[1]);
$param=explode("&",$parameters[1]);
foreach($param as $key=>$val)
{
	
	if(!(strpos($val,"echecksum=")===false)  && (strpos($val,"profilechecksum=")===false))
		$echecksum=str_replace("echecksum=","",$val);
	else if(!(strpos($val,"checksum=")===false) && (strpos($val,"profilechecksum=")===false))
		$orgChecksum=$checksum=str_replace("checksum=","",$val);
	if(!(strpos($val,"username=")===false))
		$calledUsername=str_replace("username=","",$val);
}

$logStr=$request_uri;
$request_uri=str_replace("CMGFRMMMMJS=","pass=",$request_uri);
$request_uri=str_replace("&echecksum=","&autologin=",$request_uri);
$request_uri=str_replace("?echecksum=","?autologin=",$request_uri);
$request_uri=str_replace("&checksum=","&chksum=",$request_uri);
$request_uri=str_replace("?checksum=","?ckhsum=",$request_uri);
$request_uri=str_replace(urlencode($echecksum),"",$request_uri);
$request_uri=str_replace($echecksum,"",$request_uri);
$request_uri=ltrim($request_uri,"/");

$url_to_go=$SITE_URL."/".$request_uri;

$data=authenticated();
//$donotlogin=1;
//echo "after checksum".$checksum;die;
//echo $echecksum." j   ".$checksum;
//	echo "--".$checksum;die;
$checksum=$orgChecksum;
if($echecksum && $checksum && !$data[PROFILEID])
{
	$show_chatbar=1;
	$db=connect_db();
	if($_COOKIE['chatbar']=='yes')
		$show_chatbar=0;
	 $epid=$protect_obj->js_decrypt($echecksum,"Y");
	if($checksum==$epid)
	{
		$epid_arr=explode("i",$epid);
                $profileid=$epid_arr[1];
		
		$profileid=whom_to_stop($profileid);
		if($profileid)
		{
			if(strstr($logStr,"CMGFRMMMMJS=mobile"))
			{
				$channel="S";
				logShortUrlMobileHits($profileid,$request_uri);
			}
			else
				$channel="M";
			//tracking logins #2550 by nitesh
			loginTracking($profileid,$request_uri,$channel);
			
			$sql="select PROFILEID,PASSWORD,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,USERNAME,GENDER,ACTIVATED,SOURCE,DATE(LAST_LOGIN_DT) LAST_LOGIN_DT,CASTE,MTONGUE,INCOME,RELIGION,AGE,HEIGHT,HAVEPHOTO,INCOMPLETE,MOD_DT,COUNTRY_RES,EMAIL FROM newjs.JPROFILE WHERE  PROFILEID=$profileid and  activatedKey=1";
			$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			if(($row=mysql_fetch_assoc($res)))
			{
				$username=$row['EMAIL'];
				if($calledUsername==$username)
				{
					$donotlogin=1;
	//				$logUrl=urlencode($logStr);
     ///     passthru("echo \"$logUrl\" >> /var/www/html/sameid.txt");
				}
				if(!$donotlogin)
				{
					global $protect_obj;
					$data = $protect_obj->postLogin($row);
				}
				if($data[PROFILEID])
				{
					if($show_chatbar)
					{
						$pos = strpos($request_uri,"login.php");
						$pos2= strpos($request_uri,"login_redirect.php");
						$pos4=in_array("fromRegister=1",$param);
						if($pos == false  && $pos2== false && $pos3 == false && !$isMobile){
							if(($_COOKIE['JS_MOBILE']=='N' || !isset($_COOKIE['JS_MOBILE'])))
								$url_to_go="$SITE_URL/$request_uri";
						}
					}
				}
			}
		}
	}
	
}
if(strpos($url_to_go,"mmmjs_autologin")===false)
        header("Location:$url_to_go");
else
        header("Location:$SITE_URL/profile/mainmenu.php");

//header("Location:$url_to_go");
function whom_to_stop($profileid)
{
$prof_array=array(8264221,6802915);
if(in_array($profileid,$prof_array))
	return '';
return $profileid;
}
function logShortUrlMobileHits($profileid,$request_uri)
{
	$page=explode('?',$request_uri);
	$page=$page[0];
	$page=explode('/',$page);
	$no=count($page);
	$page=$page[$no-1];
	$sqlLog="INSERT INTO MIS.ShortUrlMobileHitsLog ( `PROFILEID` , `URL` , `TIME`) VALUES ('".$profileid."', '".$page."', now())";
	$resLog=mysql_query_decide($sqlLog) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
}
//Function to track all logins happening through autologin
//channel can be Mailer(M),SMS(S)
//Webiste Version Desktop(D),Mobile(M)
function loginTracking($profileid,$request_uri,$channel)
{
	$loginTracking = LoginTracking::getInstance($profileid);	
	$loginTracking->setChannel($channel);
	$loginTracking->setRequestURI($request_uri);
	$loginTracking->loginTracking();
}

?>
