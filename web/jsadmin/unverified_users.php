<?php
/***************************************************************************************************************************
FILE NAME		: unverified_users.php
DESCRIPTION		: This file shows the list of profiles with unverified mobile number and unverified email id
MODIFICATION DATE	: June 14th 2011.
MODIFIED BY		: Pankaj Khandelwal.
***************************************************************************************************************************/

include("connect.inc");
include_once("../profile/functions_edit_profile.php");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");

$data = authenticated($cid);
$name =getname($cid);
$date = date("Y-m");
list($curyear,$curmonth) = explode("-",$date);
$dbs = connect_slave();
$db = connect_db();
$smarty->clear_all_assign();
if($data)
{       
   //uid will pass from the main page with username and this pass the details to the function which will gather the information of this profile 
   if($_GET['uid']==1)
   {
      $cid    = $_GET['cid'];
      $userid = $_GET['proId'];
      $month  = $_GET['m'];
      $year   = $_GET['y'];
      $pageno = $_GET['pageno'];
      $ids    = $_GET['ids'];
	        
      $arr = displayeditprofile($ids,$userid,$month,$year,$pageno);
   } 

   // when click on back it will display the main page
   else if($_GET['b']==1)
   {
      $cid    = $_GET['cid'];
      $month  = $_GET['m'];
      $year   = $_GET['y'];
      if((strpos($ids,$userid)+strlen($userid)+1)==strlen($ids))
      {
      $pageno--;
      }	        
      $arr = display_tpl($month,$year,$curyear,$pageno);
      $userid="";
	
   }
   // skip the profile without verifying or deleting 
   else if($_GET['skip']==1)
   {
      $cid    = $_GET['cid'];
      $userid = $_GET['proId'];
      $month  = $_GET['m'];
      $year   = $_GET['y'];
      $pageno = $_GET['pageno'];
      $ids    = $_GET['ids'];
		
      $arr = displayeditprofile($ids,$userid,$month,$year,$pageno);
		
		
   }
   // clicking on delete display the form in which we can capture the comment
   else if($delete)
   {
		
      $username = $_POST['username'];
      $month    = $_POST['month'];
      $year     = $_POST['year'];
      $nextuser = $_POST['nextuser'];
      $cid      = $_POST['cid']; 
      $ids	  = $_POST['ids'];
      $pageno   = $_POST['pageno'];		

      $sql = "SELECT PROFILEID FROM newjs.JPROFILE where USERNAME = '".$username."'";
      $res = mysql_query($sql,$dbs) or die("$res".mysql_error_js());
      while($row = mysql_fetch_array($res))
      {
         $pid = $row['PROFILEID'];
      }
      $smarty->assign("username",$username);
      $smarty->assign("profiles",$pid);
      $smarty->assign("unverify",1);
		
   }
   else if($submit)
   {
      $month = $_POST['month'];
      $year  = $_POST['year'];	
      $arr = display_tpl($month,$year,$curyear);	
   }
   else if($update)
   {
      $phone    = $_POST['mobile'];
      $user     = $_POST['username'];
      $month    = $_POST['month'];
      $year     = $_POST['year'];
      $phonestd = $_POST['phone'];
      $altphone = $_POST['altphone'];
      $nextuser = $_POST['nextuser'];
      $cid      = $_POST['cid']; 
      $ids	    = $_POST['ids'];
      $pageno   = $_POST['pageno'];
      $std	    = $_POST['std'];
      $phoneflag='Y';	
	  
	  $sql = "SELECT PROFILEID FROM newjs.JPROFILE WHERE USERNAME = '$user'";
	  $res = mysql_query($sql,$dbs) or die("$res".mysql_error_js());
	  while($row = mysql_fetch_assoc($res))
   	  {
         	$profileid = $row['PROFILEID'];
      }	
      if($phone==''&& $phonestd==''&& $altphone=='')
      {
      	$emptyflag=1;
      }
      if($phonestd)
      {
      	$phoneWithStd =  $std.$phonestd;
      	if($phoneWithStd=="09999999999"||$phoneWithStd=="9999999999")
      	{
      		$phoneflag = 'Y';
      	}
      	else {
         $phoneflag = checkLandlineNumber($phonestd,$std,$profileid);
         }
      }
      if($phone)
      {
      $mobileflag = check_mobile_phone($phone,'51');
      }
      if($altphone)
      {
         $altflag = check_mobile_phone($altphone,'51');
      }
      if($std)
      {
		  $stdflag = check_country_code($std,"STD");
	  	}
		if($phoneflag=='Y' && $mobileflag==0 && $altflag==0 && $emptyflag==0 && $stdflag ==0 )
      {
      	$sql = "SELECT PHONE_MOB, PHONE_RES,STD, PROFILEID FROM newjs.JPROFILE WHERE USERNAME = '$user' ";
      	$res = mysql_query($sql,$dbs) or die("$res".mysql_error_js());
	      while($row = mysql_fetch_assoc($res))
   	   {
      	   $profileMobile = $row['PHONE_MOB'];
         	$profilePhone = $row['PHONE_RES'];
         	$profileid = $row['PROFILEID'];
         	$profileSTD = $row['STD'];
      	}
			$sql = "SELECT jc.ALT_MOBILE FROM  newjs.JPROFILE_CONTACT jc WHERE jc.PROFILEID = '".$profileid."'";
			$res = mysql_query($sql,$dbs) or die("$res".mysql_error_js());
   		while($row = mysql_fetch_array($res))
   		{
      	$profileAlt = $row['ALT_MOBILE'];
      	}
			$arrFields = array();
      	if($phone == $profileMobile)
      	{
	         $mobile ="";
      	}
      	else 
      	{
			$arrFields["PHONE_MOB"]=$phone;
	        $mobile = "PHONE_MOB = '$phone' ,";
         	$paramArray['PHONE_MOB'] = $phone;
         	$paramArray['PROFILEID'] = $profileid;
      	}
      	if($phonestd == $profilePhone)
      	{
	         $phonetext ="";
      	}
      	else 
      	{
			$arrFields["PHONE_RES"]=$phonestd;
	        $phonetext = "PHONE_RES = '$phonestd' ,";
         	$paramArray['PHONE_RES'] = $phonestd;
         	$paramArray['PROFILEID'] = $profileid;
      	}
      	if($std == $profileSTD)
      	{
	         $stdtext ="";
      	}
      	else 
      	{
			$arrFields["STD"]=$std;
	        $stdtext = "STD = '$std' ,";
         	$paramArray['STD'] = $td;
         	$paramArray['PROFILEID'] = $profileid;
      	}
      	if($altphone != $profileAlt)
      	{
	        $paramArray['ALT_MOBILE'] = $altphone;
	        $paramArray['PROFILEID'] = $profileid; 
      	}
      	$jprofileUpdateObj = JProfileUpdateLib::getInstance(); 
		$USERNAME=$user;
		$arrFields["ACTIVATED"]='Y';
		$exrtaWhereCond = "";
		$jprofileUpdateObj->editJPROFILE($arrFields,$USERNAME,"USERNAME",$exrtaWhereCond);
		
		
      // $sql = "UPDATE newjs.JPROFILE SET $mobile $phonetext $stdtext ACTIVATED = 'Y' WHERE USERNAME = '".$user."'";
 //     	$res_mp = mysql_query($sql,$db) or die("$res_mp".mysql_error_js());
        
        // $memObject=new UserMemcache;
        // $memObject->delete("JPROFILE_CONTACT_".$profileid);
        // unset($memObject);
        
      	$sql = "UPDATE newjs.JPROFILE_CONTACT SET ALT_MOBILE = '".$altphone."' WHERE PROFILEID = $profileid";
      	$res_mp = mysql_query($sql,$db) or die("$res_mp".mysql_error_js());
      	$display = " Profile <b>".$user." </b> has been verified .";
      	$smarty->assign("result",$display);
      	
      	if($nextuser!='')
      	{
			$smarty->assign("userid",$nextuser);
			if(!empty($paramArray))
				log_edit($paramArray);
        	$arr = displayeditprofile($ids,$nextuser,$month,$year,$pageno);
			$smarty->assign("nextuser",$arr["nextuser"]["nextuser"]);
		}
      	else {
			header("Location: $SITE_URL/jsadmin/unverified_users.php?cid=$cid");	
		}
      }
      else{
         $arr = displayeditprofile($ids,$user,$month,$year,$pageno);
 	
         $userid = $user;
         $errormsg = "Error in details <br />";			
				
         if($phone!='')
         {		
         $arr['mobile'] = $phone;
			
         }
         if($phonestd!='')
         {
            $smarty->assign("phonestd",$phonestd);
      		$smarty->assign("phoneowner",$arr["phoneowner"]);
      		$smarty->assign("STD",$std);
         }
         if($altphone!='')
         {
            $arr['altmobile'] = $altphone;
         }		
         if($phoneflag =='N') 
         {
            $errormsg.= "Please Enter Correct Landline number <br/>";
         }
         if($mobileflag==1)
         {
            $errormsg.= "Please Enter Correct Mobile Number <br/>";
         }
         if($altflag==1)
         {
            $errormsg.="please Enter Correct Alternate Number <br/>";
         }
         if($emptyflag==1)
         {
         	$errormsg.="Atleast one field is require.";
         }
         if($stdflag==1)
         {
         	$errormsg.="Please Enter Correct STD code.";
         }
         
         $smarty->assign("errormsg",$errormsg);
			
      }
		
   }
   elseif($confirm)
   {
				
      $c=1; $send_mail=0;$reasons=$reason;$pid=$profiles;$flag_search=0;$flag_deletion = 1;$unverify=1;
      $sql= "INSERT INTO jsadmin.MARK_DELETE(PROFILEID, STATUS, M_DATE, DATE, REASON, COMMENTS, ENTRY_BY) VALUES('$pid','M','$date','$date','$reason','$comments','$name')";
               $res= mysql_query($sql,$db) or die(mysql_error_js());	
      include("deletepage.php");
	    $display = " Profile <b>".$username." </b> has been verified .";
      	$smarty->assign("result",$display);	
	  if($nextuser!='')
	  {
      $ids = $_POST['ids'];
		
      $arr = displayeditprofile($ids,$nextuser,$month,$year,$pageno);
		
      $userid = $nextuser;
		
      $nextuser = $arr["nextuser"]["nextuser"];
  }
  else{
	  header("Location: $SITE_URL/jsadmin/unverified_users.php?cid=$cid");
  }
	  
   }

   elseif($cancel)
   {
      $arr = displayeditprofile($ids,$username,$month,$year,$pageno);
      $userid = $username;
   }
   else 
   {
		
      $month = $curmonth;
      $year  = $curyear;
      $arr = display_tpl($month,$year,$curyear);
   }


   //Smarty assignment
   $smarty->assign("name",$name);
   $smarty->assign("mmarr",$arr["mmarr"]);
   $smarty->assign("yyarr",$arr["yyarr"]);
   $smarty->assign("curmonth",$curmonth);
   $smarty->assign("curyear",$curyear);
   $smarty->assign("cid",$cid);
   $smarty->assign("month",$month);
   $smarty->assign("year",$year);
   if ($smarty->get_template_vars("userid") === null) 
   {
      $smarty->assign("userid", $userid);
   }
	
   $smarty->assign("owner",$arr["owner"]);
   $smarty->assign("phone",$arr["mobile"]);
   //print_r($arr);
   //echo $smarty->get_template_vars("phonestd");die;
   if ($smarty->get_template_vars("phonestd") === null) 
   {
   	if(strlen($arr["phone"])>=6)
   	{
      	$smarty->assign("phonestd",$arr["phone"]);
      	$smarty->assign("phoneowner",$arr["phoneowner"]);
      	$smarty->assign("STD",$arr["STD"]);
   	}
   }
	
   else
   {
      $smarty->assign("showemsg","N");
   }
	
   $smarty->assign("altmobile",$arr["altmobile"]);
   $smarty->assign("altowner",$arr["altowner"]);
   if ($smarty->get_template_vars("nextuser") === null)
   {
   	if($nextuser)
   	{
      	$smarty->assign("nextuser",$nextuser);
   	}
   	else
   	{
     		$smarty->assign("nextuser",$arr["nextuser"]["nextuser"]);
   	}
   }
   if($ids)
   {
      $smarty->assign("ids",$ids);
   }
   else
   {
      $smarty->assign("ids",$arr["ids"]);
   }
   $smarty->assign("record",$arr["record"]);
   $smarty->assign("rownum",$arr["rownum"]);
   if($pagen)
   {
      $smarty->assign("pageno",$pagen);
   }
   elseif($arr["nextuser"]["pageno"])
   {
      $smarty->assign("pageno",$arr["nextuser"]["pageno"]);
   }
   elseif($pageno)
   {
      $smarty->assign("pageno",$pageno);
   }
   else
   {
      $smarty->assign("pageno",$arr["pageno"]);
   }
   $smarty->assign("last",$arr["last"]);

   $smarty->display("unverified_users.htm");
	
}
else
{
   $msg="Your session has been timed out<br>";
   $msg .="<a href=\"index.htm\">";
   $msg .="Login again </a>";
   $smarty->assign("MSG",$msg);
   $smarty->display("jsadmin_msg.tpl");
}

