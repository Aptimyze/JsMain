<?php
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

$useCrmMemcache = crmCommonConfig::$useCrmMemcache;
if(!function_exists("authenticated"))
{
	function authenticated($checksum="")
	{
		if($checksum)
		{
			list($md, $userno)=explode("i",$checksum);
			if(md5($userno)==$md)
			{
				if($_GET['dialer_check']==1)
				{
					if(isset($_COOKIE["CRM_LOGIN"]))
						$checksum = $_COOKIE["CRM_LOGIN"];
					else
						$checksum = $_GET["cid"];
				}
				else
				{
					$checksum = null;
				}
				$webAuthenticateObj = new BackendWebAuthentication();
				$data = $webAuthenticateObj->authenticateAgent($checksum);
				unset($webAuthenticateObj);
				if($data)
				{
					return $data;
				}
				else
				{
					return NULL;
				}
			}
			else
			{
				return NULL;
			}
		}
		else
			return null;
	}
}


function getcenter($connection, $ip="")
{
	global $TOUT;
	$useCrmMemcache = crmCommonConfig::$useCrmMemcache;
	$backendObj = new backendActionsLib(array("jsadmin_PSWRDS"=>"newjs_slave","jsadmin_CONNECT"=>"newjs_master"),$useCrmMemcache);

	$data = $backendObj->fetchPSWRDSDetailsBySessionID($connection,$ip,"CENTER");
	unset($backendObj);
	$ret = $data[0]["CENTER"];
	return $ret;
}

function get_operator_detail($checksum)
{
	global $TOUT;
	$useCrmMemcache = crmCommonConfig::$useCrmMemcache;
	list($md, $userno)=explode("i",$checksum);
	if(md5($userno)!=$md)
		return FALSE;
	else
	{
		$backendObj = new backendActionsLib(array("jsadmin_PSWRDS"=>"newjs_slave","jsadmin_CONNECT"=>"newjs_master"),$useCrmMemcache);
		
		$data = $backendObj->fetchPSWRDSDetailsBySessionID($userno,"","EMAIL,USERNAME,PHONE");
		unset($backendObj);
		$ret = $data[0];	
	}
	return $ret;
}

function getemail($checksum)
{
	global $TOUT;
	$useCrmMemcache = crmCommonConfig::$useCrmMemcache;
	list($md, $userno)=explode("i",$checksum);
    if(md5($userno)!=$md)
       return FALSE;
	else
	{
		$backendObj = new backendActionsLib(array("jsadmin_PSWRDS"=>"newjs_slave","jsadmin_CONNECT"=>"newjs_master"),$useCrmMemcache);
		
		$data = $backendObj->fetchPSWRDSDetailsBySessionID($userno,"","EMAIL");
		unset($backendObj);
		$ret = $data[0]["EMAIL"];
	}

return $ret;
}

if(!function_exists("getname"))
{
	function getname($cid)
	{
		$useCrmMemcache = crmCommonConfig::$useCrmMemcache;
	    $temp=explode("i",$cid);
	    $userid=$temp[1];
	    if($userid)
	    {
			$backendObj = new backendActionsLib(array("jsadmin_PSWRDS"=>"newjs_slave","jsadmin_CONNECT"=>"newjs_master"),$useCrmMemcache);
			$data = $backendObj->fetchPSWRDSDetailsBySessionID($userid,"","USERNAME");
			unset($backendObj);
			$username = $data[0]["USERNAME"];
		}
		else
			$username= null;	
	    return $username;
	}
}

function getprivilage($checksum="",$opname="")
{
	global $TOUT;
	$useCrmMemcache = crmCommonConfig::$useCrmMemcache;
	if($opname)
	{
		$backendObj = new jsadmin_PSWRDS("newjs_slave");
		$ret = $backendObj->getPrivilegeForAgent($opname);
		unset($backendObj);
	}
	else if($checksum)
	{
		list($md, $userno)=explode("i",$checksum);
		if(md5($userno)!=$md)
		{
			return FALSE;
		}
		else
		{
			$backendObj = new backendActionsLib(array("jsadmin_PSWRDS"=>"newjs_slave","jsadmin_CONNECT"=>"newjs_master"),$useCrmMemcache);
			$data = $backendObj->fetchPSWRDSDetailsBySessionID($userno,"","PRIVILAGE");
			unset($backendObj);
			$ret = $data[0]["PRIVILAGE"];
		}
	}
	return $ret;
}

function getuser($checksum, $ip="")
{
	global $TOUT;
	$useCrmMemcache = crmCommonConfig::$useCrmMemcache;
	list($md, $userno)=explode("i",$checksum);
	if(md5($userno)!=$md)
		return FALSE;
	else
	{                                                                                               
		$backendObj = new backendActionsLib(array("jsadmin_PSWRDS"=>"newjs_slave","jsadmin_CONNECT"=>"newjs_master"),$useCrmMemcache);
	
		$data = $backendObj->fetchPSWRDSDetailsBySessionID($userno,$ip,"USERNAME");
		unset($backendObj);
		$ret = $data[0]["USERNAME"];
	}
	return $ret;
}
if(!function_exists("logout"))
{
	function logout($checksum)
	{
		$useCrmMemcache = crmCommonConfig::$useCrmMemcache;
		list($md, $userno)=explode("i",$checksum);
		if(md5($userno)!=$md)
		    return FALSE;
		else
		{
			$backendObj = new backendActionsLib(array("jsadmin_CONNECT"=>"newjs_master"),$useCrmMemcache);
			$ret = $backendObj->deleteAgentLoginSession($userno);
		}
		return $ret;
	}
}

?>