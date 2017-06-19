<?php
//include("connect.inc");
$change=array("1"=>"z","2"=>"a","3"=>"m","4"=>"p","5"=>"r","6"=>"b","7"=>"x","8"=>"y","9"=>"t","0"=>"f");

	
	$test=135791;
//issuevouchers(3,"NIKHIL");
//$db=connect_db();
//$smarty->template_dir="/home/nikhil/htdocs/smarty/templates/jsadmin/";
//$smarty->relative_dir="";
//rand_discount_no(1234567,"NIKHIL");
//send_success_email("nikhil","rashi","09090-09090-09090","dhiman_nikhil@yahoo.com");
//set_discount_code('amisha','adfad','nikhil.dhiman@jeevansathi.com',"3691-7868-6324-4332");
function set_discount_code($sender,$receiver,$email,$discount_code)
{
	global $smarty;
	$sql="select CODE from newjs.DISCOUNT_CODE where CODE='$discount_code' and USED!='Y'";
	$res=mysql_query_decide($sql) or die($sql.mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	if($row=mysql_fetch_row($res))
	{
		$receiver=htmlspecialchars($receiver,ENT_QUOTES);
		$sql="update  newjs.DISCOUNT_CODE set RECEIVER='$receiver',EMAIL='$email' where CODE='$discount_code'";
		mysql_query_decide($sql)  or die($sql.mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		


		send_success_email($sender,$receiver,$discount_code,$email);
		//send_email($to,'','',$msg,$subject,$from);
		return  $receiver;
	}
	
	
}
function get_discount_code($profileid)
{
	global $smarty;
	$profileid=decrypt($profileid);
	$sql="select CODE,NAME,RECEIVER,EMAIL from newjs.DISCOUNT_CODE where PROFILEID='$profileid' order by ID ASC limit 5 ";
	$res=mysql_query_decide($sql)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$i=1;
	$j=0;
	while($row=mysql_fetch_array($res))
	{
		
		$smarty->assign("sender",$row['NAME']);
		$smarty->assign("SENDER",$row['NAME']);
		
		if($row['RECEIVER']!="")
		{
			$SERIAL[$j]=$j;
			$SNO[$j]=$i;
			$CODE[$j]=$row['CODE'];
			$RECEIVER[$j]=$row['RECEIVER'];
			$EMAIL[$j]=$row['EMAIL'];
			$j++;
		}
		else 
			$smarty->assign("discount_code$i",$row['CODE']);
			
		$i++;	
	}
	$smarty->assign("SERIAL",$SERIAL);
	$smarty->assign("SNO",$SNO);
	$smarty->assign("code",$CODE);
	$smarty->assign("RECEIVER",$RECEIVER);
	$smarty->assign("EMAIL",$EMAIL);
}
function send_success_email($SENDER,$RECEIVER,$DISCOUNT_CODE,$EMAIL)
{
	global $smarty;
	$to=$EMAIL;
	$from="promotions@jeevansathi.com";
	$subject="$SENDER found a match on jeevansathi.com";
	$smarty->assign("sender",$SENDER);
	$smarty->assign("receiver",$RECEIVER);
	$smarty->assign("discount_code",$DISCOUNT_CODE);
	$msg=$smarty->fetch("friend_discount.htm");
	
//$to='nikhil.dhiman@jeevansathi.com';	
 //$headers  = 'MIME-Version: 1.0' . "\r\n";
//$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
//$headers.="From:Promotions JeevanSathi.com < $from >" . "\r\n";



send_email($to,$msg,$subject,$from);

// Mail it
	//mail($to, $subject, $msg, $headers); 
	//send_mail($to,'','',$msg,"$subject","$from");
}
function rand_discount_no($profileid,$NAME,$email,$details="")
{
	$min=1000;
	$max=9999;
	if(is_array($details))
	{
		$now = date('Y-m-d G:i:s');
		$number_of_codes = $details["NUMBER_OF_CODES"];
		for($j=0;$j<$number_of_codes;$j++)
		{
			do
			{
				unset($rand);
				for($i=0;$i<4;$i++)
					$rand[] = rand($min,$max);

				$rand_num = @implode("-",$rand);

				$sql = "SELECT ID FROM newjs.DISCOUNT_CODE WHERE CODE='$rand_num'";
				$res = mysql_query_decide($sql);
				if($row = mysql_fetch_array($res))
					$type=1;
				else
				{
					if(!$details['ACTIVE'])
						$sql="INSERT INTO newjs.DISCOUNT_CODE(CODE,ENTRY_DT,NAME_OF_CODE,DISCOUNT_PERCENT,DISCOUNT_START_DATE,DISCOUNT_END_DATE,DISCOUNT_MESSAGE,ENTRY_BY,ACTIVE) values('$rand_num','$now','$details[NAME]','$details[PERCENT]','$details[START_DATE]','$details[END_DATE]','$details[MESSAGE]','$details[ENTRY_BY]','N')";
					else
						$sql="INSERT INTO newjs.DISCOUNT_CODE(CODE,ENTRY_DT,NAME_OF_CODE,DISCOUNT_PERCENT,DISCOUNT_START_DATE,DISCOUNT_END_DATE,DISCOUNT_MESSAGE,ENTRY_BY) values('$rand_num','$now','$details[NAME]','$details[PERCENT]','$details[START_DATE]','$details[END_DATE]','$details[MESSAGE]','$details[ENTRY_BY]')";
					mysql_query_decide($sql) or die(mysql_error_js());
					$type=0;
				}
			}
			while($type);
		}
	}
	else
	{
		global $smarty;
		if($NAME=="")
			$NAME="Jeevansathi member";
			
		$string=encrypt($profileid);

		for($j=0;$j<5;$j++)
		{
			do

			{
				$rand='';
				for($i=0;$i<4;$i++)
				$rand.=rand($min,$max)."-";
				$rand=substr($rand,0,strlen($rand)-1);
				$sql="Select ID from newjs.DISCOUNT_CODE where CODE='$rand'";
				$res=mysql_query_decide($sql);
				if($row=mysql_fetch_array($res))
				$type=1;
				else
				{
					$sql="Insert into newjs.DISCOUNT_CODE(`CODE`,`PROFILEID`,`NAME`,`TYPE`,`ENTRY_DT`) values('$rand','$profileid','$NAME','SS',now());";
					mysql_query_decide($sql) or die(mysql_error_js());
					$type=0;
				}
			}
			while($type);

			$random[]=$rand;
			$count[]=$j;

		}
		$smarty->assign("DISCOUNT",$count);
		$smarty->assign("rand",$random);
		$smarty->assign("NAME",$NAME);
		$smarty->assign("profileid",$string);
		$msg=$smarty->fetch("../jeevansathi/send_discount.htm");
		$from="promotions@jeevansathi.com";
		$to=$email;
		$subject="Invite your friends to jeevansathi.com";
		
	//$to='nikhil.dhiman@jeevansathi.com';	
		send_mail($to,'','',$msg,"$subject","$from");
	}
}
function decrypt($profileid)
{
	global $change,$test;
	
	foreach($change as $key=>$val)
		$rev_change[$val]=$key;
	
	
	$string=strrev($profileid);
	$chars = preg_split('//', $string, -1, PREG_SPLIT_NO_EMPTY);
	for($i=0;$i<count($chars);$i++)
	{
			$profile.=$rev_change[$chars[$i]];
	}
	$profile=$profile-$test;
	return  $profile;

}
function encrypt($profileid)
{
	
	global $change,$test;
	
	
	$plusid=$test+$profileid;

	while(intval($plusid/10)>0)
	{
        $str[]=$plusid%10;
        $plusid=$plusid/10;
	}
	$str[]=$plusid%10;
	for($i=0;$i<count($str);$i++)
	{
	        $string.=$change[$str[$i]];
	}
	return $string;
}

function issuevouchers($profileid,$NAME,$storyid)
{
        global $db,$smarty,$email,$cityres;
        if($NAME=="")
                $NAME="Jeevansathi member";
//      $db=connect_db();
        $sqlnri="SELECT COUNTRY_RES FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
        $resnri=mysql_query_decide($sqlnri) or die("$sqlnri".mysql_error_js());
        $rownri=mysql_fetch_assoc($resnri);
        if($rownri["COUNTRY_RES"]!='51')
        $option=options_available($cityres,"1");
        else
        $option=options_available($cityres);

        $options=explode(',',$option);//print_r($options);
        //if(count($options)<5)
        //$real_count=count($options);
        //else
        //$real_count=5;

        for($i=0;$i<count($options);$i++)
        {
                $sql_voucher="SELECT VOUCHER_NO,TYPE,ID FROM billing.VOUCHER_NUMBER WHERE ID=(SELECT MIN(ID) min FROM billing.VOUCHER_NUMBER WHERE ISSUED='' AND CLIENTID='$options[$i]')";
                $res_voucher=mysql_query_decide($sql_voucher) or die("$sql_voucher".mysql_error_js());
                $row_voucher=mysql_fetch_array($res_voucher);
                $voucher_no=$row_voucher['VOUCHER_NO'];
                if($voucher_no)//If Voucher No. exists for the particular Client
                {
                        if($row_voucher['TYPE']=='E')//For the case of E-Vouchers
                        {
                                $smarty->assign('voucher_no',"$voucher_no");
                                $smarty->assign('Name',"$Name");
                                $sql_gv="SELECT TEMPLATE,CLIENTID,CLIENT_NAME,HEADLINE FROM billing.VOUCHER_CLIENTS WHERE CLIENTID='$options[$i]'";
$res_gv=mysql_query_decide($sql_gv) or die("$sql_gv".mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_gv,"ShowErrTemplate");
                                $row_gv=mysql_fetch_array($res_gv);

                                $vouchers[]=$i;
                                $client_name[]=$row_gv['CLIENT_NAME'];
                                $client_id[]=$row_gv['CLIENTID'];
                                $HEADLINE[]=$row_gv['HEADLINE'];
                                $voucherno[]=$voucher_no;

                                 $sql_update="UPDATE billing.VOUCHER_NUMBER SET ISSUED='Y',PROFILEID='$profileid',STORYID='$storyid',ISSUE_DATE=now(),SOURCE='SUCCESS' WHERE ID='$row_voucher[ID]'";//Mark Issued for the E-Vouchers.
                                                mysql_query_decide($sql_update) or die("$sql_update".mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_update,"ShowErrTemplate");
                        }
                }
        }
        $smarty->assign("NAME",$NAME);
        $smarty->assign("voucherno",$voucherno);
        $smarty->assign("vouchers",$vouchers);
        $smarty->assign("client_name",$client_name);
        $smarty->assign("HEADLINE",$HEADLINE);
        $smarty->assign("client_id",$client_id);
        $smarty->assign("storyid",$storyid);
        $msg=$smarty->fetch("../jeevansathi/gift_vouchers.htm");
        $sql="UPDATE billing.VOUCHER_SUCCESSSTORY SET SELECTED='Y',OPTIONS_AVAILABLE='$option',DISPATCH_DATE='$today'  WHERE STORYID='$storyid'";
        mysql_query_decide($sql) or die(mysql_error_js());
        //echo $msg;

//$email='nikhil.dhiman@jeevansathi.com';
        send_mail("$email",'','',$msg,'Thank you for submitting your success story on jeevansathi.com','Promotions@jeevansathi.com');
}

function options_available($city_india,$nri=0)//To fetch the available options for the user
{

	$sql="SELECT CLIENTID,AVAILABLE_IN,SLABS FROM billing.VOUCHER_CLIENTS WHERE SERVICE='Y' AND CLIENTID!='VLCC01' ";
	$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	while($row=mysql_fetch_array($res))
	{
		if($city_india)
		{
			$place=explode(':',$row['AVAILABLE_IN']);
			$city=explode(',',$place[0]);
			$state=explode(',',$place[1]);
			if($row["AVAILABLE_IN"]=='')
			{
				$option_available[]=$row['CLIENTID'];
			}
			else
			{
				if($city)
				{
					if(in_array($city_india,$city) || in_array(substr($city_india,0,2),$state))
					$option_available[]=$row['CLIENTID'];
					else
					continue;
				}
				elseif($state)
				{
					if(in_array(substr($city_india,0,2),$state))
					$option_available[]=$row['CLIENTID'];
					else
					continue;
				}
			}
		}
		else
		{
			if($row["AVAILABLE_IN"]=='')
			$option_available[]=$row['CLIENTID'];
		}
		if($row["CLIENTID"]=="DM01" && $nri)
		$option_available[]="DM01";
	}//print_r($option_available);die

	if(count($option_available)>0)
	return $options_available=implode(',',$option_available);
}
