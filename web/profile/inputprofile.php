<?php

	include("inputprofile_tieup.php");
	//include("ip_new_1.php");
	
/*
include("connect.inc");
include("hits.php");
connect_db();

$ip=FetchClientIP();//Gets ipaddress of user
$smarty->assign("CAMEFROMHOMEPAGE","1");

if($source=="")
{
	if(isset($_COOKIE['JS_SOURCE']))
	{
		$source=$_COOKIE['JS_SOURCE'];
	}
}

if($source!="")
{
	$pagename=$_SERVER['PHP_SELF'];
        savehit($source,$pagename);
}

if($Submit)
{
	$is_error=0;
	
	$Religion_temp = explode('|X|',$Religion);
	$Religion = $Religion_temp[0];
		
	//Wrong or blank entry validation
	if($Relationship=="")
	{
		$is_error++;
		$smarty->assign("check_relationship","Y");
	}
	
	if($Gender=="")
	{
		$is_error++;
		$smarty->assign("check_gender","Y");
	}
	
	if($Religion=="")
	{
		$is_error++;
		$smarty->assign("check_religion","Y");
	}
	
	if($Caste=="")
	{
		$is_error++;
		$smarty->assign("check_caste","Y");
	}
	
	if($Manglik_Status=="")
	{
		$is_error++;
		$smarty->assign("check_manglik","Y");
	}
	
	if($Mtongue=="")
	{
		$is_error++;
		$smarty->assign("check_mtongue","Y");
	}
	
	if($Marital_Status=="")
	{
		$is_error++;
		$smarty->assign("check_marital","Y");
	}
	
	if($Family_Back=="")
	{
		$is_error++;
		$smarty->assign("check_familyback","Y");
	}
	
	if($Country_Residence=="")
	{
		$is_error++;
		$smarty->assign("check_countryres","Y");
	}
	
	if($Height=="")
	{
		$is_error++;
		$smarty->assign("check_height","Y");
	}
	
	if($Body_Type=="")
	{
		$is_error++;
		$smarty->assign("check_bodytype","Y");
	}
	
	if($Complexion=="")
	{
		$is_error++;
		$smarty->assign("check_complexion","Y");
	}
		
	
	//if(trim($Messenger_ID) != "")
	//{
	//	if(!$Messenger)
	//	{
	//		$is_error++;
	//		$smarty->assign("check_messenger","Y");
	//	}
	//}
	
	if($Caste)
	{	
		$sql="SELECT PARENT from CASTE WHERE VALUE=$Caste";
		$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		$myrow=mysql_fetch_row($result);
	}
	else
	{
		$is_error++;
		$myrow[0]=-1;
		$smarty->assign("check_caste","Y");
	}
    	
	if($Religion!="" && $myrow[0]!=$Religion)
	{
		$is_error++;
		$Caste="";
		$smarty->assign("check_caste","Y");
	}
	
	$Email=trim($Email);
	
	$check_email=checkemail($Email);
	$check_old_email=checkoldemail($Email);	
	if($check_email!=0 || $check_old_email!=0)
	{
		$is_error++;
		if($check_old_email==2)
			$check_email=$check_old_email;
		$smarty->assign("check_email",$check_email);
	}
	
	$check_user=check_username($Username);
	if($check_user!=0)
	{
		$is_error++;
		$smarty->assign("check_user",$check_user);
	}
	
	$check_user1=isvalid_username($Username);
	if($check_user1!=0)
	{
		$is_error++;
		$smarty->assign("check_user1",$check_user1);
	}
	
	$check_password1=check_password($Password1,$Username);
	if($check_password1!=0)
	{
		$is_error++;
		$smarty->assign("check_password1",$check_password1);
	}
	
	$confirm_password=confirm_password($Password1,$Password2);
	if($confirm_password!=0)
	{
		$is_error++;
		$smarty->assign("confirm_password",$confirm_password);
	}
	
	$check_date=validate_date($Day,$Month,$Year);
	if($check_date==1)
	{
		$is_error++;
		$smarty->assign("check_date",$check_date);
	}
	elseif($Gender!="")
	{
		$array = array($Year, $Month, $Day);
		$date_of_birth= implode("-", $array);
		$age=getAge($date_of_birth);
		
		if($Gender=="M" && $age < 21)
		{
			$is_error++;
			$smarty->assign("DATEOFBIRTH_LESS",1);
		}
		elseif($Gender=="F" && $age < 18)
		{
			$is_error++;
			$smarty->assign("DATEOFBIRTH_LESS",1);
		}
	}
	
	if(trim($Phone)=="" && trim($Mobile)=="")
	{
		$is_error++;
		$smarty->assign("check_phone","Y");
	}
	
	if($is_error > 0)
	{
		$smarty->assign("NO_OF_ERROR",$is_error);
		
		$religion=populate_religion($Religion);
		$smarty->assign("religion",$religion);
		
		$caste=populate_caste($Caste);
		$smarty->assign("caste",$caste);
		
		$mtongue=create_dd($Mtongue,"Mtongue");
		$smarty->assign("mtongue",$mtongue);
		$family_back=create_dd($Family_Back,"Family_Back");
		$smarty->assign("family_back",$family_back);
		$country_residence=create_dd($Country_Residence,"Country_Residence");
		$smarty->assign("country_residence",$country_residence);
		$smarty->assign("top_country",create_dd($Country_Residence,"top_country"));
		$height=create_dd($Height,"Height");
		$smarty->assign("height",$height);
		$smarty->assign("manglik",$Manglik_Status);
		$smarty->assign("gender",$Gender);
		$smarty->assign("marital",$Marital_Status);
		$smarty->assign("body",$Body_Type);
		$smarty->assign("complexion",$Complexion);
		$smarty->assign("phyhcp",$Phyhcp);
		$smarty->assign("relationship",$Relationship);
		$smarty->assign("has_children",$Has_Children);
		$smarty->assign("username",$Username);
		$smarty->assign("password1",$Password1);
		$smarty->assign("password2",$Password2);
		$smarty->assign("email",$Email);
		$smarty->assign("subcaste",$Subcaste);
		$smarty->assign("nakshatram",$Nakshatram);
		$smarty->assign("gothra",$Gothra);
		$smarty->assign("phone",$Phone);
		$smarty->assign("mobile",$Mobile);
		$smarty->assign("day",$Day);
		$smarty->assign("month",$Month);
		$smarty->assign("year",$Year);
		
		if($Showphone)
		{
			$smarty->assign("showphone","N");
			$showphone="N";
		}
		else
		{
		    $smarty->assign("showphone","Y");
			$showphone="Y";
		}
			
		if($Showmobile)
		{
			$smarty->assign("showmobile","N");
			$showmobile="N";
		}
		else
		{
	        $smarty->assign("showmobile","Y");
			$showmobile="Y";
		}
	
		$smarty->assign("TIEUPSOURCE",$tieup_source);
		
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$smarty->assign("HEAD",$smarty->fetch("head.htm"));
		$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
		$smarty->display("inputprofile.htm");
		
	}
	else
	{
		//age calculation from DOB
		$array = array($Year, $Month, $Day);
		$date_of_birth= implode("-", $array);
		$age=getAge($date_of_birth);
		
		$Religion_temp = explode('|X|',$Religion);
		$Religion = $Religion_temp[0];
		
		if($Showphone)
			$showphone="N";
		else
			$showphone="Y";
			
		if($Showmobile)
			$showmobile="N";
		else 
			$showmobile="Y";
		
		if($tieup_source=="")
			$tieup_source="IP";
			
		$today=date("Y-m-d");

        	$sql = "INSERT INTO JPROFILE (USERNAME,PASSWORD,EMAIL,RELATION,GENDER,RELIGION,CASTE,SUBCASTE,MANGLIK,MTONGUE,MSTATUS,HAVECHILD,DTOFBIRTH,NAKSHATRA,GOTHRA,FAMILY_BACK,PHONE_RES,PHONE_MOB,COUNTRY_RES,HEIGHT,BTYPE,COMPLEXION,HANDICAPPED,ENTRY_DT,MOD_DT,LAST_LOGIN_DT,INCOMPLETE,SHOWPHONE_RES,SHOWPHONE_MOB,AGE,IPADD,SOURCE,HAVEPHOTO,ACTIVATED,SHOWADDRESS,SHOWMESSENGER,PROMO_MAILS,SERVICE_MESSAGES,PERSONAL_MATCHES) VALUES ('$Username','$Password1','$Email','$Relationship','$Gender','$Religion','$Caste','$Subcaste','$Manglik_Status','$Mtongue','$Marital_Status','$Has_Children','$date_of_birth','$Nakshatram','$Gothra','$Family_Back','$Phone','$Mobile','$Country_Residence','$Height','$Body_Type','$Complexion','$Phyhcp',now(),now(),$today,'Y','$showphone','$showmobile','$age','$ip','$tieup_source','N','N','N','N','S','S','A')";
                                                                                                   
	    	$result= mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
	    
		$id=mysql_insert_id_js();
		
		$tm=time();
		
		$sql="insert into CONNECT(ID,USERNAME,PASSWORD,PROFILEID,SUBSCRIPTION,TIME1,GENDER,ACTIVATED) values ('','$Username','$Password1','$id','','$tm','$Gender','N')";
		$result= mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		
		$checkid=mysql_insert_id_js();
		$checksum=md5($checkid) . "i" . $checkid;

		setcookie("JS_SOURCE","",0,"/");

		showPart2($checksum,$Country_Residence);
    }
}
else
{
	$smarty->assign("religion",populate_religion());
	$smarty->assign("caste",create_dd("","Caste"));
	$smarty->assign("mtongue",create_dd("","Mtongue"));
	$smarty->assign("family_back",create_dd("","Family_Back"));
	$smarty->assign("country_residence",create_dd("","Country_Residence"));
	$smarty->assign("height",create_dd("","Height"));
	$smarty->assign("top_country",create_dd("","top_country"));
	
	$smarty->assign("showphone","Y");
	$smarty->assign("showmobile","Y");
	$smarty->assign("TIEUPSOURCE",$source);
	
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
	$smarty->display("inputprofile.htm");
}

function showPart2($checksum,$country_r)
{
	global $smarty;
	$cor=$country_r;

	include("inputprofile1.php");
}
*/
?>
