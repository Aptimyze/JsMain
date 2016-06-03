<?php

include_once(JsConstants::$alertDocRoot.'/msmjs/config/config.php');
$TOUT = 14400;	

//include_once("../jsadmin/handleCrmLogin.php");

$smarty->assign("siteUrl",JsConstants::$siteUrl);

function getAuthenticationRoutine($cid)
{

$ip = FetchClientIP();
if(authenticated($cid))
{
        $un = getuser($cid,$ip);
        $tm=getIST();
	return true;
}
else
	return false;

}
function connect_slave()
{
	$dbs = mysql_connect(MysqlDbConstants::$alerts[HOST].":".MysqlDbConstants::$alerts[PORT],MysqlDbConstants::$alerts[USER],MysqlDbConstants::$alerts[PASS]) or die(mysql_error());
	mysql_select_db("newjs",$dbs);
        return $dbs;
}


function getmailername($mailer_id)
{
	$sql="select MAILER_NAME from MAIN_MAILER where MAILER_ID='$mailer_id'";
	$result=mysql_query($sql) or die("Could not connect to Main Mailer");
        $row=mysql_fetch_array($result);
	$mailer_name=$row['MAILER_NAME'];
	return $mailer_name;

}




                /**
                *       Function        :getIST()
                *       Input           :Nothing
                *       Output          :IST time
                *       Description     :Converts server time to IST time
                **/
                                                                                                 
