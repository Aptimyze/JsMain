<?php


//********************THIS FILE NEEDS TO BE RUN THROUGH CRONTAB***********************//

/**
*	Filename	:	mmm_view_template.php
*	Description	:	This file writes a template to show the view of the template before it is to be fired.To give a view of the mail the mail is first fetched from database then written in a seprate template directory --> templates2  and cache directory --> templates_c2
**/


function check_server_table($mailer_id)
{
	$table_name= $mailer_id."mailer_s1";
        $sql="SHOW TABLES";
        $result=mysql_query($sql) or die("could not get table info ");
        $row=mysql_fetch_array($result);
        while($row=mysql_fetch_array($result))
        {
                if($row['Tables_in_mmmjs']==$table_name)
                {
			return 1;
                }
        }
}


          
		/**
		*	Function	:	get_mail_data
		*	Input		:	mailer_id
		*	Output		:	array of mail data (i.e. subject ,from address and content etc.)
		*	Description	:	this function finds the mail data for a particular mailer_id
		**/

function get_mail_data($mailer_id)
{
	$sql="SELECT * FROM MAIL_DATA WHERE MAILER_ID=$mailer_id AND ACTIVE='Y'";
	$result=mysql_query($sql) or die("Query  : $sql \nError :".mysql_error());
	$row=mysql_fetch_array($result);
	
	$arr=array("subject"=>$row[SUBJECT],
                   "f_email"=>$row[F_EMAIL],
		      "data"=>stripslashes($row[DATA])
		   );
		return $arr;
}


	
		/**
		*	Function	:	check_mailer
		*	Input		:	mailer_id
		*	Output		:	0 or 1
		*	Description	:	this function checks whether the mailer exist or not
		**/
function check_mailer($mailer_id)
{
	$sql="SELECT COUNT(*) AS COUNT FROM MAIN_MAILER WHERE MAILER_ID=$mailer_id";
	$result=mysql_query($sql) or die("Sql : $sql \n Error :".mysql_error());
	$row=mysql_fetch_array($result);
	$no=$row[COUNT];
	return $no;
}

	
		/**
		*	Function	:	get_mailer_info
		*	Input		:	mailer_id
		*	Output		:	array consisting the information of mailer(specified by mailer_id)
		*	Description	:	It finds information of mail_type and response_type of the mailer for given mailer_id
		**/
	
function get_mailer_info($mailer_id)
{
	$sql="SELECT MAIL_TYPE,RESPONSE_TYPE,MAILER_FOR FROM MAIN_MAILER WHERE MAILER_ID=$mailer_id";
	$result=mysql_query($sql) or die("Sql : $sql \n Error  :".mysql_error());
	$row=mysql_fetch_array($result);
	
	$mailer_info[0]=array("mail_type"=>$row[MAIL_TYPE],
			"response_type"=>$row[RESPONSE_TYPE],
			"mailer_for"=>$row[MAILER_FOR],
                              );
		
	return $mailer_info;
}
	
	
		/**
		*	Function	:	get_table_fields
		*	Input		:	mailer_id
		*	Output		:	array of fields(which needs to be sent in mail as data) 
		*	Description	:	It returns the array of fields of mailer_id FROM MAIL_VARS TABLE
		**/
function get_table_fields($mailer_id)
{
	$sql="SELECT VAR_NAME,TABLE_VAR_NAME FROM MAIL_VARS WHERE MAILER_ID=$mailer_id";
	$result=mysql_query($sql) or die("Sql : $sql \n Error  :".mysql_error());
	while($row=mysql_fetch_array($result))
	{
		$field_arr[]=array("var_name"=>$row[VAR_NAME],
		             "table_var_name"=>$row[TABLE_VAR_NAME]
			           );
	}
	return $field_arr;
}
	


function findchar($str,$chr)
{
	$start=strlen($str);
	if(strpos($str,$chr,$start))
		$str=substr($str,0,$start-1);

	return $str;
}



function get_sid($mailer_id)
{
        $tabarr = array();
        $sql = "select SID from MAILER_SERVER where MAILER_ID = '$mailer_id'";
        $res = mysql_query($sql) or die($sql." : ".mysql_error());
                                                                                                 
        while($row = mysql_fetch_array($res))
        {
                $tabarr[] = $row[SID];
                                                                                                 
        }
        return $tabarr;
}



//************************* MAIN MODULE STARTS HERE**********************************//

