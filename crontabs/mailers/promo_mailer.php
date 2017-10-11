<?php

/**
*       Filename        :       promo_mailer.php
*       Description     :       script to send Promotional Mails to the Users.
*       Created by      :       Tanu Gupta
*       Created on      :       07-03-2007
**/

include "connect.inc";
connect_db();
mysql_query("set session wait_timeout=1000");

$ts=time();
$today=date('Y-m-d G:i:s',$ts);
$yday=mktime(0,0,0,date("m"),date("d")-2,date("Y"));//To calculate the day before yesterday.
$dbyesterday=date("Y-m-d",$yday);
$sday=mktime(0,0,0,date("m"),date("d")-7,date("Y"));//To calculate the day before 7 days from today.
$sevenday=date("Y-m-d",$sday);
$tday=mktime(0,0,0,date("m"),date("d")-30,date("Y"));//To calculate the day before 30 days from today.
$thirtyday=date("Y-m-d",$tday);
$from='Promo@jeevansathi.com';
$subject="Promotional Offers from Jeevansathi.com";

//To send the Reminder on Day 7 of registration (only if user has not filled the form by then)
//To fetch the detail of the users who are Registered before 7 days and have not filled the Promotional Form.
$sql="SELECT A.PROFILEID,A.USERNAME,B.EMAIL FROM newjs.PROMOTIONAL_MAIL A,newjs.JPROFILE B WHERE A.ENTRY_TIME BETWEEN '$sevenday 00:00:00' AND '$sevenday 23:59:59' AND A.SENDMAIL =1 AND A.RESPONSE_TIME='0000-00-00 00:00:00' AND B.PROMO_MAILS='S' AND A.PROFILEID=B.PROFILEID AND B.ACTIVATED<>'D'";
$res=mysql_query_decide($sql) or logError("error",$sql);

while($row=mysql_fetch_array($res))
{
	$profileid=$row['PROFILEID'];
        $username=$row['USERNAME'];
        $smarty->assign("profileid",$profileid);//To pass the profileid of the user with the mailer.
        $smarty->assign("username",$username);//To pass the username of the user with the mailer.
        $msg=$smarty->fetch("finance_mailer.html");
        $email=$row['EMAIL'];
        send_email($email,$msg,$subject,$from);

        $sql2="UPDATE newjs.PROMOTIONAL_MAIL SET SENDMAIL=2 WHERE PROFILEID='$profileid' AND SENDMAIL=1";//To make confirm that mail is sent twice to the user by increasing the sendmail counter to 2.
        mysql_query_decide($sql2) or logError("error",$sql2);
}

//To send the Reminder on Day 30 of registration (only if user has not filled the form by then)
//To fetch the detail of the users who are Registered before 30 days and have not filled the Promotional Form.
$sql="SELECT A.PROFILEID,A.SENDMAIL,A.USERNAME,B.EMAIL FROM newjs.PROMOTIONAL_MAIL A,newjs.JPROFILE B WHERE A.ENTRY_TIME BETWEEN '$thirtyday 00:00:00' AND '$thirtyday 23:59:59' AND A.RESPONSE_TIME='0000-00-00 00:00:00' AND B.PROMO_MAILS='S' AND A.PROFILEID=B.PROFILEID AND B.ACTIVATED<>'D'";
$res=mysql_query_decide($sql) or logError("error",$sql);

while($row=mysql_fetch_array($res))
{       
	$profileid=$row['PROFILEID'];
        $username=$row['USERNAME'];
        $smarty->assign("profileid",$profileid);//To pass the profileid of the user with the mailer.
        $smarty->assign("username",$username);//To pass the username of the user with the mailer.
        $msg=$smarty->fetch("finance_mailer.html");
        $email=$row['EMAIL'];
        send_email($email,$msg,$subject,$from);
	$sendmail=$row['SENDMAIL']+1;

        $sql2="UPDATE newjs.PROMOTIONAL_MAIL SET SENDMAIL='$sendmail' WHERE PROFILEID='$profileid'";
	//To make confirm that mail is sent to the user by increasing the sendmail counter by 1.
        mysql_query_decide($sql2) or logError("error",$sql2);
}

//To fetch the detail of the users who are registered day before yesterday.
$sql="SELECT PROFILEID,USERNAME,EMAIL FROM JPROFILE WHERE ENTRY_DT BETWEEN '$dbyesterday 00:00:00' AND '$dbyesterday 23:59:59' AND ACTIVATED<>'D'";
$res=mysql_query_decide($sql) or logError("error",$sql);

while($row=mysql_fetch_array($res))
{
	//To pass the profileid and username of the user with the mailer.
        $smarty->assign("profileid",$row['PROFILEID']);
        $smarty->assign("username",$row['USERNAME']);
        $msg=$smarty->fetch("finance_mailer.html");
        $email=$row["EMAIL"];
        send_email($email,$msg,$subject,$from);
	
        $sql_ins="INSERT INTO newjs.PROMOTIONAL_MAIL(PROFILEID,USERNAME,EMAIL,SENDMAIL,ENTRY_TIME) VALUES('$row[PROFILEID]','$row[USERNAME]','$row[EMAIL]',1,'$today')";
	//To populate the Promotional Table.
        mysql_query_decide($sql_ins) or logError("error",$sql_ins);
}

?>