function getIST()
{
                                                                                                 
	//gmt date
        $gmt = gmdate("H-i-s-m-d-Y");
        $ga = explode("-",$gmt);
                                                                                                 
        $gmtm = mktime($ga[0],$ga[1],$ga[2],$ga[3],$ga[4],$ga[5]);
                                                                                                
        //adding 5:30 mins to gmt to get ist
        $toadd = 19800;
                                                                                                
        $retime = $gmtm + $toadd;
        return $retime;
}        

                /**
                *       Function        :get_new_mailers()
                *       Input           :Nothing
                *       Output          :Array of Mailers Id which are in new state
                *       Description     :Function will find all the mailers which are in new state
                **/
                                                                                                 
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
				//setLoginCookies($ret["ID"],$_COOKIE["CRM_NOTIFICATION_AGENT"]);
				//$ret = TRUE;
			}
			else
			{
				//unsetLoginCookies();
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
		
			$sql_getusr = "select USERNAME from jsadmin.PSWRDS where RESID=$ret_usr";
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
		
			$sql_getusr = "select PRIVILAGE from jsadmin.PSWRDS where RESID=$ret_usr";
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

function getcenter($connection, $ip)
{ 
	global $TOUT;
        list($md, $userno)=explode("i",$checksum);
        if(md5($userno)!=$md)
            return FALSE;
	else
	{

		$sql_usr = "select USER from CONNECT where ID=$connection and IPADDR='$ip'";
		$res_usr = mysql_query($sql_usr) or die("Could not return data from Connect1.");
		$count = mysql_num_rows($res_usr);
	
		if ($count > 0)
		{
			$myrow = mysql_fetch_array($res_usr);
			$ret_usr = $myrow["USER"];
		
			$sql_getusr = "select CENTER from jsadmin.PSWRDS where RESID=$ret_usr";
			$res_getusr = mysql_query($sql_getusr) or die("Could not return data from Pswrds.");
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
	}
	return $ret;
}


function getemail($connection, $ip)
{
	global $TOUT;

	$sql_usr = "select USER from CONNECT where ID=$connection and IPADDR='$ip'";
	$res_usr = mysql_query($sql_usr) or die("Could not return data from Connect1.");
	$count = mysql_num_rows($res_usr);

	if ($count > 0)
	{
		$myrow = mysql_fetch_array($res_usr);
		$ret_usr = $myrow["USER"];
		
		$sql_getusr = "select EMAIL from jsadmin.PSWRDS where RESID=$ret_usr";
		$res_getusr = mysql_query($sql_getusr) or die("Could not return data from Pswrds.");
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

/*
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
                $sql = "SELECT SQL_CACHE VALUE, LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE =51 ORDER BY SORTBY";
                $res = mysql_query($sql);
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
        return $ret;
}
*/

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


	
	if ($cname == "top_country")
	{
		$sql = "select SQL_CACHE VALUE, LABEL from newjs.TOP_COUNTRY order by SORTBY";
		$res = mysql_query($sql) or die("$sql".mysql_error());//logError("error",$sql);
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
	
	if ($cname == "Religion")
	{
		$sql = "select SQL_CACHE VALUE, LABEL from newjs.RELIGION order by SORTBY";
		$res = mysql_query($sql) or die("$sql".mysql_error());//logError("error",$sql);
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
		$sql = "select SQL_CACHE VALUE, LABEL from newjs.CASTE order by SORTBY";
		$res = mysql_query($sql) or die("$sql".mysql_error());//logError("error",$sql);
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

	if ($cname == "Mtongue")
	{	
		if($labelselect=="small")
			$sql = "select SQL_CACHE VALUE, SMALL_LABEL from newjs.MTONGUE order by SORTBY";
		else
			$sql = "select SQL_CACHE VALUE, LABEL from newjs.MTONGUE order by SORTBY";
		$res = mysql_query($sql) or die("$sql".mysql_error());//logError("error",$sql);
		$ret = "";
		while($myrow = mysql_fetch_array($res))
		{
			if(in_array($myrow["VALUE"],$s_arr))
			{
				if($labelselect=="small")
					$ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[SMALL_LABEL]</option>\n";
				else				
					$ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
			}
			else
			{
				if($labelselect=="small")
					$ret .= "<option value=\"$myrow[VALUE]\">$myrow[SMALL_LABEL]</option>\n";
				else
					$ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
			}
		}
	}

	if ($cname == "Family_Back")
	{
		$sql = "select SQL_CACHE VALUE, LABEL from newjs.FAMILY_BACK order by SORTBY";
		$res = mysql_query($sql) or die("$sql".mysql_error());//logError("error",$sql) ;
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
		$ret .= "";
	}

	if ($cname == "Country_Residence")
	{
		$sql = "select SQL_CACHE VALUE, LABEL from newjs.COUNTRY order by SORTBY";
		$res = mysql_query($sql) or die("$sql".mysql_error());//logError("error",$sql);
		$ret = "";
		while($myrow = mysql_fetch_array($res))
		{
			if(in_array($myrow["VALUE"], $s_arr))
			{
				$ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
			}
			else
			{
				$ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
			}
		}
	}

	if ($cname == "Height")
	{
		$sql = "select SQL_CACHE VALUE, LABEL from newjs.HEIGHT order by SORTBY";
		if($minormax ==1)
			$sql .= " desc";
		$res = mysql_query($sql) or die("$sql".mysql_error());//logError("error",$sql);
		$ret = "";
		while($myrow = mysql_fetch_array($res))
		{
			if(in_array($myrow["VALUE"], $s_arr))
			{
				$ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
			}
			else
			{
				$ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
			}
		}
	}
	
	if ($cname == "Occupation")
	{
		$sql = "select SQL_CACHE VALUE, LABEL from newjs.OCCUPATION order by SORTBY";
		$res = mysql_query($sql) or die("$sql".mysql_error());//logError("error",$sql);
		$ret = "";
		while($myrow = mysql_fetch_array($res))
		{
			if(in_array($myrow["VALUE"], $s_arr))
			{
				$ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
			}
			else
			{
				$ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
			}
		}
	}

	if ($cname == "Income")
	{
		$sql = "select SQL_CACHE VALUE, LABEL from newjs.INCOME order by SORTBY";
		$res = mysql_query($sql) or die("$sql".mysql_error());//logError("error",$sql);
		$ret = "";
		while($myrow = mysql_fetch_array($res))
		{
			if(in_array($myrow["VALUE"], $s_arr))
			{
				$ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
			}
			else
			{
				$ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
			}
		}
	}
	
	if ($cname == "Country_Birth")
	{
		$sql = "select SQL_CACHE VALUE, LABEL from newjs.COUNTRY order by SORTBY";
		$res = mysql_query($sql) or die("$sql".mysql_error());//logError("error",$sql);
		$ret = "";
		while($myrow = mysql_fetch_array($res))
		{
			if(in_array($myrow["VALUE"], $s_arr))
			{
				$ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
			}
			else
			{
				$ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
			}
		}
	}

  	if ($cname == "City_India")
    {
        $sql = "SELECT SQL_CACHE VALUE, LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE =51 ORDER BY SORTBY";
        $res = mysql_query($sql) or die("$sql".mysql_error());//logError("error",$sql);
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
    
  	if ($cname == "City_USA")
    {
        $sql = "SELECT SQL_CACHE VALUE, LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE =128 ORDER BY SORTBY";
        $res = mysql_query($sql) or die("$sql".mysql_error());//logError("error",$sql);
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

	if ($cname == "Education_Level")
	{
		$sql = "select SQL_CACHE VALUE, LABEL from newjs.EDUCATION_LEVEL order by SORTBY";
		$res = mysql_query($sql) or die("$sql".mysql_error());//logError("error",$sql);
		$ret = "";
		while($myrow = mysql_fetch_array($res))
		{
			if(in_array($myrow["VALUE"], $s_arr))
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


function get_all_caste($caste)
{
        $insert_caste=implode("','", $caste);
        $castesql="select SQL_CACHE VALUE,PARENT,ISALL,ISGROUP,GROUPID from newjs.CASTE where VALUE in
('$insert_caste')";
        $casteResult=mysql_query($castesql) or die("$castesql".mysql_error());//logError($ERROR_STRING,$castesql,"ShowErrTemplate");
        while($casterow=mysql_fetch_array($casteResult))
        {
                if($casterow["ISALL"]=="Y")
                {
                        $castesql="select SQL_CACHE VALUE from newjs.CASTE where PARENT='" . $casterow["PARENT"] . "'";
                        $totalCaste=mysql_query($castesql) or die("$castesql".mysql_error());//logError($ERROR_STRING,$castesql,"ShowErrTemplate");
                                                                                                 
                        while($totalCasterow=mysql_fetch_array($totalCaste))
                        {
                                $Caste_arr[]=$totalCasterow["VALUE"];
                        }
                }
                elseif($casterow["ISGROUP"]=="Y")
                {
                        $castesql="select SQL_CACHE VALUE from newjs.CASTE where GROUPID='" . $casterow["GROUPID"] . "'";
                        $totalCaste=mysql_query($castesql) or die("$castesql".mysql_error());//logError($ERROR_STRING,$castesql,"ShowErrTemplate");
                        
                                                                         
                        while($totalCasterow=mysql_fetch_array($totalCaste))
                        {
                                $Caste_arr[]=$totalCasterow["VALUE"];
                        }
                }
                else
                        $Caste_arr[]=$casterow["VALUE"];
        }
                                                                                                 
        return array_unique($Caste_arr);
}



function label_select($columnname,$value,$database="")
{
	if($database=="")
		$database="newjs";

        $sql = "select SQL_CACHE LABEL from $database.$columnname WHERE VALUE='$value'";
        $res = mysql_query($sql) or die("$sql".mysql_error());//logError("error",$sql) ;
        $myrow= mysql_fetch_array($res);
        return $myrow;
}

function getname($cid)
{
        $temp=explode("i",$cid);
        $userid=$temp[1];
        $sql="SELECT USER FROM CONNECT WHERE ID='$userid'";
        $res=mysql_query($sql) or die(mysql_error());
        $row=mysql_fetch_array($res);
        $id=$row['USER'];
                                                                                                                             
        $sql="SELECT USERNAME FROM jsadmin.PSWRDS WHERE RESID='$id'";
        $res=mysql_query($sql) or die(mysql_error());
        $row=mysql_fetch_array($res);
        $username=$row['USERNAME'];
        return $username;
}


function get_centre($cid)
{
        $temp=explode("i",$cid);
        $userid=$temp[1];
        $sql="SELECT USER FROM CONNECT WHERE ID='$userid'";
        $res=mysql_query($sql) or die(mysql_error());
        $row=mysql_fetch_array($res);
        $id=$row['USER'];
                                                                                                 
                                                                                                 
        $sql="SELECT CENTER FROM jsadmin.PSWRDS WHERE RESID='$id'";
        $res=mysql_query($sql) or die(mysql_error());
        $row=mysql_fetch_array($res);
        $centre=$row['CENTER'];
        return $centre;
}

?>
