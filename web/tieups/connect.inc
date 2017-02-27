<?php

$TOUT = 14400;   // the timeout value in seconds
include(JsConstants::$smartyDir);

include_once("../jsadmin/handleCrmLogin.php");

if(!$active_db)
        $active_db = "";
if(!$previous_db)
        $previous_db = "";
if(!$db_211)
        $db_211="";
include_once(JsConstants::$docRoot."/commonFiles/mysql_multiple_connections.php");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include(JsConstants::$docRoot."/../lib/model/lib/FieldMapLib.class.php");

$smarty=new Smarty;
$smarty->setTemplateDir(JsConstants::$docRoot."/smarty/templates/tieups");
$smarty->setCompileDir(JsConstants::$docRoot."/smarty/templates_c");

$SITE_URL=JsConstants::$siteUrl;
$smarty->assign("SITE_URL",$SITE_URL);

function connect_db()
{
	$db=db_set_active("master",MysqlDbConstants::$master[HOST],MysqlDbConstants::$master[USER],MysqlDbConstants::$master[PASS]) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","","ShowErrTemplate");
        @mysql_select_db("tieups",$db);
        return $db;
}

function connect_slave()
{
	$db=db_set_active("slave",MysqlDbConstants::$misSlave[HOST],MysqlDbConstants::$misSlave[USER],MysqlDbConstants::$misSlave[PASS]) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","","ShowErrTemplate");
        @mysql_select_db("tieups",$db);
        return $db;
}