function mmm_view_template($mailer_id,$data)
{
	$add_addresslist_hcm = "<font face=\"Arial,Helvetica,sans-serif\" color=#999999 size=\"1\" >You are receiving this mail as a registered member of Jeevansathi.com<br>Please add this id to your address book to ensure delivery into your inbox</font> <br><br>";
	$add_addresslist_tpm = "<body><font face=\"Arial,Helvetica,sans-serif\" color=#999999 size=\"1\">You are receiving this mail as a registered member of Jeevansathi.com<br>Please add this id to your address book to ensure delivery into your inbox</font><br><br>";
/*	$add_addresslist_tpm_naukri = "<body><font face=\"Arial,Helvetica,sans-serif\" color=#999999 size=\"1\">You are receiving this mail as a registered member of naukri.com<br>Please add info@naukri.com to your address book to ensure delivery into your inbox</font><br><br>";*/

$add_addresslist_hcm99 = "<font face=\"Arial,Helvetica,sans-serif\" color=#999999 size=\"1\" > You are receiving this mail as a registered member of 99acres.com<br>Please add support@99acres.com to your address book to ensure delivery into your inbox </font><br><br>";
$add_addresslist_tpm99 = "<body><font face=\"Arial,Helvetica,sans-serif\" color=#999999 size=\"1\" >You are receiving this mail as a registered member of 99acres.com<br>Please add support@99acres.com to your address book to ensure delivery into your inbox </font><br><br>";




	echo "********".$mailer_id."************";
	global $smarty;
	include "arrays.php";
	$r=shell_exec("pwd");
	$realpath=realpath(substr($r,0,strlen($r)-1));
	//VARIABLES USED IN THIS PHP
	$response_php_path="http://ser2.jeevansathi.com/mmmjs/mmm_record_response.php";
	$unsubscribe_link="http://ser2.jeevansathi.com/mmmjs/unsubscribe.php";
	$unsubscribe_link_naukri="http://www.naukri.com/customised/unsubscribe/unsubscribe.php?mail_type=urm";
	// THIS IS SERVER 1
	$sid=1;
	echo("MAILER ID : ");echo $mailer_id;echo("\n");
	if(!check_mailer($mailer_id))
	{
		die;
	}
//		This array consists if info like mailtype and response type of mailer 		
	$mailer_info=get_mailer_info($mailer_id);
	echo "MAIL TYPE : ";echo $mail_type=$mailer_info[0][mail_type];echo "\n";
	echo "RESPONSE TYPE : ";echo $response_type=$mailer_info[0][response_type];echo "\n";
	echo "MAILER_FOR :";echo $mailer_for=$mailer_info[0][mailer_for];			
//		This functions returns the server table flag to be used to pick a random record	
	$server_table=check_server_table($mailer_id);
	if($server_table==1)	
		$table_name= $mailer_id."mailer_s1";
	else
		$table_name= $mailer_id."mailer_s2";
	echo $table_name;
	if($mail_type=='hcm' || $mail_type=='tpm')
	{
//			For Hard Code Mail
		echo "This is Hard Code Mail \n";
		echo "TEMPLATE NAME : ";echo $template_name=$mailer_id."view.tpl";echo "\n";
		echo "FILE OPEN";echo "\n";
		$fp1=fopen("$realpath/templates2/$template_name","w");
		$mail_data=$data;
		fputs($fp1,$mail_data);
		fclose($fp1);
		echo "FILE CLOSE";echo "\n";
//**********************ADD CODE FOR RANDOM RECORD***********************			

echo		$sql="SELECT * FROM $table_name LIMIT 0,1";echo "\n";
		$result=mysql_query($sql)  or die("Sql : $sql \n Error  :".mysql_error());	
		$row=mysql_fetch_array($result);
		print_r($row);
//		TEST MAIL DATABASE

		echo "TABLE NAME : ".$table_name=$mailer_id."mailer_s1";echo "\n";
		$arr=get_table_fields($mailer_id);
		echo "TABLE FIELDS : ";print_r($arr);
		$s_f=sizeof($arr);
		for($j=0;$j<$s_f;$j++)
		{
			echo "var name".$arr[$j][var_name]."\n";
			echo "table_var name".$arr[$j][table_var_name]."\n";
			echo"Var value is-->".$row[$arr[$j][table_var_name]]."\n";
			echo"Var double dollar is-->".$$arr[$j][var_name]=$row[$arr[$j][table_var_name]];

			//******CODE ADDED TO GET LABEL IN PLACE OF VALUES*****//
			$var_value=$row[$arr[$j][table_var_name]];
			$column_name=$arr[$j][table_var_name];
			$var_name=$arr[$j][var_name];
			$var_value=$row[$arr[$j][table_var_name]];
			if(array_key_exists("$column_name",$from_array))
			{
				$var_label=$from_array[$column_name][$var_value];
			}
			elseif(array_key_exists("$column_name",$from_table))
			{
				$var_label=label_select($from_table[$column_name],$var_value);	
			}
			elseif(in_array("$column_name",$from_date))
			{
				list($date,$time)=explode(" ",$var_value);
				list($yy,$mm,$dd)=explode("-",$date);
				$var_label=my_format_date($dd,$mm,$yy);
			}
			elseif(in_array("$column_name",$from_direct))
			{
				$var_label=$var_value;
			}	
			$smarty->assign("$var_name",$var_label);
			
			//****CODE ADDITION ENDS HERE******//
		}
		
		
		$smarty->assign("mail_type",$mail_type);
		$data=$smarty->fetch("$realpath/templates2/$template_name");
		
//				ADDING RESPOSE HEADER
		if($response_type=="i")
		{
			$response_header="<br><IMG src=$response_php_path?mailer_id=$mailer_id&email=$email&response_type=$response_type&sid=$sid width=\"0\" height=\"0\" border=\"0\"></body>";
		}
		elseif($response_type='o')
		{
			$response_header="<br><IMG src=$response_php_path?mailer_id=$mailer_id&response_type=$response_type width=\"0\" height=\"0\" border=\"0\"></body>";
		}
		else 
		{
			$response_header="</body>";
		}
	
//              ADDING UNSUBSCRIBE FOOTER
										 
		$unsubscribe_footer_hcm="<br><a href=$unsubscribe_link?mailer_id=$mailer_id>Click here to Unsubscribe </a>";
										 
		$unsubscribe_footer_tpm="<br><a href=$unsubscribe_link?mailer_id=$mailer_id>Click here to Unsubscribe </a></body>";
//              FINAL MESSAGE
		$sql_t = "SELECT MAILER_FOR FROM MAIN_MAILER WHERE MAILER_ID='$mailer_id'";
                $res_t = mysql_query($sql_t) or die("in mmm_fire_actual1.php $sql_t");
                $row_t = mysql_fetch_array($res_t);
                $Jor9 = $row_t['MAILER_FOR'];

		if($mail_type!='tpm')
		{
			if($Jor9=='J')
				$message="<html><body>".$add_addresslist_hcm.$response_header.$data.$unsubscribe_footer_hcm."</html>";
			else if($Jor9=='9')
				$message="<html><body>".$add_addresslist_hcm99.$response_header.$data.$unsubscribe_footer_hcm."</html>";
		}
		else
		{
			$message=preg_replace("/<\/body>/i",$response_header,$data);                                        $message=preg_replace("</body>",$unsubscribe_footer_tpm,$message);
			if($Jor9=='J')
				$message=preg_replace("/<body>/i",$add_addresslist_tpm,$message);
			else if($Jor9=='9')
				$message=preg_replace("/<body>/i",$add_addresslist_tpm99,$message);
		}
		return $message;	
		echo "MESSAGE : ".$message;
	}
	elseif(($mail_type=='urm'))
	{
		echo "This is url Mail \n";
		$template_name=$mailer_id."view.tpl";
		$fp1=fopen("$realpath/templates2/$template_name","w");
		$datat=$data;
		fputs($fp1,$datat);
		fclose($fp1);
		$sql="SELECT * FROM $table_name	LIMIT 0,1";							 $result=mysql_query($sql)  or die("Sql : $sql \n Error  :".mysql_error());
		$row=mysql_fetch_array($result);
		print_r($row);
		$s=sizeof($arr_test_emails);
		$table_name=$mailer_id."mailer_s1";
		$arr=get_table_fields($mailer_id);
					
		$s_f=sizeof($arr);
		for($j=0;$j<$s_f;$j++)
		{
			$$arr[$j][var_name]=$row[$arr[$j][table_var_name]];
			$var_name=$arr[$j][var_name];
			$smarty->assign("$var_name",$row[$arr[$j][table_var_name]]);
		}
		$smarty->assign("mail_type",$mail_type);
		if($response_type=="i")
		{
			$email=$row[EMAIL];
			$response_header="<br><IMG src=$response_php_path?mailer_id=$mailer_id&email=$email&response_type=$response_type&sid=$sid width=\"0\" height=\"0\" border=\"0\"></body>";
		}
		elseif($response_type='o')
		{
			$response_header="<br><IMG src=$response_php_path?mailer_id=$mailer_id&response_type=$response_type width=\"0\" height=\"0\" border=\"0\"></body>";
		}
		else 
		{
			$response_header="</body>";
		}
		$data=$smarty->fetch("$realpath/templates2/$template_name");
                $unsubscribe_footer_tpm="<br><a href=$unsubscribe_link?mailer_id=$mailer_id>Click here to Unsubscribe </a></body>";
		$unsubscribe_footer_tpm_naukri="<br><a href=$unsubscribe_link_naukri>Click here to Unsubscribe </a></body>";
//              FINAL MESSAGE
		$message=preg_replace("/<\/body>/i",$response_header,$data);
		if($mailer_for=='J')
		{
	                $message=preg_replace("/<\/body>/i",$unsubscribe_footer_tpm,$message);
        	        $message=preg_replace("/<body>/i",$add_addresslist_tpm,$message);
		}
		else if($mailer_for=='9')
                {
                        $message=preg_replace("/<\/body>/i",$unsubscribe_footer_tpm,$message);
                        $message=preg_replace("/<body>/i",$add_addresslist_tpm99,$message);
                }

		else
		{
			$message=preg_replace("/<\/body>/i",$unsubscribe_footer_tpm_naukri,$message);
			$message=preg_replace("/<body>/i",$add_addresslist_tpm_naukri,$message);
		}
		return $message;
	}
}
?>
