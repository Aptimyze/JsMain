<?php

  
$TOUT = 9000;	// the timeout value in seconds

include_once("../jsadmin/handleCrmLogin.php");

include ("/usr/local/apache/htdocs/smarty/Smarty.class.php");
if(!$active_db)
	$active_db = "";
if(!$previous_db)
	$previous_db = "";
if(!$db_211)
	$db_211="";
include_once(JsConstants::$docRoot."/commonFiles/mysql_multiple_connections.php");

$db = db_set_active("localhost","root","Km7Iv80l") or die("Can't connect to Database".mysql_error_js());
mysql_select_db("jsadmin",$db);		// connection string
//mysql_select_db("billing",$db);		// connection string

$smarty = new Smarty;
//$smarty->template_dir="/usr/local/apache/htdocs/smarty/templates/jeevansathi/billing";
$smarty->relative_dir="jeevansathi/billing/"; 

function login ($id, $pass)
{
	global	$TOUT;	// global variables
	$mycond = 0;

	$sql = "select RESID from PSWRDS where USERNAME= binary '$id' and PASSWORD= binary '$pass'";
	$res = mysql_query_decide($sql) or die("Could not Execute query pwdError");
	$count = mysql_num_rows($res);

	
	if ($count > 0)
	{
		$myrow = mysql_fetch_array($res);
		$recid = $myrow["RESID"];
		$tm = time();
		$sql="delete from CONNECT where TIME2 < ($tm - $TOUT2)";
		$result = mysql_query_decide($sql);
	
		$sqlup="insert into CONNECT (ID, USER, IPADDR, TIME) values ('','$recid','$ip','$tm')";
                $sqlup_con = mysql_query_decide($sqlup) or die($sqlup." : ".mysql_error_js());

		$id=mysql_insert_id_js();
                                                                                                                             
	        $ret=md5($id)."i".$id;
                                                                                                                             
            	//$ret["USER"]=$name;
            	//$ret["RESID"]=$resid;

	}
	else
	{
		$ret = 0;
	}
//echo "retLogin : $ret";
	return $ret;
}

function authenticated($checksum="")
{
	global $TOUT;
	list($md, $userno)=explode("i",$checksum);

	if(md5($userno)!=$md)
            return NULL;
        else
        {
	$sql_chk = "select * from CONNECT where ID=$userno";
	$res_chk = mysql_query_decide($sql_chk) or die("Could not Authenticate User: auError $sql_chk ".mysql_error_js());
	$count = mysql_num_rows($res_chk);

	if ($count > 0)
	{
		$myrow = mysql_fetch_array($res_chk);

		if (time()-$myrow["TIME"] < $TOUT)
		{
			$tm = time();
			$sql_up = "update CONNECT set TIME=$tm where ID=$userno";
			$res_up = mysql_query_decide($sql_up) or die("Could not Authenticate User: upError");

			$ret["ID"] = $md."i".$userno;
                        $ret["USER"]=$myrow["USER"];
            setLoginCookies($ret["ID"],$_COOKIE["CRM_NOTIFICATION_AGENT"]);                 
			//$ret = TRUE;
		}
		else
		{
			unsetLoginCookies();
			$ret = NULL;
		}
	}
	else
	{
		$ret = NULL;
	}
	}

return $ret;
}

function logout($checksum)
{
	global $TOUT;

	list($md, $userno)=explode("i",$checksum);
        if(md5($userno)!=$md)
            return FALSE;
        else
        {
		$sql_chk = "delete from CONNECT where ID='$userno'";
		$res_chk = mysql_query_decide($sql_chk);

		if ($res_chk)
			$ret = TRUE;
		else
			$ret = FALSE;
	}
return $ret;
}

function getuser($checksum, $ip)
{
	global $TOUT;

        list($md, $userno)=explode("i",$checksum);
        if(md5($userno)!=$md)
            return FALSE;
        else
        {                                                                                                                    
		$sql_usr = "select USER from CONNECT where ID=$userno";
		$res_usr = mysql_query_decide($sql_usr) or die("Could not return data from Connect1. $sql_usr ".mysql_error_js());
		$count = mysql_num_rows($res_usr);

		if ($count > 0)
		{
			$myrow = mysql_fetch_array($res_usr);
			$ret_usr = $myrow["USER"];
		
			$sql_getusr = "select USERNAME from PSWRDS where RESID=$ret_usr";
			$res_getusr = mysql_query_decide($sql_getusr) or die("Could not return data from Pswrds.");
			$count_usr = mysql_num_rows($res_getusr);
		
			if($count_usr > 0)
			{
				$myrow_usr = mysql_fetch_array($res_getusr);
				$ret = $myrow_usr["USERNAME"];
			}
			else
			{
				$ret = FALSE;
			}
		}
		else
		{
			$ret = FALSE;
		}
	}
return $ret;
}

