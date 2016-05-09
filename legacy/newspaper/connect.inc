<?php

$TOUT = 9000;	// the timeout value in seconds
//include("/usr/local/apache/sites/jeevansathi.com/htdocs/smarty/Smarty.class.php");
include(JsConstants::$smartyDir);

/*
	$db = @mysql_connect("192.168.2.206","user","Km7Iv80l") or die("Can't connect to Database".mysql_error());
	mysql_select_db("jsadmin",$db);
*/ 

$smarty = new Smarty;
$smarty->template_dir="templates";
$smarty->compile_dir="templates_c";
//$smarty->relative_dir = "live/promotions/";

//$SITE_URL="http://192.168.2.220/live/promotions";

//$SITE_URL="http://203.92.45.230/jeevansathi/live/promotions";
//$SITE_URL="http://172.16.0.175/newspaper/";
//$SITE_URL="http://192.168.2.234/newspaper/";
//$SITE_URL="http://".$_SERVER["SERVER_ADDR"]."/newspaper/";
$SITE_URL="/newspaper/";
$smarty->assign("SITE_URL",$SITE_URL);

function dbsql2_connect()
{
        //$db = @mysql_connect("localhost:/tmp/mysql2.sock","root","Km7Iv80l") or die("Can't connect to Database".mysql_error());
	$db = @mysql_connect("10.208.67.37","user","CLDLRTa9") or die("Can't connect to Database".mysql_error());
        mysql_select_db("jsadmin",$db) or die(mysql_error());
        return $db;
}


function db_connect()
{
	 $db = @mysql_connect("10.208.67.37","user","CLDLRTa9") or die("Can't connect to Database".mysql_error());
	mysql_select_db("jsadmin",$db) or die(mysql_error());
	return $db;   
}


