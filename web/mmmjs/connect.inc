<?php
$TOUT = 14400;	
include_once(JsConstants::$smartyDir);
//include_once("../jsadmin/handleCrmLogin.php");

// include wrapper for logging
include_once(JsConstants::$docRoot."/classes/LoggingWrapper.class.php");
$smarty=new Smarty;
$smarty->setTemplateDir(JsConstants::$alertDocRoot."/mmmjs/templates");
$smarty->setCompileDir(JsConstants::$alertDocRoot."/mmmjs/templates_c");

define("SDIR","mmmnew");
$USER_NO = $_COOKIE["USER_NO"];

$SITE_URL=JsConstants::$siteUrl;
$smarty->assign("SITE_URL",$SITE_URL);	

$db = mysql_connect(MysqlDbConstants::$alerts[HOST].":".MysqlDbConstants::$alerts[PORT],MysqlDbConstants::$alerts[USER],MysqlDbConstants::$alerts[PASS]) or die(mysql_error());
mysql_select_db("mmmjs",$db);
mysql_query("set session wait_timeout=1000",$db);


function connect_db_99($dbname)
{
        global $SITE_URL,$servername,$db_username,$db_password,$file_path;
        $SITE_URL="http://www.99acres.com";
        $file_path="/usr/local/apache2/htdocs";
        $db=@mysql_connect(MysqlDbConstants::$MMM_99[HOST].":".MysqlDbConstants::$MMM_99[PORT],MysqlDbConstants::$MMM_99[USER],MysqlDbConstants::$MMM_99[PASS]) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","","ShowErrTemplate");
        @mysql_select_db($dbname,$db);
        return $db;
}


//*********PROPERTY TYPE ARRAY FOR SERACH PURPOSE*********

                $PROPERTY_TYPE[1]["VALUE"]="R";
                $PROPERTY_TYPE[1]["LABEL"]="All Residential";
                $PROPERTY_TYPE[2]["VALUE"]=1;
                $PROPERTY_TYPE[2]["LABEL"]="Residential Apartment";
                $PROPERTY_TYPE[3]["VALUE"]=2;
                $PROPERTY_TYPE[3]["LABEL"]="Independent House";
                $PROPERTY_TYPE[4]["VALUE"]=3;
                $PROPERTY_TYPE[4]["LABEL"]="Residential Land";
                $PROPERTY_TYPE[5]["VALUE"]=4;
                $PROPERTY_TYPE[5]["LABEL"]="Builder Floor";
                $PROPERTY_TYPE[6]["VALUE"]=5;
                $PROPERTY_TYPE[6]["LABEL"]="Farm House";
                $PROPERTY_TYPE[7]["VALUE"]=22;
                $PROPERTY_TYPE[7]["LABEL"]="Serviced Apartments";
                $PROPERTY_TYPE[8]["VALUE"]=80;
                $PROPERTY_TYPE[8]["LABEL"]="Other Residential";
                $PROPERTY_TYPE[9]["VALUE"]="C";
                $PROPERTY_TYPE[9]["LABEL"]="All Commercial";
                $PROPERTY_TYPE[10]["VALUE"]=6;
                $PROPERTY_TYPE[10]["LABEL"]="Commercial Shops";
                $PROPERTY_TYPE[11]["VALUE"]=6;
                $PROPERTY_TYPE[11]["LABEL"]="Commercial Showrooms";
                $PROPERTY_TYPE[12]["VALUE"]=7;
                $PROPERTY_TYPE[12]["LABEL"]="Commercial Office";
                $PROPERTY_TYPE[13]["VALUE"]=9;
                $PROPERTY_TYPE[13]["LABEL"]="Commercial Land/Inst. Land";
                $PROPERTY_TYPE[14]["VALUE"]=10;
                $PROPERTY_TYPE[14]["LABEL"]="Hotel/Guest House";
                $PROPERTY_TYPE[15]["VALUE"]=11;
                $PROPERTY_TYPE[15]["LABEL"]="Time Share";
                $PROPERTY_TYPE[16]["VALUE"]=12;
                $PROPERTY_TYPE[16]["LABEL"]="Space in Retail Mall";
                $PROPERTY_TYPE[17]["VALUE"]=13;
                $PROPERTY_TYPE[17]["LABEL"]="Office in Business Park";
                $PROPERTY_TYPE[18]["VALUE"]=14;
                $PROPERTY_TYPE[18]["LABEL"]="Office in IT Park";
                $PROPERTY_TYPE[19]["VALUE"]=15;
                $PROPERTY_TYPE[19]["LABEL"]="Ware House";
                $PROPERTY_TYPE[20]["VALUE"]=17;
                $PROPERTY_TYPE[20]["LABEL"]="Cold Storage";
                $PROPERTY_TYPE[21]["VALUE"]=18;
                $PROPERTY_TYPE[21]["LABEL"]="Factory";
                $PROPERTY_TYPE[22]["VALUE"]=19;
                $PROPERTY_TYPE[22]["LABEL"]="Manufacturing";
                $PROPERTY_TYPE[23]["VALUE"]=21;
                $PROPERTY_TYPE[23]["LABEL"]="Business center";
                $PROPERTY_TYPE[24]["VALUE"]=81;
                $PROPERTY_TYPE[24]["LABEL"]="Other Commercial";
                $PROPERTY_TYPE[25]["VALUE"]="L";
                $PROPERTY_TYPE[25]["LABEL"]="Land";
                $PROPERTY_TYPE[26]["VALUE"]=3;
                $PROPERTY_TYPE[26]["LABEL"]="Residential Land";
                $PROPERTY_TYPE[27]["VALUE"]=20;
                $PROPERTY_TYPE[27]["LABEL"]="Agricultural/Farm Land";
                $PROPERTY_TYPE[28]["VALUE"]=9;
                $PROPERTY_TYPE[28]["LABEL"]="Commercial Land/Inst. Land";
                $PROPERTY_TYPE[29]["VALUE"]=16;
                $PROPERTY_TYPE[29]["LABEL"]="Industrial Lands/Plots";