function getprivilage($checksum)
{
	global $TOUT;

	list($md, $userno)=explode("i",$checksum);
        if(md5($userno)!=$md)
            return FALSE;
        else
        {
         
		$sql_usr = "select USER from CONNECT where ID=$userno";
		$res_usr = mysql_query_decide($sql_usr) or die("Could not return data from Connect1.");
		$count = mysql_num_rows($res_usr);

		if ($count > 0)
		{
			$myrow = mysql_fetch_array($res_usr);
			$ret_usr = $myrow["USER"];
		
			$sql_getusr = "select PRIVILAGE from PSWRDS where RESID=$ret_usr";
			$res_getusr = mysql_query_decide($sql_getusr) or die("Could not return data from Pswrds.");
			$count_usr = mysql_num_rows($res_getusr);
		
			if($count_usr > 0)
			{
				$myrow_usr = mysql_fetch_array($res_getusr);
				$ret = $myrow_usr["PRIVILAGE"];
			}
			else
			{
				$ret = FALSE;
			}
		}
		else
		{
			$ret = FALSE;
		}
	}
return $ret;
}

function getcenter($connection, $ip)
{

	
	global $TOUT;

	$sql_usr = "select USER from CONNECT where ID=$connection and IPADDR='$ip'";
	$res_usr = mysql_query_decide($sql_usr) or die("Could not return data from Connect1.");
	$count = mysql_num_rows($res_usr);

	if ($count > 0)
	{
		$myrow = mysql_fetch_array($res_usr);
		$ret_usr = $myrow["USER"];
		
		$sql_getusr = "select CENTER from PSWRDS where RESID=$ret_usr";
		$res_getusr = mysql_query_decide($sql_getusr) or die("Could not return data from Pswrds.");
		$count_usr = mysql_num_rows($res_getusr);
		
		if($count_usr > 0)
		{
			$myrow_usr = mysql_fetch_array($res_getusr);
			$ret = $myrow_usr["CENTER"];
		}
		else
		{
			$ret = FALSE;
		}
	}
	else
	{
		$ret = FALSE;
	}

return $ret;
}

function getemail($connection, $ip)
{

	
	global $TOUT;

	$sql_usr = "select USER from CONNECT where ID=$connection and IPADDR='$ip'";
	$res_usr = mysql_query_decide($sql_usr) or die("Could not return data from Connect1.");
	$count = mysql_num_rows($res_usr);

	if ($count > 0)
	{
		$myrow = mysql_fetch_array($res_usr);
		$ret_usr = $myrow["USER"];
		
		$sql_getusr = "select EMAIL from PSWRDS where RESID=$ret_usr";
		$res_getusr = mysql_query_decide($sql_getusr) or die("Could not return data from Pswrds.");
		$count_usr = mysql_num_rows($res_getusr);
		
		if($count_usr > 0)
		{
			$myrow_usr = mysql_fetch_array($res_getusr);
			$ret = $myrow_usr["EMAIL"];
		}
		else
		{
			$ret = FALSE;
		}
	}
	else
	{
		$ret = FALSE;
	}

return $ret;
}

function current_date()
{
	$tm = date("Y-m-d",time());
	return $tm;
}

function checkemail($email)     // returns 1 if email id not valid
{
        $flag=0;
        if( trim($email) =='')
        {
                $flag=1;
        }
        elseif (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i", $email))
        {
                $flag=1;
    	}
        else
        {
                $flag=0;
        }
                                                                                                                             
    return $flag;
}


function create_dd($selected,$cname,$minormax=0,$labelselect="")
{
                                                                                                 
        if(is_array($selected))
        {
                $s_arr = $selected;
                //$selected = array();
        }
        elseif($selected!="")
        {
                $s_arr=explode(",",$selected);
        }
        else
                $s_arr=array();
                                                                                                 
        $muli ="[]";
	if ($cname == "City_India")
	{
		$sql = "SELECT SQL_CACHE VALUE, LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = 51 ORDER BY SORTBY";
		$res = mysql_query_decide($sql);
		$ret = "";
		while($myrow = mysql_fetch_array($res))
		{
			if(in_array($myrow["VALUE"],$s_arr))
			{
				$ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
			}
			else
			{
                        	$ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
			}
		}
	}
	if ($cname == "Bank")
        {
                $sql = "select SQL_CACHE NAME from billing.BANK";
                $res = mysql_query_decide($sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["NAME"],$s_arr))
                        {
                                $ret .= "<option value=\"$myrow[NAME]\" selected>$myrow[NAME]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[NAME]\">$myrow[NAME]</option>\n";
                        }
                }
        }
	if ($cname == "Walkin")
        {
                $sql = "select SQL_CACHE USERNAME from billing.PSWRDS where PRIVILAGE='B'";
                $res = mysql_query_decide($sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["USERNAME"],$s_arr))
                        {
                                $ret .= "<option value=\"$myrow[USERNAME]\" selected>$myrow[USERNAME]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[USERNAME]\">$myrow[USERNAME]</option>\n";                        }
                }
        }

	return $ret;
}
                                                                                                 
                                                                                                 

?>
