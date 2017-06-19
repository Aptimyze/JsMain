<?php
exit;
/*********************************************************************************************
* FILE NAME     : connect.inc
* DESCRIPTION   : Has common functions required for Wedding Directory
* CREATION DATE : 3 September, 2005
* CREATED BY    : SHAKTI SRIVASTAVA
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

//include("/usr/local/apache/sites/jeevansathi.com/htdocs/smarty/Smarty.class.php");
include(JsConstants::$smartyDir);
if(!$active_db)
	$active_db = "";
if(!$previous_db)
	$previous_db = "";
if(!$db_211)
        $db_211="";
include_once(JsConstants::$docRoot."/profile/mysql_multiple_connections.php");

$smarty=new Smarty;

$smarty->relative_dir="wedding/";

//$SITE_URL="http://192.168.2.220";
$SITE_URL="http://www.jeevansathi.com";
$smarty->assign("SITE_URL",$SITE_URL);

function connect_db()
{
	$db=@db_set_active("master","10.208.67.37","user","CLDLRTa9") or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","","ShowErrTemplate");
	@mysql_select_db_js("wedding_classifieds",$db);
	return $db;
}

function connect_slave()
{
	$db=@db_set_active("master","10.208.64.206","user","CLDLRTa9") or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","","ShowErrTemplate");
	@mysql_select_db_js("wedding_classifieds",$db);
	return $db;
}

function check_numeric($string)
{
	if(!eregi("^[0-9]+$", $string));
		return 1;
}

function label_select($columnname,$value,$database="")
{
	if($database=="")
		$database="newjs";

        $sql = "select SQL_CACHE LABEL from $database.$columnname WHERE VALUE='$value'";
        $res = mysql_query_decide($sql) or logError("error",$sql) ;
        $myrow= mysql_fetch_row($res);
        return $myrow;
                                                                                                 
}

function get_dd($tablename)
{
	$sql = "select SQL_CACHE VALUE from $tablename order by SORTBY";
	$res = mysql_query_decide($sql) or logError("error",$sql) ;
	while($myrow =mysql_fetch_array($res))
	{
		$coursearr[] =$myrow[VALUE];
	}		
	return $coursearr;
	
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
	
	if ($cname == "top_country")
	{
		$sql = "select SQL_CACHE VALUE, LABEL from TOP_COUNTRY order by SORTBY";
		$res = mysql_query_decide($sql) or logError("error",$sql);
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
		$sql = "select SQL_CACHE VALUE, LABEL from RELIGION order by SORTBY";
		$res = mysql_query_decide($sql) or logError("error",$sql);
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
		$sql = "select SQL_CACHE VALUE, LABEL from CASTE order by SORTBY";
		$res = mysql_query_decide($sql) or logError("error",$sql);
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
			$sql = "select SQL_CACHE VALUE, SMALL_LABEL from MTONGUE order by SORTBY";
		else
			$sql = "select SQL_CACHE VALUE, LABEL from MTONGUE order by SORTBY";
		$res = mysql_query_decide($sql) or logError("error",$sql);
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
		$sql = "select SQL_CACHE VALUE, LABEL from FAMILY_BACK order by SORTBY";
		$res = mysql_query_decide($sql) or logError("error",$sql) ;
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
		$sql = "select SQL_CACHE VALUE, LABEL from COUNTRY order by SORTBY";
		$res = mysql_query_decide($sql) or logError("error",$sql);
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
		$sql = "select SQL_CACHE VALUE, LABEL from HEIGHT order by SORTBY";
		if($minormax ==1)
			$sql .= " desc";
		$res = mysql_query_decide($sql) or logError("error",$sql);
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
		$sql = "select SQL_CACHE VALUE, LABEL from OCCUPATION order by SORTBY";
		$res = mysql_query_decide($sql) or logError("error",$sql);
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
		// Change made by Rahul Tara to display only visible values for INCOME dropdown	
		$sql = "select SQL_CACHE VALUE, LABEL from INCOME where VISIBLE <> 'N' order by SORTBY";
		$res = mysql_query_decide($sql) or logError("error",$sql);
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
		$sql = "select SQL_CACHE VALUE, LABEL from COUNTRY order by SORTBY";
		$res = mysql_query_decide($sql) or logError("error",$sql);
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
        $sql = "SELECT SQL_CACHE VALUE, LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = 51 ORDER BY SORTBY";
        $res = mysql_query_decide($sql) or logError("error",$sql);
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
        $sql = "SELECT SQL_CACHE VALUE, LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = 128 ORDER BY SORTBY";
        $res = mysql_query_decide($sql) or logError("error",$sql);
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
		$sql = "select SQL_CACHE VALUE, LABEL from EDUCATION_LEVEL order by SORTBY";
		$res = mysql_query_decide($sql) or logError("error",$sql);
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
        if ($cname == "Education_Level_New")
        {
                $sql = "select SQL_CACHE VALUE, LABEL from EDUCATION_LEVEL_NEW order by SORTBY";
                $res = mysql_query_decide($sql) or logError("error",$sql);
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
        if ($cname == "Occupation_New")
        {
                $sql = "select SQL_CACHE VALUE, LABEL from OCCUPATION_NEW order by SORTBY";
                $res = mysql_query_decide($sql) or logError("error",$sql);
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
	if ($cname == "Income_Lacs")
        {
                $sql = "select SQL_CACHE VALUE, LABEL from INCOME_NEW WHERE TYPE='L' order by SORTBY";
                $res = mysql_query_decide($sql) or logError("error",$sql);
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
	if ($cname == "Income_Thousand")
        {
                $sql = "select SQL_CACHE VALUE, LABEL from INCOME_NEW WHERE TYPE='T'order by SORTBY";
                $res = mysql_query_decide($sql) or logError("error",$sql);
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

function get_old_value($value,$tablename)
{
	if($tablename=="EDUCATION_LEVEL_NEW")
	{
	        $sql="SELECT SQL_CACHE OLD_VALUE from $tablename WHERE VALUE=$value";
	        $result=mysql_query_decide($sql) or die("old");
		$myrow=mysql_fetch_array($result);
		$old=$myrow['OLD_VALUE'];
	}
	elseif($tablename=="OCCUPATION")
        {
                $sql="SELECT SQL_CACHE VALUE from $tablename WHERE NEW_VALUE=$value";
                $result=mysql_query_decide($sql) or die("old");
                $myrow=mysql_fetch_array($result);
                $old=$myrow['VALUE'];
        }
	return $old;
}
function get_old_income_value($Ctc_Type,$Ctc_Lacs,$Ctc_thousand) 
{
        if($Ctc_Type=="R")
        {
		if($Ctc_Lacs==0 && $Ctc_Thousand<=50)
			$old=1;
		if($Ctc_Lacs==0 && $Ctc_Thousand>=50 )
                        $old=2;
		if($Ctc_Lacs==1 && $Ctc_Thousand<=95)
                        $old=3;
		if($Ctc_Lacs==2 && $Ctc_Thousand<=95)
                        $old=4;
		if($Ctc_Lacs==3 && $Ctc_Thousand<=95)
                        $old=5;
		if($Ctc_Lacs==4 && $Ctc_Thousand<=95)
                        $old=6;
		if($Ctc_Lacs>=5 )
                        $old=7;
        }
	elseif($Ctc_Type=="D")
	{
                if($Ctc_Lacs==0 && $Ctc_Thousand<=25)
                        $old=8;
                if($Ctc_Lacs==0 && $Ctc_Thousand>=25 && $Ctc_Thousand<=50)
                        $old=9;
                if($Ctc_Lacs==0 && $Ctc_Thousand>=50 && $Ctc_Thousand<=75)
                        $old=10;
                if($Ctc_Lacs==0 && $Ctc_Thousand>=75 && $Ctc_Thousand<=95)
                        $old=11;
                if($Ctc_Lacs==1 && $Ctc_Thousand<=50)
                        $old=12;
                if($Ctc_Lacs==1 && $Ctc_Thousand>=50 && $Ctc_Thousand<=95)
                        $old=13;
                if($Ctc_Lacs>=2 )
                        $old=14;
	}
        return $old;
}


function populate_religion($sel_val=0)
{
	$sql="SELECT SQL_CACHE VALUE,LABEL from RELIGION ORDER BY SORTBY";
	$result=mysql_query_decide($sql);
	$j=0;
	$i=0;
	while($myrow=mysql_fetch_row($result))
	{
        	$strtemp = '';
	        $religion_value[]="$myrow[0]";
		$religion_label[]=$myrow[1];
	        $strtemp .= $religion_value[$j]."|X|";
        
		$sql="SELECT SQL_CACHE VALUE,LABEL from CASTE where PARENT='$myrow[0]' order by SORTBY";
	        $result1= mysql_query_decide($sql);
                                                                                                 
		 while($myrow1=mysql_fetch_row($result1))
	        {
        	        $caste_value[]="$myrow1[0]";
                	$caste_label[]="$myrow1[1]";
	                $strtemp .= $caste_value[$i]."$".$caste_label[$i]."#";
        	        $i++;
	        }
        	$strtemp = substr($strtemp,0,(strlen($strtemp)-1));
        	$j++;
		
	        $str[] = $strtemp;
	}

	for($x=0;$x<count($str);$x++)
	{
		$str_temp = explode('|X|',$str[$x]);
        	$str_val = $str_temp[0];

		if($sel_val == $str_val)
			$newstr.="<option value=\"" . $str[$x] . "\" selected>" . $religion_label[$x] . "</option>\n";
		else
			$newstr.="<option value=\"" . $str[$x] . "\">" . $religion_label[$x] . "</option>\n";
	}
	return $newstr;
}

function populate_caste($Caste)
{
	$ret='';
	//$str_value= explode("|X|",$Religion);
	//$rel_value=$str_value[0];
	//$cas_value=$Caste;
	$sql= "SELECT SQL_CACHE VALUE,LABEL from CASTE ORDER BY SORTBY";
	$result=mysql_query_decide($sql);
	$i=0;
	while($myrow=mysql_fetch_array($result))
	{
		if($myrow["VALUE"]== $Caste)
	        {
			$ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";

                }
                else
                {
                	$ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                }

	}
	return $ret;
}

function checkphone($phone)     // returns 1 if phone no. is not valid
{
        $flag=0;
        if( trim($phone) =='')
        {
                $flag=1;
        }
        elseif (!ereg("^[0-9()-/+]+$", $phone))
        {
		$flag=1;
        }

	return $flag;
}

function checkrphone($phone)     // returns 1 if phone no. is not valid
{
        $flag=0;
        $pos = strrpos($phone, ".");
        if($pos!==false)
        {
                $flag=1;
        }
        if( trim($phone) =='')
        {
                $flag=1;
        }
        elseif (!ereg("^[0-9()-/+ ,]+$", $phone))
        {
		$flag=1;
        }

	return $flag;
}

function checkmphone($phone)     // returns 1 if phone no. is not valid
{
        $flag=0;
        if( trim($phone) =='')
        {
                $flag=1;
        }
        elseif (!ereg("^[+]?[0-9]+$", $phone))
        {
		$flag=1;
        }
                                                                                
	return $flag;
}

function checkemail($email)	// returns 1 if email id not valid
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
	/*elseif( trim($email) !='')
	{
		$result = mysql_query_decide("SELECT EMAIL FROM newjs.JPROFILE where EMAIL='$email'") or logError("error",$sql);
        	$myrow = mysql_fetch_array($result);
    		$email1=$myrow["EMAIL"];
    		if($email1==$email)
    			$flag=2;
	}*/
	else
	{
		$flag=0;
	}
    	return $flag;
}
function checkoldemail($email)     // returns 1 if email id not valid
{
        $flag=0;
        if( trim($email) !='')
        {
                $result = mysql_query_decide("SELECT OLD_EMAIL FROM OLDEMAIL where OLD_EMAIL='$email'") or logError("error",$sql);
        	$myrow = mysql_fetch_array($result);
        	$email1=$myrow["OLD_EMAIL"];
        	if($email1==$email)
                	$flag=2;
        }
        else
        {
                $flag=0;
        }
    	return $flag;
}


