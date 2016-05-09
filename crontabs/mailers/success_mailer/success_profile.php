<?php
/************************************************************************************************************************************
* File Name      : success_profile.php
* Description    : It will identify the Profiles who haven't uploaded there photos in success story and who have deleted their profile saying
* they found their match on "Jeevansathi.com".
* Created By     : Anurag Gautam
* Date           : 03rd September 2008
**************************************************************************************************************************************/

  $_SERVER['DOCUMENT_ROOT']=JsConstants::$docRoot;
  include($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
  //include_once("../profile/connect.inc");
  
  $db= connect_slave();
  $db2=connect_db();
  
  ini_set('max_execution_time','0');
  ini_set("memory_limit","-1");

  mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
  mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db2);

  // For Selecting the users who haven't uploaded photo for their success story.

//  $sql="SELECT DISTINCT USERNAME FROM newjs.`SUCCESS_STORIES` WHERE `USERNAME` !='' AND `PHOTO` = '' AND UPLOADED='A'";
  $sql="SELECT DISTINCT A.USERNAME FROM newjs.SUCCESS_STORIES AS A ,newjs.JPROFILE AS B WHERE A.USERNAME !='' AND A.PHOTO = '' AND B.SERVICE_MESSAGES ='S' AND A.UPLOADEDIN('A','Y') AND A.USERNAME=B.USERNAME ";
  $res= mysql_query($sql,$db) or die(mysql_error1($db,$sql));
 
  if(mysql_num_rows($res))
  {
     while($row=mysql_fetch_array($res))
     {
       $username = $row['USERNAME'];
       $sql="INSERT IGNORE INTO mailer.SS_MAILER (USERNAME,PROFILE_TYPE) VALUES('$username','1')";
       $res1= mysql_query($sql,$db2) or die(mysql_error1($db2,$sql));
     }
  }

  // For Selecting the User who have deleted their profile saying they found their match on jeevansathi.com.

   $sql="SELECT A.USERNAME FROM newjs.PROFILE_DEL_REASON A JOIN newjs.JPROFILE B ON A.USERNAME = B.USERNAME LEFT JOIN newjs.SUCCESS_STORIES C ON A.USERNAME = C.USERNAME WHERE A.DEL_REASON = 'I found my match on Jeevansathi.com' AND B.SERVICE_MESSAGES = 'S' AND C.USERNAME IS NULL";
   /*$sql="SELECT A.USERNAME FROM newjs.PROFILE_DEL_REASON A JOIN newjs.SUCCESS_STORIES B ON A.USERNAME = B.USERNAME WHERE A.DEL_REASON = 'I found my match on Jeevansathi.com' AND B.UPLOADED = 'A'";*/
/* $sql="SELECT A.USERNAME FROM newjs.PROFILE_DEL_REASON A LEFT JOIN newjs.SUCCESS_STORIES B ON A.USERNAME = B.USERNAME WHERE A.DEL_REASON ='I found my match on Jeevansathi.com' AND B.USERNAME IS NULL";*/
  $res= mysql_query($sql,$db) or die(mysql_error1($db,$sql));
 
  if(mysql_num_rows($res))
  {
     while($row=mysql_fetch_array($res))
     {
       $username = $row['USERNAME'];
       $sql="INSERT IGNORE INTO mailer.SS_MAILER (USERNAME,PROFILE_TYPE) VALUES('$username','2')";
       $res1= mysql_query($sql,$db2) or die(mysql_error1($db2,$sql));
     }
  }

?>
