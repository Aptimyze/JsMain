<?php

/************************************************************************************************************************************
* File Name      : discount_mailer_profile.php
* Description    : Logic for Identifying Users who have been in the system for more than 150 days and have less than 350 points score.
* Created By     : Anurag Gautam
* Date           : 30th August 2008
**************************************************************************************************************************************/

  $_SERVER['DOCUMENT_ROOT']=JsConstants::$docRoot;
  include($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
  $db= connect_slave();
  $db2= connect_db();

  ini_set('max_execution_time','0');
  ini_set("memory_limit","-1");
 
  $yest_dt=time()-24*60*60*180;               // Time of 180 Days Back
  $yest_dt= date("Y-m-d",$yest_dt);         // Date of 180 Days Back
  $st_dt= $yest_dt." 00:00:00";             // Starting Time  of 180 Days Back
  $end_dt= $yest_dt." 23:59:59";            // Ending Time of 180 Days Back

  mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
  mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db2);

  // New Query as per New Requirments
  $sql="SELECT A.PROFILEID FROM newjs.JPROFILE A LEFT JOIN billing.PAYMENT_DETAIL B ON A.PROFILEID = B.PROFILEID WHERE A.ENTRY_DT <='$end_dt' AND A.PROMO_MAILS = 'S' AND A.ACTIVATED = 'Y' AND (B.PROFILEID IS NULL OR B.STATUS != 'DONE')";

//  $sql="SELECT J.PROFILEID FROM newjs.JPROFILE AS J, incentive.MAIN_ADMIN_POOL AS I WHERE J.PROFILEID = I.PROFILEID AND J.ENTRY_DT > '2008-01-01 00:00:00' AND J.LAST_LOGIN_DT < '$end_dt' AND J.SUBSCRIPTION = '' AND I.SCORE <350 AND J.ACTIVATED = 'Y' AND J.PROMO_MAILS = 'S'";

// $sql="SELECT J.PROFILEID FROM newjs.JPROFILE AS J, incentive.MAIN_ADMIN_POOL AS I WHERE J.PROFILEID = I.PROFILEID AND J.LAST_LOGIN_DT < '$end_dt' AND J.SUBSCRIPTION = '' AND I.SCORE < 350 AND J.ACTIVATED ='Y' AND J.PROMO_MAILS ='S'";

  $res= mysql_query($sql,$db) or die(mysql_error1($db,$sql));
   
  if(mysql_num_rows($res))
  {
     while($row=mysql_fetch_array($res))
     {
       $id = $row['PROFILEID'];
       $sql="INSERT IGNORE INTO mailer.DISCOUNT_MAILER (PROFILE_ID) VALUES('$id')";
       mysql_query($sql,$db2) or die(mysql_error1($db2,$sql));
     }
  }


    mail("Anurag.Gautam@jeevansathi.com","Script discount_mailer_profile.php Ran Successfully", date("Y-m-d"));

  // For Executing the Script "discount_mailer.php" in Live Server

 function mysql_error1($db,$query="")
 {
      mail("Anurag.Gautam@jeevansathi.com","Error in Discount mailer discount_mailer_profile.php $query",mysql_error($db));
 }

?>