function isvalid_username($string)
{
   	$invalidchar = array('#','\'','"','\\','/',' ','!','@','$','%','^','&','*','?');
 	  
   	for($i=0;$i<strlen($string);$i++)
	   	if(in_array($string[$i],$invalidchar))
		{
			$username_flag=1;
			return $username_flag;	
		}
		
	return 0;
}


function check_username($username)
{
	$username_flag=0;
	if( trim($username)=="" || strlen($username)<2 || strlen($username)>20)
    {
        $username_flag=1; //return 1 if Username is blank or not in 2-20 range
		return $username_flag;
	}
    else
	{

	// ** EDITED BY AMAN SHARMA FOR MAKING USERNAMES CASE-INSENSITIVE**

	//	$result = mysql_query_decide("SELECT USERNAME FROM  newjs.JPROFILE where USERNAME='$username'") or logError("error",$sql);
		
		$result = mysql_query_decide("SELECT USERNAME FROM  newjs.NAMES  where USERNAME='$username'") or logError("error",$sql);
		if(mysql_num_rows($result) > 0)
			return 2;// return 2 if username already exists
		/*
        $myrow = mysql_fetch_array($result);
        $username1=$myrow["USERNAME"];
        
    	if($username1==$username)
        {
    		$username_flag=2;//return 2 if Username already exists
			return $username_flag;
        }
        */
	}
           	
	return $username_flag;
}

