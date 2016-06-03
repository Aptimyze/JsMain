<?php
/***************************************************************************************************************************
*		FILENAME    : sendemail.php
*		INCLUDED    : connect.inc ,  emailvalidation.inc,newsprom_comfunc.inc. 
*               DESCRIPTION : This file sends email to those email-ids to which the email has not been sent yet and also
*                             updates the records accordingly.
****************************************************************************************************************************/        include("connect.inc");
	include("newsprom_comfunc.inc");
                                                                                                                             
	/*$EMAIL="shobha.solanki@gmail.com";
	$id=5;
	$uid=md5($id)."i".$id;
	$smarty->assign("uid",$uid);
	$msg = $smarty->fetch("newsprom_emailmsg.htm"); */

	$sql = "SELECT ID, EMAIL FROM jsadmin.MAILER_TEST WHERE EMAILSTATUS ='' AND EMAIL = 'shobha_k_s500@yahoo.com'";
	$res = mysql_query($sql) or sendlogerror("No such Record Exists in the table!!",$sql);
        while($row = mysql_fetch_array($res))
        {
		$id = $row["ID"];
		$EMAIL = $row["EMAIL"];
		$source = $row["MODE"];
		$uid=md5($id)."i".$id;
		$smarty->assign("uid",$uid);
		$smarty->assign("source",$source);
	        $msg = $smarty->fetch("newsprom_emailmsg.htm");

                send_email($EMAIL,$msg);
           	$sendsql = "UPDATE jsadmin.MAILER_TEST SET EMAILSTATUS='Y', SENDTIME=NOW()  WHERE EMAIL='$EMAIL'";
                $sendres = mysql_query($sendsql) or sendlogerror("Error encountered while updating the records!!",$sendsql);		
       }
        
                                                                                                                             
?>