function display_query($month,$year,$pagen)
{
   if (isset($_GET['pageno'])) {
      $pageno = $_GET['pageno'];
   } else {
         $pageno = 1;
   }
   if(isset($pagen))
   {
      $pageno = $pagen;
   }
   $rowperpage = 50;
   $i=($pageno-1)*$rowperpage;
   $dbs = connect_slave();
	
   //finding details of profiles with unverified mobile number.
   $sql_mp = "SELECT DISTINCT (sd.PROFILEID)
				FROM newjs.SMS_DETAIL sd
				WHERE sd.SMS_KEY IN ( 'PHONE_UNVERIFY',  'REGISTER_RESPONSE')
				AND ADD_DATE = ( 
					SELECT MAX( ADD_DATE ) 
					FROM newjs.SMS_DETAIL
					WHERE PROFILEID = sd.PROFILEID )
					ORDER BY PROFILEID ";
			
   $res_mp = mysql_query($sql_mp,$dbs) or die("$res_mp".mysql_error_js());
   $rownum = mysql_num_rows($res_mp);
   $count=0;
   while($row = mysql_fetch_assoc($res))
   {	
		$arr[$count++] = $row['PROFILEID'];
   }
   $profile = implode(",",$arr);
   $last = ceil($rownum/$rowperpage);

   $sql_mp = "SELECT DISTINCT (jp.PROFILEID), jp.USERNAME,jp.PHONE_MOB,
         'Mobile number is not verified' as 'Message' 
         FROM newjs.JPROFILE jp
         WHERE 
         jp.PROFILEID IN ( ".$profile.")
         LIMIT ".$i.",".$rowperpage;
   $res_mp = mysql_query($sql_mp,$dbs) or die("$res_mp".mysql_error_js());	
   $k=1;
	
   while($row=mysql_fetch_assoc($res_mp))
   {		
      $ids.=$row['USERNAME']."-"; 
      $record[$k]=$row;
      $k++;
   }
   $arr["ids"] = $ids;
   $arr["record"] = $record;
   $arr["rownum"] = $rownum;
   $arr["pageno"] = $pageno;
   $arr["last"] = $last;
   return $arr;
}

