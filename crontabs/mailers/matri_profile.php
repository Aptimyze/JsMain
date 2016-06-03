<?php

/************************************************************************************************************************************
* File Name      : matri_profile.php

* Description    : Logic for Identifying Users for which mail will be send to them who has Registerd there profile 7 Days back and written less than 200 words in Registration Page infrotn of About me Column && Registerd 7 days back and People who are older than 7 days and edited their profile today and have written less than 200 characters and haven't got the mail.

* Created By     : Anurag Gautam
* Date           : 25th August 2008
**************************************************************************************************************************************/

  $_SERVER['DOCUMENT_ROOT']=JsConstants::$docRoot;
  include($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
  $db= connect_slave();
  $db2= connect_db();

  ini_set('max_execution_time','0');
  ini_set("memory_limit","-1");

  mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
  mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db2);
  
  $yest_dt=time()-24*60*60*7;               // Time of 7 Days Back
  $yest_dt= date("Y-m-d",$yest_dt);         // Date of 7 Days Back
  $st_dt= $yest_dt." 00:00:00";             // Starting Time  of 7 Days Back
  $end_dt= $yest_dt." 23:59:59";            // Ending Time of 7 Days Back
  $a=date("Y-m-d");
  $dt1=$a." 00:00:00";
  $dt2=$a." 23:59:59";
	
   $sql="SELECT PROFILEID,YOURINFO FROM newjs.JPROFILE WHERE ENTRY_DT BETWEEN '$st_dt' AND '$end_dt' && ACTIVATED !='D'";
   $res= mysql_query($sql,$db) or die(mysql_error1($db));

  if(mysql_num_rows($res))
  {
     $sql2="INSERT IGNORE INTO mailer.MATRI_MAILER (PROFILE_ID) VALUES";
     $i=0;
     while($row= mysql_fetch_array($res))
     {
          $char_len= strlen($row['YOURINFO']);
          if ($char_len<200)
	  {
	      if($i<1)
	      {
              	     if($values!='')
	       	     $values.=", ";
 	             $values.= "( '".$row['PROFILEID']."' )";
  	       	     $i++;
	      }
	   
	      else
	      {
		      $sql1=$sql2.$values;
                      mysql_query($sql1,$db2) or die(mysql_error1($db2));
		      $values='';$i=0;
   	    	      $values.="('".$row['PROFILEID']."')";	
	              $i++;
	      }
	   }
     }
  }
   
  if($values!='')
  {
        $sql1=$sql2.$values;
        mysql_query($sql1,$db2) or die(mysql_error1($db2));
  }	

 $sql="SELECT PROFILEID, YOURINFO FROM newjs.JPROFILE A LEFT JOIN mailer.MATRI_MAILER B ON A.PROFILEID = B.PROFILE_ID WHERE MOD_DT BETWEEN '$dt1' AND '$dt2' AND ENTRY_DT < '$st_dt' && A.ACTIVATED !='D' && B.PROFILE_ID IS NULL";

  $res= mysql_query($sql,$db) or die(mysql_error1($db));
  if(mysql_num_rows($res))
  {
     $sql2="INSERT IGNORE INTO mailer.MATRI_MAILER (PROFILE_ID) VALUES";
     $i=0;
     while($row= mysql_fetch_array($res))
     {
     	  $char_len= strlen($row['YOURINFO']);
          if ($char_len<200)
	  {
	      if($i<1)
	      {
              	     if($values!='')
	       	     $values.=", ";
                     $values.= "( '".$row['PROFILEID']."' )";
  	       	     $i++;
	      }
	      else
	      {
		      $sql1=$sql2.$values;
		      mysql_query($sql1,$db2) or die(mysql_error1($db2));
		      $values='';$i=0;
  	    	      $values.="('".$row['PROFILEID']."')";	
	              $i++;
	      }
	   }
     }
  }
   
  if($values!='')
  {
        $sql1=$sql2.$values;
        mysql_query($sql1,$db2) or die(mysql_error1($db2));
  }
  
  passthru(JsConstants::$php5path.' -q '.JsConstants::$docRoot.'/mailer/matri_mailer.php');

  mail("anurag.gautam@jeevansathi.com","Script matri_profile.php ran successfully", date("Y-m-d"));

  // For Executing the Script "matri_mailer.php" in Live Server

  function mysql_error1($db)
{
        mail("anurag.gautam@jeevansathi.com","Error in matri mailer matri_profile.php ",mysql_error($db));
}



?>