/**************************************************************************************/


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

function getTimeStampBefore3Months()
{
        return time() - (30 * 24 * 60 * 60);
}

                /**
                *       Function        :get_new_mailers()
                *       Input           :Nothing
                *       Output          :Array of Mailers Id which are in new state
                *       Description     :Function will find all the mailers which are in new state
                **/
                                                                                                 
function get_new_mailers()
{
	$sql="SELECT MAILER_ID FROM MAIN_MAILER WHERE STATUS='n'";
        $result=mysql_query($sql) or die("Could not connect to Main Mailer");
        while($row=mysql_fetch_array($result))
        {
        	$arr[]=array("mailer_id"=>$row[MAILER_ID]);
                                                                                                 
	}
                                                                                                
        return $arr;
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

function getcenter_for_walkin($walkin)
{ 
        $sql_usr = "select center from jsadmin.PSWRDS where USERNAME='$walkin' ";
                                                                                                                             
        $res_usr = mysql_query($sql_usr) or die("Could not return data from executive.".mysql_error());
                                                                                                                             
        $count = mysql_num_rows($res_usr);
                                                                                                                             
        if ($count > 0)
        {
                $myrow_usr = mysql_fetch_array($res_usr);
                $ret = $myrow_usr["center"];
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
                $sql = "SELECT SQL_CACHE VALUE, LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = 51 ORDER BY SORTBY";
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

	/***********New keywords added by Puneet Chawla in November 2006 to incorporate 99acres mailers in mmm******/

	if($cname=="99age")
        {
		$AGE[1]["VALUE"]=1;
	        $AGE[1]["LABEL"]="0-5 Years";
        	$AGE[2]["VALUE"]=2;
	        $AGE[2]["LABEL"]="5-10 Years";
	        $AGE[3]["VALUE"]=3;
        	$AGE[3]["LABEL"]="10-20 Years";
        	$AGE[4]["VALUE"]=4;
	        $AGE[4]["LABEL"]="20 Years plus";
	
                for($i=1;$i<=count($AGE);$i++)
                {
                        if(in_array($AGE[$i]["VALUE"],$s_arr))
                        {
                                $ret .= "<option value=".$AGE[$i]['VALUE']." selected>".$AGE[$i]['LABEL']."</option>";
                        }
                        else
                        {
                                 $ret .= "<option value=".$AGE[$i]['VALUE'].">".$AGE[$i]['LABEL']."</option>";
                        }
                }
        }
        if($cname=="99features")
        {
		unset($ret);
		$FEATURES[1]["VALUE"]=1;
	        $FEATURES[1]["LABEL"]="Swimming Pool";
        	$FEATURES[2]["VALUE"]=2;
	        $FEATURES[2]["LABEL"]="Power Back-up";
        	$FEATURES[3]["VALUE"]=3;
		$FEATURES[3]["LABEL"]="Club House";
	        $FEATURES[4]["VALUE"]=9;
        	$FEATURES[4]["LABEL"]="Security";
	        $FEATURES[5]["VALUE"]=4;
        	$FEATURES[5]["LABEL"]="Reserved Parking";
	        $FEATURES[6]["VALUE"]=5;
        	$FEATURES[6]["LABEL"]="Feng Shui/ Vaastu Compliant";
	        $FEATURES[7]["VALUE"]=6;
        	$FEATURES[7]["LABEL"]="Park";
	        $FEATURES[8]["VALUE"]=7;
        	$FEATURES[8]["LABEL"]="Servant Quarters";
	        $FEATURES[9]["VALUE"]=8;
        	$FEATURES[9]["LABEL"]="Private Garden/ Terrace";

                for($i=1;$i<=count($FEATURES);$i++)
                {
                        if(in_array($FEATURES[$i]["VALUE"],$s_arr))
                        {
                                $ret .= "<option value=".$FEATURES[$i]['VALUE']." selected>".$FEATURES[$i]['LABEL']."</option>";
                        }
                        else
                        {
                                $ret .= "<option value=".$FEATURES[$i]['VALUE'].">".$FEATURES[$i]['LABEL']."</option>";
                        }
                }
        }
	if($cname=="99features_commercial")
        {
		unset($ret);
		$FEATURES_COMMERCIALS[1]["VALUE"]=2;
	        $FEATURES_COMMERCIALS[1]["LABEL"]="Power Back-up";
        	$FEATURES_COMMERCIALS[2]["VALUE"]=3;
	        $FEATURES_COMMERCIALS[2]["LABEL"]="Club House";
        	$FEATURES_COMMERCIALS[3]["VALUE"]=9;
	        $FEATURES_COMMERCIALS[3]["LABEL"]="Security";
        	$FEATURES_COMMERCIALS[4]["VALUE"]=4;
	        $FEATURES_COMMERCIALS[4]["LABEL"]="Reserved Parking";
        	$FEATURES_COMMERCIALS[5]["VALUE"]=10;
	        $FEATURES_COMMERCIALS[5]["LABEL"]="Centrally Air Conditioned";
        	$FEATURES_COMMERCIALS[6]["VALUE"]=11;
	        $FEATURES_COMMERCIALS[6]["LABEL"]="ATMs";
        	$FEATURES_COMMERCIALS[7]["VALUE"]=12;
	        $FEATURES_COMMERCIALS[7]["LABEL"]="Fitness Center";
        	$FEATURES_COMMERCIALS[8]["VALUE"]=13;
	        $FEATURES_COMMERCIALS[8]["LABEL"]="Food Court";

                for($i=1;$i<=count($FEATURES_COMMERCIALS);$i++)
                {
                        if(in_array($FEATURES_COMMERCIALS[$i]["VALUE"],$s_arr))
                        {
                                 $ret .= "<option value=".$FEATURES_COMMERCIALS[$i]['VALUE']." selected>".$FEATURES_COMMERCIALS[$i]['LABEL']."</option>";
                        }
                        else
                        {
                                  $ret .= "<option value=".$FEATURES_COMMERCIALS[$i]['VALUE'].">".$FEATURES_COMMERCIALS[$i]['LABEL']."</option>";
                        }
                }
        }

	if($cname=="99facing")
        {
		$FACING[1]["VALUE"]=1;
	        $FACING[1]["LABEL"]="North";
        	$FACING[2]["VALUE"]=2;
	        $FACING[2]["LABEL"]="South";
        	$FACING[3]["VALUE"]=3;
	        $FACING[3]["LABEL"]="East";
        	$FACING[4]["VALUE"]=4;
	        $FACING[4]["LABEL"]="West";

                for($i=1;$i<=count($FACING);$i++)
                {
                        if(in_array($FACING[$i]["VALUE"],$s_arr))
                        {
                                $ret .= "<option value=".$FACING[$i]['VALUE']." selected>".$FACING[$i]['LABEL']."</option>";
                        }
                        else
                        {
                                 $ret .= "<option value=".$FACING[$i]['VALUE'].">".$FACING[$i]['LABEL']."</option>";
                        }
                }
        }

	if($cname=="99furnishing")
        {
		$FURNISHING[1]["VALUE"]=1;
	        $FURNISHING[1]["LABEL"]="Furnished";
        	$FURNISHING[2]["VALUE"]=4;
	        $FURNISHING[2]["LABEL"]="Semifurnished";
        	$FURNISHING[3]["VALUE"]=2;
	        $FURNISHING[3]["LABEL"]="Unfurnished";

                for($i=1;$i<=count($FURNISHING);$i++)
                {
                        if(in_array($FURNISHING[$i]["VALUE"],$s_arr))
                        {
                                $ret .= "<option value=".$FURNISHING[$i]['VALUE']." selected>".$FURNISHING[$i]['LABEL']."</option>";
                        }
                        else
                        {
                                 $ret .= "<option value=".$FURNISHING[$i]['VALUE'].">".$FURNISHING[$i]['LABEL']."</option>";
                        }
                }
        }

	if($cname=="99owntype")
        {
		$OWNTYPE[1]["VALUE"]=1;
	        $OWNTYPE[1]["LABEL"]="Freehold";
        	$OWNTYPE[2]["VALUE"]=2;
	        $OWNTYPE[2]["LABEL"]="Leasehold";
        	$OWNTYPE[3]["VALUE"]=3;
	        $OWNTYPE[3]["LABEL"]="Co-operative Society";
        	$OWNTYPE[4]["VALUE"]=4;
	        $OWNTYPE[4]["LABEL"]="Power of Attorney";

                for($i=1;$i<=count($OWNTYPE);$i++)
                {
                        if(in_array($OWNTYPE[$i]["VALUE"],$s_arr))
                        {
                                $ret .= "<option value=".$OWNTYPE[$i]['VALUE']." selected>".$OWNTYPE[$i]['LABEL']."</option>";
                        }
                        else
                        {
                                 $ret .= "<option value=".$OWNTYPE[$i]['VALUE'].">".$OWNTYPE[$i]['LABEL']."</option>";
                        }
                }
        }

	if($cname=="99transact_type")
        {
	        $TRANSACT_TYPE[1]["VALUE"]=1;
	        $TRANSACT_TYPE[1]["LABEL"]="Resale";
        	$TRANSACT_TYPE[2]["VALUE"]=2;
	        $TRANSACT_TYPE[2]["LABEL"]="New Property";

                for($i=1;$i<=count($TRANSACT_TYPE);$i++)
                {
                        if(in_array($TRANSACT_TYPE[$i]["VALUE"],$s_arr))
                        {
                                $ret .= "<option value=".$TRANSACT_TYPE[$i]['VALUE']." selected>".$TRANSACT_TYPE[$i]['LABEL']."</option>";
                        }
                        else
                        {
                                 $ret .= "<option value=".$TRANSACT_TYPE[$i]['VALUE'].">".$TRANSACT_TYPE[$i]['LABEL']."</option>";
                        }
                }
        }


	if($cname == "99area_range")
	{
	        $AREA_RANGE[1]["VALUE"]=1;
	        $AREA_RANGE[1]["LABEL"]="Less then 500";
        	$AREA_RANGE[2]["VALUE"]=2;
	        $AREA_RANGE[2]["LABEL"]="500 - 1000";
        	$AREA_RANGE[3]["VALUE"]=3;
	        $AREA_RANGE[3]["LABEL"]="1000 - 1500";
        	$AREA_RANGE[4]["VALUE"]=4;
	        $AREA_RANGE[4]["LABEL"]="1500 - 2000";
        	$AREA_RANGE[5]["VALUE"]=5;
	        $AREA_RANGE[5]["LABEL"]="2000 - 2500";
        	$AREA_RANGE[6]["VALUE"]=6;
	        $AREA_RANGE[6]["LABEL"]="2500 - 3000";
        	$AREA_RANGE[7]["VALUE"]=7;
	        $AREA_RANGE[7]["LABEL"]="3000 - 4000";
        	$AREA_RANGE[8]["VALUE"]=8;
	        $AREA_RANGE[8]["LABEL"]="4000 - 5000";
        	$AREA_RANGE[9]["VALUE"]=9;
	        $AREA_RANGE[9]["LABEL"]="5000 - 6000";
        	$AREA_RANGE[10]["VALUE"]=10;
	        $AREA_RANGE[10]["LABEL"]="6000 - 10000";
        	$AREA_RANGE[11]["VALUE"]=11;
	        $AREA_RANGE[11]["LABEL"]="More then 10000";

		for($i=1;$i<=count($AREA_RANGE);$i++)
                {
                        if(in_array($AREA_RANGE[$i]["VALUE"],$s_arr))
                        {
                                $ret .= "<option value=".$AREA_RANGE[$i]['VALUE']." selected>".$AREA_RANGE[$i]['LABEL']."</option>";
                        }
                        else
                        {
                                 $ret .= "<option value=".$AREA_RANGE[$i]['VALUE'].">".$AREA_RANGE[$i]['LABEL']."</option>";
                        }
                }
	}
	if($cname=="99monthly_budget")
	{
		$MONTHLY_BUDGET[1]["VALUE"]=1;
	        $MONTHLY_BUDGET[1]["LABEL"]="Below 4000";
        	$MONTHLY_BUDGET[2]["VALUE"]=2;
	        $MONTHLY_BUDGET[2]["LABEL"]="4,000 to 6,000";
        	$MONTHLY_BUDGET[3]["VALUE"]=3;
	        $MONTHLY_BUDGET[3]["LABEL"]="6,000 to 10,000";
        	$MONTHLY_BUDGET[4]["VALUE"]=4;
	        $MONTHLY_BUDGET[4]["LABEL"]="10,000 to 15,000";
        	$MONTHLY_BUDGET[5]["VALUE"]=5;
	        $MONTHLY_BUDGET[5]["LABEL"]="15,000 to 20,000";
        	$MONTHLY_BUDGET[6]["VALUE"]=6;
	        $MONTHLY_BUDGET[6]["LABEL"]="20,000 to 25,000";
        	$MONTHLY_BUDGET[7]["VALUE"]=7;
	        $MONTHLY_BUDGET[7]["LABEL"]="25,000 to 40,000";
        	$MONTHLY_BUDGET[8]["VALUE"]=8;
	        $MONTHLY_BUDGET[8]["LABEL"]="40,000 to 70,000";
        	$MONTHLY_BUDGET[9]["VALUE"]=9;
	        $MONTHLY_BUDGET[9]["LABEL"]="70,000 to 1 lac";
        	$MONTHLY_BUDGET[10]["VALUE"]=10;
	        $MONTHLY_BUDGET[10]["LABEL"]="1 lac to 1.5 lacs" ;
        	$MONTHLY_BUDGET[11]["VALUE"]=11;
	        $MONTHLY_BUDGET[11]["LABEL"]="1.5 lac to 2 lacs" ;
        	$MONTHLY_BUDGET[12]["VALUE"]=12;
	        $MONTHLY_BUDGET[12]["LABEL"]="2 lac to 5 lacs" ;
        	$MONTHLY_BUDGET[13]["VALUE"]=13;
	        $MONTHLY_BUDGET[13]["LABEL"]="5 lac to 10 lacs" ;
        	$MONTHLY_BUDGET[14]["VALUE"]=14;
	        $MONTHLY_BUDGET[14]["LABEL"]="Above 10 lacs" ;
        	$MONTHLY_BUDGET[15]["VALUE"]=15;
	        $MONTHLY_BUDGET[15]["LABEL"]="Negotiable";

		for($i=1;$i<=count($MONTHLY_BUDGET);$i++)
                {
                        if(in_array($MONTHLY_BUDGET[$i]["VALUE"],$s_arr))
                        {
                                $ret .= "<option value=".$MONTHLY_BUDGET[$i]['VALUE']." selected>".$MONTHLY_BUDGET[$i]['LABEL']."</option>";
                        }
                        else
                        {
                                 $ret .= "<option value=".$MONTHLY_BUDGET[$i]['VALUE'].">".$MONTHLY_BUDGET[$i]['LABEL']."</option>";
                        }
                }

	}

	if($cname=="99buying_budget")
	{
		$BUYING_BUDGET[1]["VALUE"]=1;
	        $BUYING_BUDGET[1]["LABEL"]="Below 7 Lacs";
        	$BUYING_BUDGET[2]["VALUE"]=2;
	        $BUYING_BUDGET[2]["LABEL"]="7 to 15 Lacs";
        	$BUYING_BUDGET[3]["VALUE"]=3;
	        $BUYING_BUDGET[3]["LABEL"]="15 to 25 Lacs";
        	$BUYING_BUDGET[4]["VALUE"]=4;
	        $BUYING_BUDGET[4]["LABEL"]="25 to 40 Lacs";
        	$BUYING_BUDGET[5]["VALUE"]=5;
	        $BUYING_BUDGET[5]["LABEL"]="40 to 60 Lacs";
        	$BUYING_BUDGET[6]["VALUE"]=6;
	        $BUYING_BUDGET[6]["LABEL"]="60 to 100 Lacs";
 	        $BUYING_BUDGET[7]["VALUE"]=7;
	        $BUYING_BUDGET[7]["LABEL"]="1 to 1.5 Crores";
        	$BUYING_BUDGET[8]["VALUE"]=8;
	        $BUYING_BUDGET[8]["LABEL"]="1.5 to 2 Crores";
        	$BUYING_BUDGET[9]["VALUE"]=9;
	        $BUYING_BUDGET[9]["LABEL"]="2 to 2.5 Crores";
        	$BUYING_BUDGET[10]["VALUE"]=12;
		$BUYING_BUDGET[10]["LABEL"]="2.5 to 5 Crores";
		$BUYING_BUDGET[11]["VALUE"]=13;
		$BUYING_BUDGET[11]["LABEL"]="5 to 10 Crores";
		$BUYING_BUDGET[12]["VALUE"]=14;
		$BUYING_BUDGET[12]["LABEL"]="10 to 15 Crores";
		$BUYING_BUDGET[13]["VALUE"]=15;
		$BUYING_BUDGET[13]["LABEL"]="15 to 20 Crores";
		$BUYING_BUDGET[14]["VALUE"]=16;
		$BUYING_BUDGET[14]["LABEL"]="20 to 25 Crores";
		$BUYING_BUDGET[15]["VALUE"]=17;
		$BUYING_BUDGET[15]["LABEL"]="25 to 30 Crores";
		$BUYING_BUDGET[16]["VALUE"]=18;
		$BUYING_BUDGET[16]["LABEL"]="30 to 35 Crores";
		$BUYING_BUDGET[17]["VALUE"]=19;
		$BUYING_BUDGET[17]["LABEL"]="35 to 40 Crores";
		$BUYING_BUDGET[18]["VALUE"]=20;
		$BUYING_BUDGET[18]["LABEL"]="40 to 45 Crores";
		$BUYING_BUDGET[19]["VALUE"]=21;
		$BUYING_BUDGET[19]["LABEL"]="45 to 50 Crores";
		$BUYING_BUDGET[20]["VALUE"]=22;
		$BUYING_BUDGET[20]["LABEL"]="50 to 55 Crores";
		$BUYING_BUDGET[21]["VALUE"]=23;
		$BUYING_BUDGET[21]["LABEL"]="55 to 60 Crores";
		$BUYING_BUDGET[22]["VALUE"]=24;
		$BUYING_BUDGET[22]["LABEL"]="60 to 65 Crores";
		$BUYING_BUDGET[23]["VALUE"]=25;
		$BUYING_BUDGET[23]["LABEL"]="65 to 70 Crores";
		$BUYING_BUDGET[24]["VALUE"]=26;
		$BUYING_BUDGET[24]["LABEL"]="70 to 75 Crores";
		$BUYING_BUDGET[25]["VALUE"]=27;
		$BUYING_BUDGET[25]["LABEL"]="75 to 80 Crores";
		$BUYING_BUDGET[26]["VALUE"]=28;
		$BUYING_BUDGET[26]["LABEL"]="80 to 85 Crores";
		$BUYING_BUDGET[27]["VALUE"]=29;
		$BUYING_BUDGET[27]["LABEL"]="85 to 90 Crores";
		$BUYING_BUDGET[28]["VALUE"]=30;
		$BUYING_BUDGET[28]["LABEL"]="90 to 95 Crores";
		$BUYING_BUDGET[29]["VALUE"]=31;
		$BUYING_BUDGET[29]["LABEL"]="95 to 100 Crores";
		$BUYING_BUDGET[30]["VALUE"]=10;
		$BUYING_BUDGET[30]["LABEL"]="Above 100 Crores";
        	$BUYING_BUDGET[31]["VALUE"]=11;
	        $BUYING_BUDGET[31]["LABEL"]="Negotiable";

                for($i=1;$i<=count($BUYING_BUDGET);$i++)
                {
                        if(in_array($BUYING_BUDGET[$i]["VALUE"],$s_arr))
                        {
                                $ret .= "<option value=".$BUYING_BUDGET[$i]['VALUE']." selected>".$BUYING_BUDGET[$i]['LABEL']."</option>";
                        }
                        else
                        {
                                 $ret .= "<option value=".$BUYING_BUDGET[$i]['VALUE'].">".$BUYING_BUDGET[$i]['LABEL']."</option>";
                        }
                }
	}

	if($cname=="99property_type")
	{
		//*********PROPERTY TYPE ARRAY FOR SERACH PURPOSE*********

		global $PROPERTY_TYPE;

		for($i=1;$i<=count($PROPERTY_TYPE);$i++)
                {
                        if(in_array($PROPERTY_TYPE[$i]["VALUE"],$s_arr))
                        {
                                $ret .= "<option value=".$PROPERTY_TYPE[$i]['VALUE']." selected>".$PROPERTY_TYPE[$i]['LABEL']."</option>";
                        }
                        else
                        {
                                 $ret .= "<option value=".$PROPERTY_TYPE[$i]['VALUE'].">".$PROPERTY_TYPE[$i]['LABEL']."</option>";
                        }
                }

	}

	if($cname=="99monthly_income")
        {
		$MONTHLY_INCOME[1]["VALUE"]=1;
	        $MONTHLY_INCOME[1]["LABEL"]="Less than 7000";
        	$MONTHLY_INCOME[2]["VALUE"]=2;
	        $MONTHLY_INCOME[2]["LABEL"]="7001-10000";
        	$MONTHLY_INCOME[3]["VALUE"]=3;
	        $MONTHLY_INCOME[3]["LABEL"]="10001-15000";
        	$MONTHLY_INCOME[4]["VALUE"]=4;
	        $MONTHLY_INCOME[4]["LABEL"]="15001-25000";
        	$MONTHLY_INCOME[5]["VALUE"]=5;
	        $MONTHLY_INCOME[5]["LABEL"]="25001-40000";
        	$MONTHLY_INCOME[6]["VALUE"]=6;
	        $MONTHLY_INCOME[6]["LABEL"]="Above 40000";

                for($i=1;$i<=count($MONTHLY_INCOME);$i++)
                {
                        if(in_array($MONTHLY_INCOME[$i]["VALUE"],$s_arr))
                        {
                                $ret .= "<option value=".$MONTHLY_INCOME[$i]['VALUE']." selected>".$MONTHLY_INCOME[$i]['LABEL']."</option>";
                        }
                        else
                        {
                                 $ret .= "<option value=".$MONTHLY_INCOME[$i]['VALUE'].">".$MONTHLY_INCOME[$i]['LABEL']."</option>";
                        }
                }
        }


	if($cname=="city99")
        {
		$db99=connect_db_99("property");
		$sql = "select SQL_CACHE VALUE, LABEL, FLAG from property.CITY order by SORTBY";
                $res = mysql_query($sql,$db99) or logError("error",$sql);
		mysql_close($db99);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {

                        if($myrow[FLAG] == 'Y'||$myrow[FLAG] == 'S'||$myrow[LABEL]=="International")
                                $class = "class='boldclass'";
                        else
                                 $class = "";
                                if(in_array($myrow["VALUE"],$s_arr))
                                {
                                        $ret .= "<option value=\"$myrow[VALUE]\" $class selected>$myrow[LABEL]</option>";
                                  }
                                else
                                {
                                        $ret .= "<option value=\"$myrow[VALUE]\" $class>$myrow[LABEL]</option>";
                                 }
                }

        }

	if($cname=="99sales_executives")
        {
/*		$db99=connect_db_99("property");
                $query="select USERNAME from sums.PSWRDS WHERE ACTIVE='Y' AND ROLE IN('S','B') order by USERNAME";

                $result = mysql_query($query,$db99) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$query,"ShowErrTemplate");
		mysql_close($db99);
                $ret="";
                while($row=mysql_fetch_array($result))
                {
                        if(in_array($row['USERNAME'],$s_arr))
                        {
                                $ret.="<option value=\"".$row['USERNAME']."\" selected>".$row['USERNAME']."</option>";
                        }
                        else
                        {
                                $ret.="<option value=\"".$row['USERNAME']."\"  >".$row['USERNAME']."</option>";
                        }
                }*/
        }

	/******************************99acres keywords end here**************************************/


	
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
        $sql = "SELECT SQL_CACHE VALUE, LABEL,TYPE FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = 51 ORDER BY SORTBY";
        $res = mysql_query($sql) or die("$sql".mysql_error());//logError("error",$sql);
        $ret = "";
        while($myrow = mysql_fetch_array($res))
        {
                if(in_array($myrow["VALUE"],$s_arr))
                {
                        $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
			if($myrow["TYPE"]=="STATE")
				$ret .= "<option value=\"Rest of $myrow[VALUE]\">Rest of $myrow[LABEL]</option>\n";
                }
                else
                {
                        $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
			if($myrow["TYPE"]=="STATE")
	                        $ret .= "<option value=\"Rest of $myrow[VALUE]\">Rest of $myrow[LABEL]</option>\n";
                }
        }
    }
    
  	if ($cname == "City_USA")
    {
        $sql = "SELECT SQL_CACHE VALUE, LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = 128 ORDER BY SORTBY";
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

function getIncome()
{
	$sql = "select SQL_CACHE VALUE, LABEL from newjs.INCOME order by SORTBY";
	$res = mysql_query($sql) or die("$sql".mysql_error());//logError("error",$sql);
	$i = 0;
	while($myrow = mysql_fetch_array($res))
	{
		$income[$i]["VALUE"] = $myrow["VALUE"];
		$income[$i]["LABEL"] = $myrow["LABEL"];
		$i++;
	}
	return $income;
}

function get_all_caste($caste)
{
	//REVAMP JS_DB_CASTE
include_once(JsConstants::$docRoot."/commonFiles/RevampJsDbFunctions.php");
        return get_all_caste_revamp_js_db($caste,'',1);
        //REVAMP JS_DB_CASTE
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


function logError($message,$query="",$critical="exit", $sendmailto="NO")
{
        global $db, $smarty, $checksum;
        ob_start();
        var_dump($_SERVER);
        $ret_val = ob_get_contents();
        ob_end_clean();

        $errorstring="echo \"" . date("Y-m-d G:i:s",time() + 37800) . "\nErrorMsg: $message\nMysql Error: " . addslashes(mysql_error()) ."\nMysql Error Number:". mysql_errno()."\nSQL: $query\n#User Agent : " . $_SERVER['HTTP_USER_AGENT'] . "\n #Referer : " . $_SERVER['HTTP_REFERER'] . " \n #Self :  ".$_SERVER['PHP_SELF']."\n #Uri : ".$_SERVER['REQUEST_URI']."\n #Method : ".$_SERVER['REQUEST_METHOD']."\n";
        // for 120
        $errorstring.="\" >> ".JsConstants::$alertDocRoot."jsadmin/temp/logerror.txt";

        //for resman
        //$errorstring.="\" >> /usr/local/apache/sites/site2/manager/logs/resmgr.err
        passthru($errorstring);
		LoggingWrapper::getInstance()->sendLog(LoggingEnums::LOG_ERROR, new Exception($errorstring));
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