function check_password($password1,$username)
{  
	$password_flag=0;
	if( $password1=="" || strlen($password1)<5 || strlen($password1)>40)
	{
        $password_flag=1; //return 1 if password1 is blank or not in range
		return $password_flag;
    }
    elseif($username==$password1) 
	{
		$password_flag=2; //return 2 if username=password1
		return $password_flag;
	}
	
	return $password_flag; //return 0 if password1 is correct
}

function confirm_password($password1,$password2)
{
	$password_flag=0;
	if($password1!=$password2)
    {
        $password_flag=1; //return 1 if passwords do not match
		return $password_flag;
    }
    
	return $password_flag;
}

function validate_date($day,$month,$year)
{
	// since jan equals one and not zero, hence thirteen elements in the array.  
	$no_of_days_in_month = array(0,31,28,31,30,31,30,31,31,30,31,30,31);
	if ($month >=1 && $month <=12 && $day >= 01 && $day <= 31 && $year >= 0)
	{
		//Handling february, special case. 
		if ($month == 2)
		{
			if ( ($year%4==0 && $year%100 != 0) || $year%400 == 0 )
			{
				$no_of_days_in_month[$month]=29;
			}
		}
		if ($day >=1 && $day <= $no_of_days_in_month[$month])
		{
			return 0;
		} 
		else
		{
			return 1;
		}
	}
	elseif($day=="" || $month=="" || $year=="")
    {
        return 1;
    }
    else
	{
		return 0;
	}
}