function display_tpl($month,$year,$curyear,$pageno=1){
	
   $arr = display_query($month,$year,$pageno);		
			
   for($i=1;$i<=12;$i++)
      $mmarr[] = $i;

   for($i=2007;$i<=$curyear;$i++)
      $yyarr[] = $i;
   $arr["mmarr"] = $mmarr;
   $arr["yyarr"] = $yyarr;
   return $arr;
	
}

function getNext($ids,$userid,$month,$year,$pageno)
{
   $userlen = strlen($userid);

   $userpos = strpos($ids,$userid);
   if(! $userpos)
   {
		
      $arr = display_query($month,$year,$pageno);
      $ids = $arr["ids"];
      $userpos = strpos($ids,$userid);
   }
	
   $idlen = strlen($ids);
	
   $ids = substr ( $ids , $userpos+$userlen+1  );		
	
   if(strlen($ids)<2)
   {
      $pageno++;
      $arr = display_query($month,$year,$pageno);
      $ids = $arr["ids"];

   }
   $arr["pageno"] = $pageno;
   $nextpos =  strpos($ids,"-");
   $nextuser = substr ( $ids ,0, $nextpos);	
   $arr["nextuser"] = $nextuser;

   return $arr;
}
	
function displayeditprofile($ids,$userid,$month,$year,$pageno)
{
   $dbs = connect_slave();
   //get contact details of profile 
   $sql = "SELECT jp.PHONE_MOB,jp.MOBILE_OWNER_NAME, jp.PHONE_RES,jp.PHONE_OWNER_NAME,STD  FROM newjs.JPROFILE jp WHERE jp.PROFILEID = '".$profileid."'";
		
   $res = mysql_query($sql,$dbs) or die("$res".mysql_error_js());
   while($row = mysql_fetch_array($res))
   {
      $arr["mobile"]       = $row['PHONE_MOB'];
      $arr["owner"]        = $row['MOBILE_OWNER_NAME'];
      $arr["phone"]    		= $row['PHONE_RES'];
      $arr["phoneowner"]  	= $row['PHONE_OWNER_NAME'];
      $arr["STD"]			  	= $row['STD'];
   }
	
   $sql = "SELECT jc.ALT_MOBILE,jc.ALT_MOBILE_OWNER_NAME  FROM  newjs.JPROFILE_CONTACT jc WHERE jc.PROFILEID = '".$profileid."'";
	
   $res = mysql_query($sql,$dbs) or die("$res".mysql_error_js());
   while($row = mysql_fetch_array($res))
   {
      $arr["altmobile"] = $row['ALT_MOBILE'];
      $arr["altowner"]  = $row['ALT_MOBILE_OWNER_NAME'];
   }

   //getting next user
   $arr["nextuser"]  = getNext($ids,$userid,$month,$year,$pageno,$smarty);
   $arr["profileid"] = $profileid; 
   $arr["pageno"] = $pageno;
   $arr["ids"] = $ids;
   return $arr;
	
}