function login ($id, $pass)
{
        global  $TOUT;  // global variables
	global $db2;
        $mycond = 0;
		if($id=="marketing.tieups.admin" && $pass=="Esha.Meenakshi")
		{
			$id=-1;
			return $ret=md5($id)."i".$id;
		}
		else
		{
			$sql = "select RESID from tieups.PSWRDS where USERNAME='$id' and PASSWORD='$pass'";
			$res = mysql_query_decide($sql,$db2) or die("Could not Execute query pwdError");
			$count = mysql_num_rows($res);


			if ($count > 0)
			{
					$myrow = mysql_fetch_array($res);
					$recid = $myrow["RESID"];
					$tm = time();
					$sql="delete from tieups.CONNECT where TIME < ($tm - $TOUT)";
					$result = mysql_query_decide($sql,$db2);

					$sqlup="insert into tieups.CONNECT (ID, USER, IPADDR, TIME) values ('','$recid','$ip','$tm')";
					$sqlup_con = mysql_query_decide($sqlup,$db2) or die($sqlup." : ".mysql_error_js());

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
}

function authenticated($checksum="")
{
        global $TOUT;
	global $db2;

        list($md, $userno)=explode("i",$checksum);

        if(md5($userno)!=$md)
            return NULL;
        else
        {
			if($userno==-1){
				return true;
			}
			else{
					$sql_chk = "select * from tieups.CONNECT where ID=$userno";
					$res_chk = mysql_query_decide($sql_chk,$db2) or die("Could not Authenticate User: auError $sql_chk ".mysql_error_js());
					$count = mysql_num_rows($res_chk);
					if ($count > 0)
					{
						$myrow = mysql_fetch_array($res_chk);

						if (time()-$myrow["TIME"] < $TOUT)
						{
							$tm = time();
							$sql_up = "update tieups.CONNECT set TIME=$tm where ID=$userno";
							$res_up = mysql_query_decide($sql_up,$db2) or die("Could not Authenticate User: upError");

							$ret["ID"] = $md."i".$userno;
							$ret["USER"]=$myrow["USER"];
							$cna = preg_replace('/[^A-Za-z0-9\. -_]/', '', $_COOKIE["CRM_NOTIFICATION_AGENT"]);
                            				setLoginCookies($ret["ID"],$cna);
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
}
function logout($checksum)
{
        global $TOUT;
	global $db2;

        list($md, $userno)=explode("i",$checksum);
        if(md5($userno)!=$md)
            return FALSE;
        else
        {
                $sql_chk = "delete from tieups.CONNECT where ID='$userno'";
                $res_chk = mysql_query_decide($sql_chk,$db2);

                if ($res_chk)
                        $ret = TRUE;
                else
                        $ret = FALSE;
        }
return $ret;
}
                                                                                                 
function create_dd($selected,$cname,$minormax=0,$labelselect="")
{
	global $db2;
                                                                                                 
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
  if ($cname == "Source")
        {
                $sql = "select SQL_CACHE SourceName, SourceID from MIS.SOURCE";
                $res = mysql_query_decide($sql,$db2);
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["SourceID"],$s_arr))
                        {
                                $ret .= "<option value=\"$myrow[SourceID]\" selected>$myrow[SourceName]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[SourceID]\">$myrow[SourceName]</option>\n";                        }
                }
        }
        return $ret;
}
                                                                                                 
function my_format_date($day,$month,$year)
{
        if($month=="01" || $month=="1")
                $month="January";
        elseif($month=="02" || $month=="2")
                $month="February";
        elseif($month=="03" || $month=="3")
                $month="March";
        elseif($month=="04" || $month=="4")
                $month="April";
        elseif($month=="05" || $month=="5")
                $month="May";
        elseif($month=="06" || $month=="6")
                $month="June";
        elseif($month=="07" || $month=="7")
                $month="July";
        elseif($month=="08" || $month=="8")
                $month="August";
        elseif($month=="09" || $month=="9")
                $month="September";
        elseif($month=="10")
                $month="October";
        elseif($month=="11")
                $month="November";
        else
                $month="December";

        if(strlen($day)==1)
                $day= "0" . $day;

        return $month . " " . $day . ", " . $year;
}

function current_date()
{
        $tm = date("Y-m-d",time());
        return $tm;
}

function getuser($checksum, $ip)
{
        global $TOUT;
	global $db2;

        list($md, $userno)=explode("i",$checksum);
        if(md5($userno)!=$md)
            return FALSE;
        else
        {
                $sql_usr = "select USER from tieups.CONNECT where ID=$userno";
                $res_usr = mysql_query_decide($sql_usr,$db2) or die("Could not return data from Connect1. $sql_usr ".mysql_error_js());
                $count = mysql_num_rows($res_usr);

                if ($count > 0)
                {
                        $myrow = mysql_fetch_array($res_usr);
                        $ret_usr = $myrow["USER"];

                        $sql_getusr = "select USERNAME from tieups.PSWRDS where RESID=$ret_usr";
                        $res_getusr = mysql_query_decide($sql_getusr,$db2) or die("Could not return data from Pswrds.");
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
	global $db2;

        list($md, $userno)=explode("i",$checksum);
        if(md5($userno)!=$md)
            return FALSE;
        else
        {
			if($userno==-1){
				return $privilage="admin";
			}
			else{
                $sql_usr = "select USER from tieups.CONNECT where ID=$userno";
                $res_usr = mysql_query_decide($sql_usr,$db2) or die("Could not return data from Connect1.");
                $count = mysql_num_rows($res_usr);

                if ($count > 0)
				{
					$myrow = mysql_fetch_array($res_usr);
					$ret_usr = $myrow["USER"];

					$sql_getusr = "select PRIVILAGE from tieups.PSWRDS where RESID=$ret_usr";
					$res_getusr = mysql_query_decide($sql_getusr,$db2) or die("Could not return data from Pswrds.");
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
}

function get_group($checksum)
{
	global $TOUT;
	global $db2;

        list($md, $userno)=explode("i",$checksum);
        if(md5($userno)!=$md)
            return FALSE;
        else
        {
			if($userno==-1){
				$sql_chk = "select DISTINCT GROUPNAME from MIS.SOURCE";
				$res_chk = mysql_query_decide($sql_chk,$db2) or die("Could not Authenticate User: auError $sql_chk ".mysql_error_js());
				while($row=mysql_fetch_array($res_chk))
				{
					$sourceGroupStr.=$row['GROUPNAME'].",";
				}
				$sourceGroupStr=trim($sourceGroupStr,",");
				return $sourceGroupStr;
			}
			else
			{
                $sql_usr = "select USER from tieups.CONNECT where ID=$userno";
                $res_usr = mysql_query_decide($sql_usr,$db2) or die("Could not return data from Connect1.");
                $count = mysql_num_rows($res_usr);

                if ($count > 0)
				{
					$myrow = mysql_fetch_array($res_usr);
					$ret_usr = $myrow["USER"];

					$sql_getusr = "select GROUPNAME from tieups.PSWRDS where RESID=$ret_usr";
					$res_getusr = mysql_query_decide($sql_getusr,$db2) or die("Could not return data from
					Pswrds.");
					$count_usr = mysql_num_rows($res_getusr);
                    if($count_usr > 0)
					{
						$myrow_usr = mysql_fetch_array($res_getusr);
						$ret = $myrow_usr["GROUPNAME"];
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
}


function getcenter($connection, $ip)
{


	global $db2;
        global $TOUT;

        $sql_usr = "select USER from tieups.CONNECT where ID=$connection and IPADDR='$ip'";
        $res_usr = mysql_query_decide($sql_usr,$db2) or die("Could not return data from Connect1.");
        $count = mysql_num_rows($res_usr);

        if ($count > 0)
        {
                $myrow = mysql_fetch_array($res_usr);
                $ret_usr = $myrow["USER"];

                $sql_getusr = "select CENTER from tieups.PSWRDS where RESID=$ret_usr";
                $res_getusr = mysql_query_decide($sql_getusr,$db2) or die("Could not return data from Pswrds.");
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

function getname($cid)
{
	global $db2;

        $temp=explode("i",$cid);
        $userid=$temp[1];
        $sql="SELECT USER FROM tieups.CONNECT WHERE ID='$userid'";
        $res=mysql_query_decide($sql,$db2) or die(mysql_error_js());
        $row=mysql_fetch_array($res);
        $id=$row['USER'];

        $sql="SELECT USERNAME FROM tieups.PSWRDS WHERE RESID='$id'";
        $res=mysql_query_decide($sql,$db2) or die(mysql_error_js());
        $row=mysql_fetch_array($res);
        $username=$row['USERNAME'];
        return $username;
}
?>