function logError($message,$query="",$critical="exit", $sendmailto="NO")
{
	global $_SERVER['HTTP_REFERER'],$_SERVER,$_SERVER['HTTP_USER_AGENT'], $db, $smarty, $checksum;
	//if(mysql_error_js())
	//	$sendmailto="vikas.jayna@naukri.com";

	ob_start();
 	var_dump($_SERVER);
 	$ret_val = ob_get_contents();
 	ob_end_clean();
	echo "<br>";
 	$errorstring="echo \"" . date("Y-m-d G:i:s",time() + 37800) . "\nErrorMsg: $message\nMysql Error: " . addslashes(mysql_error_js()) ."\nMysql Error Number:". mysql_errno_js()."\nSQL: $query\n#User Agent : $_SERVER['HTTP_USER_AGENT']\n #Referer : $_SERVER['HTTP_REFERER'] \n #Self :  ".$_SERVER['PHP_SELF']."\n #Uri : ".$_SERVER['REQUEST_URI']."\n #Method : ".$_SERVER['REQUEST_METHOD']."\n";
	// for 120
	$errorstring.="\" >> ".JsConstants::$docRoot."/wedding/logerror.txt";
	//$errorstring.="\" >> /usr/local/apache/sites/jeevansathi.com/htdocs/wedding/logerror.txt";

	passthru($errorstring);
	$errorstring.="\n#Details : $ret_val";
    if($sendmailto!="NO")
		$b=mail($sendmailto,"ERROR in jeevansathi", $errorstring);

	
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

function maStripVARS($action)
{ 
	global $_GET, $_POST;
	
  	if (get_magic_quotes_gpc() == 0)
  	{
  		if($action=="stripslashes")
  		{
  			if (is_array($_GET))
		  	{ 
		  		while(list($k,$v) = each($_GET))
		    	{ 
		    		if(!is_array($v))
		    		{
	    				$_GET[$k] = strip_tags(str_replace("\"","'",$v));
			      		$GLOBALS[$k] = strip_tags(str_replace("\"","'",$GLOBALS[$k]));
		    		}
		    	}
		    	reset($_GET);
		  	}
		  	if (is_array($_POST))
		  	{
		  		while(list($k,$v) = each($_POST))
		    	{ 
		    		if(!is_array($v))
		    		{
	    				$_POST[$k] = strip_tags(str_replace("\"","'",$v));
			      		$GLOBALS[$k] = strip_tags(str_replace("\"","'",$GLOBALS[$k]));
		    		}
		    	}
		    	reset($_POST);
		  	}
   			return;
  		}
  	}
  	else
  	{
  		if($action=="addslashes")
  		{
  			if (is_array($_GET))
		  	{ 
		  		while(list($k,$v) = each($_GET))
		    	{ 
		    		if(!is_array($v))
		    		{
	    				$_GET[$k] = strip_tags(str_replace("\"","'",$v));
			      		$GLOBALS[$k] = strip_tags(str_replace("\"","'",$GLOBALS[$k]));
		    		}
		    	}
		    	reset($_GET);
		  	}
		  	if (is_array($_POST))
		  	{
		  		while(list($k,$v) = each($_POST))
		    	{ 
		    		if(!is_array($v))
		    		{
	    				$_POST[$k] = strip_tags(str_replace("\"","'",$v));
			      		$GLOBALS[$k] = strip_tags(str_replace("\"","'",$GLOBALS[$k]));
		    		}
		    	}
		    	reset($_POST);
		  	}
  			return;
  		}
  	}
   		
 	if (is_array($_GET))
  	{ 
  		while(list($k,$v) = each($_GET))
    	{ 
    		if(!is_array($v))
    		{
    			if($action=="stripslashes")
    			{
		    		$_GET[$k] = strip_tags(str_replace("\"","'",stripslashes($v)));
		      		$GLOBALS[$k] = strip_tags(str_replace("\"","'",stripslashes($GLOBALS[$k])));
    			}
    			
    			if($action=="addslashes")
    			{
    				$_GET[$k] = strip_tags(str_replace("\"","'",addslashes($v)));
		      		$GLOBALS[$k] = strip_tags(str_replace("\"","'",addslashes($GLOBALS[$k])));
    			}
    		}
    	}
    	reset($_GET);
  	}
  	if (is_array($_POST))
  	{
  		while(list($k,$v) = each($_POST))
    	{ 
    		if(!is_array($v))
    		{
    			if($action=="stripslashes")
    			{
		    		$_POST[$k] = strip_tags(str_replace("\"","'",stripslashes($v)));
		      		$GLOBALS[$k] = strip_tags(str_replace("\"","'",stripslashes($GLOBALS[$k])));
    			}
    			
    			if($action=="addslashes")
    			{
    				$_POST[$k] = strip_tags(str_replace("\"","'",addslashes($v)));
		      		$GLOBALS[$k] = strip_tags(str_replace("\"","'",addslashes($GLOBALS[$k])));
    			}
    		}
    	}
    	reset($_POST);
  	}
}

//******* FUNCTION NAME :: multi_array_search()
//******* ADDED BY :: GAURAV ARORA
//******* ADDED ON :: 30 MAY 2005
//******* FUNCTION DESCRIPTION :: this function is used to search the index of a string in a multi dimension array

function multi_array_search($search_value, $the_array)
{
   if (is_array($the_array))
   {
       foreach ($the_array as $key => $value)
       {
           $result = multi_array_search($search_value, $value);
           if (is_array($result))
           {
               $return = $result;
               array_unshift($return, $key);
               return $return;
           }
           elseif ($result == true)
           {
               $return[] = $key;
               return $return;
           }
       }
       return false;
   }
   else
   {
       if ($search_value == $the_array)
       {
	   return true;
       }
       else return false;
   }
}
// end of function


function my_format_date($day,$month,$year)
{
	if($month=="01" || $month=="1")
		$month="Jan";
	elseif($month=="02" || $month=="2")
		$month="Feb";
	elseif($month=="03" || $month=="3")
		$month="Mar";
	elseif($month=="04" || $month=="4")
		$month="Apr";
	elseif($month=="05" || $month=="5")
		$month="May";
	elseif($month=="06" || $month=="6")
		$month="Jun";
	elseif($month=="07" || $month=="7")
		$month="Jul";
	elseif($month=="08" || $month=="8")
		$month="Aug";
	elseif($month=="09" || $month=="9")
		$month="Sep";
	elseif($month=="10")
		$month="Oct";
	elseif($month=="11")
		$month="Nov";
	else
		$month="Dec";
		
	if(strlen($day)==1)
		$day= "0" . $day;
		
	return $month . " " . $day . ", " . $year;
}

function getAge($newDob)
{
	$today=date("Y-m-d");
	$datearray=explode("-",$newDob);
	$todayArray=explode("-",$today);
	
	$years=($todayArray[0]-$datearray[0]);
	
	if(intval($todayArray[1]) < intval($datearray[1]))
		$years--;
	elseif(intval($todayArray[1]) == intval($datearray[1]) && intval($todayArray[2]) < intval($datearray[2]))
		$years--;
	
	return $years;
}

function checkemail1($email)
{
	if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email))
		return false;
    
    return true;
}

