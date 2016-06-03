<?php

/**********************************************************************************************************************
* FILE NAME     : connect.inc
* DESCRIPTION   : Contains functions that were needed for the Business Sathi program. 
* CREATION DATE : 17 June, 2005
* CREATED BY  	: Shakti Srivastava
* REASON	: Slight modifications were needed in order to properly and fully implement
*		: the Business Sathi program and this could not have been done with the existing
*		: functions.
*		: Functions in this file are:-
*		: connect_db(),logError(),login(),authenticated(),logout(),TimedOut(),maStripVARS(),pagelink()
* Copyright  2005, InfoEdge India Pvt. Ltd.
***********************************************************************************************************************/

$TOUT1 = 7200;
$TOUT2 = 7200;
                                                                                                                            
//include("/usr/local/apache/sites/jeevansathi.com/htdocs/smarty/Smarty.class.php");
include(JsConstants::$smartyDir);
$smarty=new Smarty;
$smarty->setTemplateDir(JsConstants::$docRoot."/smarty/templates");
$smarty->setCompileDir(JsConstants::$docRoot."/smarty/templates_c");

//$SITE_URL="http://192.168.2.220";
$SITE_URL="http://www.jeevansathi.com";
$smarty->assign("SITE_URL",$SITE_URL);


/*******************************************************************
*  FUNCTION NAME:  connect_db()
*  DESCRIPTION	:  connects to the database server
*  RETURNS	:  A Resource type ($db)
********************************************************************/
function connect_db()
{
	$db=mysql_connect(MysqlDbConstants::$master[HOST],MysqlDbConstants::$master[USER],MysqlDbConstants::$master[PASS]) or logError(mysql_error()."Due to a temporary problem your request could not be processed. Please try after a couple of minutes","","ShowErrTemplate");
        @mysql_select_db("affiliate",$db);
        return $db;
}

function connect_slave()
{
	$db=mysql_connect(MysqlDbConstants::$misSlave[HOST],MysqlDbConstants::$misSlave[USER],MysqlDbConstants::$misSlave[PASS]) or logError(mysql_error()."Due to a temporary problem your request could not be processed. Please try after a couple of minutes","","ShowErrTemplate");
        @mysql_select_db("affiliate",$db);
        return $db;
}

/*********************************************************************************************************************
*  FUNCTION NAME:  logError()
*  DESCRIPTION  :  Handles any error that may occur during the execution of the script and creates a log in logerror.txt
*		:  and either echoes the error message or shows an error template depending on the
*  RETURNS      :  Nothing
**********************************************************************************************************************/
function logError($message,$query="",$critical="ShowErrTemplate", $sendmailto="NO")
{
        global $_SERVER, $db, $smarty, $checksum;
                                                                                                                            
        ob_start();
        var_dump($_SERVER);
        $ret_val = ob_get_contents();
        ob_end_clean();
                                                                                                                    
	$errorstring="\n" . date("Y-m-d G:i:s",time() + 37800) . "\nErrorMsg: $message\nMysql Error: " . addslashes(mysql_error()) ."\nMysql Error Number:". mysql_errno()."\nSQL: $query\n#User Agent : " . $_SERVER['HTTP_USER_AGENT'] . "\n #Referer : " . $_SERVER['HTTP_REFERER'] . " \n #Self :  ".$_SERVER['PHP_SELF']."\n #Uri : ".$_SERVER['REQUEST_URI']."\n #Method : ".$_SERVER['REQUEST_METHOD']."\n$query\n";
       
	error_log($errorstring,3,JsConstants::$docRoot . "/bussiness_sathi/logerror.txt"); 

        if($critical=="exit")
        {
                echo $message;
                exit;
        }
        elseif($critical=="ShowErrTemplate")
        {
                $smarty->assign("CHECKSUM",$checksum);
                $smarty->assign("FOOT",$smarty->fetch("jeevansathi/foot.htm"));
                $smarty->assign("HEAD",$smarty->fetch("jeevansathi/head.htm"));
                $smarty->assign("SUBFOOTER",$smarty->fetch("jeevansathi/subfooter.htm"));
                $smarty->assign("SUBHEADER",$smarty->fetch("jeevansathi/subheader.htm"));
                                                                                                                            
                $smarty->assign("msg_error", $message);
                $smarty->display("jeevansathi/error_template.htm");
                exit;
        }
        elseif($critical!="continue")
        {
                echo $message;
        }                                                                                                                  
}

