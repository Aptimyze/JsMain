<?php
/*****************************************************************************************************************************           FILE NAME      : yes_no_mail.php
*           DESCRIPTION    : Allows sendingOn Demand yes-no mailer from CRM
*           FILES INCLUDED : connect.inc ; functions used : authenticated()(for authentication of the user) 
*           CREATION DATE  : 12 June, 2008
*           CREATED BY     : Neha Verma
*           Copyright  2005, InfoEdge India Pvt. Ltd.
****************************************************************************************************************************/
include_once(JsConstants::$docRoot."/commonFiles/sms_inc.php");
include("connect.inc");
$table='DAILY_CONTACT_SMS';
include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
$mysqlObj=new Mysql;

include_once("../profile/contacts_functions.php");

$db=connect_db();
if(authenticated($cid))
{
        $name= getuser($cid);

        if($submit)
        {
		$msg='';$wrong=0;
                $sql= "SELECT PROFILEID,USERNAME,SOURCE,PHONE_MOB FROM newjs.JPROFILE WHERE USERNAME IN ('$caller','$recipient')";
                $res=mysql_query_decide($sql) or die(mysql_error_js());
                while($row=mysql_fetch_array($res))
                {
                        if($row["USERNAME"]==$recipient)
                        {
                                $receiver=$row["PROFILEID"];
                                $USERNAME=$row['USERNAME'];
                                $source=$row['SOURCE'];
                                $mobile=$row['PHONE_MOB'];
                        }
                        else
                        {
                                $sender=$row["PROFILEID"];
                        }
                        $uname[]=$row['USERNAME'];

                }
                if(is_array($uname))
		{
                        if(!in_array($caller,$uname))
                        {
                                $wrong++;
                                $msg="No such Caller exists!!";
                        }
                        elseif(!in_array($recipient,$uname))
                        {
                                $wrong++;
                                $msg="No such Recipient exists!!";
                        }
                        else
                        {
				if($mobile)
				{
					$mobile=mobile_correct_format($mobile);
	                                $rec_is_correct1=ifValidNumber($mobile);
                	                $checkmobile = smsinc_checkmphone($mobile);

					$curdate = date("Y-m-d");
					if(($rec_is_correct1) && (!$checkmobile))
	                                {
                                		$sql="SELECT COUNT(*) AS CNT FROM jsadmin.OnDEMAND_MAIL_SMS WHERE RECIPIENT='$receiver' AND DATE='$curdate' AND SOURCE='sms'";
	                                	$res= mysql_query_decide($sql) or die(mysql_error_js());
	                                	$row=mysql_fetch_array($res);
	                                	if($row['CNT']>0)
	                                	{
	                                	        $wrong++;
	                                	        $msg= "On-Demand SMS cannot be sent more than once a day to a Recipient";
	                                	}
	                                	else
	                                	{
							/*$sql1="SELECT TIME FROM newjs.CONTACTS WHERE SENDER='$sender' AND RECEIVER='$receiver' AND TYPE='I' AND TIME>=DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
	                                	        $res1= mysql_query_decide($sql1) or die(mysql_error_js());
	                                	        $row= mysql_fetch_array($res1);
	                               		        if($row["TIME"])
	                               	         	{*/
							$contactResult=getResultSet("SENDER,TIME","$sender","",$receiver,"","'I'","","TIME >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)");
		                                        if(is_array($contactResult))
		                                        {
		                                                $TIME=$contactResult[0]["TIME"];
		                                        }
		                                        unset($contactResult);

							if($TIME)
		                                        {
		                                                $dt=explode(" ",$TIME);
								list($y1,$m1,$d1)=explode("-",$dt[0]);
						                $data_timestamp=mktime(0,0,0,$m1,$d1,$y1);
								$date= date("jS M Y",$data_timestamp);
								$message="User $caller contacted you on $date. Please login to www.jeevansathi.com to accept or decline the contact";
								/* Trac #364
		                                                $valid_rec=send_sms($message,'',$mobile,$receiver,$table,'Y');
								if($valid_rec)
								{
									$msg="SMS sent!!";
									$sql= "INSERT INTO jsadmin.OnDEMAND_MAIL_SMS(RECIPIENT,DATE,SOURCE) values('$receiver','$curdate','sms')";
									mysql_query_decide($sql,$db) or die(mysql_error_js());
								}
								else
									$msg="SMS not sent.. Try again!!!";
								*/
		                                        }
							else
							{
								$wrong++;
                                                		$msg="There is no EOI from Caller to Recipient";
							}				
						}
					}
					else
					{
						$wrong++;
	                                        $msg= "Recipent doesn't have a valid mobile number";
					}
				}
				else
				{
					$wrong++;
                                        $msg= "Recipent doesn't have a mobile number";
				}
			}
		}
		else
                {
                        $msg="No such Caller and Recipient exists!!";
                        $wrong++;
                }

                $smarty->assign("wrong",$wrong);
                $smarty->assign("msg",$msg);

	
	}
	$smarty->assign("sms",'1');
        $smarty->assign("user",$name);
        $smarty->assign("cid",$cid);
        $smarty->display("yes_no_mail.htm");

}
else
{
        $msg = "Your session has been timed out<br>  ";
        $msg.= "<a href=\"index.htm\">";
        $msg.= "Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}

?>