function login ($id, $pass)
{
	global	$TOUT;	// global variables
	$mycond = 0;

	$sql = "select RESID from PSWRDS where USERNAME= binary '$id' and PASSWORD= binary '$pass'";
	$res = mysql_query($sql) or die("Could not Execute query pwdError");
	$count = mysql_num_rows($res);

	
	if ($count > 0)
	{
		$myrow = mysql_fetch_array($res);
		$recid = $myrow["RESID"];
		$tm = time();
		$sql="delete from CONNECT where TIME < ($tm - $TOUT)";
		$result = mysql_query($sql);
	
		$sqlup="insert into CONNECT (ID, USER, IPADDR, TIME) values ('','$recid','$ip','$tm')";
                $sqlup_con = mysql_query($sqlup) or die($sqlup." : ".mysql_error());

		$id=mysql_insert_id();
                                                                                                                             
	        $ret=md5($id)."i".$id;
                                                                                                                             

	}
	else
	{
		$ret = 0;
	}
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
	$res_chk = mysql_query($sql_chk) or die("Could not Authenticate User: auError $sql_chk ".mysql_error());
	$count = mysql_num_rows($res_chk);

	if ($count > 0)
	{
		$myrow = mysql_fetch_array($res_chk);

		if (time()-$myrow["TIME"] < $TOUT)
		{
			$tm = time();
			$sql_up = "update CONNECT set TIME=$tm where ID=$userno";
			$res_up = mysql_query($sql_up) or die("Could not Authenticate User: upError");

			$ret["ID"] = $md."i".$userno;
                        $ret["USER"]=$myrow["USER"];
                                
			//$ret = TRUE;
		}
		else
		{
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
		$res_chk = mysql_query($sql_chk);

		if ($res_chk)
			$ret = TRUE;
		else
			$ret = FALSE;
	}
return $ret;
}

function getuser($checksum, $ip="")
{
	global $TOUT;

        list($md, $userno)=explode("i",$checksum);
        if(md5($userno)!=$md)
            return FALSE;
        else
        {                                                                                                                    
		$sql_usr = "select USER from CONNECT where ID=$userno";
		$res_usr = mysql_query($sql_usr) or die("Could not return data from Connect1. $sql_usr ".mysql_error());
		$count = mysql_num_rows($res_usr);

		if ($count > 0)
		{
			$myrow = mysql_fetch_array($res_usr);
			$ret_usr = $myrow["USER"];
		
			$sql_getusr = "select USERNAME from PSWRDS where RESID=$ret_usr";
			$res_getusr = mysql_query($sql_getusr) or die("Could not return data from Pswrds.");
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
		$res_usr = mysql_query($sql_usr) or die("Could not return data from Connect1.");
		$count = mysql_num_rows($res_usr);

		if ($count > 0)
		{
			$myrow = mysql_fetch_array($res_usr);
			$ret_usr = $myrow["USER"];
		
			$sql_getusr = "select PRIVILAGE from PSWRDS where RESID=$ret_usr";
			$res_getusr = mysql_query($sql_getusr) or die("Could not return data from Pswrds.");
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
        elseif (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email))
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
        $db	= db_connect();
	$db2	= dbsql2_connect();

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

        if ($cname == "branch")
        {
                $sql = "select SQL_CACHE VALUE, NAME from newjs.BRANCHES";
                $res = mysql_query($sql,$db) or die(mysql_error());
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
 if(in_array($myrow["VALUE"],$s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[NAME]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[NAME]</option>\n";
                        }
                }
        }

        if ($cname == "privilege")
        {
                $sql = "select SQL_CACHE VALUE , LABEL from jsadmin.PRIVILAGE";
                $res = mysql_query($sql,$db2);
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

	if ($cname == "Caste")
        {
                $sql = "select SQL_CACHE VALUE, LABEL from newjs.CASTE";
                $res = mysql_query($sql,$db) or die(mysql_error());
                $ret="";
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

	if ($cname == "Source")
        {
                $sql = "select SQL_CACHE VALUE, LABEL from NEWSPPR_SOURCE order by SORTBY";
                $res = mysql_query($sql,$db2) or die(mysql_error());
                $ret="";
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
	if ($cname == "Country")
        {
                $sql = "select SQL_CACHE VALUE, LABEL from newjs.TOP_COUNTRY order by SORTBY";
                $res = mysql_query($sql,$db) or logError("error",$sql);
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

	if ($cname == "City")
        {
                $sql = "SELECT SQL_CACHE VALUE, LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = 51 ORDER BY SORTBY";
                $res = mysql_query($sql,$db) or die(mysql_error());
                $ret="";
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

                                                                                      
        return $ret;
}

function label_select($columnname,$value,$database="")
{
	$db = dbsql2_connect();
	if($database=="")
		$database="newjs";

        $sql = "select SQL_CACHE LABEL from $database.$columnname WHERE VALUE='$value'";
		
        $res = mysql_query($sql,$db) or logError("error",$sql) ;
        $myrow= mysql_fetch_row($res);
	
        return $myrow;
}

function logError($message,$query="",$critical="exit", $sendmailto="NO")
{
        global $db, $smarty, $checksum;
        ob_start();
        var_dump($_SERVER);
        $ret_val = ob_get_contents();
        ob_end_clean();

        $errorstring="echo \"" . date("Y-m-d G:i:s",time() + 37800) . "\nErrorMsg: $message\nMysql Error: " . addslashes(mysql_error()) ."\nMysql Error Number:". mysql_errno()."\nSQL: $query\n#User Agent : " . $_SERVER['HTTP_USER_AGENT'] . "\n #Referer : " . $_SERVER['HTTP_REFERER'] . " \n #Self :  ".$_SERVER['PHP_SELF']."\n #Uri : ".$_SERVER['REQUEST_URI']."\n #Method : ".$_SERVER['REQUEST_METHOD']."\n";
        // for 120
        $errorstring.="\" >> /usr/local/apache/sites/jeevansathi.com/htdocs/jsadmin/temp/logerror.txt";

        //for resman
        //$errorstring.="\" >> /usr/local/apache/sites/site2/manager/logs/resmgr.err
        passthru($errorstring);
        $errorstring.="\n#Details : $ret_val";
    if($sendmailto!="NO")
                $b=mail($sendmailto,"ERROR in new resman connect", $errorstring);


        if($critical=="exit")
        {
                echo $message;
                exit;
        }
        elseif($critical=="ShowErrTemplate")
        {
                $smarty->assign("CHECKSUM",$checksum);
                $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
                $smarty->assign("HEAD",$smarty->fetch("head.htm"));
                $smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
                $smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));

                $smarty->assign("msg_error", $message);
		$smarty->display("error_template.htm");
                exit;
        }
        elseif($critical!="continue")
        {
                echo $message;
        }

}

function getname($cid)
{
	$db=dbsql2_connect();
        $temp=explode("i",$cid);
        $userid=$temp[1];
        $sql="SELECT USER FROM jsadmin.CONNECT WHERE ID='$userid'";
        $res=mysql_query($sql,$db) or die(mysql_error());
        $row=mysql_fetch_array($res);
        $id=$row['USER'];
                                                                                                                             
        $sql="SELECT USERNAME FROM jsadmin.PSWRDS WHERE RESID='$id'";
        $res=mysql_query($sql,$db) or die(mysql_error());
        $row=mysql_fetch_array($res);
        $username=$row['USERNAME'];
        return $username;
}

?>
