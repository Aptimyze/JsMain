<?php

// returns 1 if phone no or mobile no is not valid
function check_numeric($string)
{
        if(!preg_match("/^[0-9]+$/i", $string));
                return 1;
}

function checkphone($phone)     // returns 1 if phone no. is not valid
{
        $flag=0;
        if( trim($phone) =='')
        {
                $flag=1;
        }
         elseif (!preg_match("#^[0-9()-/+]+$#", $phone))
        {
                $flag=1;
        }

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
	
		if (!preg_match("#^[0-9()-/+ ,]+$#", $code))
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
		
		if (!preg_match("#^[0-9()-/+ ,]+$#", $code))
			$flag=1;
		else if($first_two_char=='00' && (!($length > 2 && $length < 7)))
			$flag=1;
		else if($first_two_char!='00' && (!($length > 0 && $length < 5)))
			$flag=1;
        }
	return $flag;
}

function check_landline_phone($phone,$country,$std=""){
        $flag=0;
	$first_char=substr($phone,0,1);
 	$second_char=substr($phone,1,1);
	$length=strlen($phone);
	if($std){
		$first_char_of_std=substr($std,0,1);
		if($first_char_of_std=='0') 
			$std=substr_replace($std,'',0,1);
		else if($first_char_of_std=='+')
			$std=substr_replace($std,'',0,1);
	}	
	
	if($std){
		$sql = "SELECT * FROM newjs.LANDLINE_NETWORK WHERE STD_CODE='$std'";
		$res = mysql_query_decide($sql) or logError("error",$sql);
		$count=mysql_affected_rows_js();
		while($row = mysql_fetch_array($res)){
			$airtel_4=$row['AIRTEL_4'];
			$tata_6=$row['TATA_6'];
			$reliance_3=$row['RELIANCE_3'];
			$mts_5=$row['MTS_5'];
			$hfcl_5=$row['HFCL_INFOTEL_5'];
			
			$blocked_array_runtime=array('2'); /* Allow 2 for every time */

			if($airtel_4=='Y')
				$blocked_array_runtime[]='4';
			if($tata_6=='Y')
				$blocked_array_runtime[]='6';
			if($reliance_3=='Y')
				$blocked_array_runtime[]='3';

			if($mts_5=='Y')
				$blocked_array_runtime[]='5';
			else if($hfcl_5=='Y')
				$blocked_array_runtime[]='5';
		}
	}
	
	if($std=='' || $count==0)
		$blocked_char=array('2','3','4','5','6','7');
	else
		$blocked_char=$blocked_array_runtime;
        
	$pos = strrpos($phone, ".");
        
	if($pos!==false)
                $flag=1;
        else if(trim($phone)=='')
                $flag=1;
        else if (!preg_match("#^[0-9()-/+ ,]+$#", $phone))
                $flag=1;
	else if($country=='51' && (!in_array($first_char,$blocked_char)))
                $flag=1;
	else if ((!($first_char=='7' && $second_char=='2')) && $country=='51' && $first_char=='7')
		$flag=1;
	else if($country=='51' && (!$length > 5 && $length < 9))
		$flag=1;
	else if($country!='51' && (!$length > 3 && $length < 13))
		$flag=1;
	else if($country=='51' && (strlen($phone)+strlen($std))>10)
		$flag=1;

        return $flag;
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
	elseif(!preg_match("#^[+]?[0-9]+$#", $mobile))
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

function checkrphone($phone)     // returns 1 if phone no. is not valid
{
	
		$flag=0;
        $pos = strrpos($phone, ".");
        if($pos!==false)
        {
                $flag=1;
        }
        if(trim($phone) =='')
        {
                $flag=1;
        }
        elseif (!preg_match("#^[0-9()-/+ ,]+$#", $phone))
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

	elseif (!preg_match("#^[+]?[0-9]+$#", $phone))
	{
		$flag=1;
	}

	return $flag;
}

function checkemail($email,$flagDup="")	// returns 1 if email id not valid
{
	$flag=0;
	
	$email=trim($email);
	
	if($email=="")
	{
		$flag=1;
	}
	elseif (!preg_match("/^([a-z0-9._%+-]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/", $email))
	{
		$flag=1;
    	}
	else
	{
		$result = mysql_query_decide("SELECT count(*) FROM JPROFILE where EMAIL='$email'") or logError("error",$sql);
        	$myrow = mysql_fetch_row($result);
        	
        	if($myrow[0] > 0)
    		{	$flag=2;
			$dup_email_flag='Y';
                        if(($page=find_source_page())&& ( $flagDup!='N'))
			{	track_duplicate_email($email,$page,$dup_email_flag);}
		}
		else
		{
                        $dup_email_flag='N';
                        if(($page=find_source_page())&& ($flagDup!='N'))
	                        track_duplicate_email($email,$page,$dup_email_flag);
		}
	}

	$part=explode("@",$email);
	if(!$flag)
	{
		if(strtolower($part[1])=="jeevansaathi.com")
			$flag=3;
		elseif(strtolower($part[1])=="jeevansathi.com")
			$flag=3;
	}
	
	if(!$flag)
	{
		$dotpos = strrpos($part[1],".");
		$middle = substr($part[1],0,$dotpos);
		$sql = "SELECT DOMAIN FROM newjs.INVALID_DOMAINS";
		$res = mysql_query_decide($sql) or logError("error",$sql);
		while($row = mysql_fetch_array($res))
		{
			if(strstr($middle,$row['DOMAIN']))
			{
				$flag = 4;
				break;
			}
		}
	}
$page=0;
    	return $flag;
}
/*******************
fn:find_source_page
auther:Esha
returns: a short name for pages to be tracked
Description:http://devjs.infoedge.com/mediawiki/index.php/Tracking
******************/
function find_source_page()
{
	$page = $_SERVER['PHP_SELF'];
	$php_page=explode("/",$page);
	$count=count($php_page);
	$count--;
	$source_page=trim($php_page[$count]);

	$pageArray=array(
	"registration_page1.php"=>"REG",
	"bck_registration_ajax_validation.php"=>"BREGAJX",
	"validate_input.php"=>"VALIN",
	"validate_function.php"=>"VALFN",
	"check.php"=>"CHECK",
	"order_match_astrol.php"=>"ASTRO",
	"forward_profile.php"=>"FWDPR",
	"promo_entry.php"=>"PROEN",
	"auto_reg_functions.php"=>"AUTOR",
	"1min.php"=>"A_MIN",
	"verify_email_Id.php"=>"VERID",
	"faq_other.php"=>"FAQ",
	"unsubscribe.php"=>"UNSUB",
	"rav.php"=>"RAV",
	"registration_pg1.php"=>"REG1",
	"myjs_gmailid.php"=>"GMAIL",
	"order_astrol.php"=>"OAST",
	"registration_page4.php"=>"REG4",
	"registration_new.php"=>"REGNW",
	"offline_registration.php"=>"OFREG",
	"top_save_matchalert.php"=>"MATCH",
	"naukri_js.php"=>"NAUKRI",
	"registration_ajax_validation.php"=>"REGAXVL",
	"register.php"=>"MOB_REG"
	);
	foreach ($pageArray as $key => $value) 
	{
		if($source_page==$key)
			return $value;
	}
$page="OTHERS";
return $page;
}

/***********************************************************************************
*Funtion that creates log each time a user tries to access information using email id
Author:Esha
Track id 529
Description: http://devjs.infoedge.com/mediawiki/index.php/Tracking
$flag=Y if the access to data is a possibility of tracking 
     =N if the access to data is ok
*************************************************************************************/
function track_duplicate_email($email, $page, $flag)
{
        $ip = FetchClientIP();
        if(strstr($ip, ","))
        {
                $ip_new = explode(",",$ip);
                $ip = $ip_new[1];
        }
        $sql_mis_ip = "INSERT INTO MIS.TRACK_DUPLICATE_EMAIL(EMAIL,IP,TIME,PAGE,FLAG) VALUES('$email','$ip',now(),'$page', '$flag')";
        mysql_query_decide($sql_mis_ip) or logError("Due to some temporary problem your request could not be processed. Please try after some time.");

}




function checkemail_af($email)	// returns 1 if email id not valid
{
	$flag=0;
	
	$email=trim($email);
	
	if($email=="")
	{
		$flag=1;
	}
	elseif (!preg_match("/^([a-z0-9._%+-]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i", $email))
	{
		$flag=1;
    	}
	else
	{
		$result = mysql_query_decide("SELECT count(*) FROM JPROFILE_AFFILIATE where EMAIL='$email'") or logError("error",$sql);
        	$myrow = mysql_fetch_row($result);
        	
        	if($myrow[0] > 0)
    			$flag=2;
	}
	
	$part=explode("@",$email);
	
	if(strtolower($part[1])=="jeevansaathi.com")
		$flag=3;
	elseif(strtolower($part[1])=="jeevansathi.com")
		$flag=3;
    	return $flag;
}

function checkoldemail($email,$profileid='')     // returns 1 if email id not valid
{
        $flag=0;
        if( trim($email) !="")
		{
			$sql="SELECT count(*) FROM OLDEMAIL where OLD_EMAIL='$email'";
			if($profileid)
				$sql.=" AND PROFILEID<> $profileid";
			$result = mysql_query_decide($sql) or logError("error",$sql);
        	$myrow = mysql_fetch_row($result);
        	
        	if($myrow[0] > 0)
                	$flag=2;
        }
        
    	return $flag;
}

function isvalid_username($string)
{
   	$invalidchar = array('#','\'','"','\\','/',' ','!','@','$','%','^','&','*','?','<','>','+','[',']','{','}','|',':',' ','~','`','=');

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
		$result = mysql_query_decide("SELECT USERNAME FROM NAMES where USERNAME='$username'") or logError("error",$sql);
		
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

function check_username_jprofile($username)
{
	$username_flag=0;
	if( trim($username)=="" || strlen($username)<2 || strlen($username)>20)
    	{
        	$username_flag=1; //return 1 if Username is blank or not in 2-20 range
			return $username_flag;
	}
   	 else
	{
		$result = mysql_query_decide("SELECT USERNAME FROM JPROFILE where USERNAME='$username'") or logError("error",$sql);
		
		if(mysql_num_rows($result) > 0)
			return 2;// return 2 if username already exists
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
	$email=trim($email);
	
	if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i", $email))
		return false;
    
	$part=explode("@",$email);
	
	if(strtolower($part[1])=="jeevansaathi.com")
		return false;
	elseif(strtolower($part[1])=="jeevansathi.com")
		return false;

	return true;
}

function tieup_creative($source)
{
        global $smarty;
        if (!$source)
                $source='0';
        $sql="SELECT * FROM jsadmin.BANNER_TEMPLATE WHERE STATUS='A' AND SOURCEID='$source'";
        $res=mysql_query_decide($sql) or die(mysql_error_js());
        if(mysql_num_rows($res)==0)
        {
                $sql="SELECT * FROM jsadmin.BANNER_TEMPLATE WHERE STATUS='A' AND SOURCEID='0'";
                $res=mysql_query_decide($sql) or die(mysql_error_js());
        }
        $row=mysql_fetch_array($res);
        $temp_topband=$row['TOP_BAND'];
        $temp_headline=$row['HEADLINE'];

        $smarty->assign("temp_topband",$temp_topband);
        $smarty->assign("temp_headline",$temp_headline);
}

function username_gen()
{
        $arr=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $username='';
                                                                                                                            
        $sql="INSERT INTO newjs.AUTOID VALUES ('')";
        mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        $num=mysql_insert_id_js();
                                                                                                                            
        $i=0;
        $ind=strlen($num)-4;
        $a=substr($num,0,$ind);
        while($i<strlen($a))
        {
                $b=$a[$i]*25;
                $ret=$b%26;
                $username.=$arr[$ret];
                $i++;
        }
        $b=substr($num,$ind);
        $username.=$b;
        return $username;
}

function redo_mobile_no($p)
{
	$originalnumber=$p;
	$len=strlen($p);
	if(strlen($p)<5)
                $p="";
	else
	{
		$p=leaveonlydigits($p);
		if(strlen($p)<5)
                        $p="";
		else
		{
			$flag=tenth_digit_nine($p);
			if($flag==1)
			{
				$mb=1;
				$flag_0_91=digits_0_91($p);
				if($flag_0_91==1)
					$p=take_last_ten_digits($p);
				else
					$p=$originalnumber;
			}
			else
				$p=$originalnumber;
			if(strlen($p)<5)
				$p="";
		}
	}
	return $p;
}

function leaveonlydigits($num)
{
	$len=strlen($num);
	$newnumber="";
	for($i=0;$i<$len;$i++)
	{
		$digit=substr($num,$i,1);
		if(($digit>0 && $digit<10) || $digit=="0")
			$newnumber=$newnumber.$digit;
	}
	return $newnumber;
}

function tenth_digit_nine($num)
{
	$len=strlen($num);
	$pos=$len-10;
	if(substr($num,$pos,1)=="9")
		return 1;
	else
		return 0;
}

function take_last_ten_digits($num)
{
	$len=strlen($num);
	$first_digit=$len-10;
	$newnumber=substr($num,$first_digit,10);
	return $newnumber;
}

function digits_0_91($num)
{
	if(strlen($num)==10)return 1;
        $f1=$len-11;//for 0
	$f2=$len-12;//for 91
	$f2=$len-13;//for 091
        if(strlen($num)==11 && substr($num,$f1,1)=="0")
		return 1;
	else if(strlen($num)==12 && substr($num,$f2,2)=="91")
		return 1;
	else if(strlen($num)==13 && substr($num,$f2,2)=="091")
		return 1;
	else
		return 0;
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

function setFilterReligion($rel, $mtongue)
{
    $s="";
	if($rel==1) //Hindu
	{
		if($mtongue=='27' || $mtongue=='13')
			$s="'1','4','9'";
		elseif($mtongue=='20' || $mtongue=='34')
			$s="'1','7','9'";
		else
		{
			/* Changes done as per Trac#392 */
			$mt="'".$mtongue."'";
			$all_hindi="'6','7','10','19','25','28','33'";	
			if(strstr($all_hindi,"$mt"))
				$s="'1','9'";
			else
				$s="'".$rel."'";
		}
	}
	elseif($rel==2)
		$s="'2','5'"; //muslim
	elseif($rel==3)
		$s="'3','6'"; //christian
	elseif($rel==4)
		$s="'1','4'"; //sikh
	elseif($rel==5)
		$s="'1','2','5','6'";//parsi
	elseif($rel==6)
		$s="'1','3','6'"; //jewish
	elseif($rel==7)
		$s="'1','7'";//buddhist
	elseif($rel==9)
		$s="'1','9'"; //jain

	return $s;
}
?>