/*********************************************************************************************************************
*  FUNCTION NAME:  login()
*  DESCRIPTION  :  Checks that un-authorised people are not able to log in.
*  NOTES	:  In case the username or password is wrong or if he is either Disapproved or Deleted, the person is not 
*		:  allowed to log in and NULL is returned. Otherwise an entry is done in the CONNECT table and the person
*		:  is logged in.
*  RETURNS      :  NULL if either username or password is wrong. Else returns an array $data[]
**********************************************************************************************************************/
function login($username,$password)
{
        global $TOUT1,$TOUT2,$smarty;
                                                                                                                            
        $username=addslashes(stripslashes($username));
        $password=addslashes(stripslashes($password));

        $sql="select AFFILIATEID,USERNAME from affiliate.AFFILIATE_DET where USERNAME='$username' and PASSWORD='$password' AND STATUS IN ('A','N')";

        $result=@mysql_query($sql) or logError(mysql_error()."Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                                                                                                                            
        if(mysql_num_rows($result) <= 0)
                return NULL;
                                                                                                                            
        $myrow=mysql_fetch_array($result);
                                                                                                                            
        $data["AFFILIATEID"]=$myrow["AFFILIATEID"];
        $data["USERNAME"]=$myrow["USERNAME"];
                                                                                                                            
        $today=date("Y-m-d");
        $tm=time();
	
	$sql="delete from affiliate.CONNECT where TIME1 < ($tm - $TOUT2)";
        mysql_query($sql) or die(mysql_error());
                                                                                                                            
        $sql="insert into affiliate.CONNECT(ID,USERNAME,PASSWORD,AFFILIATEID,TIME1) values ('','$username','$password','" . $data["AFFILIATEID"] . "','$tm')";
                                                                                                                            
        @mysql_query($sql) or logError(mysql_error()."Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                                                                                                                            
        $id=mysql_insert_id();
        $checksum=md5($id) . "i" . $id;
                                                                                                                            
        $data["CHECKSUM"]=$checksum;
        setcookie("JSLOGIN",$checksum,0,"/");
	$smarty->assign("CURRENTUSERNAME",$data["USERNAME"]);
        return $data;
}

/*********************************************************************************************************************
*  FUNCTION NAME:  authenticated()
*  DESCRIPTION  :  Allows only an already logged in person to view the page.
*  NOTES	:  The function checks the value of checksum (passed usually through querystring or stored in a cookie)
*		:  to see if he is already logged in. If yes than his time in the CONNECT table is updated and he is allowed
*		:  to view the page. Otherwise NULL is returned and he is not allowed to view the page.
*  RETURNS      :  NULL if person is not authorised else an array $data[] is returned.
**********************************************************************************************************************/
function authenticated($checksum1="")
{
        global $TOUT1,$TOUT2,$_COOKIE,$smarty,$checksum;
                                                                                                                            
        if($checksum1=="")
        {
                if(isset($_COOKIE['JSLOGIN']))
                {
                        $checksum1=$_COOKIE['JSLOGIN'];
                        $checksum=$checksum1;
                }
                else
                        return NULL;
        }
                                                                                                                            
        $arr=explode("i",$checksum1);
                                                                                                                            
        if($arr[0]!=md5($arr[1]))
                return NULL;
                                                                                                                            
        $id=$arr[1];
                                                                                                                            
        $sql="select AFFILIATEID,TIME1,USERNAME from affiliate.CONNECT where ID='$id'";
        $result=mysql_query($sql) or logError(mysql_error()."Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
 if(mysql_num_rows($result) <= 0)
                return NULL;
                                                                                                                            
        $myrow=mysql_fetch_array($result);
                                                                                                                            
        mysql_free_result($result);
                                                                                                                            
        if (((time()-$myrow["TIME1"]) > $TOUT1))
                return NULL;
                                                                                                                            
        $data["AFFILIATEID"]=$myrow["AFFILIATEID"];
        $data["CHECKSUM"]=$checksum1;
        $data["USERNAME"]=$myrow["USERNAME"];
                                                                                                                            
        $tm=time();
                                                                                                                            
        $sql="update CONNECT set TIME1='$tm' where ID='$id'";
        mysql_query($sql) or logError(mysql_error()."Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                                                                                                                            
	$smarty->assign("CURRENTUSERNAME",$data["USERNAME"]);
	$smarty->assign("NOIMAGE","1");
        return $data;
}

/*********************************************************************************************************************
*  FUNCTION NAME:  logout()
*  DESCRIPTION  :  Logsout the person.
*  NOTES	:  Checks the checksum (passed through the querystring or stored in a cookie) to see whether the person
*		:  is already logged in. If yes then he is logged out and his entry in the CONNECT table is deleted. Also
*		:  the cookie too is deleted.
*  RETURNS      :  'true' if the person was logged-in and his entry in CONNECT table is successfully deleted. Else 'false'.
**********************************************************************************************************************/
function logout($checksum)
{
        if($checksum=="")
        {
                if(isset($_COOKIE['JSLOGIN']))
                        $checksum=$_COOKIE['JSLOGIN'];
                else
                        return NULL;
        }
                                                                                                                            
        $arr=explode("i",$checksum);
                                                                                                                            
        if($arr[0]!=md5($arr[1]))
                return false;
                                                                                                                            
        $id=$arr[1];
                                                                                                                            
        $sql="delete from CONNECT where ID='$id'";
                                                                                                                            
        $result=mysql_query($sql);
                                                                                                                            
        // set a cookie with the name JSLOGIN to overwrite the previous value of checksum with "" and expires when the session ends and is available on the entire domain. This deletes the cookie
        setcookie("JSLOGIN","",0,"/");
                                                                                                                            
        if(!$result)
                return false;
        else
                return true;
}


/*********************************************************************************************************************
*  FUNCTION NAME:  maStripVARS()
*  DESCRIPTION  :  A generalised function that adds or strips slashes from the variables
*  RETURNS      :  Nothing
**********************************************************************************************************************/     
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
                        	        $_GET[$k] = strip_tags(str_replace("\""."'",stripslashes($v)));
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


/*********************************************************************************************************************
*  FUNCTION NAME:  TimedOut()
*  DESCRIPTION  :  Checks whether the time for which the person was has expired or not.
*  NOTES	:  In case the time for which the person was logged-in has expired and authenticated() function returns null
*		:  this function displays the login page to allow the person to log-in again.
*  RETURNS      :  Nothing
**********************************************************************************************************************/
function TimedOut()
{
        global $smarty, $checksum, $_SERVER, $_POST, $_GET, $_COOKIE;
                                                                                                                            
        if($checksum=="")
        {
                if(isset($_COOKIE['JSLOGIN']))
                {
                        $checksum1=$_COOKIE['JSLOGIN'];
                        $checksum=$checksum1;
                }
        }
                                                                                                                            
        if($checksum=="")
                $smarty->assign("NEWUSER","1");
                                                                                                                            
        if($_SERVER['REQUEST_METHOD']=="POST")
        {
                if($_POST['METHOD']=="GET")
                {
                        $smarty->assign("METHOD", "GET");
                        $smarty->assign("CHECKSUM",$_POST["checksum"]);
                        $smarty->assign("REQUESTEDURL",$_POST['REQUESTEDURL']);
                        $smarty->assign("RELOGIN","Y");
                }
		else
                {
                        $j=0;
                        foreach($_POST as $key => $value)
                        {
                                if($value != "")
                                {
                                        $data[$j]["NAME"]=$key;
                                        if(is_array($value))
                                        {
                                        $data[$j]["VALUE"]="ARRAY";
                                                $i=0;
                                foreach($value as $val)
                                            if($val != "")
                                                        {
                                                $data[$j][$i++]=$val;
                                            }
                                        }
                                        else
                                                $data[$j]["VALUE"]=$value;
                                        $j++;
                                }//if
                        }//foreach
                                                                                                                            
                        $smarty->assign("ACTION",substr($_SERVER['REQUEST_URI'],strrpos($_SERVER['REQUEST_URI'],"/")+1));
                                                                                                                            
                        $smarty->assign("RELOGIN","Y");
                        $smarty->assign("DATA",$data);
                        $smarty->assign("METHOD", "POST");
                }
        }//if
	else if($_SERVER['REQUEST_METHOD']=="GET")
        {
                $smarty->assign("METHOD", "GET");
                $smarty->assign("CHECKSUM",$_GET["checksum"]);
                $smarty->assign("REQUESTEDURL",$_SERVER['REQUEST_URI']);
                $smarty->assign("RELOGIN","Y");
        }

        $smarty->assign("HEAD",$smarty->fetch("business_sathi/heads.htm"));
        $smarty->assign("LEFT",$smarty->fetch("business_sathi/left.htm"));
                                                                                                                            
        $smarty->display("business_sathi/businesssathi_login.htm");
}


/*********************************************************************************************************************
*  FUNCTION NAME:  pagelink()
*  DESCRIPTION  :  Paging function
*  NOTES        :  The function displays the number of pages for a certain record after dividing them.
*		:  The parameters for the function are:-
*		:  $cat=CategorySize of Banners to be displayed
*		:  $pagelen = No of jobs shown in page // default 50
*		:  $totalrec = No of record found for search
*		:  $curpage = which page is shown currently
*		:  $linkno = how many link is shown on page
*		:  $link = link goes for numbers
*  RETURNS      :  Nothing
**********************************************************************************************************************/
function pagelink($cat,$pagelen,$totalrec,$curpage,$linkno,$link,$scriptname="",$searchchecksum="")
{
        global $smarty;
 
        // set the links to be shown on each page to be five
        $linkno=5;
 
        $totalpage = $totalrec / $pagelen;
        $totalpage = ceil($totalpage);
        $curpage = round($curpage);
        $startwith = $curpage / $linkno;
        $startwith = abs(floor($startwith - 0.1));
        $startwith = $startwith * $linkno;
 
        if($totalpage > ($startwith + $linkno))
        {
                $totallinkshow = $linkno;
                if($totalpage < ($startwith + ($linkno * 2)))
                $lastlink = $startwith + $totalpage;
                else
                $lastlink = $startwith + $linkno + $totallinkshow;
        }
        else if($totalpage < ($startwith + ($linkno * 2)))
        {
                $totallinkshow = $totalpage - $startwith;
                $lastlink = "";
        }
        else
        {
                $totallinkshow = $totalpage - $startwith;
                $lastlink = $startwith + $linkno + $totallinkshow;
        }
 
        $prevwith=$startwith-9;
        $linkvar = "Page : <strong>";
        if($startwith && !($startwith % $linkno))
                $linkvar .= "&nbsp;<span class=\"class6\"><a HREF=\"$scriptname?checksum=$link&cat=$cat&searchchecksum=$searchchecksum&j=".($startwith-1)*$pagelen."\">&lt;&lt;</a></span>&nbsp;";
 
          
        for($i=1;$i<=$totallinkshow;$i++)
        {
                $nos = $startwith+$i;
                if($nos != $curpage)
                        $linkvar .= "<span class=\"class6\"><a HREF=\"$scriptname?checksum=$link&cat=$cat&searchchecksum=$searchchecksum&j=".($nos-1)*$pagelen."\">";
                else
                        $linkvar .= "&nbsp;";
 
                        $linkvar .= "$nos</a></span>&nbsp;";
        }
 
        if($lastlink)
        {
                $nos = $startwith+$i;
                $linkvar .= "&nbsp;<span class=\"class6\"><a HREF=\"$scriptname?checksum=$link&cat=$cat&searchchecksum=$searchchecksum&j=".($nos-1)*$pagelen."\">";
                $linkvar .= "&gt;&gt;</strong></a></span>&nbsp;";
        }
 
        $linkvar.= "&lt;<strong><font color=\"#999999\">";
 
        if($curpage > 1)
                $linkvar .= "<span class=\"class6\"><a HREF=\"$scriptname?checksum=$link&cat=$cat&searchchecksum=$searchchecksum&j=".($curpage-2)*$pagelen."\">Previous</a></span>";
        else
                $linkvar.="Previous";
 
        $linkvar.="</font></strong> <strong>|";
 
        $linkvar.="<font color=\"#999999\">";
        if($curpage < $totalpage)
                $linkvar .= "<span class=\"class6\"><a HREF=\"$scriptname?checksum=$link&cat=$cat&searchchecksum=$searchchecksum&j=". $curpage*$pagelen ."\">Next</a></span>";
        else
                $linkvar.="Next";
        $linkvar.="</font>";
 
        $linkvar.="&gt;</strong>";
        if($totalpage==0)
                $linkvar="";
        $smarty->assign("PAGELINK",$linkvar);
}//end function pagelink

function checkemail($email)     // returns 1 if email id not valid
{
        $flag=0;
        if (!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$\i", $email))
        {
                $flag=1;
        }
        else
        {
                $flag=0;
        }
        return $flag;
}

?>
