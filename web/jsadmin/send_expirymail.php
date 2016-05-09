<?php
//Deactivated the page JSC-1358
die("This link has been disabled");
include("connect.inc");
include("../crm/mainmenunew.php");
include("../billing/comfunc_sums.php");
include "../billing/functions.php";

$ts=time();
$dir=date("mY",$ts);
$d=date("d",$ts);
$curdate = date("Y-m-d",$ts);

list($todYear,$todMonth,$todDay)=explode("-",$curdate);

if($submit)
{
	$sql= "SELECT PROFILEID,EMAIL,ACTIVATED FROM newjs.JPROFILE WHERE USERNAME='$uname'";
	$res= mysql_query_decide($sql) or die(mysql_error_js());
	$row= mysql_fetch_assoc($res);

	$profile=$row['PROFILEID'];
	$email=$row['EMAIL'];
	$activated= $row['ACTIVATED'];
	if($profile)
	{
		$sql1= "SELECT EXPIRY_DT,SERVICEID FROM billing.SERVICE_STATUS WHERE PROFILEID='$profile' ORDER BY EXPIRY_DT";
		$res1= mysql_query_decide($sql1) or die(mysql_error_js());
		while($row1= mysql_fetch_array($res1))
		{
			$exp_date=$row1['EXPIRY_DT'];
			$ser=$row1['SERVICEID'];
			list($year,$month,$day)=explode("-",$exp_date);
		}
		if(mysql_num_rows($res1)>0)
		{
			if($exp_date<$curdate)
			{
				if($activated<> 'D')
				{
					$profs= $profile;
		                	$SID= $ser;
					$service = strtoupper(substr($SID, 0, 1));
				        if(!mysql_ping_js($db))
			        	        $db=connect_db();
					$page_mail=1;
			                profileview($profs);
					$ser_name= get_service_name($service);
                                        $smarty->assign("MEMBERSHIP",$ser_name);
	
			                $smarty->assign("EXPIREDATE",my_format_date($day,$month,$year));
		
			                $smarty->assign("USERNAME",$uname);
			                $smarty->assign("EMAIL",$email);
			                $attachment = $smarty->fetch("subscription_renew_memberships.htm");
                	        //$smarty->display("subscription_renew_memberships.htm");
/* Code removed to write the html files in disk, as earlier also the logic was not working, the mail was sent runtime, hence commented 	
        			        $path="/usr/local/subs_exp/";
	
		                	if(!is_dir($path.$dir))
		                		mkdir($path.$dir);
					$dir_n=$month.$year;
					$filename=$path.$dir."/".$profile."-".$day.".htm";
					

					if(file_exists($filename))
					{
						$handle = fopen($filename, "r");
	                                        while(!feof($handle))
	                                        {
        	                                        $contents = fread($handle,1024);
                	                                $attachment=$contents;
                	                        }
					}
					else
					{
			                	$fp=fopen("$path$dir/$profile"."-"."$d.htm","w+");
	        		        	if($fp)
	        	        		{
	                				fwrite($fp,$attachment);
			                        	fclose($fp);
		                		}
	                        //passthru("/bin/echo \"".addslashes($attachment)."\" > $path$dir/$row[PROFILEID]"."-"."$d.htm ");
				//echo $attachment;die;
					}
*/
		                	$retval=sendmail($from_email,$email,'','',$subject1,$attachment);
        	                //$retval=sendmail($from_email,'shobha.solanki@gmail.com','','',$subject1,$attachment);
		                	if($retval)
		                		$msg .= "\n Expiry mail can not be sent to $email due to $retval";
					else
						$msg= "Expiry mail sent to ".$email;
					
				}
				else
					$msg= "Mail not sent(User is deleted)";
			}
			else
				$msg= "Mail not sent(Membership of the user still active)";
		}
		else
			$msg= "Mail not sent(User is not a paid member)";
	}
	else
		$msg= "No such user exists!!";	
		$smarty->assign("msg",$msg);
}

/*for($i=0;$i<31;$i++)
        {
                $ddarr[$i]=$i+1;
        }
        $k=0;
        while($k<=5)
        {
                $yyarr[]=$todYear-$k;
                $k++;
        }
        $mmarr = array(
                        array("NAME" => "Jan", "VALUE" => "01"),
                        array("NAME" => "Feb", "VALUE" => "02"),
                        array("NAME" => "Mar", "VALUE" => "03"),
                        array("NAME" => "Apr", "VALUE" => "04"),
                        array("NAME" => "May", "VALUE" => "05"),
                        array("NAME" => "Jun", "VALUE" => "06"),
                        array("NAME" => "Jul", "VALUE" => "07"),
                        array("NAME" => "Aug", "VALUE" => "08"),
                        array("NAME" => "Sep", "VALUE" => "09"),
                        array("NAME" => "Oct", "VALUE" => "10"),
                        array("NAME" => "Nov", "VALUE" => "11"),
                        array("NAME" => "Dec", "VALUE" => "12"),
                );
                $smarty->assign("ddarr",$ddarr);
//print_r($mmarr);
                $smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
*/
$smarty->display("send_expirymail.htm");


?>