function get_rights($profileid)
{
	$sql="select SUBSCRIPTION from newjs.JPROFILE where PROFILEID='$profileid'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	
	if(mysql_num_rows($result)>0)
	{
		$myrow=mysql_fetch_array($result);
		$rights=explode(",",$myrow["SUBSCRIPTION"]);
		
		return $rights;
	}
	else 
		return array();
}

function get_all_caste($caste)
{
	//REVAMP JS_DB_CASTE
        include_once("../profile/RevampJsDbFunctions.php");
        return get_all_caste_revamp_js_db($caste,'',1);
        //REVAMP JS_DB_CASTE
}

function get_all_caste_rel_sort($caste,$caste_sel)
{
	mail("lavesh.rawat@jeevansathi.com,kumar.anand@jeevansathi.com","get_all_caste_rel_sort called from wedding/connect.inc","get_all_caste_rel_sort called from wedding/connect.inc");
	return $caste;
}

function get_all_city($City_Res)
{

	for($i=0;$i<count($City_Res);$i++)
        {
                if(!(is_int($City_Res[$i])))
                {
                        $sql_state_check="SELECT TYPE FROM CITY_NEW where VALUE='$City_Res[$i]'";
                        $result_state_check=mysql_query_decide($sql_state_check) or die($sql_state_check.mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_state_check,"ShowErrTemplate");
                        $myrow_state_check=mysql_fetch_array($result_state_check);
                        if($myrow_state_check['TYPE']=="STATE")
                        {
                                $sql_state="SELECT VALUE FROM CITY_NEW where VALUE like '$City_Res[$i]%'";
                                $result_state=mysql_query_decide($sql_state) or die($sql_state.mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_state,"ShowErrTemplate");
                                while($myrow_state=mysql_fetch_array($result_state))
                                        $City_Res2[]=$myrow_state['VALUE'];
                        }
                        else
                                $City_Res2[]=$City_Res[$i];
                }
        }
return $City_Res2;

}