function check_mobile_phone($mobile,$country)
{
   $flag=0;
	
   $blocked_char=array('0','7','8','9');
   $blocked_char_new=array('7','8','9');
   $first_char=substr($mobile,0,1);
   $second_char=substr($mobile,1,1);
   $length=strlen($mobile);

   if(trim($mobile)=='')
      $flag=1;
   elseif(!ereg("^[+]?[0-9]+$", $mobile))
      $flag=1;
   else if($country=='51' && $first_char=='0' && (!in_array($second_char,$blocked_char_new)))
      $flag=1;
   else if($country=='51' && (!in_array($first_char,$blocked_char)))
      $flag=1;
   else if ($country!='51' && strlen($mobile) < 5)
      $flag=1;
   else if (($first_char=='0' && $country=='51') && strlen($mobile)!='11') 
      $flag=1;
   else if (($first_char!='0' && $country=='51') && strlen($mobile)!='10')
      $flag=1;
   else if ((strlen($mobile))<'10' && $country=='51')
      $flag=1;

   return $flag;
}
function check_country_code($code,$type)
{
   $flag=0;
   $first_two_char=substr($code,0,2);	
   $first_char=substr($code,0,1);	
   if($type=='ISD'){
      if($first_char=='+'){
         $isd_code_new=str_replace("+","",$code);
         $length=strlen($isd_code_new);
      }
      else if($first_two_char=='00'){
         $isd_code_new=str_replace("00","",$code);
         $length=strlen($isd_code_new)+2;
      }
      else{
         $isd_code_new=$code;
         $length=strlen($isd_code_new);
      }
	
      if (!ereg("^[0-9()-/+ ,]+$", $code))
         $flag=1;
      else if($first_char=='+' && (!($length > 0 && $length < 5)))
         $flag=1;
      else if($first_two_char=='00' && (!($length > 2 && $length < 7)))
         $flag=1;
      else if(!($length > 0 && $length < 7))
         $flag=1;
   }
   else if($type=='STD'){
      if($first_two_char=='00'){
         //$std_code_new=str_replace("00","",$code);
         $std_code_new=$code;
         $length=strlen($std_code_new);
      }
      else{
         $std_code_new=$code;
         $length=strlen($std_code_new);
      }
		
      if (!ereg("^[0-9()-/+ ,]+$", $code))
         $flag=1;
      else if($first_two_char=='00' && (!($length > 2 && $length < 7)))
         $flag=1;
      else if($first_two_char!='00' && (!($length > 0 && $length < 5)))
         $flag=1;
      }
   return $flag;
}
?>