function get_all_city_rel_sort($City_Res,$City_Res_temp)
{
//print_r($City_Res_temp);
//print_r($City_Res);
	for($i=0;$i<count($City_Res_temp);$i++)
	{
		$city_arr.="'".$City_Res_temp[$i]."',";
	}
	$city_arr=substr($city_arr,0,strlen($city_arr)-1);
	
	for($i=0;$i<count($City_Res);$i++)
        {
                if(!(is_int($City_Res[$i])))
                {
                        $sql_state_check="SELECT TYPE FROM CITY_NEW where VALUE='$City_Res[$i]'";
                        $result_state_check=mysql_query_decide($sql_state_check) or die($sql_state_check.mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_state_check,"ShowErrTemplate");
                        $myrow_state_check=mysql_fetch_array($result_state_check);
                        if($myrow_state_check['TYPE']=="STATE")
                        {
                                $sql_state="SELECT VALUE FROM CITY_NEW where VALUE like '$City_Res[$i]%' and VALUE not in ($city_arr)";
                                $result_state=mysql_query_decide($sql_state) or die($sql_state.mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_state,"ShowErrTemplate");
                                while($myrow_state=mysql_fetch_array($result_state))
                                        $City_Res2[]=$myrow_state['VALUE'];
                        }
                        //else
                          //      $City_Res2[]=$City_Res[$i];
                }
        }
		//print_r($City_Res2);
return $City_Res2;
}

?>
